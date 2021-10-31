<?php
require "symplexFunctions.php";

function testDebugMatrix($table){
    debugToConsole("");
    debugToConsole("tested table:");
    for ($i=0;$i<count($table); $i++) {
        $debugRes = "";
        for ($j=0;$j<count($table[$i]);$j++) {
            $debugRes .= ($table[$i][$j] . " ");
        }
        debugToConsole($debugRes);
    }
}

function doubledSimplexMethodMain($table, $resultRow){
    $rows = count($table);
    $cols = count($table[0]);

    for($i = 0;$i < $rows; $i++)
    {
        $simplexTable[$i][0] = $table[$i][$cols-1];
        for($j = 1;$j < $cols - 1; $j++)
        {
            $simplexTable[$i][$j] = $table[$i][$j - 1];
        }
    }

    // m + 1 рядок заповненння
    $simplexTable[$rows][0] = 0;
    for($i = 0;$i < count($resultRow) - 1; $i++)
    {
        $simplexTable[$rows][$i + 1] = -$resultRow[$i];
    }
    for($i = count($resultRow);$i < $cols; $i++)
    {
        $simplexTable[$rows][$i] = 0;
    }

    // О з перекресленням заповнення
    for($i = 0;$i < $rows; $i++) $tempArr[$i] = $simplexTable[$i][0];
    $electedNumber = min($tempArr);
    for($i = 0;$i < $rows; $i++) if($tempArr[$i] == $electedNumber){ $electedRow = $i; break; }
    debugToConsole("elected row " . $electedRow);

    for($i = 1; $i < $cols - 1; $i++){
        try{
            if($simplexTable[$rows][$i] == 0) throw new Exception;
        $ORow[$i - 1] = -$simplexTable[$rows][$i]/$simplexTable[$electedRow][$i];
            } catch (Exception $e){
            $ORow[$i - 1] = 0;
        }
    }
   for($i = 0; $i < count($ORow);$i++){
       debugToConsole($ORow[$i]);
   }

}

function dealWithDoubled($table, $resultRow){
    echo "<li>";
        echo "<h1>#4 змінюємо всі знаки на ≤</h1>";
        $rows = count($table);
        $cols = count($table[0]);
        for($i = 0; $i < count($table); $i++ )
        {
            if($table[$i][$cols-2] == 1) {
                for($j = 0; $j < $cols; $j++){
                    $table[$i][$j] *= -1;
                }
            }
        }
        buildFunction($cols, $resultRow);
        buildFunctionsTable($rows, $cols, $table);
    echo "</li> <br>";

    echo "<li>";
    echo "<h1>#5 додаємо базисні змінні</h1>";
        $basisTable = makeBasisTable($rows, $cols, $table);
        buildFunctionsTable($rows, $cols + $rows, $basisTable);

    echo "<br><h1>#6 Вирішуємо за допомогою двоїстої симплекс-таблиці</h1>";
        doubledSimplexMethodMain($basisTable, $resultRow);
    echo "</li>";
}
