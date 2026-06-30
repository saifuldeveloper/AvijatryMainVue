<?php

$file = __DIR__ . '/../database/seeders/ShoesTableSeeder.php';
$content = file_get_contents($file);

// Find all matches of 'id' => value
preg_match_all('/\'id\'\s*=>\s*([^,]+)/', $content, $matches);

$nonNumeric = [];
foreach ($matches[1] as $val) {
    $val = trim($val, "'\" ");
    if (!is_numeric($val)) {
        $nonNumeric[] = $val;
    }
}

echo "Total parsed: " . count($matches[0]) . "\n";
echo "Non-numeric IDs: " . count($nonNumeric) . "\n";
print_r(array_slice($nonNumeric, 0, 20));
