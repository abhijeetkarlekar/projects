<?php
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');

$dbconn = new DbConn;
$oProduct = new ProductManagement;

//if($_POST){ print_r($_REQUEST); }  die();
$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];

$competitor_product_id=$_REQUEST['competitor_product_id'];
$category_id=$_REQUEST['selected_category_id'];
if(!empty($category_id)){
	$request_param['category_id']=$category_id;
}
$brand_id=$_REQUEST['select_brand_id'];
if(!empty($brand_id)){
	$request_param['brand_id']=$brand_id;
}
$product_id=$_REQUEST['product_id'];
if(!empty($product_id)){
	$request_param['product_id'] = $product_id;
}
if(!empty($product_id)){
	$product_result = $oProduct->arrGetProductDetails($product_id,$category_id,$brand_id,'',"","","","","","","","","","",'',"",'',"");
	//print_r($product_result);
	$product_name = $product_result[0]['product_name'];
	if(!empty($product_name)){
		//echo $product_name;
		$product_info_result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,$product_name,"","","","","","","","","");
		//print_r($product_info_result);
		$product_info_id     = $product_info_result[0]['product_name_id'];
	}	
	//die();
}

//$product_info_id=$_REQUEST['select_model_id'];
if(!empty($product_info_id)){
	//if(!empty($product_id)){$product_info_id=0;}
	$request_param['product_info_id']=$product_info_id;
}

$product_ids=$_REQUEST['comp_product_id'];
if(!empty($product_ids)){
		$request_param['product_ids']=$product_ids;
}
$status=$_REQUEST['status'];
if($status!=''){
		$request_param['status']=$status;
}
if($actiontype == 'Insert'){
	if($request_param >0){
		
		$competitor_product_id=$oProduct->addUpdCompareTopCompetitorDetails($request_param);
		$msg="most popular compare set added";
	}
}
else if($actiontype == 'Update'){
	if(!empty($competitor_product_id)){
		$request_param['competitor_product_id']=$competitor_product_id;
	}
	if($request_param >0){
		$competitor_product_id=$oProduct->addUpdCompareTopCompetitorDetails($request_param);
		$msg="most popular compare set updated";
	}
}
else if($actiontype == 'Delete'){
	$result = $oProduct->boolDeleteCompareTopCompetitorDetail($competitor_product_id);
	$msg = 'most popular Detail deleted successfully.';
}

$config_details = get_config_details();
$strXML = "<?xml version='1.0' encoding='iso-8859-1'?>";
$strXML .= "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('xsl/compare_top_competitor.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
