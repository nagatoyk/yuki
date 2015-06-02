<!DOCTYPE html>
<meta charset="utf-8">
<script src="//yukimax.sinaapp.com/i/iTorr.m.js"></script>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="img">
	<input type="button" value="上传">
</form>
<?php
if(!empty($_FILES['img']['name'])){
	$path='../uploads/images';
	file_exists($path)||mkdir($path, 0700, true);
	$tp=array('image/gif', 'image/pjpeg', 'image/jpeg');
	if(!in_array($_FILES['img']['type'], $tp)){
		echo '<script>alert(\'类型错误\');/*history.go(-1);*/</script>';
		exit();
	}
	$filetype=$_FILES['img']['type'];
	$type='';
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
		$today=date('YmdHis', time());
		$url=$path.'/'.$today.$type;
		$img=$today.$type;
		$flag=1;
	}
	if($flag){
		$result=move_uploaded_file($_FILES['img']['tmp_name'], $url);
		if($result !== false){
			echo '<img src="'.$url.'"><br><a href="?act=del&filename='.$url.'">删除</a>';
		}
	｝
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
