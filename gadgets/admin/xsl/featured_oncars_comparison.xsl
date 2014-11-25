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
<title>Featured Comparison Admin</title>
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
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}featured_oncars_comparison.js"></script>
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
				<h2>Featured Comparison Managemnt</h2>
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
									<button class="btn btn-navy" name="select_category" id="select_category" onclick="javascript: getFeaturedOncarsComparisonDashboard('prod_article_dashboard','productajaxloader','','{/XML/STARTLIMIT}','{/XML/CNT}');">Select Category</button>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="prod_article_dashboard" style="display:block;">
			
    		
                Please select category for featured comparison information.
    		
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
$(document).ready(function() {
                category_details('<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" diseable-output-escaping="yes"/>','category_ajax','ajaxloader');
                <xsl:if test="/XML/SELECTED_CATEGORY_ID!=''">
                	getFeaturedOncarsComparisonDashboard('prod_article_dashboard','productajaxloader','<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/STARTLIMIT" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/CNT" diseable-output-escaping="yes"/>');
                </xsl:if>
		<xsl:if test="/XML/SELECTED_SECTION_ID!=''">
	                getFeaturedOncarsComapreSetDashboardByType('prod_article_dashboard','productajaxloader','<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/STARTLIMIT" diseable-output-escaping="yes"/>','<xsl:value-of select="/XML/CNT" diseable-output-escaping="yes"/>' ,'<xsl:value-of select="/XML/SELECTED_SECTION_ID" diseable-output-escaping="yes"/>');
		</xsl:if>
            });
				$("#updateMe").click(function(){
					alert("ok,ok,ok");
				});
</script>
</html>
</xsl:template>
</xsl:stylesheet>