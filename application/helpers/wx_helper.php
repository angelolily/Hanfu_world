<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';
use EasyWeChat\Factory;

global $config;
$config = [
    'app_id'             => 'wx61088edf470bc1f4',
    'mch_id'             => '1611083498',
    'key'                => 'Hftx780125780125780125780125Hftx',

    'cert_path'          => 'D:\phpstudy_pro\WWW\Hanfu-World74\public\cert\apiclient_cert.pem',
    'key_path'           => 'D:\phpstudy_pro\WWW\Hanfu-World74\public\cert\apiclient_key.pem',

    'notify_url'         => 'https://pay.fzz.cn/Hanfu-world74/index.php/WxPayControl/notify',
];
/**
 * 获取统一订单id
 **/
function get_prepay_id($id,$price,$openid,$description){
    $pay = Factory::payment($GLOBALS['config']);
    return $pay->order->unify(['body' => $description, 'out_trade_no' => $id, 'total_fee' => $price, 'openid' => $openid, 'trade_type' => 'JSAPI']);
}
/**
 * 查询订单状态
 **/
function get_prepay_state($out_trade_no){
    $pay = Factory::payment($GLOBALS['config']);
    return $pay->order->queryByOutTradeNumber($out_trade_no);
}
/**
 * 根据商户订单号退款
 **/
function refund_order($order_id,$order_refund_id,$order_price,$refund_price,$other = []){
    $pay = Factory::payment([
        'app_id'             => 'wx61088edf470bc1f4',
        'mch_id'             => '1611083498',
        'key'                => 'Hftx780125780125780125780125Hftx',

        'cert_path'          => 'D:\phpstudy_pro\WWW\Hanfu-World74\public\cert\apiclient_cert.pem',
        'key_path'           => 'D:\phpstudy_pro\WWW\Hanfu-World74\public\cert\apiclient_key.pem',

        'notify_url'         => 'https://pay.fzz.cn/Hanfu-world74/index.php/WxPayControl/notify_refund',
    ]);
    return $pay->refund->byOutTradeNumber($order_id, $order_refund_id, $order_price, $refund_price, $other);
}
/**
 * 根据商户订单号查询订单退款状态
 **/
function refund_order_check($order_id){
    $pay = Factory::payment($GLOBALS['config']);
    return $pay->refund->queryByOutTradeNumber($order_id);
}
/**
 * 响应退款回调通知
 **/
function respond_notify_refund(){
    $pay = Factory::payment($GLOBALS['config']);
    $response = $pay->handleRefundedNotify(function ($message, $reqInfo, $fail) {
        // 其中 $message['req_info'] 获取到的是加密信息
        // $reqInfo 为 message['req_info'] 解密后的信息
        // 你的业务逻辑...
        return true; // 返回 true 告诉微信“我已处理完成”
        // 或返回错误原因 $fail('参数格式校验错误');
    });
    $response->send();
}
function pay($prepayId){
    $payment = Factory::payment($GLOBALS['config']);
    $js_sdk = $payment->jssdk;
    return $js_sdk->bridgeConfig($prepayId, FALSE);
}
function get_random($length,$s_key=''){
    $str = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $base_psw = '';
    $aim_len = $length;
    if($s_key !== ''){
        $aim_len = $length - strlen($s_key);
        $base_psw = $s_key;
    }
    $keys = array_rand($str, $aim_len);
    $password = '';
    for($i = 0; $i < $aim_len; $i++){
        $password .= $str[$keys[$i]];
    }
    return $base_psw.$password;
}