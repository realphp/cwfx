<?php
namespace Home\Controller;

/**
 * SeventeenController
 * 17根戒尺
 */
class SeventeenController extends CommonController {
    /**
     * 17根戒尺页面
     * @return
     */
    public function index() {
        layout(false);
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
        }
        $yue1  =  ((round($sun04B17/$sun04B6,4))*100);
        $lei1  =  ((round($sun04C17/$sun04C6,4))*100);
//        dump($lei1);
        $this->assign('seven', $result);
        $this->assign('yue1', $yue1);
        $this->assign('lei1', $lei1);
        $this->display();
    }
    
    public function url1() {
        layout(false);
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
        }
        $yue1  =  ((round($sun04B17/$sun04B6,4))*100);
        $lei1  =  ((round($sun04C17/$sun04C6,4))*100);
//        dump($lei1);
        $this->assign('seven', $result);
        $this->assign('yue1', $yue1);
        $this->assign('lei1', $lei1);
        $this->display();
    }
    
    public function url2() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='23'){
                $sun04B26 = $v['now_money'];
                $sun04C26 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='41'){
                $zi05C51 = $vM['end_money'];
            }
        }
        $yue2  =  ((round($sun04B26/$zi05C51,4))*100);
        $lei2  =  ((round($sun04C26/$zi05C51,4))*100);
        
        
        $this->assign('seven', $result);
        $this->assign('yue2', $yue2);
        $this->assign('lei2', $lei2);
        $this->display();
    }
    
    public function url3() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
            if($v['mi_id']==='7'){
                $sun04B9 = $v['now_money'];
                $sun04C9 = $v['sum_money'];
            }
        }
        
        $yue3  =  ((round($sun04B9/$sun04B6,4))*100);
        $lei3  =  ((round($sun04C9/$sun04C6,4))*100);
        
        
        $this->assign('seven', $result);
        $this->assign('yue3', $yue3);
        $this->assign('lei3', $lei3);
        $this->display();
    }
    
    public function url4() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
            if($v['mi_id']==='11'){
                $sun04B13 = $v['now_money'];
                $sun04C13 = $v['sum_money'];
            }
        }
        
        $yue4  =  ((round($sun04B13/$sun04B6,4))*100);
        $lei4  =  ((round($sun04C13/$sun04C6,4))*100);
        
        
        $this->assign('seven', $result);
        $this->assign('yue4', $yue4);
        $this->assign('lei4', $lei4);
        $this->display();
    }
    
    public function url5() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
        }
        // 查询标准损益表的数据
        $conRate = D('Rate','Service')->getAllRate($cnum,$date);
        foreach($conRate as $kR=>$vR){
            if($vR['interest_id']==='5'){
                $rateB5 = $vR['now_money'];
                $rateF5 = $vR['sum_money'];
            }
        }
        $yue5  =  ((round($rateB5/$sun04B6,4))*100);
        $lei5  =  ((round($rateF5/$sun04C6,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue5', $yue5);
        $this->assign('lei5', $lei5);
        $this->display();
    }
    
    public function url6() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='23'){
                $sun04B26 = $v['now_money'];
                $sun04C26 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='25'){
                $zi05C27 = $vM['end_money'];
            }
        }
        $yue6  =  ((round($sun04B26/$zi05C27,4))*100);
        $lei6  =  ((round($sun04C26/$zi05C27,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue6', $yue6);
        $this->assign('lei6', $lei6);
        $this->display();
    }
    
    public function url7() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='16'){
                $zi05C18 = $vM['end_money'];
            }
        }
        $yue7  =  ((round($sun04B6/$zi05C18,4))*100);
        $lei7  =  ((round($sun04C6/$zi05C18,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue7', $yue7);
        $this->assign('lei7', $lei7);
        $this->display();
    }
    
    public function url8() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='4'){
                $zi05B6 = $vM['start_money'];
                $zi05C6 = $vM['end_money'];
            }
        }
        $yue8  =  ((round($zi05C6/$sun04B6,4))*100);
        $lei8  =  ((round((($zi05C6+$zi05B6)/2)/$sun04C6,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue8', $yue8);
        $this->assign('lei8', $lei8);
        $this->display();
    }
    
    public function url9() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='7'){
                $zi05B9 = $vM['start_money'];
                $zi05C9 = $vM['end_money'];
            }
            if($vM['ma_id']==='18'){
                $zi05B20 = $vM['start_money'];
                $zi05C20 = $vM['end_money'];
            }
        }
        $yue9  =  ((round($zi05C20/$zi05C9,4))*100);
        $lei9  =  ((round((($zi05C20+$zi05B20)/2)/(($zi05C9+$zi05B9)/2),4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue9', $yue9);
        $this->assign('lei9', $lei9);
        $this->display();
    }
    
    public function url10() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理现金表的内容
        $conMflow = D('Mflow','Service')->getMflowList($cnum,$date);
        foreach($conMflow as $kMf=>$vMf){
            if($vMf['mc_id']==='2'){
                $xianC4 = $vMf['money'];
            }
        }
        $yue10  =  ((round($xianC4/$sun04B6,4))*100);
        $lei10  =  ((round($xianC4/$sun04C6,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue10', $yue10);
        $this->assign('lei10', $lei10);
        $this->display();
    }
    
    public function url11() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='25'){
                $zi05C27 = $vM['end_money'];
            }
        }
        $yue11  =  ((round($sun04B6/$zi05C27,4))*100);
        $lei11  =  ((round($sun04C6/$zi05C27,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue11', $yue11);
        $this->assign('lei11', $lei11);
        $this->display();
    }
    
    public function url12() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        // 查询标准损益表的数据
        $conRate = D('Rate','Service')->getAllRate($cnum,$date);
        foreach($conRate as $kR=>$vR){
            if($vR['interest_id']==='5'){
                $rateB5 = $vR['now_money'];
                $rateF5 = $vR['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='7'){
                $zi05B9 = $vM['start_money'];
                $zi05C9 = $vM['end_money'];
            }
        }
        $yue12  =  ((round($zi05C9/$rateB5,4))*100);
        $month = date('m',time());
        if($month<10){
            $month = str_replace ("0", "", $month);
        }
        $lei12  =  ((round((($zi05C9+$zi05B9)/2)/($rateF5/$month),4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue12', $yue12);
        $this->assign('lei12', $lei12);
        $this->display();
    }
    
    public function url13() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理损益表的内容
        $con = D('Mrate','Service')->getMrateList($cnum,$date);
        foreach($con as $k=>$v){
            if($v['mi_id']==='4'){
                $sun04B6 = $v['now_money'];
                $sun04C6 = $v['sum_money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='11'){
                $zi05C13 = $vM['end_money'];
            }
        }
        $yue13  =  ((round($sun04B6/$zi05C13,4))*100);
        $lei13  =  ((round($sun04C6/$zi05C13,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue13', $yue13);
        $this->assign('lei13', $lei13);
        $this->display();
    }
    
    public function url14() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理现金表的内容
        $conMflow = D('Mflow','Service')->getMflowList($cnum,$date);
        foreach($conMflow as $kMf=>$vMf){
            if($vMf['mc_id']==='11'){
                $xianC13 = $vMf['money'];
            }
        }
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='16'){
                $zi05C18 = $vM['end_money'];
            }
        }
        $yue14  =  ((round($xianC13/$zi05C18,4))*100);
        $lei14  =  ((round($xianC13/$zi05C18,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue14', $yue14);
        $this->assign('lei14', $lei14);
        $this->display();
    }
    
    public function url15() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='16'){
                $zi05C18 = $vM['end_money'];
            }
            if($vM['ma_id']==='41'){
                $zi05C51 = $vM['end_money'];
            }
        }
        $yue15  =  ((round($zi05C18/$zi05C51,4))*100);
        $lei15  =  ((round($zi05C18/$zi05C51,4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue15', $yue15);
        $this->assign('lei15', $lei15);
        $this->display();
    }
    
    public function url16() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='8'){
                $zi05C10 = $vM['end_money'];
            }
            if($vM['ma_id']==='24'){
                $zi05C26 = $vM['end_money'];
            }
            if($vM['ma_id']==='29'){
                $zi05C32 = $vM['end_money'];
            }
        }
        $yue16  =  ((round($zi05C10/($zi05C26+$zi05C32),4))*100);
        $lei16  =  ((round($zi05C10/($zi05C26+$zi05C32),4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue16', $yue16);
        $this->assign('lei16', $lei16);
        $this->display();
    }
    
    public function url17() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //获取17根戒尺的栏目
        $result = D('Seventeen','Service')->getSeventeenList();
        //根据公司编号获取管理资产表的内容
        $conMrich = D('Mrich','Service')->getMrichList($cnum,$date);
        foreach($conMrich as $kM=>$vM){
            if($vM['ma_id']==='24'){
                $zi05C26 = $vM['end_money'];
            }
            if($vM['ma_id']==='34'){
                $zi05C44 = $vM['end_money'];
            }
        }
        //根据公司编号获取管理现金表的内容
        $conMflow = D('Mflow','Service')->getMflowList($cnum,$date);
        foreach($conMflow as $kMf=>$vMf){
            if($vMf['mc_id']==='11'){
                $xianC13 = $vMf['money'];
            }
        }
        $yue17  =  ((round($xianC13/($zi05C26+$zi05C44),4))*100);
        $lei17  =  ((round($xianC13/($zi05C26+$zi05C44),4))*100);
        
        $this->assign('seven', $result);
        $this->assign('yue17', $yue17);
        $this->assign('lei17', $lei17);
        $this->display();
    }
    
}
