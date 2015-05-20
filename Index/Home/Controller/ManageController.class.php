<?php
namespace Home\Controller;

/**
 * ManageController
 * 基础报表信息
 */
class ManageController extends CommonController {
    
    
    /**
     * 资产负债表页面
     * @return
     */
    public function zichan() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取资产负债表的栏目
        $result = D('Massets','Service')->getMassetsList();
        //根据公司编号获取资产负债表的内容
        $con = D('Rich', 'Service')->getAllRich($cnum, $date);
        if (!$con) {
            $this->error($date . '没有数据');
        }
        //========================================================================
         //根据公司编号获取标准损益表的内容
        $con1 = D('Rate','Service')->getAllRate($cnum,$date);
        
        //获取现金流量表的栏目
        $result1 = D('Mcash','Service')->getMcashList();
        
        $cid = array();
        foreach ($result1 as $key => $value) {
            $cid[] = $value['id'];
        }
        //根据公司编号获取现金流量表的内容
        $con2 = D('Flow','Service')->getFlowList($cnum,$cid,$date);
        
        if($con[0]['review']==1 && $con1[0]['review']==1 && $con2[0]['review']==1){
            $res = 0;
        }else{
            $res = 1;
        }
//        dump($res);
        $this->assign('res', $res);
        //========================================================================
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['assets_id']]=$v;
        }
        foreach ($result as $k => $v) {
            $v['rich']=$item[$v['aid']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('assets', $result);
        $this->display();
    }
    
    public function zichanData() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取资产负债表的栏目
        $result = D('Massets','Service')->getMassetsList();
        //根据公司编号获取资产负债表的内容
        $con = D('Rich','Service')->getAllRich($cnum,$date);
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['assets_id']]=$v;
        }
        foreach ($result as $k => $v) {
            $v['rich']=$item[$v['aid']];
            $result[$k]=$v;
        }
        return $result;
    }
    public function zichanData2() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取资产负债表的栏目
        $result = D('Massets','Service')->getMassetsList2();
        //根据公司编号获取资产负债表的内容
        $con = D('Rich','Service')->getAllRich($cnum,$date);
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['assets_id']]=$v;
        }
        foreach ($result as $k => $v) {
            $v['rich']=$item[$v['aid']];
            $result[$k]=$v;
        }
        return $result;
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
		
		
        $date = date("Y_m_d H-i-s",time());
        
