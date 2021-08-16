<?php


/**
 * Class Post ’岗位类
 */
class Commodity extends HTY_service
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
		$indData['a']['commodity_created_by'] = $by;
		$indData['a']['commodity_created_time'] = date('Y-m-d H:i');
        $indData['a']['commodity_status'] = "未发布";
		$postname=$this->Sys_Model->table_seleRow('commodity_id',"commodity",array('commodity_name'=>$indData['a']['commodity_name']), $like=array());
		if ($postname){
			$result = [];
		    return $result;
	}else{
            $returnInfo = true;
            $this->db->trans_begin();
            $this->Sys_Model->table_addRow("commodity", $indData['a'], 1);
            $competition=$this->db->insert_id();
            $resluts=[];
            foreach ($indData['b'] as $row){
                $row= bykey_reitem($row, 'id');
                $row['commodity_spec_created_by'] = $by;
                $row['commodity_id'] = $competition;
                $row['commodity_spec_created_time'] = date('Y-m-d H:i');
                array_push($resluts,$row);
            }
            $this->Sys_Model->table_addRow("commodity_spec", $resluts, 2);
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

        $dir = './public/commoditygraphic';
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
            $resultvalue['commodity_graphic']=$pptfiles;
            return $resultvalue;
        }
    }
    //获取图片详情
    public function getimagedetail($pic){
        $resultvalue=array();
        $dir_original='./public/commoditygraphic';

        $handler = opendir($dir_original);
        if($handler){
            $dir_original=str_replace('.','',$dir_original);
            $dirfilename = $this->config->item('localpath') . $dir_original .'/'. $pic['commodity_graphic'] ;
            //5、关闭目录
            closedir($handler);
            $resultvalue['name']=$pic['commodity_graphic'];
            $resultvalue['url']=$dirfilename;
            $resultvalue['raw']['type']="image/jpg";
            return $resultvalue;
        }
    }
    //图片封面上传
    public function imageuploadcover()
    {
        $resultvalue = array();

        $dir = './public/commoditycover';
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
            $resultvalue['commodity_cover']=$pptfiles;
            return $resultvalue;
        }
    }
    //获取图片封面
    public function getimagecover($pic){
        $resultvalue=array();
        $dir_original='./public/commoditycover';
        //2、循环的读取目录下的所有文件
        //其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
        $handler = opendir($dir_original);
        if($handler){
            $dir_original=str_replace('.','',$dir_original);
            $dirfilename = $this->config->item('localpath') . $dir_original .'/'.$pic['commodity_cover'] ;
            //5、关闭目录
            closedir($handler);
            $resultvalue['name']=$pic['commodity_cover'];
            $resultvalue['url']=$dirfilename;
            $resultvalue['raw']['type']="image/jpg";
            return $resultvalue;
        }
    }
    //商品规格图片上传
    public function uploadpicture()
    {
        $resultvalue = array();
        $dir = './public/commoditygraphic';
        if (is_dir($dir) or mkdir($dir)) {
            $files=$_FILES;
            $filename=time().rand(111,999). '.jpg';
            $file_tmp = $files['file0']['tmp_name'];
            $savePath=$dir."/".$filename;
            $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件
            if ($move_result) {//上传成功
                $pptfiles=$filename;
            } else {
                //上传失败
                return $resultvalue;
            }
            $resultvalue['commodity_image']=$pptfiles;
            return $resultvalue;
        }
    }
    //获取商品规格图片
    public function getpicture($pic){
        $resultvalue=array();
        $dir_original='./public/commoditygraphic';
        $handler = opendir($dir_original);
        if($handler){
            $dir_original=str_replace('.','',$dir_original);
            $dirfilename = $this->config->item('localpath') . $dir_original .'/'.$pic['commodity_image'] ;
            //5、关闭目录
            closedir($handler);
            $resultvalue['name']=$pic['commodity_image'];
            $resultvalue['url']=$dirfilename;
            $resultvalue['raw']['type']="image/jpg";
            return $resultvalue;
        }
    }
    //后端获取商品规格表
    public function getspecificationtwo($searchWhere = [])
    {
        $deptArr = [];
        $begin = 10;
        $offset = 0;
        $where=[];
        $where['commodity_id'] = $searchWhere['commodity_id'];
//        $competition_final=$this->Sys_Model->table_seleRow('competition_id,competition_final',"competition", $wheredata=array("competition_id"=>$searchWhere['competition_id']), $likedata=array());
//        $searchWhere['competition_final']=$competition_final[0]['competition_final'];
        $totalArr = $this->Sys_Model->table_seleRow('commodity_spec_name,commodity_size,amount,commodity_image,commodity_price  ', "commodity_spec", $where, $likedata = array());
        $results = $this->Sys_Model->table_seleRow_limit("commodity_spec_name,commodity_size,amount,commodity_image,commodity_price ", "commodity_spec", $where, $likedata = array(), $begin, $offset);
        $deptArr['alldata']=$totalArr;
        $deptArr['data']=$results;
        $deptArr['total'] = count($totalArr);//这个商品的规格
        return $deptArr;
    }

    //获取商品规格表
    public function getspecification($searchWhere = [])
    {
        $deptArr = [];
        $begin = 10;
        $offset = 0;
        $where=[];
        $ggg=[];
        $hhh=[];
        $where['commodity_id'] = $searchWhere['commodity_id'];
//        $competition_final=$this->Sys_Model->table_seleRow('competition_id,competition_final',"competition", $wheredata=array("competition_id"=>$searchWhere['competition_id']), $likedata=array());
//        $searchWhere['competition_final']=$competition_final[0]['competition_final'];
        $totalArr = $this->Sys_Model->table_seleRow('commodity_spec_id,commodity_spec_name,commodity_size,amount,commodity_image,commodity_price  ', "commodity_spec", $where, $likedata = array());
        foreach ($totalArr as $row){
            if ($row['commodity_image']!=null) {
                // 添加缩略图路径
                $row['commodity_image'] = $this->config->item('localpath') . '/public/commoditygraphic/' . $row['commodity_image'];
            }
            array_push($ggg,$row);
        }
        $results = $this->Sys_Model->table_seleRow_limit("commodity_spec_id,commodity_spec_name,commodity_size,amount,commodity_image,commodity_price ", "commodity_spec", $where, $likedata = array(), $begin, $offset);
        foreach ($results as $row){
            if ($row['commodity_image']!=null){
                // 添加缩略图路径
                $row['commodity_image']=$this->config->item('localpath') . '/public/commoditygraphic/'.$row['commodity_image'] ;
            }
            array_push($hhh,$row);
        }
        $deptArr['alldata']=$ggg;
        $deptArr['data']=$hhh;
        $deptArr['total'] = count($totalArr);//这个商品的规格
        return $deptArr;
    }

	/**
	 * Notes: 获取商品
	 * User: angelo
	 * DateTime: 2020/12/25 14:16
	 * @param array $searchWhere ‘查询条件
	 * @return array|mixed
	 */
    public function getcommodity($searchWhere = []) //查询到商品表
    {

            $where = "";
            if (count($searchWhere) > 0) {
                if ($searchWhere['commodity_id'] != '') {//商品ID  下拉
                    $where = $where . " and commodity_id in('{$searchWhere['commodity_id']}')";
                }
                $pages = $searchWhere['pages'];
                $rows = $searchWhere['rows'];
                $deptTmpArr=$this->get_commoditydata($pages, $rows,$where);
            }


        return $deptTmpArr;

    }

    public function getphonecommodity($searchWhere = []) //查询到商品表
    {
        $deptTmpArr=$this->getspecification($searchWhere);
        $result=[];
        $hhh=[];
        $ggg=[];
        foreach ($deptTmpArr['alldata'] as $row){
            array_push($hhh,$row['commodity_spec_name']);
            array_push($ggg,$row['commodity_size']);
        }
        $result['one']=array_flip(array_flip($hhh));
        $result['two']=array_flip(array_flip($ggg));
        $result['three']=$deptTmpArr['alldata'];
        return $result;

    }

