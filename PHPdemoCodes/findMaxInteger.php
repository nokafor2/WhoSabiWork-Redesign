<?php

declare(strict_types = 1);
$maxVal = 0;

function my_max(array $xs): int {
    global $maxVal;
    // $maxVal = 0;
    foreach ($xs as $key => $value) {
        if (is_array($value)) {
            // Using recursive function
            $maxVal = my_max($value);
        } elseif (is_int($value)) {
            if ($value > $maxVal) {
                $maxVal = $value;
            }
        }
    }

    return $maxVal;
}

echo my_max([1, [2, 6], [1, 90, [5, 33], [90, 100, [200, 300]]], 20]);

?>