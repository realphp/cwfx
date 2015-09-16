<?php
namespace Home\Service;

/**
 * FlowService
 */
class FlowService extends CommonService {
    /**
     * 获取现金流量数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getFlowList($cnum,$cid,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['cash_id']  = array('in',$cid);
        $rate= $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    
    public function getAllFlow($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate= $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    
    //查询历史
    public function getBetweenFlow($cnum,$cid,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['cash_id']  = array('in',$cid);
        $rate = $this->getM();
        $result = $rate->field('date,cash_id,money')->where($map)->select();
        return $result;
    }
    
    /**
     * 获取现金流量数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getFlowAddList($cnum,$aid,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['cashadd_id']  = array('in',$aid);
        $rate= $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    /**
     * 添加现金流量数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function addFlow($cnum,$flow){
        $date = $flow['date'];
        if(empty($date)){
            $newdate = date('Y-m-d',time());
        }else{
            $newdate = $flow['date'].'-01';
        }
        $Flow = $this->getD();
        $newFlow = array();
        $newFlow['cnum'] = $cnum;
        $newFlow['date'] = $newdate;
        if(empty($flow['cash_id'])){
            $newFlow['cashadd_id'] = $flow['cashadd_id'];
            $newFlow['cash_id'] = 0;
        }else{
            $newFlow['cash_id'] = $flow['cash_id'];
            $newFlow['cashadd_id'] = 0;
        }
        
        $field = $flow['fd'];
        $newFlow[$field] = $flow['val'];
        
        $as = $Flow->add($newFlow);
//        echo $Flow->getLastSql();
        return $as;
    }
    public function add($data){
        $Flow = $this->getD();
        $newFlow = array();
        $newFlow['cnum'] = $data['cnum'];
        $newFlow['date'] = $data['date'];
        $newFlow['money'] = (empty($data['money']))?0:$data['money'];
        if(empty($data['cash_id'])){
            $newFlow['cashadd_id'] = $data['cashadd_id'];
            $newFlow['cash_id'] = 0;
        }else{
            $newFlow['cash_id'] = $data['cash_id'];
            $newFlow['cashadd_id'] = 0;
        }
        $as = $Flow->add($newFlow);
//        echo $Flow->getLastSql();
        return $as;
    }
    /**
     * 修改标准损益数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function editFlow($flow){
        $Flow = $this->getD();
        $newFlow = array();
        $id = $flow['id'];
        $field = $flow['fd'];
        
        $newFlow[$field] = $flow['val'];
        $as = $Flow->where("id={$id}")
                      ->save($newFlow);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 审核现金流量表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function reviewFlow($date,$cnum){
        $Flow = $this->getD();
        $newFlow = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newFlow['review'] = 1;
        $as = $Flow->where($map)->save($newFlow);
//        echo $Rich->getLastSql();
        return $as;
    }
    /**
     * 清空现金流量表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function deleteFlow($date,$cnum){
        $Flow = $this->getD();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $as = $Flow->where($map)->delete();
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 取消审核现金流量表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function reviewCancelFlow($date,$cnum){
        $Flow = $this->getD();
        $newFlow = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newFlow['review'] = 0;
        $as = $Flow->where($map)->save($newFlow);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    protected function getModelName() {
        return 'Flow';
    }

    public function getFlowZi($cnum, $date, $inter) {
        $map['cnum'] = $cnum;
        $map['cash_id'] = $inter;
        $map['date'] = array('like', $date . '%');
        $rate = $this->getM();
        $result = $rate->where($map)->getField('money');
        return $result;
    }

}
