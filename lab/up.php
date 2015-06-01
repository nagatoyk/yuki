<?php
// 提取文件域内容名称，并判断
if(!empty($_FILES['img']['name'])):
	// 上传路径
	$path='uppic/';
	// 检查是否有该文件夹，如果没有就创建，并给予最高权限
	if(!file_exists($path)):
		mkdir($path, 0700);
	endif;
	// 允许上传的文件格式
	$tp=array('image/gif', 'image/pjpeg', 'image/jpeg');
	// 检查上传文件是否在允许上传的类型
	if(!in_array($_FILES['img']['type'], $tp));
		exit('<script>alert(\'格式不对\');/*history.go(-1);*/</script>');
	endif;
	$filetype=$_FILES['img']['type'];
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
	if($_FILES['img']['name']):
		// 获取时间并赋值给变量
		$today=date('YmdHis');
		// 图片的完整路径
		$file2=$path.$today.$type;
		// 图片名称
		$img=$today.$type;
		$flag=1;
	endif;
	if($flag):
		// 特别注意这里传递给move_uploaded_file的第一个参数为上传到服务器上的临时文件
		$result=move_uploaded_file($_FILES['img']['tmp_name'], $file2);
	endif;
else:
?>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="img">
	<input type="submit" value="上传">
</form>
<?php
endif;
