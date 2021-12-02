<?php

define("PARTNER_ID", 11748);	// Merchant ID assigned by IE Pay
define("OPERATOR", 0);			// Merchant ID assigned by IE Pay


// Use this to generate a QR-code

$qrc = RandomString();

 echo qrGenerator([
	'code' => $qrc,		// The unique code generate by your system. NOTE length <= 32
	'mid' => PARTNER_ID,
	'sign_type' => 'MD5',
]);


function qrGenerator(array $params) : string
{
	$params = [
		'prefix' => 'ipc:',
		'pid' => PARTNER_ID . OPERATOR,
		'code' => $params['code'],
		'sign_type' => $params['sign_type'],
		'suffix' => ':cpi'
	];
	return implode("|", $params);
}

function RandomString($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
    }
