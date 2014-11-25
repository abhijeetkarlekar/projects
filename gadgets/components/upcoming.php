<?php

$callType = $_REQUEST['callType'] ? $_REQUEST['callType'] : 'internal'; // response type: xml, json (default: xml)
if ($callType = 'external') {

    require_once(dirname(__FILE__) . './../include/config.php'); // uncomment when run direct php script
}
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH . 'feature.class.php');

$dbconn = new DbConn;
$product = new ProductManagement;
$category = new CategoryManagement;
$brand = new BrandManagement;

$category_id = $component_params['category_id'] ? $component_params['category_id'] : '1';
$brand_id = $component_params['brand_id'];
$brand_name = $component_params['brand_name'];
$model_id = $component_params['model_id'];
$model_name = $component_params['model_name'];
$variant_id = $component_params['variant_id'];
$variant_name = $component_params['variant_name'];
$startlimit = $component_params['offset'];
$limitcnt = $component_params['count'];
$aModuleImageResize = $component_params['imageResize'];

if (!empty($category_id)) {
	$category_result = $category->arrGetCategoryDetails($category_id);
	$cat_path = $category_result[0]['seo_path'];
    $result = $product->arrSearchUpComingProductDetails("", "", $brand_id, "", "", "", $category_id, "", '1', $startlimit, $limitcnt, "ORDER BY start_date ASC");
//    echo "<pre>";    print_r($result); // die("IN");
    $cnt = sizeof($result);
    $component_xml .= "<UPCOMING>";
    $component_xml .= "<COUNT>$cnt</COUNT>";
    if ($cnt > 0) {
        for ($i = 0; $i < $cnt; $i++) {

            // seo url
            unset($seoUrlArr);
            $seoUrlArr[] = SEO_WEB_URL;
            $seoUrlArr[] = $cat_path;

            $upcoming_product_id = $result[$i]['upcoming_product_id'];
            $product_name_id = $result[$i]['product_name_id'];
            $feature_id = $result[$i]['feature_id'];
            $min_expected_price = $result[$i]['min_expected_price'];
            $min_expected_price_unit = $result[$i]['min_expected_price_unit'];
            $max_expected_price = $result[$i]['max_expected_price'];
            $max_expected_price_unit = $result[$i]['max_expected_price_unit'];

            $amin_expected_price = explode(".", $min_expected_price);
            if ($amin_expected_price[1] == '00') {
                $min_expected_price = round($min_expected_price);
            }
            $amax_expected_price = explode(".", $max_expected_price);
            if ($amax_expected_price[1] == '00') {
                $max_expected_price = round($max_expected_price);
            }
            if ($min_expected_price_unit == "100000") {
                $min_price_unit = "Lakh";
            } elseif ($min_expected_price_unit == "10000000") {
                $min_price_unit = "Crore";
            }
            if ($max_expected_price_unit == "100000") {
                $max_price_unit = "Lakh";
            } elseif ($max_expected_price_unit == "10000000") {
                $max_price_unit = "Crore";
            }
            if ($min_expected_price_unit == $max_expected_price_unit) {
                $expected_price = $min_expected_price . "-" . $max_expected_price . " " . $min_price_unit;
            } else {
                $expected_price = $min_expected_price . " " . $min_price_unit . "-" . $max_expected_price . " " . $max_price_unit;
            }
            if (($min_expected_price == '') && ($max_expected_price == '')) {
                $expected_price = "";
            }
            $result[$i]['expected_price'] = $expected_price;
            //$expected_price = $result[$i]['expected_price'] ? priceFormat($result[$i]['expected_price']) : "";
            $expected_price = $result[$i]['expected_price'];
            $result[$i]['expected_price'] = $expected_price;
            $expected_date_text = $result[$i]['expected_date_text'];
//        echo "<br/> product_name_id - " . $product_name_id;
            if (!empty($product_name_id)) {
                $productNameInfo = $product->arrGetProductNameInfo($product_name_id, $category_id, "", "", 1, "", "", "", "", "", "", "", "1");
//            echo "<pre>"; print_r($productNameInfo); die("arrGetProductNameInfo");
                $model_name = $productNameInfo[0]['product_info_name'];
                $brand_id = $productNameInfo[0]['brand_id'];
                if (!empty($brand_id)) {
                    $brand_result = $brand->arrGetBrandDetails($brand_id, "", "1", "", "", "", "", "", "");
                    $brand_name = $brand_result[0]['brand_name'];
                    $seoUrlArr[] = $brand_result[0]['seo_path']; // seo url
                }
                $product_name = $brand_name . " " . $model_name;

                $aproduct_dispname[] = $brand_name;
                $aproduct_dispname[] = $model_name;
                $result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
                unset($aproduct_dispname);
                $image_id = $productNameInfo[0]["img_media_id"];
                $image_path = $productNameInfo[0]["image_path"];
                $seoUrlArr[] = $productNameInfo[0]['seo_path']; // seo url
            }
            unset($aproduct_dispname);
            $result[$i]['alt_product_name'] = $product_name;
            $result[$i]["product_name"] = getTruncatedString($product_name, 26);
            $result[$i]["alt_product_name"] = str_replace('-', ' ', $product_name);
            $result[$i]["product_name"] = str_replace('-', ' ', $result[$i]["product_name"]);
            if (!empty($image_path)) {
                $image_path = resizeImagePath($image_path, "145X193", $aModuleImageResize, $image_id);
            }
            $result[$i]["image_path"] = $image_path ? CENTRAL_IMAGE_URL . $image_path : '';
//            unset($ModelvariantnameSeoArr);
//            $seo_url = "";
//            $ModelvariantnameSeoArr[] = SEO_WEB_URL;
//            $ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
//            $ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
//            $seo_url = implode("/", $ModelvariantnameSeoArr);
            $result[$i]['link'] = implode('/', $seoUrlArr);
            
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $component_xml .= "<UPCOMING_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</UPCOMING_DATA>";
        }
    }
    $component_xml .= "</UPCOMING>";
    $component_xml .= "<CAT_PATH><![CDATA[$cat_path]]></CAT_PATH>";
}

//$xml = "<XML>";
//$xml .= $component_xml;
//$xml .= "</XML>";
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