//        $fileName .= "_管理资产负债表";
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
	$date11 = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];	
        $data =  D('Mrich','Service')->getMrichList($cnum,$date11);
		/*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改，这里最好能够做成对$data的遍历*/  
        foreach($data as $k => $v){  
            $num=$k+3;
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1'); //  合并 
            // 设置行高度    
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
            $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
            // 字体和样式  
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);  
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);   
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '管理标准资产负债表');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', '资金占用');
            $objPHPExcel->getActiveSheet()->setCellValue('B2', '年初数');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', '期末数');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', '');
            $objPHPExcel->getActiveSheet()->setCellValue('C3', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B11', '');
            $objPHPExcel->getActiveSheet()->setCellValue('C11', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B19', '');
            $objPHPExcel->getActiveSheet()->setCellValue('C19', '');
            $objPHPExcel->getActiveSheet()->setCellValue('A28', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B28', '');
            $objPHPExcel->getActiveSheet()->setCellValue('C28', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B29', '');
            $objPHPExcel->getActiveSheet()->setCellValue('C29', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B33', '');
            $objPHPExcel->getActiveSheet()->setCellValue('C33', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B38', '');
            $objPHPExcel->getActiveSheet()->setCellValue('C38', '');
//            $objPHPExcel->getActiveSheet()->setCellValue('A45', '融资和所有者权益总计');
//            $objPHPExcel->getActiveSheet()->setCellValue('B27', '=B18-B26');
//            $objPHPExcel->getActiveSheet()->setCellValue('C27', '=C18-C26');
//            $objPHPExcel->getActiveSheet()->setCellValue('B10', '=SUM(B4:B9)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C10', '=SUM(C4:C9)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B17', '=SUM(B12:B16)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C17', '=SUM(C12:C16)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B18', '=B10+B17');
//            $objPHPExcel->getActiveSheet()->setCellValue('C18', '=C10+C17');
//            $objPHPExcel->getActiveSheet()->setCellValue('B26', '=SUM(B20:B25)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C26', '=SUM(C20:C25)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B31', '=SUM(B29:B30)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C31', '=SUM(C29:C30)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B35', '=SUM(B33:B34)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C35', '=SUM(C33:C34)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B36', '=B31+B35');
//            $objPHPExcel->getActiveSheet()->setCellValue('C36', '=C31+C35');
//            $objPHPExcel->getActiveSheet()->setCellValue('B43', '=SUM(B38:B42)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C43', '=SUM(C38:C42)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B44', '11111');
//            $objPHPExcel->getActiveSheet()->setCellValue('C44', '=C36+C43');
//            $objPHPExcel->getActiveSheet()->setCellValue('B45', '=B36+B43');
//            $objPHPExcel->getActiveSheet()->setCellValue('C45', '=C36+C43');
            $objPHPExcel->setActiveSheetIndex(0)  
            //Excel的第A列，uid是你查出数组的键值，下面以此类推  
              ->setCellValue('A'.$num, $v['name']) 
              ->setCellValue('B'.$num, $v['start_money'])
              ->setCellValue('C'.$num, $v['end_money']);
        }
        
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->setTitle($fileName);  
        $objPHPExcel->setActiveSheetIndex(0);  
        ob_end_clean();

	header("Pragma: public"); //下面是一堆header的设置，测试的时候加了好多，现在不清楚哪个没用  
        header("Expires: 0");  
        header('Content-Type: application/vnd.ms-excel;charset=utf-8;name="管理资产负债表'.$fileName.'.xls"');  
        header("Content-Type: application/force-download");  
        header("Content-Type: application/octet-stream");  
        header("Content-Type: application/download");  
        header('Content-Disposition: attachment;filename="管理资产负债表'.$fileName.'.xls"');  
        header('Cache-Control: max-age=0');  
        header("Content-Transfer-Encoding:binary");  
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
        exit;
    }
    
    //查询历史页面
    public function historyZichan() {
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date,$end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取资产负债表的栏目
        $result = D('Massets','Service')->getMassetsList();
        //根据公司编号获取资产负债表的内容
        foreach($months as $key=>$date){
            $data[$date] = D('Rich','Service')->getBetweenRich($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
                $item[$v['assets_id']]=$v;
            }
            foreach ($result as $k => $v) {
                
                $v[$date]['rich']=$item[$v['aid']];
                $result[$k]=$v;
            }
        }
        $this->assign('months', $months);
        $this->assign('assets', $result);
        $this->display();
    }
    //资产负债历史的月份数据
    public function getHistoryZichanMonths(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date,$end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        foreach($months as $key=>$date){
            $data[$date] = D('Rich','Service')->getBetweenRich($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
        }
        return $months;
    }
    //资产负债历史数据
    public function getHistoryZichanData(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date,$end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取资产负债表的栏目
        $result = D('Massets','Service')->getMassetsList();
        //根据公司编号获取资产负债表的内容
        foreach($months as $key=>$date){
            $data[$date] = D('Rich','Service')->getBetweenRich($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
                $item[$v['assets_id']]=$v;
            }
            foreach ($result as $k => $v) {
                
                $v[$date]['rich']=$item[$v['aid']];
                $result[$k]=$v;
            }
        }
        return $result;
    }
    //===============================================================================导出资产负债表的历史数据===============================
    public function excelHistoryZichan(){
        $filename = '管理资产负债表.xls';
	$now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
        
        $months = $this->getHistoryZichanMonths();
        $data = $this->getHistoryZichanData();
        
//        dump($data);
        $str = "名称\t";
        foreach ($months as $key => $value){
            $str .= $value."\t";
        }
        $str .= "\n";
        foreach($data as $key1 => $value1){
            //拼接名称
            $str .= $value1['name']."\t";
            //拼接月份
            $bian = $value1['par_name'];
            switch ($bian) {
                case '2+3+4+5+6+7':
                    foreach($months as $key2 => $value2){
                        $param2 = $data['1'][$value2]['rich']['end_money'];
                        $param3 = $data['2'][$value2]['rich']['end_money'];
                        $param4 = $data['3'][$value2]['rich']['end_money'];
                        $param5 = $data['4'][$value2]['rich']['end_money'];
                        $param6 = $data['5'][$value2]['rich']['end_money'];
                        $param7 = $data['6'][$value2]['rich']['end_money'];
                        $param8[$value2] = $param2 + $param3 + $param4 + $param5 + $param6 + $param7;
                        $str .= $param8[$value2]."\t";
                    }   
                break;
                case '10+11+12+13+14':
                    foreach($months as $key2 => $value2){
                        $param10 = $data['9'][$value2]['rich']['end_money'];
                        $param11 = $data['10'][$value2]['rich']['end_money'];
                        $param12 = $data['11'][$value2]['rich']['end_money'];
                        $param13 = $data['12'][$value2]['rich']['end_money'];
                        $param14 = $data['13'][$value2]['rich']['end_money'];
                        $param15[$value2] = $param10 + $param11 + $param12 + $param13 + $param14;
                        $str .= $param15[$value2]."\t";
                    }
                break;
                
                case '8+15':
                    foreach($months as $key2 => $value2){
                        $res16[$value2] = $param8[$value2] + $param15[$value2];
                        $str .= $res16[$value2]."\t";
                    }
                break;
                case '18+19+20+21+22+23':
                    foreach($months as $key2 => $value2){
                        $param18 = $data['17'][$value2]['rich']['end_money'];
                        $param19 = $data['18'][$value2]['rich']['end_money'];
                        $param20 = $data['19'][$value2]['rich']['end_money'];
                        $param21 = $data['20'][$value2]['rich']['end_money'];
                        $param22 = $data['21'][$value2]['rich']['end_money'];
                        $param23 = $data['22'][$value2]['rich']['end_money'];
                        $param24[$value2] = $param18 + $param19 + $param20 + $param21 + $param22 + $param23;
                        $str .= $param24[$value2]."\t";
                    }
                break;
                case '16-24':
                    foreach($months as $key2 => $value2){
                        $param25 = $res16[$value2] - $param24[$value2];
                        $str .= $param25."\t";
                    }
                break;
                case '27+28':
                    foreach($months as $key2 => $value2){
                        $param27 = $data['26'][$value2]['rich']['end_money'];
                        $param28 = $data['27'][$value2]['rich']['end_money'];
                        $param29[$value2] = $param27 + $param28;
                        $str .= $param29[$value2]."\t";
                    }
                break;
                case '31+32':
                    foreach($months as $key2 => $value2){
                        $param31 = $data['30'][$value2]['rich']['end_money'];
                        $param32 = $data['31'][$value2]['rich']['end_money'];
                        $param33[$value2] = $param31 + $param32;
                        $str .= $param33[$value2]."\t";
                    }
                break;
                case '29+33':
                    foreach($months as $key2 => $value2){
                        $param34[$value2] = $param29[$value2] + $param33[$value2];
                        $str .= $param34[$value2]."\t";
                    }
                break;
                case '36+37+38+39+40':
                    foreach($months as $key2 => $value2){
                        $param36 = $data['35'][$value2]['rich']['end_money'];
                        $param37 = $data['36'][$value2]['rich']['end_money'];
                        $param38 = $data['37'][$value2]['rich']['end_money'];
                        $param39 = $data['38'][$value2]['rich']['end_money'];
                        $param40 = $data['39'][$value2]['rich']['end_money'];
                        $param41[$value2] = $param36 + $param37 + $param38 + $param39 + $param40;
                        $str .= $param41[$value2]."\t";
                    }
                break;
                case '34+41':
                    foreach($months as $key2 => $value2){
                        $res42 = $param34[$value2] + $param41[$value2];
                        $str .= $res42."\t";
                    }
                break;
                default:
                    foreach($months as $key2 => $value2){
                        $str .= $value1[$value2]['rich']['end_money']."\t";
                    }
                break;
            }
            //总计
            $str .= "\n";
        }
        echo $str;

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
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $con = D('Rate', 'Service')->getAllRate($cnum, $date);
        if (!$con) {
            $this->error($date . '没有数据');
        }
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;
            //取出interest_id = 9的值
            if($v['interest_id'] == '9'){
                $interest9 = $v['now_money'];
                $interest9s = $v['sum_money'];
            }
            if($v['interest_id'] == '12'){
                $interest12 = $v['now_money'];
                $interest12s = $v['sum_money'];
            }
        }
        
        foreach ($result as $k => $v) {
            $v['rate']=$item[$v['iid']];
            $result[$k]=$v;
        }
        
//        dump($con);
        //根据编号和日期获取参数录入的内容
        $can = D('Entry','Service')->getAllEntry($cnum,$date);
       
        $itemp=array();
        foreach($can as $k=>$v){
            $itemp[$v['param_id']]=$v;
            //取出param_id = 8的值
            if($v['param_id'] == '4'){
                $param4 = $v['now_money'];
                $param4s = $v['sum_money'];
            }
            if($v['param_id'] == '5'){
                $param5 = $v['now_money'];
                $param5s = $v['sum_money'];
            }
            if($v['param_id'] == '8'){
                $param8 = $v['now_money'];
                $param8s = $v['sum_money'];
            }
            if($v['param_id'] == '9'){
                $param9 = $v['now_money'];
                $param9s = $v['sum_money'];
            }
        }
        $paramhe = $param4+$param5+$param8;
        $paramhes = $param4s+$param5s+$param8s;
        //销售费用 
        $sellold1  = $interest9 - $paramhe;
        $sellold2  = $interest9s - $paramhes;
        $sell1 = number_format($sellold1,2,'.','');
        $sell2 = number_format($sellold2,2,'.','');
        $this->assign('sell1', $sell1);
        $this->assign('sell2', $sell2);
        //管理费用
        $managerold1 = $interest12 - $param9;
        $managerold2 = $interest12s - $param9s;
        $manager1 = number_format($managerold1,2,'.','');
        $manager2 = number_format($managerold2,2,'.','');
        $this->assign('manager1', $manager1);
        $this->assign('manager2', $manager2);
        
        foreach ($result as $k => $v) {
            $v['entry']=$itemp[$v['iid']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('interests', $result);
        $this->display();
    }
    public function biaozhunData() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的栏目
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getAllRate($cnum,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;
        }
        foreach ($result as $k => $v) {
            $v['rate']=$item[$v['iid']];
            $result[$k]=$v;
        }
        
        //根据编号和日期获取参数录入的内容
        $can = D('Entry','Service')->getAllEntry($cnum,$date);
       
        $itemp=array();
        foreach($can as $k=>$v){
            $itemp[$v['param_id']]=$v;
        }
        foreach ($result as $k => $v) {
            $v['entry']=$itemp[$v['iid']];
            $result[$k]=$v;
        }
        return $result;
    }
    public function sell1() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getAllRate($cnum,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;
            //取出interest_id = 9的值
            if($v['interest_id'] == '9'){
                $interest9 = $v['now_money'];
            }
        }
        
        //根据编号和日期获取参数录入的内容
        $can = D('Entry','Service')->getAllEntry($cnum,$date);
       
        $itemp=array();
        foreach($can as $k=>$v){
            $itemp[$v['param_id']]=$v;
            //取出param_id = 8的值
            if($v['param_id'] == '4'){
                $param4 = $v['now_money'];
            }
            if($v['param_id'] == '5'){
                $param5 = $v['now_money'];
            }
            if($v['param_id'] == '8'){
                $param8 = $v['now_money'];
            }
        }
        $paramhe = $param4+$param5+$param8;
        //销售费用 
        $sellold1  = $interest9 - $paramhe;
        $sell1 = number_format($sellold1,2,'.','');
        return $sell1;
    }
    public function sell2() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getAllRate($cnum,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;
            //取出interest_id = 9的值
            if($v['interest_id'] == '9'){
                $interest9s = $v['sum_money'];
            }
        }
        
        //根据编号和日期获取参数录入的内容
        $can = D('Entry','Service')->getAllEntry($cnum,$date);
       
        $itemp=array();
        foreach($can as $k=>$v){
            $itemp[$v['param_id']]=$v;
            //取出param_id = 8的值
            if($v['param_id'] == '4'){
                $param4s = $v['sum_money'];
            }
            if($v['param_id'] == '5'){
                $param5s = $v['sum_money'];
            }
            if($v['param_id'] == '8'){
                $param8s = $v['sum_money'];
            }
        }
        $paramhes = $param4s+$param5s+$param8s;
        //销售费用 
        $sellold2  = $interest9s - $paramhes;
        $sell2 = number_format($sellold2,2,'.','');
        return $sell2;
    }
    public function manager1() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getAllRate($cnum,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;if($v['interest_id'] == '12'){
                $interest12 = $v['now_money'];
            }
        }
        
        //根据编号和日期获取参数录入的内容
        $can = D('Entry','Service')->getAllEntry($cnum,$date);
       
        $itemp=array();
        foreach($can as $k=>$v){
            $itemp[$v['param_id']]=$v;
            if($v['param_id'] == '9'){
                $param9 = $v['now_money'];
            }
        }
        //管理费用
        $managerold1 = $interest12 - $param9;
        $manager1 = number_format($managerold1,2,'.','');
        return $manager1;
    }
    public function manager2() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//      //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getAllRate($cnum,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['interest_id']]=$v;
            if($v['interest_id'] == '12'){
                $interest12s = $v['sum_money'];
            }
        }
        
        //根据编号和日期获取参数录入的内容
        $can = D('Entry','Service')->getAllEntry($cnum,$date);
       
        $itemp=array();
        foreach($can as $k=>$v){
            $itemp[$v['param_id']]=$v;
            if($v['param_id'] == '9'){
                $param9s = $v['sum_money'];
            }
        }
        //管理费用
        $managerold2 = $interest12s - $param9s;
        $manager2 = number_format($managerold2,2,'.','');
        return $manager2;
    }
    //excel导出数据代码
    public function excelBiaozhun(){

        require_once'./phpexcel/PHPExcel.php';
        require_once'./phpexcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls
        require_once'./phpexcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式 
        require_once'./phpexcel/PHPExcel/IOFactory.php'; // 用于 excel-2007 格式 
		
		
        $date = date("Y_m_d H-i-s",time());
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
        
	$date11 = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];	
	$data = D('Mrate','Service')->getMrateList($cnum,$date11);
		/*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改，这里最好能够做成对$data的遍历*/  
        foreach($data as $k => $v){  
            $num=$k+3;
            $objPHPExcel->getActiveSheet()->mergeCells('A1:D1'); //  合并 
            // 设置行高度    
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
            $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
            // 字体和样式  
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
            $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);  
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '管理标准损益表');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', '名称');
            $objPHPExcel->getActiveSheet()->setCellValue('B2', '本期发生额');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', '本年累计数');
            $objPHPExcel->getActiveSheet()->setCellValue('D2', '比率分析');
            $objPHPExcel->setActiveSheetIndex(0)  
                //Excel的第A列，uid是你查出数组的键值，下面以此类推  
                ->setCellValue('A'.$num, $v['name']) 
                ->setCellValue('B'.$num, $v['now_money'])
                ->setCellValue('C'.$num, $v['sum_money'])
                ->setCellValue('D'.$num, $v['rate']);
        } 
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->setTitle($fileName);  
        $objPHPExcel->setActiveSheetIndex(0);  
        ob_end_clean();

	header("Pragma: public"); //下面是一堆header的设置，测试的时候加了好多，现在不清楚哪个没用  
        header("Expires: 0");  
        header('Content-Type: application/vnd.ms-excel;charset=utf-8;name="管理标准损益表'.$fileName.'.xls"');  
        header("Content-Type: application/force-download");  
        header("Content-Type: application/octet-stream");  
        header("Content-Type: application/download");  
        header('Content-Disposition: attachment;filename="管理标准损益表'.$fileName.'.xls"');  
        header('Cache-Control: max-age=0');  
        header("Content-Transfer-Encoding:binary");  
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
        exit;
    }
    //---------------------------------------------------------------------查询历史的代码-----------------------------------------
    public function historyBiaozhun(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date, $end_date);
