<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'wallpaper.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$oWallpaper = new Wallpapers;

$product_slide_id = $_REQUEST['product_slide_id'];
$category_id = $_REQUEST['catid'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$r_product_id = $_REQUEST['pid'];
$r_brand_id = $_REQUEST['bid'];
$view_section_id = $_REQUEST['view_section_id'];
$type_selecetd=$_REQUEST['slide_type_id'] ? $_REQUEST['slide_type_id'] :0;
$category_id = $category_id ? $category_id:1;


$aSlideSectionDet=array("0"=>array("SECTION_ID"=>"FEATURED_SLIDES","SECTION_NAME"=>"Featured Slides"));


//print "<pre>"; print_r($aArticleSectionDet);
$sSlideSectionDetail=arraytoxml($aSlideSectionDet,"SLIDE_SECTION");
$sSlideSectionDetailXML ="<SLIDE_SECTION_MASTER>".$sSlideSectionDetail."</SLIDE_SECTION_MASTER>";

if($_REQUEST['act']=='update' && !empty($view_section_id)){
	if($view_section_id=="FEATURED_SLIDES"){
                $rResult=$oWallpaper->arrGetFeaturedSlidesDetails("",$product_slide_id,$category_id,"","","","");
        }
	$xmlSid='';
        $cnt = sizeof($rResult);
        //print "<pre>"; print_r($rResult);print"</pre>";exit;
        $status = $rResult[0]['status'];
        $rResult[0]['product_slide_id'] = $rResult[0]['product_slide_id'];
        $rResult[0]['js_title'] = $rResult[0]['title'];
        $rResult[0]['title'] = $rResult[0]['title'] ? html_entity_decode($rResult[0]['title'],ENT_QUOTES) : 'Nil';

        $rResult[0]['status'] = ($status == 1) ? 'Active' : 'InActive';
        //print "<pre>"; print_r($rResult[0]);
	$rResult[0] = array_change_key_case($rResult[0],CASE_UPPER);
        $xmlVid .= "<SLIDE_DATA>";
        foreach($rResult[0] as $k1=>$v1){
                $xmlVid .= "<$k1><![CDATA[$v1]]></$k1>";
        }
        $xmlVid .= "</SLIDE_DATA>";
}

if(!empty($category_id)){
        if($view_section_id=="FEATURED_SLIDES"){
		$result=$oWallpaper->arrGetFeaturedSlidesDetails("","",$category_id,"","","","");
        }
}
//print"<pre>";print_r($result);print"</pre>";exit;
$cnt = sizeof($result);
if(!$cnt){$cnt = 0;}
//if(is_array($result)){
	$xml = "<SLIDE_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$result[$i]['product_slide_id'] = $result[$i]['product_slide_id'];
		$result[$i]['title'] = $result[$i]['title'];
		$result[$i]['group_id'] = $result[$i]['group_id'];
		$result[$i]['category_id'] = $result[$i]['category_id'];
		$result[$i]['brand_id'] = $result[$i]['brand_id'];
		$result[$i]['product_info_id'] = $result[$i]['product_info_id'];
		$result[$i]['product_id'] = $result[$i]['product_id'];
		$result[$i]['media_id'] = $result[$i]['media_id'];
		$result[$i]['media_path'] = $result[$i]['media_path'];
		$status = $result[$i]['status'];
		$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['update_date'] = date('d-m-Y',strtotime($result[$i]['update_date']));
		
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	        //print "<pre>"; print_r($result[$i]);
        	$xml .= "<SLIDE_MASTER_DATA>";
	        foreach($result[$i] as $k=>$v){
        	        $xml .= "<$k><![CDATA[$v]]></$k>";
	        }
        	$xml .= "</SLIDE_MASTER_DATA>";
	}
	$xml .= "</SLIDE_MASTER>";
//}

unset($result);
$result_list = $oWallpaper->arrGetProductSlideDetails("","","","",$category_id,"","","","","");
//print"<pre>";print_r($result_list);print"</pre>";exit;
$cnt = sizeof($result_list);
if(is_array($result_list)){
        $sxml = "<SLIDE_DETAILS_MASTER>";
        $sxml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        for($i=0;$i<$cnt;$i++){
                $result_list[$i]['product_slide_id'] = $result_list[$i]['product_slide_id'];
                $result_list[$i]['title'] = $result_list[$i]['title'];
		$result[$i]['group_id'] = $result[$i]['group_id'];
                $result[$i]['category_id'] = $result[$i]['category_id'];
                $result[$i]['brand_id'] = $result[$i]['brand_id'];
                $result[$i]['product_info_id'] = $result[$i]['product_info_id'];
                $result[$i]['product_id'] = $result[$i]['product_id'];
                $result_list[$i]['media_id'] = $result_list[$i]['media_id'];
                $result_list[$i]['media_path'] = $result_list[$i]['media_path'];
                $result_list[$i]['status'] = $result_list[$i]['status'];
                $result_list[$i]['create_date'] = date('d-m-Y',strtotime($result_list[$i]['disp_date']));
                $result_list[$i]['update_date'] = date('d-m-Y',strtotime($result_list[$i]['update_date']));

                $result_list[$i] = array_change_key_case($result_list[$i],CASE_UPPER);
                //print "<pre>"; print_r($result_list[$i]);
                $sxml .= "<SLIDE_DETAILS_MASTER_DATA>";
                foreach($result_list[$i] as $k=>$v){
                        $sxml .= "<$k><![CDATA[$v]]></$k>";
                }
                $sxml .= "</SLIDE_DETAILS_MASTER_DATA>";
        }
        $sxml .= "</SLIDE_DETAILS_MASTER>";
}

$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<VIEW_SECTION_ID><![CDATA[$view_section_id]]></VIEW_SECTION_ID>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= $config_details;
$strXML .= $sSlideSectionDetailXML;
$strXML .= $sxml;
$strXML .= $xml;
$strXML .= $xmlVid;
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
$xsl = DOMDocument::load('../xsl/add_slides_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
