<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'product.class.php');
	$dbconn = new DbConn;
	$product = new ProductManagement;
	$category_id = $_REQUEST['category_id'];
	$brand_id = $_REQUEST['brand_id'];
	$productid = $_REQUEST['productid'];

	$result = $product->arrGetProductDetails("",$category_id,$brand_id,'1',"","","",$startlimit,$limitcnt,"");
	//print "<pre>"; print_r($result);

	$cnt = sizeof($result);
	$html .= '<select name="product_id" id="product_id"><option value="">---Select product---</option>';
	for($i=0;$i<$cnt;$i++){
		unset($productnameArr);
		$product_id = $result[$i]['product_id'];
		$productnameArr[] = $result[$i]['product_name'];
		$productnameArr[] = $result[$i]['variant'];
		$result[$i]['product_name'] = implode(" ",$productnameArr);
		$product_name = html_entity_decode($result[$i]['product_name'],ENT_QUOTES);
		
		if($product_id==$productid){
		$html .= "<option value='$product_id' selected>$product_name</option>";
		}else{$html .= "<option value='$product_id'>$product_name</option>";}
	}
	$html .= "</select>";
	echo $html;	
?>
