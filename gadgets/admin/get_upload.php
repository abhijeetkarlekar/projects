<?php
	set_time_limit(3600);
	ini_set("memory_limit","1024M");
	require_once("../include/config.php");
	require_once(UPLOAD_CLIENT_PATH.'Upload.php');


	$sTitle = $_REQUEST["rtitle"];
	$sUploadHdTxt = $_REQUEST["rid"];
	$sThumbTxt = $_REQUEST["rthmb"];
	$sType = $_REQUEST["rtype"];
	$sPath = $_REQUEST["rpath"];
	$sFrm = $_REQUEST["rfrm"];
	$sContenttypeLabel = $_REQUEST["rimgcat"];
	$ftp_file = $_REQUEST["ftp_file"];
	//print_r($_REQUEST);
	//print_r($_FILES);
	//exit;

	//if(is_array($_POST) && strlen($_FILES)>0){
	   //if(is_array($_FILES)){
		if(!empty($_FILES['upload_file']['name']) || !empty($ftp_file)){
			$sFileName = basename($_FILES['upload_file']['name']);
			$sTmpName = $_FILES['upload_file']['tmp_name'];
			$aFileName = explode(".",$sFileName);
			$sConvertName = md5($aFileName[0].time());
			$stype = $_FILES['upload_file']['type'];
			//echo $stype."TYPE";
			$ext=explode("/",$stype);
			//echo "MIME---".$mime = shell_exec("file -bi " . $sTmpName);
			$sUploadFileName = $sConvertName.".".$aFileName[1];

			$sUploadFilePath=UPLOAD_TMP_PATH;
			$sUploadFile=$sUploadFilePath.$sUploadFileName;
			rename($sTmpName,$sUploadFile);
			exec("chmod 0777 $sUploadFile");
			$aImageSize=Array();
			if(is_array($_POST['imagesize']) && count($_POST['imagesize'])>0){
				foreach($_POST['imagesize'] as $iK =>$iVal){
					$aImageSize[]=$aModuleImageResize[$iVal];
				}
			}

			$upload = new Upload;
			if(!empty($_FILES['upload_file']['name'])){
				$post_param = array("service_name"=>SERVICE,"service_id"=>SERVICEID,"title"=>$_POST['fld_title'],"tags"=>$_POST['fld_tags'],"desc"=>$_POST['fld_description'],"img_size"=>$aImageSize,"source_id"=>0,'file'=>trim($sUploadFile));
				$res = $upload->upload_method($post_param);
			}else{
				$post_param = array("service_name"=>SERVICE,"service_id"=>SERVICEID,"title"=>$_POST['fld_title'],"tags"=>$_POST['fld_tags'],"desc"=>$_POST['fld_description'],"img_size"=>$aImageSize,"source_id"=>0,'ftp_file'=>trim($ftp_file));
				$res = $upload->post_method($post_param);
			}
			//print_r($post_param);//exit;
		//	echo "here = ".$res;
			//print_r($res);exit;
			if(is_array($res)){
				if($res['status']=='success'){
					$aDetails=$res['details'];

					if(is_array($aDetails)){
						$sUploadMediaTitle=$aDetails['title'];
						$iMediaId=$aDetails['media_id'];
						$mimetype = $aDetails['content_type'];

						$aImgPath=$aDetails['img_path'];

						if($mimetype == 'image'){
							$mimetype = 2;
						}else if($mimetype == 'video'){
							$mimetype = 1;
						}else if($mimetype == 'audio'){
							$mimetype = 3;
						}



						if(is_array($aImgPath)){

							if(count($aImgPath)>0){
							  $sImage = array_pop($aImgPath);
							   $sImage;
							}else{$sImage = $aImgPath[0];}
						}
						if($mimetype!='2'){
							$sImage='';
						}
						$sErrorMsg="file uploaded successfully";

					}
				}else if($res['status']=='failed'){
					$sErrorMsg = $res['error']['0'];
				}
			}else{
				$sErrorMsg="file uploaded failed";
			}
			unlink($sUploadFile);
		}elseif(isset($_REQUEST["ftp_file"])){
			$sErrorMsg="Please upload file or add file name in FTP File Name";
		}
	   //}
	//}

//print_r($res);
//	echo $sErrorMsg;
	//get the dropdown of image  sizes
	$sModuleImageResize = getSelectedDropDownlising($aModuleImageResize,1);
	$sModuleImageResizeXml ='<IMAGE_SIZE_LIST><![CDATA['.$sModuleImageResize.']]></IMAGE_SIZE_LIST>';

	$config_details = get_config_details();

	//$sErrorMsg="";
	$strXML  = "<?xml version='1.0' encoding='iso-8859-1'?>";
	$strXML .= "<HOME>";
	$strXML .="<SITEPATH><![CDATA[".SITE_PATH."]]></SITEPATH>";
	$strXML .="<CENTRAL_IMAGE_URL><![CDATA[".CENTRAL_IMAGE_URL."]]></CENTRAL_IMAGE_URL>";

	$strXML .="<ERRMSG><![CDATA[".$sErrorMsg."]]></ERRMSG>";

	$strXML .="<MEDIAID><![CDATA[".$iMediaId."]]></MEDIAID>";
	$strXML .= "<CONTENT_TYPE><![CDATA[$mimetype]]></CONTENT_TYPE>";
	$strXML .="<MEDIATITLE><![CDATA[".$sUploadMediaTitle."]]></MEDIATITLE>";
	$strXML .="<MEDIATHUMBIMG><![CDATA[".$sImage."]]></MEDIATHUMBIMG>";
	$strXML .="<MEDIAPATH><![CDATA[".$sImage."]]></MEDIAPATH>";

	$strXML .="<SFORMNAME><![CDATA[".$sFrm."]]></SFORMNAME>";
	$strXML .="<PARENT_TITLE><![CDATA[".$sTitle."]]></PARENT_TITLE>";
	$strXML .="<PARENT_UPLOADHDTXT><![CDATA[".$sUploadHdTxt."]]></PARENT_UPLOADHDTXT>";
	$strXML .="<PARENT_THUMBTXT><![CDATA[".$sThumbTxt."]]></PARENT_THUMBTXT>";
	$strXML .="<PARENT_SPATH><![CDATA[".$sPath."]]></PARENT_SPATH>";
	$strXML .="<PARENT_CONTENTPATH><![CDATA[".$sContenttypeLabel."]]></PARENT_CONTENTPATH>";



	$strXML .="<STYPE><![CDATA[".$sType."]]></STYPE>";
	$strXML .=$sModuleImageResizeXml;
	$strXML .= $strSessionXML;
	$strXML .= $config_details;
	$strXML .="</HOME>";


	$doc = new DOMDocument();
	$doc->loadXML($strXML);

	$xslt = new xsltProcessor;
	$xsl = DOMDocument::load('xsl/get_upload.xsl');
	$xslt->importStylesheet($xsl);
	//print $xslt->transformToXML($doc);
	echo html_entity_decode($xslt->transformToXML($doc));
?>
