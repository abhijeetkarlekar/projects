<?php
        /**
         * Description: Curl request to set mobile call verification status
         */
        require_once CLIENTPATH.'Upload.php';
        $upload = new Upload;

        function get_service_info($get_param){
                global $upload;
                $api = 'http://vs.int.com:3000/vs/apis/service';
                $res = $upload->get_method($get_param,$api);
                $arrRes = json_decode($res,true);
                return $arrRes['result'];
        }

        function post_service_info($post_param){
                global $upload;
                $api = 'http://vs.int.com:3000/vs/apis/service';
                $res = $upload->post_method($post_param,$api);
                $arrRes = json_decode($res,true);
                return $arrRes['result'];       
        }

	function send_SMS($post_param){
                global $upload;
		$api = 'http://cms.int.india.com/sendSMS.php';
                $res = $upload->post_method($post_param,$api);
                $arrRes = json_decode($res,true);
                return $arrRes['result'];       
	}
	
	function post_self_verified($post_param){
		global $upload;
		$api = 'http://vs.int.com:3000/vs/apis/setSelfVerified';
                $res = $upload->post_method($post_param,$api);
                $arrRes = json_decode($res,true);
                return $arrRes['result'];		
	}
