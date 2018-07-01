<?php

/**
 * User 类
 */
require_once('../config/config.php');
require_once('../lib/HttpLib.php');
require_once('Base.php');
class User extends Base
{
/*
{
    "code": 0,
    "WxUser": {
        "cName":"郭实波",
        "iD":0,
        "password":null,
        "phone":"13602266344",
        "remark":"",
        "sex":null,
        "userName":"G1-1424",
        "wxID":null
    }
}
*/
public static function getuser($user_id) {
    $post_data = [
        'action' => 'getuser',
        'UserID' => $user_id,
        'MType'  => 'U',
        'time'   => time()
    ];
    if($result = HttpLib::httpPost(INFO_URL, $post_data))
        return json_decode($result, true);
    return;
}

/*
[
    'code' => 0,
    'Meters' => [
        [
            'installtime' => '2016/10/27 9:45:18',
            'location' => '14层-G1-1424',
            'meternumber' => '004232',
            'mid' => '213',
            'onlineflag' => 1,
            'price' => 0.99,
            'type' => 'PD866E1DY-10(40)'
        ],
        ...
    ]
]
*/
public static function getUserMeter($user_id) {
    $post_data = [
        'action' => 'getuserMeter',
        'UserID' => $user_id,
        'MType'  => 'U',
        'time'   => time()
    ];
    if($result = HttpLib::httpPost(INFO_URL, $post_data))
        return json_decode($result, true);
    return;
}

public static function getAllUser($filename, $start=1, $end=50) {
    $fh = fopen($filename, 'w');
    $head_line = "user_id,cName,iD,password,phone,remark,sex,userName,wxID\n";
    echo $head_line;
    fwrite($fh, $head_line);
    for ($i=$start; $i <= $end; $i++) {
        if(($result = self::getuser($i)) && isset($result['WxUser'])) {
            $user_info = $result['WxUser'];
            $content = sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $i,
                self::filterStr($user_info['cName']),
                self::filterStr($user_info["iD"]),
                self::filterStr($user_info["password"]),
                self::filterStr($user_info["phone"]),
                self::filterStr($user_info["remark"]),
                self::filterStr($user_info["sex"]),
                self::filterStr($user_info["userName"]),
                self::filterStr($user_info["wxID"])
            );
            echo $content;
            fwrite($fh, $content);
        }
    }
    fclose($fh);
}

public static function getAllMeter($filename, $start=1, $end=50) {
    $fh = fopen($filename, 'w');
    $head_line = "user_id,installtime,location,meternumber,mid,onlineflag,price,type\n";
    echo $head_line;
    fwrite($fh, $head_line);
    for ($i=$start; $i <= $end; $i++) {
        if(($result = self::getUserMeter($i)) && isset($result['Meters'])) {
            $meters = $result['Meters'];
            foreach ($meters as $meter) {
                $content = sprintf("%s,%s,%s,%s,%s,%s,%s,%s\n",
                    $i,
                    self::filterStr($meter['installtime']),
                    self::filterStr($meter["location"]),
                    self::filterStr($meter["meternumber"]),
                    self::filterStr($meter["mid"]),
                    self::filterStr($meter["onlineflag"]),
                    self::filterStr($meter["price"]),
                    self::filterStr($meter["type"])
                );
                echo $content;
                fwrite($fh, $content);
            }
        }
    }
    fclose($fh);
}




}