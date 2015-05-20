<?php
namespace Home\Service;

/**
 * CashaddService
 */
class CashaddService extends CommonService {
    /**
     * 获取现金流量表数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getCashaddList(){
        $cash = $this->getM();
        $result = $cash->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Cashadd';
    }
}
