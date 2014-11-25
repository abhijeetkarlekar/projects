<?php
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');
$dbconn = new DbConn;
$oProduct = new ProductManagement;
$brand_id = $_REQUEST['brand_id'];
$iInfoPrdId = $_REQUEST['product_name_id'];
$product_id = $_REQUEST['product_id'];
$type = $_REQUEST['type'];

$category_id = $_REQUEST['catid'];
//$result = $oProduct->arrGetProductNameInfo($iInfoPrdId,$category_id,"","","","","","","","","1");
$result = $oProduct->arrGetProductDetails("",$category_id,$brand_id,'1',"","","1","","","1","","","","",'',"1");
$sOptionTags='';
$prev_3_month_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
$str_prev_3_month_date = strtotime($prev_3_month_date);
$str_def_month_date = strtotime('0000-00-00 00:00:00');

//print_r($_REQUEST); 
if(!empty($type) && $type=='input_price'){
	$aProductDetail = $oProduct->arrGetProductDetails($product_id,$category_id,$brand_id,'1',"","","1","","","1","","","","",'',"1");
	echo $price = $aProductDetail[0]['variant_value'];

}else if(!empty($type) && $type=='input'){
	$sProductInfoName=$result['0']['product_info_name'];
	//$aProductDetail=$oProduct->arrGetProductNameWithPrice($sProductInfoName,$category_id);
	$aProductDetail=$oProduct->arrGetProductDetails("",$category_id,"",'1',"","","1","","","1","",$sProductInfoName,"","",'',"1");
	if(is_array($aProductDetail)){
		$iId = $aProductDetail[0]['product_id'];
		echo $html = trim($iId);
	}else{
		echo false;
	}
}else{
$html = '<option value="">--Select Variant--</option>';
if(is_array($result)){
	//$sProductInfoName=$result['0']['product_info_name'];
	//$aProductDetail=$oProduct->arrGetProductNameWithPrice($sProductInfoName,$category_id);
	foreach($result as $ikey=>$aValue){
		$discontinue_date = strtotime($aValue['discontinue_date']);
		if($discontinue_date >= $str_prev_3_month_date || $discontinue_date == $str_def_month_date){
			$iId=$aValue['product_id'];
			$product_name=$aValue['product_name'];
			$sName=$aValue['variant'];
			$product_select_name = $product_name." ".$sName;
			unset($variantUrlYear);
			$variantUrlYear = buildYear($aValue['arrival_date'],$aValue['discontinue_date']);
			if(!empty($variantUrlYear)){
				$sName = $sName."($variantUrlYear)";
			}
			if($iId==$product_id){
				$html .= "<option value='$iId' selected>".trim($product_select_name)."</option>";
			}else{$html .= "<option value='$iId'>".trim($product_select_name)."</option>";}	
		}
	}	
	//echo $sOptionTags;	
	echo $html;	
}else{echo false;}
}
