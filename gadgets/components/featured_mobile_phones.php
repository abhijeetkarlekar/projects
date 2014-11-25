<?php

require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH . 'feature.class.php');
require_once(CLASSPATH.'reviews.class.php');

$dbconn = new DbConn;
$product = new ProductManagement;
$category = new CategoryManagement;
$brand = new BrandManagement;
$feature = new FeatureManagement;
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
}
$cat_path = $category_result[0]['seo_path'];
if (!empty($category_id)) {
    $result = $product->arrGetProductFeaturedDetails("", "", $category_id, "", "", $startlimit, $limitcnt);
    $cnt = sizeof($result);
    if ($cnt > 0) {
        $component_xml .= "<FEATURED_MOBILE_PHONES>";
        $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        // initialise json array
        $arrJson = array();
        for ($i = 0; $i < $cnt; $i++) {
            // seo url
            unset($seoUrlArr);
            $seoUrlArr[] = SEO_WEB_URL;
            $status = $result[$i]['status'];
            $categoryid = $result[$i]['category_id'];
            if (!empty($categoryid)) {
                $category_result = $category->arrGetCategoryDetails($categoryid);
            }
            $seoUrlArr[] = $category_result[0]['seo_path'];
            $brand_id = $result[$i]['brand_id'];
            if (!empty($brand_id)) {
                $brand_result = $brand->arrGetBrandDetails($brand_id);
                $brand_name = $brand_result[0]['brand_name'];
                $seoUrlArr[] = trim($brand_result[0]['seo_path']); // seo url
            }
            $result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name, ENT_QUOTES) : 'Nil';
            //            $model_id = $product_result[0]['product_name'];

            //            if (!empty($model_id)) {
            //                $product_result = $product->arrGetProductNameInfo($model_id);
            //                $product_name1 = $product_result[0][product_info_name];
            //                $seoUrlArr[] = trim($product_result[0]['seo_path']); // seo url
            //            }
            $product_id = $result[$i]['product_id'];
            $product_names = array();
            if (!empty($product_id)) {
                $product_result = $product->arrGetProductDetails($product_id, $category_id);
                $product_names[] = $product_result[0]['product_name'];                
                $product_names[] = $product_result[0]['variant'];  
                $variant_name = $product_result[0]['variant'];              
                $product_name = implode(" ", $product_names);
                $image_path = $product_result[0]['image_path'];
                if (!empty($image_path)) {
                    //$image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize);
                    $image_path = resizeImagePath($image_path, "225X300", $aModuleImageResize);
                    $image_path = CENTRAL_IMAGE_URL . str_replace(array(CENTRAL_IMAGE_URL), "", $image_path);
                }
                $result[$i]['image_path'] = $image_path;
                $result[$i]['variant_value'] = $product_result[0]['variant_value'];
                //$seoUrlArr[] = trim($product_result[0]['seo_path']); // seo url
            }
            $model_name = $product_result[0]['product_name'];
            if (!empty($model_name)) {
                $prod_result = $product->arrGetProductNameInfo("", $category_id, $brand_id, $model_name);                
                $seoUrlArr[] = trim($prod_result[0]['seo_path']); // seo url
            }
            if(!empty($variant_name)){
                $seoUrlArr[] = trim($product_result[0]['seo_path']); // seo url
            }

            $result[$i]['product_name'] = $product_name ? html_entity_decode($product_name, ENT_QUOTES) : 'Nil';
            $result[$i] ['product_status'] = ($status == 1) ? 'Active' : 'InActive';
            $category_name = $category_result[0]['category_name'];
            $result[$i]['category_name'] = html_entity_decode($category_name, ENT_QUOTES);
            $result[$i]['create_date'] = date('d-m-Y', strtotime($result[$i]['create_date']));
            $result[$i]['spec_link'] = implode('/', $seoUrlArr);
             //echo " BRAND  --- $brand_name ,==== MODEL --  $product_name <br>";
            // mobile phone summary
            if (!empty($product_id)) {
                unset($arr_feature_specs);
                $summary = $feature->arrGetSummary($category_id, $product_id, "");
                // echo "<pre>";
                //print_r($summary);
                $feature_model_name = trim($product_name);
                $key = trim("$feature_model_name Features Specification");
                //echo "summary[$key]<br>";
                 $feature_specs = $summary[$key];
                //echo "SPEC---"; print_r($feature_specs);
                if (count($feature_specs) > 0) {
                    $result[$i]['os'] = $arr_feature_specs[] = $feature_specs['OS'][0];
                    $result[$i]['display'] = $arr_feature_specs[] = $feature_specs['Display'][0];
                    $result[$i]['processor'] = $arr_feature_specs[] = $feature_specs['Processor'][0];
                    $result[$i]['camera'] = $arr_feature_specs[] = $feature_specs['Camera'][0];
                }
                $result[$i]['feature_specs_count'] = count($arr_feature_specs);
            }
             //echo " BRAND  --- $brand_name ,==== MODEL --  $product_name <br>";
            $expert_review_param =  implode(" ", array($brand_name ,$product_name));
            //echo EXPERT_REVIEW_API.rawurlencode(strtolower($expert_review_param));
            if($expert_review_param!=''){
                 $expert_rating = $oReview->getBgrExpertReviews($expert_review_param,1);
            }
            //echo "RATING----".$expert_rating."<br>";
            $result[$i]['expert_rating'] = $expert_rating;
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            //print_r($result[$i]);
            $component_xml .= " <FEATURED_MOBILE_PHONES_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</FEATURED_MOBILE_PHONES_DATA>";
        }
        $component_xml .= "</FEATURED_MOBILE_PHONES>";
        //        header('Content-type: text/xml');
        //        echo $component_xml;
        //        exit;
    }
}
?>