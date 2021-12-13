<?php


/**
 * Class Post ’岗位类
 */
class Vote extends HTY_service
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
	 * Notes: 获取报名人员信息
	 * User: angelo
	 * DateTime: 2020/12/25 14:16
	 * @param array $searchWhere ‘查询条件
	 * @return array|mixed
	 */
	public function getVote($searchWhere = [])
	{
        if($searchWhere['DataScope']) {
            $where = "";
            $like = "";

            if (count($searchWhere) > 0) {

                if ($searchWhere['competition_id'] != '') {//赛事ID  下拉
                    $where = $where . " and competition_id in('{$searchWhere['competition_id']}')";
                }
                if($searchWhere['vote_name']!="")
                {
                    $like=" and vote_name like '%{$searchWhere['vote_name']}%'";
                }
                if($searchWhere['DataScope']==1) {
                    if ($searchWhere['DeptId'] != '') {//赛事ID  下拉
                        $where = $where . " and DeptId in('{$searchWhere['DeptId']}')";
                    }
                }
                $pages = $searchWhere['pages'];
                $rows = $searchWhere['rows'];
                if($searchWhere['DataScope']==3 or $searchWhere['DataScope']==4){
                    $all=explode(',',$searchWhere['powerdept']);
                    $DeptId = "'" . $all[0] . "'";
                    for ($i = 1; $i < count($all); $i++) {
                        $DeptId = $DeptId . ",'" . $all[$i] . "'";
                    }
                    $where = $where . " and DeptId  in({$DeptId})";
                }
                $deptTmpArr=$this->get_Votedata($pages, $rows,$where,$like);
            }
        }
        return $deptTmpArr;

	}
	public function addData($indData = [])
    {
        $result=$this->Sys_Model->table_addRow("vote",$indData['a'],2);
     
        if ($result){
            $value['competition_is_vate']=1;
            $value['competition_vote_qrcode']=getCode("pages/vote/vote","DeptId",$indData['a'][0]['DeptId'].",".$indData['a'][0]['competition_id']);//投票
            $where="relevancy_id='".$indData['a'][0]['competition_id']."'and DeptId='".$indData['a'][0]['DeptId']."'";
            $this->Sys_Model->table_updateRow("specification",$value,$where);//更新是否投票字段为1，规格表投票二维码
        }
        return $result;
    }
//搜索报名信息页面 分页
public function get_Votedata($pages,$rows,$wheredata,$likedata){
    //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
    $offset=($pages-1)*$rows;//计算偏移量
    $sql_query="Select DISTINCT vote_id,competition_id,competition_name,vote_begin,vote_end,vote_name,vote_phone,vote_image,vote_poll,DeptId,DeptName,Phone,vote_created_time,sign_introduce,sign_from  from vote   where 1=1 ";
    $sql_query_where=$sql_query.$wheredata;
    if($wheredata!="")
    {
        $sql_query=$sql_query_where;
    }
    if($likedata!=""){//like不为空
        $sql_query=$sql_query_where." ".$likedata;
    }
    $sql_query_total=$sql_query;
    $sql_query=$sql_query." order by vote_created_time desc limit ".$offset.",".$rows;

    $query = $this->db->query($sql_query);
    $ss=$this->db->last_query();
    $r_total=$this->db->query($sql_query_total)->result_array();
    $row_arr=$query->result_array();
    $result['total']=count($r_total);//获取总行数
    $result["data"] = $row_arr;
    $result["alldata"] = $r_total;
    return $result;
}

//人员下拉
    public function showsign($search){
	    $where="";
        if ($search['competition_id'] != '') {
            $where = $where . " and sign_competition_id in('{$search['competition_id']}')";
        }
        if ($search['DeptId'] != '') {
            $where = $where . " and sign_up.DeptId in('{$search['DeptId']}')";
        }
        $where = $where . " and sign_statue ='成功报名' ";
        $pages=$search['pages'];
        $rows=$search['rows'];
        $result=$this->get_sign_limit($pages,$rows,$where);
        return $result;
    }


    //搜索报名信息页面 分页
    public function get_sign_limit($pages,$rows,$wheredata){
        //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
        $offset=($pages-1)*$rows;//计算偏移量
        $sql_query="Select DISTINCT sign_up.*  from sign_up left join vote  on  sign_up.sign_name=vote.vote_name and vote.vote_phone=sign_up.sign_phone and sign_up.sign_competition_id=vote.competition_id and sign_up.DeptId=vote.DeptId where  vote.competition_id is null  ";
        $sql_query_where=$sql_query.$wheredata;
        if($wheredata!="")
        {
            $sql_query=$sql_query_where;
        }
        $sql_query_total=$sql_query;
        $sql_query=$sql_query." order by sign_created_time desc limit ".$offset.",".$rows;

        $query = $this->db->query($sql_query);
        $ss=$this->db->last_query();
        $r_total=$this->db->query($sql_query_total)->result_array();
        $row_arr=$query->result_array();
        $result['total']=count($r_total);//获取总行数
        $result["data"] = $row_arr;
        $result["alldata"] = $r_total;
        return $result;
    }

    public function modynumvote($search){//修改票数
	    $where=[];
        $where['vote_poll']=$search['vote_poll'];
        $returnInfo=$this->Sys_Model->table_updateRow('vote', $where, array('vote_id' => $search['vote_id']));//更新票数
        return $returnInfo;
    }

//
//
//    //搜索报名信息页面 分页
//    public function get_voten_limit($pages,$rows,$wheredata){
//        //Select SQL_CALC_FOUND_ROWS UserId,UserName,base_dept.DeptName,Mobile,Birthday,UserStatus,UserEmail,Sex,Remark,IsAdmin,UserRol,UserPost,base_user.CREATED_TIME from base_user,base_dept where base_user.DeptId = base_dept.DeptId
//        $offset=($pages-1)*$rows;//计算偏移量
//        $sql_query="Select DISTINCT sign_up.*  from sign_up left join vote  on  sign_up.sign_name=vote.vote_name and vote.vote_phone=sign_up.sign_phone and sign_up.sign_competition_id=vote.competition_id and sign_up.DeptId=vote.DeptId where  vote.competition_id is null  ";
//        $sql_query_where=$sql_query.$wheredata;
//        if($wheredata!="")
//        {
//            $sql_query=$sql_query_where;
//        }
//        $sql_query_total=$sql_query;
//        $sql_query=$sql_query." order by sign_created_time desc limit ".$offset.",".$rows;
//
//        $query = $this->db->query($sql_query);
//        $ss=$this->db->last_query();
//        $r_total=$this->db->query($sql_query_total)->result_array();
//        $row_arr=$query->result_array();
//        $result['total']=count($r_total);//获取总行数
//        $result["data"] = $row_arr;
//        $result["alldata"] = $r_total;
//        return $result;
//    }
}







