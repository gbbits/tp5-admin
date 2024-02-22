<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    //模板配置
    'template'               => [
        // 模板文件名分隔符
        'view_depr'    => '-',
    ],

    //不需要登录/权限验证的控制器和方法
    'no_login'=>[
        "Login/index",
        'Login/login',
        'Login/logout',
        'Common/captcha',
        'Common/isLogin',
        'Index/add'
    ],

    //错误跳转对应的模板文件
//    'dispatch_error_tmpl' => APP_PATH . 'admin/tpl/admin.tpl',
    //成功跳转对应的模板文件
//    'dispatch_success_tmpl' => APP_PATH . 'admin/tpl/admin.tpl',
];
