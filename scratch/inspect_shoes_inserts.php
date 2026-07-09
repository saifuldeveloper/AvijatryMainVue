<?php

$file = __DIR__ . '/../database/seeders/ShoesTableSeeder.php';
$lines = file($file);

foreach ($lines as $index => $line) {
    if (str_contains($line, "->insert")) {
        echo "Line " . ($index + 1) . ": " . trim($line) . "\n";
    }
}
