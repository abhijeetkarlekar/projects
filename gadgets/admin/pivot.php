<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'pivot.class.php');
	
	$dbconn = new DbConn;
	$pivot = new PivotManagement;
	$category_id = $_REQUEST['selected_category_id'];
	$category_id = ($category_id != "") ? $category_id : SITE_CATEGORY_ID;
	$pivot_id = $_REQUEST['pivot_id'];
	$actiontype = $_REQUEST['actiontype'];
	$pivotboxcnt = $_REQUEST['pivotboxcnt'];
	$startlimit = $_REQUEST['startlimit'];
	$limitcnt = $_REQUEST['cnt'];

	if($actiontype == 'Update' || $actiontype == 'insert'){
		$request_param['category_id'] = $category_id;
		for($i=0;$i<$pivotboxcnt;$i++){
			$feature_id = $_REQUEST['select_pivot_name_'.$i];
			if(!empty($feature_id)){				
				$pivot_group = $_REQUEST['select_pivot_group_'.$i] ? $_REQUEST['select_pivot_group_'.$i] : $_REQUEST['pivot_group_'.$i];
				$pivot_group = trim($pivot_group);
				$pivot_group = htmlentities($pivot_group,ENT_QUOTES);
				$pivot_description = htmlentities($_REQUEST['pivot_description_'.$i],ENT_QUOTES);	
				$pivot_description = trim($pivot_description);
				$pivot_display_id = $_REQUEST['pivot_style_'.$i];
				$pivot_status = $_REQUEST['pivot_status_'.$i];
				
				$request_param['feature_id'] = $feature_id;
				$request_param['pivot_group'] = htmlentities($pivot_group,ENT_QUOTES);				
				$request_param['pivot_desc'] = $pivot_description;
				$request_param['pivot_display_id'] = $pivot_display_id;
				$request_param['status'] = $pivot_status;
				
				$uploadedfile_id = "uploadedfile_".$i;
                        	if($_FILES[$uploadedfile_id]["name"] != ""){
					$target_path = BASEPATH."images/";
        	                        $name = $_FILES[$uploadedfile_id]['name'];
	                                $name = strtolower(str_replace(' ', '_', trim($name)));

                        	        $target_path = $target_path.$name;
                	                rename($_FILES[$uploadedfile_id]['tmp_name'], $target_path);
        	                        $request_param['pivot_image'] = $name;
	                        }					
				if($actiontype == 'Update'){
        	   		$result = $pivot->boolUpdatePivotDetails($pivot_id,$request_param);
           			$msg = 'Pivot updated successfully.';
	        	}elseif($actiontype == 'insert'){
					$result = $pivot->intInsertPivotDetails($request_param);
           			$msg = ($result == 'exists') ? 'Pivot already exists.' : 'Pivot added successfully.';
        		}
			}
		}
	}elseif($actiontype == 'Delete'){
		$result = $pivot->boolDeletePivotDetails($pivot_id);
        $msg = 'Pivot deleted successfully.';
    }
	
	$config_details = get_config_details();

	$strXML = "<XML>";
	$strXML .= $config_details;
	$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
    $strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
    $strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
	$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>"; 
	$strXML .= $xml;
	$strXML .= "</XML>";
	//header('Content-type: text/xml');echo $strXML;exit;
	$doc = new DOMDocument();
	$doc->loadXML($strXML);
	$doc->saveXML();
	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('xsl/pivot.xsl');
	$xslt->importStylesheet($xsl);
	print $xslt->transformToXML($doc);
?>
