<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <?php require "functions.php"?>
    <title>Симплекс метод результат</title>
</head>

<body>

<?php
session_start();
$rows = $_SESSION["functionRows"];
$cols = $_SESSION["functionCols"] + 2;

$table = [$rows][$cols];
$straight = [$cols - 2];

$i = 0;
$j = 0;
{
    foreach ($_GET as $item) {
        if ($i <= $rows - 1) $table[$i][$j] = $item;
        else {
            $straight[$j] = $item;
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
    foreach ($straight as $item) {
        $debugRes .= ($item . ' ');
    }
    debugToConsole($debugRes);
}// виведення масиву в консоль(перевірка чи не пустий)
?>
<br>
<div class="container">
    <ol style="list-style: none;">
        <li>
            <h1>#1 Умова</h1>
            <p style="font-size: 20px">
                <b><?php echo (($straight[$cols - 2] == "min") ? "мінімізувати" : "максимізувати") . ':'; ?></b>
                <?php
                if ($straight[0] == 1) echo "x<sub>1</sub>";
                elseif ($straight[0] == -1) echo "-x<sub>1</sub>";
                else echo $straight[0] . "x<sub>1</sub>";

                for ($i = 1; $i <= count($straight) - 2; $i++) {
                    if ($straight[$i] == 1) echo " +x<sub>" . ($i + 1) . "</sub>";
                    elseif ($straight[$i] == -1) echo " -x<sub>" . ($i + 1) . "</sub>";
                    elseif ($straight[$i] < 0) echo ' ' . $straight[$i] . "x<sub>" . ($i + 1) . "</sub>";
                    else echo " +" . $straight[$i] . "x<sub>" . ($i + 1) . "</sub>";
                }
                ?>
            </p>

            <!--            МОжна запихнути в функцію разом з таблицею з рядка №135-->
            <table>
                <tbody>
                <?php for ($i = 0; $i < $rows; $i++): ?>
                    <tr>
                        <?php for ($j = 0; $j < $cols - 2; $j++): ?>
                            <td class="sign">
                                <?php
                                if ($table[$i][$j] < 0) echo '-';
                                elseif ($j != 0) echo "+";
                                else echo " " ?>
                            </td>
                            <td class="coef">
                                <?php $coef = $table[$i][$j] * (($table[$i][$j] < 0) ? (-1) : 1);
                                $isZero = false;
                                if ($coef == 1) echo " ";
                                elseif ($coef == 0) {
                                    $isZero = true;
                                    echo " ";
                                } else echo $coef;
                                ?>
                            </td>
                            <td class="index">
                                <?php if (!$isZero): ?>
                                    x<sub><?php echo $j + 1; ?></sub>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td class="equal">
                            <?php echo returnSign($table[$i][count($table[$i]) - 2]); ?>
                        </td>
                        <td class="equal result">
                            <?php echo $table[$i][count($table[$i]) - 1] ?>
                        </td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
            <br>
        </li>
        <li>
            <?php $isMinimized = false;?>
            <h1>#2 Позбуваємось негативних чисел в правій частині <?php if($straight[$cols - 2] == "min") echo "та приводимо до максимуму"; $isMinimized=true;?></h1>
<!--                Зробити домноження на -1 крім зміна min на max-->

            <p style="font-size: 20px">
                <b><?php
                    if($isMinimized){
                        for($i=0;$i<count($straight)-2;$i++)
                        {
                            $straight[$i]*=-1;
                        }
                        $straight[$cols-2] = "max";
                    }
                    echo (($straight[$cols - 2] == "min") ? "мінімізувати" : "максимізувати") . ':'; ?></b>
                <?php
                if ($straight[0] == 1) echo "x<sub>1</sub>";
                elseif ($straight[0] == -1) echo "-x<sub>1</sub>";
                else echo $straight[0] . "x<sub>1</sub>";

                for ($i = 1; $i <= count($straight) - 2; $i++) {
                    if ($straight[$i] == 1) echo " +x<sub>" . ($i + 1) . "</sub>";
                    elseif ($straight[$i] == -1) echo " -x<sub>" . ($i + 1) . "</sub>";
                    elseif ($straight[$i] < 0) echo ' ' . $straight[$i] . "x<sub>" . ($i + 1) . "</sub>";
                    else echo " +" . $straight[$i] . "x<sub>" . ($i + 1) . "</sub>";
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

<!--            Запихнути в функцію-->
            <table>
                <tbody>
                <?php for ($i = 0; $i < $rows; $i++): ?>
                    <tr>
                        <?php for ($j = 0; $j < $cols - 2; $j++): ?>
                            <td class="sign">
                                <?php
                                if ($table[$i][$j] < 0) echo '-';
                                elseif ($j != 0) echo "+";
                                else echo " " ?>
                            </td>
                            <td class="coef">
                                <?php $coef = $table[$i][$j] * (($table[$i][$j] < 0) ? (-1) : 1);
                                $isZero = false;
                                if ($coef == 1) echo " ";
                                elseif ($coef == 0) {
                                    $isZero = true;
                                    echo " ";
                                } else echo $coef;
                                ?>
                            </td>
                            <td class="index">
                                <?php if (!$isZero): ?>
                                    x<sub><?php echo $j + 1; ?></sub>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td class="equal">
                            <?php echo returnSign($table[$i][count($table[$i]) - 2]); ?>
                        </td>
                        <td class="equal result">
                            <?php echo $table[$i][count($table[$i]) - 1] ?>
                        </td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
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
            $basisTableCols = $cols+$rows-1;
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

            ?>
            <!--            Запихнути в функцію-->
            <table>
                <tbody>
                <?php for ($i = 0; $i < $rows; $i++): ?>
                    <tr>
                        <?php for ($j = 0; $j < $basisTableCols - 1; $j++): ?>
                            <td class="sign">
                                <?php
                                if ($basisTable[$i][$j] < 0) echo '-';
                                elseif ($j != 0) echo "+";
                                else echo " " ?>
                            </td>
                            <td class="coef">
                                <?php $coef = $basisTable[$i][$j] * (($basisTable[$i][$j] < 0) ? (-1) : 1);
                                $isZero = false;
                                if ($coef == 1) echo " ";
                                elseif ($coef == 0) {
                                    $isZero = true;
                                    echo " ";
                                } else echo $coef;
                                ?>
                            </td>
                            <td class="index">
                                <?php if (!$isZero): ?>
                                    x<sub><?php echo $j + 1; ?></sub>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                        <td class="equal">=</td>
                        <td class="equal result">
                            <?php echo $basisTable[$i][count($basisTable[$i]) - 1] ?>
                        </td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>

        </li>

        <?php endif;?>
    </ol>
</div>

</body>
</html>