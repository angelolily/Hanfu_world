<?php
class ReportControl extends CI_Controller {
    private $dataArr = [];//操作数据
    private $userArr = [];//用户数据
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('Report');
        $receive = file_get_contents('php://input');
        $this->OldDataArr = json_decode($receive, true);
    }
    /**
     * Notes:前置验证，将用户信息与数据分离
     * User: lchangelo
     * DateTime: 2020/12/24 14:39
     */
    private function hedVerify($keys="")
    {

        if ($this->OldDataArr) {
            if (count($this->OldDataArr) > 0) {
                if($keys!="")
                {
                    $errorKey=existsArrayKey($keys,$this->OldDataArr);
                    if($errorKey=="")
                    {
                        $this->userArr['Mobile'] = $this->OldDataArr['phone'];
                    }
                    else
                    {
                        $resulArr = build_resulArr('S003', false, '参数缺失', []);
                        http_data(200, $resulArr, $this);
                    }
                }
                $this->dataArr = bykey_reitem($this->OldDataArr, 'phone');
                $this->dataArr = bykey_reitem($this->dataArr, 'timestamp');
                $this->dataArr = bykey_reitem($this->dataArr, 'signature');
            } else {
                $resulArr = build_resulArr('S002', false, '无接收', []);
                http_data(200, $resulArr, $this);
            }
        } else {
            $resulArr = build_resulArr('S002', false, '无接收', []);
            http_data(200, $resulArr, $this);

        }
    }
    /**
     * Notes:赛事统计
     * User: angelo
     * DateTime: 2020/12/25 10:01
     */

    public function getcomReport()
    {
        $keys="relevancy_id,pages,rows,DeptId";
        $this->hedVerify($keys);
        $result = $this->report->getcomReport($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '获取失败', []);
            http_data(200, $resulArr, $this);
        }
    }
    /**
     * Notes: 课程统计
     * User: angelo
     * DateTime: 2020/12/25 10:01
     */

    public function getcouseReport()
    {
        $keys="course_id,pages,rows";
        $this->hedVerify($keys);
        $result = $this->report->getcouseReport($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '获取失败', []);
            http_data(200, $resulArr, $this);
        }
    }


    public function getacReport()
    {
        $keys="activity_id,pages,rows";
        $this->hedVerify($keys);
        $result = $this->report->getacReport($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '获取失败', []);
            http_data(200, $resulArr, $this);
        }
    }
    public function getdityReport()
    {
        $keys="commodity_name,pages,rows";
        $this->hedVerify($keys);
        $result = $this->report->getdityReport($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '获取失败', []);
            http_data(200, $resulArr, $this);
        }
    }
    public function getreferrerReport()
    {
        $keys="referrer_name,pages,rows";
        $this->hedVerify($keys);
        $result = $this->report->getreferrerReport($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '获取失败', []);
            http_data(200, $resulArr, $this);
        }
    }
}