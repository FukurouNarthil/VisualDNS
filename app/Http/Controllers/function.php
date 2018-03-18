<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

# 地址格式化
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

    # 更正国家信息
    if($info->country_name == 'Hong Kong' || $info->country_name == 'Macao' || $info->country_name == 'Taiwan')
    {
        #$info->country_name = 'China(Hong Kong)';
        $info->country_name = 'China';
    }

    return $info;
}

# 分割域名
function getPieces($quest)
{
    $pieces = array();
    $str = explode('.', $quest);
    $n = sizeof($str) - 1;
    $addr = $str[$n];

    $n = $n - 1;
    while ($n >= 0) {
        $addr = $str[$n].".".$addr;
        $pieces[] = $addr;
        $n = $n - 1;
    }

    return $pieces;
}

# 信息存入数据库
function saveRecord($domain, $ip, $country, $region, $city, $latitude, $longitude, $belong='')
{
    # 是否已存在相同数据
    $result = \App\Dnsrecord::where('domain',$domain)->get();

    if($result->count())
    {
    }
    else{
        # 保存到数据库
        $record = new \App\Dnsrecord;
        $record->domain = $domain;
        $record->ip = $ip;
        $record->country_name = $country;
        $record->region_name = $region;
        $record->city = $city;
        $record->latitude = $latitude;
        $record->longitude = $longitude;
        $record->belong = $belong;
        $record->save();
    }    
}

# 次数存入数据库
function saveFrequency($domain, $fre)
{
    # 是否已存在相同数据
    $result = \App\Frequencyrecord::where('domain',$domain)->get();
    if($result->count())
    {
    }
    else{
        # 遍历计算经过的外国国家次数
        $count = count($fre);
        if(array_key_exists('China',$fre))
        {
            $count -= 1;
        }
        # 保存到数据库
        $frequency_record = new \App\Frequencyrecord;
        $frequency_record->domain = $domain;
        $frequency_record->country = $count;
        $frequency_record->save();
    }
}

# 从数据库中取出已有域名的解析
function inquire($quest)
{

    $result = \App\Dnsrecord::where('domain',$quest)->get();
    # 初始化数组
    $bag = array();# 返回的数组
    $fre = array();# 记录经过的国家以及次数
    if($result->count())
    {
        $addr = getPieces($quest);
	$temp = \App\Dnsrecord::where('belong',$addr[0])->get()->count();
	if(!$temp)
	{
		return $bag;
	}
        $n = sizeof($addr);
        $i = 0;
        do {
            $arr = array();
            $targets = \App\Dnsrecord::where('belong',$addr[$i])->get();
            foreach ($targets as $target) {
                $arr_b = array("domain"=>$target->domain,"ip"=>$target->ip,"country_name"=>$target->country_name,"region_name"=>$target->region_name,"city"=>$target->city,"latitude"=>$target->latitude,"longitude"=>$target->longitude);
                $arr[] = $arr_b;
                # 计数
                if(array_key_exists($target->country_name, $fre))
                {
                    $fre[$target->country_name] += 1;
                }
                else
                {   
                    $fre[$target->country_name] = 1;
                }
            }

            $tar = \App\Dnsrecord::where('domain',$addr[$i])->first();
            $arr_a = array("domain"=>$tar->domain,"ip"=>$tar->ip,"country_name"=>$tar->country_name,"region_name"=>$tar->region_name,"city"=>$tar->city,"latitude"=>$tar->latitude,"longitude"=>$tar->longitude, "authnss"=>$arr);
            $bag[] = $arr_a;

            # 计数
            if(array_key_exists($tar->country_name, $fre))
            {
                $fre[$tar->country_name] += 1;
            }
            else
            {   
                $fre[$tar->country_name] = 1;
            }

            $i += 1;
        }while ($i < $n);

        #将经过的国家及次数打包进去
        $bag[] = $fre;

        return $bag;
    }
}
?>