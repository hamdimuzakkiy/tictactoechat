<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Auth;
use Hash;
use Carbon;

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
        ,'subscribe' => (string)$this->account()->email.date_format(Carbon\Carbon::now(),'Y_m_d_H_i_s')
        , 'movements' => []);
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

    //unset user
    protected function deleteUserCache(){
        try {
            $list = $this->getCaches();
            $room = $list[$this->getCache()['gameId']];
            $this->deleteCache();            
            $creator = $room['creator'];
            $opponent = $room['opponent'];
            $spectators = $room['spectators'];
            Cache::forget($creator);
            Cache::forget($opponent);
            foreach ($spectators as $spectator) {
                Cache::forget($spectator);
            }            
        } catch (\Exception $e) {           
        }
    }

    // unset from room
    protected function deleteCache(){
    	try {
    		$list = Cache::get('room');            
    		unset($list[$this->account()->email]);            
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

    private function checkMovements($movements, $tile){
        try {            
            $movements[$tile];
            return 0;
        } catch (\Exception $e) {
            return 1;
        }
    }

    private function checkTurn($room){        
        if (count($room['movements'])%2 == 0)
            return $room['creator'];
        return $room['opponent'];
    }

    protected function updateMovements($tile){        
        $list = $this->getCaches();
        $room = $list[$this->getCache()['gameId']];
        $movements = $room['movements'];        
        if ($this->checkTurn($room) == $this->account()->email){
            if ($this->checkMovements($movements, $tile)){                
                $movements[$tile] = $this->account()->email;
                $room['movements'] = $movements;
                $list[$this->getCache()['gameId']] = $room;
                Cache::forever('room', $list);
                return 1;
            }
        }
        return 0;
    }

    private function checkWho($movements, $tile){
        try {
            $movements[$tile];
            return 1;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function checkEquality($movements, $tile, $who){        
        try {
            if ($movements[$tile] == $who)
                return 1;
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function checkGame(){
        $room = $this->getGameCaches();
        $movements = $room['movements'];
        $col = ['A','B','C'];
        $row = ['1','2','3'];        
        for ($i=0;$i<sizeof($col);$i++){
            if (!$this->checkWho($movements, $col[$i].'1'))
                continue;
            $who = $movements[$col[$i].'1'];
            $flag = 1;
            for ($j=1;$j<sizeof($row);$j++){
                if (!$this->checkEquality($movements, $col[$i].$row[$j], $who)){                    
                    $flag = 0;
                    break;       
                }
            }    
            if ($flag)
            return $who;
        }

        for ($i=0;$i<sizeof($col);$i++){
            if (!$this->checkWho($movements,'A'.$row[$i]))
                continue;
            $who = $movements['A'.$row[$i]];
            $flag = 1;
            for ($j=1;$j<sizeof($row);$j++){
                if (!$this->checkEquality($movements, $col[$j].$row[$i], $who)){                    
                    $flag = 0;
                    break;       
                }
            }    
            if ($flag)
            return $who;
        }

        $flag = 1;

        if ($this->checkWho($movements, $col[0].$row[0])){
            $who = $movements[$col[0].$row[0]];
            for ($i=1;$i<sizeof($col);$i++){            
                if (!$this->checkEquality($movements, $col[$i].$row[$i], $who)){
                    $flag = 0;
                    break;
                }
            }
            if ($flag)
                return $who;
        }      
        $flag = 1;  
        if ($this->checkWho($movements, $col[0].$row[2])){
            $who = $movements[$col[0].$row[2]];
            for ($i=1;$i<sizeof($col);$i++){            
                if (!$this->checkEquality($movements, $col[$i].$row[2-$i], $who)){
                    $flag = 0;
                    break;
                }
            }
            if ($flag)
                return $who;
        }
        return '';
    }

}
