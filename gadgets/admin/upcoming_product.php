<?php
	require_once('../include/config.php');
	//require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'product.class.php');
	$dbconn = new DbConn;
	
	$oProduct = new ProductManagement;


	//print"<pre>";print_r($_REQUEST);print"</pre>";exit;

	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
    	$limitcnt = $_REQUEST['cnt'];
	$category_id = $_REQUEST['selected_category_id'] ? $_REQUEST['selected_category_id'] : SITE_CATEGORY_ID;
	if(!empty($category_id)){
                $request_param['category_id'] = $category_id;
        }
	$product_name_id = $_REQUEST['select_model_id'];
	if(!empty($product_name_id)){
                $request_param['product_name_id'] = $product_name_id;
	}
	$feature_id = $_REQUEST['select_feature_id'];
	if(!empty($feature_id)){
                $request_param['feature_id'] = $feature_id;
	}
	$min_exp_price = $_REQUEST['min_exp_price'];
	$request_param['min_expected_price'] = $min_exp_price;

	$min_exp_price_unit = $_REQUEST['select_min_price_unit'];
        $request_param['min_expected_price_unit'] = $min_exp_price_unit;

	$max_exp_price = $_REQUEST['max_exp_price'];
        $request_param['max_expected_price'] = $max_exp_price;

	$max_exp_price_unit = $_REQUEST['select_max_price_unit'];
        $request_param['max_expected_price_unit'] = $max_exp_price_unit;

	$exp_launch_text = $_REQUEST['exp_launch_text'];
	if(!empty($exp_launch_text)){
                $request_param['expected_date_text'] = $exp_launch_text;
	}
	$select_exp_month = $_REQUEST['select_exp_month'];
	if(!empty($select_exp_month)){
                $request_param['expected_month'] = $select_exp_month;
	}
	$select_exp_year = $_REQUEST['select_exp_year'];
	if(!empty($select_exp_year)){
                $request_param['expected_year'] = $select_exp_year;
	}

	$start_date = $_REQUEST['start_date'];
        if(!empty($start_date)){
                $request_param['start_date'] = date('Y-m-d H:i:s',strtotime($start_date));
        }
	$end_date = $_REQUEST['end_date'];
        if(!empty($end_date)){
                $request_param['end_date'] = date('Y-m-d H:i:s',strtotime($end_date));
        }
	$short_desc = $_REQUEST['short_desc'];
	if(!empty($short_desc)){
		$short_desc = translatechars($short_desc);
                $request_param['short_description'] = htmlentities(trim($short_desc));
	}
	$content = $_REQUEST['content'];
	//if(!empty($content)){
		$content = translatechars($content);
		$request_param['content'] = htmlentities(trim($content),ENT_QUOTES);
	//}
	$status = $_REQUEST['product_status'];
	$request_param['status'] = $status;

	$position = $_REQUEST['position'];
	$request_param['position'] = $position;
	//print"<pre>";print_r($request_param);print"</pre>";exit;
	$upcoming_product_id = $_REQUEST['upcoming_product_id'] ;

	if($actiontype == 'Insert'){
                $upcoming_product_id = $oProduct->intInsertUpComingProductDetail($request_param);
                $msg="Upcoming product detail added successfully.";
        }else if($actiontype == 'Update'){
                $isUpdate = $oProduct->boolUpdateUpComingProductDetail($upcoming_product_id,$request_param);
                $msg="Upcoming product detail updated successfully.";
        }else if($actiontype == 'Delete'){
                $result = $oProduct->boolDeleteUpComingProductDetail($upcoming_product_id);
                $msg = 'Upcoming product detail deleted successfully.';
        }

	$display_rows = $_REQUEST['display_rows'];
	if((($actiontype == 'Insert') || ($actiontype == 'Update')) && ($upcoming_product_id != "")){
		if($_REQUEST['display_rows']>0){
			unset($request_param);
                        $dataCount=$_REQUEST['display_rows'];
                        for($i=1;$i<=$dataCount;$i++){
				$upcoming_product_video_id = $_REQUEST['upcoming_product_video_id_'.$i];
                                if(!empty($upcoming_product_video_id)){
					$request_param['upcoming_product_video_id'] = $upcoming_product_video_id;
                                }	
				$request_param['upcoming_product_id'] = $upcoming_product_id;
				$media_id = $_REQUEST['media_id_'.$i];
                                if(!empty($media_id)){
					$request_param['media_id'] = $media_id;
                                }
				$media_path = $_REQUEST['media_upload_1_'.$i];
                                $media_path = trim($media_path);
                                $media_img_id = $_REQUEST['img_media_id_'.$i];
                                if(!empty($media_img_id)){
    					$request_param['video_img_id'] = $media_img_id;
                                }
                                $media_img_path = $_REQUEST['img_upload_id_thm_'.$i] ? $_REQUEST['img_upload_id_thm_'.$i] : $_REQUEST['thumb_title_'.$i];
                                $media_img_path = trim($media_img_path);
                                if(!empty($media_img_path )){
                                	$request_param['video_img_path'] = $media_img_path;
                                }
				$content_type = !empty($_REQUEST['video_content_type_'.$i]) ? $_REQUEST['video_content_type_'.$i] : $_REQUEST['content_type_'.$i];
                                $request_param['content_type'] = $content_type;
				if($content_type == 1){
                                        //for video
					if(!empty($media_img_path) && empty($media_path)){
                                                $request_param['media_path'] = $media_img_path;
                                                $request_param['is_media_process'] = 0;
                                	}
                                }/*else if($content_type == 2){
					//for image
                                        if(!empty($media_path)){
                                                $request_param['media_path'] = $media_path;
                                                $request_param['video_img_path'] = $media_path;
                                        }
                                        if(!empty($media_id)){
                                        	$request_param['video_img_id'] = $media_id;
                                        }
                                        $request_param['is_media_process'] = 1;
				}*/
				$media_title = $_REQUEST['media_title_'.$i];
                                $media_title=htmlentities($media_title,ENT_QUOTES);
                                $request_param['media_title'] = $media_title;
                                $external_media_source = $_REQUEST['external_media_source_'.$i];
                                $media_source_flag = $_REQUEST['media_source_flag_'.$i];
                                if($external_media_source!=''){
                                	$request_param['content_type'] = 1;
                                        $request_param['external_media_source'] = $external_media_source;
                                        $request_param['media_source_flag'] = 2;
                                }
				$image_title = $_REQUEST['image_title_'.$i];
                                $image_title=htmlentities($image_title,ENT_QUOTES);
                                $request_param['image_title'] = $image_title;
                                $check_flag = $_REQUEST['check_flag_'.$i];
                                if($check_flag == 1){
                                	$request_param['media_id'] = "";
                                        $request_param['media_path'] = "";
                                        $request_param['video_img_id']="";
                                        $request_param['video_img_path'] = "";
                                        $request_param['content_type'] = "";
                                        $request_param['is_media_process'] = "";
                                        $request_param['external_media_source'] = "";
                                        $request_param['media_source_flag'] = "";
                                        $request_param['media_title'] = "";
                                }
				//print"<pre>";print_r($request_param);print"</pre>";exit;
				if($check_flag == 1){
						if(!empty($upcoming_product_video_id)){
							$res = $oProduct->boolDeleteUsedCarUpcomingMedia($upcoming_product_video_id);
		                                }else{
							$res = $oProduct->addUpdUpcomingMediaDetails($request_param,"UPCOMING_PRODUCT_VIDEOS");
						}
				}else{
					if((($request_param['media_path'] != "") && ($request_param['video_img_path'] != "")) || ($request_param['external_media_source'] != "")){
						$upcoming_product_video_id = $oProduct->addUpdUpcomingMediaDetails($request_param,"UPCOMING_PRODUCT_VIDEOS");
					}
				}
                                unset($request_param);
			}
		}
	}
$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "</XML>";

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('xsl/upcoming_product.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
