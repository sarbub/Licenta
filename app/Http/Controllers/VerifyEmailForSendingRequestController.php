<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyEmailForSendingRequestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailFile;
use Illuminate\Support\Facades\Session;

class VerifyEmailForSendingRequestController extends Controller
{
    //
    public function verify_email_for_sending_request_controller_main(Request $request){
        $data = $request->all();

        $data['email'] = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $validator = Validator::make(
            $data,
            array_merge(

                [
                    'email' => 'required|email|unique:pending_requests,email',
                ]
            )
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'verify_email_send_request_erorr_bag')
                ->withInput()
                ->with('scrollToForm', true);
        }
         // Generate a 6-digit random code
         $randomCode = rand(100000, 999999);


         // Store the random code in the session for validation later
         Session::put('validation_code', $randomCode);
         session(['email_for_verification' => $request->email]);
         session()->flash('code_sent_success', 'Codul a fost trimis cu succes!');

         
        Mail::to($data['email'])->send(new MailFile(['code' => $randomCode]));
        return redirect()->route('insert_email_validation_code');
    }
}
