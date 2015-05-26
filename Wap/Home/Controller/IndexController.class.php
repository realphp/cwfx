<?php
namespace Home\Controller;

/**
 * IndexController
 * 系统信息管理
 */
class IndexController extends CommonController {
    /**
     * 手机站首页
     * @return
     */
    public function index(){
        layout(false);
        $this->display();
    }
    
    public function index1() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //五大决策变动比率值
        $map['cnum'] = $cnum;
        $date = date('Y-m', time());
        $map['date'] = array('like', $date . '%');
        $chs = M('fivedata')->where($map)->field('f_id,change')->select();
        $chs = arrayValueToKey($chs, 'f_id');
        $this->assign('chs', $chs);
        //预计利润
//        //获取管理损益表的 本期净利润（累计）==================================================================
        $benqijinglirun = D('Mrate','Service')->getMiidData($date,$cnum,'23');
        //查询管理损益表的 主营业务收入
        $zhuyingyewushouru = D('Mrate','Service')->getMiidData($date,$cnum,'1');
        //查询管理损益表的 减：营业税金及附加
        $yingyeshuijin = D('Mrate','Service')->getMiidData($date,$cnum,'3');
        //查询管理损益表的 主营业务净收入
        $zhuyingyewujingshouru = D('Mrate','Service')->getMiidData($date,$cnum,'4');
        //查询管理损益表的 减：直接成本
        $zhijiechengben = D('Mrate','Service')->getMiidData($date,$cnum,'5');
        //查询管理损益表的 其中：以销售收入为导向的销售费用
        $xiaoshoufeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'8');
        //查询管理损益表的 以销售量为导向的销售费用
        $xiaoshouliang = D('Mrate','Service')->getMiidData($date,$cnum,'9');
         //查询管理损益表的 变动营业费用
        $biandongfeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'7');
         //查询管理损益表的 减：管理费用
        $guanlifeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'13');
        //销量变动值
        $sell =$chs[1]['change'];
        //售价变动值
        $price = $chs[2]['change'];
        //变动成本变动值
        $chengben = $chs[3]['change'];
         //变动费用变动值
        $change = $chs[4]['change'];
        //固定费用变动值
        $constant = $chs[5]['change'];
        
        $sellData = $sell/100;
        $E5 = $zhuyingyewushouru * $sellData;
        $E6 = $yingyeshuijin * $sellData;
        $E7 = $E5 - $E6;
        $E8 = $zhijiechengben * $sellData;
        $E9 = $E7 - $E8;
        $E11 = $xiaoshouliang * $sellData;
        $E24 = $E9 - $E11;
        $C4 = $E24 / $benqijinglirun;
        
        $priceData = $price/100;
        $F5 = $zhuyingyewushouru * $priceData;
        $F6 = $yingyeshuijin * $priceData;
        $F7 = $F5 - $F6;
        $F9 = $F7;
        $F12 = $xiaoshouliang * $priceData;
        $F24 = $F9 - $F12;
        $C5 = $F24 / $benqijinglirun;
        
        $chengbenData = $chengben / 100;
        $F8 = $zhijiechengben * $chengbenData;
        $F24 = -$F8;
        $C6 = $F24 / $benqijinglirun;
        
        $changeData = $change / 100;
        $F10 = $biandongfeiyong * $changeData;
        $F24 = -$F10;
        $C7 = $F24 / $benqijinglirun;
        
        $constantData = $constant / 100;
        $F24 = $guanlifeiyong * $constantData;
        $C8 = $F24 / $benqijinglirun;
        
        //变化后净利润
        $after1 = $benqijinglirun*(1+round($C4,4));
        $after2 = $benqijinglirun*(1+round($C5,4));
        $after3 = $benqijinglirun*(1+round($C6,4));
        $after4 = $benqijinglirun*(1+round($C7,4));
        $after5 = $benqijinglirun*(1+round($C8,4));
        
            //是否是ajax传输  
       if (IS_AJAX) {
            $arr['before'] = $benqijinglirun;
            $arr['after1'] = $after1;
            $arr['after2'] = $after2;
            $arr['after3'] = $after3;
            $arr['after4'] = $after4;
            $arr['after5'] = $after5;
            echo json_encode($arr);
            exit;
        }
        $this->after1 = $after1;
        $this->after2 = $after2;
        $this->after3 = $after3;
        $this->after4 = $after4;
        $this->after5 = $after5;
        $this->benqijinglirun =$benqijinglirun;
        $this->display();
    }

    /**
     * 杜邦分析法页面
     */
    public function dubang() {
        layout(false);
        if ($_GET['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];
        //杜邦分析法
        $result = D('Dubang', 'Service')->getDubangList($cnum, $date);
//        dump($result);
        $this->assign('result', $result);
        $this->display();
    }

    /**
     * 股东回报率页面
     */
    public function gudong() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //股东回报率
        $result = D('Dubang','Service')->getGudongList($date,$cnum);
//        dump($result);
        $this->assign('result', $result);
        $this->display();
    }
    //直接盈利能力
    public function yinli() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //股东回报率
        $result = D('Dubang','Service')->getYinliList($date,$cnum);
//        dump($result);
        $this->assign('result', $result);
         $this->display();
    }
    //资本运用效率
    public function ziben() {
        layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        //股东回报率
        $result = D('Dubang','Service')->getZibenList($date,$cnum);
//        dump($result);
        $this->assign('result', $result);
         $this->display();
    }
    
    //财务杠杆
    public function caiwu() {
        layout(false);
        $cnum = $_SESSION['current_account']['cnum'];
        //股东回报率
        $date = $_GET['date'];
        $result = D('Dubang','Service')->getCaiwuList($date,$cnum);
//        dump($result);
        $this->assign('result', $result);
         $this->display();
    }
    
    public function ajaxreturn() {
        if ($_POST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];
        if ($_POST['where'] == 1) {
            $result = D('Dubang', 'Service')->getGudongList($date, $cnum);
        } elseif ($_POST['where'] == 2) {
            $result = D('Dubang', 'Service')->getYinliList($date, $cnum);
        } elseif ($_POST['where'] == 3) {
            $result = D('Dubang', 'Service')->getZibenList($date, $cnum);
        } elseif ($_POST['where'] == 4) {
            $result = D('Dubang', 'Service')->getCaiwuList($date, $cnum);
        }
        echo json_encode($result);
        exit;
    }

    //销量
    public function sell() {
        layout(false);
      if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];

        //获取管理损益表的 本期净利润（累计）==================================================================
        $benqijinglirun = D('Mrate','Service')->getMiidData($date,$cnum,'23');
        //查询管理损益表的 主营业务收入
        $zhuyingyewushouru = D('Mrate','Service')->getMiidData($date,$cnum,'1');
        //查询管理损益表的 减：营业税金及附加
        $yingyeshuijin = D('Mrate','Service')->getMiidData($date,$cnum,'3');
        //查询管理损益表的 主营业务净收入
        $zhuyingyewujingshouru = D('Mrate','Service')->getMiidData($date,$cnum,'4');
        //查询管理损益表的 减：直接成本
        $zhijiechengben = D('Mrate','Service')->getMiidData($date,$cnum,'5');
        //查询管理损益表的 其中：以销售收入为导向的销售费用
        $xiaoshoufeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'8');
        //查询管理损益表的 以销售量为导向的销售费用
        $xiaoshouliang = D('Mrate','Service')->getMiidData($date,$cnum,'9');

        //获取管理现金表的 现金及现金等价物净增加额==================================================================
        $cashUp = D('Mflow','Service')->getMcidData($date,$cnum,'35');
        //获取管理现金表的 经营活动产生的现金流量净额
        $jingyingcash = D('Mflow','Service')->getMcidData($date,$cnum,'11');
        
        //获取管理资产表的 应收账款==================================================================
        $yingshouzhangkuan = D('Mrich','Service')->getMaidData($date,$cnum,'4');
        //获取管理资产表的 存货
        $cunhuo = D('Mrich','Service')->getMaidData($date,$cnum,'7');
        //获取管理资产表的 应付账款
        $yingfuzhangkuan = D('Mrich','Service')->getMaidData($date,$cnum,'18');
        
        //查询参数录入的 现金费用比==================================================================
        $xianjinfeiyongbi = D('Entry','Service')->getPinEntry($cnum,$date);
