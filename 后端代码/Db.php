<?php

    // 增删改查使用文档
    // https://segmentfault.com/a/1190000040517153
    // 作者：TANKING
    // 博客：https://segmentfault.com/u/tanking
    // 开发日期：2023-10-11

    // 数据库操作类
    include 'DbClass.php';
    
    // 数据库配置
    $config = array (
      'db_host' => 'xxxxxxxxxxxxx', // 数据库服务器
      'db_port' => 3306, // 端口
      'db_name' => 'xxxxxxxxxxxxx', // 数据库名称
      'db_user' => 'xxxxxxxxxxxxx', // 数据库账号
      'db_prefix' => '', // 表前缀，表名被我写死了所以这个用不上但是必须留空不然报错
      'db_pass' => 'xxxxxxxxxxxxx', // 数据库密码
      'appid' => 'xxxxxxxxxxxxx', // 小程序appid
      'appsecret' => 'xxxxxxxxxxxxx', // 小程序appsecret
    );
    
    // 除了配置这个文件的这些参数之外
    // 如果你的小程序已经审核通过上线
    // 还需要去 createQrcode/createQrcode.php 里面修改一个参数
    // ------------------------------------------------------
    // 在代码的101行 "env_version" => "develop" 
    // 开发的时候这个参数是develop，小程序审核通过发布上线之后改为release
    // 因为用户无法打开开发版的小程序的，所以审核通过上线的小程序你需要改为release
    // 代表创建的小程序码是线上版本而不是开发版本
    
?>