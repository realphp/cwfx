<?php
namespace Home\Service;

/**
 * SeventeenService
 */
class SeventeenService extends CommonService {
    /**
     * 获取17根戒尺数据
     * @return array
     */
    public function getSeventeenList(){
        $seven = $this->getM();
        $result = $seven ->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Seventeen';
    }
}
