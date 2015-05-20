<?php
namespace Home\Service;

/**
 * ParamService
 */
class ParamService extends CommonService {
    /**
     * 获取标准损益表数据
     * @param  array $cnum 公司编号
     * @return array
     */
    public function getParamList(){
        $param = $this->getM();
        $result = $param->select();
        return $result;
    }
    
    protected function getModelName() {
        return 'Param';
    }
}
