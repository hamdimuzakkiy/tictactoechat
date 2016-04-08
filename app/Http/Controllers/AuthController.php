<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class AuthController extends Controller
{
    function login(){
    	return view('login');
    }

    function dologin(Request $request){    	    	
    	if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    		return redirect('/');
    	}
    	else
    		return redirect('login');
    }
}
