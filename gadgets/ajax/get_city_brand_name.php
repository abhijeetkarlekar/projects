<?php
require_once('./../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');


$dbconn = new DbConn;
$brand = new BrandManagement;


$brand_id = $_REQUEST['brand_id'];
if($_REQUEST['action'] == "get_brand_name"){
	$seo_path="";
        if(!empty($brand_id)){
                $brand_result = $brand->arrGetBrandDetails($brand_id,$category_id,"1");
                $cnt = sizeof($brand_result);
                if(!empty($cnt)){
                        $seo_path = $brand_result["0"]["seo_path"];
			echo $seo_path;
                }else{
			echo false;
		}
        }else{
		echo false;
	}
}
exit;
