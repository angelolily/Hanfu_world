<?php


class CommodityControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Commodity');
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
	 * Notes:新增记录
	 * User: ljx
	 *
	 */
	public function newRow()
	{
		$keys="phone";
		$this->hedVerify($keys);
		$resultNum = $this->commodity->addData($this->dataArr, $this->userArr['Mobile']);
		if (count($resultNum )> 0) {
			$resulArr = build_resulArr('D000', true, '插入成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D002', false, '插入失败', []);
			http_data(200, $resulArr, $this);
		}


	}


    public function Uploaddetail()
    {
        $result = $this->commodity->imageuploaddetail($this->dataArr);
        if (count($result )> 0) {
            $resulArr = build_resulArr('D000', true, '插入成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '插入失败', []);
            http_data(200, $resulArr, $this);
        }
    }

    public function finddetail()
    {
        $this->hedVerify();//前置验证
        $result = $this->commodity->getimagedetail($this->dataArr);
        if (count($result)> 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }

    public function Uploadcover()
    {
        $result = $this->commodity->imageuploadcover($this->dataArr);
        if (count($result )> 0) {
            $resulArr = build_resulArr('D000', true, '导入成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '导入失败', []);
            http_data(200, $resulArr, $this);
        }
    }

    public function findcover()
    {
        $this->hedVerify();//前置验证
        $result = $this->commodity->getimagecover($this->dataArr);
        if (count($result)> 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }

	public function getRow()
	{
		$keys="rows,pages,commodity_id";
		$this->hedVerify($keys);
		$result = $this->commodity->getcommodity($this->dataArr);
		if (count($result) >= 0) {
			$resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '获取失败', []);
			http_data(200, $resulArr, $this);
		}


	}

    public function publishRow()
    {
        $keys="commodity_id";
        $this->hedVerify($keys);
        $result = $this->commodity->publishaa($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '发布成功', []);
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '发布失败', []);
            http_data(200, $resulArr, $this);
        }


    }

    public function finallycommodityRow()
    {
        $keys="commodity_id";
        $this->hedVerify($keys);
        $result = $this->commodity->finallycommodity($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '结束成功', []);
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '结束失败', []);
            http_data(200, $resulArr, $this);
        }


    }

    public function getspecificationRow()
    {
        $keys="commodity_id,rows,pages";
        $this->hedVerify($keys);
        $result = $this->commodity->getspecification($this->dataArr);
        if (count($result) > 0) {
            $resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '获取失败', []);
            http_data(200, $resulArr, $this);
        }


    }

	public function delRow()
	{
		$keys="commodity_id";
		$this->hedVerify($keys);
//		$this->hedVerify();
		$result = $this->commodity->delcommodity($this->dataArr);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '删除成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '删除失败', []);
			http_data(200, $resulArr, $this);
		}
	}

	public function modifyRow()
    {
        $keys="phone";
        $this->hedVerify($keys);
//		$this->hedVerify();
        $result = $this->commodity->modifycommodity($this->dataArr, $this->userArr['Mobile']);
        if ($result != 0) {
            $resulArr = build_resulArr('D000', true, '修改成功', []);
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '修改失败', []);
            http_data(200, $resulArr, $this);
        }
    }


//下拉
    public function showRow()
    {
        $this->hedVerify();//前置验证
        $result = $this->commodity->showcommodity($this->dataArr);
        if (count($result) > 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }




}
