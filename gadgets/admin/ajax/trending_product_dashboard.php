<?php	
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');
	require_once(CLASSPATH.'feature.class.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'product.class.php');
	require_once(CLASSPATH.'brand.class.php');
	
	
	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	$feature = new FeatureManagement;
	$category = new CategoryManagement;
	$product = new ProductManagement;
	$brand = new BrandManagement;
	

            
	$trending_product_id = $_REQUEST['lpid'];
	$category_id = $_REQUEST['catid']?$_REQUEST['catid']:'1';
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$r_product_id = $_REQUEST['pid'];
	$r_brand_id = $_REQUEST['bid'];

	if(!empty($category_id)){
		$aParameters=array('category_id'=>$category_id);
		$result = $product->arrGetTrendingProductDetails("","",$category_id,"","",$startlimit,$limitcnt);
	}

	if($_REQUEST['trending_product_id']!=''){
		$trending_product_id = $_REQUEST['trending_product_id'];
	}
	if($_REQUEST['act']=='Delete' && !empty($trending_product_id)){
			$dresult = $product->boolDeleteTrendingProduct($trending_product_id);

	}
	
	if($_REQUEST['act']=='update' && !empty($trending_product_id)){
                
		$rResult =$product->arrGetTrendingProductDetails($trending_product_id,"","","","",$startlimit,$limitcnt);
		#print"<pre>";print_r($rResult);exit;
		$xmlArt='';
			$cnt = sizeof($rResult);
			//$xml = "<ARTICLE>";
			//$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
			//for($i=0;$i<$cnt;$i++){
				$status = $rResult[0]['status'];
				//echo $status."ggggg";
				$categoryid = $rResult[0]['category_id'];
				if(!empty($categoryid)){
					$category_result = $category->arrGetCategoryDetails($categoryid);
				}
				$brand_id = $rResult[0]['brand_id'];
				if(!empty($brand_id)){
					$brand_result = $brand->arrGetBrandDetails($brand_id);
					$brand_name = $brand_result[0]['brand_name'];
				}
				$rResult[0]['js_brand_name'] = $brand_name;
				$rResult[0]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : 'Nil';

				$product_id = $rResult[0]['product_id'];
				if(!empty($product_id)){
					//$product_result =$product->arrGetProductDetails($product_id,$category_id,"","",$startlimit,$limitcnt);
					//$product_name = $product_result[0]['product_name'];
				}

				//$rResult[0]['js_product_name'] =$product_name;
				//$rResult[0]['product_name'] = $product_name ? html_entity_decode($product_name,ENT_QUOTES) : 'Nil';

				$rResult[0]['product_status'] = ($status == 1) ? 'Active' : 'InActive';
				//echo $rResult[0]['article_status']."ccccc";
				$category_name = $category_result[0]['category_name'];
				$rResult[0]['js_category_name'] = $category_name;
				$rResult[0]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
				$rResult[0]['create_date'] = date('d-m-Y',strtotime($rResult[0]['cdate']));
				$rResult[0]['js_feature_name'] = $result[0]['feature_name'];
                                $rResult[0]['position'] = $result[0]['product_position'];

				$rResult[0] = array_change_key_case($rResult[0],CASE_UPPER);
				//print_r($result);
				//print_r($rResult[0]);
				$xmlArt .= "<PRODUCT_DATA>";
				foreach($rResult[0] as $k1=>$v1){
					$xmlArt .= "<$k1><![CDATA[$v1]]></$k1>";
				}
				 $xmlArt .= "</PRODUCT_DATA>";
			//}
			//$xml .= "</ARTICLE>";
	}
	
//print_r($result);
	$cnt = sizeof($result);
        $position_count=$cnt+1;
	$xml = "<PRODUCT_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$brand_id = $result[$i]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id);
			$brand_name = $brand_result[0]['brand_name'];
		}
		$result[$i]['js_brand_name'] = $brand_name;
		$result[$i]['brand_name'] = $brand_name ? html_entity_decode($brand_name,ENT_QUOTES) : 'Nil';
		$model_id = $result[$i]['product_info_id'];
		if(!empty($model_id)){
                        $product_result =$product->arrGetProductNameInfo($model_id);
                        $product_name1 = $product_result[0][product_info_name];
		}
		$product_id = $result[$i]['product_id'];
		$product_names = array();
                if(!empty($product_id)){
                    $product_result = $product->arrGetProductDetails($product_id,$category_id);
                    $product_names[] = $product_result[0]['product_name'];
                    $product_names[] = $product_result[0]['variant'];
		    $product_name = implode(" ",$product_names);
			
                }
		$result[$i]['js_product_name'] =$product_name;
		$result[$i]['product_name'] = $product_name ? html_entity_decode($product_name,ENT_QUOTES) : 'Nil';
		$result[$i]['product_status'] = ($status == 1) ? 'Active' : 'InActive';
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_feature_name'] = $result[$i]['feature_name'];

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		

		$xml .= "<PRODUCT_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</PRODUCT_MASTER_DATA>";
	}
	$xml .= "</PRODUCT_MASTER>";



	if(!empty($category_id)){
		$result = $brand->arrGetBrandDetails("",$category_id);
	}	
	$cnt = sizeof($result);
        $xml .= "<BRAND_MASTER>";
        $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        for($i=0;$i<$cnt;$i++){
                $status = $result[$i]['status'];
                $categoryid = $result[$i]['category_id'];
                if(!empty($categoryid)){
                        $category_result = $category->$result = $category->arrGetCategoryDetails($categoryid);
                }
                $category_name = $category_result[0]['category_name'];
                $result[$i]['js_category_name'] = $category_name;
                $result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
                $result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
                $result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
                $result[$i]['js_brand_name'] = $result[$i]['brand_name'];
                $result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'],ENT_QUOTES);
                $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
                $xml .= "<BRAND_MASTER_DATA>";
                foreach($result[$i] as $k=>$v){
                        $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</BRAND_MASTER_DATA>";
        }
        $xml .= "</BRAND_MASTER>";
	$xml .= "<POSITION_MASTER>";
	for($pc=1;$pc<=$position_count;$pc++){
            $xml .= "<POSITION_MASTER_DATA><POSITION>".$pc."</POSITION></POSITION_MASTER_DATA>";
        }
	$xml .= "</POSITION_MASTER>";
	
	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $xmlArt;
	$strXML .= "</XML>";
	#header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/trending_product_dashboard.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
