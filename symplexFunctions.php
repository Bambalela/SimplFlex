<?php

function buildFunctionsTable($rows,$cols,$table){
    echo "<table>";
                echo "<tbody>";
                for ($i = 0; $i < $rows; $i++){
                    echo "<tr>";
                        for ($j = 0; $j < $cols - 2; $j++){
                            echo "<td class='sign'>";
                                if ($table[$i][$j] < 0) echo '-';
                                elseif ($j != 0) echo "+";
                                else echo " ";
                            echo "</td>";
                            echo "<td class='coef'>";
                                $coef = $table[$i][$j] * (($table[$i][$j] < 0) ? (-1) : 1);
                                $isZero = false;
                                if ($coef == 1) echo " ";
                                elseif ($coef == 0) {
                                    $isZero = true;
                                    echo " ";
                                } else echo $coef;
                            echo "</td>";
                            echo "<td class='index'>";
                                if (!$isZero){
                                    echo "x<sub>" . ($j + 1) . "</sub>";
                                }
                            echo "</td>";
                        }
                        echo "<td class='equal'>";
                            echo returnSign($table[$i][count($table[$i]) - 2]);
                                debugToConsole(($table[$i][count($table[$i]) - 2]));

                        echo "</td>";
                        echo "<td class='equal result'>";
                            echo $table[$i][count($table[$i]) - 1];
                        echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
            echo "</table>";
}

function buildFunction($cols, $resultRow){
    echo "<p style='font-size: 20px'>";
            echo "<b>" . (($resultRow[$cols - 2] == "min") ? "Мінімізувати" : "Максимізувати") . ': ' ."</b>";
                if ($resultRow[0] == 1) echo "x<sub>1</sub>";
                elseif ($resultRow[0] == -1) echo "-x<sub>1</sub>";
                else echo $resultRow[0] . "x<sub>1</sub>";

        for ($i = 1; $i <= count($resultRow) - 2; $i++) {
            if ($resultRow[$i] == 1) echo " +x<sub>" . ($i + 1) . "</sub>";
            elseif ($resultRow[$i] == -1) echo " -x<sub>" . ($i + 1) . "</sub>";
            elseif ($resultRow[$i] < 0) echo ' ' . $resultRow[$i] . "x<sub>" . ($i + 1) . "</sub>";
            else echo " +" . $resultRow[$i] . "x<sub>" . ($i + 1) . "</sub>";
        }
    echo "</p>";
}

function makeBasisTable($rows, $cols, $table){
    $basisTableCols = $cols+$rows;
    $basisTable = [$rows][$basisTableCols];
    for($i=0;$i<$rows;$i++)
    {
        for($j=0;$j<$cols-2;$j++)
        {
            $basisTable[$i][$j] = $table[$i][$j];
        }
    }
    $k=0;
    for($i=0;$i<$rows;$i++)
    {
        $m=0;
        for($j=$cols-2;$j<$basisTableCols-1;$j++)
        {
            if($m==$k) $basisTable[$i][$j] = 1; else
                $basisTable[$i][$j] = 0;
            $m++;
        }
        $k++;
    }
    for($i=0;$i<$rows;$i++){
        $basisTable[$i][$basisTableCols-1]=$table[$i][$cols-1];
    }
    debugToConsole("");
    for ($i = 0; $i < $rows; $i++) {
        $debugRes = "";
        for ($j = 0; $j < $basisTableCols; $j++) {
            $debugRes .= ($basisTable[$i][$j] . " ");
        }
        debugToConsole($debugRes);
    }

    return $basisTable;
}
function debugToConsole($data)
{
    echo "<script>console.log('$data' + ' ');</script>";
}

function returnSign($number): string
{
    switch ($number){
        case -1: return '≤';
        case 0: return '=';
        case 1: return '≥';
        default: return ' ';
    }
}

function indexOfMinNumber($array, bool $isCol): int
{
    $indexOfMin = -1;
    $minNumber = PHP_INT_MAX;
    for ($i = 0; $i < count($array); $i++) {
        if ($isCol) {
            if ($array[$i] < $minNumber && $array[$i] < 0) {
                $minNumber = $array[$i];
                $indexOfMin = $i;
            }
        } else {
            if ($array[$i] > 0 && $array[$i] < $minNumber) {
                $minNumber = $array[$i];
                $indexOfMin = $i;
            }
        }

    }
    return $indexOfMin;
}

function printSimplexTableOnes($table, $electedCol, $electedRow, $resultRow, $basis)
{
    $electedRow++;
    echo "<table class='table table-bordered'>";
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
                echo (($electedRow == $i + 1)?  "<tr class='table-warning text-center' >" : "<tr class='text-center'>");
                // Виведення Базису
                echo "<th scope='row' class='text-center'>x<sub>" . $basis[$i] . "</sub></th>";
                // Виведення C базисного
                echo "<td class='text-center'>";
                if($basis[$i] > count($table)-1) echo "0"; else
                    echo $resultRow[$basis[$i]-1];
                echo "</td>";
                //Виведення решти таблиці

                for($j=0;$j<count($table[$i])-1;$j++)
                {
                    echo (($electedCol == $j)? "<td class='table-warning'>" : "<td>");
                        echo $table[$i][$j];
                    echo "</td>";
                }
                echo "<td>" . (($table[$i][count($table[$i])-1] <= 0)? "-" : $table[$i][count($table[$i])-1]) . "</td>";

                echo "</tr>";
            }
        echo "</tbody>";
        echo "<tfoot class='text-center'>";
            echo "<th scope='row' colspan='2' class='text-center'>ΔZ</th>";
            for($i=0;$i<count($table[count($table)-1])-1;$i++)
            {
                echo (($electedCol == $i)? "<td class='table-warning'>" : "<td>");
                echo $table[count($table)-1][$i];
                echo "</td>";
            }
        echo "</tfoot>";
    echo "</table>";
}

