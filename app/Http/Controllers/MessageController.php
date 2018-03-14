<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

function getip()
{
	return $_SERVER["REMOTE_ADDR"];
}

function geticon($ip)
{
	if($ip == '127.0.0.1')
	{
		$ip = '216.58.200.46';
	}
	$content = file_get_contents('https://freegeoip.net/json/'.$ip);
	$obj=json_decode($content);
	return $obj;
}
class MessageController extends Controller
{
	//$icon= new Functions\Icon;
    public function index()
    {
    	return view('message')->with('messages',\App\Message::all());
    }
    public function store(Request $request)
    {
    	$message = new \App\Message;
    	$message->body=$request->get('body');
    	$ip=getip();
    	$contents=geticon($ip);
    	$message->ip=$ip;
    	$message->country=$contents->{'country_name'};
    	$message->city=$contents->{'city'};
    	$message->longtitude=$contents->{'longitude'};
    	$message->latitude=$contents->{'latitude'};
    	$message->save();
    	
    	return redirect('message');
    }

}
