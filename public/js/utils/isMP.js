function isWeChatApplet() {
    const ua = window.navigator.userAgent.toLowerCase();
    return new Promise((resolve) => {
        if (ua.indexOf('micromessenger') == -1) {//不在微信或者小程序中
            resolve(false);
        } else {
            wx.miniProgram.getEnv((res) => {
                if (res.miniprogram) {//在小程序中
                    resolve(true);
                } else {//在微信中
                    resolve(false);
                }
            });
        }
    })
}
