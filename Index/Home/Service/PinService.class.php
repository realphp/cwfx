<?php
namespace Home\Service;

/**
 * PinService
 */
class PinService extends CommonService {
    /**
     * 获取销增数据
     * @return array
     */
    public function getPinList(){
        $seven = $this->getM();
        $result = $seven ->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Pin';
    }
}
