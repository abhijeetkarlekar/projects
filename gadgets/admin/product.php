<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'product.class.php');
	require_once(CLASSPATH.'price.class.php');
	require_once(CLASSPATH."feature.class.php");
	$dbconn = new DbConn;
	$product = new ProductManagement;
	$oPrice = new price;
	$oFeature  = new FeatureManagement;
//print_R($_REQUEST); //die();
	$category_id = $_REQUEST['selected_category_id'] ? $_REQUEST['selected_category_id'] : $_REQUEST['catid'];

	$selected_brand_id = $_REQUEST['select_brand'] ;
	$selected_model_id = $_REQUEST['Model'] ;
	$selected_variant_id = $_REQUEST['Variant'] ;
	$start_date = $_REQUEST['start_date'] ;
	$announced_date = $_REQUEST['announced_date'] ;
	$featurecnt = $_REQUEST['featureboxcnt'];
	$product_name = trim($_REQUEST['product_name']);
	$brand_id = $_REQUEST['select_brand_id'];
	$varient = trim($_REQUEST['varient']);
	$product_desc = translatechars($_REQUEST['product_description']);
	$ex_showroom_mrp = $_REQUEST['product_mrp_ex_showroom'];
	$onroad_mrp = $_REQUEST['product_mrp_on_road'];
	$city_id = $_REQUEST['city_id'];
	$state_id = $_REQUEST['state_id'];
	$is_upcomimg = $_REQUEST['is_upcomimg'];
	$is_latest = $_REQUEST['is_latest'];
	$status = $_REQUEST['product_status'];
	$actiontype = $_REQUEST['actiontype'];
	$product_id = $_REQUEST['product_id'];
	$price = $_REQUEST['price'];

	$fileinfo = $_FILES['img_upload_1'];
	$tmp_path = $fileinfo['tmp_name'];
	$filename = $fileinfo['name'];
	rename($tmp_path,UPLOAD_TMP_PATH.$filename);

	$request_param['category_id'] = $category_id;
	$request_param['brand_id'] = $brand_id;
	$request_param['product_name'] = $product_name;
	$request_param['variant'] = $varient;
	
	$seo_path = $_REQUEST['seo_path'];

	/*
	$request_param['product_mrp_ex_showroom'] = $ex_showroom_mrp;
	$request_param['product_mrp_on_road'] = $onroad_mrp;
	$request_param['is_upcoming'] = $is_upcomimg;
	$request_param['is_latest'] = $is_latest;
	$request_param['showroom_city_id'] = $city_id;
	$request_param['showroom_state_id'] = $state_id;
	*/
	$request_param['product_desc'] = $product_desc;
	$request_param['status'] = $status;
	$request_param['uid'] = $uid;
	//if(!empty($_REQUEST['media_id']))
	//{
		$request_param['media_id'] = $_REQUEST['media_id'];
		$request_param['video_path'] = $_REQUEST['img_upload_1'];
		$request_param['img_media_id'] = $_REQUEST['img_media_id'];
		$request_param['image_path'] = $_REQUEST['img_upload_thm'];
	//}
	if($actiontype == 'insert'){
		if(!empty($product_name) && !empty($brand_id)){
			$sVariant_color_id=$_POST['color_assignids'];
			$resultid = $product->arrGetProductNameInfo($product_name,$category_id,"","","");
			
			$product_name = $resultid[0]['product_info_name'];
			$request_param['product_name'] = $product_name;
			if(empty($seo_path) && empty($varient)){
				$seo_path = $resultid[0]['seo_path'];
			}
			$request_param['seo_path'] = $seo_path;

	/*		$request_param['arrival_date'] = $start_date;
			$request_param['announced_date'] = $announced_date;*/

			$arrival_date = date("Y-m-d", strtotime($start_date));
			$request_param['arrival_date'] = $arrival_date;
			$announced_date = date("Y-m-d", strtotime($announced_date));
			$request_param['announced_date'] = $announced_date;


			$end_date = $_REQUEST['end_date'];
			if(!empty($end_date)){
				$request_param['discontinue_date'] = $end_date;
			}else{
				$request_param['discontinue_date'] = "";
			}
			$discontinue_flag = $_REQUEST['discontinue_flag'];
			if($discontinue_flag=='on'){
				$request_param['discontinue_flag'] = 0;
			}else{
				$request_param['discontinue_flag'] = 1;
				$request_param['discontinue_date'] = "";
			}
			
			$product_id = $product->intInsertProduct($request_param);

	/*		if(!empty($sVariant_color_id)){
                            $arrVariant_color_id=explode(',', $sVariant_color_id);
                            foreach ($arrVariant_color_id as $cKey=>$cData){
                                if(!empty($cData)){
                                    $insert_param['variant_color_id']   = '';
                                    $insert_param['brand_id']           = $_POST['select_brand_id'];
                                    $insert_param['product_name_id']    = $_POST['product_name'];
                                    $insert_param['product_id']         = $product_id;
                                    $insert_param['color_id']           = $cData;
                                    $insert_param['category_id']        = $request_param['category_id'];
                                    $insert_param['status']             = '1';
                                    #print"<pre>";print_r($insert_param);
                                    $vId=$oFeature->intInsertUpdateVariantColors($insert_param);
                                }
                            }
                        }*/
			if(!empty($product_id) && $product_id != 'exists'){
				$groupmastercount=$_REQUEST['groupmastercnt'];
				for($i=1;$i<=$groupmastercount;$i++){
					$subgroupmastercount=$_REQUEST['subgroupmastercnt_'.$i];
					for($j=1;$j<=$subgroupmastercount;$j++){
						$subgroupmasterdatacount=$_REQUEST['subgroupmaster_data_cnt_'.$i.'_'.$j];
						for($k=1;$k<=$subgroupmasterdatacount;$k++){
							 $feature_value= $_REQUEST['feature_value_'.$i.'_'.$j.'_'.$k];
							 $feature_id= $_REQUEST['feature_id_'.$i.'_'.$j.'_'.$k];
							 //echo "TEST---".$feature_id."----".$feature_value."<br>";
							 if(!empty($feature_value)){
								//insert product feature.
								$feature_param['feature_value'] = $feature_value;
								$feature_param['feature_id'] = $feature_id;
								$feature_param['product_id'] = $product_id;
								$product_feature_id = $product->intInsertProductFeature($feature_param);
							}
						}
					}
				}
				//$insert_param['price'] = $price;
				$insert_param['brand_id']           = $_POST['select_brand_id'];
				$insert_param['product_id']         = $product_id;
				$insert_param['category_id']        = $request_param['category_id'];
				$insert_param['variant_value']      = $price;
				$insert_param['variant_id']             = '1';
				$insert_param['status']             = '1';
				//print"<pre>";print_r($insert_param); die();
				$priceId = $oPrice->intInsertVariantValueDetail($insert_param);

			}
		}

		$msg = ($product_id == 'exists') ? 'Product already exists.' : 'Product added successfully.';
	}elseif($actiontype == 'update'){

		 if(!empty($product_name) && !empty($brand_id)){


			$product_id=$_POST['product_id'];
            $category_id=$request_param['category_id'];

			$resultid = $product->arrGetProductNameInfo($product_name,$category_id,"","","");
			$product_name = $resultid[0]['product_info_name'];
			$request_param['product_name'] = $product_name;
			if(empty($seo_path) && empty($varient)){
				$seo_path = $resultid[0]['seo_path'];
			}
			$request_param['seo_path'] = $seo_path;

			$arrival_date = date("Y-m-d", strtotime($start_date));
			$request_param['arrival_date'] = $arrival_date;
			$announced_date = date("Y-m-d", strtotime($announced_date));
			$request_param['announced_date'] = $announced_date;
			$discontinue_flag = $_REQUEST['discontinue_flag'];
			$end_date = $_REQUEST['end_date'];
			if(!empty($end_date)){
				$request_param['discontinue_date'] = $end_date;
			}else{
				$request_param['discontinue_date'] = "";
			}
			if($discontinue_flag=='on'){
				$request_param['discontinue_flag'] = 0;
			}else{
				$request_param['discontinue_flag'] = 1;
				$request_param['discontinue_date'] = "";
			}
			//print_r($request_param); die();
			//echo "PRODUCT_ID---".$product_id;
			if(!empty($product_id)){
				$result = $product->boolUpdateProduct($product_id,$request_param);
			}
			//die();
			$groupmastercount=$_REQUEST['groupmastercnt'];
				for($i=1;$i<=$groupmastercount;$i++){
					$subgroupmastercount=$_REQUEST['subgroupmastercnt_'.$i];
					for($j=1;$j<=$subgroupmastercount;$j++){
						$subgroupmasterdatacount=$_REQUEST['subgroupmaster_data_cnt_'.$i.'_'.$j];
						for($k=1;$k<=$subgroupmasterdatacount;$k++){
							//echo "groupmastercount==".$groupmastercount."======="."subgroupmastercount==".$subgroupmastercount."+++++"."subgroupmasterdatacount==".$subgroupmasterdatacount."<br>";
							 $feature_value= $_REQUEST['feature_value_'.$i.'_'.$j.'_'.$k];
							 $feature_id= $_REQUEST['feature_id_'.$i.'_'.$j.'_'.$k];
							 //echo 'feature_value_'.$i.'_'.$j.'_'.$k."===TEST---".$feature_id."----".$feature_value."<br>";
							 if(!empty($feature_value)){
								//insert product feature.
								$feature_param['feature_value'] = $feature_value;
								$feature_param['feature_id'] = $feature_id;
								$feature_param['product_id'] = $product_id;
								$product_feature_id = $product->intInsertProductFeature($feature_param);
							}else{

								$product_feature_result = $product->arrGetProductFeatureDetails("",$feature_id,$product_id);
								$product_feature_id = $product_feature_result[0]['product_feature_id'];
								if(!empty($product_feature_id)){
									$product_feature_id = $product->boolDeleteProductFeature($product_feature_id);
								}
							}
						}
					}
				}
				//$insert_param['price'] = $price;
				$insert_param['brand_id']           = $_POST['select_brand_id'];
				$insert_param['product_id']         = $product_id;
				$insert_param['category_id']        = $request_param['category_id'];
				$insert_param['variant_value']      = $price;
				$insert_param['variant_id']         = '1';
				$insert_param['status']             = '1';
				//print"<pre>";print_r($insert_param); die();
				$priceId = $oPrice->intInsertVariantValueDetail($insert_param);

			$msg = "Product updated successfully.";
		}
	}elseif($actiontype == 'Delete'){
		$result = $product->boolDeleteProduct($product_id);
	    $msg = 'Product deleted successfully.';
   	}
	unlink(UPLOAD_TMP_PATH.$filename);
	$config_details = get_config_details();

	$strXML = "<XML>";
	$strXML .= $config_details;
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<SELECTED_BRAND_ID><![CDATA[$selected_brand_id]]></SELECTED_BRAND_ID>";
    $strXML .= "<SELECTED_MODEL_ID><![CDATA[$selected_model_id]]></SELECTED_MODEL_ID>";
    $strXML .= "<SELECTED_VARIANT_ID><![CDATA[$selected_variant_id]]></SELECTED_VARIANT_ID>";
	$strXML .= $xml;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('xsl/product.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
