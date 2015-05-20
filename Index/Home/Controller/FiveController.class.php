<?php
namespace Home\Controller;

/**
 * FiveController
 * 五大决策
 */
class FiveController extends CommonController {
    /**
     * 五大决策
     * @return
     */
    public function index() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取五大决策的栏目
        $result = D('Five','Service')->getFiveList();
        $fid = array();
        foreach($result as $k=>$v){
            $fid[] = $v['id'];
        }
        //根据公司编号获取五大决策的内容
        $con = D('Fivedata','Service')->getFiveDataList($cnum,$fid,$date);
        if(!$con){
            $this->error($date.'没有数据');
        }
        $item = array();
        foreach ($con as $k => $v) {
            $item[$v['f_id']]=$v;
        }
        foreach ($result as $k => $v) {
            $v['five']=$item[$v['id']];
            $result[$k]=$v;
        }
        
        //查询销增表的本期净利润的值
        $xiaozeng = D('Pindata','Service')->getPindataBen($cnum,$date);
        //查询涨价表的本期净利润的值
        $zhangjia = D('Rise','Service')->getRiseBen($cnum,$date);
        //查询变动成本表的本期净利润的值
        $change = D('Changeable','Service')->getChangeableBen($cnum,$date);
        //查询变动费用表的本期净利润的值
        $variable = D('Variable','Service')->getVariableBen($cnum,$date);
        //查询固定费用表的本期净利润的值
        $constant = D('Constant','Service')->getConstantBen($cnum,$date);
        //查询管理现金表的  经营活动产生的现金流量净额 mc_id = 11
        $cashMoney = D('Mflow','Service')->getCashMoney($cnum,$date);
        //查询管理现金表的  现金及现金等价物净增加额 mc_id = 35
        $cashMoney2 = D('Mflow','Service')->getCashMoney2($cnum,$date);
//        dump($xiaozeng);
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('five', $result);
        $this->assign('xiaozeng', $xiaozeng);
        $this->assign('zhangjia', $zhangjia);
        $this->assign('change', $change);
        $this->assign('variable', $variable);
        $this->assign('constant', $constant);
        $this->assign('cashMoney', $cashMoney);
        $this->assign('cashMoney2', $cashMoney2);
        $this->display();
    }
    
    /**
     * 创建五大决策数据
     * @return
     */
    public function create() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $five = $_POST;
//        dump($five);exit;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Fivedata','Service')->addFive($cnum,$five);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 更新五大决策数据
     * @return
     */
    public function update() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $five = $_POST;
       
        $result = D('Fivedata','Service')->editFive($five);
