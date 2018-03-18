<?php

	# 验证网址有效性
	function checkUrl($url)
	{
		$ch = curl_init();
		$timeout = 10;
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$contents = curl_exec($ch);
		if(false == $contents)
		{
			#echo 'Curl error: ' . curl_error($ch);
			return false;
		}
		else
		{
			return true;
		}
	}

	# 获取ip等信息，返回数组
	function getInfo($addr)
	{
		$content = file_get_contents('https://freegeoip.net/json/'.$addr);
		$info = json_decode($content);

		return $info;
	}


	# 返回json字符串
	function getArray($quest)
	{
		$dns = dns_get_record($quest);

		# 分割url
		$str = explode('.', $quest);
		$n = sizeof($str) - 1;
		$addr = $str[$n];

		$n = $n - 1;

		# 初始化数组
		$bag = array();

		while ($n >= 0) {
			# 数组
			$arr = array();
			$addr = $str[$n].".".$addr;

			$dns = dns_get_record($addr);
			foreach ($dns as $d)
			{
				if($d['type'] == 'NS')
                {
                    // if($d['type'] == 'CNAME')
                    // {
                    //     $d = dns_get_record($d['target']);
                    //     echo "cname<br>";
                    //     print_r($d);
                    // }

					$info = getInfo($d['target']);

					# 构建数组
					$arr_b = array("domain"=>$d['target'],"ip"=>$info->ip,"country_name"=>$info->country_name,"region_name"=>$info->region_name,"city"=>$info->city,"latitude"=>$info->latitude,"longitude"=>$info->longitude);

					# 添加到上一级
					$arr[] = $arr_b;
				}
			}

			$n = $n - 1;

			$info = getInfo($addr);
			$arr_a = array("domain"=>$addr,"ip"=>$info->ip,"country_name"=>$info->country_name,"region_name"=>$info->region_name,"city"=>$info->city,"latitude"=>$info->latitude,"longitude"=>$info->longitude,"authnss"=>$arr);
			$bag[] = $arr_a;
		}

		$info = getInfo($quest);
		$fin = json_encode($bag);
		echo $fin;
		return $fin;
	}

	# test
	$quest = $_GET['quest'];
	$to = getArray($quest);
	return $to;
?>
