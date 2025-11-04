<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Traits\GlobalFunctions;
use App\Models\Artisan;
use App\Models\BusBrand;
use App\Models\CarBrand;
use App\Models\Product;
use App\Models\SparePart;
use App\Models\TechnicalService;
use App\Models\TruckBrand;
use App\Models\User;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    use GlobalFunctions;
    
    public function __construct()
    {
        // Set authentication for all the methods, hence a user must be logged in to implement any
        // $this->middleware('auth');

        // Authenticate some actions with middleware
        // This can also be set in the web route file
        $this->middleware('auth')->except('index', 'create');

        // Authorize certain actions
        // $this->authorizeResource(User::class, 'user');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia('SignIn/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $artisan = new Artisan();
        $product = new Product();
        $technicalService = new TechnicalService();
        $sparePart = new SparePart();
        $vehicleCategory = new VehicleCategory();
        $carBrand = new CarBrand();
        $busBrand = new BusBrand();
        $truckBrand = new TruckBrand();
        $allArtisans = $this->getTableColumnsWithSort($artisan->table, Artisan::$columnsToExclude);
        $allProducts = $this->getTableColumnsWithSort($product->table, Product::$columnsToExclude);
        $allTechnicalServices = $this->getTableColumnsWithSort($technicalService->table, TechnicalService::$columnsToExclude);
        $allSpareParts = $this->getTableColumnsWithSort($sparePart->table, SparePart::$columnsToExclude);
        $allVehicleCategories = $this->getTableColumnsWithSort($vehicleCategory->table, VehicleCategory::$columnsToExclude);
        $allCarBrands = $this->getTableColumnsWithSort($carBrand->table, CarBrand::$columnsToExclude);
        $allBusBrands = $this->getTableColumnsWithSort($busBrand->table, BusBrand::$columnsToExclude);
        $allTruckBrands = $this->getTableColumnsWithSort($truckBrand->table, TruckBrand::$columnsToExclude);

        return inertia('SignUp/Create', [
            'allArtisans' => $allArtisans,
            'allProducts' => $allProducts,
            'allTechnicalServices' => $allTechnicalServices,
            'allSpareParts' => $allSpareParts,
            'allVehicleCategories' => $allVehicleCategories,
            'allCarBrands' => $allCarBrands,
            'allBusBrands' => $allBusBrands,
            'allTruckBrands' => $allTruckBrands
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response'
     */
    public function store(Request $request)
    {
        $artisan = new Artisan();
        $allArtisans = $this->getTableColumnsWithSort($artisan->table, Artisan::$columnsToExclude);
        // dd($request->all());

        // Validate all the variables before saving
        $validatedVals = $validatedUserVals = [...$request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'],
            'password_confirmation' => ['required'],
            'phone_number' => ['required', 'string', 'min:11', 'max:15', 'unique:users']
        ])];
        
        if (isset($request->business_name)) {
            // This a business account
            $accountType = 'business';
            $validatedAddressVals = [...$request->validate([
                'address_line_1' => ['required', 'string', 'max:250'],
                'address_line_2' => ['sometimes', 'string', 'max:250'],
                'address_line_3' => ['sometimes', 'string', 'max:250'],
                'state' => ['required', 'string', 'max:100'],
                'town' => ['required', 'string', 'max:100'],
            ])];
            $validatedVals = [...$validatedVals, ...$validatedAddressVals];
            
            $validatedBussCatVals = [...$request->validate([
                'business_name' => ['required', 'string', 'max:255'],
            ])];
            $validatedVals = [...$validatedVals, ...$validatedBussCatVals];
            // dd($validatedVals);
        } else {
            // This is a public account
            $accountType = 'regular';
        }

        // After validation is complete, save the details
        $user = User::create([
            ...$validatedUserVals,
            'password' => Hash::make($request->password),
            // 'password' => $request->password,
            'remember_token' => Str::random(10),
            'account_status' => 'active',
            'account_type' => $accountType,
            'is_admin' => false,
        ]);

        // Check if account is for business
        if (isset($request->business_name)) {
            // validate address variables before saving
            $user->address()->create([
                ...$validatedAddressVals
            ]);

            // Validate for business categories table
            $user->businessCategory()->create([
                ...$validatedBussCatVals,
                'artisan' => in_array('artisan', $request->businessCategories) ? true : false,
                'seller' => in_array('mobileMarket', $request->businessCategories) ? true : false,
                'technician' => (isset($request->selectedArtisans) && in_array('mechanic', $request->selectedArtisans)) ? true : false,
                'spare_part_seller' => (isset($request->selectedMobileSellers) && in_array('spare_parts', $request->selectedMobileSellers)) ? true : false,
            ]);

            if (in_array('artisan', $request->businessCategories) && (isset($request->selectedArtisans) && (count($request->selectedArtisans) > 0))) {
                // $validatedVals = [...$request->validate([
                //     'selectedArtisans' => ['required', 'array', 'min:1'],
                //     'selectedArtisans.*' => [Rule::in(array_keys($allArtisans->toArray()))],
                // ])];

                $selectedArtisans = array();
                foreach ($request->selectedArtisans as $type) {
                    // $selectedArtisans = [...$selectedArtisans, ...[$type => TRUE]];
                    $selectedArtisans[$type] = TRUE;
                }
                $user->artisan()->create($selectedArtisans);
            }

            if ((isset($request->selectedArtisans) && in_array('mechanic', $request->selectedArtisans))) {
                // Save selected technicians
                if (!empty($request->selectedTechnicalServices)) {
                    $selectedTechServ = array();
                    foreach ($request->selectedTechnicalServices as $type) {
                        $selectedTechServ[$type] = TRUE;
                    }
                    $user->technicalService()->create($selectedTechServ);
                }

                // Save selected vehicle categories
                if (!empty($request->selectedVehicleCategories)) {
                    $selectedVehCat = array();
                    $selectedVehCat['business_category'] = 'technical_service';
                    foreach ($request->selectedVehicleCategories as $type) {
                        $selectedVehCat[$type] = TRUE;
                    }
                    $user->vehicleCategory()->create($selectedVehCat);
                }

                // Save selected car brands
                if (!empty($request->selectedCarBrands)) {
                    $selectedCarBrands = array();
                    $selectedCarBrands['business_category'] = 'technical_service';
                    foreach ($request->selectedCarBrands as $type) {
                        $selectedCarBrands[$type] = TRUE;
                    }
                    $user->carBrand()->create($selectedCarBrands);
                }

                // Save selected bus brands
                if (!empty($request->selectedBusBrands)) {
                    $selectedBusBrands = array();
                    $selectedBusBrands['business_category'] = 'technical_service';
                    foreach ($request->selectedBusBrands as $type) {
                        $selectedBusBrands[$type] = TRUE;
                    }
                    $user->busBrand()->create($selectedBusBrands);
                }

                // Save selected truck brands
                if (!empty($request->selectedTruckBrands)) {
                    $selectedTruckBrands = array();
                    $selectedTruckBrands['business_category'] = 'technical_service';
                    foreach ($request->selectedTruckBrands as $type) {
                        $selectedTruckBrands[$type] = TRUE;
                    }
                    $user->truckBrand()->create($selectedTruckBrands);
                }
            }

            // validate for mobile maketers
            if (in_array('mobileMarket', $request->businessCategories) && (isset($request->selectedMobileSellers) && (count($request->selectedMobileSellers) > 0))) {
                $selectedMarketers = array();
                foreach ($request->selectedMobileSellers as $type) {
                    $selectedMarketers[$type] = TRUE;
                }
                $user->product()->create($selectedMarketers);
            }

            if ((isset($request->selectedMobileSellers) && in_array('spare_parts', $request->selectedMobileSellers))) {
                // Save selected spare part sellers
                if (!empty($request->selectedSpareParts)) {
                    $selectedSpareParts = array();
                    foreach ($request->selectedSpareParts as $type) {
                        $selectedSpareParts[$type] = TRUE;
                    }
                    $user->sparePart()->create($selectedSpareParts);
                }

                // Save selected vehicle categories
                if (!empty($request->selectedVehicleCategoriesSS)) {
                    $selectedVehCat = array();
                    $selectedVehCat['business_category'] = 'spare_part';
                    foreach ($request->selectedVehicleCategoriesSS as $type) {
                        $selectedVehCat[$type] = TRUE;
                    }
                    $user->vehicleCategory()->create($selectedVehCat);
                }

                // Save selected car brands
                if (!empty($request->selectedCarBrandsSS)) {
                    $selectedCarBrands = array();
                    $selectedCarBrands['business_category'] = 'spare_part';
                    foreach ($request->selectedCarBrandsSS as $type) {
                        $selectedCarBrands[$type] = TRUE;
                    }
                    $user->carBrand()->create($selectedCarBrands);
                }

                // Save selected bus brands
                if (!empty($request->selectedBusBrandsSS)) {
                    $selectedBusBrands = array();
                    $selectedBusBrands['business_category'] = 'spare_part';
                    foreach ($request->selectedBusBrandsSS as $type) {
                        $selectedBusBrands[$type] = TRUE;
                    }
                    $user->busBrand()->create($selectedBusBrands);
                }

                // Save selected truck brands
                if (!empty($request->selectedTruckBrandsSS)) {
                    $selectedTruckBrands = array();
                    $selectedTruckBrands['business_category'] = 'spare_part';
                    foreach ($request->selectedTruckBrandsSS as $type) {
                        $selectedTruckBrands[$type] = TRUE;
                    }
                    $user->truckBrand()->create($selectedTruckBrands);
                }
            }
        }

        // After saving the user, authenticate them
        Auth::login($user);

        return redirect()->route('users.show', $user->id)->with('success', 'Account was successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $artisan = new Artisan();
        $product = new Product();
        $technicalService = new TechnicalService();
        $sparePart = new SparePart();
        $vehicleCategory = new VehicleCategory();
        $carBrand = new CarBrand();
        $busBrand = new BusBrand();
        $truckBrand = new TruckBrand();
        $allArtisans = $this->getTableColumnsWithSort($artisan->table, Artisan::$columnsToExclude);
        $allProducts = $this->getTableColumnsWithSort($product->table, Product::$columnsToExclude);
        $allTechnicalServices = $this->getTableColumnsWithSort($technicalService->table, TechnicalService::$columnsToExclude);
        $allSpareParts = $this->getTableColumnsWithSort($sparePart->table, SparePart::$columnsToExclude);
        $allVehicleCategories = $this->getTableColumnsWithSort($vehicleCategory->table, VehicleCategory::$columnsToExclude);
        $allCarBrands = $this->getTableColumnsWithSort($carBrand->table, CarBrand::$columnsToExclude);
        $allBusBrands = $this->getTableColumnsWithSort($busBrand->table, BusBrand::$columnsToExclude);
        $allTruckBrands = $this->getTableColumnsWithSort($truckBrand->table, TruckBrand::$columnsToExclude);
        $galleryPhotos = $this->getPhotographsCommentsReplies($user);
        $customerCommentsAndReplies = $this->getCommentsAndReplies($user, 'customer');
        $entrepreneurCommentsAndReplies = $this->getCommentsAndReplies($user, 'entrepreneur');
        
        // Determine if it's a regular user or business user
        $userType = $user->account_type;
        
        if ($userType === 'regular') {
            return Inertia('User/UserProfile', [
                'user' => $user,
                'neutralAppointmentsSchdlr' => $this->getAppointments('neutral', null, $user->id),
                'acceptedAppointmentsSchdlr' => $this->getAppointments('accepted', null, $user->id),
                'declinedAppointmentsSchdlr' => $this->getAppointments('declined', null, $user->id),
                'cancelledAppointmentsSchdlr' => $this->getAppointments('cancelled', null, $user->id),
            ]);
        } elseif ($userType === 'business') {
            $userDetails = $this->getUserDetails($user->id);
            return Inertia('User/UserProfile', [
                'user' => $userDetails['userDetails'], 
                'userCategories' => $userDetails['userCategories'],
                'techVehCategories' => $userDetails['techVehCategories'],
                'sPartVehCategories' => $userDetails['sPartVehCategories'],
                'vehicleBrands' => $userDetails['vehicleBrands'],
                'allArtisans' => $allArtisans,
                'allProducts' => $allProducts,
                'allTechnicalServices' => $allTechnicalServices,
                'allSpareParts' => $allSpareParts,
                'allVehicleCategories' => $allVehicleCategories,
                'allCarBrands' => $allCarBrands,
                'allBusBrands' => $allBusBrands,
                'allTruckBrands' => $allTruckBrands,
                'schedules' => $this->getSchedule($user->id, '', 'many'),
                'neutralAppointments' => $this->getAppointments('neutral', $user->id, null),
                'acceptedAppointments' => $this->getAppointments('accepted', $user->id, null),
                'declinedAppointments' => $this->getAppointments('declined', $user->id, null),
                'cancelledAppointments' => $this->getAppointments('cancelled', $user->id, null),
                'neutralAppointmentsSchdlr' => $this->getAppointments('neutral', null, $user->id),
                'acceptedAppointmentsSchdlr' => $this->getAppointments('accepted', null, $user->id),
                'declinedAppointmentsSchdlr' => $this->getAppointments('declined', null, $user->id),
                'cancelledAppointmentsSchdlr' => $this->getAppointments('cancelled', null, $user->id),
                'galleryPhotos' => $galleryPhotos,
                'customerCommentsAndReplies' => $customerCommentsAndReplies,
                'entrepreneurCommentsAndReplies' => $entrepreneurCommentsAndReplies,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // $updateVal = $request->updateVal;
        dd($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $validated = $request->safe()->only(['first_name']);
        // dd($request->all());
        $updateVal = $request->updateVal;
        $updateField = $request->updateField;
        if ($updateField === 'password') {
            $updateVal2 = $request->updateVal2;
            // Perform validation
            $user->{$updateField} = Hash::make($updateVal);
        } else {
            $user->{$updateField} = $updateVal;
        }
        $result = $user->save();
        
        // return Inertia('User/UserProfile', ['user' => $user, 'updateSatus' => $result]);
        return redirect()->route('users.show', $user->id)->with('success', $result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
