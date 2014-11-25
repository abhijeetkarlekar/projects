<?php
	/**
	* @brief collection of utility functions.
	* @author Rajesh Ujade
	* @version 1.0
	* @created 23-Nov-2010 5:09:31 PM
	* @last updated on 08-Mar-2011 13:14:00 PM
	*/

	/**
	* function used to get dates, months, and years.
	* return array
	*/
	function arrGetDateMonthYearDate(){
		$currentmonth = date('m');
		$currentdate = date('d');
	        $currentYear = date('Y');
        	$currentHour = date('H');
	        $currentMinute = date('i');
		$result['currentdata']['current_month'] = $currentmonth;
		$result['currentdata']['current_date'] = $currentdate;
		$result['currentdata']['current_year'] = $currentYear;
		$result['currentdata']['current_hour'] = $currentHour;
		$result['currentdata']['current_minute'] = $currentMinute;
		for($m=1;$m<=12;$m++){
			$month   = date("m", mktime(0, 0, 0, $m, 1, 0));
			$monthname = date("F",mktime(0, 0, 0, $m, 1, 0));
			$result['month'][$monthname] = $month;
		}


		for($y=$currentYear-50;$y<=$currentYear;$y++){
			$result['year'][] = $y;
		}
		for($d=1;$d<=31;$d++){
			$result['date'][$d] = $d;
        }
        for($h=0;$h<24;$h++){
            if($h < 10){
                $h = '0'.$h;
            }
            $result['hour'][$h] = $h;
        }
        for($m=0;$m<60;$m++){
            if($m < 10){
                $m = '0'.$m;
            }
            $result['minute'][$m] = $m;
        }
		return $result;
	}
	/**
	* @note function is used to translate special characters into html format.
	* @param string $string.
	* @pre $string must be non-empty valid string.
	* @post string
	* return string.
	*/
	function translatechars($string) {

		$arrCharSetISO8859 =
			array('�'=>'&#192;','�'=>'&#193;','�'=>'&#194;','�'=>'&#195;','�'=>'&#196;','�'=>'&#197;','�'=>'&#198;','�'=>'&#199;','�'=>'&#200;','�'=>'&#201;','�'=>'&#202;','�'=>'&#203;','�'=>'&#204;','�'=>'&#205;','�'=>'&#206;','�'=>'&#207;','�'=>'&#208;','�'=>'&#209;','�'=>'&#210;','�'=>'&#211;','�'=>'&#212;','�'=>'&#213;','�'=>'&#214;','�'=>'&#216;','�'=>'&#217;','�'=>'&#218;','�'=>'&#219;','�'=>'&#220;','�'=>'&#221;','�'=>'&#222;','�'=>'&#223;','�'=>'&#224;','�'=>'&#225;','�'=>'&#226;','�'=>'&#227;','�'=>'&#228;','�'=>'&#229;','�'=>'&#230;','�'=>'&#231;','�'=>'&#232;','�'=>'&#233;','�'=>'&#234;','�'=>'&#235;','�'=>'&#236;','�'=>'&#237;','�'=>'&#238;','�'=>'&#239;','�'=>'&#240;','�'=>'&#241;','�'=>'&#242;','�'=>'&#243;','�'=>'&#244;','�'=>'&#245;','�'=>'&#246;','�'=>'&#248;','�'=>'&#249;','�'=>'&#250;','�'=>'&#251;','�'=>'&#252;','�'=>'&#253;','�'=>'&#254;','�'=>'&#255;'
		);

		$arrSymbSetISO8859 =
			array('�'=>'&#161;','�'=>'&#162;','�'=>'&#163;','�'=>'&#164;','�'=>'&#165;','�'=>'&#166;','�'=>'&#167;','�'=>'&#168;','�'=>'&#169;','�'=>'&#170;','�'=>'&#171;','�'=>'&#172;','�'=>'&#174;','�'=>'&#175;','�'=>'&#176;','�'=>'&#177;','�'=>'&#178;','�'=>'&#179;','�'=>'&#180;','�'=>'&#181;','�'=>'&#182;','�'=>'&#183;','�'=>'&#184;','�'=>'&#185;','�'=>'&#186;','�'=>'&#187;','�'=>'&#188;','�'=>'&#189;','�'=>'&#190;','�'=>'&#191;','�'=>'&#215;','�'=>'&#247;'
		);


		//following symbols are not supported
		//&#8242; &#8243; &#8254; &#8364; &#8592; &#8593; &#8594; &#8595; &#8596; &#8968; &#8969; &#8970; &#8971; &#9674; &#9824; &#9827; &#9829; &#9830;
		$arrOtherCharSet = array('�'=>'&#338;','�'=>'&#339;','�'=>'&#352;','�'=>'&#353;','�'=>'&#376;','�'=>'&#402;','�'=>'&#710;','�'=>'&#732;','�'=>'&#8211;','�'=>'&#8212;','�'=>'&#8216;','�'=>'&#8217;','�'=>'&#8218;','�'=>'&#8220;','�'=>'&#8221;','�'=>'&#8222;','�'=>'&#8224;','�'=>'&#8225;','�'=>'&#8226;','�'=>'&#8230;','�'=>'&#8240;','�'=>'&#8249;','�'=>'&#8250;','�'=>'&#8364;','�'=>'&#8482;'
		);

		//Following Maths Symbols not supported
		// &#8704; to &#8901;

		//Following Greek Letters not supported
		// &#913; to &#982;


		$charsetArr = array_merge($arrCharSetISO8859,$arrSymbSetISO8859,$arrOtherCharSet);
		return strtr($string,$charsetArr);

	}
	/**
         * @note function is used to update audio/video into the database.
         * @param an integer $media_id.
         * @param an integer $upload_media_id.
         * @param a string $table_name.
         * @param a string $default_img_path.
	 * @post array with uploaded data details.
         * retun an array.
         */
	function arrUpdateAudioVideo($media_id,$upload_media_id,$table_name,$default_img_path="",$language_video_id=""){
		if(empty($media_id)){ return false;}
		require_once(CLASSPATH.'article.class.php');
		require_once(CLASSPATH.'reviews.class.php');
		require_once(CLASSPATH.'videos.class.php');
		require_once(UPLOAD_CLIENT_PATH.'Upload.php');
		$upload = new Upload;
		$article = new article;
		$reviews = new reviews;
		$videoGallery = new videos();

		$post_param = array("service_name"=>SERVICE,"service_id"=>SERVICEID,"action"=>"api","media_id"=>$media_id,"type"=>"array");
		//	print_r($post_param);
		$res = unserialize($upload->get_method($post_param,CENTRAL_API_SERVER));
		$central_media_path = $res[0]['media_path'];
		$img_path = $res[0]['img_path'][0];
			//	print "<pre>"; print_r($res);exit;
		if(!empty($central_media_path)){

			if(empty($default_img_path) && !empty($img_path)){
				$request_param['video_img_path'] = $img_path;
				$result['video_img_path'] = $img_path;
			}


			$request_param['media_path'] = $central_media_path;
			$request_param['is_media_process'] = 1;
			if($table_name == 'UPLOAD_MEDIA_ARTICLE' or $table_name == 'UPLOAD_MEDIA_NEWS'){
				$request_param['upload_media_id'] = $upload_media_id;
				$iProdArtMediaId = $article->addUpdArticleDetails($request_param,$table_name);
			}elseif($table_name == 'UPLOAD_MEDIA_REVIEWS'){
				$request_param['upload_media_id'] = $upload_media_id;
				$iProdArtMediaId = $reviews->addUpdReviewsDetails($request_param,$table_name);
			}elseif($table_name == 'VIDEO_GALLERY'){
				$request_param['video_id'] = $upload_media_id;

				$iProdArtMediaId = $videoGallery->addUpdVideosDetails($request_param,$table_name);
			}elseif($table_name == 'LANGUAGE_VIDEO_GALLERY'){
				$request_param['media_id'] = $media_id;
				$iProdArtMediaId = $videoGallery->boolUpdateLanguageVideosDetails($request_param,$table_name,$language_video_id);
			}
			$result['media_path'] = $central_media_path;
		}
		//print "<pre>";print_r($result);
		return $result;
	}
	/**
         * @note function is used to remove slashes from string.
         * @param is a string $str
         * @post string with slashes removed.
         * retun a string.
         */
	function removeSlashes($str){
		$str = explode('\\',$str);
		$str = implode("",$str);
		return $str;
	}
	/**
         * @note function is used to evaluate a mathematical string.
         * @param is a string $mathStr
         * retun an integer.
         */
	function parse_mathematical_string($mathStr){
		$total = 0;
		eval("\$total=" .$mathStr. ";");
		return $total;
	}
	$multikey="";
	/**
	* @note function is used to sort an array.
	* @param is an array $array
	* retun an sorted array in descending order.
	*/
	function multi_sort_descending($array){
		usort($array, "compare_descending");
		return $array;
	 }
	 /**
	 * @note function is used to compare array element.It is used in multi_sort method as mentioned above.
	 * @param integer array key $a.
	 * @param integer array key $b.
	 * @pre $a and $b must be valid array keys.
	 * @post boolean true/false.
	 * return boolean/string.
	 * @author Rajesh Ujade.
	 * @created 23-Nov-2010
	 */
	 function compare_descending($a, $b)
	 {
			global $multikey;
	//        return strcmp($b[$key],$a[$key]);
			return ($a[$multikey] > $b[$multikey]) ? -1 : 1;
	 }
	 /**
         * @note function is used to sort an array.
         * @param is an array $array
         * retun an sorted array in ascending order.
         */

	 function multi_sort_ascending($array){
			usort($array, "compare_ascending");
	//array_walk($result, create_function('&$val,$key','$val=" $key=\'$val\'";')); // create new array.
			return $array;
	 }
	 /**
	 * @note function is used to compare array element.It is used in multi_sort method as mentioned above.
	 * @param integer array key $a.
	 * @param integer array key $b.
	 * @pre $a and $b must be valid array keys.
	 * @post boolean true/false.
	 * return boolean/string.
	 * @author Rajesh Ujade.
	 * @created 23-Nov-2010
	 */
	 function compare_ascending($a, $b)
	 {
			global $multikey;
	//        return strcmp($b[$key],$a[$key]);
			return ($a[$multikey] < $b[$multikey]) ? -1 : 1;
	 }
	/**
	* @note function is used to get configurational path.
	* return XML String.
	*/
	function get_config_details(){
		$xmlStr .= "<AUTOEXPOURL><![CDATA[".AUTOEXPOURL."]]></AUTOEXPOURL>";
		$xmlStr .= "<SEO_WEB_URL><![CDATA[".SEO_WEB_URL."]]></SEO_WEB_URL>";
		$xmlStr .= "<SEO_DOMAIN><![CDATA[".SEO_DOMAIN."]]></SEO_DOMAIN>";
		$xmlStr .= "<SEO_PRODUCT_FEATURE><![CDATA[".SEO_PRODUCT_FEATURE."]]></SEO_PRODUCT_FEATURE>";
		$xmlStr .= "<SEO_CAR_FINDER><![CDATA[".SEO_CAR_FINDER."]]></SEO_CAR_FINDER>";
		$xmlStr .= "<SEO_CAR_RESEARCH><![CDATA[".SEO_CAR_RESEARCH."]]></SEO_CAR_RESEARCH>";
		$xmlStr .= "<SEO_COMPARE_URL><![CDATA[".SEO_COMPARE_URL."]]></SEO_COMPARE_URL>";
		$xmlStr .= "<SEO_AUTO_ARTICLE><![CDATA[".SEO_AUTO_ARTICLE."]]></SEO_AUTO_ARTICLE>";
		$xmlStr .= "<SEO_AUTO_ALL_ARTICLE_DETAIL><![CDATA[".SEO_AUTO_ALL_ARTICLE_DETAIL."]]></SEO_AUTO_ALL_ARTICLE_DETAIL>";
		$xmlStr .= "<FACEBOOK_API_KEY><![CDATA[".FACEBOOK_API_KEY."]]></FACEBOOK_API_KEY>";
		$xmlStr .= "<FACEBOOK_PAGE_URL><![CDATA[".FACEBOOK_PAGE_URL."]]></FACEBOOK_PAGE_URL>";
		$xmlStr .= "<TWITTER_PAGE_URL><![CDATA[".TWITTER_PAGE_URL."]]></TWITTER_PAGE_URL>";
		$xmlStr .= "<WEB_URL><![CDATA[".WEB_URL."]]></WEB_URL>";
		$xmlStr .= "<FB_JS_URL><![CDATA[".FB_JS_URL."]]></FB_JS_URL>";
		$xmlStr .= "<JQUERY_TOOL><![CDATA[".JQUERY_TOOL."]]></JQUERY_TOOL>";
		$xmlStr .= "<TWITTER_JS_URL><![CDATA[".TWITTER_JS_URL."]]></TWITTER_JS_URL>";
		$xmlStr .= "<SEO_WEB_TITLE><![CDATA[".SEO_WEB_TITLE."]]></SEO_WEB_TITLE>";
		$xmlStr .= "<ADMIN_WEB_URL><![CDATA[".ADMIN_WEB_URL."]]></ADMIN_WEB_URL>";
		$xmlStr .= "<ADMIN_CSS_URL><![CDATA[".ADMIN_CSS_URL."]]></ADMIN_CSS_URL>";
		$xmlStr .= "<IMAGE_URL><![CDATA[".IMAGE_URL."]]></IMAGE_URL>";
		$xmlStr .= "<CENTRAL_IMAGE_URL><![CDATA[".CENTRAL_IMAGE_URL."]]></CENTRAL_IMAGE_URL>";
		
		$xmlStr .= "<CSS_URL><![CDATA[".CSS_URL."]]></CSS_URL>";
		$xmlStr .= "<JS_URL><![CDATA[".JS_URL."]]></JS_URL>";
		$xmlStr .= "<ADMIN_IMAGE_URL><![CDATA[".ADMIN_IMAGE_URL."]]></ADMIN_IMAGE_URL>";
		$xmlStr .= "<CSS_URL><![CDATA[".CSS_URL."]]></CSS_URL>";
		$xmlStr .= "<PLAYER_JS_URL><![CDATA[".PLAYER_JS_URL."]]></PLAYER_JS_URL>";
		$xmlStr .= "<PLAYER_URL><![CDATA[".PLAYER_URL."]]></PLAYER_URL>";
		$xmlStr .= "<ADMIN_JS_URL><![CDATA[".ADMIN_JS_URL."]]></ADMIN_JS_URL>";
		$xmlStr .= "<SITE_CATEGORY_ID><![CDATA[".SITE_CATEGORY_ID."]]></SITE_CATEGORY_ID>";
		$xmlStr .= "<PRODUCT_INFO_DETAILS><![CDATA[".PRODUCT_INFO_DETAILS."]]></PRODUCT_INFO_DETAILS>";
		$xmlStr .= "<SEO_AUTO_NEWS><![CDATA[".SEO_AUTO_NEWS."]]></SEO_AUTO_NEWS>";
		$xmlStr .= "<SEO_AUTO_NEWS_DETAIL><![CDATA[".SEO_AUTO_NEWS_DETAIL."]]></SEO_AUTO_NEWS_DETAIL>";
		//$xmlStr .="<LOGIN_DETAIL_URL><![CDATA[".LOGIN_DETAIL_URL."]]></LOGIN_DETAIL_URL>";
		//$xmlStr .="<AUTH_API_LOGIN_URL><![CDATA[".AUTH_API_LOGIN_URL."]]></AUTH_API_LOGIN_URL>";
		//$xmlStr .="<SERVICEID><![CDATA[".SERVICEID."]]></SERVICEID>";
		//$xmlStr .="<AUTH_API_DOMAIN><![CDATA[".AUTH_API_DOMAIN."]]></AUTH_API_DOMAIN>";
		$xmlStr .= "<SEO_AUTO_REVIEWS><![CDATA[".SEO_AUTO_REVIEWS."]]></SEO_AUTO_REVIEWS>";
		$xmlStr .= "<SEO_ON_ROAD_PRICE><![CDATA[".SEO_ON_ROAD_PRICE."]]></SEO_ON_ROAD_PRICE>";
		$xmlStr .= "<SEO_GET_ON_ROAD_PRICE><![CDATA[".SEO_GET_ON_ROAD_PRICE."]]></SEO_GET_ON_ROAD_PRICE>";
		$xmlStr .= "<PIWIK_HTTPS_URL><![CDATA[".PIWIK_HTTPS_URL."]]></PIWIK_HTTPS_URL>";
		$xmlStr .= "<PIWIK_HTTP_URL><![CDATA[".PIWIK_HTTP_URL."]]></PIWIK_HTTP_URL>";
		$xmlStr .= "<PIWIK_IMAGE_URL><![CDATA[".PIWIK_IMAGE_URL."]]></PIWIK_IMAGE_URL>";
		$xmlStr .= "<PIWIK_PAGE><![CDATA[".PIWIK_PAGE."]]></PIWIK_PAGE>";
		$xmlStr .= "<PIWIK_SITE_ID><![CDATA[".PIWIK_SITE_ID."]]></PIWIK_SITE_ID>";
		$xmlStr.="<APP_ID><![CDATA[".FACEBOOK_APP_ID."]]></APP_ID>";
		//$xmlStr.="<FNAME><![CDATA[".$_COOKIE['fname']."]]></FNAME>";
		//$xmlStr.="<LNAME><![CDATA[".$_COOKIE['lname']."]]></LNAME>";
		$xmlStr.="<UID></UID>";
		$xmlStr.="<SESSIONID><![CDATA[".$_COOKIE['session_id']."]]></SESSIONID>";
		$xmlStr .= "<SEO_CAR_VIDEOS_IMAGES><![CDATA[".SEO_CAR_VIDEOS_IMAGES."]]></SEO_CAR_VIDEOS_IMAGES>";
		$xmlStr .= "<SEO_CAR_VIDEOS_MOST_POPULAR><![CDATA[".SEO_CAR_VIDEOS_MOST_POPULAR."]]></SEO_CAR_VIDEOS_MOST_POPULAR>";
		$xmlStr .= "<SEO_CAR_SLIDESHIOW_LIST><![CDATA[".SEO_CAR_SLIDESHIOW_LIST."]]></SEO_CAR_SLIDESHIOW_LIST>";
		$xmlStr .= "<SEO_CAR_SLIDESHOW><![CDATA[".SEO_CAR_SLIDESHOW."]]></SEO_CAR_SLIDESHOW>";
		$xmlStr .= "<SEO_CAR_WALLPAPER_LIST><![CDATA[".SEO_CAR_WALLPAPER_LIST."]]></SEO_CAR_WALLPAPER_LIST>";
		$xmlStr .= "<SEO_CAR_WALLPAPER><![CDATA[".SEO_CAR_WALLPAPER."]]></SEO_CAR_WALLPAPER>";
		//$xmlStr .= "<SHARE_EMAIL_URL><![CDATA[".$_SERVER['REQUEST_URI']."]]></SHARE_EMAIL_URL>";
        	$sCaptchaUrl="captcha.php?r=".rand();
        	$xmlStr.="<SHARECAPTCHAURL><![CDATA[".$sCaptchaUrl."]]></SHARECAPTCHAURL>";
		$xmlStr .= "<SEO_CARS_MODEL_REVIEWS><![CDATA[".SEO_CARS_MODEL_REVIEWS."]]></SEO_CARS_MODEL_REVIEWS>";
		$xmlStr .= "<SEO_CARS_MODEL_FULLREVIEWS><![CDATA[".SEO_CARS_MODEL_FULLREVIEWS."]]></SEO_CARS_MODEL_FULLREVIEWS>";
		$xmlStr .= "<SEO_USER_REVIEWS><![CDATA[".SEO_USER_REVIEWS."]]></SEO_USER_REVIEWS>";
		$xmlStr .= "<SEO_CARS_USER_REVIEWS><![CDATA[".SEO_CARS_USER_REVIEWS."]]></SEO_CARS_USER_REVIEWS>";
		$xmlStr .= "<SEO_CARS_USER_FULLREVIEWS><![CDATA[".SEO_CARS_USER_FULLREVIEWS."]]></SEO_CARS_USER_FULLREVIEWS>";
		$xmlStr .= "<GOOGLE_AD_API_KEY><![CDATA[".GOOGLE_AD_API_KEY."]]></GOOGLE_AD_API_KEY>";
		$xmlStr .= "<GOOGLE_AD_SERVICE_JS><![CDATA[".GOOGLE_AD_SERVICE_JS."]]></GOOGLE_AD_SERVICE_JS>";
		$xmlStr .= "<ONCARS_SUPPORT_EMAIL><![CDATA[".ONCARS_SUPPORT_EMAIL."]]></ONCARS_SUPPORT_EMAIL>";
		$xmlStr .= "<ONCARS_CONTACT_EMAIL><![CDATA[".ONCARS_CONTACT_EMAIL."]]></ONCARS_CONTACT_EMAIL>";
		$xmlStr .= "<ONCARS_SHARING_SERVICE_EMAIL><![CDATA[".ONCARS_SHARING_SERVICE_EMAIL."]]></ONCARS_SHARING_SERVICE_EMAIL>";
		$xmlStr .= "<ONCARS_EXPERT_EMAIL><![CDATA[".ONCARS_EXPERT_EMAIL."]]></ONCARS_EXPERT_EMAIL>";
		$xmlStr .= "<ONCARS_NO_REPLY><![CDATA[".ONCARS_NO_REPLY."]]></ONCARS_NO_REPLY>";
		$xmlStr .= "<ONCARS_SUPPORT_EMAIL_NAME><![CDATA[".ONCARS_SUPPORT_EMAIL_NAME."]]></ONCARS_SUPPORT_EMAIL_NAME>";
		$xmlStr .= "<ONCARS_CONTACT_EMAIL_NAME><![CDATA[".ONCARS_CONTACT_EMAIL_NAME."]]></ONCARS_CONTACT_EMAIL_NAME>";
		$xmlStr .= "<ONCARS_SHARING_SERVICE_EMAIL_NAME><![CDATA[".ONCARS_SHARING_SERVICE_EMAIL_NAME."]]></ONCARS_SHARING_SERVICE_EMAIL_NAME>";
		$xmlStr .= "<ONCARS_EXPERT_EMAIL_NAME><![CDATA[".ONCARS_EXPERT_EMAIL_NAME."]]></ONCARS_EXPERT_EMAIL_NAME>";
		$xmlStr .= "<ONCARS_NO_REPLY_NAME><![CDATA[".ONCARS_NO_REPLY_NAME."]]></ONCARS_NO_REPLY_NAME>";
		$xmlStr .= "<SEO_AUTO_USER_REVIEWS><![CDATA[".SEO_AUTO_USER_REVIEWS."]]></SEO_AUTO_USER_REVIEWS>";
		$xmlStr .= "<VIDEO_GOOGLE_ANALYTICS><![CDATA[".VIDEO_GOOGLE_ANALYTICS."]]></VIDEO_GOOGLE_ANALYTICS>";
		$xmlStr .= "<SEO_AUTO_F1NEWS><![CDATA[".SEO_AUTO_F1NEWS."]]></SEO_AUTO_F1NEWS>";
		$xmlStr .= "<SEO_AUTO_F1NEWS_DETAIL><![CDATA[".SEO_AUTO_F1NEWS_DETAIL."]]></SEO_AUTO_F1NEWS_DETAIL>";
		$xmlStr .= "<VERSION><![CDATA[".VERSION."]]></VERSION>";
		$xmlStr .= "<VIEW_TRACKER_API_PATH><![CDATA[".VIEW_TRACKER_API_PATH."]]></VIEW_TRACKER_API_PATH>";
		$xmlStr .= "<CAR_DEALERS><![CDATA[".CAR_DEALERS."]]></CAR_DEALERS>";
		$xmlStr .= "<SEARCH_CAR_DEALERS><![CDATA[".SEARCH_CAR_DEALERS."]]></SEARCH_CAR_DEALERS>";
		$xmlStr .= "<CAR_DEALER><![CDATA[".CAR_DEALER."]]></CAR_DEALER>";
		$xmlStr .="<LOGIN_DETAIL_URL><![CDATA[".LOGIN_DETAIL_URL."]]></LOGIN_DETAIL_URL>";
		$xmlStr .="<AUTH_API_LOGIN_URL><![CDATA[".AUTH_API_LOGIN_URL."]]></AUTH_API_LOGIN_URL>";
		$xmlStr .="<SERVICEID><![CDATA[".SERVICEID."]]></SERVICEID>";
		$xmlStr .="<AUTH_API_DOMAIN><![CDATA[".AUTH_API_DOMAIN."]]></AUTH_API_DOMAIN>";
		$xmlStr .="<AUTH_API_ACTION_URL><![CDATA[".AUTH_API_ACTION_URL."]]></AUTH_API_ACTION_URL>";
		$xmlStr .="<AUTH_WEB_URL><![CDATA[".AUTH_API_WEB_URL."]]></AUTH_WEB_URL>";
		$xmlStr .= "<CKSESSION><![CDATA[".$_COOKIE['cksession']."]]></CKSESSION>";
		$xmlStr .= "<SITE_PATH><![CDATA[".SITE_PATH."]]></SITE_PATH>";
		$xmlStr .= "<ONCARS_HOT_COMPARISONS><![CDATA[".ONCARS_HOT_COMPARISONS."]]></ONCARS_HOT_COMPARISONS>";
		$xmlStr .= "<GET_DEALER_QUOTE><![CDATA[".GET_DEALER_QUOTE."]]></GET_DEALER_QUOTE>";
		$xmlStr .= "<BOOK_TEST_DRIVE><![CDATA[".BOOK_TEST_DRIVE."]]></BOOK_TEST_DRIVE>";
		$xmlStr .= "<GOOGLE_MAP_API_KEY><![CDATA[".GOOGLE_MAP_API_KEY."]]></GOOGLE_MAP_API_KEY>";
		$xmlStr .= "<LOGOUT_URL><![CDATA[".LOGOUT_URL."]]></LOGOUT_URL>";
		$xmlStr .= "<SEO_USEDCAR_LOGIN><![CDATA[".SEO_USEDCAR_LOGIN."]]></SEO_USEDCAR_LOGIN>";
		$xmlStr .= "<SEO_USEDCAR_SEARCH><![CDATA[".SEO_USEDCAR_SEARCH."]]></SEO_USEDCAR_SEARCH>";
		$xmlStr .= "<SEO_USEDCAR><![CDATA[".SEO_USEDCAR."]]></SEO_USEDCAR>";
		$xmlStr .= "<SEO_CAR_BRANDS><![CDATA[".SEO_CAR_BRANDS."]]></SEO_CAR_BRANDS>";
		$xmlStr .= "<SEO_USEDCAR_RESEARCH><![CDATA[".SEO_USEDCAR_RESEARCH."]]></SEO_USEDCAR_RESEARCH>";
		$xmlStr .= "<ON_ROAD_PRICE_SERVICE_ID><![CDATA[".ON_ROAD_PRICE_SERVICE_ID."]]></ON_ROAD_PRICE_SERVICE_ID>";
		$xmlStr .= "<TEST_DRIVE_SERVICE_ID><![CDATA[".TEST_DRIVE_SERVICE_ID."]]></TEST_DRIVE_SERVICE_ID>";
		$xmlStr .= "<DEALER_QUOTES_SERVICE_ID><![CDATA[".DEALER_QUOTES_SERVICE_ID."]]></DEALER_QUOTES_SERVICE_ID>";
		$xmlStr .= "<DEALER_ADDRESS_SERVICE_ID><![CDATA[".DEALER_ADDRESS_SERVICE_ID."]]></DEALER_ADDRESS_SERVICE_ID>";
		$xmlStr .= "<SEO_CAR_INSURANCE><![CDATA[".SEO_CAR_INSURANCE."]]></SEO_CAR_INSURANCE>";
		$xmlStr .= "<SEO_CAR_INSURANCE_QUOTE><![CDATA[".SEO_CAR_INSURANCE_QUOTE."]]></SEO_CAR_INSURANCE_QUOTE>";
		$xmlStr .= "<SEO_CAR_VIDEOS><![CDATA[".CAR_VIDEOS."]]></SEO_CAR_VIDEOS>";
		$xmlStr .= "<SEO_CAR_REVIEWS><![CDATA[".SEO_CAR_REVIEWS."]]></SEO_CAR_REVIEWS>";
		$xmlStr .= "<SEO_AUTO_PORN><![CDATA[".SEO_AUTO_PORN."]]></SEO_AUTO_PORN>";
		$xmlStr .= "<SEO_INTERNATIONAL_CARS><![CDATA[".SEO_INTERNATIONAL_CARS."]]></SEO_INTERNATIONAL_CARS>";
		$xmlStr .= "<SEO_CAR_FEATURES><![CDATA[".SEO_CAR_FEATURES."]]></SEO_CAR_FEATURES>";
		$xmlStr .= "<SEO_CAR_MAINTENANCE><![CDATA[".SEO_CAR_MAINTENANCE."]]></SEO_CAR_MAINTENANCE>";
		$xmlStr .= "<SEO_CAR_NEWS><![CDATA[".SEO_CAR_NEWS."]]></SEO_CAR_NEWS>";
		$xmlStr .= "<SEO_BOOK_TEST_DRIVE><![CDATA[".SEO_BOOK_TEST_DRIVE."]]></SEO_BOOK_TEST_DRIVE>";
		$xmlStr .= "<SEO_BOOK_TEST_DRIVE_THANKYOU><![CDATA[".SEO_BOOK_TEST_DRIVE_THANKYOU."]]></SEO_BOOK_TEST_DRIVE_THANKYOU>";
        $xmlStr .= "<SEO_WRITE_USER_REVIEWS><![CDATA[".SEO_WRITE_USER_REVIEWS."]]></SEO_WRITE_USER_REVIEWS>";
		$xmlStr .= "<SEO_MODEL_ALL_REVIEWS><![CDATA[".SEO_MODEL_ALL_REVIEWS."]]></SEO_MODEL_ALL_REVIEWS>";
		$xmlStr .= "<SEO_MODEL_EXPERT_REVIEWS><![CDATA[".SEO_MODEL_EXPERT_REVIEWS."]]></SEO_MODEL_EXPERT_REVIEWS>";
        $xmlStr .= "<SEO_DESIGN_REVIEW><![CDATA[".SEO_DESIGN_REVIEW."]]></SEO_DESIGN_REVIEW>";
		$xmlStr .= "<SEO_USER_EXPERIENCE_REVIEW><![CDATA[".SEO_USER_EXPERIENCE_REVIEW."]]></SEO_USER_EXPERIENCE_REVIEW>";
		$xmlStr .= "<SEO_PERFORMANCE_REVIEW><![CDATA[".SEO_PERFORMANCE_REVIEW."]]></SEO_PERFORMANCE_REVIEW>";
		$xmlStr .= "<DISCONTINUE_MONTH_DURATION><![CDATA[".DISCONTINUE_MONTH_DURATION."]]></DISCONTINUE_MONTH_DURATION>";
		$xmlStr .= "<SEO_UPCOMING_CARS><![CDATA[".SEO_UPCOMING_CARS."]]></SEO_UPCOMING_CARS>";
		$xmlStr .= "<AD_BANNER_URL><![CDATA[".AD_BANNER_URL."]]></AD_BANNER_URL>";
		$xmlStr .= "<ARTICLE_CATEGORYID><![CDATA[".ARTICLE_CATEGORYID."]]></ARTICLE_CATEGORYID>";
		$xmlStr .= "<NEWS_CATEGORYID><![CDATA[".NEWS_CATEGORYID."]]></NEWS_CATEGORYID>";
		$topNavigationBrandXml = getTopNavigationBrand(SITE_CATEGORY_ID);
		$xmlStr .= $topNavigationBrandXml;
		$isIE6 = 0;
		$isIE = 0;

		//echo $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/(?i)msie [1-6]/',$_SERVER['HTTP_USER_AGENT'])) {
			$isIE6 = 1;
		}
		if(preg_match('/(?i)msie /',$_SERVER['HTTP_USER_AGENT'])) {
			$isIE = 1;
		}
		if(preg_match('/(?i)msie [7]/',$_SERVER['HTTP_USER_AGENT'])) {
			$isIE7 = 1;
		}
		$xmlStr .= "<IS_IE><![CDATA[".$isIE ."]]></IS_IE>";
		$xmlStr .= "<IS_IE_6><![CDATA[".$isIE6 ."]]></IS_IE_6>";
		$xmlStr .= "<IS_IE_7><![CDATA[".$isIE7 ."]]></IS_IE_7>";
                $xmlStr .= "<COPY_RIGHT_YEAR><![CDATA[".date('Y')."]]></COPY_RIGHT_YEAR>";
		$pricequote_campaign_arr = array(3,4,5,6,8,13,15);
		$pricequote_campaign_str = implode(",",$pricequote_campaign_arr);
		$xmlStr .= "<CAMPAIGN_BRAND_IDS><![CDATA[$pricequote_campaign_str]]></CAMPAIGN_BRAND_IDS>";
		//$db_page_view_count = GetCampaignPageViewLimit();
		$xmlStr .= "<DOMAIN><![CDATA[".DOMAIN."]]></DOMAIN>";
		$xmlStr .= "<RSS_URL><![CDATA[".RSS_URL."]]></RSS_URL>";
		#$xmlStr .= "<DONOTSHOWLIMIT><![CDATA[".DONOTSHOWLIMIT."]]></DONOTSHOWLIMIT>";
		#$xmlStr .= "<SUBMITADFORMDATA><![CDATA[".SUBMITADFORMDATA."]]></SUBMITADFORMDATA>";
		$xmlStr.="<COOKIE_CITY_ID><![CDATA[".$_COOKIE['cookie_city_id']."]]></COOKIE_CITY_ID>";
		$xmlStr.="<COOKIE_CITY><![CDATA[".$_COOKIE['cookie_city']."]]></COOKIE_CITY>";
		$xmlStr.= "<FREE_ADVICE><![CDATA[".FREE_ADVICE_PHONE_NUMBER."]]></FREE_ADVICE>";
		$xmlStr.= "<BASE_PRICE_CONDITION><![CDATA[".BASE_PRICE_CONDITION."]]></BASE_PRICE_CONDITION>";
		$xmlStr.= "<BASE_PRICE_TEXT><![CDATA[".BASE_PRICE_TEXT."]]></BASE_PRICE_TEXT>";
		$xmlStr.= "<SHARE_URL><![CDATA[".rawurlencode($_SERVER['SCRIPT_URI'])."]]></SHARE_URL>";
		$xmlStr.= "<SEO_EMI_CALCULATOR><![CDATA[".SEO_EMI_CALCULATOR."]]></SEO_EMI_CALCULATOR>";
		$xmlStr.= "<SEO_CAR_BUYING_TIPS><![CDATA[".SEO_CAR_BUYING_TIPS."]]></SEO_CAR_BUYING_TIPS>";


		$xmlStr.= "<SEO_AUTO_ARTICLE_MAINTAINANCE_URL><![CDATA[".SEO_AUTO_ARTICLE_MAINTAINANCE_URL."]]></SEO_AUTO_ARTICLE_MAINTAINANCE_URL>";
		$xmlStr.= "<SEO_AUTO_ARTICLE_ACCESSORIES_URL><![CDATA[".SEO_AUTO_ARTICLE_ACCESSORIES_URL."]]></SEO_AUTO_ARTICLE_ACCESSORIES_URL>";
		$xmlStr.= "<SEO_AUTO_ARTICLE_BUYING_GUIID_TIPS_URL><![CDATA[".SEO_AUTO_ARTICLE_BUYING_GUIID_TIPS_URL."]]></SEO_AUTO_ARTICLE_BUYING_GUIID_TIPS_URL>";
		$xmlStr.= "<SEO_AUTO_ARTICLE_FEATURE_URL><![CDATA[".SEO_AUTO_ARTICLE_FEATURE_URL."]]></SEO_AUTO_ARTICLE_FEATURE_URL>";


		if(!empty($_REQUEST['utm_source'])){
		        $srcum = $_REQUEST['utm_source'];
		        $srcmed = $_REQUEST['utm_medium'];
		        $srccmp = $_REQUEST['utm_campaign'];
		        $srctrm = $_REQUEST['utm_term'];
		}
		if(!empty($srcum)){
			$xmlStr.= "<SRCUM><![CDATA[".$srcum."]]></SRCUM>";
			$xmlStr.= "<SRCMED><![CDATA[".$srcmed."]]></SRCMED>";
			$xmlStr.= "<SRCCMP><![CDATA[".$srccmp."]]></SRCCMP>";
			$xmlStr.= "<SRCTRM><![CDATA[".$srctrm."]]></SRCTRM>";
		}
		$xmlStr.= getToolTips();
		$xmlStr.= "<CATEGORY_PATH><![CDATA[".$_REQUEST['cat_path']."]]></CATEGORY_PATH>";
		$xmlStr.= "<TOP_MOBILES><![CDATA[".TOP_MOBILES."]]></TOP_MOBILES>";
		$xmlStr.= "<BUDGET_MOBILES><![CDATA[".BUDGET_MOBILES."]]></BUDGET_MOBILES>";
		$xmlStr.= "<UPCOMING_MOBILES><![CDATA[".UPCOMING_MOBILES."]]></UPCOMING_MOBILES>";
		$xmlStr.= "<NEW_ARRIVALS><![CDATA[".NEW_ARRIVALS."]]></NEW_ARRIVALS>";
		$xmlStr.= "<BRANDS><![CDATA[".BRANDS."]]></BRANDS>";
		$xmlStr.= "<PHONE_FINDER><![CDATA[".PHONE_FINDER."]]></PHONE_FINDER>";
		$xmlStr.= "<PHONE_COMPARE><![CDATA[".PHONE_COMPARE."]]></PHONE_COMPARE>";
		$xmlStr.= "<USER_REVIEWS><![CDATA[".USER_REVIEWS."]]></USER_REVIEWS>";
		$xmlStr.= "<BGR_NEWS_URL><![CDATA[".BGR_NEWS_URL."]]></BGR_NEWS_URL>";
		$curr_url = WEB_URL;
		if($_SERVER['REQUEST_URI'] !== '/' ){
			$curr_url = SEO_WEB_URL.$_SERVER['REQUEST_URI'];
		}
		$xmlStr.= "<CURRENT_URL><![CDATA[".$curr_url."]]></CURRENT_URL>"; 
		$xmlStr.= "<GPLUS_COUNT><![CDATA[".get_plusones($curr_url)."]]></GPLUS_COUNT>"; 
				//Getting navigation menu list
		//require_once('navigation.class.php');
		//$xmlStr.= fetchHeaderNavMenuList();
		return $xmlStr;
	}
	function getToolTips(){
		global $tooltipMsgArr;
		$toolTipXML = "<TOOL_TIPS>";
		if(is_array($tooltipMsgArr)){
			foreach($tooltipMsgArr as $k=>$v){
				$toolTipXML .= "<$k>";
				$toolTipXML .= "<![CDATA[".$v."]]>";
				$toolTipXML .= "</$k>";
			}
		}
		$toolTipXML .= "</TOOL_TIPS>";
		return $toolTipXML;
	}

	function GetCampaignPageViewLimit(){
		if(!empty($_COOKIE['pageviewscnt'])){
			$db_page_view_count = $_COOKIE['pageviewscnt'];
		}else{
			require_once(CLASSPATH.'user.class.php');
			$oUser = new user;
			$arr_db_page_view = $oUser->getPageViewsLmitDetail();
			if(is_array($arr_db_page_view)){
				$db_page_view_count = $arr_db_page_view[0]['page_view_limit'];
				setcookie ("pageviewscnt", $db_page_view_count,time()+3600,'/',$domain); //used to change ad baaner display.
			}
		}
		return $db_page_view_count;
	}

	function getTopNavigationBrand($category_id){
		require_once(CLASSPATH.'brand.class.php');
		$brand = new BrandManagement;
		$top_nav_brand_arr= array(6,5,3,31,1,4,15,26,13,2,14,8,37,38,20,25,28,18,11,19,23,17);
		$result = $brand->arrGetBrandDetails("",$category_id);
		$cnt = sizeof($result);
		foreach($result as $bkry=>$bValue){
			if(in_array($bValue['brand_id'],$top_nav_brand_arr)){
				$set_key = array_search($bValue['brand_id'], $top_nav_brand_arr);
				$bBrandArr1[$set_key] = $bValue;
			}else{
				$bBrandArr2[] = $bValue;
			}
		}
		ksort($bBrandArr1);
		unset($result);
		if(is_array($bBrandArr1) && is_array($bBrandArr2)){
			$result = array_merge($bBrandArr1,$bBrandArr2);
		}
		$xml = "<TOPNAV_BRAND_MASTER>";
		$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
		$selectedIndex = "0";
		$isBrandSelected = "0"; //used toggle all brands checkbox.
		for($i=0;$i<$cnt;$i++){
			$brand_id = $result[$i]['brand_id'];
			$status = $result[$i]['status'];
			$categoryid = $result[$i]['category_id'];
			$result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
			$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
			$result[$i]['js_brand_name'] = $result[$i]['brand_name'];
			$result[$i]['seo_brand_name'] = WEB_URL."car-brands/".constructUrl( $result[$i]['brand_name']);
			$result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'],ENT_QUOTES,'UTF-8');
			$result[$i]['short_desc'] = html_entity_decode($result[$i]['short_desc'],ENT_QUOTES,'UTF-8');
			$result[$i]['long_desc'] = html_entity_decode($result[$i]['long_desc'],ENT_QUOTES,'UTF-8');
			$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
			$xml .= "<TOPNAV_BRAND_MASTER_DATA>";
			foreach($result[$i] as $k=>$v){
				$xml .= "<$k><![CDATA[$v]]></$k>";
			}
			$xml .= "</TOPNAV_BRAND_MASTER_DATA>";
		}
		$xml .= "</TOPNAV_BRAND_MASTER>";
		return $xml;
	}

	/**
         * @note function is used to get a random value
         *
         * @param an integer $length.
         * retun a random integer value.
         */
	function get_rand_id($length)
	{
		if($length>0){
			$rand_id="";
			for($i=1; $i<=$length; $i++)
			{
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,36);
				$rand_id .= assign_rand_value($num);
			}
		}
		return $rand_id;
	}
	/**
         * @note function is used to assign  a random value to a number.
         *
         * @param an integer $num
         * retun an alphabet.
         */
	function assign_rand_value($num)
	{
		// accepts 1 - 36
		switch($num)
		{
		case "1":
		$rand_value = "a";
		break;
		case "2":
		$rand_value = "b";
		break;
		case "3":
		$rand_value = "c";
		break;
		case "4":
		$rand_value = "d";
		break;
		case "5":
		$rand_value = "e";
		break;
		case "6":
		$rand_value = "f";
		break;
		case "7":
		$rand_value = "g";
		break;
		case "8":
		$rand_value = "h";
		break;
		case "9":
		$rand_value = "i";
		break;
		case "10":
		$rand_value = "j";
		break;
		case "11":
		$rand_value = "k";
		break;
		case "12":
		$rand_value = "l";
		break;
		case "13":
		$rand_value = "m";
		break;
		case "14":
		$rand_value = "n";
		break;
		case "15":
		$rand_value = "o";
		break;
		case "16":
		$rand_value = "p";
		break;
		case "17":
		$rand_value = "q";
		break;
		case "18":
		$rand_value = "r";
		break;
		case "19":
		$rand_value = "s";
		break;
		case "20":
		$rand_value = "t";
		break;
		case "21":
		$rand_value = "u";
		break;
		case "22":
		$rand_value = "v";
		break;
		case "23":
		$rand_value = "w";
		break;
		case "24":
		$rand_value = "x";
		break;
		case "25":
		$rand_value = "y";
		break;
		case "26":
		$rand_value = "z";
		break;
		case "27":
		$rand_value = "0";
		break;
		case "28":
		$rand_value = "1";
		break;
		case "29":
		$rand_value = "2";
		break;
		case "30":
		$rand_value = "3";
		break;
		case "31":
		$rand_value = "4";
		break;
		case "32":
		$rand_value = "5";
		break;
		case "33":
		$rand_value = "6";
		break;
		case "34":
		$rand_value = "7";
		break;
		case "35":
		$rand_value = "8";
		break;
		case "36":
		$rand_value = "9";
		break;
		}
		return $rand_value;
	}
	/**
         * @note function is used to get selected drop down listing.
         *
         * @param a comma separated list ids $sListId
	 * @param is an array $aListing.
         * retun a string.
         */
	function getSelectedDropDownlising($aListing,$sListId){
		$aListingId = -1;
		if(!empty($sListId)){
			$aListingId = explode(',',$sListId);
		}
		if(is_array($aListing) && count($aListing)>0){
			foreach($aListing as $iLisingkey=>$sListingVal){
				if(in_array($iLisingkey,$aListingId)){
					$strOptions.="<option value='$iLisingkey' selected='selected'>".$sListingVal."</option>";
				}else{
					$strOptions.="<option value='$iLisingkey'>".$sListingVal."</option>";
				}
			}
		}
		return $strOptions;
	}
	/**
         * @note function is used to get image details
         *
         * @param is an integer $iMediaId.
	 * @param is an integer $iServiceId
	 * @param is a string $action
         * retun an array of image details.
         */
	function getImageDetails($iMediaId,$iServiceId,$action='api'){
		$sString = file_get_contents(IMAGE_READER_FILE."?service_id=$iServiceId&action=api&media_id=$iMediaId");
		//echo $sString;
		//header('content-type:text/xml');
		//echo IMAGE_READER_FILE."?service_id=$iServiceId&action=api&media_id=$iMediaId";die;
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->loadXML($sString);
		$MainImg = $doc->getElementsByTagName('IMG_PATH')->item(0)->nodeValue;
		//$ThumbImg = $doc->getElementsByTagName('IMG_PATH')->item(1)->nodeValue;
	        $MainTitle = $doc->getElementsByTagName('TITLE')->item(0)->nodeValue;
		$aImage = array('main_image'=>$MainImg,'title'=>$MainTitle);
		return $aImage;

	}
	/**
         * @note function is used to convert an array  to xml
         *
         * @param is an array $arr.
         * @param is a string $node.
         * retun a xml string.
         */
	function arraytoxml($arr,$node="MAIN"){
		$nodes='';
		$cnt = count($arr);
		for($b=0;$b<$cnt;$b++){
			$arrData = $arr[$b];
			$nodes .="<".$node.">";
			if(is_array($arrData)){
				$keys = array_keys($arrData);
				$values = array_values($arrData);

				for($i=0;$i<sizeof($keys);$i++){
					if($keys[$i]=='title'){
						$sTitle = removeSlashes($values[$i]);
						$sTitle = html_entity_decode($sTitle,ENT_QUOTES);
						if(strlen($sTitle)>100){ $sTitle = getCompactString($sTitle, 95).' ...'; }
						$nodes.="<SHORT_TITLE><![CDATA[".$sTitle."]]></SHORT_TITLE>";
					}
					$values[$i] = removeSlashes($values[$i]);
					$values[$i] = html_entity_decode($values[$i],ENT_QUOTES);
					$nodes.="<".strtoupper($keys[$i])."><![CDATA[".$values[$i]."]]></".strtoupper($keys[$i]).">";

					//echo "DDDD--".strtoupper($keys[$i]);
				}
			}
			$nodes .="</".$node.">";
		}
		//echo "DDDDDD".$nodes;
		return $nodes;
	}
	/**
	* @note function used to get query start limit  and offset.
	* @param integer current pageno.
	* @param integer perpage record count.
	* @pre pageno and count must be non-empty integer.
	* @post an array.
	* return an array.
	*/
	function arrGetPageLimit($pageno,$perpagecnt){
        if($pageno <= 0){
            $pageno = 1;//starting index of query is always begin from zero.
        }
		$pageno = $pageno - 1;
		$startlimit = $pageno * $perpagecnt;
		$limitArr = array('startlimit' => $startlimit , 'recordperpage' => $perpagecnt);
		return $limitArr;
    	}
	/**
        * @note function used to convert a string to javascript parameters.
        * @param is a string $str.
        * return is a string.
        */
	function convertStrtoJSParams($str){
		//$reg = '/<a href="(.*?)">/';
		$reg = '/<a href="(.*?)" class="(.*?)">/';
		$jsParamregExp = '/pageno=(.*?)/';
		if(preg_match_all($reg,$str,$matches,PREG_SET_ORDER)){
			$count = sizeof($matches);
			for($i=0;$i<$count;$i++){
				$searchArr[] = $matches[$i][0];
				$classname = $matches[$i][2];
				$replaceStr = str_replace(array(WEB_URL.basename('browser.php').'?',basename('browser.php').'?'),'',$matches[$i][1]);//removed page and url info
				//echo $replaceStr;exit;
				$jsparams = preg_replace($jsParamregExp,"'\\1','\\2','\\3','\\4','\\5'",$replaceStr); // created js function params.
				$replaceArr[] = "<a href=\"javascript:undefined;\" onclick=\"browseItems($jsparams);\" class=\"$classname\">";
			}
		}
		$str = str_replace($searchArr,$replaceArr,$str);
		return $str;
	}

	/**
        * @note function used to compact a string upto a certain number of characters.
        * @param string $sStr.
	* @param is an integer $stringCharLimit
        * @author Rajesh Ujade.
        * @created 23-Nov-2010
        * @pre str must be non-empty string.
        * @post string.
        * return string.
        */
	function getCompactString($sStr,$stringCharLimit,$iFlag=true){
		$stringCharLimit=$stringCharLimit+10;
		//echo $sStr."<br>";
		$sString=substr($sStr,0,$stringCharLimit);
		$aString=explode(" ",$sString);
		if($iFlag==true)
			$aRetString=array_pop($aString);

		$sFinalString=implode(" ",$aString);

		if(strlen($sStr)>$stringCharLimit-10 && $iFlag===false){
			$sFinalString.='...';
			//die('in Utility class.');
		}

		return $sFinalString;
	}

        /**
        * @note function used to compact a string upto a certain number of characters.
        * @param string $sStr.
        * @param is an integer $stringCharLimit
        * @author Rajesh Ujade.
        * @created 23-Nov-2010
        * @pre str must be non-empty string.
        * @post string.
        * return string.
        */
        function getTruncatedString($sStr,$stringCharLimit){
                if(strlen($sStr)>$stringCharLimit){
                        $sFinalString = substr($sStr,0,$stringCharLimit).'...';
                        //echo $sString."<br>";
                }else{
                        $sFinalString = $sStr;
                }
                return $sFinalString;
        }

	/**
	* @note function used to replace the seo string using regular expression.
	* @param string str.
	* @author Rajesh Ujade.
	* @created 23-Nov-2010
	* @pre str must be non-empty string.
	* @post string.
	* return string.
	*/
	function seo_str_replace($str){
		$str = preg_replace("/^[^a-z0-9]+/", "", $str);
		$str = preg_replace("/[^a-z0-9]+$/", "", $str);
		$str = preg_replace("/[^a-z0-9]/", "-", $str);
		return $str;
	}
	/**
        * @note function used to replace the seo description using regular expression.
        * @param string $str.
	* @param string $replaceStr.
        * @author Rajesh Ujade.
        * @created 23-Nov-2010
        * @pre $str must be non-empty string.
        * @pre $replaceStr must be non-empty string.
        * @post string.
        * return string.
        */
	function seo_description_replace($str,$replaceStr=""){
		$str = preg_replace("/<([A-Za-z][A-Za-z0-9]*)[^>]*>/",$replaceStr,$str);
		$str = preg_replace("/<\/([A-Za-z][A-Za-z0-9]*)>/",$replaceStr,$str);
		return $str;
	}
	/**
        * @note function used to replace the seo title using regular expression.
        * @param string $str.
        * @param string $replaceStr.
        * @author Rajesh Ujade.
        * @created 23-Nov-2010
        * @pre $str must be non-empty string.
        * @pre $replaceStr must be non-empty string.
        * @post string.
        * return string.
        */
	function seo_title_replace($str,$replaceStr="-"){
		//$str = str_replace("-","+.",$str);
		$str = trim($str);
		$str = implode($replaceStr,explode(" ",$str));
		$str = rawurlencode($str);
		return $str;
	}


	/**
	* @note function used to check if the email address is valid
	* @param string $email - email address
	* @access public
	*/
	function isValidEmail($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}
	/**
        * @note function used to rewrite data.
        * @param string $sDispTitle.
        * return string.
        */
	function getRewriteData($sDispTitle){
			$sDispTitle = str_replace('-','',$sDispTitle);
			$sDispTitle = str_replace(' ','-',$sDispTitle);
			$sDispTitle = str_replace(',','-',$sDispTitle);
			$sDispTitle = str_replace('&','',$sDispTitle);
			$sDispTitle = str_replace('/','-',$sDispTitle);
			$sDispTitle = str_replace('.','',$sDispTitle);
			$sDispTitle = str_replace('?','',$sDispTitle);
			$sDispTitle = str_replace("'",'',$sDispTitle);
			$sDispTitle = str_replace("!",'',$sDispTitle);
			$sDispTitle = str_replace('"','',$sDispTitle);
			$sDispTitle = str_replace('%','',$sDispTitle);
			return $sDispTitle;
	}
	/**
        * @note function used to format price by comma separated.
        * @param string $price.
	* @post string formated price.
        * return string.
        */
	function priceFormat($price){
		$pos_flag=0;
		$pos = strpos($price, "-");
		if($pos !== false){
			$pos_flag="1";
			$price = substr($price , 1, (strlen($price)));
		}
		$flag=0;
        	$x=strlen($price);
	        $num1="";$num2="";
        	if($x > 3){
                	$num1 = substr($price , 0, (strlen($price)-3));
	                $num2 = substr($price, -3);
        	        $size = strlen($num1);
                	if(($size%2) == "1"){
                        	$flag=1;
	                        $num1 = str_pad($num1,strlen($num1)+1 ,"0",STR_PAD_LEFT);
        	        }
                	$arr2=str_split($num1, 2);
	                $num1=implode(",",$arr2);
        	        $price = $num1.",".$num2;
	        	if($flag == 1){$price = substr($price, 1);}
	       	}
		if($pos_flag == 1){
			$price = "-".$price;
		}
		return $price;
	}
	/**
        * @note function used to get different video resolution
        * @param string $media_path
        * @post array video path details.
        * return array.
        */
	function arrGetDifferentVideoResolution($media_path){
		$extpos = strrpos($media_path, ".");
		$file = substr($media_path, 0, $extpos);
		$ext = substr($media_path,$extpos);
		$ext = ($ext == '.mp4') ? '.flv' : $ext;
		$low_media_path = $file."_low".$ext;
		$videoArr['low_media_path'] = $low_media_path;
		$normal_media_path = $file."_normal".$ext;
		$videoArr['normal_media_path'] = $normal_media_path;
		return $videoArr;
	}

	/**
	* @note function used to get image path of desired size
	* @param string $img_path
	* @param string $search_size
	* @post string new image path
	* return string
	*/
	function resizeImagePath($img_path,$search_size,$aModuleImageResize,$media_id=""){
		$imgArr = Array('.jpg','.png','.gif','.jpeg');
		$file = str_replace(array(CENTRAL_IMAGE_URL,CENTRAL_MEDIA_URL),'',$img_path);
       		$extpos = strrpos($file, ".");
       		$file = substr($file, 0, $extpos);
       		$ext = substr($file,$extpos);
       		if(!in_array($ext,$imgArr)){
               		$file = $file.'.jpg';
               		if(strpos($img_path,CENTRAL_IMAGE_URL) !== false){
                       		$img_path = CENTRAL_IMAGE_URL.$file;
               		}else if(strpos($img_path,CENTRAL_MEDIA_URL) !== false){
                       		$img_path = CENTRAL_MEDIA_URL.$file;
               		}
      		}
		#print_r($aModuleImageResize);
		#echo "<br>".$search_size,$img_path;
		#die;
		return $new_img_path = str_replace($aModuleImageResize,$search_size,$img_path);
		$new_img_path = str_replace($aModuleImageResize,$search_size,$img_path);
		$res = file_get_contents(ORIGIN_CENTRAL_SERVER.$new_img_path);
		if(strlen($res) > 0){
			$img_path = $new_img_path;
		}else{
			if(!empty($media_id) && !empty($search_size)){

				$search_size = strtoupper($search_size);
				list($width,$height) = explode("X",$search_size);
				$img_path = file_get_contents(ORIGIN_CENTRAL_SERVER."resize.php?media_id=$media_id&w=$width&h=$height&service_id=".SERVICEID);
				error_log('resize img = '.ORIGIN_CENTRAL_SERVER."resize.php?media_id=$media_id&w=$width&h=$height&service_id=".SERVICEID);
			}
		}
		return $img_path;
	}
	function multi_array_diff($mainArr,$newArr,$searchArr,$s='0'){
		foreach($mainArr as $key => $val){
			$searchkey = $newArr[$key];
			if(in_array($searchkey,$searchArr)){
				//print_r($mainArr);
				unset($mainArr[$key]);
			}
		}
		if(empty($s)){$mainArr = array_unique($mainArr,SORT_REGULAR);sort($mainArr);}
		return $mainArr;
	}
	function multi_array_search($array,$search_str,$is_two_dimensional='0'){
		if(!empty($is_two_dimensional)){
			$cnt = sizeof($array);
			for($i=0;$i<$cnt;$i++){
				if(in_array($search_str,$array[$i])){
					return $key = $i;
				}
			}
		}else{
			$key = array_search($search_str, $array[$i]);
		}
		return $key;
	}
	function create_dir($path){
		$path_dirs = explode("/",$path);
		array_splice($path_dirs, 0, 1);
		foreach($path_dirs as $dir){
			if($dir == "") {continue;}
			$currpath .= "/".$dir;
			if(!is_dir($currpath)){
				shell_exec("mkdir $currpath");
				shell_exec("chmod 777 $currpath");
			}
		}
		return $path;
	}

	function parse_csv($filename,$flag){
		$arrCSV = array();
		// Opening up the CSV file
		if (($handle = fopen($filename, "r")) !==FALSE) {
			// Set the parent array key to 0
			$key = 0;
			$row = 0;
			$csv_file_data = array();
			$csv_fields_num = TRUE;
			$csv_head_read = TRUE;
			$csv_head_label = TRUE;
			$csv_head_read  = FALSE;
			//$csv_head_read  = TRUE;
			$csv_head_label = TRUE;
			// While there is data available loop through unlimited times (0) using separator (,)
			while (($data = fgetcsv($handle, 0, ",")) !==FALSE) {
				// Count the total keys in each row $data is the variable for each line of the array
				//$c = count($data);
				$num = count($data);
				/* CSV First Row: Assumed as Head */
				if( $csv_head_read  == FALSE && $row == 1 ) {
					/* Next Row */
					$row++;
					/* Skip Head */
					continue;
				}
				/* Should We Take Fields Info */
				if( $csv_fields_num == TRUE ) {
					$csv_file_data[$row]['fields'] = $num;
				}
				/* Read CSV Fields in Current Row */
				for ( $c = 0; $c < $num; $c++ ) {
					/* CSV Standard Read */
					$csv_file_data[$row][$c] = $data[$c];
					$csv_head_read  = TRUE;
					/* CSV Head Label Logic */
					if( $csv_head_read  == TRUE && $csv_head_label == TRUE ) {
						$head_label = strtolower ( $csv_file_data[0][$c] );
						$head_label_array[] = strtolower ( $csv_file_data[0][$c] );
						$csv_file_data[$row][$head_label] = $data[$c];
						//echo "TESTR---".$row ."=========++++===".$head_label."==|||||====".$data[$c]."<br>";
						//echo "csv_file_data[."$row."][".$head_label."]<br>";
					}
				}
				/*  Next Row */
				$row++;
			} // end while
			// Close the CSV file
			fclose($handle);
		} // end if
		return $csv_file_data;
	}

	function file_error_log($fileErrstr,$city_name){
		$myFile = BASEPATH."uploadsheet/".str_replace("","_",$city_name)."_err_log_file.xls";
		$fh = fopen($myFile, 'w') or die("can't open file");
		$stringData = $fileErrstr;
		fwrite($fh, $stringData);
		//$stringData = "\n\r";
		//fwrite($fh, $stringData);
		fclose($fh);
	}


	function microtime_float(){
       list($usec, $sec) = explode(" ", microtime());
       return ((float)$usec + (float)$sec);
    }
	function convert_timestamp_to_days_hours($diff){
		if($diff > 86400){
         	        $time_span = intval($diff/86400);
                  $time_span_text = "";
                	if($time_span == 1){
                        	$time_span_text .="day ago";
	                }else if($time_span > 1 ){
        	                $time_span_text .="days ago";
                	}
	        }else{
        	        if($diff > 3600){
                	        $time_span = intval($diff/3600);
                        	if($time_span == 1){
                                	$time_span_text .="hour ago";
	                        }else if($time_span > 1 ){
        	                        $time_span_text .="hours ago";
                	        }
	                }else if($diff > 60){
        	                $time_span = intval($diff/60);
                	        if($time_span == 1){
                        	        $time_span_text .="minute ago";
	                        }else if($time_span > 1 ){
        	                        $time_span_text .="minutes ago";
                	        }
	                }else{
        	                $time_span = $diff;
                	        if($time_span == 1){
                        	        $time_span_text .="second ago";
	                        }else if($time_span > 1 ){
        	                        $time_span_text .="seconds ago";
                	        }
                	}
        	}
                $time_span_value = $time_span."<p style='margin:0px;padding:0;'>".$time_span_text."</p>";
      		return $time_span_value;
	}

	function generateRandStr($length){
		  $randstr = "";

		  for($i=0; $i<$length; $i++){
			$randnum = rand(0,61);
			 if($randnum < 10){
				$randstr .= chr($randnum+48);
			 }else if($randnum < 36){
				$randstr .= chr($randnum+55);
			 }else{

				$randstr .= chr($randnum+61);
			 }
		  }
		  $randstr;
		  return $randstr;
	   }

	   function genRandomString() {
			$length = 4;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
			$string = '';

			for ($p = 0; $p < $length; $p++) {
				$string .= $characters[mt_rand(0, strlen($characters))];
			}
			for ($j = 0; $j < $length; $j++) {
				if($string[$j] >='0' && $string[$j]<='9'){
					$flag = 1;
				}else{
					$flag = 0;
				}
			}
			if(strlen($string)<4){
				$string = $string.rand(1,9);
			}
			if($flag!=1){
				$final_string = str_replace($string[strlen($string)-1],rand(1,9),$string);
				//$final_string."<br>";
				return $final_string;
			}else{ return $string;}


		}

	function constructUrl($name,$tolower='1'){
          $name = html_entity_decode($name,ENT_QUOTES,'UTF-8');
          $name = removeSlashes($name);
          $name = str_replace("."," ",$name);
          $pos = strpos($name, "-");
          if ($pos != "") {
	      $name = str_replace(" -","-",$name);
              $name = str_replace("- ","-",$name);
	  }
          $spc = array(" ","  ","   ","    ");
          $name = str_replace($spc," ",$name);
          $name = str_replace("%20"," ",$name);
          $name = str_replace(" ","-",$name);

          //$name = str_replace(array("/",",","$","?","%","#","+","*","_","!","@","'",":","&",";"),"",$name);
          $chars = array("%21","%22","%23","%24","%25","%26","%27","%28","%29","%2A","%2B","%2C","%2D","%2E","%2F","%30","%31","%32","%33","%34","%35","%36","%37","%38","%39","%3A","%3B","%3C","%3D","%3E","%3F","%40","%41","%42","%43","%44","%45","%46","%47","%48","%49","%4A","%4B","%4C","%4D","%4E","%4F","%50","%51","%52","%53","%54","%55","%56","%57","%58","%59","%5A","%5B","%5C","%5D","%5E","%5F","%60","%61","%62","%63","%65","%66","%67","%68","%69","%6A","%6B","%6C","%6D","%6E","%6F","%70","%71","%72","%73","%74","%75","%76","%77","%78","%79","%7A","%7B","%7C","%7D","%7E","%7F","/",",","$","?","%3E","%","#","+","*","_","!","@","'",":",";","&","(",")",".","~","<",">","{","}","|","[","]","=","^","`",'"');
          $cnt_chars = sizeof($chars);
          for($i=0;$i<$cnt_chars;$i++){
             $char = $chars[$i];
             $char1 = "-".$chars[$i];
             $char2 = $chars[$i]."-";
             $cha_arr = array($char,$char1,$char2);
             $name = str_replace($cha_arr,"",$name);
          }
	        $hyp_arr = array("-----","----","---","--","-");
          $name = str_replace($hyp_arr,"-",$name);
	  if(empty($tolower)){
	          return $name;
	  }
          return strtolower($name);
	}

  function isCharAvail($name){
    $is_avail = 0;
    $chars = array("%20"," ","/",",","$","%","#","+","*","_","!","@","'",":",";",".","~","<",">","{","}","|","[","]","^","`",'"',"(",")");
    $cnt_chars = sizeof($chars);
    for($i=0;$i<$cnt_chars;$i++){
        $char = $chars[$i];
        $pos = strpos($name, $char);
        if ($pos != "") {
          $is_avail = 1;
        }
    }
    return $is_avail;
  }

	function priceUnit($price){
		if(($price >= '100000') && ($price < '10000000')){
			$price_val = $price/100000 ;
			$unit = "lakh";
		}elseif($price == '10000000'){
			$price_val = 1;
			$unit = 'crore';
		}elseif(($price > '10000000') && ($price < '220000000')){
			$price_val = $price/10000000 ;
			$unit = 'crore';
		}
		$price_unit = $price_val."@".$unit;
		//return $price_unit;
		return $unit;
	}
	 /**
                * @note function is used to create dir path and used to create directorys structures.
                * @param integer $id.
                * @param string $path.
                * @pre $id must be valid,non-zero integer.
                * @post string $path.
                * return string.
                */
                function create_path($path){
                        if(!$path){ return false;}
                        $path_dirs = explode("/",$path);
                        array_splice($path_dirs, 0, 1);
                        foreach($path_dirs as $dir){
                                if($dir == "") {continue;}
                                $currpath .= "/".$dir;
                                if(!is_dir($currpath)){
                                        @mkdir($currpath,0777,true);
                                        shell_exec("chmod 777 $currpath");
                                }
                        }
                        return $path;
                }
            function arr_conv_numeric_price($value)
            {
            $c_value = array();
            if($value != 0){
                $len = strlen($value);
                if(($len == 6) ||($len == 7))
                {
                    $value = round($value/100000);
                    if($value>20 && $value<=25)
                    {
                        $value = 25;
                    }
                    if($value>25 && $value<=30)
                    {
                        $value = 30;
                    }
                    if($value>30 && $value<=35)
                    {
                        $value = 35;
                    }
                    if($value>35 && $value<=40)
                    {
                        $value = 40;
                    }
                    if($value>40 && $value<=45)
                    {
                        $value = 45;
                    }
                    if($value>45 && $value<=50)
                    {
                        $value = 50;
                    }
                    if($value>50 && $value<=55)
                    {
                        $value = 55;
                    }
                    if($value>55 && $value<=60)
                    {
                        $value = 60;
                    }
                    if($value>60 && $value<=70)
                    {
                        $value = 70;
                    }
                    if($value>70 && $value<=80)
                    {
                        $value = 80;
                    }
                    if($value>80 && $value<=90)
                    {
                        $value = 90;
                    }
                    if($value>90 && $value<=99)
                    {
                        $value = 90;
                    }
                    $c_value[] = $value;
                    $c_value[] = 'lakh';
                }
                if(($len == 8) ||($len == 9))
                {
                    $value = round($value/10000000).'.0';
                    $c_value[] = $value;
                    $c_value[] = 'crore';
                }
            }
            return $c_value;
        }
