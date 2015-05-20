<?php
namespace Home\Service;

/**
 * PindataService
 */
class PindataService extends CommonService {
    /**
     * 获取销增数据
     * @return array
     */
    public function getPindataList(){
        $Pindata = $this->getM();
        $result = $Pindata ->select();
        return $result;
    }
    
    /**
     * 获取销增的本期净利润值数据 pid=19
     * @return array
     */
    public function getPindataBen($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $Pindata = $this->getM();
        $map['cnum'] = $cnum;
        $map['pid'] = 19;
        $map['date'] = array('like',$date.'%');
        $result = $Pindata->where($map)->field('sum_money,profitChange,cashChange')->find();
        return $result;
    }
    
    public function existPindata($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $Pindata = $this->getM();
        $result = $Pindata ->where($map) ->select();
        return $result;
    }

    //创建固定费用数据
    public function createPindata($cnum,$con){
        $date = $con['date'].'-01';
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        $Pindata = $this->getD();
        $newCon = array();
        $newCon['cnum'] = $cnum;
        $newCon['date'] = $date;
        $newCon['pid'] = $con['pid'];
        $newCon['sum_money'] = $con['sum_money'];
        $newCon['name'] = $con['name'];
        $newCon['unit'] = $con['unit'];
        $newCon['ratio'] = $con['ratio'];
        $newCon['profitChange'] = $con['profitChange'];
        $newCon['cashChange'] = $con['cashChange'];
        
//        $newMrate['order_id'] = $mrate['order_id'];
        $as = $Pindata->add($newCon);
//        echo $Mrate->getLastSql();
        return $as;
    }
    /**
     * 修改管理标准损益表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function updatePindata($cnum,$con){
        $Pindata = $this->getD();
        $date = $con['date'];
        $newCon = array();
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['pid'] = $con['pid'];
        $map['name'] = $con['name'];
        
        $newCon['profitChange'] = $con['profitChange'];
        $newCon['cashChange'] = $con['cashChange'];
        $newCon['unit'] = $con['unit'];
        $newCon['sum_money'] = $con['sum_money'];
        $newCon['ratio'] = $con['ratio'];
        $as = $Pindata->where($map)->save($newCon);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    protected function getModelName() {
        return 'Pindata';
    }
}
