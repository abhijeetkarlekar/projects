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
            <h2>Popular Brand Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Brand name</th>
                            <th>Category Name</th>
                            <td>Position</td>
                            <th>Status</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/POPULAR_BRAND_MASTER/POPULAR_BRAND_MASTER_DATA">
                        <tr class="odd gradeX">
                            <td >
                            <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="BRAND_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="BRAND_POSITION" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="STATUS" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                <a href="#Update" id="updateMe" onclick="updatePopularBrand('prod_article_dashboard','productajaxloader','{POPULAR_ID}','{BRAND_ID}','{POPULAR_MODEL_ID}','{CATEGORY_ID}','{/XML/SELECTED_BRAND_ID}');">Update</a>
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
        <h2>Popular Brand Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" action="{XML/ADMIN_WEB_URL}popular_brand.php" method="post" name="product_manage" id="product_manage">
            <table class="form">
                <tr>
                    <td>
                        <label>Brand name</label>
                    </td>
                    <td>
                        <select name="select_brand_id" id="select_brand_id">
                            <option value="">---Select Brand---</option>
                            <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                                <xsl:choose>
                                    <xsl:when test="/XML/POPULAR_BRAND_DETAIL/POPULAR_BRAND_DETAIL_DATA/BRAND_ID=BRAND_ID">
                                        <option value="{BRAND_ID}" selected='yes'>
                                        <xsl:value-of select="BRAND_NAME"/>
                                        </option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <option value="{BRAND_ID}">
                                        <xsl:value-of select="BRAND_NAME"/>
                                        </option>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </xsl:for-each>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                       <label>Brand Position</label>
                    </td>
                    <td>
                        <select id="brand_position" name="brand_position">
                        <option value="0">---Select Position---</option>
                        <xsl:for-each select="/XML/POSITION_MASTER/POSITION_MASTER_DATA">
                            <xsl:if test="/XML/POPULAR_BRAND_DETAIL/POPULAR_BRAND_DETAIL_DATA/BRAND_POSITION=POSITION">
                                <option value="{POSITION}" selected="yes">
                                    <xsl:value-of select="POSITION" />
                                </option>
                            </xsl:if>
                            <xsl:if test="not(/XML/POPULAR_BRAND_DETAIL/POPULAR_BRAND_DETAIL_DATA/BRAND_POSITION=POSITION)">
                                <option value="{POSITION}">
                                    <xsl:value-of select="POSITION" />
                                </option>
                            </xsl:if>
                        </xsl:for-each>
                    </select>                    </td>
                </tr>
                
                
                <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="status" id="status">
                                <xsl:choose>
                                    <xsl:when test="/XML/POPULAR_BRAND_DETAIL/POPULAR_BRAND_DETAIL_DATA/STATUS='Active'">
                                        <option value="1" selected='yes'>Active</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <option value="1">Active</option>
                                    </xsl:otherwise>
                                </xsl:choose>
                                <xsl:choose>
                                    <xsl:when test="/XML/POPULAR_BRAND_DETAIL/POPULAR_BRAND_DETAIL_DATA/STATUS='InActive'">
                                        <option value="0" selected='yes'>InActive</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <option value="0">InActive</option>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </select>
                        <input type="hidden" name="actiontype" id="actiontype" value="{/XML/SELECTED_ACTION_TYPE}"/>
                        <input type="hidden" name="popular_id" id="popular_id" value="{/XML/POPULAR_ID}"/>
                        <input type="hidden" name="row_count" id="row_count" value="{/XML/POPULAR_BRAND_MASTER/COUNT}"/>
                        <input type="hidden" name="selected_brand_id" id="selected_brand_id" value="{/XML/SELECTED_BRAND_ID}"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="return validateProduct();">Add/Update</button><button class="btn btn-navy">Cancel</button></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

