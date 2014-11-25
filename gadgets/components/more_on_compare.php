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

$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : '1';
if (!empty($category_id)) {
        $category_result = $category->arrGetCategoryDetails($category_id);
}
$cat_path = $category_result[0]['seo_path'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];

if (!empty($category_id)) {

    $component_xml .= $product->topSearchComparisons($keywords, "", "model", "");
}
header('Content-type: text/xml');
echo $component_xml;
exit;
?>