function arr_reform_numeric_price($value)
        {
            $c_value = array();
            if($value != 0){
                $len = strlen($value);
                if(($len == 6) ||($len == 7))
                {
                    $value = round($value/100000);
                    $c_value[] = $value;
                    $c_value[] = 'lakh';
                }
                if(($len == 8) ||($len == 9))
                {
                    $value = round($value/10000000).'.0';
                    $c_value[] = $value;
                    $c_value[] = 'crore';
                }
            }
            return $c_value;
        }


	function StartDate($start_date){

		$start_date_v=explode("-",$start_date);
		$start_date_v=str_replace(" ",",",$start_date_v);
		$start_date_y=$start_date_v[0];
		$start_date_m=$start_date_v[1];
		$start_date_d=$start_date_v[2];
		$start_date_d=explode(",",$start_date_d);
		$start_date_d=$start_date_d[0];
		$start_date_f=$start_date_y."-".$start_date_m."-".$start_date_d;
		$startDate = strtotime($start_date_f);
		return $startDate;
	}

	function EndDate($end_date){

		$end_date_v=explode("-",$end_date);
		$end_date_v=str_replace(" ",",",$end_date_v);
		$end_date_y=$end_date_v[0];
		$end_date_m=$end_date_v[1];
		$end_date_d=$end_date_v[2];
		$end_date_d=explode(",",$end_date_d);
		$end_date_d=$end_date_d[0];
		$end_date_f=$end_date_y."-".$end_date_m."-".$end_date_d;
		$endDate = strtotime($end_date_f);
		return $endDate;
	}

	function arrGetDbYear($category_id){
		require_once(CLASSPATH.'DbConn.php');
		require_once(CLASSPATH.'year.class.php');
		$dbconn = new DbConn;
		$year = new year;
		$result = $year->arrGetYear("",$category_id);
		$cnt = sizeof($result);
		for($i=0;$i<$cnt;$i++){
			$year = $result[$i]['year'];
			if(!empty($year)){
				$yearresult['year'][] = $year;
			}
		}
		return $yearresult;
	}

	function getCookie(){
		$xml="";
		$email = $_COOKIE['email'];
		$fname = $_COOKIE['first_name'];
		$lname = $_COOKIE['last_name'];
		$uid = $_COOKIE['uid'];

		$xml.='<LOGIN_DETAILS>';
                if(!empty($uid)){
                        $xml .= '<IS_LOGIN><![CDATA[1]]></IS_LOGIN>';
		}else{
			$xml .= '<IS_LOGIN><![CDATA[0]]></IS_LOGIN>';
		}
                $xml .= '<EMAIL><![CDATA['.$email.']]></EMAIL>';
                $xml .= '<FIRST_NAME><![CDATA['.$fname.']]></FIRST_NAME>';
                $xml .= '<LAST_NAME><![CDATA['.$lname.']]></LAST_NAME>';
                $xml .= '<USER_ID><![CDATA['.$uid.']]></USER_ID>';
                $xml .= '</LOGIN_DETAILS>';

		return $xml;
	}

	function delCookie(){
		$arr_cookie = array('email','first_name','last_name','sig','uid');
		foreach($arr_cookie as $k=>$v){
			setcookie($v, '', time()-3600, "/", DOMAIN);
                }
		//print"<pre>";print_r($_COOKIE);print"</pre>";exit;

	}

