<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Shoe;
use App\Repositories\ShoeRepository;

$shoe = Shoe::first();
if ($shoe) {
    $repo = new ShoeRepository();
    $details = $repo->getShoeDetails($shoe);
    echo json_encode($details->purchaseEntries->toArray(), JSON_PRETTY_PRINT);
} else {
    echo "No shoes found.";
}
