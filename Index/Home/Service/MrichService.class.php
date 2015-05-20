<?php
namespace Home\Service;

/**
 * MrichService
 */
class MrichService extends CommonService {
    /**
     * 获取资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getMrichList($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mrich = $this->getM();
        $result = $mrich->where($map)->select();
        return $result;
    }
    /**
     * 获取资产负债数据
     */
    public function getMrichLastList($cnum,$date){
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
        $result = $mrate->where($map)->select();
        return $result;
    }
    public function existMrich($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mrich = $this->getM();
        $result = $mrich->where($map)->select();
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
     * 添加管理资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function createMrich($cnum,$mrich){
        $date = $mrich['date']."-01";
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        $Mrich = $this->getD();
        $newMrich = array();
        $newMrich['cnum'] = $cnum;
        $newMrich['date'] = $date;
        $newMrich['ma_id'] = $mrich['ma_id'];
        $newMrich['start_money'] = $mrich['start_money'];
        $newMrich['end_money'] = $mrich['end_money'];
        $newMrich['name'] = $mrich['name'];
        $as = $Mrich->add($newMrich);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 修改管理资产负债数据
     */
    public function updateMrich($cnum,$mrich){
        $Mrich = $this->getD();
        $date = $mrich['date'];
        $newMrich = array();
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['ma_id'] = $mrich['ma_id'];
        $map['name'] = $mrich['name'];
        
        $newMrich['start_money'] = $mrich['start_money'];
        $newMrich['end_money'] = $mrich['end_money'];
        $as = $Mrich->where($map)->save($newMrich);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    //查询五大决策所需的数据
    public function getPinMrichList($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $rid = array('4','7','18');
        $map['ma_id'] = array('in',$rid);
        $map['date'] = array('like',$date.'%');
        $mfive= $this->getM();
        $result = $mfive->where($map)->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Mrich';
    }
}
