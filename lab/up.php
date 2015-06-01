<form method="post" enctype="multipart/form-data">
	<input type="file" name="img">
	<input type="submit" value="上传">
</form>
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
	/*$files=array();
	if($handle=opendir($dir)){
		while(($file = readdir($handle))!==false){
			if(is_dir($dir.'/'.$file)&&$file!='..'&&$file!='.'){
				$files[$file]=my_scandir($dir.'/'.$file);
			}else{
				$files[]=$file;
			}
		}
		closedir($handle);
		return $files;
	}*/
}
$files=my_scandir(getenv('OPENSHIFT_DATA_DIR'));
echo getenv('OPENSHIFT_DATA_DIR');
echo '<pre>';
print_r($files);
echo '</pre>';
