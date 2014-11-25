<?php
require_once('./include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'pivot.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'price.class.php');
require_once(CLASSPATH.'compare.class.php');
require_once(CLASSPATH.'Utility.php');
require_once(CLASSPATH.'videos.class.php');
require_once(CLASSPATH.'curl.class.php');
require_once(CLASSPATH.'xmlparser.class.php');
require_once(CLASSPATH.  'wallpaper.class.php');

$dbconn = new DbConn;
$pivot = new PivotManagement;
$feature = new FeatureManagement;
$category = new CategoryManagement;
$product = new ProductManagement;
$brand = new BrandManagement;
$price = new price;
$compare = new compare;
$videoGallery = new videos();
$oCurl = new curl;
$oXmlparser = new XMLParser;
$oWallpapers =new Wallpapers;

/*
$default_car = '828';
$default_car_brand = '14';
$default_car_arr = array('828');
$default_car_model = '313';

$default_car_compitetor[0]['product_ids'] = 684;
$default_car_compitetor[1]['product_ids'] = 295;
$default_car_compitetor[2]['product_ids'] = 532;
$default_car_compitetor[3]['product_ids'] = 234;
$default_car_start_price = 470000;
$default_car_end_price = 620000;
*/
//print_r($_SERVER['REQUEST_URI']); die();
$pivotArr = $product->assignPivotToSearch();
$bodyStyleArr = $pivotArr['body_style'];
$fuelTypeArr = $pivotArr['fuel_type'];
unset($pivotArr);
foreach($bodyStyleArr as $feature_id){
	unset($result);
	$result = $feature->arrGetFeatureDetails($feature_id);
	$pivotArr[$feature_id] = $result[0]['feature_name'];
}
unset($bodyStyleArr);
$bodyStyleArr = $pivotArr;
unset($pivotArr);
foreach($fuelTypeArr as $feature_id){
	unset($result);
	$result = $feature->arrGetFeatureDetails($feature_id);
	$pivotArr[$feature_id] = $result[0]['feature_name'];
}
unset($fuelTypeArr);
$fuelTypeArr = $pivotArr;
unset($pivotArr);
$category_id = $_REQUEST['category_id'];
$tab_id = $_REQUEST['fid'] ? $_REQUEST['fid'] : 1;
$productids = !empty($_REQUEST['router_compare_id']) ? $_REQUEST['router_compare_id'] : $_REQUEST['productids'];
if(!empty($productids)){
	$md5ProductIds = md5($productids);
}
$cookieProductIds = $_REQUEST['scomp'];
$uid = $_REQUEST['uid'] ? $_REQUEST['uid'] : 0;
if(!empty($productids)){
	$productArr = explode(",",$productids);
	$getpricerange = $product->arrGetProductDetails($productArr,$category_id,"",'1',"","","1","","","1");
	if(is_array($getpricerange)){
		foreach ($getpricerange as $kkey=>$kValue){
			$price_range_arr[] = $kValue['variant_value'];
		}
	}
	if(is_array($price_range_arr)){
		sort($price_range_arr);
		if(count($price_range_arr) < 1){
			$price_range_start = $price_range_arr[0];
			$price_range_end = $price_range_arr[0];
		}else{
			$price_range_start = $price_range_arr[0];
			$price_range_end = $price_range_arr[count($price_range_arr)-1];
		}
	}
	if(count($productArr) < 4 && $price_range_start >= $default_car_start_price && $price_range_end <= $default_car_end_price ){
		if(!in_array($default_car,$productArr)){
			array_push($productArr,$default_car);
		}
	}
	$compareProductArr = $productArr;
}
$md5ProductIds = md5(implode(',',$compareProductArr));
$compareProductCnt = 0;
$compareProductCnt = sizeof($productArr);
if($compareProductCnt > 4){
	echo "Compare set is more than 4.";
	exit;
}
if($compareProductCnt < 4){
	$pushCompareEmptyItem = 4 - $compareProductCnt;
	for($i=0;$i<$pushCompareEmptyItem;$i++){
		array_push($productArr,"");
	}
}

