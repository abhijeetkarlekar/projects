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
            <h2>Feature Comparison Management</h2>
            <div class="block">
                View By Section :<select name="view_section_id" id="view_section_id" onchange="javascript: getFeaturedOncarsComapreSetDashboardByType('prod_article_dashboard','productajaxloader','','{/XML/STARTLIMIT}','{/XML/CNT}','');">
                            <option value="0">---Select Section---</option>
                            <xsl:for-each select="/XML/FEATURED_COMPARISON_SET_MASTER/FEATURED_COMPARISON_SET">
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
                           <th>Compare Set Title</th>
                                        <th>Status</th>
                                        <th>Ordering</th>
                                        <th>Create date</th>
                                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/FEATURED_COMPARISON_MASTER/FEATURED_COMPARISON_MASTER_DATA">
                        <tr class="odd gradeX">
                            <td>
                                                        <xsl:value-of select="TITLE" diseable-output-esacaping="yes"/>
                                                    </td>
                            <td>
                                                        <xsl:value-of select="STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                            <td>
                                                        <xsl:value-of select="ORDERING" diseable-output-esacaping="yes"/>
                                                    </td>
                            <td>
                                                        <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                                    </td>
                            <td>
                                                        <a href="#Update" id="updateMe" onclick="updateFeaturedOncarsCompareSet('prod_article_dashboard','productajaxloader','{FEATURED_COMPARE_ID}','');">Update</a>
                                                    | <a href="javascript:undefined;" onclick="deleteFeaturedOncarsCompareSet('{FEATURED_COMPARE_ID}');">Delete</a></td>
                                                
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
        <h2>Featured Comparison Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}featured_oncars_comparison.php" name="product_manage" id="product_manage" >
            <table class="form">
                <tr>
                                                        <td><label>Featured Compare Set Section</label></td>
                                                        <td colspan="10">
                                                            <select name="select_section_id" id="select_section_id" onchange="javascript: getComapreSetList('select_comapre_list','','{/XML/STARTLIMIT}','{/XML/CNT}');">
                                                                <option value="0">---Select Section---</option>
                                <xsl:for-each select="/XML/FEATURED_COMPARISON_SET_MASTER/FEATURED_COMPARISON_SET">
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
                            <td><label>Compare Set List</label></td>
                            <td id="select_comapre_list">
                                    <select name="select_compare_set_id" id="select_compare_set_id">
                                            <option value="0">---Select Compare List---</option>
                                    <xsl:for-each select="/XML/ONCARS_COMPARE_SET_MASTER/ONCARS_COMPARE_SET_MASTER_DATA">
                                                                        <xsl:choose>
                                                                                <xsl:when test="/XML/FEATURED_ONCARS_DATA/ONCARS_COMPARE_ID=COMPETITOR_PRODUCT_ID">
                                                                                <option value="{COMPETITOR_PRODUCT_ID}" selected='yes'>
                                                                                        <xsl:value-of select="TITLE"/>
                                                                                </option>
                                                                                </xsl:when>
                                                                                <xsl:otherwise>
                                                                                        <option value="{COMPETITOR_PRODUCT_ID}">
                                                                                                <xsl:value-of select="TITLE"/>
                                                                                        </option>
                                                                                </xsl:otherwise>        
                                                                        </xsl:choose>
                                                                    </xsl:for-each>
                                    </select>
                            </td>
                                                    </tr>
                            <tr>
                                                        <td><label>Compare Set Ordering</label></td>
                                                        <td id="select_ordering">
                                                                <select name="ordering" id="ordering">
                                                                        <option value="">---Select Ordering---</option>
                                    <xsl:for-each select="/XML/FEATURED_COMPARISON_MASTER/FEATURED_COMPARISON_ORDERING">
                                                                        <xsl:choose>
                                                                                <xsl:when test="/XML/FEATURED_ONCARS_DATA/ORDERING=FEATURED_COMPARISON_ORDERING_DATA">
                                                                                <option value="{FEATURED_COMPARISON_ORDERING_DATA}" selected='yes'>
                                                                                        <xsl:value-of select="FEATURED_COMPARISON_ORDERING_DATA"/>
                                                                                </option>
                                                                                </xsl:when>
                                                                                <xsl:otherwise>
                                                                                        <option value="{FEATURED_COMPARISON_ORDERING_DATA}">
                                                                                                <xsl:value-of select="FEATURED_COMPARISON_ORDERING_DATA"/>
                                                                                        </option>
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
                                    <xsl:when test="/XML/FEATURED_ONCARS_DATA/STATUS='Active'">
                                            <option value="1" selected='yes'>Active</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                            <option value="1">Active</option>
                                    </xsl:otherwise>
                            </xsl:choose>
                            <xsl:choose>
                                    <xsl:when test="/XML/FEATURED_ONCARS_DATA/STATUS='InActive'">
                                            <option value="0" selected='yes'>InActive</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                            <option value="0">InActive</option>
                                    </xsl:otherwise>
                            </xsl:choose>
                        </select>
                         <input type="hidden" name="actiontype" id="actiontype" value="Insert"/>
                        <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                        <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                        <input type="hidden" name="hd_view_section_id" id="hd_view_section_id" value=""/>
                        <input type="hidden" name="hd_featured_compare_id" id="hd_featured_compare_id" value="{/XML/FEATURED_ONCARS_DATA/FEATURED_COMPARE_ID}"/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="return validateBrand();">Add/Update</button><!-- <button class="btn btn-navy">Cancel</button> --></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

