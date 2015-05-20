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
            array('item' => array('Index/help' => '使用帮助'),'hidden'=>true)
        )
    ),

    // 数据管理
    'Admins' => array(
        'name' => '成员管理',
        'target' => 'Admins/index',
        'sub_menu' => array(
            array('item' => array('Admins/index' => '成员管理')),
            array('item' => array('Admins/add' => '添加成员'))
        )
    ),

    // 基础报表
    'Base' => array(
        'name' => '基础报表',
        'target' => 'Base/zichan',
        'sub_menu' => array(
            array('item' => array('Base/zichan' => '资产负债表')),
            array('item' => array('Base/biaozhun' => '标准损益表')),
            array('item' => array('Base/xianjin' => '现金流量表')),
            array('item' => array('Base/canshu' => '参数录入')),
        )
    ),
    
    // 管理报表
    'Manage' => array(
        'name' => '管理报表',
        'target' => 'Manage/zichan',
        'sub_menu' => array(
            array('item' => array('Manage/zichan' => '管理资产负债表')),
            array('item' => array('Manage/historyZichan' => '管理资产负债表'),'hidden'=>true),
            array('item' => array('Manage/biaozhun' => '管理损益表')),
            array('item' => array('Manage/historyBiaozhun' => '管理损益表'),'hidden'=>true),
            array('item' => array('Manage/xianjin' => '管理现金流量表')),
            array('item' => array('Manage/historyXianjin' => '管理现金流量表'),'hidden'=>true),
        )
    ),
    
    // 杜邦分析法
    'Dubang' => array(
        'name' => '杜邦分析法',
        'target' => 'Dubang/index',
        'sub_menu' => array(
            array('item' => array('Dubang/index' => '杜邦分析法')),
        )
    ),
    
    // 五大决策
    'Five' => array(
        'name' => '五大决策',
        'target' => 'Five/index',
        'sub_menu' => array(
            array('item' => array('Five/index' => '五大决策')),
            array('item' => array('Five/pin' => '销增销减')),
            array('item' => array('Five/rise' => '涨价降价')),
            array('item' => array('Five/changeable' => '变动成本')),
            array('item' => array('Five/variable' => '变动费用')),
            array('item' => array('Five/constant' => '固定费用')),
        )
    ),

    // 字段管理
    'Fields' => array(
        'name' => '字段管理',
        'target' => 'Fields/edit',
        'mapping' => 'Models',
        'sub_menu' => array(
            array('item' => array('Fields/add' => '添加字段')),
            array('item' => array('Fields/edit' => '编辑字段')),
        )
    ),


);

return array_merge($systemMenu, $modelMenu);
