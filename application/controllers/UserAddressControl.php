<?php
class UserAddressControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('UserAddress');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, true);
    }
}