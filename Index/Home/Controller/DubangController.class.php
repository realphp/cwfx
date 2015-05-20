<?php
namespace Home\Controller;

/**
 * DubangController
 * 杜邦分析法
 */
class DubangController extends CommonController {
    /**
     * 杜邦分析法页面
     * @return
     */
    public function index() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='15'){
                $sun04B17 = $v['now_money'];
                $sun04C17 = $v['sum_money'];
            }
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
            if($v['mi_id']==='23'){
                $sun04B26 = $v['now_money'];
                $sun04C26 = $v['sum_money'];
            }
            if($v['mi_id']==='7'){
                $sun04B9 = $v['now_money'];
                $sun04C9 = $v['sum_money'];
            }
            if($v['mi_id']==='11'){
                $sun04B13 = $v['now_money'];
                $sun04C13 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='41'){
                $zi05C51 = $vM['end_money'];
            }
            if($vM['ma_id']==='25'){
                $zi05C27 = $vM['end_money'];
            }
            if($vM['ma_id']==='16'){
                $zi05C18 = $vM['end_money'];
            }
            if($vM['ma_id']==='4'){
                $zi05B6 = $vM['start_money'];
                $zi05C6 = $vM['end_money'];
            }
            if($vM['ma_id']==='7'){
                $zi05B9 = $vM['start_money'];
                $zi05C9 = $vM['end_money'];
            }
            if($vM['ma_id']==='11'){
                $zi05C13 = $vM['end_money'];
            }
            if($vM['ma_id']==='18'){
                $zi05B20 = $vM['start_money'];
                $zi05C20 = $vM['end_money'];
            }
            if($vM['ma_id']==='16'){
                $zi05C18 = $vM['end_money'];
            }
            if($vM['ma_id']==='8'){
                $zi05C10 = $vM['end_money'];
            }
            if($vM['ma_id']==='24'){
                $zi05C26 = $vM['end_money'];
            }
            if($vM['ma_id']==='29'){
                $zi05C32 = $vM['end_money'];
            }
            if($vM['ma_id']==='34'){
                $zi05C44 = $vM['end_money'];
            }
        }
        
        //根据公司编号获取管理现金表的内容
        $conMflow = D('Mflow','Service')->getMflowList($cnum,$date);
        foreach($conMflow as $kMf=>$vMf){
            if($vMf['mc_id']==='2'){
                $xianC4 = $vMf['money'];
            }
            if($vMf['mc_id']==='11'){
                $xianC13 = $vMf['money'];
            }
        }
        // 查询标准损益表的数据
        $conRate = D('Rate','Service')->getAllRate($cnum,$date);
        foreach($conRate as $kR=>$vR){
            if($vR['interest_id']==='5'){
                $rateB5 = $vR['now_money'];
                $rateF5 = $vR['sum_money'];
            }
            //获取净利润的值
            if($vR['interest_id']==='26'){
                $rateJing = $vR['now_money'];
            }
            //获取主营业务收入的值
            if($vR['interest_id']==='4'){
                $rateZhu = $vR['now_money'];
            }
        }
        // 查询标准损益表上个月的数据
        $conRateLast = D('Rate','Service')->getLastRate($cnum,$date);
        foreach($conRateLast as $kRl=>$vRl){
            //获取净利润的值
            if($vRl['interest_id']==='26'){
                $rateJingLast = $vRl['now_money'];
            }
            //获取主营业务收入的值
            if($vRl['interest_id']==='4'){
                $rateZhuLast = $vRl['now_money'];
            }
        }
//        dump($conRate);
        // 查询资产负债表的数据
        $conRich = D('Rich','Service')->getAllRich($cnum,$date);
        foreach($conRich as $kRi=>$vRi){
            //获取总资产的值
            if($vRi['assets_id']==='35'){
                $richZong = $vRi['end_money'];
            }
            //获取所有者权益的值
            if($vRi['assets_id']==='65'){
                $richSuo = $vRi['end_money'];
            }
        }
        // 查询资产负债表上个月的数据
        $conRichLast = D('Rich','Service')->getLastRich($cnum,$date);
        foreach($conRichLast as $kRil=>$vRil){
            //获取总资产的值
            if($vRil['assets_id']==='35'){
                $richZongLast = $vRil['end_money'];
            }
            //获取所有者权益的值
            if($vRil['assets_id']==='65'){
                $richSuoLast = $vRil['end_money'];
            }
        }
//        dump($conRich);
        //市场杠杆
        $shichang = (round($rateJing/$rateZhu,4))*100;
        //管理杠杆
        $guanli = (round($rateZhu/$richZong,4))*100;
        //财务杠杆
        $caiwu = (round($richZong/$richSuo,4))*100;
        
        //上个月的数据
        //市场杠杆
        $shichangLast = (round($rateJingLast/$rateZhuLast,4))*100;
        //管理杠杆
        $guanliLast = (round($rateZhuLast/$richZongLast,4))*100;
        //财务杠杆
        $caiwuLast = (round($richZongLast/$richSuoLast,4))*100;
        
        //根据公司编号获取管理损益表上个月的内容
        $conLast = D('Mrate','Service')->getMrateLastList($cnum,$date);
        foreach($conLast as $kl=>$vl){
            
            if($vl['mi_id']==='23'){
                $lastSun04B26 = $vl['now_money'];
                $lastSun04C26 = $vl['sum_money'];
            }
        }
        //根据公司编号获取管理资产表上个月的内容
        $conRichLast = D('Mrich','Service')->getMrichLastList($cnum,$date);
        foreach($conRichLast as $krl=>$vrl){
            if($vrl['ma_id']==='41'){
                $lastZi05B51 = $vrl['start_money'];
                $lastZi05C51 = $vrl['end_money'];
            }
        }