function getPriceBarValue($min_val, $max_val){

    $sliderPriceArr = Array('100000','200000','300000','400000','500000','600000','700000','800000','900000','1000000','1100000','1200000','1300000','1400000','1500000','1600000','1700000','1800000','1900000','2000000','2500000','3000000','3500000','4000000','4500000','5000000','5500000','6000000','7000000','8000000','9000000','10000000','12500000','15000000','20000000','25000000','30000000','35000000','40000000','45000000','50000000','60000000','70000000','80000000','90000000','100000000','110000000','120000000','130000000','140000000','150000000','160000000','170000000','180000000','190000000','200000000','210000000','220000000');

    $pricelistinArr = array("100000"=>"1","200000"=>"2","300000"=>"3","400000"=>"4","500000"=>"5","600000"=>"6","700000"=>"7","800000"=>"8","900000"=>"9","1000000"=>"10","1100000"=>"11","1200000"=>"12","1300000"=>"13","1400000"=>"14","1500000"=>"15","1600000"=>"16","1700000"=>"17","1800000"=>"18","1900000"=>"19","2000000"=>"20","2500000"=>"25","3000000"=>"30","3500000"=>"35","4000000"=>"40","4500000"=>"45","5000000"=>"50","5500000"=>"55","6000000"=>"60","7000000"=>"70","8000000"=>"80","9000000"=>"90","10000000"=>"1.0","12500000"=>"1.25","15000000"=>"1.5","20000000"=>"2.0","25000000"=>"2.5","30000000"=>"3.0","35000000"=>"3.5","40000000"=>"4.0","45000000"=>"4.5","50000000"=>"5.0","60000000"=>"6.0","70000000"=>"7.0","80000000"=>"8.0","90000000"=>"9.0","100000000"=>"10.0","110000000"=>"11.0","120000000"=>"12.0","130000000"=>"13.0","140000000"=>"14.0","150000000"=>"15.0","160000000"=>"16.0","170000000"=>"17.0","180000000"=>"18.0","190000000"=>"19.0","200000000"=>"20.0","210000000"=>"21.0","220000000"=>"22.0");

	$flipPricelistinArr = array_flip($pricelistinArr);
    array_push($sliderPriceArr,$min_val);
    $min_val_arr = $sliderPriceArr;
    array_pop($sliderPriceArr);
    sort($min_val_arr, SORT_NUMERIC);


    array_push($sliderPriceArr,$max_val);
    $max_val_arr = $sliderPriceArr;
    array_pop($sliderPriceArr);
    sort($max_val_arr, SORT_NUMERIC);

    $min_arr_keys = array_keys($min_val_arr, $min_val);
    $max_arr_keys = array_keys($max_val_arr, $max_val);

    $min_key = $min_arr_keys[0];
    $max_key = $max_arr_keys[0];

    $c_value = array();

    if($min_key == 0){
      $c_value['min_price_val'] = "1";
	  $c_value['min_converted_price'] = $flipPricelistinArr['1'];
      $c_value['min_price_unit'] = "lakh";
    }else if($min_key == ((sizeof($min_val_arr))-1)){
      $c_value['min_price_val'] = "22";
	  $c_value['min_converted_price'] = $flipPricelistinArr['22'];
      $c_value['min_price_unit'] = "crore";
    }else{
      $pos = $min_key-1;
      $min_price = $min_val_arr[$pos];
	  $c_value['min_converted_price'] = $min_price;
      $c_value['min_price_val'] = $pricelistinArr[$min_price];
      if($min_price < 10000000){
        $c_value['min_price_unit'] = "lakh";
      }else{
        $c_value['min_price_unit'] = "crore";
      }
    }

    if($max_key == 0){
      $c_value['max_price_val'] = "1";
	  $c_value['max_converted_price'] = $flipPricelistinArr['1'];
      $c_value['max_price_unit'] = "lakh";
    }else if($max_key == ((sizeof($max_val_arr))-1)){
      $c_value['max_price_val'] = "22";
	  $c_value['max_converted_price'] = $flipPricelistinArr['22'];
      $c_value['max_price_unit'] = "crore";
    }else{
      $pos = $max_key+1;
      $max_price = $max_val_arr[$pos];
	  $c_value['max_converted_price'] = $max_price;
      $c_value['max_price_val'] = $pricelistinArr[$max_price];
      if($max_price < 10000000){
        $c_value['max_price_unit'] = "lakh";
      }else{
        $c_value['max_price_unit'] = "crore";
      }
    }

    return $c_value;
}

