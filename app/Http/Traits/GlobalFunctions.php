<?php

namespace App\Http\Traits;

use App\Models\Address;
use App\Models\Artisan;
use App\Models\BusBrand;
use App\Models\BusinessCategory;
use App\Models\CarBrand;
use App\Models\Product;
use App\Models\SparePart;
use App\Models\TechnicalService;
use App\Models\TruckBrand;
use App\Models\User;
use App\Models\UsersAppointment;
use App\Models\UsersAvailability;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait GlobalFunctions {

    public $productColumnsToExclude = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];

    public function allTableColumns($table) {
        $allTableColumns = Schema::getColumnListing($table);
        return $allTableColumns;
    }
    
    public function getTableColumnsWithSort($table, Array $columnsToExclude) {
        // return DB::getSchemaBuilder()->getColumnListing($table);

        $allTableColumns = Schema::getColumnListing($table);
        // Eliminate columns that are not needed and create two new arrays
        $refinedTableColumns = $refinedTableColumns2 = array_diff($allTableColumns, $columnsToExclude);
        $refinedTableColumnsSorted = array();
        // Use the second similar array to remove underscores and make first letter capital
        foreach ($refinedTableColumns2 as $key => $value) {
            $refinedTableColumnsSorted[$key] = ucfirst(str_replace("_", " ", $value));
        }
        // Sort the second array alphabetically
        asort($refinedTableColumnsSorted);
        $refinedTableColumnsObj = array();
        // Merge the refined array and the sorted array into one array as an associative array of key and values
        foreach ($refinedTableColumnsSorted as $key => $value) {
            $refinedTableColumnsObj[$refinedTableColumns[$key]] = $value;
        }
        // Convert array to a collection object
        $refinedTableColumnsObj = collect($refinedTableColumnsObj);
        
        return $refinedTableColumnsObj;
    }

    public function getUserCategoryDetails(String $businessCategory) {
        $businessCategoryIds = BusinessCategory::where($businessCategory, '=', 1)->select('user_id')->get();

        if ($businessCategory == 'artisan') {
            $categoryTable = 'artisan';
        } elseif ($businessCategory == 'seller') {
            $categoryTable = 'product';
        } elseif ($businessCategory == 'technician') {
            $categoryTable = 'technicalService';
        } elseif ($businessCategory == 'spare_part_seller') {
            $categoryTable = 'sparePart';
        }

        return User::whereIn('id', $businessCategoryIds)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();
    }

    public function getSpecifiedUserDetails(String $businessCategory, $categoryType, $state, $town) {
        $businessCategoryIds = BusinessCategory::where($businessCategory, '=', 1)->select('user_id')->get();

        if ($businessCategory === 'artisan') {
            $categoryTable = 'artisan';
            // filter the specified artisans
            $categoryIds = Artisan::whereIn('user_id', $businessCategoryIds)->where($categoryType, '=', 1)->select('user_id')->get();
        } elseif ($businessCategory === 'seller') {
            $categoryTable = 'product';
            // filter the specified mobile marketers
            $categoryIds = Product::whereIn('user_id', $businessCategoryIds)->where($categoryType, '=', 1)->select('user_id')->get();
        } elseif ($businessCategory == 'technician') {
            $categoryTable = 'technicalService';
        } elseif ($businessCategory == 'spare_part_seller') {
            $categoryTable = 'sparePart';
        }
        
        // filter the category ids on the specified state and town
        $refCategoryIds = Address::whereIn('user_id', $categoryIds)->where([['state', '=', $state], ['town', '=', $town]])->select('user_id')->get();

        return User::whereIn('id', $refCategoryIds)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();
    }

    public function getTechServs() {
        // Get all columns of the table
        $allTechServCols = DB::getSchemaBuilder()->getColumnListing('technical_services');
        // Get columns to exclude from table            
        $arrayExclude = TechnicalService::$columnsToExclude;
        // Get technical service columns
        $techServCols = array_diff($allTechServCols, $arrayExclude);
        // Get the specified artisan type
        $availTechServs = TechnicalService::where(function ($query) use ($techServCols) {
            foreach($techServCols as $key => $column) {
                $query->orWhere($column, 1);
            }
        })->get();

        return $this->reduceArray2($availTechServs->toArray());
    }

    public function getVehCategories($pageName, $vehServiceOrSpare) {
        if ($pageName === 'artisan') {
            // Get the specified technical service type
            $specificCategoryType = TechnicalService::where($vehServiceOrSpare, '=', 1)->select('user_id')->get();
            // Get the business user id
            $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
            // Get the vehicle category
            $availVehCategories = VehicleCategory::whereIn('user_id', $businessUserId)->where('business_category', '=', 'technical_service')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('car', 'bus', 'truck')->get();
        } elseif ($pageName === 'seller') {
            // Get the specified mobile marketer type
            $specificCategoryType = SparePart::where($vehServiceOrSpare, '=', 1)->select('user_id')->get();
            // Get the business user id
            $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
            // Get the vehicle category
            $availVehCategories = VehicleCategory::whereIn('user_id', $businessUserId)->where('business_category', '=', 'spare_part')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('car', 'bus', 'truck')->get();
        }
        
        return $this->reduceArray2($availVehCategories->toArray());
    }

    public function getVehBrands($pageName, $vehServiceOrSpare, $vehType) {
        if ($pageName === 'artisan') {
            // Get the specified technical service type
            $specificCategoryType = TechnicalService::where($vehServiceOrSpare, '=', 1)->select('user_id')->get();
            // Get the business user id
            $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
            // Get the vehicle category
            $availVehCategoriesId = VehicleCategory::whereIn('user_id', $businessUserId)->where('business_category', '=', 'technical_service')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('user_id')->get();
        } elseif ($pageName === 'seller') {
            // Get the specified mobile marketer type
            $specificCategoryType = SparePart::where($vehServiceOrSpare, '=', 1)->select('user_id')->get();
            // Get the business user id
            $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
            // Get the vehicle category
            $availVehCategoriesId = VehicleCategory::whereIn('user_id', $businessUserId)->where('business_category', '=', 'spare_part')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('user_id')->get();
        }
        // Get the vehicle brands
        $vehBrands = $this->vehicleBrands($vehType, $availVehCategoriesId);
        
        return $this->reduceArray2($vehBrands->toArray());
    }

    public function vehicleBrands($vehType, $availVehCategoriesId) {
        if ($vehType === 'car') {
            $columnsToExclude = CarBrand::$columnsToExclude;
            $carBrandCols = $this->getTableColumnsWithSort('car_brands', $columnsToExclude);
            $vehBrands = CarBrand::whereIn('id', $availVehCategoriesId)->where(function ($query) use ($carBrandCols) {
                foreach($carBrandCols as $key => $column) {
                    $query->orWhere($key, 1);
                }
            })->get();
        } elseif ($vehType === 'bus') {
            $columnsToExclude = BusBrand::$columnsToExclude;
            $busBrandCols = $this->getTableColumnsWithSort('bus_brands', $columnsToExclude);
            $vehBrands = BusBrand::whereIn('id', $availVehCategoriesId)->where(function ($query) use ($busBrandCols) {
                foreach($busBrandCols as $key => $column) {
                    $query->orWhere($key, 1);
                }
            })->get();
        } elseif ($vehType === 'truck') {
            $columnsToExclude = TruckBrand::$columnsToExclude;
            $truckBrandCols = $this->getTableColumnsWithSort('truck_brands', $columnsToExclude);
            $vehBrands = TruckBrand::whereIn('id', $availVehCategoriesId)->where(function ($query) use ($truckBrandCols) {
                foreach($truckBrandCols as $key => $column) {
                    $query->orWhere($key, 1);
                }
            })->get();
        }

        return $vehBrands;
    }

    public function selectedVehBrandIds($pageName, $vehType, $availVehCategoriesId, $vehBrand) {
        if ($pageName === 'artisan') {
            $techServOrSparePart = 'technical_service';
        } elseif ($pageName === 'seller') {
            $techServOrSparePart = 'spare_part';
        }

        if ($vehType === 'car') {
            $vehBrandIds = CarBrand::whereIn('id', $availVehCategoriesId)->where([['business_category', '=', $techServOrSparePart], [$vehBrand, '=', 1]])->select('user_id')->get();
        } elseif ($vehType === 'bus') {
            $vehBrandIds = BusBrand::whereIn('id', $availVehCategoriesId)->where([['business_category', '=', $techServOrSparePart], [$vehBrand, '=', 1]])->select('user_id')->get();
        } elseif ($vehType === 'truck') {
            $vehBrandIds = TruckBrand::whereIn('id', $availVehCategoriesId)->where([['business_category', '=', $techServOrSparePart], [$vehBrand, '=', 1]])->select('user_id')->get();
        }

        return $vehBrandIds;
    }

    // Can be used to get the states or towns
    public function getStateTownTechOrSpare($pageName, $vehServOrSpare, $vehType, $vehBrand, $state) {
        if ($pageName === 'artisan') {
            // Get the specified technical service type
            $specificCategoryType = TechnicalService::where($vehServOrSpare, '=', 1)->select('user_id')->get();
            // Get the business user id
            $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
            // Get the vehicle category
            $availVehCategoriesId = VehicleCategory::whereIn('user_id', $businessUserId)->where('business_category', '=', 'technical_service')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('user_id')->get();
            // Get the vehicle brands
            $vehBrandsId = $this->selectedVehBrandIds($pageName, $vehType, $availVehCategoriesId, $vehBrand);
        } elseif ($pageName === 'seller') {
            // Get the specified mobile marketer type
            $specificCategoryType = SparePart::where($vehServOrSpare, '=', 1)->select('user_id')->get();
            // Get the business user id
            $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
            // Get the vehicle category
            $availVehCategoriesId = VehicleCategory::whereIn('user_id', $businessUserId)->where('business_category', '=', 'spare_part')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('user_id')->get();
            // Get the vehicle brands
            $vehBrandsId = $this->selectedVehBrandIds($pageName, $vehType, $availVehCategoriesId, $vehBrand);
        }
        
        if (empty($state)) {
            // Get the states
            $states = Address::whereIn('user_id', $vehBrandsId)->select('state')->get();
            
            return $this->reduceArray($states->toArray());
        } else {
            // Get the town
            $towns = Address::whereIn('user_id', $vehBrandsId)->where('state', '=', $state)->select('town')->get();
            
            return $this->refineArray($towns);
        }
    }

    public function getTechOrSpareUserDetails($pageName, $vehServOrSpare, $vehType, $vehBrand, $state, $town) {
        if ($pageName === 'artisan') {
            $categoryTable = 'technicalService';
            // Get the specified technical service type
            $specificCategoryType = TechnicalService::where($vehServOrSpare, '=', 1)->select('user_id')->get();
            // Get the vehicle category
            $availVehCategoriesId = VehicleCategory::whereIn('user_id', $specificCategoryType)->where('business_category', '=', 'technical_service')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('user_id')->get();
            // Get the vehicle brands
            $vehBrandsId = $this->selectedVehBrandIds($pageName, $vehType, $availVehCategoriesId, $vehBrand);
        } elseif ($pageName === 'seller') {
            $categoryTable = 'sparePart';
            // Get the specified mobile marketer type
            $specificCategoryType = SparePart::where($vehServOrSpare, '=', 1)->select('user_id')->get();
            // Get the vehicle category
            $availVehCategoriesId = VehicleCategory::whereIn('user_id', $specificCategoryType)->where('business_category', '=', 'spare_part')->orWhere([['car', '=', 1], ['bus', '=', 1], ['truck', '=', 1]])->select('user_id')->get();
            // Get the vehicle brands
            $vehBrandsId = $this->selectedVehBrandIds($pageName, $vehType, $availVehCategoriesId, $vehBrand);
        }

        // filter the category ids on the specified state and town
        $refCategoryIds = Address::whereIn('user_id', $vehBrandsId)->where([['state', '=', $state], ['town', '=', $town]])->select('user_id')->get();

        return User::whereIn('id', $refCategoryIds)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();    
    }

    public function getBussUserState($categoryType, $pageName) {
        if ($pageName === 'artisan') {
            // Get the specified artisan type
            $specificCategoryType = Artisan::where($categoryType, '=', 1)->select('user_id')->get();
        } elseif ($pageName === 'seller') {
            // Get the specified mobile marketer type
            $specificCategoryType = Product::where($categoryType, '=', 1)->select('user_id')->get();
        }
        // Get the business user id
        $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
        // Get the state
        $businessUserState = Address::whereIn('user_id', $businessUserId)->select('state')->get();
        
        return $this->refineArray($businessUserState);
    }

    public function getBussUserTown($categoryType, $selectedState, $pageName) {
        if ($pageName === 'artisan') {
            // Get the specified artisan type
            $specificCategoryType = Artisan::where($categoryType, '=', 1)->select('user_id')->get(); 
        } elseif ($pageName === 'seller') {
            // Get the specified mobile marketer type
            $specificCategoryType = Product::where($categoryType, '=', 1)->select('user_id')->get(); 
        }
        // Get the business user id
        $businessUserId = User::whereIn('id', $specificCategoryType)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->select('id')->get();
        // Get the town
        $businessUserTown = Address::whereIn('user_id', $businessUserId)->where('state', '=', $selectedState)->select('town')->get();
        
        return $this->refineArray($businessUserTown);
    }

    public function refineArray($arrayInput) {
        // convert from collection to array then reduce the array form
        $modifiedArray = $this->reduceArray($arrayInput->toArray());
        
        // Capitalize first word of each letter
        $ucValues = array_map('ucwords', $modifiedArray);

        // Elinimate duplicate
        $uniqueValues = array_unique($ucValues);

        // Sort the array in accending order
        asort($uniqueValues);

        return $uniqueValues;
    }

    public function reduceArray($arrayInput) {
        $newArray = array();
        foreach($arrayInput as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $newArray[] = $value2;
            }
        }
    
        return $newArray;
    }

    function reduceArray2($arrayInput) {
        $newArray = array();
        foreach($arrayInput as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if ($value2 === 1 ) {
                    $newArray[$key2] = $key2;
                }
            }
        }
        asort($newArray);
    
        return array_map('ucwords', array_unique($newArray));
    }

    public function searchData($searchVal, $pageName) {
        $searchedResult = collect();
        if ($pageName === 'artisan') {
            $categoryModels = ['artisan' => 'artisan', 'technician' => 'technicalService'];
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchBusinessName($searchVal, $category, $model);
                if ($result->isNotEmpty()) {
                    $searchedResult->push($result);
                }
            }
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchFullName($searchVal, $model);
                if ($result->isNotEmpty()) {
                    $searchedResult->push($result);
                }
            }
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchCategory($searchedResult, $searchVal, $model);
            }
        } elseif ($pageName === 'seller') {
            $categoryModels = ['seller' => 'product', 'spare_part_seller' => 'sparePart'];
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchBusinessName($searchVal, $category, $model);
                if ($result->isNotEmpty()) {
                    $searchedResult->push($result);
                }
            }
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchFullName($searchVal, $model);
                if ($result->isNotEmpty()) {
                    $searchedResult->push($result);
                }
            }
            foreach ($categoryModels as $category => $model) {
                $this->searchCategory($searchedResult, $searchVal, $model);
            }
        }
        
        // Convert collection to array and reduce array
        $searchedResult = $this->reduceArray($searchedResult->toArray());
        
        // return collect($this->eliminateDuplicates($searchedResult));
        return $this->eliminateDuplicates($searchedResult);
    }

    public function eliminateDuplicates(Array $inputArray) {
        $uniqueResults = array();
        foreach($inputArray as $key => $value) {
            foreach($value as $key2 => $value2) {
                if ($key2 === 'id') {
                    $uniqueResults[$value2] = $value;
                }
            }
        }

        return $uniqueResults;
    }

    // Search for business name
    public function searchBusinessName($searchVal, $bussCategory, $categoryTable) {
        // Declare variables
        $searchArray = explode(" ", $searchVal);
        
        // Search for business title
        if (count($searchArray) >= 1) {
            // Search for ids
            $searchedResultIds = BusinessCategory::where([['business_name', 'LIKE', '%'.$searchArray[0].'%'], [$bussCategory, '=', 1]])->select('user_id')->get();
            // Use the Ids to get searched data
            return User::whereIn('id', $searchedResultIds)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();
        } else {
            return collect([]);
        }
    }

    // Search for full name
    public function searchFullName($searchVal, $categoryTable) {
        // Declare variables
        $searchArray = explode(" ", $searchVal);

        // Search for full name
        if (count($searchArray) > 1) {
            return $searchedResult = User::where([['first_name', 'LIKE', $searchArray[0]], ['last_name', 'LIKE', $searchArray[1]]])->orWhere([['first_name', 'LIKE', $searchArray[1]], ['last_name', 'LIKE', $searchArray[0]]])->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();
        } else {
            return collect([]);
        }
    }

    // search for either first name, last name, username, phone number and email 
    public function searchCategory($searchedResult, $searchVal, $categoryTable) {
        // Declare variables
        $searchArray = explode(" ", $searchVal);

        // $searchedResult = collect();
        foreach($searchArray as $value) {
            $result = User::orWhere([['first_name', 'LIKE', $value], ['last_name', 'LIKE', $value], ['username', 'LIKE', $value], ['phone_number', 'LIKE', $value], ['email', 'LIKE', $value]])->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();
            if ($result->isNotEmpty()) {
                $searchedResult->push($result);
            }
        }
        
        return $searchedResult;
    }

    public function getUserDetails($userId) {
        $newCategoryType = $this->getBussCategory($userId);

        $models = ['Address', 'BusinessCategory', 'UsersRating'];
        $techVehCategories = [];
        $sparePartVehCategories = [];
        // Add category models to models array
        foreach($newCategoryType as $category) {
            if ($category === 'artisan') {
                $models[] = 'Artisan';
            } elseif ($category === 'seller') {
                $models[] = 'Product';
            } elseif ($category === 'technician') {
                $models[] = 'TechnicalService';
                // Get vehicles related to user id
                $techVehCategories = $this->getVehCategory($userId, 'technical_service');
                if (!empty($techVehCategories)) {
                    $models = array_merge($models, $this->getVehModels($techVehCategories));
                }
            } elseif ($category === 'spare_part_seller') {
                $models[] = 'SparePart';
                // Get vehicles related to user id
                $sparePartVehCategories = $this->getVehCategory($userId, 'spare_part');
                if (!empty($sparePartVehCategories)) {
                    $models = array_merge($models, $this->getVehModels($sparePartVehCategories));
                }
            }
        }
        
        $foundUser = User::whereIn('id', [$userId])->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with($models)->first()->toArray();
        // dd($foundUser);
        $refinedCategoryArray = array();
        $refVehBrand = array();
        foreach($newCategoryType as $category) {
            if ($category === 'artisan') {
                if ($foundUser['artisan'] !== null) {
                    $refinedCategoryArray['artisan'] = $this->refinedModel('App\Models\Artisan', $foundUser['artisan']);
                } else {
                    $refinedCategoryArray['artisan'] = [];
                }
            } elseif ($category === 'seller') {
                if ($foundUser['product'] !== null) {
                    $refinedCategoryArray['mobile_marketer'] = $this->refinedModel('App\Models\Product', $foundUser['product']);
                } else {
                    $refinedCategoryArray['mobile_marketer'] = [];
                }
            } elseif ($category === 'technician') {
                if ($foundUser['technical_service'] !== null) {
                    $refinedCategoryArray['mechanic'] = $this->refinedModel('App\Models\TechnicalService', $foundUser['technical_service']);
                } else {
                    $refinedCategoryArray['mechanic'] = [];
                }
                if (!empty($techVehCategories)) {
                    foreach ($techVehCategories as $vehicle) {
                        if ($vehicle === 'car') {
                            if (array_key_exists(0, $foundUser['car_brand'])) {
                                $refVehBrand['tech_car'] = $this->refinedModel('App\Models\CarBrand', $foundUser['car_brand'][0]);
                            } else {
                                $refVehBrand['tech_car'][0] = '';
                            }
                        } elseif ($vehicle === 'bus') {
                            if (array_key_exists(0, $foundUser['bus_brand'])) {
                                $refVehBrand['tech_bus'] = $this->refinedModel('App\Models\BusBrand', $foundUser['bus_brand'][0]);
                            } else {
                                $refVehBrand['tech_bus'][0] = '';
                            }
                        } elseif ($vehicle === 'truck') {
                            if (array_key_exists(0, $foundUser['truck_brand'])) {
                                $refVehBrand['tech_truck'] = $this->refinedModel('App\Models\TruckBrand', $foundUser['truck_brand'][0]);
                            } else {
                                $refVehBrand['tech_truck'][0] = '';
                            }
                        }
                    }
                }
            } elseif ($category === 'spare_part_seller') {
                if ($foundUser['spare_part'] !== null) {
                    $refinedCategoryArray['spare_part_seller'] = $this->refinedModel('App\Models\SparePart', $foundUser['spare_part']);
                } else {
                    $refinedCategoryArray['spare_part_seller'] = [];
                }
                if (!empty($sparePartVehCategories)) {
                    foreach ($sparePartVehCategories as $vehicle) {
                        if ($vehicle === 'car') {
                            if (array_key_exists(1, $foundUser['car_brand'])) {
                                $refVehBrand['sPart_car'] = $this->refinedModel('App\Models\CarBrand', $foundUser['car_brand'][1]);
                            } else {
                                $refVehBrand['sPart_car'][1] = '';
                            }
                        } elseif ($vehicle === 'bus') {
                            if (array_key_exists(1, $foundUser['bus_brand'])) {
                                $refVehBrand['sPart_bus'] = $this->refinedModel('App\Models\BusBrand', $foundUser['bus_brand'][1]);
                            } else {
                                $refVehBrand['sPart_bus'][1] = '';
                            }
                        } elseif ($vehicle === 'truck') {
                            if (array_key_exists(1, $foundUser['truck_brand'])) {
                                $refVehBrand['sPart_truck'] = $this->refinedModel('App\Models\TruckBrand', $foundUser['truck_brand'][1]);
                            } else {
                                $refVehBrand['sPart_truck'][1] = '';
                            }
                        }
                    }
                }
            }
        }

        if (isset($foundUser['users_rating']) && !empty($foundUser['users_rating'])) {
            $ratingCount = count($foundUser['users_rating']);
            // get the average rating
            $ratingSum = 0;
            foreach ($foundUser['users_rating'] as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($key2 === 'rating') {
                        $ratingSum += $value2;
                    }
                }
            }
            $avgRating = round($ratingSum / $ratingCount);
            $userRating = ['ratingCount' => $ratingCount, 'avgRating' => $avgRating];
        }

        return ['userDetails' => $foundUser, 
                'userCategories' => $refinedCategoryArray,
                'techVehCategories' => $techVehCategories,
                'sPartVehCategories' => $sparePartVehCategories,
                'vehicleBrands' => $refVehBrand,
                'userRating' => $userRating,
                'datesAvailable' => $this->getAvailabilityDates($userId),
        ];
    }

    public function getBussCategory($userId) {
        $categoryType = BusinessCategory::where('user_id', '=', $userId)->select('artisan', 'seller', 'technician', 'spare_part_seller')->first()->toArray();

        $newCategoryType = array();
        foreach($categoryType as $category => $presence) {
            if ($presence == 1) {
                $newCategoryType[] = $category;
            }
        }

        return $newCategoryType;
    }

    public function getVehCategory($userId, $techOrSpare) {
        if ($techOrSpare === 'technical_service') {
            $vehCategories = VehicleCategory::where([['user_id', '=', $userId], ['business_category', '=', 'technical_service']])->select('car', 'bus', 'truck')->first();
        } elseif ($techOrSpare === 'spare_part') {
            $vehCategories = VehicleCategory::where([['user_id', '=', $userId], ['business_category', '=', 'spare_part']])->select('car', 'bus', 'truck')->first();
        }
        if ($vehCategories !== null) {
            $vehCategories = $vehCategories->toArray();
        }
        
        $newVehCategories = array();
        if (!empty($vehCategories)) {
            foreach($vehCategories as $category => $presence) {
                if ($presence == 1) {
                    $newVehCategories[] = $category;
                }
            }
        }

        return $newVehCategories;
    }

    public function getVehModels($newVehCategories) {
        $models = [];
        // Add category models to models array
        foreach($newVehCategories as $category) {
            if ($category === 'car') {
                $models[] = 'CarBrand';
            } elseif ($category === 'bus') {
                $models[] = 'BusBrand';
            } elseif ($category === 'truck') {
                $models[] = 'TruckBrand';
            }
        }

        return $models;
    }

    public function reduceArray3($arrayInput) {
        $newArray = array();
        foreach ($arrayInput as $key => $value) {
            // Remove the id row and select only true values
            if (($key !== 'id') && ($value === 1)) {
                $newArray[$key] = ucfirst(str_replace("_", " ", $key));
            }
        }
        asort($newArray);
    
        return $newArray;
    }

    public function refinedModel($model, $category) {
        // ${$model}
        // Get columns to exclude from table            
        $arrayExclude = $model::$columnsToExclude;
        // Get artisan columns 
        $refindedCols = array_diff($category, $arrayExclude);
        // reform the array
        return $this->reduceArray3($refindedCols);
    }

    public function getSchedule($userId, $dateAvailble, $record) {
        // get current date
        $currentDate = date('Y-m-d');
        if ($record === 'single') {
            $result = UsersAvailability::where([['user_id', '=', $userId], ['date_available', '=', $dateAvailble]])->get();
        } elseif ($record === 'many') {
            $result = UsersAvailability::where([['user_id', '=', $userId], ['date_available', '>=', $currentDate]])->orderBy('date_available', 'asc')->get();
        }
        
        $arrayToExclude = ['id' => '', 'user_id' => '', 'created_at' => '', 'updated_at' => '', 'deleted_at' => ''];
        $resultArray = $result->toArray();
        $schedules = array();
        // Retrieve only time schedule
        foreach ($resultArray as $index => $array) {
            $schedules[] = array_diff_key($array, $arrayToExclude);
        }

        $newSchedule = array();
        $timeSchedule = array();
        foreach ($schedules as $key => $schedule) {
            $count = 0;
            foreach ($schedule as $key2 => $value2) {
                // Filter out the true schedule
                if ($value2 === 1) {
                    $timeSchedule[$key2] = $this->convertTime($key2);
                }
                $count++;
            }
            // $date = Carbon::parse($schedule['date_available'])->diffForHumans();
            // $date = Carbon::parse($schedule['date_available'])->format('l jS \\of F Y h:i:s A');
            $date = Carbon::parse($schedule['date_available'])->format('l jS \\of F Y');
            $newSchedule[$date] = $timeSchedule;
            // reset timeSchedule
            $timeSchedule = [];
        }

        return $newSchedule;
    }

    public function convertTime($time) {
        if ($time === 'eight_AM') {
            return ['time' => '8:00', 'period' => 'AM'];            
        } elseif ($time === 'eight_Thirty_AM') {
            return ['time' => '8:30', 'period' => 'AM'];
        } elseif ($time === 'nine_AM') {
            return ['time' => '9:00', 'period' => 'AM'];
        } elseif ($time === 'nine_Thirty_AM') {
            return ['time' => '9:30', 'period' => 'AM'];
        } elseif ($time === 'ten_AM') {
            return ['time' => '10:00', 'period' => 'AM'];
        } elseif ($time === 'ten_Thirty_AM') {
            return ['time' => '10:30', 'period' => 'AM'];
        } elseif ($time === 'eleven_AM') {
            return ['time' => '11:00', 'period' => 'AM'];
        } elseif ($time === 'eleven_Thirty_AM') {
            return ['time' => '11:30', 'period' => 'AM'];
        } elseif ($time === 'twelve_PM') {
            return ['time' => '12:00', 'period' => 'PM'];
        } elseif ($time === 'twelve_Thirty_PM') {
            return ['time' => '12:30', 'period' => 'PM'];
        } elseif ($time === 'one_PM') {
            return ['time' => '1:00', 'period' => 'PM'];
        } elseif ($time === 'one_Thirty_PM') {
            return ['time' => '1:30', 'period' => 'PM'];
        } elseif ($time === 'two_PM') {
            return ['time' => '2:00', 'period' => 'PM'];
        } elseif ($time === 'two_Thirty_PM') {
            return ['time' => '2:30', 'period' => 'PM'];
        } elseif ($time === 'three_PM') {
            return ['time' => '3:00', 'period' => 'PM'];
        } elseif ($time === 'three_Thirty_PM') {
            return ['time' => '3:30', 'period' => 'PM'];
        } elseif ($time === 'four_PM') {
            return ['time' => '4:00', 'period' => 'PM'];
        } elseif ($time === 'four_Thirty_PM') {
            return ['time' => '4:30', 'period' => 'PM'];
        } elseif ($time === 'five_PM') {
            return ['time' => '5:00', 'period' => 'PM'];
        } elseif ($time === 'five_Thirty_PM') {
            return ['time' => '5:30', 'period' => 'PM'];
        } elseif ($time === 'six_PM') {
            return ['time' => '6:00', 'period' => 'PM'];
        } elseif ($time === 'six_Thirty_PM') {
            return ['time' => '6:30', 'period' => 'PM'];
        } elseif ($time === 'seven_PM') {
            return ['time' => '7:00', 'period' => 'PM'];
        } elseif ($time === 'seven_Thirty_PM') {
            return ['time' => '7:30', 'period' => 'PM'];
        }
    }

    public function getAvailabilityDates($userId) {
        // get current date
        $currentDate = date('Y-m-d');
        $result = UsersAvailability::where([['user_id', '=', $userId], ['date_available', '>=', $currentDate]])->orderBy('date_available', 'asc')->select('date_available')->get();
        return $result;
    }

    public function getAllScheduleTime() {
        // Get all the columns of the array
        $allTableColumns = Schema::getColumnListing('users_availabilities');
        // Specify the columns to be removed from the array
        $columnsToExclude = ['id', 'user_id', 'date_available', 'created_at', 'updated_at', 'deleted_at'];
        // Eliminate columns that are not needed and create two new arrays
        $refinedTableColumns = array_diff($allTableColumns, $columnsToExclude);

        return $refinedTableColumns;
    }

    public function getAppointments($decision, $userId, $schedulerId) {
        // get current date
        $currentDate = date('Y-m-d');
        $appointments = UsersAppointment::where([['user_id', '=', $userId], ['scheduler_id', '=', $schedulerId], ['appointment_date', '>=', $currentDate], ['user_decision', '=', $decision]])->get();
        $aptNum = $appointments->count();
        $appointmentDetails = array();
        $appointments->each(function ($appointment, $key) use(&$appointmentDetails) {
            // Get user full name
            // $action = (empty($_POST['action'])) ? 'default' : $_POST['action'];
            $fullName = ($appointment->user()->first()->userFullName() !== null) ? $appointment->user()->first()->userFullName() : "";
            // Get user phone number
            $phoneNumber = $appointment->user()->first()->phone_number;
            // Get scheduler full name
            $schedulerFullName = ($appointment->scheduler()->first()->userFullName() !== null) ? $appointment->user()->first()->userFullName() : "";
            // Get user phone number
            $schedulerPhoneNumber = $appointment->scheduler()->first()->phone_number;
            // Get business name
            $businessName = $appointment->user()->first()->businessCategory()->first()->business_name;
            // Get address
            $address = $appointment->user()->first()->address()->first()->fullAddress();
            // Get date
            $date = [
                'rawDate' => $appointment->appointment_date,
                'prettyDate' => Carbon::parse($appointment->appointment_date)->format('l jS \\of F Y')
            ];
            // Get hours
            $time = explode(",", $appointment->hours);
            $timeConvert = array();
            foreach ($time as $key2 => $value2) {
                $timeConvert[$value2] = $this->convertTime($value2);
            }
            // $timeString = implode(", ", $timeConvert);
            // Get rating
            $allRatings = $appointment->user()->first()->usersRating()->get();
            $avgRating = $this->avgRating($allRatings);
            $appointmentMessage = $appointment->appointment_message;
            $appointmentId = $appointment->id;

            $appointmentDetails[] = [
                'id' => $appointmentId,
                'fullName' => $fullName,
                'phoneNumber' => $phoneNumber,
                'schedulerFullName' => $schedulerFullName,
                'schedulerPhoneNumber' => $schedulerPhoneNumber,
                'businessName' => $businessName,
                'address' => $address,
                'date' => $date,
                'time' => $timeConvert,
                'rating' => $avgRating,
                'appointmentMessage' => $appointmentMessage,
            ];
        });

        return $appointmentData[] = [
                'appointmentDetails' => $appointmentDetails,
                'aptNum' => $aptNum,
        ];
    }

    public function avgRating($userRatings) {
        $ratingCount = $userRatings->count();
        $ratingSum = 0;
        $userRatings->each(function ($userRating) use (&$ratingSum) {
            $ratingSum += $userRating->rating;
        });
        $avgRating = round($ratingSum / $ratingCount);

        return $avgRating;
    }
}

?>