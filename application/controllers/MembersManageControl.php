<?php
class MembersManageControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('MembersManage');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, TRUE);
        $this->base_url='https://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/index.php')+1);
    }
    public function get_members_integral(){
        $res = $this->membersmanage->get_members_integral($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GMI001', FALSE, 0,'获取用户积分错误', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GMI000', TRUE, 0,'获取用户积分成功', $res[0]['members_integral']);
        http_data(200, $resultArr, $this);
    }
    public function update_members_integral(){
        $res = $this->membersmanage->update_members_integral($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('UMI001', FALSE, 0,'更新用户积分错误', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('UMI000', TRUE, 0,'更新用户积分成功', null);
        http_data(200, $resultArr, $this);
    }
}