//        dump($conLast);
        $oldGudong = ((round($lastSun04C26/$lastZi05C51,4))*100);
        
        //根据公司编号获取当月目标的内容
        $conDubang = D('Dubang','Service')->getDubangList($cnum,$date);
//        dump($conDubang);
        //组合17根戒尺的数据
        foreach ($result as $ks => $vs) {
            $seven_id = $vs['id'];
            switch ($seven_id) {
                case '1':
                    $result['0']['yue']  =  ((round($sun04B17/$sun04B6,4))*100).'%';
                    $result['0']['lei']  =  ((round($sun04C17/$sun04C6,4))*100);
                break;
                case '2':
                    $result['1']['yue']  =  ((round($sun04B26/$zi05C51,4))*100).'%';
                    $result['1']['lei']  =  ((round($sun04C26/$zi05C51,4))*100);
                break;
                case '3':
                    $result['2']['yue']  =  ((round($sun04B9/$sun04B6,4))*100).'%';
                    $result['2']['lei']  =  ((round($sun04C9/$sun04C6,4))*100);
                break;
                case '4':
                    $result['3']['yue']  =  ((round($sun04B13/$sun04B6,4))*100).'%';
                    $result['3']['lei']  =  ((round($sun04C13/$sun04C6,4))*100);
                break;
                case '5':
                    //标损 B5/管损 B6
                    $result['4']['yue']  =  ((round($rateB5/$sun04B6,4))*100).'%';
                    $result['4']['lei']  =  ((round($rateF5/$sun04C6,4))*100);
                break;
                case '6':
                    $result['5']['yue']  =  ((round($sun04B26/$zi05C27,4))*100).'%';
                    $result['5']['lei']  =  ((round($sun04C26/$zi05C27,4))*100);
                break;
                case '7':
                    $result['6']['yue']  =  ((round($sun04B6/$zi05C18,4))*100).'%';
                    $result['6']['lei']  =  ((round($sun04C6/$zi05C18,4))*100);
                break;
                case '8':
                    $result['7']['yue']  =  ((round($zi05C6/$sun04B6,4))*100).'%';
                    $result['7']['lei']  =  ((round((($zi05C6+$zi05B6)/2)/$sun04C6,4))*100);
                break;
                case '9':
                    $result['8']['yue']  =  ((round($zi05C20/$zi05C9,4))*100).'%';
                    $result['8']['lei']  =  ((round((($zi05C20+$zi05B20)/2)/(($zi05C9+$zi05B9)/2),4))*100);
                break;
                case '10':
                    $result['9']['yue']  =  ((round($xianC4/$sun04B6,4))*100).'%';
                    $result['9']['lei']  =  ((round($xianC4/$sun04C6,4))*100);
                break;
                case '11':
                    $result['10']['yue']  =  ((round($sun04B6/$zi05C27,4))*100).'%';
                    $result['10']['lei']  =  ((round($sun04C6/$zi05C27,4))*100);
                break;
                case '12':
                    //  管资C9/标损B5
                    $result['11']['yue']  =  ((round($zi05C9/$rateB5,4))*100).'%';
                    //标损F5/当前月份
                    $month = $_GET['date'] ? substr($_GET['date'], -2) : date('m',time());
                    $month = (int)$month;
                    $result['11']['lei']  =  ((round((($zi05C9+$zi05B9)/2)/($rateF5/$month),4))*100);
                break;
                case '13':
                    $result['12']['yue']  =  ((round($sun04B6/$zi05C13,4))*100).'%';
                    $result['12']['lei']  =  ((round($sun04C6/$zi05C13,4))*100);
                break;
                case '14':
                    $result['13']['yue']  =  ((round($xianC13/$zi05C18,4))*100).'%';
                    $result['13']['lei']  =  ((round($xianC13/$zi05C18,4))*100);
                break;
                case '15':
                    $result['14']['yue']  =  ((round($zi05C18/$zi05C51,4))*100).'%';
                    $result['14']['lei']  =  ((round($zi05C18/$zi05C51,4))*100);
                break;
                case '16':
                    $result['15']['yue']  =  ((round($zi05C10/($zi05C26+$zi05C32),4))*100).'%';
                    $result['15']['lei']  =  ((round($zi05C10/($zi05C26+$zi05C32),4))*100);
                break;
                case '17':
                    $result['16']['yue']  =  ((round($xianC13/($zi05C26+$zi05C44),4))*100).'%';
                    $result['16']['lei']  =  ((round($xianC13/($zi05C26+$zi05C44),4))*100);
                break;
            }
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('du', $result);
        $this->assign('shichang', $shichang);
        $this->assign('guanli', $guanli);
        $this->assign('caiwu', $caiwu);
        $this->assign('oldGudong', $oldGudong);
        $this->assign('shichangLast', $shichangLast);
        $this->assign('guanliLast', $guanliLast);
        $this->assign('caiwuLast', $caiwuLast);
        $this->assign('dubang', $conDubang);
        $this->display();
    }
    
    /**
     * 添加目标
     * @return
     */
    public function create() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $target = $_POST;
//        dump($target);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Dubang','Service')->addDubang($cnum,$target);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 修改目标
     * @return
     */
    public function update() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $target = $_POST;
        $result = D('Dubang','Service')->editDubang($target);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    
    //查询是否存在杜邦分析法数据
    public function isExistDubang(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Dubang','Service')->existDubang($cnum,$date);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 修改杜邦分析表的数据
     * @return
     */
    public function editDubang() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $dubang = $_POST;
//        dump($mrich);
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Dubang','Service')->updateDubang($cnum,$dubang);
//        dump($result);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
}
