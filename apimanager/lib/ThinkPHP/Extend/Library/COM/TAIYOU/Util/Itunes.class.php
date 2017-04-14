<?php
/**
 * apple Itunes相关类
 * 
 * @since 2013-12-04
 */

import("ORG.Net.Http");

class Itunes {
	
	protected $itunesid;
	
	protected $appData = ''; //obj
	
	protected $getType = 'api'; //获取信息方法，API调用(api)、网页抓取(crawl)。默认api
	
	protected $remote_success = false; //获取数据是否成功
	
	public function __construct($itunesid = ''){
		$this->itunesid = (int)$itunesid;
		$this->lookup();
	}
	
	/**
	 * 调用itunes lookup获取应用信息
	 * @return json
	 */
	public function lookup(){
		$url = C('ITUNES_LOOKUP_API').$this->itunesid.C('ITUNES_LOOKUP_API_PARAMS');
		$data_json = file_get_contents($url);
		if ($data_json){
			$data_obj = json_decode($data_json);
			$this->appData = $data_obj->results[0];
			$this->remote_success = true;	
		}
	}
	
	/**
	 * 抓取itunes页面获取应用信息
	 * @return json
	 */
	public function crawl(){
		//...
	}
	
	/**
	 * 获取应用截图(iphone)
	 * @param $local 图片保存位置
	 * @return json
	 */
	public function screenshotUrls($local=''){
		if (!is_dir($local)){
    		mkdir($local, 0777, true);
    	}
		foreach ($this->appData->screenshotUrls as $k => $v){
			/* 1136x1136
			 * 图片格式 .jpeg
			 */
			$pic[$k]['url'] = $v;
			
			//if (Http::isExistRemoteFile($v)){
				if (stristr($v, "1136x1136")){
					//竖版
					//$pic[$k]['url'] = str_replace("1136x1136", "568x568", $v);
					$pic[$k]['url'] = $v;
					$pic[$k]['pos'] = "v";
					$pic[$k]['size'] = "640x1136";	
				}elseif (stristr($v, "320x320")){
					//横版
					$pic[$k]['url'] = str_replace("320x320", "1136x1136", $v);
					$pic[$k]['pos'] = "h";
					$pic[$k]['size'] = "1136x640";
				}
			//}
			/* 不兼容5的游戏图片暂不考虑
			else{
				//4 横版
				$pic[$k]['url'] = str_replace("320x320", "480x480", $v);
				$pic[$k]['size'] = "480x480";
				//4 竖版...
			}
			*/
			
		}
		//下载图片并保存
		foreach ($pic as $k => $v){
			Http::curlDownload($v['url'], $local.$k.'_'.$v['size'].'_'.$v['pos'].'.jpeg');
			$result[$k]['local'] = substr($local.$k.'_'.$v['size'].'_'.$v['pos'].'.jpeg', 1);
			$result[$k]['pos'] = $v['pos'];
		}
		return $result;
	}
	
	/**
	 * 获取应用截图(ipad)
	 * @param $local 图片保存位置
	 * @return json
	 */
	public function ipadScreenshotUrls($local=''){
		
	}
	
	/**
	 * 获取应用名称
	 * @return String
	 */
	public function appname(){
		return $this->appData->trackName;
	}
	
	/**
	 * 获取应用描述
	 * @return String
	 */
	public function description(){
		return $this->appData->description;
	}
	
	/**
	 * 获取应用分类标签
	 * @return json
	 */
	public function genres(){
		return json_encode($this->appData->genres);
	}
	
	/**
	 * 获取价格类型
	 * @return String
	 */
	public function formattedPrice(){
		return $this->appData->formattedPrice;
	}
	
	/**
	 * 获取价格
	 * @return float
	 */
	public function price(){
		return $this->appData->price;
	}
	
	/**
	 * 获取应用大小
	 * @return int
	 */
	public function fileSize(){
		//to MB
		//$fileSize = byte_format($this->appData->fileSizeBytes);
		
		//iTunes非标准计算而采用整除并四舍五入 单位MB
		$fileSize = round($this->appData->fileSizeBytes / 1000000, 1);
		return $fileSize;
	}
	
	/**
	 * 获取应用支持的设备列表
	 * @return json
	 */
	public function supportedDevices(){
		return json_encode($this->appData->supportedDevices);
	}
	
	/**
	 * 获取应用评级
	 * @return String
	 */
	public function trackContentRating(){
		return $this->appData->trackContentRating;
	}
	
	/**
	 * 获取应用评分
	 * @return float
	 */
	public function averageUserRating(){
		return $this->appData->averageUserRating;
	}
	
	public function remote_success(){
		 return $this->remote_success;
	}
	
}

