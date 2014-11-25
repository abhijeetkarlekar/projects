<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'pager.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$product = new ProductManagement;
$brand = new BrandManagement;
$oPager = new Pager;

//print "<pre>"; print_r($_REQUEST);
$category_id = $_REQUEST['catid'];
$category_id = ($category_id != "") ? $category_id : SITE_CATEGORY_ID;
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";

$popular_id =$_REQUEST['popular_id'] ? $_REQUEST['popular_id'] : '';
$selected_brand_id = $_REQUEST['selected_brand_id'] ? $_REQUEST['selected_brand_id'] : '';

if($_REQUEST['act']=='update' && !empty($popular_id)){
	
	$result = $brand->arrGetPopularBrandDetails($popular_id,"","",$category_id,"","","","");
	//print "<pre>"; print_r($result);print"</pre>";//exit;
	$cnt = sizeof($result);

	$xml .= "<POPULAR_BRAND_DETAIL>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));

		$brand_id = $result[$i]['brand_id'];
	        if(!empty($brand_id)){
                	$brand_result = $brand->arrGetBrandDetails($brand_id,$category_id);
        	}
	        $brand_name = $brand_result[0]['brand_name'];
        	$result[$i]['js_brand_name'] = $brand_name;
	        $result[$i]['brand_name'] = html_entity_decode($brand_name,ENT_QUOTES);
        	$brand_name = "";

	        unset($product_result);
        	$popular_model_id = $result[$i]['popular_model_id'];
	        if(!empty($popular_model_id)){
        	        $product_result = $product->arrGetProductNameInfo($popular_model_id,$category_id,$brand_id);
	        }
        	$popular_model_name = $product_result[0]['product_info_name'];
	        $result[$i]['js_popular_model_name'] = $popular_model_name;
        	$result[$i]['popular_model_name'] = html_entity_decode($popular_brand_name,ENT_QUOTES);
	        $popular_model_name = "";

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		//print "<pre>"; print_r($result[$i]);
		$xml .= "<POPULAR_BRAND_DETAIL_DATA>";
		foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</POPULAR_BRAND_DETAIL_DATA>";
	}
	$xml .= "</POPULAR_BRAND_DETAIL>";
}


unset($result);
if(!empty($category_id)){
	 $result = $brand->arrGetPopularBrandDetails("","","",$category_id,"","","","");
}
$cnt = sizeof($result);
$position_count=$cnt+1;
#print "<pre>"; print_r($result);
$xml .= "<POPULAR_BRAND_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
	
	$status = $result[$i]['status'];
	$categoryid = $result[$i]['category_id'];
	if(!empty($categoryid)){
		$category_result = $category->arrGetCategoryDetails($categoryid);
	}
	$category_name = $category_result[0]['category_name'];
	$result[$i]['js_category_name'] = $category_name;
	$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
	$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));

	$brand_id = $result[$i]['brand_id'];
	if(!empty($brand_id)){
		$brand_result = $brand->arrGetBrandDetails($brand_id,$category_id);
	}
	$brand_name = $brand_result[0]['brand_name'];
	$result[$i]['js_brand_name'] = $brand_name;
	$result[$i]['brand_name'] = html_entity_decode($brand_name,ENT_QUOTES);
	$brand_name = "";

	unset($product_result);
	$popular_model_id = $result[$i]['popular_model_id'];
        if(!empty($popular_model_id)){
                $product_result = $product->arrGetProductNameInfo($popular_model_id,$category_id,$brand_id);
        }
        $popular_model_name = $product_result[0]['product_info_name'];
        $result[$i]['js_popular_model_name'] = $popular_model_name;
        $result[$i]['popular_model_name'] = html_entity_decode($popular_model_name,ENT_QUOTES);
	$popular_model_name = "";
	
	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	//print "<pre>"; print_r($result[$i]);
	$xml .= "<POPULAR_BRAND_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml .= "</POPULAR_BRAND_MASTER_DATA>";
}
$xml .= "</POPULAR_BRAND_MASTER>";

 $xml .= "<POSITION_MASTER>";
        for($pc=1;$pc<=$position_count;$pc++){
            $xml .= "<POSITION_MASTER_DATA><POSITION>".$pc."</POSITION></POSITION_MASTER_DATA>";
        }
        $xml .= "</POSITION_MASTER>";


unset($result);
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
		$category_result = $category->arrGetCategoryDetails($categoryid);
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

unset($result);
if(!empty($category_id) && !empty($selected_brand_id) && !empty($popular_id)){
        $result = $product->arrGetProductNameInfo("",$category_id,$selected_brand_id,"");
}
$cnt = sizeof($result);
$xml .= "<MODEL_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
	$model_id = $result[$i]['product_name_id'];
	$model_name = $result[$i]['product_info_name'];
        $categoryid = $result[$i]['category_id'];
        $brand_id = $result[$i]['brand_id'];
        $result[$i]['model_id'] = $model_id;
        $result[$i]['model_name'] = html_entity_decode($model_name,ENT_QUOTES);
        $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
        $xml .= "<MODEL_MASTER_DATA>";
        foreach($result[$i] as $k=>$v){
                $xml .= "<$k><![CDATA[$v]]></$k>";
        }
        $xml .= "</MODEL_MASTER_DATA>";
}
$xml .= "</MODEL_MASTER>";

$iRelUploadCnt= $iRelUploadCnt ? $iRelUploadCnt :1;
$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "<WALLCNT><![CDATA[$iRelUploadCnt]]></WALLCNT>";
$strXML .= "<SELECTED_BRAND_ID><![CDATA[$selected_brand_id]]></SELECTED_BRAND_ID>";
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }


$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('../xsl/popular_brand_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
