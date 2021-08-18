<?php

/**
 * Class ProductDetailControl
 * 产品详情类
 */
class ProductDetailControl extends CI_Controller{
    private $base_url;
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('ProductDetail');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, TRUE);
        $this->base_url='https://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/index.php')+1);
    }
    public function get_match_info(){
        $res = $this->productdetail->get_match_info($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GMI001', FALSE, 0,'获取赛事信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res[0]['competition_cover'] = $this->base_url.'public/comcover/'.$res[0]['competition_cover'];
        $res[0]['competition_graphic'] = $this->base_url.'public/comgraphic/'.$res[0]['competition_graphic'];
        $res_spec = $this->productdetail->get_match_specs_info($this->receive_data);
        $resultArr = build_resultArr('GMI000', TRUE, 0,'获取赛区信息成功', [$res[0],$res_spec]);
        http_data(200, $resultArr, $this);
    }
    public function get_spec_info(){
        $res = $this->productdetail->get_spec_info($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GPI001', FALSE, 0,'获取赛区信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res_match = $this->productdetail->get_spec_match_info($res[0]['relevancy_id']);
        if(!$res_match){
            $resultArr = build_resultArr('GPI002', FALSE, 0,'获取赛事信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res_sign_info = $this->productdetail->get_sign_info($res_match[0]['competition_sign_model']);
        if(!$res_sign_info){
            $resultArr = build_resultArr('GPI003', FALSE, 0,'获取报名表信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res_config = $this->productdetail->get_config();
        if(!$res_config){
            $resultArr = build_resultArr('GPI004', FALSE, 0,'获取客服信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res_match[0]['competition_cover'] = $this->base_url.'public/comcover/'.$res_match[0]['competition_cover'];
        $res_match[0]['competition_graphic'] = $this->base_url.'public/comgraphic/'.$res_match[0]['competition_graphic'];
        $resultArr = build_resultArr('GPI000', TRUE, 0,'获取赛事信息成功', [$res[0],$res_match[0],$res_config,$res_sign_info]);
        http_data(200, $resultArr, $this);
    }
    public function get_aim_match_info(){
        $res = $this->productdetail->get_s_info($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GAI001', FALSE, 0,'获取赛区信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res_match = $this->productdetail->get_spec_match_info($res[0]['relevancy_id']);
        if(!$res_match){
            $resultArr = build_resultArr('GPI002', FALSE, 0,'获取赛事信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res_match[0]['competition_cover'] = $this->base_url.'public/comcover/'.$res_match[0]['competition_cover'];
        $res_match[0]['competition_graphic'] = $this->base_url.'public/comgraphic/'.$res_match[0]['competition_graphic'];
        $resultArr = build_resultArr('GPI000', TRUE, 0,'获取赛事信息成功', [$res[0],$res_match[0]]);
        http_data(200, $resultArr, $this);
    }
    public function get_img_url_arr(){
        $res_url = [];
        $dir_name = $this->receive_data['dir_name'];
        $base_dir = './public/enroll/';
        $dir=$base_dir.$dir_name;
        $handler = opendir($dir);
        if($handler){
            while( ($filename = readdir($handler)) !== false ) {
                if($filename != "." && $filename != ".."){
                    $aim_url = $this->base_url;
                    if($this->base_url === 'https://oa.fjspacecloud.com/Hanfu-World/index.php/'){
                        $aim_url = 'http://oa.fjspacecloud.com/Hanfu-World/index.php/';
                    }
                    array_push($res_url,$aim_url."public/enroll/".$dir_name.'/'.$filename);
                }
            }
        }
        closedir($handler);
        //返回$res_url为图片路径
        $resultArr = build_resultArr('GPI000', TRUE, 0,'获取图片url成功', $res_url);
        http_data(200, $resultArr, $this);
    }
    public function check_order_info(){
//        $res_c_info = $this->productdetail->get_match_info($this->receive_data);
//        if(!$res_c_info){
//            $resultArr = build_resultArr('CI001', FALSE, 0,'获取信息错误', null );
//            http_data(200, $resultArr, $this);
//        }
//        $c_info = $res_c_info[0];
        $msg = 'OK';
        $flag = TRUE;
        $type = $this->receive_data['type'];
        if($type === '比赛'){
            // 获取赛区信息,退款商品为赛事报名费时当前时间在赛区报名时限外不可退款
            $res_s_info = $this->productdetail->get_s_info($this->receive_data);
            if(!$res_s_info){
                $resultArr = build_resultArr('CI001', FALSE, 0,'获取信息错误', null );
                http_data(200, $resultArr, $this);
            }
            $s_info = $res_s_info[0];
            $is_dead_line = time() > strtotime($s_info['competition_sign_end'].' 23:59:59');
            if($is_dead_line){
                $msg = '比赛进行中，无法退款';
                $flag = FALSE;
            }
        }else if($type === '商品'){
            $res_o_info = $this->productdetail->get_order_info($this->receive_data);
            if(!$res_o_info){
                $resultArr = build_resultArr('CI002', FALSE, 0,'获取信息错误', null );
                http_data(200, $resultArr, $this);
            }
            $o_info = $res_o_info[0];
            if($o_info['order_isSeven'] === 1){
                $create_time = strtotime($o_info['order_isSeven']);
                $is_in_seven = time() > strtotime('+7 days',$create_time);
                if($is_in_seven){
                    $msg = '超出七天无理由退款时间';
                }else{
                    $msg = '七天无理由退款';
                }
            }
        }else if($type === '培训'){
            $msg = '培训';
            $flag = FALSE;
        }else if($type === '活动'){
            $msg = '活动';
            $flag = FALSE;
        }
        $resultArr = build_resultArr('CI000', TRUE, 0, $msg, $flag);
        http_data(200, $resultArr, $this);
    }
    public function set_order_refund(){
        $res = $this->productdetail->set_refund_reason($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('SER001', FALSE, 0,'更新表单信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('SER000', TRUE, 0,'更新表单信息成功', null);
        http_data(200, $resultArr, $this);
    }
    public function get_course_info(){
        $res = $this->productdetail->get_course_info($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GCI001', FALSE, 0,'获取课程信息错误', null );
            http_data(200, $resultArr, $this);
        }
        for($i=0;$i<count($res);$i++){
            $res[$i]['course_cover']="https://hftx.fzz.cn/public/coursecover/".$res[$i]['course_cover'];
            $res[$i]['course_graphic']="https://hftx.fzz.cn/public/coursegraphic/".$res[$i]['course_graphic'];
        }
        $resultArr = build_resultArr('GCI000', TRUE, 0,'获取课程信息成功', $res);
        http_data(200, $resultArr, $this);
    }
    public function get_aim_course_info(){
        $res = $this->productdetail->get_aim_course_info($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GCI101', FALSE, 0,'获取目标课程信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $res[0]['course_cover']="https://hftx.fzz.cn/public/coursecover/".$res[0]['course_cover'];
        $res[0]['course_graphic']="https://hftx.fzz.cn/public/coursegraphic/".$res[0]['course_graphic'];
        $resultArr = build_resultArr('GCI100', TRUE, 0,'获取目标课程信息成功', $res[0]);
        http_data(200, $resultArr, $this);
    }
    public function get_user_course(){
        $res = $this->productdetail->get_user_course($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GUC001', FALSE, 0,'获取目标课程信息错误', null );
            http_data(200, $resultArr, $this);
        }
        for($i=0;$i<count($res['course_info']);$i++){
            $res['course_info'][$i]['course_cover']="https://hftx.fzz.cn/public/coursecover/".$res['course_info'][$i]['course_cover'];
            $res['course_info'][$i]['course_graphic']="https://hftx.fzz.cn/public/coursegraphic/".$res['course_info'][$i]['course_graphic'];
        }
        $resultArr = build_resultArr('GUC000', TRUE, 0,'获取目标课程信息成功', json_encode($res));
        http_data(200, $resultArr, $this);
    }
    public function get_ac_list(){
        $type = $this->receive_data['type'];
        if($type === '活动'){
            $res = $this->productdetail->get_activity_list($this->receive_data);
        }else{
            $res = $this->productdetail->get_course_list($this->receive_data);
        }
        if(!$res){
            $resultArr = build_resultArr('ACL001', FALSE, 0,'获取目标信息错误', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('ACL000', TRUE, 0,'获取目标信息成功', json_encode($res));
        http_data(200, $resultArr, $this);
    }
    public function update_aim_p_state(){
        $type = $this->receive_data['aim_type'];
        if($type === '活动'){
            $res = $this->productdetail->update_activity_state($this->receive_data);
        }else if($type === '课程'){
            $res = $this->productdetail->update_course_state($this->receive_data);
        }
        $resultArr = build_resultArr('ACL000', TRUE, 0,'更新状态成功', []);
        http_data(200, $resultArr, $this);
    }
    public function update_user_point(){

    }
    public function gte_user_point(){

    }
}