//        dump($xianjinfeiyongbi);
        //根据公司编号获取五大决策 销量的值=============================================================
        $sell = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'1');
//        dump($sell);
        if($sell['change']!=0){
            //计算预计利润
            $sellData = $sell['change']/100;
            $E5 = $zhuyingyewushouru * $sellData;
            $E6 = $yingyeshuijin * $sellData;
            $E7 = $E5 - $E6;
            $E8 = $zhijiechengben * $sellData;
            $E9 = $E7 - $E8;
            $E11 = $xiaoshouliang * $sellData;
            $E24 = $E9 - $E11;
            $C4 = $E24 / $benqijinglirun;
            $D4 = $C4 / $sellData;
            $E4 = $D4 * $sellData;
            $F4 = $E4 * $benqijinglirun;
            $yujilirun = $benqijinglirun + $F4;
            $yujilirunnew = sprintf("%.2f", $yujilirun); 
            //计算预计经营现金流
            $M10  = $xianjinfeiyongbi/100;
            $M11 = -$xiaoshoufeiyong * $M10 * $sellData;
            $M12 =  -$xiaoshouliang * $M10 * $sellData;
            $M8 = -($E8 * $cunhuo / $zhijiechengben);
            $M7 = -($E7 * $yingshouzhangkuan / $zhuyingyewujingshouru);
            $M13 = -$M8 * $yingfuzhangkuan / $cunhuo;
            $M9 = $M7 + $M8;
            $M24 = $M9 + $M11 + $M12 + $M13;
            $I4 = $M24 / $jingyingcash;
            $J4 = $I4 / $sellData;
            $K4 = $J4 * $sellData;
            $L4 = $K4 * $cashUp;
            $yujicash = $cashUp + $L4;
            $yujicashnew = sprintf("%.2f", $yujicash); 
        }else{
            $yujilirun = $benqijinglirun;
            $yujicash = $cashUp;
        }
       //是否是ajax传输  
       if (IS_AJAX) {
            $arr['change'] = $sell['change'];
            $arr['houyuji'] = $yujilirunnew;
            $arr['qianyuji'] = $benqijinglirun;
            $arr['houcash'] = $yujicashnew;
            $arr['qiancash'] = $cashUp;
            echo json_encode($arr);
            exit;
        }
        $this->assign('change', $sell['change']);
        $this->assign('houyuji', $yujilirunnew); 
        $this->assign('qianyuji', $benqijinglirun); 
        $this->assign('houcash', $yujicashnew); 
        $this->assign('qiancash', $cashUp); 
        $this->display();
    }
    
    //修改销量
    public function editSell() {
            if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $change = $_POST['change'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Fivedata','Service')->updateChange($cnum,$change,$date,'1');
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    


    //售价
    public function price() {
        layout(false);
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];

        //获取管理损益表的 本期净利润（累计）==================================================================
        $benqijinglirun = D('Mrate','Service')->getMiidData($date,$cnum,'23');
        //查询管理损益表的 主营业务收入
        $zhuyingyewushouru = D('Mrate','Service')->getMiidData($date,$cnum,'1');
        //查询管理损益表的 减：营业税金及附加
        $yingyeshuijin = D('Mrate','Service')->getMiidData($date,$cnum,'3');
        //查询管理损益表的 主营业务净收入
        $zhuyingyewujingshouru = D('Mrate','Service')->getMiidData($date,$cnum,'4');
        //查询管理损益表的 以销售量为导向的销售费用
        $xiaoshouliang = D('Mrate','Service')->getMiidData($date,$cnum,'9');

        //获取管理现金表的 现金及现金等价物净增加额==================================================================
        $cashUp = D('Mflow','Service')->getMcidData($date,$cnum,'35');
        //获取管理现金表的 经营活动产生的现金流量净额
        $jingyingcash = D('Mflow','Service')->getMcidData($date,$cnum,'11');
        
        //获取管理资产表的 应收账款==================================================================
        $yingshouzhangkuan = D('Mrich','Service')->getMaidData($date,$cnum,'4');
        
        //查询参数录入的 现金费用比==================================================================
        $xianjinfeiyongbi = D('Entry','Service')->getPinEntry($cnum,$date);
//        dump($xianjinfeiyongbi);
        //根据公司编号获取五大决策 售价的值=============================================================
        $price = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'2');
//        dump($sell);
        if($price['change']!=0){
            //计算预计利润
            $priceData = $price['change']/100;
            $F5 = $zhuyingyewushouru * $priceData;
            $F6 = $yingyeshuijin * $priceData;
            $F7 = $F5 - $F6;
            $F9 = $F7;
            $F12 = $xiaoshouliang * $priceData;
            $F24 = $F9 - $F12;
            $C5 = $F24 / $benqijinglirun;
            $D5 = $C5 / $priceData;
            $E4 = $D5 * $priceData;
            $F4 = $E4 * $benqijinglirun;
            $yujilirun = $benqijinglirun + $F4;
            $yujilirunnew = sprintf("%.2f", $yujilirun); 
            //计算预计经营现金流
            $N7 = -$F7 * $yingshouzhangkuan / $zhuyingyewujingshouru;
            $N9 = $N7;
            $N12 = $xiaoshouliang * $priceData * ($xianjinfeiyongbi/100);
            $N24 = $N9 + $N12;
            $I5 = $N24 / $jingyingcash;
            $J5 = $I5 / $priceData;
            $K4 = $J5 * $priceData;
            $L4 = $K4 * $cashUp;
            $yujicash = $cashUp + $L4;
            $yujicashnew = sprintf("%.2f", $yujicash); 
        }else{
            $yujilirun = $benqijinglirun;
            $yujicash = $cashUp;
        }

        if (IS_AJAX) {
            $arr['change'] = $price['change'];
            $arr['houyuji'] = $yujilirunnew;
            $arr['qianyuji'] = $benqijinglirun;
            $arr['houcash'] = $yujicashnew;
            $arr['qiancash'] = $cashUp;
            echo json_encode($arr);
            exit;
        }

        $this->assign('change', $price['change']);
        $this->assign('houyuji', $yujilirunnew); 
        $this->assign('qianyuji', $benqijinglirun); 
        $this->assign('houcash', $yujicashnew); 
        $this->assign('qiancash', $cashUp); 
        $this->display();
    }
    
    //修改售价
    public function editPrice() {
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $change = $_POST['change'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Fivedata','Service')->updateChange($cnum,$change,$date,'2');
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    //
    //变动成本
    public function chengben() {
        layout(false);
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];

        //获取管理损益表的 本期净利润（累计）==================================================================
        $benqijinglirun = D('Mrate','Service')->getMiidData($date,$cnum,'23');
        //查询管理损益表的 减：直接成本
        $zhijiechengben = D('Mrate','Service')->getMiidData($date,$cnum,'5');

        //获取管理现金表的 现金及现金等价物净增加额==================================================================
        $cashUp = D('Mflow','Service')->getMcidData($date,$cnum,'35');
        //获取管理现金表的 经营活动产生的现金流量净额
        $jingyingcash = D('Mflow','Service')->getMcidData($date,$cnum,'11');
        
        //获取管理资产表的 应付账款
        $yingfuzhangkuan = D('Mrich','Service')->getMaidData($date,$cnum,'18');
        
//        dump($xianjinfeiyongbi);
        //根据公司编号获取五大决策 售价的值=============================================================
        $chengben = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'3');
//        dump($sell);
        if($chengben['change']!=0){
            //计算预计利润
            $chengbenData = $chengben['change']/100;
            $F8 = $zhijiechengben * $chengbenData;
            $F24 = -$F8;
            $C6 = $F24 / $benqijinglirun;
            $D6 = $C6 / $chengbenData;
            $E4 = - abs($D6) * $chengbenData;
            $F4 = $E4 * $benqijinglirun;
            $yujilirun = $benqijinglirun + $F4;
            $yujilirunnew = sprintf("%.2f", $yujilirun); 
            //计算预计经营现金流
            $N8 = $F24 * $yingfuzhangkuan / $zhijiechengben;
            $H6 = $N8;
            $I6 = $H6 / $jingyingcash;
            $J6 = $I6 / $chengbenData;
            $K4 = - abs($J6) * $chengbenData;
            $L4 = $K4 * $cashUp;
            $yujicash = $cashUp + $L4;
            $yujicashnew = sprintf("%.2f", $yujicash); 
        }else{
            $yujilirun = $benqijinglirun;
            $yujicash = $cashUp;
        }

        if (IS_AJAX) {
            $arr['change'] = $chengben['change'];
            $arr['houyuji'] = $yujilirunnew;
            $arr['qianyuji'] = $benqijinglirun;
            $arr['houcash'] = $yujicashnew;
            $arr['qiancash'] = $cashUp;
            echo json_encode($arr);
            exit;
        }
//        dump($I4);
//        dump($jingyingcash);
        $this->assign('change', $chengben['change']);
        $this->assign('houyuji', $yujilirunnew); 
        $this->assign('qianyuji', $benqijinglirun); 
        $this->assign('houcash', $yujicashnew); 
        $this->assign('qiancash', $cashUp); 
        $this->display();
    }
    
    //修改变动成本
    public function editChengben() {
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $change = $_POST['change'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Fivedata','Service')->updateChange($cnum,$change,$date,'3');
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    //变动费用
    public function change() {
        layout(false);
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];

        //获取管理损益表的 本期净利润（累计）==================================================================
        $benqijinglirun = D('Mrate','Service')->getMiidData($date,$cnum,'23');
        //查询管理损益表的 变动营业费用
        $biandongfeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'7');

        //获取管理现金表的 现金及现金等价物净增加额==================================================================
        $cashUp = D('Mflow','Service')->getMcidData($date,$cnum,'35');
        //获取管理现金表的 经营活动产生的现金流量净额
        $jingyingcash = D('Mflow','Service')->getMcidData($date,$cnum,'11');
        
        //查询参数录入的 现金费用比==================================================================
        $xianjinfeiyongbi = D('Entry','Service')->getPinEntry($cnum,$date);
//        dump($xianjinfeiyongbi);
        //根据公司编号获取五大决策 售价的值=============================================================
        $change = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'4');
