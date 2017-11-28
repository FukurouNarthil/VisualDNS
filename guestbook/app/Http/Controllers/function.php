<?php


	function getip()
	{
		return $_SERVER["REMOTE_ADDR"];
	};

	function geticon($ip)
	{
		if($ip == '127.0.0.1')
		{
			$ip = '216.58.200.46';
		}
		$content = file_get_contents('https://freegeoip.net/json/'.$ip);
		$obj=json_decode($content);
		return $obj;
	};


