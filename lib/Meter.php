<?php 

/**
 * User 类
 */
require_once('../config/config.php');
require_once('../lib/HttpLib.php');
require_once('Base.php');
class Meter extends Base
{

/*
{
    "code": 0,
    "meterAct": {
        "balanceAmount": 0,
        "hoardingLimit": 0,
        "lastMonth": 0,
        "lastReadTime": "",
        "meterValue": 0,
        "mid": 2071,
        "overdraftLimit": 0,
        "overdraftMoney": 0,
        "rKV": 0,
        "rMA": 0,
        "rPrice": 0,
        "rTotalCharge": 0,
        "switchState": "未知",
        "thisMonth": 0
    }
}
 */
public static function getMeteractInfo($mid) {
    $post_data = [
        'action' => 'getmeteractinfo',
        'mid' => $mid,
        'time'   => time()
    ];
    if($result = HttpLib::httpPost(INFO_URL, $post_data))
        return json_decode($result, true);
    return;
}

/*
    "code": 0,
    "index": "3,4,5,6,7",
    "value": "251.80,114.10,160.00,325.10,255.30"
*/
public static function getHistory($mid, $year=2017, $month=0) {
    $post_data = [
        'action'    => 'gethis',
        'mid'       => $mid,
        'time'      => time(),
        'year'      => $year,
        'month'     => $month
    ];
    if($result = HttpLib::httpPost(INFO_URL, $post_data))
        return json_decode($result, true);
    return;
}

/*
    "code": 0,
    "Charges": [
        {
            "amount": 300,
            "chargeTime": "2017/7/23 13:50:04",
            "mid": 2717,
            "quantity": 303.03,
            "remainAmount": 45.86
        }....
    ]
*/
public static function getMeterCharge($mid) {
    $post_data = [
        'action'    => 'getMeterCharge',
        'mid'       => $mid,
        'time'      => time()
    ];
    if($result = HttpLib::httpPost(INFO_URL, $post_data))
        return json_decode($result, true);
    return;
}

public static function getAllMeterInfo($filename, $start=1, $end=50) {
    $fh = fopen($filename, 'w');
    $head_line = "mid,balanceAmount,hoardingLimit,lastMonth,lastReadTime,meterValue,overdraftLimit,overdraftMoney,rKV,rMA,rPrice,rTotalCharge,switchState,thisMonth\n";
    echo $head_line;
    fwrite($fh, $head_line);
    for ($i=$start; $i < $end; $i++) {
        if(($result = self::getMeteractInfo($i)) && isset($result['meterAct'])) {
            $meter = $result['meterAct'];
            $content = sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $i,
                self::filterStr($meter['balanceAmount']),
                self::filterStr($meter['hoardingLimit']),
                self::filterStr($meter['lastMonth']),
                self::filterStr($meter['lastReadTime']),
                self::filterStr($meter['meterValue']),
                self::filterStr($meter['overdraftLimit']),
                self::filterStr($meter['overdraftMoney']),
                self::filterStr($meter['rKV']),
                self::filterStr($meter['rMA']),
                self::filterStr($meter['rPrice']),
                self::filterStr($meter['rTotalCharge']),
                self::filterStr($meter['switchState']),
                self::filterStr($meter['thisMonth'])
            );
            echo $content;
            fwrite($fh, $content);
        }
    }
    fclose($fh);
}

public static function getAllMeterHistory($filename, $start=1, $end=50, $month=0) {
    $fh = fopen($filename, 'w');
    $head_line = "mid,month,index,value\n";
    echo $head_line;
    fwrite($fh, $head_line);
    for ($i=$start; $i < $end; $i++) {
        if(($result = self::getHistory($i)) && isset($result['value'])) {
            $index_arr = explode(',', $result['index']);
            $value_arr = explode(',', $result['value']);
            foreach ($index_arr as $key => $value) {
                $content = sprintf("%s,%s,%s,%s\n",
                    $i,
                    $month,
                    self::filterStr($value),
                    self::filterStr($value_arr[$key])
                );
                echo $content;
                fwrite($fh, $content);
            }
        }
    }
    fclose($fh);
}

public static function getAllMeterCharge($filename, $start=1, $end=50) {
    $fh = fopen($filename, 'w');
    $head_line = "mid,amount,chargeTime,quantity,remainAmount\n";
    echo $head_line;
    fwrite($fh, $head_line);
    for ($i=$start; $i < $end; $i++) {
        if(($result = self::getMeterCharge($i)) && isset($result['Charges'])) {
            $charges = $result['Charges'];
            foreach ($charges as $charge) {
                $content = sprintf("%s,%s,%s,%s,%s\n",
                    $i,
                    self::filterStr($charge['amount']),
                    self::filterStr($charge['chargeTime']),
                    self::filterStr($charge['quantity']),
                    self::filterStr($charge['remainAmount'])
                );
                echo $content;
                fwrite($fh, $content);
            }
        }
    }
    fclose($fh);
}


}