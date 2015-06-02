<meta charset="utf-8">
<script src="//yukimax.sinaapp.com/i/iTorr.m.js"></script>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="img">
	<input type="submit" value="上传">
</form>
<script>
$.onsubmit = function(F, cb) {
	F.onsubmit = function() {
		for (var i = 0, l = F.length, o, r = []; i < l; i++) {
			o = F[i];
			if (o.name) {
				if (o.required && (!o.value || (o.type == 'email' && !o.value.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)) || (o.type == 'url' && !o.value.match(/^http[s]?:\/\/([\w-]+\.)+[\w-]+([\w-.\/?%&=]*)?$/)))) {
					alert('请输入正确的' + o.title);
					o.focus();
					return false
				} else if(o.type == 'radio') {
					if (o.checked == 1)
						r.push(o.name + '=' + encodeURIComponent(o.value));
				}else
					r.push(o.name + '=' + encodeURIComponent(o.value));
			}

		}
		$.x(F.action, r.join('&'), cb)
		return false
	};
};
$('form').onsubmit = function(r) {
	var xhr = new XMLHttpRequest();
	if (xhr.upload) {
		xhr.upload.onprogress = function(e) {
			console.log('width:' + e.loaded / e.total * 100 + '%')
		}
	}
};
</script>
<?php
// 提取文件域内容名称，并判断
if(!empty($_FILES['img']['name'])){
	// 上传路径
	$path='../uploads/images';
	// 检查是否有该文件夹，如果没有就创建，并给予最高权限
	file_exists($path)||mkdir($path, 0700, true);
	// 允许上传的文件格式
	$tp=array('image/gif', 'image/pjpeg', 'image/jpeg');
	// 检查上传文件是否在允许上传的类型
	if(!in_array($_FILES['img']['type'], $tp)){
		echo '<script>alert(\'格式不对\');/*history.go(-1);*/</script>';
		exit();
	}
	$filetype=$_FILES['img']['type'];
	$type = '';
	switch($filetype){
		case 'image/jpeg':
			$type='.jpg';
			break;
		case 'image/jpg':
			$type = '.jpg';
			break;
		case 'image/pjpeg':
			$type='.jpg';
			break;
		case 'image/gif':
			$type='.gif';
			break;
	}
	if($_FILES['img']['name']){
		// 获取时间并赋值给变量
		$today=date('YmdHis', time());
		// 图片的完整路径
		$url=$path.'/'.$today.$type;
		// 图片名称
		$img=$today.$type;
		$flag=1;
	}
	if($flag){
		// 特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件
		$result=move_uploaded_file($_FILES['img']['tmp_name'], $url);
		echo '<img src="'.$url.'"><br><a href="?act=del&filename='.$url.'">删除</a>';
	}
}
if(isset($_GET['act']) || preg_match('/del/', $_GET['act'])){
	if(!isset($_GET['filename']) || !preg_match('/.*\.(jpg|bmp|gif|png|json|txt)/', $_GET['filename'])){
		exit('filename格式错误');
	}else{
		file_exists($_GET['finlename']) ? unlink($_GET['filename']) : exit('文件不存在');
	}
}
function my_scandir($dir){
	$files = array();
	$dir_list = scandir($dir);
	foreach($dir_list as $file){
		if($file!='..'&&$file!='.'){
			if(is_dir($dir.'/'.$file)){
				$files[$file]=my_scandir($dir.'/'.$file);
			}else{
				$files[]=$file;
			}
		}
	}
	return $files;
}
$files = array();
$files[]=my_scandir(getenv('OPENSHIFT_REPO_DIR').'uploads');
$files[]=my_scandir(getenv('OPENSHIFT_DATA_DIR'));
echo '<pre>';
print_r($files);
echo '</pre>';
