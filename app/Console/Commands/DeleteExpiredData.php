<?php

namespace App\Console\Commands;
use App\Models\PendingRequest;

use Illuminate\Console\Command;

class DeleteExpiredData extends Command
{

    /**
     * The name and signature of the console command.
     * 
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes requests after 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find requests that have expired
        $expiredRequests = PendingRequest::where('expires_at', '<', now())->delete();
        // Output how many were deleted
        $this->info("Deleted $expiredRequests expired records.");
    }
    
}
