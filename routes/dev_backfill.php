<?php

use App\Models\StmStock;
use App\Models\StmBranchStock;
use App\Models\StmBarcode;
use Illuminate\Support\Facades\Route;

Route::get('/dev/backfill-qty', function () {
    $updated = 0;
    ini_set('max_execution_time', 300);

    $stocks = StmStock::all();
    foreach ($stocks as $stock) {
        $stock->save(); // Triggers observer/trait
        $updated++;
    }

    $bStocks = StmBranchStock::all();
    foreach ($bStocks as $bs) {
        $bs->save();
        $updated++;
    }

    $barcodes = StmBarcode::all();
    foreach ($barcodes as $bc) {
        $bc->save();
        $updated++;
    }

    return "Backfilled $updated records.";
});
