<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');
	
	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	
	
	//print "<pre>"; print_r($_REQUEST);
	$category_id = $_REQUEST['selected_category_id'] ? $_REQUEST['selected_category_id'] : $_REQUEST['category_id'];
	
	$sub_group_name = trim($_REQUEST['main_group_name']);
	$seo_path = trim($_REQUEST['seo_path']);
	$status = $_REQUEST['main_group_status'];
	$main_group_id = $_REQUEST['sub_group_id'];
	$selected_main_group = $_REQUEST['select_main_group'] ? $_REQUEST['select_main_group']:$_REQUEST['main_group_id'];
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
    $limitcnt = $_REQUEST['cnt'];	
	if(!empty($sub_group_name)){
		$request_param['sub_group_name'] = htmlentities($sub_group_name,ENT_QUOTES);
		$request_param['main_group_id'] = $selected_main_group;
	}
	if(!empty($seo_path)){
		$request_param['seo_path'] = htmlentities($seo_path,ENT_QUOTES);
	}
	if($status != ''){
		$request_param['status'] = $status;
	}
	

	$request_param['category_id'] = $category_id;
	#print_r($request_param);
	if($actiontype == 'Delete'){
	   $result = $feature->boolDeleteFeatureSubGroupDetails($main_group_id);
	   $msg = 'Feature Sub group  deleted successfully.';
	}elseif($actiontype == 'Update'){
	   $result = $feature->boolUpdateFeatureSubGroupDetails($main_group_id,$request_param);
	   $msg = 'Feature Sub group  updated successfully.';
	}elseif($actiontype == 'insert'){
	   $result = $feature->intInsertFeatureSubGroupDetails($request_param);
	   $msg = ($result == 'exists') ? 'Feature Sub group already exists.' : 'Feature Sub group added successfully.';
	}
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	
	$aMainFeatureGroup=$feature->arrGetFeatureMainGroupDetails("",$category_id,$status);
	$sMainFeatureGroup=arraytoxml($aMainFeatureGroup,"MAIN_GROUP_MASTER_DATA");
	$sMainFeatureGroupXml ="<MAIN_GROUP_MASTER>".$sMainFeatureGroup."</MAIN_GROUP_MASTER>";
	
	$config_details = get_config_details();
	$strXML = "<?xml version='1.0' encoding='iso-8859-1'?>";
	$strXML .= "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECT_MAIN_GROUP><![CDATA[$selected_main_group]]></SELECT_MAIN_GROUP>";
	$strXML .=$sMainFeatureGroupXml;
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= "</XML>";
	
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('xsl/feature_sub_group.xsl');
	
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>