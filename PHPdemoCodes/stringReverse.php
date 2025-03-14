<?php
    $str = "Reverse this string.";

    function my_rev(string $str): string {
        $reversedString = "";
        for($i = -1; $i >= -strlen($str); $i--) {
            // echo $str[$i]."\n";
            $reversedString = $reversedString.$str[$i];
        }

        return $reversedString;
    }

    echo my_rev($str);
?>