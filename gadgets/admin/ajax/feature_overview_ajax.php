<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'overview.class.php');
	require_once(CLASSPATH.'pager.class.php');

	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;
	$overview = new OverviewManagement;
	$oPager = new Pager;

	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$main_group_id = $_REQUEST['group_id'];
	if(!empty($category_id)){
		$result = $feature->arrGetFeatureMainGroupDetails("",$category_id);
	}
	
	$cnt = sizeof($result);
	
	$xml .= "<FEATURE_GROUP_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$xml .= "<FEATURE_GROUP_MASTER_DATA>";

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		foreach($result[$i] as $k=> $v){
			if(strtolower($v) != 'overview'){
				$xml .= "<$k><![CDATA[$v]]></$k>";
			}
		}
		$xml .= "</FEATURE_GROUP_MASTER_DATA>";
	}
	$xml .= "</FEATURE_GROUP_MASTER>";

	if(!empty($category_id) && !empty($main_group_id)){			
		$result = $feature->arrGetFeatureDetails("",$category_id,$main_group_id);		
	}
	
	$cnt = sizeof($result);
	
	$xml .= "<FEATURE_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$xml .= "<FEATURE_MASTER_DATA>";
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		foreach($result[$i] as $k=> $v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_MASTER_DATA>";
	}
	$xml .= "</FEATURE_MASTER>";
	//start code to update position added by rajesh on dated 13-06-2011.
	$currpos = $_REQUEST['pos'];
	$postype = $_REQUEST['type'];
	$pos_overview_id = $_REQUEST['overview_id'];
	if($postype == 'up'){
		$updatepos = $overview->updateVariantModelOverviewPosUp($pos_overview_id,$currpos,$main_group_id);		
	}else if($postype == 'down'){
		$updatepos = $overview->updateVariantModelOverviewPosDown($pos_overview_id,$currpos,$main_group_id);
	}
	//end code to update position added by rajesh on dated 13-06-2011.
	unset($result);
	if(!empty($category_id) && !empty($main_group_id)){
		/*$GetFeatureOverviewcnt = $overview->arrGetFeatureOverview("",$main_group_id,$category_id,"","","","","","","");
        $iGetFeatureviewCount=count($GetFeatureOverviewcnt);

	if($iGetFeatureviewCount!=0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage=20;
	$start  = $oPager->findStart($perpage);
	$recordcount=$iGetFeatureviewCount;
	$sExtraParam="ajax/feature_overview_ajax.php,sFeatureOverDiv,$category_id";
	$jsparams=$start.",".$perpage.",".$sExtraParam;
	$pages= $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"sFeatureOverPagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}
	 //$orderby=" order by create_date desc";
	  $result = $overview->arrGetFeatureOverview("",$main_group_id,$category_id,"","","","","",$start,$perpage);
	  #print_r($result);
	 }*/
	  $result = $overview->arrGetFeatureOverview("",$main_group_id,$category_id,"","","","","",$start,$perpage);
	}

	$cnt = sizeof($result);
	$xml .= "<FEATURE_OVERVIEW_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		$feature_id = $result[$i]['feature_id'];
		$feature_sub_group_id = $result[$i]['overview_sub_group_id'];
		if(!empty($feature_id)){			
			$feature_result = $feature->arrGetFeatureDetails($feature_id);
			$feature_name = $feature_result[0]['feature_name'];
		
		$result[$i]['js_feature_name'] = $feature_name;
		$result[$i]['feature_name'] = $feature_name ? html_entity_decode($feature_name,ENT_QUOTES,'UTF-8') : 'Nil';;
		if(!empty($feature_sub_group_id)){
			$feature_result = $feature->arrFeatureSubGroupDetails($sub_group_id);
			$feature_group_name = $feature_result[0]['sub_group_name'];
		}
		$result[$i]['feature_group_name'] = $feature_group_name;
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		
		$abbrivation = $result[$i]['abbreviation'];
		$result[$i]['js_abbreviation'] = $abbrivation;
		$result[$i]['abbreviation'] = $abbrivation ? html_entity_decode($abbrivation,ENT_QUOTES,'UTF-8') : 'Nil';

		$result[$i]['js_category_name'] = $category_name;		
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
		$result[$i]['feature_overview_status'] = ($status == 1) ? 'Active' : 'InActive';		
		
		$title = $result[$i]['title'];
		$result[$i]['js_title'] = $title;
		$result[$i]['title'] = $title ? html_entity_decode($title,ENT_QUOTES,'UTF-8') : 'Nil';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<FEATURE_OVERVIEW_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_OVERVIEW_MASTER_DATA>";
		}
	}
	$xml .= "</FEATURE_OVERVIEW_MASTER>";

	$config_details = get_config_details();

	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_MAIN_GROUP_ID><![CDATA[$main_group_id]]></SELECTED_MAIN_GROUP_ID>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $nodesPaging;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/feature_overview_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
