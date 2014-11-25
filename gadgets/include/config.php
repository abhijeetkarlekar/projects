<?php
error_reporting(E_ALL);
#ini_set('display_errors',1);
php_strip_whitespace(__FILE__);
define('VERSION',time());
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "qwerasdf");
define("DB_NAME", "gadgets_new");
define("SITE_CATEGORY_ID", "1");
define("SITE_CATEGORY_PATH", "mobile-phones");

define("BASEPATH","/var/www/projects/gadgets/");
define("ADMINPATH","/var/www/projects/gadgets/admin/");
define("CLASSPATH","/var/www/projects/gadgets/classes/");
define("BREAD_CRUMB_STR","&nbsp;->&nbsp;");
define("WEB_URL","http://new.gadgets.in/");
define("IMAGE_URL","http://new.gadgets.in/img/");
define("CSS_URL","http://new.gadgets.in/styles/");
define("JS_URL","http://new.gadgets.in/scripts/");

define("ADMIN_WEB_URL","http://new.gadgets.in/admin/");
define("ADMIN_IMAGE_URL","http://new.gadgets.in/admin/img/");
define("ADMIN_JS_URL","http://new.gadgets.in/admin/js/");
define("UPLOAD_ADMIN_PATH","/var/www/projects/gadgets/admin/upload_data/");
define("ADMIN_CSS_URL","http://new.gadgets.in/admin/css/");
require_once(CLASSPATH.'DbOp.php');
require_once(CLASSPATH.'Utility.php');
require_once(CLASSPATH.'memcache.class.php');
$top_brand_arr = array();
define("UPLOAD_BASE_URL","http://media-dev.int.india.com/upload.php");
define("UPLOAD_CLIENT_PATH",BASEPATH."client/");
//image resize sizes array
//$aModuleImageResize = array("668X376","555X416","555X312","251X188","251X141","195X110","160X120","116X65","98X55","87X65","73X55","1920X1080","1152X864","148X113","251X65","295X73","80X60","75X56","550X308","167X111","1024X768","440X330","350X160");

$aModuleImageResize = array("225X300","57X76","75X100","145X193","45X60","375X500","114X152");

define("TMP_UPLOAD_PATH",BASEPATH."tmp/");

define("SERVICEID","2");
define("SERVICE","mobile");
define("SERVICE_NAME","mobile");


//define("SERVICE","auto");
//define("SERVICEID","33");


define("UPLOAD_TMP_PATH",TMP_UPLOAD_PATH);
define('CENTRAL_API_SERVER','http://origin.media-dev.int.india.com/apiserver.php');
define('ORIGIN_CENTRAL_SERVER','http://origin.media-dev.int.india.com/');
define("CENTRAL_IMAGE_URL","http://media-dev.int.india.com/");
define("CENTRAL_MEDIA_URL","http://media-dev.int.india.com/");
define("MEMCACHE_MASTER_KEY","gad_");

define("SEO_PRODUCT_FEATURE","features");
define("SEO_WEB_URL","http://new.gadgets.in");
define("SEO_WEB_TITLE","Gadgets India");
define("SEO_DOMAIN","gadgets.in");
define("DOMAIN",".gadgets.in");
define("SEO_CAR_FINDER","search");
define("SEO_COMPARE_URL","compare");
define("SEO_AUTO_NEWS","news");
define("SEO_AUTO_NEWS_DETAIL","news");
define("SEO_AUTO_ARTICLE","articles");
define("EX_SHOWROOM_STR","Price");
define('CAR_DEALERS', "dealers");
define('COMPARE_PATH', "compare");
define('FRONT_PERPAGE', "10");
$top_brand_arr = array(6,5,4,14,3);

