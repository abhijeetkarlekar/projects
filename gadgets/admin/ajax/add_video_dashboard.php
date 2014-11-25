<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'videos.class.php');
require_once(CLASSPATH.'pager.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$product = new ProductManagement;
$brand = new BrandManagement;
$oVideos = new videos;
$oPager = new Pager;

//print "<pre>"; print_r($_REQUEST);
$category_id = $_REQUEST['catid'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";
$product_info_id =$_REQUEST['product_info_id'] ? $_REQUEST['product_info_id']  : $_REQUEST['product_info_id'];
$video_id =$_REQUEST['video_id'] ? $_REQUEST['video_id']  : $_REQUEST['video_id'];
$type_selected=$_REQUEST['type_id'] ? $_REQUEST['type_id'] : $_REQUEST['video_type_id'];

if($_REQUEST['act']=='update' && !empty($product_info_id)){
	
	$result = $oVideos->getVideosDetails("","","","",$product_info_id,$category_id,"","");
	//print "<pre>"; print_r($result);print"</pre>";exit;
	$cnt = sizeof($result);
	$iRelUploadCnt=$cnt;
	$xml_videodet .= "<VIDEO_DETAIL>";
	$xml_videodet .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
		$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));

		$brand_id = $result[$i]['brand_id'];
		if(!empty($brand_id)){
			$brand_result = $brand->arrGetBrandDetails($brand_id,$category_id);
		}
		$brand_name = $brand_result[0]['brand_name'];
		$result[$i]['js_brand_name'] = $brand_name;
		$result[$i]['brand_name'] = html_entity_decode($brand_name,ENT_QUOTES,'UTF-8');
		if($result[$i]['product_id']!=''){
			$product_id = $result[$i]['product_id'];
			if(!empty($product_id)){
				$product_result = $product->arrGetProductDetails($product_id,$category_id,"","","","","");
			}
			$product_name = $product_result[0]['product_name'];
			$product_variant = $product_result[0]['variant'];
			$result[$i]['js_product_name'] = $product_name;
			$result[$i]['product_name'] = html_entity_decode($product_name,ENT_QUOTES,'UTF-8');
			$result[$i]['product_variant'] = html_entity_decode($product_variant,ENT_QUOTES,'UTF-8');
		}
		if($result[$i]['product_info_id']!=''){
			$product_info_id = $result[$i]['product_info_id'];
			if(!empty($product_info_id)){
				$product_info_result = $product->arrGetProductNameInfo($product_info_id,$category_id);
				$product_info_name = $product_info_result[0]['product_info_name'];
			}
			$result[$i]['js_product_name'] = $product_info_name;
			$result[$i]['product_name'] = html_entity_decode($product_name,ENT_QUOTES,'UTF-8');
		}

		

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		//print "<pre>"; print_r($result[$i]);
		$xml_videodet .= "<VIDEO_DETAIL_DATA>";
		foreach($result[$i] as $k=>$v){
		$xml_videodet .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml_videodet .= "</VIDEO_DETAIL_DATA>";
		$brand_name="";$product_name="";$product_info_name="";$product_variant="";
		unset($brand_result);
		unset($product_result);
		unset($product_info_result);
	}
	$xml_videodet .= "</VIDEO_DETAIL>";
}


