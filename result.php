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
            <h1>#2 Позбуваємось негативних чисел в правій частині</h1>
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
            <h1>#3 Додаємо базисні змінні</h1>

        </li>
    </ol>
</div>

</body>
</html>