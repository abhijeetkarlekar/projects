<?php

require_once('./include/config.php');
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'user_review.class.php');
require_once(CLASSPATH . 'Utility.php');
require_once(CLASSPATH . 'pivot.class.php');
require_once(CLASSPATH . 'feature.class.php');
require_once(CLASSPATH . 'wallpaper.class.php');
require_once(CLASSPATH . 'videos.class.php');
require_once(CLASSPATH . 'curl.class.php');
require_once(CLASSPATH . 'pager.class.php');
require_once(CLASSPATH.'reviews.class.php');

$dbconn = new DbConn();
$category = new CategoryManagement;
$brand = new BrandManagement;
$product = new ProductManagement;
$userreview = new USERREVIEW;
$oWallpapers = new Wallpapers;
$pivot = new PivotManagement;
$feature = new FeatureManagement;
$videoGallery = new videos();
$oReview  = new reviews;
$oCurl = new curl;



#echo "T1->".date('l jS \of F Y h:i:s A')."<br>"; #die();
$domain = DOMAIN;
$selected_brand_name = !empty($_REQUEST['router_brand_name']) ? $_REQUEST['router_brand_name'] : $_REQUEST['bname'];
$selected_brand_id = !empty($_REQUEST['router_brand_id']) ? $_REQUEST['router_brand_id'] : $_REQUEST['brand_id'];
$selected_city_id = $_REQUEST['router_city_id'];
$selected_city_name = $_REQUEST['router_city_name'];
$feature_id = $_REQUEST['router_feature_id'];
$feature_name = $_REQUEST['router_feature_name'];
$Selectedbodystyle = $feature_id;
$Selectedbodystylename = ucfirst($feature_name);
$is_city_all = empty($selected_city_id) ? '1' : '0';
$select_all_body_style = !empty($feature_id) ? '1' : '0';
settype($selected_brand_id, "integer");
$category_id = $_REQUEST['category_id'];
/*
  if($_COOKIE['changenloc_brand'] == ''){
  setcookie ("changenloc_brand", "1",time()+3600,'/',$domain); //used to change location fiden.
  }
 */
$prev_3_month = date('Y-m-d', strtotime("-" . DISCONTINUE_MONTH_DURATION . " month")) . ' 00:00:00';
$variant_id = "1"; // important for price search.ie. ex-showroom price.

$request_uri = $_SERVER['REQUEST_URI'];
$pageurl = $_SERVER['SCRIPT_URI'];
$queryStr = $_SERVER['QUERY_STRING'];
$pos = strpos($request_uri, '?');
if ($pos > 0) {
    $request_uri = substr($request_uri, 0, $pos);
}
$pgpos = strpos($request_uri, 'page');
if ($pgpos > 0) {
    $curpagenums = explode("page/", $request_uri);
    $curpagenum = $curpagenums[1];
    $currpageurl = $curpagenums[0];
} else {
    $currpageurl = $request_uri . "/";
}


$result = $brand->arrGetBrandDetails($selected_brand_id, $category_id, "1");
$brand_cnt = sizeof($result);
if (empty($brand_cnt)) {
    $seoUrlArr[] = SEO_WEB_URL;
    $seoUrlArr[] = $_REQUEST['cat_path'];
    $seoUrlArr[] = 'brands';
    $url = implode('/', $seoUrlArr);
    header('Location: ' . $url, TRUE, 301);
    exit;
}

