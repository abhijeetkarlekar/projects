<?php
	define('RESTCONFPATH','/var/www/projects/v2.oncars.in/rest/conf/');
	define('RESTPLATFORMPATH','/var/www/projects/v2.oncars.in/rest/');
	define('FILE_BASE_PATH','/var/www/projects/v2.oncars.in/html/');
	define('PARSER_PATH','/var/www/projects/v2.oncars.in/rest/parser/');
	define('UTILITY_PATH','/var/www/projects/v2.oncars.in/rest/utility/');
	define('ROUTER_CACHE_PATH','/var/www/projects/v2.oncars.in/rest/cache/');
	
	define('GET_BRAND_KEY','router_brand');
	define('GET_DEALER_KEY','router_dealer');
	define('GET_VARIANT_KEY','router_variant');
	define('GET_MODEL_KEY','router_model');
	define('GET_CITY_KEY','router_city');
	define('GET_BODY_STYLE_KEY','router_bdstyle');

	define('GET_COMPARE_VARIANT','router_compare_variant');
	
	define('GET_REVIEW_TITLE_KEY','router_review_title');
	require_once(CLASSPATH.'memcache.class.php');
	$cache = new Cache;
