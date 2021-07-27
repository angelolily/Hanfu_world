<?php


class OrderControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Order');
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
	 * Notes:获取订单信息
	 * User: angelo
	 * DateTime: 2020/12/25 10:01
	 */

	public function getRow()
	{
		$keys="pages,rows,order_customer_name,order_capid,DataScope,order_deptid,powerdept";
		$this->hedVerify($keys);
		$result = $this->order->getOrder($this->dataArr);
		if (count($result) >= 0) {
			$resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '获取失败', []);
			http_data(200, $resulArr, $this);
		}
	}
//	public function modifypriceRow()//修改价格
//	{
//		$keys="order_id,order_price";
//		$this->hedVerify($keys);
////		$this->hedVerify();
//		$result = $this->order->modifyMoney($this->dataArr, $this->userArr['Mobile']);
//		if (count($result) > 0) {
//			$resulArr = build_resulArr('D000', true, '修改成功', []);
//			http_data(200, $resulArr, $this);
//		} else {
//			$resulArr = build_resulArr('D003', false, '修改失败', []);
//			http_data(200, $resulArr, $this);
//		}
//
//	}
//	public function modifyaddressRow() //修改地址
//	{
//		$keys="order_id,order_lg_consignee,order_lg_address,order_lg_phone";
//		$this->hedVerify($keys);
////		$this->hedVerify();
//		$result = $this->order->modifyAddress($this->dataArr, $this->userArr['Mobile']);
//		if (count($result) > 0) {
//			$resulArr = build_resulArr('D000', true, '修改成功', []);
//			http_data(200, $resulArr, $this);
//		} else {
//			$resulArr = build_resulArr('D003', false, '修改失败', []);
//			http_data(200, $resulArr, $this);
//		}
//
//	}
//	public function modifylogisticsRow() //修改物流
//	{
//		$keys="order_id,order_logistics,order_lg_name";
//		$this->hedVerify($keys);
////		$this->hedVerify();
//		$result = $this->order->modifyLogistics($this->dataArr, $this->userArr['Mobile']);
//		if (count($result) > 0) {
//			$resulArr = build_resulArr('D000', true, '修改成功', []);
//			http_data(200, $resulArr, $this);
//		} else {
//			$resulArr = build_resulArr('D003', false, '修改失败', []);
//			http_data(200, $resulArr, $this);
//		}
//
//	}
//    public function modifystatueRow() //修改状态
//    {
//        $keys="order_id";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->modifystate($this->dataArr, $this->userArr['Mobile']);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '修改成功', []);
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '修改失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }
//    public function showQuestion()  //查看电子问卷
//    {
//        $keys="order_id";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->showquestion($this->dataArr);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '显示失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }
//    public function showContract()  //查看协议
//    {
//        $keys="order_id";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->showcontract($this->dataArr);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '显示失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }
//    public function addCertificate()  //新增电子凭证
//    {
//        $keys="certificate_num,certificate_type,certificate_name,certificate_path,certificate_orderid,certificate_custome";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->addcertificate($this->dataArr, $this->userArr['Mobile']);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '新增成功', []);
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '新增失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }
//    public function pdfuploaddetail()  //上传pdf
//    {
////        $keys="order_id";
////        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->pdfuploaddetail($this->dataArr);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '上传成功', json_encode($result));
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '上传失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }
//    public function getpdf()   //获取PDF
//    {
//        $keys="certificate_path";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->getpdf($this->dataArr);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '显示失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }
//
//    public function uploadhealth()   //体检报告下载
//    {
//        $keys="order_id";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->uploadhealth($this->dataArr);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '下载成功', json_encode($result));
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '下载失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }
//    public function showhealth()   //查看体检报告
//    {
//        $keys="order_id";
//        $this->hedVerify($keys);
////		$this->hedVerify();
//        $result = $this->order->showhealth($this->dataArr);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '查看成功', json_encode($result));
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '查看失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//    }


}
