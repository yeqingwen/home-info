<?php

class Base
{
    public static function filterStr($str) {
        $str = str_replace("\n" , '', $str);
        $str = str_replace(" " , '', $str);
        $str = str_replace("'" , '', $str);
        $str = str_replace('"' , '', $str);
        return str_replace("\r" , '', $str);
    }
}
