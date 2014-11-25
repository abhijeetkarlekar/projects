<?xml version="1.0" ?>
<!DOCTYPE xsl:stylesheet  [
<!ENTITY nbsp   "&#160;">
<!ENTITY copy   "&#169;">
<!ENTITY reg    "&#174;">
<!ENTITY trade  "&#8482;">
<!ENTITY mdash  "&#8212;">
<!ENTITY ldquo  "&#8220;">
<!ENTITY rdquo  "&#8221;">
<!ENTITY pound  "&#163;">
<!ENTITY yen    "&#165;">
<!ENTITY euro   "&#8364;">
]>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" version="4.0" encoding="UTF-8" indent="yes"/>
<xsl:include href="../xsl/inc_header.xsl" /><!-- include header-->
<xsl:include href="../xsl/inc_footer.xsl" /><!-- include footer-->
<xsl:include href="../xsl/inc_leftnavigation.xsl" /><!-- include left navigation-->
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gadgets Admin</title>

<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/text.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/grid.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/nav.css" media="screen" />

<link href="css/fancy-button/fancy-button.css" rel="stylesheet" type="text/css" media="screen"/>
 <link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.core.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.resizable.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.selectable.css" media="screen" />
<!-- <link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.accordion.css" media="screen" /> -->
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.autocomplete.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.button.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.dialog.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.slider.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.tabs.css" media="screen" /> 
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.datepicker.css"  />
<link rel="stylesheet" type="text/css" href="css/themes/base/jquery.ui.progressbar.css" media="screen" />
<!--Jquery UI CSS-->
<!-- <link href="css/themes/base/jquery.ui.all.css" rel="stylesheet" type="text/css" /> -->
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" /><![endif]-->

