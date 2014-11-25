<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'overview.class.php');
	require_once(CLASSPATH.'feature.class.php');
	$dbconn = new DbConn;
	$overview = new OverviewManagement;
	$feature = new FeatureManagement;
	
	$category_id = $_REQUEST['selected_category_id'];
	$category_id = ($category_id != "") ? $category_id : SITE_CATEGORY_ID;
	$main_group_id =  $_REQUEST['sel_main_group'] ?  $_REQUEST['sel_main_group'] : $_REQUEST['main_group'];
	$feature_id = $_REQUEST['feature_id'];
	//print_R($_REQUEST);
	if($category_id != ''){
		$request_param['category_id'] = $category_id;
		$result = $feature->arrGetFeatureDetails($feature_id);
		if(sizeof($result) > 0){
			$request_param['overview_sub_group_id'] = $result[0]['main_feature_group'];
		}
	}
	
	if(!empty($feature_id)){
		$request_param['feature_id'] = $feature_id;
	}
	$feature_title = trim($_REQUEST['feature_title']);
	if(!empty($feature_title)){
		$request_param['title'] = htmlentities($feature_title,ENT_QUOTES);
	}
	$feature_display_unit = trim($_REQUEST['feature_display_unit']);
	if(!empty($feature_display_unit)){
		$request_param['abbreviation'] = htmlentities($feature_display_unit,ENT_QUOTES);
	}
	$status = $_REQUEST['feature_overview_status'];
	if($status != ''){
		$request_param['status'] = $status;
	}
	
	$overview_id = $_REQUEST['overview_id'];
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
    $limitcnt = $_REQUEST['cnt'];	
	
	
	//print_r($_REQUEST);
	if($actiontype == 'Delete'){
	   $result = $overview->boolDeleteFeaturedOverview($overview_id);
	   $msg = 'Feature Overview deleted successfully.';
	}elseif($actiontype == 'Update' && !empty($feature_id)){
	   //$result = $brand->boolUpdateBrand($brand_id,$request_param);
	  // $msg = 'Brand updated successfully.';
	}elseif($actiontype == 'insert' && !empty($feature_id)){
	   $result = $overview->intInsertFeaturedOverview($request_param);	 
	  // echo "overview id = $result";exit;
	   $msg = ($result == 'exists') ? 'Feature Overview already exists.' : 'Feature Overview added successfully.';
	}
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<SELECTED_MAIN_GROUP_ID><![CDATA[$main_group_id]]></SELECTED_MAIN_GROUP_ID>";
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
	$xsl = DOMDocument::load('xsl/feature_overview.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
