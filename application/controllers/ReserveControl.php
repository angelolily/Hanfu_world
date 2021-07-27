<?php


class ReserveControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Reserve');
		$this->load->helper('tool');
		$receiveArr = file_get_contents('php://input');
		$this->OldDataArr = json_decode($receiveArr, true);
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
     * Notes:部门新增记录
     * User: lchangelo
     * DateTime: 2020/12/24 14:41
     */
    public function newRow()
    {
        $keys="subscribe_date,subscribe_time,subscribe_name,subscribe_phone,subscribe_project,subscribe_address,subscribe_reception,subscribe_re_phone,subscribe_rate,order_id";
        $this->hedVerify($keys);
        $resultNum = $this->reserve->addData($this->dataArr, $this->userArr['Mobile']);
        if (count($resultNum) > 0) {
            $resulArr = build_resulArr('D000', true, '插入成功', []);
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '无数据插入', []);
            http_data(200, $resulArr, $this);
        }
    }
	/**
	 * Notes:显示预约
	 * User: ljx
	 * DateTime: 2020/12/31 10:51
	 */
	public function showRow()
	{
        $keys="order_id";
        $this->hedVerify($keys);
        $result = $this->reserve->showReserve($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '获取失败', []);
            http_data(200, $resulArr, $this);
        }
	}
	public function modifyRow()
	{
		$keys="order_subscribe";
		$this->hedVerify($keys);
		$result = $this->reserve->modifyReserve($this->dataArr,$this->userArr['Mobile']);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '修改成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '修改失败', []);
			http_data(200, $resulArr, $this);
		}
	}
}
