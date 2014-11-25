<?php

require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');

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
}
$cat_path = $category_result[0]['seo_path'];
if (!empty($category_id)) {

    $result = $brand->arrGetPopularBrandDetails("", "", "", $category_id, "1", $startlimit, $limitcnt);
    $cnt = sizeof($result);
//    echo "<pre>";
//    print_r($result);
//    die;
    $component_xml .= "<POPULAR_BRANDS>";
    $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
    if ($cnt > 0) {
        // seo url
        unset($seoUrlArr);
        $seoUrlArr[] = SEO_WEB_URL;
        $seoUrlArr[] = $cat_path;
        $seoUrlArr[] = 'brands';
        $component_xml .= "<ALL_BRANDS_URL><![CDATA[".implode('/', $seoUrlArr)."]]></ALL_BRANDS_URL>";

        for ($i = 0; $i < $cnt; $i++) {

            // seo url
            unset($seoUrlArr);
            $seoUrlArr[] = SEO_WEB_URL;
            $seoUrlArr[] = $cat_path;

            $status = $result[$i]['status'];
            $categoryid = $result[$i]['category_id'];
            if (!empty($categoryid)) {
                $category_result = $category->arrGetCategoryDetails($categoryid);
            }
            $category_name = $category_result[0]['category_name'];
            $result[$i]['category_name'] = html_entity_decode($category_name, ENT_QUOTES);
            $result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
            $result[$i]['create_date'] = date('d-m-Y', strtotime($result[$i]['create_date']));

            $brand_id = $result[$i]['brand_id'];
            if (!empty($brand_id)) {
                $brand_result = $brand->arrGetBrandDetails($brand_id, $category_id);
            }
//        echo "<pre>";
//        print_r($brand_result);
//        die;
            $brand_name = $brand_result[0]['brand_name'];
            $result[$i]['brand_name'] = html_entity_decode($brand_name, ENT_QUOTES);
            $seoUrlArr[] = $brand_result[0]['seo_path'];
            $result[$i]['link'] = implode('/', $seoUrlArr);
            $brand_name = "";

            $result[$i]['brand_image'] = $brand_result[0]['brand_image'];

            unset($product_result);
            $popular_model_id = $result[$i]['popular_model_id'];
            if (!empty($popular_model_id)) {
                $product_result = $product->arrGetProductNameInfo($popular_model_id, $category_id, $brand_id);
            }
            $popular_model_name = $product_result[0]['product_info_name'];
            $result[$i]['popular_model_name'] = html_entity_decode($popular_model_name, ENT_QUOTES);
            $popular_model_name = "";

            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $component_xml .= "<POPULAR_BRANDS_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</POPULAR_BRANDS_DATA>";
        }
    }
    $component_xml .= "</POPULAR_BRANDS>";
}
?>
