<!DOCTYPE html>
<html>
<head>
    <title>萌否授权回调</title>
    <script type="text/javascript">
        if (window.location.href.indexOf('verifier') >= 0) {
            var verifier = window.location.href.match(/verifier=(\w{10})/)[1];
            if (window.opener) {
                window.opener.document.getElementsByClassName('login_verifier')[0].value = verifier;
                window.opener.document.getElementsByClassName('login_confirm_last')[0].click();
                window.close();
            } else {
                prompt('请复制下面的验证码并贴入播放页指定文本框以完成验证', verifier);
                window.close();
            }
        }
    </script>
</head>
<body>
萌否授权回调
</body>
</html>