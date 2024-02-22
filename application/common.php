<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 生成指定长度的随机字符串
 * @param int $length 长度
 * @param int $type 类型
 * @return string
 */
function get_rand_srt($length = 1, $type = 1)
{
    $chars = '';
    if($type == 1){
        $chars = '123456789';
    }elseif ($type == 2){
        $chars = 'qwertyuipasdfghjklzxcvbnm';
    }elseif ($type == 3){
        $chars = 'qwertyuipasdfghjklzxcvbnm123456789';
    }elseif ($type == 4){
        $chars = 'qwertyuipasdfghjklzxcvbnmQWERTYUIPASDFGHJKLZXCVBNM';
    } elseif ($type == 5){
        $chars = 'QWERTYUIPASDFGHJKLZXCVBNM';
    }elseif ($type == 6){
        $chars = 'QWERTYUIPASDFGHJKLZXCVBNM123456789';
    }elseif ($type == 7){
        $chars = 'QWERTYUIPASDFGHJKLZXCVBNM123456789qwertyuipasdfghjklzxcvbnm';
    }
    $hash = '';
    $max  = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * @param string $string 原文或者密文
 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE 解密
 * @param string $key 密钥
 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 *  $a = authcode('abc', 'ENCODE', 'key');
 *  $b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 *  $a = authcode('abc', 'ENCODE', 'key', 3600);
 *  $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function auto_code($string, $operation = 'DECODE', $key = '', $expiry = 0){
    $ckey_length = 6;
    // 随机密钥长度 取值 0-32;
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥

    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? (substr($string, $ckey_length) !== false && preg_match('/^[0-9a-fA-F]+$/i', substr($string, $ckey_length)) ? pack("H*", substr($string, $ckey_length)) : false) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', bin2hex($result));
    }
}

/**
 * 实例化redis
 * @param int $select db库号 0-15
 * @return Redis redis实例
 */
function newRedis($select = 0)
{
    $redis = new \Redis();
    $redis->connect(\think\Config::get('session.host'),\think\Config::get('session.port'));
    $redis->auth(\think\Config::get('session.password'));
    if($select) {
        $redis->select($select);
    }
    return $redis;
}

/**
 * 添加后台操作日志
 * @param $log 日志
 * @param bool $name 用户名
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function add_log_admin($log, $name = false)
{
    if (!$name) {

        $uidKey = \think\Session::get('uid');

        if ($uidKey) {
            $uid = auto_code($uidKey);
            $user = \think\Db::name('user_admin')->where(array('id'=>$uid))->find();
            $data['username'] = $user['username'];
        } else {
            $data['username'] = '';
        }

    } else {

        $data['username'] = $name;

    }
    $data['add_time'] = time();
    $data['ip'] = \think\Request::instance()->ip();
    $data['log'] = $log;
    \Think\Db::name('log_admin')->insert($data);
}