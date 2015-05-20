<?php
namespace Home\Controller;

/**
 * BaseController
 * 基础报表信息
 */
class MobileController extends CommonController {
    public function login() {
        layout(false);
        $this->display();
    }
    public function index() {
        layout(false);
        $this->display();
    }
    /**
     * 资产负债表页面
     * @return
     */
    public function zichan() {
        layout(false);
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
            if($k ==13){
                $liudong = $result[$k]['rich']['end_money'];
            }
            if($k ==33){
                $feiliudong = $result[$k]['rich']['end_money'];
            }
        }
//        dump($liudong);dump($feiliudong);
        $this->assign('liudong', $liudong);
        $this->assign('feiliudong', $feiliudong);
        $this->display();
    }
    public function ziben() {
         layout(false);
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
            $liudongfuzhai = $result[50]['rich']['end_money'];
            $feiliudongfuzhai = $result[57]['rich']['end_money'];
            $all = $result[64]['rich']['end_money'];
        }
//        dump($feiliudongfuzhai);
        $this->assign('liudongfuzhai', $liudongfuzhai);
        $this->assign('feiliudongfuzhai', $feiliudongfuzhai);
        $this->assign('allziben', $all);
         $this->display();
    }
    public function yinli() {
         layout(false);
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
            if($k ==16){
                $lirunshouru = $result[$k]['rate']['now_money'];
            }
            if($k ==17){
                $yingyelirun = $result[$k]['rate']['now_money'];
                $zhu = $yingyelirun - $lirunshouru;
            }
            if($k ==23){
                $lirunzonge = $result[$k]['rate']['now_money'];
            }
        }
        //主营业务利润
        
//        dump($result);
        $this->assign('zhu', $zhu);
        $this->assign('yingyelirun', $yingyelirun);
        $this->assign('lirunzonge', $lirunzonge);
         $this->display();
    }
    public function maoli() {
         layout(false);
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
            if($k ==0){
                $zongshouru = $result[$k]['rate']['now_money'];
            }
            if($k ==4){
                $zongchengben = $result[$k]['rate']['now_money'];
                $maoli = $zongshouru - $zongchengben;
                $maolilv = round(($maoli/$zongshouru),2);
            }
        }
        //主营业务利润
//        dump($maolilv);
//        dump($result);
        $this->assign('zongshouru', $zongshouru);
        $this->assign('zongchengben', $zongchengben);
        $this->assign('maoli', $maoli);
        $this->assign('maolilv', $maolilv);
         $this->display();
    }
    public function yunying() {
         layout(false);
         
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
            
            $zongzichan = $result['1']['rich']['end_money'] + $result['2']['rich']['end_money'] + $result['3']['rich']['end_money']
                    + $result['4']['rich']['end_money'] + $result['5']['rich']['end_money'] + $result['6']['rich']['end_money']
                    + $result['9']['rich']['end_money'] + $result['10']['rich']['end_money'] + $result['11']['rich']['end_money']
                    + $result['12']['rich']['end_money'] + $result['13']['rich']['end_money'];
            
            $ziyunfuzhaiheji = $result['17']['rich']['end_money'] + $result['18']['rich']['end_money'] + $result['19']['rich']['end_money']
                    + $result['20']['rich']['end_money'] + $result['21']['rich']['end_money'] + $result['22']['rich']['end_money'];
            
            $yunyingzichan = $zongzichan - $ziyunfuzhaiheji;
            
        }
//        dump($zongzichan);
        $this->assign('yunyingzongzichan', $zongzichan);
        $this->assign('yunyingzichan', $yunyingzichan);
         
         $this->display();
    }
    public function maoyi() {
         layout(false);
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取标准损益表的栏目
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getAllRate($cnum,$date);
        
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
            $zhuyingyewujingshouru = $result['3']['rate']['sum_money'];
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
            $zhijiechengben = $result['4']['entry']['sum_money'];
        }
        $gongxianmaoyi = $zhuyingyewujingshouru - $zhijiechengben;
        $gongxianmaoyilv = round($gongxianmaoyi/$zhuyingyewujingshouru,2);
