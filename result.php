<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <title>Симплекс метод результат</title>
</head>

<?php
function debugToConsole($data)
{
    echo "<script>console.log('$data' + ' ');</script>";
}

?>

<body>

<?php
session_start();
$rows = $_SESSION["functionRows"];
$cols = $_SESSION["functionCols"] + 2;

$table = [$rows][$cols];
$straight = [$cols - 1];

$i = 0;
$j = 0;
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
{
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
// виведення масиву в консоль(перевірка чи не пустий)
}
?>
</body>
</html>