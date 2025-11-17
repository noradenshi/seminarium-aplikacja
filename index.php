<!DOCTYPE html>
<html lang="pl">

<?php
session_start();

$tasks = [
    ["1. Decyzyjny problem plecakowy", "BS", "DZ", "SO", "AZ"],
    ["2. Ogólny problem plecakowy", "BS", "DZ", "SO"],
    ["4. Problem doboru załogi statku kosmicznego", "BS", "SO"],
    ["6. Problem odgadywania liczby", "DZ", "MC", "BS", "EX"],
    ["7. Problem planowania produkcji mebli", "SO"],
    ["8. Problem planowania diety dziecka", "SO"],
    ["9. Problem planowania zawartości zestawu paszowego", "SO"],
    ["10. Problem czterech hetmanów", "SO", "GT"],
    ["11. Problem planowania liczebności klas", "BS", "SO"],
    ["12. Problem wysyłania pociągów", "SO"],
    ["13. Problem przydziału maszyn", "BS", "SO"],
    ["14. Problem transportu węgla", "SO"],
    ["15. Problem transportu produktów", "SO"],
    ["16. Problem produkcji samochodów", "BS", "SO"],
    ["17. Problem transportu koni", "SO"],
    ["20. Problem przewidywania liczebności populacji królików", "DZ", "EX", "PD"],
    ["21. Problem przewidywania wzrostu PKB", "EX", "PROGRAM"],
    ["22. Problem przewidywania oprocentowania od lokaty", "EX", "PROGRAM"],
    // ["23. Problem przewidywania przebiegu epidemii", "EX"],
    // ["24. Problem rozmnażania bakterii", "EX"],
    // ["25. Problem łososi i rekinów", "EX"],
    ["28. Problem wydawania reszty", "AZ", "SO"]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['losuj']))
        $_SESSION['losuj'] = true;

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION['losuj'])) {
    $tasksExcel = [];
    $tasksJava = [];
    foreach ($tasks as $task) {
        $name = $task[0];
        for ($j = 1; $j < count($task); $j++) {
            $type = $task[$j];
            if ($type === "EX" || $type === "SO") {
                $tasksExcel[] = $name . "  " . $type;
            } else {
                $tasksJava[] = $name . "  " . $type;
            }
        }
    }
    $_SESSION['tasksExcel'] = $tasksExcel;
    $_SESSION['tasksJava'] = $tasksJava;
    $_SESSION['student'] = 1;
    $_SESSION['records'] = [];
} else {
    unset($_SESSION['losuj']);

    $tasksExcel = $_SESSION['tasksExcel'];
    $tasksJava = $_SESSION['tasksJava'];
    $student = $_SESSION['student'];
    $records = $_SESSION['records'];

    if (!empty($tasksExcel) && !empty($tasksJava)) {
        $i = rand(0, count($tasksExcel) - 1);
        $j = rand(0, count($tasksJava) - 1);

        $excel = array_splice($tasksExcel, $i, 1)[0];
        $java = array_splice($tasksJava, $j, 1)[0];

        $record = "Osoba: $student<br>$java<br>$excel";
        $records[] = $record;
        $student++;

        $_SESSION['tasksExcel'] = $tasksExcel;
        $_SESSION['tasksJava'] = $tasksJava;
        $_SESSION['student'] = $student;
        $_SESSION['records'] = $records;
    }
}
?>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <title>IRZ</title>
    <style>
        .center {
            text-align: center
        }

        div {
            width: 410px;
            height: 209px
        }

        div * {
            display: block;
            width: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial;
            font-size: 10pt
        }

        p {
            padding: 0.8ex 0;
            text-align: center;
            background-color: #f3f3ee;
            border-style: solid;
            border-width: 1px;
            border-color: gray;
            font-weight: bold
        }

        textarea {
            height: 145px;
            resize: none;
            background-color: white;
            color: black;
            border-style: solid;
            border-width: 1px;
            border-top-width: 0;
            border-color: gray;
        }

        button {
            height: 32px;
            margin-top: 2px;
            font-weight: bold
        }
    </style>
</head>

<body>
    <h2 class="center">IRZ</h2>

    <div>
        <?php
        $numberExcel = count($tasksExcel);
        $numberJava = count($tasksJava);
        echo "<p id='head'>Excel: $numberExcel &nbsp;Java: $numberJava</p>";
        ?>

        <form method="post">
            <?php foreach ($records as $r): ?>
                <p class="record"><?= $r ?></p>
            <?php endforeach; ?>

            <button type="submit" name="losuj" <?= (empty($tasksExcel) || empty($tasksJava)) ? 'disabled' : '' ?>>Losuj zestaw</button>
            <button type="submit" name="reset">Reset</button>
        </form>
    </div>

</body>

</html>