//        dump($gongxianmaoyilv);
        
        $this->assign('zhuyingyewujingshouru', $zhuyingyewujingshouru);
        $this->assign('zhijiechengben', $zhijiechengben);
        $this->assign('gongxianmaoyi', $gongxianmaoyi);
        $this->assign('gongxianmaoyilv', $gongxianmaoyilv);
         $this->display();
    }
    public function anquan() {
        layout(false);
         $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];
//        $where = 'is_super=0 and cnum='.$cnum;
        //获取标准损益表的栏目
        $result = D('Minterest','Service')->getMinterestList();
        //根据公司编号获取标准损益表的内容
        $con = D('Rate','Service')->getAllRate($cnum,$date);
        
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
            $zhuyingyewujingshouru = $result['3']['rate']['now_money'];
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
            $zhijiechengben = $result['4']['entry']['sum_money'];
            $xiaoshoushouru = $result['7']['entry']['sum_money'];
            $xiaoshouliang = $result['8']['entry']['sum_money'];
            $biandongyingyefeiyong = $xiaoshoushouru + $xiaoshouliang;
            $gongxianmaoyi = $zhuyingyewujingshouru - $zhijiechengben;
            $maolilv = round($gongxianmaoyi/$zhuyingyewujingshouru,2);
            //固定营业费用
            $zhejiu = $result['13']['entry']['sum_money'];
            $gudingyingyefeiyong = $sell2 + $manager2 + $zhejiu;
            //caiwu
            $caiwu = $result['15']['entry']['sum_money'];
            //三项费用合计 = 
            $sanxiang = $manager2 + $sell2 +  $caiwu;
            //盈亏平衡点
            $pinghengdian = $sanxiang/$maolilv;
            //安全边际销售额
            $anquanbianji = $zhuyingyewujingshouru - $pinghengdian;
            //安全边际率
            $anquanbianjilv = round(($anquanbianji/$zhuyingyewujingshouru)*100,2);
        }
        
//        dump($result);
        
        $this->assign('anquan', $anquanbianjilv);
        $this->display();
    }
    public function zichanfuzhai(){
        layout(false);
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
            //营运负债合计
            $yingyunfuzhai = $result['17']['rich']['end_money'] + $result['18']['rich']['end_money'] + $result['19']['rich']['end_money']
                    + $result['20']['rich']['end_money'] + $result['21']['rich']['end_money'] + $result['22']['rich']['end_money'];
            $fuzhaiheji = $result['26']['rich']['end_money'] + $result['27']['rich']['end_money'] + $result['30']['rich']['end_money']
                    + $result['31']['rich']['end_money'];
            $zongfuzhai = $yingyunfuzhai + $fuzhaiheji;
            //应付账款
            $yingfuzhangkuan = round($result['17']['rich']['end_money'],2);
            //其他应付款
            $qita = round($result['22']['rich']['end_money'],2);
            //预收账款
            $yushouzhangkuan = round($result['18']['rich']['end_money'],2);
        }
//        dump($zongfuzhai);
//        dump($yingfuzhangkuan);
//        dump($qita);
//        dump($yushouzhangkuan);
        
        $this->assign('zongfuzhai', $zongfuzhai);
        $this->assign('yingfuzhangkuan', $yingfuzhangkuan);
        $this->assign('qita', $qita);
        $this->assign('yushouzhangkuan', $yushouzhangkuan);
        $this->display();
    }
    public function guanli(){
        layout(false);
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
            //总资产
            $total = $result['1']['rich']['end_money'] + $result['2']['rich']['end_money'] + $result['3']['rich']['end_money']
                    + $result['4']['rich']['end_money'] + $result['5']['rich']['end_money'] + $result['6']['rich']['end_money']
                    + $result['9']['rich']['end_money'] + $result['10']['rich']['end_money'] + $result['11']['rich']['end_money']
                    + $result['12']['rich']['end_money'] + $result['13']['rich']['end_money'];
            //货币资金
            $huobizijin = round($result['1']['rich']['end_money'],2);
            //应收账款
            $yingshouzhangkuan = round($result['3']['rich']['end_money'],2);
            //存货
            $cunhuo = round($result['6']['rich']['end_money'],2);
        }