<link href="css/table/demo_page.css" rel="stylesheet" type="text/css" />
<!-- BEGIN: load jquery -->
<script src="{XML/ADMIN_JS_URL}jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.core.min.js"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.widget.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.accordion.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.core.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.slide.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.mouse.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.sortable.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}table/jquery.dataTables.min.js" type="text/javascript"></script>
<!-- END: load jquery -->
<!--script type="text/javascript" src="{XML/ADMIN_JS_URL}table/table.js"></script-->
<script src="{XML/ADMIN_JS_URL}setup.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.widget.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.progressbar.min.js" type="text/javascript"></script>
<script>
var admin_web_url = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';
var image_url = '<xsl:value-of select="/XML/ADMIN_IMAGE_URL" disable-output-escaping="yes"/>';
</script>
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}common.js"></script>
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}category.js"></script>
<!-- <script src="{XML/ADMIN_JS_URL}jquery.ui.datepicker.js"></script> -->
<script language="javascript" src="{XML/ADMIN_JS_URL}slideshow.js"></script>
<script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
	setupLeftMenu();
	$('.datatable').dataTable();
	setSidebarHeight();
	setupTinyMCE();
    setupProgressbar('progress-bar');
    setDatePicker('date-picker');
});

					<![CDATA[
					
function isBlank(s){
	s = removeSpace(s);
	var len=s.length;
	var cnt;
	if(s.length==0){return true;}
	return false;
}
function removeSpace(s){
	return s.replace(/(^\s*)|(\s*$)/g, "");
}

//Function to Create/Remove one file browse element for uploading an image file
function addRemoveFileBrowseElements(iAddOrRemove){
	//get the total number of file browse elements
	var iTotalRowsCurrent = document.getElementById('display_rows').value;
	if(iAddOrRemove==0){
		//remove one file browse element
		if(iTotalRowsCurrent>1){
			document.getElementById('slideshowtbody').removeChild(document.getElementById("tr"+iTotalRowsCurrent));
			document.getElementById('display_rows').value=iTotalRowsCurrent-1;
			//alert(document.getElementById('display_rows').value);
			if(document.getElementById('display_rows').value==0){
				//hide remove button
				document.getElementById('remove').style.visibility="hidden";
			}
		}
	}else{
		//add one file browse element 
		iTotalRowsCurrent++;
		//create a tr containing the file browse element along with desc textarea
		var oTr = document.createElement("TR");
		//append the TR to TBODY 

		document.getElementById('slideshowtbody').insertBefore(oTr, document.getElementById('trAddRemove'));
		oTr.setAttribute("id","tr"+iTotalRowsCurrent);
		oTr.setAttribute("style", "border:1px solid #d5d5d5;");
		var oTdUploadMedia = document.createElement("TD");
		oTr.appendChild(oTdUploadMedia);
		oTdUploadMedia.setAttribute("valign", "left");
		//oTdUploadMedia.setAttribute("style", "border:1px solid #d5d5d5;");

		oTdUploadMedia.innerHTML="<label>Slide Image "+iTotalRowsCurrent+":</label>";

		var oTdUploadMediaFileBrowse = document.createElement("TD");
		oTr.appendChild(oTdUploadMediaFileBrowse);

		oTdUploadMediaFileBrowse.setAttribute("colspan", "10");
		//oTdUploadMediaFileBrowse.setAttribute("style", "border:1px solid #d5d5d5;");
		var html = "<label>Title </label><br/><input class='medium' type='text' name='title_"+iTotalRowsCurrent+"' id='title_"+iTotalRowsCurrent+"' value=''/> <br/><label>Slug </label><br/><input class='medium' type='text' name='slug_"+iTotalRowsCurrent+"' id='slug_"+iTotalRowsCurrent+"' value=''/> <br/> <label>Tags  </label><br/><input class='medium' type='text' name='tags_"+iTotalRowsCurrent+"' id='tags_"+iTotalRowsCurrent+"' value=''/><br/><label>Meta description </label><br/><textarea name='meta_description_"+iTotalRowsCurrent+"' id='meta_description_"+iTotalRowsCurrent+"' cols='60' rows='2' ></textarea>";

		html +="<br/><label>Upload Media (upload video/audio/image) "+iTotalRowsCurrent+"</label><br/> <input name='media_id_"+iTotalRowsCurrent+"' type='hidden' size='40' id='media_id_"+iTotalRowsCurrent+"' value=''/><input name='media_upload_1_"+iTotalRowsCurrent+"' type='hidden' size='40' id='img_upload_id_1_"+iTotalRowsCurrent+"' value=''/><input class='medium' type='text' name='title_upload_file_"+iTotalRowsCurrent+"' id='title_upload_file_"+iTotalRowsCurrent+"' value='' /><input type='hidden' name='video_content_type_"+iTotalRowsCurrent+"' id='video_content_type_"+iTotalRowsCurrent+"' value=''/><input type='button' name='btn_get' id='btn_get' value='media upload' onclick='getUploadData(\"product_manage\",\"title_upload_file_"+iTotalRowsCurrent+"\",\"media_id_"+iTotalRowsCurrent+"\",\"img_upload_id_1_"+iTotalRowsCurrent+"\",\"image\",\"video_content_type_"+iTotalRowsCurrent+"\");'/>";

		html += '<br/><span style="color:red;font-family:bold;">OR</span><br/>';

		html += "<label>Upload Thumbnail mage "+iTotalRowsCurrent+":</label><br/><input name='img_media_id_"+iTotalRowsCurrent+"' type='hidden' size='40' id='img_media_id_"+iTotalRowsCurrent+"' value=''/><input name='img_upload_id_thm_"+iTotalRowsCurrent+"' type='hidden' size='40' id='img_upload_id_thm_"+iTotalRowsCurrent+"' value=''/><input class='medium' type='text' name='thumb_title_"+iTotalRowsCurrent+"' id='thumb_title_"+iTotalRowsCurrent+"' value='' /><input type='hidden' name='content_type_"+iTotalRowsCurrent+"' id='content_type_"+iTotalRowsCurrent+"' value=''/><input type='button' name='btn_get' id='btn_get' value='image upload' onclick='getUploadData(\"product_manage\",\"thumb_title_"+iTotalRowsCurrent+"\",\"img_media_id_"+iTotalRowsCurrent+"\",\"img_upload_id_thm_"+iTotalRowsCurrent+"\",\"image\",\"content_type_"+iTotalRowsCurrent+"\");'/><input type='checkbox' name='box_"+iTotalRowsCurrent+"' id='box_"+iTotalRowsCurrent+"' value='' /> Delete <input type='hidden' name='check_flag_"+iTotalRowsCurrent+"' id='check_flag_"+iTotalRowsCurrent+"' value=''/><br/><label>postion "+iTotalRowsCurrent+":</label><input type='text' class='mini' name='ordering_"+iTotalRowsCurrent+"' id='ordering_"+iTotalRowsCurrent+"' value=''/>";

		oTdUploadMediaFileBrowse.innerHTML= html;
		//increment the total rows count
		document.getElementById('display_rows').value=iTotalRowsCurrent;
		if(document.getElementById('display_rows').value>1){
			//hide remove button
			document.getElementById('remove').style.visibility="";
		}
	}
}

					]]>

