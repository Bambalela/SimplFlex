<?php
require "symplexFunctions.php";

function doubledSimplexMethodMain($table, $resultRow){

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
        doubledSimplexMethodMain($table, $resultRow);
    echo "</li>";
}
