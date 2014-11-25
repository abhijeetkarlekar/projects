<?php
require_once('../../include/config.php');
#require_once(CLASSPATH.'top_selling_car.class.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'feature.class.php');

$dbconn         = new DbConn;
#$top_sell       = new TopSellingCar;
$oBrand         = new BrandManagement;
$oProduct       = new ProductManagement;
$oFeatureFuel   = new FeatureManagement;

$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : SITE_CATEGORY_ID;
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];

$feature_result=$oFeatureFuel->arrGetFeatureDetails($feature_ids, $category_id = "1", $main_group_id = "3", $sub_group_id = "19", $status = "1", $startlimit = "", $count = "", $feature_name);
unset($fuelData);
foreach ($feature_result as $frKey=>$frData){
    $fuelData[$frData['feature_id']]=$frData['feature_name'];
}
$fuelData[0]="All";
//print"<pre>";print_r($fuelData);exit;
//Get Last month top selling car list
$current_month=date(m);
if($current_month==1){
    $month=12;
    $year=date(Y)-1;
}else{
    $month=date(m)-1;
    $year=date(Y);
}
$month="";$year="";
#$result=$top_sell->arrGetTopSellingCarDetails("","","","1",$month,$year,"brand_id asc,product_name_id asc,fuel_type asc",$startlimit,$limitcnt);
//print"<pre>";print_r($result);exit;
$cnt = sizeof($result);
$xml = "<MODEL_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
    $brand_id=$result[$i]['brand_id'];
    $brand_result=$oBrand->arrGetBrandDetails($brand_id);
    $result[$i]['brand_name']=$brand_result[0][brand_name];
    $product_name_id=$result[$i]['product_name_id'];
    $product_result=$oProduct->arrGetProductNameInfo($product_name_id);
    $result[$i]['model_name']=$product_result[0][product_info_name];
    $result[$i]['fuel_type_name']=$fuelData[$result[$i]['fuel_type']];
    $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
    $xml .= "<MODEL_MASTER_DATA>";
    foreach($result[$i] as $k=>$v){
        $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $xml .= "</MODEL_MASTER_DATA>";
}
$xml .= "</MODEL_MASTER>";
$config_details = get_config_details();

$strXML .= "<XML>";
$strXML .= $config_details;
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SEO_URL><![CDATA[$seo_url]]></SEO_URL>";
$strXML .= "<SEO_WEB_URL><![CDATA[" . SEO_WEB_URL . "]]></SEO_WEB_URL>";
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<SEO_TAGS><![CDATA[$seo_keywords]]></SEO_TAGS>";
$strXML .= "<SEO_DESC><![CDATA[$seo_desc]]></SEO_DESC>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML.= $xml;
$strXML.= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if ($_REQUEST['debug'] == 1) {
    header('Content-type: text/xml');
    echo $strXML;
    exit;
}

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('../xsl/product_pivotfeatures_upload_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
