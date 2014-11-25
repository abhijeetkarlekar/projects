<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'wallpaper.class.php');
require_once(CLASSPATH.'videos.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$product = new ProductManagement;
$brand = new BrandManagement;
$oWallpaper = new Wallpapers;
$oVideos = new videos;


//print "<pre>"; print_r($_REQUEST);print"</pre>";exit;
$category_id = $_REQUEST['catid'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";
$slideshow_id =$_REQUEST['slideshow_id'] ? $_REQUEST['slideshow_id']  : $_REQUEST['slideshow_id'];
$product_slide_id = $_REQUEST['product_slide_id'];

if($_REQUEST['act']=='update' && !empty($product_slide_id)){
	$result = $oWallpaper->arrGetProductSlideDetails($product_slide_id,"","","",$category_id,"","","","","");
	//print "<pre>"; print_r($result);exit;
	$cnt = sizeof($result);
	$xml_wallpaperdet .= "<SLIDESHOW_DETAIL>";
	$xml_wallpaperdet .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid,"","","","");
		}
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
		$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
			
		$brand_id = $result[$i]['brand_id'];
		$result[$i]['brand_id'] = $brand_id;
                if(!empty($brand_id)){
                        $brand_result = $brand->arrGetBrandDetails($brand_id,$category_id,"","","");
                }
		$brand_name = $brand_result[0]['brand_name'];
                $result[$i]['js_brand_name'] = $brand_name;
                $result[$i]['brand_name'] = html_entity_decode($brand_name,ENT_QUOTES);
                if($result[$i]['product_id']!=''){
                        $product_id = $result[$i]['product_id'];
			$result[$i]['product_id'] = $product_id;
                        if(!empty($product_id)){
                                $product_result = $product->arrGetProductDetails($product_id,$category_id,"","","","","","","");
                        }
                        $product_name = $product_result[0]['product_name'];
                        $product_variant = $product_result[0]['variant'];
                        $result[$i]['js_product_name'] = $product_name;
                        $result[$i]['product_name'] = html_entity_decode($product_name,ENT_QUOTES);
                        $result[$i]['product_variant'] = html_entity_decode($product_variant,ENT_QUOTES);
                }
                if($result[$i]['product_info_id']!=''){
                        $product_info_id = $result[$i]['product_info_id'];
			$result[$i]['product_info_id'] = $product_info_id;
                        if(!empty($product_info_id)){
				$product_info_result = $product->arrGetProductNameInfo($product_info_id,$category_id,"","","","","","","","","","","1");
				if(sizeof($product_info_result) <= 0){
                                	$product_info_result = $product->arrGetProductNameInfo($product_info_id,$category_id,"","","","","");
				}
                                $product_info_name = $product_info_result[0]['product_info_name'];
                        }
                        $result[$i]['js_product_name'] = $product_info_name;
                        $result[$i]['product_name'] = html_entity_decode($product_name,ENT_QUOTES);
                }
		$product_slide_id = $result[$i]['product_slide_id'];
		if(!empty($product_slide_id)){
			$result[$i]['product_slide_id'] = $product_slide_id;
			if(!empty($product_slide_id)){
                                $product_info_result = $oWallpaper->arrGetProductSlideDetails($product_slide_id,"","","","","","","","","");
				//print "<pre>"; print_r($product_info_result);print"</pre>";exit;	
                                $product_slide_title = $product_info_result[0]['title'];
                                $product_slide_abstract = $product_info_result[0]['abstract'];
                                $product_slide_media_id = $product_info_result[0]['media_id'];
                                $product_slide_media_path = $product_info_result[0]['media_path'];
                        }
                        $result[$i]['product_slide_media_id'] = $product_slide_media_id;
                        $result[$i]['product_slide_abstract'] = $product_slide_abstract;
                        $result[$i]['product_slide_media_path'] = $product_slide_media_path;
                        $result[$i]['product_title'] = html_entity_decode($product_slide_title,ENT_QUOTES);
		}

		if(!empty($product_slide_id)){
			$sResult = $oWallpaper->arrGetSlideShowDetails("","","",$product_slide_id,$category_id,"","","","","");
                	//print "<pre>"; print_r($sResult);exit;
			//slideshow_id category_id brand_id product_id product_info_id product_slide_id title tags type_id group_id [meta_description video_img_id video_img_path [media_id media_path content_type is_media_process  status
        	        $iRelUploadCnt=count($sResult);
	                if(is_array($sResult)){
                	        foreach($sResult as $iKey=>$aMediaData){
                                	$aUploadSlideData[$iKey]=$aMediaData;
        	                }
	                }
                	//print "<pre>"; print_r($aUploadSlideData);exit;
                	$sSlideDataDet=arraytoxml($aUploadSlideData,"SLIDE_UPLOAD_DATA");
        	        $sSlideDetXML ="<SLIDE_UPLOAD_DETAIL>".$sSlideDataDet."</SLIDE_UPLOAD_DETAIL>";
			//echo $sSlideDetXML; exit;
	        }	

		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		//print "<pre>"; print_r($result[$i]);
		$xml_wallpaperdet .= "<SLIDESHOW_DETAIL_DATA>";
		foreach($result[$i] as $k=>$v){
		$xml_wallpaperdet .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml_wallpaperdet .= "</SLIDESHOW_DETAIL_DATA>";
	}
	$xml_wallpaperdet .= "</SLIDESHOW_DETAIL>";
}


