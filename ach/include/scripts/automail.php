#!/usr/bin/php -q
<?php 

$config = array(
        'url'=>'http://yourdomain.com/support/api/tickets.email',
        'key'=>'API KEY HERE'
        );

#pre-checks
function_exists('file_get_contents') or die('upgrade php >=4.3');
function_exists('curl_version') or die('CURL support required');
#read stdin (piped email)
$data=file_get_contents('php://stdin') or die('Error reading stdin. No message');

#set timeout
set_time_limit(10);

#curl post
$ch = curl_init();        
curl_setopt($ch, CURLOPT_URL, $config['url']);        
curl_setopt($ch, CURLOPT_POST, 1);        
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.7');
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
$result=curl_exec($ch);        
curl_close($ch);

//Use postfix exit codes...expected by MTA.
$code = 75;
if(preg_match('/HTTP\/.* ([0-9]+) .*/', $result, $status)) {
    switch($status[1]) {
        case 201: //Success
            $code = 0;
            break;
        case 400:
            $code = 66;
            break;
        case 401: /* permission denied */
        case 403:
            $code = 77;
            break;
        case 415:
        case 416:
        case 417:
        case 501:
            $code = 65;
            break;
        case 503:
            $code = 69;
            break;
        case 500: //Server error.
        default: //Temp (unknown) failure - retry 
            $code = 75;
    }
}

exit($code);
?>
