<?php
    #ini_set('display_errors', '1');
    #error_reporting(E_ERROR);
	#echo "T1===============>".date("Y-m-d H:i:s",time())."<br>";
    require_once('./include/config.php');
    require_once(CLASSPATH.'DbConn.php');
    require_once(CLASSPATH.'brand.class.php');
    require_once(CLASSPATH.'category.class.php');
    require_once(CLASSPATH.'pivot.class.php');
    require_once(CLASSPATH.'feature.class.php');
    require_once(CLASSPATH.'product.class.php');
    require_once(CLASSPATH.'price.class.php');
    require_once(CLASSPATH.'pager.class.php');
    require_once(CLASSPATH.'reviews.class.php');
    require_once(CLASSPATH.'user_review.class.php');
    require_once(CLASSPATH.'Utility.php');
    require_once(CLASSPATH.'curl.class.php');
    require_once(CLASSPATH.'xmlparser.class.php');
    require_once(CLASSPATH.'report.class.php');
	require_once(CLASSPATH . 'wallpaper.class.php');
	require_once(CLASSPATH . 'videos.class.php');

	#echo "T2===============>".date("Y-m-d H:i:s",time())."<br>";
    $dbconn = new DbConn;
    $oBrand = new BrandManagement;
    $category = new CategoryManagement;
    $oPivot = new PivotManagement;
    $oFeature = new FeatureManagement;
    $oProduct = new ProductManagement;
    $oPrice = new price;
    $ObjPager =  new Pager();
    $userreview = new USERREVIEW;
    $oReview                =       new reviews;
    $oCurl = new curl;
    $oXmlparser = new XMLParser;
    $report = new report;
	$oWallpapers = new Wallpapers;
	$videoGallery = new videos();

	//print_r($_REQUEST); die();

    $request_url = $_SERVER['REQUEST_URI'];
    $photopos = strpos($request_url, 'photos');
    if ($pos !== false) {
	    $photoslugs = explode("photos/",$request_url);
	    $photoslug = constructUrl($photoslugs[1]);
	}
	$videospos = strpos($request_url,'videos');
    if ($pos !== false){
	    $videosslugs = explode("videos/",$request_url);
	    $videosslug = constructUrl($videosslugs[1]);
	}

    if(($_REQUEST['grpid']=='undefined') || ($_REQUEST['grpid']== "")){$_REQUEST['grpid']=1;}
    if($_REQUEST['pagination']=='undefined'){$_REQUEST['pagination']=1;}
    setcookie ("reviewAdded", "", time() - 3600); //used to remove cookie of user review and rating section.

    if(isset($_REQUEST['tid'])) $iTId = $_REQUEST['tid'];
    $category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : SITE_CATEGORY_ID;
    $router_product_name_id = !empty($_REQUEST['router_model_id']) ? $_REQUEST['router_model_id'] : $_REQUEST['pname'];
    $product_name_id = !empty($_REQUEST['router_model_id']) ? $_REQUEST['router_model_id'] : $_REQUEST['pname'];
    $router_brand_id = $_REQUEST['router_brand_id'];
    $action = $_REQUEST['action'];
    settype($product_name_id, "integer");
    $color_model_id = $product_name_id;
    $compare_product_name_id = $product_name_id;
    $all_variant_page = 0;   $model_upcoming_status = 0; $selected_feature_name = ""; $selected_feature_id = ""; $upcoming_start_price = "";
    $upcoming_end_price = "";





    unset($result);
    switch($action){
        case 'model':
            $action = 'model';
            $currtab_sel = 1;
        break;
        case 'news';
            $action = "news";
            $currtab_sel = 2;
        break;
        case 'reviews';
            $action = "all_review";
            $currtab_sel = 3;
            $currtab_subsel = 3;
        break;
       
        case 'user_reviews';
            $action = "user_reviews";
            $currtab_sel = 2;
            $currtab_subsel = 2;
        break;
        case 'photos';
            $action = "photos";
            $currtab_sel = 4;
        break;
        case 'videos';
            $action = "videos";
            $currtab_sel = 5;
        break;
        default:
            $action = 'model';
            $currtab_sel = 1;
        break;
    }

	//print "<pre>"; print_r($_REQUEST);
	/*
		Array
		(
			[changenloc] => 1
			[category_id] => 1
			[category_name] => Mobiles
			[cat_path] => mobile-phones
			[router_brand_id] => 22
			[router_brand_name] => sony
			[action] => model
			[router_model_id] => 9696495
			[router_model_name] => xperia-e3
			[catid] => 
			[grpid] => 1
		)
	*/
	/*****************************************************************************************************************************/
	/*****************Gadgets code optimize start******************/
		$result = $oProduct->arrGetCheckUpcomimgProductNameInfo($product_name_id,$category_id,$brand_id,"","1","","","","","1","","");
	#echo "T3===============>".date("Y-m-d H:i:s",time())."<br>";	 
		$model_upcoming_status = $result[0]["upcoming_flag"];
		if(sizeof($result) <= 0){
			$seoUrlArr[] = SEO_WEB_URL;
			$seoUrlArr[] = $_REQUEST['cat_path'];
			$seoUrlArr[] = $_REQUEST['router_brand_name'];
			$url = implode("/",$seoUrlArr);
			header('Location: '.$url,TRUE,301);
			exit;
		}elseif(is_array($result)){
			$rating_model_id = $product_name_id;
			$thumb_image_path = CENTRAL_IMAGE_URL.resizeImagePath($result[0]['image_path'],"87X65",$aModuleImageResize);
			$result[0]['thumb_image_path'] = empty($thumb_image_path) ? IMAGE_URL.'no_image_87_65.gif' : $thumb_image_path ;
			$aProductInfoDetails = $result;
			$model_status = $result[0]["status"];
			$model_name = $result[0]["product_info_name"];
			$product_info_name = $result[0]["product_info_name"];
			$top_product_info_name = $result[0]["product_info_name"];
			$model_discontinue_status = $result[0]["discontinue_flag"];
			$model_discontinue_date = $result[0]["discontinue_date"];
			$brand_id = $result[0]['brand_id'];
			$rating_brand_id = $result[0]['brand_id'];
			if(!empty($brand_id)){
				$brandresult = $oBrand->arrGetBrandDetails($brand_id,"","1","","","","","","");
			}
			$sbrand_name = $brandresult[0]['brand_name'];
			$sModelBrandName = $brandresult[0]['brand_name'];
			$upcoming_brand = $brandresult[0]['upcoming_brand'];
			$brand_name = $brandresult[0]['brand_name'];
			$brand_seo_path = $brandresult[0]['seo_path'];
			$sproduct_name_id = $product_name_id;
			$search_product_info_name = $aProductInfoDetails[0]['product_info_name'];
			$product_info_name = $aProductInfoDetails[0]['product_info_name'];
			$model_seo_path = $aProductInfoDetails[0]['seo_path'];
			$product_info_brand_id = $aProductInfoDetails[0]['brand_id'];
			$media_path = $aProductInfoDetails[0]['image_path'];
			$seo_product_info_name = $product_info_name;
			$img_media_id = $aProductInfoDetails[0]['img_media_id'];
			if(!empty($media_path)){
				$media_path = resizeImagePath($media_path,"225X300",$aModuleImageResize,$img_media_id);
				$media_path = $media_path ? CENTRAL_IMAGE_URL.$media_path : IMAGE_URL.'no_image_251_188.gif';
			}
			$aProductInfoDetails['0']['image_path'] = $media_path ;
			$seo_product_info_name=$model_seo_path;
			$sLinkProductName=$brand_name."-".$product_info_name;
			$product_brand_name = html_entity_decode($brand_name,ENT_QUOTES,'UTF-8');
			$product_brand_name = removeSlashes($product_brand_name);
			$product_brand_name = seo_title_replace($product_brand_name);
			$product_link_name = html_entity_decode($sLinkProductName,ENT_QUOTES,'UTF-8');
			$product_link_name = removeSlashes($product_link_name);
			$product_link_name = seo_title_replace($product_link_name);
			$aHostqstr=explode("/",$_SERVER['SCRIPT_URL']);
			$seo_title_part = $brand_name." ".$product_info_name;
			$reviewName = $reviewName ? $reviewName : $tab;
			
			$pResult = $oProduct->arrGetProductDetails("",$category_id,"",'1',"","","1","","","1","order by PRICE_VARIANT_VALUES.variant_value asc",$model_name,"","",'',"1");
			
			if(is_array($pResult)){
				$least_product_name =  $pResult[0]['variant'];
				if(!empty($pResult[0]['announced_date'])){
					//echo $pResult[0]['announced_date'] ."+++++++++++++++++++++++++". $pResult[0]['arrival_date'];
					$announcedateday = explode(" ",$pResult[0]['announced_date']) ;
					
					$announcedates = explode("-",$announcedateday[0]); 
					$least_product_announced_date =  date("F j Y", mktime(0, 0, 0, $announcedates['1']  , $announcedates['2'], $announcedates['0']));
				}
				if(!empty($pResult[0]['arrival_date'])){
					$arrivaldateday = explode(" ",$pResult[0]['arrival_date']) ;
					//print "<pre>";  print_r($arrivaldateday[0]);
					$arrivaldates = explode("-",$arrivaldateday[0]) ;
					//print "<pre>"; print_r($arrivaldates);
					$least_product_arrival_date =  date("F j Y", mktime(0, 0, 0, $arrivaldates['1']  , $arrivaldates['2'], $arrivaldates['0']));
				}
				$least_product_name =  $pResult[0]['variant'];
				//echo $least_product_announced_date."&&&&&&&&&&&&&&&&&&&&&&&&".$least_product_arrival_date;
				$least_variant_seo_path =  $pResult[0]['seo_path'];
				unset($variantUrlYear);
				$variantUrlYear = buildYear($pResult[0]['arrival_date'],$pResult[0]['discontinue_date']);
				$least_product_id =  $pResult[0]['product_id'];
				$iCnt = sizeof($pResult);
				for($i=0;$i<$iCnt;$i++){
					$product_id = $pResult[$i]['product_id'];
					$sExShowRoomPrice = $pResult[$i]['variant_value'];
					$aPriceRange[$i]['price'] = $sExShowRoomPrice;
					$aPriceRange[$i]['product_id'] = $product_id;
				}
			}
		}
		$cmd = PHP_PATH.' '.BASEPATH."api/average_rating_api.php brand_id=$rating_brand_id product_name_id=$product_name_id";
		$xml_output = shell_exec($cmd);
		$expertratingxml .= $xml_output;
		$oXmlparser->XMLParse($xml_output);
		$aResultXML =$oXmlparser->getOutput();
		$avg_html_cnt = $aResultXML["AVERAGE_USER_RATING_API"]["ALL_REVIEWS_AVG_RATING"];
		$oXmlparser->clearOutput();
		if(($avg_html_cnt >= 1) && ($avg_html_cnt <= 1.5)){
			$gradeStr = "Poor";
		}else if(($avg_html_cnt > 1.5) && ($avg_html_cnt <= 2.5)){
			$gradeStr = "Fair";
		}else if(($avg_html_cnt > 2.5) && ($avg_html_cnt <= 3.5)){
			$gradeStr = "Average";
		}else if(($avg_html_cnt > 3.5) && ($avg_html_cnt <= 4.5)){
			$gradeStr = "Good";
		}else if(($avg_html_cnt > 4.5) && ($avg_html_cnt <= 5)){
			$gradeStr = "Excellent";
		}
		$expertratingxml .= "<OVERALL_AVG_HTML_MSG><![CDATA[$gradeStr]]></OVERALL_AVG_HTML_MSG>";
		$expertratingxml .= "<OVERALL_TOTAL_CNT><![CDATA[$overallcnt]]></OVERALL_TOTAL_CNT>";
		$expertratingxml .= "<OVERALL_CNT><![CDATA[$totalcnt]]></OVERALL_CNT>";
		$expertratingxml .= "<SEO_MODEL_RATING_PAGE_URL><![CDATA[$seo_model_url]]></SEO_MODEL_RATING_PAGE_URL>";
		//end code added by rajesh on dated 02-06-2011 for user.
		//if($action == 'model'){
			//start code added by rajesh on dated 10-06-2011 for variant page summary.
			$product_id = $pResult[0]['product_id'];
			if (!empty($product_id)) {
				if (!empty($selected_city_id)) {
					$aOverview = $oFeature->arrGetVariantPageSummary($category_id, $product_id, "", $selected_city_id);
				} else {
					$aOverview = $oFeature->arrGetVariantPageSummary($category_id, $product_id, "1");
				}
			}
			foreach ($aOverview as $key => $val) {
				if (!strpos($key, 'Price') && !strpos($key, 'Feature')) {
					unset($overviewArr);
					foreach ($aOverview[$key] as $overviewtitle => $overviewvalueArr) {
						$overviewvalueArr = array_change_key_case($overviewvalueArr, CASE_UPPER);
						$techspecxml .= "<TECH_SPEC_DATA>";
						$techspecxml .= "<FEATURE_TITLE><![CDATA[$overviewtitle]]></FEATURE_TITLE>";
						foreach ($overviewvalueArr as $techspeckey => $techspecval) {
							$techspecxml .= "<$techspeckey><![CDATA[$techspecval]]></$techspeckey>";
						}
						$techspecxml .= "</TECH_SPEC_DATA>";
						unset($overviewvalueArr);
					}
				}
				if (strpos($key, 'Feature')) {
					unset($overviewArr);
					foreach ($aOverview[$key] as $overviewtitle => $overviewvalueArr) {
						$overviewvalueArr = array_change_key_case($overviewvalueArr, CASE_UPPER);
						$featurespecxml .= "<FEATURE_SPEC_DATA>";
						$featurespecxml .= "<FEATURE_TITLE><![CDATA[$overviewtitle]]></FEATURE_TITLE>";
						foreach ($overviewvalueArr as $techspeckey => $techspecval) {
							$featurespecxml .= "<$techspeckey><![CDATA[$techspecval]]></$techspeckey>";
						}
						$featurespecxml .= "</FEATURE_SPEC_DATA>";
						unset($overviewvalueArr);
					}
				}
				if (strpos($key, 'Price')) {
					unset($overviewArr);
					foreach ($aOverview[$key] as $overviewtitle => $overview_value) {
						$avg_price = str_replace("(insurance, road tax and other taxes/charges extra) ", "", $overview_value);
						$org_avg_price = str_replace(array(",", " , "), "", $avg_price);
						$techspecxml .= "<AVG_EX_SHOWROOM_PRICE><![CDATA[" . str_replace("(insurance, road tax and other taxes/charges extra) ", "", $overview_value) . "]]></AVG_EX_SHOWROOM_PRICE>";
						$techspecxml .= "<AVG_ORG_SHOWROOM_PRICE><![CDATA[" . $org_avg_price . "]]></AVG_ORG_SHOWROOM_PRICE>";
					}
				}
				if (strpos($key, 'product_desc')) {
					unset($overviewArr);
					foreach ($aOverview[$key] as $overviewtitle => $overview_value) {
						$techspecxml .= "<PRODUCT_DESC><![CDATA[$overview_value]]></PRODUCT_DESC>";
					}
				}
			}
			$sOverviewXML = "<TECH_SPEC_SHORT_DESC>$techspecxml</TECH_SPEC_SHORT_DESC>";
			$sOverviewXML .= "<FEATURE_SPEC_SHORT_DESC>$featurespecxml</FEATURE_SPEC_SHORT_DESC>";
			//end code added by rajesh on dated 10-06-2011 for variant page summary.
			if (is_array($pResult)) {
				$aProductWithPriceDetail = $oProduct->constantProductInfoDetails($pResult, $category_id, $iSelCity);
			}
			//print_r($aProductWithPriceDetail); die();
			if (is_array($aProductWithPriceDetail)) {
				$sProductName = $aProductWithPriceDetail['0']['product_name'];
				$sItemSelProductName = $aProductWithPriceDetail['0']['product_name'];
				$iOnRoadPrice = $aProductWithPriceDetail['0']['On_Road_Price'];
				$iExShowRoomPriceOriginal = $aProductWithPriceDetail['0']['exshowroomprice'];
				$popad = 0;
				$popadprice = str_replace(",", "", $iExShowRoomPriceOriginal);
				$iExShowRoomPriceOriginal = str_replace(",", "", $iExShowRoomPriceOriginal);
				if ($popadprice >= 300000 && $popadprice <= 1500000) {
					$popad = 1;
				}
				$iExShowRoomPrice = $aProductWithPriceDetail['0']['exshowroomprice'];
				$variant_price = $aProductWithPriceDetail['0']['variant_value'];
				$sDisplayName = $aProductWithPriceDetail['0']['display_product_name'];
				if (!empty($sProductName)) {
					$productinforesult = $oProduct->arrGetProductNameInfo("", $category_id, "", $sProductName);
				}
				$product_desc = html_entity_decode($productinforesult['0']['product_name_desc'], ENT_QUOTES, 'UTF-8');
				$sLinkProductName = $aProductWithPriceDetail['0']['link_product_name'];
				$sImagePath = $aProductWithPriceDetail['0']['image_path'];
				$slideImagePath = $aProductWithPriceDetail['0']['image_path'];
				$img_media_id = $aProductWithPriceDetail['0']['img_media_id'];
				$product_name_id = $aProductWithPriceDetail['0']['product_name_id'];
				if (!empty($sImagePath)) {
					$sImagePath = resizeImagePath($sImagePath, "225X300", $aModuleImageResize, $img_media_id);
					$sImagePath = $sImagePath ? CENTRAL_IMAGE_URL . $sImagePath : IMAGE_URL . 'no_image_251_188.gif';
					$thumb_image_path = resizeImagePath($sImagePath, "87X65", $aModuleImageResize, $img_media_id);
					$thumb_image_path = $thumb_image_path ? $thumb_image_path : IMAGE_URL . 'no_image_87X65.gif';
				}
				//echo $sImagePath;
				$sVideoPath = $aProductWithPriceDetail['0']['video_path'];
				$sVariant = $aProductWithPriceDetail['0']['variant'];
				$sproduct_desc = $aProductWithPriceDetail['0']['product_desc'];
				$seo_variant_path = $aProductWithPriceDetail['0']['seo_path'];
				$iBrandId = $aProductWithPriceDetail['0']['brand_id'];
				$arrival_date = $aProductWithPriceDetail[0]['arrival_date'];
				$discontinue_date = $aProductWithPriceDetail[0]['discontinue_date'];
				$productvariantUrlYear = buildYear($arrival_date, $discontinue_date);
				$aPriceDetails = $aProductWithPriceDetail['0']['price_details'];
				$prodCityId = $aPriceDetails['0']['city_id'];
				if (!empty($iBrandId)) {
					$brand_result = $oBrand->arrGetBrandDetails($iBrandId, $category_id);
				}
				$brand_name = $brand_result[0]['brand_name'];
				$seo_ProductName = $sProductName;
				$seo_variant = $sVariant;
				$seo_title_part = ucfirst($brand_name) . " " . ucfirst($sProductName) . " " . ucfirst($sVariant);
				$title = ucfirst($brand_name) . " " . ucfirst($sProductName) . " " . ucfirst(str_replace(" ", "-", $sVariant));
				if (!empty($tab_id)) {
					if ($tab_id == 2) {
						$typeshow = 'Features';
					}
					if ($tab_id == 3) {
						$typeshow = 'Technical Specifications';
					}
				} else {
					$typeshow = ' Overview & Details';
				}
				$seoArr[0] = $title . " " . $typeshow;
				$breadcrumb = "Overview : $title";
				$seo_desc = $title . ' - On Cars India. Get all the latest car reviews and ratings, available versions,on road price,technical specifications, features, colours, Photos and videos on ' . SEO_DOMAIN;
				$seoArr[2] = ucfirst($brand_name) . " " . ucfirst($sProductName) . ' Cars';
				$seoArr[3] = SEO_DOMAIN;
				$seo_title = implode(" | ", $seoArr);
				$product_brand_name = html_entity_decode($brand_name, ENT_QUOTES, 'UTF-8');
				$product_link_name = html_entity_decode($sLinkProductName, ENT_QUOTES, 'UTF-8');
				$product_variant_name = html_entity_decode($sVariant, ENT_QUOTES, 'UTF-8');
				$aHostqstr = explode("/", $_SERVER['SCRIPT_URL']);
				if (!empty($sItemSelProductName)) {
					$aSelProductNameData = $oProduct->arrGetProductByName($sItemSelProductName, '', "", "1", "0", "5");
				}
				if (is_array($aSelProductNameData)) {
					foreach ($aSelProductNameData as $iwKey => $aValueWallPaperProd) {
						$aSelProductIds[] = $aValueWallPaperProd['product_id'];
					}
				}
				if (!empty($sImagePath)) {
					$thumb_image_path = resizeImagePath($thumb_image_path, "73X55", $aModuleImageResize);
				}
				$thumb_image_path = $thumb_image_path ? $thumb_image_path : IMAGE_URL . 'no_image_73X55.gif';
				
				$aProductData[0] = array("brand_id" => $rating_brand_id, "product_name_id" => $product_name_id, "product_id" => $product_id, "product_name" => $sProductName, "On_Road_Price" => $iOnRoadPrice, "display_product_name" => $sDisplayName, "link_product_name" => $sLinkProductName, "video_path" => $sVideoPath, "image_path" => $sImagePath, "city_name" => $sCityName, "variant" => $sVariant, "exshowroomprice" => $iExShowRoomPrice, "seo_url" => $seo_url, "exshowroompriceorginal" => $iExShowRoomPriceOriginal, "brand_name" => $brand_name, "product_desc" => $sproduct_desc, 'thumb_image_path' => $thumb_image_path, 'arrival_date' => $least_product_arrival_date, 'announced_date' => $least_product_announced_date);
				$disp_title = $aProductData['0']['display_product_name'];
				//echo "<pre>"; print_r($sImagePath);
				$sProductDet = arraytoxml($aProductData, "PRODUCT_DETAIL_DATA");
				$sProductDetXml = "<PRODUCT_DETAIL>" . $sProductDet . "</PRODUCT_DETAIL>";
			}
			if($three_months_plus_discontinue_date==0){
				$chk_date=1;
			}else{
				$chk_date='';
			}
			if($action== "model"){
			if(!empty($product_info_name)){
				if($selected_city_id!=''){
					$aProductDetail = $oProduct->arrGetProductDetails("",$category_id,$rating_brand_id,'1',"","","1","","","","order by PRICE_VARIANT_VALUES.variant_value asc",$product_info_name,$selected_city_id,"",'',$chk_date);
				}
				if(sizeof($aProductDetail)==0){
					$aProductDetail = $oProduct->arrGetProductDetails("",$category_id,$rating_brand_id,'1',"","","1","","","1","order by PRICE_VARIANT_VALUES.variant_value asc",$product_info_name,"","",'',$chk_date);
				}
			}

			if(is_array($aProductDetail)){
				$iCnt=sizeof($aProductDetail);
				$sProductVersionDetXML = "<PRODUCT_VERSION_DETAIL>";
				$arr_cnt = 0;
				$top_competitor_product_id=0;
				$top_competitor_brand_id=0;
				$top_competitor_model_id=$product_name_id;
				for($i=0;$i<$iCnt;$i++){
				$disc_date=''; $disc_status='';
					$product_id = $aProductDetail[$i]['product_id'];
					if(empty($top_competitor_product_id)){ $top_competitor_product_id = $aProductDetail[$iCnt-1]['product_id'];}
					if(empty($top_competitor_brand_id)){ $top_competitor_brand_id = $aProductDetail[$iCnt-1]['brand_id'];}
					$highlight_data = $oFeature->arrGetVariantPageSummary($category_id,$product_id,"1","","highlight");
					$disc_status = $aProductDetail[$i]['discontinue_flag'];
					$disc_date = $aProductDetail[$i]['discontinue_date'];
				$variantUrlYear = buildYear($aProductDetail[$i]['arrival_date'],$aProductDetail[$i]['discontinue_date']);

				$disc_date = "0";
				if((($disc_status == "0") && (strtotime($disc_date) < strtotime($prev_3_month)) && $disc_date != "0000-00-00 00:00:00")){
									$disc_date = "1";
							}else if((($disc_status == "0") && (strtotime($disc_date) > strtotime($prev_3_month)) && $disc_date != "0000-00-00 00:00:00")){

									$disc_date = "2";
							}
					$aProductDetail[$i]['month_discontinue_date'] = $disc_date;
					$aProductDetail[$i]['discontinue_date'] = $disc_date;
					$aProductDetail[$i]['discontinue_flag'] = $disc_status;
					$version_brand_id = $aProductDetail[$i]['brand_id'];
					if(!empty($version_brand_id)){
						$aBrandDetail=$oBrand->arrGetBrandDetails($version_brand_id,$category_id);
					}
					$brand_name=$aBrandDetail['0']['brand_name'];
					$seo_brand_path=$aBrandDetail['0']['seo_path'];
					$product_name=$aProductDetail[$i]['product_name'];
					$variant_seo_path=$aProductDetail[$i]['seo_path'];
					//$product_id=$aProductDetail[$i]['product_id'];

					if($aProductDetail[$i]['variant_value']!=0){
						$product_ids[]=$aProductDetail[$i]['product_id'];
						$variant=$aProductDetail[$i]['variant'];
						$aProductDetail[$i]['brand_name']=$brand_name;
						if(!empty($product_id)){
							$aOverview = $oFeature->arrGetSummary($category_id,$product_id,$type="array");
						}
						foreach($aOverview as $key=>$val){
							if(!strpos($key,'Price') && !strpos($key,'Feature') ){
								unset($overviewArr);
								$css_pos = 1; $ivpos=0;
								foreach($aOverview[$key] as $overviewtitle=>$overview_value){
									if($overviewtitle=="Mileage" || $overviewtitle=="Fuel Type" || $overviewtitle=="Engine"){
										//if(trim($overview_value[0])!=trim("-Kmpl")){ $overview_value[0]='';}
										//if(trim($overview_value[0])!=trim("-cc")){ $overview_value[0]='';}
										$techspecxml .= "<TECH_SPEC_DATA>";
										$techspecxml .= "<FEATURE_TITLE><![CDATA[$overviewtitle]]></FEATURE_TITLE>";
										$techspecxml .= "<FEATURE_VALUE><![CDATA[".implode(",&#160;",$overview_value)."]]></FEATURE_VALUE>";
										$techspecxml .= "</TECH_SPEC_DATA>";
									}
								}
							}
							if(strpos($key,'Feature') ){
								unset($overviewArr);
								$css_pos = 1;
								foreach($aOverview[$key] as $overviewtitle=>$overview_value){
									$featurespecxml .= "<FEATURE_SPEC_DATA>";
									$featurespecxml .= "<FEATURE_TITLE><![CDATA[$overviewtitle]]></FEATURE_TITLE>";
									$featurespecxml .= "<FEATURE_VALUE><![CDATA[".implode(",&#160;",$overview_value)."]]></FEATURE_VALUE>";
									$featurespecxml .= "</FEATURE_SPEC_DATA>";
								}
							}
						}
						$aProductDetail[$i]['tech_spec_short_desc'] = $techspecxml;
						unset($techspecxml);
						$aProductDetail[$i]['feature_spec_short_desc'] = $featurespecxml;
						unset($featurespecxml); $overviewArr="";
						$media_path=$aProductDetail[$i]['image_path'];
						if(!empty($media_path)){
							$media_path = CENTRAL_IMAGE_URL.$media_path;
						}
						$aProductDetail[$i]['image_path'] = $media_path ;
						$sim_brand_name = html_entity_decode($brand_name,ENT_QUOTES,'UTF-8');
						$sim_link_name= $sim_brand_name."-".$product_name;
						$sim_link_name = html_entity_decode($sim_link_name,ENT_QUOTES,'UTF-8');
						$sim_variant_name = html_entity_decode($variant,ENT_QUOTES,'UTF-8');
						unset($seoTitleArr);
						$comparename = constructUrl($sim_brand_name).'-'.constructUrl($product_name).'-'.constructUrl($sim_variant_name);
						if(!empty($variantUrlYear)){
							$comparename = $comparename.'-'.$variantUrlYear;
						}
						$aProductDetail[$i]['comparename'] = $comparename;

						$seoTitleArr[] = SEO_WEB_URL;
						$seoTitleArr[] = $_REQUEST['cat_path'];
						$seoTitleArr[] = trim($seo_brand_path);
						$seoTitleArr[] = trim($model_seo_path);
						$seoTitleArr[] = trim($variant_seo_path);
						if(!empty($variantUrlYear)){
								$seoTitleArr[] = $variantUrlYear;
						}
						$seoTitleArr[] = SEO_PRODUCT_FEATURE;
						$seo_url1 = implode("/",$seoTitleArr);

						$aProductDetail[$i]['features_seo_url'] = trim($seo_url1);
						unset($aPriceDetail);
						if(!empty($product_id)){
							if(!empty($selected_city_id)){
							$aPriceDetail = $oPrice->arrGetPriceDetails("1",$product_id,$category_id,"","",$selected_city_id,"1","","","");
							}
							if(empty($aPriceDetail)){
							$aPriceDetail = $oPrice->arrGetPriceDetails("1",$product_id,$category_id,"","","","1","","","1");
							}
						}
						if(is_array($aPriceDetail)){
							$sExShowRoomPrice=$aPriceDetail[0]['variant_value'];
							$iCity_id=$aPriceDetail[0]['city_id'];
							$iCity_name = $aPriceDetail[0]['city_name'];
							$aProductDetail[$i]['EXSHOWROOM_ORGINAL'] = $sExShowRoomPrice;
							$aProductDetail[$i]['EXSHOWROOM'] = $sExShowRoomPrice ? priceFormat($sExShowRoomPrice) : '';
							$format_price = $sExShowRoomPrice ? priceFormat($sExShowRoomPrice) : '';
							$aproducts[$sExShowRoomPrice]=$aPriceDetail[0]['product_id'];
							$arr_cnt++;
						}
						unset($seoTitleArr);
						$seoTitleArr[] = SEO_WEB_URL;
						$seoTitleArr[] = $_REQUEST['cat_path'];
						$seoTitleArr[] = trim($seo_brand_path);
						$seoTitleArr[] = trim($model_seo_path);
						$seoTitleArr[] = trim($variant_seo_path);
						if(!empty($variantUrlYear)){
								$seoTitleArr[] = $variantUrlYear;
						}
						$overview_seo_url = implode("/",$seoTitleArr );
						$aProductDetail[$i]['overview_seo_url'] = $overview_seo_url ;
						$aProductDetail[$i] = array_change_key_case($aProductDetail[$i],CASE_UPPER);
						$sProductVersionDetXML .= "<PRODUCT_VERSION_DETAIL_DATA>";
						$sProductVersionDetXML .= $highlight_data;
						foreach($aProductDetail[$i] as $k => $v){
								$sProductVersionDetXML .= ($k == 'TECH_SPEC_SHORT_DESC' || $k == 'FEATURE_SPEC_SHORT_DESC' || $k == 'TECH_SPEC_SHORT_DESC_QUICKVIEW' || $k == 'FEATURE_SPEC_SHORT_DESC_QUICKVIEW') ? "<$k>$v</$k>" : "<$k><![CDATA[$v]]></$k>";
						}
						$sProductVersionDetXML .=$sSeoUrlReviewsDetXml;
						$sProductVersionDetXML .= "</PRODUCT_VERSION_DETAIL_DATA>";
					}
				}
				$sProductVersionDetXML .= "</PRODUCT_VERSION_DETAIL>";
			}
		}
		//}
		#echo "T4===============>".date("Y-m-d H:i:s",time())."<br>"; 
		$sortArray = array();
		foreach($aPriceRange as $price){
			foreach($price as $key=>$value){
				if(!isset($sortArray[$key])){
					$sortArray[$key] = array();
				}
				$sortArray[$key][] = $value;
			}
		}
		$orderby = "price";
		//sort($aPriceRange);
		array_multisort($sortArray[$orderby],SORT_ASC,$aPriceRange);
		$lowPrice=$aPriceRange[0]['price'];
		if(count($aPriceRange)>1){
			$highPrice=$aPriceRange[count($aPriceRange)-1];
		}
		$lowprice_product_id = $aPriceRange[0]['product_id'];
		$lowprice_product_name = $aPriceRange[0]['product_name'];
		$seo_lowPrice  = numberWithDecimal($lowPrice/100000);
		$sRupeeFormatLow  = 'lakhs';
		if($seo_lowPrice>=100){
			$seo_lowPrice       = numberWithDecimal($lowPrice/10000000);
			$sRupeeFormatLow    = 'Crore';
		}
		if($highPrice['price']>0){
			$seo_highPrice = numberWithDecimal(($highPrice['price']/100000),2);
			$sRupeeFormatHigh  = 'lakhs';
			if($seo_highPrice>=100){
				$seo_highPrice = numberWithDecimal(($highPrice['price']/10000000),2,'.','');
				$sRupeeFormatHigh    = 'Crore';
			}
		}
		$lPrice = $lowPrice;
		$lowPrice = priceFormat($lowPrice);
		$highPrice = priceFormat($highPrice);
		$aProductInfoDetails['0']['lowprice_product_id'] = $lowprice_product_id;
		$aProductInfoDetails['0']['lprice'] = $lPrice;
		$aProductInfoDetails['0']['low_price'] = $lowPrice;
		$popad=0;
		if($lPrice>=300000 && $lPrice<=1500000){
			$popad = 1;
		}
		$aProductInfoDetails['0']['high_price'] = $highPrice;
		$disp_title .= $product_brand_name." ".$product_link_info_name;
		$aProductInfoDetails['0']['seo_url'] = trim($seo_url);
		$aProductInfoDetails['0']['link_product_name'] = str_replace("-"," ",$product_link_name);
		$sProductInfoDet = arraytoxml($aProductInfoDetails,"PRODUCT_INFO_DETAIL_DATA");
		$sProductInfoDetXml ="<PRODUCT_INFO_DETAIL>".$sProductInfoDet."</PRODUCT_INFO_DETAIL>";
		if($model_upcoming_status == 1){
        unset($result);
        //$result = $oProduct->arrGetProductNameInfo($product_name_id,$category_id,"","","1","","","","","","","","1");
        $result = $oProduct->arrGetUpComingProductDetails("",$product_name_id,"","","",$category_id,'1');
        $cnt = sizeof($result);
        $xml .="<UPCOMING_PRODUCT_DETAIL>";
        if($cnt > 0){
            $upcoming_product_id = $result[0]['upcoming_product_id'];
            $model_id = $result[0]['product_name_id'];
            $selected_feature_id = $result[0]['feature_id'];
            if(!empty($selected_feature_id)){
                unset($feature_result);
                $feature_result = $oFeature->arrGetFeatureDetails($selected_feature_id,$category_id,"","","1");
                $selected_feature_name = $feature_result[0]['feature_name'];
            }
            unset($model_res);
            $model_res = $oProduct->arrGetProductNameInfo($model_id,$category_id,"","","","","","","","","","","1");
            if(is_array($model_res)){
                $product_info_name = $model_res[0]["product_info_name"];
                $media_path = $model_res[0]["image_path"];
                $up_model_seo_path = $model_res[0]["seo_path"];
            }
            $search_product_info_name = $product_info_name;
            if(!empty($media_path)){
                $media_path = resizeImagePath($media_path,"225X300",$aModuleImageResize);
                $media_path = !empty($media_path) ? CENTRAL_IMAGE_URL.$media_path : IMAGE_URL.'no_image_251_188.gif';
            }
            $result[0]['image_path'] = $media_path ;
            $brand_id = $model_res[0]['brand_id'];
            $product_info_brand_id = $brand_id;
            if(!empty($brand_id)){
                $brandresult = $oBrand->arrGetBrandDetails($brand_id,"","1","","","","","","");
            }
            $brand_name = $brandresult[0]['brand_name'];
            $up_brand_seo_path = $brandresult[0]['seo_path'];
            $product_brand_name = $brand_name;
            $result[0]['product_name'] = $brand_name." ".$product_info_name;
            #$seo_title_part = constructUrl($brand_name)." ".constructUrl($product_info_name);
            $seo_title_part = $brand_name." ".$product_info_name;
            $feature_id = $result[0]['feature_id'];
            //$expected_price = $result[0]['expected_price'] ? priceFormat($result[0]['expected_price']) : "";
            $min_expected_price = $result[0]['min_expected_price'];
            $min_expected_price_unit = $result[0]['min_expected_price_unit'];
            $max_expected_price = $result[0]['max_expected_price'];
            $max_expected_price_unit = $result[0]['max_expected_price_unit'];
            $amin_expected_price = explode(".",$min_expected_price);
            if($amin_expected_price[1]== '00' ){
                $min_expected_price = round($min_expected_price);
            }
            $amax_expected_price = explode(".",$max_expected_price);
            if($amax_expected_price[1]== '00' ){
                $max_expected_price = round($max_expected_price);
            }

            $upcoming_start_price = $min_expected_price*$min_expected_price_unit;
            $upcoming_end_price = $max_expected_price*$max_expected_price_unit;

            if($min_expected_price_unit == "100000"){
                $min_price_unit = "Lakh";
            }elseif($min_expected_price_unit == "10000000"){
                $min_price_unit = "Crore";
            }
            if($max_expected_price_unit == "100000"){
                $max_price_unit = "Lakh";
            }elseif($max_expected_price_unit == "10000000"){
                $max_price_unit = "Crore";
            }
            if($min_expected_price_unit == $max_expected_price_unit){
                $expected_price = $min_expected_price."-".$max_expected_price." ".$min_price_unit;
            }else{
                $expected_price = $min_expected_price." ".$min_price_unit."-".$max_expected_price." ".$max_price_unit;
            }
            if(($min_expected_price == '') && ($max_expected_price == '')){
                $expected_price = "";
            }
            $result[0]['expected_price'] = $expected_price;
            $expected_date_text = $result[0]['expected_date_text'];
            $short_description = html_entity_decode($result[0]['short_description'],ENT_QUOTES,'UTF-8');
            $short_description = removeSlashes($short_description);
            //if(strlen($short_description)>100){ $short_description = getCompactString($short_description, 95).' ...'; }
            $result[0]['short_description'] = $short_description;
            $content = $result[0]['content'];
            if(!empty($content)){
                $content = html_entity_decode($content,ENT_QUOTES,'UTF-8');
                //$content = removeSlashes($content);
                $result[0]['content'] = $content;
            }
     

            unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
	    $seoTitleArr[] = $_REQUEST['cat_path'];
            $seoTitleArr[] = $up_brand_seo_path;
            $seoTitleArr[] = $up_model_seo_path;
            $seo_url = implode("/",$seoTitleArr);
            $result[0]['seo_url'] = $seo_url;

	        unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
	    $seoTitleArr[] = $_REQUEST['cat_path'];
            $seoTitleArr[] = $up_brand_seo_path;
            $seoTitleArr[] = $up_model_seo_path;
            $seo_photo_tab_url= implode("/",$seoTitleArr);
            $result[0]['seo_photo_tab_url'] = $seo_photo_tab_url;

	    unset($seoTitleArr);
            $seoTitleArr[] = SEO_WEB_URL;
	    $seoTitleArr[] = $_REQUEST['cat_path'];
            $seoTitleArr[] = $up_brand_seo_path;
            $seoTitleArr[] = $up_model_seo_path;
            $seo_video_tab_url= implode("/",$seoTitleArr);
            $result[0]['seo_video_tab_url'] = $seo_video_tab_url;

            $result[0] = array_change_key_case($result[0],CASE_UPPER);
            foreach($result[0] as $k=>$v){
                $xml .= "<$k><![CDATA[$v]]></$k>";
            }
        }
        $xml .="</UPCOMING_PRODUCT_DETAIL>";
    }
	unset($breadcrumb);
	$breadcrumb = modelPageBreadCrumb($category_id,$brdresult,$top_brand_arr,$product_info_brand_id,$product_brand_name,$product_name_id,$search_product_info_name,$model_upcoming_status,$action);
	function sortByArrayDate($a, $b) {
		return strtotime($a['create_date']) - strtotime($b['create_date']);
	}
	
		
		$feature_sub_group_array = $oFeature->arrGetFeatureDetails("", "", "", 18, "1");
		foreach ($feature_sub_group_array as $key => $val) {
			$feature_id = $val['feature_id'];
			$body_result = $oProduct->arrGetProductFeatureDetails("", $feature_id, $product_id);
			if (is_array($body_result)) {
				if (strtolower($body_result[0]['feature_value']) == "yes") {
					$body_type = $body_result[0]['feature_name'];
				}
			}
		}
		//$highlight_data = $oFeature->arrGetVariantPageSummary($category_id, $product_id, "1", "", "highlight");
		//echo "$category_id"; die();
		if (!empty($category_id)) {
			$result = $oFeature->arrFeatureSubGroupDetails("", $category_id, "1");
			$featureSubGroupCnt = sizeof($result);
			for ($i = 0; $i < $featureSubGroupCnt; $i++) {
				$sub_group_id = $result[$i]['sub_group_id'];
				$featureSubGroupArr[$sub_group_id] = $sub_group_id;
			}
			unset($result);
			$result = $oFeature->arrGetFeatureMainGroupDetails("", $category_id, "1", $startlimit, $limitcnt);
		}
		$cnt = sizeof($result);
		//echo "FEATURES1---".$category_id."=======".$cnt; die();
		$featureboxcntArr = Array();
		for ($i = 0; $i < $cnt; $i++) {
			$status = $result[$i]['status'];
			$main_group_id = $result[$i]['group_id'];
			$categoryid = $result[$i]['category_id'];
			$main_feature_group_name = $result[$i]['main_group_name'];
			unset($seoTitleArr);
			$seoTitleArr[] = SEO_WEB_URL;
			$seoTitleArr[] = constructUrl($product_brand_name);
			$seoTitleArr[] = constructUrl($product_info_name);
			$seoTitleArr[] = constructUrl($product_variant_name);
			if (!empty($productvariantUrlYear)) {
				$seoTitleArr[] = $productvariantUrlYear;
			}
			$seo_url = implode("/", $seoTitleArr);
			$result[$i]['seo_url'] = $seo_url;

			if (!empty($categoryid)) {
				//$category_name = $category_result[0]['category_name'];
				$result[$i]['js_category_name'] = $category_name;
				$result[$i]['category_name'] = html_entity_decode($category_name, ENT_QUOTES, 'UTF-8');
				if (!empty($main_group_id)) {
					$feature_result = $oFeature->arrGetFeatureDetails("", $category_id, $main_group_id);
				}
				$featureCnt = sizeof($feature_result);
				$featureboxcntArr[] = $featureCnt;
				for ($j = 0; $j < $featureCnt; $j++) {
					$feature_group = $feature_result[$j]['feature_group'];
					if (!empty($feature_group)) {
						$feature_sub_group_array = $oFeature->arrFeatureSubGroupDetails($feature_group, $categoryid, "1");
					}
					$sub_group_name = $feature_sub_group_array[0]['sub_group_name'];
					if (empty($sub_group_name))
						continue;
					if ($sub_group_name != 'Segments') {
						if ($feature_result[$j]['feature_name'] != 'Car description') {

							$main_feature_group = $feature_result[$j]['main_feature_group'];
							$status = $feature_result[$j]['status'];
							$categoryid = $feature_result[$j]['category_id'];
							$feature_id = $feature_result[$j]['feature_id'];
							$unit_id = $feature_result[$j]['unit_id'];
							if (!empty($feature_id)) {
								$pivot_result = $oPivot->arrGetPivotDetails("", $categoryid, $feature_id, "1");
								$product_result = $oProduct->arrGetProductFeatureDetails("", $feature_id, $least_product_id);
							}
							$feature_value = $product_result[0]['feature_value'];
							if (!empty($feature_value)) {
								$featureNameArr[] = $feature_value;
							}
							if (!empty($unit_id)) {
								$unit_result = $oFeature->arrFeatureUnitDetails($unit_id, $categoryid, "1");
								$feature_unit = $unit_result[0]['unit_name'];
							} else {
								$feature_unit = "";
							}
							if (!empty($feature_unit) && !empty($feature_value) && $feature_value != '-') {
								$featureNameArr[] = $feature_unit;
							}
							$feature_value = implode(" ", $featureNameArr);
							unset($featureNameArr);
							if (strtolower($feature_value) == 'yes') {
								$feature_value = 'yes';
							} else if (strtolower($feature_value) == 'no') {
								$feature_value = 'no';
							}

							if ($feature_result[$j]['feature_name'] == 'Colours') {
								$feature_value = "";
							}
							$feature_result[$j]['product_feature_id'] = $product_result[0]['feature_id'];
							$feature_result[$j]['feature_value'] = $feature_value;
							$feature_result[$j]['pivot_feature_id'] = $pivot_result[0]['feature_id'];
							$feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
							$feature_result[$j]['js_feature_group'] = $feature_result[$j]['feature_group'];
							$feature_result[$j]['js_feature_desc'] = $feature_result[$j]['feature_description'];
							$feature_result[$j]['js_feature_unit'] = $feature_unit;
							$feature_result[$j]['feature_status'] = ($status == 1) ? 'Active' : 'InActive';
							$feature_result[$j]['feature_unit'] = $feature_unit ? html_entity_decode($feature_unit, ENT_QUOTES, 'UTF-8') : '';
							$feature_result[$j]['feature_group'] = $feature_result[$j]['feature_group'] ? html_entity_decode($feature_result[$j]['feature_group'], ENT_QUOTES, 'UTF-8') : '';
							$feature_description = $feature_result[$j]['feature_description'];
							if ($feature_description != "") {
								$feature_description = html_entity_decode($feature_description, ENT_QUOTES, 'UTF-8');
								$feature_description = str_replace('&amp;amp;', "", $feature_description);
								$feature_description = str_replace('&#039;', "'", $feature_description);
								$feature_description = str_replace('#039;', "'", $feature_description);
							}
							$feature_result[$j]['feature_desc'] = $feature_description ? html_entity_decode($feature_description, ENT_QUOTES, 'UTF-8') : '';
							$feature_result[$j]['create_date'] = date('d-m-Y', strtotime($feature_result[$j]['create_date']));
							$feature_result[$j]['js_feature_name'] = $feature_result[$j]['feature_name'];
							$feature_result[$j]['feature_name'] = $feature_result[$j]['feature_name'] ? html_entity_decode($feature_result[$j]['feature_name'], ENT_QUOTES, 'UTF-8') : '';
							$featureresult[$main_group_id][$feature_group][] = $feature_result[$j];
							$featureresult[$main_group_id][$feature_group]['sub_group_name'] = $feature_sub_group_array[0]['sub_group_name'];
							$featureresult[$main_group_id][$feature_group]['sub_group_id'] = $feature_group;
							$pivot_feature_id = $pivot_result[0]['feature_id'];
							$featureresult[$main_group_id][$feature_group]['is_pivot_group'] = ($pivot_feature_id == $feature_id) ? 'true' : 'false';
							$featureresult[$main_group_id][$feature_group]['pivot_feature_id'] = $pivot_feature_id;
							$featureresult[$main_group_id][$feature_group]['feature_id'] = $feature_id;
						}
					}
				}
				foreach ($result[$i] as $k => $v) {
					$featureresult[$main_group_id][$k] = $v;
				}
			}
		}

		//print_r($featureresult); die();
		$groupnodexml .= "<GROUP_MASTER>";
		if ($featureresult) {
			foreach ($featureresult as $maingroupkey => $maingroupval) {
				if (is_array($maingroupval)) {
					$groupnodexml .= "<GROUP_MASTER_DATA>";
					foreach ($maingroupval as $subgroupkey => $subgroupval) {
						if (is_array($subgroupval)) {
							$groupnodexml .= "<SUB_GROUP_MASTER>";
							foreach ($subgroupval as $key => $featuredata) {
								if (is_array($featuredata)) {
									$groupnodexml .= "<SUB_GROUP_MASTER_DATA>";
									$featuredata = array_change_key_case($featuredata, CASE_UPPER);
									foreach ($featuredata as $featurekey => $featureval) {

										$groupnodexml .= "<$featurekey><![CDATA[$featureval]]></$featurekey>";
									}
									$groupnodexml .= "</SUB_GROUP_MASTER_DATA>";
								} else {
									$groupnodexml .= "<" . strtoupper($key) . "><![CDATA[$featuredata]]></" . strtoupper($key) . ">";
								}
							}
							$groupnodexml .= "</SUB_GROUP_MASTER>";
						} else {
							$groupnodexml .= "<" . strtoupper($subgroupkey) . "><![CDATA[$subgroupval]]></" . strtoupper($subgroupkey) . ">";
						}
					}
					$groupnodexml .= "</GROUP_MASTER_DATA>";
				}
			}
		}
		$groupnodexml .= "</GROUP_MASTER>";

		//echo $router_product_name_id;
		/* gallery */
		if (!empty($router_product_name_id)) {
			//echo "asasa";
			$result2 = $oWallpapers->arrSlideShowDetails("", "", "", $product_name_id, "", $category_id, "", "1");
		}
		
		$result1[0]["video_img_path"] = $slideImagePath;
		
		$result = array_merge($result1,$result2);
		//echo "<pre>"; print_r($result); die("jjjjjjj");
		$total_cnt = sizeof($result);
		for ($i = 0; $i < $total_cnt; $i++) {
			$video_img_path = $result[$i]["video_img_path"];
			if (!empty($video_img_path)) {
				$video_img_path = resizeImagePath($video_img_path, "145X193", $aModuleImageResize, $video_img_id);
				$mid_video_img_path = resizeImagePath($video_img_path, "145X193", $aModuleImageResize, $video_img_id);
				$gallery_result[$i]['video_img_path'] = $mid_video_img_path ? CENTRAL_IMAGE_URL . $mid_video_img_path : IMAGE_URL . 'no_image_145X193.gif';
				$thumb_video_img_path = resizeImagePath($video_img_path, "45X60", $aModuleImageResize, $video_img_id);
				$gallery_result[$i]['thumb_video_img_path'] = $thumb_video_img_path ? CENTRAL_IMAGE_URL . $thumb_video_img_path : IMAGE_URL . 'no_image_45X60.gif';
				$gallery_result[$i]['image_title'] = $result[$i]["image_title"];
				$gallery_result[$i]['slideshow_title'] = $result[$i]["slideshow_title"];
			}
		}

		$gallery = '<GALLERY>';
		$gallery .= '<TOTAL>' . $total_cnt . '</TOTAL>';
		$gallery .= "<EXTERIOR_COUNT>" . $ephoto_cnt . "</EXTERIOR_COUNT>";
		$gallery .= "<INTERIOR_COUNT>" . $iphoto_cnt . "</INTERIOR_COUNT>";
		foreach ($gallery_result as $arr_gallery_details) {
			$gallery .= "<GALLERY_MAIN_IMAGE_DETAILS>" . $arr_gallery_details['video_img_path'] . "</GALLERY_MAIN_IMAGE_DETAILS>";
			$arr_gallery_details = array_change_key_case($arr_gallery_details, CASE_UPPER);
			$gallery .= "<GALLERY_DETAILS>";
			foreach ($arr_gallery_details as $key => $gallery_details) {
				$gallery .= "<$key>" . $gallery_details . "</$key>";
			}
			$gallery .= "</GALLERY_DETAILS>";
		}
		$gallery .= '</GALLERY>';
		/* gallery */

		unset($result);
		///////////////////expert review ///////////////////////////////////
		$expert_review_param =  implode(" ", array($brand_name ,$model_name));
		//echo EXPERT_REVIEW_API.rawurlencode(strtolower($expert_review_param));
		
		if($expert_review_param!=''){
			$expert_rating = $oReview->getBgrExpertReviews($expert_review_param);
		}
		$cmd = PHP_PATH . ' ' . BASEPATH . "api/average_rating_api.php" . " brand_id=$router_brand_id product_name_id=$router_product_name_id ";
		#echo $cmd;
		$user_rating_xml = shell_exec($cmd);
		$userrating_xml .= $user_rating_xml;

		if($action=="model" || $action == "all_review" || $action == "user_reviews"){
			if($action == "model") { $revlimit = 2;}
			if($action == "all_review" || $action == "user_reviews") { $revlimit = 10;}
			$cmd_user = PHP_PATH.' '.BASEPATH."api/latest_user_review_api.php brand_id=$router_brand_id product_name_id=$router_product_name_id limit=$revlimit";
			$latest_review_api_url = shell_exec($cmd_user);
			$latest_review_api_xml = $latest_review_api_url;
		}
		
		
        //print_r($_REQUEST);
		//NEWS LIST
		$search_key = $sModelBrandName." ".$search_product_info_name;
		if ($action == "news") {
		    if (!empty($category_id)) {
		        $limitcnt = 10;
		        $feed_url = "http://www.bgr.in/feed/?tag=" . rawurlencode(str_replace(" ", "-", strtolower($search_key)));
				//$feed_url = $product_feed_url ;
		        $content1 = @file_get_contents($feed_url);
		        $content = str_replace('&', '&amp;', $content1);
				//header('Content-type: text/xml');
				//echo $content; die;
		        if ($content1 != false) {
		            $x = new SimpleXmlElement($content);
		            $newscnt = count($x->channel->item);
		            $news_xml .= "<NEWS_LIST_MASTER>";
		            $news_xml .= "<ALL_NEWS><![CDATA[http://www.bgr.in/category/news/]]></ALL_NEWS>";
		            $news_xml .= "<COUNT><![CDATA[$newscnt]]></COUNT>";
		            $news_xml .= "<FEED_URL><![CDATA[$feed_url]]></FEED_URL>";
		            if ($newscnt > 0) {
		                $start_count = 0;
		                foreach ($x->channel->item as $entry) {
		                    if ($start_count == $limitcnt) {
		                        break;
		                    }
		                    $categoryNameArr = array();
							//print_r($entry); die;
		                    $news_xml .= "<NEWS_LIST_MASTER_DATA>";
		                    $news_xml .= "<SEO_URL>$entry->link</SEO_URL>";
		                    $news_xml .= "<TITLE>$entry->title</TITLE>";
		                    $dc = $entry->children('http://purl.org/dc/elements/1.1/');

		                    $author = $dc->creator;
		                    $disp_date = date('M d, Y  H:i A', strtotime($entry->pubDate));
		                    $news_xml .= "<DISP_DATE>$disp_date</DISP_DATE>";
		                    $description = getCompactString(strip_tags($entry->description), 50, true) . ' ...';
		                    $news_xml .= "<DESCRIPTION>$description</DESCRIPTION>";
		                    $news_xml .= "<AUTHOR>$author</AUTHOR>";
		                    $image_path = $entry->enclosure->attributes()->url;
		                    $news_xml .= "<IMAGE_PATH>$image_path</IMAGE_PATH>";
		                    $news_xml .= "<CATEGORIES>";
		                    foreach ($entry->category as $tag => $categoryName) {
		                        if (strlen($categoryNameArr[$tag]) > 0) {
		                            break;
		                        }
		                        $news_xml .= "<CATEGORY>$categoryName</CATEGORY>";
		                        $categoryNameArr[$tag] = $categoryName;
		                    }
		                    $news_xml .= "</CATEGORIES>";
		                    $news_xml .= "</NEWS_LIST_MASTER_DATA>";
		                    $start_count++;
		                }
		            }
		            $news_xml .= "</NEWS_LIST_MASTER>";
		        }
		    }
		}

		// Fetching mapped photos to model
		$xml_slideshow = '';
		if ($action == 'photos') {
		    $new_result_list = $oWallpapers->arrSlideShowDetails("", "", "", $router_product_name_id
		            , "", $category_id, $router_brand_id, 1, "", "", "", "", "0", "1");
		    $iCount = count($new_result_list);
		    if (is_array($new_result_list) && $iCount > 0) {
		        for ($i = 0; $i < $iCount; $i++) {
		            $title = $new_result_list[$i]["title"];
		            $slug = constructUrl($new_result_list[$i]["slug"]);
		            $new_result_list[$i]["slug"] = $slug;
		            $product_info_id = $new_result_list[$i]['product_info_id'];
		            $product_id = $new_result_list[$i]['product_id'];
		            $media_id = $new_result_list[$i]["slideshow_media_id"];
		            $media_path = $new_result_list[$i]["slideshow_media_path"];
		            $status = $new_result_list[$i]['status'];
		            $product_slide_id = $new_result_list[$i]['product_slide_id'];
		            if (!empty($title)) {
		                $result[$i]['title'] = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
		            }
		            if (!empty($media_path)) {
		               if($photoslug !=''){
		               		if ($slug == $photoslug) {
			                    $media_path = resizeImagePath($media_path, "375X500", $aModuleImageResize, $media_id);
			                } else {
			                    $media_path = resizeImagePath($media_path, "114X152", $aModuleImageResize, $media_id);
			                }
		               }else{
			                if ($i == 0) {
			                    $media_path = resizeImagePath($media_path, "375X500", $aModuleImageResize, $media_id);
			                } else {
			                    $media_path = resizeImagePath($media_path, "114X152", $aModuleImageResize, $media_id);
			                }
		            	}

		                $media_path = $media_path ? CENTRAL_IMAGE_URL . $media_path : '';
		            }
		            $new_result_list[$i]['media_path'] = $media_path;
		            $new_result_list[$i]['media_id'] = $media_id;
		            unset($seoTitleArr);
		    		$seoTitleArr[] = SEO_WEB_URL;
					$seoTitleArr[] = $_REQUEST['cat_path'];
					$seoTitleArr[] = $brand_seo_path;
					$seoTitleArr[] = $model_seo_path;
					$seoTitleArr[] = "photos";	
		            $seoTitleArr[] = constructUrl(html_entity_decode($slug, ENT_QUOTES, 'UTF-8'));
		            $seo_slide_featured_url = implode("/", $seoTitleArr);
		            $new_result_list[$i]["seo_slide_url"] = $seo_slide_featured_url . "/" . $slid_s;
		            $new_result_list[$i] = array_change_key_case($new_result_list[$i], CASE_UPPER);
		        }
		        $sModelPhotoListXML = arraytoxml($new_result_list, 'SLIDESHOW_MASTER_DATA');
		        $xml_slideshow.= "<SLIDESHOW_MASTER>";
		        $xml_slideshow.= $sModelPhotoListXML;
		        $xml_slideshow.= "</SLIDESHOW_MASTER>";
		    }
		}

		$sModelVideoListXML = '';
		if ($action == 'videos') {

		    $result = $videoGallery->getVideosDetails("", "", "", '', $router_product_name_id, $category_id, $router_brand_id, "1", $startc, $perpagec, "order by V.create_date desc");
		    //echo "<pre>"; print_r($result); die;
		    if (is_array($result) && count($result) > 0) {
		        $aResultData = array();
		        foreach ($result as $iK => $aData) {
		            $media_path = $aData['video_img_path'];
		            $media_id = $aData['media_id'];
		            if (!empty($media_path)) {
		                $media_path = resizeImagePath($media_path, "225X300", $aModuleImageResize, $media_id);
		            }
		            $result[$iK]['video_img_path'] = !empty($media_path) ? CENTRAL_IMAGE_URL . $media_path : '';

		            unset($seoTitleArr);
		            $slug = constructUrl($aData["slug"]);
		            $result[$iK]['slug'] = $slug;
		          /*  $tab_type_id = $aData['type_id'];
		            $seoTitleArr[] = SEO_WEB_URL;
		            $seoTitleArr[] = constructUrl(trim($aData['title']));
		            $seoTitleArr[] = constructUrl(trim($aData['video_id']));
		            $seo_video_tab_url = implode("/", $seoTitleArr);
		            $result[$iK]['seo_video_url'] = $seo_video_tab_url;*/
		            unset($seoTitleArr);
		    		$seoTitleArr[] = SEO_WEB_URL;
					$seoTitleArr[] = $_REQUEST['cat_path'];
					$seoTitleArr[] = $brand_seo_path;
					$seoTitleArr[] = $model_seo_path;
					$seoTitleArr[] = "videos";	
		            $seoTitleArr[] = constructUrl(html_entity_decode($slug, ENT_QUOTES, 'UTF-8'));
		            $seo_video_tab_url = implode("/", $seoTitleArr);
		            $result[$iK]['seo_video_url'] = $seo_video_tab_url;
		        }
		        usort($result, 'sortByArrayDate');
		        $sModelVideoListXML .= "<MODEL_VIDEO_LIST>";
		        $sModelVideoListXML .= arraytoxml($result, 'MODEL_VIDEO_LIST_DATA');
		        $sModelVideoListXML .= "</MODEL_VIDEO_LIST>";
		    }
		}

		//write user review //
    unset($result);
    $result = $userreview->arrGetQuestions();       
    $cnt = sizeof($result);        
    $xml .= "<QUESTIONAIRE_MASTER>";
    $xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
    for($i=0;$i<$cnt;$i++){             
        $queid = $result[$i]['queid'];
        $ansresult = $userreview->arrGetQueAnswer("",$queid);
        $anscnt = 0;
        $anscnt = sizeof($ansresult);
        $xml .= "<QUESTIONAIRE_MASTER_DATA>";
        $result[$i] = array_change_key_case($result[$i],CASE_UPPER);

        foreach($result[$i] as $k=>$v){
            $xml .= "<$k><![CDATA[$v]]></$k>";
        }
        $xml .= "<QUESTIONAIRE_ANS_MASTER>";
        $xml .= "<ANS_COUNT><![CDATA[$anscnt]]></ANS_COUNT>";
        for($ans=0;$ans<$anscnt;$ans++){    
            $ansresult[$ans] = array_change_key_case($ansresult[$ans],CASE_UPPER);
            $xml .= "<QUESTIONAIRE_ANS_MASTER_DATA>";
            foreach($ansresult[$ans] as $k=>$v){
                $xml .= "<$k><![CDATA[$v]]></$k>";
            }
            $xml .= "</QUESTIONAIRE_ANS_MASTER_DATA>";
        }
        $xml .= "</QUESTIONAIRE_ANS_MASTER>";                   
        $xml .= "</QUESTIONAIRE_MASTER_DATA>";                  
    }       
    $xml .= "</QUESTIONAIRE_MASTER>";


