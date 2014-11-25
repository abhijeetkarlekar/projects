<?php
require_once('./include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH .'brand.class.php');
require_once(CLASSPATH.'product.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$brand = new BrandManagement;
$product = new ProductManagement;


$category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : SITE_CATEGORY_ID;
$cat_path = $_REQUEST['cat_path'] ? $_REQUEST['cat_path'] : SITE_CATEGORY_PATH;
if(!empty($category_id)){
	$category_result = $category->arrGetCategoryDetails($category_id);
	$category_id = $category_result[0]['category_id'];
	$category_name = $category_result[0]['category_name'];
	$cat_path = $category_result[0]['seo_path'];
}

$config_details = get_config_details();
$login_details = getCookie();

$strXML .= "<XML>";
$strXML .= $login_details;
$strXML .= $config_details;
$strXML .= getComponents('HOME', getComponentParams(array('imageResize'=>$aModuleImageResize))); // components xml
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
$xsl = DOMDocument::load('xsl/index.xsl');
$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
