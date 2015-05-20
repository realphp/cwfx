<?php
namespace Home\Controller;
/**
  +----------------------------------------------------------
 * Export Excel | 2014.2.21
 * Author:阿斑图 <man_quan@sina.cn>
  +----------------------------------------------------------
 */
class ExcelController extends CommonController {

    public function __construct() {
        parent::__construct();
        Vendor("phpExcel.PHPExcel"); //引入phpexcel类(注意你自己的路径)  
        Vendor("phpExcel.PHPExcel.IOFactory");
    }

    /**
     * 导出数据到表格文件
     * @param $expTitle     string File name
     * @param $expCellName  array  Column name
     * @param $expTableData array  Table data
     */
    public function exportExcel($expTitle, $expCellName, $expTableData) {
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle); //文件名称
        $fileName = $_SESSION['loginAccount'] . date('_YmdHis'); //or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);

        $objPHPExcel = new PHPExcel();
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        //$objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1'); //合并单元格
        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . '  Export time:' . date('Y-m-d H:i:s'));
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8   
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 2), $expTableData[$i][$expCellName[$j][0]]);
            }
        }

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls"); //attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        exit;
    }

    /**
     * 导出数据到表格文件
     * @param string $table 表名
     */
    function expData($table) {//导出Excel
        if ($table == '') {
            $table = $_REQUEST['table'];
        }
        $xlsName = $table;
        $xlsModel = D($table);
        $fields = $xlsModel->getDbFields();

        foreach ($fields as $v) {
            $str.="'" . $v . "',";
        }
        $str = substr($str, 0, strlen($str) - 1);

        $table = C('DB_PREFIX') . $table;
        $dbname = C('DB_NAME');
        //查询表字段的备注信息
        $xlsCell = $xlsModel->query("SELECT  column_name, column_comment from  Information_schema.columns where column_name in ({$str}) and table_Name='{$table}' and table_schema='{$dbname}'"); //查询字段备注信息,这么做要确定字段已填备注

        foreach ($xlsCell as $k => $v) {
            if (isset($fields[$k])) {
                $data[] = array($v['column_name'], $v['column_comment']);
            }
        }
        /*
         * 定制输出字段的数组
          $data = array(
          array('id', '账号序列'),
          array('account', '登录账户'),
          array('nickname', '账户昵称'),
          array('password', '密码')
          ); */

        $xlsData = $xlsModel->select();
        //C('OUTPUT_ENCODE',FALSE);必须把页面压缩关掉
        $this->exportExcel($xlsName, $data, $xlsData);
    }

    /**
     * 读取文件生成数组
     * @param string $filename 文件路径
     * @param string $encode  编码
     * @param string $file_type 文件类型
     * @param string $table 表名
     * @return array 读取文件生成的数组
     */
    public function read($filename, $encode, $file_type, $table) {
        if (strtolower($file_type) == 'xls') {//判断excel表类型为2003还是2007  
            Vendor("Excel.PHPExcel.Reader.Excel5");
            $objReader = PHPExcel_IOFactory::createReader('Excel5');
        } elseif (strtolower($file_type) == 'xlsx') {
            Vendor("Excel.PHPExcel.Reader.Excel2007");
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        }
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();

        //获取表字段,这么写，表格文件列顺序必须与数据库表的字段顺序相同
        $m = D($table);
        $field = $m->getDbFields();

        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {

                //$excelData[$row][(string) $objWorksheet->getCellByColumnAndRow($col, 1)->getValue()] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();//字段名存于excel表中
                //$excelData[$row][] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                //从表中获取字段
                $excelData[$row][$field[$col + 1]] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }

    /**
     * 处理上传文件，并调用读取表格文件，插入数据库
     * @param string $_POST['table'] table name
     */
    public function add() {

        $file_types = explode(".", $_FILES ['import'] ['name']);
        $file_type = $file_types [count($file_types) - 1];
        /* 判别是不是.xls文件，判别是不是excel文件 */

        if (strtolower($file_type) != "xlsx" && strtolower($file_type) != "xls") {
            $this->error('不是Excel文件，重新上传');
        }
        $fileinfo = $this->upLoad($_FILES['import'], 2); //文件返回的是文件的路径

        $res = $this->read('./' . $fileinfo, "UTF-8", $file_type, $_POST['table']); //传参,判断office2007还是office2003  
        sort($res); //用addall数组索引必须从0开始
        $kucun = D($_REQUEST['table']); //M方法
        $kucun->create($res);
        $result = $kucun->addAll($res);

        if (!$result) {
            $this->error('导入数据库失败');
            exit();
        } else {
            $this->success('导入成功');
        }
    }

}

?>
