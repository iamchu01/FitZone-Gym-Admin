<?php
header('Content-Type: application/json');

$muscle_groups = [
    "Chest" => ["img" => "assets/img/chest.svg", "file" => "chest-exercises.php"],
    "Shoulders" => ["img" => "assets/img/shoulder.svg", "file" => "shoulder-exercises.php"],
    "Triceps" => ["img" => "assets/img/triceps.svg", "file" => "triceps-exercises.php"],
    "Biceps" => ["img" => "assets/img/biceps.svg", "file" => "biceps-exercises.php"],
    "Back" => ["img" => "assets/img/back.svg", "file" => "back-exercises.php"],
    "Quads" => ["img" => "assets/img/quads.svg", "file" => "quads-exercises.php"],
    "Calves" => ["img" => "assets/img/calves.svg", "file" => "calves-exercises.php"],
    "Abs" => ["img" => "assets/img/abs.svg", "file" => "abs-exercises.php"],
    "Hamstrings" => ["img" => "assets/img/hamstrings.svg", "file" => "hamstrings-exercises.php"],
    "Forearms" => ["img" => "assets/img/forearms.svg", "file" => "forearm-exercises.php"],
    "Glutes" => ["img" => "assets/img/glutes.svg", "file" => "glutes-exercises.php"],
    "Abductor" => ["img" => "assets/img/abductor.svg", "file" => "abductor-exercises.php"],
];

echo json_encode(array_keys($muscle_groups)); // Return just the muscle group names
?>
