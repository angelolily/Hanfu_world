<?php


/**
 * Class WechatLoginRegist
 * 微信登陆注册接口
 */
class WechatLoginRegister extends HTY_service
{
	/**
	 * Dept constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Custome_Model');
        $this->load->helper('tool');
	}


    //get获取JSON
    private function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    //微信小程序登陆
    public function wechatLogin($info)
    {
        $assdata=[];
        if($info['code']!=""){


            $appid = "wx61088edf470bc1f4";
            $secret = "542a4b5583a35cce1361e60751d22e67";


            //第一步:取全局access_token

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
            $token = $this->getJson($url);

            if(array_key_exists("errcode", $token)){
                $assdata["Data"]='';
                $assdata["ErrorCode"]="user-error";
                $assdata["ErrorMessage"]=$token['errmsg'];
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WL201";
                header("HTTP/1.1 200 Created");
                header("Content-type: application/json");
                log_message("error",$token['errmsg']);
                return $assdata;


            }

            //第二步:取得openid
            $oauth2Url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code={$info['code']}&grant_type=authorization_code";
            $oauth2 = $this->getJson($oauth2Url);

            if(array_key_exists("errcode", $oauth2)){
                $assdata["Data"]='';
                $assdata["ErrorCode"]="user-error";
                $assdata["ErrorMessage"]=$oauth2['errmsg'];
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WL202";
                log_message("error",$oauth2['errmsg']);
                return $assdata;


            }



            $info['members_openid'] = $oauth2['openid'];


        }

        $clien_info = $this->Custome_Model->table_seleRow("*", 'members', array('members_openid' => $info['members_openid']));

        if(count($clien_info)>0)
        {

            $assdata['Data']=$clien_info[0];
            $assdata["ErrorCode"]="";
            $assdata["ErrorMessage"]="登陆成功";
            $assdata["Success"]=true;
            $assdata["Status_Code"]="WL200";



        }
        else{
            $assdata['Data']=$info;
            $assdata["ErrorCode"]="";
            $assdata["ErrorMessage"]="登陆失败，请注册";
            $assdata["Success"]=false;
            $assdata["Status_Code"]="WL205";

        }

        return $assdata;


    }

    //微信客户注册
    public function wechatCustomerRegist($info)
    {

        $assdata=[];
        $customeinfo=[];


        if(count($info)>0){



            //查询是否已经注册过了
            $members_info=$this->Custome_Model->table_seleRow('*',"members",['members_openid'=>$info['members_openid']]);

            if(count($members_info)>0)
            {
                $isok=$this->Custome_Model->table_updateRow('members',$info,['members_openid'=>$info['members_openid']]);
            }
            else
            {
                $isok=$this->Custome_Model->table_addRow('members',$info);
            }


            if($isok>=0){

                $customeinfo=$this->Custome_Model->table_seleRow('*',"members",['members_openid'=>$info['members_openid']]);
                $assdata['Data']=$customeinfo[0];
                $assdata["ErrorCode"]="";
                $assdata["ErrorMessage"]="插入成功";
                $assdata["Success"]=true;
                $assdata["Status_Code"]="WR200";

            }
            else
            {
                $assdata['Data']=[];
                $assdata["ErrorCode"]="";
                $assdata["ErrorMessage"]="插入失败";
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WR202";

            }



        }
        else
        {
            $assdata['Data']=[];
            $assdata["ErrorCode"]="";
            $assdata["ErrorMessage"]="无接收数据";
            $assdata["Success"]=false;
            $assdata["Status_Code"]="WR202";
        }


        return $assdata;



    }













}







