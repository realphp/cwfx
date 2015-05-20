<?php
namespace Home\Service;

/**
 * CashService
 */
class CashService extends CommonService {
    /**
     * 获取现金流量表数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getCashList(){
        $cash = $this->getM();
        $result = $cash->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Cash';
    }
}
