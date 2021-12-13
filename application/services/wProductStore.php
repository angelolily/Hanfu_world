<?php


/**
 * Class wProductStore
 * 产品商城操作类
 */
class wProductStore extends HTY_service
{
	/**
	 * Dept constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Custome_Model');
        $this->load->helper('url');
        $this->load->helper('excel');
	}


    /**
     *
     * 3、获取首页列表信息
     */
    public function getHomeProductList()
    {
        $product_list=[];
        $appdata=[];

        //获取比赛首页显示
        $competition_list = $this->Custome_Model->table_seleRow_limit("*", 'competition',['competition_ishome'=>1],[],20,0,'competition_created_time','DESC',["报名中","进行中","评奖中"],"competition_status");
        //获取活动首页显示
        $activity_list = $this->Custome_Model->table_seleRow_limit("*", 'activity',['activity_ishome'=>1],[],20,0,'activity_created_time','DESC',["报名中","进行中"],"activity_status");
        //获取培训课程首页显示
        $course_list = $this->Custome_Model->table_seleRow_limit("*", 'course',['course_ishome'=>1],[],20,0,'course_created_time','DESC',["报名中","进行中"],"course_status");
        //获取商城首页显示
        $product_list = $this->Custome_Model->table_seleRow_limit("*", 'commodity',['commodity_ishome'=>1],[],20,0,'commodity_created_time','DESC',[],"commodity_status");

        if(count($competition_list)===0 && count($activity_list)===0 && count($course_list)===0 && count($product_list)===0){
            $appdata['Data']=[];
            $appdata["ErrorCode"]="nothing-data";
            $appdata["ErrorMessage"]="无数据";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="WPS201";
        }else{
            if(count($competition_list)>0){
                for($i=0;$i<count($competition_list);$i++){
                    $competition_list[$i]['competition_cover']="https://hftx.fzz.cn/public/comcover/".$competition_list[$i]['competition_cover'];
                }
            }
            if(count($activity_list)>0){
                for($i=0;$i<count($activity_list);$i++){
                    $activity_list[$i]['activity_graphic']="https://hftx.fzz.cn/public/activitygraphic/".$activity_list[$i]['activity_graphic'];
                    $activity_list[$i]['activity_cover']="https://hftx.fzz.cn/public/activitycover/".$activity_list[$i]['activity_cover'];
                }
            }
            if(count($course_list)>0){
                for($i=0;$i<count($course_list);$i++){
                    $course_list[$i]['course_graphic']="https://hftx.fzz.cn/public/coursegraphic/".$course_list[$i]['course_graphic'];
                    $course_list[$i]['course_cover']="https://hftx.fzz.cn/public/coursecover/".$course_list[$i]['course_cover'];
                }
            }
            if(count($product_list)>0){
                for($i=0;$i<count($product_list);$i++){
                    $product_list[$i]['commodity_graphic']="https://hftx.fzz.cn/public/commoditygraphic/".$product_list[$i]['commodity_graphic'];
                    $product_list[$i]['commodity_cover']="https://hftx.fzz.cn/public/commoditycover/".$product_list[$i]['commodity_cover'];
                }
            }
            //获取广告
            $field="advertId,advertType,advertImagePath,advertSkipPath,advertSkipPath";
            $adverOne=$this->Custome_Model->table_seleRow_limit($field,"advert",['advertType'=>1],[],10,0,"advertOrder","DESC");
            $adverTwo=$this->Custome_Model->table_seleRow_limit($field,"advert",['advertType'=>2],[],1,0,"advertOrder","DESC");
            $appdata['Data']['competition']=$competition_list;
            $appdata['Data']['activity']=$activity_list;
            $appdata['Data']['course']=$course_list;
            $appdata['Data']['product']=$product_list;
            $appdata['Data']['adverOne']=$adverOne;
            $appdata['Data']['adverTwo']=$adverTwo;
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="信息获取成功";
            $appdata["Success"]=true;
            $appdata["Status_Code"]="WPS200";
        }
        /*
        if(count($competition_list)>0 && count($activity_list)>0 && count($course_list)>0 && count($product_list)>0)
        {
            for($i=0;$i<count($competition_list);$i++){
                $competition_list[$i]['competition_cover']="https://hftx.fzz.cn/public/comcover/".$competition_list[$i]['competition_cover'];
            }

            $activity_list[0]['competition_cover']="https://hftx.fzz.cn/public/activitycover/".$activity_list[0]['activity_cover'];

            $course_list[0]['competition_cover']="https://hftx.fzz.cn/public/coursecover/".$course_list[0]['course_cover'];

            $product_list[0]['competition_cover']="https://hftx.fzz.cn/public/commoditycover/".$product_list[0]['commodity_cover'];
     
            $appdata['Data']['competition']=$competition_list;
            $appdata['Data']['activity']=$activity_list;
            $appdata['Data']['course']=$course_list;
            $appdata['Data']['product']=$product_list;
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="信息获取成功";
            $appdata["Success"]=true;
            $appdata["Status_Code"]="WPS200";

        }
        else
        {
            $appdata['Data']=[];
            $appdata["ErrorCode"]="nothing-data";
            $appdata["ErrorMessage"]="无数据";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="WPS201";
        }
        */
        return $appdata;
    }


