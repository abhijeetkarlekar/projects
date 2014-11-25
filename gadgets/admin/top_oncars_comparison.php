<?php
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');

$dbconn = new DbConn;
$product = new ProductManagement; 

//if($_POST){ print_r($_REQUEST);} //die();
$actiontype = $_REQUEST['actiontype'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$request_param=$_REQUEST;
$category_id = $_REQUEST['selected_category_id'];
$category_id = ($category_id != "") ? $category_id : SITE_CATEGORY_ID;
$hd_view_section_id = $_REQUEST["hd_view_section_id"];
$select_section_id = $_REQUEST["select_section_id"] ? $_REQUEST["select_section_id"] : $hd_view_section_id;
if($actiontype == 'Insert'|| $actiontype== 'Update'){
	unset($request_param);
	$top_compare_id = $_REQUEST["hd_top_compare_id"];
	$select_compare_set_id =  $_REQUEST["select_compare_set_id"];
	$status = $_REQUEST["status"];
	$ordering = $_REQUEST["ordering"];
	$selected_category_id = $category_id;
	if($status != ""){$request_param['status'] = $status;}
	$request_param['ordering'] = $ordering;
	if($select_compare_set_id != ""){$request_param['oncars_compare_id'] = $select_compare_set_id;}
	if($selected_category_id != ""){$request_param['category_id'] = $selected_category_id;}
	if($select_section_id == "TOP_COMPARISON_SET"){
		$table_name = "TOP_ONCARS_COMPARISON";
	}
	if($actiontype == 'Insert'){
		$result = $product->addUpdTopCompareSetDetails($request_param,$table_name);
		if($sresult>0){$msg = 'Top compare set added successfully.';}
	}elseif($actiontype == 'Update'){
		$request_param['top_compare_id'] = $top_compare_id;
		$result = $product->addUpdTopCompareSetDetails($request_param,$table_name);
		if($sresult>0){$msg = 'Top compare set updated successfully.';}
	}
}

if($actiontype == 'Delete'){
	$top_compare_id = $_REQUEST["hd_top_compare_id"];
	$hd_view_section_id = $_REQUEST["hd_view_section_id"];
	if($hd_view_section_id == "TOP_COMPARISON_SET"){
		$table_name = "TOP_ONCARS_COMPARISON";
	}
	if($top_compare_id!=''){
		$result = $product->boolDeleteTopCompareSetDetail($top_compare_id,$table_name);
		$msg = 'Top compare set deleted successfully.';
	}
}

$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SELECTED_MENU_ID><![CDATA[$menu_level]]></SELECTED_MENU_ID>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= "<SELECTED_SECTION_ID><![CDATA[$select_section_id]]></SELECTED_SECTION_ID>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "</XML>";

if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('xsl/top_oncars_comparison.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>