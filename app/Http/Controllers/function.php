<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function Format($addr)
{
	if(stristr($addr, 'http://', false) === FALSE)
	{
		$url = 'htpp://'.$addr;
	}
	else
	{
		$url = $addr;
	}
	return $url;
}

# 获取ip等信息，返回数组
function getInfo($addr)
{
	$client = new GuzzleHttp\Client(['base_uri' => 'https://freegeoip.net/json/']);
	try{
		$response = $client->request('GET', $addr);
	}catch (RequestException $e) {
   		echo $e->getRequest();
   		if ($e->hasResponse()) {
       		echo $e->getResponse();
   		}
	}
	$content = $response->getBody();
	$info = json_decode($content);
	
	return $info;
}

# 分割域名
function getPieces($quest)
{
	$pieces = array();
	$str = explode('.', $quest);
    $n = count($str) - 1;
    $addr = $str[$n];

    $n = $n - 1;
	while ($n >= 0) {
        $addr = $str[$n].".".$addr;
        $pieces[] = $addr;
        $n = $n - 1;
    }

    return $pieces;
}
?>