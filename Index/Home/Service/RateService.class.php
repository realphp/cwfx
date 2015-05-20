<?php
namespace Home\Service;

/**
 * RateService
 */
class RateService extends CommonService {
    /**
     * 获取资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getRateList($cnum,$iid,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['interest_id']  = array('in',$iid);
        $rate= $this->getM();
        $result = $rate
                ->where($map)
                ->select();
        return $result;
    }
    
    public function getAllRate($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate = $this->getM();
        $result = $rate
                ->where($map)
                ->select();
        return $result;
    }
    public function getLastRate($cnum,$date){
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
        $rate = $this->getM();
        $result = $rate
                ->where($map)
                ->select();
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
    public function add($data){
        $Rate = $this->getD();
        $as = $Rate->add($data);
//        echo $Rich->getLastSql();
        return $as;
    }
    /**
     * 添加标准损益数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function addRate($cnum,$rate){
        $date = $rate['date'];
        if(empty($date)){
            $newdate = date('Y-m-d',time());
        }else{
            $newdate = $rate['date'].'-01';
        }
        $Rate = $this->getD();
        $newRate = array();
        $newRate['cnum'] = $cnum;
        $newRate['date'] = $newdate;
        $newRate['interest_id'] = $rate['interest_id'];
        $field = $rate['fd'];
        if($field == 'now_money'){
            $newRate['sum_money'] = 0;
        }else{
             $newRate['now_money'] = 0;
        }
        $newRate[$field] = $rate['val'];
        $as = $Rate->add($newRate);
//        echo $Rate->getLastSql();
        return $as;
    }
    
    /**
     * 修改标准损益数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function editRate($rate){
        $Rate = $this->getD();
        $newRate = array();
        $id = $rate['id'];
        $field = $rate['fd'];
        
        $newRate[$field] = $rate['val'];
        $as = $Rate->where("id={$id}")
                      ->save($newRate);
//        echo $Rich->getLastSql();
        return $as;
    }
    /**
     * 清空标准损益表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function deleteRate($date,$cnum){
        $Rate = $this->getD();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $as = $Rate->where($map)->delete();
//        echo $Rich->getLastSql();
        return $as;
    }
    /**
     * 审核标准损益表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function reviewRate($date,$cnum){
        $Rate = $this->getD();
        $newRate = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newRate['review'] = 1;
        $as = $Rate->where($map)->save($newRate);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 取消审核标准损益表数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function cancelReviewRate($date,$cnum){
        $Rate = $this->getD();
        $newRate = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newRate['review'] = 0;
        $as = $Rate->where($map)->save($newRate);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    protected function getModelName() {
        return 'Rate';
    }
}
