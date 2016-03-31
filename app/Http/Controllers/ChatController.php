<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Vinkla\Pusher\Facades\Pusher;

class ChatController extends Controller
{
    function send(){
    	Pusher::trigger('chat', 'message', ['message' => 'hello']);    	
    }
}
