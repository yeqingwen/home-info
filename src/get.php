<?php
require_once('../config/config.php');
require_once('../lib/HttpLib.php');

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
function getuser($user_id) {
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
function getuserMeter($user_id) {
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

function filterStr($str) {
    $str = str_replace("\n" , '', $str);
    $str = str_replace(" " , '', $str);
    $str = str_replace("'" , '', $str);
    $str = str_replace('"' , '', $str);
    return str_replace("\r" , '', $str);
}

function getAllUser($filename, $start=1, $end=50) {
    $fh = fopen($filename, 'w');
    $head_line = "user_id,cName,iD,password,phone,remark,sex,userName,wxID\n";
    echo $head_line;
    fwrite($fh, $head_line);
    for ($i=$start; $i < $end; $i++) {
        if(($result = getuser($i)) && isset($result['WxUser'])) {
            $user_info = $result['WxUser'];
            $content = sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $i,
                filterStr($user_info['cName']),
                filterStr($user_info["iD"]),
                filterStr($user_info["password"]),
                filterStr($user_info["phone"]),
                filterStr($user_info["remark"]),
                filterStr($user_info["sex"]),
                filterStr($user_info["userName"]),
                filterStr($user_info["wxID"])
            );
            echo $content;
            fwrite($fh, $content);
        }
    }
    fclose($fh);
}

function getAllMeter($filename, $start=1, $end=50) {
    $fh = fopen($filename, 'w');
    $head_line = "user_id,installtime,location,meternumber,mid,onlineflag,price,type\n";
    echo $head_line;
    fwrite($fh, $head_line);
    for ($i=$start; $i < $end; $i++) {
        if(($result = getuserMeter($i)) && isset($result['Meters'])) {
            $meters = $result['Meters'];
            foreach ($meters as $meter) {
                $content = sprintf("%s,%s,%s,%s,%s,%s,%s,%s\n",
                    $i,
                    filterStr($meter['installtime']),
                    filterStr($meter["location"]),
                    filterStr($meter["meternumber"]),
                    filterStr($meter["mid"]),
                    filterStr($meter["onlineflag"]),
                    filterStr($meter["price"]),
                    filterStr($meter["type"])
                );
                echo $content;
                fwrite($fh, $content);
            }
        }
    }
    fclose($fh);
}

// getAllUser('../output/user.txt', 1, 1550);
getAllMeter('../output/meter.txt', 1, 1550);
