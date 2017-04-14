<?php
//构成IN(*)
function comma_numeric($data, $field=''){
	if ($field != ''){
		foreach ($data as $v){
			$str .= "'".$v[$field]."',";
		}
	}elseif ($field == ''){
		foreach ($data as $v){
			$str .= "'".$v."',";
		}
	}
	$str = substr($str, 0, -1);
	return $str;
}

//分页函数
function page_request($page, $row){
	if ($page > 0){
		$page = $page - 1;
	}else{
		$page = 0;
	}
	if (!$row) $row = 15;
		
	$page = $page * $row;
	return array('page' => $page, 'row' => $row);
}

/*
 * 上传一张图片，覆盖同名文件
 */
function upload_one_image($file, $savePath=''){
	import("ORG.Net.UploadFile");
	if ($savePath == ''){
		$savePath = './uploads/'.date('Ym', time()).'/';
	}
	$file_config = array(
		'maxSize' => 1048576,
		'allowTypes' => array('image/png', 'image/jpeg', 'image/gif','image/jpg'),
		'savePath' => $savePath,
		'uploadReplace' => true
	);
	
	$UploadFile = new UploadFile($file_config);
	$fileInfo = $UploadFile->uploadOne($file);
	if ($fileInfo){
		return $fileInfo[0]['savepath'].$fileInfo[0]['savename'];	
	}
	return false;
}

/*
 * 汉字转拼音
 */
function to_pinyin($word){
	import("ORG.Util.Pinyin");
	$py = new PinYin();
	$pinyin = $py->getAllPY($word);
	return $pinyin;
}