//write user review//



    	unset($modelnameSeoArr);
    	$modelnameSeoArr[] = SEO_WEB_URL;
    	$modelnameSeoArr[] = $_REQUEST['cat_path'];
        $modelnameSeoArr[] = $brand_seo_path;
        $modelnameSeoArr[] = $model_seo_path;
        $seo_model_url =  implode("/",$modelnameSeoArr);

        unset($modelnameSeoArr);
        $modelnameSeoArr[] = SEO_WEB_URL;
    	$modelnameSeoArr[] = $_REQUEST['cat_path'];
        $modelnameSeoArr[] = $brand_seo_path;
        $modelnameSeoArr[] = $model_seo_path;
        $modelnameSeoArr[] = "news";
        $seo_modelnews_url =  implode("/",$modelnameSeoArr);


        unset($modelnameSeoArr);
        $modelnameSeoArr[] = SEO_WEB_URL;
    	$modelnameSeoArr[] = $_REQUEST['cat_path'];
        $modelnameSeoArr[] = $brand_seo_path;
        $modelnameSeoArr[] = $model_seo_path;
        $modelnameSeoArr[] = "reviews";
        $seo_modelreview_url =  implode("/",$modelnameSeoArr);

        unset($modelnameSeoArr);
        $modelnameSeoArr[] = SEO_WEB_URL;
    	$modelnameSeoArr[] = $_REQUEST['cat_path'];
        $modelnameSeoArr[] = $brand_seo_path;
        $modelnameSeoArr[] = $model_seo_path;
        $modelnameSeoArr[] = "photos";
        $seo_modelphotos_url =  implode("/",$modelnameSeoArr);

        unset($modelnameSeoArr);
        $modelnameSeoArr[] = SEO_WEB_URL;
    	$modelnameSeoArr[] = $_REQUEST['cat_path'];
        $modelnameSeoArr[] = $brand_seo_path;
        $modelnameSeoArr[] = $model_seo_path;
        $modelnameSeoArr[] = "videos";
        $seo_modelvideo_url =  implode("/",$modelnameSeoArr);

        unset($modelnameSeoArr);
        $modelnameSeoArr[] = SEO_WEB_URL;
    	$modelnameSeoArr[] = $_REQUEST['cat_path'];
        $modelnameSeoArr[] = $brand_seo_path;
        $seo_modelbrand_url =  implode("/",$modelnameSeoArr);

        unset($modelnameSeoArr);
        $modelnameSeoArr[] = SEO_WEB_URL;
    	$modelnameSeoArr[] = $_REQUEST['cat_path'];
        $modelnameSeoArr[] = $brand_seo_path;
        $modelnameSeoArr[] = $model_seo_path;
        $modelnameSeoArr[] = "user-reviews";
        $seo_modeluserreview_url =  implode("/",$modelnameSeoArr);
        

        $moreon_result = $oProduct->moreOnCar($category_id, $rounter_brand_id, $router_product_name_id, $least_product_id);
        //print_r($moreon_result);
        $compare_tab_url = $moreon_result[1]['URL'];	


		/*****************Gadgets code optimize End******************/		
	/*****************************************************************************************************************************/
	
	//print "<pre>"; print_r($sImagePath); die();