function numeric_price_reform_arr($value){
  $pricelistinArr = array("100000"=>"1","200000"=>"2","300000"=>"3","400000"=>"4","500000"=>"5","600000"=>"6","700000"=>"7","800000"=>"8","900000"=>"9","1000000"=>"10","1100000"=>"11","1200000"=>"12","1300000"=>"13","1400000"=>"14","1500000"=>"15","1600000"=>"16","1700000"=>"17","1800000"=>"18","1900000"=>"19","2000000"=>"20","2500000"=>"25","3000000"=>"30","3500000"=>"35","4000000"=>"40","4500000"=>"45","5000000"=>"50","5500000"=>"55","6000000"=>"60","7000000"=>"70","8000000"=>"80","9000000"=>"90","10000000"=>"1.0","12500000"=>"1.25","15000000"=>"1.5","20000000"=>"2.0","25000000"=>"2.5","30000000"=>"3.0","35000000"=>"3.5","40000000"=>"4.0","45000000"=>"4.5","50000000"=>"5.0","60000000"=>"6.0","70000000"=>"7.0","80000000"=>"8.0","90000000"=>"9.0","100000000"=>"10.0","110000000"=>"11.0","120000000"=>"12.0","130000000"=>"13.0","140000000"=>"14.0","150000000"=>"15.0","160000000"=>"16.0","170000000"=>"17.0","180000000"=>"18.0","190000000"=>"19.0","200000000"=>"20.0","210000000"=>"21.0","220000000"=>"22.0");
  $c_value = array();
  if($value != 0){
    $len = strlen($value);
    if(($len == 6) ||($len == 7)){
       $value = $pricelistinArr[$value];
       $c_value[] = $value;
       $c_value[] = 'lakh';
    }
    if(($len == 8) ||($len == 9)){
       $value = $pricelistinArr[$value];
       $c_value[] = $value;
       $c_value[] = 'crore';
    }
  }
  return $c_value;
}
function processEmailTemplate($file_path,$transArr){
	$defaulttransArr = array('%IMAGE_URL%'=>IMAGE_URL,'%WEB_URL%'=>ONCARS_WEB_URL,'%MAIL_TEMPLATE_EMAIL_ID_HREF%'=>MAIL_TEMPLATE_EMAIL_ID_HREF,'%MAIL_TEMPLATE_EMAIL_ID%'=>MAIL_TEMPLATE_EMAIL_ID,'%MAIL_TEMPLATE_FOOTER_TEXT%'=>MAIL_TEMPLATE_FOOTER_TEXT,'%MAIL_TEMPLATE_GREETING_TEXT%'=>MAIL_TEMPLATE_GREETING_TEXT);
	$str = file_get_contents($file_path);
	$str = strtr($str, $transArr);
	$str = strtr($str, $defaulttransArr);
	return $str;
}
function processTemplate($file_path,$transArr){
	$str = file_get_contents($file_path);
	$str = strtr($str, $transArr);
	return $str;
}
function onroadpricetemplate($brand_name,$model_name,$variant,$price,$city_name){
	$transArr = array('%BRAND_NAME%'=>$brand_name,'%CAR_MODEL%'=>$model_name,'%CAR_VARIANT%'=>$variant,'%TOTAL_ON_ROAD_PRICE%'=>$price,'%CITY_NAME%'=>$city_name);
	$file_path = BASEPATH.'lmstemplate/onroadprice.html';
	return $str = processTemplate($file_path,$transArr);
}
function emailonroadpricetemplate($brand_name,$model_name,$variant,$price,$city_name,$user_name){
	$transArr = array('%BRAND_NAME%'=>$brand_name,'%CAR_MODEL%'=>$model_name,'%CAR_VARIANT%'=>$variant,'%TOTAL_ON_ROAD_PRICE%'=>$price,'%CITY_NAME%'=>$city_name,'%USER_NAME%'=>$user_name);
	$file_path = BASEPATH.'lmstemplate/emailonroadprice.html';
	return $str = processEmailTemplate($file_path,$transArr);
}
function oncarsemailonroadpricetemplate($brand_name,$model_name,$variant,$price,$city_name,$user_name){
	$transArr = array('%BRAND_NAME%'=>$brand_name,'%CAR_MODEL%'=>$model_name,'%CAR_VARIANT%'=>$variant,'%TOTAL_ON_ROAD_PRICE%'=>$price,'%CITY_NAME%'=>$city_name,'%USER_NAME%'=>$user_name);
	$file_path = BASEPATH.'emailtemplate/onroadprice.html';
	return $str = processEmailTemplate($file_path,$transArr);
}
function dealertemplate($model_name,$variant,$user_name,$mobile,$email,$request_type){
	$transArr = array('%MODEL%'=>$model_name,'%VARIANT%'=>$variant,'%NAME%'=>$user_name,'%MOBILE%'=>$mobile,'%EMAIL%'=>$email,'%REQUEST_TYPE%'=>$request_type);
	$file_path = BASEPATH.'lmstemplate/dealer.html';
	return $str = processTemplate($file_path,$transArr);
}
function emaildealertemplate($model_name,$variant,$user_name,$mobile,$email,$request_type){
	$transArr = array('%MODEL%'=>$model_name,'%VARIANT%'=>$variant,'%USER_NAME%'=>$user_name,'%CONTACTNO%'=>$mobile,'%EMAIL%'=>$email,'%REQUEST%'=>$request_type);
	$file_path = BASEPATH.'lmstemplate/emaildealer.html';
	return $str = processEmailTemplate($file_path,$transArr);
}
function emaildealertoaddresstemplate($user_name,$mobile,$email,$request_type){
	$transArr = array('%USER_NAME%'=>$user_name,'%CONTACTNO%'=>$mobile,'%EMAIL%'=>$email,'%REQUEST%'=>$request_type);
	$file_path = BASEPATH.'lmstemplate/emailtodealeraddress.html';
	return $str = processEmailTemplate($file_path,$transArr);
}
function dealeraddresstemplate($user_name,$mobile,$email,$request_type){
	$transArr = array('%NAME%'=>$user_name,'%MOBILE%'=>$mobile,'%EMAIL%'=>$email,'%REQUEST_TYPE%'=>$request_type);
	$file_path = BASEPATH.'lmstemplate/dealeraddress.html';
	return $str = processTemplate($file_path,$transArr);
}
function emaildealeraddresstemplate($user_name,$message){
	$transArr = array('%NAME%'=>$user_name,'%MESSAGE%'=>nl2br($message));
	$file_path = BASEPATH.'lmstemplate/emaildealeraddress.html';
	return $str = processEmailTemplate($file_path,$transArr);
}
function verfiycodetemplate(){
}
function emailverfiycodetemplate(){
}
function dealeraddtemplate(){
}
function emaildealeraddtemplate(){
}
function dealeredittemplate(){
}
function emaildealeredittemplate(){
}

