<?php
require "symplexFunctions.php";

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
}