//        dump($months);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的栏目
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $data = array(); 
        foreach($months as $key=>$date){
            $data[$date] = D('Rate','Service')->getBetweenRate($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
    //                dump($v);
                $item[$v['interest_id']]=$v;
                //取出interest_id = 9的值
                if($v['interest_id'] == '9'){
                    $interest9[$date] = $v['now_money'];
                }
                if($v['interest_id'] == '12'){
                    $interest12[$date] = $v['now_money'];
                }
            }

            foreach ($result as $k => $v) {
                $v[$date]['rate']=$item[$v['iid']];
                $result[$k]=$v;
            }
            //根据编号和日期获取参数录入的内容
            $can[$date] = D('Entry','Service')->getBetweenEntry($cnum,$date);
            $itemp=array();
            foreach($can[$date] as $k=>$v){
                $itemp[$v['param_id']]=$v;
                //取出param_id = 8的值
                if($v['param_id'] == '4'){
                    $param4[$date] = $v['now_money'];
                }
                if($v['param_id'] == '5'){
                    $param5[$date] = $v['now_money'];
                }
                if($v['param_id'] == '8'){
                    $param8[$date] = $v['now_money'];
                }
                if($v['param_id'] == '9'){
                    $param9[$date] = $v['now_money'];
                }
            }
            $paramhe[$date] = $param4[$date]+$param5[$date]+$param8[$date];
            //销售费用 
            $sellold1[$date]  = $interest9[$date] - $paramhe[$date];
            $sell1[$date] = number_format($sellold1[$date],2,'.','');
            $this->assign('sell1', $sell1);
            //管理费用
            $managerold1[$date] = $interest12[$date] - $param9[$date];
            $manager1[$date] = number_format($managerold1[$date],2,'.','');
            $this->assign('manager1', $manager1);

            foreach ($result as $k => $v) {
                $v[$date]['entry']=$itemp[$v['iid']];
                $result[$k]=$v;
            }
        }
        $this->assign('months', $months);
        
