<?php


/**
 * Class Post ’岗位类
 */
class Activity extends HTY_service
{
	/**
	 * Dept constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Sys_Model');
		$this->load->helper('tool');
		$this->load->helper('qrcode');

	}
	/**
	 * Notes:新增活动
	 * User: liujunx
	 * @param array $indData
	 * @param $by /添加人员
	 * @return mixed
	 */
	public function addData($indData = [], $by)
	{
		$indData['activity_created_by'] = $by;
		$indData['activity_created_time'] = date('Y-m-d H:i');
        $indData['activity_status'] = "未发布";
		$postname=$this->Sys_Model->table_seleRow('activity_id',"activity",array('activity_name'=>$indData['activity_name']), $like=array());
		if ($postname){
			$results = [];
		    return $results;
	}else{
            $result=$this->Sys_Model->table_addRow("activity", $indData, 1);

            return $result;
		}
	}
    //图片详情上传
    public function imageuploaddetail()
    {
        $resultvalue = array();

        $dir = './public/activitygraphic';
        $pptfiles=[];
        if (is_dir($dir) or mkdir($dir)) {
            $files=$_FILES;
            foreach ($files as $file)
            {
                $filename=time().rand(19,99). '.jpg';
                $file_tmp = $file['tmp_name'];
                $savePath=$dir."/".$filename;
                $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件
                if ($move_result) {//上传成功
                    array_push($pptfiles,$filename);
                } else {
                    //上传失败
                    $resultvalue=[];
                    return $resultvalue;
                }
            }
            $pptfiles=join(',',$pptfiles);
            $resultvalue['activity_graphic']=$pptfiles;
            return $resultvalue;
        }
    }
    //获取图片详情
    public function getimagedetail($pic){
        $resultvalue=array();
        $dir_original='./public/activitygraphic';
        $handler = opendir($dir_original);
        if($handler){
            $dir_original=str_replace('.','',$dir_original);
            $arrdirfiles=array();
            $dirfilename = "https://hftx.fzz.cn" . $dir_original .'/'. $pic['activity_graphic'] ;
            //5、关闭目录
            closedir($handler);
            $resultvalue['name']=$pic['activity_graphic'];
            $resultvalue['url']=$dirfilename;
            $resultvalue['raw']['type']="image/jpg";
            return $resultvalue;
        }
    }
    //图片封面上传
    public function imageuploadcover()
    {
        $resultvalue = array();

        $dir = './public/activitycover';
        $pptfiles=[];
        if (is_dir($dir) or mkdir($dir)) {
            $files=$_FILES;

            foreach ($files as $file)
            {
                $filename=time().rand(19,99). '.jpg';
                $file_tmp = $file['tmp_name'];
                $savePath=$dir."/".$filename;
                $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件
                if ($move_result) {//上传成功
                    array_push($pptfiles,$filename);
                } else {
                    //上传失败
                    $resultvalue=[];
                    return $resultvalue;
                }
            }
            $pptfiles=join(',',$pptfiles);
            $resultvalue['activity_cover']=$pptfiles;
            return $resultvalue;
        }
    }
    //获取图片封面
    public function getimagecover($pic){
        $resultvalue=array();
        $dir_original='./public/activitycover';
        //2、循环的读取目录下的所有文件
        //其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
        $handler = opendir($dir_original);
        if($handler){
            $dir_original=str_replace('.','',$dir_original);
            $dirfilename = "https://hftx.fzz.cn" . $dir_original .'/'. $pic['activity_cover'];
            //5、关闭目录
            closedir($handler);
            $resultvalue['name']=$pic['activity_cover'];
            $resultvalue['url']=$dirfilename;
            $resultvalue['raw']['type']="image/jpg";
            return $resultvalue;
        }
    }
//获取
    public function getactivity($searchWhere = []) //查询到赛事表
    {
            $where = "";
            if (count($searchWhere) > 0) {
                if ($searchWhere['activity_id'] != '') {//赛事ID  下拉
                    $where = $where . " and activity_id in('{$searchWhere['activity_id']}')";
                }
                $pages = $searchWhere['pages'];
                $rows = $searchWhere['rows'];
                $deptTmpArr=$this->get_Activitydata($pages, $rows,$where);
            }
        return $deptTmpArr;
    }

//搜索活动信息页面 分页
    public function get_Activitydata($pages,$rows,$wheredata){
        //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
        $offset=($pages-1)*$rows;//计算偏移量
        $sql_query="Select DISTINCT activity_id,activity_name,activity_describe,activity_type,activity_graphic,activity_cover,activity_ishome,activity_sex_limit,activity_limitup,activity_limitdown,activity_number_limit,activity_beginDate,activity_endDate,activity_signBegin,activity_signEnd,activity_signIntegral,activity_status,activity_signQRcode,activity_signPrice,activity_created_time,activity_sign_model from activity  where  1=1  ";
        $sql_query_where=$sql_query.$wheredata;
        if($wheredata!="")
        {
            $sql_query=$sql_query_where;
        }
        $sql_query_total=$sql_query;
        $sql_query=$sql_query." order by activity_created_time desc limit ".$offset.",".$rows;
        $query = $this->db->query($sql_query);
        $ss=$this->db->last_query();
        $r_total=$this->db->query($sql_query_total)->result_array();
        $row_arr=$query->result_array();
        for($i=0;$i<count($r_total);$i++){
            $r_total[$i]['activity_cover']="https://hftx.fzz.cn/public/activitycover/".$r_total[$i]['activity_cover'];
            $r_total[$i]['activity_graphic']="https://hftx.fzz.cn/public/activitygraphic/".$r_total[$i]['activity_graphic'];
        }
        $result['total']=count($r_total);//获取总行数
        $result["data"] = $row_arr;
        $result["alldata"] = $r_total;
        return $result;
    }
//删除
	public function delactivity($postId = [])
	{
        $result=$this->Sys_Model->table_del("activity",$postId);
        return $result;
	}
//发布
    public function publishaa($postId = [])
    {
        $where['activity_signQRcode']=getCode("pages/sign/sign","activity_id",$postId['activity_id']);
        $where['activity_status']="已发布";
        $result=$this->Sys_Model->table_updateRow('activity', $where, array('activity_id' => $postId['activity_id']));
        return $result;
    }
//修改
	public function modifyactivity($values,$by)
    {
        $values['activity_updated_by'] = $by;
        $values['activity_updated_time'] = date('Y-m-d H:i');
        $postname = $this->Sys_Model->table_seleRow('activity_id', "activity", array('activity_name' => $values['activity_name']), $like = array());
        $resluts=[];
        if ($postname) {
            if ($postname[0]['activity_id'] == $values['activity_id']) {
                $resluts=$this->Sys_Model->table_updateRow('activity', $values, array('activity_id' => $values['activity_id']));
            }
            return $resluts;
        }
        $resluts=$this->Sys_Model->table_updateRow('activity', $values, array('activity_id' => $values['activity_id']));
        return $resluts;
    }
//下拉
    public function showactivity()
    {
        $reslut = $this->Sys_Model->table_seleRow('activity_id,activity_name', "activity", $where=array(), $like = array());
        return $reslut;
    }

//结束
    public function finallyactivity($postId = [])
    {
        $where['activity_status']="已结束";
        $result=$this->Sys_Model->table_updateRow('activity', $where, array('activity_id' => $postId['activity_id']));
        return $result;
    }

    //下架
    public function lowactivity($postId = [])
    {
        $where['activity_status']="未发布";
        $result=$this->Sys_Model->table_updateRow('activity', $where, array('activity_id' => $postId['activity_id']));
        return $result;
    }

}







