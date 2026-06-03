<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmEmailSend;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPendingEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all pending queued emails from the em_email_send table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Checking for pending emails in queue...");

        $pendingEmails = EmEmailSend::where('status', 0) // 0 = Pending
            ->orderBy('created_at', 'asc')
            ->limit(50) // process in chunks
            ->get();

        if ($pendingEmails->isEmpty()) {
            $this->info("No pending emails found.");
            return;
        }

        $this->info("Processing " . $pendingEmails->count() . " pending email(s)...");

        foreach ($pendingEmails as $email) {
            try {
                Mail::html($email->email_content, function ($message) use ($email) {
                    $message->to($email->email_address)
                        ->subject($email->email_subject);

                    if (!empty($email->attachment_path) && file_exists($email->attachment_path)) {
                        $message->attach($email->attachment_path);
                    }
                });

                $email->update([
                    'status' => 1, // 1 = Sent
                    'send_response' => 'Sent successfully at ' . now()->toDateTimeString(),
                    'updated_at' => now(),
                ]);

                $this->info("Successfully sent email to: {$email->email_address}");
            } catch (\Exception $e) {
                Log::error("Failed to send email to {$email->email_address}: " . $e->getMessage());

                $email->update([
                    'status' => 2, // 2 = Failed
                    'send_response' => substr($e->getMessage(), 0, 245),
                    'updated_at' => now(),
                ]);

                $this->error("Failed to send email to: {$email->email_address}");
            }
        }

        $this->info("Email queue processing complete.");
    }
}
