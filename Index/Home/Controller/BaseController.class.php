<?php
namespace Home\Controller;

/**
 * BaseController
 * 基础报表信息
 */
class BaseController extends CommonController {
    /**
     * 资产负债表页面
     * @return
     */
    public function zichan() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $now = time();
        if(strtotime($date)>$now){
            $this->error('选择时间不能超过本月');
        }
        //获取资产负债表的栏目
        $result = D('Assets','Service')->getAssetsList();
        
        $aid = array();
        foreach ($result as $key => $value) {
            $aid[] = $value['id'];
        }
        //根据公司编号获取资产负债表的内容
        $con = D('Rich','Service')->getRichList($cnum,$aid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['assets_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['rich']=$item[$v['id']];
            $result[$k]=$v;
        }
        foreach ($result as $kreview => $vreview){
            $review = $vreview['rich']['review'];
        }
//        dump($review);
        $this->assign('review', $review);
        $this->assign('assets', $result);
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->display();
    }
    //清空资产负债表
    public function deleteZichan(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Rich','Service')->deleteRich($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    //审核资产负债表
    public function zichanReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Rich','Service')->reviewRich($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    //取消审核资产负债表
    public function zichanCancelReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Rich','Service')->cancelReviewRich($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    public function zichanData() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取资产负债表的栏目
        $result = D('Assets','Service')->getAssetsList();
        
        $aid = array();
        foreach ($result as $key => $value) {
            $aid[] = $value['id'];
        }
        //根据公司编号获取资产负债表的内容
        $con = D('Rich','Service')->getRichList($cnum,$aid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['assets_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['rich']=$item[$v['id']];
            $result[$k]=$v;
        }
        return $result;
    }
    
    /**
     * 创建负债数据
     * @return
     */
    public function create() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $rich = $_POST;
//        dump($rich);exit();
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Rich','Service')->addRich($cnum,$rich);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    public function getCompanyCnum(){
        $cnum = $_SESSION['current_account']['cnum'];
        return $cnum;
    }
    
    //excel导出数据代码
    public function excelFuzhai(){

        require_once'./phpexcel/PHPExcel.php';
        require_once'./phpexcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls
        require_once'./phpexcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式 
        require_once'./phpexcel/PHPExcel/IOFactory.php'; // 用于 excel-2007 格式 
		
		
        $date = date("Y_m_d",time());
        $fileName .= "_资产负债表";
        $fileName .= "_{$date}.xlsx";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //创建新的PHPExcel对象
        $objPHPExcel = new \PHPExcel();

         /*以下是一些设置 ，什么作者  标题啊之类的*/  
        $objPHPExcel->getProperties()->setCreator("SEIEE")  
                  ->setLastModifiedBy("SEIEE")  
                  ->setTitle("Data export")  
                  ->setSubject("Data export")  
                  ->setDescription("Data Backup")  
                  ->setKeywords("excel")  
                  ->setCategory("result file");  
		
	$data = $this->zichanData();	
		/*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改，这里最好能够做成对$data的遍历*/  
        foreach($data as $k => $v){  
            $num=$k+3;
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '标准资产负债表');
                     $objPHPExcel->getActiveSheet()->setCellValue('A2', '名称');
                     $objPHPExcel->getActiveSheet()->setCellValue('B2', '行次');
                     $objPHPExcel->getActiveSheet()->setCellValue('C2', '年初金额');
                     $objPHPExcel->getActiveSheet()->setCellValue('D2', '期末金额');
            $objPHPExcel->setActiveSheetIndex(0)  
            //Excel的第A列，uid是你查出数组的键值，下面以此类推  
                    //$headArr = array('车主','车牌号','时间','故障码','故障名称','故障描述','故障原因','解决方案');
              ->setCellValue('A'.$num, $v['name'])      
              ->setCellValue('B'.$num, $v['id'])  
              ->setCellValue('C'.$num, $v['rich']['start_money'])
              ->setCellValue('D'.$num, $v['rich']['end_money']);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->setTitle($fileName);  
        $objPHPExcel->setActiveSheetIndex(0);  
        ob_end_clean();

	header("Pragma: public"); //下面是一堆header的设置，测试的时候加了好多，现在不清楚哪个没用  
        header("Expires: 0");  
        header('Content-Type: application/vnd.ms-excel;charset=utf-8;name="'.$fileName.'.xls"');  
        header("Content-Type: application/force-download");  
        header("Content-Type: application/octet-stream");  
        header("Content-Type: application/download");  
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');  
        header('Cache-Control: max-age=0');  
        header("Content-Transfer-Encoding:binary");  
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
        exit;
    }


