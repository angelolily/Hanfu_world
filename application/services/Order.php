<?php


/**
 * Class Usermanage ’用户管理类
 */
class Order extends HTY_service
{
	/**
	 * Dept constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Sys_Model');
		$this->load->helper('tool');

	}



	//搜索订单页面 分页
	public function get_orderdata($pages,$rows,$wheredata,$likedata){
		//Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
		$offset=($pages-1)*$rows;//计算偏移量
		$field='Select * ';
		$sql_query=$field." from `order` where 1=1 ".$wheredata;
		if($likedata!=""){//like不为空
		    $sql_query=$sql_query." ".$likedata;
		}
		$sql_query_total=$sql_query;
		$sql_query=$sql_query." order by created_time desc limit ".$offset.",".$rows;
 		$query = $this->db->query($sql_query);
		$ss=$this->db->last_query();
		$r_total=$this->db->query($sql_query_total)->result_array();
		$row_arr=$query->result_array();
		$result['total']=count($r_total);//获取总行数
		$result["data"] = $row_arr;
		return $result;
	}

	/**
	 * Notes: 获取用户信息或者刷新
	 * User: junxiong
	 * DateTime: 2021/1/11 15:04
	 * @param array $searchWhere ‘查询条件
	 * @return array|mixed
	 */
	public function getOrder($searchWhere = [])
	{
		if($searchWhere['DataScope']) {
            $where = "";
            $like = "";
            $curr = $searchWhere['pages'];
            $limit = $searchWhere['rows'];
            if ($searchWhere['order_customer_name'] != "") {
                $like = " and order_customer_name like '%{$searchWhere['order_customer_name']}%'";
            }
            if ($searchWhere['order_capid'] != "") {
                $where = $where . " and  order_capid in('{$searchWhere['order_capid']}')";
            }
            if ($searchWhere['DataScope']==1){
                if ($searchWhere['order_deptid'] != "") {
                    $where = $where . " and  order_deptid in('{$searchWhere['order_deptid']}')";
                }
            }
            if ($searchWhere['DataScope']==3 or $searchWhere['DataScope']==4){
                $all=explode(',',$searchWhere['powerdept']);
                $DeptId = "'" . $all[0] . "'";
                for ($i = 1; $i < count($all); $i++) {
                    $DeptId = $DeptId . ",'" . $all[$i] . "'";
                }
                $where = $where . " and ( order_deptid in({$DeptId})) ";
            }
            $items = $this->get_orderdata($curr, $limit, $where, $like);
        }
		return $items;

	}
//	/**
//	 * * Notes: 修改价格
//	 * User: junxiong
//	 * DateTime: 2021/1/19 10:10
//	 * @param array $values
//	 * @return mixed
//	 */
//	public function modifyMoney($values,$by)
//	{
//		$values['order_updated_by'] = $by;
//		$values['order_updated_time'] = date('Y-m-d H:i');
//        $restulNum = $this->Sys_Model->table_updateRow('cell_order', $values, array('order_id' => $values['order_id']));
//        return $restulNum;
//	}	/**
//	 * * Notes: 修改地址
//	 * User: junxiong
//	 * DateTime: 2021/1/19 10:10
//	 * @param array $values
//	 * @return mixed
//	 */
//	public function modifyAddress($values,$by)
//	{
//        $values['order_updated_by'] = $by;
//        $values['order_updated_time'] = date('Y-m-d H:i');
//        $values = bykey_reitem($values, 'region');
//        $values = bykey_reitem($values, 'address');
//        $restulNum = $this->Sys_Model->table_updateRow('cell_order', $values, array('order_id' => $values['order_id']));
//        return $restulNum;
//	}	/**
//	 * * Notes: 修改物流信息
//	 * User: junxiong
//	 * DateTime: 2021/1/19 10:10
//	 * @param array $values
//	 * @return mixed
//	 */
//	public function modifyLogistics($values,$by)
//	{
//        $values['order_updated_by'] = $by;
//        $values['order_updated_time'] = date('Y-m-d H:i');
//        $restulNum = $this->Sys_Model->table_updateRow('cell_order', $values, array('order_id' => $values['order_id']));
//        return $restulNum;
//	}
//	/**
//	 * * Notes: 显示电子问卷
//	 * User: junxiong
//	 * DateTime: 2021/1/19 10:10
//	 * @param array $values
//	 * @return mixed
//	 */
//	public function showquestion($values)//显示问卷
//	{
//        $restulNum = $this->Sys_Model->table_seleRow('question_id,question_true,question_false,question_date,question_custome,question_order,question_result', 'cell_question', array('question_order' => $values['order_id']));
//        return $restulNum;
//	}
//	/**
//	 * * Notes: 显示订单协议
//	 * User: junxiong
//	 * DateTime: 2021/1/19 10:10
//	 * @param array $values
//	 * @return mixed
//	 */
//	public function showcontract($values)//显示协议
//	{
//        $restulNum = $this->Sys_Model->table_seleRow('order_contract', 'cell_order', array('order_id' => $values['order_id']));
//        $result=[];
//        foreach ($restulNum as $item){
//            if($item['order_contract']){
//                $result['data']='http://wdcells.fjspacecloud.com/wdstem-cells-admin/public/protocol/protocol/'.$item['order_contract'].'.pdf';
//
//            }
//        }
//
//        return $result;
//	}
//
//
//    //电子凭证详情上传
//    public function pdfuploaddetail()
//    {
//        $resultvalue = array();
//        $dir = './public/productimg';
//        $pptfiles=[];
//        if (is_dir($dir) or mkdir($dir)) {
//            $files=$_FILES;
//
//            foreach ($files as $file)
//            {
//                $filename=time().rand(19,99). '.pdf';
//                $file_tmp = $file['tmp_name'];
//                $savePath=$dir."/".$filename;
//                $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件
//                if ($move_result) {//上传成功
//                    array_push($pptfiles,$filename);
//                } else {
//                    //上传失败
//                    $resultvalue=[];
//                    return $resultvalue;
//                }
//            }
//            $pptfiles=join(',',$pptfiles);
//            $resultvalue['certificate_path']=$filename;
//            return $resultvalue;
//        }
//    }
//    //获取电子凭证
//    public function getpdf($pic){
//        $resultvalue=array();
//        $dir_original='./public/productimg';
//        //2、循环的读取目录下的所有文件
//        //其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
//        $handler = opendir($dir_original);
//        if($handler){
//            $dir_original=str_replace('.','',$dir_original);
//            $dirfilename = "http://wdcells.fjspacecloud.com/wdstem-cells-admin" . $dir_original .'/'. $pic['certificate_path'] ;
//            //5、关闭目录
//            closedir($handler);
//            $resultvalue=$dirfilename;
//            return $resultvalue;
//        }
//    }
//
//    /**
//     * Notes:新增电子凭证
//     */
//    public function addcertificate($indData = [], $by)
//    {
//        $indData['certificate_created_by'] = $by;
//        $indData['certificate_created_time'] = date('Y-m-d H:i');
//        $postname=$this->Sys_Model->table_seleRow('certificate_id',"cell_certificate",array('certificate_num'=>$indData['certificate_num']), $like=array());
//        if ($postname){
//            $result = [];
//            return $result;
//        }else{
//            $result = $this->Sys_Model->table_addRow("cell_certificate", $indData, 1);
//            if($result>=0)
//            {
//                $result = $this->Sys_Model->table_updateRow("cell_order",['order_statue'=>'已完成'], ['order_id'=>$indData['certificate_orderid']]);
//            }
//
//
//            return $result;
//        }
//    }
////    /**
////     * * Notes: 体检报告下载
////     */
////    public function uploadhealth($values)
////    {
////        $restulNum = $this->Sys_Model->table_seleRow('order_health', 'cell_order', array('order_id' => $values['order_id']));
////        return $restulNum;
////    }
//    //压缩打包目录下载
//    public function uploadhealth($values){
//        $zipfile=[];
//        $restulNum = $this->Sys_Model->table_seleRow('order_health', 'cell_order', array('order_id' => $values['order_id']));
//        if($restulNum[0]['order_health']!=""){
//            $zipfile=rand(100000,999999).'.zip';
//            $dir1='./public/medical/'.$restulNum[0]['order_health'];
//            $dir2='./public/productimg/'.$zipfile;
//            if(is_dir($dir1)){
//                $this->load->library('zip');
//                $this->zip->clear_data();
//                $this->zip->read_dir($dir1, FALSE);
//                $this->zip->archive($dir2);
//                $zipfile="http://wdcells.fjspacecloud.com/wdstem-cells-admin/public/productimg/". $zipfile ;
//            }
//        }
//        return $zipfile;
//    }
//
//    //查看
//    public function showhealth($values){
//        $restulNum = $this->Sys_Model->table_seleRow('order_health', 'cell_order', array('order_id' => $values['order_id']));
//        if($restulNum[0]['order_health']!=""){
//            $dir1='./public/medical/'.$restulNum[0]['order_health'];
//            //2、循环的读取目录下的所有文件
//            //其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
//            $handler = opendir($dir1);
//            if($handler){
////                $dir_original=str_replace('.','',$dir1);
//                $result=array();
//                while( ($filename = readdir($handler)) !== false ) {
//                    //3、目录下都会有两个文件，名字为’.'和‘..’，不要对他们进行操作
//                    if($filename != "." && $filename != ".."){
//                        $dirfilename = "http://wdcells.fjspacecloud.com/wdstem-cells-admin/public/medical/". $restulNum[0]['order_health']."/".$filename;
////                        $resultvalue['name']=$filename;
//                        $resultvalue=$dirfilename;
////                        $resultvalue['raw']['type']="image/jpg";
//                        array_push($result,$resultvalue);
//
//                    }
//                }
//                closedir($handler);
//            }
//        }
//     return $result;
//    }
//
//
//    /**
//     * * Notes: 看完体检报告修改状态信息
//     * User: junxiong
//     * DateTime: 2021/1/19 10:10
//     * @param array $values
//     * @return mixed
//     */
//    public function modifystate($values,$by)
//    {
//        $values['order_updated_by'] = $by;
//        $values['order_statue'] = "待付款";
//        $values['order_updated_time'] = date('Y-m-d H:i');
//        $restulNum = $this->Sys_Model->table_updateRow('cell_order', $values, array('order_id' => $values['order_id']));
//        return $restulNum;
//    }

}