    /**
     *
     * 4、获取比赛列表
     */

    public function getcompetitionList($info)
    {
        $appdata=[];
        $oid=[];
        $like=[];
        if(count($info)>0)
        {
            if($info['competition_id']==0)
            {
                if($info['competition_name']!="")
                {
                    $like=['competition_name'=>$info['competition_name']];
                }

            }
            else
            {
                if($info['competition_name']!="")
                {
                    $oid=['competition_id <'=>$info['competition_id']];
                    $like=['competition_name'=>$info['competition_name']];
                }
                else{
                    $oid=['competition_id <'=>$info['competition_id']];
                }

            }


            $order_list=$this->Custome_Model->table_seleRow_limit("*","competition",
                $oid,$like,99,0,"competition_created_time,competition_id","DESC",["报名中","进行中","评奖中"],"competition_status");


            if(count($order_list)>0)
            {
                $appdata['Data']=$order_list;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="赛事获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="COS200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无赛事数据";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="COS201";
            }



        }

        return $appdata;
        
    }




    /**
     * 5、获取客户比赛订单列表
     */
    public function getCustomeOrderList($info=[])
    {
        $appdata=[];
        $oid=[];
        if(count($info)>0)
        {
            if($info['order_autoid']==0)
            {
                if($info['order_product']!="")
                {
                    $oid=['order_product'=>$info['order_product'],'members_id'=>$info['members_id'],'order_type'=>$info['order_type']];
                }
                else
                {
                    $oid=['members_id'=>$info['members_id'],'order_type'=>$info['order_type']];
                }


            }
            else
            {
                if($info['order_product']!="")
                {
                    $oid=['order_autoid <'=>$info['order_autoid'],'order_product'=>$info['order_product'],'members_id'=>$info['members_id'],'order_type'=>$info['order_type']];
                }
                else{
                    $oid=['order_autoid <'=>$info['order_autoid'],'members_id'=>$info['members_id'],'order_type'=>$info['order_type']];
                }


            }





            $order_list=$this->Custome_Model->table_joinSeleRow_limit("*","order",$oid,[],10,0,"order_datetime,order_autoid","DESC",[],"","left","competition","competition.competition_id=order.order_capid");






            if(count($order_list)>0)
            {
                $appdata['Data']=$order_list;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="订单获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="COS200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无订单数据";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="COS201";
            }



        }

        return $appdata;

    }





    //根据赛事id获取报名信息