function array_keys_multi($array,&$vals){
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        array_keys_multi($value,$vals);
      }else{
        $vals[] = $value;
      }
    }
    return $vals;
  }


function testDriveBreadCrumb($testdriveurl,$breadcrumb_str){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url"><span class="link-act" itemprop="title">New Cars</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$_SERVER['SCRIPT_URI'].'" itemprop="url"><span class="link-act" itemprop="title">Book Test Drive</span></a>';
	if(!empty($breadcrumb_str)){
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$breadcrumb_str;
	}
	$new_breadcrumb .= '</span></span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function onRoadPriceBreadCrumb($onraodurl,$breadcrumb_str){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home<span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url"><span class="link-act" itemprop="title">New Cars</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_GET_ON_ROAD_PRICE.'" itemprop="url"><span class="link-act" itemprop="title">Get On Road Price</span></a>';

	if(!empty($breadcrumb_str)){
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$breadcrumb_str.'</span>';
	}

	$new_breadcrumb .= '</span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function carResearchBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span itemprop="title" class="link-act">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">New Cars</span>';
	$new_breadcrumb .= '</span></span>';
	$new_breadcrumb .= '<div class="clear"></div></div>';
	return $new_breadcrumb;
}

function carCompareBreadCrumb($seotitle){
	if(!empty($seotitle)){
		$new_breadcrumb .= '<div class="breadcumbs">';
		$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" class="home"><a href="'.WEB_URL.'" itemprop="url" class="home"></a></a>';
		$new_breadcrumb .= '<span class="brdcrum-arr"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$seotitle;
		$new_breadcrumb .= '</span></span></span>';
		$new_breadcrumb .= '<div class="clear"></div></div>';
	}else{
		$new_breadcrumb .= '<div class="breadcumbs">';
		$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" ><span class="link-act" itemprop="title">Home</span></a>';
		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Compare Cars</span>';
		$new_breadcrumb .= '</span></span>';
		$new_breadcrumb .= '<div class="clear"></div></div>';

	}
	return $new_breadcrumb;
}

function brandPageBreadCrumb($nav_brand_result,$seo_brand_name,$top_brand_arr,$brand_id){
	$pricequote_campaign_arr = array(3,4,5,6,8,13,15);
	if($_COOKIE['pageviews']!=''){
		$get_pageview = explode("_",$_COOKIE['pageviews']);
		$page_viewcount = $get_pageview[1];
		if($get_pageview[0]== $user_ip_address){
			$user_pageviews = $get_pageview[1] + 1;
		}
	}
	$dbpageviewscnt = $_COOKIE['pageviewscnt'];
	if(is_array($nav_brand_result)){
		foreach($nav_brand_result as $bkry=>$bValue){
			if(in_array($bValue['brand_id'],$top_brand_arr)){
				$set_key = array_search($bValue['brand_id'], $top_brand_arr);
				$bBrandArr1[$set_key] = $bValue;
			}else{
				$bBrandArr2[] = $bValue;
			}
		}
		ksort($bBrandArr1);
		unset($result);
		if(is_array($bBrandArr1) && is_array($bBrandArr2)){
			$result = array_merge($bBrandArr1,$bBrandArr2);
		}
	}

	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" itemprop="title">Home</a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'Newcar-Search" itemprop="url" itemprop="title">
	New Cars</a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
	<div id="breadcrumb_sub_brand" class="b_s_home"><span>Brand</span><i class="b_s_direction"></i><div class="clear"></div>';
	$new_breadcrumb .= '<div class="bredcrumb_submenu" id="breadcrumb_brand"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Brand BreadCrumb Nav\', \'click\', \'Brand BreadCrumb Nav - Brand Page Link\']);"><div class="browsemenus">';
	foreach($result as $bbkey=>$brandValue){
		$seo_brand_url = WEB_URL.'car-brands/'.constructUrl( $brandValue['brand_name']);
		$new_breadcrumb .= '<a href='.$seo_brand_url.' onclick="sponserAdWindow(\''.$brandValue['brand_id'].'\',\''.PRICEQUOTE_CAMPAIGN_STR.'\');"><span class="link-act" itemprop="title">'.$brandValue['brand_name'].'</span></a>';
		if($bbkey > 0 && $bbkey%8 == 0){
			$new_breadcrumb .= '</div><div class="browsemenus">';
		}
	}
	$new_breadcrumb .= '</div><div class="clear"></div></div></div></div><span>:</span>';
	$new_breadcrumb .='<a href="'.WEB_URL.'car-brands/'.constructUrl( $seo_brand_name).'" itemprop="url" onclick="sponserAdWindow(\''.$brand_id.'\',\''.PRICEQUOTE_CAMPAIGN_STR.'\');" itemprop="title"><span class="link-act" itemprop="title">'.str_replace('-', ' ', $seo_brand_name).'</a>';
	$new_breadcrumb .= '</span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function modelPageBreadCrumbold($category_id,$brdresult,$top_brand_arr,$product_info_brand_id,$product_brand_name,$product_name_id,$search_product_info_name,$model_upcoming_status){
	if($_COOKIE['pageviews']!=''){
		$get_pageview = explode("_",$_COOKIE['pageviews']);
		$page_viewcount = $get_pageview[1];
		if($get_pageview[0]== $user_ip_address){
			$user_pageviews = $get_pageview[1] + 1;
		}
	}
	$dbpageviewscnt = $_COOKIE['pageviewscnt'];
	if(is_array($brdresult)){
	foreach($brdresult as $bkry=>$bValue){
		if(in_array($bValue['brand_id'],$top_brand_arr)){
			$set_key = array_search($bValue['brand_id'], $top_brand_arr);
			$bBrandArr1[$set_key] = $bValue;
		}else{
			$bBrandArr2[] = $bValue;
		}
	}
	ksort($bBrandArr1);
	unset($result);
	if(is_array($bBrandArr1) && is_array($bBrandArr2)){
		$result = array_merge($bBrandArr1,$bBrandArr2);
	}
	}
	require_once(CLASSPATH.'product.class.php');
	$oProduct 	= 	new ProductManagement;
	$brand_model_result = $oProduct->arrGetProductNameInfo("",$category_id,$product_info_brand_id,"","1","","","","","1","1");

	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" itemprop="title"><span class="link-act" itemprop="title">Home</span></a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.$_REQUEST['cat_path'].'/Search" itemprop="url">
	<span class="link-act" itemprop="title">'.$_REQUEST['category_name'].'</span></a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	if($model_upcoming_status == 1){
		$new_breadcrumb .= '<span itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'upcoming-cars" itemprop="url"><span class="link-act" itemprop="title">Upcoming Cars</span></a>';
		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$product_brand_name.' '.$search_product_info_name.'</span>';
		$new_breadcrumb .= '</span></span></span></span>';
	}else{

		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
		<div id="breadcrumb_sub_brand" class="b_s_home"><span>Brand</span><i class="b_s_direction"></i><div class="clear"></div>';
		$new_breadcrumb .= '<div class="bredcrumb_submenu" id="breadcrumb_brand"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Brand BreadCrumb Nav\', \'click\', \'Brand BreadCrumb Nav - Brand Page Link\']);"><div class="browsemenus">';
		foreach($result as $bbkey=>$brandValue){
				if($bbkey > 0 && $bbkey%8 == 0){
					$new_breadcrumb .= '</div><div class="browsemenus">';
				}
				$seo_brand_url = WEB_URL.'car-brands/'.constructUrl( $brandValue['brand_name']);
				$new_breadcrumb .= '<a href='.$seo_brand_url.' onclick="sponserAdWindow(\''.$brandValue['brand_id'].'\',\''.PRICEQUOTE_CAMPAIGN_STR.'\');" itemprop="url"><span class="link-act" itemprop="title">'.$brandValue['brand_name'].'</span></a>';


		   }
		$new_breadcrumb .= '</div><div class="clear"></div></div></div></div><span>:</span>';
		$new_breadcrumb .='<a href="'.WEB_URL.'car-brands/'.constructUrl( $product_brand_name).'" itemprop="url" onclick="sponserAdWindow(\''.$product_info_brand_id.'\',\''.PRICEQUOTE_CAMPAIGN_STR.'\');"><span class="link-act" itemprop="title">'.$product_brand_name.'</span></a>';
		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><div id="breadcrumb_sub_model" class="b_s_home"><span>Model</span><i class="b_s_direction"></i><div class="clear"></div><div class="bredcrumb_submenu" id="breadcrumb_model"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Model BreadCrumb Nav\', \'click\', \'Model BreadCrumb Nav - Model Page Link\']);"><div class="browsemenus">';
		foreach($brand_model_result as $mmkey=>$modelValue){
				if($mmkey > 0 &&  $mmkey%8 == 0){
					$new_breadcrumb .= '</div><div class="browsemenus">';
				}
				$seo_model_url = WEB_URL.constructUrl($product_brand_name).'-cars/'.constructUrl($product_brand_name).'-'.constructUrl( $modelValue['product_info_name']).'/Model/'.constructUrl($modelValue['product_info_name']).'/'.$modelValue['product_name_id'];
				$new_breadcrumb .= '<a href='.$seo_model_url.' itemprop="url"><span class="link-act" itemprop="title">'.$modelValue['product_info_name'].'</span></a>';
	   	}
		$new_breadcrumb .= '</div><div class="clear"></div></div></div></div><span> :</span>';
		$seo_curr_model_url = WEB_URL.constructUrl($product_brand_name).'-cars/'.constructUrl($product_brand_name).'-'.constructUrl( $search_product_info_name).'/Model/'.constructUrl($search_product_info_name).'/'.$product_name_id;

		$new_breadcrumb .= '<span itemprop="title"> '.$search_product_info_name.'</span>';
		$new_breadcrumb .= '</span></span></span></span>';
	}
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function modelPageBreadCrumb($category_id,$brdresult,$top_brand_arr,$product_info_brand_id,$product_brand_name,$product_name_id,$search_product_info_name,$model_upcoming_status,$action){
	if($_COOKIE['pageviews']!=''){
		$get_pageview = explode("_",$_COOKIE['pageviews']);
		$page_viewcount = $get_pageview[1];
		if($get_pageview[0]== $user_ip_address){
			$user_pageviews = $get_pageview[1] + 1;
		}
	}
	$dbpageviewscnt = $_COOKIE['pageviewscnt'];

	require_once(CLASSPATH.'product.class.php');
	$oProduct 	= 	new ProductManagement;
	$brand_model_result = $oProduct->arrGetProductNameInfo("",$category_id,$product_info_brand_id,"","1","","","","","1","1");
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span itemprop="title" class="link-act">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.$_REQUEST['cat_path'].'/search" itemprop="url" ><span class="link-act" itemprop="title">'.$_REQUEST['category_name'].'</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	if($model_upcoming_status == 1){
		$new_breadcrumb .= '<span itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'upcoming-cars" itemprop="url"><span itemprop="title" class="link-act">Upcoming Cars</span></a>';
	/*$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'upcoming-cars" itemprop="url" itemprop="title">Upcoming Cars</a>';*/
		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$product_brand_name.' '.$search_product_info_name.'</span>';
		$new_breadcrumb .= '</span></span></span></span>';
	}else{
		$new_breadcrumb .= '<span itemprop="child" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">';
		$new_breadcrumb .='<a href="'.WEB_URL.$_REQUEST['cat_path'].'/'.constructUrl( $product_brand_name).'" itemprop="url" onclick="sponserAdWindow(\''.$product_info_brand_id.'\',\''.PRICEQUOTE_CAMPAIGN_STR.'\');" itemprop="title"><span class="link-act" itemprop="title">'.$product_brand_name.'</span></a>';
		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
		$seo_curr_model_url = WEB_URL.$_REQUEST['cat_path'].'/'.constructUrl($product_brand_name).'/'.constructUrl( $search_product_info_name);
		if($action=="all_review" || $action=="expert_review" || $action=="user_review"|| $action=="model_color" ){
			$new_breadcrumb .= '<a href="'.$seo_curr_model_url.'" itemprop="url"><span class="link-act" itemprop="title"> '.$search_product_info_name.'</span></a>';
		}else{
			$new_breadcrumb .= '<span itemprop="title"> '.$search_product_info_name.'</span>';
		}
		if($action=="all_review"){
			$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span><span itemprop="title">Reviews</span>';
		}elseif($action=="expert_review"){
			$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span><span itemprop="title">Expert Reviews</span>';
		}elseif($action=="user_review"){
                        $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span><span itemprop="title">User Reviews</span>';
                }elseif($action=="model_color"){
                        $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span><span itemprop="title">Colors</span>';
                }

		$new_breadcrumb .= '</span></span></span></span>';
	}
 	$new_breadcrumb .= '<div class="clear"></div></div>';
	return $new_breadcrumb;
}

