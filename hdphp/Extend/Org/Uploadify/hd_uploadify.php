<?php
if (!defined("HDPHP_PATH"))exit('No direct script access allowed');
// .-----------------------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |   Version: 2013.01
// |      Site: http://www.hdphp.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://houdunwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------
header("Content-Type: text/html; charset=utf-8");
//删除图片 
if (ACTION == "hd_uploadify_del") {
    $files = array_filter(explode("@@", $_POST['file']));
    foreach ($files as $f) {
        is_file($f) && @unlink($f);
    }
    echo 1;
    exit;
}
$data = array();
$upload = new upload(Q('upload_dir'), array(), Q('fileSizeLimit'));
$file = $upload->upload();
if ($file) {
    $file = $file[0];
    $data['status'] = 1;
    $data['url'] = $file['url'];
    $data['path'] = $file['path'];
    $data['name'] = $file['filename'];
    //加水印
    if ($file['image'] && Q('water')) {
        $img = new Image();
        $img->water($file['path']);
    }
} else {
    $data['status'] = 0;
    $data['message'] = $upload->error;
}
echo json_encode($data);
exit;
