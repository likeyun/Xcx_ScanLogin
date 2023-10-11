<?php

    // 增删改查使用文档
    // https://segmentfault.com/a/1190000040517153
    // 作者：TANKING
    // 博客：https://segmentfault.com/u/tanking
    // 开发日期：2023-10-11

	// 编码
	header("Content-type:application/json");
	
    // 获取参数
    $scene = trim(intval($_GET['scene']));
    
    if($scene) {
       
        // 数据库配置
    	include '../Db.php';
        
        // 实例化类
    	$db = new DB_API($config);
    	
    	$checkScene = $db->set_table('scanlogin_loginAuth')->find(['scene' => $scene]);
    	if($checkScene){
    	    
    	    // 获取成功
    		$result = array(
    		    'code' => 200,
    		    'msg' => '获取成功'
    		);
    	}else{
    	    
    	    // 获取失败
            $result = array(
                'code' => 204,
                'msg' => '参数错误'
            );
    	} 
        
    }else {
        
        $result = array(
            'code' => 204,
            'msg' => '缺少参数...'
        );
    }

	// 输出JSON
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
	
?>