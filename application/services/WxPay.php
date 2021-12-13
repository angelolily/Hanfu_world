<?php

/**
 * Class WxPay
 * @property WxPay Sys_Model
 */
class WxPay extends HTY_service{
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->model('Sys_Model');
    }
    public function update_order_info($data){
        $where = array('order_autoid'=>$data['order_id']);
        $update = array(
            'order_prepay_id'=>$data['prepay_id'],
            'order_datetime'=>$data['time_stamp'],
            'order_mic_id'=>$data['mic_id'],
            'order_statue'=>'进行中'
        );
        return $this->Sys_Model->table_updateRow("order",$update,$where);
    }
    public function update_enroll_info($data){
        $where = array(
            'sign_order_id'=>$data['order_id']
        );
        $update = array(
            'sign_statue'=>'成功报名'
        );
        return $this->Sys_Model->table_updateRow("sign_up",$update,$where);
    }
}