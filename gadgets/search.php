<?php
require_once('./include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'pivot.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'PageNavigator.php');
require_once(CLASSPATH.'Utility.php');
require_once(CLASSPATH.'pager.class.php');
//require_once(CLASSPATH.'user_review.class.php');


$dbconn = new DbConn;
$brand = new BrandManagement;
$category = new CategoryManagement;
$pivot = new PivotManagement;
$feature = new FeatureManagement;
$product = new ProductManagement;
//$userreview = new USERREVIEW;


    //print "<pre>"; print_r($_POST);
    
	$category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : SITE_CATEGORY_ID;
	$cat_path = $_REQUEST['cat_path'] ? $_REQUEST['cat_path'] : SITE_CATEGORY_PATH;
	$variant_id = "1";
	$request_uri = $_SERVER['REQUEST_URI'];
	$pageurl = $_SERVER['SCRIPT_URI'];
	$queryStr = $_SERVER['QUERY_STRING'];	
	/*
	$category_search =  explode("/", $request_uri);
	$category_path  = $category_search[1];
	if(!empty($category_path)){
        $category_result = $category->arrGetCategoryDetails($categoryid);
        $category_id = $category_result[0]['category_id'];
        $category_name = $category_result[0]['category_name'];
        $cat_path = $category_result[0]['seo_path'];
	}*/
	$pos = strpos($request_uri,'?');
	if($pos > 0){
		$request_uri = substr($request_uri,0,$pos);
	}
	$pgpos = strpos($request_uri,'page');
	if($pgpos > 0){ 
       $curpagenums = explode("page/",$request_uri);
       $curpagenum = $curpagenums[1];
       $currpageurl  = $curpagenums[0];
    }else{
        $currpageurl  =$request_uri."/";
    }

    $selectedbrandArr = Array(); $featurenamesArr= Array();
    $sortproductBY = $_REQUEST['sortproduct'] ? $_REQUEST['sortproduct'] : "latest";
    $sortproductxml = "<SELECTED_SORT_PRODUCT_BY><![CDATA[$sortproductBY]]></SELECTED_SORT_PRODUCT_BY>";
    switch($sortproductBY){
        case 'priceasc':
            $orderby=" PRICE_VARIANT_VALUES.variant_value asc "; 
        break;
        case 'pricedesc':
            $orderby = " PRICE_VARIANT_VALUES.variant_value desc";
        break;
        case 'bgrrating':
            $orderby = " PRODUCT_MASTER.bgrrating asc";           
        break;
        default:
            $orderby = " PRODUCT_MASTER.create_date desc";
        break;
    }
        $price_exp = "/price\-([^\/]+)/";
        if(preg_match_all($price_exp,$request_uri,$matches,PREG_SET_ORDER)){
            $priceArr = explode("-",$matches[0][1]);
            $mn_price = $priceArr[0];
            $mx_price = $priceArr[1];
            $price_values = "price-".$mn_price."-".$mx_price;
            array_push($url_arr,$price_values);
            $startprice = $mn_price;
            $endprice = $mx_price;
        }else{
            $mn_price = 99;
            $mx_price = 99999;
        }

	if(empty($selectedbrandArr)){
		//for brand.
		$brand_exp = "/brand\-([^\/]+)/";
		if(preg_match_all($brand_exp,$request_uri,$matches,PREG_SET_ORDER)){
       		$result = $brand->arrGetBrandDetails("",$category_id,"1","","");
       		//print "<pre>"; print_r($result);
          	$brand_cnt = sizeof($result);
			$brandnamesArr = explode("_",$matches[0][1]);
        	foreach($brandnamesArr as $k=>$brand_name){
          		for($i=0;$i<$brand_cnt;$i++){
            			$br_name = "";
            			$br_name = $result[$i]['brand_name'];
            			if(strtolower(constructUrl($br_name,'0')) == strtolower(constructUrl($brand_name,'0'))) {
              				$brand_id = $result[$i]['brand_id'];
              				if(!empty($brand_id)){
	      						$selectedbrandArr[] = $brand_id;
	      						$selectedbrandNames['Mobile Brand'][] = $brand_name;
              				}
          			}
          	}
      	}
      	}
    }
    //print"<pre>";print_r($selectedbrandNames);print"</pre>"; die();
    unset($result);
	$result = $feature->arrGetPivotFeatureDetails("",$category_id);
	
	$result_cnt = sizeof($result);
    $phonetype_exp = "/phone-type\-([^\/]+)/";
    if(preg_match_all($phonetype_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Phone Type'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $availability_exp = "/availability\-([^\/]+)/";
	if(preg_match_all($availability_exp,$request_uri,$matches,PREG_SET_ORDER)){
	        //for segments pivot.
			$featurenamesArr = explode("_",$matches[0][1]);
	        foreach($featurenamesArr as $k=>$feature_name){
	                for($i=0;$i<$result_cnt;$i++){
	                     $fr_name = $result[$i]['feature_name'];
	                     $feature_name = trim($feature_name);
	                     if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
	                        $feature_id = $result[$i]['feature_id'];
	                        if(!empty($feature_id)){
	                             $selectedfeatureArr[] = $result[$i]['feature_id'];
	                             $selectedFeaturesNames['Availability'][] = $fr_name;
	                        }
	                     }
	                }
	        }
	        unset($featurenamesArr);
	}
	$form_factor_exp = "/form-factor\-([^\/]+)/";
    if(preg_match_all($form_factor_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Form Factor'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $input_mechanism_exp = "/input-mechanism\-([^\/]+)/";
    if(preg_match_all($input_mechanism_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Input Mechanism'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $ram_exp = "/ram\-([^\/]+)/";
    if(preg_match_all($ram_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['RAM'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $features_exp = "/features\-([^\/]+)/";
    if(preg_match_all($features_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Features'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $network_type_exp = "/network-type\-([^\/]+)/";
    if(preg_match_all($network_type_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Network Type'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $no_of_sim_exp = "/sim\-([^\/]+)/";
    if(preg_match_all($no_of_sim_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['No Of SIM'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $network_exp = "/networks\-([^\/]+)/";
    if(preg_match_all($network_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Networks'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    
    $primary_camera_exp = "/camera\-([^\/]+)/";
    if(preg_match_all($primary_camera_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Primary Camera'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $processor_exp = "/processor\-([^\/]+)/";
    if(preg_match_all($processor_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Processor'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $screen_size_exp = "/screen-size\-([^\/]+)/";
    if(preg_match_all($screen_size_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Screen Size'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $announced_exp = "/announced\-([^\/]+)/";
    if(preg_match_all($announced_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Announced'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }
    $os_exp = "/os\-([^\/]+)/";
    if(preg_match_all($os_exp,$request_uri,$matches,PREG_SET_ORDER)){
            //for segments pivot.
    		$featurenamesArr = explode("_",$matches[0][1]);
            foreach($featurenamesArr as $k=>$feature_name){
                    for($i=0;$i<$result_cnt;$i++){
                         $fr_name = $result[$i]['feature_name'];
                         $feature_name = trim($feature_name);
                         if(strtolower(constructUrl($fr_name,'0')) == strtolower(constructUrl($feature_name,'0'))){
                            $feature_id = $result[$i]['feature_id'];
                            if(!empty($feature_id)){
                                 $selectedfeatureArr[] = $result[$i]['feature_id'];
                                 $selectedFeaturesNames['Operating System'][] = $fr_name;
                            }
                         }
                    }
            }
            unset($featurenamesArr);
    }

   // print_r($selectedbrandNames); die();
    if(is_array($selectedbrandNames)){
        $selectedbrandNamesXml .="<SELECTEDBRANDS>";
        foreach($selectedbrandNames as $fkey=>$fValue){
                $selectedbrandNamesXml .="<SELECTEDBRANDVALUE>";
                $selectedbrandNamesXml .="<LABEL>$fkey</LABEL>";
                $cnt = sizeof($fValue);
                for($i=0;$i<$cnt;$i++){
                    $selectedbrandNamesXml .="<SELECTEDBRANDVALUEDATA>";
                    $selectedbrandNamesXml .="<LABELVALUE>".$fValue[$i]."</LABELVALUE>";
                    $selectedbrandNamesXml .="<JSLABELVALUE>".strtolower(constructUrl($fValue[$i],'0'))."</JSLABELVALUE>";   
                    $selectedbrandNamesXml .="<LABELNAME>".$fkey."</LABELNAME>"; 
                    $selectedbrandNamesXml .="</SELECTEDBRANDVALUEDATA>"; 
                }
                $selectedbrandNamesXml .="</SELECTEDBRANDVALUE>";
        }
        $selectedbrandNamesXml .="</SELECTEDBRANDS>";
    }

    if(is_array($selectedFeaturesNames)){
    	$selectedFeaturesNamesXml .="<SELECTEDFEATURES>";
    	foreach($selectedFeaturesNames as $fkey=>$fValue){
    			$selectedFeaturesNamesXml .="<SELECTEDFEATURESVALUE>";
    			$selectedFeaturesNamesXml .="<LABEL>$fkey</LABEL>";
    			$cnt = sizeof($fValue);
    			for($i=0;$i<$cnt;$i++){
    				$selectedFeaturesNamesXml .="<SELECTEDFEATURESVALUEDATA>";
    				$selectedFeaturesNamesXml .="<LABELVALUE>".$fValue[$i]."</LABELVALUE>";
    				$selectedFeaturesNamesXml .="<JSLABELVALUE>".strtolower(constructUrl($fValue[$i],'0'))."</JSLABELVALUE>";	
    				$selectedFeaturesNamesXml .="<LABELNAME>".$fkey."</LABELNAME>";	
    				$selectedFeaturesNamesXml .="</SELECTEDFEATURESVALUEDATA>";
    			}
    			$selectedFeaturesNamesXml .="</SELECTEDFEATURESVALUE>";
    	}
    	$selectedFeaturesNamesXml .="</SELECTEDFEATURES>";
    }

//removeFeatureChecked('smart-phone','Phone Type');

	if(!empty($category_id)){
		$result = $brand->arrGetBrandDetails("",$category_id,1);
	}
	foreach($result as $bkry=>$bValue){
			if(in_array($bValue['brand_id'],$top_brand_arr)){
				$set_key = array_search($bValue['brand_id'], $top_brand_arr);
				$bBrandArr1[$set_key] = $bValue;
			}else{
				$bBrandArr2[] = $bValue;
			}
		}
		ksort($bBrandArr1);
		unset($result);
		if(is_array($bBrandArr1) && is_array($bBrandArr2)){
			$result = array_merge($bBrandArr1,$bBrandArr2);
		}
	$cnt = sizeof($result);
	$xml .= "<SELECTED_BRAND_MASTER>";
	$xml .= "</SELECTED_BRAND_MASTER>";
	$xml .= "<BRAND_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	$selectedIndex = "0";
	$isBrandSelected = "0"; //used toggle all brands checkbox.
	for($i=0;$i<$cnt;$i++){
		$brand_id = $result[$i]['brand_id'];
		$brand_name = $result[$i]['brand_name'];
		$short_desc = $result[$i]['short_desc'];
    if(strlen($short_desc)>70){ $short_desc = getCompactString($short_desc, 70).' ...'; }
		if(!empty($short_desc)){
			$result[$i]['short_desc'] = html_entity_decode($short_desc,ENT_QUOTES,'UTF-8');
		}
		if(in_array($brand_id,$selectedbrandArr)){
			$result[$i]['selected_brand_id'] = $brand_id;
			$result[$i]['selected_brand_name'] = constructUrl($brand_name,'0');
			$result[$i]['selected_brand_name_display'] = $brand_name;
			$selecteditemArr[$selectedIndex]['selected_id'] = $brand_id;
			$selecteditemArr[$selectedIndex]['is_brand_select'] = "1";
			$selecteditemArr[$selectedIndex]['selected_type'] = 'checkbox_brand_id_'.$brand_id;
			$selecteditemArr[$selectedIndex]['selected_name'] = constructUrl($result[$i]['brand_name'],'0');
			$selecteditemArr[$selectedIndex]['selected_name_display'] =$result[$i]['brand_name'];
			$selectedIndex++;
			$isBrandSelected++;
			
		}

		if(in_array($result[$i]['brand_id'],$top_brand_arr)){
			$result[$i]['top_brand'] = 1;
		}else{
			$result[$i]['top_brand'] = 0;
		}
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		$category_seo_path = $category_result[0]['seo_path'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
		$result[$i]['category_seo_path'] = html_entity_decode($category_seo_path,ENT_QUOTES,'UTF-8');
		$result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$brand_name = $result[$i]['brand_name'];
		$brand_name = html_entity_decode($brand_name,ENT_QUOTES,'UTF-8');
		$result[$i]['js_brand_name'] = strtolower(constructUrl($brand_name,'0'));
		$result[$i]['brand_name_display'] = $brand_name;
		$result[$i]['brand_name'] = constructUrl($brand_name,'0');
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);

		$xml .= "<BRAND_MASTER_DATA>";
			unset($popular_brand);
			
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</BRAND_MASTER_DATA>";
	}
	$xml .= "</BRAND_MASTER>";



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
		if(!empty($category_id)){
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
				$pivot_desc = $pivot_result[$j]['pivot_desc'];
				if(!empty($pivot_desc)){
					$pivot_result[$j]['pivot_desc'] = html_entity_decode($pivot_desc,ENT_QUOTES,'UTF-8');
				}
				$categoryid = $pivot_result[$j]['category_id'];
				$feature_id = $pivot_result[$j]['feature_id'];
				if(!empty($feature_id)){
					$feature_result = $feature->arrGetFeatureDetails($feature_id,$categoryid,"","","1");
					$feature_name = $feature_result[0]['feature_name'];
					$feature_img_path = $feature_result[0]['feature_img_path'];
					$feature_description = $feature_result[0]['feature_description'];
				}
				if(in_array($feature_id,$selectedfeatureArr)){
					$pivot_result[$j]['selected_feature_id'] = $feature_id;
					$selecteditemArr[$selectedIndex]['selected_id'] = $feature_id;
					$selecteditemArr[$selectedIndex]['is_feature_select'] = "1";
					$selecteditemArr[$selectedIndex]['selected_type'] = 'checkbox_feature_id_'.$feature_id;
					$selecteditemArr[$selectedIndex]['selected_name'] = constructUrl($feature_name,'0');
					$selecteditemArr[$selectedIndex]['selected_feature_group'] = $sub_group_name;
					$selecteditemArr[$selectedIndex]['selected_name_display'] = $feature_name;
					$selectedIndex++;
					$plusminusimgstatus++;
				}
				$pivot_result[$j]['feature_img_path'] = $feature_img_path;
				$pivot_result[$j]['feature_display_name'] = $feature_name;
				$new_feature_name = constructUrl($feature_name,'0');
				$pivot_result[$j]['feature_name'] = $new_feature_name;
				$pivot_result[$j]['js_feature_name'] = strtolower($new_feature_name);
				$pivot_result[$j]['feature_description'] = $feature_description;
				$pivot_result[$j]['pivot_display_type'] = $pivot_display_type;
				$pivot_result[$j]['sub_group_id'] = $sub_group_id;
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
							 $feature_id = $featuredata['feature_id'];
							 
							if(is_array($featuredata)){
								$groupnodexml .= "<SUB_PIVOT_MASTER_DATA>";
								$groupnodexml .= $popularfeaturexml;
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
	


    
	//$startlimit=0 ; $endlimit=100;
	
    if(sizeof($selectedbrandArr) <= 0 && sizeof($selectedfeatureArr) <= 0){	
		$count_result = $product->arrGetProductByPriceAscCount("",$category_id,"",'1',$startprice,$endprice,$variant_id,'','',"","1");
		//$result = $product->arrGetProductByPriceAsc("",$category_id,"",'1',$startprice,$endprice,"1",$startlimit,$endlimit,'',"1","0","",$orderby);
	}else{
		$count_result = $product->searchProductCount($category_id,$selectedbrandArr,"",$selectedfeatureArr,"1",$startprice,$endprice,$variant_id,"","","","1");
		//$result = $product->searchProduct($category_id,$selectedbrandArr,"",$selectedfeatureArr,"1",$startprice,$endprice,$variant_id,$startlimit,$endlimit,$orderby,"","1");
	}
	 
	$totalcount = $count_result[0]['cnt'] ?  $count_result[0]['cnt'] : 0;
    // paging
    
    $endlimit = empty($curpagenum) ? FRONT_PERPAGE : 10;
    $oPager = new Pager();
    $startlimit = $oPager->findStart($limit);
    $pages = ceil($totalcount/FRONT_PERPAGE);
    
    $siteUrl = SEO_WEB_URL.$currpageurl;
    if(empty($curpagenum)){
        $startlimit = 0; 
        $curpagenum =1;
    }else{
        $startlimit = ($curpagenum-1) * $endlimit;
    }
    
    if(!empty($curpagenum)){
        $sPagingXml .= $oPager->pageNumNextPrevUrl($curpagenum, $pages, $siteUrl, $link_type);
        
    }
    if($curpagenum > 1){ 
       $showingstart = ($endlimit*($curpagenum-1)) + 1;
       $showingend  = ($endlimit * $curpagenum);
    }else{ 
        $showingstart = $curpagenum;
        $showingend  = $endlimit;
    }
  
    //echo $startlimit."=============".$endlimit; die();
    if(sizeof($selectedbrandArr) <= 0 && sizeof($selectedfeatureArr) <= 0){ 
        //$count_result = $product->arrGetProductByPriceAscCount("",$category_id,"",'1',$startprice,$endprice,$variant_id,'','',"","1");
        $result = $product->arrGetProductByPriceAsc("",$category_id,"",'1',$startprice,$endprice,"1",$startlimit,$endlimit,'',"1","0","",$orderby);
    }else{
        //$count_result = $product->searchProductCount($category_id,$selectedbrandArr,"",$selectedfeatureArr,"1",$startprice,$endprice,$variant_id,"","","","1");
        $result = $product->searchProduct($category_id,$selectedbrandArr,"",$selectedfeatureArr,"1",$startprice,$endprice,$variant_id,$startlimit,$endlimit,$orderby,"","1");
    }
    
 	$cnt = sizeof($result);
	//print "<pre>"; print_r($result); die();
	$productxml = "<PRODUCT_MASTER>";
	$productxml .= "<TOTAL_SEARCH_ITEM_FOUND><![CDATA[".$totalcount."]]></TOTAL_SEARCH_ITEM_FOUND>";
	$productxml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	$pcount=0;
	for($i=0;$i<$cnt;$i++){
		$link_model_name="";$seo_model_url="";unset($modelnameSeoArr);
		unset($modelnameArr);unset($variantnameSeoArr);
		$product_id = $result[$i]['product_id'];
		$brand_id = $result[$i]['brand_id'];
		$category_id = $result[$i]['category_id'];
		$product_name = trim($result[$i]['product_name']);
		$variant = trim($result[$i]['variant']);
		//$short_desc = trim($result[$i]['short_desc']);
		$product_discontinue_flag = $result[$i]['discontinue_flag'];
		$product_discontinue_date = $result[$i]['discontinue_date'];
		$result[$i]['product_discontinue_flag'] = $product_discontinue_flag;
		$result[$i]['product_discontinue_date'] = $product_discontinue_date;
		$three_months_plus_discontinue_date = 0;
		$prev_3_month = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
		if(($product_discontinue_flag == "0") && (strtotime($product_discontinue_date) < strtotime($prev_3_month)) && $product_discontinue_date!='0000-00-00 00:00:00'){
			$three_months_plus_discontinue_date = 1;
		}
		if(($product_discontinue_flag == "0") && ((strtotime($product_discontinue_date) > strtotime($prev_3_month)) || $product_discontinue_date=='0000-00-00 00:00:00') ){
			$three_months_plus_discontinue_date = 2;
		}
		$result[$i]['three_months_plus_discontinue_date'] = $three_months_plus_discontinue_date;
		$brand_result = $brand->arrGetBrandDetails($brand_id,$category_id);
		$brand_name = trim($brand_result[0]['brand_name']);
		unset($product_discontinue_date); unset($product_discontinue_flag);
        if(!empty($category_id)){
            $category_result = $category->arrGetCategoryDetails($category_id);
        }
        $category_seo_path = $category_result[0]['seo_path'];
		//set seo url for product variant page.
		$variantnameSeoArr[] = SEO_WEB_URL;	
        $variantnameSeoArr[] = $category_seo_path;	
		$brand_name = $brand_name;
		$product_name = $product_name;
		$variant = $variant;
		$variantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
		$variantnameSeoArr[] = seo_title_replace(constructUrl($product_name));
		$variantnameSeoArr[] = seo_title_replace(constructUrl($variant));


        
        
		unset($varianUrlYear);
		$varianUrlYear = buildYear($result[$i]['arrival_date'],$result[$i]['discontinue_date']);
		if(!empty($varianUrlYear)){
			$variantnameSeoArr[] = $varianUrlYear;
		}
		if(!empty($brand_name)){					
			$modelnameArr[] = $brand_name;
		}
		if(!empty($product_name)){					
			$modelnameArr[] = $product_name;
		}
        if(!empty($brand_name)){        
		      $comparenames[]= constructUrl($brand_name);
        }
        if(!empty($product_name)){        
              $comparenames[]= constructUrl($product_name);
        }
        if(!empty($variant)){        
              $comparenames[]= constructUrl($variant);
        }
	    if(!empty($varianUrlYear)){
            $comparenames[]= $varianUrlYear;
		}
        $comparename = constructUrl(implode("-",$comparenames));
        unset($comparenames);
        $result[$i]['comparename'] = $comparename;
		if(empty($brandCheck)){
			//get model name and seo url.
			$modelnameSeoArr[] = SEO_WEB_URL;
			$modelnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
			$modelnameSeoArr[] = seo_title_replace(constructUrl($product_name));			
			$model_result=$product->arrGetProductNameInfo("",$category_id,"",$product_name);
			$model_id = $model_result[0]['product_name_id'];
			$result[$i]['product_name_id'] = $model_id;
			$link_model_name = implode(" ",$modelnameArr);
			$seo_model_url =  implode("/",$modelnameSeoArr);	
			$brandCheck = 1;
		}
		if(!empty($variant)){					
			$modelnameArr[] = $variant;
		}
		//echo CENTRAL_IMAGE_URL; die();
		$product_video_path = $result[$i]['video_path'];
		if(!empty($product_video_path)){
			$result[$i]['video_path'] = CENTRAL_IMAGE_URL.str_replace(array(CENTRAL_IMAGE_URL),"",$product_video_path);
		}
		//$image_path = $result[$i]['model_image_path'];
		$image_path = $result[$i]['image_path'];
		if(!empty($image_path)){
			$image_path = resizeImagePath($image_path,"145X193",$aModuleImageResize);
			$result[$i]['image_path'] = CENTRAL_IMAGE_URL.str_replace(array(CENTRAL_IMAGE_URL),"",$image_path);
		}
		$result[$i]['EXSHOWROOMPRICE_ORIGIONAL'] = $result[$i]['variant_value'];
		$result[$i]['EXSHOWROOMPRICE'] = $result[$i]['variant_value'] ? priceFormat($result[$i]['variant_value']) : ''; 
		//echo $link_model_name."====".$result[$i]['EXSHOWROOMPRICE']."<br>";
		$priceValueArr[] = $result[$i]['variant_value'];	
		$result[$i]['DISPLAY_PRODUCT_NAME'] = implode(" ",$modelnameArr);
		$result[$i]['SEO_URL'] = implode("/",$variantnameSeoArr);			
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		//print_r($result[$i]);
		$productxml .= "<PRODUCT_MASTER_DATA>";
		$rootxml = "";
		foreach($result[$i] as $k=>$v){
			if(is_array($v) && $k=="SHORT_DESC"){
				$productxml .= "<PRODUCT_FEATURE_MASTER_DATA>";
				foreach($v as $fk=>$fv){
					$productxml .= "<PRODUCT_FEATURE_SUMMERY_DATA><![CDATA[$fv]]></PRODUCT_FEATURE_SUMMERY_DATA>";
				}
				$productxml .= "</PRODUCT_FEATURE_MASTER_DATA>";
			}
			$productxml .= "<$k><![CDATA[$v]]></$k>";
		}
		$productxml .= "</PRODUCT_MASTER_DATA>";
	}
	$productxml .= "</PRODUCT_MASTER>";
	
    if(sizeof($selectedbrandArr) > 0){
        
        foreach($selectedbrandArr as $brand_id){
            if(!empty($brand_id)){
                $result = $brand->arrGetBrandDetails($brand_id,$category_id);
                $bname = $result[0]['brand_name'];
                $bname = constructUrl($bname,'0');              
                $seoUrlArr[] = $bname;
                $seoBrandArr[] = rawurlencode($bname);
            }
            unset($result);

        }
    }

    if(sizeof($selectedfeatureArr) > 0){
        foreach($selectedfeatureArr as $feature_id){
            if(!empty($feature_id)){
                $result = $feature->arrGetFeatureDetails($feature_id,$category_id);
                //print_r($result);
                $fname = $result[0]['feature_name'];
                $fname = constructUrl($fname,'0');
                $seoUrlArr[] = $fname;
                if($result[0]['feature_group']=='18'){
                    $seoFeatureArrBdy[] = rawurlencode($fname);
                }else{
                    $seoFeatureArr[] = rawurlencode($fname);
                }
                unset($result);
            }
        }
    }
    $seopriceArr[] = $mn_price;
    $seopriceArr[] = $mx_price;

    $seoDesctitleArr[] = "Compare";
    if(sizeof($seoBrandArr) > 0 && sizeof($seoBrandArr) <= 2){
        $seotitleArr[] = implode(",",$seoBrandArr);
        $seoDesctitleArr[] = implode(",",$seoBrandArr);
    }
    if(sizeof($seoFeatureArrBdy) > 0 && sizeof($seoFeatureArrBdy) <= 2){
        $seotitleArr[] = implode(",",$seoFeatureArrBdy);
        $seoDesctitleArr[] = implode(",",$seoFeatureArrBdy);
    }
    if(sizeof($seoFeatureArr) > 0 && sizeof($seoFeatureArr) <= 2){
        $seotitleArr[] = 'Mobiles with '.implode(",",$seoFeatureArr);
        $seoDesctitleArr[] = 'Mobiles with '.implode(",",$seoFeatureArr);
    }else if(sizeof($seoFeatureArr) > 0 && sizeof($seoFeatureArr) > 2){
        $seotitleArr[] = 'Mobiles';
        $seoDesctitleArr[] = 'Mobiles';
    }else if(sizeof($seoFeatureArr) == 0 && sizeof($seoBrandArr) >0 && sizeof($seoFeatureArrBdy) > 0){
        $seotitleArr[] = 'Mobiles';
        $seoDesctitleArr[] = 'Mobiles';
    }
    else if(sizeof($seoFeatureArr) == 0 && sizeof($seoBrandArr) >0 && sizeof($seoFeatureArrBdy) == 0){
        $seotitleArr[] = 'Mobiles';
        $seoDesctitleArr[] = 'Mobiles';
    }
        $seotitle = implode(" ",$seotitleArr);
    unset($seotitleArr);
    if(!empty($seotitle)){
        $seotitleArr[] = "Search & Compare ".$seotitle;
        
    }
    if(sizeof($seotitleArr) > 0){       
        $seotitleArr[] = "Price Rs.".implode(" - ",$seopriceArr);
        $seotitleArr[] = SEO_DOMAIN;
    }else{
        $seotitleArr[] = "Car Finder - Price Rs.".implode(" - ",$seopriceArr);
        $seotitleArr[] = SEO_DOMAIN;
    }
    
    if(sizeof($seotitleArr) > 0){
        $seo_title = implode(" | ",$seotitleArr);
    }else{
        $seo_title = "Phone Finder - On Gadgets India | Search Mobiles, New mobile by Brands, Price, Phone Type, Mobile Features ".SEO_DOMAIN;
        
    }
    array_pop($seotitleArr);
    $sub_title = rawurldecode(implode(" ",str_replace(",",", ",$seotitleArr)));


$config_details = get_config_details();
$login_details = getCookie();
$strXML = "<XML>";
$strXML .= getComponents('SEARCH', getComponentParams()); // components xml
$strXML .="<PAGING><![CDATA[$sPagingXml]]></PAGING>";
$strXML .="<SHOWSTART><![CDATA[$showingstart]]></SHOWSTART>";
$strXML .="<SHOWEND><![CDATA[$showingend]]></SHOWEND>";
$strXML .= $selectedbrandNamesXml;
$strXML .= $selectedFeaturesNamesXml;
$strXML .= $xml;
$strXML .= $sortproductxml;
$strXML .= $productxml;
$strXML .= $groupnodexml;
$strXML .= $login_details;
$strXML .= $config_details;
$strXML .= "<MIN_PRICE_VALUE><![CDATA[$startprice]]></MIN_PRICE_VALUE>";
$strXML .= "<MAX_PRICE_VALUE><![CDATA[$endprice]]></MAX_PRICE_VALUE>";
$strXML .= "<MAX_PRICE><![CDATA[$mx_price]]></MAX_PRICE>";
$strXML .= "<MAX_PRICE_UNIT><![CDATA[$mx_price_unit]]></MAX_PRICE_UNIT>";
$strXML .= "<MIN_PRICE><![CDATA[$mn_price]]></MIN_PRICE>";
$strXML .= "<MIN_PRICE_UNIT><![CDATA[$mn_price_unit]]></MIN_PRICE_UNIT>";
$strXML .= "<SEO_CARFINDER_COMPARE_URL><![CDATA[".WEB_URL.SEO_COMPARE_URL."]]></SEO_CARFINDER_COMPARE_URL>";
$strXML .= "<SEO_CAR_FINDER><![CDATA[".SEO_CAR_FINDER."]]></SEO_CAR_FINDER>";
$strXML .= "<SEO_JS><![CDATA[$seo_js]]></SEO_JS>";
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<SUB_TITLE><![CDATA[$sub_title]]></SUB_TITLE>";
$strXML .= "<BREAD_CRUMB><![CDATA[$new_breadcrumb]]></BREAD_CRUMB>";
$strXML .= "<SEO_DESC><![CDATA[$seo_desc]]></SEO_DESC>";
$strXML .= "<SEO_TAGS><![CDATA[$seo_tags]]></SEO_TAGS>";
$strXML .= "<STARTLIMIT><![CDATA[".$offset."]]></STARTLIMIT>";
$strXML .= "<PAGE_OFFSET><![CDATA[".OFFSET."]]></PAGE_OFFSET>";
$strXML .= "<CNT><![CDATA[$numpages]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= "<SELECTED_CATEGORY_PATH><![CDATA[$category_seo_path]]></SELECTED_CATEGORY_PATH>";
$strXML .= "<SELECTED_BRAND_ID><![CDATA[$isBrandSelected]]></SELECTED_BRAND_ID>";
$strXML .= "<SELECTEDTABID><![CDATA[$tab_id]]></SELECTEDTABID>";
$strXML .= "<SEO_PRICE_STR><![CDATA[".implode("-",array($startprice,$endprice))."]]></SEO_PRICE_STR>";
$strXML .= "<PAGE_NAME><![CDATA[".$_SERVER['SCRIPT_URI']."]]></PAGE_NAME>";
$strXML .= "<POPAD><![CDATA[$popad]]></POPAD>";
$strXML .= "<CARPRICE><![CDATA[$lowpricevalue]]></CARPRICE>";
$strXML .= "<PERPAGE>".PERPAGE."</PERPAGE>";
$strXML .= "<CAT_PATH><![CDATA[".$cat_path."]]></CAT_PATH>";

if(empty($selectedbrandArr)){ $strXML .= "<BRANDAD><![CDATA[1]]></BRANDAD>";}
if(is_array($selectedbrandArr)){
if(in_array('6',$selectedbrandArr)){ $strXML .= "<BRANDAD><![CDATA[1]]></BRANDAD>"; }
}
$strXML .= "</XML>";
#$_REQUEST['debug'] = 1;
//header('Content-type: text/xml');echo $strXML;exit;
if($_REQUEST['debug']==1){ header('Content-type: text/xml');echo $strXML;exit;}
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;    
//$xslt->registerPHPFunctions();
$xsl = DOMDocument::load('xsl/search.xsl');
$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
