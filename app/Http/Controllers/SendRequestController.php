<?php

namespace App\Http\Controllers;
use App\Mail\requestsMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Models\PendingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class SendRequestController extends Controller
{
    //main method
    public function mainSendRequestMethod(Request $request)
    {

        // Trim data before validating
        $data = $request->all();

        // Trim each field individually
        $data['first_name'] = htmlspecialchars(trim($data['first_name']), ENT_QUOTES, 'UTF-8');
        $data['last_name'] = htmlspecialchars(trim($data['last_name']), ENT_QUOTES, 'UTF-8');
        $data['collage'] = htmlspecialchars(trim($data['collage']), ENT_QUOTES, 'UTF-8');
        $data['address'] = htmlspecialchars(trim($data['address']), ENT_QUOTES, 'UTF-8');


        // Validating the data
        $validator = Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZăâîșțĂÂÎȘȚ\s]+$/u'],
            'last_name'  => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZăâîșțĂÂÎȘȚ\s]+$/u'],
            'age'        => ['required', 'integer', 'min:18', 'max:100'],
            'collage'    => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZăâîșțĂÂÎȘȚ\s]+$/u'],
            'address'    => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZăâîșțĂÂÎȘȚ\s]+$/u'],
            'siblings'   => ['required', 'integer', 'min:0'],
            'income'     => ['required', 'numeric', 'min:0'],

        ], [
            // Custom messages for validation errors
            'first_name.required' => 'Please enter your first name.',
            'first_name.string'   => 'First name must be a valid text.',
            'first_name.max'      => 'First name cannot exceed 255 characters.',
            'first_name.regex' => 'First name may only contain letters and spaces.',

            'last_name.required' => 'Please enter your last name.',
            'last_name.string'   => 'Last name must be a valid text.',
            'last_name.max'      => 'Last name cannot exceed 255 characters.',
            'last_name.regex'  => 'Last name may only contain letters and spaces.',

            'age.required' => 'Please enter your age.',
            'age.integer'  => 'Age must be a valid number.',
            'age.min'      => 'You must be at least 18 years old.',
            'age.max'      => 'Age cannot exceed 100 years.',

            'collage.required' => 'Please enter your college name.',
            'collage.string'   => 'College name must be valid text.',
            'collage.max'      => 'College name cannot exceed 255 characters.',
            'collage.regex'    => 'College name may only contain letters and spaces.',

            'address.required' => 'Please enter your address.',
            'address.string'   => 'Address must be valid text.',
            'address.max'      => 'Address cannot exceed 255 characters.',
            'address.regex'    => 'Address may only contain letters, numbers, spaces, commas, dots, and hyphens.',

            'siblings.required' => 'Please enter the number of siblings.',
            'siblings.integer'  => 'Number of siblings must be a valid number.',
            'siblings.min'      => 'Number of siblings cannot be negative.',

            'income.required' => 'Please enter your family income.',
            'income.numeric'  => 'Family income must be a valid number.',
            'income.min'      => 'Family income cannot be negative.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'send_request_errors')
                ->withInput();
        }

        try {
            $validatedData = $validator->validated();
            $pendingRequest = PendingRequest::create([
                'first_name' => $validatedData['first_name'],
                'last_name'  => $validatedData['last_name'],
                'email'  => session('email_for_verification'),
                'age'        => $validatedData['age'],
                'collage'    => $validatedData['collage'],
                'address'    => $validatedData['address'],
                'siblings'   => $validatedData['siblings'],
                'income'     => Crypt::encryptString($validatedData['income'])
            ]);

            // Redirect with success message
            session()->flash('request_success', 'Cererea dvs a fost trimisă cu succes!');
            Mail::to([session('email_for_verification'),'sarbub5@gmail.com'])->send(new requestsMail(
                $validatedData['first_name'],
                $validatedData['last_name'],
                $validatedData['age'],
                $validatedData['collage'],
                $validatedData['address'],
                $validatedData['siblings'],
                $validatedData['income'] // If you need to decrypt the income
            ));
            return redirect()->route('index');
        } catch (\Exception $e) {
            // Handle any errors during creation
            echo "erorr here";
            Log::error('Error during request creation: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Optionally, you can also display the error message on the screen for debugging purposes
            echo "Error occurred: " . $e->getMessage();
            return back()->with('error', 'An error occurred while processing your request.');
        }
    }
}
