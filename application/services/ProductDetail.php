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
}