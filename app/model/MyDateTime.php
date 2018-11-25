<?php

namespace App\Model;

use Nette\Utils\DateTime;

class MyDateTime extends \Nette\Utils\DateTime{  

    public static function getYear($time){
        return $time->format('Y');
    }
    
}