<?php
namespace Home\Service;

/**
 * RateService
 */
class EntryService extends CommonService {
    /**
     * 获取资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getEntryList($cnum,$iid,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = array('in',$iid);
        $rate= $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    //查询是否存在当月的数据
    public function isExistEntry($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate= $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    public function getAllEntry($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate= $this->getM();
        $result = $rate->where($map)->select();
        return $result;
    }
    
    public function getBetweenEntry($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate = $this->getM();
        $result = $rate->field('date,param_id,now_money')->where($map)->select();
        return $result;
    }
    
    public function getEntryShou($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = 4;
        $entry = $this->getM();
        $result = $entry->where($map)->select();
        return $result;
    }
    public function getEntrySell($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = 5;
        $entry = $this->getM();
        $result = $entry->where($map)->select();
        return $result;
    }
    public function getEntryZhi($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = 2;
        $entry = $this->getM();
        $result = $entry->where($map)->select();
        return $result;
    }
    //查询生产制造折旧
    public function getEntryS($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = 7;
        $entry = $this->getM();
        $result = $entry->where($map)->select();
        return $result;
    }
    //销售部门
    public function getEntryX($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = 8;
        $entry = $this->getM();
        $result = $entry->where($map)->select();
        return $result;
    }
    //管理部门
    public function getEntryG($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = 9;
        $entry = $this->getM();
        $result = $entry->where($map)->select();
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
    public function addEntry($cnum,$entry,$date){
        if(empty($date)){
            $newdate = date('Y-m-d',time());
        }else{
            $newdate = $date.'-01';
        }
        
        $Entry = $this->getD();
        $newEntry = array();
        $newEntry['cnum'] = $cnum;
        $newEntry['date'] = $newdate;
        $newEntry['param_id'] = $entry['param_id'];
        $newEntry['sum_money'] = $entry['sum_money'];
        $newEntry['now_money'] = $entry['now_money'];
        $as = $Entry->add($newEntry);
//        echo $Entry->getLastSql();die;
        return $as;
    }
    public function addEntryZ($cnum,$entry){
        $date = date('Y-m-d',time());
        $Entry = $this->getD();
        $newEntry = array();
        $newEntry['cnum'] = $cnum;
        $newEntry['date'] = $date;
        $newEntry['param_id'] = $entry['param_id'];
        $newEntry['now_money'] = $entry['now_money'];
        $newEntry['sum_money'] = $entry['sum_money'];
        
        $as = $Entry->add($newEntry);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 修改标准损益数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function editEntry($entry){
        $Entry = $this->getD();
        $newEntry = array();
        $id = $entry['id'];
        $newEntry['now_money'] = $entry['now_money'];
        $newEntry['sum_money'] = $entry['sum_money'];
        
        $as = $Entry->where("id={$id}")
                      ->save($newEntry);
//        echo $Rich->getLastSql();
        return $as;
    }
    //删除现有数据
    public function delEntry($entry){
        $Entry = $this->getD();
        $newEntry = array();
        $id = $entry['id'];
        $as = $Entry->where("id={$id}")->delete();
//        echo $Rich->getLastSql();
        return $as;
    }
    public function editEntryZ($date,$cnum,$entry){
        $Entry = $this->getD();
        $newEntry = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id'] = $entry['param_id'];
        
        $newEntry['now_money'] = $entry['now_money'];
        $newEntry['sum_money'] = $entry['sum_money'];
        
        $as = $Entry->where($map) ->save($newEntry);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    public function editEntry3($date,$cnum,$entry){
        $Entry = $this->getD();
        $newEntry = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id'] = $entry['param_id'];
        
        $newEntry['now_money'] = $entry['now_money'];
        $newEntry['sum_money'] = $entry['sum_money'];
        
        $as = $Entry->where($map) ->save($newEntry);
//        echo $Entry->getLastSql();
        return $as;
    }
    
    /**
     * 清空参数录入数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function deleteEntry($date,$cnum){
        $Entry = $this->getD();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $as = $Entry->where($map)->delete();
//        echo $Entry->getLastSql();
        return $as;
    }
    
    /**
     * 审核参数录入数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function reviewEntry($date,$cnum){
        $Entry = $this->getD();
        $newEntry = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newEntry['review'] = 1;
        $as = $Entry->where($map)->save($newEntry);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 取消审核参数录入数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function reviewCancelEntry($date,$cnum){
        $Entry = $this->getD();
        $newEntry = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newEntry['review'] = 0;
        $as = $Entry->where($map)->save($newEntry);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    //获取参数录入的现金费用比（五大决策的销增销减用）
    public function getPinEntry($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['param_id']  = 10;
        $entry = $this->getM();
        $result = $entry->where($map)->getField('now_money');
        return $result;
    }
    protected function getModelName() {
        return 'Entry';
    }
}
