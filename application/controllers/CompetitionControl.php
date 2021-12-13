<?php


class CompetitionControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Competition');
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
		$resultNum = $this->competition->addData($this->dataArr, $this->userArr['Mobile']);
		if (count($resultNum )> 0) {
			$resulArr = build_resulArr('D000', true, '插入成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D002', false, '插入失败', []);
			http_data(200, $resulArr, $this);
		}


	}
    /**
     * Notes:新增总决赛记录
     * User: ljx
     *
     */
    public function newallRow()
    {
        $keys="phone";
        $this->hedVerify($keys);
        $resultNum = $this->competition->addallData($this->dataArr, $this->userArr['Mobile']);
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
        $result = $this->competition->imageuploaddetail($this->dataArr);
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
        $result = $this->competition->getimagedetail($this->dataArr);
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
        $result = $this->competition->imageuploadcover($this->dataArr);
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
        $result = $this->competition->getimagecover($this->dataArr);
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
		$keys="DataScope,powerdept,rows,pages,competition_id";
		$this->hedVerify($keys);
		$result = $this->competition->getcompetition($this->dataArr);
		if (count($result) >= 0) {
			$resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '获取失败', []);
			http_data(200, $resulArr, $this);
		}
	}

    public function getonlyRow()
    {
        $keys="DataScope,powerdept,rows,pages,competition_id";
        $this->hedVerify($keys);
        $result = $this->competition->getonlycompetition($this->dataArr);
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
        $keys="competition_id";
        $this->hedVerify($keys);
        $result = $this->competition->publishaa($this->dataArr);
        if (count($result) >= 0) {
            $resulArr = build_resulArr('D000', true, '发布成功', []);
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '发布失败', []);
            http_data(200, $resulArr, $this);
        }


    }

    public function finallycompetitionRow()
    {
        $keys="competition_id";
        $this->hedVerify($keys);
        $result = $this->competition->finallycompetition($this->dataArr);
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
        $keys="competition_id,rows,pages,DataScope,powerdept";
        $this->hedVerify($keys);
        $result = $this->competition->getspecification($this->dataArr);
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
		$keys="competition_id";
		$this->hedVerify($keys);
//		$this->hedVerify();
		$result = $this->competition->delcompetition($this->dataArr);
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
        $result = $this->competition->modifycompetition($this->dataArr, $this->userArr['Mobile']);
        if ($result != 0) {
            $resulArr = build_resulArr('D000', true, '修改成功', []);
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '修改失败', []);
            http_data(200, $resulArr, $this);
        }
    }

//    public function modifyallRow()
//    {
//        $keys="phone";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->competition->modifyallcompetition($this->dataArr, $this->userArr['Mobile']);
//        if ($result!=0) {
//            $resulArr = build_resulArr('D000', true, '修改成功', []);
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '修改失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//
//
//	}

    public function showRow()
    {
        $this->hedVerify();//前置验证
        $result = $this->competition->showdept($this->dataArr);
        if (count($result) > 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }




}
