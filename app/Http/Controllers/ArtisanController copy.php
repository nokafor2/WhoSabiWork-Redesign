<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtisan;
use App\Http\Traits\GlobalFunctions;
use App\Models\Address;
use App\Models\Artisan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ArtisanController extends Controller
{
    use GlobalFunctions;
    
    public function __construct() {
        // This controller functions are protected by the auth middleware
        // Authentication by must be needed to access them
        // $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Comparison of eager-loading to lazy-loading
        // Enable the database to log queries made
        // DB::enableQueryLog();

        // // $users = User::all();
        // $users = User::with('comments')->get();
        // foreach ($users as $user) {
        //     foreach ($user->comments as $comment) {
        //         echo $comment->body."<br/>";
        //     }
        // }

        // dd(DB::getQueryLog());

        // return view(
        //     'home.mechanics',
        //     // a new property comments_count will be created 
        //     ['addresses' => Address::withCount('comments')->get()]
        // );

        // return view('customers.home',
        //     // a new property comments_count will be created by the withCount() method
        //     [
        //         // 'users' => User::withCount('comments')->with('user')->get(),
        //         'users' => User::withCount('comments')->get(),
        //         'mostCommented' => User::mostCommented()->take(5)->get(),
        //         'mostActiveLastMonth' => User::withMostCommentLastMonth()->take(5)->get(),
        //     ]
        // );

        // Setting up cache
        // The remeber method take 3 arguments, the key, time to store the cache and a call back
        // These have been transfered to the views composer
        // $mostCommented = Cache::tags(['user'])->remember('user-most-commented', now()->addMinutes(10), function() {
        //     return User::mostCommented()->take(5)->get();
        // });

        // $mostActiveLastMonth = Cache::remember('user-most-active-last-month', now()->addMinutes(10), function() {
        //     return User::withMostCommentLastMonth()->take(5)->get();
        // });

        // $users = Cache::remember('users', now()->addMinutes(10), function() {
        //     return User::withCount('comments')->with('tags')->get();
        //     // using queryscope to modify to: 
        //     // return User::latestWithRelations()->get();
        // });

        // return view('customers.home',
        //     // a new property comments_count will be created by the withCount() method
        //     [
        //         // 'users' => User::withCount('comments')->with('user')->get(),
        //         // These will be provided by the ViewsComposer
        //         'users' => $users,
        //         'mostCommented' => $mostCommented,
        //         'mostActiveLastMonth' => $mostActiveLastMonth
        //     ]
        // );
        
        $artisans = $this->getUserCategoryDetails('artisan');

        $artisanObj = new Artisan();
        $artisanTypes = $this->getTableColumnsWithSort($artisanObj->table, Artisan::$columnsToExclude);

        return inertia(
            'Artisan/Index', 
            [ 
                'artisans' => $artisans,
                'artisanTypes' => $artisanTypes
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Set an authorization to create an address with a policy
        // $this->authorize('addresses.create');
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArtisan $request)
    {
        // // Validating the data
        // $request->validate([
        //     'address_line_1' => 'bail|required',
        //     'town' => 'required',
        //     'state' => 'required'
        // ]);
        
        // $address = new Address();
        // $address->address_line_1 = $request->input('address_line_1');
        // $address->address_line_2 = $request->input('address_line_2');
        // $address->address_line_3 = $request->input('address_line_3');
        // $address->town = $request->input('town');
        // $address->state = $request->input('state');
        // $address->save();
        

        // Validation replaced with custom StoreArtisan validation
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $address = new Address();
        // Use mass assignement for the validated inputs
        $address = Address::make($validated);

        // $address->user_id = count(Address::all()) + 1;
        // $address->address_line_1 = $validated['address_line_1'];
        // $address->address_line_2 = $request->input('address_line_2');
        // $address->address_line_3 = $request->input('address_line_3');
        // $address->town = $validated['town'];
        // $address->state = $validated['state'];
        
        $address->save();

        // Save a message to the session
        $request->session()->flash('status', 'The address was created!');

        return redirect()->route('artisans.show', ['artisan' => $address->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort_if(!isset($this->posts[$id]), 404);
        // return view('home.artisans', 
        //     ["address" => Address::with('comments')->findOrFail($id)]
        // );

        // One method of sorting the comments when they are fetched
        // return view('home.artisans', 
        //     ["user" => User::with(['address', 'comments' => function($query) {
        //         return $query->newest();
        //     }])->findOrFail($id)]
        // );

        // Setting up cache
        // A dynamic catch with the id is set up, so that it will be easy to remove an update a cache when there is changes
        // Set tag for the cache
        $userCache = Cache::tags(['user'])->remember("user-{$id}", 60, function() use($id) {
            return User::with(['address', 'comments.user', 'tags'])
                ->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey = "user-{$id}-counter";
        $usersKey = "user-{$id}-users";

        $users = Cache::tags(['user'])->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        // // Check if the user was on the list and if time is still valid
        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        // Check if the 
        if (!array_key_exists($sessionId, $users) || 
        $now->diffInMinutes($users[$sessionId]) >= 1) {
            $difference++;
        }

        // Store the user in the cache if it doesn't exist
        $usersUpdate[$sessionId] = $now;
        Cache::tags(['user'])->forever($usersKey, $usersUpdate);

        if (!Cache::tags(['user'])->has($counterKey)) {
            Cache::tags(['user'])->forever($counterKey, 1);
        } else {
            Cache::tags(['user'])->increment($counterKey, $difference);
        }
        
        // Set a counter to keep track of users who have viewed the user
        $counter = Cache::tags(['user'])->get($counterKey);

        return view('home.artisans', 
            [
                "user" => $userCache,
                "counter" => $counter,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check if the $id exists
        $address = Address::findOrFail($id);
        
        // Using gate to set authorization
        // Check if a user can perform a particular action using the gate
        // if (Gate::denies('update-address', $address)) {
        //     abort(403, "You can't edit the address");
        // }
        // Using the authorize method to check for authorization
        // $this->authorize('update-address', $address);
        
        // Using policies to set authorization
        // $this->authorize('addresses.update', $address);
        // Another way of using policy
        $this->authorize('update', $address);

        return view('customers.edit', ['artisan' => Address::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreArtisan $request, $id)
    {
        // Check if the $id exists
        $address = Address::findOrFail($id);

        // Using gate to set authorization
        // Check if a user can perform a particular action using the gate
        // if (Gate::denies('update-address', $address)) {
        //     abort(403, "You can't edit the address");
        // }
        // // another method that can be used is:
        // // Gate::allows('update-address', $address);
        // $this->authorize('update-address', $address);

        // Using policies to set authorization
        // $this->authorize('addresses.update', $address);
        // Another way of using policy
        $this->authorize('update', $address);

        // Get the values of the validated data
        $validated = $request->validated();
        $address->fill($validated);
        // For data that is not validated
        // $address->address_line_2 = $request->input('address_line_2');
        // $address->address_line_3 = $request->input('address_line_3');
        $address->save();

        // Save a message to the session
        $request->session()->flash('status', 'The address was updated!');

        return redirect()->route('artisans.show', ['artisan' => $address->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        
        // Using gate to set authorization
        // Check if a user can perform a particular action using the gate
        // if (Gate::denies('delete-address', $address)) {
        //     abort(403, "You can't delete the address");
        // }
        // $this->authorize('delete-address', $address);

        // Using policies to set authorization
        // $this->authorize('addresses.delete', $address);
        // Another way of using policy
        $this->authorize('delete', $address);

        $address->delete();

        session()->flash('status', 'Address was deleted!');

        return redirect()->route('mechanics.index');
    }
}