    public function getSignUp($searchWhere = []){
        $pages = $searchWhere['pages'];
        $rows = $searchWhere['rows'];

        $offset=($pages-1)*$rows;//计算偏移量

        $oid=[];
        $like=[];
        $sql_code = "select s.*,o.order_id from sign_up as s left join `order` as o on s.sign_order_id = o.order_autoid";
        if($searchWhere['sign_competition_id']!="" && $searchWhere['DeptId']!=""){
            $sql_code = $sql_code." where s.sign_competition_id = {$searchWhere['sign_competition_id']} and s.DeptId = '{$searchWhere['DeptId']}'";
            $where=['sign_competition_id'=>$searchWhere['sign_competition_id'],'DeptId'=>$searchWhere['DeptId']];
            if($searchWhere['sign_name']!="" ){
                $like=['sign_name'=>$searchWhere['sign_name']];
                $sql_code = $sql_code." and s.sign_name like '%{$searchWhere['sign_name']}%'";
            }
            if($searchWhere['sign_card_num']!="" ){
                $like=['sign_card_num'=>$searchWhere['sign_card_num']];
                $sql_code = $sql_code." and s.sign_card_num like '%{$searchWhere['sign_card_num']}%'";
            }
            //$allsign=$this->Custome_Model->table_seleRow("sign_id","sign_up",$where,$like);
            //$SignUp_list=$this->Custome_Model->table_seleRow_limit("*","sign_up",$where,$like,$rows,$offset,"sign_created_time,sign_id","DESC");
            $allsign = $this->Custome_Model->execute_sql($sql_code);
            $sql_code = $sql_code." order by s.sign_created_time DESC limit {$offset},{$rows}";
            $SignUp_list = $this->Custome_Model->execute_sql($sql_code);

            if(count($SignUp_list)>0)
            {
                $appdata['Data']['total']=count($allsign);
                $appdata['Data']['data']=$SignUp_list;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="报名表获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="SING200";
            }
            else
            {
                $appdata['Data']=[[]];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无报名表数据";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="SING201";
            }
        }
        else
        {
            $appdata['Data']=[[]];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="无报名表数据";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="SING202";

        }

        return $appdata;

    }
    
    
    //根据赛事id获取报名信息（总决赛使用）
    public function getSignFinl($searchWhere=[])
    {
        $pages = $searchWhere['pages'];
        $rows = $searchWhere['rows'];

        $offset=($pages-1)*$rows;//计算偏移量

        $oid=[];
        $like=[];
        if($searchWhere['sign_competition_id']!="" && $searchWhere['DeptId']!="")
        {
            $where=['sign_competition_id'=>$searchWhere['sign_competition_id'],'DeptId'=>$searchWhere['DeptId'],"sign_isfinl"=>0];

            $allsign=$this->Custome_Model->table_seleRow("sign_id","sign_up",$where,$like);

            $SignUp_list=$this->Custome_Model->table_seleRow_limit("*","sign_up",
                $where,$like,$rows,$offset,"sign_created_time,sign_id","DESC");


            if(count($SignUp_list)>0)
            {
                $appdata['Data']['total']=count($allsign);
                $appdata['Data']['data']=$SignUp_list;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="报名表获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="SING200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无报名表数据";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="SING201";
            }
        }
        else
        {
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="无报名表数据";
            $appdata["Success"]=true;
            $appdata["Status_Code"]="SING202";

        }

        return $appdata;


    }
    
    
        //通用添加记录
        public function addGeneral($table,$Datas){

            return $this->Custome_Model->table_addRow($table,$Datas,1);
    
        }
    
        //通用修改记录
        public function updateGeneral($table,$Datas,$where){
    
            return $this->Custome_Model->table_updateRow($table,$Datas,$where);
    
        }
    
        //通用添加记录
        public function delGeneral($table,$where){
    
            return $this->Custome_Model->table_del($table,$where);
    
        }
    
        //通用查询不带where,分页
        public function getAllGeneral($table,$fields,$wheres=[])
        {
    
            $mywhere=$wheres;
            $alladvert=$this->Custome_Model->table_seleRow($fields,$table,$mywhere,[]);
            $ss=$this->db->last_query();
            if(count($alladvert)>0)
            {
    
                $appdata['Data']['data']=$alladvert;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="数据获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="G200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无数据";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="G201";
            }
    
    
    
            return $appdata;
    
        }
        //根据广告名称获取报名信息
    
        public function geAdvert($searchWhere = []){
            $pages = $searchWhere['pages'];
            $rows = $searchWhere['rows'];
    
            $offset=($pages-1)*$rows;//计算偏移量
    
    
            $like=[];
    
            if($searchWhere['advertTitle']!="" )
            {
                $like=['advertTitle'=>$searchWhere['advertTitle']];
    
            }
    
            $alladvert=$this->Custome_Model->table_seleRow("advertId","advert",[],$like);
            $allad_list=$this->Custome_Model->table_seleRow_limit("*","advert",
                [],$like,$rows,$offset,"advertId","DESC");
    
    
            if(count($allad_list)>0)
            {
                $appdata['Data']['total']=count($alladvert);
                $appdata['Data']['data']=$allad_list;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="广告表获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="ADVERT200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无广告表数据";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="ADVERT201";
            }
    
    
    
            return $appdata;
    
        }
    
    //根据选择的名单，输送到总决赛

