# 微信小程序实现的网页扫码授权登录

微信小程序实现的网页扫码授权登录，无论是个人小程序还是企业小程序，都可以调用wx.login接口获取到openid实现微信鉴权快速扫码登录！

摘要
---
现如今，扫码登录已经在网站普遍使用，其中微信扫码登录极其普遍。但是微信扫码登录的实现方法有多重，大多数都是具有一些门槛的，例如企业、需要服务号、需要认证等，这些都是个人开发者，个人创作者无法使用的。

但其实，微信小程序也可以作为一个授权登录的“中介”，因为微信小程序有一个 **`wx.login`** 的API可以轻松实现获取openid来作为微信用户的鉴权。

实现
---
调用微信小程序生成葵花码的API生成一个带有 **`scene`** 参数的小程序码，其中 **`scene`** 作为小程序码的唯一参数，用于记录和进行本次扫码登录。

以下是服务端的代码结构：

<img src="http://img10.360buyimg.com/imgzone/jfs/t1/43837/32/23508/101315/6526695dFbaa927d3/b29a153ca9c6fbf7.jpg" width="500" />

访问 **`createQrcode`** 目录即可生成一个小程序码。

![生成一个小程序码](https://img10.360buyimg.com/imgzone/jfs/t1/219772/40/35160/20329/6526695dF28d1bcc6/a901aa6d25ca41be.jpg)

**扫码后打开小程序**

![扫码后打开小程序](https://img10.360buyimg.com/imgzone/jfs/t1/198383/13/31335/11552/65266a67Fd651e0bd/04ea831a08f156bf.jpg)

此时，网页端也会有相应的变化，会立刻切换为已扫码。

![切换为已扫码](https://img10.360buyimg.com/imgzone/jfs/t1/100144/8/40511/15921/6526695dF3edeb10c/043fd766ec1836db.jpg)

当在小程序点击 **`授权登录`** 后，网页端会切换为 **`登录成功`**，如果你配置了 **`callback`**，登录成功后会自动跳转至 **`callback`** 并且携带 **`token`** 参数。

![已登录](https://img10.360buyimg.com/imgzone/jfs/t1/224793/36/8/15732/6526695dFd6bbbefa/9fe8311d55a52b68.jpg)

# 程序逻辑

具体逻辑如下：<br/><br/>
![程序逻辑](https://img10.360buyimg.com/imgzone/jfs/t1/168191/28/33813/139647/65266affF3816d7df/f433003bc13bc64b.png)

# 配置

只需要配置 **`Db.php`** 这个文件里面的一些参数。<br/><br/>

```
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
```

**数据库创建：**

直接在SQL执行的窗口粘贴:
```
CREATE TABLE `scanlogin_loginAuth` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `scene` varchar(32) NOT NULL,
  `openid` varchar(32) DEFAULT NULL,
  `createTime` varchar(32) DEFAULT NULL,
  `authTime` varchar(32) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `expire` int(1) NOT NULL DEFAULT '1',
  `token` varchar(32) DEFAULT NULL COMMENT '登录成功的Token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

**以phpMyAdmin操作为例：**

![phpMyAdmin](https://img10.360buyimg.com/imgzone/jfs/t1/189338/34/38528/55535/65267070F59c28ec8/e70cc6b7cec49bdc.jpg)

微信小程序配置及发布
---
下载小程序端的代码，在微信开发者工具导入项目，打开 **`project.config.json`** 修改 **`appid`** 为你的小程序后进行编译。

![小程序端的代码](https://img10.360buyimg.com/imgzone/jfs/t1/151123/24/39070/23735/65267751F0e1c2276/84198275d9b3d71e.jpg)

在 **`app.js`** 修改你的服务器域名以及后端服务所在的目录名称，如果是根目录，只需输入一个 / ，如果是二级目录，输入 /目录名/ ，三级目录输入 /二级目录名/三级目录名/ ，其它同理。域名无需携带 http或https

![小程序端的代码](https://img10.360buyimg.com/imgzone/jfs/t1/164943/11/39242/25711/65267794F16c8c03d/ea8218ed67f6a2f5.jpg)

**你所配置的服务器域名必须要在微信小程序管理后台的 开发管理->开发设置->服务器域名->request合法域名进行配置后，并且需要备案，开启https访问，才能生效。**

小程序发布之后，微信扫一扫，扫描网页生成的小程序码，才能正常使用。

使用
---
假设你的服务器域名是 www.qq.com <br/>
后端代码部署在二级目录，目录名称是：xcxScanLogin <br/>
那么你可以直接在你的网页通过a标签跳转至： <br/>
```
www.qq.com/xcxScanLogin/createQcode
```
即可打开扫码页面。 <br/>
如果你需要进行回调，那么可以直接在Url后面加入callback <br/>
假设你的callback页面是：https://www.qq.com/call/
```
www.qq.com/xcxScanLogin/createQcode/?callback=https://www.qq.com/call/
```
扫码登录授权成功后，将会跳转到callback地址并携带token参数。 <br/>
例如：
```
https://www.qq.com/call/?token=xxxxxxxxxxxx
```
这个token参数会被记录在 scanlogin_loginAuth 这个表的 token字段。 <br/>

如果你需要在你网站实现自己的页面，那么可以在你的网站通过异步请求：
```
www.qq.com/xcxScanLogin/createQcode/createQcode.php
```
将会返回JSON格式的数据：
```
{'':200,'msg' => '创建成功','scene' => 'xxxxxxx','qrcode' => 'xxxxxx.png'}
```
注意：'qrcode' => 'xxxxxx.png' 真实小程序码地址是 qrcode 目录里面的 xxxxxx.png ，即需要加上目录名才可以正常在页面显示小程序码。 <br/>

在线体验
---
https://likeyunkeji.likeyunba.com/likeyunkeji_minipro/createQrcode/

作者
---
TANKING
