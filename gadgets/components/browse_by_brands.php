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

    $result = $brand->arrGetBrandDetails("", $category_id, "1", $startlimit, $limitcnt);
    //arrGetBrandDetails($brand_ids="",$category_id="",$status="1",$startlimit="",$count="",$brand_name="",$orderby="",$discontinue_flag="",$upcoming_brand="0")
    $cnt = sizeof($result);
    $component_xml .= "<BRANDS>";
    $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
    if ($cnt > 0) {
        for ($i = 0; $i < $cnt; $i++) {

            if ($brand_id == $result[$i]['brand_id']) {
                continue;
            }
            // seo url
            unset($seoUrlArr);
            $seoUrlArr[] = SEO_WEB_URL;
	    $categoryid = $result[$i]['category_id'];
	    if (!empty($categoryid)) {
                $category_result = $category->arrGetCategoryDetails($categoryid);
            }
            $cat_path = $category_result[0]['seo_path'];
            $seoUrlArr[] = $cat_path;

            $brand_name = $result[$i]['brand_name'];
            $result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name, ENT_QUOTES) : 'Nil';
//        $result[$i]['brand_url'] = SEO_WEB_URL .'/'. $category_path .'/'. constructUrl($brand_name) ;
            $seoUrlArr[] = $result[$i]['seo_path']; // seo url
            $result[$i]['link'] = implode('/', $seoUrlArr);
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $component_xml .= "<BRANDS_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</BRANDS_DATA>";
        }
    }
    $component_xml .= "</BRANDS>";
}
//$xml = "<XML>";
//$xml .= $component_xml;
//$xml .= "</XML>";
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
