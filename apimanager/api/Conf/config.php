<?php
return array(

	'LOAD_EXT_CONFIG' => 'db,ex,status',

	//url router
	'URL_MODEL' => 2,
	'URL_CASE_INSENSITIVE' =>true,
	'URL_HTML_SUFFIX' => '',

	//debug trace
	//'SHOW_PAGE_TRACE'=>true,

	//cache
	
	//function
	"LOAD_EXT_FILE"=>"trace,verify",

	"PIC_BASE_URL" => "http://img.itaiyou.cn",
	'LAYOUT_ON' => true,
	/**
	 * filter
	 * 无过滤 方便保存但安全性较差
	 */
	//_get() _post()
	'DEFAULT_FILTER' => '',
	//_GET[] _POST[]
	'VAR_FILTERS' => '',
 );
?>
