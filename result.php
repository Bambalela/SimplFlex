<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <?php require "symplexFunctions.php"?>
    <title>Симплекс метод</title>
</head>

<?php
session_start();
$rows = $_SESSION["functionRows"];
$cols = $_SESSION["functionCols"] + 2;

$table = [$rows][$cols];
$resultRow = [$cols - 2];

$i = 0;
$j = 0;
{
    foreach ($_GET as $item) {
        if ($i <= $rows - 1) $table[$i][$j] = $item;
        else {
            $resultRow[$j] = $item;
        }
        $j++;
        if ($j > $cols - 1) {
            $j = 0;
            $i++;
        }
    }
//  = - 0
// >= - 1
// <= - 2
    for ($i = 0; $i < $rows; $i++) {
        $debugRes = "";
        for ($j = 0; $j < $cols; $j++) {
            $debugRes .= ($table[$i][$j] . " ");
        }

        debugToConsole($debugRes);
    }
    $debugRes = "";
    foreach ($resultRow as $item) {
        $debugRes .= ($item . ' ');
    }
    debugToConsole($debugRes);
}// виведення масиву в консоль(перевірка чи не пустий)
?>

<body>
<br>
<div class="container" style="margin-bottom: 50%">
    <ol style="list-style: none;">
        <li>
            <h1>#1 Умова</h1>
            <p style="font-size: 20px">
                <b><?php echo (($resultRow[$cols - 2] == "min") ? "мінімізувати" : "максимізувати") . ':'; ?></b>
                <?php
                if ($resultRow[0] == 1) echo "x<sub>1</sub>";
                elseif ($resultRow[0] == -1) echo "-x<sub>1</sub>";
                else echo $resultRow[0] . "x<sub>1</sub>";

                for ($i = 1; $i <= count($resultRow) - 2; $i++) {
                    if ($resultRow[$i] == 1) echo " +x<sub>" . ($i + 1) . "</sub>";
                    elseif ($resultRow[$i] == -1) echo " -x<sub>" . ($i + 1) . "</sub>";
                    elseif ($resultRow[$i] < 0) echo ' ' . $resultRow[$i] . "x<sub>" . ($i + 1) . "</sub>";
                    else echo " +" . $resultRow[$i] . "x<sub>" . ($i + 1) . "</sub>";
                }
                ?>
            </p>

            <?php buildFunctionsTable($rows,$cols,$table); ?>

            <br>
        </li>
        <li>
            <?php $isMinimized = false;?>
            <h1>#2 Позбуваємось негативних чисел в правій частині <?php if($resultRow[$cols - 2] == "min") {echo "та приводимо до максимуму"; $isMinimized=true;}?></h1>
            <p style="font-size: 20px">
                <b><?php
                    if($isMinimized){
                        for($i=0;$i<count($resultRow)-1;$i++)
                        {
                            $resultRow[$i]*=-1;
                        }
                        $resultRow[$cols-2] = "max";
                    }
                    echo (($resultRow[$cols - 2] == "min") ? "мінімізувати" : "максимізувати") . ':'; ?></b>
                <?php
                if ($resultRow[0] == 1) echo "x<sub>1</sub>";
                elseif ($resultRow[0] == -1) echo "-x<sub>1</sub>";
                else echo $resultRow[0] . "x<sub>1</sub>";

                for ($i = 1; $i <= count($resultRow) - 2; $i++) {
                    if ($resultRow[$i] == 1) echo " +x<sub>" . ($i + 1) . "</sub>";
                    elseif ($resultRow[$i] == -1) echo " -x<sub>" . ($i + 1) . "</sub>";
                    elseif ($resultRow[$i] < 0) echo ' ' . $resultRow[$i] . "x<sub>" . ($i + 1) . "</sub>";
                    else echo " +" . $resultRow[$i] . "x<sub>" . ($i + 1) . "</sub>";
                }
                ?>
            </p>

            <?php
            $negativeRows = [$rows];
            $negativeCount = 0;
            for ($i = 0; $i < $rows; $i++) {
                if ($table[$i][count($table[$i]) - 1] < 0) {
                    $negativeRows[$negativeCount] = $i;
                    $negativeCount++;
                }
            }
            foreach ($negativeRows as $row) {
                for ($i = 0; $i < $cols; $i++) {
                    $table[$row][$i] *= -1;
                }
            }
            ?>

            <?php buildFunctionsTable($rows,$cols,$table); ?>

            <br>
        </li>
        <li>
            <h1>#3 Визначаємо метод</h1>
            <?php
            $allAreTheSame = true;
            for($i=0;$i<$rows-1;$i++){
                if($table[$i][count($table[$i])-2] != $table[$i+1][count($table[$i])-2]){
                    $allAreTheSame = false;
                    break;
                }
            } ?>
            <br>

            <?php if($allAreTheSame):?>
                <table class="table table-success"><tr><td><h5>Вирішуємо за допомогою звичайного симплекс методу</h5></tr></td></table>
            <?php else:?>
                <table class="table table-warning"><tr><td><h5>Я не вмію вирішувати метод штучного базису (можливо зможу пізніше)</h5></tr></td></table>
            <?php endif;?>
            <br>
        </li>
        <?php if($allAreTheSame): ?>
        <li>
            <h1>#4 Додаємо базисні змінні</h1>
            <?php
            $basisTable = makeBasisTable($rows, $cols, $table);
            buildFunctionsTable($rows, $cols + $rows, $basisTable);
            ?>
        </li> <br>
        <h1>#5 Вирішуємо за допомогою симплекс-таблиці</h1>
        <?php simplexMethodMain($basisTable, $resultRow, $cols + $rows, $rows); ?>

        <?php endif;?>
    </ol>
</div>

</body>
</html>