    public function upload(){
        $upload = new \Org\Util\UploadFile();;// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->allowExts = array('xls', 'xlsx');// 设置附件上传类型  
        $upload->savePath = WEB_ROOT.'Public/uploads/excel/'; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
         $this->error($upload->getErrorMsg());
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $path=$info[0]['savepath'].$info[0]['savename'];
        }
        //是这个1.不是想
        $list		=	D("Excel","Service")->read($path);
//        dump($list);
        $cnum		=	$this->getCompanyCnum();
        $arr_assets_id	=	array();
        $arr_start_money	=	array();
        $arr_end_money	=	array();
        foreach($list as $k=>$v){
                if($k==1){continue;}
                $arr_assets_id[]	=	$v[1];	//	得到文件里面的全部行次，即assets_id
                $arr_start_money[]	=	$v[2];	//	得到文件里面的全部期初余额；
                $arr_end_money[]	=	$v[3];  //得到的文件里面的全部期末余额
        }
//        dump($list);
        //在数据库中查找所有的当月的期初余额来进行对比，如果有值就不让上传
        $date = $_POST['date'];
        if(empty($date)){$newdate = date('Y-m',time());}else{$newdate = $date;}
        
        $money	=	D("Rich","Service")->getAllRich($cnum,$newdate);
        foreach($money as $k=>$v){
                if(!empty($v['start_money'])){
                    $this->error("数据已存在");       
                }
                if(!empty($v['end_money'])){
                    $this->error("数据已存在");       
                }
        }
        
        //上传成功之后就开始插入数据库
        $data=array();  //插入到rich的数据
        foreach($list as $k=>$v){
            if($k==1){continue;}
            $data['start_money']				=	(empty($v[2]))?0:$v[2];
            $data['end_money']                                  =	(empty($v[3]))?0:$v[3];
            $data['assets_id']                                  =	$v[1];
            $data['cnum']	                                =	$cnum;
            $data['date']                                       =	$newdate.'-01';
            $rich_id						=	D('Rich','Service')->add($data);
        }
        
        $this->success("文件上传成功！");
    }
    
    /**
     * 更新负债数据
     * @return
     */
    public function update() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $rich = $_POST;
       
        $result = D('Rich','Service')->editRich($rich);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    /**
     * 标准损益表页面
     * @return
     */
    public function biaozhun() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取标准损益表的栏目
        $result = D('Interest','Service')->getInterestList();
        $iid = array();
        foreach ($result as $key => $value) {
            $iid[] = $value['id'];
        }
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getRateList($cnum,$iid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['rate']=$item[$v['id']];
            $result[$k]=$v;
        }
//        dump($result);
        foreach ($result as $kreview => $vreview){
            $review = $vreview['rate']['review'];
        }
