<?php
	require_once('../../include/config.php');

	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'category.class.php');

	$dbconn = new DbConn;
	$category = new CategoryManagement;
	$category_level = $_REQUEST['catid'];

	if(!empty($category_level)){
		$breadcrumbresult = $category->arrGetCategoryBreadCrumb($category_level);
		#print_r($breadcrumbresult);die("WTF");
		$breadcrumbresultcnt = sizeof($breadcrumbresult);
		$breadCrumb = implode(BREAD_CRUMB_STR,$breadcrumbresult);
	}
	$xml = "<BREAD_CRUMB><![CDATA[$breadCrumb]]></BREAD_CRUMB>";

	$divid = $_REQUEST['divid'];
	$ajaxloaderid = $_REQUEST['ajaxloaderid'];
	$totalcatboxcnt = 1;
	#$selectedboxcnt = ($breadcrumbresultcnt <= 0) ? 0 : 1;
	$selectedboxcnt = 0;
	if($breadcrumbresultcnt > 0 && !empty($category_level)){
		$xml .= "<CATEGORY_LVL_SELECT>";
		foreach($breadcrumbresult as $category_id => $category_name){

			$result = $category->arrGetCategoryDetails("",$category_id);
			$cnt = sizeof($result);
			if($cnt > 0){
				$html = "<select class='suggestClass' id='categoryOptionsLevel_".$totalcatboxcnt."' size='8' onchange=\"javascript:category_level('categoryOptionsLevel_".$totalcatboxcnt."','$divid','$ajaxloaderid');\">";
				$html .= "<option value=''>---------------- Select Category ----------------</option>";
				for($i=0;$i<$cnt;$i++){
					$catid = $result[$i]['category_id'];
					$catname = $result[$i]['category_name'];
					if(array_key_exists($catid,$breadcrumbresult)){
						$html .= "<option value='$catid' selected='selected'>$catname</option>";
						$selectedboxcnt++;
					}else{
						$html .= "<option value='$catid'>$catname</option>";
					}
				 }
				$html .= "</select>";
				$xml .= "<CATEGORY_LVL_SELECT_BOX><![CDATA[$html]]></CATEGORY_LVL_SELECT_BOX>";
				$last_lvl_catid = end(array_flip($breadcrumbresult));
				if($last_lvl_catid == $category_id){
					$lastlevelhtml .= "<!--start below code is used to set last category id value and category name as a hidden field for use in form-->";
					$lastlevelhtml .= "<input type='hidden' name='last_lvl_catid' id='last_lvl_catid' value='$catid'/>";
					$lastlevelhtml .="<input type='hidden' name='last_lvl_cat_name' id='last_lvl_cat_name' value='$catname'/>";
					$lastlevelhtml .= "<!--start below code is used to set last category id value and category name as a hidden field for use in form-->";
				}
				$totalcatboxcnt++;
			}else{
				$lastlevelhtml .= "<!--start below code is used to set last category id value and category name as a hidden field for use in form-->";
				$lastlevelhtml .= "<input type='hidden' name='last_lvl_catid' id='last_lvl_catid' value='$category_id'/>";
				$lastlevelhtml .="<input type='hidden' name='last_lvl_cat_name' id='last_lvl_cat_name' value='$category_name'/>";
				$lastlevelhtml .= "<!--start below code is used to set last category id value and category name as a hidden field for use in form-->";
			}
			$hiddenhtml .= "<!--start below code is used to set category id value and category name as a hidden field for use in form(for {$totalcatboxcnt}st select box)-->";
			$hiddenhtml .= "<input type='hidden' name='categoryId_{$totalcatboxcnt}' id='categoryId_{$totalcatboxcnt}' value='$category_id'/>";
			$hiddenhtml .="<input type='hidden' name='categoryValue_{$totalcatboxcnt}' id='categoryValue_{$totalcatboxcnt}' value='$category_name'/>";
			$hiddenhtml .= "<!--end above code is used to set category id value and category name as a hidden field for use in form(for {$totalcatboxcnt}st select box)-->";


			$hiddenhtml .=	$lastlevelhtml;
		}
		$xml .= "<CATEGORY_LVL_HIDDEN_DATA><![CDATA[$hiddenhtml]]></CATEGORY_LVL_HIDDEN_DATA>";
		$xml .= "</CATEGORY_LVL_SELECT>";
	}


	$result = $category->arrGetCategoryDetails("","0");
	$cnt = sizeof($result);
	$xml .= "<ROOT_LVL_CAEGORY>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$category_id = $result[$i]['category_id'];
		$xml .= "<ROOT_LVL_DATA>";
		//if($breadcrumbresultcnt > 1 && !empty($category_level)){
			if(array_key_exists($category_id,$breadcrumbresult) && !empty($category_level)){
				$selectedboxcnt++;
				$xml .= "<SELECTED_CATEGORY><![CDATA[$category_id]]></SELECTED_CATEGORY>";
			}
		//}
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		foreach($result[$i] as $k=>$v){
			$xml .="<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</ROOT_LVL_DATA>";
	}
	$xml .= "</ROOT_LVL_CAEGORY>";

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= "<DIV_ID><![CDATA[$divid]]></DIV_ID>";
	$strXML .= "<AJAX_LOADER_ID><![CDATA[$ajaxloaderid]]></AJAX_LOADER_ID>";
	$strXML .= "<TOTAL_CAT_BOX><![CDATA[$totalcatboxcnt]]></TOTAL_CAT_BOX>";
	$strXML .= "<SELECTED_CAT_BOX><![CDATA[$selectedboxcnt]]></SELECTED_CAT_BOX>";
	$strXML .= "<SELECTED_CAT_ID><![CDATA[$category_level]]></SELECTED_CAT_ID>";
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('../xsl/category_select_ajax.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