function GetSeoVariantPageUrl($brand_name,$product_name,$variant,$product_id,$variantUrlYear){
	//set seo url for product variant page.
	$variantnameSeoArr[] = SEO_WEB_URL;
	if(!empty($brand_name)){
		$variantnameSeoArr[] = constructUrl($brand_name);
	}
	if(!empty($brand_name) && !empty($product_name)){
		$variantnameSeoArr[] = constructUrl($product_name);
	}
	if(!empty($variant)){
		$variantnameSeoArr[] = constructUrl($variant);
	}
	if(!empty($variantUrlYear)){
		$variantnameSeoArr[] = constructUrl($variantUrlYear);
	}
	$seo_url = implode("/",$variantnameSeoArr);
	return $seo_url;
}
$iflag=0;
$productxml = "<PRODUCT_EX_SHOW_ROOM_PRICE>";
$productxml .= "<COUNT><![CDATA[".sizeof($productArr)."]]></COUNT>";
$modelnamesetArr = Array();
$productnamesetArr = Array();
foreach($productArr as $key => $productid){
	if(empty($productid)) continue;
	unset($productNameArr);
	unset($result);
	$first_product = $productArr[0];
	$seo_variant_page_url = "";
	if(!empty($productid)){
		$insert_param = array("product_id"=>$productid,"uid"=>$uid);
		if($md5ProductIds != $cookieProductIds){
			//used to save compare only 1 time for a single user.
			setcookie('scomp',$md5ProductIds);
			//	$save_compare_id = $compare->saveComparision($insert_param);
		}
		$result = $product->arrGetProductDetails($productid,$category_id,"",'1',"","","1","","","1");

		$variant_value = $result[0]['variant_value'];
		$variant_price = $variant_value ? priceFormat($variant_value) : '';
		$product_id = $result[0]['product_id'];
		$arrival_date = $result[0]['arrival_date'];
		$discontinue_date = $result[0]['discontinue_date'];
		unset($variantUrlYear);
		$variantUrlYear = buildYear($arrival_date,$discontinue_date);
		$brand_id = $result[0]['brand_id'];
		$model_name = $result[0]['product_name'];
		$model_res = $product->arrGetProductNameInfo("",$category_id,"",$model_name);
		if(is_array($model_res)){
			$product_info_name = $model_res[0]["product_info_name"];
			$product_name_id = $model_res[0]["product_name_id"];
			$media_path = $model_res[0]["image_path"];
			$video_path = $model_res[0]["video_path"];
		}
		$media_path = $result[0]['image_path'];
		$video_path = str_replace(array(CENTRAL_IMAGE_URL,CENTRAL_MEDIA_URL),"",$video_path);
		if(!empty($video_path)){
			$result[0]['video_path'] = CENTRAL_MEDIA_URL.$video_path;
		}
		$image_path = str_replace(array(CENTRAL_IMAGE_URL,CENTRAL_MEDIA_URL),"",$media_path);
		if(!empty($image_path)){
			$image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize);
			$result[0]['image_path'] = CENTRAL_IMAGE_URL.$image_path;
		}
		 if(!empty($image_path)){
                                $image_path1 = resizeImagePath($image_path,"251X188",$aModuleImageResize,$img_media_id);
                                $image_path1 = CENTRAL_IMAGE_URL.$image_path1;
                                $res = file_get_contents($image_path1);
                                if(strlen($res) > 0){
                                        $image_path1 = $image_path1;
                                }else{
                                        $image_path1 = IMAGE_URL."no-image.png";
                                }
                 }else{
                                $image_path1 = IMAGE_URL."no-image.png";
                 }
		$result[0]['share_image_path'] = $image_path1;
		$exshowroomprice = $result[0]['variant_value'];
		$seoProductPivotArr['price'][$product_id] = $exshowroomprice;
		$exshowroomprice = $exshowroomprice ? priceFormat($exshowroomprice) : '';
		$brand_result = $brand->arrGetBrandDetails($result[0]['brand_id']);
		$brand_name = trim($brand_result[0]['brand_name']);
		$seoProductPivotArr['brand'][$product_id] = constructUrl($brand_name);
		$modelnamesetArr[] = constructUrl($brand_name).'-'.constructUrl($product_info_name);
		if(!empty($brand_name)){
			$productNameArr[] = $brand_name;
		}
		$product_name = trim($result[0]['product_name']);
		$productNameArr[] = $product_name;
		$variant = trim($result[0]['variant']);



		unset($productnameset);
		$productnameset = constructUrl($brand_name).'-'.constructUrl($product_info_name).'-'.constructUrl($variant);
		if(!empty($variantUrlYear)){
			$productnameset = $productnameset.'-'.$variantUrlYear;
		}
		$productnamesetArr[] = $productnameset;
		
		$compare_product_set_name = constructUrl($brand_name).'-'.constructUrl($product_info_name).'-'.constructUrl($variant);
		if(!empty($variantUrlYear)){
			$compare_product_set_name = $compare_product_set_name.'-'.$variantUrlYear;
		}
		//$productNameArr[] = $variant;
		//set seo url for product variant page.
		$seo_variant_page_url = GetSeoVariantPageUrl($brand_name,$product_name,$variant,$product_id,$variantUrlYear);
		if($iflag==0){
			$subtitle[]=$brand_name;
			$subtitle[]=$product_name;
			$subtitle[]=$variant;
			$strsubtitle=implode(" ",$subtitle);
		}
		//start code added by rajesh on dated 02-06-2011 for expert rating and graph.
		$rating_brand_id = $result[0]['brand_id'];
		$product_info_name = $result[0]['product_name'];
		$productresult = $product->arrGetProductNameInfo("",$category_id,$rating_brand_id,$product_info_name);
		$product_name_id = $productresult[0]['product_name_id'];
		$product_name_desc = $productresult[0]['product_name_desc'];	
		
		$brandresult = $brand->arrGetBrandDetails($rating_brand_id);
		$product_brand_name = html_entity_decode($brandresult[0]['brand_name'],ENT_QUOTES,'UTF-8');
		$product_brand_name = removeSlashes($product_brand_name);
		$product_brand_name = seo_title_replace($product_brand_name);
		$product_link_name = $product_brand_name."-".$product_info_name;
		$product_link_name = html_entity_decode($product_link_name,ENT_QUOTES,'UTF-8');
		$product_link_name = removeSlashes($product_link_name);
		$product_link_name = seo_title_replace($product_link_name);

		unset($seoTitleArr);
		$seoTitleArr[] = SEO_WEB_URL;
		$seoTitleArr[] = constructUrl($product_brand_name);
                $seoTitleArr[] = constructUrl($product_info_name);
                $seoTitleArr[] = constructUrl($variant);
		if(!empty($variantUrlYear)){
			$seoTitleArr[] = $variantUrlYear;
		}
		$seoTitleArr[] = "expert-reviews";
		$seo_model_url = implode("/",$seoTitleArr);
		
		unset($seoTitleArr);
		$seoTitleArr[] = SEO_WEB_URL;
		$seoTitleArr[] = constructUrl($product_brand_name);
                $seoTitleArr[] = constructUrl($product_info_name);
                $seoTitleArr[] = constructUrl($variant);
		if(!empty($variantUrlYear)){
                        $seoTitleArr[] = $variantUrlYear;
                }
		$seoTitleArr[] = "user-reviews";
		$seo_user_review_model_url = implode("/",$seoTitleArr);

		unset($seoTitleArr);
		$seoTitleArr[] = SEO_WEB_URL;
		$seoTitleArr[] = constructUrl($product_brand_name);
		$seoTitleArr[] = constructUrl($product_info_name);
		$seoTitleArr[] = constructUrl($variant);
		if(!empty($variantUrlYear)){
                        $seoTitleArr[] = $variantUrlYear;
                }
		$seoTitleArr[] = SEO_GET_ON_ROAD_PRICE;
		$seo_on_road_price_url = implode("/",$seoTitleArr);

		unset($seoTitleArr);
		$seoTitleArr[] = SEO_WEB_URL;
		$seoTitleArr[] = constructUrl($product_brand_name);
                $seoTitleArr[] = constructUrl($product_info_name);
                $seoTitleArr[] = constructUrl($variant);
		if(!empty($variantUrlYear)){
                        $seoTitleArr[] = $variantUrlYear;
                }
		$seoTitleArr[] = SEO_EMI_CALCULATOR;
		$seo_emi_url = implode("/",$seoTitleArr);

		unset($seoTitleArr);
		
	}
	$compare_product_name = implode(" ",$productNameArr);
	if(!empty($compare_product_name)){
		$seoCompareProductArr[] = $compare_product_name;
	}
	$compare_prdct_img = $result[0]['image_path'];
	$compare_share_prdct_img = $result[0]['share_image_path'];

	


	$var_discontinue_status = $result[0]["discontinue_flag"];
	$var_discontinue_date = $result[0]["discontinue_date"];
	$three_months_plus_discontinue_date = 0;
	$prev_3_month = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
	if(($var_discontinue_status == "0") && (strtotime($var_discontinue_date) < strtotime($prev_3_month)) && $var_discontinue_date!='0000-00-00 00:00:00' ){
		$three_months_plus_discontinue_date = 1;
	}else if(($var_discontinue_status == "0") && ((strtotime($var_discontinue_date) > strtotime($prev_3_month)) || $var_discontinue_date!='0000-00-00 00:00:00') ){
		$three_months_plus_discontinue_date = 2;
	}
	//$pResult = $product->arrGetProductByName($product_info_name,"","","","","","","1");
	$pResult = $product->arrGetProductDetails("",$category_id,"",'1',"","","1","","","1","",$product_info_name);
	$xmld='';
	$pResultcount = count($pResult);
	if(is_array($pResult)){
		for($i=0;$i<$pResultcount;$i++){
			$pResult[$i] = array_change_key_case($pResult[$i],CASE_UPPER);
		    	$variant = !empty($pResult[$i]['variant']) ? $pResult[$i]['variant'] : $pResult[$i]['seo_path'];
			$pResult[$i]['variant'] = $variant; 
	    $xmld .= "<COUNT><![CDATA[$pResultcount]]></COUNT>";
            $xmld .= "<PRODUCT_MASTER_DATA>";
            foreach($pResult[$i] as $k=>$v){

                    $xmld .= "<$k><![CDATA[".html_entity_decode($v,ENT_QUOTES,'UTF-8')."]]></$k>";
            }
            $xmld .= "</PRODUCT_MASTER_DATA>";
		}
	}

	$productxml .= "<PRODUCT_EX_SHOW_ROOM_PRICE_DATA>";
	$productxml .= "<DISCONTINUE_DATE><![CDATA[".trim($three_months_plus_discontinue_date)."]]></DISCONTINUE_DATE>";
	$productxml .= "<PRODUCT_EX_SHOW_ROOM_PRICE><![CDATA[".trim($exshowroomprice)."]]></PRODUCT_EX_SHOW_ROOM_PRICE>";
	$productxml .= "<EX_SHOW_ROOM_PRICE_PRODUCT_ID><![CDATA[$productid]]></EX_SHOW_ROOM_PRICE_PRODUCT_ID>";
	$productxml .= "<SEO_ON_ROAD_PRICE_URL><![CDATA[$seo_on_road_price_url]]></SEO_ON_ROAD_PRICE_URL>";
    
	$productxml .= "<SEO_EMI_URL><![CDATA[$seo_emi_url]]></SEO_EMI_URL>";
	$productxml .= "<COMPARE_DISCONTINUE><![CDATA[$var_discontinue_status]]></COMPARE_DISCONTINUE>";
	$productxml .= "</PRODUCT_EX_SHOW_ROOM_PRICE_DATA>";

	$productxml .= "<PRODUCT_DESC_DATA>";
	$productxml .= "<PRODUCT_DESC_DETAIL_DATA><![CDATA[".html_entity_decode(trim($product_name_desc),ENT_QUOTES,'UTF-8')."]]></PRODUCT_DESC_DETAIL_DATA>";
	$productxml .= "<PRODUCT_DESC_PRODUCT_ID><![CDATA[$productid]]></PRODUCT_DESC_PRODUCT_ID>";
	$productxml .= "</PRODUCT_DESC_DATA>";
	$product_name_descarr[] = $product_name_desc;
	$product_name_desc = "";
	$productxml .= "<EX_SHOW_ROOM_PRICE_PRODUCT_ID><![CDATA[$productid]]></EX_SHOW_ROOM_PRICE_PRODUCT_ID>";

	// photo count
	$group_ids = array('0'=>'1','1'=>'2');
	$slideshow_result = $oWallpapers->arrSlideShowDetailsCount("",$group_ids,$product_id,'',"",$category_id,"","1");
	$scnt = $slideshow_result[0]['cnt'];
	// video count
	// COUNTS
    unset($result_count);
    $result_count = $videoGallery->getVideosDetailsCount("","","","",$model_id,$category_id,"","1");
    $category_result_count = $result_count[0]['cnt'];
    // Reviews Category Result count 
    unset($result_count);
	$videocnt = $category_result_count;
	if($key == 0){
		if($product_name_id == $default_car_model){
			$comparesetxml .= "<DEFAULT_FIRST_COMPARE_PRODUCT_BGID>1</DEFAULT_FIRST_COMPARE_PRODUCT_BGID>";
		}
		$compare_result = $product->arrGetProdCompetitorDetailsCnt("",$product_id,$product_name_id,$brand_id,'1','1','1','1','1');   
                $comparecnt = $compare_result[0]['cnt'];

		$comparesetxml .= "<COMPARE_SET_MASTER>";
		$comparesetxml .= "<COUNT><![CDATA[$comparecnt]]></COUNT>";
		$comparesetxml .= "</COMPARE_SET_MASTER>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_NAME_ID><![CDATA[$product_name_id]]></FIRST_COMPARE_PRODUCT_NAME_ID>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_PRICE><![CDATA[$variant_price]]></FIRST_COMPARE_PRODUCT_PRICE>";
		$comparesetxml .= "<FIRST_COMPARE_BRAND_ID><![CDATA[$brand_id]]></FIRST_COMPARE_BRAND_ID>";
		$comparesetxml .= "<FIRST_COMPARE_MODEL_ID><![CDATA[$product_name_id]]></FIRST_COMPARE_MODEL_ID>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_ID><![CDATA[$product_id]]></FIRST_COMPARE_PRODUCT_ID>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_NAME><![CDATA[$compare_product_name]]></FIRST_COMPARE_PRODUCT_NAME>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_SET_NAME><![CDATA[$compare_product_set_name]]></FIRST_COMPARE_PRODUCT_SET_NAME>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_DATA>$xmld</FIRST_COMPARE_PRODUCT_DATA>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_IMG><![CDATA[$compare_prdct_img]]></FIRST_COMPARE_PRODUCT_IMG>";
		$comparesetxml .= "<FIRST_COMPARE_SHARE_PRODUCT_IMG><![CDATA[$compare_share_prdct_img]]></FIRST_COMPARE_SHARE_PRODUCT_IMG>";
		$comparesetxml .= "<FIRST_COMPARE_PRODUCT_VARIANT_PAGE_URL><![CDATA[$seo_variant_page_url]]></FIRST_COMPARE_PRODUCT_VARIANT_PAGE_URL>";
		$comparesetxml .= "<FIRST_VAR_DISCONTINUE_FLAG><![CDATA[$var_discontinue_status]]></FIRST_VAR_DISCONTINUE_FLAG>";
		$comparesetxml .= "<FIRST_THREE_MONTHS_PLUS_DISCONTINUE_DATE><![CDATA[$three_months_plus_discontinue_date]]></FIRST_THREE_MONTHS_PLUS_DISCONTINUE_DATE>";
		$comparesetxml .= "<FIRST_COMPARE_PHOTO_COUNT><![CDATA[$scnt]]></FIRST_COMPARE_PHOTO_COUNT>";
		$comparesetxml .= "<FIRST_COMPARE_VIDEO_COUNT><![CDATA[$videocnt]]></FIRST_COMPARE_VIDEO_COUNT>";
		$comparesetxml .= "<FIRST_COMPARE_DISCONTINUE><![CDATA[$var_discontinue_status]]></FIRST_COMPARE_DISCONTINUE>";
		$first_prd_name = $product_name;
		$first_brd_name = $product_brand_name;
	}
	if($key == 1){
		if($product_name_id == $default_car_model){
			$comparesetxml .= "<DEFAULT_SECOND_COMPARE_PRODUCT_BGID>1</DEFAULT_SECOND_COMPARE_PRODUCT_BGID>";
		}
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_NAME_ID><![CDATA[$product_name_id]]></SECOND_COMPARE_PRODUCT_NAME_ID>";
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_PRICE><![CDATA[$variant_price]]></SECOND_COMPARE_PRODUCT_PRICE>";
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_ID><![CDATA[$productid]]></SECOND_COMPARE_PRODUCT_ID>";
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_NAME><![CDATA[$compare_product_name]]></SECOND_COMPARE_PRODUCT_NAME>";
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_SET_NAME><![CDATA[$compare_product_set_name]]></SECOND_COMPARE_PRODUCT_SET_NAME>";
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_DATA>$xmld</SECOND_COMPARE_PRODUCT_DATA>";
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_IMG><![CDATA[$compare_prdct_img]]></SECOND_COMPARE_PRODUCT_IMG>";
		$comparesetxml .= "<SECOND_COMPARE_PRODUCT_VARIANT_PAGE_URL><![CDATA[$seo_variant_page_url]]></SECOND_COMPARE_PRODUCT_VARIANT_PAGE_URL>";
		$comparesetxml .= "<SECOND_VAR_DISCONTINUE_FLAG><![CDATA[$var_discontinue_status]]></SECOND_VAR_DISCONTINUE_FLAG>";
		$comparesetxml .= "<SECOND_THREE_MONTHS_PLUS_DISCONTINUE_DATE><![CDATA[$three_months_plus_discontinue_date]]></SECOND_THREE_MONTHS_PLUS_DISCONTINUE_DATE>";
		$comparesetxml .= "<SECOND_COMPARE_PHOTO_COUNT><![CDATA[$scnt]]></SECOND_COMPARE_PHOTO_COUNT>";
		$comparesetxml .= "<SECOND_COMPARE_VIDEO_COUNT><![CDATA[$videocnt]]></SECOND_COMPARE_VIDEO_COUNT>";
		$comparesetxml .= "<SECOND_COMPARE_DISCONTINUE><![CDATA[$var_discontinue_status]]></SECOND_COMPARE_DISCONTINUE>";
	}
	if($key == 2){
		if($product_name_id == $default_car_model){
			$comparesetxml .= "<DEFAULT_THIRD_COMPARE_PRODUCT_BGID>1</DEFAULT_THIRD_COMPARE_PRODUCT_BGID>";
		}
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_NAME_ID><![CDATA[$product_name_id]]></THIRD_COMPARE_PRODUCT_NAME_ID>";
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_PRICE><![CDATA[$variant_price]]></THIRD_COMPARE_PRODUCT_PRICE>";
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_ID><![CDATA[$productid]]></THIRD_COMPARE_PRODUCT_ID>";
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_NAME><![CDATA[$compare_product_name]]></THIRD_COMPARE_PRODUCT_NAME>";
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_SET_NAME><![CDATA[$compare_product_set_name]]></THIRD_COMPARE_PRODUCT_SET_NAME>";
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_DATA>$xmld</THIRD_COMPARE_PRODUCT_DATA>";
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_IMG><![CDATA[$compare_prdct_img]]></THIRD_COMPARE_PRODUCT_IMG>";
		$comparesetxml .= "<THIRD_COMPARE_PRODUCT_VARIANT_PAGE_URL><![CDATA[$seo_variant_page_url]]></THIRD_COMPARE_PRODUCT_VARIANT_PAGE_URL>";
		$comparesetxml .= "<THIRD_VAR_DISCONTINUE_FLAG><![CDATA[$var_discontinue_status]]></THIRD_VAR_DISCONTINUE_FLAG>";
		$comparesetxml .= "<THIRD_THREE_MONTHS_PLUS_DISCONTINUE_DATE><![CDATA[$three_months_plus_discontinue_date]]></THIRD_THREE_MONTHS_PLUS_DISCONTINUE_DATE>";
		$comparesetxml .= "<THIRD_COMPARE_PHOTO_COUNT><![CDATA[$scnt]]></THIRD_COMPARE_PHOTO_COUNT>";
		$comparesetxml .= "<THIRD_COMPARE_VIDEO_COUNT><![CDATA[$videocnt]]></THIRD_COMPARE_VIDEO_COUNT>";
		$comparesetxml .= "<THIRD_COMPARE_DISCONTINUE><![CDATA[$var_discontinue_status]]></THIRD_COMPARE_DISCONTINUE>";
	}
	if($key == 3){
		if($product_name_id == $default_car_model){
			$comparesetxml .= "<DEFAULT_FOURTH_COMPARE_PRODUCT_BGID>1</DEFAULT_FOURTH_COMPARE_PRODUCT_BGID>";
		}
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_NAME_ID><![CDATA[$product_name_id]]></FOURTH_COMPARE_PRODUCT_NAME_ID>";
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_PRICE><![CDATA[$variant_price]]></FOURTH_COMPARE_PRODUCT_PRICE>";
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_ID><![CDATA[$productid]]></FOURTH_COMPARE_PRODUCT_ID>";
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_NAME><![CDATA[$compare_product_name]]></FOURTH_COMPARE_PRODUCT_NAME>";
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_SET_NAME><![CDATA[$compare_product_set_name]]></FOURTH_COMPARE_PRODUCT_SET_NAME>";
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_DATA>$xmld</FOURTH_COMPARE_PRODUCT_DATA>";
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_IMG><![CDATA[$compare_prdct_img]]></FOURTH_COMPARE_PRODUCT_IMG>";
		$comparesetxml .= "<FOURTH_COMPARE_PRODUCT_VARIANT_PAGE_URL><![CDATA[$seo_variant_page_url]]></FOURTH_COMPARE_PRODUCT_VARIANT_PAGE_URL>";
		$comparesetxml .= "<FOURTH_VAR_DISCONTINUE_FLAG><![CDATA[$var_discontinue_status]]></FOURTH_VAR_DISCONTINUE_FLAG>";
		$comparesetxml .= "<FOURTH_THREE_MONTHS_PLUS_DISCONTINUE_DATE><![CDATA[$three_months_plus_discontinue_date]]></FOURTH_THREE_MONTHS_PLUS_DISCONTINUE_DATE>";
		$comparesetxml .= "<FOURTH_COMPARE_PHOTO_COUNT><![CDATA[$scnt]]></FOURTH_COMPARE_PHOTO_COUNT>";
		$comparesetxml .= "<FOURTH_COMPARE_VIDEO_COUNT><![CDATA[$videocnt]]></FOURTH_COMPARE_VIDEO_COUNT>";
		$comparesetxml .= "<FOURTH_COMPARE_DISCONTINUE><![CDATA[$var_discontinue_status]]></FOURTH_COMPARE_DISCONTINUE>";
	}
	$ratingxmlArr[$key][] = $expertratingxml;
	$productid="";
	$exshowroomprice="";
	$compare_product_name="";
	$compare_prdct_img="";
	$html="";
	$expertratingxml = "";
	$iflag++;
}
$productxml .= "<PRODUCT_NAME_DESCARR><![CDATA[".count($three_months_plus_discontinue_date)."]]></PRODUCT_NAME_DESCARR>";
$productxml .= "</PRODUCT_EX_SHOW_ROOM_PRICE>";
//start code for compare set
$ratecnt = sizeof($ratingxmlArr);
$ratexml = "<RATING_MASTER>";
for($i=0;$i<$ratecnt;$i++){
	$ratexml .= "<RATING_MASTER_DATA>";
	foreach($ratingxmlArr[$i] as $k=>$v){
		$ratexml .= $v;
	}
	$ratexml .= "</RATING_MASTER_DATA>";
}
$ratexml .= "</RATING_MASTER>";

