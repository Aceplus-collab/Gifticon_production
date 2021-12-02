<?php

use Aws\S3\S3Client;

require 'vendor/autoload.php';

class s3config extends CI_Model
{
	public $s3val = [];
	function __construct()
	{	
		// $this->s3val = ['s3' => ['key' => 'AKIAVOMMZ2I66THGX6SX','secret' => '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','bucket' => 'hlink-bucket-office1']];

		$this->s3val = ['s3' => ['key' => 'AKIAY7XMYEWAQJWMTOUX','secret' => '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','bucket' => 'gnew']]; // old  //gifticonlive

	//	$this->s3val = ['s3' => ['key' => 'AKIAY7XMYEWAWH45FUDM','secret' => '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','bucket' => 'gnew']]; // new

		
	}
}




?>