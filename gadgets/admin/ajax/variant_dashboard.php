<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'price.class.php');
	require_once(CLASSPATH.'category.class.php');

	$dbconn = new DbConn;
	$oPrice = new price;
	$category = new CategoryManagement;

	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$cnt = 0;
	if(!empty($category_id)){
		$result = $oPrice->arrGetVariantDetail("",$category_id,"",$startlimit,$limitcnt);
		$cnt = sizeof($result);
	}


	$xml = "<VARIANT_MASTER>";
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
		$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_variant'] = $result[$i]['variant'];
		$result[$i]['variant'] = html_entity_decode($result[$i]['variant'],ENT_QUOTES);
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<VARIANT_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</VARIANT_MASTER_DATA>";
	}
	$xml .= "</VARIANT_MASTER>";

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= "</XML>";

	#header('Content-type: text/xml');echo $strXML;exit;

	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();

	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/variant_dashboard.xsl');

	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