unset($result);
//echo "category_id==".$category_id;exit;
if(!empty($category_id)){
	//$result = $oWallpaper->arrGetSlideShowDetails("","","","",$category_id,"","");
	$result = $oWallpaper->arrGetProductSlideDetails("","","","",$category_id,"","","","","create_date");

}
//print"<pre>";print_r($result);print"</pre>";exit;
$cnt = sizeof($result);
$xml_wallpaper .= "<SLIDESHOW_MASTER>";
$xml_wallpaper .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){

	$status = $result[$i]['status'];
	$categoryid = $result[$i]['category_id'];
	$brand_id = $result[$i]['brand_id'];
	$product_id = $result[$i]['product_id'];
	$product_info_id = $result[$i]['product_info_id'];
	$product_slide_id = $result[$i]['product_slide_id'];
	if(!empty($product_slide_id)){$result[$i]['product_slide_id'] = $product_slide_id;}

	if(!empty($categoryid)){
	$category_result = $category->arrGetCategoryDetails($categoryid);
	}
	$category_name = $category_result[0]['category_name'];
	$result[$i]['js_category_name'] = $category_name;
	$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES);
	$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
	$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));

	if(!empty($product_slide_id)){$result[$i]['product_slide_id'] = $product_slide_id;}

	$brand_id = $result[$i]['brand_id'];
	$result[$i]['brand_id'] = $brand_id;
	if(!empty($brand_id)){
		$brand_result = $brand->arrGetBrandDetails($brand_id,$category_id);
	}
	$brand_name = $brand_result[0]['brand_name'];
	$result[$i]['js_brand_name'] = $brand_name;
	$result[$i]['brand_name'] = html_entity_decode($brand_name,ENT_QUOTES);
	if($result[$i]['product_id']!=''){
		$product_id = $result[$i]['product_id'];
		$result[$i]['product_id'] = $product_id;
		if(!empty($product_id)){
		$product_result = $product->arrGetProductDetails($product_id,$category_id,"","","","","","","");
		}
		$product_name = $product_result[0]['product_name'];
		$product_variant = $product_result[0]['variant'];
		$result[$i]['js_product_name'] = $product_name;
		$result[$i]['product_name'] = html_entity_decode($product_name,ENT_QUOTES);
		$result[$i]['product_variant'] = html_entity_decode($product_variant,ENT_QUOTES);
	}

	if($result[$i]['product_info_id']!=''){
		$product_info_id = $result[$i]['product_info_id'];
		$result[$i]['product_info_id'] = $product_info_id;
		if(!empty($product_info_id)){
			//echo $product_info_id;
			$product_info_result = $product->arrGetProductNameInfo($product_info_id,$category_id,"","","","","","","","","","","");
	        /*if(sizeof($product_info_result) <= 0){
				$product_info_result = $product->arrGetProductNameInfo($product_info_id,$category_id);
	        }*/
	        //print_r($product_info_result);
			$product_info_name = $product_info_result[0]['product_info_name'];
		}
		$result[$i]['js_product_name'] = $product_info_name;
		$result[$i]['product_name'] = html_entity_decode($product_info_name,ENT_QUOTES);
	}
	if($result[$i]['type_id']!=''){
		$type_id = $result[$i]['type_id'];
		$result[$i]['type_id'] = $type_id;
		if(!empty($type_id)){
		$type_id_result = $oVideos->arrGetVideoTypeDetails($type_id,"",$category_id);
		//print_r($type_id_result);
		$type_name = $type_id_result[0]['type_name'];
		}
		$result[$i]['js_type_name'] = $type_name;
		$result[$i]['type_name'] = html_entity_decode($type_name,ENT_QUOTES);
	}

	if($result[$i]['group_id']!=''){
		$group_id = $result[$i]['group_id'];
		$result[$i]['group_id'] = $group_id;
		if(!empty($group_id)){
		$group_id_result = $oVideos->arrGetVideoGroupDetails($group_id,"",$category_id,"");
		//print_r($group_id_result);
		$group_name = $group_id_result[0]['group_name'];
		}
		$result[$i]['js_group_name'] = $group_name;
		$result[$i]['group_name'] = html_entity_decode($group_name,ENT_QUOTES);
	}
	$product_name=''; $product_variant=''; $product_info_name='';
	$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	//print "<pre>"; print_r($result[$i]);
	$xml_wallpaper .= "<SLIDESHOW_MASTER_DATA>";
	foreach($result[$i] as $k=>$v){
		$xml_wallpaper .= "<$k><![CDATA[$v]]></$k>";
	}
	$xml_wallpaper .= "</SLIDESHOW_MASTER_DATA>";
	$product_info_name=""; $product_variant=""; $product_name='';
	unset($brand_result);
	unset($product_result);
	unset($product_info_result);
}
$xml_wallpaper .= "</SLIDESHOW_MASTER>";
//echo "TEST---".$xml_wallpaper;

