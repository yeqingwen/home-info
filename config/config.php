<?php

define('INFO_URL', 'http://xxxxx');

function filterStr($str) {
    $str = str_replace("\n" , '', $str);
    $str = str_replace(" " , '', $str);
    $str = str_replace("'" , '', $str);
    $str = str_replace('"' , '', $str);
    return str_replace("\r" , '', $str);
}