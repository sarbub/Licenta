<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // Keep Mail facade
use App\Mail\requestAcceptedMailer;
use Livewire\Component;
use App\Models\PendingRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt; // Needed for decrypting income if displayed

class RequestTable extends Component
{
    public $requests;

    // --- Confirmation Modal State ---
    public $showConfirmModal = false;
    public $confirmingRequestId = null;
    public $confirmingAction = null; // 'accepted', 'declined', 'deleted'
    public $confirmingRequestName = null; // For display in modal
    // --- End Confirmation Modal State ---

    protected function loadRequests()
    {
        $user = Auth::user();
        if (!$user) {
            $this->requests = collect();
            return;
        }
        if ($user->account_type === 'admin') {
            $this->requests = PendingRequest::latest()->get();
        } elseif ($user->account_type === 'moderator') {
            // Example: Moderators see requests matching their domain or specific criteria
            // Adjust this logic as needed
            $this->requests = PendingRequest::latest()->get(); // Or filter differently
        } else {
            $this->requests = PendingRequest::where('email', $user->email)->latest()->get();
        }
    }

    public function mount()
    {
        $this->loadRequests();
    }

    /**
     * Opens the confirmation modal and stores the intended action.
     */
    public function confirmAction($requestId, $action)
    {
        $request = PendingRequest::find($requestId);
        if (!$request) {
            session()->flash('error', 'Request not found.');
            return;
        }

        // Basic authorization check (Admin/Moderator can act)
        if (!in_array(Auth::user()->account_type, ['admin', 'moderator'])) {
             session()->flash('error', 'You are not authorized to perform this action.');
             return;
        }

        $this->confirmingRequestId = $requestId;
        $this->confirmingAction = $action;
        $this->confirmingRequestName = $request->first_name . ' ' . $request->last_name;
        $this->showConfirmModal = true;
        Log::debug("Confirmation modal opened for request ID: {$requestId}, action: {$action}");
    }

    /**
     * Closes the confirmation modal and resets state.
     */
    public function cancelConfirmation()
    {
        $this->showConfirmModal = false;
        $this->confirmingRequestId = null;
        $this->confirmingAction = null;
        $this->confirmingRequestName = null;
        Log::debug('Confirmation modal cancelled/closed.');
    }

    /**
     * Executes the stored action after confirmation.
     */
    public function performConfirmedAction()
    {
        if (!$this->confirmingRequestId || !$this->confirmingAction) {
            session()->flash('error', 'Action could not be confirmed.');
            $this->cancelConfirmation();
            return;
        }

        Log::debug("Performing confirmed action '{$this->confirmingAction}' for request ID: {$this->confirmingRequestId}");

        // Call the original logic (now renamed slightly or kept as index)
        $this->executeAction($this->confirmingRequestId, $this->confirmingAction);

        // Close the modal after action is attempted/completed
        $this->cancelConfirmation();
    }

    /**
     * Contains the original logic for updating/deleting the request.
     * Renamed from index() for clarity, or keep as index() if preferred.
     */
    private function executeAction($requestId, $state)
    {
        $request = PendingRequest::find($requestId);
        if (!$request) {
            session()->flash('error', 'Request not found.');
            Log::warning("executeAction failed: Request ID {$requestId} not found.");
            return; // Stop if request doesn't exist
        }

        // Re-check authorization before executing
        if (!in_array(Auth::user()->account_type, ['admin', 'moderator'])) {
             session()->flash('error', 'You are not authorized to perform this action.');
             Log::warning("executeAction failed: Unauthorized attempt for request ID {$requestId} by User ID: " . Auth::id());
             return;
        }

        $successMessage = '';
        $logMessage = '';

        try {
            if ($state == 'deleted') {
                // Option 1: Soft Delete (if using SoftDeletes trait on model)
                // $request->delete();
                // $successMessage = 'Request moved to trash.';
                // $logMessage = "Request ID {$requestId} soft deleted.";

                // Option 2: Hard Delete (current implementation)
                // Note: Consider implications before hard deleting. Maybe move to another table first?
                // For now, let's stick to the expires_at logic you had:
                $now = Carbon::now();
                $expiresAt = $now->copy()->addMinutes(2); // Or addDays(30) as per your previous logic
                $request->expires_at = $expiresAt;
                $request->status = 'deleted'; // Also update status
                $request->save();
                $successMessage = 'Request marked for deletion.'; // More accurate message
                $logMessage = "Request ID {$requestId} marked for deletion, expires at {$expiresAt}.";

            } else {
                // Handle 'accepted' or 'declined'
                $request->status = $state;
                $request->expires_at = null; // Clear expiry if accepting/declining after marked for deletion
                $request->save();
                $successMessage = 'Request status updated to ' . $state . '.';
                $logMessage = "Request ID {$requestId} status updated to {$state}.";
                
                // Send email notifications (consider moving to a Job for better performance)
                // Use the $state ('accepted' or 'declined') directly as the response/status
                // Determine the response for the email based on the original $state
                $emailResponse = ($state === 'accepted') ? 'a' : 'nu a';

                try {
                    // Pass the determined $emailResponse to the mailer
                    Mail::to($request->email)->queue(new requestAcceptedMailer($request, $emailResponse)); // Use queue() and pass $emailResponse
                    Log::info("Queued status update email ({$emailResponse}) for request ID: {$requestId} to {$request->email}");
                } catch (\Exception $mailError) {
                    Log::error("Failed to queue status update email for request ID {$requestId}: " . $mailError->getMessage());
                }
            }

            session()->flash('success', $successMessage); // Use a consistent flash key like 'success'
            Log::info($logMessage . " by User ID: " . Auth::id());

            // Dispatch event for frontend feedback if needed (using the 'success' flash might be enough)
            // $this->dispatch('request-action-complete', message: $successMessage);

        } catch (\Exception $e) {
            Log::error("Error executing action '{$state}' for request ID {$requestId}: " . $e->getMessage());
            session()->flash('error', "Could not {$state} the request. Please try again.");
        } finally {
            // Always reload requests to reflect changes in the table
            $this->loadRequests();
        }
    }

    public function render()
    {
        return view('livewire.request-table');
    }
}
