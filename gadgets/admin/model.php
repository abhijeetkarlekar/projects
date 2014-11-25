<?php
//ini_set('display_errors','On');
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH."feature.class.php");

$dbconn = new DbConn;
$oProduct = new ProductManagement;
$oFeature  = new FeatureManagement;

//print_r($_REQUEST); die();
$actiontype = $_REQUEST['actiontype'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$product_info_name = $_REQUEST['model_title'];
if(!empty($product_info_name)){
	$request_param['product_info_name'] = htmlentities(trim($product_info_name),ENT_QUOTES,'UTF-8');
}
$category_id = $_REQUEST['catid'];
	$category_id = (!empty($category_id)) ? $category_id : $_REQUEST['selected_category_id'];

$category_id = ($category_id != "") ? $category_id : SITE_CATEGORY_ID;
if(!empty($category_id)){
	$request_param['category_id'] = $category_id;
}
$brand_id=$_REQUEST['select_brand_id'];
if(!empty($brand_id)){
	$request_param['brand_id'] = $brand_id;
}
$model_description = $_REQUEST['model_description'];
$request_param['product_name_desc'] = htmlentities($model_description,ENT_QUOTES,'UTF-8');
$model_abstract = $_REQUEST['model_abstract'];
if(!empty($model_abstract)){
	$request_param['abstract'] = htmlentities($model_abstract,ENT_QUOTES,'UTF-8');
}
$seo_path = $_REQUEST['seo_path'];
if(!empty($seo_path)){
	$request_param['seo_path'] = htmlentities($seo_path,ENT_QUOTES,'UTF-8');
}
$model_tags = trim($_REQUEST['model_tags']);
if(!empty($model_tags)){
	$request_param['tags'] = htmlentities($model_tags,ENT_QUOTES,'UTF-8');
}
$media_id = $_REQUEST['media_id'];
if(!empty($media_id)){
	$request_param['media_id'] = $media_id;
}
$video_path = trim($_REQUEST['img_upload_1']);
if(!empty($video_path)){
	$request_param['video_path'] = $video_path;
}
$img_media_id = $_REQUEST['img_media_id'];
if(!empty($img_media_id)){
	$request_param['img_media_id'] = $img_media_id;
}
$image_path = trim($_REQUEST['img_upload_thm']);
if(!empty($image_path)){
	$request_param['image_path'] = $image_path;
}
$status = $_REQUEST['model_status'];
$request_param['status'] = $status;
$start_date = $_REQUEST['start_date'];
if(!empty($start_date)){
	$request_param['arrival_date'] = $start_date;
}
$end_date = $_REQUEST['end_date'];
if(!empty($end_date)){
	$request_param['discontinue_date'] = $end_date;
}else{
	$request_param['discontinue_date'] = "";
}
$discontinue_flag = $_REQUEST['discontinue_flag'];
if($discontinue_flag=='on'){
	$request_param['discontinue_flag'] = 0;
}else{
	$request_param['discontinue_flag'] = 1;
	$request_param['discontinue_date'] = "";
}
$upcoming_flag = $_REQUEST['upcoming_flag'];
if($upcoming_flag=='on'){
	$request_param['upcoming_flag'] = 1;
}else{
	$request_param['upcoming_flag'] = 0;
}

$product_name_id = $_REQUEST['product_name_id'];
if(!empty($product_name_id)){
	$product_result_name = $oProduct->arrGetProductNameInfo($product_name_id);
	$old_product_name = strtolower($product_result_name[0]['product_info_name']);
}
if($actiontype == 'Insert'){
	$product_name_id = $oProduct->addUpdProductInfoDetails($request_param,"PRODUCT_NAME_INFO");
	$msg="Model detail added successfully.";
}else if($actiontype == 'Update'){
	$request_param['product_name_id'] = $product_name_id;

	$product_name_id = $oProduct->addUpdProductInfoDetails($request_param,"PRODUCT_NAME_INFO");
	$result = $oProduct->boolUdateProductName($product_info_name,$old_product_name,"");
	$msg="Model detail updated successfully.";
	unset($res);
}else if($actiontype == 'Delete'){
	$result = $oProduct->deleteModel($product_name_id);
	$msg = 'Model deleted successfully.';
}
$config_details = get_config_details();
$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "</XML>";
//header('Content-type: text/xml');echo $strXML;exit;
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('xsl/model.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
