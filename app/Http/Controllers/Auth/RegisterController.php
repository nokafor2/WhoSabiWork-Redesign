<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\GlobalFunctions;
use App\Models\Address;
use App\Models\Artisan;
use App\Models\BusBrand;
use App\Models\BusinessCategory;
use App\Models\CarBrand;
use App\Models\Product;
use App\Models\SparePart;
use App\Models\TechnicalService;
use App\Models\TruckBrand;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\VehicleCategory;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, GlobalFunctions;
    // use GlobalFunctions;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index() {
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

        return inertia('SignUp/Index', [
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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // Check if it a user registration or business registration
        if (isset($data['user_register']) && $data['user_register'] === 'true') {
            return Validator::make($data, [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
                'phone_number' => ['required', 'string', 'min:11', 'max:15', 'unique:users']
            ]);
        } else {
            return Validator::make($data, [
                /* 'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                // 'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
                'phone_number' => ['required', 'string', 'min:11', 'max:15', 'unique:users'],
                'address_line_1' => ['required', 'string', 'max:255'],
                'address_line_2' => ['sometimes', 'string', 'nullable', 'max:255'],
                'address_line_3' => ['sometimes', 'string', 'nullable', 'max:255'],
                'town' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:255'],
                'business_name' => ['required', 'string', 'max:255'],
                'business_categories' => ['required', 'array', 'min:1'],
                'business_categories.*' => ['required', 'string', 'distinct'],
                
                'artisans' => ['required_without_all:products,technical_services,spare_parts', 'array', 'min:1'],
                'artisans.*' => ['required_without_all:products,technical_services,spare_parts', 'string', 'distinct'],
                'products' => ['required_without_all:artisans,technical_services,spare_parts', 'array', 'min:1'],
                'products.*' => ['required_without_all:artisans,technical_services,spare_parts', 'required', 'string', 'distinct'],
                'technical_services' => ['required_without_all:products,artisans,spare_parts', 'array', 'min:1'],
                'technical_services.*' => ['required_without_all:products,artisans,spare_parts', 'string', 'distinct'],
                'spare_parts' => ['required_without_all:products,technical_services,artisans', 'array', 'min:1'],
                'spare_parts.*' => ['required_without_all:products,technical_services,artisans', 'string', 'distinct'],
                
                'vehicle_category' => ['required_with:technical_services', 'string', 'max:255'], */
                // 'car_brands' => ['sometimes', 'required_without_all:bus_brands,truck_brands', 'string', 'distinct'],
                // 'car_brands.*' => ['sometimes', 'required_without_all:car_brands,truck_brands', 'string', 'distinct'],
                // 'bus_brands' => ['sometimes', 'required_without_all:car_brands,truck_brands', 'string', 'distinct'],
                // 'bus_brands.*' => ['sometimes', 'required_without_all:car_brands,truck_brands', 'string', 'distinct'],
                // 'truck_brands' => ['sometimes', 'required_without_all:car_brands,bus_brands', 'string', 'distinct'],
                // 'truck_brands.*' => ['sometimes', 'required_without_all:car_brands,bus_brands', 'string', 'distinct'],

                // 'vehicle_category_spare' => ['sometimes', 'required_with:spare_parts', 'string'],
                // 'car_brands_spare' => ['sometimes', 'required_with:vehicle_category_spare', 'array', 'min:1'],
                // 'car_brands_spare.*' => ['sometimes', 'required_with:vehicle_category_spare', 'string', 'distinct'],
                // 'bus_brands_spare' => ['required', 'array', 'min:1'],
                // 'bus_brands_spare.*' => ['required', 'string', 'distinct'],
                // 'truck_brands_spare' => ['required', 'array', 'min:1'],
                // 'truck_brands_spare.*' => ['required', 'string', 'distinct'],
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd($data);

        if (isset($data['user_register']) && $data['user_register'] === 'true') {
            $user = User::create([
                // 'name' => $data['name'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_number' => $data['phone_number'],
                'account_type' => 'regular',
                'account_status' => 'active'
            ]);

            return $user;
        } elseif (isset($data['business_register']) && $data['business_register'] === 'true') {
            $user = User::create([
                // 'name' => $data['name'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'gender' => $data['gender'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone_number' => $data['phone_number'],
                'account_type' => 'business',
                'account_status' => 'active'
            ]);

            // Save address record
            $address = Address::create([
                'user_id' => $user->id,
                'address_line_1' => $data['address_line_1'],
                'address_line_2' => $data['address_line_2'],
                'address_line_3' => $data['address_line_3'],
                'town' => $data['town'],
                'state' => $data['state']
            ]);

            $artisan = false;
            $seller = false;
            $mechanic = false;
            $spare_part_seller = false;
            foreach ($data['business_categories'] as $category) {
                if ($category === 'artisan') {
                    $artisan = true;
                } elseif ($category === 'seller') {
                    $seller = true;
                } elseif ($category === 'mechanic') {
                    $mechanic = true;
                } elseif ($category === 'spare_part_seller') {
                    $spare_part_seller = true;
                }
                // ${$category} = true;
            }

            // Save business category details
            $business_category = BusinessCategory::create([
                'user_id' => $user->id,
                'business_name' => $data['business_name'],
                'artisan' => $artisan,
                'seller' => $seller,
                'mechanic' => $mechanic,
                'spare_part_seller' => $spare_part_seller,
                'town' => $data['town'],
                'state' => $data['state']
            ]);

            // Check if artisan is selected and save
            if ($artisan) {
                $artisan = new Artisan();
                $artisan->user_id = $user->id;
                foreach ($data['artisans'] as $type) {
                    $artisan->{$type} = TRUE;
                }
                $savedArtisan = $artisan->save();
            }

            // Check if seller is selected and save
            if ($seller) {
                $product = new Product();
                $product->user_id = $user->id;
                foreach ($data['products'] as $type) {
                    $product->{$type} = TRUE;
                }
                $savedProduct = $product->save();
            }

            // Check if technical_service is selected and save
            if ($mechanic) {
                $technicalService = new TechnicalService();
                $technicalService->user_id = $user->id;
                foreach ($data['technical_services'] as $type) {
                    $technicalService->{$type} = TRUE;
                }
                $savedTechnicalService = $technicalService->save();

                // Save the vehicle type
                $car = false;
                $bus = false;
                $truck = false;
                if ($data['vehicle_category'] === 'car') {
                    $car = true;
                } elseif ($data['vehicle_category'] === 'bus') {
                    $bus = true;
                } elseif ($data['vehicle_category'] === 'truck') {
                    $truck = true;
                }

                // $vehicleCategory = VehicleCategory::create([
                //     'user_id' => $user->id,
                //     'business_category' => "technical_service",
                //     'car' => $car,
                //     'bus' => $bus,
                //     'truck' => $truck,
                // ]);

                $vehicleCategory = new VehicleCategory();
                $vehicleCategory->user_id = $user->id;
                $vehicleCategory->business_category = "technical_service";
                $vehicleCategory->car = $car;
                $vehicleCategory->bus = $bus;
                $vehicleCategory->truck = $truck;
                $vehicleCategory->save();

                // Save cars or buses or trucks
                if ($data['vehicle_category'] === 'car') {
                    $carBrand = new CarBrand();
                    $carBrand->user_id = $user->id;
                    $carBrand->business_category = "technical_service";
                    foreach ($data['car_brands'] as $type) {
                        $carBrand->{$type} = TRUE;
                    }
                    $savedCarBrand = $carBrand->save();
                } elseif ($data['vehicle_category'] === 'bus') {
                    $busBrand = new BusBrand();
                    $busBrand->user_id = $user->id;
                    $busBrand->business_category = "technical_service";
                    foreach ($data['bus_brands'] as $type) {
                        $busBrand->{$type} = TRUE;
                    }
                    $savedBusBrand = $busBrand->save();
                } elseif ($data['vehicle_category'] === 'truck') {
                    $truckBrand = new TruckBrand();
                    $truckBrand->user_id = $user->id;
                    $truckBrand->business_category = "technical_service";
                    foreach ($data['truck_brands'] as $type) {
                        $truckBrand->{$type} = TRUE;
                    }
                    $savedTruckBrand = $truckBrand->save();
                }
            }

            // Check if spare part seller is selected and save
            if ($spare_part_seller) {
                $sparePart = new SparePart();
                $sparePart->user_id = $user->id;
                foreach ($data['spare_parts'] as $type) {
                    $sparePart->{$type} = TRUE;
                }
                $savedSparePart = $sparePart->save();

                // Save the vehicle type
                $car = false;
                $bus = false;
                $truck = false;
                if ($data['vehicle_category_spare'] === 'car') {
                    $car = true;
                } elseif ($data['vehicle_category_spare'] === 'bus') {
                    $bus = true;
                } elseif ($data['vehicle_category_spare'] === 'truck') {
                    $truck = true;
                }

                // $$vehicleCategorySpare = VehicleCategory::create([
                //     'user_id' => $user->id,
                //     'car' => $car,
                //     'bus' => $bus,
                //     'truck' => $truck,
                // ]);
                $vehicleCategorySpare = new VehicleCategory();
                $vehicleCategorySpare->user_id = $user->id;
                $vehicleCategorySpare->business_category = "spare_part";
                $vehicleCategorySpare->car = $car;
                $vehicleCategorySpare->bus = $bus;
                $vehicleCategorySpare->truck = $truck;
                $vehicleCategorySpare->save();

                // Save cars or buses or trucks
                if ($data['vehicle_category_spare'] === 'car') {
                    $carBrand = new CarBrand();
                    $carBrand->user_id = $user->id;
                    $carBrand->business_category = "spare_part";
                    foreach ($data['car_brands_spare'] as $type) {
                        $carBrand->{$type} = TRUE;
                    }
                    $savedCarBrandSpare = $carBrand->save();
                } elseif ($data['vehicle_category_spare'] === 'bus') {
                    $busBrand = new BusBrand();
                    $busBrand->user_id = $user->id;
                    $busBrand->business_category = "spare_part";
                    foreach ($data['bus_brands_spare'] as $type) {
                        $busBrand->{$type} = TRUE;
                    }
                    $savedBusBrandSpare = $busBrand->save();
                } elseif ($data['vehicle_category_spare'] === 'truck') {
                    $truckBrand = new TruckBrand();
                    $truckBrand->user_id = $user->id;
                    $truckBrand->business_category = "spare_part";
                    foreach ($data['truck_brands_spare'] as $type) {
                        $truckBrand->{$type} = TRUE;
                    }
                    $savedTruckBrandSpare = $truckBrand->save();
                }                                
            }

            // After making all the data, save all
            // $user->save();
            // $address->save();
            // $business_category->save();
            // if ($artisan) {
            //     $savedArtisan->save();
            // }
            // if ($seller) {
            //     $savedProduct->save();
            // }
            // if ($mechanic) {
            //     $savedTechnicalService->save();
            //     $vehicleCategory->save();
            //     if ($data['vehicle_category'] === 'car') {
            //         $savedCarBrand->save();
            //     } elseif ($data['vehicle_category'] === 'bus') {
            //         $savedBusBrand->save();
            //     } elseif ($data['vehicle_category'] === 'truck') {
            //         $savedTruckBrand->save();
            //     }                
            // }
            // if ($spare_part_seller) {
            //     $savedSparePart->save();
            //     $vehicleCategorySpare->save();
            //     if ($data['vehicle_category_spare'] === 'car') {
            //         $savedCarBrandSpare->save();
            //     } elseif ($data['vehicle_category_spare'] === 'bus') {
            //         $savedBusBrandSpare->save();
            //     } elseif ($data['vehicle_category_spare'] === 'truck') {
            //         $savedTruckBrandSpare->save();
            //     }                
            // }

            return $user;
        }
    }
}
