<?php
namespace Home\Service;

/**
 * FivedataService
 */
class FivedataService extends CommonService {
    /**
     * 获取五大决策数据
     */
    public function getFiveDataList($cnum,$fid,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['f_id'] = array('in',$fid);
        $map['date'] = array('like',$date.'%');
        $mfive= $this->getM();
        $result = $mfive->where($map)->select();
        return $result;
    }
    //查询是否存在五大决策数据
    public function existFivedata($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mfive = $this->getM();
        $result = $mfive ->where($map) ->select();
        return $result;
    }
    
    /**
     * 修改管理资产负债数据
     */
    public function updateFivedata($five){
        $fiveData = $this->getD();
        $newFive = array();
        $map['id'] = $five['id'];
        
        $newFive['up_down'] = $five['upDown'];
        $newFive['before_change'] = $five['before'];
        $newFive['change'] = $five['change'];
        $newFive['after_change'] = $five['after'];
        $newFive['sensitive'] = $five['sensitive'];
        $newFive['cash_flow'] = $five['cashFlow'];
        $newFive['cash_rate'] = $five['cashRate'];
        $newFive['cash_sensitivity'] = $five['cashSensitivity'];
        $newFive['up'] = $five['up'];
        $newFive['all_up'] = $five['allUp'];
        $newFive['profit'] = $five['profit'];
        $newFive['cash_up'] = $five['cashUp'];
        $newFive['cash_increase'] = $five['cashIncrease'];
        $as = $fiveData->where($map)->save($newFive);
//        echo $Rich->getLastSql();
        return $as;
    }
    /**
     * 添加资产负债数据
     */
    public function addFive($cnum,$five){
        $date = $five['date'];
        if(empty($date)){
            $date = date('Y-m-d',time());
        }else{
            $date = $five['date'].'-01';
        }
        
        $fivedata = $this->getD();
        $newFive = array();
        $newFive['cnum'] = $cnum;
        $newFive['date'] = $date;
        $newFive['f_id'] = $five['f_id'];
        $newFive['change'] = $five['change'];
        
        $as = $fivedata->add($newFive);
//        echo $fivedata->getLastSql();
        return $as;
    }
    /**
     * 修改五大决策数据
     */
    public function editFive($five){
        $Five = $this->getD();
        $newFive = array();
        $id = $five['fd'];
        
        $newFive['change'] = $five['change'];
        $as = $Five->where("id={$id}")->save($newFive);
//        echo $Rich->getLastSql();
        return $as;
    }
    //查询销量
    public function getFiveDataSell($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['f_id'] = '1';
        $map['date'] = array('like',$date.'%');
        $mfive= $this->getM();
        $result = $mfive->where($map)->getField('change');
        return $result;
    }
    
    //查询售价
    public function getFiveDataPrice($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['f_id'] = '2';
        $map['date'] = array('like',$date.'%');
        $mfive= $this->getM();
        $result = $mfive->where($map)->getField('change');
        return $result;
    }
    
    //查询变动成本
    public function getFiveDataChange($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['f_id'] = '3';
        $map['date'] = array('like',$date.'%');
        $mfive= $this->getM();
        $result = $mfive->where($map)->getField('change');
        return $result;
    }
    
    //查询变动费用
    public function getFiveDataVariable($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['f_id'] = '4';
        $map['date'] = array('like',$date.'%');
        $mfive= $this->getM();
        $result = $mfive->where($map)->getField('change');
        return $result;
    }
    
    //查询固定费用
    public function getFiveDataConstant($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['f_id'] = '5';
        $map['date'] = array('like',$date.'%');
        $mfive= $this->getM();
        $result = $mfive->where($map)->getField('change');
        return $result;
    }
    
    protected function getModelName() {
        return 'Fivedata';
    }
}
