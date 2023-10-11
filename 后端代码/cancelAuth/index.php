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
    
    include '../Db.php';
    $db = new DB_API($config);
    
    // 验证scene
    $cancelAuth = $db->set_table('scanlogin_loginAuth')->find(['scene' => $scene]);
    if($cancelAuth) {
        
        // 更新为取消授权且设置小程序码为过期
        $update = $db->set_table('scanlogin_loginAuth')->update(
            ['scene'=>$scene],
            ['status'=>4,'expire'=>2]
        );
        if($update){
            
            $ret = array(
        	    'code' => 200,
        	    'msg' => '已取消授权'
            );
        }else{
            
            $ret = array(
    	        'code' => 201,
    	        'msg' => '取消失败'
            );
        }
    }else {
        
        $ret = array(
    	    'code' => 202,
    	    'msg' => '取消失败，scene不存在'
        );
    }

    // 返回结果
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    
?>