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

$skipsamebrand = $component_params['skipsamebrand'];
$skipsamemodel = $component_params['skipsamemodel'];
$skipsamevariant = $component_params['skipsamevariant'];

if (!empty($category_id)) {

    // arrGetProdCompetitorDetailsarrGetProdCompetitorDetails($top_competitor_ids="",$product_ids="",$product_info_ids="",$brand_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$skipsamevariant="0",$skipsamemodel="0",$skipsamebrand="0"){}
    //$result = $product->arrGetProdCompetitorDetails("", $variant_id, $model_id, $brand_id, $category_id, "1", $startlimit, $limitcnt, "0", "0", "0");
    $result = $product->arrGetProdCompetitorDetails("", $variant_id, $model_id, $brand_id, $category_id, "1", 0, 4, $skipsamevariant, $skipsamemodel, $skipsamebrand);
//    echo "<pre>"; print_r($result); die;
    $cnt = count($result);
    if ($cnt > 0) {
        $component_xml .= "<SIMILAR>";
        $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        $compareIdsArr = Array();
        for ($i = 0; $i < $cnt; $i++) {
            $brand_id = $result[$i]['brand_ids'];
            $product_name_id = $result[$i]['product_info_ids'];
            $product_id = $result[$i]['product_ids'];
            #array_push($compareIdsArr,$product_id);	
            $position = $result[$i]['position'];
            $status = $result[$i]['status'];
            $categoryid = $result[$i]['category_id'];
            if (!empty($brand_id)) {
                $brand_result = $brand->arrGetBrandDetails($brand_id);
                $brand_name = $brand_result[0]['brand_name'];
            }
            if (!empty($categoryid)) {
                $category_result = $category->arrGetCategoryDetails($categoryid);
            }
	    $cat_path = $category_result[0]['seo_path'];
            unset($model_image_path);
            unset($image_path);
            if (!empty($product_name_id)) {
                $productNameInfo = $product->arrGetProductNameInfo($product_name_id, $category_id, "", "", 1, "", "");
                $model_name = $productNameInfo[0]['product_info_name'];
                $image_id = $productNameInfo[0]["img_media_id"];
                $model_image_path = $productNameInfo[0]["image_path"];
            }
            unset($variantUrlYear);
            if (!empty($product_id)) {
                # $aProductDetail = $product->arrGetProductDetails($product_id,$category_id,"","1","","1","","","1","","","","","","","1");
                $aProductDetail = $product->arrGetProductDetails($product_id, $category_id, "", '1', "", "", "1", "", "", "1", "", "", "", "", '', "1");
                $variant = $aProductDetail[0]['variant'];
                unset($variantUrlYear);
                $variantUrlYear = buildYear($aProductDetail[0]['arrival_date'], $aProductDetail[0]['discontinue_date']);
                $image_id = $aProductDetail[0]["img_media_id"];
                $image_path = $aProductDetail[0]["image_path"];
                $price = $aProductDetail[0]['variant_value'];
            }

            $image_path = !empty($model_image_path) ? $model_image_path : $image_path;
            if ($type == 'model') {
                unset($variantUrlYear);
                $result[$i]["alt_product_name"] = $brand_name . "-" . $model_name;
                $result[$i]["product_name"] = getTruncatedString($brand_name . "-" . $model_name, 26);
                $aproduct_dispname[] = $brand_name;
                $aproduct_dispname[] = $model_name;
                $result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
                unset($aproduct_dispname);
                unset($prores);
                $prores = $product->arrGetProductDetails("", "", $brand_id, '1', "", "", "1", "", "", "1", "", $model_name, "", "", "", "1");

                $prores_cnt = sizeof($prores);
                unset($aPriceRange);
                $arr_cnt = 0;
                for ($j = 0; $j < $prores_cnt; $j++) {
                    $sExShowRoomPrice = $prores[$j]['variant_value'];
                    $aPriceRange[$arr_cnt]['price'] = $sExShowRoomPrice;
                    $aPriceRange[$arr_cnt]['product_id'] = $prores[$j]['product_id'];
                    $aPriceRange[$arr_cnt]['variant'] = $prores[$j]['variant'];
                    $aPriceRange[$arr_cnt]['arrival_date'] = $prores[$j]['arrival_date'];
                    $aPriceRange[$arr_cnt]['discontinue_date'] = $prores[$j]['discontinue_date'];
                    $arr_cnt++;
                }

                $sortArray = array();
                foreach ($aPriceRange as $price) {
                    foreach ($price as $key => $value) {
                        if (!isset($sortArray[$key])) {
                            $sortArray[$key] = array();
                        }
                        $sortArray[$key][] = $value;
                    }
                }
                $orderby = "price";
                array_multisort($sortArray[$orderby], SORT_ASC, $aPriceRange);
                #print_R($aPriceRange);die();
                $lowPrice = $aPriceRange[0]['price'];
                $variantUrlYear = buildYear($aPriceRange[0]['arrival_date'], $aPriceRange[0]['discontinue_date']);
                if (count($aPriceRange) > 1) {
                    $highPrice = $aPriceRange[count($aPriceRange) - 1]['price'];
                }
                $lowprice_product_id = $aPriceRange[0]['product_id'];
                $lowprice_variant_name = $aPriceRange[0]['variant'];

                $result[$i]['price'] = $lowPrice ? priceFormat($lowPrice) : '';
//                $comparename = constructUrl($brand_name) . '-' . constructUrl($model_name) . '-' . constructUrl($lowprice_variant_name);
//                $result[$i]['comparename'] = $comparename;
                unset($rangepArr);
                if (!empty($lowPrice)) {
                    $rangepArr[] = priceFormat($lowPrice);
                }
                if (!empty($highPrice)) {
                    $rangepArr[] = priceFormat($highPrice);
                }

                $result[$i]['rangeprice'] = implode(' - ', $rangepArr);
                unset($ModelOnRoadPriceSeoArr);
                $on_road_price_seo_url = "";
                $ModelOnRoadPriceSeoArr[] = SEO_WEB_URL;
                $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($brand_name));
                $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($model_name));
                $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($lowprice_variant_name));
                if (!empty($variantUrlYear)) {
                    $ModelOnRoadPriceSeoArr[] = $variantUrlYear;
                }
                $ModelOnRoadPriceSeoArr[] = SEO_GET_ON_ROAD_PRICE;
                $on_road_price_seo_url = implode("/", $ModelOnRoadPriceSeoArr);
                $result[$i]['on_road_price_seo_url'] = $on_road_price_seo_url;
            } else {
                $result[$i]["alt_product_name"] = $brand_name . "-" . $model_name . "-" . $variant;
                if ($type == 'viewonroadprice') {
                    $result[$i]["product_name"] = getTruncatedString($brand_name . "-" . $model_name . "-" . $variant, 15);
                } else {
                    $result[$i]["product_name"] = getTruncatedString($brand_name . "-" . $model_name . "-" . $variant, 26);
                }
                $aproduct_dispname[] = $brand_name;
                $aproduct_dispname[] = $model_name;
                $aproduct_dispname[] = $variant;
                $result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
                unset($aproduct_dispname);
                $result[$i]['price'] = $price ? priceFormat($price) : '';
                unset($ModelOnRoadPriceSeoArr);
                $on_road_price_seo_url = "";
                $ModelOnRoadPriceSeoArr[] = SEO_WEB_URL;
                $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($brand_name));
                $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($model_name));
                $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($variant));
                if (!empty($variantUrlYear)) {
                    $ModelOnRoadPriceSeoArr[] = $variantUrlYear;
                }
