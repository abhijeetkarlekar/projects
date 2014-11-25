<?php
require_once('./include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'pager.class.php');
require_once(CLASSPATH.'videos.class.php');
require_once(CLASSPATH.'Utility.php');
require_once(CLASSPATH.'wallpaper.class.php');
require_once(CLASSPATH.'pivot.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'pager.class.php');


$dbconn = new DbConn;
$oBrand = new BrandManagement;
$category = new CategoryManagement;
$oProduct = new ProductManagement;
$videoGallery = new videos();
$oWallpapers =new Wallpapers;
$pivot = new PivotManagement;
$oFeature = new FeatureManagement;
$oPager = new Pager();

$category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : SITE_CATEGORY_ID;
$cat_path = $_REQUEST['cat_path'] ? $_REQUEST['cat_path'] : SITE_CATEGORY_PATH;
if(!empty($category_id)){
    $category_result = $category->arrGetCategoryDetails($category_id);
    $category_id = $category_result[0]['category_id'];
    $category_name = $category_result[0]['category_name'];
    $cat_path = $category_result[0]['seo_path'];
}

$request_uri = urldecode($_SERVER['REQUEST_URI']);
$pgpos = strpos($request_uri,'page');
if($pgpos > 0){ 
    $curpagenums = explode("page/",$request_uri);
    $curpagenum = $curpagenums[1];
    $currpageurl  = $curpagenums[0];
}else{
    $currpageurl  =$request_uri."/";
}

$arequest_uris = explode("/upcoming-mobiles/",$request_uri);
if($arequest_uris['1']!=''){
    $arrvalues = explode("/",$arequest_uris['1']);
}
if($arrvalues['0']!=''){
    $first_value = $arrvalues['0'];
}
if($arrvalues['1']!=''){
    $sec_value = $arrvalues['1'];
}
if($arrvalues['2']!=''){
    $third_value = $arrvalues['2'];
}
$arr_duration = array("next-1-month"=>"1month","next-3-month"=>"3months","next-6-month"=>"6months","2013"=>"thisyear","2014"=>"nextyear"); 
$duration_exp = "/launches\-([^\/]+)/";
if(preg_match_all($duration_exp,$request_uri,$matches,PREG_SET_ORDER)){
    $durnamesArr = explode("_",$matches[0][1]);
    if (array_key_exists($durnamesArr[0], $arr_duration)) { 
        $selected_duration = $arr_duration[$durnamesArr['0']];
    }      
}
$arr_body_style = array("basic-phone"=>"81","feature-phone"=>"80","smart-phone"=>"79","tablet"=>"78");
if(!empty($first_value)){
    if (array_key_exists($first_value, $arr_body_style)) { 
        $selected_body = $arr_body_style[$first_value];
    }  
}
if(!empty($sec_value)){
    if (array_key_exists($sec_value, $arr_body_style)) { 
        $selected_body = $arr_body_style[$sec_value];
    }  
}

if(!empty($first_value)){
    $chk_name = $first_value;
    $result = $oBrand->arrGetBrandName($category_id,$chk_name);
        if(!empty($result)){
            $selected_brand_id = $result[0]['brand_id'];
        }
}
unset($result);

$selected_brand_id = !empty($_REQUEST['Brand']) ? $_REQUEST['Brand'] : $selected_brand_id;
$selected_feature_id = !empty($_REQUEST['Feature']) ? $_REQUEST['Feature'] : $selected_body;
$selected_duration = !empty($_REQUEST['Duration']) ? $_REQUEST['Duration'] : $selected_duration;

$startlimit = $_REQUEST['startlimit'];
$endlimit = $_REQUEST['cnt'];
// Start Pagination constants.
define("PERPAGE", 10);
define("OFFSET", "pageno");
define("STARTPAGESHOWN",10);
define("MAXPAGESHOWN",10);
$offset = $_REQUEST[OFFSET] ? $_REQUEST[OFFSET] : 1;
if(!isset($offset)){
    $totaloffset = 0;
}else{
    $totaloffset = $offset * PERPAGE;
    $totalrecords = $totaloffset;
}
$pagelimitArr = arrGetPageLimit($offset,PERPAGE);
if(empty($startlimit)){
    $startlimit = $pagelimitArr['startlimit'];
}
if(empty($endlimit)){
    $endlimit = $pagelimitArr['recordperpage'];
}
$brand_arr =Array();
$feature_id_arr =Array();
if(!empty($selected_brand_id)){
    unset($brandresult);
    $brandresult = $oBrand->arrGetBrandDetails($selected_brand_id,"","1","","","","","","");
    $selected_brand_name = $brandresult[0]['brand_name'];
}
if(!empty($selected_feature_id)){
    unset($feature_result);
    $feature_result = $oFeature->arrGetFeatureDetails($selected_feature_id,$category_id,"","","1");
    $selected_feature_name = $feature_result[0]['feature_name'];
}
unset($result);

$result = $oProduct->arrSearchUpComingProductDetails("","","","","","",$category_id,"",'1',"","","ORDER BY start_date ASC");
$cnt = sizeof($result);
for($i=0;$i<$cnt;$i++){
    $model_id = $result[$i]['product_name_id'];
    unset($model_res);
    $model_res = $oProduct->arrGetProductNameInfo($model_id,$category_id,"","","","","","","","","","","");
    $brand_id = $model_res[0]['brand_id'];
    if(!empty($brand_id)){
        if(!in_array($brand_id,$brand_arr)){
            array_push($brand_arr,$brand_id);
        }
    }
    $feature_id = $result[$i]['feature_id'];
    unset($feature_result);
    $feature_result = $oFeature->arrGetFeatureDetails($feature_id,$category_id,"","","1");
    $feature_name = $feature_result[0]['feature_name'];
    $result[$i]['feature_name'] = $feature_name;
    if(!in_array($feature_id,$feature_id_arr)){
        array_push($feature_id_arr,$feature_id);
    }
}
unset($result);
$result = $oProduct->arrSearchUpComingProductDetailsCnt("","",$selected_brand_id,$selected_feature_id,"","",$category_id,$selected_duration,'1');
$totalcount = $result[0]['cnt'] ?  $result[0]['cnt'] : 0;
// paging
$endlimit = empty($curpagenum) ? 3 : 3;
$oPager = new Pager();
$startlimit = $oPager->findStart($limit);
$pages = ceil($totalcount/3);
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
unset($result);
$result = $oProduct->arrSearchUpComingProductDetails("","",$selected_brand_id,$selected_feature_id,"","",$category_id,$selected_duration,'1',$startlimit,$endlimit,"ORDER BY start_date ASC");
$pageurl = $_SERVER['SCRIPT_URI'];
$pagename = $pageurl;
//start code for pagination.
$queryStr = $_SERVER['QUERY_STRING'];
$cnt = sizeof($result);
$xml .="<UPCOMING_PRODUCT_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
    $upcoming_product_id = $result[$i]['upcoming_product_id'];
    $feature_id = $result[$i]['feature_id'];
    unset($feature_result);
    $feature_result = $oFeature->arrGetFeatureDetails($feature_id,$category_id,"","","1");
    $feature_name = $feature_result[0]['feature_name'];
    $result[$i]['feature_name'] = $feature_name;
    $model_id = $result[$i]['product_name_id'];
    $media_path = $result[$i]["image_path"];
    if(!empty($media_path)){
        $media_path = resizeImagePath($media_path,"145X193",$aModuleImageResize);
        $media_path = CENTRAL_IMAGE_URL.$media_path;
    }
    $result[$i]['image_path'] = $media_path ;
    $product_info_name = $result[$i]["product_info_name"];
    $brand_id = $result[$i]['brand_id'];
    if(!empty($brand_id)){
        $brandresult = $oBrand->arrGetBrandDetails($brand_id,"","1","","","","","","");
    }
    $brand_name = $brandresult[0]['brand_name'];
    $result[$i]['product_name'] = $brand_name." ".$product_info_name;
    unset($modelnameSeoArr);
    $modelnameSeoArr[] = SEO_WEB_URL;
    $modelnameSeoArr[] =  $cat_path;
    $modelnameSeoArr[] = constructUrl($brand_name);
    $modelnameSeoArr[] = constructUrl($product_info_name);
    $seo_model_url =  implode("/",$modelnameSeoArr);
    $result[$i]['seo_model_url'] = $seo_model_url;
    //$expected_price = $result[$i]['expected_price'] ? priceFormat($result[$i]['expected_price']) : "";
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
    $expected_date_text = $result[$i]['expected_date_text'];
    $expected_month = $result[$i]['expected_month'];
    $expected_year = $result[$i]['expected_year'];
    $short_description = html_entity_decode($result[$i]['short_description'],ENT_QUOTES,'UTF-8');
    $short_description = removeSlashes($short_description);
    if(strlen($short_description)>= 250){ $short_description = getCompactString($short_description, 250).' ...'; }
    $result[$i]['short_description'] = $short_description;
    /* photo and video count */
 /*   unset($p_res);
    $p_res = $oWallpapers->arrSlideShowDetails("","","",$model_id,"",$category_id,"","1",'','','','',1);
    $photo_cnt = sizeof($p_res);
    $result[$i]['photo_cnt'] = $photo_cnt;
    unset($v_res);
    $v_res = $oProduct->arrGetUploadUpcomingMediaDetails($upcoming_product_id); 
    $video_cnt = sizeof($v_res);
    $result[$i]['video_cnt'] = $video_cnt;
   
    unset($seoTitleArr);
    $seoTitleArr[] = SEO_WEB_URL;
    $modelnameSeoArr[] =  $cat_path;
    $seoTitleArr[] = constructUrl($brand_name);
    $seoTitleArr[] = constructUrl($brand_name).'-'.constructUrl($product_info_name);
    $seoTitleArr[] = "Model-Exterior";
    $seoTitleArr[] = constructUrl($product_info_name);
    $seoTitleArr[] = $model_id;
    $seoTitleArr[] = "1"; //"interior"
    $seo_photo_tab_url= implode("/",$seoTitleArr);
    $result[$i]['seo_photo_tab_url'] = $seo_photo_tab_url;
    unset($seoTitleArr);
    $seoTitleArr[] = SEO_WEB_URL;
    $seoTitleArr[] = constructUrl($brand_name)."-cars";
    $seoTitleArr[] = constructUrl($brand_name).'-'.constructUrl($product_info_name);
    $seoTitleArr[] = "Model-Videos";
    $seoTitleArr[] = constructUrl($product_info_name);
    $seoTitleArr[] = $model_id;
    $seoTitleArr[] = "3"; //"interior"
    $seo_video_tab_url= implode("/",$seoTitleArr);
    $result[$i]['seo_video_tab_url'] = $seo_video_tab_url;*/
    $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
    //print"<pre>";print_r($result[$i]);print"</pre>";
    $xml .= "<UPCOMING_PRODUCT_MASTER_DATA>";
    foreach($result[$i] as $k=>$v){
        $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $xml .= "</UPCOMING_PRODUCT_MASTER_DATA>";
    }
$xml .="</UPCOMING_PRODUCT_MASTER>";

$brand_arr_cnt = sizeof($brand_arr);
$brandresult = $oBrand->arrGetBrandDetails($brand_arr,"","1","","","","","","");
unset($brand_arr);
foreach($brandresult as $bkey=>$bValue){
    $brandsArr[$bValue['brand_name']] = $bValue;
}
ksort($brandsArr);
foreach($brandsArr as $brkey=>$brValue){
    $brand_arr[] = $brValue;
}
$xml .="<BRAND_LIST>";
$xml .= "<COUNT><![CDATA[$brand_arr_cnt]]></COUNT>";
for($i=0;$i<$brand_arr_cnt;$i++){
    $brand_id = $brand_arr[$i]['brand_id'];
    $brand_name = $brand_arr[$i]['brand_name'];
    $xml .= "<BRAND_LIST_DATA>";
    $xml .= "<BRAND_ID>".$brand_id."</BRAND_ID>";
    $xml .= "<BRAND_NAME>".$brand_name."</BRAND_NAME>";
    $xml .= "</BRAND_LIST_DATA>";
}
$xml .="</BRAND_LIST>";

$feature_arr_cnt = sizeof($feature_id_arr);
$xml .="<BODY_STYLE_LIST>";
$xml .= "<COUNT><![CDATA[$feature_arr_cnt]]></COUNT>";
for($i=0;$i<$feature_arr_cnt;$i++){
    unset($feature_result);
    $feature_id = $feature_id_arr[$i];
    $feature_result = $oFeature->arrGetFeatureDetails($feature_id,$category_id,"","","1");
    //print"<pre>";print_r($feature_result);print"</pre>";
    $feature_name = $feature_result[0]['feature_name'];
    $xml .= "<BODY_STYLE_LIST_DATA>";
    $xml .= "<FEATURE_ID>".$feature_id."</FEATURE_ID>";
    $xml .= "<FEATURE_NAME>".$feature_name."</FEATURE_NAME>";
    $xml .= "</BODY_STYLE_LIST_DATA>";
}
$xml .="</BODY_STYLE_LIST>";

//Start of SEO details   
unset($seoArr);
unset($seoKeyArr);
$this_year = date("Y");
$next_year = date("Y", strtotime('next year'));
$next_month = date('F', strtotime('next month'));
$next_month_year = date('Y', strtotime('next month'));
$next_3months = date('F', strtotime('+3 months'));
$next_3months_year = date('Y', strtotime('+3 months'));
$next_6months = date('F', strtotime('+6 months'));
$next_6months_year = date('Y', strtotime('+6 months'));

if(($selected_brand_id != "") && ($selected_feature_id != "") && ($selected_duration != "")){
if($selected_duration == "1month"){
//URL: www.onMobiles.in/upcoming-Mobiles/Honda/sedan/launches-next-1-month
$seoArr[] = "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India - ".$next_month.", ".$next_month_year;
$seoArr[] = "Upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month.", ".$next_month_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India - ".$next_month.", ".$next_month_year.":  Find out upcoming ".$selected_brand_name." ".$selected_feature_name." Mobile launches in ".$next_month.", ".$next_month_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches , speculated launches, futuristic models";       
}elseif($selected_duration == "3months"){
//URL: www.onMobiles.in/upcoming-Mobiles/Honda/sedan/launches-next-3-month
$seoArr[] = "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoArr[] = "Upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;

$seo_desc =  "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year.":  Find out upcoming ".$selected_brand_name." ".$selected_feature_name." Mobile launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches , speculated launches, futuristic models";
}elseif($selected_duration == "6months"){
//URL: www.onMobiles.in/upcoming-Mobiles/Honda/sedan/launches-next-6-month
$seoArr[] = "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoArr[] = "Upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year.":  Find out upcoming ".$selected_brand_name." ".$selected_feature_name." Mobile launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches , speculated launches, futuristic models";
}elseif($selected_duration == "thisyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/Honda/sedan/launches-2012
$seoArr[] = "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$this_year;
$seoArr[] = "Upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$this_year;
$seoArr[] = "Expected Mobile Launches in ".$this_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$this_year.":  Find out upcoming ".$selected_brand_name." ".$selected_feature_name." Mobile launches in ".$this_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles ".$this_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$this_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$this_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches , speculated launches, futuristic models";
}elseif($selected_duration == "nextyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/Honda/sedan/launches-2013
$seoArr[] = "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$next_year;
$seoArr[] = "Upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$next_year;
$seoArr[] = "Expected Mobile Launches in ".$next_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$next_year.":  Find out upcoming ".$selected_brand_name." ".$selected_feature_name." Mobile launches in ".$next_year." with the expected launch date  & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India ".$next_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches , speculated launches, futuristic models";
}
}elseif(($selected_brand_id != "") && ($selected_feature_id != "") && ($selected_duration == "")){
//URL: www.onMobiles.in/upcoming-Mobiles/<brand-name>/<body-style>
$seoArr[] = "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches with Price";

$seo_desc =  "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India:  Find out upcoming ".$selected_brand_name." ".$selected_feature_name." Mobile launches in coming year with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "New ".$selected_brand_name." ".$selected_feature_name." Mobiles in India Upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles";
$seoKeyArr[] = "upcoming ".$selected_brand_name." ".$selected_feature_name." Mobiles in india";
$seoKeyArr[] = "new ".$selected_brand_name." ".$selected_feature_name." Mobile launches";
$seoKeyArr[] = "upcoming Mobile launches, upcoming ".$selected_brand_name." ".$selected_feature_name." Mobile models";
}elseif(($selected_brand_id != "") && ($selected_feature_id == "") && ($selected_duration != "")){
if($selected_duration == "1month"){
//URL: www.onMobiles.in/upcoming-Mobiles/<brand-name>/launches-next-1-month
$seoArr[] = "New ".$selected_brand_name." Mobiles in India - ".$next_month.", ".$next_month_year;
$seoArr[] = "Upcoming ".$selected_brand_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month.", ".$next_month_year;

$seo_desc =  "New ".$selected_brand_name." Mobiles in India - ".$next_month.", ".$next_month_year.":  Find out upcoming ".$selected_brand_name." Mobile launches in ".$next_month.", ".$next_month_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "Upcoming ".$selected_brand_name." Mobiles ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." Mobiles in India ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "new ".$selected_brand_name." upcoming launches";
$seoKeyArr[] = "upcoming Mobile launches in ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}elseif($selected_duration == "3months"){
//URL: www.onMobiles.in/upcoming-Mobiles/<brand-name>/launches-next-3-month
$seoArr[] = "New ".$selected_brand_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoArr[] = "Upcoming ".$selected_brand_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;

$seo_desc =  "New ".$selected_brand_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year.":  Find out upcoming ".$selected_brand_name." Mobile launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "Upcoming ".$selected_brand_name." Mobiles ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." Mobiles in India ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "new ".$selected_brand_name." upcoming launches";
$seoKeyArr[] = "upcoming Mobile launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
}elseif($selected_duration == "6months"){
//URL: www.onMobiles.in/upcoming-Mobiles/<brand-name>/launches-next-6-month
$seoArr[] = "New ".$selected_brand_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoArr[] = "Upcoming ".$selected_brand_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year.":  Find out upcoming ".$selected_brand_name." Mobile launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "Upcoming ".$selected_brand_name." Mobiles ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." Mobiles in India ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "new ".$selected_brand_name." upcoming launches";
$seoKeyArr[] = "upcoming Mobile launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
}elseif($selected_duration == "thisyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/<brand-name>/launches-2012
$seoArr[] = "New ".$selected_brand_name." Mobiles in India ".$this_year;
$seoArr[] = "Upcoming ".$selected_brand_name." Mobiles in India ".$this_year;
$seoArr[] = "Expected Mobile Launches in ".$this_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." Mobiles in India ".$this_year.":  Find out upcoming ".$selected_brand_name." Mobile launches in ".$this_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "Upcoming ".$selected_brand_name." Mobiles ".$this_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." Mobiles in India ".$this_year;
$seoKeyArr[] = "new ".$selected_brand_name." upcoming launches";
$seoKeyArr[] = "upcoming Mobile launches in ".$this_year;
}elseif($selected_duration == "nextyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/<brand-name>/launches-2013
$seoArr[] = "New ".$selected_brand_name." Mobiles in India ".$next_year;
$seoArr[] = "Upcoming ".$selected_brand_name." Mobiles in India ".$next_year;
$seoArr[] = "Expected Mobile Launches in ".$next_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." Mobiles in India ".$next_year.":  Find out upcoming ".$selected_brand_name." Mobile launches in ".$next_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "Upcoming ".$selected_brand_name." Mobiles ".$next_year;
$seoKeyArr[] = "upcoming ".$selected_brand_name." Mobiles in India ".$next_year;
$seoKeyArr[] = "new ".$selected_brand_name." upcoming launches";
$seoKeyArr[] = "upcoming Mobile launches in ".$next_year;
}
}elseif(($selected_brand_id == "") && ($selected_feature_id != "") && ($selected_duration != "")){
if($selected_duration == "1month"){
//URL: www.onMobiles.in/upcoming-Mobiles/sedan/launches-next-1-month
$seoArr[] = "New ".$selected_feature_name." Mobiles in India - ".$next_month.", ".$next_month_year;
$seoArr[] = "Upcoming ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month.", ".$next_month_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_feature_name." Mobiles in India - ".$next_month.", ".$next_month_year.":  Find out upcoming ".$selected_feature_name." Mobile launches in ".$next_month.", ".$next_month_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming ".$selected_feature_name." ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming ".$selected_feature_name." Mobiles in India ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}elseif($selected_duration == "3months"){
//URL: www.onMobiles.in/upcoming-Mobiles/sedan/launches-next-3-month
$seoArr[] = "New ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoArr[] = "Upcoming ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year.":  Find out upcoming ".$selected_feature_name." Mobile launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming ".$selected_feature_name." ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "upcoming ".$selected_feature_name." Mobiles in India ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}elseif($selected_duration == "6months"){
//URL: www.onMobiles.in/upcoming-Mobiles/sedan/launches-next-6-month
$seoArr[] = "New ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoArr[] = "Upcoming ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Expected Mobile Launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;

$seo_desc =  "New ".$selected_feature_name." Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year.":  Find out upcoming ".$selected_feature_name." Mobile launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming ".$selected_feature_name." ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "upcoming ".$selected_feature_name." Mobiles in India ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}elseif($selected_duration == "thisyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/sedan/launches-2012
$seoArr[] = "New ".$selected_feature_name." Mobiles in India ".$this_year;
$seoArr[] = "Upcoming ".$selected_feature_name." Mobiles in India ".$this_year;
$seoArr[] = "Expected Mobile Launches in ".$this_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_feature_name." Mobiles in India - ".$this_year.":  Find out upcoming ".$selected_feature_name." Mobile launches in ".$this_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming ".$selected_feature_name." ".$this_year;
$seoKeyArr[] = "upcoming ".$selected_feature_name." Mobiles in India ".$this_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$this_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}elseif($selected_duration == "nextyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/sedan/launches-2013
$seoArr[] = "New ".$selected_feature_name." Mobiles in India ".$next_year;
$seoArr[] = "Upcoming ".$selected_feature_name." Mobiles in India ".$next_year;
$seoArr[] = "Expected Mobile Launches in ".$next_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_feature_name." Mobiles in India - ".$next_year.":  Find out upcoming ".$selected_feature_name." Mobile launches in ".$next_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming ".$selected_feature_name." ".$next_year;
$seoKeyArr[] = "upcoming ".$selected_feature_name." Mobiles in India ".$next_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}
}elseif(($selected_brand_id != "") && ($selected_feature_id == "") && ($selected_duration == "")){
//URL: www.onMobiles.in/upcoming-Mobiles/<brand-name>
$seoArr[] = "New ".$selected_brand_name." Mobiles in India";
$seoArr[] = "Upcoming ".$selected_brand_name." Mobiles in India";
$seoArr[] = $selected_brand_name." Mobile Launches with Price";
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_brand_name." Mobiles in India:  Find out upcoming ".$selected_brand_name." Mobile launches in coming year with the expected launch date & price in India. Get latest news & videos on Mobile launches, & also find specifications, features, photos & videos of upcoming Mobiles in India at OnMobiles.in  at ".SEO_DOMAIN;

$seoKeyArr[] = "New ".$selected_brand_name." Mobiles in India";
$seoKeyArr[] = $selected_brand_name." upcoming Mobiles";
$seoKeyArr[] = "upcoming ".$selected_brand_name." Mobiles in India";
$seoKeyArr[] = "new ".$selected_brand_name." Mobile launches";
$seoKeyArr[] = "upcoming Mobile launches";
$seoKeyArr[] = $selected_brand_name." upcoming Mobile models";
$seoKeyArr[] = "future Mobiles";
}elseif(($selected_brand_id == "") && ($selected_feature_id != "") && ($selected_duration == "")){
//URL: www.onMobiles.in/upcoming-Mobiles/<body-style>
$seoArr[] = "New ".$selected_feature_name." Mobiles in India";
$seoArr[] = "Upcoming ".$selected_feature_name." Mobiles in India";
$seoArr[] = $selected_feature_name." Mobile Launches with Price";
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New ".$selected_feature_name." Mobiles in India:  Find out upcoming ".$selected_feature_name." Mobile launches in coming year with the expected launch date & price in India at ".SEO_DOMAIN;

$seoKeyArr[] = "New ".$selected_feature_name." Mobiles in India";
$seoKeyArr[] = "upcoming ".$selected_feature_name." Mobiles";
$seoKeyArr[] = "upcoming ".$selected_feature_name." Mobiles in india";
$seoKeyArr[] = "new ".$selected_feature_name." Mobile launches";
$seoKeyArr[] = "upcoming Mobile launches, upcoming ".$selected_feature_name." Mobile models, future Mobiles";

}elseif(($selected_brand_id == "") && ($selected_feature_id == "") && ($selected_duration != "")){
if($selected_duration == "1month"){
//URL: www.onMobiles.in/upcoming-Mobiles/launches-next-1-month
$seoArr[] = "New Mobiles in India - ".$next_month.", ".$next_month_year;
$seoArr[] = "Upcoming Mobiles in India |Expected Mobile Launches in ".$next_month.", ".$next_month_year;
$seoArr[] = SEO_DOMAIN;

$seo_desc =  "New Mobiles in India - ".$next_month.", ".$next_month_year.":  Find out upcoming Mobile launches in ".$next_month.", ".$next_month_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming Mobiles ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming Mobiles in India ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_month.", ".$next_month_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";

}elseif($selected_duration == "3months"){
//URL: www.onMobiles.in/upcoming-Mobiles/launches-next-3-month
$seoArr[] = "New Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoArr[] = "Upcoming Mobiles in India |Expected Mobile Launches in next 3 months";

$seo_desc =  "New Mobiles in India in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year.":  Find out upcoming Mobile launches with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming Mobiles in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year;
$seoKeyArr[] = "upcoming Mobiles in India ".$next_month." ".$next_month_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile ".$next_month." ".$next_month_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";

}elseif($selected_duration == "6months"){
//URL: www.onMobiles.in/upcoming-Mobiles/launches-next-6-month
$seoArr[] = "New Mobiles in India - ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoArr[] = "Upcoming Mobiles in India |Expected Mobile Launches in next 6 months";

$seo_desc =  "New Mobiles in India in ".$next_month." ".$next_month_year." to ".$next_3months." ".$next_3months_year.":  Find out upcoming Mobile launches with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming Mobiles in ".$next_month." ".$next_month_year." to ".$next_6months." ".$next_6months_year;
$seoKeyArr[] = "upcoming Mobiles in India ".$next_month." ".$next_month_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile ".$next_month." ".$next_month_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";

}elseif($selected_duration == "thisyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/launches-2012 (this year)
$seoArr[] = "New Mobiles in India in ".$this_year;
$seoArr[] = "Upcoming Mobiles in India ".$this_year;
$seoArr[] = "Expected Mobile Launches in ".$this_year;

$seo_desc =  "New Mobiles in India in ".$this_year.":  Find out upcoming Mobile launches in ".$this_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming Mobiles in ".$this_year;
$seoKeyArr[] = "upcoming Mobiles in India ".$this_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$this_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}elseif($selected_duration == "nextyear"){
//URL: www.onMobiles.in/upcoming-Mobiles/launches-2013 (Next year year)
$seoArr[] = "New Mobiles in India in ".$next_year;
$seoArr[] = "Upcoming Mobiles in India ".$next_year;
$seoArr[] = "Expected Mobile Launches in ".$next_year;

$seo_desc =  "New Mobiles in India in ".$next_year.":  Find out upcoming Mobile launches in ".$next_year." with the expected launch date & price in India. Get latest news & videos on Mobile launches at ".SEO_DOMAIN;

$seoKeyArr[] = "upcoming Mobiles in ".$next_year;
$seoKeyArr[] = "upcoming Mobiles in India ".$next_year;
$seoKeyArr[] = "new upcoming launches, upcoming Mobile launches in ".$next_year;
$seoKeyArr[] = "upcoming Mobile models, future Mobiles, future Mobile launches, speculated launches, futuristic models";
}
}elseif(($selected_brand_id == "") && ($selected_feature_id == "") && ($selected_duration == "")){
//URL: www.onMobiles.in/upcoming-Mobiles
$seoArr[] = "Upcoming Mobiles in India";
$seoArr[] = "New Mobiles in India";
$seoArr[] = "Mobile Launches in India";
$seoArr[] = "New Mobiles with Price in India";

$seo_desc =  "Upcoming Mobiles in India:  Find out new Mobile launches in coming years with their expected launch date & price in India. Get latest news & videos on Mobile launches, & also find specifications, features, photos & videos of upcoming Mobiles in India at ".SEO_DOMAIN;
$seoKeyArr[] = "New Mobiles in india, upcoming Mobiles in india, new upcoming launches, new Mobiles with price, upcoming Mobile launches, upcoming Mobile models, future Mobiles, future Mobile launches,speculated launches, futuristic models";
}
$h1 = $seoArr[0];
$seo_title = implode(" | ",$seoArr);
$seo_keywords = strtolower(implode(",",$seoKeyArr));
//End of SEO details     

//$new_breadcrumb = CATEGORY_HOME."<a href='".WEB_URL."Newcar-Search'>New Car Search</a> > Upcoming Mobiles"; 

$new_breadcrumb = upcomingCarsListBreadCrumb($cat_path);
$config_details = get_config_details();


$strXML .= "<XML>";
$login_details = getCookie();
$strXML .= $login_details;
$strXML .= getComponents('UPCOMING', getComponentParams(array('imageResize'=>$aModuleImageResize))); // components xml
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<BREAD_CRUMB><![CDATA[$new_breadcrumb]]></BREAD_CRUMB>";
$strXML .= "<SEO_DESC><![CDATA[$seo_desc]]></SEO_DESC>";
$strXML .= "<SEO_TAGS><![CDATA[$seo_keywords]]></SEO_TAGS>";
$strXML .= "<SEO_JS><![CDATA[$seo_js]]></SEO_JS>";
//$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
//$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_BRAND_ID><![CDATA[$selected_brand_id]]></SELECTED_BRAND_ID>";
$strXML .= "<SELECTED_FEATURE_ID><![CDATA[$selected_feature_id]]></SELECTED_FEATURE_ID>";
$strXML .= "<SELECTED_DURATION><![CDATA[$selected_duration]]></SELECTED_DURATION>";

$strXML .= "<SELECTED_BRAND_NAME><![CDATA[$selected_brand_name]]></SELECTED_BRAND_NAME>";
$strXML .= "<SELECTED_FEATURE_NAME><![CDATA[$selected_feature_name]]></SELECTED_FEATURE_NAME>";
$strXML .= "<THIS_YEAR><![CDATA[$this_year]]></THIS_YEAR>";
$strXML .= "<NEXT_YEAR><![CDATA[$next_year]]></NEXT_YEAR>";
$strXML .= "<NEXT_MONTH><![CDATA[$next_month]]></NEXT_MONTH>";
$strXML .= "<NEXT_MONTH_YEAR><![CDATA[$next_month_year]]></NEXT_MONTH_YEAR>";
$strXML .= "<NEXT_3MONTHS><![CDATA[$next_3months]]></NEXT_3MONTHS>";
$strXML .= "<NEXT_3MONTHS_YEAR><![CDATA[$next_3months_year]]></NEXT_3MONTHS_YEAR>";
$strXML .= "<NEXT_6MONTHS><![CDATA[$next_6months]]></NEXT_6MONTHS>";
$strXML .= "<NEXT_6MONTHS_YEAR><![CDATA[$next_6months_year]]></NEXT_6MONTHS_YEAR>";
$strXML .= "<STARTLIMIT><![CDATA[".$offset."]]></STARTLIMIT>";
$strXML .= "<PAGE_OFFSET><![CDATA[".OFFSET."]]></PAGE_OFFSET>";
$strXML .= "<PAGE_CNT><![CDATA[$numpages]]></PAGE_CNT>";
$strXML .= "<TOTAL_RECORD_COUNT><![CDATA[$totalcount]]></TOTAL_RECORD_COUNT>";
$strXML .= "<PAGER><![CDATA[$pageNavStr]]></PAGER>";
$strXML .= "<PERPAGE><![CDATA[".PERPAGE."]]></PERPAGE>";
$strXML .= "<H1><![CDATA[$h1]]></H1>";
$strXML .= "<PAGE_NAME><![CDATA[".SEO_WEB_URL.$_SERVER['REQUEST_URI']."]]></PAGE_NAME>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .=  "<PAGING><![CDATA[$sPagingXml]]></PAGING>";
$strXML .= "<OC_HOME_BOTTOM_NORTH_728x90><![CDATA[OC_HOME_BOTTOM_NORTH_728x90]]></OC_HOME_BOTTOM_NORTH_728x90>";
$strXML .= "<OC_Homepage_Middle_620x80><![CDATA[OC_Homepage_Middle_620x80]]></OC_Homepage_Middle_620x80>";
$strXML .= "<OC_ROS_Middle_HB_468x60><![CDATA[OC_ROS_Middle_HB_468x60]]></OC_ROS_Middle_HB_468x60>";
$strXML .= "<OC_ROS_Middle_HOME_468x60><![CDATA[OC_ROS_Middle_HOME_468x60]]></OC_ROS_Middle_HOME_468x60>";
$strXML .= "<OC_HOME_TOP_RHS_LREC_300x250_1><![CDATA[OC_Home_Top_RHS_Lrec_300x250_1]]></OC_HOME_TOP_RHS_LREC_300x250_1>";
$strXML .= "<OC_HOME_RHS_BOTTOM_LREC_300x250_2_MID><![CDATA[OC_Home_RHS_Bottom_Lrec_300x250_2]]></OC_HOME_RHS_BOTTOM_LREC_300x250_2_MID>";
$strXML .= "<OC_Home_Right_Bottom_Lrec_300x250_3><![CDATA[OC_Home_Right_Bottom_Lrec_300x250_3]]></OC_Home_Right_Bottom_Lrec_300x250_3>";
$strXML .= "<OC_ROS_BOTTOM_NORTH_728x90><![CDATA[OC_ROS_Bottom_North_728x90]]></OC_ROS_BOTTOM_NORTH_728x90>";
$strXML .= "<OC_RIGHT_BOTTOM_300X250><![CDATA[OC_Right_Bottom_300x250]]></OC_RIGHT_BOTTOM_300X250>";
$strXML .= "<OC_ROS_TOP_RHS_LREC_300x250_TOP><![CDATA[OC_ROS_Top_RHS_Lrec_300x250_top]]></OC_ROS_TOP_RHS_LREC_300x250_TOP>";
$strXML .= "<CAT_PATH><![CDATA[".$cat_path."]]></CAT_PATH>";
$strXML .= "</XML>";


$strXML = mb_convert_encoding($strXML, "UTF-8");
//header('Content-type: text/xml');echo $strXML;exit;
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;    $xslt->registerPHPFunctions();
$xsl = DOMDocument::load('xsl/upcoming_product_list.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>