function SimplexMethod($table1, $electedCol, $electedRow, $resultRow, $basis)
{
    $lastCol = [];
    $continue = true;
    for($iteration = 1; $continue; $iteration++) {
        echo "<li>";
        echo "<br><h4 style='margin-left: 50px'>\tІтерація #". $iteration ."</h4>";
        printSimplexTableOnes($table1, $electedCol, $electedRow, $resultRow, $basis);
        if($electedCol == $electedRow && $electedRow == -1) {
            $continue = false;
            echo "</li>";
            break;
        }
        $table2 = $table1;

        for($i=0;$i<count($table1); $i++){
            for($j=0;$j<count($table1[0]);$j++)
            {
                if($i == $electedRow) $table2[$i][$j] = round($table1[$i][$j] /
                    ($table1[$electedRow][$electedCol]),2); else
                $table2[$i][$j] = round(($table1[$electedRow][$electedCol]*$table1[$i][$j] -
                    $table1[$i][$electedCol] * $table1[$electedRow][$j])
                    / $table1[$electedRow][$electedCol], 2);
            }
        }
        $basis[$electedRow] = $electedCol;
        $electedCol = indexOfMinNumber($table2[count($table2)-1], true);

        for($i = 0; $i < count($table2)-1;$i++) {
            try {
                $lastCol[$i] = round($table2[$i][0] / $table2[$i][$electedCol], 2);
            } catch(DivisionByZeroError $e) {
                $lastCol[$i] = 0;
            }
        }
        for($i = 0; $i < count($table2)-1;$i++) $table2[$i][count($table2[$i])-1] = $lastCol[$i];
        debugToConsole("elatedCol = " . $electedCol);
        $test = "";
        for($i = 0; $i < count($table2) - 2;$i++) {
            $test .= $lastCol[$i] . " ";
        }
        debugToConsole("lastCol: " . $test);
        $electedRow = indexOfMinNumber($lastCol, false);
        debugToConsole("elatedRow = " . $electedRow);
        debugToConsole(" ");
        debugToConsole("simplex method table:");
        for ($i=0;$i<count($table1); $i++) {
            $debugRes = "";
            for ($j=0;$j<count($table1[$i]);$j++) {
                $debugRes .= ($table2[$i][$j] . " ");
            }
            debugToConsole($debugRes);
        }
        $table1 = $table2;
        echo "</li>";
    }
    echo "<li>";
        echo "<div class='container' style='background-color: #d3d3d3; border-radius: 10px;'> ";
            echo "<p style='font-size: 20px'>Результат Виконання:       Z<sub>max</sub> = ";
                echo $table2[count($table2) - 1][0];
            echo "</p>";
        echo "</div>";
    echo "</li>";
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
    for ($i = 0; $i <= $cols; $i++) {
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
        try {
            $lastCol[$i] = $table[$i][count($table[$i]) - 1] / $minColArr[$i];
            if ($lastCol[$i] < 0) $lastCol[$i] = 0;
        } catch (DivisionByZeroError $e)
        {
            $lastCol[$i] = 0;
        }
        // Якщо 0 значить прочерк
    }
    for ($i = 0; $i < $rows; $i++) {
        $basisTable[$i][count($basisTable[$i])] = $lastCol[$i];
    }
    $debugRes = "";
    for ($i = 0; $i < $cols; $i++) {
        $debugRes .= ($lastCol[$i] . " ");
    }
    debugToConsole(" ");
    debugToConsole("rez: " . $debugRes);

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
    SimplexMethod($basisTable, $electedCol, $electedRow, $resultRow, $basisIndexes);

}

function dealWithCasual($rows, $cols, $table, $resultRow){
    echo "<li>";
            echo "<h1>#4 Додаємо базисні змінні</h1>";
            $basisTable = makeBasisTable($rows, $cols, $table);
            buildFunctionsTable($rows, $cols + $rows, $basisTable);
        echo "</li> <br>";
        echo "<h1>#5 Вирішуємо за допомогою симплекс-таблиці</h1>";

        simplexMethodMain($basisTable, $resultRow, $cols + $rows, $rows);
    echo "</li>";
}
