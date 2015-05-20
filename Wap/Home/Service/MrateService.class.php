<?php
namespace Home\Service;

/**
 * RateService
 */
class MrateService extends CommonService {
    /**
     * 获取资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getMrateList($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mrate= $this->getM();
        $result = $mrate->where($map)->order('order_id')->select();
        return $result;
    }
    //获取 mc_id确定 的累计值
    public function getMiidData($date,$cnum,$miid){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['mi_id'] = $miid;
        $map['date'] = array('like',$date.'%');
        $mrate= $this->getM();
        $result = $mrate->where($map)->getField('sum_money');
        return $result;
    }
    /**
     * 获取资产负债数据
     */
    public function getMrateLastList($cnum,$date){
        if(empty($date)){
            $date1 = date('Y-m-d',  time());
            $timestamp=strtotime($date1);
            $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
            $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
            $str2 = substr($lastday,0,7);
            $date = $str2;
        }else{
            $timestamp=strtotime($date);
            $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
            $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
            $str1 = substr($lastday,0,7);
            $date = $str1;
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mrate= $this->getM();
        $result = $mrate->where($map)->order('order_id')->select();
        return $result;
    }
    
    /**
     * 获取销增表所需的数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getPinMrateList($cnum,$mi_id,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['mi_id'] = array('in',$mi_id);
        $map['date'] = array('like',$date.'%');
        $mrate= $this->getM();
        $result = $mrate->where($map)->select();
        return $result;
    }
    
    //管理损益表里面是否存在数据
    public function existMrate($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mrate = $this->getM();
        $result = $mrate ->where($map) ->select();
        return $result;
    }
    
    public function getBetweenRate($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate = $this->getM();
        $result = $rate->field('date,interest_id,now_money')->where($map)->select();
        return $result;
    }
    
    //获取其中：主营业务成本
    public function getRateOther($cnum,$date){
        $map['cnum'] = $cnum;
        $map['interest_id'] = 5;
        $map['date'] = array('like',$date.'%');
        $rate = $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    
    //获取资产减值损失
    public function getRateZi($cnum,$date){
        $map['cnum'] = $cnum;
        $map['interest_id'] = 15;
        $map['date'] = array('like',$date.'%');
        $rate = $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    /**
     * 添加管理标准损益表的数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function createMrate($cnum,$mrate){
        $date = $mrate['date'].'-01';
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        $Mrate = $this->getD();
        $newMrate = array();
        $newMrate['cnum'] = $cnum;
        $newMrate['date'] = $date;
        $newMrate['mi_id'] = $mrate['mi_id'];
        $newMrate['now_money'] = $mrate['now_money'];
        $newMrate['sum_money'] = $mrate['sum_money'];
        $newMrate['name'] = $mrate['name'];
        $newMrate['rate'] = $mrate['rate'];
        $newMrate['order_id'] = $mrate['order_id'];
        $as = $Mrate->add($newMrate);
//        echo $Mrate->getLastSql();
        return $as;
    }
    
    /**
     * 修改管理标准损益表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function updateMrate($cnum,$mrate){
        $Mrate = $this->getD();
        $date = $mrate['date'];
        $newMrate = array();
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['mi_id'] = $mrate['mi_id'];
        $map['name'] = $mrate['name'];
        
        $newMrate['now_money'] = $mrate['now_money'];
        $newMrate['sum_money'] = $mrate['sum_money'];
        $newMrate['order_id'] = $mrate['order_id'];
        $as = $Mrate->where($map)->save($newMrate);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    protected function getModelName() {
        return 'Mrate';
    }
}
