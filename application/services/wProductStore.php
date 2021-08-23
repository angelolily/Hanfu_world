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
        $product_list = $this->Custome_Model->table_seleRow_limit("*", 'commodity',['commodity_ishome'=>1],[],20,0,'commodity_created_time','DESC',["报名中","进行中"],"commodity_status");

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
            $appdata['Data']['competition']=$competition_list;
            $appdata['Data']['activity']=$activity_list;
            $appdata['Data']['course']=$course_list;
            $appdata['Data']['product']=$product_list;
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
                $oid,$like,10,0,"competition_created_time,competition_id","DESC",["报名中","进行中","评奖中"],"competition_status");


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
        if($searchWhere['sign_competition_id']!="" && $searchWhere['DeptId']!="")
        {
            $where=['sign_competition_id'=>$searchWhere['sign_competition_id'],'DeptId'=>$searchWhere['DeptId']];
            if($searchWhere['sign_name']!="" )
            {
                $like=['sign_name'=>$searchWhere['sign_name']];

            }
            if($searchWhere['sign_card_num']!="" )
            {
                $like=['sign_card_num'=>$searchWhere['sign_card_num']];
            }

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
                $inser['order_lg_cover']=$competition_data[0]['competition_cover'];
                $inser['order_finals_flag']=1;


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



    


//
//    /**
//     * 首次添加订单
//     * @param array $info 添加订单信息
//     * @return array
//     */
//    public function OneAddOrder($info=[])
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//            $info['order_datetime']=date('Y-m-d H:i:s');
//            $info['order_id']=time().rand(1111,9999);
//            $isAddtrue=$this->Custome_Model->table_addRow("cell_order",$info);
//            if($isAddtrue>0)
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="订单添加成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="ODA200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="订单添加失败";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="ODA201";
//
//            }
//
//        }
//
//
//        return $appdata;
//
//    }
//
//
//    /**
//     * 获取预约记录
//     * @param $info
//     * @return array
//     */
//    public function getSubscribe($info)
//    {
//        $appdata=[];
//        $oid=[];
//        if(count($info)>0)
//        {
//            if($info['subscribe_id']==0)
//            {
//                $oid=['subscribe_custome'=>$info['subscribe_custome']];
//            }
//            else
//            {
//                $oid=['subscribe_id <='=>$info['subscribe_id'],'subscribe_custome'=>$info['subscribe_custome']];
//            }
//
//
//            $subscribe=$this->Custome_Model->table_seleRow_limit("*","cell_subscribe",
//                $oid,[],10,0,"subscribe_created_time","DESC");
//
//            if(count($subscribe)>0)
//            {
//                $appdata['Data']=$subscribe;
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="预定信息获取成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="SUB200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="无预定数据";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="SUB201";
//            }
//
//
//
//        }
//
//        return $appdata;
//
//
//    }
//
//    /**
//     * @param $info
//     * @return array
//     */
//    public function getAdvice($info)
//    {
//        $appdata=[];
//        $oid=[];
//        if(count($info)>0)
//        {
//            if($info['advice_id']==0)
//            {
//                $oid=['advice_custome'=>$info['advice_custome']];
//            }
//            else
//            {
//                $oid=['advice_id <'=>$info['advice_id'],'advice_custome'=>$info['advice_custome']];
//            }
//
//
//            $Advice=$this->Custome_Model->table_seleRow_limit("*","cell_advice",
//                $oid,[],10,0,"advice_created_time","DESC");
//
//            if(count($Advice)>0)
//            {
//                $appdata['Data']=$Advice;
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="预定信息获取成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="SUB200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="无预定数据";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="SUB201";
//            }
//
//
//
//        }
//
//        return $appdata;
//
//
//    }
//
//    /**
//     * 添加投诉建议
//     * @param array $info
//     * @return array
//     */
//    public function AddAdvice($info=[])
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//            $info['advice_created_time']=date('Y-m-d H:i:s');
//            //获取要分配的客服
//            $service=$this->Custome_Model->table_seleRow('Userid',"base_user",['UserDept'=>$info['custome_deptid'],'UserPost'=>'HTY60a243c88b9003.00110708']);
//            $info['advice_service']=$service[0]['Userid'];
//            $info['advice_status']='待处理';
//            $isAddtrue=$this->Custome_Model->table_addRow("cell_advice",$info);
//            if($isAddtrue>0)
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="添加成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="ADDA200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="添加失败";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="ADDA201";
//
//            }
//
//        }
//
//
//        return $appdata;
//
//    }
//
//
//    /**
//     * 获取账号额度
//     * @param $info
//     * @return array
//     */
//    public function getAccount($info=[])
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//
//            //获取要分配的客服
//            $service=$this->Custome_Model->table_seleRow('custome_balance',"cell_customer",['custome_id'=>$info['custome_id']]);
//
//            if(count($service)>0)
//            {
//                $appdata['Data']=$service[0]['custome_balance'];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="查询成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="ACNT200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="查询成功";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="ACNT201";
//
//            }
//
//        }
//
//
//        return $appdata;
//
//
//    }
//
//
//    /**
//     * 获得充值历史记录
//     * @param $info
//     */
//    public function getRechargeList($info=[])
//    {
//        $appdata=[];
//        $oid=[];
//        if(count($info)>0)
//        {
//            if($info['recharge_id']==0)
//            {
//                $oid=['recharge_custome'=>$info['recharge_custome']];
//            }
//            else
//            {
//                $oid=['recharge_id <='=>$info['recharge_id'],'recharge_custome'=>$info['recharge_custome']];
//            }
//
//
//            $Advice=$this->Custome_Model->table_seleRow_limit("*","call_racharge",
//                $oid,[],10,0,"recharge_created_time","DESC");
//
//            if(count($Advice)>0)
//            {
//                $appdata['Data']=$Advice;
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="充值记录获取成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="RCH200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="无充值数据";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="RCH201";
//            }
//
//
//
//        }
//
//        return $appdata;
//
//    }
//
//
//    /**
//     * 新增充值记录
//     * @param array $info
//     * @return array
//     */
//    public function addRecharge($info=[])
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//            $info['recharge_created_time']=date('Y-m-d H:i:s');
//            $info['recharge_status']="汇款中";
//            $isAddtrue=$this->Custome_Model->table_addRow("call_racharge",$info);
//            if($isAddtrue>0)
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="添加成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="ARECH200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="添加失败";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="ARECH201";
//
//            }
//
//        }
//
//
//        return $appdata;
//
//    }
//
//
//    /**
//     * 将汇款中的状态改为已汇款
//     * @param array $info
//     * @return array
//     */
//    public function modifyRecharge($info=[])
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//
//            $service=$this->Custome_Model->table_seleRow('recharge_status',"call_racharge",['recharge_id'=>$info['recharge_id']]);
//
//            if(count($service)>0)
//            {
//                if($service[0]['recharge_status']=="汇款中")
//                {
//                    $mod=['recharge_status'=>'已汇款'];
//                    $mod['recharge_updated_time']=date('Y-m-d H:i:s');
//                    $isAddtrue=$this->Custome_Model->table_updateRow("call_racharge",$mod,['recharge_id'=>$info['recharge_id']]);
//                    if($isAddtrue>0)
//                    {
//                        $appdata['Data']=[];
//                        $appdata["ErrorCode"]="";
//                        $appdata["ErrorMessage"]="修改成功";
//                        $appdata["Success"]=true;
//                        $appdata["Status_Code"]="ARECH200";
//                    }
//                    else
//                    {
//                        $appdata['Data']=[];
//                        $appdata["ErrorCode"]="";
//                        $appdata["ErrorMessage"]="修改失败";
//                        $appdata["Success"]=false;
//                        $appdata["Status_Code"]="ARECH201";
//
//                    }
//
//                }
//                else
//                {
//                    $appdata['Data']=[];
//                    $appdata["ErrorCode"]="";
//                    $appdata["ErrorMessage"]="状态不是汇款中，无法改变状态";
//                    $appdata["Success"]=false;
//                    $appdata["Status_Code"]="ARECH200";
//                }
//            }
//
//
//        }
//
//
//        return $appdata;
//
//    }
//
//
//
//    /**
//     * 上传成功体检报告修改数据表信息
//     * @param array $info
//     * @return array
//     */
//    public function modifyHealth($orderid)
//    {
//        $appdata=[];
//        if($orderid!="")
//        {
//            $mod['order_health']=$orderid;
//            $mod['order_statue']="待审核";
//            $isAddtrue=$this->Custome_Model->table_updateRow("cell_order",$mod,['order_id'=>$orderid]);
//            if($isAddtrue>0)
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="修改成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="MDH200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="修改失败";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="MDH201";
//
//            }
//
//        }
//        else
//        {
//            $appdata['Data']=[];
//            $appdata["ErrorCode"]="";
//            $appdata["ErrorMessage"]="参数接收失败";
//            $appdata["Success"]=false;
//            $appdata["Status_Code"]="MDH202";
//        }
//
//
//        return $appdata;
//
//    }
//
//
//    public function delAdvice($info)
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//
//            $ag_custome=$this->Custome_Model->table_seleRow('recharge_status',"call_racharge",['recharge_id'=>$info['recharge_id']]);
//            if(count($ag_custome)>0)
//            {
//                if($ag_custome[0]['recharge_status']=="汇款中")
//                {
//                    $isAddtrue=$this->Custome_Model->table_del("call_racharge",['recharge_id'=>$info['recharge_id']]);
//                    if($isAddtrue>0)
//                    {
//                        $appdata['Data']=[];
//                        $appdata["ErrorCode"]="";
//                        $appdata["ErrorMessage"]="删除成功";
//                        $appdata["Success"]=true;
//                        $appdata["Status_Code"]="DRCH200";
//                    }
//                    else
//                    {
//                        $appdata['Data']=[];
//                        $appdata["ErrorCode"]="";
//                        $appdata["ErrorMessage"]="删除失败";
//                        $appdata["Success"]=false;
//                        $appdata["Status_Code"]="DRCH201";
//
//                    }
//                }
//                else
//                {
//                    $appdata['Data']=[];
//                    $appdata["ErrorCode"]="";
//                    $appdata["ErrorMessage"]="订单状态不是汇款中";
//                    $appdata["Success"]=false;
//                    $appdata["Status_Code"]="DRCH202";
//
//                }
//            }
//
//
//
//        }
//        else
//        {
//            $appdata['Data']=[];
//            $appdata["ErrorCode"]="";
//            $appdata["ErrorMessage"]="参数接收失败";
//            $appdata["Success"]=false;
//            $appdata["Status_Code"]="DRCH203";
//        }
//
//
//        return $appdata;
//
//    }
//
//    public function delOrder($info)
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//
//            $ag_custome=$this->Custome_Model->table_seleRow('order_statue',"cell_order",['order_id'=>$info['order_id']]);
//            if(count($ag_custome)>0)
//            {
//                if($ag_custome[0]['order_statue']!="进行中" || $ag_custome[0]['order_statue']!="已完成" || $ag_custome[0]['order_statue']!="待实名")
//                {
//                    $isAddtrue=$this->Custome_Model->table_del("cell_order",['order_id'=>$info['order_id']]);
//                    if($isAddtrue>0)
//                    {
//                        $appdata['Data']=[];
//                        $appdata["ErrorCode"]="";
//                        $appdata["ErrorMessage"]="删除成功";
//                        $appdata["Success"]=true;
//                        $appdata["Status_Code"]="DRCH200";
//                    }
//                    else
//                    {
//                        $appdata['Data']=[];
//                        $appdata["ErrorCode"]="";
//                        $appdata["ErrorMessage"]="删除失败";
//                        $appdata["Success"]=false;
//                        $appdata["Status_Code"]="DRCH201";
//
//                    }
//                }
//                else
//                {
//                    $appdata['Data']=[];
//                    $appdata["ErrorCode"]="";
//                    $appdata["ErrorMessage"]="订单状态不对";
//                    $appdata["Success"]=false;
//                    $appdata["Status_Code"]="DRCH202";
//
//                }
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="订单不存在";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="DRCH202";
//
//            }
//
//
//
//        }
//        else
//        {
//            $appdata['Data']=[];
//            $appdata["ErrorCode"]="";
//            $appdata["ErrorMessage"]="参数接收失败";
//            $appdata["Success"]=false;
//            $appdata["Status_Code"]="DRCH203";
//        }
//
//
//        return $appdata;
//
//    }
//
//    /**
//     * 身份证背面上传
//     * @param array $info
//     * @return array
//     */
//    public function updateBackup($order_id,$cardback)
//    {
//        $appdata=[];
//        if($order_id && $cardback)
//        {
//
//            $isAddtrue=$this->Custome_Model->table_updateRow("cell_order",['order_CardSaveBack'=>$cardback],['order_id'=>$order_id]);
//            if($isAddtrue>0)
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="修改成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="ARECH200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="修改失败";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="ARECH201";
//
//            }
//
//
//        }
//
//
//        return $appdata;
//
//    }
//
//
//
//
//    /**
//     * 新增客户寄出牙髓盒快递记录
//     * @param array $info
//     * @return array
//     */
//    public function addCustomelogistics($info=[])
//    {
//        $appdata=[];
//        if(count($info)>0)
//        {
//
//            $isAddtrue=$this->Custome_Model->table_updateRow("cell_order",['order_Customelogistics'=>$info['order_Customelogistics']],['order_id'=>$info['order_id']]);
//            if($isAddtrue>0)
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="添加成功";
//                $appdata["Success"]=true;
//                $appdata["Status_Code"]="CLSC203200";
//            }
//            else
//            {
//                $appdata['Data']=[];
//                $appdata["ErrorCode"]="";
//                $appdata["ErrorMessage"]="添加失败";
//                $appdata["Success"]=false;
//                $appdata["Status_Code"]="CLSC203201";
//
//            }
//
//        }
//
//
//        return $appdata;
//
//    }
//
//
//
    

    





}







