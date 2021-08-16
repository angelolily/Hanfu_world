<?php
class UserAddress extends HTY_service{
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->model('Sys_Model');
    }

    /**
     * 获取用户收获地址列表
     * @param $val
     * @return mixed
     */
    public function getUserAddress($val){
        $where = array('custome_id'=>$val['custome_id']);
        $field = 'address_id,address_province,address_city,address_county,address_street,address_detail,address_default,address_user_name,address_user_phone';
        return $this->Sys_Model->table_seleRow($field,'address',$where);
    }

    /**
     * 根据地址id获取目标收获地址
     * @param $val
     * @return mixed
     */
    public function getAimAddress($val){
        $where = array('address_id'=>$val['address_id']);
        $field = 'address_province,address_city,address_county,address_street,address_detail,address_default,address_user_name,address_user_phone';
        return $this->Sys_Model->table_seleRow($field,'address',$where);
    }

    /**
     * 获取用户收获地址数量
     * @param $val
     * @return mixed
     */
    public function getNumOfAddress($val){
        $where = array('custome_id'=>$val);
        return $this->Sys_Model->table_seleRow('address_id','address',$where);
    }

    /**
     * 初始化用户默认收获地址
     * @param $val
     * @return mixed
     */
    public function setDefaultAddress($val){
        $where = array('custome_id'=>$val);
        $update = array('address_default'=>0);
        return $this->Sys_Model->table_updateRow("address",$update,$where);
    }

    /**
     * 添加新收获地址
     * @param $val
     * @return mixed
     */
    public function addAddress($val){
        $new_data = array(
            'custome_id'=>$val['custome_id'],
            'address_province'=>$val['address_province'],
            'address_city'=>$val['address_city'],
            'address_county'=>$val['address_county'],
            'address_street'=>$val['address_street'],
            'address_detail'=>$val['address_detail'],
            'address_default'=>$val['address_default'],
            'address_user_name'=>$val['address_user_name'],
            'address_user_phone'=>$val['address_user_phone'],
        );
        return $this->Sys_Model->table_addRow("address",$new_data);
    }

    /**
     * 保存修改后的收获地址
     * @param $val
     * @return mixed
     */
    public function editAddress($val){
        $where = array('address_id'=>$val['address_id']);
        $update = array(
            'address_province'=>$val['address_province'],
            'address_city'=>$val['address_city'],
            'address_county'=>$val['address_county'],
            'address_street'=>$val['address_street'],
            'address_detail'=>$val['address_detail'],
            'address_default'=>$val['address_default'],
            'address_user_name'=>$val['address_user_name'],
            'address_user_phone'=>$val['address_user_phone'],
        );
        return $this->Sys_Model->table_updateRow("address",$update,$where);
    }

    /**
     * 获取用户默认收获地址
     * @param $val
     * @return mixed
     */
    public function getDefaultAddress($val){
        $where = array('custome_id'=>$val['custome_id'],'address_default'=>1);
        $field = 'address_id,address_province,address_city,address_county,address_street,address_detail,address_default,address_user_name,address_user_phone';
        return $this->Sys_Model->table_seleRow($field,'address',$where);
    }
}