function carDetailBreadCrumb($category_id,$brdresult,$top_brand_arr,$rating_brand_id,$product_brand_name,$seo_model_id,$product_info_dispname,$rev_product_id,$product_variant_name,$selected_city_id,$action,$year){
	if($_COOKIE['pageviews']!=''){
		$get_pageview = explode("_",$_COOKIE['pageviews']);
		$page_viewcount = $get_pageview[1];
		if($get_pageview[0]== $user_ip_address){
			$user_pageviews = $get_pageview[1] + 1;
		}
	}
	$dbpageviewscnt = $_COOKIE['pageviewscnt'];
		if(is_array($brdresult)){
			foreach($brdresult as $bkry=>$bValue){
				if(in_array($bValue['brand_id'],$top_brand_arr)){
					$set_key = array_search($bValue['brand_id'], $top_brand_arr);
					$bBrandArr1[$set_key] = $bValue;
				}else{
					$bBrandArr2[] = $bValue;
				}
			}
			ksort($bBrandArr1);
			unset($result);
			if(is_array($bBrandArr1) && is_array($bBrandArr2)){
				$result = array_merge($bBrandArr1,$bBrandArr2);
			}
		}
		require_once(CLASSPATH.'product.class.php');
		require_once(CLASSPATH.'price.class.php');
		$oProduct=new ProductManagement;
		$oPrice=new price;
		#$brand_model_result = $oProduct->arrGetProductNameInfo("",$category_id,$rating_brand_id,"","1","","","","","","1");
		#$variant_result = $oProduct->arrGetProductByName($product_info_dispname,"","","","","","","1");
		if(!empty($variant_result)){
			foreach($variant_result as $pkey=>$pValue){
				#$is_price_result = $oPrice->arrGetPriceDetails(1,$pValue['product_id'],$category_id);
				if(is_array($is_price_result)){
					$variant_dataresult[] = $pValue;
				}
			}
		}
	$new_breadcrumb .= '<div class="breadcumbs">';
$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'Newcar-Search" itemprop="url"><span class="link-act" itemprop="title">
New Cars</span></a>';
$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
$new_breadcrumb .='<a href="'.WEB_URL.constructUrl($product_brand_name).'" itemprop="url"><span class="link-act" itemprop="title">'.$product_brand_name.'</span></a>';
$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
$seo_curr_model_url = WEB_URL.constructUrl($product_brand_name).'/'.constructUrl($product_info_dispname);
$new_breadcrumb .= '<a href="'.$seo_curr_model_url.'" itemprop="url"><span class="link-act" itemprop="title">'.$product_info_dispname.'</span></a>';
$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
$seo_curr_variant_url = WEB_URL.constructUrl($product_brand_name).'/'.constructUrl($product_info_dispname).'/'.constructUrl( $product_variant_name);
if($year!=''){
	$seo_curr_variant_url .= "/".$year;
}

if($action=="all_review" || $action=="expert_review" || $action=="user_review" || $action=="expert_review_detail" || $action=="user_review_detail" || $action=="variant_color" || $action=="features"){
	$new_breadcrumb .= ' <a href="'.$seo_curr_variant_url.'" itemprop="url"><span class="link-act" itemprop="title">'.$product_variant_name.'</span></a>';
}else{
	 $new_breadcrumb .= ' <span itemprop="title">'.$product_variant_name.'</span>';
}
if($action=="all_review"){
        $new_breadcrumb .= ' <span class="breadcrumb sprit-icon"></span><span itemprop="title">Reviews</span>';
}elseif($action=="expert_review" || $action=="expert_review_detail"){
         $new_breadcrumb .= ' <span class="breadcrumb sprit-icon"></span><span itemprop="title">Expert Reviews</span>';
}elseif($action=="user_review" || $action=="user_review_detail"){
         $new_breadcrumb .= ' <span class="breadcrumb sprit-icon"></span><span itemprop="title">User Reviews</span>';
}elseif($action=="variant_color"){
         $new_breadcrumb .= ' <span class="breadcrumb sprit-icon"></span><span itemprop="title">Colors</span>';
}elseif($action=="features"){
         $new_breadcrumb .= ' <span class="breadcrumb sprit-icon"></span><span itemprop="title">Features</span>';
}


$new_breadcrumb .= '</span></span></span></span>';
return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function CarDealersBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" itemprop="title">Home</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'Newcar-Search" itemprop="url" itemprop="title">
	New Cars</a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Locate a Dealer</span>';
	$new_breadcrumb .= '</span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function CarDealersListBreadCrumb($selected_city_id,$selected_brand_id,$selected_city_name,$selected_brand_name,$brand_page_url,$city_page_url){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" itemprop="title">Home</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	/*$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$_SERVER['PHP_SELF'].'" itemprop="url">
	<span itemprop="title">Dealer List</span></a>';
	$new_breadcrumb .= '<span>/</span>';
	*/
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url" itemprop="title">
	New Cars</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEARCH_CAR_DEALERS.'" itemprop="url" itemprop="title">
	Car Dealers</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	if(!empty($selected_city_id) && empty($selected_brand_id)){
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$selected_city_name.'</span>';
	}elseif(empty($selected_city_id) && !empty($selected_brand_id)){
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$selected_brand_name.'  Dealers</span>';
	}elseif(!empty($selected_city_id) && !empty($selected_brand_id)){
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$city_page_url.'" itemprop="url" itemprop="title">'.$selected_city_name.'</a>';
		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$selected_brand_name.'  Dealers</span>';
		$new_breadcrumb .= '</span>';
	}
	$new_breadcrumb .= '</span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function CarDealersDetailBreadCrumbold($city_page_url,$selected_city_name,$brand_page_url,$selected_brand_name,$page_url,$selected_dealer_name){

	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" itemprop="title">Home</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url" itemprop="title">
	New Cars</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEARCH_CAR_DEALERS.'" itemprop="url" itemprop="title">
	Car Dealers</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$city_page_url.'" itemprop="url"><span itemprop="title">'.$selected_city_name.'</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$brand_page_url.'" itemprop="url"><span itemprop="title">'.$selected_brand_name.'</span></a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$selected_dealer_name.'</span>';

	$new_breadcrumb .= '</span></span></span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}
function CarDealersDetailBreadCrumb($city_page_url,$selected_city_name,$brand_page_url,$selected_brand_name,$page_url,$selected_dealer_name){

	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" itemprop="title">Home</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url" itemprop="title">
	New Cars</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEARCH_CAR_DEALERS.'" itemprop="url">Car Dealers</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$city_page_url.'" itemprop="url">'.$selected_city_name.'</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$brand_page_url.'" itemprop="url">'.$selected_brand_name.'</a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$selected_dealer_name.'</span>';

	$new_breadcrumb .= '</span></span></span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}


function CarPremiumDealersDetailBreadCrumb($city_page_url,$selected_city_name,$brand_page_url,$selected_brand_name,$page_url,$selected_dealer_name){

	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" itemprop="title">Home</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url" itemprop="title">
	New Cars</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEARCH_CAR_DEALERS.'" itemprop="url">Car Dealers</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$city_page_url.'" itemprop="url">'.$selected_city_name.'</a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$brand_page_url.'" itemprop="url">'.$selected_brand_name.'</a>';

	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$selected_dealer_name.'</span>';

	$new_breadcrumb .= '</span></span></span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

/*
function carFinderBreadCrumb(){

	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span>/</span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">All Cars';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}
*/

function carFinderBreadCrumb(){

	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<b></b>';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span itemprop="title" class="link-act">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Car Research</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function carBrandBreadCrumb($brand_name){
        
$new_breadcrumb .= '<div class="breadcumbs">';
$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" class="home"></a>';
$new_breadcrumb .= '<span class="brdcrum-arr"></span>';
$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.$_REQUEST['cat_path'].'/search" itemprop="url"><span class="link-act" itemprop="title">'.$_REQUEST['category_name'].'</span></a>';
$new_breadcrumb .= '<span class="brdcrum-arr"></span>';
$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$brand_name.'</span>';
$new_breadcrumb .= '</span></span></span>';
return $new_breadcrumb .= '<div class="clear"></div></div>';
}


function carComparisonListBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs"> ';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url"><span class="link-act" itemprop="title">New Cars</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Oncars Comparison</span></a>';
	/*<a href="'.WEB_URL.ONCARS_HOT_COMPARISONS.'" itemprop="url"><span class="link-act" itemprop="title">Oncars Comparison</span></a>*/
	$new_breadcrumb .= '</span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function WriteReviewBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Write Review</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function UserReviewBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" class="home"><span class="link-act" itemprop="title"></span></a>';
	$new_breadcrumb .= '<span class="brdcrum-arr"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">User Reviews & Ratings</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function carReviewsBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" ><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Reviews</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function newsDetailBreadCrumb($newstitle){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_AUTO_NEWS.'" itemprop="url"><span class="link-act" itemprop="title">News</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$newstitle;
	$new_breadcrumb .= '</span></span></span>';
	//$new_breadcrumb .= '</span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function newsListBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">News</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function articleDetailBreadCrumb($title,$sChildUrl,$sChildType){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_AUTO_ARTICLE.'" itemprop="url"><span class="link-act" itemprop="title">Articles</span></a>';
	if(strlen($sChildUrl)>0 && strlen($sChildType)>0){
		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$sChildUrl.'" itemprop="url"><span class="link-act" itemprop="title">'.$sChildType.'</span></a>';
	}
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$title;
	$new_breadcrumb .= '</span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function articleListBreadCrumb($sArticleName){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	if(empty($sArticleName)){
			$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Articles</span>';
	}else{
		$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_AUTO_ARTICLE.'" itemprop="url"><span class="link-act" itemprop="title">Articles</span></a>';

		$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
		$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$sArticleName;
		$new_breadcrumb .= '</span></span></span></span>';

	}
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function allArticleListBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">All Article & Guides</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function wallpeperListBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Wallpapers</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function slidesShowListBreadCrumb(){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Car Photos</span>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function  slidesShowBreadCrumb($main_slide_title){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_SLIDESHIOW_LIST.'" itemprop="url"><span class="link-act" itemprop="title">Car photos</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$main_slide_title;
	$new_breadcrumb .= '</span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function videoListBreadCrumb($breadcrum_title){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'car-videos" itemprop="url"><span class="link-act" itemprop="title">Car videos</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$breadcrum_title;
	$new_breadcrumb .= '</span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function videosBreadCrumb($breadcrum_title){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Car videos</span></a>';
	$new_breadcrumb .= '</span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function carInfoDetailBreadCrumb($brand_name,$product_name,$sVariant){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$name_disp_brd = constructUrl($brand_name)."/".constructUrl($product_name)."/".constructUrl($sVariant)."/";
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.$name_disp_brd.SEO_GET_ON_ROAD_PRICE.'" itemprop="url"><span class="link-act" itemprop="title">Get On Road Price</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$brand_name." ".$product_name." ".$sVariant;
	$new_breadcrumb .= '</span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';

}

function testDriveThankyouBreadCrumb($testdriveurl,$breadcrumb_str){
	$new_breadcrumb .= '<div class="breadcumbs">';
	$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url"><span class="link-act" itemprop="title">New Cars</span></a>';
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$testdriveurl.'" itemprop="url">
	<span class="link-act" itemprop="title">Book Test Drive</span></a>';
	if(!empty($breadcrumb_str)){
	$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
	$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$breadcrumb_str;
	}
	$new_breadcrumb .= '</span></span></span></span></span>';
	return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function emiCalculatorPageBreadCrumb($category_id="",$brdresult="",$top_brand_arr="",$brand_id="",$brand_name="",$product_name_id="",$product_info_name="",$product_id="",$product_name=""){
        $new_breadcrumb = "";
        if(($product_id != "") && ($product_name_id != "")){//From Variant Page
                if(is_array($brdresult)){
                        foreach($brdresult as $bkry=>$bValue){
                                if(in_array($bValue['brand_id'],$top_brand_arr)){
                                        $set_key = array_search($bValue['brand_id'], $top_brand_arr);
                                        $bBrandArr1[$set_key] = $bValue;
                                }else{
                                        $bBrandArr2[] = $bValue;
                                }
                        }
                        ksort($bBrandArr1);
                        unset($result);
                        if(is_array($bBrandArr1) && is_array($bBrandArr2)){
                                $result = array_merge($bBrandArr1,$bBrandArr2);
                        }
                }
                require_once(CLASSPATH.'product.class.php');
                require_once(CLASSPATH.'price.class.php');
                $oProduct       =       new ProductManagement;
                $oPrice         =       new price;
        $brand_model_result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id);
                $variant_result = $oProduct->arrGetProductByName($product_info_name);
                if(!empty($variant_result)){
                        foreach($variant_result as $pkey=>$pValue){
                                $is_price_result = $oPrice->arrGetPriceDetails(1,$pValue['product_id'],$category_id);
                                if(is_array($is_price_result)){
                                        $variant_dataresult[] = $pValue;
                                }
                        }
                }
                $new_breadcrumb .= '<div class="breadcumbs">';
                $new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

                $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'Newcar-Search" itemprop="url"><span class="link-act" itemprop="title">New Cars</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

                $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                <div id="breadcrumb_sub_brand" class="b_s_home"><span itemprop="title">Brand</span><i class="b_s_direction"></i><div class="clear"></div>';
                $new_breadcrumb .= '<div class="bredcrumb_submenu" id="breadcrumb_brand"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Brand BreadCrumb Nav\', \'click\', \'Brand BreadCrumb Nav - Brand Page Link\']);"><div class="browsemenus">';
        foreach($result as $bbkey=>$brandValue){
                        if($bbkey > 0 && $bbkey%8 == 0){
                                $new_breadcrumb .= '</div><div class="browsemenus">';
                        }
                        $seo_brand_url = WEB_URL.'car-brands/'.constructUrl( $brandValue['brand_name']);
                        $new_breadcrumb .= '<a href='.$seo_brand_url.' itemprop="url"><span class="link-act" itemprop="title">'.$brandValue['brand_name'].'</span></a>';
                }
                $new_breadcrumb .= '</div><div class="clear"></div></div></div></div><span>:</span>';
                $new_breadcrumb .='<a href="'.WEB_URL.'car-brands/'.constructUrl($brand_name).'" itemprop="url"><span class="link-act" itemprop="title">'.$brand_name.'</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

                $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><div id="breadcrumb_sub_model" class="b_s_home"><span>Model</span><i class="b_s_direction"></i><div class="clear"></div><div class="bredcrumb_submenu" id="breadcrumb_model"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Model BreadCrumb Nav\', \'click\', \'Model BreadCrumb Nav - Model Page Link\']);"><div class="browsemenus">';
                foreach($brand_model_result as $mmkey=>$modelValue){
                        if($mmkey > 0 && $mmkey%8 == 0){
                                $new_breadcrumb .= '</div><div class="browsemenus">';
                        }
                        $seo_model_url = WEB_URL.constructUrl($brand_name).'-cars/'.constructUrl($brand_name).'-'.constructUrl( $modelValue['product_info_name']).'/Model/'.constructUrl($modelValue['product_info_name']).'/'.$modelValue['product_name_id'];
                        $new_breadcrumb .= '<a href='.$seo_model_url.' itemprop="url"><span class="link-act" itemprop="title">'.$modelValue['product_info_name'].'</span></a>';
                }
                $new_breadcrumb .= '</div><div class="clear"></div></div></div></div><span> :</span>';

                $seo_curr_model_url = WEB_URL.constructUrl($brand_name).'-cars/'.constructUrl($brand_name).'-'.constructUrl( $product_info_name).'/Model/'.constructUrl($product_info_name).'/'.$product_name_id;

                $new_breadcrumb .= '<a href="'.$seo_curr_model_url.'" itemprop="url"><span class="link-act" itemprop="title">'.$product_info_name.'</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
        $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                <div id="breadcrumb_sub_var" class="b_s_home"><span>Variant</span><i class="b_s_direction"></i><div class="clear"></div>
                <div class="bredcrumb_submenu" id="breadcrumb_model"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Variant BreadCrumb Nav\', \'click\', \'Variant BreadCrumb Nav - Variant Page Link\']);"><div class="browsemenus">';
                foreach($variant_dataresult as $vvkey=>$vvValue){
                        if($mmkey > 0 && $mmkey%8 == 0){
                                $new_breadcrumb .= '</div><div class="browsemenus">';
                        }
                        $seo_variant_url = WEB_URL.constructUrl($brand_name).'-cars/'.constructUrl($brand_name).'-'.constructUrl($product_info_name).'/'.constructUrl( $vvValue['variant']).'/Overviews/'.$vvValue['product_id'];
                        $new_breadcrumb .= '<a href='.$seo_variant_url.' itemprop="url"><span class="link-act" itemprop="title">'.$vvValue['variant'].'</span></a>';
                }
                $new_breadcrumb .= '</div><div class="clear"></div></div></div></div> <span>:</span>';
                $seo_curr_variant_url = WEB_URL.constructUrl($brand_name).'-cars/'.constructUrl($brand_name).'-'.constructUrl($product_info_name).'/'.constructUrl( $product_name).'/Overviews/'.$product_id;
                $new_breadcrumb .= '<a href="'.$seo_curr_variant_url.'" itemprop="url"><span class="link-act" itemprop="title">'.$product_name.'</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
                $new_breadcrumb .= '<span itemprop="title">Emi Calculator</span>';

                $new_breadcrumb .= '</span></span></span></span></span>';
                $new_breadcrumb .= '<div class="clear"></div></div>';
        }elseif(($product_id == "") && ($product_name_id != "")){//From Model Page
        if(is_array($brdresult)){
                        foreach($brdresult as $bkry=>$bValue){
                                if(in_array($bValue['brand_id'],$top_brand_arr)){
                                        $set_key = array_search($bValue['brand_id'], $top_brand_arr);
                                        $bBrandArr1[$set_key] = $bValue;
                                }else{
                                       $bBrandArr2[] = $bValue;
                                }
                        }
                        ksort($bBrandArr1);
                        unset($result);
                        if(is_array($bBrandArr1) && is_array($bBrandArr2)){
                                $result = array_merge($bBrandArr1,$bBrandArr2);
                        }
                }
                require_once(CLASSPATH.'product.class.php');
                $oProduct       =       new ProductManagement;
                $brand_model_result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id);

                $new_breadcrumb .= '<div class="breadcumbs">';
                $new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

                $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'Newcar-Search" itemprop="url" ><span class="link-act" itemprop="title">New Cars</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
                $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><div id="breadcrumb_sub_brand" class="b_s_home"><span>Brand</span><i class="b_s_direction"></i><div class="clear"></div>';
                $new_breadcrumb .= '<div class="bredcrumb_submenu" id="breadcrumb_brand"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Brand BreadCrumb Nav\', \'click\', \'Brand BreadCrumb Nav - Brand Page Link\']);"><div class="browsemenus">';
        foreach($result as $bbkey=>$brandValue){
                                if($bbkey > 0 && $bbkey%8 == 0){
                                        $new_breadcrumb .= '</div><div class="browsemenus">';
                                }
                                $seo_brand_url = WEB_URL.'car-brands/'.constructUrl( $brandValue['brand_name']);
                                $new_breadcrumb .= '<a href='.$seo_brand_url.'>'.$brandValue['brand_name'].'</a>';
                   }
                $new_breadcrumb .= '</div><div class="clear"></div></div></div></div><span>:</span>';
                $new_breadcrumb .='<a href="'.WEB_URL.'car-brands/'.constructUrl( $brand_name).'" itemprop="url"><span class="link-act" itemprop="title">'.$brand_name.'</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

                $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><div id="breadcrumb_sub_model" class="b_s_home"><span>Model</span><i class="b_s_direction"></i><div class="clear"></div><div class="bredcrumb_submenu" id="breadcrumb_model"><i class="bredcrumbarrow"></i><div class="sub_menu_brands" onclick="_gaq.push([\'_trackEvent\', \'Model BreadCrumb Nav\', \'click\', \'Model BreadCrumb Nav - Model Page Link\']);"><div class="browsemenus">';
                foreach($brand_model_result as $mmkey=>$modelValue){
                                if($mmkey > 0 &&  $mmkey%8 == 0){
                                        $new_breadcrumb .= '</div><div class="browsemenus">';
                                }
                                $seo_model_url = WEB_URL.constructUrl($brand_name).'-cars/'.constructUrl($brand_name).'-'.constructUrl( $modelValue['product_info_name']).'/Model/'.constructUrl($modelValue['product_info_name']).'/'.$modelValue['product_name_id'];
                                $new_breadcrumb .= '<a href='.$seo_model_url.' itemprop="url"><span class="link-act" itemprop="title">'.$modelValue['product_info_name'].'</span></a>';
                   }
                $new_breadcrumb .= '</div><div class="clear"></div></div></div></div><span> :</span>';
                $seo_curr_model_url = WEB_URL.constructUrl($brand_name).'-cars/'.constructUrl($brand_name).'-'.constructUrl( $product_info_name).'/Model/'.constructUrl($product_info_name).'/'.$product_name_id;

                $new_breadcrumb .= '<a href="'.$seo_curr_model_url.'" itemprop="url"><span class="link-act" itemprop="title">'.$product_info_name.'</span></a>';
                $new_breadcrumb .= '<span>/</span>';
                $new_breadcrumb .= '<span itemprop="title">Emi Calculator</span>';
                $new_breadcrumb .= '</span></span></span></span>';
        $new_breadcrumb .= '<div class="clear"></div></div>';
        }elseif(($product_id == "") && ($product_name_id == "")){//Landing Page
                $new_breadcrumb .= '<div class="breadcumbs">';
                $new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

                $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'Newcar-Search" itemprop="url" ><span class="link-act" itemprop="title">New Cars</span></a>';

                $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
                $new_breadcrumb .= '<span itemprop="title">Emi Calculator<span>';

                $new_breadcrumb .= '</span></span>';
                $new_breadcrumb .= '<div class="clear"></div></div>';
        }
        return $new_breadcrumb;
}

function brandsListBreadCrumb(){
        $new_breadcrumb .= '<div class="breadcumbs">';
        $new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" class="home"></a>';
        $new_breadcrumb .= '<span class="brdcrum-arr"></span>';
        $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.$_REQUEST['cat_path'].'/search" itemprop="url"><span class="link-act" itemprop="title">'.$_REQUEST['category_name'].'</span></a>';
        $new_breadcrumb .= '<span class="brdcrum-arr"></span>';
        $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">'.$_REQUEST['category_name'].' Brands</span>';
        $new_breadcrumb .= '</span></span></span>';
        return $new_breadcrumb .= '<div class="clear"></div></div>';

}
function searchNewsListBreadCrumb(){
$new_breadcrumb .= '<div class="breadcumbs">';
$new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url"><span class="link-act" itemprop="title">Home</span></a>';
$new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
$new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Search Result</span>';
$new_breadcrumb .= '</span></span>';
return $new_breadcrumb .= '<div class="clear"></div></div>';

}
function ratingslab($rating){
        if($rating == ""){
                $res = 0;
        }else{
                if(($rating>=1.0) && ($rating < 1.5)){
                        $res = 1;
                }else if(($rating >=1.5) && ($rating < 2.0)){
                        $res = 1.5;
                }else if(($rating >=2.0) && ($rating < 2.5)){
                        $res = 2;
                }else if(($rating >=2.5) && ($rating < 3.0)){
                        $res = 2.5;
                }else if(($rating >=3.0) && ($rating < 3.5)){
                        $res = 3;
                }else if(($rating >=3.5) && ($rating < 4.0)){
                        $res = 3.5;
                }else if(($rating >=4.0) && ($rating < 4.5)){
                        $res = 4;
                }else if(($rating >=4.5) && ($rating < 5.0)){
                        $res = 4.5;
                }else if($rating == 5.0 ) {
                        $res = 5;
                }
        }
        return $res;
}
function upcomingCarsListBreadCrumb($cat_path){

        $new_breadcrumb .= '<div class="breadcumbs">';
        $new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" class="home"><span class="link-act" itemprop="title"></span></a>';
        $new_breadcrumb .= '<span class="brdcrum-arr"></span>';

        $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.$cat_path.'/search" itemprop="url"">
        <span class="link-act" itemprop="title">Mobile Phones</span></a>';

        $new_breadcrumb .= '<span class="brdcrum-arr"></span>';
        $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">Upcoming Mobiles</span>';
        $new_breadcrumb .= '</span></span></span>';
        return $new_breadcrumb .= '<div class="clear"></div></div>';
}

function RecentCarsListBreadCrumb(){

        $new_breadcrumb .= '<div class="breadcumbs">';
        $new_breadcrumb .= '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'" itemprop="url" ><span class="link-act" itemprop="title">Home</span></a>';
        $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';

        $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.WEB_URL.'Newcar-Search" itemprop="url"">
        <span class="link-act" itemprop="title">New Cars</span></a>';

        $new_breadcrumb .= '<span class="breadcrumb sprit-icon"></span>';
        $new_breadcrumb .= '<span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">New Launch Cars</span>';
        $new_breadcrumb .= '</span></span></span>';
        return $new_breadcrumb .= '<div class="clear"></div></div>';
}


function getTimeDifference($date1){
	$time1 = time();
	$date_parse = date_parse($date1);
	$time2 = mktime($date_parse['hour'],$date_parse['minute'],$date_parse['second'],$date_parse['month'],$date_parse['day'],$date_parse['year']);
	#echo $time1 ."-". $time2."<br>";
	return ($time1 - $time2) / 3600; // 3600 seconds in hour
}

