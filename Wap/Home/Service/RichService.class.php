<?php
namespace Home\Service;

/**
 * RichService
 */
class RichService extends CommonService {
    /**
     * 获取资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getRichList($cnum,$aid,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['assets_id']  = array('in',$aid);
        $rich = $this->getM();
        $result = $rich
//                ->join(array(' LEFT JOIN __ASSETS__ ON __RICH__.assets_id = __ASSETS__.id'))
                ->where($map)
                ->select();
        return $result;
    }
    
    public function getAllRich($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rich = $this->getM();
        $result = $rich
                ->where($map)
                ->select();
        return $result;
    }
    public function getLastRich($cnum,$date){
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
        $rich = $this->getM();
        $result = $rich
                ->where($map)
                ->select();
        return $result;
    }
    //查询历史
    public function getBetweenRich($cnum,$date){
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $rate = $this->getM();
        $result = $rate->field('date,assets_id,end_money')->where($map)->select();
        return $result;
    }
    /**
     * 添加资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function addRich($cnum,$rich){
        $date = $rich['date'];
        if(empty($date)){
            $newdate = date('Y-m-d',time());
        }else{
            $newdate = $rich['date'].'-01';
        }
        
        $Rich = $this->getD();
        $newRich = array();
        $newRich['cnum'] = $cnum;
        $newRich['date'] = $newdate;
        $newRich['assets_id'] = $rich['assets_id'];
        $field = $rich['fd'];
        if($field == 'start_money'){
            $newRich['end_money'] = 0;
        }else{
             $newRich['start_money'] = 0;
        }
        $newRich[$field] = $rich['val'];
        
        $as = $Rich->add($newRich);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    public function add($data){
        $Rich = $this->getD();
        $as = $Rich->add($data);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 修改资产负债数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function editRich($rich){
        $Rich = $this->getD();
        $newRich = array();
        $id = $rich['id'];
        $field = $rich['fd'];
        
        $newRich[$field] = $rich['val'];
        $as = $Rich->where("id={$id}")
                      ->save($newRich);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 审核资产负债数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function reviewRich($date,$cnum){
        $Rich = $this->getD();
        $newRich = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newRich['review'] = 1;
        $as = $Rich->where($map)->save($newRich);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 取消审核资产负债数据
     * @param  array $rich 公司编号
     * @return array
     */
    public function cancelReviewRich($date,$cnum){
        $Rich = $this->getD();
        $newRich = array();
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        
        $newRich['review'] = 0;
        $as = $Rich->where($map)->save($newRich);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    protected function getModelName() {
        return 'Rich';
    }
}
