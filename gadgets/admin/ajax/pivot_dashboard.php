<?php	
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'pager.class.php');
	
	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;
	$oPager = new Pager;

	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	
	if(!empty($category_id)){
		$result = $feature->arrGetFeatureDetails("",$category_id,"","",1,"","");
	}

//	print_r($result);exit;

	$cnt = sizeof($result);
	$xml = "<FEATURE_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->$result = $category->arrGetCategoryDetails($categoryid);
		}
		$unit_id = $result[$i]['unit_id'];
		if(!empty($unit_id)){
			$unit_result = $feature->arrFeatureUnitDetails($unit_id,$categoryid);
			$feature_unit = $unit_result[0]['unit_name'];
		}
		$featureidsArr[] = $result[$i]['feature_id'];		
		$featurenameArr[] = $result[$i]['feature_name'];

		$result[$i]['js_feature_name'] = $result[$i]['feature_name'];
		$result[$i]['js_feature_group'] = $result[$i]['feature_group'];
		$result[$i]['js_feature_desc'] = $result[$i]['feature_description'];
		$result[$i]['js_feature_unit'] = $feature_unit;
		$result[$i]['feature_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['feature_unit'] = $feature_unit ? html_entity_decode($feature_unit,ENT_QUOTES) : 'Nil';
		$result[$i]['feature_group'] = $result[$i]['feature_group'] ? html_entity_decode($result[$i]['feature_group'],ENT_QUOTES) : 'Nil';
		$result[$i]['feature_desc'] = $result[$i]['feature_desc'] ? html_entity_decode($result[$i]['feature_desc'],ENT_QUOTES) : 'Nil';
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_feature_name'] = $result[$i]['feature_name'];
		$result[$i]['feature_name'] = $result[$i]['feature_name'] ? html_entity_decode($result[$i]['feature_name'],ENT_QUOTES) : 'Nil';
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<FEATURE_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_MASTER_DATA>";
	}
	$xml .= "</FEATURE_MASTER>";
	
	if(!empty($category_id)){
		$GetPivotcnt = $pivot->arrGetPivotDetails("",$category_id,"","","","");
		$iGetPivotCount=count($GetPivotcnt);

	if($iGetPivotCount!=0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage=20;
	$start  = $oPager->findStart($perpage);
	$recordcount=$iGetPivotCount;
	$sExtraParam="ajax/pivot_dashboard.php,sPivotOverDiv,$category_id";
	$jsparams=$start.",".$perpage.",".$sExtraParam;
	$pages= $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"sPivotOverPagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}
	 //$orderby=" order by create_date desc";
	  $result = $pivot->arrGetPivotDetails("",$category_id,"","","",$start,$perpage);
	 }
	}
	//print_r($result);exit;
	$cnt = sizeof($result);
	$xml .= "<PIVOT_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		
		$pivot_display_id = $result[$i]['pivot_display_id'];
		if(!empty($pivot_display_id)){
			$display_result = $pivot->arrPivotDisplayDetails($pivot_display_id);
		}
		$result[$i]['pivot_display_type'] = $display_result[0]['pivot_display_name'] ? html_entity_decode($display_result[0]['pivot_display_name'],ENT_QUOTES) : 'Nil';
		
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'] ? $category_result[0]['category_name'] : 'Nil';
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);

		$feature_id = $result[$i]['feature_id'];
		
		if(!empty($feature_id)){			
			$feature_result = $feature->arrGetFeatureDetails($feature_id,$categoryid);
		}
		$pivot_sub_group = $result[$i]['pivot_group'];
		if(!empty($pivot_sub_group)){
			$pivot_group_result = $pivot->arrPivotSubGroupDetails($pivot_sub_group);	
			$pivot_group_name = $pivot_group_result[0]['sub_group_name'];
		}
		$result[$i]['pivot_group_name'] = $pivot_group_name;
		$feature_name = $feature_result[0]['feature_name'];
		$pivot_name =  $feature_name ? $feature_name : 'Nil';
		$result[$i]['js_pivot_name'] = $pivot_name;
		$result[$i]['pivot_name'] = html_entity_decode($pivot_name,ENT_QUOTES);
		
		$pivot_group = $result[$i]['pivot_group'];
		$result[$i]['js_pivot_group'] = $pivot_group;
		$result[$i]['pivot_group'] = $pivot_group ? $pivot_group : 'Nil';
		$pivot_desc = $result[$i]['pivot_desc'];
		$result[$i]['js_pivot_desc'] = $pivot_desc;
		$result[$i]['pivot_desc'] = $pivot_desc ? $pivot_desc : 'Nil';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$status = $result[$i]['status'];
		$result[$i]['pivot_status'] = ($status == 1) ? 'Active' : 'InActive';

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<PIVOT_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</PIVOT_MASTER_DATA>";
	}
	$xml .= "</PIVOT_MASTER>";

	$pivot_display_result = $pivot->arrPivotDisplayDetails();
	$displayCnt = sizeof($pivot_display_result);
	$xml .= "<PIVOT_DISPLAY_TYPES>";
	$xml .= "<CNT><![CDATA[$displayCnt]]></CNT>";
	for($i=0;$i<$displayCnt;$i++){
		$pivotStyleIdsArr[] = $pivot_display_result[$i]['pivot_display_id'];
		$pivotStyleValueArr[] = $pivot_display_result[$i]['pivot_display_name'];
		$pivot_display_result[$i] = array_change_key_case($pivot_display_result[$i],CASE_UPPER);
		$xml .= "<PIVOT_DISPLAY_TYPES_DATA>";	
		foreach($pivot_display_result[$i] as $k=>$v){
		   $xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</PIVOT_DISPLAY_TYPES_DATA>";	
	}
	$xml .= "</PIVOT_DISPLAY_TYPES>";
	$pivot_group_result = $pivot->arrPivotSubGroupDetails("",$category_id);	
	$groupCnt = sizeof($pivot_group_result);
	$xml .= "<PIVOT_GROUP>";
	$xml .= "<CNT><![CDATA[$groupCnt]]></CNT>";
	for($i=0;$i<$groupCnt;$i++){
		$pivot_group = $pivot_group_result[$i]['pivot_group'];
		$pivotgroupArr[] = $pivot_group;
		$pivot_subgrp_name = $pivot_group_result[$i]['sub_group_name'];
		$pivotsubgroupArr[] = $pivot_subgrp_name;
		$pivot_subgrp_id = $pivot_group_result[$i]['sub_group_id'];
		$pivotsubgroupidArr[] = $pivot_subgrp_id;
		$pivot_group_result[$i]['SUB_GROUP_ID'] = $pivot_group_result[$i]['sub_group_id'];
		$pivot_group_result[$i]['SUB_GROUP_NAME'] = $pivot_group_result[$i]['sub_group_name'];
		$pivot_group_result[$i]['js_pivot_group'] = $pivot_group;
		$pivot_group_result[$i]['pivot_group'] = html_entity_decode($pivot_group,ENT_QUOTES);

		$pivot_group_result[$i] = array_change_key_case($pivot_group_result[$i],CASE_UPPER);
		$xml .= "<PIVOT_GROUP_DATA>";
		foreach($pivot_group_result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</PIVOT_GROUP_DATA>";
	}
	$xml .= "</PIVOT_GROUP>";

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
	$strXML .= "<FEATURE_NAME_ARR_STR><![CDATA[".implode(",",$featurenameArr)."]]></FEATURE_NAME_ARR_STR>";
	$strXML .= "<FEATURE_IDS_ARR_STR><![CDATA[".implode(",",$featureidsArr)."]]></FEATURE_IDS_ARR_STR>";
	$strXML .= "<PIVOT_GROUP_ARR_STR><![CDATA[".implode(",",$pivotgroupArr)."]]></PIVOT_GROUP_ARR_STR>";
	$strXML .= "<PIVOT_SUB_GROUP_ARR_STR><![CDATA[".implode(",",$pivotsubgroupArr)."]]></PIVOT_SUB_GROUP_ARR_STR>";
	$strXML .= "<PIVOT_SUB_GROUP_ID_ARR_STR><![CDATA[".implode(",",$pivotsubgroupidArr)."]]></PIVOT_SUB_GROUP_ID_ARR_STR>";
	$strXML .= "<PIVOT_STYLE_ID_ARR_STR><![CDATA[".implode(",",$pivotStyleIdsArr)."]]></PIVOT_STYLE_ID_ARR_STR>";
	$strXML .= "<PIVOT_STYLE_VALUE_ARR_STR><![CDATA[".implode(",",$pivotStyleValueArr)."]]></PIVOT_STYLE_VALUE_ARR_STR>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $nodesPaging;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/pivot_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
