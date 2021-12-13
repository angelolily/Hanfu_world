<?php


/**
 * Class Post ’岗位类
 */
class Competition extends HTY_service
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
	 * Notes:新增预赛数据
	 * User: liujunx
	 * @param array $indData
	 * @param $by /添加人员
	 * @return mixed
	 */
	public function addData($indData = [], $by)
	{
		$indData['a']['competition_created_by'] = $by;
		$indData['a']['competition_created_time'] = date('Y-m-d H:i');
        $indData['a']['competition_status'] = "未发布";
        $indData['a']['competition_type'] = "预赛";

		$postname=$this->Sys_Model->table_seleRow('competition_id',"competition",array('competition_name'=>$indData['a']['competition_name']), $like=array());
		if ($postname){
			$result = [];
		    return $result;
	}else{
            $returnInfo = true;
            $this->db->trans_begin();
            $this->Sys_Model->table_addRow("competition", $indData['a'], 1);
            $competition=$this->db->insert_id();
            $resluts=[];
            foreach ($indData['b'] as $row){
                $row['created_by'] = $by;
                $row['relevancy_id'] = $competition;
                $row['created_time'] = date('Y-m-d H:i');
                $row['competition_sign_qrcode']=getNormalCode("pages/detail/match","sid",$row['DeptId'].",".$competition);//报名
                array_push($resluts,$row);
            }
            $this->Sys_Model->table_addRow("specification", $resluts, 2);
            $row=$this->db->affected_rows();
            if (($this->db->trans_status() === FALSE) && $row<=0){
                $this->db->trans_rollback();
                $returnInfo = false;
            }else{
                $this->db->trans_commit();
            }
            return $returnInfo;

		}
	}

    /**
     * Notes:新增总决赛数据
     * User: liujunx
     * @param array $indData
     * @param $by /添加人员
     * @return mixed
     */
    public function addallData($indData = [], $by)//插入总决赛
    {
        $indData['a']['competition_created_by'] = $by;
        $indData['a']['competition_created_time'] = date('Y-m-d H:i');
        $indData['a']['competition_status'] = "报名中";
        $indData['a']['competition_type'] = "总决赛";
//        if($indData['a']['competition_ishome']==true){
//            $indData['a']['competition_ishome']=1;
//        }else{
//            $indData['a']['competition_ishome']=0;
//        }
        $postname=$this->Sys_Model->table_seleRow('competition_id',"competition",array('competition_name'=>$indData['a']['competition_name']), $like=array());
        if ($postname){
            $result = [];
            return $result;
        }else{
            $returnInfo = true;
            $this->db->trans_begin();
            $where='competition_id='.$indData['a']['competition_id'];
            $indData['a']=$this->dataArr = bykey_reitem($indData['a'], 'competition_id');
            $this->Sys_Model->table_addRow("competition", $indData['a'], 1);
            $value['competition_final']=$this->db->insert_id();
            $value['competition_status'] = "评奖中";
            $this->Sys_Model->table_updateRow("competition",$value,$where);//更新预赛 总决赛字段
            $resluts=[];
            foreach ($indData['b'] as $row){
                $row['relevancy_id'] = $value['competition_final'];
                $row['created_by'] = $by;
                $row['created_time'] = date('Y-m-d H:i');
//                $row['competition_sign_qrcode']=getCode("pages/detail/match","cid",$row['competition_deptid']);//报名
                array_push($resluts,$row);
            }
            $this->Sys_Model->table_addRow("specification", $resluts, 2);//新增一条总赛区的规格
            $row=$this->db->affected_rows();
            if (($this->db->trans_status() === FALSE) && $row<=0){
                $this->db->trans_rollback();
                $returnInfo = false;
            }else{
                $this->db->trans_commit();
            }
            return $returnInfo;
        }
    }

    //图片详情上传
    public function imageuploaddetail()
    {
        $resultvalue = array();

        $dir = './public/comgraphic';
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
            $resultvalue['competition_graphic']=$pptfiles;
            return $resultvalue;
        }
    }
    //获取图片详情
    public function getimagedetail($pic){
        $resultvalue=array();
        $dir_original='./public/comgraphic';

        $handler = opendir($dir_original);
        if($handler){
            $dir_original=str_replace('.','',$dir_original);
            $arrdirfiles=array();
            $dirfilename = "https://hftx.fzz.cn" . $dir_original .'/'. $pic['competition_graphic'] ;
            //5、关闭目录
            closedir($handler);
            $resultvalue['name']=$pic['competition_graphic'];
            $resultvalue['url']=$dirfilename;
            $resultvalue['raw']['type']="image/jpg";
            return $resultvalue;
        }
    }
    //图片封面上传
    public function imageuploadcover()
    {
        $resultvalue = array();

        $dir = './public/comcover';
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
            $resultvalue['competition_cover']=$pptfiles;
            return $resultvalue;
        }
    }
    //获取图片封面
    public function getimagecover($pic){
        $resultvalue=array();
        $dir_original='./public/comcover';
        //2、循环的读取目录下的所有文件
        //其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
        $handler = opendir($dir_original);
        if($handler){
            $dir_original=str_replace('.','',$dir_original);
            $dirfilename = "https://hftx.fzz.cn" . $dir_original .'/'. $pic['competition_cover'];
            //5、关闭目录
            closedir($handler);
            $resultvalue['name']=$pic['competition_cover'];
            $resultvalue['url']=$dirfilename;
            $resultvalue['raw']['type']="image/jpg";
            return $resultvalue;
        }
    }
    //获取规格表
    public function getspecification($searchWhere = [])
    {
        if($searchWhere['DataScope']) {
            $deptArr = [];
            $begin = $searchWhere['rows'];
            $offset = ($searchWhere['pages'] - 1) * $searchWhere['rows'];
            $where=[];
            if($searchWhere['DataScope']==3){
                $where['DeptId']=$searchWhere['powerdept'];
            }
            $where['relevancy_id'] = $searchWhere['competition_id'];
//        $competition_final=$this->Sys_Model->table_seleRow('competition_id,competition_final',"competition", $wheredata=array("competition_id"=>$searchWhere['competition_id']), $likedata=array());
//        $searchWhere['competition_final']=$competition_final[0]['competition_final'];
            $totalArr = $this->Sys_Model->table_seleRow('relevancy_id,competition_sign_qrcode,competition_vote_qrcode,DeptId,DeptName,Phone,unit_price,amount,competition_sign_begin,competition_sign_end,competition_is_vate ', "specification", $where, $likedata = array());
            $results = $this->Sys_Model->table_seleRow_limit("relevancy_id,competition_sign_qrcode,competition_vote_qrcode,DeptId,DeptName,Phone,unit_price,amount,competition_sign_begin,competition_sign_end,competition_is_vate ", "specification", $where, $likedata = array(), $begin, $offset);
            $deptArr['total'] = count($totalArr);//查询这个赛区会查询到总决赛或预赛的规格表
            $deptArr['data'] = $results;
            $deptArr["alldata"] = $totalArr;
        }
        return $deptArr;
    }

	/**
	 * Notes: 获取赛事
	 * User: angelo
	 * DateTime: 2020/12/25 14:16
	 * @param array $searchWhere ‘查询条件
	 * @return array|mixed
	 */
    public function getcompetition($searchWhere = []) //查询到赛事表
    {
        if($searchWhere['DataScope']) {
            $where = "";
            if (count($searchWhere) > 0) {
                if ($searchWhere['competition_id'] != '') {//赛事ID  下拉
                    $where = $where . " and c.competition_id in('{$searchWhere['competition_id']}')";
                }
                $pages = $searchWhere['pages'];
                $rows = $searchWhere['rows'];
                if($searchWhere['DataScope']==3 or $searchWhere['DataScope']==4){
                    $all=explode(',',$searchWhere['powerdept']);
                    $DeptId = "'" . $all[0] . "'";
                    for ($i = 1; $i < count($all); $i++) {
                        $DeptId = $DeptId . ",'" . $all[$i] . "'";
                    }
                    $where = $where . " and s.DeptId  in({$DeptId})";
                    $where = $where . " and c.competition_status not in ('未发布')";
                }
                if(isset($searchWhere['spec_id'])){
                    if($searchWhere['spec_id'] != ''){
                        $where = $where . " and s.spec_id = {$searchWhere['spec_id']}";
                    }
                }
                $deptTmpArr=$this->get_Competitiondata($pages, $rows,$where);
            }
        }
        return $deptTmpArr;

    }

