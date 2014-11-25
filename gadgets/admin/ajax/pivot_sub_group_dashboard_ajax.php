<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');
	require_once(CLASSPATH.'category.class.php');

	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	$category = new CategoryManagement;

	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	if(!empty($category_id)){
		$result = $pivot->arrPivotSubGroupDetails("",$category_id,"","","");
	}
	$cnt = sizeof($result);
	$xml = "<PIVOT_GROUP_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['sub_group_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_sub_group_name'] = $result[$i]['sub_group_name'];
		$result[$i]['sub_group_name'] = html_entity_decode($result[$i]['sub_group_name'],ENT_QUOTES);
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<PIVOT_GROUP_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</PIVOT_GROUP_MASTER_DATA>";
	}
	$xml .= "</PIVOT_GROUP_MASTER>";

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
	$xsl = DOMDocument::load('../xsl/pivot_sub_group_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
