<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Auth;
use Hash;

class LogicController extends Controller
{

    private function account(){
        return Auth::user();
    }

    private function createNewRoom($list, $password){
        if ($password!='')
            $isPassword = 1;
        else
            $isPassword = 0;
        $list[$this->account()->email] = array(
        'creator'=>$this->account()->email                                            
        ,'password'=> bcrypt($password)
        , 'isPassword' => $isPassword
        ,'opponent'=>''
        ,'spectators' => []
        , 'movement' => []);
        Cache::forever($this->account()->email,array('gameId'=>$this->account()->email, 'role'=>'own'));
        Cache::forever('room',$list);
        return 1;
    }

    private function logicGame($id){
        try {
            $this->getGameCaches();
            return 0;
        } catch (\Exception $e) {            
            $list = $this->getCaches();       
            try {                                
                $room = $list[$id];
                if ($room['creator'] == $this->account()->email)
                    return 0;                            
                if ($room['opponent'] == '')                 
                    return 'opponent';                                    
                else if (sizeof($room['spectators'])<10)
                    return 'spectators';                                   
                else
                    return 0;                            
            } catch (Exception $e) {                
                return 0;
            }            
            return 0;
        }
    }

    // set cache for new room
    protected function setCache($password = ''){        
    	if (!Cache::has('room'))
    		Cache::forever('room',array());        
    	$list = Cache::get('room');        
    	try {                        
    		$list[$this->account()->email];
    		return 0;
    	} catch (\Exception $e) {
            if (Cache::has($this->account()->email))
                return 0;
    		return $this->createNewRoom($list,$password);
    	}    	    	
    }

    // get all room 
    protected function getCaches(){    	    	
    	return Cache::get('room');
    }

    // get gameId user
    protected function getCache(){
        return Cache::get($this->account()->email);
    }

    // unset from room
    protected function deleteCache(){
    	try {
    		$list = Cache::get('room');
    		unset($list[$this->account()->email]);
            Cache::forget($this->account()->email);
    		Cache::forever('room',$list);            
    		return;
    	} catch (\Exception $e) {
    		return;
    	}
    }

    //clear all cache
    protected function clearCache(){
        return Cache::flush();
    }

    // get detail game 
    protected function getGameCaches(){
        $list = $this->getCaches();               
        return $list[$this->getCache()['gameId']];
    }

    // join to game, decide whether the user have a game or no, and assign to opponent or spectator    
    protected function joinCache($id, $password){        
        $role = $this->logicGame($id);
        if ($role == '0')
            return 0;        
        $list = $this->getCaches();
        $room = $list[$id];        
        if (!Hash::check($password, $room['password']))
            return 0;
        if ($role == 'opponent'){
            $room['opponent'] = $this->account()->email;
            Cache::forever($this->account()->email, array('gameId'=>$id, 'role'=>'opponent'));
        }
        else if ($role == 'spectators'){
            array_push($room['spectators'], $this->account()->email);
            Cache::forever($this->account()->email, array('gameId'=>$id, 'role'=>'spectators'));
        }        
        $list[$id] = $room;                
        Cache::forever('room', $list);                
        return 1;
    }
}
