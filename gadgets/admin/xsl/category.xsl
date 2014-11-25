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
    <title>Gadgets  Admin</title>
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}reset.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}text.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}grid.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}layout.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}nav.css" media="screen" />
    <!--[if IE 6]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" /><![endif]-->
    <link href="css/table/demo_page.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN: load jquery -->
    <script src="{XML/ADMIN_JS_URL}jquery-1.6.4.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.core.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.widget.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.accordion.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.core.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.slide.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.mouse.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.sortable.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}table/jquery.dataTables.min.js" type="text/javascript"></script>
    <!-- END: load jquery -->
    <script src="{XML/ADMIN_JS_URL}table/table.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}setup.js" type="text/javascript"></script>
    <script type="text/javascript" src="{XML/ADMIN_JS_URL}common.js"></script>
    <script type="text/javascript" src="{XML/ADMIN_JS_URL}category.js"></script>
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
        <xsl:call-template name="incHeader"/>
        <div class="clear">
        </div>
        <div class="clear">
        </div>
        <xsl:call-template name="incLeftNavigation"/>
        
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Category Management</h2>
                <div class="block">
                    <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Category Name</th>
                            <th>Category SEO Path</th>
                            <th>Status</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:choose>
                            <xsl:when test="/XML/CATEGORY_MASTER/COUNT&lt;=0">
                                <tr>
                                    <td>
                                        <div align="center">Zero Result Found.</div>
                                    </td>
                                </tr>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:for-each select="/XML/CATEGORY_MASTER/CATEGORY_MASTER_DATA">
                                    <tr class="odd gradeX">
                                        <td>
                                            <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="SEO_PATH" diseable-output-esacaping="yes"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="CATEGORY_STATUS" diseable-output-esacaping="yes"/>
                                        </td>
                                        <td>
                                            <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                        </td>
                                        <td>
                                            <a href="#Update" onclick="updateCategory('{CATEGORY_ID}','{JS_CATEGORY_NAME}','{STATUS}','{CATEGORY_LEVEL}','{SEO_PATH}');">Update</a> | 
                                            <a href="javascript:undefined;" onclick="deleteCategory('{CATEGORY_ID}','{JS_CATEGORY_NAME}');">Delete</a>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                            </xsl:otherwise>
                        </xsl:choose>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Category</h2>
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
                    </td>
                    </tr>
                    <xsl:if test="/XML/CATEGORY_MASTER/COUNT&gt;0">
                    <tr><td></td><td></td><td><button class="btn btn-navy" >Select Category</button></td></tr>
                    </xsl:if>
                    </table>
                    </div>
            </div>
        </div>
        
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Category Add/Update</h2>
                <form action="{/XML/ADMIN_WEB_URL}category.php" method="post" name="category_action" id="category_action">
                    
                    <table class="form">
                    <tr>
                    <td>
                    <label>Category name</label>
                    </td>
                    <td>
                    <input type="text" name="category_name" id="category_name"/>
                    <input type="hidden" name="category_id" id="category_id" value=""/>
                    <input type="hidden" name="actiontype" id="actiontype" value="insert"/>
                    <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                    <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                    </td>
                    </tr>
                    <tr>
                    <td>
                    <label>Category SEO Path</label>
                    </td>
                    <td>
                    <input type="text" name="seo_path" id="seo_path"/>
                    </td>
                    </tr>
                    <tr>
                    <td>
                    <label>Status</label>
                    </td>
                    <td>
                    <select id="category_status" name="category_status">
                    <option value="1">Active</option>
                    <option value="0">InActive</option>
                    </select>
                    </td>
                    </tr>
                    <tr><td></td><td><button class="btn btn-navy" onclick="return validateCategory();">Add/Update</button><!-- <input type="submit" name="categorysubmit" id="categorysubmit" value="Add/Update" class="btn btn-navy"/><button class="btn btn-navy" onclick="return validateCategory();">Cancel</button> --></td></tr>
                    </table>
                </form>
            </div>
        </div>
	        <div class="clear"></div>
	    </div>
	    <div class="clear">
	 </div>
        <xsl:call-template name="incFooter"/>
</body>
        <script>
             $(document).ready(function() {
                category_details('<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" disable-output-escaping="yes"/>','category_ajax','ajaxloader');
             });
        </script>
        </html>
    </xsl:template>
</xsl:stylesheet>
   