#echo "T5===============>".date("Y-m-d H:i:s",time())."<br>"; 
$config_details = get_config_details();
$strXML .= "<XML>";
$login_details = getCookie();
$strXML .= $login_details;
$strXML .= $cpopularxml;
$strXML .= $strMoreOn;
$strXML .= $expert_rating;
$strXML .= $userrating_xml;
$strXML .= $news_xml;
$strXML .= $xml_slideshow;
$strXML .= $sModelVideoListXML;

$strXML .= getComponents('MODEL', getComponentParams()); // components xml
#echo "T5A===============>".date("Y-m-d H:i:s",time())."<br>"; 
$strXML .= "<UPCOMING_COUNT><![CDATA[$up_result]]></UPCOMING_COUNT>";
$strXML .= "<COMPARE_TAB_URL>$compare_tab_url</COMPARE_TAB_URL>";
$strXML .= "<PAGE_NAME><![CDATA[$page_name]]></PAGE_NAME>";
$strXML .= "<VIEW_DISP_TITLE><![CDATA[".$views_title."]]></VIEW_DISP_TITLE>";
$strXML .= "<SEO_CAR_FINDER><![CDATA[".SEO_CAR_FINDER."]]></SEO_CAR_FINDER>";
$strXML .= "<BREAD_CRUMB><![CDATA[$breadcrumb]]></BREAD_CRUMB>";
$strXML .= "<SEO_URL><![CDATA[$seo_url]]></SEO_URL>";
$strXML .= "<SEO_WEB_URL><![CDATA[".SEO_WEB_URL."]]></SEO_WEB_URL>";
$strXML .= "<SEO_AUTO_NEWS><![CDATA[".SEO_AUTO_NEWS."]]></SEO_AUTO_NEWS>";
$strXML .= "<ONCARS_SEO_URL><![CDATA[$oncars_seo_url]]></ONCARS_SEO_URL>";
$strXML .= "<EXPERTS_SEO_URL><![CDATA[$expert_seo_url]]></EXPERTS_SEO_URL>";
$strXML .= "<SEO_TITLE><![CDATA[$seo_title]]></SEO_TITLE>";
$strXML .= "<WRITE_REVIEW_LINK><![CDATA[$write_review_link]]></WRITE_REVIEW_LINK>";
$strXML .= "<SEO_DESC_OG><![CDATA[$seo_desc]]></SEO_DESC_OG>";
$seo_desc = "<meta name=\"Description\" content=\"$seo_desc\" />";
$seo_tags = "<meta name=\"Keywords\" content=\"$seo_keywords\" />";
$strXML .= "<SEO_DESC><![CDATA[".html_entity_decode($seo_desc ,ENT_QUOTES,"UTF-8")."]]></SEO_DESC>";
$strXML .= "<SEO_TAGS><![CDATA[".html_entity_decode($seo_tags ,ENT_QUOTES,"UTF-8")."]]></SEO_TAGS>";
$strXML .= "<MODEL_BRAND_NAME><![CDATA[$sModelBrandName ]]></MODEL_BRAND_NAME>";
$strXML .= "<MODEL_NAME><![CDATA[$search_product_info_name]]></MODEL_NAME>";
$strXML.= $xml;
$strXML.= $dealer_xml;
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= "<SELECTEDTABID><![CDATA[$tab_id]]></SELECTEDTABID>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<SELECTED_CITY_ID><![CDATA[$iCity_id]]></SELECTED_CITY_ID>";
$strXML .= "<SELECTED_CITY_NAME><![CDATA[$iCity_name]]></SELECTED_CITY_NAME>";
$strXML .= "<PHOTO_TAB_ID><![CDATA[$photo_tab_id]]></PHOTO_TAB_ID>";
$strXML .= "<SEO_PHOTO_TAB_URL><![CDATA[$seo_photo_tab_url]]></SEO_PHOTO_TAB_URL>";
$strXML .= "<SEO_VIDEO_TAB_URL><![CDATA[$seo_video_tab_url]]></SEO_VIDEO_TAB_URL>";
$strXML .= $config_details;
$strXML .= $sProductDetXml;
$strXML .= $srevDetailxml;
$strXML .= $sReviewDetailXml;
$strXML .= $groupnodexml;
$strXML .= $sArticleDetXml;
$strXML .= $sCityDetXml;
$strXML .= $strmediaxml;
$strXML .= $sTopCopmetitorsListing;
$strXML .= $sWallpapersDetXml;
$strXML .= $sProductInfoDetXml;
$strXML .= $sProductVersionDetXML;
$strXML .= $sBrandDataDetXML;
$strXML .= $sReviewDetailsDetXML;
$strXML .= $sProductRevImageDetXML;
$strXML .= $sReviewsGroupDetailXML;
$strXML .= $sRevIdDetXML;
$strXML .= $strRevGroupxml;
$strXML .= $sPhotoDetXML;
$strXML .= $sPhotoVideoDetXML;
$strXML .= "<OVERVIEW>$sOverviewXML</OVERVIEW>";
$strXML .= "<PRODSELCITY>$prodCityId</PRODSELCITY>";
$strXML .= "<PRODSELGROUP>$rev_grp_id</PRODSELGROUP>";
$strXML .= "<DEF_REVIEW_ID>$defRevId</DEF_REVIEW_ID>";
$strXML .= "<USERREVIEW_SEO_URL><![CDATA[$userreview_seo_url]]></USERREVIEW_SEO_URL>";
$strXML .= "<OC_ROS_TOP_RHS_LREC_300x250_1><![CDATA[OC_ROS_Top_RHS_Lrec_300x250_1]]></OC_ROS_TOP_RHS_LREC_300x250_1>";
$strXML .= "<OC_ROS_Right_Bottom_Lrec_300x250_2><![CDATA[OC_ROS_Right_Bottom_Lrec_300x250_2]]></OC_ROS_Right_Bottom_Lrec_300x250_2>";
$strXML.= $expert_review_url;
$strXML.= $video_review_url;
$strXML.= $latest_review_api_url;
$strXML.= "<MBREPLYLIST>";
$strXML.= $sReplyXml.$nodesPaging;
$strXML.= "<MBREPLYCOUNT><![CDATA[".$iRecCnt."]]></MBREPLYCOUNT>";
$strXML.= "<MBTID><![CDATA[".$iTId."]]></MBTID>";
$strXML.= "<CPAGE><![CDATA[".$page."]]></CPAGE>";
$strXML.= "<SERVICEID><![CDATA[".SERVICEID."]]></SERVICEID>";
$strXML.= "<CATEGORY><![CDATA[".$rev_category_id."]]></CATEGORY>";
$strXML.= "</MBREPLYLIST>";
$strXML.= "<DEALER_QUOTE_URL><![CDATA[$dealer_quote_url]]></DEALER_QUOTE_URL>";
$strXML.= "<BOOK_TEST_DRIVE_URL><![CDATA[$book_test_drive_url]]></BOOK_TEST_DRIVE_URL>";
$strXML.= "<COMPARE_CARS_URL><![CDATA[$compareCarsUrl]]></COMPARE_CARS_URL>";
$strXML.= "<CURR_CITY><![CDATA[".$curr_city_name."]]></CURR_CITY>";
$strXML.= "<CURR_CITY_ID><![CDATA[".$curr_city_id."]]></CURR_CITY_ID>";
$strXML.= "<DEALER_FLAG><![CDATA[".$dealer_flag."]]></DEALER_FLAG>";
$strXML.= "<RELATED_CITY_ID><![CDATA[".$related_city_id."]]></RELATED_CITY_ID>";
$strXML.= "<USER_SEL_CITYID><![CDATA[".$user_sel_cityid."]]></USER_SEL_CITYID>";
$strXML.= "<PAGING><![CDATA[$sPagingXml]]></PAGING>";
$strXML.= "<CATEGORY_ID><![CDATA[$category_id]]></CATEGORY_ID>";
$strXML.= "<BRAND_ID><![CDATA[$product_info_brand_id]]></BRAND_ID>";
$strXML.= "<PRODUCT_NAME_ID><![CDATA[$router_product_name_id]]></PRODUCT_NAME_ID>";
$strXML.= "<SELECTED_FEATURE_ID><![CDATA[$selected_feature_id]]></SELECTED_FEATURE_ID>";
$strXML.= "<SELECTED_FEATURE_NAME><![CDATA[$selected_feature_name]]></SELECTED_FEATURE_NAME>";
$strXML.= "<UPCOMING_START_PRICE><![CDATA[$upcoming_start_price]]></UPCOMING_START_PRICE>";
$strXML.= "<UPCOMING_END_PRICE><![CDATA[$upcoming_end_price]]></UPCOMING_END_PRICE>";
$strXML.= "<RETURN_REVIEW_URL><![CDATA[".$_SERVER['SCRIPT_URI']."]]></RETURN_REVIEW_URL>";
$strXML.= "<USER_REVIEW_ID><![CDATA[$user_review_id]]></USER_REVIEW_ID>";
$strXML.= "<OC_ROS_BOTTOM_NORTH_728x90><![CDATA[OC_ROS_Bottom_North_728x90]]></OC_ROS_BOTTOM_NORTH_728x90>";
$strXML.= "<OC_ROS_TOP_RHS_LREC_300x250_1><![CDATA[OC_ROS_Top_RHS_Lrec_300x250_1]]></OC_ROS_TOP_RHS_LREC_300x250_1>";
$strXML.= "<OC_ROS_Right_Bottom_Lrec_300x250_2><![CDATA[OC_ROS_Right_Bottom_Lrec_300x250_2]]></OC_ROS_Right_Bottom_Lrec_300x250_2>";
$strXML.= "<OC_Home_Right_Bottom_Lrec_300x250_3><![CDATA[OC_Home_Right_Bottom_Lrec_300x250_3]]></OC_Home_Right_Bottom_Lrec_300x250_3>";
$strXML.= "<OC_HOME_RHS_BOTTOM_LREC_300x250_2_MID><![CDATA[OC_Home_RHS_Bottom_Lrec_300x250_2]]></OC_HOME_RHS_BOTTOM_LREC_300x250_2_MID>";
$strXML.= "<COMMENT_COUNT><![CDATA[".$iRecCnt."]]></COMMENT_COUNT>";
$strXML.= "<VIEWS_COUNT><![CDATA[$views_count]]></VIEWS_COUNT>";
$strXML.= "<VIEWS_PAGE_NAME><![CDATA[".$views_page_name."]]></VIEWS_PAGE_NAME>";
$strXML.= $expertratingxml;
$strXML.= "<CHECKLOCATION><![CDATA[". $_COOKIE['changenloc']."]]></CHECKLOCATION>";
$strXML.= "<INTERIOR_COUNT><![CDATA[". $iphoto_cnt."]]></INTERIOR_COUNT>";
$strXML.= "<EXTERIOR_COUNT><![CDATA[". $ephoto_cnt."]]></EXTERIOR_COUNT>";
$strXML.= "<VIDEO_CNT><![CDATA[". $video_cnt."]]></VIDEO_CNT>";
$strXML.= "<EMI_CALCULATOR_URL><![CDATA[". $emi_calculator_url."]]></EMI_CALCULATOR_URL>";
$strXML.= "<SEO_ALL_REVIEWS_SEO_URL><![CDATA[". $seo_all_reviews_seo_url."]]></SEO_ALL_REVIEWS_SEO_URL>";
$strXML.= "<SEO_EXPERT_REVIEWS_SEO_URL><![CDATA[". $seo_expert_reviews_seo_url."]]></SEO_EXPERT_REVIEWS_SEO_URL>";
$strXML.= "<ALL_VARIANT_PAGE><![CDATA[". $all_variant_page."]]></ALL_VARIANT_PAGE>";
$strXML.= "<MODEL_DISCONTINUE_STATUS><![CDATA[". $model_discontinue_status."]]></MODEL_DISCONTINUE_STATUS>";
$strXML.= "<THREE_MONTHS_PLUS_DISCONTINUE_DATE><![CDATA[". $three_months_plus_discontinue_date."]]></THREE_MONTHS_PLUS_DISCONTINUE_DATE>";
$strXML.= "<MODEL_UPCOMING_STATUS><![CDATA[". $model_upcoming_status."]]></MODEL_UPCOMING_STATUS>";

