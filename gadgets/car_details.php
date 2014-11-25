<?php

//ini_set("display_errors",1);
require_once('./include/config.php');
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'pivot.class.php');
require_once(CLASSPATH . 'feature.class.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'price.class.php');
require_once(CLASSPATH . "campus_discussion.class.php");
require_once(CLASSPATH . 'pager.class.php');
require_once(CLASSPATH . 'user_review.class.php');
require_once(CLASSPATH.'reviews.class.php');
require_once(CLASSPATH . 'report.class.php');
require_once(CLASSPATH . 'Utility.php');
require_once(CLASSPATH . 'curl.class.php');
require_once(CLASSPATH . 'xmlparser.class.php');
require_once(CLASSPATH . 'wallpaper.class.php');
require_once(CLASSPATH . 'videos.class.php');

$dbconn = new DbConn;
$oBrand = new BrandManagement;
$category = new CategoryManagement;
$oPivot = new PivotManagement;
$oFeature = new FeatureManagement;
$oProduct = new ProductManagement;
$oPrice = new price;
$oCampusDiscussion = new campus_discussion();
$ObjPager = new Pager();
$oReview  = new reviews;
$userreview = new USERREVIEW;
$report = new report;
$oCurl = new curl;
$oXmlparser = new XMLParser;
$oWallpapers = new Wallpapers;
$videoGallery = new videos();
//print_r($_REQUEST);  die();

$request_url = $_SERVER['REQUEST_URI'];
$photopos = strpos($request_url, 'photos');
if ($pos !== false) {
    $photoslugs = explode("photos/",$request_url);
    $photoslug = constructUrl($photoslugs[1]);
}
$videospos = strpos($request_url,'videos');
if ($pos !== false){
    $videosslugs = explode("videos/",$request_url);
    $videosslug = constructUrl($videosslugs[1]);
}

$product_id = !empty($_REQUEST['router_product_id']) ? $_REQUEST['router_product_id'] : $_REQUEST['pid'];
$router_product_id = !empty($_REQUEST['router_product_id']) ? $_REQUEST['router_product_id'] : $_REQUEST['pid'];
$write_prd_id = $product_id;
settype($product_id, "integer");
$rev_product_id = $_REQUEST['pid'];
settype($rev_product_id, "integer");
$top_competitor_product_id = $product_id;
$curr_product_id = $product_id;
$router_model_id = !empty($_REQUEST['router_model_id']) ? $_REQUEST['router_model_id'] : $_REQUEST['model_id'];
$router_brand_id = !empty($_REQUEST['router_brand_id']) ? $_REQUEST['router_brand_id'] : $_REQUEST['brand_id'];
$router_review_id = !empty($_REQUEST['router_review_id']) ? $_REQUEST['router_review_id'] : $_REQUEST['router_review_id'];
$urevid = $_REQUEST['urevid'];
$category_id = $_REQUEST['category_id'];
$category_name = $_REQUEST['category_name'] ? $_REQUEST['category_name'] : '';
$cat_path = $_REQUEST['cat_path'] ? $_REQUEST['cat_path'] : '';
$action = !empty($_REQUEST['action']) ? trim($_REQUEST['action']) : 'overviews';

if ($action == 'variant') {
    $action = 'overviews';
}
if (false !== strpos($request_url, 'features')) {
    $action = 'features';
}
if (!empty($urevid)) {
    $_REQUEST['userrevid'] = $urevid;
}
switch ($action) {
    case 'variant':
    $action = 'overviews';
    $currtab_sel = 1;
    break;
    case 'news':
    $action = "news";
    $currtab_sel = 2;
    break;
    case 'reviews';
    $action = "all_reviews";
    $currtab_sel = 3;
    $currtab_subsel = 1;
    break;
    case 'user_reviews';
    $action = "user_reviews";
    $currtab_sel = 3;
    $currtab_subsel = 2;
    break;
    case 'user_review_detail':
    $action = "user_review_detail";
    $currtab_sel = 3;
    $currtab_subsel = 3;
    break;
    case 'videos';
    $action = "videos";
    $currtab_sel = 5;
    break;
    case 'photos';
    $action = "photos";
    $currtab_sel = 4;
    break;
    default:
    $action = 'overviews';
    $currtab_sel = 1;
    break;
}

$req_review_id = !empty($_REQUEST['revid']) ? trim($_REQUEST['revid']) : $router_review_id;
$revid = $req_review_id;
$req_rev_grp_id = trim($_REQUEST['grpid']);
$request_url = str_replace("//", '/', $request_url);
$aProductname = explode("/", $request_url);
$url_variant_name = trim(str_replace("-", " ", $aProductname[3]));
$review_in_url = trim($aProductname[5]); //on-cars-reviews
unset($result);
unset($red_seo_url);
unset($redirect_seo_url);
if (!empty($product_id)) {
    $pro_detail = $oProduct->arrGetProductDetails($product_id, $category_id, "", "1", "", "", "1", "", "", "1", "", "", "", "", "", "");
    if (sizeof($pro_detail) <= 0) {
        $seoUrlArr[] = SEO_WEB_URL;
        $seoUrlArr[] = $cat_path;
        $seoUrlArr[] = $_REQUEST['router_brand_name'];
        $seoUrlArr[] = $_REQUEST['router_model_name'];
        $url = implode('/', $seoUrlArr);
        header('Location: ' . $url, TRUE, 301);
        exit;
    }
    if (is_array($pro_detail)) {
        $product_status = $pro_detail[0]['status'];
        $product_discontinue_status = $pro_detail[0]["discontinue_flag"];
        $product_discontinue_date = $pro_detail[0]["discontinue_date"];
        $product_info_dispname = $pro_detail[0]['product_name'];
        $product_info_name = $pro_detail[0]['product_name'];
        $variant_name = $pro_detail[0]['variant'];
        $seo_variant_path = !empty($_REQUEST['router_product_name']) ? $pro_detail[0]['seo_path'] : "";
        $brand_id = $pro_detail[0]['brand_id'];
        $top_competitor_brand_id = $brand_id;
        if (!empty($brand_id)) {
            $brandresult = $oBrand->arrGetBrandDetails($brand_id);
        }
        $brand_name = constructUrl($brandresult[0]['brand_name']);
        $seo_brand_path = $brandresult[0]['seo_path'];
        $curr_brand_name = $brandresult[0]['brand_name'];
        $sImagePath = $pro_detail['0']['image_path'];
        $slideImagePath = $pro_detail['0']['image_path'];
        $img_media_id = $pro_detail['0']['img_media_id'];
        if (!empty($sImagePath)) {
            $sImagePath = resizeImagePath($sImagePath, "225X300", $aModuleImageResize, $img_media_id);
            $sImagePath = $sImagePath ? CENTRAL_IMAGE_URL . $sImagePath : IMAGE_URL . 'no_image_251_188.gif';
            $thumb_image_path = resizeImagePath($sImagePath, "87X65", $aModuleImageResize, $img_media_id);
            $thumb_image_path = $thumb_image_path ? $thumb_image_path : IMAGE_URL . 'no_image_87X65.gif';
        }
        if (!empty($product_info_name)) {
            $productNameData = $oProduct->arrGetProductNameInfo("", $category_id, "", $product_info_name, "1", "", "", "", "", "");
            if (sizeof($productNameData) <= 0) {
                $seoUrlArr[] = SEO_WEB_URL;
                $seoUrlArr[] = $cat_path;
                $seoUrlArr[] = $_REQUEST['router_brand_name'];
                $url = implode('/', $seoUrlArr);
                header('Location: ' . $url, TRUE, 301);
                exit;
            }
        }
        if (is_array($productNameData)) {
            $model_status = $productNameData[0]['status'];
            $seo_model_id = $productNameData[0]['product_name_id'];
            $seo_model_path = $productNameData[0]['seo_path'];
            $color_model_id = $productNameData[0]['product_name_id'];
            $model_discontinue_status = $productNameData[0]["discontinue_flag"];
            $model_discontinue_date = $productNameData[0]["discontinue_date"];
            $used_model_name = $productNameData[0]["product_info_name"];
            $curr_model_name = $productNameData[0]["product_info_name"];
            $product_desc = html_entity_decode($productNameData['0']['product_name_desc'], ENT_QUOTES, 'UTF-8');
        }
        $top_competitor_model_id = $seo_model_id;
    }
} else {
    header("Location:" . WEB_URL, TRUE, 301);
    exit;
}

//start code added by rajesh on dated 10-06-2011 for variant page summary.
if (!empty($product_id)) {
if (!empty($selected_city_id)) {
$aOverview = $oFeature->arrGetVariantPageSummary($category_id, $product_id, "", $selected_city_id);
} else {
$aOverview = $oFeature->arrGetVariantPageSummary($category_id, $product_id, "1");
}
}
foreach ($aOverview as $key => $val) {
if (!strpos($key, 'Price') && !strpos($key, 'Feature')) {
unset($overviewArr);
foreach ($aOverview[$key] as $overviewtitle => $overviewvalueArr) {
$overviewvalueArr = array_change_key_case($overviewvalueArr, CASE_UPPER);
$techspecxml .= "<TECH_SPEC_DATA>";
$techspecxml .= "<FEATURE_TITLE><![CDATA[$overviewtitle]]></FEATURE_TITLE>";
foreach ($overviewvalueArr as $techspeckey => $techspecval) {
$techspecxml .= "<$techspeckey><![CDATA[$techspecval]]></$techspeckey>";
}
$techspecxml .= "</TECH_SPEC_DATA>";
unset($overviewvalueArr);
}
}
if (strpos($key, 'Feature')) {
unset($overviewArr);
foreach ($aOverview[$key] as $overviewtitle => $overviewvalueArr) {
$overviewvalueArr = array_change_key_case($overviewvalueArr, CASE_UPPER);
$featurespecxml .= "<FEATURE_SPEC_DATA>";
$featurespecxml .= "<FEATURE_TITLE><![CDATA[$overviewtitle]]></FEATURE_TITLE>";
foreach ($overviewvalueArr as $techspeckey => $techspecval) {
$featurespecxml .= "<$techspeckey><![CDATA[$techspecval]]></$techspeckey>";
}
$featurespecxml .= "</FEATURE_SPEC_DATA>";
unset($overviewvalueArr);
}
}
if (strpos($key, 'Price')) {
unset($overviewArr);
foreach ($aOverview[$key] as $overviewtitle => $overview_value) {
$avg_price = str_replace("(insurance, road tax and other taxes/charges extra) ", "", $overview_value);
$org_avg_price = str_replace(array(",", " , "), "", $avg_price);
$techspecxml .= "<AVG_EX_SHOWROOM_PRICE><![CDATA[" . str_replace("(insurance, road tax and other taxes/charges extra) ", "", $overview_value) . "]]></AVG_EX_SHOWROOM_PRICE>";
$techspecxml .= "<AVG_ORG_SHOWROOM_PRICE><![CDATA[" . $org_avg_price . "]]></AVG_ORG_SHOWROOM_PRICE>";
}
}
if (strpos($key, 'product_desc')) {
unset($overviewArr);
foreach ($aOverview[$key] as $overviewtitle => $overview_value) {
$techspecxml .= "<PRODUCT_DESC><![CDATA[$overview_value]]></PRODUCT_DESC>";
}
}
}
$sOverviewXML = "<TECH_SPEC_SHORT_DESC>$techspecxml</TECH_SPEC_SHORT_DESC>";
$sOverviewXML .= "<FEATURE_SPEC_SHORT_DESC>$featurespecxml</FEATURE_SPEC_SHORT_DESC>";
//end code added by rajesh on dated 10-06-2011 for variant page summary.

$getRecentCookie = $_COOKIE["recentView"];
if (!empty($getRecentCookie)) {
$cookie_product_ids = $getRecentCookie . "," . $product_id;
} else {
$cookie_product_ids = $product_id;
}
setcookie("recentView", $cookie_product_ids, time() + 3600 * 24, "/", "", "0", "1");
$three_months_plus_discontinue_date = 0;
$prev_3_month = date('Y-m-d', strtotime("-" . DISCONTINUE_MONTH_DURATION . " month")) . ' 00:00:00';
if ((($model_discontinue_status == "0") && (strtotime($model_discontinue_date) < strtotime($prev_3_month)) && $model_discontinue_date != "0000-00-00 00:00:00") || (($product_discontinue_status == "0") && (strtotime($product_discontinue_date) < strtotime($prev_3_month)) && $product_discontinue_date != "0000-00-00 00:00:00")) {
$three_months_plus_discontinue_date = 1;
}
if (($model_status == "0") || ($product_status == "0")) {
$seoUrlArr[] = SEO_WEB_URL;
$seoUrlArr[] = $cat_path;
$seoUrlArr[] = $_REQUEST['router_brand_name'];
$url = implode('/', $seoUrlArr);
header('Location: ' . $url, TRUE, 301);
exit;
}
if ($_COOKIE['changenloc'] == '') {
setcookie("changenloc", "1", time() + 3600, '/', $domain); //used to change location fiden.
}
$selected_city_id = $_REQUEST["cookie_city_id"];
$selected_city_name = $_REQUEST['hd_city_name'];
$dealer_location_id = $_REQUEST['dealer_location_id'];
$selected_city_id = $dealer_location_id ? $dealer_location_id : $selected_city_id;
$chars = array("%21", "%22", "%23", "%24", "%25", "%26", "%27", "%28", "%29", "%2A", "%2B", "%2C", "%2D", "%2E", "%2F", "%30", "%31", "%32", "%33", "%34", "%35", "%36", "%37", "%38", "%39", "%3A", "%3B", "%3C", "%3D", "%3E", "%40", "%41", "%42", "%43", "%44", "%45", "%46", "%47", "%48", "%49", "%4A", "%4B", "%4C", "%4D", "%4E", "%4F", "%50", "%51", "%52", "%53", "%54", "%55", "%56", "%57", "%58", "%59", "%5A", "%5B", "%5C", "%5D", "%5E", "%5F", "%60", "%61", "%62", "%63", "%65", "%66", "%67", "%68", "%69", "%6A", "%6B", "%6C", "%6D", "%6E", "%6F", "%70", "%71", "%72", "%73", "%74", "%75", "%76", "%77", "%78", "%79", "%7A", "%7B", "%7C", "%7D", "%7E", "%7F", "/", ",", "$", "%3E", "%", "#", "+", "*", "_", "!", "@", "'", ":", ";", "&", "(", ")", ".", "~", "<", ">", "{", "}", "|", "[", "]", "=", "^", "`", '"');
setcookie("reviewAdded", "", time() - 3600); //used to remove cookie of user review and rating section.
$user_name = "";
$tab = $_REQUEST['tab'];
$is_avail = 0;
$request_url = $_SERVER['REQUEST_URI'];
$is_avail = isCharAvail($request_url);
$tab_id = $_REQUEST['fid'];
$reviewName = trim($_REQUEST['rev']);
$revid = trim($_REQUEST['revid']);
settype($revid, "integer");
$rev_grp_id = trim($_REQUEST['grpid']);
settype($rev_grp_id, "integer");
$revtype = $_REQUEST['revtype'];
$user_review_id = $_REQUEST['userrevid'];
$get_user_review_id = $_REQUEST['userrevid'];
settype($user_review_id, "integer");
$curr_user_review_id = $user_review_id;
$tab = $_REQUEST['tab'];
$photo_tab_id = $_REQUEST['photo_tab_id'];
$sCityId = $selected_city_id;
$sCityName = $selected_city_name;
if (!empty($category_id)) {
$price_data = $oPrice->arrGetVariantDetail("", $category_id, "1");
$variant_id = $price_data[0]['variant_id'];
}
if (!empty($product_id)) {
$aProductDetail = $oProduct->arrGetProductDetails($product_id, $category_id, "", '1', "", "", "1", "", "", "1");
}
$rating_brand_id = $aProductDetail[0]['brand_id'];
$product_info_name = $aProductDetail[0]['product_name'];
$seo_product_info_name = $product_info_name;
if (!empty($product_info_name)) {
$result = $oProduct->arrGetProductNameInfo("", $category_id, $rating_brand_id, $product_info_name);
}
$product_name_id = $result[0]['product_name_id'];
if (!empty($product_name_id)) {
$result = $userreview->arrGetAdminExpertGrade($category_id, '', '', $product_name_id);
}
$design_rating = $result[0]['design_rating'];
$performance_rating = $result[0]['performance_rating'];
$user_rating = $result[0]['user_rating'];
$design_rating_proportion = ($design_rating * 100) / 10;
$performance_rating_proportion = ($performance_rating * 100) / 10;
$user_rating_proportion = ($user_rating * 100) / 10;
$overallgrade = $result[0]['overallgrade'];
$rating_algo_key = "";
foreach ($rangeArr as $key => $range) {
if ($overallgrade >= $range[0] && $overallgrade <= $range[1]) {
$rating_algo_key = $key;
break;
}
}
$expertratinghtml = '';
$expertratinghtml .= $ratingAlgoArr[$rating_algo_key] ? $ratingAlgoArr[$rating_algo_key] : 0;
$rating_algo_key = $rating_algo_key ? $rating_algo_key : 'Not Yet Rated';
$expertratingxml .= "<STAR_EXPERT_GRAPH_RATING_STR><![CDATA[$expertratinghtml]]></STAR_EXPERT_GRAPH_RATING_STR>";
$expertratingxml .= "<STAR_EXPERT_GRAPH_RATING_MSG><![CDATA[$rating_algo_key]]></STAR_EXPERT_GRAPH_RATING_MSG>";
//$expertratinghtml =  ($rating_algo_key != 'onestar' && $rating_algo_key != 'halfstar' && $rating_algo_key != '') ? $expertratinghtml."($rating_algo_key)" : $expertratinghtml;
$expertratingxml .= "<STAR_EXPERT_RATING_STR><![CDATA[$expertratinghtml]]></STAR_EXPERT_RATING_STR>";
$expertratingxml .= "<EXPERT_DESIGN_RATING_PROPORTION><![CDATA[$design_rating_proportion]]></EXPERT_DESIGN_RATING_PROPORTION>";
$expertratingxml .= "<EXPERT_DESIGN_RATING><![CDATA[$design_rating]]></EXPERT_DESIGN_RATING>";
$expertratingxml .= "<EXPERT_PERFORMANCE_RATING_PROPORTION><![CDATA[$performance_rating_proportion]]></EXPERT_PERFORMANCE_RATING_PROPORTION>";
$expertratingxml .= "<EXPERT_PERFORMANCE_RATING><![CDATA[$performance_rating]]></EXPERT_PERFORMANCE_RATING>";
$expertratingxml .= "<EXPERT_USER_RATING_PROPORTION><![CDATA[$user_rating_proportion]]></EXPERT_USER_RATING_PROPORTION>";
$expertratingxml .= "<EXPERT_USER_RATING><![CDATA[$user_rating]]></EXPERT_USER_RATING>";
$expertratingxml .= "<STAR_EXPERT_OVERALLGRADE><![CDATA[$overallgrade]]></STAR_EXPERT_OVERALLGRADE>";
//$product_brand_name." ".$product_info_dispname." ".$product_variant_name;
unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $cat_path;
$seoTitleArr[] = $seo_brand_path;
$seoTitleArr[] = $seo_model_name;
$seoTitleArr[] = "expert-reviews";
$seo_expert_model_url = implode("/", $seoTitleArr);
$expertratingxml .= "<EXPERT_REVIEW_URL><![CDATA[$seo_expert_model_url]]></EXPERT_REVIEW_URL>";
unset($seoTitleArr);
if (!empty($product_name_id)) {
$reviewsresult = $userreview->arrGetAdminOverallGrade($category_id, $rating_brand_id, $product_id, $product_name_id, "1");
}
$reviewscnt = sizeof($reviewsresult);
$overallcnt = 0;
$overallavg = round($reviewsresult[0]['overallgrade']);
if ($reviewscnt <= 0) {
if (!empty($product_name_id)) {
$reviewsresult = $userreview->arrGetOverallGrade($category_id, $rating_brand_id, $product_id, $product_name_id, "1");
}
$overallavg = round($reviewsresult[0]['overallavg']);
$overallcnt = $reviewsresult[0]['totaloverallcnt'] ? $reviewsresult[0]['totaloverallcnt'] : 0;
if (!empty($product_name_id)) {
$totalcnt = $userreview->arrGetUserReviewDetailsCount("", "", "", "", "", $rating_brand_id, $category_id, $product_name_id, $product_id, "1");
}
}
if (!empty($rating_brand_id)) {
$brandresult = $oBrand->arrGetBrandDetails($rating_brand_id);
}
$product_brand_name = html_entity_decode($brandresult[0]['brand_name'], ENT_QUOTES, 'UTF-8');
$product_info_name = html_entity_decode($product_info_name, ENT_QUOTES, 'UTF-8');
$product_link_name = $product_brand_name . "-" . $product_info_name;
unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $cat_path;
$seoTitleArr[] = $seo_brand_path;
$seoTitleArr[] = $seo_model_path;
$seoTitleArr[] = "user-reviews";
$seo_model_url = implode("/", $seoTitleArr);
unset($seoTitleArr);
/*$html = "";
for ($grade = 1; $grade <= 5; $grade++) {
if ($grade <= $overallavg) {
$html2 .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
} else {
$html2 .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
}
}
if ($overallavg == 1) {
$gradeStr = "Poor";
$html = $overallavg + 12;
} else if ($overallavg == 2) {
$gradeStr = "Fair";
$html = ($overallavg - 1) * 12 + (11 * $overallavg);
} else if ($overallavg == 3) {
$html = ($overallavg - 1) * 12 + (11 * $overallavg);
$gradeStr = "Average";
} else if ($overallavg == 4) {
$html = ($overallavg - 1) * 12 + (11 * $overallavg);
$gradeStr = "Good";
} else if ($overallavg == 5) {
$html = ($overallavg - 1) * 12 + (11 * ($overallavg - 1));
$gradeStr = "Excellent";
} else {
$html = 0;
}
$html1 = (($overallavg * 100) / 10) * 2;
$cmd = PHP_PATH . ' ' . BASEPATH . "api/average_rating_api.php brand_id=$router_brand_id product_name_id=$product_name_id product_id=$product_id";
$xml_output = shell_exec($cmd);
$oXmlparser->XMLParse($xml_output);
$aResultXML = $oXmlparser->getOutput();
$avg_html = $aResultXML["AVERAGE_USER_RATING_API"]["ALL_REVIEWS_AVG_RATING_PROPERTION"];
$avg_html_cnt = $aResultXML["AVERAGE_USER_RATING_API"]["ALL_REVIEWS_AVG_RATING"];
$oXmlparser->clearOutput();
if (($avg_html_cnt >= 1) && ($avg_html_cnt <= 1.5)) {
$gradeStr = "Poor";
} else if (($avg_html_cnt > 1.5) && ($avg_html_cnt <= 2.5)) {
$gradeStr = "Fair";
} else if (($avg_html_cnt > 2.5) && ($avg_html_cnt <= 3.5)) {
$gradeStr = "Average";
} else if (($avg_html_cnt > 3.5) && ($avg_html_cnt <= 4.5)) {
$gradeStr = "Good";
} else if (($avg_html_cnt > 4.5) && ($avg_html_cnt <= 5)) {
$gradeStr = "Excellent";
}
$expertratingxml .= $xml_output;
$expertratingxml .= "<OVERALL_AVG_HTML_MSG><![CDATA[$gradeStr]]></OVERALL_AVG_HTML_MSG>";
$expertratingxml .= "<OVERALL_AVG_HTML_DISP><![CDATA[$avg_html]]></OVERALL_AVG_HTML_DISP>";
$expertratingxml .= "<OVERALL_AVG_CNT><![CDATA[$avg_html_cnt]]></OVERALL_AVG_CNT>";
$expertratingxml .= "<OVERALL_TOTAL_CNT><![CDATA[$overallcnt]]></OVERALL_TOTAL_CNT>";
$expertratingxml .= "<OVERALL_CNT><![CDATA[$totalcnt]]></OVERALL_CNT>";
$expertratingxml .= "<SEO_MODEL_RATING_PAGE_URL><![CDATA[$seo_model_url]]></SEO_MODEL_RATING_PAGE_URL>";
//end code added by rajesh on dated 02-06-2011 for user.*/
if (empty($aProductDetail)) {
$seoUrlArr[] = SEO_WEB_URL;
$seoUrlArr[] = $_REQUEST['router_brand_name'];
$seoUrlArr[] = $_REQUEST['router_model_name'];
$url = implode('/', $seoUrlArr);
header('Location: ' . $url, TRUE, 301);
exit;
}
if (!empty($product_id)) {
if (!empty($selected_city_id)) {
$price_result = $oPrice->arrGetPriceDetails("", $product_id, $categoryid, "", "", $selected_city_id, "1", "", "", "");
} else {
$price_result = $oPrice->arrGetPriceDetails("", $product_id, $categoryid, "", "", "", "1", "", "", "1");
}
}

$iSelCity = $price_result[0]['city_id'];
$iSelCityName = $price_result[0]['city_name'];
$aBrandDetail = $oBrand->arrGetBrandDetails("", $category_id);
if (is_array($aBrandDetail)) {
foreach ($aBrandDetail as $ibKey => $aBrandData) {
$aBrandDetailName[$aBrandData['brand_id']][] = $aBrandData['brand_name'];
}
}
if (is_array($aProductDetail)) {
$aProductWithPriceDetail = $oProduct->constantProductInfoDetails($aProductDetail, $category_id, $iSelCity);
}
//print_r($aProductWithPriceDetail); die();
if (is_array($aProductWithPriceDetail)) {
$sProductName = $aProductWithPriceDetail['0']['product_name'];
$sItemSelProductName = $aProductWithPriceDetail['0']['product_name'];
$iOnRoadPrice = $aProductWithPriceDetail['0']['On_Road_Price'];
$iExShowRoomPriceOriginal = $aProductWithPriceDetail['0']['exshowroomprice'];
$popad = 0;
$popadprice = str_replace(",", "", $iExShowRoomPriceOriginal);
$iExShowRoomPriceOriginal = str_replace(",", "", $iExShowRoomPriceOriginal);
if ($popadprice >= 300000 && $popadprice <= 1500000) {
$popad = 1;
}
$iExShowRoomPrice = $aProductWithPriceDetail['0']['exshowroomprice'];
$variant_price = $aProductWithPriceDetail['0']['variant_value'];
$sproduct_desc = $aProductWithPriceDetail['0']['product_desc'];
$sDisplayName = $aProductWithPriceDetail['0']['display_product_name'];
if (!empty($sProductName)) {
//echo "SSSSSSSSSSSSSSS" ; print_r($sProductName);
$productinforesult = $oProduct->arrGetProductNameInfo("", $category_id, "", $sProductName);
//print_r($productinforesult);
}
$product_desc = html_entity_decode($productinforesult['0']['product_name_desc'], ENT_QUOTES, 'UTF-8');
$sLinkProductName = $aProductWithPriceDetail['0']['link_product_name'];
$product_name_id = $productinforesult['0']['product_name_id'];
/*$sImagePath = $productinforesult['0']['image_path'];
$img_media_id = $productinforesult['0']['img_media_id'];
if (!empty($sImagePath)) {
$sImagePath = resizeImagePath($sImagePath, "251X188", $aModuleImageResize, $img_media_id);
$sImagePath = $sImagePath ? CENTRAL_IMAGE_URL . $sImagePath : IMAGE_URL . 'no_image_251_188.gif';
$thumb_image_path = resizeImagePath($sImagePath, "87X65", $aModuleImageResize, $img_media_id);
$thumb_image_path = $thumb_image_path ? $thumb_image_path : IMAGE_URL . 'no_image_87X65.gif';
}*/
$sVideoPath = $aProductWithPriceDetail['0']['video_path'];
$sVariant = $aProductWithPriceDetail['0']['variant'];
/*$seo_variant_path = $aProductWithPriceDetail['0']['seo_path'];*/
$iBrandId = $aProductWithPriceDetail['0']['brand_id'];

$arrival_date = $aProductWithPriceDetail[0]['arrival_date'];
$discontinue_date = $aProductWithPriceDetail[0]['discontinue_date'];
$productvariantUrlYear = buildYear($arrival_date, $discontinue_date);
$aPriceDetails = $aProductWithPriceDetail['0']['price_details'];

if(!empty($aProductWithPriceDetail['0']['announced_date'])){
//echo $pResult[0]['announced_date'] ."+++++++++++++++++++++++++". $pResult[0]['arrival_date'];
$announcedateday = explode(" ",$aProductWithPriceDetail['0']['announced_date']) ;
$announcedates = explode("-",$announcedateday[0]); 
$product_announced_date =  date("F j Y", mktime(0, 0, 0, $announcedates['1']  , $announcedates['2'], $announcedates['0']));
}
if(!empty($aProductWithPriceDetail['0']['arrival_date'])){
$arrivaldateday = explode(" ",$aProductWithPriceDetail['0']['arrival_date']) ;
//print "<pre>";  print_r($arrivaldateday[0]);
$arrivaldates = explode("-",$arrivaldateday[0]) ;
//print "<pre>"; print_r($arrivaldates);
$product_arrival_date =  date("F j Y", mktime(0, 0, 0, $arrivaldates['1']  , $arrivaldates['2'], $arrivaldates['0']));
}




$prodCityId = $aPriceDetails['0']['city_id'];
if (!empty($iBrandId)) {
$brand_result = $oBrand->arrGetBrandDetails($iBrandId, $category_id);
}
$brand_name = $brand_result[0]['brand_name'];
$seo_ProductName = $sProductName;
$seo_variant = $sVariant;
$seo_title_part = ucfirst($brand_name) . " " . ucfirst($sProductName) . " " . ucfirst($sVariant);
$title = ucfirst($brand_name) . " " . ucfirst($sProductName) . " " . ucfirst(str_replace(" ", "-", $sVariant));
if (!empty($tab_id)) {
if ($tab_id == 2) {
$typeshow = 'Features';
}
if ($tab_id == 3) {
$typeshow = 'Technical Specifications';
}
} else {
$typeshow = ' Overview & Details';
}
$seoArr[0] = $title . " " . $typeshow;
$breadcrumb = "Overview : $title";
$seo_desc = $title . ' - On Cars India. Get all the latest car reviews and ratings, available versions,on road price,technical specifications, features, colours, Photos and videos on ' . SEO_DOMAIN;
$seoArr[2] = ucfirst($brand_name) . " " . ucfirst($sProductName) . ' Cars';
$seoArr[3] = SEO_DOMAIN;
$seo_title = implode(" | ", $seoArr);
$product_brand_name = html_entity_decode($brand_name, ENT_QUOTES, 'UTF-8');
$product_link_name = html_entity_decode($sLinkProductName, ENT_QUOTES, 'UTF-8');
$product_variant_name = html_entity_decode($sVariant, ENT_QUOTES, 'UTF-8');
$aHostqstr = explode("/", $_SERVER['SCRIPT_URL']);
if (!empty($sItemSelProductName)) {
$aSelProductNameData = $oProduct->arrGetProductByName($sItemSelProductName, '', "", "1", "0", "5");
}
if (is_array($aSelProductNameData)) {
foreach ($aSelProductNameData as $iwKey => $aValueWallPaperProd) {
$aSelProductIds[] = $aValueWallPaperProd['product_id'];
}
}

$aProductData[0] = array("brand_id" => $rating_brand_id, "product_name_id" => $product_name_id, "product_id" => $product_id, "product_name" => $sProductName, "On_Road_Price" => $iOnRoadPrice, "display_product_name" => $sDisplayName, "link_product_name" => $sLinkProductName, "video_path" => $sVideoPath, "image_path" => $sImagePath, "city_name" => $sCityName, "variant" => $sVariant, "exshowroomprice" => $iExShowRoomPrice, "seo_url" => $seo_url, "exshowroompriceorginal" => $iExShowRoomPriceOriginal, "brand_name" => $brand_name, "product_desc" => $sproduct_desc, 'thumb_image_path' => $thumb_image_path, 'arrival_date' => $product_arrival_date, 'announced_date' => $product_announced_date);
$disp_title = $aProductData['0']['display_product_name'];
$sProductDet = arraytoxml($aProductData, "PRODUCT_DETAIL_DATA");
$sProductDetXml = "<PRODUCT_DETAIL>" . $sProductDet . "</PRODUCT_DETAIL>";
}

$aParamaters = array("category_id" => $category_id, "brand_id" => $brand_id);
if (!empty($sProductName)) {
$productNameInfo = $oProduct->arrGetProductNameInfo("", $category_id, "", $sProductName, 1);
}
$productNameInfoId = $productNameInfo[0]['product_name_id'];
$brand_id = $iBrandId;
$product_name_id = $productNameInfoId;


/*if ($action == 'user_review' || $action == 'overviews') {
    $sub_grp[] = "10";
    $sub_grp[] = "13";
    $feature_sub_group_array = $oFeature->arrGetFeatureDetails("", "", "", $sub_grp);
    if ($user_review_id != '0') {
        
        if ($action == 'model')
            $cnt = 10;
        $result = $userreview->arrGetUserReviewDetails($user_review_id, "", "", "", "", $brand_id, $category_id, '', "", "1", $startlimit, $cnt);
        $cnt = sizeof($result);
        if (empty($cnt)) {
            $seoUrlArr[] = SEO_WEB_URL;
            $seoUrlArr[] = $cat_path;
            $seoUrlArr[] = $_REQUEST['router_brand_name'];
            $seoUrlArr[] = $_REQUEST['router_model_name'];
            $seoUrlArr[] = 'user-reviews';
            $url = implode('/', $seoUrlArr);
            header('Location: ' . $url, TRUE, 301);
            exit;
        }
        $xml = "<USER_REVIEW_MASTER>";
        $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        for ($i = 0; $i < $cnt; $i++) {
            $user_review_id = $result[$i]['user_review_id'];
            $comment_result = $report->arrGetUserReviewCommentCount($user_review_id);
            $comment_count = $comment_result[0]['comment_count'];
            if (!empty($comment_count) || $comment_count != 0) {
                $result[$i]['comment_count'] = $comment_count;
            }
            $user_name = $result[$i]['user_name'];
            $brand_id = $result[$i]['brand_id'];
            $model_id = $result[$i]['product_info_id'];
            $product_id = $result[$i]['product_id'];
            $create_date = $result[$i]['create_date'];
            $result[$i]['create_date'] = date('d F Y', strtotime($create_date));
            $result[$i]['createdate'] = date('d', strtotime($create_date));
            $result[$i]['createdate_mon_year'] = date('F/y', strtotime($create_date));
            $user_name = $result[$i]['user_name'];
            $title = $result[$i]['title'];
            if ($user_name != "") {
                $user_name = html_entity_decode($user_name, ENT_QUOTES, 'UTF-8');
                $result[$i]['user_name'] = $user_name;
            }
            if (!empty($brand_id)) {
                $brand_result = $oBrand->arrGetBrandDetails($brand_id, $category_id);
                $brand_name = $brand_result[0]['brand_name'];
                $seo_brand_path = $brand_result[0]['seo_path'];
                $modelArr[] = $brand_name;
            }
            $result[$i]['brand_name'] = $brand_name;
            if (!empty($model_id)) {
                $product_result = $oProduct->arrGetProductNameInfo($model_id, $category_id, $brand_id, "", "1", "", "", "", "", "", "1");
                $model_name = $product_result[0]['product_info_name'];
                $seo_model_path = $product_result[0]['seo_path'];
                $modelArr[] = $model_name;
            }
            $image_path = $product_result[0]['image_path'];
            if (!empty($image_path)) {
                $image_path = resizeImagePath($image_path, "251X188", $aModuleImageResize);
            }
            $result[$i]['image_path'] = $image_path ? CENTRAL_IMAGE_URL . $image_path : IMAGE_URL . 'no_image_251_188.gif';
            $result[$i]['model_name'] = $model_name;
            $result[$i]['brand_model_name'] = implode(" ", $modelArr);
            if (!empty($product_id)) {
                $product_result = $oProduct->arrGetProductDetails($product_id, $category_id, $brand_id, '1', "", "", "", "", "", "", "", "", "", "", '', "1");
                $variant = $product_result[0]['variant'];
                $seo_variant_path = $product_result[0]['seo_path'];
                $modelArr[] = $variant_name;
                $arrival_date = $product_result[0]['arrival_date'];
                $discontinue_date = $product_result[0]['discontinue_date'];
                unset($variantUrlYear);
                $variantUrlYear = buildYear($arrival_date, $discontinue_date);
            }
            $result[$i]['brand_model_variant_name'] = implode(" ", $modelArr);
            unset($modelArr);
            $result[$i]['variant'] = $variant;
            if ($title != "") {
                $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
                $result[$i]['title'] = $title;
            } else {
                $title = 'My experience with my ' . $result[$i]['brand_model_variant_name'];
                $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
                $result[$i]['title'] = $title;
            }
            $brand_name = html_entity_decode($brand_name, ENT_QUOTES, 'UTF-8');
            $brand_name = removeSlashes($brand_name);
            $brand_name = seo_title_replace($brand_name);
            $model = html_entity_decode($model_name, ENT_QUOTES, 'UTF-8');
            $model = removeSlashes($model);
            $model = seo_title_replace($model);
            $variant = html_entity_decode($variant, ENT_QUOTES, 'UTF-8');
            $variant = removeSlashes($variant);
            $variant = seo_title_replace($variant);
            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
            $seoTitleArr[] = $cat_path;
            $seoTitleArr[] = $seo_brand_path;
            $seoTitleArr[] = $seo_model_path;
            $seoTitleArr[] = $seo_variant_path;
            if (!empty($variantUrlYear)) {
                $seoTitleArr[] = $variantUrlYear;
            }
            $seoTitleArr[] = "user-reviews";
            $seo_url = implode("/", $seoTitleArr) . "?urevid=$user_review_id";
            $aViewCntUrl[$seo_url] = $result[$i]['user_review_id'];
            $aEncViewCntUrl[$seo_url] = $result[$i]['user_review_id'];
            $views_page_name = $seo_url;
            $aViewCnt = Array();
            $aViewCnt = $report->getPageViews($aViewCntUrl, $aEncViewCntUrl);
            $result[$i]['views_count'] = $aViewCnt[$result[$i]['user_review_id']];
            $views_count = $aViewCnt[$result[$i]['user_review_id']];
            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
            $seoTitleArr[] = $cat_path;
            $seoTitleArr[] = $seo_brand_path;
            $seoTitleArr[] = $seo_model_path;
            $seoTitleArr[] = $seo_variant_path;
            if (!empty($variantUrlYear)) {
                $seoTitleArr[] = $variantUrlYear;
            }
            $seoTitleArr[] = "user-reviews";
            $user_review_url = implode("/", $seoTitleArr);
            if (!empty($user_review_id)) {
                $user_review_url = "$user_review_url?urevid=$user_review_id";
            }
            $result[$i]["user_review_url"] = $user_review_url;
            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
            $seoTitleArr[] = $cat_path;
            $seoTitleArr[] = $seo_brand_path;
            $seoTitleArr[] = $seo_model_path;
            $seoTitleArr[] = $seo_variant_path;
            if (!empty($variantUrlYear)) {
                $seoTitleArr[] = $variantUrlYear;
            }
            $seoTitleArr[] = SEO_GET_ON_ROAD_PRICE;
            $seoTitleArr[] = $product_id;
            $on_road_url = implode("/", $seoTitleArr);
            $result[$i]["on_road_url"] = $on_road_url;
            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
            $seoTitleArr[] = $cat_path;
            $seoTitleArr[] = $seo_brand_path;
            $seoTitleArr[] = $seo_model_path;
            $seoTitleArr[] = $seo_variant_path;
            if (!empty($year)) {
                $seoTitleArr[] = $variantUrlYear;
            }
            $seoTitleArr[] = SEO_PRODUCT_FEATURE;
            $featuresspecs_url = implode("/", $seoTitleArr);
            $result[$i]["featuresspecs_url"] = $featuresspecs_url;
            $result[$i]["display_product_name"] = $brand_name . " " . $product_info_name . " " . $variant;
            foreach ($feature_sub_group_array as $key => $val) {
                if ($val['feature_name'] == "Fuel type") {
                    $feature_id = $val['feature_id'];
                    $fuel_feature_id = $val['feature_id'];
                    $fuel_result = $oProduct->arrGetProductFeatureDetails("", $feature_id, $product_id);
                    if (is_array($fuel_result)) {
                        $fuel_type = $fuel_result[0]['feature_value'];
                        $search_fuel_type = $fuel_result[0]['feature_value'];
                    }
                }
            }
            $result[$i]["fuel_type"] = $fuel_type;
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $xml .= "<USER_REVIEW_MASTER_DATA>";
            foreach ($result[$i] as $k => $v) {
                $xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $xml .= "</USER_REVIEW_MASTER_DATA>";
        }
        unset($seoTitleArr);
        $seoTitleArr[] = SEO_WEB_URL;
        $seoTitleArr[] = $cat_path;
        $seoTitleArr[] = "Car-User-Reviews";
        $user_review_seo_moreurl = implode("/", $seoTitleArr);
        $xml .= "<USER_REVIEW_SEO_MOREURL><![CDATA[" . $user_review_seo_moreurl . "]]></USER_REVIEW_SEO_MOREURL>";
        $cmd = PHP_PATH . ' ' . BASEPATH . "api/average_rating_api.php brand_id=$brand_id product_name_id=$product_name_id product_id=$product_id user_review_id=$user_review_id";
        $xml_output = shell_exec($cmd);
        $xml .= $xml_output;
        $xml .= "</USER_REVIEW_MASTER>";
//get user qna
        if (!empty($user_review_id)) {
            $result = $userreview->arrGetUserQnA('', '', $user_review_id, "1");
        }
        $cnt = sizeof($result);
        $xml .= "<USER_REVIEW_ANSWER_MASTER>";
        $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        for ($i = 0; $i < $cnt; $i++) {
            unset($que_result);
            $que_id = $result[$i]['que_id'];
            if (!empty($que_id)) {
                $que_result = $userreview->arrGetQuestions($que_id);
            }
            $result[$i]['quename'] = $que_result[0]['quename'];
            $answer = $result[$i]['answer'];
            $ansArr = explode(",", $answer);
            $gradeCnt = $result[$i]['grade'];
            $html = "";
            for ($grade = 1; $grade <= 5; $grade++) {
                if ($grade <= $gradeCnt) {
                    $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr mlr1 " />';
                } else {
                    $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr mlr1" />';
                }
            }
            $html_proportion = (($gradeCnt * 100) / 10) * 2;
            $result[$i]['grade_proportion'] = $html_proportion;
            $result[$i]['grade'] = $html;
            $result[$i]['grade_cnt'] = $gradeCnt;
            if (!empty($que_id)) {
                $ans_result = $userreview->arrGetQueAnswer("", $que_id);
            }
            $anscnt = sizeof($ans_result);
            $xml .= "<USER_REVIEW_ANSWER_MASTER_DATA>";
            $xml .= "<QUE_ANSWER_MASTER>";
            $xml .= "<COUNT><![CDATA[$anscnt]]></COUNT>";
            if ($anscnt > 0) {
                for ($ans = 0; $ans < $anscnt; $ans++) {
                    $ans_id = trim($ans_result[$ans]['ans_id']);
                    $html = "";
                    $ansCnt = $ansArr[$ans];
                    for ($grade = 1; $grade <= 5; $grade++) {
                        if ($grade <= $ansCnt) {
                            $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="starOn"/>';
                        } else {
                            $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="starOff"/>';
                        }
                    }
                    $ans_result[$ans]['selected_answer'] = $html;
                    $ans_proportion = ((($ansCnt * 100) / 10) * 2) + 0.66;
                    $ans_result[$ans]['selected_answer_proportion'] = $ans_proportion;
                    $ans_result[$ans] = array_change_key_case($ans_result[$ans], CASE_UPPER);
                    $xml .= "<QUE_ANSWER_MASTER_DATA>";
                    foreach ($ans_result[$ans] as $key => $val) {
                        $xml .= "<$key><![CDATA[$val]]></$key>";
                    }
                    $xml .= "</QUE_ANSWER_MASTER_DATA>";
                }
            }
            $xml .= "</QUE_ANSWER_MASTER>";
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            foreach ($result[$i] as $k => $v) {
                $xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $xml .= "</USER_REVIEW_ANSWER_MASTER_DATA>";
        }
        $xml .= "</USER_REVIEW_ANSWER_MASTER>";
        if (!empty($user_review_id)) {
            $result = $userreview->arrGetUserQnA('', '', $user_review_id, "0", "1");
        }
        $cnt = sizeof($result);
        $xml .= "<USER_REVIEW_COMMENT_ANSWER_MASTER>";
        for ($i = 0; $i < $cnt; $i++) {
            $que_id = $result[$i]['que_id'];
            if (!empty($que_id)) {
                $que_result = $userreview->arrGetQuestions($que_id);
            }
            $result[$i]['quename'] = $que_result[0]['quename'];
            $result[$i]['answer'] = nl2br($result[$i]['answer']);
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $xml .= "<USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
            foreach ($result[$i] as $k => $v) {
                $xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $xml .= "</USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
        }
        $xml .= "</USER_REVIEW_COMMENT_ANSWER_MASTER>";
        
        //used to check admin rating.
        if (!empty($product_id)) {
            $result = $userreview->arrGetAdminOverallGrade($category_id, $brand_id, $product_id);
        }
        $cnt = sizeof($result);
        $overallcnt = 0;
        $overallavg = round($result[0]['overallgrade']);
        if ($cnt <= 0) {
            if (!empty($product_id)) {
                $result = $userreview->arrGetOverallGrade($category_id, $brand_id, $product_id, "", "1", $user_review_id);
            }
            $overallcnt = $result[0]['totaloverallcnt'];
            $overallavg = round($result[0]['overallavg']);
        }
        $html = "";
        for ($grade = 1; $grade <= 5; $grade++) {
            if ($grade <= $overallavg) {
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
            } else {
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
            }
        }
        if (!empty($user_review_id)) {
            $resultoption = $userreview->GetUserReviewOptions("", $user_review_id, $category_id);
        }
        $oCount = sizeof($resultoption);
        if ($oCount > 0) {
            $like_yes = $resultoption[0]['like_yes'];
            $like_no = $resultoption[0]['like_no'];
            $tot_cnt = $like_yes + $like_no;
            $optionhtml .= "<REVIEW_RATE_OPTION>";
            $optionhtml .= "<REVIEW_RATE_OPTION_TOTAL_COUNT>$tot_cnt</REVIEW_RATE_OPTION_TOTAL_COUNT>";
            $optionhtml .= "<REVIEW_RATE_OPTION_YES>$like_yes</REVIEW_RATE_OPTION_YES>";
            $optionhtml .= "<REVIEW_RATE_OPTION_NO>$like_no</REVIEW_RATE_OPTION_NO>";
            $optionhtml .= "</REVIEW_RATE_OPTION>";
        }
        $html_proportion = (($overallavg * 100) / 10) * 2;
        $optionhtml .= "<REVIEW_RATE_OVERALL_DET_IMAGE><![CDATA[" . $html . "]]></REVIEW_RATE_OVERALL_DET_IMAGE>";
        ///////user review list of same fuel type start///////
        
        $product_model_name = html_entity_decode($product_info_name, ENT_QUOTES, 'UTF-8');
        $curpage = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
        $request_uri = $_SERVER['REQUEST_URI'];
        $limit = USER_REVIEW_PAGING;
        $oPager = new Pager();
        if (!isset($start)) {
            $start1 = $oPager->findStart($limit);
        } else {
            $start1 = $start;
        }
        if (!empty($product_name_id)) {
            $count = $userreview->arrGetUserReviewDetailsByFuelCount("", "", "", "", "", $brand_id, $category_id, $product_name_id, "", "1", $search_fuel_type, $user_review_id);
        }
        $pages = $oPager->findPages($count, $limit);
        $siteUrl = WEB_URL . constructUrl($product_brand_name) . "/" . constructUrl($product_model_name) . "/" . constructUrl($product_variant_name) . "/user-reviews";
        unset($seoTitleArr);
        $seoTitleArr[] = SEO_WEB_URL;
        $seoTitleArr[] = $cat_path;
        $seoTitleArr[] = $seo_brand_path;
        $seoTitleArr[] = $seo_model_path;
        $seoTitleArr[] = $seo_variant_path;
        $seoTitleArr[] = user - reviews;
        $siteUrl = implode("/", $seoTitleArr);
        unset($nextViewxml);
        for ($i = 1; $i < $pages; $i++) {
            $nextViewxml1 .= "<MODEL_NEXT_VIEW_DATA><NEXT_VIEW><![CDATA[" . $i * $limit . "]]></NEXT_VIEW></MODEL_NEXT_VIEW_DATA>";
        }
        if (!empty($product_name_id)) {
            $result = $userreview->arrGetUserReviewDetailsByFuel("", "", "", "", "", $brand_id, $category_id, $product_name_id, "", "1", $start1, $limit, "", "", $search_fuel_type, $user_review_id);
        }
        $cnt = sizeof($result);
        for ($k = 0; $k < $cnt; $k++) {
            $user_review_id = $result[$k]['user_review_id'];
            $brand_id = $result[$k]['brand_id'];
            $model_id = $result[$k]['product_info_id'];
            $product_id = $result[$k]['product_id'];
            $seo_product_path = $result[$k]['seo_path'];
            if (!empty($brand_id)) {
                $brand_result = $oBrand->arrGetBrandDetails($brand_id, $category_id);
                $brand_name = $brand_result[0]['brand_name'];
                $seo_brand_path = $brand_result[0]['seo_path'];
            }
            if (!empty($model_id)) {
                $product_result = $oProduct->arrGetProductNameInfo($model_id, $category_id, $brand_id, "", "1", "", "", "", "", "", "1");
                $model_name = $product_result[0]['product_info_name'];
                $seo_model_path = $product_result[0]['seo_path'];
            }
            if (!empty($product_id)) {
                $product_result = $oProduct->arrGetProductDetails($product_id, $category_id, $brand_id, '1', "", "", "", "", "", "", "", "", "", "", '', "1");
                $variant = $product_result[0]['variant'];
                $seo_variant_path = $product_result[0]['seo_path'];
                $arrival_date = $product_result[0]['arrival_date'];
                $discontinue_date = $product_result[0]['discontinue_date'];
                unset($variantUrlYear);
                $variantUrlYear = buildYear($arrival_date, $discontinue_date);
                $variant = removeSlashes($variant);
                $variant = seo_title_replace($variant);
            }
            $result[$i]['brand_model_variant_name'] = implode(" ", $modelArr);
            unset($modelArr);
            $product_model_name = html_entity_decode($model_name, ENT_QUOTES, 'UTF-8');
            $product_model_name = removeSlashes($product_model_name);
            $product_model_name = seo_title_replace($product_model_name);
            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
            $seoTitleArr[] = $cat_path;
            $seoTitleArr[] = $seo_brand_path;
            $seoTitleArr[] = $seo_model_path;
            $seoTitleArr[] = $seo_variant_path;
            if (!empty($variantUrlYear)) {
                $seoTitleArr[] = $variantUrlYear;
            }
            $seoTitleArr[] = "user-reviews";
            $seo_url = implode("/", $seoTitleArr);
            if (!empty($user_review_id)) {
                $seo_url = "$seo_url?urevid=$user_review_id";
            }
            $aViewCntUrl[$seo_url] = $result[$k]['user_review_id'];
            $aEncViewCntUrl[$seo_url] = $result[$k]['user_review_id'];
            $aMBList[] = $result[$k]['user_review_id'];
        }
        //get pageviews from API
        $aViewCnt = Array();
        $aViewCnt = $report->getPageViews($aViewCntUrl, $aEncViewCntUrl);
        $aComParameters = array();
        $aMBData = array();
        $aComParameters = Array("title" => implode(",", $aMBList), "cid" => USER_REVIEW_VARIANT_CATEGORY_ID, "sid" => SERVICEID);
        $aMBData = $oCampusDiscussion->getMulThreadParentReplyCnt($aComParameters);

        
        $listxml = "<MODEL_USER_REVIEW_MASTER>";
        $listxml .= "<TOTCOUNT><![CDATA[$count]]></TOTCOUNT>";
        $listxml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        $listxml .= "<MODEL_NEXT_VIEW_MASTER>$nextViewxml1</MODEL_NEXT_VIEW_MASTER>";
        $listxml .= "<SEARCH_FUEL_TYPE><![CDATA[$search_fuel_type]]></SEARCH_FUEL_TYPE>";
        $listxml .= "<CURR_USER_REVIEW_ID><![CDATA[$user_review_id]]></CURR_USER_REVIEW_ID>";
        for ($i = 0; $i < $cnt; $i++) {
            $user_review_id = $result[$i]['user_review_id'];
            $SERVICEID = SERVICEID;
            $USER_REVIEW_VARIANT_CATEGORY_ID = USER_REVIEW_VARIANT_CATEGORY_ID;
            $comment_count = $aMBData['data'][$user_review_id][$USER_REVIEW_VARIANT_CATEGORY_ID][$SERVICEID];
            if (!empty($comment_count) || $comment_count != 0) {
                $result[$i]['comment_count'] = $comment_count;
            }
            if (isset($aViewCnt) && $aViewCnt[$user_review_id] != '') {
                $result[$i]['views_count'] = $aViewCnt[$user_review_id];
            }
            $brand_id = $result[$i]['brand_id'];
            $model_id = $result[$i]['product_info_id'];
            $product_id = $result[$i]['product_id'];
            $category_id = $result[$i]['category_id'];
            $create_date = $result[$i]['create_date'];
            $result[$i]['create_date'] = date('d F Y', strtotime($create_date));
            $result[$i]['createdate'] = date('d', strtotime($create_date));
            $result[$i]['createdate_mon_year'] = date('F/y', strtotime($create_date));
            if (!empty($brand_id)) {
                $brand_result = $oBrand->arrGetBrandDetails($brand_id, $category_id);
                $brand_name = $brand_result[0]['brand_name'];
                $seo_brand_path = $brand_result[0]['seo_path'];
                $modelArr[] = $brand_name;
            }
            $result[$i]['brand_name'] = $brand_name;
            if (!empty($model_id)) {
                $product_result = $oProduct->arrGetProductNameInfo($model_id, $category_id, $brand_id, "", "1", "", "", "", "", "", "1");
                $model_name = $product_result[0]['product_info_name'];
                $seo_model_path = $product_result[0]['seo_path'];
                $modelArr[] = $model_name;
            }
            $image_path = $product_result[0]['image_path'];
            if (!empty($image_path)) {
                $image_path = resizeImagePath($image_path, "160X120", $aModuleImageResize);
            }
            $result[$i]['image_path'] = $image_path ? CENTRAL_IMAGE_URL . $image_path : IMAGE_URL . 'no_image_160X120.gif';
            $result[$i]['model_name'] = $model_name;
            $result[$i]['brand_model_name'] = implode(" ", $modelArr);
            if (!empty($product_id)) {
                $product_result = $oProduct->arrGetProductDetails($product_id, $category_id, $brand_id, '1', "", "", "", "", "", "", "", "", "", "", '', "1");
                $variant = $product_result[0]['variant'];
                $seo_variant_path = $product_result[0]['seo_path'];
                $arrival_date = $product_result[0]['arrival_date'];
                $discontinue_date = $product_result[0]['discontinue_date'];
                $variantUrlYear = buildYear($arrival_date, $discontinue_date);
                $modelArr[] = $variant;
            }
            $result[$i]['brand_model_variant_name'] = implode(" ", $modelArr);
            unset($modelArr);
            $result[$i]['variant'] = $variant;
//get the reply count
            $aParameters = Array("title" => $user_review_id, "cid" => USER_REVIEW_VARIANT_CATEGORY_ID, "sid" => SERVICEID);
            $aMBReplyCnt = $oCampusDiscussion->getMBDetails($aParameters);
            $sReplyXml = $oCampusDiscussion->getReply(array("tid" => $aMBReplyCnt['tid'], "rowcnt" => 1, "start" => 0));
            $pos = strpos($sReplyXml, "<response>");
            $sReplyXml = substr($sReplyXml, $pos);
//header('Content-type: text/xml'); echo $sReplyXml; die;
            $listxml .= "<MODEL_USER_REVIEW_MASTER_DATA>";
            $listxml .= $sReplyXml;
            if (!empty($user_review_id)) {
                $ratingresult = $userreview->arrGetUserQnA('', '', $user_review_id, "1");
            }
            $ratingcnt = sizeof($ratingresult);
            $listxml .= "<MODEL_USER_RATING_MASTER>";
            for ($rating = 0; $rating < $ratingcnt; $rating++) {
                $que_id = $ratingresult[$rating]['que_id'];
                $que_result = $userreview->arrGetQuestions($que_id);
                $ratingresult[$rating]['quename'] = $que_result[0]['quename'];
                $answer = $ratingresult[$rating]['answer'];
                $ansArr = explode(",", $answer);
                $gradeCnt = $ratingresult[$rating]['grade'];
                $html = "";
                for ($grade = 1; $grade <= 5; $grade++) {
                    if ($grade <= $gradeCnt) {
                        $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="starOn" />';
                    } else {
                        $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="starOff" />';
                    }
                }
                $ratingresult[$rating]['grade'] = $html;
                $listxml .= "<MODEL_USER_RATING_MASTER_DATA>";
                $ratingresult[$rating] = array_change_key_case($ratingresult[$rating], CASE_UPPER);
                foreach ($ratingresult[$rating] as $k => $v) {
                    $listxml .= "<$k><![CDATA[$v]]></$k>";
                }
                $listxml .= "</MODEL_USER_RATING_MASTER_DATA>";
            }
            $listxml .= "</MODEL_USER_RATING_MASTER>";
            if (!empty($user_review_id)) {
                $reviewresult = $userreview->arrGetUserQnA('', '', $user_review_id, "0", "1"); // for comment
            }
            $reviewcnt = sizeof($reviewresult);
            $listxml .= "<MODEL_USER_REVIEW_COMMENT_ANSWER_MASTER>";
            for ($review = 0; $review < $reviewcnt; $review++) {
                $que_id = $reviewresult[$review]['que_id'];
                $answer = $reviewresult[$review]['answer'];
                $answer = removeSlashes($answer);
                $answer = html_entity_decode($answer, ENT_QUOTES, 'UTF-8');
                if (strlen($answer) > 100) {
                    $answer = getCompactString($answer, 95) . ' ...';
                }
                $reviewresult[$review]['answer'] = $answer;
                $que_result = $userreview->arrGetQuestions($que_id);
                $reviewresult[$review]['quename'] = $que_result[0]['quename'];
                $reviewresult[$review] = array_change_key_case($reviewresult[$review], CASE_UPPER);
                $listxml .= "<MODEL_USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
                foreach ($reviewresult[$review] as $k => $v) {
                    $listxml .= "<$k><![CDATA[$v]]></$k>";
                }
                $listxml .= "</MODEL_USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
            }
            $listxml .= "</MODEL_USER_REVIEW_COMMENT_ANSWER_MASTER>";
            $product_model_name = html_entity_decode($model_name, ENT_QUOTES, 'UTF-8');
            unset($res);
            $res = $userreview->arrGetOverallGrade($category_id, $brand_id, $product_id, $product_info_id, '1', $user_review_id);
            $overallcnt = $res[0]['totaloverallcnt'];
            $overallavg = round($res[0]['overallavg']);
            $html = "";
            for ($grade = 1; $grade <= 5; $grade++) {
                if ($grade <= $overallavg) {
                    $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
                } else {
                    $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
                }
            }
            $html_proportion = (($overallavg * 100) / 10) * 2;
            $cmd = PHP_PATH . ' ' . BASEPATH . "api/average_rating_api.php brand_id=$brand_id product_name_id=$product_info_id product_id=$product_id user_review_id=$user_review_id";
            $xml_output = shell_exec($cmd);
            $listxml .= $xml_output;
            $listxml .= "<MODEL_OVERALL_AVG_HTML><![CDATA[$html]]></MODEL_OVERALL_AVG_HTML>";
            $listxml .= "<MODEL_OVERALL_TOTAL_CNT><![CDATA[$overallcnt]]></MODEL_OVERALL_TOTAL_CNT>";
            if (!empty($user_review_id)) {
                $resultoption = $userreview->GetUserReviewOptions("", $user_review_id, $category_id);
            }
            $oCount = sizeof($resultoption);
            $listxml .= "<MODEL_REVIEW_RATE_OPTION>";
            $like_yes = 0;
            $like_no = 0;
            $tot_cnt = 0;
            if ($oCount > 0) {
                $like_yes = $resultoption[0]['like_yes'];
                $like_no = $resultoption[0]['like_no'];
                $tot_cnt = $like_yes + $like_no;
            }
            $listxml .= "<MODEL_REVIEW_RATE_OPTION_TOTAL_COUNT>$tot_cnt</MODEL_REVIEW_RATE_OPTION_TOTAL_COUNT>";
            $listxml .= "<MODEL_REVIEW_RATE_OPTION_YES>$like_yes</MODEL_REVIEW_RATE_OPTION_YES>";
            $listxml .= "<MODEL_REVIEW_RATE_OPTION_NO>$like_no</MODEL_REVIEW_RATE_OPTION_NO>";
            $listxml .= "</MODEL_REVIEW_RATE_OPTION>";
//seo user review
            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
            $seoTitleArr[] = $cat_path;
            $seoTitleArr[] = $seo_brand_path;
            $seoTitleArr[] = $seo_model_path;
            $seoTitleArr[] = $seo_variant_path;
            if (!empty($variantUrlYear)) {
                $seoTitleArr[] = $variantUrlYear;
            }
            $seoTitleArr[] = "user-reviews";
            $user_review_url = implode("/", $seoTitleArr);
            if (!empty($user_review_id)) {
                $user_review_url = "$user_review_url?urevid=$user_review_id";
            }
            $result[$i]["user_review_url"] = $user_review_url;
            foreach ($feature_sub_group_array as $key => $val) {
                if ($val['feature_name'] == "Fuel type") {
                    $feature_id = $val['feature_id'];
                    $fuel_feature_id = $val['feature_id'];
                    $fuel_result = $oProduct->arrGetProductFeatureDetails("", $feature_id, $product_id);
                    if (is_array($fuel_result)) {
                        $fuel_type = $fuel_result[0]['feature_value'];
                    }
                }
            }
            $result[$i]["fuel_type"] = $fuel_type;
            $result[$i]["display_product_name"] = $brand_name . " " . $product_info_name . " " . $product_variant_name;
            $result[$i]['user_review_seo_url'] = $user_review_url;
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            foreach ($result[$i] as $k => $v) {
                $listxml .= "<$k><![CDATA[$v]]></$k>";
            }
            $listxml .= "</MODEL_USER_REVIEW_MASTER_DATA>";
        }
        unset($seoTitleArr);
        $seoTitleArr[] = SEO_WEB_URL;
        $seoTitleArr[] = $cat_path;
        $seoTitleArr[] = "Car-User-Reviews";
        $user_review_seo_moreurl = implode("/", $seoTitleArr);
        $listxml .= "<MODEL_USER_REVIEW_SEO_MOREURL><![CDATA[" . $user_review_seo_moreurl . "]]></MODEL_USER_REVIEW_SEO_MOREURL>";
        $listxml .= "</MODEL_USER_REVIEW_MASTER>";
        
 /*       if (!empty($product_id)) {
            $result = $userreview->arrGetAdminOverallGrade($category_id, $brand_id, $product_id);
        }
        $cnt = sizeof($result);
        $overallcnt = 0;
        $overallavg = round($result[0]['overallgrade']);
        if ($cnt <= 0) {
            $result = $userreview->arrGetOverallGrade($category_id, $brand_id, $product_id);
            $overallcnt = $result[0]['totaloverallcnt'];
            $overallavg = round($result[0]['overallavg']);
        }
        $html = "";
        for ($grade = 1; $grade <= 5; $grade++) {
            if ($grade <= $overallavg) {
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
            } else {
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
            }
        }
        $optionhtml .= "<MODEL_REVIEW_RATE_OVERALL_LIST>$overallavg</MODEL_REVIEW_RATE_OVERALL_LIST>";
        $optionhtml .= "<MODEL_REVIEW_RATE_OVERALL_LIST_IMAGE><![CDATA[" . $html . "]]></MODEL_REVIEW_RATE_OVERALL_LIST_IMAGE>";
///////user review list of same fuel type end /////
    } else {
//variant user review list start//
//echo $reviewName."NAME213<br>";
        
        unset($result);
        unset($limit);
        unset($count);
        unset($cnt);
        $limit = USER_REVIEW_PAGING;
        $oPager = new Pager();
        $start = $_REQUEST['start'];
        if (!isset($start)) {
            $start1 = $oPager->findStart($limit);
        } else {
            $start1 = $start;
        }
        if (!empty($product_name_id) && !empty($product_id)) {
            $count = $userreview->arrGetUserReviewDetailsCount("", "", "", "", "", $brand_id, $category_id, $product_name_id, $product_id, "1");
        }
        $pages = $oPager->findPages($count, $limit);
        for ($i = 1; $i < $pages; $i++) {
            $nextViewxml .= "<NEXT_VIEW_DATA><NEXT_VIEW><![CDATA[" . $i * $limit . "]]></NEXT_VIEW></NEXT_VIEW_DATA>";
        }
        if (!empty($product_name_id)) {
            $result = $userreview->arrGetUserReviewDetails("", "", "", "", "", $brand_id, $category_id, $product_name_id, $product_id, "1", $start1, $limit);
        }
        $cnt = sizeof($result);
        
        $xml = "<USER_REVIEW_MASTER>";
        $xml .= "<TOTCOUNT><![CDATA[$count]]></TOTCOUNT>";
        $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        $xml .= "<NEXT_VIEW_MASTER>$nextViewxml</NEXT_VIEW_MASTER>";
        for ($i = 0; $i < $cnt; $i++) {
            $user_review_id = $result[$i]['user_review_id'];
            $SERVICEID = SERVICEID;
            $USER_REVIEW_VARIANT_CATEGORY_ID = USER_REVIEW_VARIANT_CATEGORY_ID;
            $brand_id = $result[$i]['brand_id'];
            $model_id = $result[$i]['product_info_id'];
            $product_id = $result[$i]['product_id'];
//$category_id = $result[$i]['category_id'];
            $create_date = $result[$i]['create_date'];
            if (!empty($brand_id)) {
                $brand_result = $oBrand->arrGetBrandDetails($brand_id, $category_id);
                $brand_name = $brand_result[0]['brand_name'];
                $seo_brand_path = $brand_result[0]['seo_path'];
                $modelArr[] = $brand_name;
            }
            $result[$i]['brand_name'] = $brand_name;
            if (!empty($model_id)) {
                $product_result = $oProduct->arrGetProductNameInfo($model_id, $category_id, $brand_id, "", "1", "", "", "", "", "", "1");
                $model_name = $product_result[0]['product_info_name'];
                $seo_model_path = $product_result[0]['seo_path'];
                $modelArr[] = $model_name;
            }
            $result[$i]['model_name'] = $model_name;
            $result[$i]['brand_model_name'] = implode(" ", $modelArr);
            if (!empty($product_id)) {
                $product_result = $oProduct->arrGetProductDetails($product_id, $category_id, $brand_id, '1', "", "", "", "", "", "", "", "", "", "", '', "1");
                $variant = $product_result[0]['variant'];
                $seo_variant_path = $product_result[0]['seo_path'];
                $arrival_date = $product_result[0]['arrival_date'];
                $discontinue_date = $product_result[0]['discontinue_date'];
                $variantUrlYear = buildYear($arrival_date, $discontinue_date);
                $modelArr[] = $variant_name;
            }
            $result[$i]['brand_model_variant_name'] = implode(" ", $modelArr);
            unset($modelArr);
            $result[$i]['variant'] = $variant;
            $create_date = $result[$i]['create_date'];
            if ($create_date != "") {
                $result[$i]['create_date'] = date('d M Y', strtotime($create_date));
                $result[$i]['createdate'] = date('d', strtotime($create_date));
                $result[$i]['createdate_mon_year'] = date('F/y', strtotime($create_date));
            } else {
                $result[$i]['createdate'] = "";
                $result[$i]['createdate_mon_year'] = "";
            }
            $result[$i]['display_product_name'] = $brand_name . " " . $model_name . " " . $variant;
//get the reply count
            $aParameters = Array("title" => $user_review_id, "cid" => USER_REVIEW_VARIANT_CATEGORY_ID, "sid" => SERVICEID);
            $aMBReplyCnt = $oCampusDiscussion->getMBDetails($aParameters);
            $sReplyXml = $oCampusDiscussion->getReply(array("tid" => $aMBReplyCnt['tid'], "rowcnt" => 1, "start" => 0));
            $pos = strpos($sReplyXml, "<response>");
            $sReplyXml = substr($sReplyXml, $pos);
//header('Content-type: text/xml'); echo $sReplyXml; die;
// number of comments
            
            $iRecCnt = $aMBReplyCnt['reply_cnt'];
            $xml .= "<USER_REVIEW_MASTER_DATA>";
            $xml .= $sReplyXml;
            $ratingresult = $userreview->arrGetUserQnA('', '', $user_review_id, "1");
            $ratingcnt = sizeof($ratingresult);
            $xml .= "<USER_RATING_MASTER>";
            for ($rating = 0; $rating < $ratingcnt; $rating++) {
                $que_id = $ratingresult[$rating]['que_id'];
                if (!empty($que_id)) {
                    $que_result = $userreview->arrGetQuestions($que_id);
                }
                $ratingresult[$rating]['quename'] = $que_result[0]['quename'];
                $answer = $ratingresult[$rating]['answer'];
                $ansArr = explode(",", $answer);
                $gradeCnt = $ratingresult[$rating]['grade'];
                $html = "";
                for ($grade = 1; $grade <= 5; $grade++) {
                    if ($grade <= $gradeCnt) {
                        $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="starOn"/>';
                    } else {
                        $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="starOff"/>';
                    }
                }
                $ratingresult[$rating]['grade'] = $html;
                $xml .= "<USER_RATING_MASTER_DATA>";
                $ratingresult[$rating] = array_change_key_case($ratingresult[$rating], CASE_UPPER);
                foreach ($ratingresult[$rating] as $k => $v) {
                    $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</USER_RATING_MASTER_DATA>";
            }
            $xml .= "</USER_RATING_MASTER>";
            if (!empty($user_review_id)) {
                $reviewresult = $userreview->arrGetUserQnA('', '', $user_review_id, "0", "1", "0", "1"); // for comment
            }
            $reviewcnt = sizeof($reviewresult);
            $xml .= "<USER_REVIEW_COMMENT_ANSWER_MASTER>";
            for ($review = 0; $review < $reviewcnt; $review++) {
                $que_id = $reviewresult[$review]['que_id'];
                $answer = $reviewresult[$review]['answer'];
                $answer = removeSlashes($answer);
                $answer = html_entity_decode($answer, ENT_QUOTES, 'UTF-8');
                if (strlen($answer) > 100) {
                    $answer = getCompactString($answer, 95) . ' ...';
                }
                $reviewresult[$review]['answer'] = $answer;
                $que_result = $userreview->arrGetQuestions($que_id);
                $reviewresult[$review]['quename'] = $que_result[0]['quename'];
                $reviewresult[$review] = array_change_key_case($reviewresult[$review], CASE_UPPER);
                $xml .= "<USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
                foreach ($reviewresult[$review] as $k => $v) {
                    $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
            }
            $xml .= "</USER_REVIEW_COMMENT_ANSWER_MASTER>";
            $product_model_name = html_entity_decode($model_name, ENT_QUOTES, 'UTF-8');
            unset($res);
            $res = $userreview->arrGetOverallGrade($category_id, "", "", "", '1', $user_review_id);
            $overallcnt = $res[0]['totaloverallcnt'];
            $overallavg = round($res[0]['overallavg']);
            $html = "";
            for ($grade = 1; $grade <= 5; $grade++) {
                if ($grade <= $overallavg) {
                    $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
                } else {
                    $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
                }
            }
            $html_proportion = (($overallavg * 100) / 10) * 2;
            $cmd = PHP_PATH . ' ' . BASEPATH . "api/average_rating_api.php brand_id=$rating_brand_id product_name_id=$model_id product_id=$product_id user_review_id=$user_review_id";
            $xml_output = shell_exec($cmd);
            $xml .= $xml_output;
            $xml .= "<OVERALL_AVG_HTML><![CDATA[$html]]></OVERALL_AVG_HTML>";
            $xml .= "<OVERALL_TOTAL_CNT><![CDATA[$overallcnt]]></OVERALL_TOTAL_CNT>";
            if (!empty($user_review_id)) {
                $resultoption = $userreview->GetUserReviewOptions("", $user_review_id, $category_id);
            }
            $oCount = sizeof($resultoption);
            $xml .= "<REVIEW_RATE_OPTION>";
            $like_yes = 0;
            $like_no = 0;
            $tot_cnt = 0;
            if ($oCount > 0) {
                $like_yes = $resultoption[0]['like_yes'];
                $like_no = $resultoption[0]['like_no'];
                $tot_cnt = $like_yes + $like_no;
            }
            $xml .= "<REVIEW_RATE_OPTION_TOTAL_COUNT>$tot_cnt</REVIEW_RATE_OPTION_TOTAL_COUNT>";
            $xml .= "<REVIEW_RATE_OPTION_YES>$like_yes</REVIEW_RATE_OPTION_YES>";
            $xml .= "<REVIEW_RATE_OPTION_NO>$like_no</REVIEW_RATE_OPTION_NO>";
            $xml .= "</REVIEW_RATE_OPTION>";
            $fuel_type = "";
            $aOverview = $oFeature->arrGetSummary($category_id, $product_id, $type = "array");
            foreach ($aOverview as $key => $val) {
                if (!strpos($key, 'Price') && !strpos($key, 'Feature')) {
                    unset($overviewArr);
                    unset($summery);
                    foreach ($aOverview[$key] as $overviewtitle => $overviewvalueArr) {
                        $overviewvalueArr = array_change_key_case($overviewvalueArr, CASE_UPPER);
                        if (strtolower($overviewtitle) == strtolower('Fuel type')) {
                            $fuel_type = $overviewvalueArr[0];
                        }
                    }
                    unset($overviewvalueArr);
                }
            }
            $result[$i]['fuel_type'] = $fuel_type;
            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
            $seoTitleArr[] = $cat_path;
            $seoTitleArr[] = $seo_brand_path;
            $seoTitleArr[] = $seo_model_path;
            $seoTitleArr[] = $seo_variant_path;
            if (!empty($variantUrlYear)) {
                $seoTitleArr[] = $variantUrlYear;
            }
            $seoTitleArr[] = "user-reviews";
            $user_review_url = implode("/", $seoTitleArr);
            if (!empty($user_review_id)) {
                $user_review_url = "$user_review_url?urevid=$user_review_id";
            }
            $result[$i]["user_review_seo_url"] = $user_review_url;
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            foreach ($result[$i] as $k => $v) {
                $xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $xml .= "</USER_REVIEW_MASTER_DATA>";
        }
        $xml .= "</USER_REVIEW_MASTER>";
        
//header('Content-type: text/xml'); echo $xml; die;
//used to check admin rating.
        /*if (!empty($model_id)) {
            $result = $userreview->arrGetAdminOverallGrade($category_id, $brand_id, '0', $model_id);
        }
        $cnt = sizeof($result);
        $overallcnt = 0;
        $overallavg = round($result[0]['overallgrade']);
        if ($cnt <= 0) {
            if (!empty($product_name_id)) {
                $result = $userreview->arrGetOverallGrade($category_id, $rating_brand_id, '0', $product_name_id);
                $overallcnt = $result[0]['totaloverallcnt'];
                $overallavg = round($result[0]['overallavg']);
            }
        }
        $html = "";
        for ($grade = 1; $grade <= 5; $grade++) {
            if ($grade <= $overallavg) {
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
            } else {
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
            }
        }
    }//variant user review list end//
}*/



unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $cat_path;
$seoTitleArr[] = $seo_brand_path;
$seoTitleArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
    $seoTitleArr[] = $seo_variant_path;
}

