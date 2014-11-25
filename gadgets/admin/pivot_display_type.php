<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');

	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	//print_r($_REQUEST);
	$pivot_display_id = $_REQUEST['pivot_display_id'];
	$pivot_display_name = trim($_REQUEST['pivot_display_name']);
	$status = $_REQUEST['pivot_display_status'];
	$actiontype = $_REQUEST['actiontype'];
	
	if(!empty($pivot_display_name)){
		$request_param['pivot_display_name'] = htmlentities($pivot_display_name,ENT_QUOTES);
	}
	if($status != ''){
		$request_param['status'] = $status;
	}
	
	if($actiontype == 'Delete'){
	   $result = $pivot->boolDeletePivotDisplayType($pivot_display_id);
	   $msg = 'Pivot display deleted successfully.';
	}elseif($actiontype == 'Update'){
	   $result = $pivot->boolUpdatePivotDisplayType($pivot_display_id,$request_param);
	   $msg = 'Pivot display updated successfully.';
	}elseif($actiontype == 'insert'){
	   $result = $pivot->intInsertPivotDisplayType($request_param);
	   $msg = ($result == 'exists') ? 'Pivot display already exists.' : 'Pivot display added successfully.';
	}
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$result = $pivot->arrPivotDisplayDetails("",$startlimit,$limitcnt);
	$cnt = sizeof($result);
	$xml = "<PIVOT_DISPLAY_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$result[$i]['pivot_display_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_pivot_display_name'] = $result[$i]['pivot_display_name'];
		$result[$i]['pivot_display_name'] = html_entity_decode($result[$i]['pivot_display_name'],ENT_QUOTES);
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<PIVOT_DISPLAY_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</PIVOT_DISPLAY_MASTER_DATA>";
	}
	$xml .= "</PIVOT_DISPLAY_MASTER>";

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
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
	$xsl = DOMDocument::load('xsl/pivot_display_type.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
