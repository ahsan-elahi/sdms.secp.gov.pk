#!/usr/bin/php -q
<?php
$config = array(
        'url'=>'http://yourdomain.com/support/api/task/cron',
        'key'=>'API KEY HERE'
        );

#pre-checks
function_exists('curl_version') or die('CURL support required');

#set timeout
set_time_limit(30);

#curl post
$ch = curl_init();        
curl_setopt($ch, CURLOPT_URL, $config['url']);        
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, '');
curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.7');
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result=curl_exec($ch);        
curl_close($ch);

if(preg_match('/HTTP\/.* ([0-9]+) .*/', $result, $status) && $status[1] == 200)
    exit(0);

echo $result;
exit(1);
?>
