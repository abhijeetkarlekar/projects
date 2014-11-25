<?php
        require_once('../include/config.php');
        require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'brand.class.php');
	
        $dbconn = new DbConn;
	$brand = new BrandManagement;
	
	//if($_POST){ print_r($_REQUEST);} die();
	$actiontype = $_REQUEST['actiontype'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];
	$request_param=$_REQUEST;
	$category_id = $_REQUEST['catid'];
	$category_id = (!empty($category_id)) ? $category_id : $_REQUEST['selected_category_id'];
	$selected_brand_id = $_REQUEST['select_brand_id'] ? $_REQUEST['select_brand_id'] : $_REQUEST['selected_brand_id'] ;
	$brand_position = $_REQUEST['brand_position'] ? $_REQUEST['brand_position'] : $_REQUEST['brand_position'] ;

	if($actiontype == 'Insert' || $actiontype == 'Update'){
                //$model_id_arr =Array();
                //$model_id_arr = $_REQUEST['select_popular_model_id'];
                //$cnt = sizeof($model_id_arr);
                //if($cnt > 0){
                //	for($i=0;$i<$cnt;$i++){
                        	unset($request_param);
				$brand_id =  $_REQUEST["select_brand_id"];
				//$popular_model_id = $model_id_arr[$i];
                        	if(!empty($brand_id)){ $request_param['brand_id'] = $brand_id;}
				$request_param['brand_position'] = $brand_position;
                        	//if(!empty($popular_model_id)){ $request_param['popular_model_id'] = $popular_model_id;}
                        	$status = $_REQUEST['status'];
	                        if($status!=''){ $request_param['status'] = $status;}
				$request_param['category_id'] = $category_id;
	        		if($actiontype == 'Insert'){
		        		$result = $brand->intInsertPopularBrand($request_param);
				}elseif($actiontype == 'Update'){
					if($i == 0){
						$popular_id = $_REQUEST['popular_id'];
						$result = $brand->boolUpdatePopularBrand($popular_id,$request_param);
					}else{
						$result = $brand->intInsertPopularBrand($request_param);
					}
				}
			}
		
        	        if($sresult>0){
				if($actiontype == 'Insert'){
					$msg = 'popular brand added successfully.';
				}else{
					$msg = 'popular brand updated successfully.';
				}
		//	}
		//}
		
	}


	if($actiontype == 'Delete'){
		$popular_id = $_REQUEST["popular_id"];
        	if($popular_id != ''){
                	$result = $brand->boolDeletePopularBrand($popular_id);
	                $msg = 'popular brand deleted successfully.';
        	}
	}

	$config_details = get_config_details();

        $strXML = "<XML>";
        $strXML .= "<MSG><![CDATA[$msg]]></MSG>";
        $strXML .= "<SELECTED_MENU_ID><![CDATA[$menu_level]]></SELECTED_MENU_ID>";
        $strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
	$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
	$strXML .= "<SELECTED_BRAND_ID><![CDATA[$selected_brand_id]]></SELECTED_BRAND_ID>";
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
        $xsl = DOMDocument::load('xsl/popular_brand.xsl');

        $xslt->importStylesheet($xsl);
        print $xslt->transformToXML($doc);

?>

