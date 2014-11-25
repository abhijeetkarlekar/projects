<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');
	
	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;

	//if($_POST){
		//print "<pre>";  print_r($_REQUEST); die();
	//}
	$category_id = $_REQUEST['selected_category_id'];
	$category_id = ($category_id != "") ? $category_id : SITE_CATEGORY_ID;
	$category_level = $category_id;
	$startlimit = $_REQUEST['startlimit'];
    $limitcnt = $_REQUEST['cnt'];
	$feature_id = $_REQUEST['feature_id'];
	$featureboxcnt = $_REQUEST['featureboxcnt'];
	$actiontype = $_REQUEST['actiontype'];
	if($actiontype == 'Update' || $actiontype == 'insert'){
		for($i=0;$i<$featureboxcnt;$i++){
			$feature_name = trim($_REQUEST["feature_name_".$i]);
			$feature_name = htmlentities($feature_name,ENT_QUOTES);
			$seo_path = trim($_REQUEST["seo_path_".$i]);
			$seo_path = htmlentities($seo_path,ENT_QUOTES);
			if(!empty($feature_name)){
				$main_feature_group = $_REQUEST['select_main_group_'.$i];
				$feature_group = $_REQUEST['select_feature_group_'.$i] ? $_REQUEST['select_feature_group_'.$i] : $_REQUEST['feature_group_'.$i];
				$feature_group = trim($feature_group);
				$feature_group = htmlentities($feature_group,ENT_QUOTES);
				$feature_description = htmlentities($_REQUEST['feature_description_'.$i],ENT_QUOTES);	
				$feature_description = trim($feature_description);
				$feature_unit = $_REQUEST['feature_unit_'.$i];
				$feature_status = $_REQUEST['feature_status_'.$i];
				$request_param['main_feature_group'] = $main_feature_group;
				$request_param['feature_name'] = $feature_name;
				$request_param['seo_path'] = $seo_path;
				$request_param['category_id'] = $category_id;
				$request_param['feature_description'] = $feature_description;
				$request_param['feature_group'] = htmlentities($feature_group,ENT_QUOTES);
				$request_param['unit_id'] = $feature_unit;
				$request_param['status'] = $feature_status;
				
				$uploadedfile_id = "uploadedfile_".$i;
                                if($_FILES[$uploadedfile_id]["name"] != ""){
					$target_path = BASEPATH."images/";
                                        $name = $_FILES[$uploadedfile_id]['name'];
                                        $name = strtolower(str_replace(' ', '_', trim($name)));

                                        $target_path = $target_path.$name;
                                        rename($_FILES[$uploadedfile_id]['tmp_name'], $target_path);
                                        $request_param['feature_img_path'] = $name;
                                }
                             #print_r($request_param);   
				if($actiontype == 'Update'){
        	   			$result = $feature->boolUpdateFeature($feature_id,$request_param);
           				$msg = 'Feature updated successfully.';
	        		}elseif($actiontype == 'insert'){
					$result = $feature->intInsertFeature($request_param);
           				$msg = ($result == 'exists') ? 'Feature already exists.' : 'Feature added successfully.';
        			}
			}
		}
	}elseif($actiontype == 'Delete'){
           $result = $feature->boolDeleteFeature($feature_id);
           $msg = 'Feature deleted successfully.';
        }
	$main_group_result = $feature->arrGetFeatureMainGroupDetails();
	$mainGroupCnt = sizeof($main_group_result);
	$xml .= "<FEATURE_GROUP_MASTER>";
        $xml .= "<COUNT><![CDATA[$mainGroupCnt]]></COUNT>";
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
                $xml .= "<FEATURE_GROUP_MASTER_DATA>";
                foreach($main_group_result[$i] as $k=>$v){
                        $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</FEATURE_GROUP_MASTER_DATA>";
        }
        $xml .= "</FEATURE_GROUP_MASTER>";	
		
	$feature_unit_result = $feature->arrFeatureUnitDetails("",$categoryid);
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
	$feature_group_result = $feature->arrFeatureSubGroupDetails();	
	
	$groupCnt = sizeof($feature_group_result);
	$xml .= "<FEATURE_GROUP>";
	$xml .= "<CNT><![CDATA[$groupCnt]]></CNT>";
	for($i=0;$i<$groupCnt;$i++){
		$feature_group_result[$i]['js_feature_group'] = $feature_group_result[$i]['sub_group_name'];
		$feature_group_result[$i]['sub_group_name'] = html_entity_decode($feature_group_result[$i]['sub_group_name'],ENT_QUOTES);
		$feature_group_result[$i] = array_change_key_case($feature_group_result[$i],CASE_UPPER);
		$xml .= "<FEATURE_GROUP_DATA>";
		foreach($feature_group_result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_GROUP_DATA>";
	}
	$xml .= "</FEATURE_GROUP>";
	//header('Content-type: text/xml');echo $xml;exit;
	$config_details = get_config_details();

	$strXML = "<XML>";
	$strXML .= $config_details;
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
        $strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_level]]></SELECTED_CATEGORY_ID>";
        $strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
        $strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $xml;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('xsl/feature.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
