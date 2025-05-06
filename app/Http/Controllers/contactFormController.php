<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateUserDataRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\contactFormMailer;

class contactFormController extends Controller
{
    //
    public function mainContactFormController(ValidateUserDataRequest $request)
    {
        $data = $request->all();
        $data['email'] = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
        $data['bio'] = strip_tags(trim($data['bio']));


        $validator = Validator::make(
            $data,
            array_merge(
                (new ValidateUserDataRequest)->rulesForEmail(),
                (new ValidateUserDataRequest)->rulesForBio()
            )
        );

        if($validator->fails()){
            return redirect()->route('contact')
            ->withErrors($validator)
            ->withInput();
        }



        // dd($data);


        Mail::to('sarbub5@gmail.com')->send(new contactFormMailer([
            'email' => $data['email'],
            'bio' => $data['bio']

        ]));

        session()->flash('contact_success', 'Mesajul a fost trimis cu succes');
        return redirect()->route('contact');
    }
}
