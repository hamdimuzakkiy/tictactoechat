<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Auth;

class LogicController extends Controller
{

    protected function setCache(){        
    	if (!Cache::has('room'))
    		Cache::forever('room',array());
    	$list = Cache::get('room');    	
    	try {    		
    		$list[ Auth::user()->email];
    		return false;
    	} catch (\Exception $e) {    		
    		$list[Auth::user()->email] = array('creator'=>Auth::user()->email,'password'=>'','opponent'=>'','spectators' => []);
    		Cache::forever('room',$list);    		
    	}    	
    	return true;
    }

    protected function getCache(){    	    	
    	return Cache::get('room');
    }

    protected function deleteCache(){
    	try {
    		$list = Cache::get('room');
    		unset($list[Auth::user()->email]);
    		Cache::put('room',$list);
    		return;
    	} catch (\Exception $e) {
    		return;
    	}
    }
}
