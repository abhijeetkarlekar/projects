<?php
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'pivot.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'PageNavigator.php');
require_once(CLASSPATH.'Utility.php');
require_once(CLASSPATH.'pager.class.php');
//require_once(CLASSPATH.'user_review.class.php');


$dbconn = new DbConn;
$brand = new BrandManagement;
$category = new CategoryManagement;
$pivot = new PivotManagement;
$feature = new FeatureManagement;
$product = new ProductManagement;

$category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : 1;
$product_id = $_REQUEST['product_id'];
//error_log("ProductManagement=============".$product_id);


//print_r($_COOKIE);  die();
if(isset($_COOKIE['cmpids'])){
	if($product_id!=''){
		$productIdsExArr = explode("|",$_COOKIE['cmpids']);
		if(!in_array($product_id, $productIdsExArr)){
			$productids = $_COOKIE['cmpids']."|".$product_id;
		}
	}else{
		
		$productids = $_COOKIE['cmpids'];
	}	
}else{
	if($product_id!=''){
		//echo "IN2-";
		$productids = $product_id;
	}
}

setcookie ('cmpids',$productids,time()+3600*24, '/',DOMAIN);
if(!empty($productids)){
	$product_ids = str_replace("|", ",", $productids);
	//$product_ids = substr($productids1,0,-1);
	$productIdsArr = explode(",",$product_ids);
	if(sizeof($productIdsArr)>4){
		//echo "IN4-";
	 	$productIdsArr = array_slice($productIdsArr, 0, 3);
	}
	
	$productIdsArr = array_unique($productIdsArr);
	$product_ids = implode(",",$productIdsArr);
	
	if($product_ids!=''){
		$aProductDetail = $product->arrGetProductDetails($product_ids,$category_id,'','1',"","","1","","","1","","","","",'',"1");
	}
	if(is_array($aProductDetail)){
		//print "<pre>"; print_r($aProductDetail)	; die();
		$cnt = sizeof($aProductDetail);
		$xml .= "<COMPAREPRODUCT_MASTER>";	
			for($i=0;$i<$cnt;$i++){
					$product_id = $aProductDetail[$i]['product_id'];
					$brand_id = $aProductDetail[$i]['brand_id'];
					if(!empty($brand_id)){
						$result = $brand->arrGetBrandDetails($brand_id,$category_id,"1","","");
						if(is_array($result)){
							$brand_name = $result[0]['brand_name'];
						}
					}
					
					if(!empty($category_id)){
						$category_result = $category->arrGetCategoryDetails($category_id);
					}
					
					$category_name = $category_result[0]['category_name'];
					$category_seo_path = $category_result[0]['seo_path'];
					$product_name = $aProductDetail[$i]['product_name'];
					$variant = $aProductDetail[$i]['variant'];
					$variant_value = $aProductDetail[$i]['variant_value'];
					$image_path = $aProductDetail[$i]['image_path'];
					if(!empty($image_path)){
						$image_path = resizeImagePath($image_path,"87X65",$aModuleImageResize);
						$aProductDetail[$i]['image_path'] = CENTRAL_IMAGE_URL.str_replace(array(CENTRAL_IMAGE_URL),"",$image_path);
					}
					$product_name = html_entity_decode($product_name,ENT_QUOTES,'UTF-8');
					$brand_name = html_entity_decode($brand_name,ENT_QUOTES,'UTF-8');
					$variant = html_entity_decode($variant,ENT_QUOTES,'UTF-8');
					if(!empty($brand_name)){					
						$modelnameArr[] = $brand_name;
					}
					if(!empty($product_name)){					
						$modelnameArr[] = $product_name;
					}
					if(!empty($variant)){					
						$modelnameArr[] = $variant;
					}
					$aProductDetail[$i]['DISPLAY_PRODUCT_NAME'] = implode(" ",$modelnameArr);
					unset($modelnameArr);
					if(!empty($brand_name)){        
					      $comparenames[]= constructUrl($brand_name);
			        }
			        if(!empty($product_name)){        
			              $comparenames[]= constructUrl($product_name);
			        }
			        if(!empty($variant)){        
			              $comparenames[]= constructUrl($variant);
			        }
				    if(!empty($varianUrlYear)){
			            $comparenames[]= $varianUrlYear;
					}
			        $comparename = constructUrl(implode("-",$comparenames));
			        unset($comparenames);
			        $aProductDetail[$i]['comparename'] = $comparename;
			        $comparedtring[] = $comparename;
					$aProductDetail[$i] = array_change_key_case($aProductDetail[$i],CASE_UPPER);
					$xml .= "<COMPAREPRODUCT_MASTER_DATA>";
					foreach($aProductDetail[$i] as $k=>$v){
						$xml .= "<$k><![CDATA[$v]]></$k>";
					}
					$xml .= "</COMPAREPRODUCT_MASTER_DATA>";
			}
			$compareData[] = SEO_WEB_URL;
			$compareData[] = $category_seo_path;
			$compareData[] = SEO_COMPARE_URL;
			$compareData[] = constructUrl(implode(" Vs ", $comparedtring));
			$seo_compare_url =  implode("/",$compareData);
			$xml .= "<COMPAREPRODUCT_URL_LINK>$seo_compare_url</COMPAREPRODUCT_URL_LINK>";
			$xml .= "</COMPAREPRODUCT_MASTER>";
		}
		
}
$config_details = get_config_details();
$login_details = getCookie();
$strXML = "<XML>";
$strXML .= $xml;
$strXML .= $login_details;
$strXML .= $config_details;
$strXML .= "</XML>";
#$_REQUEST['debug'] = 1;
//header('Content-type: text/xml');echo $strXML;exit;
if($_REQUEST['debug']==1){ header('Content-type: text/xml');echo $strXML;exit;}
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;    
//$xslt->registerPHPFunctions();
$xsl = DOMDocument::load('../xsl/add_to_compare.xsl');
$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
