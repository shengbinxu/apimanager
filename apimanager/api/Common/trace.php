<?php
/**
 * 结果输出
 */

function trace_success(){
	echo json_encode(array('errorCode' => 0));
	exit();
}

function trace_error($msg){
	//echo json_encode(array('errorCode' => 1, 'errorMsg' => $msg));
	echo json_encode(array('errorCode' => 1, 'errorMessage' => $msg));	
	exit();
}

function trace_result($result, $total=0){
	if ($total != 0){
		echo json_encode(array('result' => $result, 'totalCount' => $total, 'errorCode' => 0));
		exit;
	}
	echo json_encode(array('result' => $result, 'errorCode' => 0));
	exit();
}

function trace_empty(){
	echo json_encode(array('result' => array(), 'errorCode' => 0));
	exit();
}

