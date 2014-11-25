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
<title>Admin Pivot Display Management</title>
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
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}pivot_display_type.js"></script>
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
            <h2>Pivot Display Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                           <th>Sr no.</th>
                            <th>Pivot Display Name</th>
                            <th>Status</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       <xsl:for-each select="/XML/PIVOT_DISPLAY_MASTER/PIVOT_DISPLAY_MASTER_DATA">
                            <tr>
                                <td>
                                    <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="PIVOT_DISPLAY_NAME" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="PIVOT_DISPLAY_STATUS" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <a href="#Update" onclick="updatePivotDisplayType('{PIVOT_DISPLAY_ID}','{JS_PIVOT_DISPLAY_NAME}','{STATUS}');">Update</a>
                                 | 
                                    <a href="javascript:undefined;" onclick="deletePivotDisplayType('{PIVOT_DISPLAY_ID}','{JS_PIVOT_DISPLAY_NAME}');">Delete</a>
                                </td>
                            </tr>
                        </xsl:for-each> 
                        <!-- <tr class="even gradeC">
                            <td>Trident</td>
                            <td>InternetExplorer 5.0</td>
                            <td>Win 95+</td>
                            <td class="center">5</td>
                            <td class="center">C</td>
                        </tr> -->
                       

                    </tbody>
                </table>
           </div>
        </div>
    </div>
    <div class="grid_10">
        <div class="box round first grid">
        <h2>Pivot Display Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form action="{/XML/ADMIN_WEB_URL}pivot_display_type.php" method="post" name="pivot_display_action" id="pivot_display_action" onsubmit="return validatePivotDisplayType();">
            
            <table class="form">
                                    <tr>
                                        <td><label>Pivot Display Name</label></td>
                                        <td>
                                            <input class="medium" type="text" name="pivot_display_name" id="pivot_display_name"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Status</label></td>
                                        <td>
                                            <select id="pivot_display_status" name="pivot_display_status">
                                                <option value="1">Active</option>
                                                <option value="0">InActive</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <input type="hidden" name="pivot_display_id" id="pivot_display_id" value=""/>
                                        <input type="hidden" name="actiontype" id="actiontype" value="insert"/>
                                        <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                                        <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button class="btn btn-navy" onclick="return validateBrand();">Add/Update</button>
                                        </td>
                                        <!-- <td>
                                            <input type="button" name="cancel" id="cancel" value="Cancel" onclick="javascript:this.form.reset();"/>
                                        </td> -->
                                    </tr>
                                </table>
            </form>

            </div>
        </div>
    </div>
		
		<div class="clear">
		</div>
	</div>
	
	<div class="clear">
	</div>
	<xsl:call-template name="incFooter"/>
</body>

</html>
</xsl:template>
</xsl:stylesheet>