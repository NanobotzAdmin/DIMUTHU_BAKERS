<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;
try {
    $count = DB::table('ad_cubusiness_has_invoice')->count();
    echo "INVOICE_COUNT: $count\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
