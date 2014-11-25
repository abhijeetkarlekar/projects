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

$model_name = str_replace("-", " ", $component_params['model_name']);
$product_name = $component_params['brand_name'] . " " . $model_name;
if (!empty($category_id)) {
	$category_result = $category->arrGetCategoryDetails($category_id);
}
$cat_path = $category_result[0]['seo_path'];
//if (!empty($product_name) && !empty($model_name)) {

//$compare_url = $product->topSearchComparisons($product_name, $model_name, "model", "", TRUE);
$compare_url = SEO_WEB_URL .'/'. $cat_path .'/'. constructUrl($brand_name) ;
$moreon_result[] = array("URL" => $compare_url, "TITLE" => "Upcoming $brand_name Mobile Phones in India");
//}
//if (!empty($variant_id)) {
//    $moreon_result = $product->moreOnCar($category_id, $brand_id, $model_id, $variant_id);
#print_r($moreon_result); die("moreOnCar");
//    if (!empty($moreon_result)) {
//        $moreon_result = array_merge($moreon_result, (array) $compare_moreon_result);
$cnt = count($moreon_result);
$start_count = 0;
$component_xml .="<MORE_ON_BRAND>";
$component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for ($i = 0; $i < $cnt; $i++) {

    if ($start_count == $limitcnt) {
        break;
    }
    $component_xml .="<MORE_ON_BRAND_DATA>";
    $component_xml .="<MORE_ON_BRAND_DATALINK>" . $moreon_result[$i]['URL'] . "</MORE_ON_BRAND_DATALINK>";
    $component_xml .="<MORE_ON_BRAND_DATATITLE>" . $moreon_result[$i]['TITLE'] . "</MORE_ON_BRAND_DATATITLE>";
    $component_xml .="</MORE_ON_BRAND_DATA>";
    $start_count++;
}
$component_xml .="</MORE_ON_BRAND>";
//    }
//}
//$xml = "<XML>";
//$xml .= $component_xml;
//$xml .= "</XML>";
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
