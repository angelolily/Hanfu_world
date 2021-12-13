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

    /**
     * 8、上传图片
     *
     */
    public function AdvertImageUpload()
    {
        $resultvalue = array();

        $dir = './public/advert';
        $pptfiles=[];
        if (is_dir($dir) or mkdir($dir)) {


            $files=$_FILES;

            foreach ($files as $file)
            {

                $filename=time().rand(19,99). '.jpg';
                $file_tmp = $file['tmp_name'];
                $savePath=$dir."/".$filename;
                $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件
                if ($move_result) {//上传成功
                    array_push($pptfiles,$filename);
                } else {
                    //上传失败
                    $resultvalue=[];
                    return $resultvalue;
                }

            }
            $pptfiles=join(',',$pptfiles);
            $resultvalue['Advert_image']="https://hftx.fzz.cn/public/advert/".$pptfiles;
            http_data(200, $resultvalue, $this);
        }
    }

    /**
     * 9、获取广告数据
     *
     */

    public function getAdvertData()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        if (array_key_exists("advertTitle", $info) && array_key_exists("pages", $info) && array_key_exists("rows", $info)) {



            $requestData = $this->wproductstore->geAdvert($info);

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
     * 10、新增广告
     *
     */
    public function newAdvert()
    {


        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo,true);

        if(count($info)>0)
        {
            $info=bykey_reitem($info, 'phone');
            $info=bykey_reitem($info, 'timestamp');
            $info=bykey_reitem($info, 'signature');
            $info['created_time']=date("Y-m-d H:i");
            $resultNum = $this->wproductstore->addGeneral("advert", $info);
            if (count($resultNum )> 0) {
                $resulArr = build_resulArr('AD000', true, '插入成功', []);
                http_data(200, $resulArr, $this);
            } else {
                $resulArr = build_resulArr('AD002', false, '插入失败', []);
                http_data(200, $resulArr, $this);
            }
        }
        else{
            $resulArr = build_resulArr('AD001', false, '参数接收失败', []);
            http_data(200, $resulArr, $this);
        }




    }


    /**
     * 11、修改广告
     * User:
     *
     */

    public function updateAdvert()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo,true);

        if(count($info)>0)
        {
            $info=bykey_reitem($info, 'phone');
            $info=bykey_reitem($info, 'timestamp');
            $info=bykey_reitem($info, 'signature');
            $info['updated_time']=date("Y-m-d H:i");
            $where['advertId']=$info['advertId'];
            $resultNum = $this->wproductstore->updateGeneral("advert",$info,$where);
            if (count($resultNum )> 0) {
                $resulArr = build_resulArr('ADU00', true, '修改成功', []);
                http_data(200, $resulArr, $this);
            } else {
                $resulArr = build_resulArr('ADU02', false, '修改失败', []);
                http_data(200, $resulArr, $this);
            }
        }
        else{
            $resulArr = build_resulArr('ADU01', false, '参数接收失败', []);
            http_data(200, $resulArr, $this);
        }

    }


    /**
     * 12、删除广告
     * User:
     *
     */

    public function delAdvert()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo,true);

        if(count($info)>0)
        {
            $info=bykey_reitem($info, 'phone');
            $info=bykey_reitem($info, 'timestamp');
            $info=bykey_reitem($info, 'signature');

            $resultNum = $this->wproductstore->delGeneral("advert", $info);
            if (count($resultNum )> 0) {
                $resulArr = build_resulArr('ADU00', true, '删除成功', []);
                http_data(200, $resulArr, $this);
            } else {
                $resulArr = build_resulArr('ADU02', false, '删除失败', []);
                http_data(200, $resulArr, $this);
            }
        }
        else{
            $resulArr = build_resulArr('ADU01', false, '参数接收失败', []);
            http_data(200, $resulArr, $this);
        }

    }

    /**
     * 13、获取跳转
     * User:
     *
     */

    public function getTypeInfo(){
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo,true);
        $tableName="";
        $fields="";
        if(count($info)>0) {
            switch ($info['type'])
            {
                case 1:$tableName="competition";$fields="competition_id,competition_name,competition_status";$where=['competition_status!='=>'未发布'];break;
                case 2:$tableName="activity";$fields="activity_id,activity_name,activity_status";$where=['activity_status!='=>'未发布'];break;
                case 3:$tableName="course";$fields="course_id,course_name,course_status";$where=['course_status!='=>'未发布'];break;
                default:$tableName="commodity";$fields="commodity_id,commodity_name,commodity_status";$where=['commodity_status!='=>'已发布'];break;
            }


            $requestData = $this->wproductstore->getAllGeneral($tableName,$fields,$where);

            http_data(200, $requestData, $this);



        }
        else{
            $resulArr = build_resulArr('TYDU01', false, '参数接收失败', []);
            http_data(200, $resulArr, $this);
        }


    }

    /**
     * 14、修改会员等级
     * User:
     *
     */

    public function updateisTrue()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo,true);

        if(count($info)>0)
        {
            $info=bykey_reitem($info, 'phone');
            $info=bykey_reitem($info, 'timestamp');
            $info=bykey_reitem($info, 'signature');
            $where['members_id']=$info['members_id'];
            $resultNum = $this->wproductstore->updateGeneral("members",$info,$where);
            if (count($resultNum )> 0) {
                $resulArr = build_resulArr('ADU00', true, '修改成功', []);
                http_data(200, $resulArr, $this);
            } else {
                $resulArr = build_resulArr('ADU02', false, '修改失败', []);
                http_data(200, $resulArr, $this);
            }
        }
        else{
            $resulArr = build_resulArr('ADU01', false, '参数接收失败', []);
            http_data(200, $resulArr, $this);
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


    //导出Excel
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


    //导出压缩包
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



    /**
     * 获取物流信息
     */
    public function getExpress()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "order_logistics";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->getExpressinfo($info['order_logistics']);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ACNT203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ACNT203";

        }

        http_data("200", $requestData, $this);


    }







    /**
     * html转图片
     */

    public function HtmlConverPng(){

        //$agentinfo = file_get_contents('php://input');

        $agentinfo= $_POST;

        if(array_key_exists("content",$agentinfo)){
            $result = $this->wproductstore->htmltopng($agentinfo['content']);
            if ($result['Success']) {
    
                http_data(200, $result, $this);
            } else {
    
                http_data(200, $result, $this);
            }
        }

        $appdata['Data']=[];
        $appdata["ErrorCode"]="";
        $appdata["ErrorMessage"]="data accept error";
        $appdata["Success"]=false;
        $appdata["Status_Code"]="TRA201";
        http_data(200, appdata, $this);


    }

}