<?php

require_once(CLASSPATH . 'DbConn.php');
//require_once(CLASSPATH . 'product.class.php');
//require_once(CLASSPATH . 'category.class.php');
//require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH . 'wallpaper.class.php');

$dbconn = new DbConn;
//$product = new ProductManagement;
//$category = new CategoryManagement;
//$brand = new BrandManagement;
$wallpapers = new Wallpapers;

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
/* Gallery Code */
if (!empty($category_id) && !empty($model_id)) {

    $result = $wallpapers->arrSlideShowDetails("", "", "", $model_id, "", $category_id, $brand_id, 1, "", "", "", "", "0", "1");

    //print_r($result); die("gallery");
    $total_cnt = sizeof($result);
    for ($i = 0; $i < $total_cnt; $i++) {
        $video_img_path = $result[$i]["video_img_path"];
        if (!empty($video_img_path)) {
            $video_img_path = resizeImagePath($video_img_path, "251X188", $aModuleImageResize, $video_img_id);
            $mid_video_img_path = resizeImagePath($video_img_path, "251X188", $aModuleImageResize, $video_img_id);
            $component_xml_result[$i]['video_img_path'] = $mid_video_img_path ? CENTRAL_IMAGE_URL . $mid_video_img_path : IMAGE_URL . 'no_image_251X188.gif';
            $thumb_video_img_path = resizeImagePath($video_img_path, "73X55", $aModuleImageResize, $video_img_id);
            $component_xml_result[$i]['thumb_video_img_path'] = $thumb_video_img_path ? CENTRAL_IMAGE_URL . $thumb_video_img_path : IMAGE_URL . 'no_image_73X55.gif';
            $component_xml_result[$i]['image_title'] = $result[$i]["image_title"];
            $component_xml_result[$i]['slideshow_title'] = $result[$i]["slideshow_title"];
        }
    }

    $component_xml = '<GALLERY>';
    $component_xml .= '<TOTAL>' . $total_cnt . '</TOTAL>';
//    $component_xml .= "<EXTERIOR_COUNT>" . $ephoto_cnt . "</EXTERIOR_COUNT>";
//    $component_xml .= "<INTERIOR_COUNT>" . $iphoto_cnt . "</INTERIOR_COUNT>";
    foreach ($component_xml_result as $arr_gallery_details) {
        $arr_gallery_details = array_change_key_case($arr_gallery_details, CASE_UPPER);
        $component_xml .= "<GALLERY_DETAILS>";
        foreach ($arr_gallery_details as $key => $component_xml_details) {
            $component_xml .= "<$key>" . $component_xml_details . "</$key>";
        }
        $component_xml .= "</GALLERY_DETAILS>";
    }
    $component_xml .= '</GALLERY>';
}
/*$xml = "<XML>";
$xml .= $component_xml;
$xml .= "</XML>";*/
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