//        dump($zongzichan);
//        dump($huobizijin);
//        dump($yingshouzhangkuan);
//        dump($cunhuo);
//        $zongzichan = 123;
        $this->assign('total', $total);
        $this->assign('huobizijin', $huobizijin);
        $this->assign('yingshouzhangkuan', $yingshouzhangkuan);
        $this->assign('cunhuo', $cunhuo);
        $this->display();
    }
    public function liuru() {
        layout(false);
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
        $con = D('Flow','Service')->getFlowList($cnum,$cid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['cash_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['cash']=$item[$v['cid']];
            $result[$k]=$v;
            $yiliuru = $result['1']['cash']['money'] + $result['2']['cash']['money'] + $result['3']['cash']['money'];
            $erliuru = $result['12']['cash']['money'] + $result['13']['cash']['money'] + $result['14']['cash']['money']
                    + $result['15']['cash']['money'];
            $sanliuru = $result['23']['cash']['money'] + $result['24']['cash']['money'] + $result['25']['cash']['money'];
        }
        $liurubi1 = round($yiliuru/($yiliuru+$erliuru+$sanliuru),4)*100 ;
        $liurubi2 = round($erliuru/($yiliuru+$erliuru+$sanliuru),4)*100 ;
        $liurubi3 = round($sanliuru/($yiliuru+$erliuru+$sanliuru),4)*100 ;
        
        $this->assign('liurubi1', $liurubi1); 
        $this->assign('liurubi2', $liurubi2); 
        $this->assign('liurubi3', $liurubi3); 
        $this->display();
    }
    public function liuchu() {
        layout(false);
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
        $con = D('Flow','Service')->getFlowList($cnum,$cid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['cash_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['cash']=$item[$v['cid']];
            $result[$k]=$v;
            $yiliuchu = $result['5']['cash']['money'] + $result['6']['cash']['money'] + $result['7']['cash']['money'] 
                    + $result['7']['cash']['money'];
            $erliuchu = $result['17']['cash']['money'] + $result['18']['cash']['money'] + $result['19']['cash']['money'];
            $sanliuchu = $result['27']['cash']['money'] + $result['28']['cash']['money'] + $result['29']['cash']['money'];
        }
        $liuchubi1 = round($yiliuchu/($yiliuchu+$erliuchu+$sanliuchu),4)*100 ;
        $liuchubi2 = round($erliuchu/($yiliuchu+$erliuchu+$sanliuchu),4)*100 ;
        $liuchubi3 = round($sanliuchu/($yiliuchu+$erliuchu+$sanliuchu),4)*100 ;
        
        $this->assign('liuchubi1', $liuchubi1); 
        $this->assign('liuchubi2', $liuchubi2); 
        $this->assign('liuchubi3', $liuchubi3); 
        $this->display();
    }
    public function liuruliuchu() {
        layout(false);
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
        $con = D('Flow','Service')->getFlowList($cnum,$cid,$date);
        
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['cash_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['cash']=$item[$v['cid']];
            $result[$k]=$v;
            $yiliuru = $result['1']['cash']['money'] + $result['2']['cash']['money'] + $result['3']['cash']['money'];
            $yiliuchu = $result['5']['cash']['money'] + $result['6']['cash']['money'] + $result['7']['cash']['money'] 
                    + $result['7']['cash']['money'];
            $erliuru = $result['12']['cash']['money'] + $result['13']['cash']['money'] + $result['14']['cash']['money']
                    + $result['15']['cash']['money'];
            $erliuchu = $result['17']['cash']['money'] + $result['18']['cash']['money'] + $result['19']['cash']['money'];
            $sanliuru = $result['23']['cash']['money'] + $result['24']['cash']['money'] + $result['25']['cash']['money'];
            $sanliuchu = $result['27']['cash']['money'] + $result['28']['cash']['money'] + $result['29']['cash']['money'];
        }
        
        $liuruchubi1 = round($yiliuru/$yiliuchu,4)*100 ;
        $liuruchubi2 = round($erliuru/$erliuchu,4)*100 ;
        $liuruchubi3 = round($sanliuru/$sanliuchu,4)*100 ;
//        dump($liuruchubi1);dump($liuruchubi2);dump($liuruchubi3);
        $this->assign('liuruchubi1', $liuruchubi1); 
        $this->assign('liuruchubi2', $liuruchubi2); 
        $this->assign('liuruchubi3', $liuruchubi3); 
        $this->display();
    }
    
}
