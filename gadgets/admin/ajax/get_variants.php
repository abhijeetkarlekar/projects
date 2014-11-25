<?php
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');
$dbconn = new DbConn;
$oProduct = new ProductManagement;
$brand_id = $_REQUEST['brand_id'];
$iInfoPrdId = $_REQUEST['product_name_id'];
$product_id = $_REQUEST['product_id'];

$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : SITE_CATEGORY_ID;
$result = $oProduct->arrGetProductNameInfo($iInfoPrdId,$category_id,"","","1");
//print_r($result);

$sOptionTags='';
$html = '<option value="">--Select Variant--</option>';
if(!empty($result)){
	//echo "sasasas";
	$sProductInfoName=$result['0']['product_info_name'];
	$aProductDetail=$oProduct->arrGetProductName($sProductInfoName,$category_id,$brand_id);
	//print_r($aProductDetail);

	foreach($aProductDetail as $ikey=>$aValue){
		$iId=$aValue['product_id'];
		$sName=$aValue['variant'];
		/*if(empty($sOptionTags)){
			$sOptionTags .= $iId."#".$sName."#false#false";
		}else{
			$sOptionTags .="|".$iId."#".$sName."#false#false";
		}*/

		if($iId==$product_id){
			$html .= "<option value='$iId' selected>$sName</option>";
		}else{$html .= "<option value='$iId'>$sName</option>";}

	}
	//echo $sOptionTags;
	echo $html;
}else{echo false;}
?>
