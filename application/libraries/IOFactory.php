<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/third_party/PHPExcel/IOFactory.php');

class IOFactory extends PHPExcel_IOFactory {
    public function __construct()
    {
        parent::__construct();
    }
}
?>