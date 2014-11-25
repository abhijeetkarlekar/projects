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
    <xsl:include href="inc_header.xsl" /><!-- include header-->
    <xsl:include href="inc_footer.xsl" /><!-- include footer-->
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>:: Ajax - Admin Slides Dashboard Management ::</title>
        
                <link rel="stylesheet" type="text/css" href="{/XML/CSS_URL}main.css" />
            <script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}category.js"></script>
            <script language="javascript" src="{XML/ADMIN_JS_URL}add_slides.js"></script>
                <script language="javascript" src="{XML/ADMIN_JS_URL}tiny_mce/tiny_mce.js"></script>
                <script>var admin_web_url = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';</script>
       
            </head>
            <body>
                
                <table align="center" width="100%" border="0" cellpadding="2" cellspacing="2">
                    <tr>
                        <td>View By Section :<select name="view_section_id" id="view_section_id" onchange="javascript: getProductSlideDashboardByType('prod_article_dashboard','productajaxloader','','{/XML/STARTLIMIT}','{/XML/CNT}');">
                            <option value="0">---Select Section---</option>
                            <xsl:for-each select="/XML/SLIDE_SECTION_MASTER/SLIDE_SECTION">
                            <xsl:choose>
                            <xsl:when test="/XML/VIEWSECTION=SECTION_ID">
                                <option value="{SECTION_ID}" selected='yes'>
                                <xsl:value-of select="SECTION_NAME"/>
                                </option>
                            </xsl:when>
                            <xsl:otherwise>
                            <option value="{SECTION_ID}">
                            <xsl:value-of select="SECTION_NAME"/>
                            </option>
                            </xsl:otherwise>                                                                
                            </xsl:choose>                                                           
                            </xsl:for-each> 
                            </select>
                        </td>
                </tr>
                            <tr>
                         <td>
                            <div align="center" style="border: 1px solid #d5d5d5;">
                                <table width="100%" border="0" id="product_dashboard" colspacing="0" rowspacing="0">
                                    <tr style="border: 1px solid #d5d5d5;">
                                        <td colspan="18" style="border: 1px solid #d5d5d5;">
                                            <div align="center">
                                                <h3>Dashboard</h3>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr style="border: 1px solid #d5d5d5;">
                                        <td colspan="18" style="border: 1px solid #d5d5d5;">
                                            <div align="right">pagination goes here</div>
                                        </td>
                                    </tr>
                                    <tr class="row0">
                                        <td>Slide Title</td>
                                        <td>Status</td>
                                        <td>Create date</td>
                    <td colspan="4">Action</td>
                                    </tr>
                                    <xsl:choose>
                                        <xsl:when test="/XML/SLIDE_MASTER/COUNT&lt;=0">
                                            <tr>
                                                <td colspan="12">
                                                    <div align="center">Zero result found.</div>
                                                </td>
                                            </tr>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <xsl:for-each select="/XML/SLIDE_MASTER/SLIDE_MASTER_DATA">
                                                
                                                <tr class="row1">
                                                    <td>
                                                        <xsl:value-of select="TITLE" diseable-output-esacaping="yes"/>
                                                    </td>
                            
                            <td>
                                                        <xsl:value-of select="STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                            <td>
                                                        <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                            <a href="#Update" id="updateMe" onclick="updateProductSlide('prod_article_dashboard','productajaxloader','{PRODUCT_SLIDE_ID}','');">Update</a>
                                                    </td>
                                                    <td><a href="javascript:undefined;" onclick="deleteProductSlide('{PRODUCT_SLIDE_ID}');">Delete</a></td>
                                                </tr>
                                            </xsl:for-each>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                    <tr style="border: 1px solid #d5d5d5;">
                                        <td colspan="18" style="border: 1px solid #d5d5d5;">
                                            <div align="right">pagination goes here</div>
                                        </td>
                                    </tr>
                                </table>
                                <!--start code to add product form -->
                                <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}add_slides.php" name="product_manage" id="product_manage" onsubmit="return validateProduct();">
                                    <table width="100%" border="0" id="add_product_table">                                        
                                        <tr >
                                            <td align="right">                                                
                                                <table width="100%" border="0">
                                                    <tr class="row1">
                                                        <td>
                                                            <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                                                        </td>
                            <td>
                                                            <input type="hidden" name="hd_view_section_id" id="hd_view_section_id" value=""/>
                                                            <input type="hidden" name="hd_product_slide_id" id="hd_product_slide_id" value=""/>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="actiontype" id="actiontype" value=""/>
                                                            
                                                        </td>
                                                        <td></td>
                                                        <td colspan="2"></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                                <table width="100%" id="Update" border="0" class="row1" style="border:1px solid #d4d4d4;">
                            <tbody id="slideshowtbody">
                                                    <tr class="datarow1">
                                                        <td>Slide Section</td>
                                                        <td colspan="10">
                                                            <select name="select_section_id" id="select_section_id">
                                                                <option value="0">---Select Section---</option>
                                <xsl:for-each select="/XML/SLIDE_SECTION_MASTER/SLIDE_SECTION">
                                    <xsl:choose>
                                        <xsl:when test="/XML/VIEWSECTION=SECTION_ID">
                                        <option value="{SECTION_ID}" selected='yes'>
                                            <xsl:value-of select="SECTION_NAME"/>
                                        </option>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <option value="{SECTION_ID}">
                                                <xsl:value-of select="SECTION_NAME"/>
                                            </option>
                                        </xsl:otherwise>    
                                    </xsl:choose>
                                </xsl:for-each> 
                                    </select>
                                                        </td>
                                                    </tr>
                            <tr class="datarow1">
                            <td>Slide List</td>
                            <td colspan="10">
                                    <select name="select_product_id" id="select_product_id">
                                            <option value="0">---Select Slide---</option>
                                            <xsl:for-each select="/XML/SLIDE_DETAILS_MASTER/SLIDE_DETAILS_MASTER_DATA">
                                    <xsl:choose>
                                        <xsl:when test="/XML/SLIDE_DATA/PRODUCT_SLIDE_ID=PRODUCT_SLIDE_ID">
                                                <option value="{PRODUCT_SLIDE_ID}" selected='yes'><xsl:value-of select="TITLE"/></option>
                                        </xsl:when>
                                                                            <xsl:otherwise>
                                                <option value="{PRODUCT_SLIDE_ID}"><xsl:value-of select="TITLE"/></option>
                                        </xsl:otherwise>
                                        </xsl:choose>
                                         </xsl:for-each>
                                    </select>
                                    <div id="ajaxloaderType" style="display:none;">
                                    <div align="center"><img src="{/XML/IMAGE_URL}ajax-loader.gif"/></div>
                                </div>
                            </td>
                                                    </tr>
                                <tr>
                                                    <td>Related Video Status#1</td>
                                                        <td colspan="14">
                                                            <select name="status" id="status">
                                    <xsl:choose>
                                            <xsl:when test="/XML/SLIDE_DATA/STATUS='Active'">
                                            <option value="1" selected='yes'>Active</option>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <option value="1" >Active</option>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                    <xsl:choose>
                                        <xsl:when test="/XML/SLIDE_DATA/STATUS='InActive'">
                                            <option value="0" selected='yes'>InActive</option>
                                        </xsl:when>     
                                        <xsl:otherwise>
                                            <option value="0" >InActive</option>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </select>
                                                        </td>
                                                    </tr>
                        <tr>                        
                           <td colspan="11"><input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/></td>
                        </tr>
                        <tr>
                            <td colspan="9">&nbsp;</td>
                                                    <td>
                                                    <div align="center">
                                                        <input type="submit" name="save" value="Save" class="formbtn"/>
                                                    </div>
                                                    </td>
                                                    <td>
                                                    <div align="center">
                                                        <input type="button" name="cancel" value="Cancel" class="formbtn" onclick="javascript:this.form.reset();"/>
                                                    </div>
                                                    </td>
                                                </tr>
                        </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                                <!--end code to add product form -->
                            </div>
                        </td>
                        <!-- main area  END -->
                    </tr>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