for ($i = 0; $i < $brand_cnt; $i++) {
    $brand_name = $result[$i]['brand_name'];
    $brand_seo_path = $result[$i]['seo_path'];
    $brand_desc = $result[$i]['short_desc'];
    $brand_longdesc = $result[$i]['long_desc'];
    $upcoming_brand = $result[$i]['upcoming_brand'];
    $xml .= "<BRAND_DETAIL>";
    $short_desc = $result[$i]['short_desc'];
    if (strlen($brand_desc) > 400) {
        $short_desc = getCompactString($brand_desc, 400) . ' ...';
    }

    $result[$i]['long_desc'] = $brand_longdesc;
    $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
    $xml .= "<BRAND_DETAIL_DATA>";
    foreach ($result[$i] as $k => $v) {
        $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $xml .= "</BRAND_DETAIL_DATA>";
    $xml .= "</BRAND_DETAIL>";
}

unset($seoUrlArr);

$result = $pivot->arrGetPivotDetails("", $category_id, "", "1", "3");
$cnt = sizeof($result);
$bodyStyleArr = array();
for ($i = 0; $i < $cnt; $i++) {
    $bodyStyleArr[] = $result[$i]['feature_id'];
}
$result = $feature->arrGetBodyStyleByProduct(array('brand_id' => $selected_brand_id, 'feature_id' => implode(',', $bodyStyleArr)));
$cnt = sizeof($result);
$bodyStyleArr = array();
for ($i = 0; $i < $cnt; $i++) {
    $bodyStyleArr[] = $result[$i]['feature_id'];
}
unset($result);
unset($cnt);

$sortproductBY = $_REQUEST['sortproduct'] ? $_REQUEST['sortproduct'] : '1';
$sortproductxml = "<SELECTED_SORT_PRODUCT_BY><![CDATA[$sortproductBY]]></SELECTED_SORT_PRODUCT_BY>";
/* Start Pagination constants. */
define("PERPAGE", 10);
define("OFFSET", "pageno");
define("STARTPAGESHOWN", 10);
define("MAXPAGESHOWN", 10);
$cnt = 0;
$totalcount = 0;
$page = (int) (!isset($_REQUEST["page"]) ? 1 : $_REQUEST["page"]);
$endlimit = PERPAGE;
$startlimit = ($page * $endlimit) - $endlimit;
switch ($sortproductBY) {
    case '1':
        //echo "ASC 1";
        $orderby = ' PRICE_VARIANT_VALUES.variant_value asc ';
        break;
    case '2':
        //echo "ASC 2";
        $orderby = " PRICE_VARIANT_VALUES.variant_value desc";
        break;
    default:
        //echo "ASC 5";
        $orderby = "PRICE_VARIANT_VALUES.variant_value asc";
        break;
}

$city_base_cnt = 0;



$count_result = $product->searchProductCount($category_id, $selected_brand_id, "", $feature_id, "1", $startprice, $endprice, $variant_id, "", "", "", "1");
//$result = $product->searchProduct($category_id,$selectedbrandArr,"",$selectedfeatureArr,"1",$startprice,$endprice,$variant_id,$startlimit,$endlimit,$orderby,"","1");



$totalcount = $count_result[0]['cnt'] ? $count_result[0]['cnt'] : 0;
//echo "totalcount---".$totalcount."<br>";
// paging

$endlimit = empty($curpagenum) ? FRONT_PERPAGE : 10;
$oPager = new Pager();
$startlimit = $oPager->findStart($limit);
$pages = ceil($totalcount / FRONT_PERPAGE);

$siteUrl = SEO_WEB_URL . $currpageurl;
if (empty($curpagenum)) {
    $startlimit = 0;
    $curpagenum = 1;
} else {
    $startlimit = ($curpagenum - 1) * $endlimit;
}

if (!empty($curpagenum)) {
    //echo "$curpagenum , $pages , $siteUrl";
    $sPagingXml .= $oPager->pageNumNextPrevUrl($curpagenum, $pages, $siteUrl, $link_type);
}
if ($curpagenum > 1) {
    $showingstart = ($endlimit * ($curpagenum - 1)) + 1;
    $showingend = ($endlimit * $curpagenum);
} else {
    $showingstart = $curpagenum;
    $showingend = $endlimit;
}

// echo $startlimit."=============".$endlimit; 
//$count_result = $product->searchProductCount($category_id,$selectedbrandArr,"",$selectedfeatureArr,"1",$startprice,$endprice,$variant_id,"","","","1");
$result = $product->searchProduct($category_id, $selected_brand_id, "", $feature_id, "1", $startprice, $endprice, $variant_id, $startlimit, $endlimit, $orderby, "", "1");


$cnt = sizeof($result);
//echo "CNONT===".$cnt;
//print "<pre>"; print_r($result); die();
$productxml = "<PRODUCT_MASTER>";
$productxml .= "<TOTAL_SEARCH_ITEM_FOUND><![CDATA[" . $totalcount . "]]></TOTAL_SEARCH_ITEM_FOUND>";
$productxml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
$pcount = 0;
for ($i = 0; $i < $cnt; $i++) {
    $link_model_name = "";
    $seo_model_url = "";
    unset($modelnameSeoArr);
    unset($modelnameArr);
    unset($variantnameSeoArr);
    $product_id = $result[$i]['product_id'];
    $brand_id = $result[$i]['brand_id'];
    $category_id = $result[$i]['category_id'];
    $product_name = trim($result[$i]['product_name']);
    $product_name_id = $result[$i]['product_name_id'];
    $variant = trim($result[$i]['variant']);
    //$short_desc = trim($result[$i]['short_desc']);
    $product_discontinue_flag = $result[$i]['discontinue_flag'];
    $product_discontinue_date = $result[$i]['discontinue_date'];
    $result[$i]['product_discontinue_flag'] = $product_discontinue_flag;
    $result[$i]['product_discontinue_date'] = $product_discontinue_date;
    $three_months_plus_discontinue_date = 0;
    $prev_3_month = date('Y-m-d', strtotime("-" . DISCONTINUE_MONTH_DURATION . " month")) . ' 00:00:00';
    if (($product_discontinue_flag == "0") && (strtotime($product_discontinue_date) < strtotime($prev_3_month)) && $product_discontinue_date != '0000-00-00 00:00:00') {
        $three_months_plus_discontinue_date = 1;
    }
    if (($product_discontinue_flag == "0") && ((strtotime($product_discontinue_date) > strtotime($prev_3_month)) || $product_discontinue_date == '0000-00-00 00:00:00')) {
        $three_months_plus_discontinue_date = 2;
    }
    $result[$i]['three_months_plus_discontinue_date'] = $three_months_plus_discontinue_date;
    $brand_result = $brand->arrGetBrandDetails($brand_id, $category_id);
    $brand_name = trim($brand_result[0]['brand_name']);
    $brand_seo_path = trim($brand_result[0]['seo_path']);
    $curr_brand_name = trim($brand_result[0]['brand_name']);
    unset($product_discontinue_date);
    unset($product_discontinue_flag);
    if (!empty($category_id)) {
        $category_result = $category->arrGetCategoryDetails($category_id);
    }
    $category_seo_path = $category_result[0]['seo_path'];
    



    //set seo url for product variant page.
    $variantnameSeoArr[] = SEO_WEB_URL;
    $variantnameSeoArr[] = $category_seo_path;
    $brand_name = $brand_name;
    $product_name = $product_name;
    $variant = $variant;
    $variantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
    $variantnameSeoArr[] = seo_title_replace(constructUrl($product_name));
    $variantnameSeoArr[] = seo_title_replace(constructUrl($variant));

    unset($varianUrlYear);
    $varianUrlYear = buildYear($result[$i]['arrival_date'], $result[$i]['discontinue_date']);
    if (!empty($varianUrlYear)) {
        $variantnameSeoArr[] = $varianUrlYear;
    }
    if (!empty($brand_name)) {
        $modelnameArr[] = $brand_name;
    }
    if (!empty($product_name)) {
        $modelnameArr[] = $product_name;
    }
    if (!empty($brand_name)) {
        $comparenames[] = constructUrl($brand_name);
    }
    if (!empty($product_name)) {
        $comparenames[] = constructUrl($product_name);
    }
    if (!empty($variant)) {
        $comparenames[] = constructUrl($variant);
    }
    if (!empty($varianUrlYear)) {
        $comparenames[] = $varianUrlYear;
    }
    $comparename = constructUrl(implode("-", $comparenames));
    unset($comparenames);
    $result[$i]['comparename'] = $comparename;



    if (empty($brandCheck)) {
        //get model name and seo url.
        $modelnameSeoArr[] = SEO_WEB_URL;
        $modelnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
        $modelnameSeoArr[] = seo_title_replace(constructUrl($product_name));
        $model_result = $product->arrGetProductNameInfo("", $category_id, "", $product_name);
        $model_id = $model_result[0]['product_name_id'];
        $curr_model_name = $model_result[0]['product_info_name'];
        $result[$i]['product_name_id'] = $model_id;
        $link_model_name = implode(" ", $modelnameArr);
        $seo_model_url = implode("/", $modelnameSeoArr);
        $brandCheck = 1;
    }
    if (!empty($variant)) {
        $modelnameArr[] = $variant;
    }
    //echo CENTRAL_IMAGE_URL; die();
    $product_video_path = $result[$i]['video_path'];
    if (!empty($product_video_path)) {
        $result[$i]['video_path'] = CENTRAL_IMAGE_URL . str_replace(array(CENTRAL_IMAGE_URL), "", $product_video_path);
    }
    //$image_path = $result[$i]['model_image_path'];
    $image_path = $result[$i]['image_path'];
    if (!empty($image_path)) {
        $image_path = resizeImagePath($image_path, "145X193", $aModuleImageResize);
        $result[$i]['image_path'] = CENTRAL_IMAGE_URL . str_replace(array(CENTRAL_IMAGE_URL), "", $image_path);
    }
    $result[$i]['EXSHOWROOMPRICE_ORIGIONAL'] = $result[$i]['variant_value'];
    $result[$i]['EXSHOWROOMPRICE'] = $result[$i]['variant_value'] ? priceFormat($result[$i]['variant_value']) : '';
    //echo $link_model_name."====".$result[$i]['EXSHOWROOMPRICE']."<br>";
    $priceValueArr[] = $result[$i]['variant_value'];
    $result[$i]['DISPLAY_PRODUCT_NAME'] = implode(" ", $modelnameArr);
    $result[$i]['SEO_URL'] = implode("/", $variantnameSeoArr);

    $expert_review_param =  implode(" ", array($curr_brand_name ,$curr_model_name));
    //echo EXPERT_REVIEW_API.rawurlencode(strtolower($expert_review_param));
    if($expert_review_param!=''){
        $expert_rating = $oReview->getBgrExpertReviews($expert_review_param,1);
    }

    $result[$i]['expert_rating'] = $expert_rating;

    $search_key = $curr_brand_name." ".$curr_model_name;
    $feed_url = BGR_NEWSFEED_URL.rawurlencode(str_replace(" ", "-", strtolower($search_key)));
    $content1 = @file_get_contents($feed_url);
    $content = str_replace('&', '&amp;', $content1);
    //header('Content-type: text/xml');
    //echo $content; die;
    if ($content1 != false) {
        echo "TRUE";
        $result[$i]['is_news'] = 1;  
        $result[$i]['news_url'] = BGR_NEWS_URL.str_replace(" ", "-", strtolower($search_key));   
    }
    unset($photocnt);
    $photocnt = $oWallpapers->arrSlideShowDetailsCount("", "", "", $product_name_id,"", $category_id, $selected_brand_id, 1, "", "", "", "", "0", "1");
    print_r($photocnt); 
    if($photocnt > 0){
        $PhotoSeoArr[] = SEO_WEB_URL;
        $PhotoSeoArr[] = $category_seo_path;
        $variantnameSeoArr[] = $seo_brand_path;
        $variantnameSeoArr[] = $model_seo_path;
        if($variant!=""){
            $variantnameSeoArr[] = $variant_seo_path;
        }
    }
    $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
    //print_r($result[$i]);
    $productxml .= "<PRODUCT_MASTER_DATA>";
    $rootxml = "";
    foreach ($result[$i] as $k => $v) {
        if (is_array($v) && $k == "SHORT_DESC") {
            $productxml .= "<PRODUCT_FEATURE_MASTER_DATA>";
            foreach ($v as $fk => $fv) {
                $productxml .= "<PRODUCT_FEATURE_SUMMERY_DATA><![CDATA[$fv]]></PRODUCT_FEATURE_SUMMERY_DATA>";
            }
            $productxml .= "</PRODUCT_FEATURE_MASTER_DATA>";
        }
        $productxml .= "<$k><![CDATA[$v]]></$k>";
    }
    $productxml .= "</PRODUCT_MASTER_DATA>";
}
$productxml .= "</PRODUCT_MASTER>";




$aExpertCnt = $aVideoCnt = $aPhotoCnt = array();
foreach ($aModelCount as $iK => $aData) {
    if ($aData['model_video_count'] > 0) {
        $iVideoCnt = count($aVideoCnt);
        $aVideoCnt[$iVideoCnt]['count'] = $aData['model_video_count'];
        $aVideoCnt[$iVideoCnt]['seo_url'] = $aData['model_video_url'];
        $aVideoCnt[$iVideoCnt]['model'] = $aData['model_name'];
    }
    if ($aData['model_photo_count'] > 0) {
        $iPhotoCnt = count($aPhotoCnt);
        $aPhotoCnt[$iPhotoCnt]['count'] = $aData['model_photo_count'];
        $aPhotoCnt[$iPhotoCnt]['seo_url'] = $aData['model_photo_url'];
        $aPhotoCnt[$iPhotoCnt]['model'] = $aData['model_name'];
    }
}
if (is_array($aPhotoCnt) && count($aPhotoCnt) > 0) {
    $iPhotoCnt = count($aPhotoCnt);
    if ($iPhotoCnt > 5) {
        $aExpertRand = array_rand($aPhotoCnt, 5);
    }
    foreach ($aPhotoCnt as $iK => $aData) {
        if ($iPhotoCnt > 5) {

            if (in_array($iK, $aExpertRand)) {
                $sModelExpertReviewLinks.= '<MODEL_PHOTO_LINK>';
                $sModelExpertReviewLinks.= "<SEO_PHOTO_URL><![CDATA[" . $aData['seo_url'] . "]]></SEO_PHOTO_URL>";
                $sModelExpertReviewLinks.= "<SEO_MODEL><![CDATA[" . $aData['model'] . "]]></SEO_MODEL>";
                $sModelExpertReviewLinks.= '</MODEL_PHOTO_LINK>';
            }
        } else {
            $sModelExpertReviewLinks.= '<MODEL_PHOTO_LINK>';
            $sModelExpertReviewLinks.= "<SEO_PHOTO_URL><![CDATA[" . $aData['seo_url'] . "]]></SEO_PHOTO_URL>";
            $sModelExpertReviewLinks.= "<SEO_MODEL><![CDATA[" . $aData['model'] . "]]></SEO_MODEL>";
            $sModelExpertReviewLinks.= '</MODEL_PHOTO_LINK>';
        }
    }
}

if (is_array($aVideoCnt) && count($aVideoCnt) > 0) {
    $iVideoCnt = count($aVideoCnt);
    if ($iVideoCnt > 5) {
        $aExpertRand = array_rand($aVideoCnt, 5);
    }
    foreach ($aVideoCnt as $iK => $aData) {
        if ($iVideoCnt > 5) {

            if (in_array($iK, $aExpertRand)) {

                $sModelExpertReviewLinks.= '<MODEL_VIDEO_LINK>';
                $sModelExpertReviewLinks.= "<SEO_VIDEO_URL><![CDATA[" . $aData['seo_url'] . "]]></SEO_VIDEO_URL>";
                $sModelExpertReviewLinks.= "<SEO_MODEL><![CDATA[" . $aData['model'] . "]]></SEO_MODEL>";
                $sModelExpertReviewLinks.= '</MODEL_VIDEO_LINK>';
            }
        } else {
            $sModelExpertReviewLinks.= '<MODEL_VIDEO_LINK>';
            $sModelExpertReviewLinks.= "<SEO_VIDEO_URL><![CDATA[" . $aData['seo_url'] . "]]></SEO_VIDEO_URL>";
            $sModelExpertReviewLinks.= "<SEO_MODEL><![CDATA[" . $aData['model'] . "]]></SEO_MODEL>";
            $sModelExpertReviewLinks.= '</MODEL_VIDEO_LINK>';
        }
    }
}

if (is_array($aExpertCnt) && count($aExpertCnt) > 0) {
    if ($iExpertCnt > 5) {
        $aExpertRand = array_rand($aExpertCnt, 5);
    }
    foreach ($aExpertCnt as $iK => $aData) {
        if ($iExpertCnt > 5) {

            if (in_array($iK, $aExpertRand)) {

                $sModelExpertReviewLinks.= '<MODEL_EXPERT_REVIEW_LINK>';
                $sModelExpertReviewLinks.= "<SEO_EXPERT__REVIEW_URL><![CDATA[" . $aData['seo_url'] . "]]></SEO_EXPERT__REVIEW_URL>";
                $sModelExpertReviewLinks.= "<SEO_MODEL><![CDATA[" . $aData['model'] . "]]></SEO_MODEL>";
                $sModelExpertReviewLinks.= '</MODEL_EXPERT_REVIEW_LINK>';
            }
        } else {
            $sModelExpertReviewLinks.= '<MODEL_EXPERT_REVIEW_LINK>';
            $sModelExpertReviewLinks.= "<SEO_EXPERT__REVIEW_URL><![CDATA[" . $aData['seo_url'] . "]]></SEO_EXPERT__REVIEW_URL>";
            $sModelExpertReviewLinks.= "<SEO_MODEL><![CDATA[" . $aData['model'] . "]]></SEO_MODEL>";
            $sModelExpertReviewLinks.= '</MODEL_EXPERT_REVIEW_LINK>';
        }
    }
}

//echo "T6->".date('l jS \of F Y h:i:s A')."<br>"; //die();

if (empty($is_price)) {
    if ((sizeof($presult)) > 0) {
        if ((sizeof($presult)) > 1) {
            //$min_variant_price = $presult[0][0]['variant_value'];
            //$max_variant_price = $presult[$k][0]['variant_value'];
            foreach ($presult as $kp => $kpValue) {
                if (is_array($kpValue)) {
                    foreach ($kpValue as $kpr => $kprValue) {
                        $kpr_prices[] = $kprValue['variant_value'];
                    }
                }
            }
            sort($kpr_prices);
            $k = sizeof($kpr_prices) - 1;
            $min_variant_price = $kpr_prices[0];
            $max_variant_price = $kpr_prices[$k];
        } elseif ((sizeof($presult)) == 1) {
            $min_variant_price = $presult[0][0]['variant_value'];
            $max_variant_price = $min_variant_price;
        }
        $pval_arr = getPriceBarValue($min_variant_price, $max_variant_price);
        $mn_price = $pval_arr['min_price_val'];
        $mn_price_unit = $pval_arr['min_price_unit'];
        $mx_price = $pval_arr['max_price_val'];
        $mx_price_unit = $pval_arr['max_price_unit'];
        $min_conv_price = $pval_arr['min_converted_price'];
        $mx_conv_price = $pval_arr['max_converted_price'];
    }
} else {
    $min_conv_price = $startprice;
    $mx_conv_price = $endprice;
}
unset($result);
unset($cnt);

global $top_cities;

$iTopCities = implode(',', $top_cities);

unset($result);
unset($cnt);
/* local dealer of brand and location */

//$paging = $oPager->searchpagination($totalcount,$endlimit,$page);
//$xml_paging .="<PAGING><![CDATA[".$paging."]]></PAGING>";

$strXMLCache .= $productxml;
$strXMLCache .= $sArticleDetXml;
$strXMLCache .= $rev_xml;
$strXMLCache .= $xml;
//$strXMLCache .= $dealer_xml;
$strXMLCache .= $strArticlexml;
$strXMLCache .= $xml_paging;
$strXMLCache .= "<TOTAL_SEARCH_ITEM_FOUND><![CDATA[" . $totalcount . "]]></TOTAL_SEARCH_ITEM_FOUND>";
$strXMLCache .= "<TOTAL_SEARCH_ITEM><![CDATA[" . $def_total_count . "]]></TOTAL_SEARCH_ITEM>";
$strXMLCache .= "<MAX_PRICE><![CDATA[$mx_price]]></MAX_PRICE>";
$strXMLCache .= "<MAX_PRICE_UNIT><![CDATA[$mx_price_unit]]></MAX_PRICE_UNIT>";
$strXMLCache .= "<MIN_PRICE><![CDATA[$mn_price]]></MIN_PRICE>";
$strXMLCache .= "<MIN_PRICE_UNIT><![CDATA[$mn_price_unit]]></MIN_PRICE_UNIT>";
if (!empty($mx_price) && !empty($mn_price)) {
    $mx_price_value = $mx_price * $mx_price_unit;
    $mn_price_value = $mn_price * $mn_price_unit;
}
$fp = fopen($xml_file_path, "w+");
fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>' . $strXMLCache);
fclose($fp);

/* Start of Code for user reivew on brand name */
$selected_brand_name = ucfirst($selected_brand_name);
$Selectedbodystylename = ucfirst($Selectedbodystylename);
$curr_city_name = ucfirst($curr_city_name);

unset($result);
unset($cnt);
//$selected_brand_name = ucwords($selected_brand_name);
$seo_titlearr[] = ucwords(str_replace('-', ' ', $selected_brand_name));
if (!empty($Selectedbodystylename)) {
    $seo_titlearr[] = ucfirst(str_replace('-', ' ', $Selectedbodystylename));
}
//if($is_city == 1){
if (strlen($curr_city_name) > 0) {
    $seo_titlearr[] = "Cars in " . ucfirst($curr_city_name) . " - Explore ";
} else {
    $seo_titlearr[] = "Cars in India - Explore ";
}
$seo_titlearr[] = str_replace('-', ' ', $selected_brand_name);
if (!empty($Selectedbodystylename)) {
    $seo_titlearr[] = ucfirst(str_replace('-', ' ', $Selectedbodystylename));
}
$seo_titlearr[] = "Cars Models with Price";
if ($isprice == 1) {
    $seo_titlearr[] = $mn_price . " " . $mn_price_unit . " to " . $mx_price . " " . $mx_price_unit;
}
//if($is_city == 1){
if (strlen($curr_city_name) > 0) {
    $seo_titlearr[] = "in " . ucfirst($curr_city_name);
} else {
    $seo_titlearr[] = "in India";
}
$seo_titlearr[] = "| OnCars.in";
$seo_title = implode(" ", $seo_titlearr);

$seo_descarr[] = ucwords(str_replace('-', ' ', $selected_brand_name));

if (!empty($Selectedbodystylename)) {
    $seo_descarr[] = str_replace('-', ' ', $Selectedbodystylename);
}
if (strlen($curr_city_name) > 0) {
    $seo_descarr[] = "Cars in " . ucfirst($curr_city_name) . " : Explore ";
} else {
    $seo_descarr[] = "Cars in India : Explore ";
}
$seo_descarr[] = ucwords(str_replace('-', ' ', $selected_brand_name));
if (!empty($Selectedbodystylename)) {
    $seo_descarr[] = str_replace('-', ' ', $Selectedbodystylename);
}
$seo_descarr[] = "Cars Models with Price";
if ($isprice == 1) {
    $seo_descarr[] = $mn_price . " " . $mn_price_unit . " to " . $mx_price . " " . $mx_price_unit;
}
if (strlen($curr_city_name) > 0) {
    $seo_descarr[] = "in $curr_city_name. Get all";
} else {
    $seo_descarr[] = "in India. Get all";
}
$seo_descarr[] = str_replace('-', ' ', $selected_brand_name);
if (!empty($Selectedbodystylename)) {
    $seo_descarr[] = str_replace('-', ' ', $Selectedbodystylename);
}
$seo_descarr[] = "Car reviews, features,  price,  seating capacity, fuel type at OnCars.in";
$seo_desc = implode(" ", $seo_descarr);

$seo_brand = ucwords(str_replace('-', ' ', $selected_brand_name));
$seo_tags = "$seo_brand, $seo_brand cars, $seo_brand india, $seo_brand cars in india, $seo_brand car prices, $seo_brand car india, $seo_brand car dealers, $seo_brand car features, $seo_brand cars price in india, $seo_brand car price, $seo_brand, $seo_brand car variants, $seo_brand car models, $seo_brand models in india";

$featureresult = $feature->arrGetFeatureDetails(array_unique($bodyStyleArr));
$bodystylexml = "<BODY_STYLE>";
$featurecnt = sizeof($featureresult);
for ($fc = 0; $fc < $featurecnt; $fc++) {
    $count_prodcut_result = '';
    $bdy_feature_id = '';
    $body_style_seo_url = array();
    $body_style_seo_url[] = SEO_WEB_URL;
    $body_style_seo_url[] = $_REQUEST['cat_path'];
    $body_style_seo_url[] = constructUrl($selected_brand_name);
    $body_style_seo_url[] = constructUrl($curr_city_name);
    $body_style_seo_url[] = constructUrl($featureresult[$fc]['feature_name']);
    #$body_style_seo_url[] = "price-".$min_conv_price."-".$mx_conv_price;

    $style_seo_url = implode("/", $body_style_seo_url);
    $featureresult[$fc]['style_seo_url'] = $style_seo_url;
    $bdy_feature_id = $featureresult[$fc]['feature_id'];
    $count_prodcut_result = $product->searchProductCountByBodyStyle($category_id, $selected_brand_id, "", $bdy_feature_id, "1", $startprice, $endprice, "1", "", "", $sCityId, "", "1");
    $featureresult[$fc]['bdy_product_count'] = $count_prodcut_result[0]['cnt'];
    $featureresult[$fc] = array_change_key_case($featureresult[$fc], CASE_UPPER);
    $bdy_feature_id = '';
    $bodystylexml .= "<BODY_STYLE_DATA>";
    foreach ($featureresult[$fc] as $k => $v) {
        $bodystylexml .= "<$k><![CDATA[$v]]></$k>";
    }
    $bodystylexml .= "</BODY_STYLE_DATA>";
}
$bodystylexml .= "</BODY_STYLE>";

$count_prodcut_result_all_bdy = $product->searchProductCountByBodyStyle($category_id, $selected_brand_id, "", "", "1", $startprice, $endprice, "1", "", "", $sCityId, "", "1");
$count_all_bdy = $count_prodcut_result_all_bdy[0]['cnt'];
$bodystylexmlall[] = SEO_WEB_URL;
$bodystylexmlall[] = $_REQUEST['cat_path'];
$bodystylexmlall[] = $selected_brand_name;
$bodystylexmlall[] = constructUrl($curr_city_name);
#$bodystylexmlall[] = "price-".$min_conv_price."-".$mx_conv_price;
$style_seo_allurl = implode("/", $bodystylexmlall);
$bodystylexmlallurl = "<BODY_STYLE_ALL>$style_seo_allurl</BODY_STYLE_ALL>";

unset($result);

if (!empty($category_id)) {
    $result = $brand->arrGetBrandDetails("", $category_id, 1);
}
unset($bBrandArr1);
unset($bBrandArr2);
$cnt = sizeof($result);
$xml = "<BRAND_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
$selectedIndex = "0";
$isBrandSelected = "0"; //used toggle all brands checkbox.
for ($i = 0; $i < $cnt; $i++) {
    $brand_id = $result[$i]['brand_id'];
    if (in_array($brand_id, $selectedbrandArr)) {
        $result[$i]['selected_brand_id'] = $brand_id;
        $selecteditemArr[$selectedIndex]['selected_id'] = $brand_id;
        $selecteditemArr[$selectedIndex]['selected_type'] = 'checkbox_brand_id_' . $brand_id;
        $selecteditemArr[$selectedIndex]['selected_name'] = $result[$i]['brand_name'];
        $selectedIndex++;
        $isBrandSelected++;
    }
    if (in_array($result[$i]['brand_id'], $top_brand_arr)) {
        $result[$i]['top_brand'] = 1;
    } else {
        $result[$i]['top_brand'] = 0;
    }
    $result[$i]['seo_brand_name'] = constructUrl($result[$i]['brand_name']);
    $status = $result[$i]['status'];
    $categoryid = $result[$i]['category_id'];
    $result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
    $result[$i]['create_date'] = date('d-m-Y', strtotime($result[$i]['create_date']));
    $result[$i]['js_brand_name'] = $result[$i]['brand_name'];
    $result[$i]['selected_brand_name'] = constructUrl($result[$i]['brand_name']);
    $result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'], ENT_QUOTES, 'UTF-8');
    $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
    $xml .= "<BRAND_MASTER_DATA>";
    foreach ($result[$i] as $k => $v) {
        $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $xml .= "</BRAND_MASTER_DATA>";
}
$xml .= "</BRAND_MASTER>";

#$new_breadcrumb = CATEGORY_HOME.'<a href="'.WEB_URL.SEO_CAR_RESEARCH.'" itemprop="url" itemprop="title">New Cars</a><span class="breadcrumb sprit-icon"></span>'. $brand_name;
$new_breadcrumb = carBrandBreadCrumb($brand_name);
////ip tracking code start
$pviews = 1;
////ip tracking code end

$moreon_result = Array('0' => array('link' => implode('/', array(SEO_WEB_URL, $_REQUEST['cat_path'], SEO_UPCOMING_CARS, ucfirst(constructUrl($selected_brand_name)))), 'title' => "Upcoming $brand_name cars in India")
);


$strMoreOn .="<MORE_ON_CAR>";
$cnt = count($moreon_result);
for ($i = 0; $i < $cnt; $i++) {
    $strMoreOn .="<MORE_ON_CAR_DATA>";
    $strMoreOn .="<MORE_ON_CAR_DATALINK>" . $moreon_result[$i]['link'] . "</MORE_ON_CAR_DATALINK>";
    $strMoreOn .="<MORE_ON_CAR_DATATITLE>" . $moreon_result[$i]['title'] . "</MORE_ON_CAR_DATATITLE>";
    $strMoreOn .="</MORE_ON_CAR_DATA>";
}
$strMoreOn .="</MORE_ON_CAR>";


/* News Feed Start */
$feed_url = "http://www.bgr.in/feed/?tag=" . urlencode(str_replace(" ", "-", $brand_name));
$content1 = @file_get_contents($feed_url);
$content = str_replace('&', '&amp;', $content1);
if ($content1 != false) {
    $x = new SimpleXmlElement($content);
    $newscnt = count($x->channel->item);
    $sArticleDetXml .= "<NEWS_MASTER>";
    $sArticleDetXml .= "<COUNT><![CDATA[$newscnt]]></COUNT>";
    $sArticleDetXml .= "<FEED_URL><![CDATA[$feed_url]]></FEED_URL>";
    if ($newscnt > 0) {
        foreach ($x->channel->item as $entry) {
            $sArticleDetXml .= "<NEWS_MASTER_DATA>";
            $sArticleDetXml .= "<SEO_URL>$entry->link</SEO_URL>";
            $sArticleDetXml .= "<TITLE>$entry->title</TITLE>";
            $disp_date = date('d M Y', strtotime($entry->pubDate));
            $sArticleDetXml .= "<DISP_DATE>$disp_date</DISP_DATE>";
            $description = getCompactString(strip_tags($entry->description), 180, true) . ' ...';
            $sArticleDetXml .= "<DESCRIPTION>$description</DESCRIPTION>";
            $sArticleDetXml .= "</NEWS_MASTER_DATA>";
        }
    }
    $sArticleDetXml .= "</NEWS_MASTER>";
}
$sProductNewsDetXml = $sArticleDetXml;
/* News Feed End */


$up_result = $product->getUpcomingProductCount($category_id, $selected_brand_id, $product_name_id, $feature_id, $min_conv_price);
//PRINT_R($_REQUEST);
$config_details = get_config_details();
$login_details = getCookie();
#$css_detail = get_css(array("style.css","brandpagecss.css","widgets_css.css","review_page_css.css"));
$strXML .= "<XML>";
$strXML .= getComponents('BRAND', getComponentParams()); // components xml
$strXML .= $strMoreOn;
$strXML .= $css_detail;
$strXML .= $login_details;
$strXML .= $sArticleDetXml;
$strXML .= "<UPCOMING_COUNT><![CDATA[$up_result]]></UPCOMING_COUNT>";
$strXML .= "<COUNT_ALL_TYPE><![CDATA[$count_all_bdy]]></COUNT_ALL_TYPE>";
$strXML .= "<PAGE_NAME><![CDATA[" . $_SERVER['SCRIPT_URI'] . "]]></PAGE_NAME>";
$strXML .= "<CAT_PATH><![CDATA[" . $_REQUEST['cat_path'] . "]]></CAT_PATH>";
$strXML .= "<SELECTED_CATEGORY_NAME><![CDATA[" . $_REQUEST['category_name'] . "]]></SELECTED_CATEGORY_NAME>";
$strXML .= "<SEO_CARFINDER_COMPARE_URL><![CDATA[" . WEB_URL . $_REQUEST['cat_path'] . SEO_COMPARE_URL . "]]></SEO_CARFINDER_COMPARE_URL>";
$strXML .= "<SEO_CAR_FINDER><![CDATA[" . SEO_CAR_FINDER . "]]></SEO_CAR_FINDER>";
$strXML .= "<SEO_JS><![CDATA[$seo_js]]></SEO_JS>";
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<SUB_TITLE><![CDATA[$sub_title]]></SUB_TITLE>";
$strXML .= "<BREAD_CRUMB><![CDATA[$new_breadcrumb]]></BREAD_CRUMB>";
$strXML .= "<SEO_DESC><![CDATA[$seo_desc]]></SEO_DESC>";
$strXML .= "<SEO_TAGS><![CDATA[$seo_tags]]></SEO_TAGS>";
$strXML .= "<SEO_URL><![CDATA[$seo_url]]></SEO_URL>";
$strXML .= "<STARTLIMIT><![CDATA[$offset]]></STARTLIMIT>";
$strXML .= "<PAGE_OFFSET><![CDATA[" . OFFSET . "]]></PAGE_OFFSET>";
$strXML .= "<CNT><![CDATA[$numpages]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<SELECTED_BRAND_ID><![CDATA[$isBrandSelected]]></SELECTED_BRAND_ID>";
$strXML .= "<DEF_BRAND_ID><![CDATA[$selected_brand_id]]></DEF_BRAND_ID>";
$strXML .= "<SELECTEDTABID><![CDATA[$tab_id]]></SELECTEDTABID>";
$strXML .= "<SEO_PRICE_STR><![CDATA[" . implode("-", array($startprice, $endprice)) . "]]></SEO_PRICE_STR>";
$strXML .= "<START_PRICE><![CDATA[$startprice]]></START_PRICE>";
$strXML .= "<END_PRICE><![CDATA[$endprice]]></END_PRICE>";

$strXML .= "<START_PRICE_PARAM><![CDATA[$min_conv_price]]></START_PRICE_PARAM>";
$strXML .= "<END_PRICE_PARAM><![CDATA[$mx_conv_price]]></END_PRICE_PARAM>";
$strXML .= $config_details;
$strXML .= $sBrandDataDetXML;
$strXML .= $bodystylexmlallurl;
$strXML .="<CURR_CITY><![CDATA[" . $curr_city_name . "]]></CURR_CITY>";
$strXML .= "<PAGER><![CDATA[$pageNavStr]]></PAGER>";
$strXML .= "<DEFAULT_SEARCH><![CDATA[$default_search]]></DEFAULT_SEARCH>";
$strXML .= $bodystylexml;
$strXML .= "<SELECTED_BODY_STYLE><![CDATA[$Selectedbodystyle]]></SELECTED_BODY_STYLE>";
$strXML .= "<BODY_STYLE_ID><![CDATA[$feature_id]]></BODY_STYLE_ID>";
$strXML .= "<SELECTED_BODY_STYLE_NAME><![CDATA[$Selectedbodystylename]]></SELECTED_BODY_STYLE_NAME>";
$strXML .= "<PRODUCT_COUNT><![CDATA[$prod_cnt]]></PRODUCT_COUNT>";
$strXML .="<CHECKLOCATION><![CDATA[" . $_COOKIE['changenloc_brand'] . "]]></CHECKLOCATION>";
$strXML .="<SEO_CAR_BRANDS><![CDATA[" . SEO_CAR_BRANDS . "]]></SEO_CAR_BRANDS>";
$strXML .= "<SELECT_ALL_BODY_STYLE><![CDATA[$select_all_body_style]]></SELECT_ALL_BODY_STYLE>";
$strXML .= $sCityDetXml;
$strXML .= $strXMLCache;
$strXML .= $sortproductxml;
$strXML .= $xml;
$strXML .="<PAGING><![CDATA[$sPagingXml]]></PAGING>";
$strXML .= "<PAGE><![CDATA[$page]]></PAGE>";
$strXML .= "<OC_CARBRANDS_TOP_960X50><![CDATA[OC_CarBrands_Top_960x50]]></OC_CARBRANDS_TOP_960X50>";
$strXML .= "<OC_CARBRANDS_RIGHT_TOP_300X250><![CDATA[OC_CarBrands_Right_Top_300x250]]></OC_CARBRANDS_RIGHT_TOP_300X250>";
$strXML .= "<OC_CARBRANDS_RIGHT_MIDDLE_300X110><![CDATA[OC_CarBrands_Right_Middle_300x110]]></OC_CARBRANDS_RIGHT_MIDDLE_300X110>";
$strXML .= "<OC_CARBRANDS_MIDDLE_LHS_300X110_1><![CDATA[OC_CarBrands_Middle_LHS_300x110_1]]></OC_CARBRANDS_MIDDLE_LHS_300X110_1>";
$strXML .= "<OC_CARBRANDS_MIDDLE_RHS_300X110_2><![CDATA[OC_CarBrands_Middle_RHS_300x110_2]]></OC_CARBRANDS_MIDDLE_RHS_300X110_2>";
$strXML .= "<OC_CARBRANDS_RIGHT_BOTTOM_300X250><![CDATA[OC_CarBrands_Right_Bottom_300x250]]></OC_CARBRANDS_RIGHT_BOTTOM_300X250>";
$strXML .= "<SORTPRODUCTBY><![CDATA[$sortproductBY]]></SORTPRODUCTBY>";
$strXML .= "<POPAD><![CDATA[$popad]]></POPAD>";
$strXML .= "<CARPRICE><![CDATA[$endprice]]></CARPRICE>";
$strXML .= "<BRANDID><![CDATA[$selected_brand_id]]></BRANDID>";
$strXML .= "<BRAND_ID><![CDATA[$selected_brand_id]]></BRAND_ID>";
$strXML .= "<SELECTED_BRAND_NAME><![CDATA[$selected_brand_name]]></SELECTED_BRAND_NAME>";
$strXML .= "<MODEL_BRAND_NAME><![CDATA[$selected_brand_name]]></MODEL_BRAND_NAME>";
$strXML .= "<PAGE_URL><![CDATA[$request_uri]]></PAGE_URL>";
$strXML .= "<EMI_CALCULATOR_URL><![CDATA[$brand_emi_calculator_url]]></EMI_CALCULATOR_URL>";
$strXML .= "<PERPAGE>" . PERPAGE . "</PERPAGE>";
$strXML .= "<OC_RIGHT_BOTTOM_300X250><![CDATA[OC_Right_Bottom_300x250]]></OC_RIGHT_BOTTOM_300X250>";
$strXML .= $sDealerListXML . $sModelExpertReviewLinks;
$strXML .= "<BRAND_NAME><![CDATA[$brand_name]]></BRAND_NAME>";

$strXML .= "</XML>";
#echo "T5->".date('l jS \of F Y h:i:s A')."<br>";
setcookie("sel_loc", $curr_city_name, time() + 3600, '/', $domain); //used to change location fiden.
//setcookie ("cookie_city_id",$sCityId,time()+3600,'/',$domain);
//$html = render_html(BASEPATH.'xsl/brand_page.xsl',$strXML,$_REQUEST['debug']);
//header('Content-type: text/xml');echo $strXML;exit;
if ($_GET['debug'] == 1) {
    header('Content-type: text/xml');
    echo $strXML;
    exit;
}

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;
$xslt->registerPHPFunctions();
$xslt->registerPHPFunctions();
if ($_GET['debug'] == 11) {
    $xsl = DOMDocument::load('xsl/brand_page1.xsl');
} else {
    $xsl = DOMDocument::load('xsl/brand_page.xsl');
}

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
