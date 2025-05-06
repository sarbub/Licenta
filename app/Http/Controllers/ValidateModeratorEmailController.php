<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\validateMailForNarator;
use Illuminate\Support\Facades\Session;





class ValidateModeratorEmailController extends Controller
{
    //
    public function ValidateNaratorEmailControllerMainMethod(request $request){
        $data = $request->all();
        $data['email'] = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        // $data['password'] = trim($data['password']);

        $validator = Validator::make($data,[
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/','regex:/[@$!%*?&]/'],
        ],[
            'email.required' => 'Please enter your email address.',
            'email.string'   => 'Email must be a valid text.',
            'email.email'    => 'Email must be a valid email address.',
            'email.max'      => 'Email cannot exceed 255 characters.',
            'email.unique'   => 'This email address is already in use.',

            // 'password.required' => 'A password is required.',
            // 'password.min' => 'Your password must be at least 8 characters long.',
            // 'password.confirmed' => 'The password confirmation does not match.',
            // 'password.regex' => 'Password must include at least one uppercase letter, one number, and one special character.',
        ]);
        // dd($data);
        if ($validator->fails()) {
            echo "there is a problem";
            return back()
                ->withErrors($validator, 'narator_error_bag')
                ->withInput()
                ->with('scrollToForm', true);
        }

        $randomCode = rand(100000, 999999);
        // Store the random code in the session for validation later
        Session::put('NaratorEmailValidationCode', $randomCode);
        Session::put('naratorEmail', $data['email']);

        try{
            Mail::to($data['email'])->send(new validateMailForNarator($randomCode));
        session()->flash('NaratorCodeSuccess','Codul de validare a fost trimis cu succes');
        return redirect()->route('naratorCodeValidation');
        }catch (\Exception $e){
            return back()->withErrors(['email']);

        }
    }
}