//        dump($sell);
        if($change['change']!=0){
            //计算预计利润
            $changeData = $change['change']/100;
            $F10 = $biandongfeiyong * $changeData;
            $F24 = -$F10;
            $C7 = $F24 / $benqijinglirun;
            $D7 = $C7 / $changeData;
            $E4 = - abs($D7) * $changeData;
            $F4 = $E4 * $benqijinglirun;
            $yujilirun = $benqijinglirun + $F4;
            $yujilirunnew = sprintf("%.2f", $yujilirun); 
            //计算预计经营现金流
            $N10 = $biandongfeiyong * $changeData * ($xianjinfeiyongbi/100);
            $H7 = - $N10;
            $I7 = $H7 / $jingyingcash;
            $J7 = $I7 / $changeData;
            $K4 = - abs($J7) * $changeData;
            $L4 = $K4 * $cashUp;
            $yujicash = $cashUp + $L4;
            $yujicashnew = sprintf("%.2f", $yujicash); 
        }else{
            $yujilirun = $benqijinglirun;
            $yujicash = $cashUp;
        }

        if (IS_AJAX) {
            $arr['change'] = $change['change'];
            $arr['houyuji'] = $yujilirunnew;
            $arr['qianyuji'] = $benqijinglirun;
            $arr['houcash'] = $yujicashnew;
            $arr['qiancash'] = $cashUp;
            echo json_encode($arr);
            exit;
        }

        $this->assign('change', $change['change']);
        $this->assign('houyuji', $yujilirunnew); 
        $this->assign('qianyuji', $benqijinglirun); 
        $this->assign('houcash', $yujicashnew); 
        $this->assign('qiancash', $cashUp); 
        $this->display();
    }
    
    //修改变动成本
    public function editChange() {
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $change = $_POST['change'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Fivedata', 'Service')->updateChange($cnum, $change, $date, '4');
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    //固定费用
    public function constant() {
        layout(false);
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];

        //获取管理损益表的 本期净利润（累计）==================================================================
        $benqijinglirun = D('Mrate','Service')->getMiidData($date,$cnum,'23');
        //查询管理损益表的 减：管理费用
        $guanlifeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'13');

        //获取管理现金表的 现金及现金等价物净增加额==================================================================
        $cashUp = D('Mflow','Service')->getMcidData($date,$cnum,'35');
        //获取管理现金表的 经营活动产生的现金流量净额
        $jingyingcash = D('Mflow','Service')->getMcidData($date,$cnum,'11');
        
        //查询参数录入的 现金费用比==================================================================
        $xianjinfeiyongbi = D('Entry','Service')->getPinEntry($cnum,$date);
//        dump($xianjinfeiyongbi);
        //根据公司编号获取五大决策 销量的值=============================================================
        $constant = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'5');
