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

function indexOfMinNumber($array, bool $isCol ): int
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

function printSimplexTableOnes($table, $electedCol, $electedRow)
{
        echo "<table class='table'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th scope='col'>Базис</th>";
                    echo "<th scope='col'>C<sub>б</sub></th>";
                    echo "<th scope='col'>A<sub>0</sub></th>";
                    for($i=1;$i<count($table[$i]); $i++)
                    {
                        echo "<th scope='col'>x<sub>" . $i ."</sub></th>";
                    }
                echo "</tr>";
            echo "</thead>";
        echo "</$table>";
}

function simplexMethod($table, $resultRow, $cols, $rows){
    $basisTable = [$rows+1][$cols+1];
    $electedRow = -1;
    $electedCol = -1;

    for($i=0;$i<$rows;$i++) {$basisTable[$i][0] = $table[$i][count($table[$i])-1];}
    for($i=0;$i<$rows;$i++)
    {
        for($j=1;$j<=$cols-1;$j++) $basisTable[$i][$j] = $table[$i][$j-1];
    }
    // Заповнення рядку дельта Z
    for($i=0;$i<$cols;$i++){$basisTable[$rows][$i] = 0;}
    for($i=1;$i<count($resultRow);$i++){$basisTable[$rows][$i] = $resultRow[$i-1] * -1;}
    // Заповння рядку О з косою рискою
    $electedCol = indexOfMinNumber($basisTable[$rows], true);
    $minColArr = $lastCol = [$rows];
    for($i=0;$i<=$rows;$i++) $minColArr[$i] = $basisTable[$i][$electedCol];
    for($i=0;$i<$rows;$i++) {
        $lastCol[$i] = $table[$i][count($table[$i])-1]/$minColArr[$i];
        if($lastCol[$i] < 0) $lastCol[$i] = 0;
        // Якщо 0 значить прочерк
    }
    for($i=0;$i<$rows;$i++) {
        $basisTable[$i][count($basisTable[$i])] = $lastCol[$i];
    }
    $electedRow = indexOfMinNumber($lastCol, false);

        debugToConsole(" ");
        debugToConsole("simplex method table:");
        for ($i = 0; $i <= $rows; $i++) {
            $debugRes = "";
            for ($j = 0; $j <= $cols; $j++) {
                $debugRes .= ($basisTable[$i][$j] . " ");
            }
            debugToConsole($debugRes);
        }

echo "<li>";

echo "</li>";
}