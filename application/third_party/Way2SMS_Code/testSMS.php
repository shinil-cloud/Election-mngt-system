<?php
require_once 'sendsms.php';

//----- Way2SMS user details...
$userId 	= "9633428498"; // this is the registered mobile no.
$password 	= "H6432A"; // this is the registered password.


//---- for sending bulk sms. passing as coma separated string.
/*$phone	  	= "9895401989 , 9633428498, 9895074274 ,
					9995144898, 9567391420,9947406123,
					8129150678, 9544760088, 8281055880,9895176127";
				  	
				 */  
$phone		= "8330843428";
$msg	  	="This is a testing sms via php new code";

//for($i=0;$i<count($phone); $i++)
		$res = sendSMS($userId, $password, $phone, $msg);

	print_r($res);
	foreach($res as $a)
		echo $a["msg"];
		
if($res)
	echo "success";
else
	echo "failed";

?>