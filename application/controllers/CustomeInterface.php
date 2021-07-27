<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: text/html; charset=utf-8');

/**
 * Class CustomeInterface
 * 客户端接口类
 *
 */
class CustomeInterface extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->service('WechatLoginRegister');
        $this->load->service('wProductStore');
        $this->load->helper('tool');
    }


    /**
     *  1、小程序端登陆
     */
    public function wechat_login()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData=array();


        if (array_key_exists("code", $info)  && array_key_exists("members_openid", $info))
        {


            $requestData=$this->wechatloginregister->wechatLogin($info);


        }
        else{
            $requestData['Data']='';
            $requestData["ErrorCode"]="parameter-error";
            $requestData["ErrorMessage"]="参数接收错误";
            $requestData["Success"]=false;
            $requestData["Status_Code"]="WL206";
        }
        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);




    }


    /**
     *   2、微信客户注册
     */
    public function wechat_custome_regist()
    {

        $agentinfo = file_get_contents('php://input');

        if($agentinfo!=""){
            $info = json_decode($agentinfo, true);
            $requestData=array();
            $keys="members_openid,members_photo,members_name,members_phone,members_sex,members_card";
            $errorKey=existsArrayKey($keys,$info);
            if($errorKey=="")
            {



                $requestData=$this->wechatloginregister->wechatCustomerRegist($info);



            }
            else
            {
                $requestData['Data']='';
                $requestData["ErrorCode"]="parameter-error";
                $requestData["ErrorMessage"]="参数接收错误";
                $requestData["Success"]=false;
                $requestData["Status_Code"]="OAD203";

            }




        }
        else
        {
            $requestData['Data']='';
            $requestData["ErrorCode"]="parameter-error";
            $requestData["ErrorMessage"]="参数接收错误";
            $requestData["Success"]=false;
            $requestData["Status_Code"]="OAD203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }


    /**
     *   3、首页获取列表
     */
    public function ControlHomeProductList()
    {

        $requestData = $this->wproductstore->getHomeProductList();

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);

    }


    /**
     * 4、获取比赛列表
     *
     */
    public function CompetitionlList()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo,true);
        $requestData = array();


        if (array_key_exists("competition_id", $info) && array_key_exists("competition_name", $info)) {



            $requestData = $this->wproductstore->getcompetitionList($info);

            header("HTTP/1.1 200 Created");
            header("Content-type: application/json");
            echo json_encode($requestData);


        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "OSS203";

        }

    }


    /**
     * 5、获取订单列表
     * order_type：比赛、培训、活动、商品
     */
    public function ControlOrderList()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        if (array_key_exists("order_autoid", $info) && array_key_exists("order_type", $info) && array_key_exists("order_product", $info) && array_key_exists("members_id", $info)) {



            $requestData = $this->wproductstore->getCustomeOrderList($info);

            header("HTTP/1.1 200 Created");
            header("Content-type: application/json");
            echo json_encode($requestData);


        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "OSS203";

        }
    }


    /**
     * 6、获取报名表数据
     *
     */

    public function getSignData()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        if (array_key_exists("sign_competition_id", $info) && array_key_exists("pages", $info) && array_key_exists("rows", $info) && array_key_exists("DeptId", $info) && array_key_exists("sign_name", $info) && array_key_exists("sign_card_num", $info)) {



            $requestData = $this->wproductstore->getSignUp($info);

            http_data(200, $requestData, $this);


        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "OSS203";

        }


    }

    /**
     * 7、获取报名表数据，没有进入总决赛数据
     *
     */
    public function getSignfinl()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        if (array_key_exists("sign_competition_id", $info) && array_key_exists("pages", $info) && array_key_exists("rows", $info) && array_key_exists("DeptId", $info)) {



            $requestData = $this->wproductstore->getSignFinl($info);

            http_data(200, $requestData, $this);


        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "OSS203";

        }


    }

    public function sendFinl()
    {
        $agentinfo = file_get_contents('php://input');
        $receiveData = json_decode($agentinfo, true);
        $info=$receiveData['data1'];
        $sign=$receiveData['data2'];
        $signid=$receiveData['data3'];


        if (array_key_exists("competition_final", $info) && count($sign)>=1) {



            $requestData = $this->wproductstore->sendFinl($info,$sign,$signid);




        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "OSS203";

        }
        http_data(200, $requestData, $this);

    }


    public function ControlExcel()
    {
        $agentinfo = file_get_contents('php://input');
        $receiveData = json_decode($agentinfo, true);
        $table=$receiveData['table'];
        $where=$receiveData['where'][0];
        $like=$receiveData['like'][0];


        $requestData = $this->wproductstore->outExcel($table,$where,$like);


        http_data(200, $requestData, $this);

    }


    public function Controlzip()
    {
        $agentinfo = file_get_contents('php://input');
        $receiveData = json_decode($agentinfo, true);
        $table=$receiveData['table'];
        $where=$receiveData['where'][0];
        $like=$receiveData['like'][0];


        $requestData = $this->wproductstore->zipAll($table,$where,$like);


        http_data(200, $requestData, $this);

    }


















}