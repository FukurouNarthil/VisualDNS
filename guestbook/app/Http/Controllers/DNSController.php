<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
include 'function.php';


class DNSController extends Controller
{
    public function index()
    {
    	return view('inquire')->with('dnsrecords',\App\Dnsrecord::all());
    }
    public function store(Request $request)
    {
    	$record = new \App\Dnsrecord;
    	$record->domain=$request->get('body');
    	$dns = dns_get_record($record->domain);
    	if($dns[0]['type']=='A')
    	{
    		$record->cname='null';
    	}
    	
    	else 
    	{
    		while($dns[0]['type']=='CNAME')
    		{
    			$record->cname=$dns[0]['target'];
    			
    			$dns = dns_get_record($record->cname);
    		}
    	}
    	
    	$record->ip=$dns[0]['ip'];    	
       	$contents = geticon($record->ip);
    	$record->country=$contents->{'country_name'};
    	$record->city=$contents->{'city'};
    	$record->longtitude=$contents->{'longitude'};
    	$record->latitude=$contents->{'latitude'};
    	$record->save();
    	
    	return redirect('inquire');
    }

}
