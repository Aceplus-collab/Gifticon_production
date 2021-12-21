<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use PHPMailer\PHPMailer;
use PHPMailer\Exception;

class PHPMailer_Lib
{
    public function __construct(){
        parent::__construct();
    }

    public function load(){
        // Include PHPMailer library files
        require_once __ROOT__.'third_party/PHPMailer/Exception.php';
        require_once __ROOT__.'third_party/PHPMailer/PHPMailer.php';
        require_once __ROOT__.'third_party/PHPMailer/SMTP.php';

        $mail = new PHPMailer(true);
        return $mail;
    }
}