<?php


include('way2sms-api.php');

if (isset($_REQUEST['uid']) && isset($_REQUEST['pwd']) && isset($_REQUEST['phone']) && isset($_REQUEST['msg'])) {
    $res = sendSMS($_REQUEST['uid'], $_REQUEST['pwd'], $_REQUEST['phone'], $_REQUEST['msg']);
    if (is_array($res))
        echo $res[0]['result'] ? 'true' : 'false';
    else
        echo $res;
    exit;
}

/**
 * Helper Function to send to sms to single/multiple people via way2sms
 * @example sendSMS ( '9000012345' , 'password' , '987654321,9876501234' , 'Hello World')
 */

function sendSMS($uid, $pwd, $phone, $msg)
{
	if (!function_exists("curl_init")) 
		{
			echo "Error : Curl library not installed";
			return FALSE;
		}	
    $client = new WAY2SMSClient();
    $client->login($uid, $pwd);
    $result = $client->send($phone, $msg);
    $client->logout();
    return $result;
}