//        dump($months);
        
        $this->assign('interests', $result);
        $this->display();
    }
    //===============================================================================
    public function historyBiaozhunMonths(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date, $end_date);
//        dump($months);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的栏目
        //根据公司编号获取标准损益表的内容
        $data = array(); 
        foreach($months as $key=>$date){
            $data[$date] = D('Rate','Service')->getBetweenRate($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
        }
//        dump($months);
        return $months;
    }
    //===============================================================================
    public function historyBiaozhunDatas(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date, $end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的栏目
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $data = array(); 
        foreach($months as $key=>$date){
            $data[$date] = D('Rate','Service')->getBetweenRate($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
                $item[$v['interest_id']]=$v;
            }

            foreach ($result as $k => $v) {
                $v[$date]['rate']=$item[$v['iid']];
                $result[$k]=$v;
            }
            //根据编号和日期获取参数录入的内容
            $can[$date] = D('Entry','Service')->getBetweenEntry($cnum,$date);
            $itemp=array();
            foreach($can[$date] as $k=>$v){
                $itemp[$v['param_id']]=$v;
            }
            foreach ($result as $k => $v) {
                $v[$date]['entry']=$itemp[$v['iid']];
                $result[$k]=$v;
            }
        }
//        dump($result);
        return $result;
    }
    //==============================================================================
    public function historyBiaozhunSell(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date, $end_date);
