<?php

    // 增删改查使用文档
    // https://segmentfault.com/a/1190000040517153
    // 作者：TANKING
    // 博客：https://segmentfault.com/u/tanking
    // 开发日期：2023-10-11

    // 编码
    header("Content-type:application/json");
    
    // 获取数据库配置
    include '../Db.php';
    
    // 实例化数据库
    $db = new DB_API($config);
    
    // appid和appsecret
    // 从配置文件中读取
    $appid = $config['appid'];
    $appsecret = $config['appsecret'];
    
    // 本地缓存access_token的文件
    $cacheFile = 'access_token.php';
    
    // 获取access_token
    $access_token = getAccessToken($cacheFile, $appid, $appsecret);
    
    // 获取access_token
    function getAccessToken($cacheFile, $appid, $appsecret) {
        
        // 如果缓存文件存在
        if (file_exists($cacheFile)) {
            
            // 读取这个文件
            $cacheFiledata = include($cacheFile);
            
            // 如果未过期
            if ($cacheFiledata['expire_time'] > time()) {
                
                // 返回当前access_token
                return $cacheFiledata['access_token'];
            }else {
                
                // 已过期
                // 生成access_token
                return createAccessToken($appid, $appsecret, $cacheFile);
            }
        } else {
            
            // 如果文件不存在
            // 生成access_token
            return createAccessToken($appid, $appsecret, $cacheFile);
        }
    }
    
    // 生成access_token
    function createAccessToken($appid, $appsecret, $cacheFile) {
        
        // 获取access_token
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $response = file_get_contents($url);
        $access_token_data = json_decode($response, true);
        
        // 获取获取到access_token
        if (isset($access_token_data['access_token'])) {
            
            // 设置2小时后过期
            $access_token_data['expire_time'] = time() + 7200;
            
            // 创建缓存文件
            $cacheFiledata = [
                'access_token' => $access_token_data['access_token'],
                'expire_time' => $access_token_data['expire_time']
            ];
            
            // 写入本地缓存文件
            file_put_contents($cacheFile, "<?php return " . var_export($cacheFiledata, true) . ";");
            
            // 返回当前生成的access_token
            return $access_token_data['access_token'];
        }
    }
    
    // 创建小程序码
    function creatQrcode($db, $access_token){
        
        // 请求接口创建小程序码
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token);
        curl_setopt($ch, CURLOPT_POST, true);
        
        // 生成scene（如果你要换算法，也只能生成纯数字的）
        $scene = rand(1000000,9999999);
        
        // 请求参数
        $data = array(
            "page" => "pages/index/index", // 小程序扫码页面的路径
            "scene" => $scene,
            "check_path" => false, // 是否验证你的路径是否正确
            "env_version" => "develop" // 开发的时候这个参数是develop，小程序审核通过发布上线之后改为release
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
    
        // 将数据写入本地文件并保存在qrcode目录
        file_put_contents('./qrcode/' . $scene . '.png', $result);
        curl_close($ch);
        
        // 向数据库插入一条生成小程序码的记录
        $creatQrcode = $db->set_table('scanlogin_loginAuth')->add(
            [
                'createTime' => time(),
                'scene' => $scene
            ]
        );
        
        // 创建成功
        if($creatQrcode){
        
            $result = array(
                'code' => 200,
                'msg' => '创建成功',
                'scene' => $scene,
                'qrcode' => $scene.'.png'
            );
            
        }else{
            
            // 创建失败
            $result = array(
                'code' => 201,
                'msg' => '创建失败'
            );
        }
        
        // 输出JSON数据
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    // 调用函数进行创建小程序码
    creatQrcode($db, $access_token);

?>