    public function sendFinl($info=[],$data=[],$signdata=[])
    {
        $appdata=[];
        $inser=[];//订单表
        $sign=[];
        $signitem=[];//报名表
        $allOrderData=[];
        //根据预赛id，判断是否已建立了总决赛

        if($info['competition_final']!="")
        {
            //查询总决赛数据
            $competition_data=$this->Custome_Model->table_seleRow("competition_name,competition_cover","competition",['competition_id'=>$info['competition_final']]);

            //查询价格
            $unit_price=$this->Custome_Model->table_seleRow("DeptId,unit_price,DeptName,Phone","specification",['relevancy_id'=>$info['competition_final']]);


            foreach ($data as $item) {

                //插入订单表，插入报名表
                $inser['order_id']=time()+rand(1111,9999);
                $inser['order_datetime']=date("yyyy-mm-dd h:i");
                $inser['order_statue']="未付款";
                $inser['order_product']=$competition_data[0]['competition_name'];
                $inser['order_type']="比赛";
                $inser['order_num']="1";
                $inser['order_price']=$unit_price[0]['unit_price'];
                $inser['order_customer_name']=$item['sign_name'];
                $inser['order_customer_phone']=$item['sign_phone'];
                $inser['members_id']=$item['members_id'];
                $inser['order_capid']=$info['competition_final'];
                $inser['order_deptid']=$unit_price[0]['DeptId'];
                $inser['order_lg_cover']="https://hftx.fzz.cn/public/comcover/".$competition_data[0]['competition_cover'];
                $inser['order_finals_flag']=1;
                $inser['order_format']=$item['DeptName'];


                //报名表
                $item['sign_competition_id']=$info['competition_final'];
                $item['DeptId']=$unit_price[0]['DeptId'];
                $item['DeptName']=$unit_price[0]['DeptName'];
                $item['Phone']=$unit_price[0]['Phone'];
                $item['sign_statue']='未付款';

                array_push($allOrderData,$inser);
                array_push($sign,$item);
            }


            $resultNum=$this->Custome_Model->table_addRow("order",$allOrderData,2);

            $resultNum1=$this->Custome_Model->table_addRow("sign_up",$sign,2);


            if($resultNum>0 && $resultNum1>0)
            {

                foreach ($signdata as $signit)
                {
                    $upsign['sign_id']=$signit;
                    $upsign['sign_isfinl']=1;
                    array_push($signitem,$upsign);

                }


                $resultUp=$this->Custome_Model->table_updateBatchRow("sign_up",$signitem,"sign_id");
                if($resultUp>0)
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="推送总决赛成功";
                    $appdata["Success"]=true;
                    $appdata["Status_Code"]="SING200";
                }
                else{
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="推送总决赛失败";
                    $appdata["Success"]=false;
                    $appdata["Status_Code"]="SING203";
                }

            }
            else
            {

                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无报名表数据";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="SING201";
            }





        }
        else
        {
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="无总决赛数据";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="SING202";

        }