//        dump($months);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的栏目
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $data = array(); 
        foreach($months as $key=>$date){
            $data[$date] = D('Rate','Service')->getBetweenRate($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
                $item[$v['interest_id']]=$v;
                if($v['interest_id'] == '9'){
                    $interest9[$date] = $v['now_money'];
                }
            }

            foreach ($result as $k => $v) {
                $v[$date]['rate']=$item[$v['iid']];
                $result[$k]=$v;
            }
            //根据编号和日期获取参数录入的内容
            $can[$date] = D('Entry','Service')->getBetweenEntry($cnum,$date);
            $itemp=array();
            foreach($can[$date] as $k=>$v){
                $itemp[$v['param_id']]=$v;
                //取出param_id = 8的值
                if($v['param_id'] == '4'){
                    $param4[$date] = $v['now_money'];
                }
                if($v['param_id'] == '5'){
                    $param5[$date] = $v['now_money'];
                }
                if($v['param_id'] == '8'){
                    $param8[$date] = $v['now_money'];
                }
                if($v['param_id'] == '9'){
                    $param9[$date] = $v['now_money'];
                }
            }
            $paramhe[$date] = $param4[$date]+$param5[$date]+$param8[$date];
            //销售费用 
            $sellold1[$date]  = $interest9[$date] - $paramhe[$date];
            $sell[$date] = number_format($sellold1[$date],2,'.','');
        }
        return $sell;
    }
    //===================================================================================
    public function historyBiaozhunManage(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date, $end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取标准损益表的栏目
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $data = array(); 
        foreach($months as $key=>$date){
            $data[$date] = D('Rate','Service')->getBetweenRate($cnum,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
                $item[$v['interest_id']]=$v;
                if($v['interest_id'] == '12'){
                    $interest12[$date] = $v['now_money'];
                }
            }

            foreach ($result as $k => $v) {
                $v[$date]['rate']=$item[$v['iid']];
                $result[$k]=$v;
            }
            //根据编号和日期获取参数录入的内容
            $can[$date] = D('Entry','Service')->getBetweenEntry($cnum,$date);
            $itemp=array();
            foreach($can[$date] as $k=>$v){
                $itemp[$v['param_id']]=$v;
                if($v['param_id'] == '9'){
                    $param9[$date] = $v['now_money'];
                }
            }
            //管理费用
            $managerold1[$date] = $interest12[$date] - $param9[$date];
            $manage[$date] = number_format($managerold1[$date],2,'.','');
        }
        return $manage;
    }
    //===========================================================================查询历史的导出数据==================================================
    public function excelHistoryBiaozhun(){
        $filename = '管理标准损益表.xls';
	$now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
        
        $months = $this->historyBiaozhunMonths();
        $data = $this->historyBiaozhunDatas();
        //销售费用
        $sell = $this->historyBiaozhunSell();
        //管理费用
        $manage = $this->historyBiaozhunManage();
//        dump($data);
        $str = "名称\t";
        foreach ($months as $key => $value){
            $str .= $value."\t";
        }
        $str .= "\n";
        foreach($data as $key1 => $value1){
            //拼接名称
            $str .= $value1['name']."\t";
            //拼接月份
            $bian = $value1['par_name'];
            switch ($bian) {
                case 'interest':
                    foreach($months as $key2 => $value2){
                        $str .= $value1[$value2]['rate']['now_money']."\t";
                    }   
                break;
                case 'param':
                    foreach($months as $key2 => $value2){
                        $str .= $value1[$value2]['entry']['now_money']."\t";
                    }   
                break;
                case '4-5':
                    foreach($months as $key2 => $value2){
                        $param4 = $data['3'][$value2]['rate']['now_money'];
                        $param5 = $data['4'][$value2]['entry']['now_money'];
                        $res6 = $param4 - $param5;
                        $str .= $res6."\t";
                    }
                break;
                
                case '8+9':
                    foreach($months as $key2 => $value2){
                        $param8 = $data['7'][$value2]['entry']['now_money'];
                        $param9 = $data['8'][$value2]['entry']['now_money'];
                        $res7 = $param8 + $param9;
                        $str .= $res7."\t";
                    }
                break;
                case '6-7':
                    foreach($months as $key2 => $value2){
                        $res10 = $res6 - $res7;
                        $str .= $res10."\t";
                    }
                break;
                case 'interest 9-pa(4+5+8)':
                    foreach($months as $key2 => $value2){
                        $ressell = $sell[$value2];
                        $str .= $ressell."\t";
                    }
                break;
                case 'interest12-param9':
                    foreach($months as $key2 => $value2){
                        $resmanage = $manage[$value2];
                        $str .= $resmanage."\t";
                    }
                break;
                case '12+13+14':
                    foreach($months as $key2 => $value2){
                        $param14 = $data['13'][$value2]['entry']['now_money'];
                        $res11 = $sell[$value2] + $manage[$value2] + $param14;
                        $str .= $res11."\t";
                    }
                break;
                case '10-11':
                    foreach($months as $key2 => $value2){
                        $res15 = $res10 - $res11;
                        $str .= $res15."\t";
                    }
                break;
                case '15-16':
                    foreach($months as $key2 => $value2){
                        $param16 = $data['15'][$value2]['rate']['now_money'];
                        $res17 = $res15 - $param16;
                        $str .= $res17."\t";
                    }
                break;
                case '17+18+19+20+21-22':
                    foreach($months as $key2 => $value2){
                        $param18 = $data['17'][$value2]['rate']['now_money'];
                        $param19 = $data['18'][$value2]['rate']['now_money'];
                        $param20 = $data['19'][$value2]['rate']['now_money'];
                        $param21 = $data['20'][$value2]['rate']['now_money'];
                        $param22 = $data['21'][$value2]['rate']['now_money'];
                        $res23 = $res17 + $param18 + $param19 + $param20 + $param21 - $param22;
                        $str .= $res23."\t";
                    }
                break;
                default:
                    $str .= "\t";
                break;
            }
           //总计
            $str .= "\n";
        }
        echo $str;

    }
   
    //获取查询历史选择的所有月份
    public function getMonths($start_date,$end_date){
        //列出区间里面的所有月份
        $year_min = substr($start_date, 0, 4);
        $year_max = substr($end_date, 0, 4);
        
        $month_min = substr($start_date, 5, 2);
        $month_max = substr($end_date, 5, 2);
        $period = array();
        try {
            if ($year_min > $year_max){
                throw new Exception();
            }else if ($year_min == $year_max){
                if ($month_min > $month_max){
                    throw new Exception();
                }
                for ($month = $month_min; $month <= $month_max; $month++) {
                    $str = '0' . $month;
                    $len=strlen($str);
                    $r=substr($str,$len-2,$len);
                    $period[] = $year_max . '-'.$r;
                }
            }else {
                for ($month = $month_min; $month <= 12; $month++) {
                    $str = '0' . $month;
                    $len=strlen($str);
                    $r=substr($str,$len-2,$len);
                    $period[] = $year_min . '-'.$r;
                }
                for ($year = $year_min + 1; $year < $year_max; $year++) {
                    for ($month = $month_min; $month <= $month_max; $month++) {
                        $str = '0' . $month;
                        $len=strlen($str);
                        $r=substr($str,$len-2,$len);
                        $period[] = $year . '-'.$r;
                    }
                }
                for ($month = 1; $month <= $month_max; $month++) {
                    $str = '0' . $month;
                    $len=strlen($str);
                    $r=substr($str,$len-2,$len);
                    $period[] = $year_max . '-'.$r;
                }
            }
        }catch (Exception $e) {
            echo 'Start date occures after end date.';
        }
        return $period;
    }


    /**
     * 管理现金流量表页面
     * @return
     */
    public function xianjin() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取现金流量表的栏目
        $result = D('Mcash','Service')->getMcashList();
        
        $cid = array();
        foreach ($result as $key => $value) {
            $cid[] = $value['id'];
        }
        //根据公司编号获取现金流量表的内容
        $con = D('Flow', 'Service')->getFlowList($cnum, $cid, $date);
        if (!$con) {
            $this->error($date . '没有数据');
        }

        $item=array();
        foreach($con as $k=>$v){
            $item[$v['cash_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['cash']=$item[$v['cid']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
//        dump($result);
        $this->assign('cashs', $result);
        
        $this->display();
    }
    
    
    public function xianjinData() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取现金流量表的栏目
        $result = D('Mcash','Service')->getMcashList();
        
        //根据公司编号获取现金流量表的内容
        $con = D('Flow','Service')->getAllFlow($cnum,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['cash_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['cash']=$item[$v['id']];
            $result[$k]=$v;
        }
        return $result;
        
    }
   //excel导出数据代码
    public function excelXianjin(){

        require_once'./phpexcel/PHPExcel.php';
        require_once'./phpexcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls
        require_once'./phpexcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式 
        require_once'./phpexcel/PHPExcel/IOFactory.php'; // 用于 excel-2007 格式 
		
		
        $date = date("Y_m_d H-i-s",time());
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
	
        $date11 = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
	$data = D('Mflow','Service')->getMflowList($cnum,$date11);
        
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改，这里最好能够做成对$data的遍历*/  
        foreach($data as $k => $v){  
            $num=$k+3;
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1'); //  合并 
            // 设置行高度    
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);  
            $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);  
  
            // 字体和样式  
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
            $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);  
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true); 
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '管理现金流量表');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', '项目');
            $objPHPExcel->getActiveSheet()->setCellValue('B2', '金额');
            $objPHPExcel->getActiveSheet()->setCellValue('C2', '内部结构');
            $objPHPExcel->getActiveSheet()->setCellValue('D2', '流入结构');
            $objPHPExcel->getActiveSheet()->setCellValue('E2', '流出结构');
            $objPHPExcel->getActiveSheet()->setCellValue('F2', '流入流出结构');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', ''); 
            $objPHPExcel->getActiveSheet()->setCellValue('B14', ''); 
            $objPHPExcel->getActiveSheet()->setCellValue('B25', '');
            $objPHPExcel->getActiveSheet()->setCellValue('B35', '');
            $objPHPExcel->getActiveSheet()->setCellValue('A35', '');