//        dump($review);
        $this->assign('review', $review);
        $this->assign('interests', $result);
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->display();
    }
     //清空标准损益表
    public function deleteBiaozhun(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Rate','Service')->deleteRate($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    //审核标准损益表
    public function biaozhunReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Rate','Service')->reviewRate($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    
    //取消审核标准损益表
    public function biaozhunCancelReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Rate','Service')->cancelReviewRate($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    public function biaozhunData() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取标准损益表的栏目
        $result = D('Interest','Service')->getInterestList();
        $iid = array();
        foreach ($result as $key => $value) {
            $iid[] = $value['id'];
        }
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getRateList($cnum,$iid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['rate']=$item[$v['id']];
            $result[$k]=$v;
        }
//        dump($result);
       return $result;
    }
    //excel导出数据代码
    public function excelBiaozhun(){

        require_once'./phpexcel/PHPExcel.php';
        require_once'./phpexcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls
        require_once'./phpexcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式 
        require_once'./phpexcel/PHPExcel/IOFactory.php'; // 用于 excel-2007 格式 
		
		
        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xlsx";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //创建新的PHPExcel对象
        $objPHPExcel = new \PHPExcel();

         /*以下是一些设置 ，什么作者  标题啊之类的*/  
        $objPHPExcel->getProperties()->setCreator("SEIEE")  
                  ->setLastModifiedBy("SEIEE")  
                  ->setTitle("Data export")  
                  ->setSubject("Data export")  
                  ->setDescription("Data Backup")  
                  ->setKeywords("excel")  
                  ->setCategory("result file");  
		
	$data = $this->biaozhunData();	
		/*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改，这里最好能够做成对$data的遍历*/  
        foreach($data as $k => $v){  
            $num=$k+3;
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '标准损益表');
                     $objPHPExcel->getActiveSheet()->setCellValue('A2', '名称');
                     $objPHPExcel->getActiveSheet()->setCellValue('B2', '行次');
                     $objPHPExcel->getActiveSheet()->setCellValue('C2', '本期发生额');
                     $objPHPExcel->getActiveSheet()->setCellValue('D2', '累计金额');
            $objPHPExcel->setActiveSheetIndex(0)  
            //Excel的第A列，uid是你查出数组的键值，下面以此类推  
                    //$headArr = array('车主','车牌号','时间','故障码','故障名称','故障描述','故障原因','解决方案');
              ->setCellValue('A'.$num, $v['name'])      
              ->setCellValue('B'.$num, $v['id'])  
              ->setCellValue('C'.$num, $v['rate']['now_money'])
              ->setCellValue('D'.$num, $v['rate']['sum_money']);
        } 
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->setTitle($fileName);  
        $objPHPExcel->setActiveSheetIndex(0);  
        ob_end_clean();

	header("Pragma: public"); //下面是一堆header的设置，测试的时候加了好多，现在不清楚哪个没用  
        header("Expires: 0");  
        header('Content-Type: application/vnd.ms-excel;charset=utf-8;name="'.$fileName.'.xls"');  
        header("Content-Type: application/force-download");  
        header("Content-Type: application/octet-stream");  
        header("Content-Type: application/download");  
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');  
        header('Cache-Control: max-age=0');  
        header("Content-Transfer-Encoding:binary");  
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
        exit;
    }
    
    public function uploadBiaozhun(){
        $upload = new \Org\Util\UploadFile();;// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('xls', 'xlsx');// 设置附件上传类型  
        $upload->savePath = WEB_ROOT.'Public/uploads/excel/biaozhun'; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
              $this->error($upload->getErrorMsg());
//            $this->error("请选择您要上传的文件！"); 
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $path=$info[0]['savepath'].$info[0]['savename'];
        }
        //是这个1.不是想
        $list		=	D("Excel","Service")->read($path);
//        dump($list);
        $cnum		=	$this->getCompanyCnum();
        $arr_interest_id	=	array();
        $arr_now_money	=	array();
        $arr_sum_money	=	array();
        foreach($list as $k=>$v){
                if($k==1){continue;}
                $arr_interest_id[]	=	$v[1];	//	得到文件里面的全部行次，即assets_id
                $arr_now_money[]	=	$v[2];	//	得到文件里面的全部期初余额；
                $arr_sum_money[]	=	$v[3];  //得到的文件里面的全部期末余额
        }
