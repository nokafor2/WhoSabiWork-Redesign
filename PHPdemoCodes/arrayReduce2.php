<?php 

$arrayInput = [
    0 => [
        "car" => 0,
        "bus" => 1,
        "truck" => 1,
    ],
    1 => [
        "car" => 0,
        "bus" => 1,
        "truck" => 1,
    ],
    2 => [
        "car" => 0,
        "bus" => 0,
        "truck" => 1,
    ],
    3 => [
        "car" => 0,
        "bus" => 1,
        "truck" => 0,
    ]
];

function reduceArray($arrayInput) {
    $newArray = array();
    foreach($arrayInput as $key => $value) {
        foreach ($value as $key2 => $value2) {
            if ($value2 === 1 ) {
                $newArray[] = $key2;
            }
        }
    }

    return array_unique($newArray);
}

$modifiedArray = reduceArray($arrayInput);

print_r($modifiedArray);

$newArray = array();

print_r(array_keys($arrayInput[0]));

$diffArray = array_diff($newArray, $arrayInput[0]);

print_r($diffArray);

echo in_array('car', array_keys($arrayInput[0]));

?>