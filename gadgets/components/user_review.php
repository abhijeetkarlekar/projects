<?php

$callType = $_REQUEST['callType'] ? $_REQUEST['callType'] : 'internal'; // response type: xml, json (default: xml)
if ($callType = 'external') {

    require_once(dirname(__FILE__) . './../include/config.php'); // uncomment when run direct php script
}
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH . 'user_review.class.php');
require_once(CLASSPATH . 'feature.class.php');

$dbconn = new DbConn;
$product = new ProductManagement;
$category = new CategoryManagement;
$brand = new BrandManagement;
$userreview = new USERREVIEW;
$feature = new FeatureManagement;

$category_id = $component_params['category_id'] ? $component_params['category_id'] : '1';
$brand_id = $component_params['router_model_id'];
$brand_name = $component_params['brand_name'];
$product_info_id = $component_params['router_model_id'];
$model_name = $component_params['model_name'];
$product_id = $component_params['product_id'];
$variant_name = $component_params['variant_name'];
$startlimit = $component_params['offset'];
$limitcnt = $component_params['count'];

if (!empty($category_id)) {
        $category_result = $category->arrGetCategoryDetails($category_id);
}
$cat_path = $category_result[0]['seo_path'];
//print "<pre>"; print_r($_REQUEST); //die();
$user_review_result = $userreview->arrGetUserReviewDetails("", "", "", "", "", $brand_id, $category_id, $product_info_id, $product_id, "1", 0, $limitcnt);
//die();
$resultCnt = sizeof($user_review_result);
$xml .= "<LATEST_USER_REVIEW_MASTER>";
$xml .= "<COUNT>$resultCnt</COUNT>";
for ($i = 0; $i < $resultCnt; $i++) {

    $user_review_id = $user_review_result[$i]["user_review_id"];
    $uid = $user_review_result[$i]["uid"];
    $title = $user_review_result[$i]["title"];
    $user_name = $user_review_result[$i]["user_name"];
    $email = $user_review_result[$i]["email"];
    $location = $user_review_result[$i]["location"];
    $brand_id = $user_review_result[$i]["brand_id"];
    $category_id = $user_review_result[$i]["category_id"];
    $product_info_id = $user_review_result[$i]["product_info_id"];
    $product_id = $user_review_result[$i]["product_id"];
    $running = $user_review_result[$i]["running"];
    $year_manufacture = $user_review_result[$i]["year_manufacture"];
    $color = $user_review_result[$i]["color"];
    $SERVICEID = SERVICEID;
    $USER_REVIEW_VARIANT_CATEGORY_ID = USER_REVIEW_VARIANT_CATEGORY_ID;
    $comment_count = $aMBData['data'][$user_review_id][$USER_REVIEW_VARIANT_CATEGORY_ID][$SERVICEID];
    //if(!empty($comment_count) || $comment_count!=0){
    $user_review_result[$i]['comment_count'] = $comment_count;
    //}
    $create_date = $user_review_result[$i]["create_date"];
    if (!empty($category_id)) {
        $category_result = $category->arrGetCategoryDetails($category_id);
    }
    $category_path = $category_result[0]['seo_path'];


    if ($create_date != "" || $create_date != "0000-00-00 00:00:00") {
        $create_date = date("d F Y", strtotime($create_date));
        $user_review_result[$i]["create_date"] = $create_date;
    } else {
        $user_review_result[$i]["create_date"] = "";
    }
    $res = $product->arrGetProductNameInfo($product_info_id, $category_id, "", "", "1", "", "");
    $product_info_name = $res[0]['product_info_name'];
    $user_review_result[$i]["product_info_name"] = $product_info_name;
    $image_path = $res["0"]["image_path"];
    if (!empty($image_path)) {
        $image_path = resizeImagePath($image_path, "87X65", $aModuleImageResize, $video_img_id);
        $image_path = $image_path ? CENTRAL_IMAGE_URL . $image_path : '';
    }
    if (!empty($brand_id)) {
        $brand_result = $brand->arrGetBrandDetails($brand_id, $category_id);
        $brand_name = $brand_result[0]['brand_name'];
    }
    $user_review_result[$i]["brand_name"] = $brand_name;

    if (!empty($selected_city_id)) {
        $pro_detail = $product->arrGetProductDetails($product_id, $category_id, "", '1', "", "", "1", "", "", "", "", "", $selected_city_id);
    } else {
        $pro_detail = $product->arrGetProductDetails($product_id, $category_id, "", '1', "", "", "1", "", "", "1");
    }
    //echo $product_id."<br>";  print_r($pro_detail);   echo "<br>========================<br>";

    if (is_array($pro_detail)) {
        $variant_product_name = $pro_detail[0]['variant'];
        unset($variantUrlYear);
        $variantUrlYear = buildYear($pro_detail[0]['arrival_date'], $pro_detail[0]['discontinue_date']);
    }
    $user_review_result[$i]["variant_product_name"] = $variant_product_name;
    if ($variant_product_name == "") {
        $user_review_result[$i]["display_product_name"] = $brand_name . " " . $product_info_name;
    } else {
        $user_review_result[$i]["display_product_name"] = $brand_name . " " . $product_info_name . " " . $variant_product_name;
    }
    $fuel_type = "";
    if (!empty($product_id)) {
        unset($aProductDetail);
        if (!empty($selected_city_id)) {
            $aProductDetail = $product->arrGetProductDetails($product_id, $category_id, "", '1', "", "", "1", "", "", "", "", "", $selected_city_id);
        } else {
            $aProductDetail = $product->arrGetProductDetails($product_id, $category_id, "", '1', "", "", "1", "", "", "1");
        }
        $arrival_date = $aProductDetail[0]['arrival_date'];
        $discontinue_date = $aProductDetail[0]['discontinue_date'];


        $image_id = $aProductDetail[0]['image_id'];
        $image_path = $aProductDetail[0]['image_path'];
        if (!empty($image_path)) {
            $image_path = resizeImagePath($image_path, "45X60", $aModuleImageResize, $image_id);
        }
        $user_review_result[$i]["image_path"] = $image_path ? CENTRAL_IMAGE_URL . $image_path : '';


        $variantUrlYear = buildYear($arrival_date, $discontinue_date);
        $aOverview = $feature->arrGetSummary($category_id, $product_id, $type = "array");
        foreach ($aOverview as $key => $val) {
            if (!strpos($key, 'Price') && !strpos($key, 'Feature')) {
                unset($overviewArr);
                unset($summery);
                foreach ($aOverview[$key] as $overviewtitle => $overviewvalueArr) {
                    $overviewvalueArr = array_change_key_case($overviewvalueArr, CASE_UPPER);
                    if (strtolower($overviewtitle) == strtolower('phone type')) {
                        $fuel_type = $overviewvalueArr[0];
                    }
                }
                unset($overviewvalueArr);
            }
        }
    }
    $user_review_result[$i]['fuel_type'] = $fuel_type;
    unset($seoTitleArr);
    unset($user_review_url);
    $seoTitleArr[] = SEO_WEB_URL;
    $seoTitleArr[] = $category_path;
    $seoTitleArr[] = constructUrl($brand_name);
    $seoTitleArr[] = constructUrl($product_info_name);
    $seoTitleArr[] = constructUrl($variant_product_name);
    if (!empty($variantUrlYear)) {
        $seoTitleArr[] = $variantUrlYear;
    }
    $seoTitleArr[] = "user-reviews";
    $user_review_url = implode("/", $seoTitleArr);
    if (!empty($user_review_id)) {
        $user_review_url = "$user_review_url?urevid=$user_review_id";
    }
    $user_review_result[$i]["user_review_url"] = $user_review_url;
    $xml .="<LATEST_USER_REVIEW_MASTER_DATA>";
    $xml .= $sReplyXml;
    $user_review_result[$i] = array_change_key_case($user_review_result[$i], CASE_UPPER);

    foreach ($user_review_result[$i] as $k => $v) {
        $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $ratingresult = $userreview->arrGetUserQnA('', '', $user_review_id, "1");
    $ratingcnt = sizeof($ratingresult);
    $xml .= "<USER_RATING_MASTER>";
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
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
            } else {
                $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
            }
        }
        $ratingresult[$rating]['grade'] = $html;
        $rating_proportion = (($gradeCnt * 100) / 10) * 2;
        $ratingresult[$rating]['grade_proportion'] = $rating_proportion;
        $xml .= "<USER_RATING_MASTER_DATA>";
        $ratingresult[$rating] = array_change_key_case($ratingresult[$rating], CASE_UPPER);
        foreach ($ratingresult[$rating] as $k => $v) {
            $xml .= "<$k><![CDATA[$v]]></$k>";
        }
        $xml .= "</USER_RATING_MASTER_DATA>";
    }
    $xml .= "</USER_RATING_MASTER>";
    $reviewresult = $userreview->arrGetUserQnA('', '', $user_review_id, "0", "1"); // for comment
    $reviewcnt = sizeof($reviewresult);
    $xml .= "<USER_REVIEW_COMMENT_ANSWER_MASTER>";
    for ($review = 0; $review < $reviewcnt; $review++) {
        $que_id = $reviewresult[$review]['que_id'];
        $answer = $reviewresult[$review]['answer'];
        $answer = removeSlashes($answer);
        $answer = html_entity_decode($answer, ENT_QUOTES);
        if (strlen($answer) > 200) {
            $answer = getCompactString($answer, 200) . ' ...';
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

    $result = $userreview->arrGetOverallGrade($category_id, $brand_id, $product_id, $product_info_id, '1', $user_review_id);
    $overallcnt = $result[0]['totaloverallcnt'];
    $overallavg = round($result[0]['overallavg']);
    $html = "";
    for ($grade = 1; $grade <= 5; $grade++) {
        if ($grade <= $overallavg) {
            $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="vsblStr"/>';
        } else {
            $html .= '<img src="' . IMAGE_URL . 'spacer.gif" class="dsblStr"/>';
        }
    }
    $html_proportion = (($overallavg * 100) / 10) * 2;
    /*     * ***********added for user review rating*********** */
    $url = "";
    $url_postString = "";
    $cmd = PHP_PATH . ' ' . BASEPATH . "api/average_rating_api.php" . " brand_id=$brand_id product_name_id=$product_info_id product_id=$product_id user_review_id=$user_review_id";
    #echo $cmd;
    $xml_output = shell_exec($cmd);

    /*     * ***********added for user review rating*********** */
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
    unset($seoTitleArr);

    $seoTitleArr[] = SEO_WEB_URL;
    $seoTitleArr[] = $category_path;
    $seoTitleArr[] = "Reviews";
    $seoTitleArr[] = "Write-User-Reviews";
    $seoTitleArr[] = constructUrl($brand_name);
    $seoTitleArr[] = constructUrl($brand_name) . "-" . constructUrl($product_info_name);
    $seoTitleArr[] = $brand_id;
    $seoTitleArr[] = $product_info_id;

    $seo_write_review_url = implode("/", $seoTitleArr);
    $xml .= "<SEO_WRITE_REVIEW_URL><![CDATA[" . $seo_write_review_url . "]]></SEO_WRITE_REVIEW_URL>";
    $xml .= "</LATEST_USER_REVIEW_MASTER_DATA>";
}
unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = $category_path;
$seoTitleArr[] = "user-reviews";
$user_review_seo_moreurl = implode("/", $seoTitleArr);
$xml .= "<USER_REVIEW_SEO_MOREURL><![CDATA[" . $user_review_seo_moreurl . "]]></USER_REVIEW_SEO_MOREURL>";
$xml .= "</LATEST_USER_REVIEW_MASTER>";
$component_xml .= "<CAT_PATH><![CDATA[$cat_path]]></CAT_PATH>";
$component_xml .= $xml;

//$xml = "<XML>";
//$xml .= $component_xml;
//$xml .= "</XML>";
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
