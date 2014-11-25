<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'category.class.php');
	$dbconn = new DbConn;
	$category = new CategoryManagement;
	//print_r($_REQUEST);
	$category_level = $_REQUEST['selected_category_id'];
	$category_name = trim($_REQUEST['category_name']);
	$status = $_REQUEST['category_status'];
	$category_id = $_REQUEST['category_id'];
	$actiontype = $_REQUEST['actiontype'];
	$seo_path = $_REQUEST['seo_path'];
	//$request_param = array('category_name'=>$category_name,'status'=>$status,'category_level'=>$category_level);
	if(!empty($category_name)){
		$request_param['category_name'] = htmlentities($category_name,ENT_QUOTES);
	}
	if($status != ''){
		$request_param['status'] = $status;
	}
	if($seo_path != ''){
		$request_param['seo_path'] = $seo_path;
	}
	if($category_level != ''){
		$request_param['category_level'] = $category_level;
	}
	if($actiontype == 'Delete'){
	   $result = $category->boolDeleteCategory($category_id);
	   $msg = 'Category deleted successfully.';
	}elseif($actiontype == 'Update'){
	   $result = $category->boolUpdateCategory($category_id,$request_param);
	   $msg = 'Category updated successfully.';
	}elseif($actiontype == 'insert'){
	   $result = $category->intInsertCategory($request_param);
	   $msg = ($result == 'exists') ? 'Category already exists.' : 'Category added successfully.';
	}
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$result = $category->arrGetCategoryDetails("","","",$startlimit,$limitcnt);
	$cnt = sizeof($result);
	$xml = "<CATEGORY_MASTER>";
	$xml .= "<COUNT><![CDATA[$cnt]]></COUNT>";
	for($i=0;$i<$cnt;$i++){
		$status = $result[$i]['status'];
		$result[$i]['category_status'] = ($status == 1) ? 'Active' : 'InActive';
		$result[$i]['create_date'] = date('d-m-Y',strtotime($result[$i]['create_date']));
		$result[$i]['js_category_name'] = $result[$i]['category_name'];
		$result[$i]['category_name'] = html_entity_decode($result[$i]['category_name'],ENT_QUOTES);
		$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
		$xml .= "<CATEGORY_MASTER_DATA>";
		foreach($result[$i] as $k=>$v){
			$xml .= "<$k><![CDATA[$v]]></$k>";
		}
		$xml .= "</CATEGORY_MASTER_DATA>";
	}
	$xml .= "</CATEGORY_MASTER>";

	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_level]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= "</XML>";
//	header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('xsl/category.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
