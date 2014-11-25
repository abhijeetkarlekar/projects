<?php	
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'product.class.php');
	require_once(CLASSPATH.'brand.class.php');
	require_once(CLASSPATH.'pivot.class.php');
	
	
	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;
	$product = new ProductManagement;
	$brand = new BrandManagement;
	$pivot = new PivotManagement;


	//print"<pre>";print_r($_REQUEST);print"</pre>";

	$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : SITE_CATEGORY_ID;
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$upcoming_product_id = $_REQUEST['upcoming_product_id'];
	$actiontype = $_REQUEST['actiontype'] ;


	//Start code for dashboard	
	if(!empty($category_id)){
		unset($result);
		$result = $product->arrGetUpComingProductDetails("","","","","",$category_id,'',$startlimit,$count);
		//print"<pre>";print_r($result);print"</pre>";	
		$cnt = sizeof($result);
		$xml .= "<UPCOMING_PRODUCT_LIST>";
	        $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        	for($i=0;$i<$cnt;$i++){
			$upcoming_product_id = $result[$i]['upcoming_product_id'];
            		$model_id = $result[$i]['product_name_id'];
			unset($model_res); $product_info_name=''; $brand_id=''; unset($brandresult); $brand_name='';
	                $model_res = $product->arrGetProductNameInfo($model_id,$category_id,"","","","","","","","","","","");
					if(is_array($model_res)){
					$product_info_name = $model_res[0]["product_info_name"];
					}
					$brand_id = $model_res[0]['brand_id'];
					if(!empty($brand_id)){
					$brandresult = $brand->arrGetBrandDetails($brand_id,"","1","","","","","","");
					}
        	        $brand_name = $brandresult[0]['brand_name'];
                	$result[$i]['brand_name'] = $brand_name;
	                $result[$i]['model_name'] = $product_info_name;
			$min_expected_price = $result[$i]['min_expected_price'];
			$min_expected_price_unit = $result[$i]['min_expected_price_unit'];
			$max_expected_price = $result[$i]['max_expected_price'];
			$max_expected_price_unit = $result[$i]['max_expected_price_unit'];

			if($min_expected_price_unit == "100000"){
               			$min_price_unit = "Lakh";
                        }elseif($min_expected_price_unit == "10000000"){
                                $min_price_unit = "Crore";
                        }
			if($max_expected_price_unit == "100000"){
                                $max_price_unit = "Lakh";
                        }elseif($max_expected_price_unit == "10000000"){
                                $max_price_unit = "Crore";
			}
			if($min_expected_price_unit == $max_expected_price_unit){
				$expected_price = $min_expected_price."-".$max_expected_price." ".$min_price_unit;
			}else{
				$expected_price = $min_expected_price." ".$min_price_unit."-".$max_expected_price." ".$max_price_unit;
			}
			if(($min_expected_price == '') && ($max_expected_price == '')){
				$expected_price = "";	
			}
			$result[$i]['expected_price'] = $expected_price;
			$result[$i]['expected_date_text'] = $result[$i]['expected_date_text'];
			$result[$i]['create_date'] = $result[$i]['create_date'];		
			$result[$i]['position'] = $result[$i]['position'];		
			$status = $result[$i]['status'];
			$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';

			$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
			//print "<pre>"; print_r($result[$i]);
	                $xml .= "<UPCOMING_PRODUCT_LIST_DATA>";
        	        foreach($result[$i] as $k=>$v){
                	        $xml .= "<$k><![CDATA[$v]]></$k>";
                	}
                	$xml .= "</UPCOMING_PRODUCT_LIST_DATA>";
		}
		
		$xml .= "<UPCOMING_PRODUCT_LIST_POSITION>";
		//echo $cnt; die();
		if($cnt>0){
			for($ik=0;$ik<=$cnt;$ik++){
				$k = $ik+1;
				$xml .= "<UPCOMING_PRODUCT_POSITION>";
				$xml .= "<POSITION>$k</POSITION>";
				$xml .= "</UPCOMING_PRODUCT_POSITION>";
			}
		}else{
			$xml .= "<UPCOMING_PRODUCT_POSITION>";
			$xml .= "<POSITION>1</POSITION>";
			$xml .= "</UPCOMING_PRODUCT_POSITION>";
		}

		$xml .= "</UPCOMING_PRODUCT_LIST_POSITION>";
		$xml .= "</UPCOMING_PRODUCT_LIST>";
	}
	//End code for dashboard	

	//Start code for update a product	
	$upcoming_product_id = $_REQUEST['upcoming_product_id'];
	if($_REQUEST['act']=='update' && !empty($upcoming_product_id)){
		unset($result);
		$result = $product->arrGetUpComingProductDetails($upcoming_product_id,"","","","",$category_id,"");
	//	print"<pre>";print_r($result);print"</pre>";
		$upcoming_product_id = $result[0]['upcoming_product_id'];
		
		$media_res = $product->arrGetUploadUpcomingMediaDetails($upcoming_product_id);
		$iRelUploadCnt=count($media_res);
		if(is_array($media_res)){
                        foreach($media_res as $iKey=>$aMediaData){
                                $iMediaId=$aMediaData['media_id'];
                                $iMediaThmId=$aMediaData['video_img_id'];
                                $aUploadMediaData[$iKey]=$aMediaData;
				
				$aUploadMediaData[$iKey]['video_path_title'] = $aMediaData['media_path'];
                                $aUploadMediaData[$iKey]['image_path_title'] = $aMediaData['video_img_path'];
                        }
                }
                $sArticleMediaDataDet=arraytoxml($aUploadMediaData,"MEDIA_UPLOAD_DATA");
                $xml .="<MEDIA_UPLOAD_DETAIL>".$sArticleMediaDataDet."</MEDIA_UPLOAD_DETAIL>";

                $result[0]['product_name_id'] = $result[0]['product_name_id'];
                $result[0]['expected_price'] = $result[0]['expected_price'];
                $result[0]['expected_date_text'] = $result[0]['expected_date_text'];
                $result[0]['expected_month'] = $result[0]['expected_month'];
                $result[0]['expected_year'] = $result[0]['expected_year'];
                $short_description = $result[0]['short_description'] ? html_entity_decode($result[0]['short_description'],ENT_QUOTES) : '';
		$result[0]['short_description'] = $short_description;
		$content = $result[0]['content'] ? html_entity_decode($result[0]['content'],ENT_QUOTES) : '';	
		$result[0]['content'] = $content;
		$status = $result[0]['status'];
		$result[0]['status'] = ($status == 1) ? 'Active' : 'InActive';
	
		$result[0] = array_change_key_case($result[0],CASE_UPPER);
	        //print "<pre>"; print_r($result[0]);print"</pre>";
        	$xml .= "<UPCOMING_PRODUCT_DETAILS>";
	        foreach($result[0] as $k1=>$v1){
        	        $xml .= "<$k1><![CDATA[$v1]]></$k1>";
        	}
        	$xml .= "</UPCOMING_PRODUCT_DETAILS>";

	}
	//End code for update a product	
	
	//Start code for Upcoming Product List 	
	unset($result);
	$result = $product->arrGetProductNameInfo("",$category_id,"","","","","","","","","","","");
	//print"<pre>";print_r($result);print"</pre>";
	$cnt = sizeof($result);
        $xml .= "<UPCOMING_PRODUCT_MASTER>";
        $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$result[$i]['product_name_id'] = $result[$i]['product_name_id'];
		$product_info_name = $result[$i]["product_info_name"];
      	      	$brand_id = $result[$i]['brand_id'];
      	      	if(!empty($brand_id)){
              		$brandresult = $brand->arrGetBrandDetails($brand_id,"","1","","","","","","");
		}
              	$brand_name = $brandresult[0]['brand_name'];
		$result[$i]['brand_name'] = $brand_name;
		$result[$i]['model_name'] = $product_info_name;
		$result[$i]['upcoming_product_name'] = $brand_name." ".$product_info_name;

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<UPCOMING_PRODUCT_MASTER_DATA>";
                foreach($result[$i] as $k=>$v){
                        $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</UPCOMING_PRODUCT_MASTER_DATA>";
	}
	$xml .= "</UPCOMING_PRODUCT_MASTER>";
	//End code for UPcoming Product List 	

	//Start code for Months List 	
	$month_array = Array("Jan","Feb","Mar","Apr","May","Jun","July","Aug","Sep","Oct","Nov","Dec");	
	$mon_arr_cnt = sizeof($month_array);
	$xml .= "<MONTH_MASTER>";
	$xml .= "<COUNT><![CDATA[$mon_arr_cnt]]></COUNT>";
	for($i=0;$i<$mon_arr_cnt;$i++){
		$month_text = $i+1;
		$month_val = $month_array[$i];
		$xml .= "<MONTH_MASTER_DATA>";
		$xml .= "<MONTH_TEXT>".$month_text."</MONTH_TEXT>";
		$xml .= "<MONTH_VAL>".$month_val."</MONTH_VAL>";
		$xml .= "</MONTH_MASTER_DATA>";
	}
	$xml .= "</MONTH_MASTER>";
	//End code for Months List 	

	//Start code for Years List 	
	$curr_year = date("Y");
	$end_year = $curr_year+10;
	$xml .= "<YEAR_MASTER>";
        $xml .= "<COUNT>10</COUNT>";	
	for($i=$curr_year;$i<=$end_year;$i++){
		$xml .= "<YEAR_MASTER_DATA>";
		$xml .= "<YEAR_VAL>".$i."</YEAR_VAL>";
		$xml .= "</YEAR_MASTER_DATA>";
	}
	$xml .= "</YEAR_MASTER>";
	//End code for Years List 	

	if(!empty($category_id)){
                unset($result);
                $result = $pivot->arrPivotSubGroupDetails("",$category_id,1);
        }
        $cnt = sizeof($result);
        for($i=0;$i<$cnt;$i++){
                $plusminusimgstatus = 0;
                $status = $result[$i]['status'];
                $sub_group_id = $result[$i]['sub_group_id'];
                $categoryid = $result[$i]['category_id'];
                $sub_group_name = $result[$i]['sub_group_name'];

                if(!empty($categoryid)){
                    $category_name = $category_result[0]['category_name'];
                    $result[$i]['js_category_name'] = $category_name;
                        $result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
                        $pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1",$sub_group_id);
                        $pivotCnt = sizeof($pivot_result);
                        for($j=0;$j<$pivotCnt;$j++){
                                $pivot_display_id = $pivot_result[$j]['pivot_display_id'];
                                if(!empty($pivot_display_id)){
                                        $pivot_display_type_result = $pivot->arrPivotDisplayDetails($pivot_display_id,"1");
                                        $pivot_display_type = $pivot_display_type_result[0]['pivot_display_name'];
                                }else{
                                        $pivot_display_type = "checkbox";
                                }
                                $pivot_group = $pivot_result[$j]['pivot_group'];
                                $main_pivot_group = $sub_group_name ;

                                $status = $pivot_result[$j]['status'];
				$categoryid = $pivot_result[$j]['category_id'];
                                $feature_id = $pivot_result[$j]['feature_id'];

                                if(!empty($feature_id)){
                                        $feature_result = $feature->arrGetFeatureDetails($feature_id,$categoryid,"","","1");
                                        $feature_name = $feature_result[0]['feature_name'];
                                        $feature_img_path = $feature_result[0]['feature_img_path'];
                                }

                                if(in_array($feature_id,$selectedfeatureArr)){
                                        $pivot_result[$j]['selected_feature_id'] = $feature_id;
                                        $selecteditemArr[$selectedIndex]['selected_id'] = $feature_id;
                                        $selecteditemArr[$selectedIndex]['selected_type'] = 'checkbox_feature_id_'.$feature_id;
                                        $selecteditemArr[$selectedIndex]['selected_name'] = $feature_name;
                                        $selectedIndex++;
                                        $plusminusimgstatus++;
                                }

                                $pivot_result[$j]['feature_name'] = $feature_name;
                                $pivot_result[$j]['feature_img_path'] = !empty($feature_img_path) ? IMAGE_URL.$feature_img_path : '';
                                $pivot_result[$j]['pivot_display_type'] = $pivot_display_type;

                                $pivotresult[$sub_group_id][$pivot_group][] = $pivot_result[$j];
                                $result[$i]['plus_minus_img_status'] = $plusminusimgstatus;
                                foreach($result[$i] as $k=> $v){
                                        $pivotresult[$sub_group_id][$k] = $v;
                                }
                        }
                }

        }
        $groupnodexml .= "<PIVOT_MASTER>";
	if($pivotresult){
                foreach($pivotresult as $maingroupkey => $maingroupval){
                        if(is_array($maingroupval)){

                                $groupnodexml .= "<PIVOT_MASTER_DATA>";
                                foreach($maingroupval as $subgroupkey=>$subgroupval){

                                        if(is_array($subgroupval)){
                                                $groupnodexml .= "<SUB_PIVOT_MASTER>";
                                                 foreach($subgroupval as $key => $featuredata){
                                                        if(is_array($featuredata)){
                                                                $groupnodexml .= "<SUB_PIVOT_MASTER_DATA>";
                                                                $featuredata = array_change_key_case($featuredata,CASE_UPPER);
                                                                foreach($featuredata as $featurekey => $featureval){
                                                                        $groupnodexml .= "<$featurekey><![CDATA[$featureval]]></$featurekey>";
                                                                }
                                                                $groupnodexml .= "</SUB_PIVOT_MASTER_DATA>";
                                                        }else{
                                                                $key = strtoupper($key);
                                                                $groupnodexml .= "<$key><![CDATA[$featuredata]]></$key>";
                                                        }
                                                }
                                                $groupnodexml .= "</SUB_PIVOT_MASTER>";
                                        }else{
                                                $subgroupkey = strtoupper($subgroupkey);
                                                $groupnodexml .= "<$subgroupkey><![CDATA[$subgroupval]]></$subgroupkey>";
                                        }


                                }
                                $groupnodexml .= "</PIVOT_MASTER_DATA>";
				}
                }
        }
        $groupnodexml .= "</PIVOT_MASTER>";
	
	$iRelUploadCnt= $iRelUploadCnt ? $iRelUploadCnt :1;
	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
	$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
	$strXML .= "<WALLCNT><![CDATA[$iRelUploadCnt]]></WALLCNT>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $groupnodexml;
	$strXML .= $xmlArt;
	$strXML .= "</XML>";
//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/upcoming_product_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