//        dump($sell);
        if($constant['change']!=0){
            //计算预计利润
            $constantData = $constant['change']/100;
            $F24 = $guanlifeiyong * $constantData;
            $C8 = $F24 / $benqijinglirun;
            $D8 = $C8 / $constantData;
            $E4 = - abs($D8) * $constantData;
            $F4 = $E4 * $benqijinglirun;
            $yujilirun = $benqijinglirun + $F4;
            $yujilirunnew = sprintf("%.2f", $yujilirun); 
            //计算预计经营现金流
            $M10  = $xianjinfeiyongbi/100;
            $N16 = $guanlifeiyong * $M10 * $constantData;
            $H8 = -$N16;
            $I8 = $H8 / $jingyingcash;
            $J8 = $I8 / $constantData;
            $K4 = - abs($J8) * $constantData;
            $L4 = $K4 * $cashUp;
            $yujicash = $cashUp + $L4;
            $yujicashnew = sprintf("%.2f", $yujicash); 
        }else{
            $yujilirun = $benqijinglirun;
            $yujicash = $cashUp;
        }
        if (IS_AJAX) {
            $arr['change'] = $constant['change'];
            $arr['houyuji'] = $yujilirunnew;
            $arr['qianyuji'] = $benqijinglirun;
            $arr['houcash'] = $yujicashnew;
            $arr['qiancash'] = $cashUp;
            echo json_encode($arr);
            exit;
        }
        $this->assign('change', $constant['change']);
        $this->assign('houyuji', $yujilirunnew); 
        $this->assign('qianyuji', $benqijinglirun); 
        $this->assign('houcash', $yujicashnew); 
        $this->assign('qiancash', $cashUp); 
        $this->display();
        die;
    }
    
    //修改固定费用
    public function editConstant() {
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $change = $_POST['change'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Fivedata', 'Service')->updateChange($cnum, $change, $date, '5');
        if (!$result) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //五大决策
    public function five() {
        layout(false);
        if ($_REQUEST['type'] == 1) {
            $time = strtotime("last Month");
            $date = date('Y-m', $time);
        } else {
            $date = date('Y-m', time());
        }
        $cnum = $_SESSION['current_account']['cnum'];

        //获取管理损益表的 本期净利润（累计）==================================================================
        $benqijinglirun = D('Mrate','Service')->getMiidData($date,$cnum,'23');
        //查询管理损益表的 主营业务收入
        $zhuyingyewushouru = D('Mrate','Service')->getMiidData($date,$cnum,'1');
        //查询管理损益表的 减：营业税金及附加
        $yingyeshuijin = D('Mrate','Service')->getMiidData($date,$cnum,'3');
        //查询管理损益表的 主营业务净收入
        $zhuyingyewujingshouru = D('Mrate','Service')->getMiidData($date,$cnum,'4');
        //查询管理损益表的 减：直接成本
        $zhijiechengben = D('Mrate','Service')->getMiidData($date,$cnum,'5');
        //查询管理损益表的 变动营业费用
        $biandongfeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'7');
        //查询管理损益表的 其中：以销售收入为导向的销售费用
        $xiaoshoufeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'8');
        //查询管理损益表的 以销售量为导向的销售费用
        $xiaoshouliang = D('Mrate','Service')->getMiidData($date,$cnum,'9');
        //查询管理损益表的 减：管理费用
        $guanlifeiyong = D('Mrate','Service')->getMiidData($date,$cnum,'13');
        

        //获取管理现金表的 现金及现金等价物净增加额==================================================================
        $cashUp = D('Mflow','Service')->getMcidData($date,$cnum,'35');
        //获取管理现金表的 经营活动产生的现金流量净额
        $jingyingcash = D('Mflow','Service')->getMcidData($date,$cnum,'11');
        
        //获取管理资产表的 应收账款==================================================================
        $yingshouzhangkuan = D('Mrich','Service')->getMaidData($date,$cnum,'4');
        //获取管理资产表的 存货
        $cunhuo = D('Mrich','Service')->getMaidData($date,$cnum,'7');
        //获取管理资产表的 应付账款
        $yingfuzhangkuan = D('Mrich','Service')->getMaidData($date,$cnum,'18');
        
        //查询参数录入的 现金费用比==================================================================
        $xianjinfeiyongbi = D('Entry','Service')->getPinEntry($cnum,$date);
//        dump($xianjinfeiyongbi);
        //根据公司编号获取五大决策 五个数据的值=============================================================
        $B4 = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'1');
        $B5 = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'2');
        $B6 = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'3');
        $B7 = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'4');
        $B8 = D('Fivedata','Service')->getFivedataChangeLine($cnum,$date,'5');
        
