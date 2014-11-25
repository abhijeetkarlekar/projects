<?php

require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH . 'product.class.php');
require_once(CLASSPATH . 'category.class.php');
require_once(CLASSPATH . 'brand.class.php');
require_once(CLASSPATH . 'pivot.class.php');

$dbconn = new DbConn;
$product = new ProductManagement;
$category = new CategoryManagement;
$brand = new BrandManagement;
$pivot = new PivotManagement;

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

$predefined_sub_group_id = $component_params['sub_group_id'];

$model_name = str_replace("-", " ", $model_name);
$product_name = $component_params['brand_name'] . " " . $model_name;
if (!empty($category_id)) {
	$category_result = $category->arrGetCategoryDetails($category_id);
}
$cat_path = $category_result[0]['seo_path'];
if (!empty($category_id)) {

    $result = $brand->arrGetBrandDetails("", $category_id, 1, $startlimit, $limitcnt);
//    echo "<pre>"; print_r($result);
    $cnt = sizeof($result);

    $component_xml .= "<BRAND_MASTER>";
    $component_xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
    if ($cnt > 0) {

        for ($i = 0; $i < $cnt; $i++) {

            $result[$i] = array_change_key_case($result[$i], CASE_UPPER);
            $component_xml .= "<BRAND_MASTER_DATA>";
            foreach ($result[$i] as $k => $v) {

                $component_xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $component_xml .= "</BRAND_MASTER_DATA>";
        }
    }
    $component_xml .= "</BRAND_MASTER>";

    unset($result);
    $result = $pivot->arrPivotSubGroupDetails("", $category_id, 1);
//    echo "<pre>";
//    print_r($result);

    $cnt = sizeof($result);
    for ($i = 0; $i < $cnt; $i++) {
        $plusminusimgstatus = 0;
        $status = $result[$i]['status'];
        $sub_group_id = $result[$i]['sub_group_id'];
        if ($sub_group_id != $predefined_sub_group_id) {

            continue;
        }
        $categoryid = $result[$i]['category_id'];
        $sub_group_name = $result[$i]['sub_group_name'];
        if (!empty($category_id)) {
            $category_name = $category_result[0]['category_name'];
            $result[$i]['category_name'] = html_entity_decode($category_name, ENT_QUOTES, 'UTF-8');
            $pivot_result = $pivot->arrGetPivotDetails("", $category_id, "", "1", $sub_group_id);
            $pivotCnt = sizeof($pivot_result);
            for ($j = 0; $j < $pivotCnt; $j++) {
                /*
                  $pivot_display_id = $pivot_result[$j]['pivot_display_id'];
                  if (!empty($pivot_display_id)) {
                  $pivot_display_type_result = $pivot->arrPivotDisplayDetails($pivot_display_id, "1");
                  $pivot_display_type = $pivot_display_type_result[0]['pivot_display_name'];
                  } else {
                  $pivot_display_type = "checkbox";
                  }
                 */
                $pivot_group = $pivot_result[$j]['pivot_group'];
                $main_pivot_group = $sub_group_name;
                $status = $pivot_result[$j]['status'];
//                $pivot_desc = $pivot_result[$j]['pivot_desc'];
//                if (!empty($pivot_desc)) {
//                    $pivot_result[$j]['pivot_desc'] = html_entity_decode($pivot_desc, ENT_QUOTES, 'UTF-8');
//                }
                $categoryid = $pivot_result[$j]['category_id'];
                $feature_id = $pivot_result[$j]['feature_id'];
                if (!empty($feature_id)) {
                    $feature_result = $feature->arrGetFeatureDetails($feature_id, $categoryid, "", "", "1");
                    $feature_name = $feature_result[0]['feature_name'];
                    $feature_img_path = $feature_result[0]['feature_img_path'];
                    $feature_description = $feature_result[0]['feature_description'];
                }
//                if (in_array($feature_id, $selectedfeatureArr)) {
//                    $pivot_result[$j]['selected_feature_id'] = $feature_id;
//                    $selecteditemArr[$selectedIndex]['selected_id'] = $feature_id;
//                    $selecteditemArr[$selectedIndex]['is_feature_select'] = "1";
//                    $selecteditemArr[$selectedIndex]['selected_type'] = 'checkbox_feature_id_' . $feature_id;
//                    $selecteditemArr[$selectedIndex]['selected_name'] = constructUrl($feature_name, '0');
//                    $selecteditemArr[$selectedIndex]['selected_feature_group'] = $sub_group_name;
//                    $selecteditemArr[$selectedIndex]['selected_name_display'] = $feature_name;
//                    $selectedIndex++;
//                    $plusminusimgstatus++;
//                }
                $pivot_result[$j]['feature_img_path'] = $feature_img_path;
                $pivot_result[$j]['feature_display_name'] = $feature_name;
                $new_feature_name = constructUrl($feature_name, '0');
                $pivot_result[$j]['feature_name'] = $new_feature_name;
                $pivot_result[$j]['feature_description'] = $feature_description;
                $pivot_result[$j]['sub_group_id'] = $sub_group_id;
                $pivotresult[$sub_group_id][$pivot_group][] = $pivot_result[$j];
//                $result[$i]['plus_minus_img_status'] = $plusminusimgstatus;
                foreach ($result[$i] as $k => $v) {
                    $pivotresult[$sub_group_id][$k] = $v;
                }
            }
        }
    }

    $component_xml .= "<PIVOT_MASTER>";
    if ($pivotresult) {
        foreach ($pivotresult as $maingroupkey => $maingroupval) {
            if (is_array($maingroupval)) {
                $component_xml .= "<PIVOT_MASTER_DATA>";
                foreach ($maingroupval as $subgroupkey => $subgroupval) {
                    if (is_array($subgroupval)) {
                        $component_xml .= "<SUB_PIVOT_MASTER>";
                        foreach ($subgroupval as $key => $featuredata) {
                            $feature_id = $featuredata['feature_id'];

                            if (is_array($featuredata)) {
                                $component_xml .= "<SUB_PIVOT_MASTER_DATA>";
                                $component_xml .= $popularfeaturexml;
                                $featuredata = array_change_key_case($featuredata, CASE_UPPER);
                                foreach ($featuredata as $featurekey => $featureval) {
                                    $component_xml .= "<$featurekey><![CDATA[$featureval]]></$featurekey>";
                                }
                                $component_xml .= "</SUB_PIVOT_MASTER_DATA>";
                            } else {
                                $key = strtoupper($key);
                                $component_xml .= "<$key><![CDATA[$featuredata]]></$key>";
                            }
                        }
                        $component_xml .= "</SUB_PIVOT_MASTER>";
                    } else {
                        $subgroupkey = strtoupper($subgroupkey);
                        $component_xml .= "<$subgroupkey><![CDATA[$subgroupval]]></$subgroupkey>";
                    }
                }
                $component_xml .= "</PIVOT_MASTER_DATA>";
            }
        }
    }
    $component_xml .= "</PIVOT_MASTER>";
}
$component_xml .= "<CAT_PATH><![CDATA[$cat_path]]></CAT_PATH>";

//$xml = "<XML>";
//$xml .= $component_xml;
//$xml .= "</XML>";
//header('Content-type: text/xml');
//echo $xml;
//exit;
?>
