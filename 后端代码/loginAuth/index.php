<?php

    // 增删改查使用文档
    // https://segmentfault.com/a/1190000040517153
    // 作者：TANKING
    // 博客：https://segmentfault.com/u/tanking
    // 开发日期：2023-10-11
    
    // 编码
    header("content-type:application/json");
    
    // 获取scene
    $scene = $_GET['scene'];
    
    // 数据库配置
    include '../Db.php';
    $db = new DB_API($config);
    
    // 验证scene
    $checkScene = $db->set_table('scanlogin_loginAuth')->find(['scene' => $scene]);
    if($checkScene) {
        
        // 生成token
        // 生成规则：scene+appsecret+时间戳进行MD5签名
        // 后面登录回调地址会携带这个token
        $token = MD5($scene.$config['appsecret'].time());
        
        // 更新为已授权登录且设置小程序码为过期
        $updateAuthStatus = $db->set_table('scanlogin_loginAuth')->update(
            ['scene' => $scene],
            ['authTime' => time(), 'token' => $token, 'status' => 3, 'expire' => 2]
        );

        if($updateAuthStatus){
            
            // 已授权
            $ret = array(
        	    'code' => 200,
        	    'msg' => '已授权'
            );
        }else{
            
            // 授权失败
            $ret = array(
    	        'code' => 201,
    	        'msg' => '授权失败'
            );
        }
    }else {
        
        // scene不存在
        $ret = array(
    	    'code' => 202,
    	    'msg' => '授权失败，scene不存在'
        );
    }

    // 返回结果
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);

?>