/*
$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","3");
*/
$pivot_result = $compare->arrGetTopCompitatorsByFeatureBodytype($category_id);
$pivotcnt = sizeof($pivot_result);
$bodyStyleArr = array();
for($i=0;$i<$pivotcnt;$i++){
        $bodyStyleArr[] = $pivot_result[$i]['feature_id'];
}
$featureresult = $feature->arrGetFeatureDetails(array_unique($bodyStyleArr),"","","18");
$xml .= "<BODY_STYLE>";
$featurecnt = sizeof($featureresult);
        for($fc=0;$fc<$featurecnt;$fc++){
                $count_prodcut_result=''; $bdy_feature_id='';
                $featureresult[$fc] = array_change_key_case($featureresult[$fc],CASE_UPPER);
                $bdy_feature_id = '';
                $xml .= "<BODY_STYLE_DATA>";
                foreach($featureresult[$fc] as $k=>$v){
                        $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</BODY_STYLE_DATA>";
        }
$xml .= "</BODY_STYLE>";

$same_feature_value=array();
if(!empty($category_id)){
	$result = $feature->arrGetFeatureMainGroupDetails("",$category_id,"",$startlimit,$limitcnt);
}
$cnt = sizeof($result);
for($i=0;$i<$cnt;$i++){
	$status = $result[$i]['status'];
	$main_group_id = $result[$i]['group_id'];
	$categoryid = $result[$i]['category_id'];
	$main_feature_group_name = $result[$i]['main_group_name'];
	if(!empty($categoryid)){
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
		if(strtolower($main_feature_group_name) == 'overview'){
			$overview_result = $compare->arrGetCompareOverview("","","","",$category_id);
			$overview_result_cnt = sizeof($overview_result);
			for($overview=0;$overview<$overview_result_cnt;$overview++){
				$unit_id = "";$feature_unit="";
				$feature_overview_id = $overview_result[$overview]['feature_id'];
				$feature_overview_result = $feature->arrGetFeatureDetails($feature_overview_id);
				$overview_result[$overview]['feature_name'] = $feature_overview_result[0]['feature_name'];
				$feature_description = $feature_overview_result[0]['feature_description'];
				$unit_id = $feature_overview_result[0]['unit_id'];
				if(!empty($unit_id)){
					$unit_result = $feature->arrFeatureUnitDetails($unit_id);
					$feature_unit = $unit_result[0]['unit_name'];
				}
				if($feature_description != ""){
					$feature_description = html_entity_decode($feature_description,ENT_QUOTES,'UTF-8');
					$feature_description = str_replace('&amp;amp;',"",$feature_description);
					$feature_description = str_replace('&#039;',"'",$feature_description);
					$feature_description = str_replace('#039;',"'",$feature_description);
				}
				$overview_result[$overview]['feature_description'] = $feature_description;
				foreach($productArr as $key => $product_id){
					if(!empty($product_id)){
						$product_result = $product->arrGetProductFeatureDetails("",$feature_overview_id,$product_id);
						//$overview_result[$overview]['product_feature'][$product_id]['product_feature_value'] = $product_result[0]['feature_value'];
						$feature_value = $product_result[0]['feature_value'];
						if(strtolower($feature_value) == 'yes'){
							$feature_value = 'yes';
						}else if(strtolower($feature_value) == 'no'){
							$feature_value = 'no';
						}
						$overview_result[$overview]['product_feature'][][$product_id]['product_feature_value'] =
					($feature_value!='-' && strtolower($feature_value)!='yes' && strtolower($feature_value)!='no')? implode(" ",array($feature_value,$feature_unit)) : $feature_value;

					}
				}
				$main_feature_group = $overview_result[$overview]['main_feature_group'];
				$feature_overview_result = $feature->arrGetFeatureMainGroupDetails($main_feature_group);
				$main_group_name = $feature_overview_result[0]['main_group_name'];
				$overviewresult[$main_feature_group][] = $overview_result[$overview];
				$overviewresult[$main_feature_group]['main_group_name'] = $main_group_name;
			}
		}
		$feature_result = $feature->arrGetFeatureDetails("",$category_id,$main_group_id,"","1");
		$featureCnt = sizeof($feature_result);
		for($j=0;$j<$featureCnt;$j++){
			$feature_group = $feature_result[$j]['feature_group'];
			$feature_sub_group_array = $feature->arrFeatureSubGroupDetails($feature_group,"","1");
			$sub_group_name = $feature_sub_group_array[0]['sub_group_name'];
			if(empty($sub_group_name)) continue;
			if($sub_group_name!="Segments"){
				$main_feature_group = $feature_result[$j]['main_feature_group'];
				$status = $feature_result[$j]['status'];
				$categoryid = $feature_result[$j]['category_id'];
				$feature_id = $feature_result[$j]['feature_id'];
				$unit_id = $feature_result[$j]['unit_id'];
				if(!empty($unit_id)){
					$unit_result = $feature->arrFeatureUnitDetails($unit_id,"","1");
					$feature_unit = $unit_result[0]['unit_name'];
				}
				if(!empty($feature_id)){
					$pivot_result = $pivot->arrGetPivotDetails("",$categoryid,$feature_id,"1");

					foreach($productArr as $key => $product_id){
						if(!empty($product_id)){
							$product_result = $product->arrGetProductFeatureDetails("",$feature_id,$product_id);
							if(array_key_exists($product_result[0]['feature_id'],$bodyStyleArr)) {
								$seoProductPivotArr['feature']['style-'][$product_id] = $bodyStyleArr[$product_result[0]['feature_id']];
							}
							if(array_key_exists($product_result[0]['feature_id'],$fuelTypeArr)) {
								$seoProductPivotArr['feature']['fuel-'][$product_id] = $fuelTypeArr[$product_result[0]['feature_id']];
							}
							$feature_value = $product_result[0]['feature_value'];
							if(strtolower($feature_value) == 'yes'){
								$feature_value = 'yes';
							}else if(strtolower($feature_value) == 'no'){
								$feature_value = 'no';
							}
							$feature_result[$j]['product_feature'][$key]['feature_product_id'] = $product_id;
							$feature_result[$j]['product_feature'][$key]['product_feature_id'] = $product_result[0]['feature_id'];
							$feature_result[$j]['product_feature'][$key]['product_feature_value'] = ($feature_value!='-' && strtolower($feature_value)!='yes' && strtolower($feature_value)!='no')? implode(" ",array($feature_value,$feature_unit)) : $feature_value;
							if(!in_array($feature_value,$same_feature_value[$product_result[0]['feature_id']])){
									$same_feature_value[$product_result[0]['feature_id']][] = $feature_value;
							}
						}
					}
					$count = count($same_feature_value[$feature_id]);
					if($count==1){
							$feature_result[$j]['same_feature_value'] = 1;
					}
				}

				$feature_unit = "";
				$pivot_feature_id = $pivot_result[0]['feature_id'];
				$feature_result[$j]['pivot_feature_id'] = $pivot_feature_id;
				$feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
				$feature_result[$j]['js_feature_group'] = $feature_result[$j]['feature_group'];
				$feature_result[$j]['js_feature_desc'] = $feature_result[$j]['feature_description'];

				$feature_result[$j]['js_feature_unit'] = $feature_unit;
				$feature_result[$j]['feature_status'] = ($status == 1) ? 'Active' : 'InActive';
				$feature_result[$j]['feature_unit'] = $feature_unit ? html_entity_decode($feature_unit,ENT_QUOTES,'UTF-8') : '';
				$feature_result[$j]['feature_group'] = $feature_result[$j]['feature_group'] ? html_entity_decode($feature_result[$j]['feature_group'],ENT_QUOTES,'UTF-8') : '';
				$feature_description = $feature_result[$j]['feature_description'];
				if($feature_description != ""){
					$feature_description = html_entity_decode($feature_description,ENT_QUOTES,'UTF-8');
					$feature_description = str_replace('&amp;amp;',"",$feature_description);
					$feature_description = str_replace('&#039;',"'",$feature_description);
					$feature_description = str_replace('#039;',"'",$feature_description);
				}
				$feature_result[$j]['feature_description'] = $feature_description ? html_entity_decode($feature_description,ENT_QUOTES,'UTF-8') : '';
				$feature_result[$j]['create_date'] = date('d-m-Y',strtotime($feature_result[$j]['create_date']));
				$feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
				$feature_result[$j]['feature_name'] = $feature_result[$j]['feature_name'] ? html_entity_decode($feature_result[$j]['feature_name'],ENT_QUOTES,'UTF-8') : '';

				$featureresult[$main_group_id][$feature_group][] = $feature_result[$j];
				$featureresult[$main_group_id][$feature_group]['sub_group_name'] = $feature_sub_group_array[0]['sub_group_name'];
				$featureresult[$main_group_id][$feature_group]['sub_group_id'] = $feature_group;
				$featureresult[$main_group_id][$feature_group]['pivot_feature_id'] = $pivot_feature_id;
				$featureresult[$main_group_id][$feature_group]['feature_id'] = $feature_id;
			}

		}
		foreach($result[$i] as $k=> $v){
			$featureresult[$main_group_id][$k] = $v;
		}
	}
}

function strGetCompareXML($featureresult,$rootnode="GROUP_MASTER"){
	$groupnodexml .= "<$rootnode>";
	if($featureresult){
		foreach($featureresult as $maingroupkey => $maingroupval){
			if(is_array($maingroupval)){
				$groupnodexml .= "<GROUP_MASTER_DATA>";
				foreach($maingroupval as $subgroupkey=>$subgroupval){
					if(is_array($subgroupval)){
						$groupnodexml .= "<SUB_GROUP_MASTER>";
							foreach($subgroupval as $key => $featuredata){
								if(is_array($featuredata)){
									$groupnodexml .= "<SUB_GROUP_MASTER_DATA>";
									$featuredata = array_change_key_case($featuredata,CASE_UPPER);
									foreach($featuredata as $featurekey => $featureval){
										if(is_array($featureval)){
											$groupnodexml .= "<PRODUCT_FEATURE_MASTER>";
											foreach($featureval as $productid=>$productfeatureArr){
												$groupnodexml .= "<PRODUCT_FEATURE_MASTER_DATA>";
												$productfeatureArr = array_change_key_case($productfeatureArr,CASE_UPPER);
												foreach($productfeatureArr as $product_feature_key => $product_feature_value){
													$groupnodexml .= "<$product_feature_key><![CDATA[$product_feature_value]]></$product_feature_key>";
												}
												$groupnodexml .= "</PRODUCT_FEATURE_MASTER_DATA>";
											}
											$groupnodexml .= "</PRODUCT_FEATURE_MASTER>";
										}else{
											$groupnodexml .= "<$featurekey><![CDATA[$featureval]]></$featurekey>";
										}
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
	$groupnodexml .= "</$rootnode>";
	return $groupnodexml;
}

$overviewxml .= "<OVERVIEW_MASTER>";
foreach($overviewresult as $maingroupkey => $maingroupval){
	if(is_array($maingroupval)){
		$overviewxml .= "<GROUP_MASTER_DATA>";
		foreach($maingroupval as $subgroupkey=>$subgroupval){
			if(is_array($subgroupval)){
				$overviewxml .= "<SUB_GROUP_MASTER>";
				foreach($subgroupval as $key => $featuredata){
					if(is_array($featuredata)){
						$overviewxml .= "<PRODUCT_FEATURE_MASTER>";
						$featuredata = array_change_key_case($featuredata,CASE_UPPER);
						foreach($featuredata as $featurekey => $featureval){
							if(is_array($featureval)){
								$featureval = array_change_key_case($featureval,CASE_UPPER);
								foreach($featureval as $productfeaturekey=>$productfeaturevalueArr){
									if(is_array($featureval)){
										$productfeaturevalueArr = array_change_key_case($productfeaturevalueArr,CASE_UPPER);
										foreach($productfeaturevalueArr as $product_feature_key => $product_feature_value){
											$overviewxml .= "<PRODUCT_FEATURE_MASTER_DATA>";
											$overviewxml .= "<$product_feature_key><![CDATA[$product_feature_value]]></$product_feature_key>";
											$overviewxml .= "<PRODUCT_ID><![CDATA[$productfeaturekey]]></PRODUCT_ID>";
											$overviewxml .= "</PRODUCT_FEATURE_MASTER_DATA>";
										}
									}
								}
							}else{
								$overviewxml .= "<$featurekey><![CDATA[$featureval]]></$featurekey>";
							}
						}
						$overviewxml .= "</PRODUCT_FEATURE_MASTER>";
					}else{
						$overviewxml .= "<".strtoupper($key)."><![CDATA[$featuredata]]></".strtoupper($key).">";
					}
				}
				$overviewxml .= "</SUB_GROUP_MASTER>";
			}else{
				$overviewxml .= "<".strtoupper($subgroupkey)."><![CDATA[$subgroupval]]></".strtoupper($subgroupkey).">";
			}
		}
		$overviewxml .= "</GROUP_MASTER_DATA>";
	}
}
$overviewxml .= "</OVERVIEW_MASTER>";

$xml .= strGetCompareXML($featureresult);
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
	$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
	$result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
	$result[$i]['js_brand_name'] = $result[$i]['brand_name'];
	$result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'],ENT_QUOTES,'UTF-8');
	if(in_array($result[$i]['brand_id'],$top_brand_arr)){
		$result[$i]['top_brand'] = 1;
	}else{
		$result[$i]['top_brand'] = 0;
	}
	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	$xml .= "<BRAND_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml .= "</BRAND_MASTER_DATA>";
}
$xml .= "</BRAND_MASTER>";

$config_details = get_config_details();
$tabQueryArr[] = SEO_COMPARE_URL;
if(!empty($productids)){
	//$tabQueryArr[] = "catid=$category_id";
	$tabQueryArr[] = "$category_id";
}
if(!empty($productids)){
	//$tabQueryArr[] = "productids=$productids";
	$tabQueryArr[] = "$productids";
}
if(sizeof($tabQueryArr)){
	$tabQueryStr = WEB_URL.implode("/",$tabQueryArr)."/";
}
$prcnt = sizeof($seoCompareProductArr);
$seo_compare_tag="";
for($i=0;$i<$prcnt;$i++){
	$seo_compare_tag.="compare ".$seoCompareProductArr[$i].",";
}
$seotitle = implode(" Vs ",$seoCompareProductArr);
$seotitle = ucfirst($seotitle);

$seoProductFeatureArr = array_unique($seoProductPivotArr['feature']['style-']);
$seoProductPriceArr = array_unique($seoProductPivotArr['price']);
sort($seoProductPriceArr,SORT_NUMERIC);
$seoMinPrice = $seoProductPriceArr[0];
$seoMinPrice = $seoMinPrice-($seoMinPrice*10/100);
$seoMinPrice = round($seoMinPrice);
$seoMaxPrice = array_pop($seoProductPriceArr);
$seoMaxPrice = $seoMaxPrice+($seoMaxPrice*10/100);
$seoMaxPrice = round($seoMaxPrice);
$seoProductPriceArr = getPriceBarValue($seoMinPrice, $seoMaxPrice);
$seoMinPrice = $seoProductPriceArr['min_converted_price'];
$seoMaxPrice = $seoProductPriceArr['max_converted_price'];
$seoMinPriceStr = 'Rs. '.$seoProductPriceArr['min_price_val'].' '.$seoProductPriceArr['min_price_unit'];
$seoMaxPriceStr = 'Rs. '.$seoProductPriceArr['max_price_val'].' '.$seoProductPriceArr['max_price_unit'];
unset($seoProductPriceArr);
$seoCarFinderArr = array(SEO_WEB_URL,SEO_CAR_FINDER);
if(!empty($seoMinPrice)){
	$seoProductPriceArr[] = $seoMinPrice;
	$seoProductPriceLinkArr[] = $seoMinPriceStr;
}
if(!empty($seoMaxPrice)){
	$seoProductPriceArr[] = $seoMaxPrice;
	$seoProductPriceLinkArr[] = $seoMaxPriceStr;
}
if(sizeof($seoProductBrandArr) > 0){
	$seoCarFinderArr[] = 'brand-'.implode('_',$seoProductBrandArr);
}
$bodyStyleCnt = sizeof($seoProductFeatureArr);
if($bodyStyleCnt > 0){
	$seoCarFinderArr[] = 'style-'.implode('_',$seoProductFeatureArr);
	if($bodyStyleCnt > 1){
		$lastkey = array_pop(array_keys($seoProductFeatureArr));
		$lastval = 'and '.$seoProductFeatureArr[$lastkey];
		unset($seoProductFeatureArr[$lastkey]);
	}
	$seoCarfinderLink[] = implode(", ",$seoProductFeatureArr);
	if(!empty($lastval)){
		$seoCarfinderLink[] = $lastval;
	}
}
if(sizeof($seoProductPriceArr) > 0){
	$seoCarFinderArr[] = 'price-'.implode('-',$seoProductPriceArr);
}
if(sizeof($seoProductFuelArr) > 0){
	$seoCarFinderArr[] = 'fuel-'.implode('_',$seoProductFuelArr);
}
if($bodyStyleCnt > 0){
	$seoCarfinderLink = 'Search for '.implode(' ',$seoCarfinderLink).' mobiles between '.implode(' - ',$seoProductPriceLinkArr);
	$carfinderUrl = implode("/",$seoCarFinderArr);
}
unset($seoCarFinderArr);unset($seoProductPriceLinkArr);
/*
$xml .= "<EACH_PRODUCT_CARFINDER>";
$productcnt = 0;
foreach($productArr as $key => $productid){
	if(!empty($productid)){
		$productcnt++;
		$xml .= "<EACH_PRODUCT_CARFINDER_DATA>";
		$seoCarFinderArr = array(SEO_WEB_URL,SEO_CAR_FINDER);
		$seoCarfinderLinkArr[] = 'Search for';
		$bodyStyle = $seoProductPivotArr['feature']['style-'][$productid];
		$seoCarfinderLinkArr[] = $bodyStyle;
		$seoCarfinderLinkArr[] = 'cars between';
		$seoCarFinderArr[] = !empty($bodyStyle) ? 'style-'.$bodyStyle : '';
		$price = $seoProductPivotArr['price'][$productid];
		$seoProductPriceArr = getPriceBarValue($price, $price);
		$seoMinPrice = $seoProductPriceArr['min_converted_price'];
		$seoMaxPrice = $seoProductPriceArr['max_converted_price'];
		$seoMinPriceStr = 'Rs. '.$seoProductPriceArr['min_price_val'].' '.$seoProductPriceArr['min_price_unit'];
		$seoMaxPriceStr = 'Rs. '.$seoProductPriceArr['max_price_val'].' '.$seoProductPriceArr['max_price_unit'];
		unset($seoProductPriceArr);
		if(!empty($seoMinPrice)){
			$seoProductPriceArr[] = $seoMinPrice;
			$seoProductPriceLinkArr[] = $seoMinPriceStr;
		}
		if(!empty($seoMaxPrice)){
			$seoProductPriceArr[] = $seoMaxPrice;
			$seoProductPriceLinkArr[] = $seoMaxPriceStr;
		}
		$seoCarfinderLinkArr[] = implode(' - ',$seoProductPriceLinkArr);
		$seoCarFinderArr[] = 'price-'.implode('-',$seoProductPriceArr);
		$xml .= "<CARFINDER_PAGE_URL><![CDATA[".implode("/",$seoCarFinderArr)."]]></CARFINDER_PAGE_URL>";
		$xml .= "<CARFINDER_PAGE_URL_LINK_NAME><![CDATA[".implode(" ",$seoCarfinderLinkArr)."]]></CARFINDER_PAGE_URL_LINK_NAME>";
		$xml .= "</EACH_PRODUCT_CARFINDER_DATA>";
		unset($seoProductPriceArr);unset($seoProductPriceLinkArr);unset($seoCarFinderArr);unset($seoCarfinderLinkArr);
	}
}
$xml .= "<COUNT><![CDATA[$productcnt]]></COUNT>";
$xml .= "</EACH_PRODUCT_CARFINDER>";
*/
$current_month = date(m);
if ($current_month == 1) {
	$month = 12;
	$year = date(y) - 1;
} else {
	$month = date(m) - 1;
	$year = date(y);
}

$fid=89;//hatchback body type as default for top selling mobile
if($compareProductCnt == 0){
	$seo_title = "Compare Mobiles - $seotitle On Mobiles India | Mobile Comparison by Price, Performance, Mobile Features & Specification on ".SEO_DOMAIN;
	$seo_desc = "Compare mobiles on ".SEO_DOMAIN." by comparing their price, features, technical specification and performance. Select minimum two and maximum four mobiles by their brand and model name.";
	$seo_tags = "mbile compiare, mobile compare, mobile compare, mobiles comparison, compare mobile by price, compare mobile by features, compare mobile by brands, compare mobiles at ".SEO_DOMAIN.", compare mobiles at on mobiles india";
	if($tab_id == 1){
	$display_heading = "Compare Mobiles";
	}else if($tab_id == 2){
	$display_heading = "Features Comparison";
	}else if($tab_id == 3){
	$display_heading = "Tech Specs Comparison";
	} 
}else{
	if($tab_id == 1){ //overview
		if($compareProductCnt > 1){
			$seo_title = "Compare $seotitle | Compare Mobiles at ".SEO_DOMAIN;
			$seo_desc = "Compare $seotitle - Mobile comparison by price, body style, performance, car features & specification at ".SEO_DOMAIN;
		}else{
			$seo_title = "Compare $seotitle with other mobiles | Compare mobiles at ".SEO_DOMAIN;
			$seo_desc = "Compare $seotitle with other mobiles- mobile comparison by price, body style, performance, mobile features & specification at ".SEO_DOMAIN;
		}
		$seo_tags = "Compare mobiles, $seo_compare_tag compare mobile features, compare mobile specifications, compare mobile prices, mobile comparison, compare mobile prices in india";
		$display_heading = "Compare $seotitle By Features & Specification";
	}else if($tab_id == 2){ //Features
		if($compareProductCnt > 1){
			$seo_title = "Compare $seotitle | Compare mobile Features at ".SEO_DOMAIN;
			$seo_desc = "Compare $seotitle by mobile features - mobile comparison by price, body style, performance, mobile features & specification at ".SEO_DOMAIN;
		}else{
			$seo_title = "Compare $seotitle with other mobiles | Compare mobile Features at ".SEO_DOMAIN;
			$seo_desc = "Compare $seotitle with other mobiles by mobile features - mobile comparison by price, body style, performance, mobile features & specification at ".SEO_DOMAIN;
		}
		$seo_tags = "Compare mobiles, $seo_compare_tag compare mobile features, mobile features comparison, compare mobile prices, mobile comparison, compare mobile prices in India";
		$display_heading = "Mobiles Features Comparison - $seotitle";
	}else if($tab_id == 3){//Tech Specs
		if($compareProductCnt > 1){
			$seo_title = "Compare $seotitle | Compare mobile Specifications at ".SEO_DOMAIN;
			$seo_desc = "Compare $seotitle by tech specifications - mobile comparison by price, body style, performance, mobile features & specification at ".SEO_DOMAIN;
		}else{
			$seo_title = "Compare $seotitle with other mobiles | Compare mobile Specifications at ".SEO_DOMAIN;
			$seo_desc = "Compare $seotitle with other mobiles by tech specifications - mobile comparison by price, body style, performance, mobile features & specification at ".SEO_DOMAIN;
		}
		$seo_tags = "Compare mobiles, $seo_compare_tag mobile specifications comparison, compare mobiles by specifications, compare mobile prices, mobile comparison, compare mobile prices in india";
		$display_heading = "mobile Tech Specification Comparison - $seotitle";
	}  
}

/*
if(!empty($seotitle)){
	$breadcrumb = CATEGORY_HOME."$seotitle";
}else{
	$breadcrumb = CATEGORY_HOME."Compare mobiles";
}
*/
$seo_desc = "<meta name=\"Description\" content=\"$seo_desc\" />";
$seo_tags = "<meta name=\"Keywords\" content=\"$seo_tags\" />";
$breadcrumb = carCompareBreadCrumb($seotitle);

$recent_view_car = $_COOKIE['recentView'];
if(!empty($recent_view_car)){
	$array_recent_view_car = explode(',',$recent_view_car);
	$arr_recent_view_car = array_unique($array_recent_view_car);
	if (($key = array_search($first_product, $arr_recent_view_car)) !== false) {
	    unset($arr_recent_view_car[$key]);
	}
	$recent_view_car='';
	if(!empty($arr_recent_view_car)){
        if(!empty($first_product)){
        	$a_recent_view_car = array_splice($arr_recent_view_car,0,3);
        	$recent_view_car = implode(',',$a_recent_view_car);
        }else{
        	$a_recent_view_car = array_splice($arr_recent_view_car,0,4);
			$recent_view_car = implode(',',$a_recent_view_car);
		}
	}
}
$modelnameset = implode('|',$modelnamesetArr);
$productnameset = implode(',',$productnamesetArr);
// car compare

$search_key = $first_brd_name." ".$first_prd_name;
#$search_key = $sModelBrandName." ".$search_product_info_name;
if(!empty($first_prd_name)){
	$top_comp_search = $product->topSearchComparisons($search_key,$first_prd_name,"variant");
}

$login_details = getCookie();
$strXML = "<XML>";
$strXML .= $login_details;
$strXML .= getComponents('COMPARE', getComponentParams(array("search_key"=>$search_key))); // components xml
$strXML .= "<CARFINDER_PAGE_URL><![CDATA[$carfinderUrl]]></CARFINDER_PAGE_URL>";
$strXML .= "<CARFINDER_PAGE_URL_LINK_NAME><![CDATA[$seoCarfinderLink]]></CARFINDER_PAGE_URL_LINK_NAME>";
$strXML .= "<IS_MOST_POPULAR_SET><![CDATA[$ismostpopular]]></IS_MOST_POPULAR_SET>";
$strXML .= "<DISPLAY_HEADING><![CDATA[$display_heading]]></DISPLAY_HEADING>";
$strXML .= "<NO_OF_COMPARISONS><![CDATA[$compareProductCnt]]></NO_OF_COMPARISONS>";
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<SUB_TOP_TITLE><![CDATA[$strsubtitle]]></SUB_TOP_TITLE>";
$strXML .= "<BREAD_CRUMB><![CDATA[$breadcrumb]]></BREAD_CRUMB>";
$strXML .= "<SEO_DESC><![CDATA[".html_entity_decode($seo_desc ,ENT_QUOTES,"UTF-8")."]]></SEO_DESC>";
$strXML .= "<SEO_TAGS><![CDATA[".html_entity_decode($seo_tags ,ENT_QUOTES,"UTF-8")."]]></SEO_TAGS>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<SELECTEDTABID><![CDATA[$tab_id]]></SELECTEDTABID>";
$strXML .= "<COMPARE_PRODUCT_SET><![CDATA[$productnameset]]></COMPARE_PRODUCT_SET>";
$strXML .= "<COMPARE_MODEL_NAME_SET><![CDATA[$modelnameset]]></COMPARE_MODEL_NAME_SET>";
$strXML .= "<TAB_QUERY_STR><![CDATA[$tabQueryStr]]></TAB_QUERY_STR>";
$strXML .= "<SEO_COMPARE_URL><![CDATA[".SEO_COMPARE_URL."]]></SEO_COMPARE_URL>";
$strXML .= "<FEATURE_YES_IMAGE_URL><![CDATA[".FEATURE_YES_IMAGE_URL."]]></FEATURE_YES_IMAGE_URL>";
$strXML .= "<FEATURE_NO_IMAGE_URL><![CDATA[".FEATURE_NO_IMAGE_URL."]]></FEATURE_NO_IMAGE_URL>";
$strXML .= $overviewxml;
$strXML .= $config_details;
$strXML .= $comparesetxml;
$strXML .= $productxml;
$strXML .= $xml;
$strXML .= $strproductmodxml;
$strXML .= $ratexml;
$strXML .= "<PAGE_NAME>".$_SERVER['SCRIPT_URI']."</PAGE_NAME>";
$strXML .= "<DEFAULT_BRAND_ID><![CDATA[$default_car_brand]]></DEFAULT_BRAND_ID>";
$strXML .= "<DEFAULT_CAR_ID><![CDATA[$default_car]]></DEFAULT_CAR_ID>";
$strXML .= "<DEFAULT_MODEL_ID><![CDATA[$default_car_model]]></DEFAULT_MODEL_ID>";
$strXML .= "<RECENT_VIEW_CAR><![CDATA[$recent_view_car]]></RECENT_VIEW_CAR>";
$strXML .= "<OC_ROS_BOTTOM_NORTH_728x90><![CDATA[OC_ROS_Bottom_North_728x90]]></OC_ROS_BOTTOM_NORTH_728x90>";
$strXML .= "<OC_ROS_TOP_RHS_LREC_300x250_1><![CDATA[OC_ROS_Top_RHS_Lrec_300x250_1]]></OC_ROS_TOP_RHS_LREC_300x250_1>";
$strXML .= "<OC_COMPARECARS_RIGHT_TOP_300X250><![CDATA[OC_CompareCars_Right_Top_300x250]]></OC_COMPARECARS_RIGHT_TOP_300X250>";
$strXML .= "<OC_COMPARECARS_BOTTOM_728X90><![CDATA[OC_CompareCars_Bottom_728x90]]></OC_COMPARECARS_BOTTOM_728X90>";
$strXML .= "<OC_ComapreCars_Bottom_North_728x90_1><![CDATA[OC_ComapreCars_Bottom_North_728x90_1]]></OC_ComapreCars_Bottom_North_728x90_1>";
$strXML .= $generated_format;
$strXML .= $top_comp_search;
$strXML .= "<CAT_PATH><![CDATA[".$_REQUEST['cat_path']."]]></CAT_PATH>";
$strXML .= "<SELECTED_CATEGORY_NAME><![CDATA[".$_REQUEST['category_name']."]]></SELECTED_CATEGORY_NAME>";
$strXML .= "</XML>";
$strXML = mb_convert_encoding($strXML, "UTF-8");

//header('Content-type: text/xml');echo $strXML;exit;
if($_REQUEST['debug']==1){
	header('Content-type: text/xml');echo $strXML;exit;
}

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;    $xslt->registerPHPFunctions();
$xsl = DOMDocument::load('xsl/compare_cars_details.xsl');
$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
