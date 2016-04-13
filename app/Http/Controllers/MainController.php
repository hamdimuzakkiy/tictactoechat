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
        $status =  $this->joinCache($request->id, $request->password);
        if ($status){
            Pusher::trigger('room', 'room', $this->getCaches());
            $this->broadcastGame();
        }
        return $status;
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

    function setRoom(Request $request){        
    	if ($this->setCache($request->password)){
            Pusher::trigger('room', 'room', $this->getCaches());
            return 1;
        }        
        return 0;
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

    function profile(){
        return Auth::user();
    }

    function turn(Request $request){
        if ($this->updateMovements($request->tile)){            
            $this->broadcastGame();
            if ($this->checkGame() != ''){
                $this->gameWiner($this->checkGame());
                $this->deleteUserCache();
            }            
            $this->broadcastRoom();
        }                        
    }

    function broadcastRoom(){
        Pusher::trigger('room', 'room', $this->getCaches());
    }

    function gameWiner($winner){
        Pusher::trigger($this->getCaches()[$this->getCache()['gameId']]['subscribe'],'winner'
            , $winner); 
    }

    function broadcastGame(){
        Pusher::trigger($this->getCaches()[$this->getCache()['gameId']]['subscribe'],'message'
            , $this->getCaches()[$this->getCache()['gameId']]); 
    }

    function test(){
        //$this->deleteUserCache();
        return $this->getCache();
    }
}
