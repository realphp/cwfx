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
        $cnum = $_SESSION['current_account']['cnum'];
        $where = 'is_super=0 and cnum='.$cnum;
        $result = $this->getPagination('Admin',$where);
        
        $this->assign('admins', $result['data']);
        $this->assign('rows_count', $result['total_rows']);
        $this->assign('page', $result['show']);
        $this->display();
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
        //获取当前公司能保存几个子账户
        $admin = D('Admin','Service')->getCnum($_POST['admin']['cnum']);
        
        //获取当前公司已经有几个账户
        $num = D('Admin','Service')->getNowAdmin($_POST['admin']['cnum']);
        
        //获取当前公司的基本信息
        $nowCompany = D('Admin','Service')->getNowCompany($_POST['admin']['cnum']);
        
        if($num>=$admin['childnum']){
            return $this->errorReturn('你最多可以设置'.$admin['childnum'].'个子账户！', U('Admins/index'));
        }
        
        $data = $_POST['admin'];
        $data['name'] = $nowCompany['0']['name'];
        $data['address'] = $nowCompany['0']['address'];
        $data['position'] = '财务';
        $data['kind'] = '1';
        $data['price'] = $nowCompany['0']['price'];
        $data['address'] = $nowCompany['0']['address'];
        $data['enddate'] = $nowCompany['0']['enddate'];
        
//        dump($data);
        $result = D('Admin', 'Service')->add($data);
        if (!$result['status']) {
            return $this->errorReturn($result['data']['error']);
        }

        return $this->successReturn('添加管理员成功！', U('Admins/index'));
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
