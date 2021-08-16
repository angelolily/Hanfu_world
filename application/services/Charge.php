<?php


/**
 * Class
 */
class Charge extends HTY_service
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_Model');
        $this->load->helper('tool');
        $this->load->library('encryption');
    }
    /**
     * Notes:获取投票表
     * User: hyr
     * DateTime: 2021/6/24 14:52
     * @param array $val 赛区id：DeptId 赛事id:competition_id
     * @return array $result
     */
    public function getVote($val)
    {
        $field="*";
        $sql="select $field from vote where DeptId='{$val['DeptId']}' and competition_id={$val['competition_id']} order by vote_poll desc";
        $allData=$this->Sys_Model->execute_sql($sql);
        try{
            if(count($allData)>0) {
                $base_dir = './public/enroll';
                for ($i = 0; $i < count($allData); $i++) {
                    $res_url = '';
                    $dir_name = $allData[$i]['vote_image'];
                    $dir = $base_dir . '/' . $dir_name;
                    if(file_exists($dir)){
                        $handler = opendir($dir);
                        if ($handler) {
                            while (($filename = readdir($handler)) !== false) {
                                if ($filename != "." && $filename != "..") {
                                    $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
                                    if(strstr($filename,"f")){
                                        break;
                                    }
                                }
                            }
                        }
                        closedir($handler);
                        $allData[$i]['vote_image'] = $res_url;
                    }

                }
            }


        }
        catch(Exception $ex)
        {

        }
        return $allData;
    }
    /**
     * Notes:获取赛事表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     * @param array $val 负责人电话：Phone
     * @return array $result
     */
    public function getSpec($val)
    {
        $field="a.spec_id,a.competition_sign_qrcode,a.DeptId,a.DeptName,b.competition_cover,b.competition_name,b.competition_describe";
        $sql="select $field from specification a left join competition b on b.competition_id=a.relevancy_id where a.Phone={$val['Phone']}";
        $allData=$this->Sys_Model->execute_sql($sql);
        $result=$allData;
        return $result;
    }
    /**
     * Notes:赛事负责人登录验证
     * User: hyr
     * DateTime: 2021/6/28 14:29
     * @param array $val 负责人电话：Mobile 密码：UserPassword
     * @return array $result
     */
    public function login($val)
    {
        $field="UserStatus,UserName,Mobile,UserPassword,UserRole,Sex,IsAdmin,UserDept,UserPost,Avatar";
        $where['Mobile']=$val['Mobile'];
        $allData=$this->Sys_Model->table_seleRow($field,'base_user',$where);
        if(count($allData)>0) {
            $pwd = $this->encryption->decrypt($allData[0]['UserPassword']);
            if ($val['UserPassword'] == $pwd) {
                return $allData;
            } else {
                return [];
            }
        }
        return [];
    }
 /**
     * Notes:获取参赛人员表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     * @param array $val 负责人电话：Phone 赛区id:DeptId
     * @return array $result
     */
    public function getB($val)
    {
        $result=[];
        $field="sign_statue,sign_name,sign_phone,sign_picture";
        $allData=$this->Sys_Model->table_seleRow($field,'sign_up',$val);

        try{
            if(count($allData)>0) {
                for ($i = 0; $i < count($allData); $i++) {
                    $res_url = '';
                    $dir_name = $allData[$i]['sign_picture'];
                    $base_dir = './public/enroll';
                    $dir = $base_dir . '/' . $dir_name;
                
                    if(file_exists($dir))
                    {
                        $handler = opendir($dir);
                        if ($handler) {
                            while (($filename = readdir($handler)) !== false) {
                                if ($filename != "." && $filename != "..") {
                                    $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
                                    if(strstr($filename,"f")){
                                        break;
                                    }
                                }
                            }
                        }
                        closedir($handler);
                        $allData[$i]['sign_picture']=$res_url;

                    }
              
                }
            }
           

        }
        catch(Exception $ex)
        {

        }
       
        $result=$allData;
        return $result;
    }
    /**
     * Notes:获取参赛人员表
     * User: hyr
     * DateTime: 2021/6/28 11:19
     * @param array $val 赛事、赛区、购买人手机号 competition_id、DeptId、vote_phone
     * @return array $result
     */
    public function getSv($val)
    {
        $field="vote_id,vote_name,vote_phone,vote_image,vote_poll,vote_end,competition_skip_id,DeptName";
        $allData=$this->Sys_Model->table_seleRow($field,'vote',$val);
        if(count($allData)>0){
            $res_url = '';
            $dir_name = $allData[0]['vote_image'];
            $base_dir = './public/enroll/';
            $dir=$base_dir.'/'.$dir_name;
            $handler = opendir($dir);
            if($handler){
                while (($filename = readdir($handler)) !== false) {
                    if ($filename != "." && $filename != "..") {
                        $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
                        if(strstr($filename,"f")){
                            break;
                        }
                    }
                }
            }
            closedir($handler);
            $allData[0]['vote_image']=$res_url;
        }

        //返回$res_url为图片路径
        $result=$allData;
        return $result;
    }
    /**
     * Notes:获取参赛人员表
     * User: hyr
     * DateTime: 2021/7/08 11:19
     * @param array $val 用户openid:members_id
     * @return array $result
     */
    public function getPa($val)
    {
        $field="a.order_autoid,a.order_datetime,a.order_statue,b.activity_name,b.activity_describe,b.activity_cover,b.activity_beginDate,b.activity_endDate,b.activity_signPrice,b.activity_status";
        $sql="select $field from `order` a left join activity b on a.order_capid=b.activity_id where a.members_id='{$val['members_id']}' and order_type='活动' order by a.order_datetime desc";
        $allData=$this->Sys_Model->execute_sql($sql);
        $result=$allData;
        return $result;
    }
    /**
     * Notes:活动签到
     * User: hyr
     * DateTime: 2021/7/08 11:19
     * @param array $val 会员openid:members_openid 活动id:activity_id
     * @return array $result
     */
    public function sign($val)
    {
        $where['activity_id']=$val['activity_id'];
        $info=$this->Sys_Model->table_seleRow('activity_signIntegral','activity',$where);
        $point=$info[0]['activity_signIntegral'];
        $sql_arr=[];
        $sql1="update `order` set order_statue='已结束' where order_autoid={$val['order_autoid']}";
        $sql2="update `members` set members_integral=members_integral+{$point} where members_openid='{$val['members_openid']}'";
        array_push($sql_arr,$sql1,$sql2);
        $result=$this->Sys_Model->table_trans($sql_arr);
        return $result;
    }
    /**
     * Notes:获取商品
     * User: hyr
     * DateTime: 2021/7/13 10:19
     * @param array $val 页码:pages 每页条数:rows 商品名:commodity_name
     * @return array $result
     */
    public function getCommodity($val)
    {
        $field="commodity_id,commodity_name,commodity_describe,commodity_graphic,commodity_type,commodity_cover,commodity_price,commodity_integral,commodity_carriage";
        $begin=$val['rows'];
        $offset=($val['pages']-1)*$val['rows'];
        $like=[];
        if($val['commodity_name']!=""){
            $like['commodity_name']=$val['commodity_name'];
        }
        $where['commodity_status']='已发布';
        $totalArr=$this->Sys_Model->table_seleRow('commodity_id',"commodity", $where, $like);
        if($totalArr && count($totalArr)>0)
        {
            $TmpArr = $this->Sys_Model->table_seleRow_limit($field, "commodity", $where, $like,$begin,$offset,$order="commodity_id","desc");
            foreach($TmpArr as $i => $value){
                $TmpArr[$i]['commodity_cover']=$this->config->item('localpath') . '/public/commoditycover/' . $TmpArr[$i]['commodity_cover'];
                $TmpArr[$i]['commodity_graphic']=$this->config->item('localpath') . '/public/commoditygraphic/' . $TmpArr[$i]['commodity_graphic'];
            }
            $result['total']=count($totalArr);
            $result['data']=$TmpArr;
        }
        else
        {
            $result['total']=0;
            $result['data']=[];
        }
        return $result;
    }
    /**
     * Notes:获取所有商品图片
     * User: hyr
     * DateTime: 2021/7/16 11:19
     * @param array $val 投票id:vote_id
     * @return array $result
     */
    public function getImg($val)
    {
        $allData=$this->Sys_Model->table_seleRow('vote_image','vote',$val);
        $result=[];
        try{
            if(count($allData)>0) {
                    $base_dir = './public/enroll';
                    $res_url = '';
                    $dir_name = $allData[0]['vote_image'];
                    $dir = $base_dir . '/' . $dir_name;
                    if(file_exists($dir)){
                        $handler = opendir($dir);
                        if ($handler) {
                            while (($filename = readdir($handler)) !== false) {
                                if ($filename != "." && $filename != "..") {
                                    $res_url = "https://hftx.fzz.cn/public/enroll/" . $dir_name . '/' . $filename;
//                                    if(strstr($filename,"f")){
//                                        break;
//                                    }
                                    $arr['content']=$res_url;
                                    array_push($result,$arr);
                                }
                            }
                        }
                        closedir($handler);
                    }

            }


        }
        catch(Exception $ex)
        {

        }
        return $result;
    }
    /**
     * Notes:添加商品至购物车
     * User: hyr
     * DateTime: 2021/7/26 10:16
     * @param array $val 购物车信息
     * @return array $result
     */
    public function addCart($val){
        $where['members_id']=$val['members_id'];
        $where['commodity_spec_id']=$val['commodity_spec_id'];
        $where['commodity_id']=$val['commodity_id'];
        $count=$this->Sys_Model->table_seleRow('commodity_num','shop_cart',$where);
        //判断是否已存在相同商品，相同增加数量
        if(count($count)>0){
            $update['commodity_num']=$count[0]['commodity_num']+$val['commodity_num'];
            $return=$this->Sys_Model->table_updateRow('shop_cart',$update,$where);
        }else{
            $val['carid_created_by'] = $val['members_id'];
            $val['carid_created_time'] = date('Y-m-d H:i');
            $val['carid_select'] = '0';
            $return=$this->Sys_Model->table_addRow('shop_cart',$val);
        }
        return $return;
    }
    /**
     * Notes:获取商品
     * User: hyr
     * DateTime: 2021/7/26 10:19
     * @param array $val 页码:pages 每页条数:rows 会员id:members_id
     * @return array $result
     */
    public function getCart($val)
    {
        $field="a.carid,a.members_id,a.commodity_id,a.commodity_name,a.commodity_spec_name,a.commodity_size,a.commodity_num,a.commodity_price,
        a.carid_select,a.carid_created_by,a.carid_created_time,a.carid_updated_by,a.carid_updated_time,a.commodity_spec_id,a.commodity_image,
        a.order_user,a.order_points,a.commodity_carriage,a.shop_carriage,b.amount,b.commodity_price as commodity_unit_price";
        $begin=$val['rows'];
        $offset=($val['pages']-1)*$val['rows'];
        $by=$val['members_id'];
        $where['members_id']=$by;
        $totalArr=$this->Sys_Model->table_seleRow('carid',"shop_cart", $where);
        $sql="select $field from shop_cart a left join commodity_spec b on a.commodity_spec_id=b.commodity_spec_id where a.members_id=$by order by a.carid_created_time desc LIMIT $offset,$begin";
        $TmpArr = $this->Sys_Model->execute_sql($sql);
        $result['total']=count($totalArr);
        $result['data']=$TmpArr;
        return $result;
    }
    /**
     * Notes:删除购物车
     * User: hyr
     * DateTime: 2021/7/26 10:19
     * @param array $val 购物车id:carid
     * @return array $result
     */
    public function delCart($val)
    {
        $result = $this->Sys_Model->table_del("shop_cart",$val);
        return $result;
    }
    /**
     * Notes:购物车勾选状态变化
     * User: hyr
     * DateTime: 2021/7/26 10:19
     * @param array $val 所有数组
     * @return array $result
     */
    public function selectCart($val)
    {
        try {
            $this->db->trans_begin();
            foreach ($val as $item){
                $where['carid']=$item['carid'];
                if($item['checked']){
                    $update['carid_select']="1";

                }else{
                    $update['carid_select']="0";
                }
                $update['commodity_num']=$item['commodity_num'];
                 $this->Sys_Model->table_updateRow("shop_cart",$update,$where);
            }
            if (($this->db->trans_status() === FALSE))
            {
                $this->db->trans_rollback();
                return [];
            }
            else {
                $this->db->trans_commit();
                $this->db->cache_delete_all();
                return [true];
            }

        }
        catch (Exception $ex)
        {
            $this->db->trans_rollback();
            return [];
        }
    }
    /**
     * Notes:获取用户积分
     * User: hyr
     * DateTime: 2021/7/26 10:19
     * @param array $val  会员id:members_id
     * @return array $result
     */
    public function getPoint($val)
    {
        $result=$this->Sys_Model->table_seleRow('*',"members", $val);
        return $result;
    }
    /**
     * Notes:购买产品生成订单
     * 扣除库存
     * 已支付订单注意
     * 1.通过购物车购买的需要清空购物车
     * 2.如果推荐人字段不为空，增加推荐人积分
     * User: hyr
     * DateTime: 2021/7/26 10:16
     * @param array $val order:订单信息 order_item:订单明细数组
     * @return array $result
     */
    public function addOrder($val){
        $order=$val['order'];
        $order['order_id']=time().mt_rand(0,9999);
        $order['created_by']=$order['members_id'];
        $order['created_time']=date('Y-m-d H:i');
        $order_item=$val['order_item'];
        //已支付订单
        if($order['order_statue']=="买家已付款"){
            try {
                $this->db->trans_begin();
                $this->Sys_Model->table_addRow('order',$order);
                //判断是否使用积分
                if($order['order_integral']!=0){
                    $sql="update members set members_integral=members_integral-{$order['order_integral']} where members_id = {$order['members_id']}";
                    $this->Sys_Model->execute_sql($sql,2);
                }
                foreach ($order_item as $val){
                    $val['orderitem_created_by']=$order['members_id'];
                    $val['orderitem_created_time']=date('Y-m-d H:i');
                    $val['order_id']=$order['order_id'];
                    $this->Sys_Model->table_addRow("orderitem",$val);
                    //扣去库存
                    $sql_spec="update commodity_spec set amount=amount-{$val['buy_num']} where commodity_spec_id = {$val['commodity_spec_id']}";
                    $this->Sys_Model->execute_sql($sql_spec,2);
                    //判断是否为购物车渠道
                    if(isset($val['carid'])&&$val['carid']){
                        $where_cart['carid']=$val['carid'];
                        $this->Sys_Model->table_del("shop_cart",$where_cart);
                    }
                }
                if (($this->db->trans_status() === FALSE))
                {
                    $this->db->trans_rollback();
                    return [];
                }
                else {
                    $this->db->trans_commit();
                    $this->db->cache_delete_all();
                    return [$order['order_id']];
                }

            }
            catch (Exception $ex)
            {
                $this->db->trans_rollback();
                return [];
            }
        }else{
            try {
                $this->db->trans_begin();
                $this->Sys_Model->table_addRow('order',$order);
                foreach ($order_item as $val){
                    $val['orderitem_created_by']=$order['members_id'];
                    $val['orderitem_created_time']=date('Y-m-d H:i');
                    $val['order_id']=$order['order_id'];
                    $this->Sys_Model->table_addRow("orderitem",$val);
                    //扣去库存
                    $sql_spec="update commodity_spec set amount=amount-{$val['buy_num']} where commodity_spec_id = {$val['commodity_spec_id']}";
                    $this->Sys_Model->execute_sql($sql_spec,2);
                }
                if (($this->db->trans_status() === FALSE))
                {
                    $this->db->trans_rollback();
                    return [];
                }
                else {
                    $this->db->trans_commit();
                    $this->db->cache_delete_all();
                    return [$order['order_id']];
                }

            }
            catch (Exception $ex)
            {
                $this->db->trans_rollback();
                return [];
            }
        }

    }
    /**
     * Notes:微信支付后更新订单
     * 1.更新订单状态
     * 2.通过购物车购买的需要清空购物车
     * 3.如果推荐人字段不为空，增加推荐人积分
     * User: hyr
     * DateTime: 2021/7/26 10:19
     * @param array $val 所有数组
     * @return array $result
     */
    public function updateOrder($val)
    {
        $where['order_id']=$val['order_id'];
        $order_update=$val['order'];
        $orderitem_update['orderitem_return_type']='0';
        try {
            $this->db->trans_begin();
            $this->Sys_Model->table_updateRow("order",$order_update,$where);
            $this->Sys_Model->table_updateRow("orderitem",$orderitem_update,$where);
            $item= $this->Sys_Model->table_seleRow("*","orderitem",$where);
            foreach ($item as $val){
                //判断是否为购物车渠道
                if($val['carid']){
                    $where_cart['carid']=$val['carid'];
                    $this->Sys_Model->table_del("shop_cart",$where_cart);
                }
            }
            if (($this->db->trans_status() === FALSE))
            {
                $this->db->trans_rollback();
                return [];
            }
            else {
                $this->db->trans_commit();
                $this->db->cache_delete_all();
                return [true];
            }

        }
        catch (Exception $ex)
        {
            $this->db->trans_rollback();
            return [];
        }
    }
    /**
     * Notes:获取我的商品订单
     * User: hyr
     * DateTime: 2021/7/26 10:19
     * @param array $val 页码:pages 每页条数:rows 会员id:members_id 商品名:commodity_name 订单状态:order_statue
     * @return array $result
     */
    public function getOrder($val)
    {
        $begin=$val['rows'];
        $offset=($val['pages']-1)*$val['rows'];
        $by=$val['members_id'];
        $where_statue="";
        $where_com_name="";
        if($val['order_statue']!=""){
            $where_statue=" and a.order_statue='{$val['order_statue']}'";
        }
        if($val['order_refund_flag']!=""){
            $where_statue=" and a.order_refund_flag!=0";
            if($val['order_refund_flag'] == "0"){
                $where_statue=" and a.order_refund_flag=0";
            }
        }
        if($val['commodity_name']!=""){
            $where_com_name=" and b.commodity_name like '%{$val['commodity_name']}%'";
        }
        $sql_all="select a.* from `order` a left join orderitem b on a.order_id=b.order_id where a.members_id={$by}".$where_statue.$where_com_name. " group by  a.order_autoid order by a.created_time desc";
        $sql_limit=$sql_all." LIMIT $offset,$begin";
        $totalArr=$this->Sys_Model->execute_sql($sql_all);
        $TmpArr = $this->Sys_Model->execute_sql($sql_limit);
        //搜索订单明细表，并插入到TempArr
        foreach($TmpArr as $key=>$value){
            $where_item['order_id']=$value['order_id'];
            $item= $this->Sys_Model->table_seleRow("*","orderitem",$where_item);
            $TmpArr[$key]['order_item']=$item;
        }
        $result['total']=count($totalArr);
        $result['data']=$TmpArr;
        return $result;
    }
    /**
     * Notes:确认收货
     * User: hyr
     * DateTime: 2021/7/28 11:19
     * @param array $val  订单id:order_id
     * @return array $result
     */
    public function confirm($val)
    {
        $update['order_statue']="已完成";
        $result=$this->Sys_Model->table_updateRow('order',$update,$val);
        $order_item=$this->Sys_Model->table_seleRow('*','orderitem',$val);
        foreach ($order_item as $val){
            //判断是否有积分
            if($val['order_points']){
                $sql="update members set members_integral=members_integral+{$val['order_points']} where members_id = {$val['order_user']}";
                $this->Sys_Model->execute_sql($sql,2);
            }
        }
        return $result;
    }
    /**
     * Notes:退款
     * User: hyr
     * DateTime: 2021/7/28 11:19
     * @param array $val 订单明细表id:orderitem_id  退款类型:orderitem_return_type 退货理由:orderitem_return_rete
     * @return array $result
     */
    public function refund($val)
    {
        $where['orderitem_id']=$val['orderitem_id'];
        $update['orderitem_return_type']=$val['orderitem_return_type'];
        $update['orderitem_return_rete']=$val['orderitem_return_rete'];
        $update['orderitem_status']=1;
        $select=$this->Sys_Model->table_seleRow("*","orderitem",$where);
        $order_id=$select[0]['order_id'];
        $where_order['order_id']=$order_id;
        $update_order['order_refund_flag']=3;
        $update_order['order_refund_rate']=$val['orderitem_return_rete'];
        $this->Sys_Model->table_updateRow('order',$update_order,$where_order);
        $result=$this->Sys_Model->table_updateRow('orderitem',$update,$where);
        return $result;
    }
    /**
     * Notes:买家填写物流单号
     * User: hyr
     * DateTime: 2021/7/28 11:19
     * @param array $val
     * @return array $result
     */
    public function logistics($val)
    {
        $update['orderitem_return_logistics']=$val['orderitem_return_logistics'];
        $where['orderitem_id']=$val['orderitem_id'];
        $result=$this->Sys_Model->table_updateRow('orderitem',$update,$where);
        return $result;
    }
}







