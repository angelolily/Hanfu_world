<?php

/**
 * Class WxPayControl
 * @property WxPayControl WxPay
 */
class WxPayControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('wx');
        $this->load->helper('tool');
        $this->load->service('WxPay');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, TRUE);
    }
    public function get_prepay_id(){
        $description = $this->receive_data['description'];
        $openid = $this->receive_data['openid'];
        $price = $this->receive_data['price'];
        $id = get_random(32,'HFTX');
        $result = get_prepay_id($id,$price,$openid,$description);
        $msg = $result['return_msg'];
        $res = null;
        if($result['result_code'] === 'SUCCESS'){
            $appId = $result['appid'];
            $nonceStr = $result['nonce_str'];
            $prepay_id = $result['prepay_id'];
            $timeStamp = time();
            $key = 'Hftx780125780125780125780125Hftx';
            $paySign = md5("appId=$appId&nonceStr=$nonceStr&package=prepay_id=$prepay_id&signType=MD5&timeStamp=$timeStamp&key=$key");
            $state = get_prepay_state($id);
            $res = [
                'nonceStr' => $nonceStr,
                'prepay_id' => $prepay_id,
                'timeStamp' => strval($timeStamp),
                'paySign' => $paySign,
                'signType' => 'MD5'
            ];
            $resultArr = build_resultArr('GPI000', TRUE, 0, $msg, [$res,$result,$state,$id]);
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GPI001', FALSE, 0, $msg, $res);
        http_data(200, $resultArr, $this);
    }
    public function update_order_info(){
        $res = $this->wxpay->update_order_info($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('UOI001', FALSE, 0, '更新订单信息失败', null);
            http_data(200, $resultArr, $this);
        }
        if($this->receive_data['order_type']==='比赛'){
            $res_sign = $this->wxpay->update_enroll_info($this->receive_data);
            if(!$res_sign){
                $resultArr = build_resultArr('UOI002', FALSE, 0, '更新报名信息失败', null);
                http_data(200, $resultArr, $this);
            }
        }
        $resultArr = build_resultArr('UOI000', TRUE, 0, '更新订单信息成功', null);
        http_data(200, $resultArr, $this);
    }
    public function notify(){

    }
    public function login(){
        $appid = 'wx61088edf470bc1f4';
        $secret = '542a4b5583a35cce1361e60751d22e67';
        $code = $this->receive_data['code'];
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($output, TRUE);
        $resultArr = build_resultArr('GPI000', TRUE, 0,'openid', $res );
        http_data(200, $resultArr, $this);
    }
}