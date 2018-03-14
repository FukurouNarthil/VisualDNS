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
	
	return $content;
}
$contents=geticon('216.58.200.46');
print_r($contents);


