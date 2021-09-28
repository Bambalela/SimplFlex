<?php

function debugToConsole($data)
{
    echo "<script>console.log('$data' + ' ');</script>";
}

function returnSign($number): string
{
    switch ($number) {
        case -1:
        {
            return '≤';
            break;
        }
        case 0:
        {
            return '=';
            break;
        }
        case 1:
        {
            return '≥';
            break;
        }
        default:
            return ' ';
    }
}

