<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/10
 * Time: 10:08
 */

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 绑定模型
define('BIND_MODULE','admin');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';