</script>
<style>
				#myPopUp{
					position:absolute;top:668px;left:155px;width:970px;height:550px;background:#ccc;overflow-x:hidden;overflow-y:auto;
					display:none;
				}
			</style>
			<div id="myPopUp">
				here data will appear
			</div>
</head>
<body>
	<div class="container_12">
		<div class="grid_12 header-repeat">
			<xsl:call-template name="incHeader"/>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<xsl:call-template name="incLeftNavigation"/>
		<div class="grid_10">
			<div class="box round first grid">
				<h2>Slideshow Managemnt</h2>
				<div class="block">
					<table class="form">
						
						<tr>
							<td>
								<div id="ajaxloader" style="display:none;">
									<div align="center">
										<img src="{/XML/ADMIN_IMAGE_URL}ajax-loader.gif"/>
									</div>
								</div>
								<div id="category_ajax" style="display:none;"></div>
								<div >
                                            <!-- <input type="button" name="select_box" value="Select" id="select_box_id" class="formbtn" onclick="javascript: getProductModelDashboard('prod_article_dashboard','productajaxloader','','{/XML/STARTLIMIT}','{/XML/CNT}');"/> -->
                                            <button class="btn btn-navy" name="select_category" id="select_category" onclick="javascript: getSlideshowDashboard('prod_article_dashboard','productajaxloader','','{/XML/STARTLIMIT}','{/XML/CNT}');">Select Category</button>	
                                        </div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		  
                            <div id="prod_article_dashboard" style="display:block;">
                                        	Please select category for Product information.
                            </div>
		<div class="clear">
		</div>
	</div>
	<div id="productajaxloader" style="display:none;">
                                <div align="center">
                                    <img src="{/XML/ADMIN_IMAGE_URL}ajax-loader.gif"/>
                                </div>
                            </div>
	<div class="clear">
	</div>
	<xsl:call-template name="incFooter"/>
</body>
   <script>
            var admin_web_url = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';
			var image_url = '<xsl:value-of select="/XML/ADMIN_IMAGE_URL" disable-output-escaping="yes"/>';
			var siteURL = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';

             $(document).ready(function() {
                category_details('<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" diseable-output-escaping="yes"/>','category_ajax','ajaxloader');
                <xsl:if test="/XML/SELECTED_CATEGORY_ID!=''">
                        getSlideshowDashboard('prod_article_dashboard','productajaxloader','<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/STARTLIMIT" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/CNT" diseable-output-escaping="yes"/>');
                </xsl:if>
            });
				$("#updateMe").click(function(){
					alert("ok,ok,ok");
				});
            </script>
</html>
</xsl:template>
</xsl:stylesheet>