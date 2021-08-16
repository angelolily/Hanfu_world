<?php
class ProductDetail extends HTY_service{
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->model('Sys_Model');
    }
    public function get_match_info($data){
        $where = array('competition_id'=>$data['competition_id']);
        return $this->Sys_Model->table_seleRow("*",'competition',$where);
    }
    public function get_match_specs_info($data){
        $where = array('relevancy_id'=>$data['competition_id']);
        return $this->Sys_Model->table_seleRow("*",'specification',$where);
    }
    public function get_spec_info($data){
        $where = array('spec_id'=>$data['spec_id']);
        return $this->Sys_Model->table_seleRow("*",'specification',$where);
    }
    public function get_spec_match_info($relevancy_id){
        $where = array('competition_id'=>$relevancy_id);
        return $this->Sys_Model->table_seleRow("*",'competition',$where);
    }
    public function get_s_info($data){
        $where = array(
            'DeptId'=>$data['spec_dept_id'],
            'relevancy_id'=>$data['competition_id']
        );
        return $this->Sys_Model->table_seleRow("*",'specification',$where);
    }
    public function get_sign_info($data){
        $field = "sign_index_index,sign_control_column,sign_control_title,sign_control_tip,sign_is_require";
        $where = array('sign_relevancy_id'=>$data);
        return $this->Sys_Model->table_seleRow_limit($field, 'sign_index', $where, [], 999, 0, 'sign_index_index', 'ASC');
    }
    public function get_order_info($data){
        $where = array('order_autoid'=>$data['id']);
        return $this->Sys_Model->table_seleRow("*",'order',$where);
    }
    public function set_refund_reason($data){
        $where = array('order_autoid'=>$data['id']);
        $update = array(
            'order_statue'=>'退款中',
            'order_refund_flag'=>0,
            'order_refund_rate'=>$data['reason']
        );
        return $this->Sys_Model->table_updateRow("order",$update,$where);
    }
    public function get_config(){
        $where = array('ParameterTitle'=>'培训客服');
        return $this->Sys_Model->table_seleRow("*",'base_parameter',$where);
    }
    public function get_course_info($data){
        return $this->Sys_Model->table_seleRow("*",'course',[]);
    }
    public function get_aim_course_info($data){
        $where = array('course_id'=>$data['course_id']);
        return $this->Sys_Model->table_seleRow("*",'course',$where);
    }
    public function get_user_course($data){
        $field="a.order_autoid,a.order_datetime,a.order_statue,b.course_name,b.course_describe,b.course_cover,b.course_beginDate,b.course_endDate,b.course_signPrice,b.course_status";
        $sql="select $field from `order` a left join course b on a.order_capid=b.course_id where a.members_id='{$data['openid']}' and order_type='培训' order by a.order_datetime desc";
        return $this->Sys_Model->execute_sql($sql);
    }
    public function get_activity_list($data){
        return $this->Sys_Model->table_seleRow("activity_id as id,activity_name as name",'activity',[]);
    }
    public function get_course_list($data){
        return $this->Sys_Model->table_seleRow("course_id as id,course_name as name",'course',[]);
    }
    public function update_activity_state($data){
        $where = array('activity_id'=>$data['aim_id']);
        $update = array(
            'activity_status'=>$data['aim_state']);
        return $this->Sys_Model->table_updateRow("activity",$update,$where);
    }
    public function update_course_state($data){
        $where = array('course_id'=>$data['aim_id']);
        $update = array(
            'course_status'=>$data['aim_state']);
        return $this->Sys_Model->table_updateRow("course",$update,$where);
    }
}