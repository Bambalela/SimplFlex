<?php

function debugToConsole($data)
{
    echo "<script>console.log('$data' + ' ');</script>";
}

function returnSign($number): string
{
    return match ($number) {
        -1 => '≤',
        0 => '=',
        1 => '≥',
        default => ' ',
    };
}

function indexOfMinNumber($array, bool $isCol): int
{
    $indexOfMin = -1;
    $minNumber = 0;
    for ($i = 0; $i < count($array); $i++) {
        if ($isCol) {
            if ($array[$i] < $minNumber && $array[$i] < 0) {
                $minNumber = $array[$i];
                $indexOfMin = $i;
            }
        } else {
            if ($array[$i] != 0) {
                $minNumber = $array[$i];
                $indexOfMin = $i;
            }
        }

    }
    return $indexOfMin;
}

function printSimplexTableOnes($table, $electedCol, $electedRow, $resultRow, $basis)
{
    echo "<br><table class='table table-bordered'>";
        echo "<thead>";
            echo "<tr>";
            echo "<th scope='col' class='text-end' style='width: 70px'>Базис</th>";
            echo "<th scope='col' class='text-center'>C<sub>б</sub></th>";
            echo "<th scope='col' class='text-center'>A<sub>0</sub></th>";
            for ($i = 1; $i < count($table[0]) - 1; $i++) {
                if($electedCol == $i) echo "<th scope='col' class='text-center table-warning'>"; else
                echo "<th scope='col' class='text-center'>";
                echo "x<sub>" . $i . "</sub></th>";
            }
            echo "<th scope='col' class='text-center'>Ø</th>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
            for ($i = 0; $i < count($table)-1; $i++) {
                echo (($electedRow == $i+1)?  "<tr class='table-warning text-center' >" : "<tr class='text-center'>");
                // Виведення Базису
                echo "<th scope='row' class='text-center'>x<sub>" . $basis[$i] . "</sub></th>";
                // Виведення C базисного
                echo "<td class='text-center'>";
                if($basis[$i] > count($table)-1) echo "0"; else
                    echo $resultRow[$i];
                echo "</td>";
                //Виведеення решти таблиці

                for($j=0;$j<count($table[$i]);$j++)
                {
                    echo (($electedCol == $j)? "<td class='table-warning'>" : "<td>");
                        echo $table[$i][$j];
                    echo "</td>";
                }
                echo "</tr>";
            }
        echo "</tbody>";
        echo "<tfoot class='text-center'>";
            echo "<th scope='row' colspan='2' class='text-center'>ΔZ</th>";
            for($i=0;$i<count($table[count($table)-1]);$i++)
            {
                echo (($electedCol == $i)? "<td class='table-warning'>" : "<td>");
                echo $table[count($table)-1][$i];
                echo "</td>";
            }
        echo "</tfoot>";
    echo "</$table>";
}

function simplexMethodMain($table, $resultRow, $cols, $rows)
{
    $basisTable = [$rows + 1][$cols + 1];
    $electedRow = -1;
    $electedCol = -1;

    for ($i = 0; $i < $rows; $i++) {
        $basisTable[$i][0] = $table[$i][count($table[$i]) - 1];
    }
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 1; $j <= $cols - 1; $j++) $basisTable[$i][$j] = $table[$i][$j - 1];
    }
    // Заповнення рядку дельта Z
    for ($i = 0; $i < $cols; $i++) {
        $basisTable[$rows][$i] = 0;
    }
    for ($i = 1; $i < count($resultRow); $i++) {
        $basisTable[$rows][$i] = $resultRow[$i - 1] * -1;
    }
    // Заповння рядку О з косою рискою
    $electedCol = indexOfMinNumber($basisTable[$rows], true);
    $minColArr = $lastCol = [$rows];
    for ($i = 0; $i <= $rows; $i++) $minColArr[$i] = $basisTable[$i][$electedCol];
    for ($i = 0; $i < $rows; $i++) {
        $lastCol[$i] = $table[$i][count($table[$i]) - 1] / $minColArr[$i];
        if ($lastCol[$i] < 0) $lastCol[$i] = 0;
        // Якщо 0 значить прочерк
    }
    for ($i = 0; $i < $rows; $i++) {
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
    $basisIndexes = [$rows];
    for ($i = 0; $i < $rows; $i++) {
        $basisIndexes[$i] = $rows + $i + 1;
    }

    echo "<li>";
    printSimplexTableOnes($basisTable, $electedCol, $electedRow, $resultRow, $basisIndexes);
    echo "</li>";
}