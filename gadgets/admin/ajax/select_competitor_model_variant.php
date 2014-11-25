<?php
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');
$dbconn = new DbConn;
$oProduct = new ProductManagement;
$brand_id = $_REQUEST['brand_id'];
$iInfoPrdId = $_REQUEST['product_name_id'];
$product_id = $_REQUEST['product_id'];
$category_id = $_REQUEST['category_id'];



$html .= "<select name=\"comp_product_id\" id=\"comp_product_id\" ><option value=\"\">---Select---</option>";
if(!empty($brand_id)){
	$aProductDetail = $oProduct->arrGetProductByName("","","","","","","","",$brand_id);
	foreach($aProductDetail as $ikey=>$aValue){
		$product_names = array();
		$iId = $aValue['product_id'];
		$product_names[] = $aValue['product_name'];
		$product_names[] = $aValue['variant'];
		$sName = $aValue['variant'];
		$product = implode(" ",$product_names);
		if($iId == $product_id){
			$html .= "<option value='$iId' selected>$product</option>";
		}else{$html .= "<option value='$iId'>$product</option>";}	
		
	}	
	$html .= "</select>";
        //$html .= "<input type='hidden' name='hd_product_id' id='hd_product_id' value=''>";
	//echo $sOptionTags;	
	echo $html;	
}else{echo false;}
?>
