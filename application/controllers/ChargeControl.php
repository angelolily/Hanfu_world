<?php

class ChargeControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('Charge');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, true);
    }
    /**
     * Notes:赛区负责人登录彦祖
     * User: hyr
     * DateTime: 2021/6/28 14:30
     */
    public function login(){
        $result = $this->charge->login($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('lg000', true, 0,'登录成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('lg002', false, 0,'登录失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取投票表
     * User: hyr
     * DateTime: 2021/6/24 14:52
     */
    public function getVote(){
//        $result = null;
//        $this->receive_data['offset'] = ($this->receive_data['pages']-1)*$this->receive_data['rows'];
        $result = $this->charge->getVote($this->receive_data);
        if (count($result) > 0) {
//            $where_data = array('competition_id'=>$result[0]['competition_id']);
//            $res_c_info = $this->charge->get_c_info($where_data);
//            if(!$res_c_info){
//                $resultArr = build_resultArr('gv001', false, 0,'获取失败', []);
//                http_data(200, $resultArr, $this);
//            }
//            $resultArr = build_resultArr('gv000', true, 0,'获取成功', [json_encode($result),$res_c_info[0]['competition_name']]);
            $resultArr = build_resultArr('gv000', true, 0,'获取成功', json_encode($result));
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gv002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取赛事表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     */
    public function getSpec(){
        $result = $this->charge->getSpec($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gv000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gv002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取报名人员表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     */
    public function getB(){
        $result = $this->charge->getB($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gb000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gb002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取单独页面投票信息
     * User: hyr
     * DateTime: 2021/6/28 11:19
     */
    public function getSv(){
        $result = $this->charge->getSv($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gsv000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gsv002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取个人参加活动信息
     * User: hyr
     * DateTime: 2021/7/08 11:19
     */
    public function getPa(){
        $result = $this->charge->getPa($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gpa000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gpa002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:活动签到
     * User: hyr
     * DateTime: 2021/7/08 11:19
     */
    public function sign(){
        $result = $this->charge->sign($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('sg000', true, 0,'签到成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('sg002', false, 0,'签到失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取商品
     * User: hyr
     * DateTime: 2021/7/13 10:19
     */
    public function getCommodity(){
        $result = $this->charge->getCommodity($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gc000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gc002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取所有商品图片
     * User: hyr
     * DateTime: 2021/7/16 10:19
     */
    public function getImg(){
        $result = $this->charge->getImg($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gi000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gi002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }

}