<?php
	require_once('../include/config.php');
	//require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'product.class.php');
	$dbconn = new DbConn;
	
	
	$oProduct = new ProductManagement;
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$request_param=$_REQUEST;
//	print_r($_REQUEST); die();	
	$cayegory_id=$_REQUEST['selected_category_id'];
	$best_seller_product_id=$_REQUEST['best_seller_product_id'];
	if($_POST['product_id']!='' && strlen($_POST['product_id'])>0){
                //print"<pre>";print_r($_POST);exit;
			$product_id = $_POST['product_id'];
		$brand_id = $_REQUEST['select_brand_id'];
		if(!empty($product_id)){
			$product_result = $oProduct->arrGetProductDetails($product_id,$category_id,$brand_id,'',"","","","","","","","","","",'',"",'',"");
			//print_r($product_result);
			$product_name = $product_result[0]['product_name'];
			if(!empty($product_name)){
				$product_info_result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,$product_name,"","","","","","","","","");
				//print_r($product_info_result);
				$product_info_id     = $product_info_result[0]['product_name_id'];
			}	
		}
		$aParameters = array("category_id"=>$_REQUEST['selected_category_id'],"brand_id"=>$_REQUEST['select_brand_id'],"product_info_id"=>$product_info_id ,"product_id"=>$_REQUEST['product_id'],"product_position"=>$_REQUEST['product_position'],"status"=>$_REQUEST['product_status'] );
		$best_seller_product_id==0 ? $aParameters['create_date']=date("Y-m-d H:i:s") : $aParameters['update_date']=date("Y-m-d H:i:s"); 
               # print"<pre>";print_r($aParameters);exit;
		if($best_seller_product_id>0) $aParameters['best_seller_product_id']=$best_seller_product_id;
		$iResId=$oProduct->intInsertBestSellerProduct($aParameters);
	 	$best_seller_product_id=$iResId;
		
		if($best_seller_product_id==0) {
			$best_seller_product_id=$iResId;
			$msg="Product detail added successfully.";
		}else {
			$msg="Product detail updated successfully.";
		}
	
	}

	if($actiontype == 'Delete'){
	   $result = $oProduct->boolDeleteBestSellerProduct($best_seller_product_id);
	   $msg = 'Product deleted successfully.';
	}
	
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$config_details = get_config_details();




$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "</XML>";
#header('Content-type: text/xml');echo $strXML;exit;
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;
$xsl = DOMDocument::load('xsl/best_seller_product.xsl');
$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
