<?php
namespace Home\Service;

/**
 * DubangService
 */
class DubangService extends CommonService {
    /**
     * 获取资产负债数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getDubangList($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $mrate= $this->getM();
        $result = $mrate->where($map)->select();
        return $result;
    }
    //是否存在杜邦数据
    public function existDubang($cnum,$date){
        if(empty($date)){
            $date = date('Y-m',time());
        }
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $dubang = $this->getM();
        $result = $dubang->where($map)->select();
        return $result;
    }
    
    /**
     * 添加目标的数据
     * @param   $cnum 公司编号
     */
    public function addDubang($cnum,$target){
        $date = $target['date'].'-01';
        if(empty($date)){
            $date = date('Y-m-d',time());
        }
        $Dubang = $this->getD();
        $newDubang = array();
        $newDubang['cnum'] = $cnum;
        $newDubang['date'] = $date;
        $newDubang['name'] = $target['name'];
        $newDubang['target'] = $target['target'];
        $as = $Dubang->add($newDubang);
//        echo $Mrate->getLastSql();
        return $as;
    }
    
    /**
     * 修改目标数据
     */
    public function editDubang($target){
        $Dubang = $this->getD();
        $date = $target['date'];
        $newDubang = array();
        $map['id'] = $target['id'];
        
        $newDubang['target'] = $target['target'];
        $as = $Dubang->where($map)->save($newDubang);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    /**
     * 修改杜邦数据
     */
    public function updateDubang($cnum,$dubang){
        $Model = $this->getD();
        $date = $dubang['date'];
        $newDubang = array();
        $map['cnum'] = $cnum;
        $map['date'] = array('like',$date.'%');
        $map['id'] = $dubang['id'];
        $map['name'] = $dubang['name'];
        
        $newDubang['month'] = $dubang['month'];
        $newDubang['lastMonth'] = $dubang['lastMonth'];
        $as = $Model->where($map)->save($newDubang);
//        echo $Rich->getLastSql();
        return $as;
    }
    
    protected function getModelName() {
        return 'Dubang';
    }
}
