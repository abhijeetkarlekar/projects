<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'product.class.php');
	require_once(CLASSPATH.'brand.class.php');
	require_once(CLASSPATH."price.class.php");
	require_once(CLASSPATH.'pager.class.php');

	$dbconn = new DbConn;
	$category = new CategoryManagement;
	$product = new ProductManagement;
	$brand = new BrandManagement;
	$oPrice	= new price;
	$oPager = new Pager;

	$price_variant = $_REQUEST['price_variant'];
	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$r_product_id = $_REQUEST['pid'];
	$r_brand_id = $_REQUEST['bid'];

	$selected_brand_id = $_REQUEST['selected_brand_id'] ? $_REQUEST['selected_brand_id'] :"";
	$selected_model_id = $_REQUEST['selected_model_id'] ? $_REQUEST['selected_model_id'] : "" ;
	$selected_variant_id = $_REQUEST['selected_variant_id'] ? $_REQUEST['selected_variant_id'] : "" ;
	$selected_city_id = $_REQUEST['city_id'] ? $_REQUEST['city_id'] :"";
	$state_id = $_REQUEST['state_id'];


	$iItemCountresult = $oPrice->arrGetVariantValueDetailCount("","",$selected_variant_id,$category_id,$selected_brand_id,"",$selected_city_id,"","");
	$iItemCount = $iItemCountresult[0]['cnt'];
	if($iItemCount != 0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage = 30;
	$start  = $oPager->findStart($perpage);
	$recordcount = $iItemCount;
	$sExtraParam = "ajax/variant_value_dashboard_list.php,DivArticle,$category_id,$selected_brand_id,$selected_model_id,$selected_variant_id,$selected_city_id";
	$jsparams = $start.",".$perpage.",".$sExtraParam;
	//echo $recordcount,$perpage."<br>"; die();
	$pages = $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist = $oPager->jsPageNumNextPrev($page,$pages,"sPricePagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}

		$orderby=" order by update_date desc";
		$result = $oPrice->arrGetVariantValueDetail("","",$selected_variant_id,$category_id,$selected_brand_id,"",$selected_city_id,"",$start,$perpage,"",$orderby);
	}

	$cnt = sizeof($result);
	$xml = "<VARIANT_VALUE_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$category_id = $result[$i]['category_id'];
		$brand_id = $result[$i]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
		}
		$result[$i]['js_brand_name'] = $brand_name;
		$result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : 'Nil';
		$product_id1 = $result[$i]['product_id'];
		#echo $result[$i]['product_id'];
		if(!empty($product_id1)){
			unset($productNameArr);
			$product_result =$product->arrGetProductDetails($product_id1,$category_id);
			#print_r($product_result); die();
			$product_name1 = $product_result[0]['product_name'];
			$variant1 = $product_result[0]['variant'];
			if(!empty($product_name1)){
				$productNameArr[] = $product_name1;
			}
			if(!empty($variant1)){
				$productNameArr[] = $variant1;
			}
			$product_name1 = implode(" ",$productNameArr);
			//echo $product_name1."<br>";
		}
		$variant_id = $result[$i]['variant_id'];
		unset($city_result);
		if(!empty($variant_id)){
			$variant_result = $oPrice->arrGetVariantDetail($variant_id,$category_id,"");
			$variant = $variant_result[0]['variant'];
		}
		$result[$i]['variant'] = $variant ? html_entity_decode($variant,ENT_QUOTES) : 'Nil';
		$result[$i]['js_product_name'] =$product_name1;
		$result[$i]['product_name'] = $product_name1 ? html_entity_decode($product_name1,ENT_QUOTES) : 'Nil';

		//echo $product_name1."<br>";

		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['update_date'] = date('d-m-Y',strtotime($result[$i]['update_date']));
		$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<VARIANT_VALUE_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</VARIANT_VALUE_MASTER_DATA>";
	}
	$xml .= "</VARIANT_VALUE_MASTER>";

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
		$result[$i]['update_date'] = date('d-m-Y',strtotime($result[$i]['update_date']));
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


	if(!empty($category_id)){
		$resultVariant = $oPrice->arrGetVariantDetail("",$category_id);
	}

	$vcnt = sizeof($resultVariant);
	$xml .= "<VARIANT_MASTER>";
	$xml .= "<COUNT><![CDATA[$vcnt]]></COUNT>";
	for($i=0;$i<$vcnt;$i++){
		$status = $resultVariant[$i]['status'];
		$categoryid = $resultVariant[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result =$category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		$resultVariant[$i]['js_category_name'] = $category_name;
		$resultVariant[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$resultVariant[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
		$resultVariant[$i]['create_date'] = date('d-m-Y',strtotime($resultVariant[$i]['create_date']));
		$resultVariant[$i]['update_date'] = date('d-m-Y',strtotime($resultVariant[$i]['update_date']));
		$resultVariant[$i]['js_variant'] = $result[$i]['variant'];
		$resultVariant[$i]['variant'] = html_entity_decode($resultVariant[$i]['variant'],ENT_QUOTES);
		$resultVariant[$i] = array_change_key_case($resultVariant[$i],CASE_UPPER);
		$xml .= "<VARIANT_MASTER_DATA>";
		foreach($resultVariant[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</VARIANT_MASTER_DATA>";
	}
	$xml .= "</VARIANT_MASTER>";

	unset($result);

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
		$strXML .= "<SELECTED_BRAND_ID><![CDATA[$selected_brand_id]]></SELECTED_BRAND_ID>";
	$strXML .= "<SELECTED_MODEL_ID><![CDATA[$selected_model_id]]></SELECTED_MODEL_ID>";
	$strXML .= "<SELECTED_VARIANT_ID><![CDATA[$selected_variant_id]]></SELECTED_VARIANT_ID>";
	$strXML .= "<SELECTED_CITY_ID><![CDATA[$selected_city_id]]></SELECTED_CITY_ID>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $xmlArt;
	$strXML .= $nodesPaging;
	$strXML .= "</XML>";

	$strXML = mb_convert_encoding($strXML, "UTF-8");
	if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();

	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/variant_value_dashboard_list.xsl');

	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
