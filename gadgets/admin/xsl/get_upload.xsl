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
<title>Typography | BlueWhale Admin</title>
<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/text.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/grid.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/nav.css" media="screen" />
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
<script>
var admin_web_url = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';
var image_url = '<xsl:value-of select="/XML/ADMIN_IMAGE_URL" disable-output-escaping="yes"/>';
</script>
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}common.js"></script>
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}category.js"></script>
<script src="{XML/ADMIN_JS_URL}jquery.ui.datepicker.js"></script>
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}brand.js"></script>
<script type="text/javascript">

var sform_name = '<xsl:value-of select="HOME/SFORMNAME"/>'
var stitle_text = '<xsl:value-of select="HOME/PARENT_TITLE"/>';
var media_hdtext = '<xsl:value-of select="HOME/PARENT_UPLOADHDTXT"/>';
var hdthumbtext = '<xsl:value-of select="HOME/PARENT_THUMBTXT"/>';
var sParPath = '<xsl:value-of select="HOME/PARENT_SPATH"/>';
var stype = '<xsl:value-of select="HOME/STYPE"/>';
var scontenttype = '<xsl:value-of select="HOME/PARENT_CONTENTPATH"/>';

var content_type = '<xsl:value-of select="/HOME/CONTENT_TYPE" disable-output-escaping="yes"/>';

function UpdateData(){
	
	var title = document.getElementById('media_title').value;
	var media_id = document.getElementById('media_id').value;
	var sPath = document.getElementById('spath').value;
	window.opener.document.getElementById(stitle_text).value = sPath;
	window.opener.document.getElementById(media_hdtext).value = media_id;
	window.opener.document.getElementById(sParPath).value = sPath;
	window.opener.document.getElementById(scontenttype).value = content_type;
	window.close();
}
</script>
</head>
<body>

				<div class="message success">
						<xsl:if test="/HOME/ERRMSG != ''">
							<div style="color:red;"><xsl:value-of select="/HOME/ERRMSG" disable-output-escaping="yes"/></div>
						</xsl:if>
						<form name="frmupload" method="post" action="" enctype="multipart/form-data">
		
						<table>
							<tr>
								<td>
									<label>Title</label>
								</td>	
								<td>
									<input class="warning" name="fld_title" type="text" id="fld_title" size="20" value=""/>
									<input name="media_id" type="hidden" id="media_id" size="20" value="{/HOME/MEDIAID}"/>
									<input name="media_title" type="hidden" id="media_title" size="20" value="{/HOME/MEDIATITLE}"/>
									<input name="thumb_image" type="hidden" id="thumb_image" size="20" value="{/HOME/MEDIATHUMBIMG}"/>
									<input name="spath" type="hidden" id="spath" size="20" value="{/HOME/MEDIAPATH}"/>
									<input name="scontentpath" type="hidden" id="scontentpath" size="20" value="{/HOME/PARENT_CONTENTPATH}"/>
									<div id="fld_title_div" class="rederr"></div>
								</td>
							</tr>
							<tr>
								<td><label>Tags</label></td>
								<td>	
									<input class="warning" name="fld_tags" type="text" id="fld_tags" size="20" value=""/>
									<div id="fld_tags_div" class="rederr"></div>
								</td>
							</tr>
							<tr>
								<td>
									<label>Description</label>
								</td>
								<td>	
									<textarea name="fld_description" id="fld_description" cols="40" rows="2"></textarea>
									<div id="fld_description_div"></div>
								</td>
								<td>
									<img src="{/HOME/CENTRAL_IMAGE_URL}{/HOME/MEDIATHUMBIMG}" id="img_name" name="img_name" widht="50" height="50"/>
								</td>
							</tr>
							<tr>
								<td></td>
							</tr>
							<tr>
								<td>
									<label>Select Size</label>
								</td><td>	
									<span class="gr">
									<select name="imagesize[]" size='4' multiple='yes'> <xsl:value-of select="HOME/IMAGE_SIZE_LIST" disable-output-escaping="yes"/></select>
									</span>
								</td>
							</tr>
						 	<tr>
								<td><label>Add FTP File Name </label>
								</td>
								<td>
									<span class="gr">
									<input class="warning" type="text" name="ftp_file" id="ftp_file" value="" size="30"/>
									</span>
								</td>
								<td>OR</td>
							</tr>
							<tr>
								<td><label>Upload file</label>
							</td>
							<td><input type="file" name="upload_file" id="upload_file" value=""/></td>
							</tr>
							<tr>
								<td>
								<input type="submit" name="btn_save" id="btn_save" value="upload"/>
								</td>
								<td>
								<input type="button" name="btn_done" id="btn_done" value="Done" onclick="UpdateData();"/>
								</td>
								<td>
								<input type="button" name="btn_close" id="btn_close" value="close without saving" onclick="window.close();"/>
								</td>
							</tr>
						</table>

				
	</form>
	
				</div>
			
		
	
	
	
</body>

</html>
</xsl:template>
</xsl:stylesheet>