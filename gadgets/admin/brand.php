<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'brand.class.php');
	require_once(CLASSPATH.'product.class.php');
	$dbconn = new DbConn;
	$brand = new BrandManagement;
	$oProduct = new ProductManagement;
	//print"<pre>";print_r($_REQUEST);print"</pre>";die();
	$category_id = $_REQUEST['catid'];
	$category_id = (!empty($category_id)) ? $category_id : $_REQUEST['selected_category_id'];
	$brand_level = (!empty($category_id)) ? $category_id : SITE_CATEGORY_ID;
	$brand_name = trim($_REQUEST['brand_name']);
	$short_desc = $_REQUEST["short_desc"];
	$long_desc = $_REQUEST["long_desc"];
	$status = $_REQUEST['brand_status'];
	$brand_id = $_REQUEST['brand_id'];
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
    $limitcnt = $_REQUEST['cnt'];
    $seo_path = $_REQUEST['seo_path'];
	if(!empty($brand_name)){
		$request_param['brand_name'] = htmlentities($brand_name,ENT_QUOTES, "UTF-8");
	}
	$request_param['short_desc'] = htmlentities($short_desc,ENT_QUOTES, "UTF-8");
	$request_param['long_desc'] = htmlentities($long_desc,ENT_QUOTES, "UTF-8");
	if($status != ''){
		$request_param['status'] = $status;
	}
	if($seo_path != ''){
		$request_param['seo_path'] = $seo_path;
	}
        $end_date = $_REQUEST['end_date'];
        if(!empty($end_date)){
        	$request_param['discontinue_date'] = $end_date;
        }else{
        	$request_param['discontinue_date'] = "";
        }
	$discontinue_flag = $_REQUEST['discontinue_flag'];
        if($discontinue_flag=='on'){
                $request_param['discontinue_flag'] = "0";
        }else{
		$request_param['discontinue_flag'] = "1";
        	$request_param['discontinue_date'] = "";
	}
	$upcoming_brand = $_REQUEST['upcoming_brand'];
	if($upcoming_brand=='on'){
		$request_param['upcoming_brand'] = 1;
	}else{
		 $request_param['upcoming_brand'] = 0;
	}
	if($brand_level != ''){
		$request_param['category_id'] = $brand_level;
	}
	if($actiontype == 'insert' || $actiontype == 'Update'){
		if($_FILES["uploadedfile"]["name"] != ""){
			$target_path = BASEPATH."img/";
                	$name = $_FILES['uploadedfile']['name'];
	                $name = strtolower(str_replace(' ', '_', trim($name)));

        	        $target_path = $target_path.$name;
        	        $_FILES['uploadedfile']['tmp_name']."==========". $target_path."<br>";
                	$dec = rename($_FILES['uploadedfile']['tmp_name'], $target_path);
	                $request_param['brand_image'] = $name;
        	}
		if($_FILES["uploadedresearchimagefile"]["name"] != ""){
                        $target_path = BASEPATH."img/";
                        $research_img_name = $_FILES['uploadedresearchimagefile']['name'];
                        $research_img_name = strtolower(str_replace(' ', '_', trim($research_img_name)));

                        $target_path = $target_path.$research_img_name;
                        rename($_FILES['uploadedresearchimagefile']['tmp_name'], $target_path);
                        $request_param['brand_research_image'] = $research_img_name;
                }
	}
	//print"<pre>";print_r($request_param);print"</pre>";die();
	if($actiontype == 'Delete'){
	   $result = $brand->boolDeleteBrand($brand_id);
	   $msg = 'Brand deleted successfully.';
	}elseif($actiontype == 'Update'){
	   $result = $brand->boolUpdateBrand($brand_id,$request_param);
	   $msg = 'Brand updated successfully.';
	}elseif($actiontype == 'insert'){
	   $result = $brand->intInsertBrand($request_param);
	   $msg = ($result == 'exists') ? 'Brand already exists.' : 'Brand added successfully.';
	}
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$config_details = get_config_details();
	$strXML = "<XML>";
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$brand_level]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
	$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= $config_details;
	$strXML .= $xml;
	$strXML .= "</XML>";
	$strXML = mb_convert_encoding($strXML, "UTF-8");
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('xsl/brand.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
