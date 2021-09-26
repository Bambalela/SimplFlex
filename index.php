<!doctype html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <title>Симплекс метод</title>
</head>
<body>
<main><br>

    <?php
    $rows = $_GET["rows-cnt"];
    $cols = $_GET["cols-cnt"];
    session_start();
    $_SESSION["functionRows"] = $rows;
    $_SESSION["functionCols"] = $cols;
    if (!is_numeric($rows) || !is_numeric($cols) || $rows == null || $cols == null): ?>
        <div class="container">
            <form action="index.php" method="get">
                <div class="input-group mb-3">
                    <span class="input-group-text">Кількість рядків (обмежень):</span>
                    <input name="rows-cnt" type="text" class="form-control" aria-label="Function parameters input">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Кількість змінних:</span>
                    <input name="cols-cnt" type="text" class="form-control" aria-label="Function parameters input">
                </div>
                <button type="submit" class="btn btn-primary mb-3">Обчислити</button>
            </form>
        </div>
    <?php else: ?>
        <div class="container">
            <form action="result.php" method="get">
                <table class="table table-success table-bordered table-striped">
                    <thead class="table-success">
                    <?php for ($i = 1; $i <= $cols; $i++): ?>
                        <td>x<sub><?php echo $i ?></sub></td>
                    <?php endfor; ?>
                    <td style="width: 60px"></td>
                    <td>B</td>
                    </thead>
                    <?php for ($i = 1; $i <= $rows; $i++): ?>
                        <tr>
                            <?php for ($j = 1; $j <= $cols; $j++): ?>
                                <td><input name="row-<?php echo $i ?>-col-<?php echo $j ?>" class="form-control"
                                           style="width:100%" type="text" value="0"
                                           aria-label="Function parameters input"></td>
                            <?php endfor; ?>
                            <td><select name="sign-<?php echo $i ?>" class="form-control" size="1"
                                        aria-label="Function parameters input">
                                    <option value="1">≥</option>
                                    <option value="0">=</option>
                                    <option value="-1" selected="">≤</option>
                                </select></td>
                            <td><input name="row-<?php echo $i ?>-B" class="form-control" style="width:80px" type="text"
                                       value="0"
                                       aria-label="Function parameters input"></td>
                        </tr>
                    <?php endfor; ?>
                </table>
                <table>
                    <tbody>
                    <tr>
                        <?php for ($i = 1; $i <= $cols; $i++): ?>
                            <td>x<sub><?php echo $i ?></sub></td>
                        <?php endfor; ?>
                        <td>напрямок</td>
                    </tr>
                    <tr>
                        <?php for ($j = 1; $j <= $cols; $j++): ?>
                            <td><input name="fx-<?php echo $j ?>" style="width:100%" class="form-control" type="text"
                                       value="0" size="4" aria-label="Function parameters input"></td>
                        <?php endfor; ?>
                        <td><select name="vid" class="form-control" size="1" aria-label="Function parameters input">
                                <option value="min">min</option>
                                <option value="max">max</option>
                            </select></td>
                    </tr>
                    </tbody>
                </table>

                <h5>x<sub> <?php echo 1; for ($i = 2; $i <= $cols; $i++) echo ','.$i?></sub>≥ 0</h5>
                <button type="submit" class="btn btn-success mb-3">Обчислити</button>
            </form>
        </div>

    <?php endif ?>

</main>
</body>
</html>