//        dump($list);
        //在数据库中查找所有的当月的期初余额来进行对比，如果有值就不让上传
        $date = $_POST['date'];
        if(empty($date)){$newdate = date('Y-m',time());}else{$newdate = $date;}
        $money	=	D("Rate","Service")->getAllRate($cnum,$newdate);
        foreach($money as $k=>$v){
                if(!empty($v['now_money'])){
                    $this->error("数据已存在");       
                }
                if(!empty($v['sum_money'])){
                    $this->error("数据已存在");       
                }
        }
        
        //上传成功之后就开始插入数据库
        $data=array();  //插入到rate的数据
        foreach($list as $k=>$v){
            if($k==1){continue;}
            $data['now_money']				=	(empty($v[2]))?0:$v[2];
            $data['sum_money']                                  =	(empty($v[3]))?0:$v[3];
            $data['interest_id']                                  =	$v[1];
            $data['cnum']	                                =	$cnum;
            $data['date']                                       =	$newdate.'-01';
            $rate_id						=	D('Rate','Service')->add($data);
        }
        
        $this->success("文件上传成功！");
    }
    
    /**
     * 创建损益数据
     * @return
     */
    public function createRate() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $rate = $_POST;
       
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Rate','Service')->addRate($cnum,$rate);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }

    /**
     * 更新损益数据
     * @return
     */
    public function updateRate() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $rate = $_POST;
       
        $result = D('Rate','Service')->editRate($rate);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    /**
     * 现金流量表页面
     * @return
     */
    public function xianjin() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取现金流量表的栏目
        $result = D('Cash','Service')->getCashList();
        
        $cid = array();
        foreach ($result as $key => $value) {
            $cid[] = $value['id'];
        }
        //根据公司编号获取现金流量表的内容
        $con = D('Flow','Service')->getFlowList($cnum,$cid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['cash_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['cash']=$item[$v['id']];
            $result[$k]=$v;
        }
        foreach ($result as $kreview => $vreview){
            $review = $vreview['cash']['review'];
        }
//        dump($review);
        $this->assign('review', $review);
//        dump($result);
        $this->assign('cashs', $result);
        //获取现金流量表补充资料的栏目
        $resultadd = D('Cashadd','Service')->getCashaddList();
        $aid = array();
        foreach ($resultadd as $key => $value) {
            $aid[] = $value['id'];
        }
        //根据公司编号获取现金流量表的内容
        $cona = D('Flow','Service')->getFlowAddList($cnum,$aid,$date);
        
        $itema=array();
        foreach($cona as $k=>$v){
            $itema[$v['cashadd_id']]=$v;
        }
        
        foreach ($resultadd as $k => $v) {
            $v['cashadd']=$itema[$v['id']];
            $resultadd[$k]=$v;
        }
//        dump($resultadd);
        $this->assign('cashadd', $resultadd);
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->display();
    }
     //清空现金流量表
    public function deleteXianjin(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Flow','Service')->deleteFlow($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    //审核现金流量表
    public function xianjinReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Flow','Service')->reviewFlow($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    //取消审核现金流量表
    public function xianjinCancelReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Flow','Service')->reviewCancelFlow($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    public function xianjinData() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取现金流量表的栏目
        $result = D('Cash','Service')->getCashList();
        
        $cid = array();
        foreach ($result as $key => $value) {
            $cid[] = $value['id'];
        }
        //根据公司编号获取现金流量表的内容
        $con = D('Flow','Service')->getFlowList($cnum,$cid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['cash_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['cash']=$item[$v['id']];
            $result[$k]=$v;
        }
//        dump($result);
//        $this->assign('cashs', $result);
        return $result;
        
    }
    public function xianjinDataAdd(){
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取现金流量表补充资料的栏目
        $resultadd = D('Cashadd','Service')->getCashaddList();
        $aid = array();
        foreach ($resultadd as $key => $value) {
            $aid[] = $value['id'];
        }
        //根据公司编号获取现金流量表的内容
        $cona = D('Flow','Service')->getFlowAddList($cnum,$aid,$date);
        
        $itema=array();
        foreach($cona as $k=>$v){
            $itema[$v['cashadd_id']]=$v;
        }
        
        foreach ($resultadd as $k => $v) {
            $v['cashadd']=$itema[$v['id']];
            $resultadd[$k]=$v;
        }
//        $this->assign('cashadd', $resultadd);
//        $this->display();
        return $resultadd;
    }
    
     //excel导出数据代码
    public function excelXianjin(){

        require_once'./phpexcel/PHPExcel.php';
        require_once'./phpexcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls
        require_once'./phpexcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式 
        require_once'./phpexcel/PHPExcel/IOFactory.php'; // 用于 excel-2007 格式 
		
		
        $date = date("Y_m_d",time());
        $fileName .= "_{$date}";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //创建新的PHPExcel对象
        $objPHPExcel = new \PHPExcel();

         /*以下是一些设置 ，什么作者  标题啊之类的*/  
        $objPHPExcel->getProperties()->setCreator("SEIEE")  
                  ->setLastModifiedBy("SEIEE")  
                  ->setTitle("Data export")  
                  ->setSubject("Data export")  
                  ->setDescription("Data Backup")  
                  ->setKeywords("excel")  
                  ->setCategory("result file");  
		
	$data = $this->xianjinData();
        $dataAdd = $this->xianjinDataAdd();
        
		/*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改，这里最好能够做成对$data的遍历*/  
        foreach($data as $k => $v){  
            $num=$k+3;
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '现金流量表');
                     $objPHPExcel->getActiveSheet()->setCellValue('A2', '名称');
                     $objPHPExcel->getActiveSheet()->setCellValue('B2', '行次');
                     $objPHPExcel->getActiveSheet()->setCellValue('C2', '金额');
                     
            $objPHPExcel->setActiveSheetIndex(0)  
            //Excel的第A列，uid是你查出数组的键值，下面以此类推  
                    //$headArr = array('车主','车牌号','时间','故障码','故障名称','故障描述','故障原因','解决方案');
              ->setCellValue('A'.$num, $v['name'])      
              ->setCellValue('B'.$num, $v['id'])  
              ->setCellValue('C'.$num, $v['cash']['money']);
        } 
        foreach ($dataAdd as $k=>$v){
            $num = $k+3;
            $objPHPExcel->getActiveSheet()->setCellValue('D2', '补充资料');
            $objPHPExcel->getActiveSheet()->setCellValue('E2', '行次');
            $objPHPExcel->getActiveSheet()->setCellValue('F2', '金额');
            
            $objPHPExcel->setActiveSheetIndex(0)  
            //Excel的第A列，uid是你查出数组的键值，下面以此类推  
              ->setCellValue('D'.$num, $v['name'])      
              ->setCellValue('E'.$num, $v['id'])  
              ->setCellValue('F'.$num, $v['cashadd']['money']);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->setTitle($fileName);  
        $objPHPExcel->setActiveSheetIndex(0);  
        ob_end_clean();

	header("Pragma: public"); //下面是一堆header的设置，测试的时候加了好多，现在不清楚哪个没用  
        header("Expires: 0");  
        header('Content-Type: application/vnd.ms-excel;charset=utf-8;name="'.$fileName.'.xls"');  
        header("Content-Type: application/force-download");  
        header("Content-Type: application/octet-stream");  
        header("Content-Type: application/download");  
        header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');  
        header('Cache-Control: max-age=0');  
        header("Content-Transfer-Encoding:binary");  
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
        exit;
    }
    
    public function uploadXianjin(){
        $upload = new \Org\Util\UploadFile();;// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('xls', 'xlsx');// 设置附件上传类型  
        $upload->savePath = WEB_ROOT.'Public/uploads/excel/xianjin/'; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
           $this->error($upload->getErrorMsg());
        }else{// 上传成功
            $info =  $upload->getUploadFileInfo();
            $path=$info[0]['savepath'].$info[0]['savename'];
        }
        //是这个1.不是想
        $list		=	D("Excel","Service")->read($path);
//        dump($list);exit;
        $cnum		=	$this->getCompanyCnum();
        $arr_cash_id	=	array();
        $arr_cash_money	=	array();
        $arr_cashaddd_id	=	array();
        $arr_cashadd_money	=	array();
        foreach($list as $k=>$v){
                if($k==1){continue;}
                $arr_cash_id[]	=	$v[1];	//	得到文件里面的全部行次，即assets_id
                $arr_cash_money[]	=	$v[2];	//	得到文件里面的全部期初余额；
                $arr_cashaddd_id[]	=	$v[4];
                $arr_cashadd_money[]	=	$v[5];  //得到的文件里面的全部期末余额
        }
//        dump($arr_cash_id);dump($arr_cash_money);exit;
        //在数据库中查找所有的当月的期初余额来进行对比，如果有值就不让上传
        $date = $_POST['date'];
        if(empty($date)){$newdate = date('Y-m',time());}else{$newdate = $date;}
        $money	=	D("Flow","Service")->getAllFlow($cnum,$newdate);
        foreach($money as $k=>$v){
            if(!empty($v['money'])){
                $this->error("数据已存在");       
            }
        }
        
        //上传成功之后就开始插入数据库
        $data=array();  //插入到Flow的数据
        foreach($list as $k=>$v){
            if($k==1){continue;}
            if(!empty($v[2])){
                $data['cash_id']                        =	$v[1];
                $data['money']				=	(empty($v[2]))?0:$v[2];
                $data['cashadd_id']                     =       NULL;
                $data['cnum']	                        =	$cnum;
                $data['date']                           =	$newdate.'-01';
                $flow_id				=	D('Flow','Service')->add($data);
            }
            if(!empty($v[5])){
                $data['cashadd_id']                     =	$v[4];
                $data['money']				=	(empty($v[5]))?0:$v[5];
                $data['cash_id']                        =	NULL;
                $data['cnum']	                        =	$cnum;
                $data['date']                           =	$newdate.'-01';
                $flow_id				=	D('Flow','Service')->add($data);
            }
            
        }
        
        $this->success("文件上传成功！");
    }
    
    /**
     * 创建现金流量数据
     * @return
     */
    public function createFlow() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $flow = $_POST;
       
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Flow','Service')->addFlow($cnum,$flow);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }

    /**
     * 更新现金流量数据
     * @return
     */
    public function updateFlow() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $flow = $_POST;
       
        $result = D('Flow','Service')->editFlow($flow);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    /**
     * 参数录入页面
     * @return
     */
    public function canshu() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取标准损益表的栏目
        $result = D('Param','Service')->getParamList();
        $pid = array();
        foreach ($result as $key => $value) {
            $pid[] = $value['id'];
        }
        //根据公司编号获取canshu表的内容
        $con = D('Entry','Service')->getEntryList($cnum,$pid,$date);
       
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['param_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['entry']=$item[$v['id']];
            $result[$k]=$v;
        }
        foreach ($result as $kreview => $vreview){
            $review = $vreview['entry']['review'];
        }
