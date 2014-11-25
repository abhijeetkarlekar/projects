<?php
	define('RESTCONFPATH','/var/www/projects/gadgets/rest/conf/');
	define('RESTPLATFORMPATH','/var/www/projects/gadgets/rest/');
	define('FILE_BASE_PATH','/var/www/projects/gadgets/html/');
	define('PARSER_PATH','/var/www/projects/gadgets/rest/parser/');
	define('UTILITY_PATH','/var/www/projects/gadgets/rest/utility/');
	define('ROUTER_CACHE_PATH','/var/www/projects/gadgets/rest/cache/');
	define('GET_BRAND_KEY','router_brand');
    	define('GET_VARIANT_KEY','router_variant');
    	define('GET_MODEL_KEY','router_model');

    	define('GET_BODY_STYLE_KEY','router_bdstyle');
    	define('GET_COMPARE_VARIANT','router_compare_variant');
    	define('GET_REVIEW_TITLE_KEY','router_review_title');
	define('GET_VIDEO_TITLE_KEY','router_video_title');
	define('GET_SLIDESHOW_TITLE_KEY','router_slideshow_title');

	require_once(CLASSPATH.'memcache.class.php');
	$cache = new Cache;
