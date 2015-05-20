<?php
namespace Home\Service;

/**
 * AssetsService
 */
class AssetsService extends CommonService {
    /**
     * 获取资产负债数据
     * @return array
     */
    public function getAssetsList(){
        $assets = $this->getM();
        $result = $assets ->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Assets';
    }
}
