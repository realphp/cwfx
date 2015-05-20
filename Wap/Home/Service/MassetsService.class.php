<?php
namespace Home\Service;

/**
 * MassetsService
 */
class MassetsService extends CommonService {
    /**
     * 获取资产负债数据
     * @return array
     */
    public function getMassetsList(){
        $assets = $this->getM();
        $result = $assets ->select();
        return $result;
    }
    
    public function getMassetsList1(){
        $assets = $this->getM();
        $map['id']  = array('between','1,45');
        $result = $assets->where($map) ->select();
        return $result;
    }
    public function getMassetsList2(){
        $assets = $this->getM();
        $map['id']  = array('between',array(25,42));
        $result = $assets->where($map) ->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Massets';
    }
}
