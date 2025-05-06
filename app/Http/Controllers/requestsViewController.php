<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingRequest;
use Illuminate\Support\Facades\Auth; // <-- 1. Import the Auth Facade

class requestsViewController extends Controller
{
    public function index(){
        return view('requestsView');
    }
}
