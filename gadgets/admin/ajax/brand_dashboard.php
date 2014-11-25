<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'brand.class.php');
	require_once(CLASSPATH.'category.class.php');
	require_once(CLASSPATH.'pager.class.php');

	$dbconn = new DbConn;
	$brand = new BrandManagement;
	$category = new CategoryManagement;
	$oPager = new Pager;

	$category_id = $_REQUEST['catid'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$brand_id = $_REQUEST['brand_id'];
	
if($_REQUEST['act']=='update' && !empty($brand_id)){
	$result = $brand->arrGetBrandDetails($brand_id,$category_id,"",$startlimit,$limitcnt,"","","","");
        //print "<pre>"; print_r($result);exit;
        $cnt = sizeof($result);
        $xml .= "<BRAND_DETAIL>";
        $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
        for($i=0;$i<$cnt;$i++){
                $brand_id = $result[$i]['brand_id'];
                $categoryid = $result[$i]['category_id'];
                $brand_name = $result[$i]['brand_name'];
                if($brand_name != ""){$result[$i]['brand_name'] = html_entity_decode($brand_name,ENT_QUOTES, "UTF-8");}
                $short_desc = $result[$i]['short_desc'];
                if($short_desc != ""){$result[$i]['short_desc'] = html_entity_decode($short_desc,ENT_QUOTES, "UTF-8");}
				$long_desc = $result[$i]['long_desc'];
                if($long_desc != ""){$result[$i]['long_desc'] = html_entity_decode($long_desc,ENT_QUOTES, "UTF-8");}
                $status = $result[$i]['status'];
                $result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
                $result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
                $brand_image = $result[$i]["brand_image"];
                $brand_research_image = $result[$i]["brand_research_image"];

				$brand_image_path="";
				$brand_research_image_path="";
				if(!empty($brand_image)){
					$brand_image_path=IMAGE_URL.$brand_image;
				}
				if(!empty($brand_research_image)){
					$brand_research_image_path = IMAGE_URL.$brand_research_image;
				}
				$result[$i]["brand_image_path"] = $brand_image_path;
				$result[$i]["brand_research_image_path"] = $brand_research_image_path;
		
                $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
                //print "<pre>"; print_r($result[$i]);
                $xml .= "<BRAND_DETAIL_DATA>";
                foreach($result[$i] as $k=>$v){
	                $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</BRAND_DETAIL_DATA>";
        }
        $xml .= "</BRAND_DETAIL>";
}	

	unset($result);
	if(!empty($category_id)){
	$Brandcntresult = $brand->arrGetBrandDetails("",$category_id,"","","","","order by create_date desc","","");
	$ibrandItemCount=count($Brandcntresult);
	
	 if($ibrandItemCount!=0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage=20;
	$start  = $oPager->findStart($perpage);
	$recordcount=$ibrandItemCount;
	$sExtraParam="ajax/brand_dashboard.php,sBrandDiv,$category_id";
	$jsparams=$start.",".$perpage.",".$sExtraParam;
	$pages= $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"sBrandPagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}
	$orderby=" order by create_date desc";
	//$result = $product->arrGetProductDetails("",$category_id,"","","","","",$start,$perpage,"",$orderby);
		######$result = $brand->arrGetBrandDetails("",$category_id,"",$start,$perpage,"",$orderby,"","");
		$result = $brand->arrGetBrandDetails("",$category_id,"","","","",$orderby,"","");
	}
	
 }
	//print"<pre>";print_r($result);print"</pre>";exit;
	$cnt = sizeof($result);
	$xml .= "<BRAND_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";	
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$discontinue_status = $result[$i]['discontinue_flag'];
		$upcoming_brand = $result[$i]['upcoming_brand'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($categoryid)){
			$category_result = $category->arrGetCategoryDetails($categoryid);
		}
		$category_name = $category_result[0]['category_name'];
		$result[$i]['js_category_name'] = $category_name;
		$result[$i]['category_name'] = html_entity_decode($category_name,ENT_QUOTES, "UTF-8");
		$result[$i]['brand_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['discontinue_status'] = ($discontinue_status == 1) ? '---' : 'Discontinued';
		$result[$i]['upcoming_brand'] = ($upcoming_brand == 1) ? 'upcoming' : '';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_brand_name'] = $result[$i]['brand_name'];
		$result[$i]['brand_name'] = html_entity_decode($result[$i]['brand_name'],ENT_QUOTES, "UTF-8");
		$short_desc = $result[$i]['short_desc'];
		if($short_desc != ""){$result[$i]['short_desc'] = html_entity_decode($short_desc,ENT_QUOTES, "UTF-8");}
		$long_desc = $result[$i]['long_desc'];
		if($long_desc != ""){$result[$i]['long_desc'] = html_entity_decode($long_desc,ENT_QUOTES, "UTF-8");}
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<BRAND_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</BRAND_MASTER_DATA>";
	}
	$xml .= "</BRAND_MASTER>";

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= $nodesPaging;
	$strXML .= "</XML>";
	$strXML = mb_convert_encoding($strXML, "UTF-8");
	if($_REQUEST["debug"] == "1"){header('Content-type: text/xml');echo $strXML;exit;}
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/brand_dashboard_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