//        dump($sell);
        if($B4['change']!=0){
            $B4z = $B4['change']/100;
            $E5 = $zhuyingyewushouru * $B4z;
            $E6 = $yingyeshuijin * $B4z;
            $E7 = $E5 - $E6;
            $E8 = $zhijiechengben * $B4z;
            $E9 = $E7 - $E8;
            $E11 = $xiaoshouliang * $B4z;
            $E24 = $E9 - $E11;
            $C4 = $E24 / $benqijinglirun;
            $D4 = $C4 / $B4z;
            $D4 = round($D4,2);
            //获取D5
            $B5z = $B5['change']/100;
            $F5 = $zhuyingyewushouru * $B5z;
            $F6 = $yingyeshuijin * $B5z;
            $F7 = $F5 - $F6;
            $F9 = $F7;
            $F12 = $xiaoshouliang * $B5z;
            $F24_2 = $F9 - $F12;
            $C5 = $F24_2 / $benqijinglirun;
            $D5 = $C5 / $B5z;
            $D5 = round($D5,2);
            
            //获取D6
            $B6z = $B6['change']/100;
            $F8 = $zhijiechengben * $B6z;
            $F24_3 = -$F8;
            $C6 = $F24_3 / $benqijinglirun;
            $D6 = $C6 / $B6z;
            $D6 = round($D6,2);
            
            //获取D7
            $B7z = $B7['change']/100;
            $F10 = $biandongfeiyong * $B7z;
            $F24_4 = -$F10;
            $C7 = $F24_4 / $benqijinglirun;
            $D7 = $C7 / $B7z;
            $D7 = round($D7,2);
            
            //获取D8
            $B8z = $B8['change']/100;
            $F24_5 = $guanlifeiyong * $B8z;
            $C8 = $F24_5 / $benqijinglirun;
            $D8 = $C8 / $B8z;
            $D8 = round($D8,2);
            
            $E4 = $D4 * $B4z + $D5 * $B5z -abs($D6) * $B6z -abs($D7) * $B7z -abs($D8) * $B8z + $D5*$B4z*$B5z - abs(D6)*$B4z*$B6z -abs($D7)*$B4z*$B7z;
            $F4 = $E4 * $benqijinglirun;
            $yujilirun = $benqijinglirun + $F4;
            $yujilirunnew = sprintf("%.2f", $yujilirun); 
            //计算预计经营现金流
            $M10  = $xianjinfeiyongbi/100;
            $M11 = -$xiaoshoufeiyong * $M10 * $B4z;
            $M12 =  -$xiaoshouliang * $M10 * $B4z;
            $M8 = -($E8 * $cunhuo / $zhijiechengben);
            $M7 = -($E7 * $yingshouzhangkuan / $zhuyingyewujingshouru);
            $M13 = -$M8 * $yingfuzhangkuan / $cunhuo;
            $M9 = $M7 + $M8;
            $M24 = $M9 + $M11 + $M12 + $M13;
            $I4 = $M24 / $jingyingcash;
            $J4 = $I4 / $B4z;
            $J4 = round($J4,2);
            
            //计算J5
            $N7 = -$F7 * $yingshouzhangkuan / $zhuyingyewujingshouru;
            $N9 = $N7;
            $N12 = $xiaoshouliang * $B5z * $M10;
            $N24 = $N9 + $N12;
            $I5 = $N24 / $jingyingcash;
            $J5 = $I5 / $B5z;
            $J5 = round($J5,2);
            
            //获取J6
            $F9_3 = -$zhijiechengben*$B6z;
            $N8 = $F9_3 * $yingfuzhangkuan / $zhijiechengben;
            $H6 = $N8;
            $I6 = $H6 / $jingyingcash;
            $J6 = $I6 / $B6z;
            $J6 = round($J6,2);
            
            //获取J7
            $N10 = $biandongfeiyong * $B7z * $M10;
            $H7 = - $N10;
            $I7 = $H7 / $jingyingcash;
            $J7 = $I7 / $B7z;
            $J7 = round($J7,2);
            
            //获取J8
            $N16 = $guanlifeiyong * $M10 * $B8z;
            $H8 = -$N16;
            $I8 = $H8 / $jingyingcash;
            $J8 = $I8 / $B8z;
            $J8 = round($J8,2);
            
            $K4 = $J4 * $B4z + $J5 * $B5z - abs($J6) * $B6z -  abs($J7) * $B7z - abs($J8) * $B8z + $J5*$B4z*$B5z - abs($J6)*$B4z*$B6z -abs($J7)*$B4z*$B7z;
            $L4 = $K4 * $cashUp;
            $yujicash = $cashUp + $L4;
            $yujicashnew = sprintf("%.2f", $yujicash); 
        }else{
            $yujilirun = $benqijinglirun;
            $yujicash = $cashUp;
        }
       
        if (IS_AJAX) {
            $arr['houyuji'] = $yujilirunnew;
            $arr['qianyuji'] = $benqijinglirun;
            $arr['houcash'] = $yujicashnew;
            $arr['qiancash'] = $cashUp;
            echo json_encode($arr);
            exit;
        }

        $this->assign('houyuji', $yujilirunnew); 
        $this->assign('qianyuji', $benqijinglirun); 
        $this->assign('houcash', $yujicashnew); 
        $this->assign('qiancash', $cashUp); 
        $this->display();
    }
    
    
    public function ajaxChange() {
        $change = I('change');
        $cnum = $_SESSION['current_account']['cnum'];
        $change = arrayValueToKey($change, 'name');

        //五大决策变动比率值
        $map['cnum'] = $cnum;
        $date = date('Y-m', time());
        $map['date'] = array('like', $date . '%');
        $chs = M('fivedata')->where($map)->field('f_id,change')->select();
        $chs = arrayValueToKey($chs, 'f_id');
        if (is_numeric($change['sell']['value']) && $chs[1]['change'] != $change['sell']['value']) {
            $res = D('Fivedata', 'Service')->updateChange($cnum, $change['sell']['value'], $date, 1);
            if (!$res) {
                echo 1;
                exit;
            }
        }

        if (is_numeric($change['price']['value']) && $chs[2]['change'] != $change['price']['value']) {
            $res = D('Fivedata', 'Service')->updateChange($cnum, $change['price']['value'], $date, 2);
            if (!$res) {
                echo 1;
                exit;
            }
        }
        if (is_numeric($change['cost']['value']) && $chs[3]['change'] != $change['cost']['value']) {
            $res = D('Fivedata', 'Service')->updateChange($cnum, $change['cost']['value'], $date, 3);
            if (!$res) {
                echo 1;
                exit;
            }
        }
        if (is_numeric($change['costchange']['value']) && $chs[4]['change'] != $change['costchange']['value']) {
            $res = D('Fivedata', 'Service')->updateChange($cnum, $change['costchange']['value'], $date, 4);
            if (!$res) {
                echo 1;
                exit;
            }
        }
        if (is_numeric($change['fix']['value']) && $chs[5]['change'] != $change['fix']['value']) {
            $res = D('Fivedata', 'Service')->updateChange($cnum, $change['fix']['value'], $date, 5);
            if (!$res) {
                echo 1;
                exit;
            }
        }
    }

}
