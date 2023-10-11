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

<img src="http://img10.360buyimg.com/imgzone/jfs/t1/43837/32/23508/101315/6526695dFbaa927d3/b29a153ca9c6fbf7.jpg" width="300" />

访问 **`createQrcode`** 目录即可生成一个小程序码。

![生成一个小程序码](https://img10.360buyimg.com/imgzone/jfs/t1/219772/40/35160/20329/6526695dF28d1bcc6/a901aa6d25ca41be.jpg)

**扫码后打开小程序**

![扫码后打开小程序](https://img10.360buyimg.com/imgzone/jfs/t1/198383/13/31335/11552/65266a67Fd651e0bd/04ea831a08f156bf.jpg)

此时，网页端也会有相应的变化，会立刻切换为已扫码。

![切换为已扫码](https://img10.360buyimg.com/imgzone/jfs/t1/100144/8/40511/15921/6526695dF3edeb10c/043fd766ec1836db.jpg)

当在小程序点击 **`授权登录`** 后，网页端会切换为 **`登录成功`**，如果你配置了 **`callback`**，登录成功后会自动跳转至 **`callback`** 并且携带 **`token`** 参数。

![已登录](https://img10.360buyimg.com/imgzone/jfs/t1/224793/36/8/15732/6526695dFd6bbbefa/9fe8311d55a52b68.jpg)

在线体验
---
https://likeyunkeji.likeyunba.com/likeyunkeji_minipro/createQrcode/

作者
---
TANKING
