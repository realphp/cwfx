<?php

$modelMenu = include('model_menu.php');

if (false === $modelMenu) {
    $modelMenu = array();
}

// 菜单项配置
$systemMenu = array(
    // 后台首页
    'Index' => array(
        'name' => '首页',
        'target' => 'Index/index',
        'sub_menu' => array(
            array('item' => array('Index/index' => '系统信息')),
            array('item' => array('Index/editPassword' => '修改密码')),
            array('item' => array('Index/siteEdit' => '站点信息')),
            array('item' => array('Cache/index' => '清除缓存'))
        )
    ),

    

    // 数据管理
    'Admins' => array(
        'name' => '客户管理',
        'target' => 'Admins/index',
        'sub_menu' => array(
            array('item' => array('Admins/index' => '客户信息')),
            array('item' => array('Admins/add' => '添加客户')),
            array('item'=>array('Admins/edit'=>'编辑客户信息'),'hidden'=>true),
        )
    ),

    

    
);

return array_merge($systemMenu, $modelMenu);
