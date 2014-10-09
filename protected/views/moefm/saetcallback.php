<!DOCTYPE html>
<html>
<head>
    <title>新浪微博授权回调</title>
    <script type="text/javascript">
        if (window.location.href.indexOf('code') >= 0) {
            if (window.opener) {
                window.opener.wb_check();
                alert('3秒后关闭窗口');
                setTimeout(window.close, 3000)
            } else {
                alert('请手动刷新')
            }
        }
    </script>
</head>
<body>
新浪微博授权回调
</body>
</html>