//            $objPHPExcel->getActiveSheet()->setCellValue('B7', '=SUM(B4:B6)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B12', '=SUM(B8:B11)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B13', '=B7+B12');
//            $objPHPExcel->getActiveSheet()->setCellValue('B19', '=SUM(B15:B18)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B23', '=SUM(B20:B22)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B24', '=B19+B23');
//            $objPHPExcel->getActiveSheet()->setCellValue('B29', '=SUM(B26:B28)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B33', '=SUM(B30:B32)');
//            $objPHPExcel->getActiveSheet()->setCellValue('B34', '=B29+B33');
//            $objPHPExcel->getActiveSheet()->setCellValue('B37', '=B13+B24+B34+B36');
//            
//            $objPHPExcel->getActiveSheet()->setCellValue('C4', '=ROUND(B4/B7,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C5', '=ROUND(B5/B7,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C6', '=ROUND(B6/B7,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C7', '=B7/B7');
//            $objPHPExcel->getActiveSheet()->setCellValue('C8', '=ROUND(B8/B12,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C9', '=ROUND(B9/B12,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C10', '=ROUND(B10/B12,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C11', '=ROUND(B11/B12,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C12', '=B12/B12');
//            
//            $objPHPExcel->getActiveSheet()->setCellValue('C15', '=ROUND(B15/B19,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C16', '=ROUND(B16/B19,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C17', '=ROUND(B17/B19,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C18', '=ROUND(B18/B19,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C19', '=B19/B19');
//            $objPHPExcel->getActiveSheet()->setCellValue('C20', '=ROUND(B20/B23,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C21', '=ROUND(B21/B23,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C22', '=ROUND(B22/B23,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C23', '=B23/B23');
//            
//            $objPHPExcel->getActiveSheet()->setCellValue('C26', '=ROUND(B26/B29,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C27', '=ROUND(B27/B29,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C28', '=ROUND(B28/B29,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C29', '=B29/B29');
//            $objPHPExcel->getActiveSheet()->setCellValue('C30', '=ROUND(B30/B33,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C31', '=ROUND(B31/B33,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C32', '=ROUND(B32/B33,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('C33', '=B33/B33');
//            
//            $objPHPExcel->getActiveSheet()->setCellValue('D7', '=ROUND(B7/(B7+B19+B29),2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('D19', '=ROUND(B19/(B7+B19+B29),2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('D29', '=ROUND(B29/(B7+B19+B29),2)');
//            
//            $objPHPExcel->getActiveSheet()->setCellValue('E12', '=ROUND(B12/(B12+B23+B33),2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('E23', '=ROUND(B23/(B12+B23+B33),2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('E33', '=ROUND(B33/(B12+B23+B33),2)');
//            
//            $objPHPExcel->getActiveSheet()->setCellValue('F12', '=ROUND(B7/B12,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('F23', '=ROUND(B19/B23,2)');
//            $objPHPExcel->getActiveSheet()->setCellValue('F33', '=ROUND(B29/B33,2)');
            
            $objPHPExcel->setActiveSheetIndex(0)  
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                  ->setCellValue('A'.$num, $v['name'])
                ->setCellValue('B'.$num, $v['money'])
                ->setCellValue('C'.$num, $v['inside'])
                ->setCellValue('D'.$num, $v['into'])
                ->setCellValue('E'.$num, $v['outof'])
                ->setCellValue('F'.$num, $v['in_out']);