define("PHP_PATH","/usr/bin/php");
define ("EXPERT_REVIEW_API","http://www.bgr.in/api/review/");
// page component configuration
define('COMPONENT_PATH', BASEPATH . 'components/');
$component_configuration = array(
	'BRAND' => array(
                'more_on_brands' => array(
                        'category_id' => 1,
			'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                        'brand_id' => null,
                        'brand_name' => null,
                ),
		'upcoming' => array(
	                'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 3,
                        'brand_id' => null,
		),
		'browse_by_brands' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 30,
                        'brand_id' => null,
		),
	),
        'SEARCH' => array(
                'featured_mobile_phones' => array(
                        'category_id' => 1,
			'cat_path' => 'mobile-phones',
                        'offset' => 0,
                        'count' => 3,
                ),
                'top_comparison' => array(
                        'category_id' => 1,
			'cat_path' => 'mobile-phones',
                        'offset' => 0,
                        'count' => 3
                ),
        ),
        'COMPARE' => array(
                'top_comparison' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3
                ),
                'news' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3,
                        //'search_key' => null,
                ),
        ),
	'MODEL' => array(
                'compare' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3,
                        'brand_name' => null,
                        'model_name' => null,
                ),
                'news' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3,
                        'brand_name' => null,
                        'model_name' => null,
                ),
                'more_on' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 4,
                        'brand_id' => null,
                        'brand_name' => null,
                        'model_id' => null,
                        'model_name' => null,
                        'variant_id' => null,
                ),
                'trending_now' => array(
                        'category_id' => 1,
			'cat_path' => null,
                        'offset' => 0,
                        'count' => 3,
                        'brand_id' => null,
                        'model_id' => null,
                        'variant_id' => null,
                ),
                'other' => array(
                        'category_id' => 1,
			'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                ),
		'user_review' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 5,
                        'brand_id' => null,
                        'model_id' => null,
                ),
        ),
        'DETAIL' => array(
                'compare' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 3,
                        'brand_name' => null,
                        'model_name' => null,
                ),
                'news' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3,
                        'brand_name' => null,
                        'model_name' => null,
			'view_all_news' => 'http://www.bgr.in/news/'
                ),
                'more_on' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                        'brand_id' => null,
                        'brand_name' => null,
                        'model_id' => null,
                        'model_name' => null,
                        'variant_id' => null,
                ),
                'trending_now' => array(
                        'category_id' => 1,
			'cat_path' => null,
                        'offset' => 0,
                        'count' => 3,
                        'brand_id' => null,
                        'model_id' => null,
                        'variant_id' => null,
                ),
                'other' => array(
                        'category_id' => 1,
			'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                ),
		
                'user_review' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 5,
                        'brand_id' => null,
                        'model_id' => null,
                ),
                /*'gallery' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 5,
                        'brand_id' => null,
                        'model_id' => null,
                        'variant_id' => null,
                ),*/
        ),
        'TOP_MOBILE' => array(
                'best_seller' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 4,
                ),
                'featured_compare' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3
                ),
        ),
	'USER-REVIEW' => array(
                'best_seller' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 4,
                ),
                'featured_compare' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3
                ),
        ),
	'HOME' => array(
                'best_seller' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                ),
                'featured_compare' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                ),
		'user_review' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 5,
                        'brand_id' => null,
                        'model_id' => null,
                        'variant_id' => null,
                ),
		'upcoming' => array(
	                'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 5,
                        'brand_id' => null,
			'imageResize' => null,
		),
                'featured_mobile_phones' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 3,
			'imageResize' => null,
                ),
		'budget' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 5,
                        'imageResize' => null,
                ),
		'new_arrivals' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                        'imageResize' => null,
                ),
                'popular_brands' => array(
                        'category_id' => 1,
			'cat_path' => null,
                        'offset' => 0,
                        'count' => 8,
                        'imageResize' => null,
                ),
		'search_box' => array(
			'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 30,
                        'imageResize' => null,
			'sub_group_id' => 11, // Phone Type listing
		),
        ),
	'MOBILE_BRANDS' => array(
                'best_seller' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 4,
                ),
                'featured_compare' => array(
                        'category_id' => 1,
                        'offset' => 0,
                        'count' => 3
                ),
                'user_review' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 3,
                        'brand_id' => null,
                        'model_id' => null,
                        'variant_id' => null,
                ),
        ),
	'UPCOMING' => array(
		'upcoming_brands' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 5,
                        'brand_id' => null,
                        'brand_name' => null,
                        'model_id' => null,
                        'model_name' => null,
                        'variant_id' => null,
                ),
		'upcoming_bytype' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 4,
                        'brand_id' => null,
                        'brand_name' => null,
                        'model_id' => null,
                        'model_name' => null,
                        'variant_id' => null,
                ),
	
                'browse_by_brands' => array(
                        'category_id' => 1,
                        'cat_path' => null,
                        'offset' => 0,
                        'count' => 30,
                        'brand_id' => null,
                ),
	),


);

define("TOP_MOBILES","top-mobiles");
define("BUDGET_MOBILES","budget-mobiles");
define("NEW_ARRIVALS","new-arrivals");
define("UPCOMING_MOBILES","upcoming-mobiles");
define("BRANDS","brands");
define("PHONE_FINDER","search");
define("PHONE_COMPARE","compare");
define("USER_REVIEWS","mobile-user-reviews");
define("BGR_NEWS_URL","http://www.bgr.in/category/news/");
define("BGR_NEWSFEED_URL","http://www.bgr.in/feed/?tag=");
define("BGR_REVEWS_URL","http://www.bgr.in/api/review/");

define('TWEETER_CONSUMER_KEY', 'GRDPDzhZQXemJl90iNC3Ita3Q');
define('TWEETER_CONSUMER_SECRET', 'A7Bojvk9hYVZ9ys5wc3d51fW9JDyPJZvKrhxVLBXXjPHFHTbaV');
define('TWEETER_OAUTH_CALLBACK','');


?>
