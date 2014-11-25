<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'pager.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$product = new ProductManagement();
$brand = new BrandManagement();
$oPager = new Pager;

//print"<pre>";print_r($_REQUEST);print"</pre>";

$featured_compare_id = $_REQUEST['featured_compare_id'];
$category_id = $_REQUEST['catid'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$view_section_id = $_REQUEST['view_section_id'];
$category_id = $category_id ? $category_id : SITE_CATEGORY_ID;


$aFeaturedSectionDet=array("0"=>array("SECTION_ID"=>"FEATURED_COMPARISON_SET","SECTION_NAME"=>"Featured Comparison Set"));


//print "<pre>"; print_r($aArticleSectionDet);
$aFeaturedSectionDetail=arraytoxml($aFeaturedSectionDet,"FEATURED_COMPARISON_SET");
$aFeaturedSectionDetailXML ="<FEATURED_COMPARISON_SET_MASTER>".$aFeaturedSectionDetail."</FEATURED_COMPARISON_SET_MASTER>";

unset($result);
if($_REQUEST['act']=='update' && !empty($view_section_id) && !empty($featured_compare_id)){
	if($view_section_id=="FEATURED_COMPARISON_SET"){
			$result=$product->arrGetFeaturedCompareSetDetails($featured_compare_id,"",$category_id,"");
        }
        $cnt = sizeof($result);
        //print "<pre>"; print_r($result);print"</pre>";exit;
        $status = $result[0]['status'];
        $result[0]['featured_compare_id'] = $result[0]['featured_compare_id'];
        $result[0]['oncars_compare_id'] = $result[0]['oncars_compare_id'];
        $result[0]['ordering'] = $result[0]['ordering'];
        $result[0]['status'] = ($status == 1) ? 'Active' : 'InActive';
        //print "<pre>"; print_r($result[0]);
		$result[0] = array_change_key_case($result[0],CASE_UPPER);
        //print "<pre>"; print_r($result[0]);
        $xml .= "<FEATURED_ONCARS_DATA>";
        foreach($result[0] as $k1=>$v1){
                $xml .= "<$k1><![CDATA[$v1]]></$k1>";
        }
        $xml .= "</FEATURED_ONCARS_DATA>";
}

	if(!empty($category_id)){
        if($view_section_id=="FEATURED_COMPARISON_SET"){			 
		$result=$product->arrGetFeaturedCompareSetDetails("","",$category_id,"");
	}
       	$cnt=count($result);
		
	if($ivideoGalleryCount!=0){
		$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
		$perpage=20;
		$start  = $oPager->findStart($perpage);
		$recordcount=$ivideoGalleryCount;
		$sExtraParam="ajax/related_video_dashboard.php,svideoGalleryDiv,$category_id";
		$jsparams=$start.",".$perpage.",".$sExtraParam;
		$pages= $oPager->findPages($recordcount,$perpage);
		if($pages > 1 ){
			$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"svideoGalleryPagination",$jsparams,"text");
			$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
			$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
			$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
		}
	}
}
    
