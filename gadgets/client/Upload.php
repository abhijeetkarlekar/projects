<?php
	require_once(UPLOAD_CLIENT_PATH.'UploadClient.php');
	/**
	* @brief class upload client.This class is use to get and set http request data to upload file system.
	* @author Rajesh Ujade.
	* @version 1.0
	* @created 26-11-2010.
	*/
	class Upload extends UploadClient{
		/**Initialize the constructor.*/
		function Upload(){
		}
		/**
		* @note function is used to post the content and upload file.
		* @param associative array $post_param.
		* @pre associative $post_param['file'] key and value is required.
		* @post upload response in array.
		* return array.
		*/
		function upload_method($post_param,$url=""){

			if(empty($post_param["file"])){
				return '0';
			}
			if(!empty($post_param['img_size'])){
				$post_param['img_size'] = serialize($post_param['img_size']);
			}
			if(!empty($post_param['img_count'])){
				$post_param['img_count'] = serialize($post_param['img_count']);
			}
			$post_param["file"] = '@'.str_replace('@','',$post_param["file"]);
			#echo UPLOAD_BASE_URL."====".$post_param;
			#print_r($post_param);
			if(empty($url)){
				$res = $this->httpPost(UPLOAD_BASE_URL,$post_param);
			}else{
				$res = $this->httpPost($url,$post_param);
			}
			#echo $res;
			return unserialize($res);
		}
		/**
		* @note function is used to post the content.Internal it used the form post method.
		* @param associative array $post_param.
		* @pre $post_param be valid,non-empty associative array.
		* @post upload response.
		* return response.
		*/
		function post_method($post_param,$url=""){
			$postStr = $this->returnQueryString($post_param);
			if(empty($url)){
	                	$res = $this->httpPost(UPLOAD_BASE_URL,$postStr);
			}else{
				$res = $this->httpPost($url,$postStr);
			}
        	       return $res;
	        }
		/**
		* @note function is used to get the content.Internal it used the form get method.
		* @param associative array $get_param.
		* @pre $post_param be valid,non-empty associative array.
		* @post upload response.
		* return response.
		*/
		function get_method($get_param,$url=""){
			/*
			$getStr = $this->returnQueryString($get_param);
			if(empty($url)){
		                $res = $this->httpGet(UPLOAD_BASE_URL,$getStr);
			}else{
			    $res = $this->httpGet($url,$getStr);
			}
			return $res;
			*/
			$getStr = $this->returnQueryString($get_param);
			$url = $url.'?'.$getStr;
			if(empty($url)){
		                $res = $this->httpGet(UPLOAD_BASE_URL);
			}else{
			    $res = $this->httpGet($url);
			}
			return $res;
		}
	}
?>
