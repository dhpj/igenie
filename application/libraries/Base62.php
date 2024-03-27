<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Config model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

//2021-02-26 º¯°æ
class Base62{
    private $alphabet = array(0 => "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
                                  // "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
                                   "0","1","2","3","4","5","6","7","8","9","_","-");
    function encode($id) {
        $shortenedId = "";
        while($id>0){
            $remainder = $id % 36;
            //echo "id : ".$id." / rem : ".$remainder."<BR>";
            $id = floor($id / 36);
            $shortenedId = $this->alphabet[$remainder].$shortenedId;
        }
        return $shortenedId;
    }
}

/*
class Base62{
    private $alphabet = array(1 => "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z",
                                   "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
                                   "0","1","2","3","4","5","6","7","8","9","_","-");
    
    function encode($id) {
        $shortenedId = "";
        //log_message("error", $id);
        while($id>0){
            $remainder = $id % 62;
            $id = $id / 62;
            $shortenedId = $this->alphabet[$remainder].$shortenedId;
        }
        //$var = '==========';
        //$equal_str = substr($var, 1, 10 - strlen($shortenedId) );
        return $shortenedId;
    }
}
*/