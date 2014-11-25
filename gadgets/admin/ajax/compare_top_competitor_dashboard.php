<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'pivot.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'brand.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$product = new ProductManagement;
$brand = new BrandManagement;
$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";
$category_id = $_REQUEST['catid'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$r_product_id = $_REQUEST['pid'];
$r_brand_id = $_REQUEST['bid'];
$iCmptId=$_REQUEST['competitor_product_id'];
if(!empty($category_id)){
	$result = $product->arrGetProductCompareCompetitorDetails("","","",$category_ids,"",$startlimit,$cnt);
}

if($_REQUEST['act']=='update' && !empty($iCmptId)){
	$rResult = $product->arrGetProductCompareCompetitorDetails($iCmptId,"","",$category_ids,"",$startlimit,$cnt);
	//print "<pre>";	print_r($rResult);
	$xmlArt='';
	$cnt = sizeof($rResult);
	$status = $rResult[0]['status'];
	$categoryid = $rResult[0]['category_id'];
	if(!empty($categoryid)){
		$category_result = $category->arrGetCategoryDetails($categoryid);
	}
	$brand_id = $rResult[0]['brand_id'];
	if(!empty($brand_id)){
		$brand_result = $brand->arrGetBrandDetails($brand_id);
		$brand_name = $brand_result[0]['brand_name'];
	}
	$rResult[0]['js_brand_name'] = $brand_name;
	$rResult[0]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : '';
	$product_id = $rResult[0]['product_id'];
	if(!empty($product_id)){
		$product_result =$product->arrGetProductDetails($product_id,$category_id,"",'',"","","","","","");
		$product_name = $product_result[0]['product_name'];
		$variant_name = $product_result[0]['variant'];
		$rResult[0]['variant_name'] = $variant_name;
		
	}
	$rResult[0]['js_product_name'] =$product_name;
	$rResult[0]['product_name'] = $product_name ? html_entity_decode($product_name,ENT_QUOTES) : '';
	
	$product_ids = $rResult[0]['product_ids'];
	if(!empty($product_ids)){
		$product_result =$product->arrGetProductDetails($product_ids,$category_id,"",'',"","","","","","");
		$product_name = $product_result[0]['product_name'];
		$variant_name = $product_result[0]['variant'];
		$brand_id = $product_result[0]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
			
		}
		$rResult[0]['js_brand_name_comp'] = $brand_name;
		$rResult[0]['brand_id_comp'] = $brand_id;
		$rResult[0]['brand_name_comp'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : '';
		
		$product_model =$product->arrGetProductNameInfo("",$category_id,"",$product_name,"1");
		$product_name_id = $product_model[0]['product_name_id'];
		$rResult[0]['comp_model_id'] = $product_name_id;
	}
	$rResult[0]['js_product_names'] =$product_name;
	$rResult[0]['product_names'] = $product_name ? html_entity_decode($product_name,ENT_QUOTES) : '';
	$rResult[0]['variant_name_comp'] = $variant_name;
	
	$rResult[0]['status'] = ($status == 1) ? 'Active' : 'InActive';
	$category_name = $category_result[0]['category_name'];
	$rResult[0]['js_category_name'] = $category_name;
	$rResult[0]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
	$rResult[0]['create_date'] = date('d-m-Y',strtotime($rResult[0]['create_date']));
	
	$rResult[0] = array_change_key_case($rResult[0],CASE_UPPER);
	//print "<pre>"; print_r($rResult[0]);
	$xmlArt .= "<TOP_COMPETITOR_DATA>";
	foreach($rResult[0] as $k1=>$v1){
		$xmlArt .= "<$k1><![CDATA[$v1]]></$k1>";
	}
	$xmlArt .= "</TOP_COMPETITOR_DATA>";
	//$brand_name="";$product_name="";$product_info_name="";$variant_name="";
        //unset($brand_result);
       // unset($product_result);
        //unset($product_model);
}

//print "<pre>"; print_r($result);
$cnt = sizeof($result);
$xml = "<TOP_COMPETITOR_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
for($i=0;$i<$cnt;$i++){
	$status = $result[$i]['status'];
	$categoryid = $result[$i]['category_id'];
	if(!empty($categoryid)){
		$category_result = $category->arrGetCategoryDetails($categoryid);
	}
	$brand_id = $result[$i]['brand_id'];
	if(!empty($brand_id)){
		$brand_result = $brand->arrGetBrandDetails($brand_id);
		$brand_name = $brand_result[0]['brand_name'];
	}
	$result[$i]['js_brand_name'] = $brand_name;
	$result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : '';
	$product_info_id1 = $result[$i]['product_info_id'];
	if(!empty($product_info_id1) && $product_info_id1!=0){
		$product_info_result = $product->arrGetProductNameInfo($product_info_id1,$category_id,"","","1");
		$product_info_name = $product_info_result[0]['product_info_name'];
		$result[$i]['product_name'] = $product_info_name ? html_entity_decode($product_info_name,ENT_QUOTES) : '';
	}
	unset($product_info_result);
	unset($product_model); unset($product_result); unset($brand_result);
	$product_name_id=''; $brand_name=''; $product_name1='';

	$product_id1 = $result[$i]['product_id'];
	if(!empty($product_id1) && $product_id1!=0){
		$product_result =$product->arrGetProductDetails($product_id1,$category_id,"","1","","","",$startlimit,$limitcnt,"");
		$product_name1 = $product_result[0]['product_name'];
		$product_variant1 = $product_result[0]['variant'];
		$result[$i]['js_product_name'] =$product_name1;
		$result[$i]['product_name'] = $product_name1 ? html_entity_decode($product_name1,ENT_QUOTES) : '';
		$result[$i]['js_variant'] =$product_variant1;
		$result[$i]['variant'] = $product_variant1 ? html_entity_decode($product_variant1,ENT_QUOTES) : '';
	}
	$product_ids = $result[$i]['product_ids'];
	if(!empty($product_ids) && $product_ids!=0){
		$product_result =$product->arrGetProductDetails($product_ids,$category_id,"","1","","","",$startlimit,$limitcnt,"");
		$product_name1 = $product_result[0]['product_name'];
		$product_variant1 = $product_result[0]['variant'];
		$result[$i]['js_product_names'] =$product_name1;
		$result[$i]['product_names'] = $product_name1 ? html_entity_decode($product_name1,ENT_QUOTES) : '';
		$result[$i]['js_variants'] =$product_variant1;
		$result[$i]['variants'] = $product_variant1 ? html_entity_decode($product_variant1,ENT_QUOTES) : '';
		$brand_id = $product_result[0]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
		}
		$result[$i]['comp_brand_id'] = $brand_id;
		$result[$i]['comp_brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : '';
		
		$product_model =$product->arrGetProductNameInfo("",$category_id,"",$product_name1,"1");
		$product_name_id = $product_model[0]['product_name_id'];
		$result[$i]['comp_model_id'] = $product_name_id;
	}
	

	$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
	$category_name = $category_result[0]['category_name'];
	$result[$i]['js_category_name'] = $category_name;
	$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
	$result[$i]['js_feature_name'] = $result[$i]['feature_name'];
	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	//print "<pre>"; print_r($result[$i]);
	$xml .= "<TOP_COMPETITOR_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml .= "</TOP_COMPETITOR_MASTER_DATA>";
	unset($product_info_result);
	unset($product_model); unset($product_result); unset($brand_result);
	$product_name_id=''; $brand_name=''; $product_name1='';
}
$xml .= "</TOP_COMPETITOR_MASTER>";

if(!empty($category_id)){
	$result = $brand->arrGetBrandDetails("",$category_id);
}	
$cnt = sizeof($result);
$xml .= "<BRAND_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
	$status = $result[$i]['status'];
	$categoryid = $result[$i]['category_id'];
	if(!empty($categoryid)){
		$category_result = $category->$result = $category->arrGetCategoryDetails($categoryid);
	}
	$category_name = $category_result[0]['category_name'];
	$result[$i]['js_category_name'] = $category_name;
	$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
	$result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
	$result[$i]['js_brand_name'] = $result[$i]['brand_name'];
	$result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'],ENT_QUOTES);
	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	$xml .= "<BRAND_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml .= "</BRAND_MASTER_DATA>";
}
$xml .= "</BRAND_MASTER>";



$config_details = get_config_details();
$strXML = "<?xml version='1.0' encoding='iso-8859-1'?>";
$strXML .= "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= $xmlArt;
$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('../xsl/compare_top_competitor_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
