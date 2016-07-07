<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>温馨提示</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<script src="resource/hdjs/js/jquery.min.js"></script>
	<script src="resource/hdjs/app/util.js"></script>
	<script src="resource/hdjs/require.js"></script>
	<script src="resource/hdjs/app/config.js"></script>
	<link href="resource/hdjs/css/bootstrap.min.css" rel="stylesheet">
	<link href="resource/hdjs/css/font-awesome.min.css" rel="stylesheet">
</head>
<body style="background: url('{{__ROOT__}}/resource/images/bg.jpg');background-size: cover">
<!--导航-->
<nav class="navbar navbar-inverse" style="border-radius: 0px;">
	<div class="container-fluid">
		<div class="navbar-header">
			<ul class="nav navbar-nav">
				<li>
					<a href="http://bbs.houdunwang.com" target="_blank"><i class="fa fa-w fa-forumbee"></i> 后盾论坛</a>
				</li>
				<li>
					<a href="http://www.houdunwang.com" target="_blank"><i class="fa fa-w fa-phone-square"></i> 联系后盾</a>
				</li>
			</ul>
		</div>
	</div>
</nav>
<!--导航end-->

<div class="container-fluid">
	<h1>&nbsp;</h1>
	<div style="background: url('web/resource/images/logo.png') no-repeat;background-size: contain;height:60px;"></div>
	<br/>
	<div class="alert alert-info clearfix jumbotron" s>
		<br/>
		<div class="col-xs-2">
			<i class="fa fa-info-circle fa-5x"></i>
		</div>
		<div class="col-xs-10">
			<p>{{$message}}</p>

			<p>
				<a href="{{$sUrl}}" class="btn btn-primary" style="width: 80px;">是</a>&nbsp;
				<a href="{{$eUrl}}" class="btn btn-default" style="width: 80px;">否</a>
			</p>
			<p>
				[<a href="javascript:;" onclick="location.href=history.go(-1)" class="alert-link">点击返回上一页</a>]
				[<a href="{{__ROOT__}}" class="alert-link">首页</a>]
			</p>
		</div>
	</div>
</div>
</body>
</html>