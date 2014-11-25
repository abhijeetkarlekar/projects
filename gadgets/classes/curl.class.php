<?php
	/**
	* @brief class curl.This class is use to get and set http request data using curl.
	* @author rajesh.
	* @version 1.0
	* @created 10-06-2009.
	*/
	class curl{
		/**Initialize the constructor.*/
		function curl(){
		}
		/**
		* @note function used to create encoded querystring.
		* @param array request_param is an associate array.
		* @pre array must be non-empty associative array.
		* @post string querystring.
		* return string.
		*/
        function returnQueryString($request_param){
            $queryStr = "";
            foreach($request_param as $param=>$value){
                if(strlen($queryStr)>0){
                    $queryStr .= '&';
                }
                $queryStr .= urlencode($param).'='.urlencode($value);
            }
            return $queryStr;
        }
		/**
		* @note function used to post the data to the http request.
		* @param string url.
		* @param string postString.
		* @param string defaultCharset.
		* @pre url,postString,defaultCharset must be non-empty string.
		* @post string html/header/cookies.
		* return string.
		*/
        function httpPost($url,$postString=null,$defaultCharset='iso-8859-1'){
            return $this->httpRequest($url,true,$postString,$defaultCharset);
        }
		/**
		* @note function used to get the data from the http request.
		* @param string url.
		* @param string postString.
		* @param string defaultCharset.
		* @pre url,postString,defaultCharset must be non-empty string.
		* @post string html/header/cookies.
		* return string.
		*/
        function httpGet($url,$postString=null,$defaultCharset='iso-8859-1'){
             return $this->httpRequest($url,false,$postString,$defaultCharset);
        }
		/**
		* @note function used to perform curl action.
		* @param string url.
		* @param string postString.
		* @param boolean ispost(1-post,0-get)
		* @param string defaultCharset.
		* @pre url,postStr must be non-empty string.
		* @post associative array.
		* return an associative array.
		*/
		function httpRequest($url,$ispost=false,$postString=null,$defaultCharset='iso-8859-1'){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL,$url);
			//curl_setopt($curl, CURLOPT_REFERER, true);

			/*Start code for http authenticaton.*/
				//$server_user = SERVER_USER;
				//$server_pass = SERVER_PASSWORD;
			        $server_user = demo;
				$server_pass = demo;
				
				curl_setopt($curl, CURLOPT_USERPWD, "$server_user:$server_pass");
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			/*End code for http authenticaton.*/

			if($ispost == true){
				curl_setopt($curl,CURLOPT_POST,1);
				curl_setopt($curl, CURLOPT_HEADER, true);
			}else{
				curl_setopt($curl,CURLOPT_HTTPGET,1);
			}
			if(!empty($postString)){
				curl_setopt($curl,CURLOPT_POSTFIELDS,$postString);
			}

			curl_setopt ($curl, CURLOPT_COOKIE,"testcookie=testvalue");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$res = curl_exec($curl);
			return $res;
		}
		/**
	    * @note Convert an object to an array.
	    * @param object $object The object to convert.
	    * return array.
	    */
	    function objectToArray($object){
			$obj = new stdClass();
	        if( !is_object($object) && !is_array( $object )){
	            return $object;
			}
			if(is_object( $object )){
				$object = get_object_vars( $object );
			}
			return array_map(array("curl","objectToArray"),$object);
		}
	}
