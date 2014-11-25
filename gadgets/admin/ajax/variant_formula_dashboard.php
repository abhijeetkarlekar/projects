<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'product.class.php');
	require_once(CLASSPATH.'brand.class.php');
	require_once(CLASSPATH."price.class.php");

	$dbconn = new DbConn;
	$category = new CategoryManagement;
	$product = new ProductManagement;
	$brand = new BrandManagement;
	$oPrice	= new price;
	//print "<pre>"; print_r($_REQUEST);
	$variant_formula_id = $_REQUEST['variant_formula_id'];
	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	if($_REQUEST['act']=='update' && !empty($variant_formula_id)){
		$category_id = $category_id ? $category_id : $_REQUEST['catid'];
		$aParameters=array('category_id'=>$category_id);
		$rResult = $oPrice->arrGetVariantFormulaDetail($variant_formula_id,"","","","",$startlimit,$limitcnt);
		$xmlArt='';
		$cnt = sizeof($rResult);
		$status = $rResult[0]['status'];
		$category_id = $category_id ?  $category_id : $rResult[0]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$brand_id = $rResult[0]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
		}
		$formula = $rResult[0]['formula'];
		$trans = array("+" => ",", "-" => ",", "*" => ",", "/" => ",");
		$sFormulaVarIds= strtr($formula, $trans);
		$formulalen=strlen($formula);
		for($m=0;$m<$formulalen;$m++){
			$char =substr($formula, $m, 1);
			$aFormulaExp[]=$char;
		}
		$rResult[0]['variant'] = $variant ? html_entity_decode($variant,ENT_QUOTES) : 'Nil';
		$rResult[0]['js_brand_name'] = $brand_name;
		$rResult[0]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : 'Nil';
		$product_id = $rResult[0]['product_id'];
		$rResult[0]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$category_name = $category_result[0]['category_name'];
		$rResult[0]['js_category_name'] = $category_name;
		$rResult[0]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$rResult[0]['create_date'] = date('d-m-Y',strtotime($rResult[0]['cdate']));
		$rResult[0] = array_change_key_case($rResult[0],CASE_UPPER);
		$xmlArt .= "<VARIANT_VALUE_DATA>";
		foreach($rResult[0] as $k1=>$v1){
			$xmlArt .= "<$k1><![CDATA[$v1]]></$k1>";
		}
		$xmlArt .= "</VARIANT_VALUE_DATA>";
	}

	if(!empty($category_id)){
		$aParameters=array('category_id'=>$category_id);
		$result = $oPrice->arrGetVariantFormulaDetail("","",$category_id,"","",$startlimit,$limitcnt);
		$variant_cnt_result = $oPrice->arrGetVariantDetail("",$category_id,"");
		$iVariantCount=sizeof($variant_cnt_result);
	}

	$cnt = sizeof($result);
	$xml = "<VARIANT_VALUE_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$category_id = $result[$i]['category_id'];
		if(!empty($category_id)){
			$category_result = $category->arrGetCategoryDetails($category_id);
		}
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);

		$brand_id = $result[$i]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
		}
		$result[$i]['js_brand_name'] = $brand_name;
		$result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : 'Nil';
		$product_id1 = $result[$i]['product_id'];
		if(!empty($product_id1)){
			$product_result =$product->arrGetProductDetails($product_id1,$category_id,"","1","","","",$startlimit,$limitcnt);
			$product_name1 = $product_result[0]['product_name'];
		}
		$result[$i]['variant'] = $variant ? html_entity_decode($variant,ENT_QUOTES) : 'Nil';
		$result[$i]['js_product_name'] =$product_name1;
		$result[$i]['product_name'] = $product_name1 ? html_entity_decode($product_name1,ENT_QUOTES) : 'Nil';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
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

	$Frxml .= "<VARIANT_FORMULA_MASTER>";
	$Frxml .= "<COUNT><![CDATA[$iVariantCount]]></COUNT>";
	for($k=0;$k<$iVariantCount;$k++){
		$Frxml .= "<VARIANT_FORMULA_MASTER_DATA>";
		$Frxml .= "<IPOS><![CDATA[$k]]></IPOS>";
		$Frxml .= "</VARIANT_FORMULA_MASTER_DATA>";
	}
	$Frxml .= "</VARIANT_FORMULA_MASTER>";

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

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $xmlArt;
	$strXML .= $Frxml;
	$strXML .= "</XML>";

	$strXML = mb_convert_encoding($strXML, "UTF-8");
	if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();

	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/variant_formula_dashboard.xsl');

	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
