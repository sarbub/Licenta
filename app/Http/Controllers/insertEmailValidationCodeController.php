<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class insertEmailValidationCodeController extends Controller
{
    //
    public function MainInsertEmailValidationCodeMethod(Request $request)
    {
        $data = $request->all();
        $code_one = trim($data['digit1']);  // Access the 'digit1' from $data
        $code_two = trim($data['digit2']);
        $code_three = trim($data['digit3']);
        $code_four = trim($data['digit4']);
        $code_five = trim($data['digit5']);
        $code_six = trim($data['digit6']);

        $validator = Validator::make($data, [
            'digit1' => ['required', 'integer', 'digits:1'],
            'digit2' => ['required', 'integer', 'digits:1'],
            'digit3' => ['required', 'integer', 'digits:1'],
            'digit4' => ['required', 'integer', 'digits:1'],
            'digit5' => ['required', 'integer', 'digits:1'],
            'digit6' => ['required', 'integer', 'digits:1'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'send_email_verification_code_error_bag')
                ->withInput();
        }

        $session_code = (string) session('validation_code');
        $user_code = request('digit1') .
            request('digit2') .
            request('digit3') .
            request('digit4') .
            request('digit5') .
            request('digit6');

        if (session('code_verf_error') && now()->diffInMinutes(session('verification_code_sent_at')) > 5) {
            // If 5 minutes have passed, show an error and ask for a new code
            return redirect()->back()->withErrors(['code_expired' => 'Your verification code has expired. Please request a new code.']);
        }


        if ($user_code !== $session_code){
            return back()
                ->withErrors(['code_verf_error' => 'Codul de verificare este incorect'])
                ->withInput();
        }
        return redirect()->route('send_request');
    }
}
