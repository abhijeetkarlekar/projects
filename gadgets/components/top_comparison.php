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
            $category_result = $category->arrGetCategoryDetails($category_id);
            $cat_path = $category_result[0]['seo_path'];
    $result = $product->arrGetTopCompareSetDetails("", "", $category_id, "1", "", $startlimit, $limitcnt, "");
    //echo "<pre>";
    //print_r($result);
    //die;
    $cnt = sizeof($result);
    if ($cnt > 0) {

        $component_xml .= "<TOP_COMPARISION>";
        $component_xml .= "<TOTAL_COUNT><![CDATA[$total]]></TOTAL_COUNT>";
        $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";

        // initialise json array
        $arrJson = array();
        for ($i = 0; $i < $cnt; $i++) {

            // seo url
            unset($seoUrlArr);
            unset($compareUrlArr);
            $seoUrlArr[] = SEO_WEB_URL;
            
            $seoUrlArr[] = $cat_path;

            $product_ids = array();
            $result[$i]['top_compare_id'] = $result[$i]['top_compare_id'];
            $oncars_compare_id = "";
            $oncars_compare_id = $result[$i]['oncars_compare_id'];
            if (!empty($oncars_compare_id) && $oncars_compare_id != '0') {
                unset($compare_set_result);
                $compare_set_result = $product->arrGetProductCompareCompetitorDetails($oncars_compare_id, "", "", $category_ids, "", $startlimit, $cnt);
                $product_ids[] = $compare_set_result[0]["product_id"];
                $product_ids[] = $compare_set_result[0]["product_ids"];
                $productName1 = "";
                if (!empty($product_ids) && $product_ids != 0) {
                    $component_inner_xml = "<PRODUCTS>";
                    $productidsarr = $product_ids;
                    for ($j = 0; $j < sizeof($productidsarr); $j++) {
                        $component_inner_xml .= "<PRODUCT>";
                        $productid = $productidsarr[$j];
                        $prod_result = $product->arrGetProductDetails($productid, $category_id, "", "", "", "", "", "", "", "");
                        if (is_array($prod_result)) {
                            $sProductName = $prod_result[0]['product_name'];
                            $iProductId = $prod_result[0]['product_id'];
                            $iBrandId = $prod_result[0]['brand_id'];
                            unset($brand_result);
                            $brand_result = $brand->arrGetBrandDetails($iBrandId, $category_id);
                            $brand_name = $brand_result[0]['brand_name'];
                            $sVariant = $prod_result[0]['variant'];
                            $productName = $brand_name . " " . $sProductName . " " . $sVariant;
                            $compareUrlArr[] = trim($brand_result[0]['seo_path']); // seo url
                            if(!empty($sProductName)){
                                $product_result = $product->arrGetProductNameInfo("", $category_id, $iBrandId, $sProductName);
                                $compareUrlArr[] = trim($product_result[0]['seo_path']); // seo url
                            }
                            if($prod_result[0]['variant']!=''){
                                $compareUrlArr[] = trim($prod_result[0]['seo_path']); // seo url
                            }
                            $brand_name = "";
                            $productName1 .=$productName . " v/s ";
                            if (($j + 1) % 2 != 0) {
                                $compareUrlArr[] = 'Vs';
                            }
                            $component_inner_xml .= "<PRODUCT_NAME>" . $productName . "</PRODUCT_NAME>";
                            $image_path = $prod_result[0]['image_path'];
                            if (!empty($image_path)) {
//$image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize);
                                $image_path = resizeImagePath($image_path, "87X65", $aModuleImageResize);
                                $image_path = CENTRAL_IMAGE_URL . str_replace(array(CENTRAL_IMAGE_URL), "", $image_path);
                            }
                            $component_inner_xml .= "<IMAGE_PATH>" . $image_path . "</IMAGE_PATH>";
                        }
                        $component_inner_xml .= "</PRODUCT>";
                    }
                    $product_name1 = substr($productName1, 0, -5);
                    $component_inner_xml .= "</PRODUCTS>";
                }
            } else {
                $product_name1 = "";
            }
            $result[$i]['title'] = $product_name1 ? html_entity_decode($product_name1, ENT_QUOTES, 'UTF-8') : '';
            $result[$i]['category_id'] = $result[$i]['category_id'];
            $result[$i]['status'] = $result[$i]['status'];
            $result[$i]['ordering'] = $result[$i]['ordering'];
            $status = $result[$i]['status'];
            $result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
            $result[$i]['create_date'] = date('d-m-Y', strtotime($result[$i]['create_date']));
            $result[$i]['update_date'] = date('d-m-Y', strtotime($result[$i]['update_date']));
            $result[$i]['link'] = implode('/', $seoUrlArr) . '/compare/' . implode('-', $compareUrlArr); // seo url

            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $component_xml .= " <TOP_COMPARISION_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= $component_inner_xml;
            $component_xml .= "</TOP_COMPARISION_DATA>";
        }

        $component_xml .= "</TOP_COMPARISION>";
    }
//    header('Content-type: text/xml');
//    echo $component_xml;
//    exit;
}
?>
