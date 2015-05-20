<?php
namespace Home\Service;

/**
 * InterestService
 */
class InterestService extends CommonService {
    /**
     * 获取标准损益表数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getInterestList(){
        $interest = $this->getM();
        $result = $interest->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Interest';
    }
}
