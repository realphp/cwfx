<?php

namespace Home\Controller;

use Think\Controller;

/**
 * IndexController
 * 系统信息管理
 */
class DemoController extends Controller {

    public function index() {
        $list = M('hit')->group('type')->field('url,sum(hit)as pv,count(type) as uv')->select();
        $this->assign('list', $list);
        $this->display();
    }

    public function census() {
        $type = I('type');
        if ($type == 1) {
            $url = "http://www.iqiyi.com/v_19rrnq0xvs.html?src=frbdaldjunest";
        } else if ($type == 2) {
            $url = "http://www.iqiyi.com/v_19rrojgadc.html#vfrm=2-3-0-1";
        } else if ($type == 3) {
            $url = "http://www.iqiyi.com/v_19rrog9zzs.html#vfrm=2-3-0-1";
        } else if ($type == 4) {
            $url = "http://www.iqiyi.com/v_19rrol2iro.html?vfm=f_191_360y";
       }else if ($type == 5) {
            $url = "http://www.iqiyi.com/v_19rro9nt5g.html?src=frbdaldjunest";
       	}else if ($type == 6) {
            $url = "http://t.cn/RLHXXTD";
        } else {
            exit;
        }
        $ip = get_client_ip();
        $map['ip'] = $ip;
        $map['url'] = $url;
        $res = M('hit')->where($map)->find();
        //统计数据
        if ($res) {
            M('hit')->where($map)->setInc('hit');
        } else {
            $data['type'] = $type;
            $data['url'] = $url;
            $data['ip'] = get_client_ip();
            $data['ctime'] = date('Y-m-d');
            $data['hit'] = 1;
            M('hit')->add($data);
        }
        redirect($url);
    }

}
