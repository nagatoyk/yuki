<!DOCTYPE html>
<html>
<head>
    <title>新浪微博授权回调</title>
    <script type="text/javascript">
    if (window.opener) {
        window.opener.wb_check();
        window.close();
    } else {
        con = confirm('请手动关闭');
        if (con) {
            window.close();
        }
    }
    </script>
</head>
<body>
新浪微博授权回调
</body>
</html>