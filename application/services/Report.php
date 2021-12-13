<?php


/**
 * Class Usermanage ’用户管理类
 */
class Report extends HTY_service
{
    /**
     * Dept constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_Model');
        $this->load->helper('tool');
        $this->load->library('encryption');

    }
    //赛事
    public function getcomReport($searchWhere = [])
    {

            $where = "";
            $curr = $searchWhere['pages'];
            $limit = $searchWhere['rows'];
            if ($searchWhere['relevancy_id'] != "") {
                $where = $where . " and relevancy_id in('{$searchWhere['relevancy_id']}') ";
            }
            if ($searchWhere['DeptId'] != "")//是搜索
            {
                $where = $where . " and  DeptId in('{$searchWhere['DeptId']}')";
            }
            $offset = ($curr - 1) * $limit;//计算偏移量
            $field = 'Select DISTINCT competition.competition_name,competition.competition_type,DeptName,specification.competition_sign_begin,specification.competition_sign_end,specification.created_time';
            if ($where!=''){
                if ($searchWhere['DeptId'] != ""){
                    $field=$field."
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}') and sign_up.DeptId in('{$searchWhere['DeptId']}') and sign_statue='未付款' ) as weifukuan
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}') and sign_up.DeptId in('{$searchWhere['DeptId']}') and sign_statue='已退款') as yituikuan
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}') and sign_up.DeptId in('{$searchWhere['DeptId']}') and sign_isfinl<>'') as zongjuesai
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}') and sign_up.DeptId in('{$searchWhere['DeptId']}') and sign_statue='成功报名') as chenggongbaoming";
                }else{
                    $field=$field."
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}') and sign_statue='未付款' ) as weifukuan
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}')  and sign_statue='已退款') as yituikuan
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}')  and sign_isfinl<>'') as zongjuesai
                ,(Select count(sign_id) from sign_up where sign_competition_id in('{$searchWhere['relevancy_id']}')  and sign_statue='成功报名') as chenggongbaoming";
                }

            }
            $sql_query = $field . " from competition right join specification on competition.competition_id = specification.relevancy_id where 1=1 " . $where;
            $sql_query_total = $sql_query;
            $sql_query = $sql_query . " order by specification.created_time desc limit " . $offset . "," . $limit;
            $query = $this->db->query($sql_query);
            $ss = $this->db->last_query();
            $r_total = $this->db->query($sql_query_total)->result_array();
            $row_arr = $query->result_array();
            $result['total'] = count($r_total);//获取总行数
            $result["data"] = $row_arr;
            return $result;
        }
        //课程
    public function getcouseReport($searchWhere = [])
    {
        $where = "";
        $curr = $searchWhere['pages'];
        $limit = $searchWhere['rows'];
        if ($searchWhere['course_id'] != "") {
            $where = $where . " and course_id in('{$searchWhere['course_id']}') ";
        }
        $offset = ($curr - 1) * $limit;//计算偏移量
        $field = 'Select  course_id,course_status,course_name,course_type,course_signBegin,course_signEnd';
        if ($where!=''){
            $field=$field."
            ,(Select count(sign_id)  from sign_up right join course on course.course_id = sign_up.sign_competition_id where  sign_up.sign_competition_id in('{$searchWhere['course_id']}') and sign_type='培训'  and sign_statue='未付款' ) as weifukuan
            ,(Select count(sign_id) from sign_up right join course on course.course_id = sign_up.sign_competition_id where sign_up.sign_competition_id in('{$searchWhere['course_id']}') and sign_type='培训' and sign_statue in ('成功报名','已付款')) as chenggongbaoming";
        }
        $sql_query = $field . " from course where 1=1 " . $where;
        $sql_query_total = $sql_query;
        $sql_query = $sql_query . " order by course_created_time desc limit " . $offset . "," . $limit;
        $query = $this->db->query($sql_query);
        $ss = $this->db->last_query();
        $r_total = $this->db->query($sql_query_total)->result_array();
        $row_arr = $query->result_array();
        $result['total'] = count($r_total);//获取总行数
        $result["data"] = $row_arr;
        return $result;
    }

//活动
    public function getacReport($searchWhere = [])
    {
        $where = "";
        $curr = $searchWhere['pages'];
        $limit = $searchWhere['rows'];
        if ($searchWhere['activity_id'] != "") {
            $where = $where . " and activity_id in('{$searchWhere['activity_id']}') ";
        }
        $offset = ($curr - 1) * $limit;//计算偏移量
        $field = 'Select  activity_id,activity_name,activity_status,activity_number_limit,activity_type,activity_signBegin,activity_signEnd';
        if ($where!=''){
            $field=$field."
            ,(Select count(sign_id)  from sign_up right join activity on activity.activity_id = sign_up.sign_competition_id where  sign_up.sign_competition_id in('{$searchWhere['activity_id']}') and sign_type='活动'  and sign_statue='未付款' ) as weifukuan
            ,(Select count(sign_id) from sign_up right join activity on activity.activity_id = sign_up.sign_competition_id where sign_up.sign_competition_id in('{$searchWhere['activity_id']}') and sign_type='活动' and sign_statue in ('成功报名','已付款')) as chenggongbaoming
            ,(Select count(sign_id) from sign_up right join activity on activity.activity_id = sign_up.sign_competition_id where sign_up.sign_competition_id in('{$searchWhere['activity_id']}') and sign_type='活动' and sign_statue in ('成功签到')) as chenggongqiandao";
        }
        $sql_query = $field . " from activity where 1=1 " . $where;
        $sql_query_total = $sql_query;
        $sql_query = $sql_query . " order by activity_created_time desc limit " . $offset . "," . $limit;
        $query = $this->db->query($sql_query);
        $ss = $this->db->last_query();
        $r_total = $this->db->query($sql_query_total)->result_array();
        $row_arr = $query->result_array();
        $result['total'] = count($r_total);//获取总行数
        $result["data"] = $row_arr;
        return $result;
    }

    //商品
    public function getdityReport($searchWhere = [])
    {
        $like = "";
        $curr = $searchWhere['pages'];
        $limit = $searchWhere['rows'];
        if ($searchWhere['commodity_name'] != "") {
            $like = $like . " and orderitem.commodity_name like '%{$searchWhere['commodity_name']}%' ";
        }
        $offset = ($curr - 1) * $limit;//计算偏移量
        $field = "SELECT
  orderitem.commodity_name as '商品名称',any_value(orderitem.commodity_price) as '单价',
  sum(CASE when orderitem.orderitem_status=0 or orderitem.orderitem_status=4 then orderitem.buy_num else 0 end) AS '销售数量',
	sum(CASE when orderitem.orderitem_status=4 then (orderitem.buy_num) else 0 end) AS '退款数量',
	sum(CASE when orderitem.orderitem_status=0 then (orderitem.buy_num) else 0 end) AS '购买数量'";

        $sql_query = $field . " from orderitem left join `order` on orderitem.order_id = `order`.order_id    where `order`.order_type='商品' " . $like."  group by orderitem.commodity_name";
        $sql_query_total = $sql_query;
        $sql_query = $sql_query . " limit " . $offset . "," . $limit;
        $query = $this->db->query($sql_query);
        $ss = $this->db->last_query();
        $r_total = $this->db->query($sql_query_total)->result_array();
        $row_arr = $query->result_array();
        $result['total'] = count($r_total);//获取总行数
        $result["data"] = $row_arr;
        return $result;
    }

//推荐人
    public function getreferrerReport($searchWhere = [])
    {
        $like = "";
        $curr = $searchWhere['pages'];
        $limit = $searchWhere['rows'];
        if ($searchWhere['referrer_name'] != "") {
            $like = $like . " and referrer_name like '%{$searchWhere['referrer_name']}%' ";
        }
        $offset = ($curr - 1) * $limit;//计算偏移量
        $field = "SELECT  referrer_name as A,referrer_phone,referrer_projname,
(SELECT count(referrer_id) from  referrer where referrer_name=A) AS '推荐用户数量' from referrer  where 1=1
        ";
        $sql_query = $field . $like;
        $sql_query_total = $sql_query;
        $sql_query = $sql_query . " limit " . $offset . "," . $limit;
        $query = $this->db->query($sql_query);
        $ss = $this->db->last_query();
        $r_total = $this->db->query($sql_query_total)->result_array();
        $row_arr = $query->result_array();
        $result['total'] = count($r_total);//获取总行数
        $result["data"] = $row_arr;
        return $result;
    }


}