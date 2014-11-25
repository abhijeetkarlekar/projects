<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'product.class.php');
	require_once(CLASSPATH.'price.class.php');
	require_once(CLASSPATH.'brand.class.php');
	require_once(CLASSPATH.'pager.class.php');

	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;
	$product = new ProductManagement;
	$oPrice = new price;
	$brand = new BrandManagement;
	$oPager = new Pager;

	//print"<pre>";print_r($_REQUEST);print"</pre>"; die();
	$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : SITE_CATEGORY_ID;
	$selected_brand_id="";$selected_model_id="";$selected_model_name = "";$selected_variant_id="";
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$selected_brand_id = ($_REQUEST['selected_brand_id'] !="") ? $_REQUEST['selected_brand_id'] : '';
        $selected_model_id = ($_REQUEST['selected_model_id'] != "") ? $_REQUEST['selected_model_id'] : '' ;
        $selected_variant_id = ($_REQUEST['selected_variant_id'] != "") ? $_REQUEST['selected_variant_id'] : '' ;

	if($selected_model_id != ""){
		$selected_model_name_arr = $product->arrGetProductNameInfo($selected_model_id,$category_id,$selected_brand_id,"","","","");
		$selected_model_name = $selected_model_name_arr[0]['product_info_name'];
	}


	//$aProductItemCount = $product->arrGetProductDetailsCount("",$category_id,"","","","","");
	$aProductItemCount = $product->arrGetProductDetailsCount($selected_variant_id,$category_id,$selected_brand_id,'',"","","","",$selected_model_name,"","",'','');
	$iProductItemCount=$aProductItemCount[0]['cnt'];
	if($iProductItemCount!=0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage=20;
	$start  = $oPager->findStart($perpage);
	$recordcount=$iProductItemCount;
	$sExtraParam="ajax/product_ajax_list.php,sProductDiv,$category_id";
	$jsparams=$start.",".$perpage.",".$sExtraParam;
	$pages= $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"sProductPagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}
	$orderby=" order by PRODUCT_MASTER.create_date desc";
	//$result = $product->arrGetProductDetails("",$category_id,"","","","","",$start,$perpage,"",$orderby);
		$result = $product->arrGetProductDetails($selected_variant_id,$category_id,$selected_brand_id,'',"","","","","","",$orderby,$selected_model_name,"","",'',"",'');
	}

	//print_r($result);exit;

	$cnt = sizeof($result);
	$xml = "<PRODUCT_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$discontinue_status = $result[$i]['discontinue_flag'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$brand_id = $result[$i]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
		}
		$product_name = $result[$i]['product_name'];
		$variant = $result[$i]['variant'];
		$result[$i]['js_brand_name'] = $brand_name;
		$result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : 'Nil';
		$result[$i]['product_title_name'] = implode(" ",array($product_name,$variant));
		$result[$i]['js_product_name'] = $product_name;
		$result[$i]['product_name'] = $product_name ? html_entity_decode($product_name,ENT_QUOTES) : 'Nil';

		$product_id = $result[$i]['product_id'];
		if(!empty($product_id)){
			$price_result = $oPrice->arrGetVariantValueDetail("","1",$product_id,$categoryid,$brand_id);;
			$price = $price_result[0]['variant_value'];
		}
		$result[$i]['price'] = ($price != "") ? $price : '';
		$result[$i]['product_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['discontinue_status'] = ($discontinue_status == 1) ? '---' : 'Discontinued';

		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_feature_name'] = $result[$i]['feature_name'];

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<PRODUCT_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</PRODUCT_MASTER_DATA>";
	}
	$xml .= "</PRODUCT_MASTER>";

	if(!empty($category_id)){
		$result = $brand->arrGetBrandDetails("",$category_id);
	}
	$cnt = sizeof($result);
        $xml .= "<BRAND_MASTER>";
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
                $result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
                $result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
                $result[$i]['js_brand_name'] = $result[$i]['brand_name'];
                $result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'],ENT_QUOTES);
                $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
                $xml .= "<BRAND_MASTER_DATA>";
                foreach($result[$i] as $k=>$v){
                        $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</BRAND_MASTER_DATA>";
        }
        $xml .= "</BRAND_MASTER>";

	if(!empty($category_id)){
		//$startlimit = 0;$limitcnt=2;
		$result = $feature->arrGetFeatureMainGroupDetails("",$category_id,"",$startlimit,$limitcnt);
	}
	$cnt = sizeof($result);
	$featureboxcntArr=Array();
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$main_group_id = $result[$i]['group_id'];
		$categoryid = $result[$i]['category_id'];
		$main_feature_group_name = $result[$i]['main_group_name'];
               	//$result['main_group_details'][$i]['main_feature_group_name'] = $main_feature_group_name;
		if(!empty($categoryid)){
                        $category_name = $category_result[0]['category_name'];
                        $result[$i]['js_category_name'] = $category_name;
                        $result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
                        $feature_result = $feature->arrGetFeatureDetails("",$category_id,$main_group_id,"","");
			$featureCnt = sizeof($feature_result);
			$featureboxcntArr[] = $featureCnt;
			for($j=0;$j<$featureCnt;$j++){
				$feature_group = $feature_result[$j]['feature_group'];
				$feature_sub_group_array = $feature->arrFeatureSubGroupDetails($feature_group,"","");
				$sub_group_name = $feature_sub_group_array[0]['sub_group_name'];
				$main_feature_group = $feature_result[$j]['main_feature_group'];

				$status = $feature_result[$j]['status'];

                                $categoryid = $feature_result[$j]['category_id'];
                                $feature_id = $feature_result[$j]['feature_id'];

                                $unit_id = $feature_result[$j]['unit_id'];
                                if(!empty($unit_id)){
                                        $unit_result = $feature->arrFeatureUnitDetails($unit_id,$categoryid);
                                        $feature_unit = $unit_result[0]['unit_name'];
                                }
                                if(!empty($feature_id)){
                                        $pivot_result = $pivot->arrGetPivotDetails("",$categoryid,$feature_id,"");
                                }

                                $feature_result[$j]['pivot_feature_id'] = $pivot_result[0]['feature_id'];
                                $feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
                                $feature_result[$j]['js_feature_group'] = $feature_result[$j]['feature_group'];
                                $feature_result[$j]['js_feature_desc'] = $feature_result[$j]['feature_description'];
                                $feature_result[$j]['js_feature_unit'] = $feature_unit;
                                $feature_result[$j]['feature_status'] = ($status == 1) ? 'Active' : 'InActive';
                                $feature_result[$j]['feature_unit'] = $feature_unit ? html_entity_decode($feature_unit,ENT_QUOTES) : 'Nil';
                                $feature_result[$j]['feature_group'] = $feature_result[$j]['feature_group'] ? html_entity_decode($feature_result[$j]['feature_group'],ENT_QUOTES) : 'Nil';
                                $feature_result[$j]['feature_desc'] = $feature_result[$j]['feature_desc'] ? html_entity_decode($feature_result[$j]['feature_desc'],ENT_QUOTES) : 'Nil';
                                $feature_result[$j]['create_date'] = date('d-m-Y',strtotime($feature_result[$j]['create_date']));
                                $feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
                                $feature_result[$j]['feature_name'] = $feature_result[$j]['feature_name'] ? html_entity_decode($feature_result[$j]['feature_name'],ENT_QUOTES) : 'Nil';

				$featureresult[$main_group_id][$feature_group][] = $feature_result[$j];
				$featureresult[$main_group_id][$feature_group]['sub_group_name'] = $feature_sub_group_array[0]['sub_group_name'];
				$featureresult[$main_group_id][$feature_group]['sub_group_id'] = $feature_group;
				foreach($result[$i] as $k=> $v){
					$featureresult[$main_group_id][$k] = $v;
				}
			}
		}



	}
	$groupnodexml .= "<GROUP_MASTER>";
	if($featureresult){
		foreach($featureresult as $maingroupkey => $maingroupval){
			 //echo "1st = ".$maingroupkey."+ & $maingroupval<br>";
			if(is_array($maingroupval)){
				//echo "outside foreach 2nd = $maingroupkey=>$maingroupval<br/>";
				$groupnodexml .= "<GROUP_MASTER_DATA>";
				foreach($maingroupval as $subgroupkey=>$subgroupval){

					if(is_array($subgroupval)){
						$groupnodexml .= "<SUB_GROUP_MASTER>";
						 foreach($subgroupval as $key => $featuredata){
							if(is_array($featuredata)){
								$groupnodexml .= "<SUB_GROUP_MASTER_DATA>";
								$featuredata = array_change_key_case($featuredata,CASE_UPPER);
								foreach($featuredata as $featurekey => $featureval){
                                                                        $groupnodexml .= "<$featurekey><![CDATA[$featureval]]></$featurekey>";
                                                                }
								$groupnodexml .= "</SUB_GROUP_MASTER_DATA>";
							}else{
								$groupnodexml .= "<".strtoupper($key)."><![CDATA[$featuredata]]></".strtoupper($key).">";
							}
						}
						$groupnodexml .= "</SUB_GROUP_MASTER>";
					}else{
						 $groupnodexml .= "<".strtoupper($subgroupkey)."><![CDATA[$subgroupval]]></".strtoupper($subgroupkey).">";
					}


				}
				$groupnodexml .= "</GROUP_MASTER_DATA>";
			}
		}
	}
	$groupnodexml .= "</GROUP_MASTER>";
	$xml .= $groupnodexml;

	//header('Content-type: text/xml');echo $groupnodexml;exit;
	//print_r($featureresult);exit;
	/*
	$xml .= "<FEATURE_GROUP_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$main_group_id = $result[$i]['group_id'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['main_group_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_main_group_name'] = $result[$i]['main_group_name'];
		$result[$i]['main_group_name'] = html_entity_decode($result[$i]['main_group_name'],ENT_QUOTES);
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<FEATURE_GROUP_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</FEATURE_GROUP_MASTER_DATA>";
	}
	$xml .= "</FEATURE_GROUP_MASTER>";

	if(!empty($category_id)){
		$result = $feature->arrGetFeatureDetails("",$category_id,"","","",$startlimit,$limitcnt);
	}
	$multikey = 'main_feature_group';
	$result = multi_sort($result);
	//print_r($result);exit;
	$cnt = sizeof($result);
	$xml .= "<FEATURE_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		$feature_id = $result[$i]['feature_id'];
		$main_feature_group = $result[$i]['main_feature_group'];
		$main_feature_group_array = $feature->arrGetFeatureMainGroupDetails($main_feature_group);
		$main_feature_group_name = $main_feature_group_array[0]['main_group_name'];
		$result[$i]['main_feature_group_name'] = $main_feature_group_name;
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$unit_id = $result[$i]['unit_id'];
		if(!empty($unit_id)){
			$unit_result = $feature->arrFeatureUnitDetails($unit_id);
			$feature_unit = $unit_result[0]['unit_name'];
		}
		if(!empty($feature_id)){
			$pivot_result = $pivot->arrGetPivotDetails("",$categoryid,$feature_id,"");
		}
		$result[$i]['pivot_feature_id'] = $pivot_result[0]['feature_id'];
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
	*/


	$result = $product->arrGetProductNameInfo("",$category_id,$brand_id,"","1",$startlimit,$cnt);
	$cnt = sizeof($result);
    $xml .= "<MODEL_MASTER>";
	for($i=0;$i<$cnt;$i++){
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
                $xml .= "<MODEL_MASTER_DATA>";
                foreach($result[$i] as $k=>$v){
                        $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</MODEL_MASTER_DATA>";
        }
    $xml .= "</MODEL_MASTER>";


	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<SELECTED_BRAND_ID><![CDATA[$selected_brand_id]]></SELECTED_BRAND_ID>";
    $strXML .= "<SELECTED_MODEL_ID><![CDATA[$selected_model_id]]></SELECTED_MODEL_ID>";
    $strXML .= "<SELECTED_VARIANT_ID><![CDATA[$selected_variant_id]]></SELECTED_VARIANT_ID>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $nodesPaging;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/product_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
