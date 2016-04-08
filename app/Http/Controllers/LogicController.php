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
    	//return Cache::add(Auth::user()->email,Auth::user()->id,10);
    	if (Cache::has('user'))
    	return Cache::put('user',Auth::user()->id,10);
    }	

    protected function getCache(){    	
    	return Cache::get(Auth::user()->email);
    }
}
