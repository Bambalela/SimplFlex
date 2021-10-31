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

function printDoubledSimplex($electedRow, $electedCol, $ORow, $table, $basisIndex){
    echo "<table class='table table-bordered'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th scope='col' class='text-end' style='width: 70px'>Базис</th>";
                echo "<th scope='col' class='text-center'>C<sub>б</sub></th>";
                echo "<th scope='col' class='text-center'>A</th>";
                for($i = 1; $i < count($table[0]);$i++)
                {
                    echo "<th scope='col' class='text-center "; // Виводимо основні властивості тега
                    if($i == $electedCol) echo "table-warning'"; // Якщо це необхідний стовбець, то додаєм клас таблиця-попередження
                        else echo "'"; // Інакше закриваємо лапки
                    echo ">x<sub>" . $i ."</sub></th>"; // Закінчуємо тег
                }
            echo "</tr>";
        echo "</thead>";
    echo "<tbody>";
        for($i = 0; $i < count($table) - 1; $i++)
        {
            $makeWarning = ($i == $electedRow);

                echo "<th class='text-center "; // Починаємо виводити текст
                if($makeWarning) echo "table-warning"; // Якщо потрібно, робимо клітинку жовтою
                echo "'>"; // Закінчуємо виводити тег
                    echo "x<sub>" . $basisIndex[0][$i] . "</sub>";
                echo "</th>";

                echo "<th class='text-center "; // Починаємо виводити текст
                if($makeWarning) echo "table-warning"; // Якщо потрібно, робимо клітинку жовтою
                echo "'>"; // Закінчуємо виводити тег
                    echo $basisIndex[1][$i];
                echo "</th>";
                for($j = 0; $j < count($table[0]); $j++)
                {
                    echo "<th class='text-center "; // Починаємо виводити текст
                    if($makeWarning || $j == $electedCol) echo "table-warning"; // Якщо потрібно, робимо клітинку жовтою
                    echo "'>"; // Закінчуємо виводити тег
                        echo $table[$i][$j];
                    echo "</th>";
                }
            echo "</tr>";
        }

    echo "</tbody>";
        echo "<tfoot class='text-center'>";
            echo "<tr>";
                echo "<th scope='row' colspan='2' class='text-center'>m + 1</th>";
                for($i = 0; $i < count($table[0]); $i++)
                {
                    echo "<th"; // Починаємо виводити тег
                    if($i == $electedCol) echo " class='table-warning'>"; // Якщо потрібно, добавляємо клас попередження
                    else echo ">"; // Закінчуємо виводити тег
                        echo $table[count($table) - 1][$i];
                    echo "</th>";
                }
            echo "</tr>";

            echo "<tr>";
                echo "<th scope='row' colspan='3' class='text-center'>Ø</th>";
                for($i = 0; $i < count($ORow); $i++)
                {
                    echo "<th"; // Починаємо виводити тег
                    if($i == $electedCol - 1) echo " class='table-warning'>"; // Якщо потрібно, добавляємо клас попередження
                    else echo ">"; // Закінчуємо виводити тег
                    if($ORow[$i] == PHP_INT_MAX) echo '';
                    else echo $ORow[$i];
                    echo "</th>";
                }
            echo "</tr>";
        echo "</tfoot>";
    echo "</table>";
}

function DoubledSimplex($electedCol, $electedRow, $ORow, $table, $resultRow){
    $j = $_SESSION["functionCols"]; // заповнення базисних індексів
    for($i = 0; $i < count($table) - 1; $i++){
        $basisIndexes[0][$i] = $j + $i + 1;
    }
    for($i = 0; $i < count($basisIndexes[0]); $i++){
        $basisIndexes[1][$i] = 0;
    }

    for($iteration = 1; true; $iteration++){
        echo "<li>";
            echo "<br><h4 style='margin-left: 50px'>\tІтерація #". $iteration ."</h4>";
            printDoubledSimplex($electedRow, $electedCol, $ORow, $table, $basisIndexes);

            //if($iteration == 2) break;

            //перевірка на числа < 0
            for($i = 0;$i < count($table) - 1; $i++)
                $ACol[$i] = $table[$i][0];
            $electedNumber = min($ACol);
        debugToConsole($electedNumber);

        if($electedNumber >= 0) break;
            //Ставимо нові значення базису
            $basisIndexes[0][$electedRow] = $electedCol;
            $basisIndexes[1][$electedRow] = (($electedCol - 1 < (count($resultRow) - 1)? $resultRow[$electedCol - 1] : 0));

            $rows = count($table);
            $cols = count($table[0]);
            //Перераховуємо симплекс таблицю
            for($i = 0; $i < $rows; $i++){
                for($j = 0; $j < $cols; $j++){
                    $tempTable[$i][$j] = round(($table[$electedRow][$electedCol] * $table[$i][$j]
                        - $table[$electedRow][$j] * $table[$i][$electedCol])
                        /$table[$electedRow][$electedCol],1);
                }
            }

                //Перераховуємо вибраний рядок
            for($j = 0; $j < $cols; $j++){
                $tempTable[$electedRow][$j] = round($table[$electedRow][$j] / $table[$electedRow][$electedCol],1);
            }

        debugToConsole('');
        for($i = 0; $i < $rows; $i++){
            $test = '';
            for($j = 0; $j < $cols; $j++){
                $test .=  $tempTable[$i][$j] . ' ';
            }
            debugToConsole($test);
        }
        debugToConsole('');


        // Перешуковуємо рядок
            for($i = 0;$i < $rows - 1; $i++) $tempArr[$i] = $tempTable[$i][0];
            $electedNumber = min($tempArr);
            for($i = 0;$i < $rows; $i++) if($tempArr[$i] == $electedNumber){ $electedRow = $i; break; }
            debugToConsole("elected row " . $electedRow);

                // Перераховуємо О з рискою
            for($i = 1; $i < $cols; $i++){
                try{
                    if($tempTable[$rows - 1][$i] == 0) throw new Exception;
                    $ORow[$i - 1] = -1 * round($tempTable[$rows - 1][$i]/$tempTable[$electedRow][$i] ,2);
                } catch (Exception $e){
                    $ORow[$i - 1] = PHP_INT_MAX;
                }
            }
                // Перешукуємо стовбець
            $electedNumber = min($ORow);
            for($i = 0;$i < count($ORow); $i++) if($ORow[$i] == $electedNumber){ $electedCol = $i + 1; break; }
            debugToConsole("elected col ".$electedCol);

        echo "</li>";

        $table = $tempTable;
        //break;
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
        $ORow[$i - 1] = -1 * $simplexTable[$rows][$i]/$simplexTable[$electedRow][$i];
            } catch (Exception $e){
            $ORow[$i - 1] = PHP_INT_MAX;
        }
    }
    $electedNumber = min($ORow);
    for($i = 0;$i < count($ORow); $i++) if($ORow[$i] == $electedNumber){ $electedCol = $i + 1; break; }
    debugToConsole("elected col ".$electedCol);

    DoubledSimplex($electedCol, $electedRow, $ORow, $simplexTable, $resultRow);
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
