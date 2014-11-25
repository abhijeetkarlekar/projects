<?php
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'wallpaper.class.php');

$dbconn = new DbConn;
$oWallpaper = new Wallpapers;

$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$category_id = $_REQUEST['selected_category_id'];
//print_r($_REQUEST); 
if($actiontype == 'Insert'|| $actiontype== 'Update'){
	if($_REQUEST['display_rows']>0){
		$product_slide_id = "";
		$product_slide_id = $_REQUEST['hd_product_slide_id'] ? $_REQUEST['hd_product_slide_id'] : $product_slide_id;
		if(!empty($product_slide_id)){$request_param['product_slide_id'] = $product_slide_id;}
		$title = trim($_REQUEST['slide_title']);
		if(!empty($title)){ $request_param['title'] = htmlentities($title,ENT_QUOTES);}
		$group_id = $_REQUEST['select_group_id'];
		if(!empty($group_id)){$request_param['group_id'] = $group_id;}
		$category_id = $_REQUEST['selected_category_id'];
		if($category_id!=''){ $request_param['category_id'] = $category_id;}
		$brand_id = $_REQUEST['select_brand_id'];
		if(!empty($brand_id)){$request_param['brand_id'] = $brand_id;}
		$product_id = $_REQUEST['product_id'];
		/*if(!empty($product_id)){
		$product_result = $oProduct->arrGetProductDetails($product_id,$category_id,$brand_id,'',"","","","","","","","","","",'',"",'',"");
		//print_r($product_result);
		$product_name = $product_result[0]['product_name'];
		if(!empty($product_name)){
			$product_info_result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,$product_name," ","","","","","","","","");
				print_r($product_info_result);
			$product_info_id     = $product_info_result[0]['product_name_id'];
		}	
		}*/
		
		
		$product_info_id     = $_REQUEST['select_model_id'];	
		if(!empty($product_info_id)){$request_param['product_info_id'] = $product_info_id;}

	/*$product_info_id = $_REQUEST['select_model_id'];
	if(!empty($product_info_id)){$request_param['product_info_id'] = $product_info_id;}*/

	if(!empty($product_id)){$request_param['product_id'] = $product_id;}
	$publish_time = $_REQUEST['publish_time'];
	if($publish_time != ''){ $request_param['publish_time'] = $publish_time;}
	$status = $_REQUEST['status'];
	if($status!=''){ $request_param['status'] = $status;}
	$media_id = $_REQUEST['abstract_img_id'];
	if(!empty($media_id)){$request_param['media_id'] = $media_id;}
	$media_path = trim($_REQUEST['abstract_img_path']);
	if(!empty($media_path)){$request_param['media_path'] = $media_path;}
	$abstract = $_REQUEST['product_slide_abstract'];
	if(!empty($abstract)){$request_param['abstract']=$abstract;}
	//print_r($request_param) ; die();
	//if($title!=''){
		$product_slide_id=$oWallpaper->intInsertProductSlides($request_param,"PRODUCT_SLIDES");
	//}
	if($product_slide_id != ""){
	unset($request_param);
	$dataCount=$_REQUEST['display_rows'];
	for($i=1;$i<=$dataCount;$i++){
	unset($request_param);
	$request_param['product_slide_id'] = $product_slide_id;
	$title = trim($_REQUEST['title_'.$i]);	
	//if(!empty($title)){ $request_param['title'] = htmlentities($title,ENT_QUOTES);}
	$request_param['title'] = htmlentities($title,ENT_QUOTES);

	$slug = trim($_REQUEST['slug_'.$i]);	
	//if(!empty($title)){ $request_param['title'] = htmlentities($title,ENT_QUOTES);}
	$request_param['slug'] = htmlentities($slug,ENT_QUOTES);

	$tags = trim($_REQUEST['tags_'.$i]);
	if(!empty($tags)){ $request_param['tags'] = $tags;}	

	$meta_description = $_REQUEST['meta_description_'.$i];
	if(!empty($meta_description)){ $request_param['meta_description'] = $meta_description;}	

	$status = $_REQUEST['status'];
	if($status!=''){ $request_param['status'] = $status;}


	$slideshow_id = $_REQUEST['slideshow_id_'.$i];
	if(!empty($slideshow_id)){ $request_param['slideshow_id'] = $slideshow_id;}

	$group_id = $_REQUEST['select_group_id'];
	if(!empty($group_id)){$request_param['group_id'] = $group_id;}

	$upload_media_id = $_REQUEST['upload_media_id_'.$i];
	if(!empty($upload_media_id)){
	$request_param['upload_media_id'] = $upload_media_id;
	}

	$media_id = $_REQUEST['media_id_'.$i];
	if(!empty($media_id)){
	$request_param['media_id'] = $media_id;	
	}

	$media_path = trim($_REQUEST['media_upload_1_'.$i]);
	if(!empty($media_path)){
	$request_param['media_path'] = $media_path;
	}

	$media_img_id = $_REQUEST['img_media_id_'.$i];
	if(!empty($media_img_id)){	
	$request_param['video_img_id'] = $media_img_id;
	}

	$media_img_path = trim($_REQUEST['img_upload_id_thm_'.$i]);
	if(!empty($media_img_path)){	
	$request_param['video_img_path'] = $media_img_path;
	}

	$content_type = !empty($_REQUEST['video_content_type_'.$i]) ? $_REQUEST['video_content_type_'.$i] : $_REQUEST['content_type_'.$i];
	$request_param['content_type'] = $content_type;

	if($content_type == 1){
	//for video
	if(!empty($media_img_path)){
	$request_param['media_path'] = $media_img_path;
	}
	$request_param['is_media_process'] = 0;
	}else if($content_type == 2){
	//for image
	if(!empty($media_path)){
	$request_param['video_img_path'] = $media_path;
	}
	if(!empty($media_id)){
	$request_param['video_img_id'] = $media_id;
	}
	$request_param['is_media_process'] = 1;
	}else if($content_type == 3){
	//for audio
	if(!empty($media_img_path)){
	$request_param['media_path'] = $media_img_path;
	}
	$request_param['is_media_process'] = 0;
	}

	$check_flag = $_REQUEST['check_flag_'.$i];
	if($check_flag == 1){
		$request_param['media_id'] = "";
		$request_param['media_path'] = "";
		$request_param['video_img_id']="";
		$request_param['video_img_path'] = "";
		$request_param['content_type'] = "";
		$request_param['is_media_process'] = "";
	}
	$ordering = trim($_REQUEST['ordering_'.$i]);
	if(!empty($ordering)){ $request_param['ordering'] = $ordering;}	
	//print "<pre>"; print_r($request_param); 
	$iResId=$oWallpaper->intInsertSlideshow($request_param,"SLIDESHOW");
	//$iResId=$oWallpaper->intInsertWallpapers($aParameters);
	}
	}
}
//die();
	if($iResId>0){
		$msg = 'slideshow added successfully.';
	}
}


if($actiontype == 'Delete' && $_REQUEST['product_slideshow_id']!=''){
	$product_slideshow_id = $_REQUEST['product_slideshow_id'];
	$result = $oWallpaper->boolDeleteSlideshow($product_slideshow_id);
	$msg = 'slideshow deleted successfully.';
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
$xsl = DOMDocument::load('xsl/slideshow.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
