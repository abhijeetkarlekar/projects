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

    $result = $product->arrGetTrendingProductDetails("", "", $category_id, "", "", $startlimit, $limitcnt);
    //echo "<pre>";
    //print_r($result);
    $cnt = sizeof($result);
    $component_xml .= "<TRENDING_NOW>";
    $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
    if ($cnt > 0) {
        for ($i = 0; $i < $cnt; $i++) {

            // seo url
            unset($seoUrlArr);
            $seoUrlArr[] = SEO_WEB_URL;


            $status = $result[$i]['status'];
            $categoryid = $result[$i]['category_id'];
            if (!empty($categoryid)) {
                $category_result = $category->arrGetCategoryDetails($categoryid);
            }
	    $cat_path = $category_result[0]['seo_path'];
            $seoUrlArr[] = $cat_path;
            $brand_id = $result[$i]['brand_id'];
            if (!empty($brand_id)) {
                $brand_result = $brand->arrGetBrandDetails($brand_id);
                $brand_name = $brand_result[0]['brand_name'];
                $seoUrlArr[] = $brand_result[0]['seo_path']; // seo url
            }
            $result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name, ENT_QUOTES) : 'Nil';
//            $model_id = $result[$i]['product_info_id'];
//            if (!empty($model_id)) {
//                $product_result = $product->arrGetProductNameInfo($model_id);
//                $product_name1 = $product_result[0][product_info_name];
//            }
            $product_id = $result[$i]['product_id'];
            $product_names = array();
            if (!empty($product_id)) {
                $product_result = $product->arrGetProductDetails($product_id, $category_id);
                $product_names[] = $product_result[0]['product_name'];
                $product_names[] = $product_result[0]['variant'];
                $product_name = implode(" ", $product_names);
            }
            $model_name = $product_result[0]['product_name'];
            if (!empty($model_name)) {
                $prod_result = $product->arrGetProductNameInfo("", $category_id, $brand_id, $model_name);
                $seoUrlArr[] = trim($prod_result[0]['seo_path']); // seo url
            }
            if ($product_result[0]['variant'] != '') {
                $seoUrlArr[] = trim($product_result[0]['seo_path']); // seo url
            }

            $result[$i]['product_name'] = $product_name ? html_entity_decode($product_name, ENT_QUOTES) : 'Nil';
            $result[$i]['product_status'] = ($status == 1) ? 'Active' : 'InActive';
            $category_name = $category_result[0]['category_name'];
            $result[$i]['category_name'] = html_entity_decode($category_name, ENT_QUOTES);
            $result[$i]['create_date'] = date('d-m-Y', strtotime($result[$i]['create_date']));
            $result[$i]['link'] = implode('/', $seoUrlArr);
            
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);


            $component_xml .= "<TRENDING_NOW_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</TRENDING_NOW_DATA>";
        }
    }
    $component_xml .= "</TRENDING_NOW>";

    //$xml = "<XML>";
    //$xml .= $component_xml;
    //$xml .= "</XML>";
    //header('Content-type: text/xml');
    //echo $xml;
    //exit;
}
?>
