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

function indexOfMinNumber($array, $isCol = true): int
{
    $indexOfMin = -1;
    $minNumber = 0;
    for($i = 0; $i < count($array); $i++)
    {
        if($isCol){
            if($array[$i] < $minNumber && $array[$i] < 0){
                $minNumber = $array[$i];
                $indexOfMin = $i;
            }
        } else
        {
            if($array[$i]!=0) {
                $minNumber = $array[$i];
                $indexOfMin = $i;
            }
        }

    }
    return $indexOfMin;
}

function simplexMethod($table, $resultRow, $cols, $rows){
    $basisTable = [$rows+1][$cols];
    $electedRow = -1;
    $electedCol = -1;
    for($i=0;$i<$rows;$i++)
    {
        for($j=0;$j<$cols-1;$j++)
        {
            $basisTable[$i][$j] = $table[$i][$j];
        }
    }
    // Заповнення рядку дельта Z
    for($i=0;$i<=$cols;$i++){$basisTable[$rows][$i] = 0;}
    for($i=0;$i<count($resultRow)-1;$i++){$basisTable[$rows][$i] = $resultRow[$i] * -1;}
    // Заповння рядку О з косою рискою
    $electedCol = indexOfMinNumber($basisTable[$rows], true);
    $minColArr = $lastCol = [$rows];
    for($i=0;$i<$rows;$i++) $minColArr[$i] = $basisTable[$i][$electedCol];
    for($i=0;$i<$rows;$i++) {
        $lastCol[$i] = $table[$i][count($table[$i])-1]/$minColArr[$i];
        if($lastCol[$i] < 0) $lastCol[$i] = 0;
        // Якщо 0 значить прочерк
    }
    for($i=0;$i<$rows;$i++) {
        $basisTable[$i][count($basisTable[$i])] = $lastCol[$i];
    }
    $electedRow = indexOfMinNumber($lastCol, false);
    echo $electedRow;

        debugToConsole(" ");
    debugToConsole("simplex method table:");
    for ($i = 0; $i <= $rows; $i++) {
        $debugRes = "";
        for ($j = 0; $j < $cols; $j++) {
            $debugRes .= ($basisTable[$i][$j] . " ");
        }
        debugToConsole($debugRes);
    }

echo "<li>";

echo "</li>";
}