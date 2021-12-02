<?php

define("MID", '11748');			// Merchant ID assigned by IE Pay
define("API_KEY", 'b94b5b6d0d4bdfbd3d1c66e99c9189c3');		// API KEY assigned by IE Pay

$params['code'] = $_POST['code'];
$params['pid'] = $_POST['pid'];
$params['device_owner'] = $_POST['device_owner'];
$params['timestamp'] = $_POST['timestamp'];

$datetime = $_POST['datetime'];			// date time string our server is using GMT+8 Beijing Time
$sign = $_POST['sign'];
$signType = $_POST['sign_type'];		// same with the code

$calSign = sign($params, $signType);

if($calSign == $sign)
{
	

	echo json_encode(['success' => true, 'error_code' => 0, 'message' => 'OK', 'data' => []]);
	exit;
}
else
{
	// SIGN FAILED
	// This request may not send by IE Pay
	echo json_encode(['success' => false, 'error_code' => 100, 'message' => 'ERROR', 'data' => []]);
	exit;
}


function sign(array $params, string $method = 'MD5') : string
{
	ksort($params);

	$paramStr = build_query($params);

	$paramStr .= API_KEY;

	
	if($method == 'MD5')
	{
		return md5($paramStr);
	}

	if($method == 'SHA256')
	{
		return hash('sha256', $paramStr);
	}
}

function build_query(array $params) : string
{
	foreach($params as $key => $val)
	{
		$pieces[] = $key . '=' . $val;
	}
	return implode("&", $pieces);
}