$strXML .= "<POPAD><![CDATA[$popad]]></POPAD>";
if(!empty($upcoming_end_price)){
    $strXML .= "<CARPRICE><![CDATA[$upcoming_end_price]]></CARPRICE>";
}else{
    $strXML .= "<CARPRICE><![CDATA[$lPrice]]></CARPRICE>";
}

$strXML.= "<ALL_VARIANT_URL><![CDATA[". $all_variant_url."]]></ALL_VARIANT_URL>";
$strXML.= "<ALL_REVIEW_URL><![CDATA[". $all_review_url."]]></ALL_REVIEW_URL>";
$strXML.= "<ALL_USERREVIEW_URL><![CDATA[". $all_userreview_url."]]></ALL_USERREVIEW_URL>";
$strXML.= "<ALL_EXPERTREVIEW_URL><![CDATA[". $all_expertreview_url."]]></ALL_EXPERTREVIEW_URL>";
$strXML.= "<ALL_VIDEOREVIEW_URL><![CDATA[". $all_videoreview_url."]]></ALL_VIDEOREVIEW_URL>";
$strXML.= "<MODEL_VIDEO_URL><![CDATA[". $model_video_url."]]></MODEL_VIDEO_URL>";
$strXML.= "<MODEL_PHOTO_URL><![CDATA[". $model_photo_url."]]></MODEL_PHOTO_URL>";
$strXML.= "<MODEL_UPCOMING><![CDATA[". $model_upcoming_status."]]></MODEL_UPCOMING>";
$strXML.= "<BRAND_UPCOMING><![CDATA[". $upcoming_brand."]]></BRAND_UPCOMING>";
$strXML .= $strModelVariant;
$strXML .= $gallery;
$strXML .= "<SET_POSITION>$start</SET_POSITION>";
$strXML .= "<USER_REVIEW_PAGING>".USER_REVIEW_PAGING."</USER_REVIEW_PAGING>";
$strXML .= "<ONROAD_SEO_URL>".$onroad_seo_url."</ONROAD_SEO_URL>";
$strXML .=  "<ONCARS_REVIEW_CATEGORYID>".ONCARS_REVIEW_CATEGORYID."</ONCARS_REVIEW_CATEGORYID>";
$strXML .=  "<USER_REVIEW_VARIANT_CATEGORY_ID>".USER_REVIEW_VARIANT_CATEGORY_ID."</USER_REVIEW_VARIANT_CATEGORY_ID>";
$strXML .= "<OC_RIGHT_BOTTOM_300X250><![CDATA[OC_Right_Bottom_300x250]]></OC_RIGHT_BOTTOM_300X250>";
$strXML .= $top_comp_search;
///$strXML .= $sAlternateCarListXML.$sSimilarCarListXML.$sOtherCarListXML.$sUpComingCarWidgetList;
$strXML .= $sProductNewsDetXml;

