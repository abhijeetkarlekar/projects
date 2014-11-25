<?php
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'videos.class.php');
require_once(CLASSPATH.'product.class.php');
$dbconn = new DbConn;
$oVideos = new videos;
$oProduct = new ProductManagement;

$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$category_id = $_REQUEST['selected_category_id'];

//print "<pre>"; print_r($_REQUEST); 
//die();
//echo "<br>=============================<br>";
if($actiontype == 'Insert'|| $actiontype== 'Update'){
	if($_REQUEST['display_rows']>0){
		unset($request_param);
		$dataCount=$_REQUEST['display_rows'];
		for($i=1;$i<=$dataCount;$i++){
				unset($request_param);
				$title = $_REQUEST['title_'.$i];	
				$title = trim($title);
				if(!empty($title)){ $request_param['title'] = htmlentities($title,ENT_QUOTES);}
				
				$slug = $_REQUEST['slug_'.$i];	
				$slug = trim($title);
				if(!empty($slug)){ $request_param['slug'] = htmlentities($slug,ENT_QUOTES);}

				$tags = $_REQUEST['tags_'.$i];
				$tags = trim($tags);
				if(!empty($tags)){ $request_param['tags'] = $tags;}	

				$meta_description = $_REQUEST['meta_description_'.$i];
				if(!empty($meta_description)){ $request_param['meta_description'] = $meta_description;}	

				$publish_time = $_REQUEST['publish_time']; 
		                if($publish_time != ''){ $request_param['publish_time'] = $publish_time;}

				//$status = $_REQUEST['status_'.$i];
				$status = $_REQUEST['status'];
				if($status!=''){ $request_param['status'] = $status;}

				$type_id = $_REQUEST['select_type_id'];
				if($type_id!=''){ $request_param['type_id']= $type_id;}

				$video_id = $_REQUEST['video_id_'.$i];
				if(!empty($video_id)){ $request_param['video_id'] = $video_id;}


				$upload_media_id = $_REQUEST['upload_media_id_'.$i];
				if(!empty($upload_media_id)){
					$request_param['upload_media_id'] = $upload_media_id;
				}

				$media_id = $_REQUEST['media_id_'.$i];
				if(!empty($media_id)){
					$request_param['media_id'] = $media_id;	
				}

				$media_path = $_REQUEST['media_upload_1_'.$i];
				/*
				if(!empty($media_path)){
					$request_param['media_path'] = $media_path;
				}
				*/
				$media_img_id = $_REQUEST['img_media_id_'.$i];
				if(!empty($media_img_id)){	
					$request_param['video_img_id'] = $media_img_id;
				}

				$media_img_path = $_REQUEST['img_upload_id_thm_'.$i];
				if(!empty($media_img_path)){	
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

				}else if($content_type == 2){
					//for image
					if(!empty($media_path)){
						$request_param['media_path'] = $media_path;
						$request_param['video_img_path'] = $media_path;
					}
					if(!empty($media_id)){
						$request_param['video_img_id'] = $media_id;
					}
					$request_param['is_media_process'] = 1;
				}else if($content_type == 3){
					//for audio
					if(!empty($media_img_path) && empty($media_path)){
						$request_param['media_path'] = $media_img_path;
						$request_param['is_media_process'] = 0;
					}
				}
				$category_id=$_REQUEST['selected_category_id'];
				$brand_id=$_REQUEST['select_brand_id'] ? $_REQUEST['select_brand_id'] : $_REQUEST['hd_select_brand_id'];
				//$product_id=$_REQUEST['product_id'] ? $_REQUEST['product_id'] : $_REQUEST['hd_product_id'];

				$product_info_id=$_REQUEST['select_model_id'] ? $_REQUEST['select_model_id'] : $_REQUEST['hd_select_model_id'];
				/*  if(!empty($product_id)){
					$product_result = $oProduct->arrGetProductDetails($product_id,$category_id,$brand_id,'',"","","","","","","","","","",'',"",'',"");
					//print_r($product_result);
					$product_name = $product_result[0]['product_name'];


					if(!empty($product_name)){
                		$product_info_result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,$product_name," ","","","","","","","","");
                		//print_r($product_info_result);
                		$product_info_id     = $product_info_result[0]['product_name_id'];
                	}	
                }*/
                


				$product_video_id=$_REQUEST['product_video_id_'.$i];
				$external_media_source = $_REQUEST['external_media_source_'.$i];
				$media_source_flag = $_REQUEST['media_source_flag_'.$i];
				if($external_media_source!=''){
					$request_param['content_type'] = 1;
					$request_param['external_media_source'] = $external_media_source;
					$request_param['media_source_flag'] = 2;
				}
				//print_r($request_param);
				$ordering = $_REQUEST['ordering_'.$i];
				$ordering = trim($ordering);
				if(!empty($ordering)){ $request_param['ordering'] = $ordering;}	
				//print "<pre>"; print_r($request_param);
				$video_result_id=$oVideos->addUpdVideosDetails($request_param,"VIDEO_GALLERY");
				$video_id = $video_result_id ? $video_result_id : $_REQUEST['video_id_'.$i];
				unset($request_param);
				if(!empty($video_id)){ $request_param['video_id'] = $video_id;}
				if(!empty($category_id)){ $request_param['category_id']=$category_id;}
				if($brand_id!=''){ $request_param['brand_id']=$brand_id;}
				//if($product_id!=''){ $request_param['product_id']=$product_id;}
				//if(!empty($product_info_id)){$request_param['product_info_id'] = $product_info_id;}
				if($product_info_id!=''){ $request_param['product_info_id']= $product_info_id;}
				if(!empty($product_video_id)){ $request_param['product_video_id'] = $product_video_id;}
				$product_video_result_id=$oVideos->addUpdVideosDetails($request_param,"PRODUCT_VIDEOS");
				if($video_result_id!=''){ $sresult++;}
	
		}
	}
	if($sresult>0){
		$msg = 'video added successfully.';
	}
}


if($actiontype == 'Delete'){
	$video_id = $_REQUEST['video_id'];
	if($video_id!=''){
		$result = $oVideos->booldeleteVideos($video_id);
		$msg = 'video deleted successfully.';
	}
}
$config_details = get_config_details();
$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('xsl/add_video.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
