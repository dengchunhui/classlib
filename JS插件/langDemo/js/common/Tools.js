var Tools = {
    /**
     * 动态加载js文件
     * @param loadUrl 要加载的js路径
     * @param callback 加载好之后的回调函数
     */
    loadJs: function (loadUrl, callback) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        if (typeof(callback) != "undefined") {
            if (script.readyState) {
                script.onreadystatechange = function () {
                    if (script.readyState == "loaded" || script.readyState == "complete") {
                        script.onreadystatechange = null;
                        callback();
                    }
                };
            } else {
                script.onload = function () {
                    callback();
                };
            }
        }
        script.src = loadUrl;
        document.body.appendChild(script);
    },
    /**
     * 接收url里的参数
     * @param name url中的参数值
     */
    getQueryString: function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return unescape(r[2]);
        }
        return null;
    },
};