//        dump($result);
        if (!$result) {
            echo 1;
        }else{
            echo 0;
        }
    }
    //判断五大决策数据是否已经存在
    public function isExistFive(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Fivedata','Service')->existFivedata($cnum,$date);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 修改杜邦分析表的数据
     */
    public function editFive() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $five = $_POST;
        $result = D('Fivedata','Service')->updateFivedata($five);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 销增销减
     * @return
     */
    public function pin() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取销增销减的栏目
        $result = D('Pin','Service')->getPinList();
        
        $mi_id = array();
        foreach($result as $k=>$v){
            $mi_id[] = $v['mi_id'];
        }
        //根据公司编号获取五大决策的销量
        $sell = D('Fivedata','Service')->getFiveDataSell($cnum,$date);
//        dump($sell);
        //查询管理资产表的数据
        $mrich = D('Mrich','Service')->getPinMrichList($cnum,$date);
        //查询参数录入的现金费用比
        $cashbi = D('Entry','Service')->getPinEntry($cnum,$date);
        //获取管理损益表的数据
        $con = D('Mrate','Service')->getPinMrateList($cnum,$mi_id,$date);
        if(!$con){
            $this->error($date.'没有数据');
        }
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['mi_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['mrate']=$item[$v['mi_id']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
//        dump($result);
        $this->assign('pin', $result);
        $this->assign('sell', $sell);
        $this->assign('mrich', $mrich);
        $this->assign('cashbi', $cashbi);
        $this->display();
    }
    //判断销增销减数据是否已经存在
    public function isExistPin(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Pindata','Service')->existPindata($cnum,$date);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 创建销增销减数据
     * @return
     */
    public function addPin() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Pindata','Service')->createPindata($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 修改销增销减数据
     * @return
     */
    public function editPin() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Pindata','Service')->updatePindata($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 涨价降价
     * @return
     */
    public function rise() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取17根戒尺的栏目
        $result = D('Pin','Service')->getPinList();
        $mi_id = array();
        foreach($result as $k=>$v){
            $mi_id[] = $v['mi_id'];
        }
        //根据公司编号获取五大决策的售价
        $price = D('Fivedata','Service')->getFiveDataPrice($cnum,$date);
        //查询管理资产表的数据
        $mrich = D('Mrich','Service')->getPinMrichList($cnum,$date);
        //查询参数录入的现金费用比
        $cashbi = D('Entry','Service')->getPinEntry($cnum,$date);
        //获取管理损益表的数据
        $con = D('Mrate','Service')->getPinMrateList($cnum,$mi_id,$date);
        if(!$con){
            $this->error($date.'没有数据');
        }
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['mi_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['mrate']=$item[$v['mi_id']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('pin', $result);
        $this->assign('price', $price);
        $this->assign('mrich', $mrich);
        $this->assign('cashbi', $cashbi);
        $this->display();
    }
    //判断涨价降价数据是否已经存在
    public function isExistRise(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Rise','Service')->existRise($cnum,$date);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 创建涨价降价数据
     * @return
     */
    public function addRise() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Rise','Service')->createRise($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 修改涨价降价数据
     * @return
     */
    public function editRise() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Rise','Service')->updateRise($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    
    /**
     * 变动成本
     * @return
     */
    public function changeable() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取17根戒尺的栏目
        $result = D('Pin','Service')->getPinList();
        $mi_id = array();
        foreach($result as $k=>$v){
            $mi_id[] = $v['mi_id'];
        }
        //根据公司编号获取五大决策的变动成本
        $change = D('Fivedata','Service')->getFiveDataChange($cnum,$date);
        //查询管理资产表的数据
        $mrich = D('Mrich','Service')->getPinMrichList($cnum,$date);
        //获取管理损益表的数据
        $con = D('Mrate','Service')->getPinMrateList($cnum,$mi_id,$date);
          if(!$con){
            $this->error($date.'没有数据');
        }
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['mi_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['mrate']=$item[$v['mi_id']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('pin', $result);
        $this->assign('change', $change);
        $this->assign('mrich', $mrich);
        $this->display();
    }
    //判断变动成本数据是否已经存在
    public function isExistChangeable(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Changeable','Service')->existChangeable($cnum,$date);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 创建变动成本数据
     * @return
     */
    public function addChangeable() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Changeable','Service')->createChangeable($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 修改变动成本数据
     * @return
     */
    public function editChangeable() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Changeable','Service')->updateChangeable($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 变动费用
     * @return
     */
    public function variable() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取17根戒尺的栏目
        $result = D('Pin','Service')->getPinList();
        $mi_id = array();
        foreach($result as $k=>$v){
            $mi_id[] = $v['mi_id'];
        }
        //根据公司编号获取五大决策的变动费用
        $variable = D('Fivedata','Service')->getFiveDataVariable($cnum,$date);
        //查询参数录入的现金费用比
        $cashbi = D('Entry','Service')->getPinEntry($cnum,$date);
        //获取管理损益表的数据
        $con = D('Mrate','Service')->getPinMrateList($cnum,$mi_id,$date);
        if(!$con){
            $this->error($date.'没有数据');
        }
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['mi_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['mrate']=$item[$v['mi_id']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('pin', $result);
        $this->assign('variable', $variable);
        $this->assign('cashbi', $cashbi);
        $this->display();
    }
    //判断变动成本数据是否已经存在
    public function isExistVariable(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Variable','Service')->existVariable($cnum,$date);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 创建变动成本数据
     * @return
     */
    public function addVariable() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Variable','Service')->createVariable($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 修改变动成本数据
     * @return
     */
    public function editVariable() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Variable','Service')->updateVariable($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 固定费用
     * @return
     */
    public function constant() {
        $date = $_GET['date'];
        $cnum = $_SESSION['current_account']['cnum'];

        //获取五大决策的栏目
        $result = D('Pin','Service')->getPinList();
        $mi_id = array();
        foreach($result as $k=>$v){
            $mi_id[] = $v['mi_id'];
        }
        //根据公司编号获取五大决策的固定费用
        $constant = D('Fivedata','Service')->getFiveDataConstant($cnum,$date);
        //查询参数录入的现金费用比
        $cashbi = D('Entry','Service')->getPinEntry($cnum,$date);
        //获取管理损益表的数据
        $con = D('Mrate','Service')->getPinMrateList($cnum,$mi_id,$date);
         if(!$con){
            $this->error($date.'没有数据');
        }
        $item=array();
        foreach($con as $k=>$v){
            $item[$v['mi_id']]=$v;
        }
        
        foreach ($result as $k => $v) {
            $v['mrate']=$item[$v['mi_id']];
            $result[$k]=$v;
        }
        if($date){
            $dateVal = $date;
        }else{
            $dateVal = date('Y-m',time());
        }
        $this->assign('dateVal', $dateVal);
        $this->assign('pin', $result);
        $this->assign('constant', $constant);
        $this->assign('cashbi', $cashbi);
        $this->display();
    }
    
    //判断固定费用数据是否已经存在
    public function isExistConstant(){
        $date = $_POST['date'];
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Constant','Service')->existConstant($cnum,$date);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 创建固定费用数据
     * @return
     */
    public function addConstant() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Constant','Service')->createConstant($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
    /**
     * 修改固定费用数据
     * @return
     */
    public function editConstant() {
        if (!isset($_POST)) {
            return $this->errorReturn('无效的操作！');
        }
        $con = $_POST;
        $cnum = $_SESSION['current_account']['cnum'];
        $result = D('Constant','Service')->updateConstant($cnum,$con);
        if ($result) {
            echo 0;
        }else{
            echo 1;
        }
    }
}
