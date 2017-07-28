<?php 

/**
 * 基础函数库
 * @author qingwen.ye <[<email address>]>
 */

class HttpLib
{
    /**
     * http get 请求
     * @author qingwen.ye
     * @param  str    $url    请求的链接地址
     * @param  str    $data   请求时需要发送的数据
     * @param  array  $config 配置参数
     * @return str    返回请求的结果数据
     */
    
    public static function httpGet($url, $data="", array $config=array()) {
        $s_ch = curl_init();
        if($data)
            $url .= '?'.$data;
        curl_setopt($s_ch, CURLOPT_URL, $url);
        curl_setopt($s_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($s_ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($s_ch, CURLOPT_TIMEOUT, 5);
        if(!empty($config)) {
            foreach ($config as $key => $value) {
                curl_setopt($s_ch, $key, $value);
            }
        }
        $data = curl_exec($s_ch);
        $httpcode = curl_getinfo($s_ch, CURLINFO_HTTP_CODE);
        curl_close($s_ch );
        return ($httpcode >= 200 && $httpcode < 300) ? $data : '';
    }

    /**
     * http post 请求
     * @author qingwen.ye
     * @param  str    $url    请求的链接地址
     * @param  str    $data   请求时需要发送的数据
     * @param  array  $config 配置参数
     * @return str    返回请求的结果数据
     */
    public static function httpPost($url, $data="", array $config=array()) {
        $s_ch = curl_init ();
        curl_setopt($s_ch, CURLOPT_URL, $url);
        curl_setopt($s_ch, CURLOPT_POST, 1);
        curl_setopt($s_ch, CURLOPT_HEADER, 0);
        curl_setopt($s_ch, CURLOPT_RETURNTRANSFER, 1);

        if(!empty($config)) {
            foreach ($config as $key => $value) {
                curl_setopt($s_ch, $key, $value);
            }
        }
        curl_setopt($s_ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($s_ch);
        $httpcode = curl_getinfo($s_ch, CURLINFO_HTTP_CODE);
        return ($httpcode >= 200 && $httpcode < 300) ? $data : '';
    }

}