unset($result);
if(!empty($category_id)){
        $result = $brand->arrGetBrandDetails("",$category_id);
}
$cnt = sizeof($result);
$xml = "";
$xml .= "<BRAND_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
        $status = $result[$i]['status'];
        $categoryid = $result[$i]['category_id'];
        if(!empty($categoryid)){
                //$category_result = $category->$result = $category->arrGetCategoryDetails($categoryid);
		$category_result = $category->arrGetCategoryDetails($categoryid,"","","","");
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

if(!empty($category_id)){
        $result = $oVideos->arrGetVideoGroupDetails("","",$category_id,"1","0","2");
}
//print"<pre>";print_r($result);print"</pre>";exit;
$cnt = sizeof($result);
$xml_type="";
$xml_type .= "<TYPE_MASTER>";
$xml_type .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
        $status = $result[$i]['status'];
        $result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['icreate_date']));
	$result[$i]['group_id'] = $result[$i]['group_id'];
        $result[$i]['group_name'] = html_entity_decode($result[$i]['group_name'],ENT_QUOTES);
        $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
        $xml_type .= "<TYPE_MASTER_DATA>";
        foreach($result[$i] as $k=>$v){
                $xml_type .= "<$k><![CDATA[$v]]></$k>";
        }
        $xml_type .= "</TYPE_MASTER_DATA>";
}
$xml_type .= "</TYPE_MASTER>";

$iRelUploadCnt= $iRelUploadCnt ? $iRelUploadCnt :0;
$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= $config_details;
$strXML .= $xml_wallpaper;
$strXML .= $xml_wallpaperdet;
$strXML .= $xml;
$strXML .= $xml_type;
$strXML .= $sSlideSectionDetailXML;
$strXML .= $sSlideDetXML;
$strXML .= "<WALLCNT><![CDATA[$iRelUploadCnt]]></WALLCNT>";
$strXML .= "<SELECTED_TYPE><![CDATA[$type_selecetd]]></SELECTED_TYPE>";
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }


$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('../xsl/slideshow_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
