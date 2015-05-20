<?php
namespace Home\Service;
class ExcelService extends CommonService {
	public function read($filename,$encode='utf-8')
    {
		require_once'./phpexcel/PHPExcel.php';
		
		$objPHPExcel = \PHPExcel_IOFactory::load($filename);
		$excelarray= $objPHPExcel->getSheet(0)->toArray();	
		return $excelarray;		
    }
    

    /**
     * 是否关联查询
     * @return boolean
     */
    protected function isRelation() {
        return false;
    }

    protected function getModelName() {
        return 'User';
    }


}