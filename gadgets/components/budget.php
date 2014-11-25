<?php

require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH.'reviews.class.php');
$dbconn = new DbConn;
$product = new ProductManagement;
$category = new CategoryManagement;
$brand = new BrandManagement;
$oReview  = new reviews;

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
    $result = $product->arrGetBudgetProductDetails("", "", $category_id, "", "", $startlimit, $limitcnt);
    //echo "<pre>";    print_r($result);
    $cnt = sizeof($result);
    $component_xml .= "<BUDGET>";
    $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
    if ($cnt > 0) {
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
            $curr_brand_name  = "";
            $brand_id = $result[$i]['brand_id'];
            if (!empty($brand_id)) {
                $brand_result = $brand->arrGetBrandDetails($brand_id);
                $brand_name = $brand_result[0]['brand_name'];
                $curr_brand_name = $brand_result[0]['brand_name'];
                $seoUrlArr[] = $brand_result[0]['seo_path']; // seo url
            }
            $result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name, ENT_QUOTES) : 'Nil';
            $model_id = $result[$i]['product_info_id'];
            $curr_model_name = "";
            if (!empty($model_id)) {
                $product_result = $product->arrGetProductNameInfo($model_id);
                $product_name1 = $product_result[0][product_info_name];
                $curr_model_name = $product_result[0][product_info_name];
                $seoUrlArr[] = $product_result[0]['seo_path']; // seo url
            }
            $product_id = $result[$i]['product_id'];
            $product_names = array();
            if (!empty($product_id)) {
                $product_result = $product->arrGetProductDetails($product_id, $category_id);
                $variant_name = $product_result[0]['variant'];
                if(!empty($variant_name)){
                    $seoUrlArr[] = $product_result[0]['seo_path']; // seo url
                }
                $product_names[] = $product_result[0]['product_name'];
                $curr_model_name = $product_result[0]['product_name'];
                $product_names[] = $product_result[0]['variant'];
                $product_name = implode(" ", $product_names);
                $image_path = $product_result[0]['image_path'];
                if (!empty($image_path)) {
//                    $image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize);
                    $image_path = resizeImagePath($image_path, "114X152", $aModuleImageResize);
                    $image_path = CENTRAL_IMAGE_URL . str_replace(array(CENTRAL_IMAGE_URL), "", $image_path);
                }
                $result[$i]['image_path'] = $image_path;
                $result[$i]['price'] = $product_result[0]['variant_value'];
            }
            $result[$i]['product_name'] = $product_name ? html_entity_decode($product_name, ENT_QUOTES) : 'Nil';
            $result[$i]['product_status'] = ($status == 1) ? 'Active' : 'InActive';
            $category_name = $category_result[0]['category_name'];
            $result[$i]['category_name'] = html_entity_decode($category_name, ENT_QUOTES);
            $result[$i]['create_date'] = date('d-m-Y', strtotime($result[$i]['create_date']));
            $result[$i]['link'] = implode('/', $seoUrlArr); // seo url
            //echo " BRAND  --- $curr_brand_name ,==== MODEL --  $curr_model_name <br>";
            $expert_review_param =  implode(" ", array($curr_brand_name ,$curr_model_name));
            //echo EXPERT_REVIEW_API.rawurlencode(strtolower($expert_review_param));
            if($expert_review_param!=''){
                $expert_rating = $oReview->getBgrExpertReviews($expert_review_param,1);
            }
            $result[$i]['expert_rating'] = $expert_rating;
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $component_xml .= "<BUDGET_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</BUDGET_DATA>";
        }
    }
    $component_xml .= "</BUDGET>";
    $component_xml .= "<CAT_PATH><![CDATA[$cat_path]]></CAT_PATH>";
}
?>
