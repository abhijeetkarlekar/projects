<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'videos.class.php');
//require_once(CLASSPATH.'reviews.class.php');
#require_once(CLASSPATH.'article.class.php');

$dbconn = new DbConn;
$category = new CategoryManagement;
$videoGallery = new videos();
//$reviews = new reviews(); 
#$article = new article();

//print"<pre>";print_r($_REQUEST);print"</pre>";

$video_id = $_REQUEST['vid'];
$category_id = $_REQUEST['catid'];
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$r_product_id = $_REQUEST['pid'];
$r_brand_id = $_REQUEST['bid'];
$view_section_id = $_REQUEST['view_section_id'];
$type_selecetd=$_REQUEST['video_type_id'] ? $_REQUEST['video_type_id'] :0;
$category_id = $category_id ? $category_id:1;


$aVideoSectionDet=array("0"=>array("SECTION_ID"=>"MOST_POPULAR_VIDEOS","SECTION_NAME"=>"Most Popular"),"1"=>array("SECTION_ID"=>"RELATED_VIDEOS","SECTION_NAME"=>"Related"),"2"=>array("SECTION_ID"=>"FEATURED_VIDEOS","SECTION_NAME"=>"Featured"));
//$aVideoSectionDet=array("0"=>array("SECTION_ID"=>"FEATURED_VIDEOS","SECTION_NAME"=>"Featured"));


//print "<pre>"; print_r($aArticleSectionDet);
$sVideoSectionDetail=arraytoxml($aVideoSectionDet,"VIDEO_SECTION");
$sVideoSectionDetailXML ="<VIDEO_SECTION_MASTER>".$sVideoSectionDetail."</VIDEO_SECTION_MASTER>";

if($_REQUEST['act']=='update' && !empty($view_section_id)){
	if($view_section_id=="MOST_POPULAR_VIDEOS"){
                $rResult=$videoGallery->arrGetMostPopularVideosDetails($video_id,"",$category_id,"","","","","");
        }
        if($view_section_id=="RELATED_VIDEOS"){
                $rResult = $videoGallery->arrGetRelatedVideosDetails("",$video_id,"",$category_id,"","","","");
        }
        if($view_section_id=="FEATURED_VIDEOS"){
                $rResult = $videoGallery->arrGetFeaturedVideosDetails("",$video_id,"",$category_id,"",'','',"");
        }
	$xmlVid='';
        $cnt = sizeof($rResult);
        //print "<pre>"; print_r($rResult);print"</pre>";exit;
        $status = $rResult[0]['status'];
        if(!empty($categoryid)){ 
                $category_result = $category->arrGetCategoryDetails($categoryid);
        }

        $rResult[0]['video_id'] = $rResult[0]['video_id'];
        $rResult[0]['js_title'] = $rResult[0]['title'];
        $rResult[0]['title'] = $rResult[0]['title'] ? html_entity_decode($rResult[0]['title'],ENT_QUOTES) : 'Nil';

        $rResult[0]['status'] = ($status == 1) ? 'Active' : 'InActive';
        //print "<pre>"; print_r($rResult[0]);
	$rResult[0] = array_change_key_case($rResult[0],CASE_UPPER);
        $xmlVid .= "<VIDEO_DATA>";
        foreach($rResult[0] as $k1=>$v1){
                $xmlVid .= "<$k1><![CDATA[$v1]]></$k1>";
        }
        $xmlVid .= "</VIDEO_DATA>";
}

if(!empty($category_id)){
        if($view_section_id=="MOST_POPULAR_VIDEOS"){
		$result=$videoGallery->arrGetMostPopularVideosDetails("","",$category_id,"","","","","");
        }
        if($view_section_id=="RELATED_VIDEOS"){
		$result = $videoGallery->arrGetRelatedVideosDetails("","","",$category_id,"","","","");
        }
        if($view_section_id=="FEATURED_VIDEOS"){
		$result = $videoGallery->arrGetFeaturedVideosDetails("","","",$category_id,"",'','',"create_date");
        }
}
//print"<pre>";print_r($result);print"</pre>";exit;
$cnt = sizeof($result);
if(!$cnt){$cnt = 0;}
//if(is_array($result)){
	$xml = "<VIDEO_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$result[$i]['video_id'] = $result[$i]['video_id'];
		$result[$i]['title'] = $result[$i]['title'];
		$result[$i]['tags'] = $result[$i]['tags'];
		$result[$i]['media_id'] = $result[$i]['media_id'];
		$result[$i]['media_path'] = $result[$i]['media_path'];
		$result[$i]['video_img_id'] = $result[$i]['video_img_id'];
		$result[$i]['video_img_path'] = $result[$i]['video_img_path'];
		$result[$i]['content_type'] = $result[$i]['content_type'];
		$result[$i]['is_media_process'] = $result[$i]['is_media_process'];
		$status = $result[$i]['status'];
        	$result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['update_date'] = date('d-m-Y',strtotime($result[$i]['update_date']));
		
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
	        //print "<pre>"; print_r($result[$i]);
        	$xml .= "<VIDEO_MASTER_DATA>";
	        foreach($result[$i] as $k=>$v){
        	        $xml .= "<$k><![CDATA[$v]]></$k>";
	        }
        	$xml .= "</VIDEO_MASTER_DATA>";
	}
	$xml .= "</VIDEO_MASTER>";
//}

$result_list = $videoGallery->getVideosDetails("","","","","",$category_id,"","","","","");
//print"<pre>";print_r($result_list);print"</pre>";exit;
$cnt = sizeof($result_list);
if(is_array($result_list)){
        $vxml = "<VIDEO_DETAILS_MASTER>";
        $vxml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        for($i=0;$i<$cnt;$i++){
                $result_list[$i]['video_id'] = $result_list[$i]['video_id'];
                $result_list[$i]['title'] = $result_list[$i]['title'];
                $result_list[$i]['tags'] = $result_list[$i]['tags'];
                $result_list[$i]['media_id'] = $result_list[$i]['media_id'];
                $result_list[$i]['media_path'] = $result_list[$i]['media_path'];
                $result_list[$i]['video_img_id'] = $result_list[$i]['video_img_id'];
                $result_list[$i]['video_img_path'] = $result_list[$i]['video_img_path'];
                $result_list[$i]['content_type'] = $result_list[$i]['content_type'];
                $result_list[$i]['is_media_process'] = $result_list[$i]['is_media_process'];
                $result_list[$i]['status'] = $result_list[$i]['status'];
                $result_list[$i]['create_date'] = date('d-m-Y',strtotime($result_list[$i]['disp_date']));
                $result_list[$i]['update_date'] = date('d-m-Y',strtotime($result_list[$i]['update_date']));

                $result_list[$i] = array_change_key_case($result_list[$i],CASE_UPPER);
                //print "<pre>"; print_r($result_list[$i]);
                $vxml .= "<VIDEO_DETAILS_MASTER_DATA>";
                foreach($result_list[$i] as $k=>$v){
                        $vxml .= "<$k><![CDATA[$v]]></$k>";
                }
                $vxml .= "</VIDEO_DETAILS_MASTER_DATA>";
        }
        $vxml .= "</VIDEO_DETAILS_MASTER>";
}

$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<VIEW_SECTION_ID><![CDATA[$view_section_id]]></VIEW_SECTION_ID>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= $config_details;
$strXML .= $sVideoSectionDetailXML;
$strXML .= $xml;
$strXML .= $vxml;
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
$xsl = DOMDocument::load('../xsl/related_video_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
