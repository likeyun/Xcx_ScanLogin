// 获取应用实例
const app = getApp()

// 获取服务器域名和目录名
const domain = app.domain.url;
const dirName = app.dirName.dir;

Page({
	data: {
		scanStep: 1
	},

	// 获取扫码结果
	onLoad(options) {
		
		const that = this;
		if(options !== undefined) {
			if(options.scene) {

				// 获取scene
				let scene = decodeURIComponent(options.scene);

				wx.showLoading({
					title: '加载中'
				})

				// 验证scene是否存在
				wx.request({
					url: 'https://' + domain + dirName + 'checkScene/?scene=' + scene,
					header: {
						'content-type': 'application/json'
					},
					success (res) {

						// 输出验证结果
						console.log(res.data)

						// 存在
						if(res.data.code == 200) {

							// 微信登录
							wx.login({
								success (res) {
									if (res.code) {
										wx.request({
											url: 'https://' + domain + dirName + 'getOpenid/?code=' + res.code + '&scene=' + scene,
											header: {
												"content-type": "application/json"
											},
											success (res) {
												
												// 成功获取到Openid
												if(res.data.code == 200) {

													// 切换至授权界面
													that.setData({
														scanStep: 2,
														sceneCode: scene
													})
												}else {

													// 获取失败
													that.setData({
														scanStep: 3,
														loginSuccess: false,
														errorMsg: res.data.msg
													})
												}
											}
										});
									}
								}
							});
						}
						wx.hideLoading();
					}
				})
			}
		}
	},

	// 点击授权登录
	loginAuth() {
		const that = this;
		wx.showNavigationBarLoading();
		wx.request({
			url: 'https://' + domain + dirName + 'loginAuth/?scene=' + that.data.sceneCode,
			header: {
				'content-type': 'application/json'
			},
			success (res) {
				if(res.data.code == 200) {

					// 切换至授权结果
					that.setData({
						scanStep: 3,
						loginSuccess: true
					})

					wx.hideNavigationBarLoading();
				}else{

					that.setData({
						scanStep: 3,
						loginSuccess: false,
						errorMsg: res.data.msg
					})
				}
			}
		})
	},

	// 取消授权
	cancelAuth() {
		const that = this;
		wx.request({
			url: 'https://' + domain + dirName + 'cancelAuth/?scene=' + that.data.sceneCode,
			header: {
				'content-type': 'application/json'
			},
			success (res) {
				that.setData({
					scanStep: 3,
					loginSuccess: false,
					errorMsg: res.data.msg
				})
			}
		})
	}
})