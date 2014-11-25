<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');

	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	//print_r($_REQUEST);
	$category_id = $_REQUEST['catid'];
	$unit_id = $_REQUEST['unit_id'];
	$unit_name = $_REQUEST['unit_name'];
	$status = $_REQUEST['unit_status'];
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];

	if(!empty($unit_name)){
		$request_param['unit_name'] = htmlentities($unit_name,ENT_QUOTES);
	}
	if($status != ''){
		$request_param['status'] = $status;
	}
	
	$result = $feature->arrFeatureUnitDetails("",$category_id,$startlimit,$limitcnt);
	$cnt = sizeof($result);
	$xml = "<UNIT_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$result[$i]['unit_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_unit_name'] = $result[$i]['unit_name'];
		$result[$i]['unit_name'] = html_entity_decode($result[$i]['unit_name'],ENT_QUOTES);
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<UNIT_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</UNIT_MASTER_DATA>";
	}
	$xml .= "</UNIT_MASTER>";

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/feature_unit_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