//        dump($review);
        $this->assign('review', $review);
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('entrys', $result);
        $this->display();
    }
     //清空参数录入数据
    public function deleteCanshu(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Entry','Service')->deleteEntry($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    //审核参数录入
    public function canshuReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Entry','Service')->reviewEntry($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    //取消审核现金流量表
    public function canshuCancelReview(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        
        $result = D('Entry','Service')->reviewCancelEntry($date,$cnum);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    public function createEntryZ() {
        //在数据库中查找所有的当月的期初余额来进行对比，如果有值就不让上传
        $date = $_POST['date'];
        if (!$date) {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];
        $isCunzai = D('Entry', 'Service')->isExistEntry($cnum, $date);
        if (!$isCunzai) {
            if (!isset($_POST)) {
                return $this->errorReturn('无效的操作！');
            }

            $entry = $_POST;
            array_shift($entry);
            foreach ($entry as $k => $v) {
                $result = D('Entry', 'Service')->addEntry($cnum, $v, $date);
            }
//        var_dump($_POST);die;
            if ($result) {
                $add = array(
                    "bian" => Array(
                        "param_id" => 3,
                        "now_money" => $entry['shou']['now_money'] + $entry['liang']['now_money'],
                        "sum_money" => $entry['shou']['sum_money'] + $entry['liang']['sum_money']
                    ),
                    "cheng" => Array(
                        "param_id" => 7,
                        "now_money" => $this->getEntryZa($date),
                        "sum_money" => $this->getEntryZa2($date)
                    )
                );
                //        dump($entry);exit();
                //        $data = array_merge($entry, $add);
                //print_r($data);
                foreach ($add as $k => $v) {
                    $result1 = D('Entry', 'Service')->addEntry($cnum, $v, $date);
                }
                if ($result1) {
                    $add2 = array(
                        "gu" => Array(
                            "param_id" => 6,
                            "now_money" => $this->getEntryGu($date),
                            "sum_money" => $this->getEntryGu2($date)
                        )
                    );
                    foreach ($add2 as $k => $v) {
                        $result2 = D('Entry', 'Service')->addEntry($cnum, $v, $date);
                    }

                    $lirun_10 = D('Rate', 'Service')->getRateZi($cnum, $date, 10);
                    $lirun_14 = D('Rate', 'Service')->getRateZi($cnum, $date, 14);
                    $lirun_12 = D('Rate', 'Service')->getRateZi($cnum, $date, 9);
                    $lirun_15 = D('Rate', 'Service')->getRateZi($cnum, $date, 12);
                    $lirun_19 = D('Rate', 'Service')->getRateZi($cnum, $date, 16);
                    $folw_11 = D('Flow', 'Service')->getFlowZi($cnum, $date, 11);
                    $bilv_m = $folw_11 / ($lirun_12[0]['now_money'] + $lirun_15[0]['now_money'] + $lirun_19[0]['now_money']);
                    $bilv_y = $folw_11 / ($lirun_12[0]['sum_money'] + $lirun_15[0]['sum_money'] + $lirun_19[0]['sum_money']);
                    $bilv_m = floor($bilv_m * 10000) / 10000 * 100;
                    $bilv_y = floor($bilv_y * 10000) / 10000 * 100;
                    $add5 = array(
                        "2" => Array(
                            "param_id" => '8',
                            "now_money" => $lirun_10[0]['now_money'],
                            "sum_money" => $lirun_10[0]['sum_money']
                        ),
                        "3" => Array(
                            "param_id" => '9',
                            "now_money" => $lirun_14[0]['now_money'],
                            "sum_money" => $lirun_14[0]['sum_money']
                        ),
                        "4" => Array(
                            "param_id" => '10',
                            "now_money" => $bilv_m,
                            "sum_money" => $bilv_y
                        ),
                    );
                    foreach ($add5 as $v) {
                        $result = D('Entry', 'Service')->addEntry($cnum, $v, $date);
                    }
                    echo 0;
                } else {
                    echo 1;
                }
            } else {
                echo 1;
            }
        } else {
            echo 1;
        }
    }
    
  public function updateEntryZ() {
//        var_dump($_POST);
        $date = $_POST['date'];
        if(!$date){
            $date = date('Y-m',time());
        }
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $cnum = $_SESSION['current_account']['cnum'];
        $entry = $_POST;
        foreach ($entry as $k => $v) {
            $result = D('Entry','Service')->editEntry($v);
        }
        $lirun_10 = D('Rate','Service')->getRateZi($cnum,$date,10);
        $lirun_14 = D('Rate','Service')->getRateZi($cnum,$date,14);
        $lirun_12 = D('Rate','Service')->getRateZi($cnum,$date,9);
        $lirun_15 = D('Rate','Service')->getRateZi($cnum,$date,12);
        $lirun_19 = D('Rate','Service')->getRateZi($cnum,$date,16);
        $folw_11 = D('Flow','Service')->getFlowZi($cnum,$date,11);
        $bilv_m = $folw_11/($lirun_12[0]['now_money']+ $lirun_15[0]['now_money']+ $lirun_19[0]['now_money']);
        $bilv_y = $folw_11/($lirun_12[0]['sum_money']+ $lirun_15[0]['sum_money']+ $lirun_19[0]['sum_money']);
        $bilv_m = floor($bilv_m*10000)/10000*100;
        $bilv_y = floor($bilv_y*10000)/10000*100;
        $add = array(
            "0" => Array(
                "param_id"  => '3',
                "now_money" => $entry['4']['now_money']+$entry['5']['now_money'],
                "sum_money" => $entry['4']['sum_money']+$entry['5']['sum_money']
            ),
            "1" => Array(
                "param_id"  => '7',
                "now_money" => $this->getEntryZa($date),
                "sum_money" => $this->getEntryZa2($date)
            ),
            "2" => Array(
                "param_id"  => '8',
                "now_money" => $lirun_10[0]['now_money'],
                "sum_money" => $lirun_10[0]['sum_money']
            ),
            "3" => Array(
                "param_id"  => '9',
                "now_money" => $lirun_14[0]['now_money'],
                "sum_money" => $lirun_14[0]['sum_money']
            ),
            "4" => Array(
                "param_id"  => '10',
                "now_money" => $bilv_m,
                "sum_money" => $bilv_y
            ),
        );
        foreach ($add as $ka => $va) {
            $result = D('Entry','Service')->editEntry3($date,$cnum,$va);
        }
        $add2 = array(
            "param_id"  => '6',
            "now_money" => $this->getEntryGu($date),
            "sum_money" => $this->getEntryGu2($date)
        );
        $result2 = D('Entry','Service')->editEntry3($date,$cnum,$add2);
        if($result2){
            echo 0;
        }else{
            echo 1;
        }
    }
    
    public function getEntryZa(){
        $date = date('Y-m',time());
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的 减：主营业务总成本
        $result1 = D('Rate','Service')->getRateOther($cnum,$date);
        $result2 = D('Entry','Service')->getEntryZhi($cnum,$date);
        $result = $result1[0]['now_money']-$result2[0]['now_money'];
        
        return $result;
    }
    public function getEntryZa2(){
        $date = date('Y-m',time());
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的 减：主营业务总成本
        $result1 = D('Rate','Service')->getRateOther($cnum,$date);
        $result2 = D('Entry','Service')->getEntryZhi($cnum,$date);
        $result = $result1[0]['sum_money']-$result2[0]['sum_money'];
        return $result;
    }
    
    public function getEntryGu(){
        $date = date('Y-m',time());
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的其中：主营业务成本
        $result1 = D('Rate','Service')->getRateZi($cnum,$date);
        $result2 = D('Entry','Service')->getEntryS($cnum,$date);
        $result3 = D('Entry','Service')->getEntryX($cnum,$date);
        $result4 = D('Entry','Service')->getEntryG($cnum,$date);
        $result = $result1[0]['now_money']+$result2[0]['now_money']+$result3[0]['now_money']+$result4[0]['now_money'];
//        dump($result);
        return $result;
    }
     public function getEntryGu2(){
        $date = date('Y-m',time());
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的其中：主营业务成本
        $result1 = D('Rate','Service')->getRateZi($cnum,$date);
        $result2 = D('Entry','Service')->getEntryS($cnum,$date);
        $result3 = D('Entry','Service')->getEntryX($cnum,$date);
        $result4 = D('Entry','Service')->getEntryG($cnum,$date);
        $result = $result1[0]['sum_money']+$result2[0]['sum_money']+$result3[0]['sum_money']+$result4[0]['sum_money'];
        return $result;
    }
    /**
     * 更新损益数据
     * @return
     */
    public function updateEntry() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $entry = $_POST;
        dump($entry);
        $result = D('Entry','Service')->editEntry($entry);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
}
