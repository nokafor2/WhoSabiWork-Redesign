<?php

namespace App\Http\Traits;

use App\Models\Address;
use App\Models\Artisan;
use App\Models\BusinessCategory;
use App\Models\Product;
use App\Models\User;
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
            if ($categoryType === 'mechanic') {

            }
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
        
        // convert from collection to array then reduce the array form
        $modifiedArray = $this->reduceArray($businessUserState->toArray());
        
        // Capitalize first word of each letter
        $ucStates = array_map('ucwords', $modifiedArray);

        // Elinimate duplicate
        $uniqueStates = array_unique($ucStates);

        // Sort the array in accending order
        asort($uniqueStates);
        
        return $uniqueStates;
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
        
        // convert from collection to array then reduce the array form
        $modifiedArray = $this->reduceArray($businessUserTown->toArray());
        
        // Capitalize first word of each letter
        $ucTowns = array_map('ucwords', $modifiedArray);

        // Elinimate duplicate
        $uniqueTowns = array_unique($ucTowns);

        // Sort the array in accending order
        asort($uniqueTowns);

        return $uniqueTowns;
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
}

?>