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
//        $this->receive_data['offset'] = ($this->receive_data['pages']-1)*$this->receive_data['rows'];
        $result = $this->charge->getVote($this->receive_data);
//        $result = null;
        if (count($result) > 0) {
            $resultArr = build_resultArr('gv000', true, 0,'获取成功',json_encode($result) );
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
        if ($result || $result == 0) {
            $resultArr = build_resultArr('sg000', true, 0,'签到成功', $result);
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
    /**
     * Notes:添加商品至购物车
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function addCart(){
        $result = $this->charge->addCart($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('ac000', true, 0,'添加成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('ac002', false, 0,'添加失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取登录用户购物车
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function getCart(){
        $result = $this->charge->getCart($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gc000', true, 0,'获取成功',json_encode($result));
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gc002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:删除购物车
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function delCart(){
        $result = $this->charge->delCart($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('dc000', true, 0,'删除成功',[]);
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('dc002', false, 0,'删除失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:购物车勾选状态变化
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function selectCart(){
        $result = $this->charge->selectCart($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('dc000', true, 0,'修改成功',[]);
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('dc002', false, 0,'修改失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取用户积分
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function getPoint(){
        $result = $this->charge->getPoint($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gp000', true, 0,'获取成功',json_encode($result));
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gp002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:购买产品生成订单
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function addOrder(){
        $result = $this->charge->addOrder($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('ao000', true, 0,'添加成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('ao002', false, 0,'添加失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:微信支付后更新订单
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function updateOrder(){
        $result = $this->charge->updateOrder($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('uo000', true, 0,'修改成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('uo002', false, 0,'修改失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取我的商品订单
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function getOrder(){
        $result = $this->charge->getOrder($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('go000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('go002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:确认收货
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function confirm(){
        $result = $this->charge->confirm($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('cf000', true, 0,'修改成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('cf002', false, 0,'修改失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:确认收货
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function refund(){
        $result = $this->charge->refund($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('rf000', true, 0,'修改成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('rf002', false, 0,'修改失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:买家填写物流单号
     * User: hyr
     * DateTime: 2021/7/26 10:19
     */
    public function logistics(){
        $result = $this->charge->logistics($this->receive_data);
        if (count($result) > 0) {
            $resultArr = build_resultArr('lt000', true, 0,'修改成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('lt002', false, 0,'修改失败', []);
            http_data(200, $resultArr, $this);
        }
    }

}