function grep_src($str){
	//http://gaforflash.googlecode.com/svn-history/r330/trunk/src/com/google/analytics/campaign/CampaignTracker.as;
	//http://stackoverflow.com/questions/821321/what-does-the-utmscr-or-utmcct-values-mean-in-reference-to-the-http-cookie-serve
	if(!$str) { return false; }
	$str = urldecode($str);
	if(strpos($str,'utmcsr')){
		$str = implode("|utmcsr",explode('utmcsr',$str));
	}
	$regex = '/([^;|=]+)=([^;|]+)/';
	if(preg_match_all($regex,$str,$matches,PREG_SET_ORDER)){
		//print_r($matches);
		$cnt = sizeof($matches);
		for($i=0;$i<$cnt;$i++){
			$type = str_replace(array(';',')','(','|'),'',$matches[$i][1]);
			$src = str_replace(array(';',')','(','|'),'',$matches[$i][2]);
			$type = trim($type);
			$src = trim($src);
			if(!empty($type) && !empty($src)){
				$srcArr[$type] = $src;
			}
		}
	}
	return $srcArr;
}

function word_teaser($string, $count){
  $original_string = $string;
  if(strlen($original_string)>25){
	  $words = explode(' ', $original_string);
	  if (count($words) > $count){
		$words = array_slice($words, 0, $count);
		$string = implode(' ', $words);
	  }
  }
  return $string;
}

function read_dir($img_dirpath){
	//	echo $img_dirpath;
               $fileArr = array_diff(scandir($img_dirpath),array('.','..'));
               sort($fileArr); // used to maintain the array index.
               return $fileArr;
        }

function updated_at($date){
    #$date =  gmdate('D, d M Y H:i:s \G\M\T', strtotime($date));
    #$date = date('<b>h:i a,</b> F d, Y',strtotime($date));
    $date = date('h:i a, F d, Y',strtotime($date));
    return $date;
}

function validateImageAspectRatio($width,$height){
	$arrAspectRatios = array("4:3","16:9");
	$gcd=gcd($width,$height);
	//echo "Aspect ratio = ". ($width/$gcd) . ":" . ($height/$gcd);
	$aspect_ratio = trim(($width/$gcd) . ":" . ($height/$gcd));
	if(!in_array($aspect_ratio,$arrAspectRatios)){
		return 0;
	}else{
		return 1;
	}
}

function gcd($a, $b){
    if ($a == 0 || $b == 0)
        return abs( max(abs($a), abs($b)) );

    $r = $a % $b;
    return ($r != 0) ?
        gcd($b, $r) :
        abs($b);
}
function calculate_average($arr) {
    $count = count($arr); //total numbers in array
    foreach ($arr as $value) {
        $total = $total + $value; // total value of array numbers
    }
    $average = ($total/$count); // get average value
    return $average;
}

function getMeasurements($mm) {
    $inches = ceil($mm/25.4);
   $feet = floor(($inches/12));
    //$inches = $mm/25.4;
    //$feet = $inches/12;
    if($feet >= 1){
    	$measurement = $feet."ft ".($inches%12).'in';
    }else{
	$measurement = ($inches%12).'in';
   }

    return $measurement;
}
function makeValidHTML($str){
                $str = str_replace(array("\n","\t","\r\n","\r","\r\n\t"),array('','','','',''),$str);
                $str = preg_replace('/\<![ \r\n\t]*(--([^\-]|[\r\n]|-[^\-])*--[ \r\n\t]*)\>/','',$str);
                $str = preg_replace('/\/\*(?:.|[\r\n])*?\*\//','',$str);
		$str = strip_tags($str);
                return $str;
}
function argvtoRequest($argv){
	foreach($argv as $v){
                $arr = explode('=',$v);
                if($arr == true && sizeof($arr) > 1){
                        $_REQUEST[$arr[0]] = $arr[1];
                }
        }
	return $_REQUEST;
}
function deleteRouterCache($type){
	require_once(CLASSPATH.'memcache.class.php');
	$cache = new Cache;
	$cache->searchDeleteKeys($type);
}
function render_html($file,$strXML,$html='2'){
	#$html = 2;
	$script_uri = $_SERVER['SCRIPT_URI'];
	$cache = new Cache;
	php_strip_whitespace(__FILE__);
	#global $requestingDevice; // defined in config.php
	#$resolution_width = $requestingDevice->getCapability('resolution_width');
	#$resolution_height = $requestingDevice->getCapability('resolution_height');
	#$brand_name = $requestingDevice->getCapability('brand_name');
	#$model_name = $requestingDevice->getCapability('model_name');
	#$marketing_name = $requestingDevice->getCapability('marketing_name');
	#$preferred_markup = $requestingDevice->getCapability('preferred_markup');
	$isPC = '0';
	$isMobile = '0';
	/*switch($brand_name){
		case 'generic web browser':
			$isPC = '1';
			break;
		default:
			$isMobile = '1';
			break;
	}
	$fileArr = explode("/",$file);

	$file = array_pop($fileArr);

	if($resolution_width <= 1024 && $isMobile == '1'){
		$fileArr[] = 'high';
	}
	*/
	$fileArr[] = $file;
	$file = implode("/",$fileArr);

	$strXML = mb_convert_encoding($strXML, "UTF-8");
	$strXML = str_replace(array("\n","\t","\r\n"),'',$strXML);
	if($html == '2'){ header('Content-type: text/xml');echo $strXML;exit; }
	/*
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xslt->registerPHPFunctions();
	$xsl = DOMDocument::load($file);
	$xslt->importStylesheet($xsl);
	$html = $xslt->transformToXML($doc);
*/

$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();

	$xslt = new xsltProcessor;
	$xslt->registerPHPFunctions();
	$xsl = DOMDocument::load($file);

	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
	#$html = str_replace(array("\n","\t","\r\n"),array('','',''),$html);
	#$html = preg_replace('/\<![ \r\n\t]*(--([^\-]|[\r\n]|-[^\-])*--[ \r\n\t]*)\>/','',$html);
	#$html = preg_replace('/\/\*(?:.|[\r\n])*?\*\//','',$html);
	#$html = preg_replace('/(?|( )+|(\\n)+)/', '$1', $html);;
	$html = trim($html);
	#ob_end_clean();
	$cache->set($script_uri,$html,60);
	return $html;
}
function getYouTubeUrl($external_media_source){
	preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $external_media_source, $matches);
	$youtube_source = $matches[1].'?wmode=opaque&amp;rel=0';
	return $youtube_source;
}
function getYouTubeIframeUrl($external_media_source){
	$pattern = '/src="(.*?)"/';
	$replacement = 'src="${1}?wmode=opaque&rel=0"';
	return preg_replace($pattern, $replacement, $external_media_source);
}
function buildYear($startyear,$endyear){
        if(empty($startyear) || empty($endyear)){
                return false;
        }else if($startyear == '0000-00-00 00:00:00' || $startyear == '0000-00-00'){
                return false;
	}else if($endyear == '0000-00-00 00:00:00' || $endyear == '0000-00-00'){
                return false;
        }
        $yearArr[] = date('Y',strtotime($startyear));
        $yearArr[] = date('Y',strtotime($endyear));
        return implode('-',$yearArr);
}
function cleanMySqlInt($str){
                $str = trim($str);
                $str = preg_replace('/[^0-9]/', '', $str);
                $str = preg_replace('/\s+/', '', $str);
                return $str;
        }

