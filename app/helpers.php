<?php

function int_to_string($number)
{
    $int_string = (string)$number;

    switch (strlen($int_string)) {
        case 1:
            $result = '00' . $int_string;
            break;
        case 2:
            $result = '0' . $int_string;
            break;
        case 3:
            $result = $int_string;
            break;
        default:
            $result = '000';
    }

    return $result;
}