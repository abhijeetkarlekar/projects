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
<xsl:template match="/">
   
    <div class="grid_10">
        <div class="box round first grid">
            <h2>Assign Slide Show Management</h2>
            <div class="block">
                View By Section :<select name="view_section_id" id="view_section_id" onchange="javascript: getProductSlideDashboardByType('prod_article_dashboard','productajaxloader','','{/XML/STARTLIMIT}','{/XML/CNT}');">
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
                <table class="data display datatable" id="example">

                    
                       
                        <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Slide Title</th>
                            <th>Status</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/SLIDE_MASTER/SLIDE_MASTER_DATA">
                        <tr class="odd gradeX">
                            <td>
                            <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                            </td>
                            
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
                             | 
                                <a href="javascript:undefined;" onclick="deletePopularBrand('{POPULAR_ID}','{BRAND_ID}');">Delete</a>
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
        <h2>Assign SlidesShow</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->

            <form enctype="multipart/form-data" action="{XML/ADMIN_WEB_URL}add_slides.php" method="post" name="product_manage" id="product_manage">
            <table class="form">
                <tr>
                                                        <td><label>Slide Section</label></td>
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
               <tr>
                            <td><label>Slide List</label></td>
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
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="status" id="status">
                                <xsl:choose>
                                    <xsl:when test="/XML/SLIDE_DETAILS_MASTER/SLIDE_DETAILS_MASTER_DATA/STATUS='Active'">
                                        <option value="1" selected='yes'>Active</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <option value="1">Active</option>
                                    </xsl:otherwise>
                                </xsl:choose>
                                <xsl:choose>
                                    <xsl:when test="/XML/SLIDE_DETAILS_MASTER/SLIDE_DETAILS_MASTER_DATA/STATUS='InActive'">
                                        <option value="0" selected='yes'>InActive</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <option value="0">InActive</option>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </select>
                        <input type="hidden" name="actiontype" id="actiontype" value="{/XML/SELECTED_ACTION_TYPE}"/>
                       
                        <input type="hidden" name="row_count" id="row_count" value="{/XML/POPULAR_BRAND_MASTER/COUNT}"/>
                        <input type="hidden" name="selected_brand_id" id="selected_brand_id" value="{/XML/SELECTED_BRAND_ID}"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>

                        <input type="hidden" name="hd_view_section_id" id="hd_view_section_id" value=""/>
                        <input type="hidden" name="hd_product_slide_id" id="hd_product_slide_id" value=""/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="return validateProduct();">Add/Update</button><!-- <button class="btn btn-navy">Cancel</button> --></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

