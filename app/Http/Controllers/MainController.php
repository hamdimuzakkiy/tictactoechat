<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Jobs\CounterGame;

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
    	Pusher::trigger(['chat'], 'message', ['message' => $request->message, 'user'=>Auth::user()->email]);
    }

    function joinRoom(Request $request){        
        if ($this->joinCache($request->id))            
            return 0;
        return 1;
    }

    function getGame(){
        try {                
            return [$this->getCache(),$this->getGameCaches()];
        }catch (\Exception $e) {            
            return [];
        }
    }

    function getRooms(){        	
    	return $this->getCaches();
    }

    function setRoom(){        
    	$this->setCache();
        Pusher::trigger('room', 'room', $this->getCaches());
    }    

    function deleteRoom(){
        $this->deleteCache();
        Pusher::trigger('room', 'room', $this->getCaches());
    }

    function clearRoom(){
    	return $this->clearCache();
    }

    function resets(){
        $job = (new CounterGame())->delay(60 * 5);
        $this->dispatch($job);
    }
}
