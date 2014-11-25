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

$result = $oProduct->arrGetProductNameByBrand($category_id,$brand_id);
//print"<pre>";print_r($result);print"</pre>";
$cnt = sizeof($result);
if($result){
$sOptionTags='';
$html .= "<select name=\"select_model_id\" id=\"select_model_id\"><option value=\"\">---Select Model---</option>";
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
	$html .= "<input type='hidden' name='hd_select_model_id' id='hd_select_model_id' value=''>";
	$html .= "<div id='ajaxloadervariant' style='display:none;'>";
	$html .= "<div align='center'>";
	$html .= "<img src='".IMAGE_URL."ajax-loader.gif'/>";
	$html .= "</div>";
	$html .= "</div>";	
	echo $html;	
}else{echo false;}
}else{echo false;}
?>
