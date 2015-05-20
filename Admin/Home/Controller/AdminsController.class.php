<?php
namespace Home\Controller;

/**
 * AdminsController
 * 管理员信息
 */
class AdminsController extends CommonController {
    /**
     * 管理员列表
     * @return
     */
    public function index() {
        $where = "is_super=0";
        $result = $this->getPagination('Admin',$where,$fields,'id desc');

        $this->assign('admins', $result['data']);
        $this->assign('rows_count', $result['total_rows']);
        $this->assign('page', $result['show']);
        $this->display();
    }
    //客户
    public function customers(){
        $where = "is_super=0";
        $result = D('Admin','Service')->getAllCustomers($where);
//        dump($result);
        return $result;
    }
    //excel导出数据代码
    public function excel(){

        require_once'./phpexcel/PHPExcel.php';
        require_once'./phpexcel/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls
        require_once'./phpexcel/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式 
        require_once'./phpexcel/PHPExcel/IOFactory.php'; // 用于 excel-2007 格式 
		
		
        $date = date("Y_m_d H-i-s",time());
        $fileName .= "_{$date}.xlsx";
        
        $fileName = iconv("utf-8", "gb2312", $fileName);
        //创建新的PHPExcel对象
        $objPHPExcel = new \PHPExcel();

         /*以下是一些设置 ，什么作者  标题啊之类的*/  
        $objPHPExcel->getProperties()->setCreator("SEIEE")  
                  ->setLastModifiedBy("SEIEE")  
                  ->setTitle("Data export")  
                  ->setSubject("Data export")  
                  ->setDescription("Data Backup")  
                  ->setKeywords("excel")  
                  ->setCategory("result file");  
		
	$data = $this->customers();	
		/*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改，这里最好能够做成对$data的遍历*/  
        foreach($data as $k => $v){  
            $num=$k+3;
            $objPHPExcel->getActiveSheet()->setCellValue('A1', '客户表');
                    $objPHPExcel->getActiveSheet()->setCellValue('A2', '客户编号');
                    $objPHPExcel->getActiveSheet()->setCellValue('B2', '公司');
                    $objPHPExcel->getActiveSheet()->setCellValue('C2', '用户名');
                    $objPHPExcel->getActiveSheet()->setCellValue('D2', '真实姓名');
                    $objPHPExcel->getActiveSheet()->setCellValue('E2', '职位');
                    $objPHPExcel->getActiveSheet()->setCellValue('F2', '联系方式');
                    $objPHPExcel->getActiveSheet()->setCellValue('G2', '公司地址');
                    $objPHPExcel->getActiveSheet()->setCellValue('H2', '电子邮箱');
                    $objPHPExcel->getActiveSheet()->setCellValue('I2', '订购金额');
                    $objPHPExcel->getActiveSheet()->setCellValue('J2', '开户时间');
                    $objPHPExcel->getActiveSheet()->setCellValue('K2', '到期时间');
                    $objPHPExcel->getActiveSheet()->setCellValue('L2', '子账户数量');
                    $objPHPExcel->getActiveSheet()->setCellValue('M2', '备注');
            $objPHPExcel->setActiveSheetIndex(0)  
            //Excel的第A列，uid是你查出数组的键值，下面以此类推  
                ->setCellValue('A'.$num, $v['cnum'])      
                ->setCellValue('B'.$num, $v['name'])  
                ->setCellValue('C'.$num, $v['username'])
                ->setCellValue('D'.$num, $v['realname'])
                ->setCellValue('E'.$num, $v['position'])      
                ->setCellValue('F'.$num, $v['mobile'])  
                ->setCellValue('G'.$num, $v['address'])
                ->setCellValue('H'.$num, $v['email'])
                ->setCellValue('I'.$num, $v['price'])      
                ->setCellValue('J'.$num, date("Y-m-d",$v['created_at']))  
                ->setCellValue('K'.$num, $v['enddate'])
                ->setCellValue('L'.$num, $v['childnum'])
                ->setCellValue('M'.$num, $v['remark']);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(40);
        $objPHPExcel->getActiveSheet()->setTitle($fileName);  
        $objPHPExcel->setActiveSheetIndex(0);  
        ob_end_clean();

	header("Pragma: public"); //下面是一堆header的设置，测试的时候加了好多，现在不清楚哪个没用  
        header("Expires: 0");  
        header('Content-Type: application/vnd.ms-excel;charset=utf-8;name="'.$fileName.'.xls"');  
        header("Content-Type: application/force-download");  
        header("Content-Type: application/octet-stream");  
        header("Content-Type: application/download");  
        header('Content-Disposition: attachment;filename="客户信息表'.$fileName.'.xls"');  
        header('Cache-Control: max-age=0');  
        header("Content-Transfer-Encoding:binary");  
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output');  
        exit;
    }
    
    /**
     * 添加管理员
     * @return
     */
    public function add() {
        $this->assign('roles', D('Role', 'Service')->getRoles());
        $this->display();
    }

    /**
     * 创建管理员
     * @return
     */
    public function create() {
        if (!isset($_POST['admin'])) {
            return $this->errorReturn('无效的操作！');
        }
        $data = $_POST['admin'];
        $data['kind'] = '1';
        $result = D('Admin', 'Service')->add($data);
        if (!$result['status']) {
            return $this->errorReturn($result['data']['error']);
        }

        return $this->successReturn('添加用户成功！', U('Admins/index'));
    }

    /**
     * 编辑管理员信息
     * @return
     */
    public function edit() {
        if (!isset($_GET['id'])
        	  || !D('Admin', 'Service')->existAdmin($_GET['id'])) {
            return $this->error('需要编辑的管理员信息不存在！');
        }

        $admin = M('Admin')->getById($_GET['id']);
        if (C('SUPER_ADMIN_EMAIL') == $admin['email']
            && !$_SESSION[C('ADMIN_AUTH_KEY')]) {
            return $this->errorReturn('您没有权限执行该操作！');
        }

        $this->assign('admin', $admin);
        $this->assign('roles', D('Role', 'Service')->getRoles());
        $this->display();
    }

    /**
     * 更新管理员信息
     * @return
     */
    public function update() {
        $adminService = D('Admin', 'Service');
        if (!isset($_POST['admin'])
            || !$adminService->existAdmin($_POST['admin']['id'])) {
            return $this->errorReturn('无效的操作！');
        }

        $result = $adminService->update($_POST['admin']);
        if (!$result['status']) {
            return $this->errorReturn($result['data']['error']);
        }

        return $this->successReturn('更新管理员信息成功！', U('Admins/index'));
    }
}
