<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'pager.class.php');

	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;
	$oPager = new Pager;

	$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : $_REQUEST['selected_category_id'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	if(!empty($category_id)){
		$FeatureDetailscnt = $feature->arrGetFeatureDetailsCnt("",$category_id);
		$iFeatureDetailsCount = $FeatureDetailscnt[0]['cnt'];

	if($iFeatureDetailsCount!=0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage=20;
	$start  = $oPager->findStart($perpage);
	$recordcount=$iFeatureDetailsCount;
	$sExtraParam="ajax/feature_dashboard.php,sFeatureDetailsOverDiv,$category_id";
	$jsparams=$start.",".$perpage.",".$sExtraParam;
	$pages= $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"sFeatureDetailsOverPagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}
	 //$orderby=" order by create_date desc";
	  $result = $feature->arrGetFeatureDetails("",$category_id,"","","","","");
	  #print_r($result);
   }

	}
//	print_r($result);exit;
	$cnt = sizeof($result);
	$xml = "<FEATURE_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$feature_unit="";
		$unit_id = $result[$i]['unit_id'];
		if(!empty($unit_id)){			
			$unit_result = $feature->arrFeatureUnitDetails($unit_id,$categoryid);
			$feature_unit = $unit_result[0]['unit_name'];
		}
		
		$result[$i]['js_feature_name'] = $result[$i]['feature_name'];
		$feature_sub_group = $result[$i]['feature_group'];
		if(!empty($feature_sub_group)){
			$feature_group_result = $feature->arrFeatureSubGroupDetails($feature_sub_group);	
			$feature_group_name = $feature_group_result[0]['sub_group_name'];
		}
		$result[$i]['js_feature_group'] = $feature_group_name;
		$feature_description = $result[$i]['feature_description'];
                if($feature_description != ""){
                        $feature_description = html_entity_decode($feature_description,ENT_QUOTES);
                        $feature_description = str_replace('&amp;amp;',"",$feature_description);
                        $feature_description = str_replace('&#039;',"'",$feature_description);
                        $feature_description = str_replace('#039;',"'",$feature_description);
                }
		$result[$i]['js_feature_desc'] = urlencode($feature_description);
		$result[$i]['js_feature_unit'] = $feature_unit;
		$result[$i]['feature_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['feature_unit'] = $feature_unit ? html_entity_decode($feature_unit,ENT_QUOTES) : 'Nil';

		$result[$i]['feature_group_name'] = $feature_group_name ? html_entity_decode($feature_group_name,ENT_QUOTES) : 'Nil';
		$result[$i]['feature_desc'] = $feature_description ? html_entity_decode($feature_description,ENT_QUOTES) : 'Nil';
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_feature_name'] = $result[$i]['feature_name'];
		$result[$i]['feature_name'] = $result[$i]['feature_name'] ? html_entity_decode($result[$i]['feature_name'],ENT_QUOTES) : 'Nil';

		$feature_group_name = "";

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		#print_r($result[$i]);
		$xml .= "<FEATURE_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_MASTER_DATA>";
	}
	$xml .= "</FEATURE_MASTER>";
	
	$main_group_result = $feature->arrGetFeatureMainGroupDetails("",$category_id);
	$mainGroupCnt = sizeof($main_group_result);
	$xmlMain .= "<FEATURE_GROUP_MASTER>";
        $xmlMain .= "<COUNT><![CDATA[$mainGroupCnt]]></COUNT>";
        for($i=0;$i<$mainGroupCnt;$i++){
                $status = $main_group_result[$i]['status'];
                $categoryid = $main_group_result[$i]['category_id'];
                if(!empty($categoryid)){
                        $category_result = $category->arrGetCategoryDetails($categoryid);
                }
                $category_name = $category_result[0]['category_name'];
                $main_group_result[$i]['js_category_name'] = $category_name;
                $main_group_result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
                $main_group_result[$i]['main_group_status'] = ($status == 1) ? 'Active' : 'InActive';
                $main_group_result[$i]['create_date'] = date('d-m-Y',strtotime($main_group_result[$i]['create_date']));
                $main_group_result[$i]['js_main_group_name'] = $main_group_result[$i]['main_group_name'];
                $main_group_result[$i]['main_group_name'] = html_entity_decode($main_group_result[$i]['main_group_name'],ENT_QUOTES);
                $main_group_result[$i] = array_change_key_case($main_group_result[$i],CASE_UPPER);
                $xmlMain .= "<FEATURE_GROUP_MASTER_DATA>";
                foreach($main_group_result[$i] as $k=>$v){
                        $xmlMain .= "<$k><![CDATA[$v]]></$k>";
                }
                $xmlMain .= "</FEATURE_GROUP_MASTER_DATA>";
        }
        $xmlMain .= "</FEATURE_GROUP_MASTER>";	

		$feature_unit_result = $feature->arrFeatureUnitDetails("",$category_id);
	$featureCnt = sizeof($feature_unit_result);
	$xml .= "<FEATURE_UNIT>";
	$xml .= "<CNT><![CDATA[$featureCnt]]></CNT>";
	for($i=0;$i<$featureCnt;$i++){
		$feature_unit_result[$i] = array_change_key_case($feature_unit_result[$i],CASE_UPPER);
		$xml .= "<FEATURE_UNIT_DATA>";	
		foreach($feature_unit_result[$i] as $k=>$v){
		   $xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_UNIT_DATA>";	
	}
	$xml .= "</FEATURE_UNIT>";
	
	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $xmlMain;
	$strXML .=$nodesPaging;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/feature_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