        return $appdata;

        
    }


    //导出excel
    public function outExcel($table,$where=[],$like=[])
    {
        $title=[];
        $appdata=[];
        $slike=[];
        $swhere=[];
        $sql_struct="select column_comment from INFORMATION_SCHEMA.Columns where table_name='".$table."' and table_schema='hanfu-world-db'";


        $array_struct=$this->Custome_Model->execute_sql($sql_struct);

        if(count($array_struct)>0)
        {
            foreach ($array_struct as $key=>$value){


                array_push($title,$value['column_comment']);


            }

            $excel_data=$this->Custome_Model->table_seleRow("*",$table,$where,$like);
            if(count($excel_data)>0)
            {

                $filename=date("Y-m-dhis");
                $fistdir='./public/'.'outputExcel/';
                $files=exportExcel($title,$excel_data,$filename,$fistdir,true,'a1');
                //force_download($files, null);
                if($files){

                    $appdata['Data']=$this->config->item('serverExcelFilePata').$filename.'.xlsx';
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="";
                    $appdata["Success"]=true;
                    $appdata["Status_Code"]="SING200";
                }
                else{
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="导出失败";
                    $appdata["Success"]=false;
                    $appdata["Status_Code"]="SING201";

                }

            }
            else{
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无数据";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="SING202";
            }


        }
        else{
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="无数据";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="SING203";
        }




        return $appdata;


    }


    //批量压缩导出
    public function zipAll($table,$where=[],$like=[])
    {

        $phone_data=$this->Custome_Model->table_seleRow("sign_name,sign_picture,sign_image",$table,$where,$like);


        if(count($phone_data)>0)
        {
            $zipAllfileName=time();
            //新建本次保存的临时目录
            $saveTemp_dir="./public/tempPhotoSave/".$zipAllfileName;
            if(is_dir($saveTemp_dir) or mkdir($saveTemp_dir))
            {


            }

            foreach ($phone_data as $row)
            {
                $encode = stristr(PHP_OS, 'WIN') ? 'GBK' : 'UTF-8';
                $name = iconv('UTF-8', $encode, $row['sign_name']);
                $tmepDir=$saveTemp_dir."/".$name;
                if(is_dir($tmepDir) or mkdir($tmepDir))
                {

                }
                //2寸
                $dir_original="./public/enroll/".$row['sign_image'];
                $zipfile=$tmepDir."/2small".'.zip';
                $this->load->library('zip');
                $this->zip->clear_data();
                $this->zip->read_dir($dir_original, FALSE);
                $this->zip->archive($zipfile);

                //汉服照片
                $dir_original2="./public/enroll/".$row['sign_picture'];
                $zipfile2=$tmepDir."/photo".'.zip';
                $this->load->library('zip');
                $this->zip->clear_data();
                $this->zip->read_dir($dir_original2, FALSE);
                $this->zip->archive($zipfile2);


            }

            //全部压缩包

            $dir_all="./public/tempPhotoSave";
            $zipfileAll=$dir_all."/".$zipAllfileName.'.zip';
            $this->load->library('zip');
            $this->zip->clear_data();
            $this->zip->read_dir($saveTemp_dir, FALSE);
            $this->zip->archive($zipfileAll);


            $appdata['Data']=$this->config->item('serverZipFilePata').$zipAllfileName.'.zip';
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="";
            $appdata["Success"]=true;
            $appdata["Status_Code"]="SING200";


        }

        else{
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="无数据";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="SING203";
        }







        return $appdata;







    }


        //查询物流信息
        public function getExpressinfo($num)
        {
            $appdata=[];
            // 云市场分配的密钥Id
            $secretId = 'AKIDev8S7xbdkgdGpzb3R3kBzlwavn4eApcWQ0tv';
            // 云市场分配的密钥Key
            $secretKey = 'j753yOF5i073nwr6bGfn4lyxWmwE7UCqunUqh5Cp';
            $source = 'market';
    
            // 签名
            $datetime = gmdate('D, d M Y H:i:s T');
            $signStr = sprintf("x-date: %s\nx-source: %s", $datetime, $source);
            $sign = base64_encode(hash_hmac('sha1', $signStr, $secretKey, true));
            $auth = sprintf('hmac id="%s", algorithm="hmac-sha1", headers="x-date x-source", signature="%s"', $secretId, $sign);
    
            // 请求方法
            $method = 'POST';
            // 请求头
            $headers = array(
                'X-Source' => $source,
                'X-Date' => $datetime,
                'Authorization' => $auth,
            );
            // 查询参数
            $queryParams = array (
                'express_id' => $num,
                'express_name' => '',
            );
            // body参数（POST方法下）
            $bodyParams = array (
            );
            // url参数拼接
            $url = 'https://service-m1lhix6w-1253285064.gz.apigw.tencentcs.com/release/qxt_express/';
            if (count($queryParams) > 0) {
                $url .= '?' . http_build_query($queryParams);
            }
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($v, $k) {
                return $k . ': ' . $v;
            }, array_values($headers), array_keys($headers)));
            if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
            }
    
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
    
                $appdata['Data']=curl_error($ch);
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="CAD200";
            } else {
                $appdata['Data']=json_decode($data,true);
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="CAD200";
    
            }
            curl_close($ch);
    
            return $appdata;
    
        }


    private function strToUtf8($str){
        $encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        if($encode == 'UTF-8'){
            return $str;
        }else{
            return mb_convert_encoding($str, 'UTF-8', $encode);
        }
    }

    public function htmltopng($strhtml){

        $head="<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title></title></head><body>";
        $temp="<head><meta charset=\"UTF-8\" /><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" /><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" /><title></title></head><body style=\"background: #ffffff;\">";
        $end="</body></html>";
        //$strhtml=$this->strToUtf8($strhtml);
        $strhtml=$temp.$strhtml.$end;
        $fileName=time().".html";
        $savePath="./public/htmlcover/";
        $file = fopen($savePath.$fileName,"w");
        fwrite($file,$strhtml);
        fclose($file);

        if(file_exists($savePath)){

            $url="https://hftx.fzz.cn/public/htmlcover/".$fileName;
            $fileNamePng=time().".png";
            $pngSavePath="./public/htmlcover/".$fileNamePng;
            $jsPath = './public/htmlcover/capture.js';
            $command = "./public/htmlcover/phantomjs {$jsPath}  {$url}  {$pngSavePath}";
            $result = @exec($command );
            if(file_exists($savePath.$fileNamePng)){
                $appdata['Data']="https://hftx.fzz.cn/public/htmlcover/".$fileNamePng;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="转换成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="TRA200";

            }
            else{
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="转换图片失败";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="TRA201";
                log_message("error","转换图片失败");

            }
        }else{
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="转换图片失败";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="TRA202";
            log_message("error","网页转换失败");

        }
        return $appdata;

    }

    





}







 