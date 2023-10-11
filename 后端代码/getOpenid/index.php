<?php

    // 增删改查使用文档
    // https://segmentfault.com/a/1190000040517153
    // 作者：TANKING
    // 博客：https://segmentfault.com/u/tanking
    // 开发日期：2023-10-11
    
    // 编码
    header("content-type:application/json");
    
    // 获取参数
    $scene = $_GET['scene'];
    $code = $_GET["code"];
    
    // 获取数据库配置
    include '../Db.php';
    
    // 实例化数据库
    $db = new DB_API($config);
    
    $appid = $config['appid'];
    $appsecret = $config['appsecret'];
    
    // 换取openid的API
    $api = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$appsecret&js_code=$code&grant_type=authorization_code";
    $result = file_get_contents($api);
    $arr_result = json_decode($result, true);

    // 解析出openid
    $openid = $arr_result["openid"];
    
    // 验证scene参数
    $checkScene = $db->set_table('scanlogin_loginAuth')->find(['scene' => $scene]);
    if($checkScene) {
        
        // 如果存在scene
        // 验证小程序码是否过期
        $expire = $checkScene['expire'];

        if($expire == 2) {
            
            // 小程序码已过期
            $ret = array(
        	    'code' => 201,
        	    'msg' => '小程序码已过期'
            );
        }else{
            
            // 更新为已扫码
            $updateScanStaus = $db->set_table('scanlogin_loginAuth')->update(
                ['scene'=>$scene],
                ['openid'=>$openid,'status'=>2]
            );

            if($updateScanStaus){
                
                $ret = array(
            	    'code' => 200,
            	    'msg' => '已授权',
            	    'openid' => $openid
                );
            }else{
                
                // 数据库操作失败
                $ret = array(
            	    'code' => 202,
            	    'msg' => '授权失败'
                );
            }
        }
    }

    // 返回结果
    echo json_encode($ret, JSON_UNESCAPED_UNICODE);
    
?>