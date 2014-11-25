<?php	
require_once('../../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'pager.class.php');
require_once(CLASSPATH."feature.class.php");

$dbconn = new DbConn;
$category = new CategoryManagement;
$oProduct = new ProductManagement;
$oBrand = new BrandManagement;
$oPager = new Pager;
$oFeature  = new FeatureManagement;


//print"<pre>";print_r($_REQUEST);print"</pre>";
$product_name_id = $_REQUEST['product_name_id'];
$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : SITE_CATEGORY_ID;
$startlimit = $_REQUEST['startlimit'];
$limitcnt = $_REQUEST['cnt'];
$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : 'Insert';
$search_status = $_REQUEST['search_status'];
if(!empty($category_id)){
	$aBrandDetail=$oBrand->arrGetBrandDetails("",$category_id,"","","","","","","","");
	$sBrandDataDet=arraytoxml($aBrandDetail,"BRAND_MASTER_DATA");
	$sBrandDataDetXML ="<BRAND_MASTER>".$sBrandDataDet."</BRAND_MASTER>";
	if(is_array($aBrandDetail)){
		foreach($aBrandDetail as $ibKey=>$aBrandData){
			$aBrandDetailName[$aBrandData['brand_id']][]=$aBrandData['brand_name'];
		}
	}

	/*
	if($search_status == "upcoming"){
		$oProductcnt = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","","","","","","","",$search_status,"1");
	}elseif($search_status == "discontinue"){
		$oProductcnt = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","","","","","","","",$search_status);
	}else{
		$oProductcnt1 = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","","","","","","","","","1");
		$oProductcnt2 = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","","","","","","","");
		//print"<pre>";print_r($oProductcnt1);print"</pre>";
		//print"<pre>";print_r($oProductcnt2);print"</pre>";
		if((sizeof($oProductcnt1) > 0) && (sizeof($oProductcnt2) > 0)){
			$oProductcnt = array_merge($oProductcnt1,$oProductcnt2);
		}elseif((sizeof($oProductcnt1) <= 0) && (sizeof($oProductcnt2) > 0)){
			$oProductcnt = $oProductcnt2;
		}elseif((sizeof($oProductcnt1) > 0) && (sizeof($oProductcnt2) <= 0)){
			$oProductcnt = $oProductcnt1;
		}
	}
	*/
	$oProductcnt = $oProduct->arrGetProductNameInfoCnt("",$category_id,$brand_id,"","","","","","",$search_status,"");

   	$iGetoProduCount = $oProductcnt[0]['cnt'];
	if($iGetoProduCount!=0){
	$page = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
	$perpage=20;
	$start  = $oPager->findStart($perpage);
	$recordcount=$iGetoProduCount;
	$sExtraParam="ajax/model_dashboard.php,sOProduOverDiv,$category_id,$search_status";
	$jsparams=$start.",".$perpage.",".$sExtraParam;
	$pages= $oPager->findPages($recordcount,$perpage);
	if($pages > 1 ){
		$pagelist= $oPager->jsPageNumNextPrev($page,$pages,"sOProduOverPagination",$jsparams,"text");
		$nodesPaging .= "<Pages><![CDATA[".$pagelist."]]></Pages>";
		$nodesPaging .= "<Page><![CDATA[".$page."]]></Page>";
		$nodesPaging .= "<Perpage><![CDATA[".$perpage."]]></Perpage>";
	}

	$aProductInfoResultList = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","","","",'order by create_date desc',"","","",$search_status,"");
	//print_r($arrGetProductNameInfo);
   }

	
	if(is_array($aProductInfoResultList)){
		foreach($aProductInfoResultList as $iKey=>$aProductInfoData){
			$aProductInfoList[$iKey]=$aProductInfoData;
			$status = $aProductInfoData['status'];
			$discontinue_status = $aProductInfoData['discontinue_flag'];
			$aProductInfoList[$iKey]['status'] = ($status == 1) ? 'Active' : 'InActive';
			$aProductInfoList[$iKey]['discontinue_status'] = ($discontinue_status == '1')  ? '---' : 'Discontinued';
			$upcoming_status = $aProductInfoData['upcoming_flag'];
			$aProductInfoList[$iKey]['upcoming_status'] = ($upcoming_status == '1')  ? 'Upcoming Model' : '---';
			if(is_array($aBrandDetailName) && isset($aBrandDetailName[$aProductInfoData['brand_id']])){
					$sModelBrandName=$aBrandDetailName[$aProductInfoData['brand_id']][0];
			}
			$aProductInfoList[$iKey]['brand_name']=$sModelBrandName;
			$categoryid = $aProductInfoData['category_id'];
			if(!empty($categoryid)){
				$category_result = $category->arrGetCategoryDetails($categoryid);
			}
			$aProductInfoList[$iKey]['category_name']=$category_result[0]['category_name'];
		}
		$sModelDataDet = arraytoxml($aProductInfoList,"MODEL_DETAIL_DATA");
		$sModelDataDetXML = "<MODEL_DETAIL>".$sModelDataDet."</MODEL_DETAIL>";
	}
}
#echo "DTATA---".$sModelDataDetXML;die();
if($_REQUEST['act']=='Delete' && !empty($product_name_id)){
	$dresult = $oProduct->deleteProductInfo($product_name_id,'PRODUCT_NAME_INFO');
}
if($_REQUEST['act']=='update' && !empty($product_name_id)){
	/*
	$aProductInfoResultDetail = $oProduct->arrGetProductNameInfo($product_name_id,$category_id,"","","",$startlimit,$cnt,"","","","","","1");
	$resCnt = sizeof($aProductInfoResultDetail);
	if($resCnt <= 0){
		$aProductInfoResultDetail = $oProduct->arrGetProductNameInfo($product_name_id,$category_id,"","","",$startlimit,$cnt);
	}
	*/
	$aProductInfoResultDetail = $oProduct->arrGetProductNameInfo($product_name_id,$category_id,$brand_id,"","","","",'order by create_date desc',"","","","","");
	#$aProductInfoResultDetail = $oProduct->arrGetProductNameInfoTest($product_name_id,$category_id,$brand_id,"","","","",'order by create_date desc',"","","","","");
	#print "<pre>"; print_r($aProductInfoResultDetail);
	if(is_array($aProductInfoResultDetail)){
		foreach($aProductInfoResultDetail as $iKey=>$aProductInfoValue){
			$aProductInfoDetail[$iKey]=$aProductInfoValue;
			if(is_array($aBrandDetailName) && isset($aBrandDetailName[$aProductInfoData['brand_id']])){
					$sModelBrandName=$aBrandDetailName[$aProductInfoData['brand_id']][0];
			}
			$aProductInfoDetail[$iKey]['brand_name']=$sModelBrandName;
			if($aProductInfoData['media_id']!=''){
				#$sMainImagePath=getImageDetails($aProductInfoData['media_id'],SERVICEID,$action='api');
				//$sMainImage = $sMainImagePath['main_image'];
				#$aProductInfoDetail[$iKey]['video_path_title'] = $sMainImagePath['title'];
			}
			if($aProductInfoData['img_media_id']!=''){
				#$sMainThmImagePath=getImageDetails($aProductInfoData['img_media_id'],SERVICEID,$action='api');
				//$sMainThmImage = $sMainThmImagePath['main_image'];
				#$aProductInfoDetail[$iKey]['image_path_title'] = $sMainThmImagePath['title'];
			}
		}
		$sModelValueData=arraytoxml($aProductInfoDetail,"MODEL_MASTER_DATA");
		$sModelValueDataXML ="<MODEL_MASTER>".$sModelValueData."</MODEL_MASTER>";
	}
	unset($result);
	$result = $oFeature->arrGetModelColors("",$product_name_id,"",$category_id,"1");
	//print"<pre>";print_r($result);print"</pre>";
	$colors_cnt = sizeof($result);
	$xml .= "<MODEL_COLORS>";
        $xml .= "<COUNT><![CDATA[$colors_cnt]]></COUNT>";
	for($i=0;$i<$colors_cnt;$i++){
                $status = $result[$i]['status'];
                $model_color_code = $result[$i]['color_code'];
                $categoryid = $result[$i]['category_id'];
                $result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
                $result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
                $result[$i]['color_code']=html_entity_decode($model_color_code,ENT_QUOTES);
                $result[$i]['mcolor_id'] = $result[$i]['color_id'];

                $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
                //print "<pre>"; print_r($result[$i]);print"</pre>";
                $xml .= "<MODEL_COLORS_DATA>";
                foreach($result[$i] as $k=>$v){
                        $xml .= "<$k><![CDATA[$v]]></$k>";
                }
                $xml .= "</MODEL_COLORS_DATA>";
         }
         $xml .= "</MODEL_COLORS>";

}
#die("here --- $xml");
unset($result);
if(!empty($category_id)){
         $result = $oFeature->arrGetModelColorDetails("","",$category_id,"");
}
//print"<pre>";print_r($result);print"</pre>";exit;
$cnt = sizeof($result);
$xml .= "<MODEL_COLOR_MASTER>";
$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
for($i=0;$i<$cnt;$i++){
        $status = $result[$i]['status'];
        $model_color_code = $result[$i]['model_color_code'];
        $categoryid = $result[$i]['category_id'];
        $result[$i]['status'] = ($status == 1) ? 'Active' : 'InActive';
        $result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
    $result[$i]['model_color_code']=html_entity_decode($model_color_code,ENT_QUOTES);

        $result[$i] = array_change_key_case($result[$i],CASE_UPPER);
        #print "<pre>"; print_r($result[$i]);
        $xml .= "<MODEL_COLOR_MASTER_DATA>";
        foreach($result[$i] as $k=>$v){
                $xml .= "<$k><![CDATA[$v]]></$k>";
        }
        $xml .= "</MODEL_COLOR_MASTER_DATA>";
}
$xml .= "</MODEL_COLOR_MASTER>";

$config_details = get_config_details();

$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>"; 
$strXML .= "<SELECTED_SEARCH_STATUS><![CDATA[$search_status]]></SELECTED_SEARCH_STATUS>"; 
$strXML .= $config_details;
$strXML .= $sModelDataDetXML;
$strXML .= $sModelValueDataXML;
$strXML .= $sBrandDataDetXML;
$strXML .= $xml;
$strXML .= $nodesPaging;
$strXML .= "<WALLCNT><![CDATA[$iRelUploadCnt]]></WALLCNT>";
$strXML .= "</XML>";
$strXML = mb_convert_encoding($strXML, "UTF-8");
//header('Content-type: text/xml');echo $strXML;exit;
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('../xsl/model_dashboard.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>
