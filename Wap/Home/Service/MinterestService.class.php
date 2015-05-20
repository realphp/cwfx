<?php
namespace Home\Service;

/**
 * MinterestService
 */
class MinterestService extends CommonService {
    /**
     * 获取标准损益表数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getMinterestList(){
        $interest = $this->getM();
        $result = $interest->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Minterest';
    }
}
