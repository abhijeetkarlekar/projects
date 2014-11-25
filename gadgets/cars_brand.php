<?php
require_once('./include/config.php');
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'memcache.class.php');
require_once(CLASSPATH . 'brand.class.php');


$dbconn = new DbConn();
$brand = new BrandManagement;
//print_r($_REQUEST); //DIE();

$category_id = $_REQUEST['category_id'];
$category_name = $_REQUEST['category_name'];
$cat_path = $_REQUEST['cat_path'];

unset($result);
$result = $brand->arrGetBrandDetails("",$category_id,"1","","","");

unset($bBrandArr1); unset($bBrandArr2);

foreach($result as $bkry=>$bValue){
        if(in_array($bValue['brand_id'],$top_brand_arr)){
                $set_key = array_search($bValue['brand_id'], $top_brand_arr);
                $bBrandArr1[$set_key] = $bValue;
        }else{
                $bBrandArr2[] = $bValue;
        }
}

unset($result);
ksort($bBrandArr1);

if(is_array($bBrandArr1) && is_array($bBrandArr2)){
	$result = array_merge($bBrandArr1,$bBrandArr2);
}else{
        $result = $bBrandArr2;
}

#print_r($result);die();
//print"<pre>";print_r($result);print"</pre>";
$count = sizeof($result);

$xml = "<BRAND_MASTER>";
$xml .= "<COUNT><![CDATA[$count]]></COUNT>";
for($i=0;$i<$count;$i++){
	$brand_id = $result[$i]["brand_id"];
	$brand_name = $result[$i]["brand_name"];
	$brand_image = $result[$i]["brand_image"];
	$short_desc = $result[$i]["brand_desc"];

	$brand_name = html_entity_decode($brand_name , ENT_QUOTES,'UTF-8');
	$short_desc = html_entity_decode($short_desc , ENT_QUOTES,'UTF-8');
	if($brand_image != ""){
		$brand_image = IMAGE_URL.$brand_image;
		$result[$i]["image_path"] = $brand_image;
	}
	$result[$i]["brand_name"] = $brand_name;
	$result[$i]["short_desc"] = $short_desc;

	$result[$i]["brand_url"] = WEB_URL."$cat_path/".constructUrl($brand_name);

	#$model_result = $product->arrGetProductNameInfo("","",$brand_id,"","1");
	#$model_result = $product->arrGetProductNameInfoCnt("",$category_id,$brand_id,"","1","","","","1","","0");
	$model_count = $model_result[0]['cnt'];

	$result[$i]["model_count"] = $model_count;

	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
        $xml .= "<BRAND_MASTER_DATA>";
        foreach($result[$i] as $k=>$v){
                $xml .= "<$k><![CDATA[".html_entity_decode($v,ENT_QUOTES,'UTF-8')."]]></$k>";
        }
        $xml .= "</BRAND_MASTER_DATA>";

}
$xml.= "</BRAND_MASTER>";

unset($result);
$result = $brand->arrGetBrandDetails("",$category_id,"1","","");
$brand_cnt = sizeof($result);
$xml .= "<BRAND_MASTER_DETAIL>";
for($i=0;$i<$brand_cnt;$i++){
	if(in_array($result[$i]['brand_id'],$top_brand_arr)){
        	$result[$i]['top_brand'] = 1;
        }else{
		$result[$i]['top_brand'] = 0;
	}
        $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	//print "<pre>"; print_r($result[$i]);
        $xml .= "<BRAND_MASTER_DETAIL_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
        }
	$xml .= "</BRAND_MASTER_DETAIL_DATA>";
}
$xml .= "</BRAND_MASTER_DETAIL>";

$seo_title = "Car Brands In India | Car Manufacturers in India | All Car Models & Variants in India at ".SEO_DOMAIN;
$seo_desc = "Car Brands In India - Get the details of all car brands with their models & variants, car prices in India, car reviews, car photos & videos at ".SEO_DOMAIN;
$seo_tags = "Car brands, auto brands, car manufacturers, Car brands in India, auto brands in india, car manufacturers in India, car prices in india, car video reviews";


$new_breadcrumb .= brandsListBreadCrumb();

$config_details = get_config_details();
$login_details = getCookie();
$strXML  = "<XML>";
$strXML .= $login_details;
$strXML .= getComponents('MOBILE_BRANDS', getComponentParams()); // components xml
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
/*
header('Content-type: text/xml');
echo $strXML;
exit;
*/
$html = render_html('xsl/cars_brand.xsl',$strXML,$_REQUEST['debug']);
