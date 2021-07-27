<?php
class RecordsControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('Records');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, true);
    }

    /**
     *获取订单列表
     */
    public function getRecordsList(){
        $res = $this->records->getRecordsList($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GRL001', false, 0,'获取健康档案失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GRL000', true, 0,'获取健康档案成功', $res );
        http_data(200, $resultArr, $this);
    }

    /**
     *获取档案信息
     */
    public function getRecordsInf(){
        $res = $this->records->getRecordsInf($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GRI001', false, 0,'获取健康档案失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GRI000', true, 0,'获取健康档案成功', $res[0] );
        http_data(200, $resultArr, $this);
    }

    /**
     *存储健康档案信息
     */
    public function saveRecordsInf(){
        $res = $this->records->saveRecordsInf($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('SRI001', false, 0,'存储健康档案失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('SRI000', true, 0,'存储健康档案成功', $res );
        http_data(200, $resultArr, $this);
    }
}