//搜索商品
    public function get_commoditydata($pages,$rows,$wheredata){
        //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
        $offset=($pages-1)*$rows;//计算偏移量
        $sql_query="Select DISTINCT commodity_id,commodity_name,commodity_describe,commodity_status,commodity_type,commodity_graphic,commodity_cover,commodity_price,commodity_ishome,commodity_carriage,
            commodity_integral,commodity_created_time from commodity  where  1=1 ";
        $sql_query_where=$sql_query.$wheredata;
        if($wheredata!="")
        {
            $sql_query=$sql_query_where;
        }
        $sql_query_total=$sql_query;
        $sql_query=$sql_query." order by commodity_created_time desc limit ".$offset.",".$rows;
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
	 * Notes: 删除商品数据 包括规格
	 * User: ljx
	 * DateTime: 2021/6/25
	 * @param array $postId  岗位ID
	 * @return mixed
	 */
	public function delcommodity($postId = [])
	{

        $result=$this->Sys_Model->table_del("commodity",$postId);
        if(count($result)>0){
            $this->Sys_Model->table_del("commodity_spec",array('commodity_id' => $postId['commodity_id']));
        }
        return $result;
	}
//发布（上架商品）
    public function publishaa($postId = [])
    {
        $where['commodity_status']="已发布";
        $result=$this->Sys_Model->table_updateRow('commodity', $where, array('commodity_id' => $postId['commodity_id']));
        return $result;
    }
	/**
	 * * Notes: 修改商品数据
	 * User: junxiong
	 * DateTime: 2020/6、23
	 * @param array $values
	 * @return mixed
	 */
	public function modifycommodity($values,$by)
	{
		$values['a']['commodity_updated_by'] = $by;
		$values['a']['commodity_updated_time'] = date('Y-m-d H:i');
		$postname=$this->Sys_Model->table_seleRow('commodity_id',"commodity",array('commodity_name'=>$values['a']['commodity_name']), $like=array());
		if ($postname){
			if($postname[0]['commodity_id']==$values['a']['commodity_id']){
                $returnInfo = true;
                $this->db->trans_begin();
				$this->Sys_Model->table_updateRow('commodity', $values['a'], array('commodity_id' => $values['a']['commodity_id']));
                $this->Sys_Model->table_del("commodity_spec",array('commodity_id' => $values['a']['commodity_id']));
                $resluts=[];
                foreach ($values['b'] as $row){
                    $row= bykey_reitem($row, 'id');
                    $row['commodity_spec_created_by'] = $by;
                    $row['commodity_spec_created_time'] = date('Y-m-d H:i');
                    $row['commodity_id'] = $values['a']['commodity_id'];
                    array_push($resluts,$row);
                }
                $this->Sys_Model->table_addRow("commodity_spec", $resluts, 2);
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
            $this->Sys_Model->table_updateRow('commodity', $values['a'], array('commodity_id' => $values['a']['commodity_id']));
            $this->Sys_Model->table_del("commodity_spec",array('commodity_id' => $values['a']['commodity_id']));
            $resluts=[];
            foreach ($values['b'] as $row){
                $row['commodity_spec_created_by'] = $by;
                $row['commodity_spec_created_time'] = date('Y-m-d H:i');
                $row['commodity_id'] = $values['a']['commodity_id'];
                array_push($resluts,$row);
            }
            $this->Sys_Model->table_addRow("commodity_spec", $resluts, 2);
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
//下拉商品
    public function showcommodity()
    {
        $deptArr = $this->Sys_Model->table_seleRow('commodity_id,commodity_name', "commodity", $where=array(), $like = array());
        return $deptArr;
    }
//下架
    public function finallycommodity($postId = [])
    {
        $where['commodity_status']="未发布";
        $result=$this->Sys_Model->table_updateRow('commodity', $where, array('commodity_id' => $postId['commodity_id']));
        return $result;
    }

}







