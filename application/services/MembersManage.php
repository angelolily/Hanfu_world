<?php
class MembersManage extends HTY_service{
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->model('Sys_Model');
    }
    public function get_members_integral($data){
        $where = array('members_openid'=>$data['openid']);
        return $this->Sys_Model->table_seleRow("members_integral",'members',$where);
    }
    public function update_members_integral($data){
        $where = array('members_openid'=>$data['openid']);
        $update = array(
            'members_integral'=>(int)$data['integral']
        );
        return $this->Sys_Model->table_updateRow("members",$update,$where);
    }
}