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
<script language="javascript" src="{XML/ADMIN_WEB_URL}js/feature.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	setupLeftMenu();
	$('.datatable').dataTable();
	setSidebarHeight();
});
</script>
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
				<h2>Feature Management</h2>
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
								<div>
									<button class="btn btn-navy" name="select_category" id="select_category" onclick="javascript:getFeatureDashboard('feature_dashboard','featureajaxloader','','{/XML/STARTLIMIT}','{/XML/CNT}');">Select Category</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="feature_dashboard" style="display:block;">
			
    		
                Please select category for Feature information.
    		
		</div>
		<div class="clear">
		</div>
	</div>
	<div id="featureajaxloader" style="display:none;">
        <div align="center">
            <img src="{/XML/ADMIN_IMAGE_URL}ajax-loader.gif"/>
        </div>
    </div>
	<div class="clear">
	</div>
	<xsl:call-template name="incFooter"/>
</body>
 <script>
             var featureGroupArr = Array();
		var featureGroupIdsArr = Array();
		var featureUnitArr = Array();
		var featureUnitIdArr = Array();
		var featureMainGroupArr = Array();
		var featureMainGroupIdsArr = Array();
                $(document).ready(function() {
                    category_details('<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" diseable-output-escaping="yes"/>','category_ajax','ajaxloader');
                    <xsl:if test="/XML/SELECTED_CATEGORY_ID!=''">
                        getFeatureDashboard('feature_dashboard','featureajaxloader','<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/STARTLIMIT" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/CNT" diseable-output-escaping="yes"/>');
                    </xsl:if>
                });
		<xsl:for-each select="/XML/FEATURE_GROUP/FEATURE_GROUP_DATA">
			featureGroupIdsArr.push('<xsl:value-of select="SUB_GROUP_ID" disable-output-escaping="yes"/>'); 
			featureGroupArr.push('<xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/>'); 
		</xsl:for-each>
		<xsl:for-each select="/XML/FEATURE_GROUP_MASTER/FEATURE_GROUP_MASTER_DATA">
			featureMainGroupIdsArr.push('<xsl:value-of select="GROUP_ID" disable-output-escaping="yes"/>');
			featureMainGroupArr.push('<xsl:value-of select="MAIN_GROUP_NAME" disable-output-escaping="yes"/>');
                </xsl:for-each>
                <xsl:for-each select="/XML/FEATURE_UNIT/FEATURE_UNIT_DATA">
			featureUnitArr.push('<xsl:value-of select="UNIT_NAME" disable-output-escaping="yes"/>');
			featureUnitIdArr.push('<xsl:value-of select="UNIT_ID" disable-output-escaping="yes"/>');
		</xsl:for-each>
            </script>
</html>
</xsl:template>
</xsl:stylesheet>