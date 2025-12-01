<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// Definicja zadań
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
    ["28. Problem wydawania reszty", "AZ", "SO"]
];

function initializeSession($tasks) {
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
    
    $_SESSION['maxExcel'] = count($tasksExcel);
    $_SESSION['maxJava'] = count($tasksJava);
    
    $_SESSION['student'] = 1;
    $_SESSION['records'] = [];
    $_SESSION['initialized'] = true;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = isset($input['action']) ? $input['action'] : '';

if ($action === 'reset') {
    session_unset();
    initializeSession($tasks);
} 
elseif ($action === 'draw') {
    if (!isset($_SESSION['initialized'])) {
        initializeSession($tasks);
    }

    $tasksExcel = $_SESSION['tasksExcel'];
    $tasksJava = $_SESSION['tasksJava'];
    $student = $_SESSION['student'];
    $records = $_SESSION['records'];

    if (!empty($tasksExcel) && !empty($tasksJava)) {
        $i = rand(0, count($tasksExcel) - 1);
        $j = rand(0, count($tasksJava) - 1);

        $excel = array_splice($tasksExcel, $i, 1)[0];
        $java = array_splice($tasksJava, $j, 1)[0];

        $record = "Osoba: $student\n$java\n$excel\n";
        $records[] = $record;
        $student++;

        $_SESSION['tasksExcel'] = $tasksExcel;
        $_SESSION['tasksJava'] = $tasksJava;
        $_SESSION['student'] = $student;
        $_SESSION['records'] = $records;
    }
} else {
    if (!isset($_SESSION['initialized'])) {
        initializeSession($tasks);
    }
}

echo json_encode([
    'records' => $_SESSION['records'],
    'maxExcel' => $_SESSION['maxExcel'],
    'maxJava' => $_SESSION['maxJava'],
]);
exit;
?>