//                $comparename = constructUrl($brand_name) . '-' . constructUrl($model_name) . '-' . constructUrl($variant);
//                if (!empty($variantUrlYear)) {
//                    $comparename = $comparename . '-' . $variantUrlYear;
//                }
//                $result[$i]['comparename'] = $comparename;
//                if ($type == 'viewonroadprice') {
//                    $ModelOnRoadPriceSeoArr[] = SEO_VIEW_ON_ROAD_PRICE;
//                } else {
//                    $ModelOnRoadPriceSeoArr[] = SEO_GET_ON_ROAD_PRICE;
//                }
//                $on_road_price_seo_url = implode("/", $ModelOnRoadPriceSeoArr);
//                $result[$i]['on_road_price_seo_url'] = $on_road_price_seo_url;
            }

//            array_push($compareIdsArr, $comparename);

            $image_path = str_replace(array(CENTRAL_IMAGE_URL, CENTRAL_MEDIA_URL), "", $image_path);

            if (!empty($image_path)) {
                //$smallimg = CENTRAL_IMAGE_URL.resizeImagePath($image_path,"87X65",$aModuleImageResize,$image_id);
                $smallimg = CENTRAL_IMAGE_URL . resizeImagePath($image_path, "73X55", $aModuleImageResize, $image_id);
                $image_path = CENTRAL_IMAGE_URL . resizeImagePath($image_path, "160X120", $aModuleImageResize, $image_id);
            } else {
                $image_path = IMAGE_URL . 'no-image.png';
            }

            $result[$i]["image_path"] = $image_path;
            $result[$i]["smallimg"] = !empty($smallimg) ? $smallimg : $image_path;
            if ($type == 'model') {
                unset($ModelvariantnameSeoArr);
                $seo_url = "";
                $ModelvariantnameSeoArr[] = SEO_WEB_URL;
                $ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
                $ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
                $seo_url = implode("/", $ModelvariantnameSeoArr);
                $alt_model_name = implode(' ', array($brand_name, $model_name));
                $result[$i]['alt_model_name'] = $alt_model_name;
                $result[$i]["model_name"] = getTruncatedString($alt_model_name, 26);
                $result[$i]['model_seo_url'] = $seo_url;
            } else {
                unset($variantnameSeoArr);
                $seo_url = "";
                $variantnameSeoArr[] = SEO_WEB_URL;
                $variantnameSeoArr[] = $cat_path;
                $variantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
                $variantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
                $variantnameSeoArr[] = seo_title_replace(constructUrl($variant));
                if (!empty($variantUrlYear)) {
                    $variantnameSeoArr[] = $variantUrlYear;
                }
                $seo_url = implode("/", $variantnameSeoArr);
                $result[$i]['seo_url'] = $seo_url;
            }
            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $component_xml .= " <SIMILAR_DATA>";
            foreach ($result[$i] as $k => $v) {
                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</SIMILAR_DATA>";
            /*
              foreach ($result[$i] as $k => $v) {
              if ($dataArr[$k]) {
              $jsonArr['results'][$i][$dataArr[$k]] = $v;
              $k = $dataArr[$k];
              }
              }
             *
             */
        }
        $component_xml .= "</SIMILAR>";
    }
}
//$xml = "<XML>";
//$xml .= $component_xml;
//$xml .= "</XML>";
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