//搜索赛事信息页面 分页
    public function get_Competitiondata($pages,$rows,$wheredata){
        //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
        $offset=($pages-1)*$rows;//计算偏移量
        $sql_query="Select DISTINCT s.spec_id, s.DeptName,s.competition_sign_begin as s_sign_b,s.competition_sign_end as s_sign_e, c.* from specification as s right JOIN competition as c on s.relevancy_id=c.competition_id where c.competition_id is not null ";
        $sql_query_where=$sql_query.$wheredata;
        if($wheredata!="")
        {
            $sql_query=$sql_query_where;
        }
        $sql_query_total=$sql_query;
        $sql_query=$sql_query." order by c.competition_created_time desc limit ".$offset.",".$rows;
        $query = $this->db->query($sql_query);
        $ss=$this->db->last_query();
        $r_total=$this->db->query($sql_query_total)->result_array();
        $row_arr=$query->result_array();
        $result['total']=count($r_total);//获取总行数
        $result["data"] = $row_arr;
        $result["alldata"] = $r_total;
        return $result;
    }




    public function getonlycompetition($searchWhere = []) //查询到赛事表
    {
        if($searchWhere['DataScope']) {
            $where = "";
            if (count($searchWhere) > 0) {
                if ($searchWhere['competition_id'] != '') {//赛事ID  下拉
                    $where = $where . " and competition_id in('{$searchWhere['competition_id']}')";
                }
                $pages = $searchWhere['pages'];
                $rows = $searchWhere['rows'];
                if($searchWhere['DataScope'] == 3 or $searchWhere['DataScope'] == 4){
                    $where = $where." and competition_id in (select relevancy_id from specification where DeptId = '{$searchWhere['powerdept']}')";
                }

                $deptTmpArr=$this->get_Competitiononlydata($pages, $rows,$where);
            }
        }
        return $deptTmpArr;

    }

