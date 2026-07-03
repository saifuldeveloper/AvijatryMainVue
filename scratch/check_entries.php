<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ic = App\Models\InventoryCheck::find(108);
if ($ic) {
    echo "Total: " . $ic->inventoryCheckDetails()->count() . "\n";
    echo "Partial: " . $ic->partialMatchEntries()->count() . "\n";
    echo "Extra: " . $ic->extraMatchEntries()->count() . "\n";
    echo "Full: " . $ic->fullMatchEntries()->count() . "\n";
} else {
    echo "Not found\n";
}
