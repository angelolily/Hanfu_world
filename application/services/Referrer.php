<?php


/**
 * Class Post ’岗位类
 */
class Referrer extends HTY_service
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

    public function getreferrer($searchWhere = []) //查询到赛事表
    {
            $like = "";
            if (count($searchWhere) > 0) {
                if ($searchWhere['referrer_name'] != "") {
                    $like = " and referrer_name like '%{$searchWhere['referrer_name']}%'";
                }
                if ($searchWhere['referrer_phone'] != "") {
                    $like = " and referrer_phone like '%{$searchWhere['referrer_phone']}%'";
                }
                $pages = $searchWhere['pages'];
                $rows = $searchWhere['rows'];
                $deptTmpArr=$this->get_Referrerdata($pages, $rows,$like);
            }
        return $deptTmpArr;
    }

//搜索活动信息页面 分页
    public function get_Referrerdata($pages,$rows,$likedata){
        //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
        $offset=($pages-1)*$rows;//计算偏移量
        $sql_query="Select * from referrer  where  1=1  ";
        $sql_query=$sql_query.$likedata;
        $sql_query_total=$sql_query;
        $sql_query=$sql_query." order by created_time desc limit ".$offset.",".$rows;
        $query = $this->db->query($sql_query);
        $ss=$this->db->last_query();
        $r_total=$this->db->query($sql_query_total)->result_array();
        $row_arr=$query->result_array();
        $result['total']=count($r_total);//获取总行数
        $result["data"] = $row_arr;
        $result["alldata"] = $r_total;
        return $result;
    }

}