//搜索赛事信息页面 分页
    public function get_Competitiononlydata($pages,$rows,$wheredata){
        //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
        $offset=($pages-1)*$rows;//计算偏移量
        $sql_query="Select DISTINCT * from  competition  where 1=1 and competition_id is not null  ";
        $sql_query_where=$sql_query.$wheredata;
        if($wheredata!="")
        {
            $sql_query=$sql_query_where;
        }
        $sql_query_total=$sql_query;
        $sql_query=$sql_query." order by competition_created_time desc limit ".$offset.",".$rows;
        $query = $this->db->query($sql_query);
        $ss=$this->db->last_query();
        $r_total=$this->db->query($sql_query_total)->result_array();
        $row_arr=$query->result_array();
        $result['total']=count($r_total);//获取总行数
        $result["data"] = $row_arr;
        $result["alldata"] = $r_total;
        return $result;
    }
	/**
	 * Notes: 删除赛事数据
	 * User: ljx
	 * DateTime: 2021/6/25
	 * @param array $postId  岗位ID
	 * @return mixed
	 */
	public function delcompetition($postId = [])
	{

        $result=$this->Sys_Model->table_del("competition",$postId);
        if(count($result)>0){
            $this->Sys_Model->table_del("specification",array('relevancy_id' => $postId['competition_id']));
        }
        return $result;
	}
//发布
    public function publishaa($postId = [])
    {
        $where['competition_status']="报名中";
        $result=$this->Sys_Model->table_updateRow('competition', $where, array('competition_id' => $postId['competition_id']));
        return $result;
    }
	/**
	 * * Notes: 修改赛事预赛数据
	 * User: junxiong
	 * DateTime: 2020/6、23
	 * @param array $values
	 * @return mixed
	 */
	public function modifycompetition($values,$by)
	{
		$values['a']['competition_updated_by'] = $by;
		$values['a']['competition_updated_time'] = date('Y-m-d H:i');
		$postname=$this->Sys_Model->table_seleRow('competition_id',"competition",array('competition_name'=>$values['a']['competition_name']), $like=array());
		if ($postname){
			if($postname[0]['competition_id']==$values['a']['competition_id']){
                $returnInfo = true;
                $this->db->trans_begin();
				$this->Sys_Model->table_updateRow('competition', $values['a'], array('competition_id' => $values['a']['competition_id']));
                $this->Sys_Model->table_del("specification",array('relevancy_id' => $values['a']['competition_id']));
                $resluts=[];
                foreach ($values['b'] as $row){
                    $row['created_by'] = $by;
                    $row['created_time'] = date('Y-m-d H:i');
                    $row['relevancy_id'] = $values['a']['competition_id'];
                    $row['competition_sign_qrcode']=getNormalCode("match?".$row['DeptId'].",".$values['a']['competition_id']);//报名
                    array_push($resluts,$row);
                }
                $this->Sys_Model->table_addRow("specification", $resluts, 2);
                $row=$this->db->affected_rows();
                if (($this->db->trans_status() === FALSE) && $row<=0){
                    $this->db->trans_rollback();
                    $returnInfo = false;
                }else{
                    $this->db->trans_commit();
                }
                return $returnInfo;
			}
		}else{
            $returnInfo = true;
            $this->db->trans_begin();
            $this->Sys_Model->table_updateRow('competition', $values['a'], array('competition_id' => $values['a']['competition_id']));

            $this->Sys_Model->table_del("specification",array('relevancy_id' => $values['a']['competition_id']));

            $resluts=[];
            foreach ($values['b'] as $row){
                $row['created_by'] = $by;
                $row['created_time'] = date('Y-m-d H:i');
                $row['competition_sign_qrcode']=getNormalCode("match?".$row['DeptId'].",".$values['a']['competition_id']);//报名
                array_push($resluts,$row);
            }
            $this->Sys_Model->table_addRow("specification", $resluts, 2);
            $row=$this->db->affected_rows();
            if (($this->db->trans_status() === FALSE) && $row<=0){
                $this->db->trans_rollback();
                $returnInfo = false;
            }else{
                $this->db->trans_commit();
            }
            return $returnInfo;
        }
	}

    public function showdept()
    {
        $reslut=[];
        $deptArr = $this->Sys_Model->table_seleRow('DeptId,DeptName,Phone', "base_dept", $where=array("Status"=>"0","DelFlag"=>"1"), $like = array());
        foreach ($deptArr as $row){
            $row['unit_price']="";
            $row['amount']="";
            $row['competition_sign_begin']="";
            $row['competition_sign_end']="";
            $row['competition_is_vate']="0";
            $row['competition_sign_qrcode']="";
            $row['competition_vote_qrcode']="";
            array_push($reslut,$row);
        }
        return $reslut;
    }

//结束
    public function finallycompetition($postId = [])
    {
        $where['competition_status']="已结束";
        $result=$this->Sys_Model->table_updateRow('competition', $where, array('competition_id' => $postId['competition_id']));
        return $result;
    }

}







