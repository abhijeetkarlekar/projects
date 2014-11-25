<?php
require_once('./include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH .'brand.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'user_review.class.php');
require_once(CLASSPATH . 'pager.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$brand = new BrandManagement;
$product = new ProductManagement;
$userreview = new USERREVIEW;

$category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : SITE_CATEGORY_ID;
$cat_path = $_REQUEST['cat_path'] ? $_REQUEST['cat_path'] : SITE_CATEGORY_PATH;
if(!empty($category_id)){
	$category_result = $category->arrGetCategoryDetails($category_id);
	$category_id = $category_result[0]['category_id'];
	$category_name = $category_result[0]['category_name'];
	$cat_path = $category_result[0]['seo_path'];
}


$request_uri = $_SERVER['REQUEST_URI'];
$pageurl = $_SERVER['SCRIPT_URI'];
$queryStr = $_SERVER['QUERY_STRING'];
$pos = strpos($request_uri, '?');
if ($pos > 0) {
    $request_uri = substr($request_uri, 0, $pos);
}
$pgpos = strpos($request_uri, 'page');
if ($pgpos > 0) {
    $curpagenums = explode("page/", $request_uri);
    $curpagenum = $curpagenums[1];
    $currpageurl = $curpagenums[0];
} else {
    $currpageurl = $request_uri . "/";
}
/* Start Pagination constants. */
define("PERPAGE", 10);
define("OFFSET", "pageno");
define("STARTPAGESHOWN", 10);
define("MAXPAGESHOWN", 10);
$cnt = 0;
$totalcount = 0;
$page = (int) (!isset($_REQUEST["page"]) ? 1 : $_REQUEST["page"]);
$endlimit = PERPAGE;
$startlimit = ($page * $endlimit) - $endlimit;

$totalcount =  $userreview->arrGetUserReviewDetailsCount("","","","","","",$category_id,"","");

//$totalcount = $count_result[0]['cnt'] ? $count_result[0]['cnt'] : 0;
//echo "totalcount---".$totalcount."<br>";
// paging
$endlimit = empty($curpagenum) ? FRONT_PERPAGE : 10;
$oPager = new Pager();
$startlimit = $oPager->findStart($limit);
$pages = ceil($totalcount / $endlimit);

$siteUrl = SEO_WEB_URL . $currpageurl;
if (empty($curpagenum)) {
    $startlimit = 0;
    $curpagenum = 1;
} else {
    $startlimit = ($curpagenum - 1) * $endlimit;
}

if (!empty($curpagenum)) {
    //echo "$curpagenum , $pages , $siteUrl";
    $sPagingXml .= $oPager->pageNumNextPrevUrl($curpagenum, $pages, $siteUrl, $link_type);
}
if ($curpagenum > 1) {
    $showingstart = ($endlimit * ($curpagenum - 1)) + 1;
    $showingend = ($endlimit * $curpagenum);
} else {
    $showingstart = $curpagenum;
    $showingend = $endlimit;
}

if($totalcount>0){
	$cmd_user = PHP_PATH.' '.BASEPATH."api/latest_user_review_api.php brand_id=$router_brand_id product_name_id=$router_model_id start=$startlimit limit=$endlimit";
	$latest_review_api_url = shell_exec($cmd_user);
	$latest_review_api_xml = $latest_review_api_url;
}


$config_details = get_config_details();
$login_details = getCookie();
$breadcrumb = UserReviewBreadCrumb();

$strXML .= "<XML>";
$strXML .= $login_details;
$strXML .= $config_details;
$strXML .= "<BREAD_CRUMB><![CDATA[$breadcrumb]]></BREAD_CRUMB>";
$strXML .= "<MODEL_USER_REVIEW>$latest_review_api_xml</MODEL_USER_REVIEW>";
$strXML .="<PAGING><![CDATA[$sPagingXml]]></PAGING>";
$strXML .= getComponents('USER-REVIEW', getComponentParams(array('imageResize'=>$aModuleImageResize))); // components xml
$strXML .= "<CAT_PATH><![CDATA[".$cat_path."]]></CAT_PATH>";
$strXML .= "</XML>";
//$_REQUEST['debug'] = 1;
//header('Content-type: text/xml');echo $strXML;exit;
if($_REQUEST['debug']==1){ header('Content-type: text/xml');echo $strXML;exit;}
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;    
//$xslt->registerPHPFunctions();
$xsl = DOMDocument::load('xsl/user_review.xsl');
$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>