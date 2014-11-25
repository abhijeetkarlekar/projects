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
            <h2>Comparison Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                           <th>Sr.No</th>
                            <th>Category Name</th>
                            <th>Brand Name</th>
                            <th>Product Name</th>
                            <th>Variant</th>
                            <th>Competitor Product Names</th>
                            <th>Status</th>
                            <th>Create date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/TOP_COMPETITOR_MASTER/TOP_COMPETITOR_MASTER_DATA">
                        <tr class="odd gradeX">
                            <td>
                                                        <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="BRAND_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="PRODUCT_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="VARIANT" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="PRODUCT_NAMES" diseable-output-esacaping="yes"/><span style="padding-left:2px;"></span>
                                                         <xsl:value-of select="VARIANTS" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    
                                                    <td>
                                                        <a href="#Update" id="updateMe" onclick="updateProductCompetitor('prod_article_dashboard','productajaxloader','{COMPETITOR_PRODUCT_ID}','{PRODUCT_ID}','{CATEGORY_ID}','{BRAND_ID}','{PRODUCT_INFO_ID}','{PRODUCT_IDS}','{COMP_BRAND_ID}','{COMP_MODEL_ID}');">Update</a>
                                                   |
                                                        <a href="javascript:undefined;" onclick="deleteProductCompetitor('{COMPETITOR_PRODUCT_ID}');">Delete</a>
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
        <h2>Comparison Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}compare_top_competitor.php" name="product_manage" id="product_manage" >
            <table class="form" id="Update" border="0" >
                
                         
               
                <tbody id="slideshowtbody">
                                                    <tr class="datarow1">
                                                        <td><label>Brand Name</label></td>
                                                        <td colspan="10">
                                                            <select name="select_brand_id" id="select_brand_id" onchange="getModelByBrand('ajaxloader','0');">
                                                                <option value="">---Select Brand---</option>
                                                                <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                                                                <xsl:if test="/XML/TOP_COMPETITOR_DATA/BRAND_ID=BRAND_ID">
                                                                <option value="{BRAND_ID}" selected='yes'>
                                                                    <xsl:value-of select="BRAND_NAME"/>
                                                                </option>
                                                                </xsl:if>
                                                                <xsl:if test="not(/XML/TOP_COMPETITOR_DATA/BRAND_ID=BRAND_ID)">
                                                                <option value="{BRAND_ID}">
                                                                    <xsl:value-of select="BRAND_NAME"/>
                                                                </option>
                                                                </xsl:if>
                                                            </xsl:for-each>
                                                            </select>
                                                            <div id="ajaxloader" style="display:none;">
                                                                <div align="center">
                                                                    <img src="{/XML/IMAGE_URL}ajax-loader.gif"/>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="datarow1">
                                                        <td><label>Competitor Brand Name</label></td>
                                                        <td colspan="10">
                                                            <select name="select_comp_brand_id" id="select_comp_brand_id" onchange="getModelByBrandCompetitor('ajaxloader1','0');">
                                                                <option value="">---Select Brand---</option>
                                                                <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                                                                <xsl:if test="/XML/TOP_COMPETITOR_DATA/BRAND_ID_COMP=BRAND_ID">
                                                                <option value="{BRAND_ID}" selected='yes'>
                                                                    <xsl:value-of select="BRAND_NAME"/>
                                                                </option>
                                                                </xsl:if>
                                                                <xsl:if test="not(/XML/TOP_COMPETITOR_DATA/BRAND_ID_COMP=BRAND_ID)">
                                                                <option value="{BRAND_ID}">
                                                                    <xsl:value-of select="BRAND_NAME"/>
                                                                </option>
                                                                </xsl:if>
                                                            </xsl:for-each>
                                                            </select>
                                                            <div id="ajaxloader1" style="display:none;">
                                                                <div align="center">
                                                                    <img src="{/XML/IMAGE_URL}ajax-loader.gif"/>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    
               
                
                <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="brand_status" id="brand_status">
                            <xsl:choose>
                                    <xsl:when test="XML/TOP_COMPETITOR_DATA/STATUS='Active'">
                                            <option value="1" selected='yes'>Active</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                            <option value="1">Active</option>
                                    </xsl:otherwise>
                            </xsl:choose>
                            <xsl:choose>
                                   <xsl:when test="XML/TOP_COMPETITOR_DATA/STATUS='InActive'">
                                            <option value="0" selected='yes'>InActive</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                            <option value="0">InActive</option>
                                    </xsl:otherwise>
                            </xsl:choose>
                        </select>
                         
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                        <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                        <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                        
                        <input type="hidden" name="competitor_product_id" id="competitor_product_id" value="{XML/TOP_COMPETITOR_DATA/COMPETITOR_PRODUCT_ID}"/>
                        <input type="hidden" name="competitor_brand_id" id="competitor_brand_id" value="{XML/TOP_COMPETITOR_DATA/BRAND_ID_COMP}"/>
                        <input type="hidden" name="competitor_productids" id="competitor_productids" value="{XML/TOP_COMPETITOR_DATA/PRODUCT_IDS}"/>
                        <input type="hidden" name="actiontype" id="actiontype" value="{/XML/SELECTED_ACTION_TYPE}"/>
                        <input type="hidden" name="featureboxcnt" id="featureboxcnt" value="{/XML/FEATURE_MASTER/COUNT}"/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="return validateProduct();">Add/Update</button><!-- <button class="btn btn-navy">Cancel</button> --></td></tr>
            </tbody>
            </table>
            </form>

            </div>
        </div>
    </div>
    
</xsl:template>
</xsl:stylesheet>