function getSolrNewCarSearchData($sSolrQueryURL,$sSolrQueryVarField,$sSolrSortby,$sSolrsortorder,$oWallpapers,$oReview,$oArticle,$videoGallery,$userreview,$iCategoryId=1){

	//echo $sSolrQueryURL."<br/>";die;
        $xmlStr 		= file_get_contents($sSolrQueryURL);
        $aSolrData 		= json_decode($xmlStr);

        $aCarModelList 	= array();
        $iIndex = $iTotVariantRow = 0;

        $sModalList 	= $sModelIds = '';
        $aModelMinMax 	= array();

        $iCnt = $aSolrData->grouped->product_name->matches;
        $iRowCount = $aSolrData->grouped->product_name->ngroups;


        $aUniqueFuel = array();
        if($iCnt>0){
	        foreach($aSolrData as $index => $sSolr) {
	        	foreach($sSolr->product_name->groups as $iK=>$result){
	        		$iVariantCount = $result->doclist->numFound;
	        		$iTotVariantRow = $iTotVariantRow+$iVariantCount;
	        		foreach($result->doclist->docs as $iK=>$resultValue){


	        					if(empty($sModalList)){
	        							$sModalList = 'product_name:"'.$resultValue->product_name.'"';
	        					}else{
	        							$sModalList .= ' OR product_name:"'.$resultValue->product_name.'"';
	        					}

	        					if(!empty($resultValue->image_path)){
	        							global $aModuleImageResize;

                                        $resultValue->image_path = resizeImagePath($resultValue->image_path,"87X65",$aModuleImageResize);
                                        $resultValue->image_path = CENTRAL_IMAGE_URL.str_replace(array(CENTRAL_IMAGE_URL),"",$resultValue->image_path);
                        		}


								//$aCarModelList[$resultValue->product_name][$iIndex]['product_id'] 	= $resultValue->id;
								$aCarModelList[$resultValue->product_name][$iIndex]['category_id'] 	= 1;
								$aCarModelList[$resultValue->product_name][$iIndex]['product_id'] 	= $resultValue->id;
								$aCarModelList[$resultValue->product_name][$iIndex]['brand_id'] 	=  $resultValue->brand_id;
								$aCarModelList[$resultValue->product_name][$iIndex]['product_name'] = $resultValue->product_name;
								$aCarModelList[$resultValue->product_name][$iIndex]['product_name_id'] = $resultValue->product_name_id;

								$aCarModelList[$resultValue->product_name][$iIndex]['variant'] 		= $resultValue->variant;
								$aCarModelList[$resultValue->product_name][$iIndex]['product_desc'] = $resultValue->product_name_desc;
								$aCarModelList[$resultValue->product_name][$iIndex]['image_path'] 	= $resultValue->image_path;
								$aCarModelList[$resultValue->product_name][$iIndex]['model_image_path'] = $resultValue->image_path;
								$aCarModelList[$resultValue->product_name][$iIndex]['variant_value'] = $resultValue->variant_value;

								$aCarModelList[$resultValue->product_name][$iIndex]['discontinue_flag'] = $resultValue->product_discontinue;
								$aCarModelList[$resultValue->product_name][$iIndex]['no_of_variant'] = $iVariantCount;
								$aCarModelList[$resultValue->product_name][$iIndex]['fuel'] = str_replace('Fuel type=','',$resultValue->feature_name_value[33]);
								$aCarModelList[$resultValue->product_name][$iIndex]['mileage'] = str_replace('ARAI efficiency=','',$resultValue->feature_name_value[47]);

								$aCarModelList[$resultValue->product_name][$iIndex]['overallcnt']		= $resultValue->overallcnt;
								$aCarModelList[$resultValue->product_name][$iIndex]['videocnt']			= $resultValue->videocnt;
								$aCarModelList[$resultValue->product_name][$iIndex]['reviewvideocnt']	= $resultValue->reviewvideocnt;
								$aCarModelList[$resultValue->product_name][$iIndex]['newsvideocnt']		= $resultValue->newsvideocnt;
								$aCarModelList[$resultValue->product_name][$iIndex]['articlevideocnt']	= $resultValue->articlevideocnt;
								$aCarModelList[$resultValue->product_name][$iIndex]['iphotocnt']		= $resultValue->iphotocnt;
								$aCarModelList[$resultValue->product_name][$iIndex]['ephotocnt']		= $resultValue->ephotocnt;
								$aCarModelList[$resultValue->product_name][$iIndex]['avg_user_rating']	= implode(',',$resultValue->avg_user_rating);



								if(empty($sModelIds)){
									$sModelIds = $resultValue->product_name_id;
								}else{
									$sModelIds.= ','.$resultValue->product_name_id;
								}


								if(is_array($resultValue->feature_name_value) && count($resultValue->feature_name_value)){
									foreach($resultValue->feature_name_value as $iKy=>$sFeatureValue){
										$iEfficiency = strstr($sFeatureValue,'ARAI efficiency');
										if($iEfficiency!==FALSE){
											$aCarModelList[$resultValue->product_name][$iIndex]['model_mileage'] = str_replace('ARAI efficiency=','',$resultValue->feature_name_value[$iKy]);
											$aCarModelList[$resultValue->product_name][$iIndex]['mileage'] = str_replace('ARAI efficiency=','',$resultValue->feature_name_value[$iKy]);


											$aCarModelList[$resultValue->product_name][$iIndex]['mileage'] = ($aCarModelList[$resultValue->product_name][$iIndex]['mileage']!=='-') ? $aCarModelList[$resultValue->product_name][$iIndex]['mileage'] : '';
											$aCarModelList[$resultValue->product_name][$iIndex]['model_mileage'] = ($aCarModelList[$resultValue->product_name][$iIndex]['model_mileage']!=='-') ? $aCarModelList[$resultValue->product_name][$iIndex]['model_mileage'] : '';


											if(trim($aCarModelList[$resultValue->product_name][$iIndex]['mileage'])!=''){
												$aCarModelList[$resultValue->product_name][$iIndex]['mileage'] = $aCarModelList[$resultValue->product_name][$iIndex]['mileage'] .' Kmpl';
											}


											if(trim($aCarModelList[$resultValue->product_name][$iIndex]['model_mileage'])!=''){
												$aCarModelList[$resultValue->product_name][$iIndex]['model_mileage'] = $aCarModelList[$resultValue->product_name][$iIndex]['model_mileage'].' Kmpl';

											}



										}
										$iFuelType = strstr($sFeatureValue,'Fuel type');
										$sFuelStr ='';
										if(!empty($iFuelType)){

											$sModelFuel = trim(str_replace('Fuel type=','',$resultValue->feature_name_value[$iKy]));
											//echo $resultValue->product_name."===".$sModelFuel."<br/>";

											if(!empty($sModelFuel)){
												if(strpos($sModelFuel,',')>=0){
														$aCarModelListFuel = explode(',',$sModelFuel);
												}else{
													$aCarModelListFuel = array($sModelFuel);
												}
												foreach($aCarModelListFuel as $iFKey=>$sFValue){
													if(empty($sFuelStr)){
														$sFuelStr = trim($sFValue);
													}else{
														$sFuelStr .= ','.trim($sFValue);
													}
												}
											}



											$aCarModelList[$resultValue->product_name][$iIndex]['model_fuel'] = $sFuelStr;
											$aCarModelList[$resultValue->product_name][$iIndex]['fuel'] = $sFuelStr;

											$aCarModelList[$resultValue->product_name][$iIndex]['isvisited'] = 0;


										}
									}
								}

	        		}
	        	}
	        }

	        $sSolrQueryVarField .= rawurlencode(' AND ('.$sModalList.')');
	        $sURL = SOLAR_SEARCH_URL.$sSolrQueryVarField."&sort=$sSolrSortby+$sSolrsortorder&start=0&rows=$iTotVariantRow&wt=json";

	        $xmlStr = file_get_contents($sURL);
	        $aSolrVariantData = json_decode($xmlStr);


	       	$iModel  = 0;
	       	$arrVariantStr = array();

	        foreach($aSolrVariantData as $index => $sSolr) {
	        	$iVarCnt = 0;


	        	foreach($sSolr->docs as $iK=>$resultValue){

						if($aCarModelList[$resultValue->product_name]){



							if($aCarModelList[$resultValue->product_name][0]['product_id']!=$resultValue->id || $aCarModelList[$resultValue->product_name][0]['no_of_variant']==1){

										$iVarCnt = count($aCarModelList[$resultValue->product_name]);


										$aCarModelList[$resultValue->product_name][$iVarCnt]['product_id'] 	= $resultValue->id;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['category_id'] = 1;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['brand_id'] 	=  $resultValue->brand_id;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['product_name'] = $resultValue->product_name;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['variant'] = $resultValue->variant;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['product_desc'] = $resultValue->product_name_desc;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['image_path'] 	= $resultValue->image_path;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['variant_value'] = $resultValue->variant_value;


										$aCarModelList[$resultValue->product_name][$iVarCnt]['discontinue_flag'] = $resultValue->product_discontinue;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['no_of_variant'] = $iVariantCount;




										if(is_array($resultValue->feature_name_value) && count($resultValue->feature_name_value)){
												foreach($resultValue->feature_name_value as $iKy=>$sFeatureValue){
													$iEfficiency = strstr($sFeatureValue,'ARAI efficiency');
													if($iEfficiency!==FALSE){
														$aCarModelList[$resultValue->product_name][$iVarCnt]['mileage'] = str_replace('ARAI efficiency=','',$resultValue->feature_name_value[$iKy]);
														$aCarModelList[$resultValue->product_name][$iVarCnt]['mileage_data'] = str_replace('ARAI efficiency=','',$resultValue->feature_name_value[$iKy]);

														$aCarModelList[$resultValue->product_name][$iVarCnt]['mileage'] = ($aCarModelList[$resultValue->product_name][$iVarCnt]['mileage']!=='-') ? $aCarModelList[$resultValue->product_name][$iVarCnt]['mileage'] : '';

														if(trim($aCarModelList[$resultValue->product_name][$iVarCnt]['mileage'])!==''){
															$aCarModelList[$resultValue->product_name][$iVarCnt]['mileage']= $aCarModelList[$resultValue->product_name][$iVarCnt]['mileage'].' Kmpl';
														}


													}
													$iFuelType = strstr($sFeatureValue,'Fuel type');
													if($iFuelType!==FALSE){

														$aCarModelList[$resultValue->product_name][$iVarCnt]['fuel'] = trim(str_replace('Fuel type=','',$resultValue->feature_name_value[$iKy]));

														if(strpos($aCarModelList[$resultValue->product_name][$iVarCnt]['fuel'],',')){
															$aCarModelListVariant = explode(',',$aCarModelList[$resultValue->product_name][$iVarCnt]['fuel']);
														}else{
															$aCarModelListVariant = array($aCarModelList[$resultValue->product_name][$iVarCnt]['fuel']);
														}

														$aFuelVariantArr = explode(',',trim($aCarModelList[$resultValue->product_name][0]['model_fuel']));

														foreach($aCarModelListVariant as $iFKey=>$sFValue){
															if(!in_array(trim($sFValue),$aFuelVariantArr)){
																$aCarModelList[$resultValue->product_name][0]['model_fuel'] .= ','.trim($sFValue);


															}
														}


													}
												}
											}
											$sMileageData = trim($aCarModelList[$resultValue->product_name][$iVarCnt]['mileage_data']);

											$iFlag = strpos(trim($aCarModelList[$resultValue->product_name][$iVarCnt]['mileage_data']),'-');
											if($iFlag===FALSE){
												$aModelMinMax[$resultValue->product_name][$iVarCnt] = $sMileageData;
											}


										$aModelBrand[$resultValue->product_name] = $resultValue->brand_name;


										$modelVarnameArr = array();
										if(!empty($resultValue->brand_name)){
												$modelVarnameArr[] = $resultValue->brand_name;
										}
										if(!empty($resultValue->product_name)){
												$modelVarnameArr[] = $resultValue->product_name;
										}

										if(!empty($resultValue->variant)){
												$modelVarnameArr[] = $resultValue->variant;
										}

										$aCarModelList[$resultValue->product_name][$iVarCnt]['DISPLAY_PRODUCT_NAME'] = implode(" ",$modelVarnameArr);

										$aCarModelList[$resultValue->product_name][$iVarCnt]['EXSHOWROOMPRICE_ORIGIONAL'] = $resultValue->variant_value;
										$aCarModelList[$resultValue->product_name][$iVarCnt]['EXSHOWROOMPRICE'] = $resultValue->variant_value ? priceFormat($resultValue->variant_value) : ''; // use for price showing ie Rs-1,20,00.

										$sArrivalDate = $resultValue->arrival_date;
										if($sArrivalDate=='0002-11-30T00:00:00Z'){
											$sArrivalDate = '0000-00-00 00:00:00';

										}else{
											$sArrivalDate = str_replace(array('T','Z'), ' ', $sArrivalDate);
										}

										$aCarModelList[$resultValue->product_name][$iVarCnt]['arrival_date'] = $sArrivalDate;

										$sDiscontinueDate = $resultValue->discontinue_date;
										if($sDiscontinueDate=='0002-11-30T00:00:00Z'){
											$sDiscontinueDate = '0000-00-00 00:00:00';
										}else{
											$sDiscontinueDate = str_replace(array('T','Z'), ' ', $sDiscontinueDate);

										}
										$aCarModelList[$resultValue->product_name][$iVarCnt]['discontinue_date'] = $sDiscontinueDate;

										unset($variantnameSeoArr);

										$variantnameSeoArr[] 	= SEO_WEB_URL;
										$brand_name 			= $resultValue->brand_name;
										$product_name 			= $resultValue->product_name;
										$variant 				= $resultValue->variant;
										$variantnameSeoArr[] 	= seo_title_replace(constructUrl($brand_name));
										$variantnameSeoArr[] 	= seo_title_replace(constructUrl($product_name));
										$variantnameSeoArr[] 	= seo_title_replace(constructUrl($variant));
										unset($varianUrlYear);

										$varianUrlYear = buildYear($sArrivalDate,$sDiscontinueDate);
										if(!empty($varianUrlYear)){
											$variantnameSeoArr[] = $varianUrlYear;
										}


										if(!empty($brand_name)){
											$modelnameArr[] = $brand_name;
										}
										if(!empty($product_name)){
										$modelnameArr[] = $product_name;
										}
										$comparename = constructUrl($brand_name).'-'.constructUrl($product_name).'-'.constructUrl($variant);
										if(!empty($varianUrlYear)){
											$comparename = $comparename.'-'.$varianUrlYear;
										}
										$aCarModelList[$resultValue->product_name][$iVarCnt]['comparename'] = $comparename;

										$aCarModelList[$resultValue->product_name][$iVarCnt]['SEO_URL'] = implode("/",$variantnameSeoArr);

										$aProductDetail = array_change_key_case($aCarModelList[$resultValue->product_name][$iVarCnt],CASE_UPPER);

										$xmlString = $resultValue->product_name."_XML";
										$$xmlString .= "<SIMILAR_PRODUCT_MASTER>";
										foreach($aProductDetail as $k=>$v){
											$$xmlString .= "<$k><![CDATA[$v]]></$k>";
										}
										$$xmlString .= "</SIMILAR_PRODUCT_MASTER>";





									if($aCarModelList[$resultValue->product_name][0]['no_of_variant']==1){
										$iVariantChkCnt = 0;
									}
									else{
										$iVariantChkCnt = count($aCarModelList[$resultValue->product_name])-1;
									}
									unset($modelVarnameArr);

									if($iVariantChkCnt==$aCarModelList[$resultValue->product_name][0]['no_of_variant']-1){
										if($aCarModelList[$resultValue->product_name][0]['no_of_variant']>1){
											$arrVariantStr[$resultValue->product_name] = $$xmlString;
										}

									}


						}

					}

	        	}
	        }

	        $cnt = $iCnt;
			$totalcount = ($iRowCount>0) ?  $iRowCount : 0;

			if(empty($totalcount)) $totalcount =0;

	        $productxml = "<PRODUCT_MASTER>";
			$productxml .= "<TOTAL_SEARCH_ITEM_FOUND><![CDATA[".$totalcount."]]></TOTAL_SEARCH_ITEM_FOUND>";
			$productxml .= "<COUNT><![CDATA[$cnt]]></COUNT>";


	        foreach($aCarModelList as $sProductName=>$aData){


						$iMin = min($aModelMinMax[$sProductName]);
						$iMax = max($aModelMinMax[$sProductName]);
						if($iMin!==$iMax)
							$aCarModelList[$sProductName][0]['model_mileage'] = $iMin.' kmpl -'.$iMax.' kmpl';


						$brand_name 	= $aModelBrand[$sProductName];
						$product_name 	= $sProductName;
						$variant 		= $aData[0]['variant'];


						$modelnameArr = array();
						if(!empty($brand_name)){
							$modelnameArr[] = $brand_name;
						}
						if(!empty($product_name)){
							$modelnameArr[] = $product_name;
						}

						$modelVarnameArr = $modelnameArr;
						if(!empty($variant)){
								$modelVarnameArr[] = $variant;
						}



						$seo_model_url 		=  implode("/",$modelnameSeoArr);
						$link_model_name	= implode(" ",$modelnameArr);

						//get model name and seo url.
						$modelnameSeoArr[] = SEO_WEB_URL;
						$modelnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
						$modelnameSeoArr[] = seo_title_replace(constructUrl($product_name));
						$model_id = $aCarModelList[$sProductName][0]['product_name_id'];

						/* check to show photo video link */
						$iphoto_cnt = $aCarModelList[$sProductName][0]['iphotocnt'];
						$ephoto_cnt = $aCarModelList[$sProductName][0]['ephotocnt'];
						$photo_cnt 	= $iphoto_cnt + $ephoto_cnt;



						$aCarModelList[$sProductName][0]['display_photo_link'] = ($photo_cnt>0) ? 1 : 0;


						$aCarModelList[$sProductName][0]['DISPLAY_PRODUCT_NAME'] 		= implode(" ",$modelVarnameArr);
						$aCarModelList[$sProductName][0]['EXSHOWROOMPRICE_ORIGIONAL'] 	= $aCarModelList[$sProductName][0]['variant_value'];
						$aCarModelList[$sProductName][0]['EXSHOWROOMPRICE'] 			= $aCarModelList[$sProductName][0]['variant_value'] ? priceFormat($aCarModelList[$sProductName][0]['variant_value']) : ''; // use for price showing ie Rs-1,20,00.

						$category_result_count 						= $aCarModelList[$sProductName][0]['videocnt'];
						$reviews_category_result_count 				= $aCarModelList[$sProductName][0]['reviewvideocnt'];
						$news_category_result_count 				= $aCarModelList[$sProductName][0]['newsvideocnt'];
						$article_maintainance_category_result_count = $aCarModelList[$sProductName][0]['articlevideocnt'];
						$totalcnt 									= $aCarModelList[$sProductName][0]['overallcnt'];

						$aAvgRating 								= explode(',',$aCarModelList[$sProductName][0]['avg_user_rating']);

						$all_reviews_avg_rating                     = array_sum($aAvgRating);



						$all_reviews_avg_rating = $userreview->reviewRatingslab($all_reviews_avg_rating/4);
						$all_reviews_avg_rating_proportion = (($all_reviews_avg_rating*100)/10)*2;

						$ext_reviews_avg_rating = $userreview->reviewRatingslab($aAvgRating[0]);
						$ext_reviews_avg_rating_proportion = (($ext_reviews_avg_rating*100)/10)*2;

						$int_reviews_avg_rating = $userreview->reviewRatingslab($aAvgRating[1]);
						$int_reviews_avg_rating_proportion = (($int_reviews_avg_rating*100)/10)*2;

						$perf_reviews_avg_rating = $userreview->reviewRatingslab($aAvgRating[2]);
						$perf_reviews_avg_rating_proportion = (($perf_reviews_avg_rating*100)/10)*2;

						$serv_reviews_avg_rating = $userreview->reviewRatingslab($aAvgRating[3]);
						$serv_reviews_avg_rating_proportion = (($serv_reviews_avg_rating*100)/10)*2;


						$ext_reviews_avg_rating = ($ext_reviews_avg_rating > 0) ? $ext_reviews_avg_rating : 0;
						$ext_reviews_avg_rating_proportion = ($ext_reviews_avg_rating_proportion > 0) ? $ext_reviews_avg_rating_proportion : 0;

						$int_reviews_avg_rating = ($int_reviews_avg_rating > 0) ? $int_reviews_avg_rating :0;
						$int_reviews_avg_rating_proportion = ($int_reviews_avg_rating_proportion > 0) ? $int_reviews_avg_rating_proportion : 0;

						$perf_reviews_avg_rating = ($perf_reviews_avg_rating > 0) ? $perf_reviews_avg_rating : 0;
						$perf_reviews_avg_rating_proportion = ($perf_reviews_avg_rating_proportion > 0) ? $perf_reviews_avg_rating_proportion : 0;

						$serv_reviews_avg_rating = ($serv_reviews_avg_rating > 0) ? $serv_reviews_avg_rating :0;
						$serv_reviews_avg_rating_proportion = ($serv_reviews_avg_rating_proportion > 0) ? $serv_reviews_avg_rating_proportion : 0;

						$all_reviews_avg_rating = ($all_reviews_avg_rating > 0) ? $all_reviews_avg_rating : 0;
						$all_reviews_avg_rating_proportion = ($all_reviews_avg_rating_proportion > 0) ? $all_reviews_avg_rating_proportion : 0;

						$videocnt 									= $category_result_count + $reviews_category_result_count + $news_category_result_count + $article_maintainance_category_result_count;

						$aCarModelList[$sProductName][0]['display_video_link'] = ($videocnt>0) ? 1 : 0;
						/* check to show photo video link */

						$modelnameSeoArr11 = $modelnameSeoArr;
						$modelnameSeoArr11[] = seo_title_replace(constructUrl($variant));


						$link_model_name = implode(" ",$modelnameArr);
						$seo_model_url =  implode("/",$modelnameSeoArr);
						$seo_model_url11 =  implode("/",$modelnameSeoArr11);

						//echo $seo_model_url."<br/>" ;

						$aCarModelList[$sProductName][0]['seo_url'] = $seo_model_url11;

						$comparename = constructUrl($brand_name).'-'.constructUrl($product_name).'-'.constructUrl($variant);
						$modelUrlYear = buildYear($aCarModelList[$sProductName][0]['arrival_date'],$aCarModelList[$sProductName][0]['discontinue_date']);

						if(!empty($modelUrlYear)){
							$comparename = $comparename.'-'.$modelUrlYear;
						}

						$aCarModelList[$sProductName][0]['comparename'] = $comparename;
						$aProductDetail = array_change_key_case($aCarModelList[$sProductName][0],CASE_UPPER);


						$productxml .= "<PRODUCT_MASTER_DATA>";

						foreach($aProductDetail as $k=>$v){
							$productxml .= "<$k><![CDATA[$v]]></$k>";
						}
						if($aCarModelList[$sProductName][0]['no_of_variant']>1){
							$productxml .= $arrVariantStr[$sProductName]; //$$xmlString;
						}

						$iBrandId = $aCarModelList[$sProductName][0]['brand_id'];


						$productxml.="<AVERAGE_USER_RATING_API>";
						$productxml.="<EXT_REVIEWS_AVG_RATING>".$ext_reviews_avg_rating."</EXT_REVIEWS_AVG_RATING>";
						$productxml.="<EXT_REVIEWS_AVG_RATING_PROPERTION>".$ext_reviews_avg_rating_proportion."</EXT_REVIEWS_AVG_RATING_PROPERTION>";
						$productxml.="<INT_REVIEWS_AVG_RATING>".$int_reviews_avg_rating."</INT_REVIEWS_AVG_RATING>";
						$productxml.="<INT_REVIEWS_AVG_RATING_PROPERTION>".$int_reviews_avg_rating_proportion."</INT_REVIEWS_AVG_RATING_PROPERTION>";
						$productxml.="<PERF_REVIEWS_AVG_RATING>".$perf_reviews_avg_rating."</PERF_REVIEWS_AVG_RATING>";
						$productxml.="<PERF_REVIEWS_AVG_RATING_PROPERTION>".$perf_reviews_avg_rating_proportion."</PERF_REVIEWS_AVG_RATING_PROPERTION>";
						$productxml.="<SERV_REVIEWS_AVG_RATING>".$serv_reviews_avg_rating."</SERV_REVIEWS_AVG_RATING>";
						$productxml.="<SERV_REVIEWS_AVG_RATING_PROPERTION>".$serv_reviews_avg_rating_proportion."</SERV_REVIEWS_AVG_RATING_PROPERTION>";

						$productxml.="<ALL_REVIEWS_AVG_RATING>".$all_reviews_avg_rating."</ALL_REVIEWS_AVG_RATING>";
						$productxml.="<ALL_REVIEWS_AVG_RATING_PROPERTION>".$all_reviews_avg_rating_proportion."</ALL_REVIEWS_AVG_RATING_PROPERTION>";

						if($all_reviews_avg_rating < 2){
							$all_reviews_avg_grade = "Poor";
						}elseif($all_reviews_avg_rating >= 2 and $all_reviews_avg_rating < 3){
							$all_reviews_avg_grade = "Fair";
						}elseif($all_reviews_avg_rating >= 3 and $all_reviews_avg_rating < 4){
							$all_reviews_avg_grade = "Average";
						}elseif($all_reviews_avg_rating >= 4 and $all_reviews_avg_rating < 5){
							$all_reviews_avg_grade = "Good";
						}elseif($all_reviews_avg_rating == 5){
							$all_reviews_avg_grade = "Excellent";
						}
						$productxml.="<ALL_REVIEWS_AVG_GRADE>".$all_reviews_avg_grade."</ALL_REVIEWS_AVG_GRADE>";
						$productxml.="</AVERAGE_USER_RATING_API>";



						$productxml .= "<OVERALL_TOTAL_CNT><![CDATA[$overallcnt]]></OVERALL_TOTAL_CNT>";
						$productxml .= "<OVERALL_CNT><![CDATA[$totalcnt]]></OVERALL_CNT>";
						$productxml .= "<SEO_MODEL_RATING_PAGE_URL><![CDATA[".$seo_model_url.'/user-reviews'."]]></SEO_MODEL_RATING_PAGE_URL>";


						$productxml .= "<LINK_PRODUCT_NAME><![CDATA[".$link_model_name."]]></LINK_PRODUCT_NAME>";
						$productxml .= "<SEO_MODEL_PAGE_URL><![CDATA[".$seo_model_url."]]></SEO_MODEL_PAGE_URL>";
						$productxml .= "<MODEL_ID><![CDATA[".$model_id."]]></MODEL_ID>";
						$productxml .= "<SEO_MODEL_PHOTO_PAGE_URL><![CDATA[".$seo_model_url."]]></SEO_MODEL_PHOTO_PAGE_URL>";
						$productxml .= "<SEO_MODEL_VIDEO_PAGE_URL><![CDATA[".$seo_model_url."]]></SEO_MODEL_VIDEO_PAGE_URL>";
						$productxml .= "<SIMILAR_PRODUCT_COUNT><![CDATA[".$aCarModelList[$sProductName][0]['no_of_variant']."]]></SIMILAR_PRODUCT_COUNT>";
						$productxml .= "<MODEL_DISCONTINUE_FLAG><![CDATA[$discontinue_flag]]></MODEL_DISCONTINUE_FLAG>";
						$productxml .= "<MODEL_DISCONTINUE_DATE><![CDATA[$discontinue_date]]></MODEL_DISCONTINUE_DATE>";
						$productxml .= "<THREE_MONTHS_PLUS_DISCONTINUE_DATE><![CDATA[$three_months_plus_discontinue_date]]></THREE_MONTHS_PLUS_DISCONTINUE_DATE>";


						$productxml .= "</PRODUCT_MASTER_DATA>";
						unset($modelnameArr);
						unset($modelnameSeoArr);
						$iFlag='';


	        }
	        $productxml .= "</PRODUCT_MASTER>";

    }
     return $productxml;
}


function generateTinyUrl($sUrl){
		$domainurl = "https://api-ssl.bitly.com/v3/shorten?access_token=342f4f57098b535e09140393316862ce68cf0f64&longUrl=$sUrl&domain=bit.ly&format=json";

		$sResult = file_get_contents($domainurl);
		$sDecode = json_decode($sResult);

		$sTinyUrl = $sDecode->data->url;

		if(!empty($sTinyUrl)){
			return $sTinyUrl;
		}

		return $sUrl;



	}

function sendSMS($userid,$sMobile,$message,$oUser){
	global $sms_viva_api;
	echo $send_sms = $sms_viva_api."&MobileNo=".$sMobile."&SenderID=".SENDERID."&CDMAHeader=91".$sMobile."&Message=".urlencode($message);
	$response = file_get_contents($send_sms);
        if(!empty($response)){
	      if(strpos($response,"GUID")){
			$arr_response = explode("SUBMITDATE",$response);
			$arr_guid = explode("=",$arr_response[0]);
			$ack_id = str_replace('"','',$arr_guid[1]);
	      }else{
		return 0;
	      }
	      if(!empty($ack_id)){
              	$aSmsParameter = array("lead_id"=>$userid,"mobile_no"=>$sMobile,"ack_id"=>$ack_id,"create_date"=>'now()',"update_date"=>'now()');
              	$iSmsDetail = $oUser->intInsertSmsDetail($aSmsParameter);
		return true;
	      }
        }
}

function getSMSAcknowledgement($leadid,$sMobile,$ack_id,$oUser){
        global $sms_viva_reportapi;
	#http:// whitelist.smsapi.org/GetReportsMessageID.aspx?UserName=xxxx&Password=xxxx&MessageID=xxxx
        $get_status = $sms_viva_reportapi."&MessageID=".$ack_id;
        $response = file_get_contents($get_status);
        //print_r($response);
         if(!empty($response)){
		if(trim($response)=='DLR not found.'){
			$sMsgStatus=trim($response);
		}else{
         		$aResponse = explode('|',$response);

         		$aMsgStatus = explode(' ',$aResponse[3]);
	         	$sMsgStatus = $aMsgStatus[0];
		}

			$aSmsParameter = array("lead_id"=>$leadid,"ack_id"=>$ack_id,"status"=>$sMsgStatus,"update_date"=>'now()');
			$iSmsDetail = $oUser->intUpdateSMSAckDetail($ack_id,$aSmsParameter);
			return true;
         }


}
function numberWithDecimal($iNumber){

	if(strpos($iNumber,'.')>=0){
		$aNumber = explode('.',$iNumber);
		if(!empty($aNumber[1])){
			$iNumber = $aNumber[0].'.'.substr($aNumber[1],0,2);
		}else{
			$iNumber = $aNumber[0];
		}
		return $iNumber;
	}
	return $iNumber;

}

/**
 * add extra in basic component params if required
 * @param Array $componentArr
 * @return Array
 */
function getComponentParams($componentArr = array()) {

    $component_params = array(
        'category_id' => $_REQUEST['category_id'],
	'category_name' => $_REQUEST['category_name'],
	'cat_path' => $_REQUEST['cat_path'],
        'brand_id' => $_REQUEST['router_brand_id'],
        'brand_name' => $_REQUEST['router_brand_name'],
        'model_id' => $_REQUEST['router_model_id'],
        'model_name' => $_REQUEST['router_model_name'],
        'variant_id' => $_REQUEST['router_product_id'],
        'variant_name' => $_REQUEST['router_product_name']
    );
    if (is_array($componentArr)) {

        $component_params = array_merge($componentArr, $component_params);
    }
    return $component_params;
}

function getComponents($page, $dynamic_component_params) {

    $component_xml = "<COMPONENTS_XML>";
    if (!empty($page)) {

        global $component_configuration;
        $page_component_configuration = $component_configuration[$page];

        if (is_array($page_component_configuration)) {
            //echo "components";
            foreach ($page_component_configuration as $component => $static_component_params) {
                //echo "<br/>$component";
                //$_REQUEST['callType'] = 'internal';
//                $_REQUEST['catid'] = $static_component_params['category_id'];
//                $_REQUEST['startlimit'] = $static_component_params['offset'];
//                $_REQUEST['cnt'] = $static_component_params['count'];
                
                foreach ($static_component_params as $key => $val) {

                    if (array_key_exists($key, $dynamic_component_params) && !empty($dynamic_component_params[$key])) {

                        $component_params[$key] = $dynamic_component_params[$key];
                    } else {

                        $component_params[$key] = $val;
                    }
                }
//                print_r($component_params);
//                die;
                // include file
                require_once( COMPONENT_PATH . $component . ".php" );
                unset($component_params);
            }            
        }
        $component_xml .= "</COMPONENTS_XML>";
        return $component_xml;
    }
    $component_xml .= "</COMPONENTS_XML>";
    return $component_xml;
}
function get_plusones($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"AIzaSyAlUe7Lr6Oihu0zpH5QJEJTV0MEOhhj30A","apiVersion":"v1"}]');
	//curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        $curl_results = curl_exec ($curl);
        curl_close ($curl);
        $json = json_decode($curl_results, true);
        $total = intval( $json[0]['result']['metadata']['globalCounts']['count'] );
        $total = is_numeric($total) ? $total : 0;
        return !empty($total) ? $total : 0;
}

