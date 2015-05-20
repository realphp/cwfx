<?php
namespace Home\Service;

/**
 * MflowService
 */
class MflowService extends CommonService {
    /**
     * 获取资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getMflowList($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate= $this->getM();
        $result = $rate ->where($map) ->select();
        return $result;
    }
    
    //查询 现金及现金等价物净增加额
    public function getMcidData($date,$cnum,$mcid){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['mc_id'] = $mcid;
        $map['date'] = array('like',$date.'%');
        $rate= $this->getM();
        $result = $rate ->where($map) ->getField('money');
        return $result;
    }
    
    //管理损益表里面是否存在数据
    public function existMflow($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mrate = $this->getM();
        $result = $mrate ->where($map) ->select();
        return $result;
    }
    
    
    /**
     * 添加管理现金流量表的数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function createMflow($cnum,$mflow){
        $date = $mflow['date'].'-01';
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        $Mflow = $this->getD();
        $newMflow = array();
        $newMflow['cnum'] = $cnum;
        $newMflow['date'] = $date;
        $newMflow['mc_id'] = $mflow['mc_id'];
        $newMflow['money'] = $mflow['money'];
        $newMflow['inside'] = $mflow['inside'];
        $newMflow['name'] = $mflow['name'];
        $newMflow['into'] = $mflow['into'];
        $newMflow['outof'] = $mflow['outof'];
        $newMflow['in_out'] = $mflow['in_out'];
        $as = $Mflow->add($newMflow);
//        echo $Mflow->getLastSql();
        return $as;
    }
    
    /**
     * 修改管理现金流量表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function updateMflow($cnum,$mflow){
        $Mflow = $this->getD();
        $date = $mflow['date'];
        $newMflow = array();
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['mc_id'] = $mflow['mc_id'];
        $map['name'] = $mflow['name'];
        
        $newMflow['money'] = $mflow['money'];
        $newMflow['inside'] = $mflow['inside'];
        $newMflow['into'] = $mflow['into'];
        $newMflow['outof'] = $mflow['outof'];
        $newMflow['in_out'] = $mflow['in_out'];
        $as = $Mflow->where($map)->save($newMflow);
        echo $Mflow->getLastSql();
        return $as;
    }
    
    /**
     * 获取管理现金流量表的经营活动产生的现金流量净额 mc_id = 11
     * @return array
     */
    public function getCashMoney($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $Constant = $this->getM();
        $map['cnum'] = $cnum;
        $map['mc_id'] = 11;
        $map['date'] = array('like',$date.'%');
        $result = $Constant->where($map)->field('money')->find();
        return $result;
    }
    
    /**
     * 获取管理现金流量表的经营活动产生的现金流量净额 mc_id = 35
     * @return array
     */
    public function getCashMoney2($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $Constant = $this->getM();
        $map['cnum'] = $cnum;
        $map['mc_id'] = 35;
        $map['date'] = array('like',$date.'%');
        $result = $Constant->where($map)->field('money')->find();
        return $result;
    }
    
    protected function getModelName() {
        return 'Mflow';
    }
}
