<?php

$domain = $_GET['domain'];

$records = dns_get_record($domain, DNS_A);
$ips = [];
foreach($records as $record){
 $ips[] = $record['ip'];
}
$json = ['domain' => $domain, 'ips' => $ips, ];
echo json_encode($json);
?>
