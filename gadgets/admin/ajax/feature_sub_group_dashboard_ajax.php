<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');

	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;

	
	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	if(!empty($category_id)){
		$result = $feature->arrFeatureSubGroupDetails("",$category_id,"",$startlimit,$limitcnt);
	}
	$cnt = sizeof($result);
	for($i=0;$i<$cnt;$i++){		
		$categoryid = $result[$i]['category_id'];
		$main_group_id = $result[$i]['main_group_id'];
		$aMainFeatureGroup = $feature->arrGetFeatureMainGroupDetails($main_group_id,$categoryid);
		$main_group_name = $aMainFeatureGroup[0]['main_group_name'];
		$result[$i]['main_group_name'] = html_entity_decode($main_group_name,ENT_QUOTES);
		unset($aMainFeatureGroup);
	}
	$multikey = 'main_group_name';
	$result = multi_sort_ascending($result);
	
	
	$xml = "<FEATURE_GROUP_MASTER>";
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
		$xml .= "<FEATURE_GROUP_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_GROUP_MASTER_DATA>";
	}
	$xml .= "</FEATURE_GROUP_MASTER>";

	$aMainFeatureGroup=$feature->arrGetFeatureMainGroupDetails("",$category_id);
	
	$sMainFeatureGroup=arraytoxml($aMainFeatureGroup,"MAIN_GROUP_MASTER_DATA");
	$sMainFeatureGroupXml ="<MAIN_GROUP_MASTER>".$sMainFeatureGroup."</MAIN_GROUP_MASTER>";	
	
	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $config_details;
	$strXML .= $sMainFeatureGroupXml;
	
	$strXML .= $xml;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/feature_sub_group_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
