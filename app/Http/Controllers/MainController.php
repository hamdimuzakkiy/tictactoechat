<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Vinkla\Pusher\Facades\Pusher;
use Auth;
use Cache;

class MainController extends LogicController
{

    function index(){
        return view('index');
    }

    function lobby(){    	
    	return view('lobby');
    }

    function chatLobby(Request $request){    	
    	Pusher::trigger(['lobby','chat'], 'message', ['message' => $request->message, 'user'=>Auth::user()->email]);
    }

    function getRoom(){        	
    	return $this->getCache();
    }

    function setRoom(){        
    	$this->setCache();
    }    

    function room(){
    	
    }
}
