<?php
        require_once('../include/config.php');
        require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'wallpaper.class.php');
	
        $dbconn = new DbConn;
	$oWallpaper = new Wallpapers;	

	//if($_POST){ print_r($_REQUEST);} //die();
	$actiontype = $_REQUEST['actiontype'] ? $_REQUEST['actiontype'] : "Insert";
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$request_param=$_REQUEST;
	$category_id = $_REQUEST['selected_category_id'];

	if($actiontype == 'Insert'|| $actiontype== 'Update'){
		unset($request_param);
		$hd_view_section_id = $_REQUEST["hd_view_section_id"];
		$select_product_slide_id =  $_REQUEST["select_product_id"];
		$select_section_id = $_REQUEST["select_section_id"];
		$status = $_REQUEST["status"];
		$selected_category_id = $_REQUEST["selected_category_id"];

		if($select_product_slide_id != ""){$request_param['slide_id'] = $select_product_slide_id;}
		if($selected_category_id != ""){$request_param['category_id'] = $selected_category_id;}
		if($status != ""){$request_param['status'] = $status;}

		//print"<pre>";print_r($request_param);print"</pre>";exit;
	        $table_name = $select_section_id;
	        $result = $oWallpaper->intInsertFeaturedSlides($request_param,$table_name);

	        if($actiontype == 'Insert'){
        	       if($sresult>0){$msg = 'Slide added successfully.';}
		}elseif($actiontype == 'Update'){
        		if($sresult>0){$msg = 'Slide updated successfully.';}
		}
		
	}


	if($actiontype == 'Delete'){
		$slide_id = $_REQUEST["hd_product_slide_id"];
		$hd_view_section_id = $_REQUEST["hd_view_section_id"];
	        $table_name = $hd_view_section_id;
        	if($slide_id!=''){
                	$result = $oWallpaper->boolDeleteFeaturedSlides($slide_id,$table_name);
	                $msg = 'slide deleted successfully.';
        	}
	}

	$config_details = get_config_details();

        $strXML = "<XML>";
        $strXML .= "<MSG><![CDATA[$msg]]></MSG>";
        $strXML .= "<SELECTED_MENU_ID><![CDATA[$menu_level]]></SELECTED_MENU_ID>";
        $strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
        $strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
        $strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
        $strXML .= $config_details;
        $strXML .= $xml;
        $strXML .= "</XML>";

	if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }

        $doc = new DOMDocument();
        $doc->loadXML($strXML);
        $doc->saveXML();

        $xslt = new xsltProcessor;
        $xsl = DOMDocument::load('xsl/add_slides.xsl');

        $xslt->importStylesheet($xsl);
        print $xslt->transformToXML($doc);

?>

