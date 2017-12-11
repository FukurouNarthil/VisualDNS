<?php
namespace App\Http\Controllers;

require_once(__DIR__.'/../../../vendor/autoload.php');
use GuzzleHttp\Client;

use Validator;
use Illuminate\Http\Request;

include'function.php';


class DNSController extends Controller
{
    public function index()
    {
        return view('inquire')->with('dnsrecords',\App\Dnsrecord::all());
    }

    public function store(Request $request)
    {   
        #$validator = Validator::make($request->all(), [
        #    'quest' => 'required|active_url'
        #]);

        #if ($validator->fails()) {
        #    return redirect('inquire')
        #                ->withErrors($validator)
        #                ->withInput();
        #}       
        #$quest = parse_url($quest)['host'];
     
        $define_quest = Format($_GET['quest']);
        $flag = filter_var($define_quest, FILTER_VALIDATE_URL);
        if($flag === FALSE)
        {
            return json_encode("[]");
        }
        else
        {
            $quest = explode('//', $define_quest)[1];
        }
        
        # HTTP
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://freegeoip.net/json/']);

        # 分割url
        $str = explode('.', $quest);
        $n = sizeof($str) - 1;
        $addr = $str[$n];
        $n = $n - 1;
        
        # 初始化数组
        $bag = array();
        $grade = 1;
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
                    // }

                    $response = $client->request('GET', $d['target']);
                    $content = $response->getBody();
                    $info = json_decode($content);
                    //$info = getInfo($d['target']);
                    # 构建数组
                    $arr_b = array("domain"=>$d['target'],"ip"=>$info->ip,"country_name"=>$info->country_name,"region_name"=>$info->region_name,"city"=>$info->city,"latitude"=>$info->latitude,"longitude"=>$info->longitude);

                    # 添加到上一级
                    $arr[] = $arr_b;

                    $record = new \App\Dnsrecord;
                    $record->domain = $d['target'];
                    $record->ip = $info->ip;
                    $record->country_name = $info->country_name;
                    $record->region_name = $info->region_name;
                    $record->city = $info->city;
                    $record->latitude = $info->latitude;
                    $record->longitude = $info->longitude;
                    $record->belong = $addr;
                    $record->level = $grade;
                    $record->save();
                }
            }

            $n = $n - 1;
            $grade = $grade + 1;
            $info = getInfo($addr);
            $arr_a = array("domain"=>$addr,"ip"=>$info->ip,"country_name"=>$info->country_name,"region_name"=>$info->region_name,"city"=>$info->city,"latitude"=>$info->latitude,"longitude"=>$info->longitude,"authnss"=>$arr);
            $bag[] = $arr_a;
        }

        $fin = json_encode($bag);
        return $fin;
    }
}