if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seoTitleArr[] = "user-reviews";
$userreview_seo_url = implode("/", $seoTitleArr);

$aBrandDetail = $oBrand->arrGetBrandDetails("", $category_id);
$sBrandDataDet = arraytoxml($aBrandDetail, "BRAND_DETAIL_DATA");
$sBrandDataDetXML = "<BRAND_DETAIL>" . $sBrandDataDet . "</BRAND_DETAIL>";

unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $cat_path;
$seoTitleArr[] = $seo_brand_path;
$seoTitleArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
    $seoTitleArr[] = $seo_variant_path;
}
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seoTitleArr[] = "expert-reviews";
$oncars_group_seo_url = implode("/", $seoTitleArr);
//get the message board data
if ($action === 'expert_review_detail') {
    $revid = $req_review_id;
} else if ($action === 'user_review' || $action == 'overviews') {
    $rev_category_id = USER_REVIEW_VARIANT_CATEGORY_ID;
    $iRecCnt = $comment_result[0]['comment_count'];
    $iTId = $comment_result[0]['comment_board_id'];
    $sRequestUrl = WEB_URL . substr($_SERVER['REQUEST_URI'], 1);
    $aParameters = Array("title" => $curr_user_review_id, "turl" => $sRequestUrl, "cid" => USER_REVIEW_VARIANT_CATEGORY_ID, "sid" => SERVICEID);
    $comment_result = $report->intInsertUpdateCommentCount($aParameters);
    $iRecCnt = $comment_result['comment_count'];
    $iTId = $comment_result['iTId'];
    if ($iRecCnt > 0) {
//get the reply list
        $iRecordPerPage = MBOARD_COMMENTS_PER_PAGE;
//get the reply count
        $aParameters = Array("title" => $curr_user_review_id, "cid" => USER_REVIEW_VARIANT_CATEGORY_ID, "sid" => SERVICEID);
        $aMBReplyCnt = $oCampusDiscussion->getMBDetails($aParameters);
        $iRecCnt = $aMBReplyCnt['reply_cnt'];
        unset($seoTitleArr);
        $seoTitleArr[] = SEO_WEB_URL;
        $seoTitleArr[] = constructUrl($product_brand_name);
        $seoTitleArr[] = constructUrl($product_info_name);
        $seoTitleArr[] = constructUrl($product_variant_name);
        if (!empty($productvariantUrlYear)) {
            $seoTitleArr[] = $productvariantUrlYear;
        }
        $seoTitleArr[] = "user-reviews";
        unset($user_review_url);
        $user_review_url = implode("/", $seoTitleArr);
        if (!empty($user_review_id)) {
            $user_review_url = "$user_review_url?urevid=$user_review_id";
        }
        $page_url = $user_review_url;
        if ($iRecCnt != 0) {
            if ($_REQUEST['pagination'] == 1) {
                $sTemplate = "xsl/mboard_pagination.xsl";
            }
            $page = 1;
            if (isset($_REQUEST['page'])) {
                $page = $_REQUEST['page'];
            }
            $page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
            $page_url = $page_url . "/1/" . $iTId;
            $start = $ObjPager->findStart($iRecordPerPage);
            $jsparams = $start . "," . $iRecordPerPage . "," . $page_url . ",qcontainer,$user_review_id/$iTId";
            $pages = $ObjPager->findPages($iRecCnt, $iRecordPerPage);
            if ($pages > 1) {
                $pagelist = $ObjPager->jsPageNumNextPrev($page, $pages, "mBPagination_new", $jsparams, "text");
                $nodesPaging .= "<Pages><![CDATA[" . $pagelist . "]]></Pages>";
                $nodesPaging .= "<Page><![CDATA[" . $page . "]]></Page>";
                $nodesPaging .= "<Perpage><![CDATA[" . $perpage . "]]></Perpage>";
            }
            $sReplyXml = $oCampusDiscussion->getReply(array("tid" => $iTId, "rowcnt" => $iRecordPerPage, "start" => $start));
            $pos = strpos($sReplyXml, "<response>");
            $sReplyXml = substr($sReplyXml, $pos);
//header('Content-type:text/xml'); echo $sReplyXml; die;
        }
    }
    if ($iRecCnt != 0) {
        if ($_REQUEST['pagination'] == 1) {
            $sTemplate = "xsl/mboard_pagination.xsl";
        }
    }
}
unset($seoTitleArr);
$product_info_name = seo_title_replace($product_info_name);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $cat_path;
$seoTitleArr[] = $seo_brand_path;
$seoTitleArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
    $seoTitleArr[] = $seo_variant_path;
}
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seo_photo_tab_url = implode("/", $seoTitleArr);

