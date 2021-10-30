<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <?php
    //require "symplexFunctions.php";
    require "symplexDoubled.php";
    ?>
    <title>Симплекс метод</title>
</head>

<?php
session_start();
$rows = $_SESSION["functionRows"];
$cols = $_SESSION["functionCols"] + 2;

/*$table = [$rows][$cols];
$resultRow = [$cols - 1];*/

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
// <= - -1
    for ($i = 0; $i < $rows; $i++) {
        $debugRes = "";
        for ($j = 0; $j < $cols; $j++) {
            $debugRes .= ($table[$i][$j] . " ");
        }

        debugToConsole($debugRes);
    }
    debugToConsole("resultRow");
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
            <h1>#1 Умова </h1>
            <p>
            <?php
                buildFunction($cols, $resultRow);
                buildFunctionsTable($rows,$cols,$table); ?>
            </p>
            <br>
        </li>
        <li>
            <?php $isMinimized = false;?>
            <h1>#2 Позбуваємось негативних чисел в правій частині <?php if($resultRow[$cols - 2] == "min") {echo "та приводимо до максимуму"; $isMinimized=true;}?></h1>
            <p style="font-size: 20px">
                <?php
                buildFunction($cols, $resultRow);
                buildFunctionsTable($rows,$cols,$table); ?>
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

            <br>
        </li>
        <li>
            <h1>#3 Визначаємо метод</h1>
            <?php
            $equalExists = false;
            for($i=0;$i<$rows-1;$i++){
                if($table[$i][count($table[$i])-2] == 0){
                    echo "<table class='table table-warning'><tr><td><h5>Я не вмію вирішувати метод штучного базису (можливо зможу пізніше)</h5></tr></td></table>";
                    $equalExists = true;
                    break;
                }
            }
            if(!$equalExists)
            {
                $dealWithCasual = true;
                    for($i = 0; $i < $rows - 1; $i++)
                    {
                        if($table[$i][count($table[$i])-2] != $table[$i+1][count($table[$i])-2]){
                            $dealWithCasual = false;
                            break;
                        }
                    }
                    echo "<table class='table table-success'><tr><td><h5>Вирішуємо за допомогою";
                    echo (($dealWithCasual)? " звичайного" : " двоїстого");
                    echo " симплекс-методу </h5></tr></td></table>";
            }
            ?>

            <br>
        </li>
        <?php
            if(!$equalExists){
                if($dealWithCasual) dealWithCasual($rows, $cols, $table, $resultRow);
                else dealWithDoubled($table, $resultRow);
            }

        ?>

    </ol>

</div>

</body>
</html>