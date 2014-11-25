<?php
require_once('./include/config.php');
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'memcache.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH.'product.class.php');

$dbconn = new DbConn();
$category = new CategoryManagement;
$brand = new BrandManagement;
$product = new ProductManagement;

$category_id = $_REQUEST['category_id'];
$category_name = $_REQUEST['category_name'];
$cat_path = $_REQUEST['cat_path'];

unset($result);
$result = $product->arrGetNewArrivalProductDetails("","",$category_id,"","",$startlimit,$limitcnt);
#print_r($result);die();
//print"<pre>"; print_r($result); print"</pre>";
$cnt = sizeof($result);
	$position_count=$cnt+1;
	$xml = "<NEW_ARRIVALS_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
			$category_seo_path = $category_result[0]['seo_path']; 
		}
		$brand_id = $result[$i]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
			$brand_seo_path = $brand_result[0]['seo_path'];
		}
		$result[$i]['js_brand_name'] = $brand_name;
		$result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : 'Nil';
		$model_id = $result[$i]['product_info_id'];
		if(!empty($model_id)){
			$product_result =$product->arrGetProductNameInfo($model_id);
			$product_name1 = $product_result[0][product_info_name];
		}
		$product_id = $result[$i]['product_id'];
		$product_names = array();
		if(!empty($product_id)){
			$product_result = $product->arrGetProductDetails($product_id,$category_id);
			//print_r($product_result);
			$product_names[] = $product_result[0]['product_name'];
			$product_names[] = $product_result[0]['variant'];
			$product_name = implode(" ",$product_names);
			$media_path = $product_result[0]['image_path'];
			$product_seo_path = $product_result[0]['seo_path'];
			$variant_value = $product_result[0]['variant_value'];
			$variant_price = $variant_value ? priceFormat($variant_value) : '';
			if(empty($product_seo_path)){
				$product_seo_path = constructurl(trim($product_name));
			}
		}
		
		/*$video_path = str_replace(array(CENTRAL_IMAGE_URL,CENTRAL_MEDIA_URL),"",$video_path);
		if(!empty($video_path)){
			$result[$i]['video_path'] = CENTRAL_MEDIA_URL.$video_path;
		}*/
		$image_path = str_replace(array(CENTRAL_IMAGE_URL,CENTRAL_MEDIA_URL),"",$media_path);
		if(!empty($image_path)){
			$image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize);
			$result[$i]['image_path'] = CENTRAL_IMAGE_URL.$image_path;
		}
		$result[$i]['js_product_name'] =$product_name;
		$result[$i]['variant_price'] =$variant_price;
		$result[$i]['product_name'] = $product_name ? html_entity_decode($product_name,ENT_QUOTES) : 'Nil';
		$result[$i]['product_status'] = ($status == 1) ? 'Active' : 'InActive';
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$variantnameSeoArr[] = SEO_WEB_URL;	
        $variantnameSeoArr[] = $category_seo_path;	
		$variantnameSeoArr[] = $brand_seo_path;
		$variantnameSeoArr[] = $product_seo_path;
		$result[$i]['seo_url'] = implode("/",$variantnameSeoArr);
		unset($variantnameSeoArr);
		$result[$i]['product_display_name'] = implode(" ",array($brand_name,$product_name));
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		//print_r($result[$i]);
		$xml .= "<NEW_ARRIVALS_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</NEW_ARRIVALS_MASTER_DATA>";
	}
	$xml .= "</NEW_ARRIVALS_MASTER>";

//die();

$seo_title = "Car Brands In India | Car Manufacturers in India | All Car Models & Variants in India at ".SEO_DOMAIN;
$seo_desc = "Car Brands In India - Get the details of all car brands with their models & variants, car prices in India, car reviews, car photos & videos at ".SEO_DOMAIN;
$seo_tags = "Car brands, auto brands, car manufacturers, Car brands in India, auto brands in india, car manufacturers in India, car prices in india, car video reviews";


$new_breadcrumb .= brandsListBreadCrumb();

$config_details = get_config_details();
$login_details = getCookie();
$strXML  = "<XML>";
$strXML .= $login_details;
$strXML .= "<PAGE_NAME><![CDATA[".$_SERVER['SCRIPT_URI']."]]></PAGE_NAME>";
$strXML .= "<SEO_CARFINDER_COMPARE_URL><![CDATA[".WEB_URL.SEO_COMPARE_URL."]]></SEO_CARFINDER_COMPARE_URL>";
$strXML .= "<SEO_CAR_FINDER><![CDATA[".SEO_CAR_FINDER."]]></SEO_CAR_FINDER>";
$strXML .= "<SEO_JS><![CDATA[$seo_js]]></SEO_JS>";
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<SUB_TITLE><![CDATA[$sub_title]]></SUB_TITLE>";
$strXML .= "<BREAD_CRUMB><![CDATA[$new_breadcrumb]]></BREAD_CRUMB>";
$seo_desc = "<meta name=\"Description\" content=\"$seo_desc\" />";
$seo_tags = "<meta name=\"Keywords\" content=\"$seo_tags\" />";
$strXML .= "<SEO_DESC><![CDATA[".html_entity_decode($seo_desc ,ENT_QUOTES,"UTF-8")."]]></SEO_DESC>";
$strXML .= "<SEO_TAGS><![CDATA[".html_entity_decode($seo_tags ,ENT_QUOTES,"UTF-8")."]]></SEO_TAGS>";
$strXML .= "<SEO_URL><![CDATA[$seo_url]]></SEO_URL>";
$strXML .= "<STARTLIMIT><![CDATA[$offset]]></STARTLIMIT>";
$strXML .= "<PAGE_OFFSET><![CDATA[".OFFSET."]]></PAGE_OFFSET>";
$strXML .= "<CNT><![CDATA[$numpages]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<SELECTED_CATEGORY_NAME><![CDATA[$category_name]]></SELECTED_CATEGORY_NAME>";
$strXML .= "<SELECTED_CATEGORY_PATH><![CDATA[$cat_path]]></SELECTED_CATEGORY_PATH>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "<OC_CARBRANDS_TOP_960X50><![CDATA[OC_CarBrands_Top_960x50]]></OC_CARBRANDS_TOP_960X50>";
$strXML .= "<OC_CARBRANDS_RIGHT_TOP_300X250><![CDATA[OC_CarBrands_Right_Top_300x250]]></OC_CARBRANDS_RIGHT_TOP_300X250>";
$strXML .= "<OC_CARBRANDS_RIGHT_MIDDLE_300X110><![CDATA[OC_CarBrands_Right_Middle_300x110]]></OC_CARBRANDS_RIGHT_MIDDLE_300X110>";
$strXML .= "<OC_CARBRANDS_MIDDLE_LHS_300X110_1><![CDATA[OC_CarBrands_Middle_LHS_300x110_1]]></OC_CARBRANDS_MIDDLE_LHS_300X110_1>";
$strXML .= "<OC_CARBRANDS_MIDDLE_RHS_300X110_2><![CDATA[OC_CarBrands_Middle_RHS_300x110_2]]></OC_CARBRANDS_MIDDLE_RHS_300X110_2>";
$strXML .= "<OC_RIGHT_BOTTOM_300X250><![CDATA[OC_Right_Bottom_300x250]]></OC_RIGHT_BOTTOM_300X250>";
$strXML .= "</XML>";

$html = render_html('xsl/new_arrivals.xsl',$strXML,$_REQUEST['debug']);
