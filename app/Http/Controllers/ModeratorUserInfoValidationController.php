<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModeratorUserInfoValidationRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Exception;

class ModeratorUserInfoValidationController extends Controller
{

    
    //

    public function NaratorUserInfoValidationControllerMainMethod(ModeratorUserInfoValidationRequest $request){

        $naratorEmail = session('naratorEmail');

        $data = $request->all();
        $validated = $request->validated();

        if(!$naratorEmail){
            echo "email does not exist";
            return;
        }

        try{
            if(User::where('account_type', 'moderator')->exists()){
                User::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $naratorEmail,
                    'password' => Hash::make($validated['password']),
                    'account_type' => 'admin'
                ]);
                session()->flash('AdminUserCreated', 'Contul de admin a fost creat cu succes');
                return redirect()->route('narator');
            }
            User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $naratorEmail,
                'password' => Hash::make($validated['password']),
                'account_type' => 'moderator'
            ]);
            session()->flash('ModeratorUserCreated', 'Contul de moderator a fost creat cu succes');
            return redirect()->route('narator');
        }catch(Exception $exception){
            echo $exception->getMessage();
            error_log($exception->getMessage(), 3, storage_path('logs/error_log.txt'));            
            session()->flash('createAdminModeratorAccountError','Eroare la crearea contului de admin/moderator, vă rugam sa incercati din nou');
            return redirect()->route('createNarator');
        }

    }

}