//print"<pre>";print_r($result);print"</pre>";//exit;
$cnt = sizeof($result);
if(!$cnt){$cnt = 0;}
$ordering_cnt = $cnt+1;
//if(is_array($result)){
	$xml .= "<FEATURED_COMPARISON_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($orcnt=1;$orcnt<=$ordering_cnt;$orcnt++){
		$xml .= "<FEATURED_COMPARISON_ORDERING>";
		$xml .= "<FEATURED_COMPARISON_ORDERING_DATA>".$orcnt."</FEATURED_COMPARISON_ORDERING_DATA>";
		$xml .= "</FEATURED_COMPARISON_ORDERING>";
	}
	
	//print_r($result);
	for($i=0;$i<$cnt;$i++){
		$product_ids = array();
		$result[$i]['featured_compare_id'] = $result[$i]['featured_compare_id'];
		$oncars_compare_id="";
		$oncars_compare_id = $result[$i]['oncars_compare_id'];
		if(!empty($oncars_compare_id) && $oncars_compare_id != '0'){
			unset($compare_set_result);
			$compare_set_result = $product->arrGetProductCompareCompetitorDetails($oncars_compare_id,"","",$category_ids,"",$startlimit,$cnt);
			//$compare_set_result = $product->arrGetOncarsComarrGetProductCompareCompetitorDetailspareSetDetails($oncars_compare_id,$category_id,"","","");
			 //print "<pre>"; print_r($compare_set_result);
			$product_ids[] = $compare_set_result[0]["product_id"];
			$product_ids[] = $compare_set_result[0]["product_ids"];
	        $productName1 = "";
	         //print "<pre>"; print_r($product_ids);
			if(!empty($product_ids) && $product_ids!=0){
         		       $productidsarr = $product_ids;
				for($j=0;$j<sizeof($productidsarr);$j++){
	        	                $productid = $productidsarr[$j];
        	        	        $prod_result =$product->arrGetProductDetails($productid,$category_id,"","","","","","","","");
	        	                if(is_array($prod_result)){
                	        	        $sProductName=$prod_result[0]['product_name'];
                        	        	$iProductId=$prod_result[0]['product_id'];
	                                	$iBrandId=$prod_result[0]['brand_id'];
		                                unset($brand_result);
        		                        $brand_result = $brand->arrGetBrandDetails($iBrandId,$category_id);
						//print"<pre>";print_r($brand_result);
                	        	        $brand_name = $brand_result[0]['brand_name'];
                        	        	$sVariant=$prod_result[0]['variant'];
	                                	$productName=$brand_name." " .$sProductName." ".$sVariant;
		                                $brand_name="";
        		                        $productName1 .=$productName." v/s ";
                		        }
	                	}
		                $product_name1=substr($productName1 , 0 , -5);
	        	}
		}else{
			$product_name1="";
		}
               	$result[$i]['title'] = $product_name1 ? html_entity_decode($product_name1,ENT_QUOTES,'UTF-8') : '';
		$result[$i]['category_id'] = $result[$i]['category_id'];
		$result[$i]['status'] = $result[$i]['status'];
		$result[$i]['ordering'] = $result[$i]['ordering'];
		$status = $result[$i]['status'];
        	$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['update_date'] = date('d-m-Y',strtotime($result[$i]['update_date']));
		
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	       
        	$xml .= "<FEATURED_COMPARISON_MASTER_DATA>";
	        foreach($result[$i] as $k=>$v){
        	        $xml .= "<$k><![CDATA[$v]]></$k>";
	        }
        	$xml .= "</FEATURED_COMPARISON_MASTER_DATA>";
	}
	$xml .= "</FEATURED_COMPARISON_MASTER>";
//}
unset($result);
//if(!empty($view_section_id)){
	$result = $product->arrGetProductCompareCompetitorDetails("","","",$category_id,"","","");
//}
//print"<pre>";print_r($result);print"</pre>"; die("sasasa");
$cnt = sizeof($result);
if(!$cnt){$cnt = 0;} 
$xml .= "<ONCARS_COMPARE_SET_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
	$productids  = array();
	$result[$i]["competitor_product_id"] = $result[$i]["competitor_product_id"];
	$productids[] = $result[$i]["product_id"];
	$productids[] = $result[$i]["product_ids"];
	$product_name = "";
	
	if(!empty($productids) && sizeof($productids)>0){
		$productids_arr = $productids;
		for($j=0;$j<sizeof($productids_arr);$j++){
			$productName = "";
			 $productid = $productids_arr[$j];
			unset($prod_result);

	        	$prod_result =$product->arrGetProductDetails($productid,$category_id,"","","","","","","","");
	        	if(is_array($prod_result)){
	        	        $sProductName=$prod_result[0]['product_name'];
        	        	$iProductId=$prod_result[0]['product_id'];
	                	$iBrandId=$prod_result[0]['brand_id'];
		                unset($brand_result);
        		        $brand_result = $brand->arrGetBrandDetails($iBrandId,$category_id);
		                $brand_name = $brand_result[0]['brand_name'];
        		        $sVariant=$prod_result[0]['variant'];
                		$productName=$brand_name." " .$sProductName." ".$sVariant;
	                	$brand_name="";
	        	        $product_name .=$productName.",";
			}
		}
		$product_name=substr($product_name , 0 , -1);
	}
	$result[$i]['title'] = $product_name ? html_entity_decode($product_name,ENT_QUOTES,'UTF-8') : 'Nil';

	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	$xml .= "<ONCARS_COMPARE_SET_MASTER_DATA>";
        foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
        }
        $xml .= "</ONCARS_COMPARE_SET_MASTER_DATA>";
}
$xml .= "</ONCARS_COMPARE_SET_MASTER>";

$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<VIEW_SECTION_ID><![CDATA[$view_section_id]]></VIEW_SECTION_ID>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= $config_details;
$strXML .= $aFeaturedSectionDetailXML;
$strXML .= $xml;
$strXML .= $nodesPaging;
$strXML .= "<WALLCNT><![CDATA[$iRelUploadCnt]]></WALLCNT>";
$strXML .= "<VIEWSECTION><![CDATA[$view_section_id]]></VIEWSECTION>";
$strXML .= "<ARTICLETYPE><![CDATA[$type_selecetd]]></ARTICLETYPE>";
$strXML .= "</XML>";

//$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('../xsl/featured_oncars_comparison_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