$strXML .= "<CAT_PATH><![CDATA[".$_REQUEST['cat_path']."]]></CAT_PATH>";
$strXML .= "<SELECTED_CATEGORY_NAME><![CDATA[".$_REQUEST['category_name']."]]></SELECTED_CATEGORY_NAME>";
$strXML .= "<BRAND_NAME><![CDATA[$sModelBrandName]]></BRAND_NAME>";
$strXML .= "<PRODUCT_NAME_ID>$product_name_id</PRODUCT_NAME_ID>";
$strXML .= "<WR_PRODUCT_ID>$least_product_id</WR_PRODUCT_ID>";
$strXML .= "<ACTION><![CDATA[$action]]></ACTION>";
$strXML .= "<CURRTAB_SEL><![CDATA[$currtab_sel]]></CURRTAB_SEL>";
$strXML .= "<CURRTAB_SEL_SUB><![CDATA[$currtab_subsel]]></CURRTAB_SEL_SUB>";
$strXML .= "<MODEL_SEO_URL><![CDATA[".$seo_model_url."]]></MODEL_SEO_URL>";
$strXML .= "<MODELNEWS_SEO_URL><![CDATA[".$seo_modelnews_url."]]></MODELNEWS_SEO_URL>";
$strXML .= "<MODELREVIEWS_SEO_URL><![CDATA[".$seo_modelreview_url."]]></MODELREVIEWS_SEO_URL>";
$strXML .= "<MODELPHOTOS_SEO_URL><![CDATA[".$seo_modelphotos_url."]]></MODELPHOTOS_SEO_URL>";
$strXML .= "<MODELVIDEOS_SEO_URL><![CDATA[".$seo_modelvideo_url."]]></MODELVIDEOS_SEO_URL>";
$strXML .= "<COMPARE_TAB_URL>$compare_tab_url</COMPARE_TAB_URL>";
$strXML .= "<MODELBRAND_SEO_URL><![CDATA[".$seo_modelbrand_url."]]></MODELBRAND_SEO_URL>";
$strXML .= "<MODEL_USER_REVIEW_URL><![CDATA[".$seo_modeluserreview_url."]]></MODEL_USER_REVIEW_URL>";
$strXML .= "<MODEL_USER_REVIEW>$latest_review_api_xml</MODEL_USER_REVIEW>";
$strXML .= "<MODEL_PHOTO_SLUG>$photoslug</MODEL_PHOTO_SLUG>";
$strXML .= "<MODEL_VIDEO_SLUG>$videosslug</MODEL_VIDEO_SLUG>";


$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
##echo "T6===============>".date("Y-m-d H:i:s",time())."<br>"; die();
#header('Content-type: text/xml');echo $strXML;exit;
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }
$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();
$xslt = new xsltProcessor;    $xslt->registerPHPFunctions();
if($model_upcoming_status==1){
    $xsl = DOMDocument::load('xsl/model_page.xsl');
}else{
	//echo "REQUEST_URI = ".$action; die;
	if($action=="user_reviews"){
		$xsl = DOMDocument::load('xsl/model_user_review_detail.xsl');
	    	//$xsl = DOMDocument::load('xsl/model_page.xsl');
	}else if($action === 'news') {
		$xsl = DOMDocument::load('xsl/model_news_details.xsl');
    }else if($action === 'all_review') {
		$xsl = DOMDocument::load('xsl/model_review_page.xsl');
    }else if($action === 'photos') {
	     $xsl = DOMDocument::load('xsl/photos_model_page.xsl');
	}else if($action === 'videos') {
	      $xsl = DOMDocument::load('xsl/videos_model_page.xsl');
	}else{
	    	$xsl = DOMDocument::load('xsl/model_page.xsl');
	}
}

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
