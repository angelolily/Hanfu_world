<?php


/**
 * Class
 */
class Charge extends HTY_service
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_Model');
        $this->load->helper('tool');
        $this->load->library('encryption');
    }
    /**
     * Notes:获取投票表
     * User: hyr
     * DateTime: 2021/6/24 14:52
     * @param array $val 赛区id：DeptId 赛事id:competition_id
     * @return array $result
     */
    public function getVote($val)
    {
        $field="*";
        $sql="select $field from vote where DeptId='{$val['DeptId']}' and competition_id={$val['competition_id']} order by vote_poll desc";
        $allData=$this->Sys_Model->execute_sql($sql);
        try{
            if(count($allData)>0) {
                $base_dir = './public/enroll';
                for ($i = 0; $i < count($allData); $i++) {
                    $res_url = '';
                    $dir_name = $allData[$i]['vote_image'];
                    $dir = $base_dir . '/' . $dir_name;
                    if(file_exists($dir)){
                        $handler = opendir($dir);
                        if ($handler) {
                            while (($filename = readdir($handler)) !== false) {
                                if ($filename != "." && $filename != "..") {
                                    $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
                                    if(strstr($filename,"f")){
                                        break;
                                    }
                                }
                            }
                        }
                        closedir($handler);
                        $allData[$i]['vote_image'] = $res_url;
                    }

                }
            }


        }
        catch(Exception $ex)
        {

        }
        return $allData;
    }
    /**
     * Notes:获取赛事表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     * @param array $val 负责人电话：Phone
     * @return array $result
     */
    public function getSpec($val)
    {
        $field="a.spec_id,a.competition_sign_qrcode,a.DeptId,a.DeptName,b.competition_cover,b.competition_name,b.competition_describe";
        $sql="select $field from specification a left join competition b on b.competition_id=a.relevancy_id where a.Phone={$val['Phone']}";
        $allData=$this->Sys_Model->execute_sql($sql);
        $result=$allData;
        return $result;
    }
    /**
     * Notes:赛事负责人登录验证
     * User: hyr
     * DateTime: 2021/6/28 14:29
     * @param array $val 负责人电话：Mobile 密码：UserPassword
     * @return array $result
     */
    public function login($val)
    {
        $field="UserStatus,UserName,Mobile,UserPassword,UserRole,Sex,IsAdmin,UserDept,UserPost,Avatar";
        $where['Mobile']=$val['Mobile'];
        $allData=$this->Sys_Model->table_seleRow($field,'base_user',$where);
        if(count($allData)>0) {
            $pwd = $this->encryption->decrypt($allData[0]['UserPassword']);
            if ($val['UserPassword'] == $pwd) {
                return $allData;
            } else {
                return [];
            }
        }
        return [];
    }
    /**
     * Notes:获取参赛人员表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     * @param array $val 负责人电话：Phone 赛区id:DeptId
     * @return array $result
     */
    public function getB($val)
    {
        $result=[];
        $field="sign_statue,sign_name,sign_phone,sign_picture";
        $allData=$this->Sys_Model->table_seleRow($field,'sign_up',$val);

        try{
            if(count($allData)>0) {
                for ($i = 0; $i < count($allData); $i++) {
                    $res_url = '';
                    $dir_name = $allData[$i]['sign_picture'];
                    $base_dir = './public/enroll';
                    $dir = $base_dir . '/' . $dir_name;
                
                    if(file_exists($dir))
                    {
                        $handler = opendir($dir);
                        if ($handler) {
                            while (($filename = readdir($handler)) !== false) {
                                if ($filename != "." && $filename != "..") {
                                    $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
                                    if(strstr($filename,"f")){
                                        break;
                                    }
                                }
                            }
                        }
                        closedir($handler);
                        $allData[$i]['sign_picture']=$res_url;

                    }
              
                }
            }
           

        }
        catch(Exception $ex)
        {

        }
       
        $result=$allData;
        return $result;
    }
    /**
     * Notes:获取参赛人员表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     * @param array $val 赛事、赛区、购买人手机号 competition_id、DeptId、vote_phone
     * @return array $result
     */
    public function getSv($val)
    {
        $field="vote_id,vote_name,vote_phone,vote_image,vote_poll,vote_end,competition_skip_id,DeptName";
        $allData=$this->Sys_Model->table_seleRow($field,'vote',$val);
        if(count($allData)>0){
            $res_url = '';
            $dir_name = $allData[0]['vote_image'];
            $base_dir = './public/enroll/';
            $dir=$base_dir.'/'.$dir_name;
            $handler = opendir($dir);
            if($handler){
                while (($filename = readdir($handler)) !== false) {
                    if ($filename != "." && $filename != "..") {
                        $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
                        if(strstr($filename,"f")){
                            break;
                        }
                    }
                }
            }
            closedir($handler);
            $allData[0]['vote_image']=$res_url;
        }

        //返回$res_url为图片路径
        $result=$allData;
        return $result;
    }
    /**
     * Notes:获取参赛人员表
     * User: hyr
     * DateTime: 2021/7/08 11:19
     * @param array $val 用户openid:members_id
     * @return array $result
     */
    public function getPa($val)
    {
        $field="a.order_autoid,a.order_datetime,a.order_statue,b.activity_name,b.activity_describe,b.activity_cover,b.activity_beginDate,b.activity_endDate,b.activity_signPrice,b.activity_status";
        $sql="select $field from `order` a left join activity b on a.order_capid=b.activity_id where a.members_id='{$val['members_id']}' order by a.order_datetime desc";
        $allData=$this->Sys_Model->execute_sql($sql);
        $result=$allData;
        return $result;
    }
    /**
     * Notes:活动签到
     * User: hyr
     * DateTime: 2021/7/08 11:19
     * @param array $val 会员openid:members_openid 活动id:activity_id
     * @return array $result
     */
    public function sign($val)
    {
        $where['activity_id']=$val['activity_id'];
        $info=$this->Sys_Model->table_seleRow('activity_signIntegral','activity',$where);
        $point=$info[0]['activity_signIntegral'];
        $sql_arr=[];
        $sql1="update `order` set order_statue='已结束' where order_autoid={$val['order_autoid']}";
        $sql2="update `members` set members_integral=members_integral+{$point} where members_openid='{$val['members_openid']}'";
        array_push($sql_arr,$sql1,$sql2);
        $result=$this->Sys_Model->table_trans($sql_arr);
        return $result;
    }
    /**
     * Notes:获取商品
     * User: hyr
     * DateTime: 2021/7/13 10:19
     * @param array $val 页码:pages 每页条数:rows 商品名:commodity_name
     * @return array $result
     */
    public function getCommodity($val)
    {
        $field="commodity_id,commodity_name,commodity_describe,commodity_graphic,commodity_type,commodity_cover,commodity_price,commodity_integral";
        $begin=$val['rows'];
        $offset=($val['pages']-1)*$val['rows'];
        $like=[];
        if($val['commodity_name']!=""){
            $like['commodity_name']=$val['commodity_name'];
        }
        $totalArr=$this->Sys_Model->table_seleRow('commodity_id',"commodity", array(), $like);
        if($totalArr && count($totalArr)>0)
        {
            $TmpArr = $this->Sys_Model->table_seleRow_limit($field, "commodity", array(), $like,$begin,$offset,$order="commodity_id","desc");
            $result['total']=count($totalArr);
            $result['data']=$TmpArr;
        }
        else
        {
            $result['total']=0;
            $result['data']=[];
        }
        return $result;
    }
    /**
     * Notes:获取所有商品图片
     * User: hyr
     * DateTime: 2021/7/16 11:19
     * @param array $val 投票id:vote_id
     * @return array $result
     */
    public function getImg($val)
    {
        $allData=$this->Sys_Model->table_seleRow('vote_image','vote',$val);
        $result=[];
        try{
            if(count($allData)>0) {
                    $base_dir = './public/enroll';
                    $res_url = '';
                    $dir_name = $allData[0]['vote_image'];
                    $dir = $base_dir . '/' . $dir_name;
                    if(file_exists($dir)){
                        $handler = opendir($dir);
                        if ($handler) {
                            while (($filename = readdir($handler)) !== false) {
                                if ($filename != "." && $filename != "..") {
                                    $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
//                                    if(strstr($filename,"f")){
//                                        break;
//                                    }
                                    $arr['content']=$res_url;
                                    array_push($result,$arr);
                                }
                            }
                        }
                        closedir($handler);
                    }

            }


        }
        catch(Exception $ex)
        {

        }
        return $result;
    }
    /**
     * @Note 获取赛事名称
     * @User yjl
     * @param $where
     * @return mixed
     */
    public function get_c_info($where){
        return $this->Sys_Model->table_seleRow("competition_name",'competition',$where);
    }
}







