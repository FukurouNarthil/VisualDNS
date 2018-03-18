<?php
namespace App\Http\Controllers;

require_once(__DIR__.'/../../../vendor/autoload.php');
use GuzzleHttp\Client;

use Validator;
use Illuminate\Http\Request;

include'function.php';


class DNSController extends Controller
{
    public function store(Request $request)
    {   
        # 判断地址有效性
        $define_quest = Format($_GET['quest']);
        $flag = filter_var($define_quest, FILTER_VALIDATE_URL);
        if($flag === FALSE)
        {
            return json_encode("[]");
        }else {
            $quest = explode('//', $define_quest)[1];
        }

        # 查询数据库
        $q = inquire($quest);
        if(!empty($q)){
            return json_encode($q);
        }

        # 分割url
        $addr = getPieces($quest);
        $n = sizeof($addr);
        $i = 0;
        
        # 初始化数组
        $bag = array();# 返回的数组
        $fre = array();# 记录经过的国家以及次数

        do {
            # 数组
            $arr_authnss = array();
            $dns = dns_get_record($addr[$i]);

            #如果这个函数出了问题
            if(!$dns)
            {
                return json_decode([]);
            }

            foreach ($dns as $d) 
            {
                if($d['type'] == 'NS')
                {
                    # 获得信息
                    $info = getInfo($d['target']);

                    # 构建数组,添加到上一级
                    $arr_authnss[] = array("domain"=>$d['target'],"ip"=>$info->ip,"country_name"=>$info->country_name,"region_name"=>$info->region_name,"city"=>$info->city,"latitude"=>$info->latitude,"longitude"=>$info->longitude);


                    # 保存到数据库
                    saveRecord($d['target'], $info->ip, $info->country_name, $info->region_name, $info->city, $info->latitude, $info->longitude, $addr[$i]);
                   
                    # 计数
                    if(array_key_exists($info->country_name, $fre))
                    {
                        $fre[$info->country_name] += 1;
                    }
                    else
                    {   
                        $fre[$info->country_name] = 1;
                    }
                }
            }   
            
            $info = getInfo($addr[$i]);
            $bag[] = array("domain"=>$addr[$i],"ip"=>$info->ip,"country_name"=>$info->country_name,"region_name"=>$info->region_name,"city"=>$info->city,"latitude"=>$info->latitude,"longitude"=>$info->longitude,"authnss"=>$arr_authnss);

            # 保存到数据库
            saveRecord($addr[$i], $info->ip, $info->country_name, $info->region_name, $info->city, $info->latitude, $info->longitude);
            $i = $i + 1;

            # 计数
            if(array_key_exists($info->country_name, $fre))
            {
                $fre[$info->country_name] += 1;
            }
            else
            {
                $fre[$info->country_name] = 1;
            }
        }while ($i < $n);

        # 将次数存表
        saveFrequency($quest, $fre);

        #将经过的国家及次数打包进去
        $bag[] = $fre;

        # 返回json数据
        return json_encode($bag);
    }

    #取出推荐top5
    public function top(Request $request)
	{
    	$result = \App\Frequencyrecord::orderBy('country',true)->get();
	//print_r($result);
    	$n = count($result);

    	if($n > 4)
    	{
    		$n = 5;
    	}
    	$top = array();
    	for($i=0; $i<$n; $i++){

        	$top[] = $result[$i]->domain;
    	}
    	return json_encode($top);
	}
}
