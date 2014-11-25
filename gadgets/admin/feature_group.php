<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');
	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	//print_r($_REQUEST);
	$category_level = $_REQUEST['selected_category_id'];
	$main_group_name = trim($_REQUEST['main_group_name']);
	$seo_path = trim($_REQUEST['seo_path']);
	$overview_display_group_name = trim($_REQUEST['overview_display_group_name']);
	$status = $_REQUEST['main_group_status'];
	$main_group_id = $_REQUEST['main_group_id'];
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
    $limitcnt = $_REQUEST['cnt'];	
	
	if(!empty($main_group_name)){
		$request_param['main_group_name'] = htmlentities($main_group_name,ENT_QUOTES);
	}
	if(!empty($seo_path)){
		$request_param['seo_path'] = htmlentities($seo_path,ENT_QUOTES);
	}
	if(!empty($overview_display_group_name)){
		$request_param['overview_display_name'] = htmlentities($overview_display_group_name,ENT_QUOTES);
	}
	if($status != ''){
		$request_param['status'] = $status;
	}
	if($category_level != ''){
		$request_param['category_id'] = $category_level;
	}
	//print_r($request_param);
	if($actiontype == 'Delete'){
	   $result = $feature->boolDeleteFeatureMainGroup($main_group_id);
	   $msg = 'Feature Group deleted successfully.';
	}elseif($actiontype == 'Update'){
	   $result = $feature->boolUpdateFeatureMainGroup($main_group_id,$request_param);
	   $msg = 'Feature Group updated successfully.';
	}elseif($actiontype == 'insert'){
	   $result = $feature->insertFeatureMainGroup($request_param);
	   $msg = ($result == 'exists') ? 'Feature Group already exists.' : 'Feature Group added successfully.';
	}
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_level]]></SELECTED_CATEGORY_ID>";
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
	$xsl = DOMDocument::load('xsl/feature_group.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