unset($seoTitleArr);
$product_info_name = seo_title_replace($product_info_name);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $cat_path;
$seoTitleArr[] = $seo_brand_path;
$seoTitleArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
    $seoTitleArr[] = $seo_variant_path;
}
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seo_video_tab_url = implode("/", $seoTitleArr);
$xml .= "<OVERALL_AVG_HTML><![CDATA[$html1]]></OVERALL_AVG_HTML>";
$xml .= "<OVERALL_AVG_CNT><![CDATA[$overallavg]]></OVERALL_AVG_CNT>";
$xml .= "<OVERALL_TOTAL_CNT><![CDATA[$overallcnt]]></OVERALL_TOTAL_CNT>";
$xml .= $optionhtml;
$brand_page_link = WEB_URL . $cat_path . "/" . $seo_brand_path;
unset($seoTitleArr);
$product_info_name = seo_title_replace($product_info_name);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $cat_path;
$seoTitleArr[] = $seo_brand_path;
$seoTitleArr[] = $seo_model_path;
$model_page_link = implode("/", $seoTitleArr);

unset($compareCarsUrl);
$compareCarsUrl[] = SEO_WEB_URL;
$compareCarsUrl[] = $cat_path;
$compareCarsUrl[] = SEO_COMPARE_URL;
if(!empty($seo_variant_path)){
    $compareCarsUrl[] = $seo_brand_path . '-' . $seo_model_path . '-' . $seo_variant_path;
}else{
    $compareCarsUrl[] = $seo_brand_path . '-' . $seo_model_path;
}
if (!empty($productvariantUrlYear)) {
    $compareCarsUrl[] = $productvariantUrlYear;
}
$compareCarsUrl = implode("/", $compareCarsUrl);
if (!empty($category_id)) {
    $result = $oBrand->arrGetBrandDetails("", $category_id, 1);
}
$cnt = sizeof($result);
foreach ($result as $bkry => $bValue) {
    if (in_array($bValue['brand_id'], $top_brand_arr)) {
        $set_key = array_search($bValue['brand_id'], $top_brand_arr);
        $bBrandArr1[$set_key] = $bValue;
    } else {
        $bBrandArr2[] = $bValue;
    }
}
ksort($bBrandArr1);
unset($result);
if (is_array($bBrandArr1) && is_array($bBrandArr2)) {
    $result = array_merge($bBrandArr1, $bBrandArr2);
}
$cnt = sizeof($result);
$xml .= "<BRAND_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
$selectedIndex = "0";
$isBrandSelected = "0";
for ($i = 0; $i < $cnt; $i++) {
    $brand_id = $result[$i]['brand_id'];
    $result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'], ENT_QUOTES, 'UTF-8');
    if (in_array($result[$i]['brand_id'], $top_brand_arr)) {
        $result[$i]['top_brand'] = 1;
    } else {
        $result[$i]['top_brand'] = 0;
    }
    $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
    $brdresult[$i] = array_change_key_case($result[$i], CASE_LOWER);
    $xml .= "<BRAND_MASTER_DATA>";
    foreach ($result[$i] as $k => $v) {
        $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $xml .= "</BRAND_MASTER_DATA>";
}
$xml .= "</BRAND_MASTER>";


unset($pres);
$pres = $oFeature->arrGetProductFeatureDetails($product_id, "");
$pres_cnt = sizeof($pres);
if ($pres_cnt > 0) {
    for ($p = 0; $p < $pres_cnt; $p++) {
        $feature_group = $pres[$p]['feature_group'];
        $main_feature_group = $pres[$p]['main_feature_group'];
        if (($main_feature_group == 3) && ($feature_group == 18)) {
            $selected_feature_id = $pres[$p]['feature_id'];
        }
    }
}


$config_details = get_config_details();
$variant_page = 0;
$overviewpos = '';



if ($action == 'features' || $action == 'overviews') {

    $aOverview = $oFeature->arrGetSummary($category_id, $product_id, $type = "array");
//print"<pre>";print_r($aOverview);exit;
    foreach ($aOverview as $key => $val) {
        if (!strpos($key, 'Price') && !strpos($key, 'Feature')) {
            foreach ($aOverview[$key] as $overviewtitle => $overview_value) {
                if ($overviewtitle == 'Mileage' || $overviewtitle == 'Fuel Type' || $overviewtitle == 'Engine' || $overviewtitle == 'Transmission') {
                    $techspecs_arr[] = implode(",&#160;", $overview_value);
                }
            }
        }
    }
    if (is_array($techspecs_arr)) {
        $summerystr = implode(', ', $techspecs_arr);
    }
}

//similar product section start//
$aSimlilarProductData = array();
if (!empty($product_info_dispname)) {
    $aSimilarProductDetails = $oProduct->arrGetProductByName($product_info_dispname, $product_id, "", "1", "", "", "", "1");
}
if (is_array($aSimilarProductDetails)) {
    foreach ($aSimilarProductDetails as $iKey => $aSimilarProductVersion) {
        $iSimProdBrandId = $aSimilarProductVersion['brand_id'];
        $iSimProdName = $aSimilarProductVersion['product_name'];
        $iSimProdId = $aSimilarProductVersion['product_id'];
        $iSimProdVariant = $aSimilarProductVersion['variant'];
        unset($variantUrlYear);
        $variantUrlYear = buildYear($aSimilarProductVersion['arrival_date'], $aSimilarProductVersion['discontinue_date']);
        $aSimlilarProductData[$iKey] = $aSimilarProductVersion;
        if (is_array($aBrandDetailName) && isset($aBrandDetailName[$iSimProdBrandId])) {
            $sBrandName = $aBrandDetailName[$iSimProdBrandId][0];

            $sSimDisplayName = $sBrandName . " " . $iSimProdName . " " . $iSimProdVariant;
            if ($variantUrlYear != '') {
                $sSimDisplayName = $sSimDisplayName . "($variantUrlYear)";
            }
            $sim_link_name = $sBrandName . " " . $iSimProdName;
            $aSimlilarProductData[$iKey]['similar_prod_brand_name'] = $sBrandName;
            $aSimlilarProductData[$iKey]['similar_prod_display_name'] = $sSimDisplayName;
        }
        $sim_brand_name = html_entity_decode($sBrandName, ENT_QUOTES, 'UTF-8');
        $sim_link_name = html_entity_decode($sim_link_name, ENT_QUOTES, 'UTF-8');
        $sim_variant_name = html_entity_decode($iSimProdVariant, ENT_QUOTES, 'UTF-8');
        unset($seoTitleArr);
        $seoTitleArr[] = SEO_WEB_URL;
        $seoTitleArr[] = constructUrl($sim_brand_name);
        $seoTitleArr[] = constructUrl($iSimProdName);
        $seoTitleArr[] = constructUrl($sim_variant_name);
        if (!empty($variantUrlYear)) {
            $seoTitleArr[] = $variantUrlYear;
        }
        $seoTitleArr[] = "features";
        $seo_url = implode("/", $seoTitleArr);
        $aSimlilarProductData[$iKey]['seo_url'] = $seo_url;
    }
}
if (is_array($aSimlilarProductData)) {
    $icnt = sizeof($aSimlilarProductData);
    $sSimilarProductDetXml .= "<SIMILAR_PRODUCT_DETAIL>";
    foreach ($aSimlilarProductData as $ikey => $aValue) {
        $aValue = array_change_key_case($aValue, CASE_UPPER);

        $sSimilarProductDetXml .= "<SIMILAR_PRODUCT_DATA>";
        foreach ($aValue as $k => $v) {
            $sSimilarProductDetXml .= "<$k><![CDATA[$v]]></$k>";
        }
        $sSimilarProductDetXml .= "</SIMILAR_PRODUCT_DATA>";
    }
    $sSimilarProductDetXml .= "</SIMILAR_PRODUCT_DETAIL>";
}
//similar product section END//
$aSimlilarProductData = array();



$all_exp = "/all-reviews/";
$user_exp = "/user-reviews/";
$expert_exp = "/expert-reviews/";


/*if ($action == "all_review" || $action == 'overviews') {
   
    if ($action == 'overviews')
        $limit = 5;
    $cmd_user = PHP_PATH . ' ' . BASEPATH . "api/latest_user_review_api.php brand_id=$router_brand_id product_name_id=$product_name_id product_id=$product_id limit=$limit";
    $latest_review_api_url = shell_exec($cmd_user);
}


if ($action === "expert_review" || $action == 'overviews') {
    $cmd = PHP_PATH . ' ' . BASEPATH . "api/expert_reviews_api.php brand_id=$router_brand_id product_name_id=$product_name_id product_id=$product_id";
    $expert_review_url = shell_exec($cmd);
}*/


if ($action == 'features' || $action == 'overviews') {
//echo "FEATURES"; die();
    $feature_sub_group_array = $oFeature->arrGetFeatureDetails("", "", "", 18, "1");
    foreach ($feature_sub_group_array as $key => $val) {
        $feature_id = $val['feature_id'];
        $body_result = $oProduct->arrGetProductFeatureDetails("", $feature_id, $product_id);
        if (is_array($body_result)) {
            if (strtolower($body_result[0]['feature_value']) == "yes") {
                $body_type = $body_result[0]['feature_name'];
            }
        }
    }
    $highlight_data = $oFeature->arrGetVariantPageSummary($category_id, $product_id, "1", "", "highlight");

    if (!empty($category_id)) {
        $result = $oFeature->arrFeatureSubGroupDetails("", $category_id, "1");
        $featureSubGroupCnt = sizeof($result);
        for ($i = 0; $i < $featureSubGroupCnt; $i++) {
            $sub_group_id = $result[$i]['sub_group_id'];
            $featureSubGroupArr[$sub_group_id] = $sub_group_id;
        }
        unset($result);
        $result = $oFeature->arrGetFeatureMainGroupDetails("", $category_id, "1", $startlimit, $limitcnt);
    }

    $cnt = sizeof($result);
//echo "FEATURES1---".$category_id."=======".$cnt; die();
    $featureboxcntArr = Array();
    for ($i = 0; $i < $cnt; $i++) {
        $status = $result[$i]['status'];
        $main_group_id = $result[$i]['group_id'];
        $categoryid = $result[$i]['category_id'];
        $main_feature_group_name = $result[$i]['main_group_name'];
        unset($seoTitleArr);
        $seoTitleArr[] = SEO_WEB_URL;
        $seoTitleArr[] = constructUrl($product_brand_name);
        $seoTitleArr[] = constructUrl($product_info_name);
        $seoTitleArr[] = constructUrl($product_variant_name);
        if (!empty($productvariantUrlYear)) {
            $seoTitleArr[] = $productvariantUrlYear;
        }
        $seo_url = implode("/", $seoTitleArr);
        $result[$i]['seo_url'] = $seo_url;

        if (!empty($categoryid)) {
//$category_name = $category_result[0]['category_name'];
            $result[$i]['js_category_name'] = $category_name;
            $result[$i]['category_name'] = html_entity_decode($category_name, ENT_QUOTES, 'UTF-8');
            if (!empty($main_group_id)) {
                $feature_result = $oFeature->arrGetFeatureDetails("", $category_id, $main_group_id);
            }
            $featureCnt = sizeof($feature_result);
            $featureboxcntArr[] = $featureCnt;
            for ($j = 0; $j < $featureCnt; $j++) {
                $feature_group = $feature_result[$j]['feature_group'];
                if (!empty($feature_group)) {
                    $feature_sub_group_array = $oFeature->arrFeatureSubGroupDetails($feature_group, $categoryid, "1");
                }
                $sub_group_name = $feature_sub_group_array[0]['sub_group_name'];
                if (empty($sub_group_name))
                    continue;
                if ($sub_group_name != 'Segments') {
                    if ($feature_result[$j]['feature_name'] != 'Car description') {

                        $main_feature_group = $feature_result[$j]['main_feature_group'];
                        $status = $feature_result[$j]['status'];
                        $categoryid = $feature_result[$j]['category_id'];
                        $feature_id = $feature_result[$j]['feature_id'];
                        $unit_id = $feature_result[$j]['unit_id'];
                        if (!empty($feature_id)) {
                            $pivot_result = $oPivot->arrGetPivotDetails("", $categoryid, $feature_id, "1");
                            $product_result = $oProduct->arrGetProductFeatureDetails("", $feature_id, $product_id);
                        }
                        $feature_value = $product_result[0]['feature_value'];
                        if (!empty($feature_value)) {
                            $featureNameArr[] = $feature_value;
                        }
                        if (!empty($unit_id)) {
                            $unit_result = $oFeature->arrFeatureUnitDetails($unit_id, $categoryid, "1");
                            $feature_unit = $unit_result[0]['unit_name'];
                        } else {
                            $feature_unit = "";
                        }
                        if (!empty($feature_unit) && !empty($feature_value) && $feature_value != '-') {
                            $featureNameArr[] = $feature_unit;
                        }
                        $feature_value = implode(" ", $featureNameArr);
                        unset($featureNameArr);
                        if (strtolower($feature_value) == 'yes') {
                            $feature_value = 'yes';
                        } else if (strtolower($feature_value) == 'no') {
                            $feature_value = 'no';
                        }

                        if ($feature_result[$j]['feature_name'] == 'Colours') {
                            $feature_value = "";
                        }
                        $feature_result[$j]['product_feature_id'] = $product_result[0]['feature_id'];
                        $feature_result[$j]['feature_value'] = $feature_value;
                        $feature_result[$j]['pivot_feature_id'] = $pivot_result[0]['feature_id'];
                        $feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
                        $feature_result[$j]['js_feature_group'] = $feature_result[$j]['feature_group'];
                        $feature_result[$j]['js_feature_desc'] = $feature_result[$j]['feature_description'];
                        $feature_result[$j]['js_feature_unit'] = $feature_unit;
                        $feature_result[$j]['feature_status'] = ($status == 1) ? 'Active' : 'InActive';
                        $feature_result[$j]['feature_unit'] = $feature_unit ? html_entity_decode($feature_unit, ENT_QUOTES, 'UTF-8') : '';
                        $feature_result[$j]['feature_group'] = $feature_result[$j]['feature_group'] ? html_entity_decode($feature_result[$j]['feature_group'], ENT_QUOTES, 'UTF-8') : '';
                        $feature_description = $feature_result[$j]['feature_description'];
                        if ($feature_description != "") {
                            $feature_description = html_entity_decode($feature_description, ENT_QUOTES, 'UTF-8');
                            $feature_description = str_replace('&amp;amp;', "", $feature_description);
                            $feature_description = str_replace('&#039;', "'", $feature_description);
                            $feature_description = str_replace('#039;', "'", $feature_description);
                        }
                        $feature_result[$j]['feature_desc'] = $feature_description ? html_entity_decode($feature_description, ENT_QUOTES, 'UTF-8') : '';
                        $feature_result[$j]['create_date'] = date('d-m-Y', strtotime($feature_result[$j]['create_date']));
                        $feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
                        $feature_result[$j]['feature_name'] = $feature_result[$j]['feature_name'] ? html_entity_decode($feature_result[$j]['feature_name'], ENT_QUOTES, 'UTF-8') : '';
                        $featureresult[$main_group_id][$feature_group][] = $feature_result[$j];
                        $featureresult[$main_group_id][$feature_group]['sub_group_name'] = $feature_sub_group_array[0]['sub_group_name'];
                        $featureresult[$main_group_id][$feature_group]['sub_group_id'] = $feature_group;
                        $pivot_feature_id = $pivot_result[0]['feature_id'];
                        $featureresult[$main_group_id][$feature_group]['is_pivot_group'] = ($pivot_feature_id == $feature_id) ? 'true' : 'false';
                        $featureresult[$main_group_id][$feature_group]['pivot_feature_id'] = $pivot_feature_id;
                        $featureresult[$main_group_id][$feature_group]['feature_id'] = $feature_id;
                    }
                }
            }
            foreach ($result[$i] as $k => $v) {
                $featureresult[$main_group_id][$k] = $v;
            }
        }
    }

//	print_r($featureresult); die();
    $groupnodexml .= "<GROUP_MASTER>";
    if ($featureresult) {
        foreach ($featureresult as $maingroupkey => $maingroupval) {
            if (is_array($maingroupval)) {
                $groupnodexml .= "<GROUP_MASTER_DATA>";
                foreach ($maingroupval as $subgroupkey => $subgroupval) {
                    if (is_array($subgroupval)) {
                        $groupnodexml .= "<SUB_GROUP_MASTER>";
                        foreach ($subgroupval as $key => $featuredata) {
                            if (is_array($featuredata)) {
                                $groupnodexml .= "<SUB_GROUP_MASTER_DATA>";
                                $featuredata = array_change_key_case($featuredata, CASE_UPPER);
                                foreach ($featuredata as $featurekey => $featureval) {

                                    $groupnodexml .= "<$featurekey><![CDATA[$featureval]]></$featurekey>";
                                }
                                $groupnodexml .= "</SUB_GROUP_MASTER_DATA>";
                            } else {
                                $groupnodexml .= "<" . strtoupper($key) . "><![CDATA[$featuredata]]></" . strtoupper($key) . ">";
                            }
                        }
                        $groupnodexml .= "</SUB_GROUP_MASTER>";
                    } else {
                        $groupnodexml .= "<" . strtoupper($subgroupkey) . "><![CDATA[$subgroupval]]></" . strtoupper($subgroupkey) . ">";
                    }
                }
                $groupnodexml .= "</GROUP_MASTER_DATA>";
            }
        }
    }
    $groupnodexml .= "</GROUP_MASTER>";


    if ($featureresult) {
        foreach ($featureresult as $maingroupkey => $maingroupval) {
            if (is_array($maingroupval)) {
                foreach ($maingroupval as $subgroupkey => $subgroupval) {
                    if (is_array($subgroupval)) {
                        foreach ($subgroupval as $key => $featuredata) {
                            if (is_array($featuredata)) {
                                $featuredata = array_change_key_case($featuredata, CASE_UPPER);
                                $featuredata_arr[] = array_change_key_case($featuredata, CASE_UPPER);

                                foreach ($featuredata as $featurekey => $featureval) {
                                    if ($featurekey == "FEATURE_NAME" and $featureval == "Dimensions") {
                                        $Dimensionsdata1 = $featuredata['FEATURE_VALUE'];
                                    }
                                    if ($featurekey == "FEATURE_NAME" and $featureval == "Ground clearance") {
                                        $Dimensionsdata2 = $featuredata['FEATURE_VALUE'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    if (!empty($Dimensionsdata1)) {
        $aDimensionsdata1 = explode('x', $Dimensionsdata1);
        /* $dLength = getMeasurements($aDimensionsdata1[0]);
          $dwidth = getMeasurements($aDimensionsdata1[1]);
          $aheight = explode(" ",$aDimensionsdata1[2]);
          $dheight = getMeasurements($aheight[0]);
          $aDimensionsdata2 = explode(" ",$Dimensionsdata2);
          $clearance = getMeasurements($aDimensionsdata2[0]);
         */
        $dLength = $aDimensionsdata1[0] . " mm";
        $dwidth = $aDimensionsdata1[1] . " mm";
        $aheight = $aDimensionsdata1[2] . " mm";
        $dheight = $aheight;
    }
    if (!empty($Dimensionsdata2)) {
        $aDimensionsdata2 = explode(" ", $Dimensionsdata2);
        $clearance = $aDimensionsdata2[0] . " mm";
    }
    $Dimensionstr .= "<DIM_LENGTH>$dLength</DIM_LENGTH>";
    $Dimensionstr .= "<DIM_HEIGHT>$dheight</DIM_HEIGHT>";
    $Dimensionstr .= "<DIM_WIDTH>$dwidth</DIM_WIDTH>";
    $Dimensionstr .= "<DIM_CLEARANCE>$clearance</DIM_CLEARANCE>";
}
unset($result);


if ($action == 'expert_review_detail') {
    
}
unset($seoTitleArr);

unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = constructUrl($cat_path);
$seoTitleArr[] = constructUrl($product_brand_name);
$seoTitleArr[] = constructUrl($product_link_name);
$seoTitleArr[] = constructUrl($product_variant_name);
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$oncars_overview_seo_url = implode("/", $seoTitleArr);

unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = constructUrl($cat_path);
$seoTitleArr[] = constructUrl($product_brand_name);
$seoTitleArr[] = constructUrl($product_link_name);
$seoTitleArr[] = constructUrl($product_variant_name);
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seoTitleArr[] = SEO_PRODUCT_FEATURE;
$oncars_features_seo_url = implode("/", $seoTitleArr);
unset($seoTitleArr);

$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = constructUrl($cat_path);
$seoTitleArr[] = constructUrl($product_brand_name);
$seoTitleArr[] = constructUrl($product_link_name);
$seoTitleArr[] = constructUrl($product_variant_name);
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seoTitleArr[] = "reviews";
$oncars_all_reviews_seo_url = implode("/", $seoTitleArr);

unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = constructUrl($cat_path);
$seoTitleArr[] = constructUrl($product_brand_name);
$seoTitleArr[] = constructUrl($product_link_name);
$seoTitleArr[] = constructUrl($product_variant_name);
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seoTitleArr[] = "user-reviews";
$seo_all_userreview_url = implode("/", $seoTitleArr);


unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = constructUrl($cat_path);
$seoTitleArr[] = constructUrl($product_brand_name);
$seoTitleArr[] = constructUrl($product_link_name);
$seoTitleArr[] = constructUrl($product_variant_name);
if (!empty($productvariantUrlYear)) {
    $seoTitleArr[] = $productvariantUrlYear;
}
$seoTitleArr[] = "expert-reviews";
$oncars_expert_reviews_seo_url = implode("/", $seoTitleArr);

unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = constructUrl($cat_path);
$seoTitleArr[] = constructUrl($product_brand_name);
$seoTitleArr[] = constructUrl($product_link_name);
$seoTitleArr[] = "expert-reviews";
$expert_reviews_seo_url = implode("/", $seoTitleArr);



unset($seoTitleArr);

$SeoWriteReviewurl[] = SEO_WEB_URL;
$seoTitleArr[] = constructUrl($cat_path);
$SeoWriteReviewurl[] = constructUrl($product_brand_name);
$SeoWriteReviewurl[] = constructUrl($product_info_dispname);
$SeoWriteReviewurl[] = constructUrl($product_variant_name);
if (!empty($productvariantUrlYear)) {
    $SeoWriteReviewurl[] = $productvariantUrlYear;
}
$SeoWriteReviewurl[] = "write-review";

$write_review_link = implode("/", $SeoWriteReviewurl);

//breadcrumb script start here
unset($breadcrum);
$breadcrumb = carDetailBreadCrumb($category_id, $brdresult, $top_brand_arr, $rating_brand_id, $product_brand_name, $product_name_id, $seo_product_info_name, $rev_product_id, $product_variant_name, $selected_city_id, $action, $productvariantUrlYear);
//breadcrumb script ends here
// Alternate Cars
$group_ids = array('0' => '1', '1' => '2');

$compare_result = $oProduct->arrGetProdCompetitorDetailsCnt("", $top_competitor_product_id, $top_competitor_model_id, $top_competitor_brand_id, "1", "1", "0", "1", "1");
$comparecnt = $compare_result[0]['cnt'];
$compare_result = $oProduct->arrGetProdCompetitorDetailsCnt("", "", $top_competitor_model_id, $top_competitor_brand_id, "1", "1", "0", "1", "1");
$comparecnt1 = $compare_result[0]['cnt'];

unset($result);

$usercnt = $userreview->arrGetUserReviewDetailsCount("", "", "", "", "", $top_competitor_brand_id, $category_id, $top_competitor_model_id, $top_competitor_product_id);
$usercnt = !empty($usercnt) ? $usercnt : '0';


$cpopularxml .= "<COMPARE_SET_MASTER>";
$cpopularxml .= "<USER_COUNT><![CDATA[$usercnt]]></USER_COUNT>";
$cpopularxml .= "<EXPERT_COUNT><![CDATA[$expertcnt]]></EXPERT_COUNT>";
$cpopularxml .= "<COUNT><![CDATA[$comparecnt]]></COUNT>";
$cpopularxml .= "<MODEL_COUNT><![CDATA[$comparecnt1]]></MODEL_COUNT>";
$cpopularxml .= "</COMPARE_SET_MASTER>";
$cpopularxml .= "<TOP_COMPETITOR_PRODUCT_ID><![CDATA[$top_competitor_product_id]]></TOP_COMPETITOR_PRODUCT_ID>";
$cpopularxml .= "<TOP_COMPETITOR_MODEL_ID><![CDATA[$top_competitor_model_id]]></TOP_COMPETITOR_MODEL_ID>";
$cpopularxml .= "<TOP_COMPETITOR_BRAND_ID><![CDATA[$top_competitor_brand_id]]></TOP_COMPETITOR_BRAND_ID>";

//echo "top_competitor_product_id = $top_competitor_product_id";

/*if (!empty($top_competitor_product_id)) {
    $moreon_result = $oProduct->moreOnCar($category_id, $top_competitor_brand_id, $top_competitor_model_id, $top_competitor_product_id);
//print_r($moreon_result);
    if (!empty($moreon_result)) {
        $strMoreOn .="<MORE_ON_CAR>";
        for ($i = 0; $i < count($moreon_result); $i++) {
            $strMoreOn .="<MORE_ON_CAR_DATA>";
            $strMoreOn .="<MORE_ON_CAR_DATALINK>" . $moreon_result[$i]['URL'] . "</MORE_ON_CAR_DATALINK>";
            $strMoreOn .="<MORE_ON_CAR_DATATITLE>" . $moreon_result[$i]['TITLE'] . "</MORE_ON_CAR_DATATITLE>";
            $strMoreOn .="</MORE_ON_CAR_DATA>";
        }
        $strMoreOn .="</MORE_ON_CAR>";
    }
}
$compare_tab_url = empty($moreon_result[1]['URL']) ? $compareCarsUrl : $moreon_result[1]['URL'];*/
// Recommanded cars
$cnt = 0;
if (!empty($top_competitor_model_id)) {
    $result = $oProduct->arrGetProdCompetitorDetails("", "", $top_competitor_model_id, $top_competitor_brand_id, "1", "1", '', '', '0', '1', '1');
    $cnt = sizeof($result);
    for ($i = 0; $i < $cnt; $i++) {
        $product_info_ids = $result[$i]['product_info_ids'];
        if (!empty($product_info_ids)) {
            $competitorArr[] = $product_info_ids;
        }
    }
    if (sizeof($competitorArr) > 0) {
        setcookie("antoprcsl", $product_info_id); // article news top competitor slider
        $competitorArr = array_unique($competitorArr);
        $competitorids = implode(',', $competitorArr);
    }
    $xml .= "<COMPETITOR><![CDATA[$competitorids]]></COMPETITOR>";
}
/*
if (!empty($competitorids)) {
    $result = $oReview->getReviewsDetailsCnt("", "1", "", "", $competitorids, $category_id, "", "1", "group by PR.product_info_id");
}
*/
$total = $result[0]['cnt'];
$xml .= "<COMPETITOR_COUNT><![CDATA[$cnt]]></COMPETITOR_COUNT>";
$xml .= "<REVIEW_COMPETITOR_COUNT><![CDATA[$total]]></REVIEW_COMPETITOR_COUNT>";

$up_result = $oProduct->getUpcomingProductCount($category_id, $iBrandId, $productNameInfoId, $selected_feature_id, $popadprice);
/* get number of variant counts */
$result = $oProduct->arrGetProductDetails("", $category_id, $router_brand_id, '1', "", "", "1", "", "", "1", "order by PRICE_VARIANT_VALUES.variant_value asc", $product_info_dispname, "", "", '', "1");
$variant_count = count($result);

unset($seoArr);
$tabid = $tab_id ? $tab_id : $tab;
$reviewName = $reviewName ? $reviewName : $tabid;
switch ($action) {
    case 'oncars':
        if (($rev_grp_id == 0) || ($rev_grp_id == 1)) {
            $seoArr[] = $seo_title_part . ' Design Review';
            $seo_desc = $seo_title_part . ' Design Review - OnCars Reviews. Get Design reviews of all variants of ' . $brand_name . ' ' . $seo_ProductName . ' from Experts at ' . SEO_DOMAIN;
//$seoKeyArr[] = $seo_title_part;
            $seoKeyArr[] = $brand_name . " reviews";
            $seoKeyArr[] = $brand_name . " design reviews";
            $seoKeyArr[] = $seo_title_part . " reviews";
            $seoKeyArr[] = $seo_title_part . " design reviews";
        } elseif ($rev_grp_id == 2) {
            $seoArr[] = $seo_title_part . ' User Experience Review';
            $seo_desc = $seo_title_part . ' User Experience Review - On Cars Reviews. Get User Experience reviews of all variants of ' . $brand_name . ' ' . $seo_ProductName . ' from Experts at ' . SEO_DOMAIN;
            $seoKeyArr[] = $brand_name . " reviews";
            $seoKeyArr[] = $brand_name . " user experince reviews";
            $seoKeyArr[] = $seo_title_part . " reviews";
            $seoKeyArr[] = $seo_title_part . " user experince reviews";
        } elseif ($rev_grp_id == 3) {
            $seoArr[] = $seo_title_part . ' Performance Review';
            $seo_desc = $seo_title_part . ' Performance Review - On Cars Reviews. Get Performance Review of all variants of ' . $brand_name . ' ' . $seo_ProductName . ' from experts at ' . SEO_DOMAIN;
            $seoKeyArr[] = $brand_name . " reviews";
            $seoKeyArr[] = $brand_name . " performance reviews";
            $seoKeyArr[] = $seo_title_part . " reviews";
            $seoKeyArr[] = $seo_title_part . " performance reviews";
        }
        $seoArr[] = 'On cars Reviews';
        $seoArr[] = $brand_name . " " . $seo_ProductName . " cars";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " price";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " photos";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " reviews";
        break;
    case 'all_review' :
        $seoArr[] = $seo_title_part . ' Reviews & Ratings ';
        $seoArr[] = $seo_title_part . ' User & Expert Reviews ';
        $seo_desc = $seo_title_part . ' Reviews & Ratings - Get all the latest car reviews of ' . $seo_title_part . ' including user reviews, expert reviews & video reviews at ' . SEO_DOMAIN;
        $seoKeyArr[] = $seo_title_part . " reviews";
        $seoKeyArr[] = $seo_title_part . " reviews & ratings";
        $seoKeyArr[] = $seo_title_part . " video reviews";
        $seoKeyArr[] = $seo_title_part . " expert reviews";
        $seoKeyArr[] = $seo_title_part . " user reviews";
        break;
    case 'expert_review':
        $seoArr[] = $seo_title_part . ' Expert Reviews';
        $seoArr[] = $seo_title_part . ' Design, Performance & User Experience Review';
        $seo_desc = $seo_title_part . ' Expert Reviews - Read ' . $seo_title_part . ' design, performance & user experience reviews from our experts at ' . SEO_DOMAIN;
        $seoKeyArr[] = $seo_title_part . " reviews";
        $seoKeyArr[] = $seo_title_part . " expert reviews";
        $seoKeyArr[] = $seo_title_part . " design review";
        $seoKeyArr[] = $seo_title_part . " performance review";
        $seoKeyArr[] = $seo_title_part . " user experience review";
        $seoKeyArr[] = $seo_title_part . " meta reviews";
        $seoKeyArr[] = $seo_ProductName;
        $seoKeyArr[] = $brand_name . ' ' . $seo_ProductName . ' ' . $seo_variant . " reviews";
        $seoKeyArr[] = $seo_ProductName . ' wallpapers';
        $seoKeyArr[] = $seo_ProductName . ' price';
        $seoKeyArr[] = $seo_ProductName . ' photos';
        $seoKeyArr[] = $seo_ProductName . ' reviews';
        break;
    case 'expert_review_detail':
        $seoArr[] = $sReviewTitle . ' |  ' . $brand_name . ' ' . $seo_ProductName . ' ' . $seo_variant . ' Expert Reviews';
//$seoArr[] =$seo_title_part. ' Design, Performance & User Experience Review';
        $seo_desc = $seo_title_part . ' Expert Reviews - Read ' . $seo_title_part . ' design, performance & user experience reviews from our experts at ' . SEO_DOMAIN;
        $seoKeyArr[] = $seo_title_part . " reviews";
        $seoKeyArr[] = $seo_title_part . " expert reviews";
        $seoKeyArr[] = $seo_title_part . " design review";
        $seoKeyArr[] = $seo_title_part . " performance review";
        $seoKeyArr[] = $seo_title_part . " user experience review";
        break;
    case 'experts':
        $seoArr[] = $seo_title_part . ' Meta Reviews';
        $seoArr[] = 'Reviews & Ratings';
        $seoArr[] = $brand_name . " " . $seo_ProductName . " Cars";
        $seo_desc = $brand_name . " " . $seo_ProductName . ' Meta Reviews - Reviews & Ratings - Get all the latest' . $seo_title_part . ' car reviews and ratings at ' . SEO_DOMAIN;
        $seoKeyArr[] = $brand_name . " meta reviews";
        $seoKeyArr[] = $seo_ProductName;
        $seoKeyArr[] = $seo_title_part . " reviews";
        $seoKeyArr[] = $seo_ProductName . " wallpapers";
        $seoKeyArr[] = $seo_ProductName . " price";
        $seoKeyArr[] = $seo_ProductName . " photos";
        $seoKeyArr[] = $seo_ProductName . " reviews";
        break;
    case 'user_reviews':
        $seoArr[] = $seo_title_part . ' User Reviews';
        $seoArr[] = $seo_title_part . ' Owners Reviews';
        $seo_desc = $seo_title_part . ' User Reviews - Get reviews from actual ' . $seo_title_part . ' owners. Get User Reviews & Ratings at ' . SEO_DOMAIN;
        $seoKeyArr[] = $seo_title_part . " reviews";
        $seoKeyArr[] = $seo_title_part . " User Reviews";
        $seoKeyArr[] = $seo_title_part . " customer reviews";
        $seoKeyArr[] = $seo_title_part . " customer feedback";
        $seoKeyArr[] = $seo_title_part . " owner feedback";
        break;
    case 'user_review':
        $seoArr[] = $title . ' | ' . $seo_title_part . ' User reviews ';
//$seoArr[] = $brand_name." ".$seo_ProductName." Reviews";
        $seo_desc = $title . '- Get reviews from actual ' . $seo_title_part . ' owners. Get User Reviews & Ratings at ' . SEO_DOMAIN;
        $seoKeyArr[] = $brand_name . " user reviews";
        $seoKeyArr[] = $seo_ProductName;
        $seoKeyArr[] = $seo_title_part . " user reviews";
        $seoKeyArr[] = $seo_ProductName . " wallpapers";
        $seoKeyArr[] = $seo_ProductName . " price";
        $seoKeyArr[] = $seo_ProductName . " photos";
        $seoKeyArr[] = $seo_ProductName . " reviews";
        break;
    case 'photos_videos':
        if ($photo_tab_id == '1') {
            $seoArr[] = $seo_title_part . ' Exterior Photos';
            $seoArr[] = ' Photos & Videos';
            $seo_desc = $seo_title_part . ' Exterior Photos - Photos & Videos. Get all the latest Exterior Photos of ' . $seo_title_part . " at " . SEO_DOMAIN;
//$seoKeyArr[] = $seo_title_part;
            $seoKeyArr[] = $brand_name;
            $seoKeyArr[] = $seo_ProductName;
            $seoKeyArr[] = $seo_title_part;
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " wallpapers";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " price";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " photos";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " reviews";
        } else if ($photo_tab_id == '2') {
            $seoArr[] = $seo_title_part . ' Interior Photos';
            $seoArr[] = ' Photos & Videos';
            $seo_desc = $seo_title_part . ' Interior Photos - Photos & Videos. Get all the latest Interior Photos of ' . $seo_title_part . " at " . SEO_DOMAIN;
            $seoKeyArr[] = $brand_name;
            $seoKeyArr[] = $seo_ProductName;
            $seoKeyArr[] = $seo_title_part;
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " wallpapers";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " price";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " photos";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " reviews";
        } else {
            $seoArr[] = $seo_title_part . ' Video Reviews';
            $seo_desc = $seo_title_part . ' Video Reviews. Get launch & review Videos of ' . $seo_title_part . " at " . SEO_DOMAIN;
            $seoKeyArr[] = $brand_name . " Videos";
            $seoKeyArr[] = $seo_ProductName;
            $seoKeyArr[] = $seo_title_part;
            $seoKeyArr[] = $seo_title_part . " Videos";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " price";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " photos";
            $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " reviews";
        }
        $seoArr[] = $brand_name . " " . $seo_ProductName . " Cars";
        break;
    case 'features':
        $seoArr[] = $seo_title_part . ' Features | ' . $seo_title_part . ' Specifications | ' . SEO_DOMAIN;
        $seo_desc = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . ' Features & Specifications - Get all the ' . $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . ' features & technical specifications in details at ' . SEO_DOMAIN;
        $seoKeyArr[] = $seo_title_part;
        $seoKeyArr[] = $seo_title_part . " Features";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . " " . $seo_variant . " specifications";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . " " . $seo_variant . " specs";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " wallpapers";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " price";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " photos";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " reviews";
        break;
    case '3':
        $seoArr[] = $seo_title_part . ' Technical Specifications';
        $seoArr[] = $brand_name . " " . $seo_ProductName . " Cars";
        $seo_desc = $seo_title_part . ' Technical Specifications. Get all the ' . $seo_title_part . ' technical specifications in details at ' . SEO_DOMAIN;
        $seoKeyArr[] = $seo_title_part;
        $seoKeyArr[] = $brand_name;
        $seoKeyArr[] = $seo_ProductName;
        $seoKeyArr[] = $seo_title_part . " specifications";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " wallpapers";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " price";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " photos";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " reviews";
        break;
    case 'videos':
        $seoArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " Video Gallery";
        $seoArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " Car News & Review Videos";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " videos";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " cars reviews";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " cars news";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " video reviews";
        $seo_desc = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " Video Gallery" . " - Watch car launch videos, latest car news & reviews videos of " . $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " at " . SEO_DOMAIN;
        break;
    case 'photos':
        $seoArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " Photos";
        $seoArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " Interior & Exterior Picture Gallery";
        $seoArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " Images";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " photos";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " pictures";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " interior photos";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " exterior photos";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " interior pictures";
        $seoKeyArr[] = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " images";
        $seo_desc = $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " Photos - Get exclusive picture gallery of " . $brand_name . " " . $seo_ProductName . ' ' . $seo_variant . " interior & exterior features online at " . SEO_DOMAIN;
        break;
    default:
        $seoArr[] = $seo_title_part . ' Price & Reviews in India';
        $seoArr[] = $brand_name . " " . $seo_ProductName . " cars";
        $seo_desc = $seo_title_part . ' Price & Reviews in India. Get ' . $seo_title_part . ' car reviews and ratings, Car news, available versions, on road price, technical specifications, features, colors, photos and videos at ' . SEO_DOMAIN;
        $seoKeyArr[] = $seo_title_part;
        $seoKeyArr[] = $brand_name;
        $seoKeyArr[] = $seo_ProductName;
        $seoKeyArr[] = $seo_title_part . " overview";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " wallpapers";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " price";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " photos";
        $seoKeyArr[] = $seo_ProductName . " " . $seo_variant . " reviews";
        break;
}
$seoArr[] = SEO_DOMAIN;
$seo_title = implode(" | ", $seoArr);
$seo_keywords = strtolower(implode(" , ", $seoKeyArr));
$search_key = $product_brand_name . " " . $product_info_dispname;
$top_comp_search = $oProduct->topSearchComparisons($search_key, $product_info_dispname, "variant", $cat_path);
// Fetching mapped videos to model
$sModelVideoListXML = '';
        if ($action == 'videos') {

            $result = $videoGallery->getVideosDetails("", "", "", '', $router_model_id, $category_id, $router_brand_id, "1", $startc, $perpagec, "order by V.create_date desc");
            //echo "<pre>"; print_r($result); die;
            if (is_array($result) && count($result) > 0) {
                $aResultData = array();
                foreach ($result as $iK => $aData) {
                    $media_path = $aData['video_img_path'];
                    $media_id = $aData['media_id'];
                    if (!empty($media_path)) {
                        $media_path = resizeImagePath($media_path, "225X300", $aModuleImageResize, $media_id);
                    }
                    $result[$iK]['video_img_path'] = !empty($media_path) ? CENTRAL_IMAGE_URL . $media_path : '';

                    unset($seoTitleArr);
                    $slug = constructUrl($aData["slug"]);
                    $result[$iK]['slug'] = $slug;
                  /*  $tab_type_id = $aData['type_id'];
                    $seoTitleArr[] = SEO_WEB_URL;
                    $seoTitleArr[] = constructUrl(trim($aData['title']));
                    $seoTitleArr[] = constructUrl(trim($aData['video_id']));
                    $seo_video_tab_url = implode("/", $seoTitleArr);
                    $result[$iK]['seo_video_url'] = $seo_video_tab_url;*/
                    unset($seoTitleArr);
                    $seoTitleArr[] = SEO_WEB_URL;
                    $seoTitleArr[] = $_REQUEST['cat_path'];
                    $seoTitleArr[] = $seo_brand_path;
                    $seoTitleArr[] = $seo_model_path;
                    if(!empty($seo_variant_path)){
                        $seoTitleArr[] = $seo_variant_path;
                     }
                    $seoTitleArr[] = "videos";  
                    $seoTitleArr[] = constructUrl(html_entity_decode($slug, ENT_QUOTES, 'UTF-8'));
                    $seo_video_tab_url = implode("/", $seoTitleArr);
                    $result[$iK]['seo_video_url'] = $seo_video_tab_url;
                }
                usort($result, 'sortByArrayDate');
                $sModelVideoListXML .= "<MODEL_VIDEO_LIST>";
                $sModelVideoListXML .= arraytoxml($result, 'MODEL_VIDEO_LIST_DATA');
                $sModelVideoListXML .= "</MODEL_VIDEO_LIST>";
            }
        }

function sortByArrayDate($a, $b) {
    return strtotime($a['create_date']) - strtotime($b['create_date']);
}
/*unset($result);
// Getting Alternate car Widget Listing
$total = '';
$skip = 0;
$perpage = '';
$variant_id = $product_id;
$product_info_id = $router_model_id;
$brand_id = $router_brand_id;
$type = 'variant';
$skipsamevariant = 0;
$skipsamemodel = 1;
$skipsamebrand = 1;
$sAlternateCarListXML = $oProduct->modelTopCompetitor($brand_id, $product_info_id, $variant_id, $type, $skip, $skipsamebrand, $skipsamemodel, $skipsamevariant, $perpage, $total = '', $oBrand);

//Getting Same brand other car listing
$type = 'model';
$brand_id = $brand_id;
$model_id = $product_info_id;
$variant_id = '';
$sOtherCarListXML = $oProduct->fetchSameBrandCarListing($category_id, $brand_id, $model_id, $variant_id, $type, $oBrand, '', '', $category_name);

// Getting Upcoming car widget Listing
//$category_id            = SITE_CATEGORY_ID;
$selected_brand_id = $brand_id ? $brand_id : "";
$product_name_id = $product_info_id ? $product_info_id : "";
$feature_id = 89;
$price_value = $popadprice;
$startlimit = 0;
$limit_cnt = 3;
$bres_cnt = 0;
$sUpComingCarWidgetList = $oProduct->getUpcomingProductWidgetList($category_id, $selected_brand_id, $product_name_id, $feature_id, $price_value, $startlimit, $limit_cnt, $oBrand, $oFeature);

*/
//NEWS LIST

if ($action == "news") {
    if (!empty($category_id)) {
        $limitcnt = 10;
        $feed_url = "http://www.bgr.in/feed/?tag=" . urlencode(str_replace(" ", "-", strtolower($search_key)));
//$feed_url = $product_feed_url ;
        $content1 = @file_get_contents($feed_url);
        $content = str_replace('&', '&amp;', $content1);
//header('Content-type: text/xml');
//echo $content; die;
        if ($content1 != false) {
            $x = new SimpleXmlElement($content);
            $newscnt = count($x->channel->item);
            $news_xml .= "<NEWS_LIST_MASTER>";
            $news_xml .= "<ALL_NEWS><![CDATA[http://www.bgr.in/category/news/]]></ALL_NEWS>";
            $news_xml .= "<COUNT><![CDATA[$newscnt]]></COUNT>";
            $news_xml .= "<FEED_URL><![CDATA[$feed_url]]></FEED_URL>";
            if ($newscnt > 0) {
                $start_count = 0;
                foreach ($x->channel->item as $entry) {
                    if ($start_count == $limitcnt) {
                        break;
                    }
                    $categoryNameArr = array();
//print_r($entry); die;
                    $news_xml .= "<NEWS_LIST_MASTER_DATA>";
                    $news_xml .= "<SEO_URL>$entry->link</SEO_URL>";
                    $news_xml .= "<TITLE>$entry->title</TITLE>";
                    $dc = $entry->children('http://purl.org/dc/elements/1.1/');

                    $author = $dc->creator;
                    $disp_date = date('M d, Y  H:i A', strtotime($entry->pubDate));
                    $news_xml .= "<DISP_DATE>$disp_date</DISP_DATE>";
                    $description = getCompactString(strip_tags($entry->description), 50, true) . ' ...';
                    $news_xml .= "<DESCRIPTION>$description</DESCRIPTION>";
                    $news_xml .= "<AUTHOR>$author</AUTHOR>";
                    $image_path = $entry->enclosure->attributes()->url;
                    $news_xml .= "<IMAGE_PATH>$image_path</IMAGE_PATH>";
                    $news_xml .= "<CATEGORIES>";
                    foreach ($entry->category as $tag => $categoryName) {
                        if (strlen($categoryNameArr[$tag]) > 0) {
                            break;
                        }
                        $news_xml .= "<CATEGORY>$categoryName</CATEGORY>";
                        $categoryNameArr[$tag] = $categoryName;
                    }
                    $news_xml .= "</CATEGORIES>";
                    $news_xml .= "</NEWS_LIST_MASTER_DATA>";
                    $start_count++;
                }
            }
            $news_xml .= "</NEWS_LIST_MASTER>";
        }
    }
}

/* gallery */
if (!empty($product_name_id)) {
    $result = $oWallpapers->arrSlideShowDetails("", "", "", $product_name_id, "", $category_id, "", "1");
}
#print_r($result); die("jjjjjjj");
$total_cnt = sizeof($result);
for ($i = 0; $i < $total_cnt; $i++) {
    $video_img_path = $result[$i]["video_img_path"];
    if (!empty($video_img_path)) {
        $video_img_path = resizeImagePath($video_img_path, "145X193", $aModuleImageResize, $video_img_id);
        $mid_video_img_path = resizeImagePath($video_img_path, "145X193", $aModuleImageResize, $video_img_id);
        $gallery_result[$i]['video_img_path'] = $mid_video_img_path ? CENTRAL_IMAGE_URL . $mid_video_img_path : IMAGE_URL . 'no_image_145X193.gif';
        $thumb_video_img_path = resizeImagePath($video_img_path, "45X60", $aModuleImageResize, $video_img_id);
        $gallery_result[$i]['thumb_video_img_path'] = $thumb_video_img_path ? CENTRAL_IMAGE_URL . $thumb_video_img_path : IMAGE_URL . 'no_image_45X60.gif';
        $gallery_result[$i]['image_title'] = $result[$i]["image_title"];
        $gallery_result[$i]['slideshow_title'] = $result[$i]["slideshow_title"];
    }
}

$gallery = '<GALLERY>';
$gallery .= '<TOTAL>' . $total_cnt . '</TOTAL>';
$gallery .= "<EXTERIOR_COUNT>" . $ephoto_cnt . "</EXTERIOR_COUNT>";
$gallery .= "<INTERIOR_COUNT>" . $iphoto_cnt . "</INTERIOR_COUNT>";
foreach ($gallery_result as $arr_gallery_details) {
    $gallery .= "<GALLERY_MAIN_IMAGE_DETAILS>" . $arr_gallery_details['video_img_path'] . "</GALLERY_MAIN_IMAGE_DETAILS>";
    $arr_gallery_details = array_change_key_case($arr_gallery_details, CASE_UPPER);
    $gallery .= "<GALLERY_DETAILS>";
    foreach ($arr_gallery_details as $key => $gallery_details) {
        $gallery .= "<$key>" . $gallery_details . "</$key>";
    }
    $gallery .= "</GALLERY_DETAILS>";
}
$gallery .= '</GALLERY>';
/* gallery */


        // Fetching mapped photos to model
        $xml_slideshow = '';
        if ($action == 'photos') {
            $new_result_list = $oWallpapers->arrSlideShowDetails("", "", "", $router_model_id
                    , "", $category_id, $router_brand_id, 1, "", "", "", "", "0", "1");
            $iCount = count($new_result_list);
            if (is_array($new_result_list) && $iCount > 0) {
                for ($i = 0; $i < $iCount; $i++) {
                    $title = $new_result_list[$i]["title"];
                    $slug = constructUrl($new_result_list[$i]["slug"]);
                    $new_result_list[$i]["slug"] = $slug;
                    $product_info_id = $new_result_list[$i]['product_info_id'];
                    $product_id = $new_result_list[$i]['product_id'];
                    $media_id = $new_result_list[$i]["slideshow_media_id"];
                    $media_path = $new_result_list[$i]["slideshow_media_path"];
                    $status = $new_result_list[$i]['status'];
                    $product_slide_id = $new_result_list[$i]['product_slide_id'];
                    if (!empty($title)) {
                        $result[$i]['title'] = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
                    }
                    if (!empty($media_path)) {
                       if($photoslug !=''){
                            if ($slug == $photoslug) {
                                $media_path = resizeImagePath($media_path, "375X500", $aModuleImageResize, $media_id);
                            } else {
                                $media_path = resizeImagePath($media_path, "114X152", $aModuleImageResize, $media_id);
                            }
                       }else{
                            if ($i == 0) {
                                $media_path = resizeImagePath($media_path, "375X500", $aModuleImageResize, $media_id);
                            } else {
                                $media_path = resizeImagePath($media_path, "114X152", $aModuleImageResize, $media_id);
                            }
                        }

                        $media_path = $media_path ? CENTRAL_IMAGE_URL . $media_path : '';
                    }
                    $new_result_list[$i]['media_path'] = $media_path;
                    $new_result_list[$i]['media_id'] = $media_id;
                    unset($seoTitleArr);
                    $seoTitleArr[] = SEO_WEB_URL;
                    $seoTitleArr[] = $_REQUEST['cat_path'];
                    $seoTitleArr[] = $seo_brand_path;
                    $seoTitleArr[] = $seo_model_path;
                    if(!empty($seo_variant_path)){
                        $seoTitleArr[] = $seo_variant_path;
                     }
                    $seoTitleArr[] = "photos";  
                    $seoTitleArr[] = constructUrl(html_entity_decode($slug, ENT_QUOTES, 'UTF-8'));
                    $seo_slide_featured_url = implode("/", $seoTitleArr);
                    $new_result_list[$i]["seo_slide_url"] = $seo_slide_featured_url . "/" . $slid_s;
                    $new_result_list[$i] = array_change_key_case($new_result_list[$i], CASE_UPPER);
                }
                $sModelPhotoListXML = arraytoxml($new_result_list, 'SLIDESHOW_MASTER_DATA');
                $xml_slideshow.= "<SLIDESHOW_MASTER>";
                $xml_slideshow.= $sModelPhotoListXML;
                $xml_slideshow.= "</SLIDESHOW_MASTER>";
            }
        }


//write user review //
    unset($result);
    $result = $userreview->arrGetQuestions();       
    $cnt = sizeof($result);        
    $xml .= "<QUESTIONAIRE_MASTER>";
    $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
    for($i=0;$i<$cnt;$i++){             
        $queid = $result[$i]['queid'];
        $ansresult = $userreview->arrGetQueAnswer("",$queid);
        $anscnt = 0;
        $anscnt = sizeof($ansresult);
        $xml .= "<QUESTIONAIRE_MASTER_DATA>";
        $result[$i] = array_change_key_case($result[$i],CASE_UPPER);

        foreach($result[$i] as $k=>$v){
            $xml .= "<$k><![CDATA[$v]]></$k>";
        }
        $xml .= "<QUESTIONAIRE_ANS_MASTER>";
        $xml .= "<ANS_COUNT><![CDATA[$anscnt]]></ANS_COUNT>";
        for($ans=0;$ans<$anscnt;$ans++){    
            $ansresult[$ans] = array_change_key_case($ansresult[$ans],CASE_UPPER);
            $xml .= "<QUESTIONAIRE_ANS_MASTER_DATA>";
            foreach($ansresult[$ans] as $k=>$v){
                $xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $xml .= "</QUESTIONAIRE_ANS_MASTER_DATA>";
        }
        $xml .= "</QUESTIONAIRE_ANS_MASTER>";                   
        $xml .= "</QUESTIONAIRE_MASTER_DATA>";                  
    }       
    $xml .= "</QUESTIONAIRE_MASTER>";


//write user review//

    ///////////////////////////////////////////////////////////////////////////////

        $expert_review_param =  implode(" ", array($curr_brand_name ,$curr_model_name));
        //echo EXPERT_REVIEW_API.rawurlencode(strtolower($expert_review_param));
        
        if($expert_review_param!=''){
            $expert_rating = $oReview->getBgrExpertReviews($expert_review_param);
        }
        $cmd = PHP_PATH . ' ' . BASEPATH . "api/average_rating_api.php" . " brand_id=$router_brand_id product_name_id=$router_model_id ";
        #echo $cmd;
        $user_rating_xml = shell_exec($cmd);
        $userrating_xml .= $user_rating_xml;

        if($action=="overviews" || $action == "all_reviews" || $action == "user_reviews" || $action == "user_review_detail"){
            if($action == "overviews") { $revlimit = 2;}
            if($action == "all_reviews" || $action == "user_reviews" || $action == "user_review_detail") { $revlimit = 10;}
            $cmd_user = PHP_PATH.' '.BASEPATH."api/latest_user_review_api.php brand_id=$router_brand_id product_name_id=$router_model_id limit=$revlimit";
            $latest_review_api_url = shell_exec($cmd_user);
            $latest_review_api_xml = $latest_review_api_url;
        }  
        if($action =="user_reviews"){
            $cmd_rev = PHP_PATH.' '.BASEPATH."api/latest_user_review_api.php brand_id=$router_brand_id product_name_id=$router_model_id product_id=$router_product_id user_review_id=$urevid limit=$revlimit";
            $current_review_detail = shell_exec($cmd_rev);
            if(!empty($urevid)){
                $resultoption = $userreview->GetUserReviewOptions("",$urevid,$category_id);
            }
            $oCount=sizeof($resultoption);
            if($oCount>0){
                $like_yes = $resultoption[0]['like_yes'];
                $like_no = $resultoption[0]['like_no'];
                $tot_cnt = $like_yes+$like_no;
                $votehtml .= "<REVIEW_RATE_OPTION>";
                $votehtml .= "<REVIEW_RATE_OPTION_TOTAL_COUNT>$tot_cnt</REVIEW_RATE_OPTION_TOTAL_COUNT>";
                $votehtml .= "<REVIEW_RATE_OPTION_YES>$like_yes</REVIEW_RATE_OPTION_YES>";
                $votehtml .= "<REVIEW_RATE_OPTION_NO>$like_no</REVIEW_RATE_OPTION_NO>";
                $votehtml .= "</REVIEW_RATE_OPTION>";
            }
             
        }

unset($variantnameSeoArr);
$variantnameSeoArr[] = SEO_WEB_URL;
$variantnameSeoArr[] = $_REQUEST['cat_path'];
$variantnameSeoArr[] = $seo_brand_path;
$variantnameSeoArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
$variantnameSeoArr[] = $seo_variant_path;
}
$seo_variant_url =  implode("/",$variantnameSeoArr);

unset($variantnameSeoArr);
$variantnameSeoArr[] = SEO_WEB_URL;
$variantnameSeoArr[] = $_REQUEST['cat_path'];
$variantnameSeoArr[] = $seo_brand_path;
$variantnameSeoArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
$variantnameSeoArr[] = $seo_variant_path;
}
$variantnameSeoArr[] = "news";
$seo_variantnews_url =  implode("/",$variantnameSeoArr);


unset($variantnameSeoArr);
$variantnameSeoArr[] = SEO_WEB_URL;
$variantnameSeoArr[] = $_REQUEST['cat_path'];
$variantnameSeoArr[] = $seo_brand_path;
$variantnameSeoArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
$variantnameSeoArr[] = $seo_variant_path;
}
$variantnameSeoArr[] = "reviews";
$seo_variantreview_url =  implode("/",$variantnameSeoArr);

unset($variantnameSeoArr);
$variantnameSeoArr[] = SEO_WEB_URL;
$variantnameSeoArr[] = $_REQUEST['cat_path'];
$variantnameSeoArr[] = $seo_brand_path;
$variantnameSeoArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
$variantnameSeoArr[] = $seo_variant_path;
}
$variantnameSeoArr[] = "photos";
$seo_variantphotos_url =  implode("/",$variantnameSeoArr);

unset($variantnameSeoArr);
$variantnameSeoArr[] = SEO_WEB_URL;
$variantnameSeoArr[] = $_REQUEST['cat_path'];
$variantnameSeoArr[] = $seo_brand_path;
$variantnameSeoArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
$variantnameSeoArr[] = $seo_variant_path;
}
$variantnameSeoArr[] = "videos";
$seo_variantvideo_url =  implode("/",$variantnameSeoArr);

unset($variantnameSeoArr);
$variantnameSeoArr[] = SEO_WEB_URL;
$variantnameSeoArr[] = $_REQUEST['cat_path'];
$variantnameSeoArr[] = $seo_brand_path;
$seo_variantbrand_url =  implode("/",$variantnameSeoArr);

unset($variantnameSeoArr);
$variantnameSeoArr[] = SEO_WEB_URL;
$variantnameSeoArr[] = $_REQUEST['cat_path'];
$variantnameSeoArr[] = $seo_brand_path;
$variantnameSeoArr[] = $seo_model_path;
if(!empty($seo_variant_path)){
$variantnameSeoArr[] = $seo_variant_path;
}
$variantnameSeoArr[] = "user-reviews";
$seo_variantuserreview_url =  implode("/",$variantnameSeoArr);


$moreon_result = $oProduct->moreOnCar($category_id, $rounter_brand_id, $router_model_id, $router_product_id);
//print_r($moreon_result);
$compare_tab_url = empty($moreon_result[1]['URL']) ? $compareCarsUrl : $moreon_result[1]['URL'];



$login_details = getCookie();

$strXML .= "<XML>";
$strXML .= $login_details;
$strXML .= $config_details;
$strXML .= $news_xml;
$strXML .= $xml_slideshow;
$strXML .= $expert_rating;
$strXML .= $userrating_xml;
$strXML .= $votehtml;
$strXML .= getComponents('DETAIL', getComponentParams()); // components xml
$strXML .= "<CATEGORY_ID><![CDATA[$category_id]]></CATEGORY_ID>";
$strXML .= "<CATEGORY_NAME><![CDATA[$category_name]]></CATEGORY_NAME>";
$strXML .= "<BRAND_ID><![CDATA[$iBrandId]]></BRAND_ID>";
$strXML .= "<PRODUCT_NAME_ID><![CDATA[$productNameInfoId]]></PRODUCT_NAME_ID>";
$strXML .= "<PRODUCT_ID><![CDATA[$rev_product_id]]></PRODUCT_ID>";
$strXML .= $cpopularxml;
$strXML .= $strMoreOn;
$strXML .= "<UPCOMING_COUNT><![CDATA[$up_result]]></UPCOMING_COUNT>";
$strXML .= "<COMPARE_TAB_URL>$compare_tab_url</COMPARE_TAB_URL>";
$strXML .= "<BREAD_CRUMB><![CDATA[$breadcrumb]]></BREAD_CRUMB>";
$strXML .= "<VIEW_DISP_TITLE><![CDATA[" . $disp_title_name . "]]></VIEW_DISP_TITLE>";
$strXML .= "<OC_ROS_BOTTOM_NORTH_728x90><![CDATA[OC_ROS_Bottom_North_728x90]]></OC_ROS_BOTTOM_NORTH_728x90>";
$strXML .= "<OC_ROS_TOP_RHS_LREC_300x250_1><![CDATA[OC_ROS_Top_RHS_Lrec_300x250_1]]></OC_ROS_TOP_RHS_LREC_300x250_1>";
$strXML .= "<SEO_CAR_FINDER><![CDATA[" . SEO_CAR_FINDER . "]]></SEO_CAR_FINDER>";
$strXML .= "<SEO_WEB_URL><![CDATA[" . SEO_WEB_URL . "]]></SEO_WEB_URL>";
$strXML .= "<SEO_AUTO_NEWS><![CDATA[" . SEO_AUTO_NEWS . "]]></SEO_AUTO_NEWS>";
$strXML .= "<ONCARS_SEO_URL><![CDATA[$oncars_seo_url]]></ONCARS_SEO_URL>";
$strXML .= "<SEO_USERREVIEW_URL><![CDATA[$seo_userreview_url]]></SEO_USERREVIEW_URL>";
$strXML .= "<ONCARS_ALL_REVIEWS_SEO_URL><![CDATA[$oncars_all_reviews_seo_url]]></ONCARS_ALL_REVIEWS_SEO_URL>";
$strXML .= "<ONCARS_EXPERT_REVIEWS_SEO_URL><![CDATA[$oncars_expert_reviews_seo_url]]></ONCARS_EXPERT_REVIEWS_SEO_URL>";
$strXML .= "<EXPERT_REVIEWS_SEO_URL><![CDATA[$expert_reviews_seo_url]]></EXPERT_REVIEWS_SEO_URL>";
$strXML .= "<EXPERTS_SEO_URL><![CDATA[$expert_seo_url]]></EXPERTS_SEO_URL>";
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<WRITE_REVIEW_LINK><![CDATA[$write_review_link]]></WRITE_REVIEW_LINK>";
$strXML .= "<SEO_OG_DESC><![CDATA[" . $seo_desc . "]]></SEO_OG_DESC>";
$seo_desc = "<meta name=\"Description\" content=\"$seo_desc\" />";
$seo_tags = "<meta name=\"Keywords\" content=\"$seo_keywords\" />";
$strXML .= "<SEO_DESC><![CDATA[" . html_entity_decode($seo_desc, ENT_QUOTES, "UTF-8") . "]]></SEO_DESC>";
$strXML .= "<SEO_TAGS><![CDATA[" . html_entity_decode($seo_tags, ENT_QUOTES, "UTF-8") . "]]></SEO_TAGS>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<SELECTEDTABID><![CDATA[$tab_id]]></SELECTEDTABID>";
$strXML .= "<PHOTO_TAB_ID><![CDATA[$photo_tab_id]]></PHOTO_TAB_ID>";
$strXML .= "<SEO_PHOTO_TAB_URL><![CDATA[$seo_photo_tab_url]]></SEO_PHOTO_TAB_URL>";
$strXML .= "<SEO_VIDEO_TAB_URL><![CDATA[$seo_video_tab_url]]></SEO_VIDEO_TAB_URL>";
$strXML .= "<CURR_CITY><![CDATA[" . $curr_city_name . "]]></CURR_CITY>";
$strXML .= "<DEALER_FLAG><![CDATA[" . $dealer_flag . "]]></DEALER_FLAG>";
$strXML .= "<DEALER_QUOTE_URL><![CDATA[$dealer_quote_url]]></DEALER_QUOTE_URL>";
$strXML .= "<BOOK_TEST_DRIVE_URL><![CDATA[$book_test_drive_url]]></BOOK_TEST_DRIVE_URL>";
$strXML .= "<RELATED_CITY_ID><![CDATA[" . $related_city_id . "]]></RELATED_CITY_ID>";
$strXML .= "<CURR_CITY_ID><![CDATA[" . $curr_city_id . "]]></CURR_CITY_ID>";
$strXML .= "<COMPARE_CARS_URL><![CDATA[$compareCarsUrl]]></COMPARE_CARS_URL>";
$strXML .= $sBrandDataDetXML;
$strXML .= $sProductDetXml;
$strXML .= $groupnodexml;
$strXML .= $sSimilarProductDetXml;
$strXML .= $sTopCopmetitorsListing;
$strXML .= $sWallpapersDetXml;
$strXML .= $sProductNewsDetXml;
$strXML .= $strmediaxml;
$strXML .= $srevDetailxml;
$strXML .= $sReviewDetailXml;
$strXML .= $strRevGroupxml;
$strXML .= $sReviewGROUPWISEDetailXml;
$strXML .= $sPhotoDetXML;
$strXML .= $sPhotoVideoDetXML;
$strXML .= $xml;
$strXML .= "<OVERVIEW>$sOverviewXML</OVERVIEW>";
$strXML .= "<PRODSELCITY>$prodCityId</PRODSELCITY>";
$strXML .= "<REVIEW_NAME>$reviewName</REVIEW_NAME>";
$strXML .= "<DEF_REVIEW_ID>$defRevId</DEF_REVIEW_ID>";
$strXML .= "<PRODSELGROUP>$rev_grp_id</PRODSELGROUP>";
$strXML .= "<FEATURE_YES_IMAGE_URL><![CDATA[" . FEATURE_YES_IMAGE_URL . "]]></FEATURE_YES_IMAGE_URL>";
$strXML .= "<FEATURE_NO_IMAGE_URL><![CDATA[" . FEATURE_NO_IMAGE_URL . "]]></FEATURE_NO_IMAGE_URL>";
$strXML .= "<MBREPLYLIST>";
$strXML .= $sReplyXml . $nodesPaging;
$strXML .= "<MBREPLYCOUNT><![CDATA[" . $iRecCnt . "]]></MBREPLYCOUNT>";
$strXML .= "<MBTID><![CDATA[" . $iTId . "]]></MBTID>";
$strXML .= "<CPAGE><![CDATA[" . $page . "]]></CPAGE>";
$strXML .= "<SERVICEID><![CDATA[" . SERVICEID . "]]></SERVICEID>";
$strXML .= "<CATEGORY><![CDATA[" . $rev_category_id . "]]></CATEGORY>";
$strXML .= "<PERPAGE><![CDATA[" . MBOARD_COMMENTS_PER_PAGE . "]]></PERPAGE>";
$strXML .= "</MBREPLYLIST>";
$strXML .= "<CITY_ID><![CDATA[" . $iSelCity . "]]></CITY_ID>";
$strXML .= "<USERREVIEW_SEO_URL><![CDATA[$userreview_seo_url]]></USERREVIEW_SEO_URL>";
$strXML .= "<PAGING><![CDATA[$sPagingXml]]></PAGING>";
$strXML .= "<RETURN_REVIEW_URL><![CDATA[" . $_SERVER['SCRIPT_URI'] . "]]></RETURN_REVIEW_URL>";
$strXML .= "<PAGE_NAME><![CDATA[" . $_SERVER['SCRIPT_URI'] . "]]></PAGE_NAME>";
$strXML .= "<USER_REVIEW_ID><![CDATA[$user_review_id]]></USER_REVIEW_ID>";
$strXML .= "<COMMENT_COUNT><![CDATA[" . $iRecCnt . "]]></COMMENT_COUNT>";
$strXML .= "<VIEWS_COUNT><![CDATA[$views_count]]></VIEWS_COUNT>";
$strXML .= "<VIEWS_PAGE_NAME><![CDATA[" . $views_page_name . "]]></VIEWS_PAGE_NAME>";
$strXML .= $expertratingxml;
$strXML .= $strexpertreviewxml;
$strXML .= $sVideoDetXML;
$strXML .= "<CHECKLOCATION><![CDATA[" . $_COOKIE['changenloc'] . "]]></CHECKLOCATION>";
$strXML .= "<PHOTO_CNT><![CDATA[" . $photo_cnt . "]]></PHOTO_CNT>";
$strXML .= "<VIDEO_CNT><![CDATA[" . $video_cnt . "]]></VIDEO_CNT>";
$strXML .= "<EMI_CALCULATOR_URL><![CDATA[" . $emi_calculator_url . "]]></EMI_CALCULATOR_URL>";
$strXML .= "<USER_SEL_CITYID><![CDATA[" . $usersel_city_id . "]]></USER_SEL_CITYID>";
$strXML .= "<RATING_REVIEW_TITLE_TYPE><![CDATA[" . $rating_review_title_type . "]]></RATING_REVIEW_TITLE_TYPE>";
$strXML .= "<PRODUCT_BRAND_NAME><![CDATA[" . $product_brand_name . "]]></PRODUCT_BRAND_NAME>";
$strXML .= "<PRODUCT_MODEL_NAME><![CDATA[" . $product_info_dispname . "]]></PRODUCT_MODEL_NAME>";
$strXML .= "<VARIANT_NAME><![CDATA[" . $product_variant_name . "]]></VARIANT_NAME>";
$pname = $product_brand_name . " " . $product_info_dispname . " " . $product_variant_name;
$mname = $product_brand_name . " " . $product_info_dispname;
$strXML .= "<PRODUCT_NAME><![CDATA[" . $pname . "]]></PRODUCT_NAME>";
$strXML .= "<MODEL_DISP_NAME><![CDATA[" . $mname . "]]></MODEL_DISP_NAME>";
$strXML .= "<MODEL_DISCONTINUE_STATUS><![CDATA[" . $model_discontinue_status . "]]></MODEL_DISCONTINUE_STATUS>";
$strXML .= "<PRODUCT_DISCONTINUE_STATUS><![CDATA[" . $product_discontinue_status . "]]></PRODUCT_DISCONTINUE_STATUS>";
$strXML .= "<THREE_MONTHS_PLUS_DISCONTINUE_DATE><![CDATA[" . $three_months_plus_discontinue_date . "]]></THREE_MONTHS_PLUS_DISCONTINUE_DATE>";
$strXML .= $most_helpful_review_url;
$strXML .= $expert_review_url;
$strXML .= $video_review_url;
$strXML .= $latest_review_api_url;
$strXML .= $rating_api_url;
$strXML .= "<VARIANT_PAGE><![CDATA[" . $variant_page . "]]></VARIANT_PAGE>";
$strXML .= "<OVERIEW_URL><![CDATA[" . $overiew_url . "]]></OVERIEW_URL>";
$strXML .= "<POPAD><![CDATA[$popad]]></POPAD>";
$strXML .= "<CARPRICE><![CDATA[$popadprice]]></CARPRICE>";
$strXML .= "<SELECTED_FEATURE_ID><![CDATA[$selected_feature_id]]></SELECTED_FEATURE_ID>";
$strXML .= "<INTERIOR_COUNT><![CDATA[" . $iphoto_cnt . "]]></INTERIOR_COUNT>";
$strXML .= "<EXTERIOR_COUNT><![CDATA[" . $ephoto_cnt . "]]></EXTERIOR_COUNT>";
$strXML .= "<ACTION><![CDATA[$action]]></ACTION>";
$strXML .= "<CURRTAB_SEL><![CDATA[$currtab_sel]]></CURRTAB_SEL>";
$strXML .= "<CURRTAB_SEL_SUB><![CDATA[$currtab_subsel]]></CURRTAB_SEL_SUB>";
$strXML .= "<CARSUMMERY><![CDATA[$summerystr]]></CARSUMMERY>";
$strXML .= $Dimensionstr;
$strXML .= $gallery;
$strXML .= "<OVERVIEW_URL><![CDATA[" . $oncars_overview_seo_url . "]]></OVERVIEW_URL>";
$strXML .= "<FEATURES_URL><![CDATA[" . $oncars_features_seo_url . "]]></FEATURES_URL>";
$strXML .= "<FREE_ADVICE><![CDATA[" . FREE_ADVICE_PHONE_NUMBER . "]]></FREE_ADVICE>";
$strXML .= "<ALL_REVIEW_URL><![CDATA[" . $oncars_all_reviews_seo_url . "]]></ALL_REVIEW_URL>";
$strXML .= "<ALL_USERREVIEW_URL><![CDATA[" . $seo_all_userreview_url . "]]></ALL_USERREVIEW_URL>";
$strXML .= "<ALL_EXPERTREVIEW_URL><![CDATA[" . $oncars_expert_reviews_seo_url . "]]></ALL_EXPERTREVIEW_URL>";
$strXML .= "<ALL_VIDEOREVIEW_URL><![CDATA[" . $all_videoreview_url . "]]></ALL_VIDEOREVIEW_URL>";
$strXML .= "<COLOR_URL><![CDATA[" . $oncars_color_url . "]]></COLOR_URL>";
$strXML .= $xml_tag;
$strXML .= $highlight_data;
$strXML .= "<EMI><![CDATA[" . $EMI . "]]></EMI>";
$strXML .= "<VIDEO_COUNT><![CDATA[" . $total_video_count . "]]></VIDEO_COUNT>";
$strXML .= "<PHOTO_COUNT><![CDATA[" . $scnt . "]]></PHOTO_COUNT>";
$strXML .= "<SET_POSITION></SET_POSITION>";
$strXML .= $listxml;
$strXML .= "<CURR_USER_REVIEW_ID><![CDATA[" . $curr_user_review_id . "]]></CURR_USER_REVIEW_ID>";
$strXML .= "<BODY_TYPE><![CDATA[" . $body_type . "]]></BODY_TYPE>";
$strXML .= "<COMPARE_TOP_COMPETITOR><![CDATA[" . $compare_tab_url . "]]></COMPARE_TOP_COMPETITOR>";
$strXML .= "<ONCARS_REVIEW_CATEGORYID>" . ONCARS_REVIEW_CATEGORYID . "</ONCARS_REVIEW_CATEGORYID>";
$strXML .= "<USER_REVIEW_VARIANT_CATEGORY_ID>" . USER_REVIEW_VARIANT_CATEGORY_ID . "</USER_REVIEW_VARIANT_CATEGORY_ID>";
$strXML .= "<COLOR_CNT>$color_cnt</COLOR_CNT>";
$strXML .= "<VARIANT_COUNT><![CDATA[" . $variant_count . "]]></VARIANT_COUNT>";
$strXML .= "<OC_RIGHT_BOTTOM_300X250><![CDATA[OC_Right_Bottom_300x250]]></OC_RIGHT_BOTTOM_300X250>";
$strXML .= $top_comp_search;
$strXML .= $sModelVideoListXML;
$strXML .= "<VIDEOPAGE_URL><![CDATA[" . $oncars_overview_seo_url . '/videos' . "]]></VIDEOPAGE_URL>";
$strXML .= "<PHOTOPAGE_URL><![CDATA[" . $oncars_overview_seo_url . '/photos' . "]]></PHOTOPAGE_URL>";
$strXML .= "<MODEL_VID_COUNT><![CDATA[" . $iModelVidCnt . "]]></MODEL_VID_COUNT>";
$strXML .= "<VID_TAB_CNT><![CDATA[" . $iTabVidCnt . "]]></VID_TAB_CNT>";
$strXML .= $sModelExpertReviewLinks . $sDealerListXML;
$strXML .= "<BRAND_NAME>$brand_name</BRAND_NAME>";
$strXML .= "<WR_PRODUCT_ID>$write_prd_id</WR_PRODUCT_ID>";

$strXML .= "<MODEL_SEO_URL><![CDATA[".$seo_variant_url."]]></MODEL_SEO_URL>";
$strXML .= "<MODELNEWS_SEO_URL><![CDATA[".$seo_variantnews_url."]]></MODELNEWS_SEO_URL>";
$strXML .= "<MODELREVIEWS_SEO_URL><![CDATA[".$seo_variantreview_url."]]></MODELREVIEWS_SEO_URL>";
$strXML .= "<MODELPHOTOS_SEO_URL><![CDATA[".$seo_variantphotos_url."]]></MODELPHOTOS_SEO_URL>";
$strXML .= "<MODELVIDEOS_SEO_URL><![CDATA[".$seo_variantvideo_url."]]></MODELVIDEOS_SEO_URL>";
$strXML .= "<COMPARE_TAB_URL>$compare_tab_url</COMPARE_TAB_URL>";
$strXML .= "<VARIANTBRAND_SEO_URL><![CDATA[".$seo_variantbrand_url."]]></VARIANTBRAND_SEO_URL>";
$strXML .= "<VARIANT_USER_REVIEW_URL><![CDATA[".$seo_variantuserreview_url."]]></VARIANT_USER_REVIEW_URL>";
$strXML .= "<VARIANT_USER_REVIEW>$latest_review_api_xml</VARIANT_USER_REVIEW>";
$strXML .= "<VARIANT_PHOTO_SLUG>$photoslug</VARIANT_PHOTO_SLUG>";
$strXML .= "<VARIANT_VIDEO_SLUG>$videosslug</VARIANT_VIDEO_SLUG>";
$strXML .= "<VARIANT_USER_REVIEW_DETAIL>$current_review_detail</VARIANT_USER_REVIEW_DETAIL>";
$strXML .= $sAlternateCarListXML . $sOtherCarListXML . $sUpComingCarWidgetList;
$strXML .= "</XML>";
$strXML = mb_convert_encoding($strXML, "UTF-8");
//header('Content-type: text/xml');echo $strXML;exit;
//echo $action; die();
if ($_REQUEST['debug'] == 1) {
    header('Content-type: text/xml');
    echo $strXML;
    exit;
}
/*if ($action === 'USER_REVIEW_SEO_MOREURL') {
    if ($get_user_review_id != '') {
        $xsl = "xsl/variant_user_review_detail.xsl";
    } else {
        $xsl = "xsl/variant_page_user_reviews.xsl";
    }
} else*/ if ($action === 'expert_review_detail') {
    $xsl = "xsl/expert_reviews_details.xsl";
}else if ($action === 'user_reviews') {
    if ($urevid != '') {
        $xsl = "xsl/variant_user_review_detail.xsl";
    }else{
         $xsl = "xsl/variant_review_page.xsl";
    }

} else if ($action === 'all_reviews') {
    $xsl = "xsl/variant_review_page.xsl";
}else if ($action === 'news') {
    $xsl = "xsl/gadgets_news_details.xsl";
} else if ($action === 'photos') {
    $xsl = "xsl/photos.xsl";
} else if ($action === 'videos') {
    $xsl = "xsl/videos.xsl";
} else {
    $xsl = "xsl/gadget_detail.xsl";
}
$xsl = DOMDocument::load($xsl);
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;
$xslt->registerPHPFunctions();
$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