//            if($v['par_name'] == 'cash'){
//                $objPHPExcel->setActiveSheetIndex(0)    
//                  ->setCellValue('B'.$num, $v['cash']['money']);
//            }
            
        } 
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->setTitle($fileName);  
        $objPHPExcel->setActiveSheetIndex(0);  
        ob_end_clean();

	header("Pragma: public"); //下面是一堆header的设置，测试的时候加了好多，现在不清楚哪个没用  
        header("Expires: 0");  
        header('Content-Type: application/vnd.ms-excel;charset=utf-8;name="管理现金流量表'.$fileName.'.xls"');  
        header("Content-Type: application/force-download");  
        header("Content-Type: application/octet-stream");  
        header("Content-Type: application/download");  
        header('Content-Disposition: attachment;filename="管理现金流量表'.$fileName.'.xls"');  
        header('Cache-Control: max-age=0');  
        header("Content-Transfer-Encoding:binary");  
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
        exit;
    }
    //查询历史现金流量表页面
    public function historyXianjin(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date,$end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取现金流量表的栏目
        $result = D('Mcash','Service')->getMcashList();
        $cid = array();
        foreach ($result as $key => $value) {
            $cid[] = $value['id'];
        }
        //根据公司编号获取资产负债表的内容
        foreach($months as $key=>$date){
            $data[$date] = D('Flow','Service')->getBetweenFlow($cnum,$cid,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
                $item[$v['cash_id']]=$v;
            }
            foreach ($result as $k => $v) {
                $v[$date]['cash']=$item[$v['cid']];
                $result[$k]=$v;
            }
        }
//        dump($result);
        $this->assign('months', $months);
        $this->assign('cashs', $result);
        $this->display();
    }
    //获取excel所需的月份
    public function historyXianjinMonths(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date,$end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取现金流量表的栏目
        $result = D('Mcash','Service')->getMcashList();
        $cid = array();
        foreach ($result as $key => $value) {
            $cid[] = $value['id'];
        }
        //根据公司编号获取资产负债表的内容
        foreach($months as $key=>$date){
            $data[$date] = D('Flow','Service')->getBetweenFlow($cnum,$cid,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
        }
        return $months;
    }
    //获取excel所需的data数据
    public function historyXianjinDatas(){
        $start_date = $_GET['sdate'];
        $end_date = $_GET['edate'];
        //获取日期区间里面的所有月份
        $months = $this->getMonths($start_date,$end_date);
        $cnum = $_SESSION['current_account']['cnum'];
        //获取现金流量表的栏目
        $result = D('Mcash','Service')->getMcashList();
        $cid = array();
        foreach ($result as $key => $value) {
            $cid[] = $value['id'];
        }
        //根据公司编号获取资产负债表的内容
        foreach($months as $key=>$date){
            $data[$date] = D('Flow','Service')->getBetweenFlow($cnum,$cid,$date);
            if(empty($data[$date])){
                unset($months[$key]); 
            }
            $item=array();
            foreach($data[$date] as $k=>$v){
                $item[$v['cash_id']]=$v;
            }
            foreach ($result as $k => $v) {
                $v[$date]['cash']=$item[$v['cid']];
                $result[$k]=$v;
            }
        }
        return $result;
    }
    //=========================================================================excel导出多月份管理现金流量表的数据===========================================
    public function excelHistoryXianjin(){
        $filename = '管理现金流量表.xls';
	$now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
        
        $months = $this->historyXianjinMonths();
        $data = $this->historyXianjinDatas();
        $str = "名称\t";
        foreach ($months as $key => $value){
            $str .= $value."\t";
        }
        $str .= "总计\n";
        foreach($data as $key1 => $value1){
            //拼接名称
            $str .= $value1['name']."\t";
            //拼接月份
            $bian = $value1['par_name'];
            switch ($bian) {
                case 'cash':
                    foreach($months as $key2 => $value2){
                        $str .= $value1[$value2]['cash']['money']."\t";
                    }   
                break;
                case '2+3+4':
                    foreach($months as $key2 => $value2){
                        $param2 = $data['1'][$value2]['cash']['money'];
                        $param3 = $data['2'][$value2]['cash']['money'];
                        $param4 = $data['3'][$value2]['cash']['money'];
                        $res5[$value2] = $param2 + $param3 + $param4;
                        $str .= $res5[$value2]."\t";
                    }
                break;
                
                case '6+7+8+9':
                    foreach($months as $key2 => $value2){
                        $param6 = $data['5'][$value2]['cash']['money'];
                        $param7 = $data['6'][$value2]['cash']['money'];
                        $param8 = $data['7'][$value2]['cash']['money'];
                        $param9 = $data['8'][$value2]['cash']['money'];
                        $res10[$value2] = $param6 + $param7 + $param8 + $param9;
                        $str .= $res10[$value2]."\t";
                    }
                break;
                case '5-10':
                    foreach($months as $key2 => $value2){
                        $res11[$value2] = $res5[$value2] - $res10[$value2];
                        $str .= $res11[$value2]."\t";
                    }
                break;
                case '13+14+15+16':
                    foreach($months as $key2 => $value2){
                        $param13 = $data['12'][$value2]['cash']['money'];
                        $param14 = $data['13'][$value2]['cash']['money'];
                        $param15 = $data['14'][$value2]['cash']['money'];
                        $param16 = $data['15'][$value2]['cash']['money'];
                        $res17[$value2] = $param13 + $param14 + $param15 + $param16;
                        $str .= $res17[$value2]."\t";
                    }
                break;
                case '18+19+20':
                    foreach($months as $key2 => $value2){
                        $param18 = $data['17'][$value2]['cash']['money'];
                        $param19 = $data['18'][$value2]['cash']['money'];
                        $param20 = $data['19'][$value2]['cash']['money'];
                        $res21[$value2] = $param18 + $param19 + $param20;
                        $str .= $res21[$value2]."\t";
                    }
                break;
                case '17-21':
                    foreach($months as $key2 => $value2){
                        $res22[$value2] = $res17[$value2] - $res21[$value2];
                        $str .= $res22[$value2]."\t";
                    }
                break;
                case '24+25+26':
                    foreach($months as $key2 => $value2){
                        $param24 = $data['23'][$value2]['cash']['money'];
                        $param25 = $data['24'][$value2]['cash']['money'];
                        $param26 = $data['25'][$value2]['cash']['money'];
                        $res27[$value2] = $param24 + $param25 + $param26;
                        $str .= $res27[$value2]."\t";
                    }
                break;
                case '28+29+30':
                    foreach($months as $key2 => $value2){
                        $param28 = $data['27'][$value2]['cash']['money'];
                        $param29 = $data['28'][$value2]['cash']['money'];
                        $param30 = $data['29'][$value2]['cash']['money'];
                        $res31[$value2] = $param28 + $param29 + $param30;
                        $str .= $res31[$value2]."\t";
                    }
                break;
                case '27-31':
                    foreach($months as $key2 => $value2){
                        $res32[$value2] = $res27[$value2] - $res31[$value2];
                        $str .= $res32[$value2]."\t";
                    }
                break;
                case '11+22+32+34':
                    foreach($months as $key2 => $value2){
                        $param34 = $data['33'][$value2]['cash']['money'];
                        $res35[$value2] = $res11[$value2] + $res22[$value2] + $res32[$value2] + $param34;
                        $str .= $res35[$value2]."\t";
                    }
                break;
                default:
                    $str .= "\t\t";
                break;
            }
           //总计
            if(empty($bian) || $bian=="未用"){
                $str .= "\n";
            }else{
                $str .= "总计\n";
            }
            
        }
        echo $str;
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
        //根据公司编号获取标准损益表的内容
        $con = D('Entry','Service')->getEntryList($cnum,$pid,$date);
       
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['param_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['entry']=$item[$v['id']];
            $result[$k]=$v;
        }
//        dump($result);
        $this->assign('entrys', $result);
        $this->display();
    }
    
    /**
     * 创建管理资产负债表的数据
     * @return
     */
    public function addMrich() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $mrich = $_POST;
//        dump($mrich);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mrich','Service')->createMrich($cnum,$mrich);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 修改管理资产负债表的数据
     * @return
     */
    public function editMrich() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $mrich = $_POST;
//        dump($mrich);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mrich','Service')->updateMrich($cnum,$mrich);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    //查询mrich表当月数据是否已经存在
    public function isExistMrich(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mrich','Service')->existMrich($cnum,$date);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    //查询mrate表当月数据是否已经存在
    public function isExistMrate(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mrate','Service')->existMrate($cnum,$date);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 创建管理标准损益表的数据
     * @return
     */
    public function addMrate() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $mrate = $_POST;
//        dump($mrich);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mrate','Service')->createMrate($cnum,$mrate);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 修改管理标准损益表的数据
     * @return
     */
    public function editMrate() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $mrate = $_POST;
//        dump($mrich);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mrate','Service')->updateMrate($cnum,$mrate);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    //查询mflow表当月数据是否已经存在
    public function isExistMflow(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mflow','Service')->existMflow($cnum,$date);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 创建管理现金流量表的数据
     * @return
     */
    public function addMflow() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $mflow = $_POST;
//        dump($mrich);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mflow','Service')->createMflow($cnum,$mflow);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 修改管理现金流量表的数据
     * @return
     */
    public function editMflow() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $mflow = $_POST;
//        dump($mrich);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Mflow','Service')->updateMflow($cnum,$mflow);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
}
