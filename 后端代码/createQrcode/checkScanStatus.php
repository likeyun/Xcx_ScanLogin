<?php

    // 增删改查使用文档
    // https://segmentfault.com/a/1190000040517153
    // 作者：TANKING
    // 博客：https://segmentfault.com/u/tanking
    // 开发日期：2023-10-11

	// 编码
	header("Content-type:application/json");
	
    // 获取参数
    $Scene = trim($_GET['scene']);
    
    if($Scene) {
       
        // 数据库配置
    	include '../Db.php';
        
        // 实例化类
    	$db = new DB_API($config);
    	
        // 查看Scene的状态
    	$checkScanStatus = $db->set_table('scanlogin_loginAuth')->find(['scene' => $Scene]);
    	if($checkScanStatus){
    	   
            // 扫码状态
    	    $status = $checkScanStatus['status'];

            // openid
    	    $openid = $checkScanStatus['openid'];

            // 小程序码过期状态
    	    $expire = $checkScanStatus['expire'];
    	    
    	    // token
    	    $token = $checkScanStatus['token'];
    	    
    	    if($status == 1) {
    	        
    	       // 未扫码
    	       $result = array(
    		        'code' => 202,
    		        'msg' => '请使用微信扫码'
    		   );
    	       
    	    }else if($status == 2) {
    	        
    	       // 已扫码
    	       $result = array(
    		        'code' => 203,
    		        'msg' => '已扫码，请点击授权登录'
    		   );
    		   
    	    }else if($status == 3 && $openid) {
    	        
    		   // 删除临时文件
               unlink('qrcode/' . $Scene . '.png');
               
               // 登录成功的处理
               // 例如存SESSION
               // 数据库操作等
               // -----------------------------------
               // 在这里编写你的逻辑
               
               // 已登录
    	       $result = array(
    		        'code' => 200,
    		        'msg' => '登录成功',
    		        'token' => $token
    		   );
    		   
    	    }else if($status == 4) {
    	        
    	       // 已取消授权
    	       $result = array(
    		        'code' => 204,
    		        'msg' => '已取消授权'
    		   );
    		   
    		   // 删除临时文件
               unlink('qrcode/' . $Scene . '.png');
    	       
    	    }
    	}else{
    	    
    	    // 获取失败
            $result = array(
                'code' => 204,
                'msg' => '该二维码无法登录'
            );
    	}
    }else {
        
        $result = array(
            'code' => 204,
            'msg' => '缺少参数'
        );
    }

	// 输出JSON
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
	
?>