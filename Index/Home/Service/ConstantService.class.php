<?php
namespace Home\Service;

/**
 * ConstantService
 */
class ConstantService extends CommonService {
    /**
     * 获取销增数据
     * @return array
     */
    public function getConstantList(){
        $seven = $this->getM();
        $result = $seven ->select();
        return $result;
    }
    /**
     * 获取固定费用的本期净利润值数据 pid=19
     * @return array
     */
    public function getConstantBen($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $Constant = $this->getM();
        $map['cnum'] = $cnum;
        $map['pid'] = 19;
        $map['date'] = array('like',$date.'%');
        $result = $Constant->where($map)->field('sum_money,profitChange,cashChange')->find();
        return $result;
    }
    public function existConstant($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $constant = $this->getM();
        $result = $constant ->where($map) ->select();
        return $result;
    }

    //创建固定费用数据
    public function createConstant($cnum,$con){
        $date = $con['date'].'-01';
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        $Constant = $this->getD();
        $newCon = array();
        $newCon['cnum'] = $cnum;
        $newCon['date'] = $date;
        $newCon['pid'] = $con['pid'];
        $newCon['sum_money'] = $con['sum_money'];
        $newCon['name'] = $con['name'];
        $newCon['unit1'] = $con['unit1'];
        $newCon['ratio'] = $con['ratio'];
        $newCon['unit2'] = $con['unit2'];
        $newCon['profitChange'] = $con['profitChange'];
        $newCon['cashChange'] = $con['cashChange'];
        
//        $newMrate['order_id'] = $mrate['order_id'];
        $as = $Constant->add($newCon);
//        echo $Mrate->getLastSql();
        return $as;
    }
    /**
     * 修改管理标准损益表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function updateConstant($cnum,$con){
        $Constant = $this->getD();
        $date = $con['date'];
        $newCon = array();
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['pid'] = $con['pid'];
        $map['name'] = $con['name'];
        
        $newCon['profitChange'] = $con['profitChange'];
        $newCon['cashChange'] = $con['cashChange'];
        $newCon['unit1'] = $con['unit1'];
        $newCon['unit2'] = $con['unit2'];
        $newCon['sum_money'] = $con['sum_money'];
        $newCon['ratio'] = $con['ratio'];
        $as = $Constant->where($map)->save($newCon);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    protected function getModelName() {
        return 'Constant';
    }
}
