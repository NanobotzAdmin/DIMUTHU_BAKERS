<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $exists = Schema::hasTable('ad_settlements');
    echo $exists ? "TABLE_EXISTS\n" : "TABLE_MISSING\n";
    
    if (!$exists) {
        echo "Attempting to create table manually...\n";
        DB::statement("
            CREATE TABLE ad_settlements (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                agent_id BIGINT UNSIGNED NOT NULL,
                route_id BIGINT UNSIGNED NOT NULL,
                daily_load_id BIGINT UNSIGNED NULL,
                settlement_number VARCHAR(255) UNIQUE NOT NULL,
                settlement_date DATE NOT NULL,
                total_sales DECIMAL(22, 2) DEFAULT 0,
                cash_sales DECIMAL(22, 2) DEFAULT 0,
                credit_sales DECIMAL(22, 2) DEFAULT 0,
                cheque_sales DECIMAL(22, 2) DEFAULT 0,
                commission_earned DECIMAL(22, 2) DEFAULT 0,
                status VARCHAR(45) DEFAULT 'pending',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (agent_id) REFERENCES ad_agent(id) ON DELETE CASCADE,
                FOREIGN KEY (route_id) REFERENCES ad_routes(id) ON DELETE CASCADE,
                FOREIGN KEY (daily_load_id) REFERENCES ad_daily_loads(id) ON DELETE SET NULL
            )
        ");
        echo "TABLE_CREATED_SUCCESSFULLY\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
