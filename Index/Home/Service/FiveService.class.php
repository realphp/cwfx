<?php
namespace Home\Service;

/**
 * FiveService
 */
class FiveService extends CommonService {
    /**
     * 获取五大决策数据
     * @return array
     */
    public function getFiveList(){
        $seven = $this->getM();
        $result = $seven ->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Five';
    }
}
