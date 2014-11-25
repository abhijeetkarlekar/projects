<?php

$callType = $_REQUEST['callType'] ? $_REQUEST['callType'] : 'internal'; // response type: xml, json (default: xml)
if ($callType = 'external') {

    require_once(dirname(__FILE__) . './../include/config.php'); // uncomment when run direct php script
}
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');

$dbconn = new DbConn;
$product = new ProductManagement;
$category = new CategoryManagement;
$brand = new BrandManagement;

$category_id = $component_params['category_id'] ? $component_params['category_id'] : SITE_CATEGORY_ID;
$cat_path = $_REQUEST['cat_path'] ? $_REQUEST['cat_path'] : SITE_CATEGORY_PATH;
if(!empty($category_id)){
    $category_result = $category->arrGetCategoryDetails($category_id);
    $category_id = $category_result[0]['category_id'];
    $category_name = $category_result[0]['category_name'];
    $cat_path = $category_result[0]['seo_path'];
}

$brand_id = $component_params['brand_id'];
$brand_name = $component_params['brand_name'];
$model_id = $component_params['model_id'];
$model_name = $component_params['model_name'];
$variant_id = $component_params['variant_id'];
$variant_name = $component_params['variant_name'];
$startlimit = $component_params['offset'];
$limitcnt = $component_params['count'];
$aModuleImageResize = $component_params['imageResize'];
$limitcnt = $component_params['count'];
$model_name = str_replace("-", " ", $component_params['model_name']);

$params['category_id'] = $category_id;
$params['limit'] = $limitcnt;

$product_name = $component_params['brand_name'] . " " . $model_name;
//if (!empty($variant_id)) {
    $moreon_result = $product->arrGetUpcomingBodyStyleWithBrand($params);
    //print_r($moreon_result); die("moreOnCar");
    if (!empty($moreon_result)) {
        $cnt = count($moreon_result);
        $component_xml .="<MORE_ON_UPCMBYTYPESGADGET>";
            $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        for ($i=0; $i < $cnt ; $i++) { 
            $seo_path = constructUrl($moreon_result[$i]['feature_name']);
            $category_id = $moreon_result[$i]['category_id'];
            $category_result = $category->arrGetCategoryDetails($category_id);
            $cat_path = $category_result[0]['seo_path'];
            $feature_name = $moreon_result[$i]['feature_name'];
            $title = "Upcoming $feature_name Mobiles in India";
            $url = WEB_URL.$cat_path."/upcoming-mobiles/".$seo_path;
            $start_count = 0;
            $component_xml .="<MORE_ON_GADGET_DATA>";
            $component_xml .="<MORE_ON_GADGET_DATALINK>" . $url . "</MORE_ON_GADGET_DATALINK>";
            $component_xml .="<MORE_ON_GADGET_DATATITLE>" . $title . "</MORE_ON_GADGET_DATATITLE>";
            $component_xml .="</MORE_ON_GADGET_DATA>";
            //$start_count++;
            
        }
        $component_xml .="</MORE_ON_UPCMBYTYPESGADGET>";
    }
//}
/*$xml = "<XML>";
$xml .= $component_xml;
$xml .= "</XML>";
header('Content-type: text/xml');
echo $xml;
exit;*/
?>
