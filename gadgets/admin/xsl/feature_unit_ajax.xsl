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
            <h2>Feature Unit Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Unit Name</th>
                            
                            <th>Status</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/UNIT_MASTER/UNIT_MASTER_DATA">
                        <tr class="odd gradeX">
                            <td >
                            <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="UNIT_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="UNIT_STATUS" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                 <a href="#Update" onclick="updateFeatureUnit('{UNIT_ID}','{JS_UNIT_NAME}','{STATUS}');">Update</a>
                   | 
                    <a href="javascript:undefined;" onclick="deleteFeatureUnit('{UNIT_ID}','{JS_UNIT_NAME}');">Delete</a>
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
        <h2>Feature Unit Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" action="{XML/ADMIN_WEB_URL}feature_unit.php" method="post" name="feature_unit_action" id="feature_unit_action">
            <table class="form">
                <tr>
                    <td>
                        <label>Feature Unit name</label>
                    </td>
                    <td>
                        <input class="warning" type="text" name="unit_name" id="unit_name" value="{XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_NAME}"/>
                    </td>
                </tr>
           
                
                
                <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="unit_status" id="unit_status">
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
                        <input type="hidden" name="actiontype" id="actiontype" value="insert"/>
                        <input type="hidden" name="unit_id" id="unit_id" value=""/>
                        <input type="hidden" name="row_count" id="row_count" value="{/XML/POPULAR_BRAND_MASTER/COUNT}"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="return validateFeatureUnit();">Add/Update</button><button class="btn btn-navy">Cancel</button></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

