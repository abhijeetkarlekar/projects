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

$result = $oProduct->arrGetProductNameInfo($iInfoPrdId,$category_id,"","","1");

if($result){
$sOptionTags='';
$html .= "<select name=\"product_id\" id=\"product_id\" ><option value=\"\">---Select Variant---</option>";
if(is_array($result)){
	$sProductInfoName=$result['0']['product_info_name'];
	$aProductDetail=$oProduct->arrGetProductByName($sProductInfoName);
	///print_r($aProductDetail//);
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
	$html .= "</select>";
        $html .= "<input type='hidden' name='hd_product_id' id='hd_product_id' value=''>";
	//echo $sOptionTags;	
	echo $html;	
}else{echo false;}
}else{echo false;}
?>