unset($result);
if(!empty($category_id)){
	
	//if($type_selected == ""){$type_selected = Array(3,4);}
	$oVideoscnt = $oVideos->arrGetProductVideosDetailsCnt("","","","",$category_id,"","","","","");
	///$oVideoscnt = $oVideos->getVideosDetailsCount("","",$type_selected,"","",$category_id,"","","","","order by V.create_date desc");
	//print_r($oVideoscnt); //die();
	$ioVideosCount=($oVideoscnt[0]['cnt']);

	if($ioVideosCount!=0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage=20;
	$start  = $oPager->findStart($perpage);
	$recordcount=$ioVideosCount;
	$sExtraParam="ajax/add_video_dashboard.php,soVideosDiv,$category_id,$type_selected";
	$jsparams=$start.",".$perpage.",".$sExtraParam;
	$pages= $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"soVideosPagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}
	 //$orderby=" order by create_date desc";
	 $result = $oVideos->arrGetProductVideosDetails("","","","",$category_id,"","",$start,$perpage,"create_date");
	 ////////////$result = $oVideos->getVideosDetails("","",$type_selected,"","",$category_id,"","",$start,$perpage,"order by V.create_date desc");

	
	
	}

}
$cnt = sizeof($result);
//print "<pre>"; print_r($result);
$xml_video .= "<VIDEO_MASTER>";
$xml_video .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
  $sr_no = ($perpage*($page-1))+($i+1);	
	$result[$i]['sr_no'] = $sr_no;
	$status = $result[$i]['status'];
	$categoryid = $result[$i]['category_id'];
	if(!empty($categoryid)){
		$category_result = $category->arrGetCategoryDetails($categoryid);
	}
	$category_name = $category_result[0]['category_name'];
	$result[$i]['js_category_name'] = $category_name;
	$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
	$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));

	$brand_id = $result[$i]['brand_id'];
	if(!empty($brand_id)){
		$brand_result = $brand->arrGetBrandDetails($brand_id,$category_id);
	}
	$brand_name = $brand_result[0]['brand_name'];
	$result[$i]['js_brand_name'] = $brand_name;
	$result[$i]['brand_name'] = html_entity_decode($brand_name,ENT_QUOTES,'UTF-8');
	
	if($result[$i]['product_id']!='0'){
		$product_id = $result[$i]['product_id'];
		if(!empty($product_id)){
			$product_result = $product->arrGetProductDetails($product_id,$category_id,"","","","","");
		}
		$product_name = $product_result[0]['product_name'];
		$product_variant = $product_result[0]['variant'];
		$result[$i]['js_product_name'] = $product_name;
		$result[$i]['product_name'] = html_entity_decode($product_name,ENT_QUOTES,'UTF-8');
		$result[$i]['product_variant'] = html_entity_decode($product_variant,ENT_QUOTES,'UTF-8');
	}
	
	if($result[$i]['product_info_id']!='0'){
		$product_info_id = $result[$i]['product_info_id'];
		if(!empty($product_info_id)){
			$product_info_result = $product->arrGetProductNameInfo($product_info_id,$category_id);
			//print_r($product_iinfo_result);
			$product_info_name = $product_info_result[0]['product_info_name'];
		}
		$result[$i]['js_product_name'] = $product_info_name;
		$result[$i]['product_name'] = html_entity_decode($product_info_name,ENT_QUOTES,'UTF-8');
	}
	
		if($result[$i]['type_id']!=''){
			$type_id = $result[$i]['type_id'];
			if(!empty($type_id)){
				//$type_id_result = $oVideos->arrGetVideoTypeDetails($type_id,"",$category_id);
				$type_id_result = $oVideos->arrGetTabDetails($type_id,"","","");
				//print_r($type_id_result);
				$type_name = $type_id_result[0]['tab_name'];
			}
			$result[$i]['js_type_name'] = $type_name;
			$result[$i]['type_name'] = html_entity_decode($type_name,ENT_QUOTES,'UTF-8');
		}

		if($result[$i]['group_id']!=''){
			$group_id = $result[$i]['group_id'];
			if(!empty($group_id)){
				$group_id_result = $oVideos->arrGetVideoGroupDetails($group_id,"",$category_id);
				//print_r($group_id_result);
				$group_name = $group_id_result[0]['group_name'];
			}
			$result[$i]['js_group_name'] = $group_name;
			$result[$i]['group_name'] = html_entity_decode($group_name,ENT_QUOTES,'UTF-8');
		}
		//echo $product_info_name."====".$product_name."====".$product_variant."SAC<br>";
		$product_info_name=""; $product_variant=""; $product_name='';

	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	//print "<pre>"; print_r($result[$i]);
	$xml_video .= "<VIDEO_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml_video .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml_video .= "</VIDEO_MASTER_DATA>";
	$product_info_name=""; $product_variant=""; $product_name='';
	unset($brand_result);
    unset($product_result);
    unset($product_info_result);
}
$xml_video .= "</VIDEO_MASTER>";
//echo "TEST---".$xml_video;



unset($result);
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
		$category_result = $category->arrGetCategoryDetails($categoryid);
	}
	$category_name = $category_result[0]['category_name'];
	$result[$i]['js_category_name'] = $category_name;
	$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES,'UTF-8');
	$result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
	$result[$i]['js_brand_name'] = $result[$i]['brand_name'];
	$result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'],ENT_QUOTES,'UTF-8');
	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	$xml .= "<BRAND_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml .= "</BRAND_MASTER_DATA>";
}
$xml .= "</BRAND_MASTER>";


if(!empty($category_id)){
	//$result = $oVideos->arrGetVideoTypeDetails("","",$category_id,"1");
	$result = $oVideos->arrGetTabDetails("","","","",1);
}	
#print "<pre>";
#print_r($result);
$cnt = sizeof($result);
$xml_type .= "<TYPE_MASTER>";
$xml_type .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
	if($result[$i]['tab_name']!='Most Popular' && $result[$i]['tab_name']!='Reviews' && $result[$i]['tab_name']!='News' && $result[$i]['tab_name']!='Hindi' && $result[$i]['tab_name']!='DIY/Maintainance'){
		$status = $result[$i]['status'];
		//$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['tab_id'] = html_entity_decode($result[$i]['tab_id'],ENT_QUOTES,'UTF-8');
		$result[$i]['tab_name'] = html_entity_decode($result[$i]['tab_name'],ENT_QUOTES,'UTF-8');
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml_type .= "<TYPE_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml_type .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml_type .= "</TYPE_MASTER_DATA>";
	}
}
$xml_type .= "</TYPE_MASTER>";


if(!empty($category_id)){
	$result = $oVideos->arrGetVideoGroupDetails("","",$category_id);
}

$cnt = sizeof($result);
$xml_type1 .= "<GTYPE_MASTER>";
$xml_type1 .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
	$status = $result[$i]['status'];
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
	$result[$i]['group_name'] = html_entity_decode($result[$i]['group_name'],ENT_QUOTES,'UTF-8');
	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	$xml_type1 .= "<GTYPE_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml_type1 .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml_type1 .= "</GTYPE_MASTER_DATA>";
}
$xml_type1 .= "</GTYPE_MASTER>";


$iRelUploadCnt= $iRelUploadCnt ? $iRelUploadCnt :1;
$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= $xml_type1;
$strXML .= $xml_type;
$strXML .= $xml_video;
$strXML .= $xml_videodet;
$strXML .= $nodesPaging;
$strXML .= "<WALLCNT><![CDATA[$iRelUploadCnt]]></WALLCNT>";
$strXML .= "<SELECTED_TYPE><![CDATA[$type_selected]]></SELECTED_TYPE>";
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }


$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('../xsl/add_video_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
