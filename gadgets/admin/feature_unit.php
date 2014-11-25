<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');

	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	

	#print "<pre>"; print_r($_POST); //die();

	$unit_id = $_REQUEST['unit_id'];
	$unit_name = trim($_REQUEST['unit_name']);
	$status = $_REQUEST['unit_status'];
	$actiontype = $_REQUEST['actiontype'];
	$category_id = $_REQUEST['selected_category_id'];
	$request_param['category_id'] = $category_id;
	if(!empty($unit_name)){
		$request_param['unit_name'] = htmlentities($unit_name,ENT_QUOTES);
	}
	if($status != ''){
		$request_param['status'] = $status;
	}
//	print_r($request_param);
	if($actiontype == 'Delete'){
	   $result = $feature->boolDeleteFeatureUnit($unit_id);
	   $msg = 'Feature Unit deleted successfully.';
	}elseif($actiontype == 'Update'){
	   $result = $feature->boolUpdateFeatureUnit($unit_id,$request_param);
	   $msg = 'Feature unit updated successfully.';
	}elseif($actiontype == 'insert'){
	   $result = $feature->intInsertFeatureUnit($request_param);
	   $msg = ($result == 'exists') ? 'Feature unit already exists.' : 'Feature unit added successfully.';
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
	$xsl = DOMDocument::load('xsl/feature_unit.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
