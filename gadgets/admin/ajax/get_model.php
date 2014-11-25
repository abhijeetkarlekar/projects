<?php
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');
$dbconn = new DbConn;
$oProduct = new ProductManagement;
$brand_id = $_REQUEST['brand_id'];
$iInfoPrdId = $_REQUEST['product_name_id'];
$category_id = $_REQUEST['category_id'];

//$result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","1");
$category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : SITE_CATEGORY_ID;
$result = $oProduct->arrGetUniqueProductName($category_id,$brand_id);
$cnt = sizeof($result);
for($i=0;$i<$cnt;$i++){
	$existProductNameArr[] = trim($result[$i]['product_name']);
}
$result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","","","","","","");
$cnt = sizeof($result);
if(!empty($cnt)){
	for($i=0;$i<$cnt;$i++){
		$existProductInfoArr[] = $result[$i]['product_info_name'];
	}
	$newProductNameArr = array_diff($existProductNameArr,$existProductInfoArr);
}else{
	$newProductNameArr = $existProductNameArr;
}
$newProductNameArr = array_unique($newProductNameArr);

if(sizeof($newProductNameArr) > 0){
	$productresult = $oProduct->arrGetUniqueProductName($category_id,$brand_id);
	$cnt = sizeof($productresult);
	for($i=0;$i<$cnt;$i++){
		$insert_param['product_info_name'] = trim($productresult[$i]['product_name']);
		$insert_param['brand_id'] = $productresult[$i]['brand_id'];
		$insert_param['category_id'] = $productresult[$i]['category_id'];
		#$result = $oProduct->intInsertProductNameInfo($insert_param);
	}
	$result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id);
}


if($result){
$sOptionTags='';
$html .= "<select name=\"product_name\" id=\"product_name\" ><option value=\"\">---Select Model---</option>";
if(is_array($result)){
	foreach($result as $ikey=>$aValue){
		$iId=$aValue['product_name_id'];
		$sName=$aValue['product_info_name'];
		if(empty($sOptionTags)){
			$sOptionTags .= $iId."#".$sName."#true#false";
		}else{
			$sOptionTags .="|".$iId."#".$sName."#true#false";
		}

		if($iId==$iInfoPrdId){
			$html .= "<option value='$iId' selected>$sName</option>";
		}else{$html .= "<option value='$iId'>$sName</option>";}

	}
	$html .= "</select>";

	echo $html;
}else{echo false;}
}else{echo false;}
?>
