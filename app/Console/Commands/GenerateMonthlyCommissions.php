<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdAgentMonthlyTarget;
use App\Models\StmOrderRequest;
use App\CommonVariables;
use Carbon\Carbon;

class GenerateMonthlyCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:generate-commissions {--month= : Month (1-12)} {--year= : Year (e.g. 2026)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and store agent commissions for the specified month.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Default to previous month if not specified
        $month = $this->option('month') ?: Carbon::now()->subMonth()->month;
        $year = $this->option('year') ?: Carbon::now()->subMonth()->year;

        $this->info("--------------------------------------------------");
        $this->info("Commission Generation: {$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT));
        $this->info("--------------------------------------------------");

        // Fetch all active targets for the specified period
        $targets = AdAgentMonthlyTarget::where('target_year', $year)
            ->where('target_month', $month)
            ->where('status', CommonVariables::$agentStatusActive)
            ->get();

        if ($targets->isEmpty()) {
            $this->warn("No active monthly targets found for {$year}-{$month}.");
            return;
        }

        foreach ($targets as $target) {
            $this->processAgentCommission($target);
        }

        $this->info("--------------------------------------------------");
        $this->info("Commission generation completed successfully.");
    }

    /**
     * Process commission for a single monthly target record.
     */
    protected function processAgentCommission($target)
    {
        $agentName = $target->agent->agent_name ?? "Agent #{$target->agent_id}";
        
        // Define completed and settled status (Status 7 in CommonVariables)
        $completedStatus = CommonVariables::$orderRequestCompleteSettled;

        // 1. Fetch completed/settled orders for this agent in the target month/year
        $orders = StmOrderRequest::where('agent_id', $target->agent_id)
            ->where('status', $completedStatus)
            ->whereMonth('delivery_date', $target->target_month)
            ->whereYear('delivery_date', $target->target_year)
            ->get();

        $totalSales = $orders->sum('grand_total');

        // Link these orders to the target record for future reporting/audit
        if ($orders->isNotEmpty()) {
            StmOrderRequest::whereIn('id', $orders->pluck('id'))
                ->update(['monthly_target_id' => $target->id]);
        }

        // 2. Base Invoicing Commission
        $baseCommission = ($totalSales * ($target->invoicing_commission_rate / 100));
        $bonusCommission = 0;

        // 3. Calculate Target Bonuses
        // Full Bonus: If total sales >= monthly_sales_target
        // Reduced Bonus: If total sales >= (monthly_sales_target * achievement_threshold%)
        
        $achievementPercentage = $target->monthly_sales_target > 0 
            ? ($totalSales / $target->monthly_sales_target) * 100 
            : ($totalSales > 0 ? 100 : 0);

        $thresholdLevel = (float)($target->achievement_threshold ?? 80.00);

        if ($achievementPercentage >= 100) {
            // Target Fully Achieved
            $bonusCommission = ($totalSales * ($target->target_commission_rate / 100));
            $achievementMsg = "100% Achieved (Full Bonus)";
        } elseif ($achievementPercentage >= $thresholdLevel) {
            // Threshold Achieved
            $bonusCommission = ($totalSales * ($target->reduced_target_commission_rate / 100));
            $achievementMsg = "{$achievementPercentage}% Achieved (Reduced Bonus)";
        } else {
            $achievementMsg = "{$achievementPercentage}% Achieved (No Bonus)";
        }

        $totalCommission = $baseCommission + $bonusCommission;

        // 4. Update the target record with the calculated commission
        $target->update([
            'monthly_commission' => $totalCommission,
            'payment_status' => 0, // Reset to Pending for the new calculation
            'updated_at' => now()
        ]);

        $this->line("Processing {$agentName}:");
        $this->line(" - Total Sales: LKR " . number_format($totalSales, 2));
        $this->line(" - Progress: {$achievementMsg}");
        $this->line(" - Commission: LKR " . number_format($totalCommission, 2));
        $this->line("--------------------------------------------------");
    }
}
