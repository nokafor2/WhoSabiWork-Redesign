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
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

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
        $searchedResult2 = collect();
        if ($pageName === 'artisan') {
            $categoryModels = ['artisan' => 'artisan', 'technician' => 'technicalService'];
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchBusinessName($searchVal, $category, $model);
                $searchedResult->push($result);
                
                // dd($searchedResult);
            }
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchFullName($searchVal, $model);
                $searchedResult->push($result);

                dd($searchedResult);
            }
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchCategory($searchVal, $model);
                $searchedResult2->push($result);
            }
        } elseif ($pageName === 'seller') {
            $categoryModels = ['seller' => 'product', 'spare_part_seller' => 'sparePart'];
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchBusinessName($searchVal, $category, $model);
                $searchedResult->push($result);
            }
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchFullName($searchVal, $model);
                $searchedResult->push($result);
            }
            foreach ($categoryModels as $category => $model) {
                $result = $this->searchCategory($searchVal, $model);
                $searchedResult2->push($result);
            }
        }
        $searchedResult = $searchedResult->merge($searchedResult2);
        
        
        return $searchedResult;
    }

    // Search for business name
    public function searchBusinessName($searchVal, $bussCategory, $categoryTable) {
        // Declare variables
        $searchArray = explode(" ", $searchVal);

        // Search for business title
        if (count($searchArray) >= 1) {
            // Search for ids
            $searchedResultIds = BusinessCategory::where([['business_name', 'LIKE', '%'.$searchVal.'%'], [$bussCategory, '=', 1]])->select('user_id')->get();
            // Use the Ids to get searched data
            return $searchedResult = User::whereIn('id', $searchedResultIds)->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();
        } else {
            return false;
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
            return false;
        }
    }

    // search for either first name, last name, username, phone number and email 
    public function searchCategory($searchVal, $categoryTable) {
        // Declare variables
        $searchArray = explode(" ", $searchVal);

        $searchedResult = collect();
        foreach($searchArray as $value) {
            $result = User::orWhere([['first_name', 'LIKE', $value], ['last_name', 'LIKE', $value], ['username', 'LIKE', $value], ['phone_number', 'LIKE', $value], ['email', 'LIKE', $value]])->where([['account_type', '=', 'business'], ['account_status', '=', 'active']])->with('address', $categoryTable, 'businessCategory')->get();
            $searchedResult->add($result);
        }
        
        return $searchedResult;
    }
}

?>