<?php

use Aws\S3\S3Client;

require 'vendor/autoload.php';

//$config_s3 = require('config.php');


// S3
/**
* 
*/
class s3config extends CI_Model
{
	public $s3val = [];
	function __construct()
	{	
		$this->s3val = ['s3' => ['key' => 'AKIAJVL2OXKUSCETFRAA','secret' => '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','bucket' => 'nedbucket']];
	}
}




?>