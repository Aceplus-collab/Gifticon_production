<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailer_Lib extends PHPMailer
{
    public function __construct(){
        parent::__construct();
    }
}