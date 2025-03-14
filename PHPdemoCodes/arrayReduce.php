<?php

$businessUserState = [
    5 => ["state" => "Alaska",],
    7 => ["state" => "Alaska",],
    8 => ["state" => "Indiana",],
    0 => ["state" => "Kansas",],
    1 => ["state" => "Louisiana",],
    2 => ["state" => "Louisiana",],
    6 => ["state" => "Louisiana",],
    3 => ["state" => "Massachusetts",],
    31 => ["state" => "Massachusetts",],
    32 => ["state" => "Massachusetts",],
    9 => ["state" => "Oklahoma",],
    101 => ["state" => "Pennsylvania",],
    102 => ["state" => "Pennsylvania",],
    10 => ["state" => "Pennsylvania",],
    4 => ["state" => "Vermont",],
    111 => ["state" => "abuja",],
    112 => ["state" => "abuja",],
    11 => ["state" => "abuja",],
];

function reduceArray($arrayInput) {
    $newArray = array();
    foreach($arrayInput as $key => $value) {
        foreach ($value as $key2 => $value2) {
            $newArray[] = $value2;
        }
    }

    return $newArray;
}

$modifiedArray = reduceArray($businessUserState);

// print_r($modifiedArray);

// Capitalize first word of each letter
$ucStates = array_map('ucwords', $modifiedArray);
print_r($ucStates);

// Elinimate duplicate
$uniqueStates = array_unique($ucStates);
print_r($uniqueStates);

// Sort the array in accending order
asort($uniqueStates);
print_r($uniqueStates);

?>