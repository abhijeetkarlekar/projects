<?php
	require_once('include/config.php');

	require_once(BASEPATH.'rest/conf/conf.php');

	require_once(PARSER_PATH.'RestParser.php');

	require_once(CLASSPATH.'DbConn.php');


	$dbconn = new DbConn;
	$dbop = new DbOperation;
	$cache = new Cache;

	$domain = DOMAIN;
	$arr = $_REQUEST;

	$q = $_SERVER['REQUEST_URI'];

	#$q = $_SERVER;
	#echo '<pre>';
	#print_r($q);die;
	#$qArr = explode('?',$q);
	#$q = $qArr[0];

	$utm_source = $_GET['utm_source'] ? $_GET['utm_source'] : $utmsrc['utmcsr'];
        $utm_medium = $_GET['utm_medium'] ? $_GET['utm_medium'] : $utmsrc['utmcmd'];
        $utm_campaign = $_GET['utm_campaign'] ? $_GET['utm_campaign'] : $utmsrc['utmccn'];
        $utm_term = $_GET['utm_term'] ? $_GET['utm_term'] : $utmsrc['utmctr'];
        $utm_clickid = $_GET['utmgclid'] ? $_GET['utmgclid'] : $utmsrc['utmgclid'];

        /*
	if(empty($_COOKIE['utm_source'])){
                setcookie('utm_source', $utm_source, time()+3600, "/",$domain);
        }
        if(empty($_COOKIE['utm_medium'])){
                setcookie('utm_medium', $utm_medium, time()+3600, "/",$domain);
        }
        if(empty($_COOKIE['utm_campaign'])){
                setcookie('utm_campaign', $utm_campaign, time()+3600, "/",$domain);
        }
        if(empty($_COOKIE['utm_term'])){
                setcookie('utm_term', $utm_term, time()+3600, "/",$domain);
        } */

	#$html = $cache->get($_SERVER['SCRIPT_URI']);
	if(!empty($html)){
		header('Content-type: text/html');
		echo $html;
		exit;
	}

	if(!empty($q)){
		$RestParser = new RestParser($q);
		$case = $RestParser->page_200;
		$requestArr = $RestParser->requestArr;
	}

	if(is_array($requestArr) && !empty($case)){
		$_REQUEST = array_merge($_REQUEST,$requestArr);
	}
	$_REQUEST['catid'] = SITE_CATEGORY_ID;

	switch($case){
		case 'allbrandpage':
			require_once(BASEPATH.'cars_brand.php');
			break;
		case 'brandpage':
			require_once(BASEPATH.'brand_page.php');
			break;
		case 'modelpage':
			require_once(BASEPATH.'model_page.php');
			break;
		case 'variantpage':
			require_once(BASEPATH.'car_details.php');
			break;
		case 'writereviewpage':
            require_once(BASEPATH.'write_user_review_landing.php');
			break;
		case 'writeuserreviewpage':
            require_once(BASEPATH.'write_user_review.php');
			break;
		case 'emicalculator':
            require_once(BASEPATH.'loan_emi_calculator.php');
			break;
		case 'getonroadprice':
			require_once(BASEPATH.'on_road_price.php');
			break;
		case 'viewonroadprice':
			require_once(BASEPATH.'car_info_details.php');
			break;
		case 'popularcarcomparisons':
			require_once(BASEPATH.'list_oncars_compare_set.php');
			break;
		case 'comparepage':
			require_once(BASEPATH.'compare.php');
			break;
		case 'cardealersearchpage':
			require_once(BASEPATH.'car_dealers.php');
			break;
		case 'cardealerlistpage':
			require_once(BASEPATH.'car_dealer_listing.php');
			break;
		case 'cardealerdetailpage':
			require_once(BASEPATH.'dealer_details.php');
			break;
		default:
			require_once(BASEPATH.'index.php');
			break;
	}
	if(!empty($html)){
		echo $html;
		exit();
	}
