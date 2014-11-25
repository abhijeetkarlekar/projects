<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');
	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	//print_r($_REQUEST);
	$category_level = $_REQUEST['selected_category_id'];
	$sub_group_name = trim($_REQUEST['main_group_name']);
	$status = $_REQUEST['main_group_status'];
	$main_group_id = $_REQUEST['sub_group_id'];
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
        $limitcnt = $_REQUEST['cnt'];	
	if(!empty($sub_group_name)){
		$request_param['sub_group_name'] = htmlentities($sub_group_name,ENT_QUOTES);
	}
	if($status != ''){
		$request_param['status'] = $status;
	}
	if($category_level != ''){
		$request_param['category_id'] = $category_level;
	}
	//print_r($request_param);
	if($actiontype == 'Delete'){
	   $result = $pivot->boolDeletePivotSubGroupDetails($main_group_id);
	   $msg = 'Pivot Sub group  deleted successfully.';
	}elseif($actiontype == 'Update'){
	   $result = $pivot->boolUpdatePivotSubGroupDetails($main_group_id,$request_param);
	   $msg = 'Pivot Sub group  updated successfully.';
	}elseif($actiontype == 'insert'){
	   $result = $pivot->intInsertPivotSubGroupDetails($request_param);
	   $msg = ($result == 'exists') ? 'Pivot Sub group already exists.' : 'Pivot Sub group added successfully.';
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
	$xsl = DOMDocument::load('xsl/pivot_sub_group.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
