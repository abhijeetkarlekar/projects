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
            <h2>Feature Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                        <th>Sr.No</th>
                        <th>Feature name</th>
                        <th>Seo Path</th>
                        <th>Category Name</th>
                        <th>Feature group</th>
                        <th>Feature Unit</th>
                        <th>Status</th>
                        <th>create date</th>
                        <th>Up</th>
                        <th>Down</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/FEATURE_MASTER/FEATURE_MASTER_DATA">
                                                <tr class="odd gradeX">
                                                       <td>
                                                        <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="FEATURE_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="SEO_PATH" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="FEATURE_GROUP_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="FEATURE_UNIT" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="FEATURE_STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>Up</td>
                                                    <td>Down</td>
                                                    <td>
                                                        <a href="#Update" onclick="updateFeature('{FEATURE_ID}','{JS_FEATURE_NAME}','{FEATURE_GROUP}','{JS_FEATURE_DESC}','{UNIT_ID}','{STATUS}','{MAIN_FEATURE_GROUP}','{SEO_PATH}');">Update</a>
                                                     | 
                                                        <a href="javascript:undefined;" onclick="deleteFeature('{FEATURE_ID}','{JS_FEATURE_NAME}');">Delete</a>
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
        <h2>Feature Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" action="{XML/ADMIN_WEB_URL}feature.php" method="post" name="feature_manage" id="feature_manage">
            <table class="form">
                <tr>
                    <td><label>Feature Name#1</label></td>
                    <td colspan="10">
                    <input class="warning" type="text" name="feature_name_0" id="feature_name_0" />
                    </td>
                    </tr>
                    <tr>
                    <td><label>Seo Path#1</label></td>
                    <td colspan="10">
                    <input class="warning" type="text" name="seo_path_0" id="seo_path_0" />
                    </td>
                    </tr>
                    <tr>
                    <td><label>Main Feature Group#1</label></td>
                    <td >
                    <select name="select_main_group_0" id="select_main_group_0" onchange="getSubGroupByMainGroup('ajaxloadermaingroup','select_main_group_0','select_feature_group_0','')">
                    <option value="">---Select Main Feature Group---</option>
                    <xsl:for-each select="/XML/FEATURE_GROUP_MASTER/FEATURE_GROUP_MASTER_DATA">
                    <option value="{GROUP_ID}">
                    <xsl:value-of select="MAIN_GROUP_NAME" disable-output-escaping="yes"/>
                    </option>
                    </xsl:for-each>
                    </select>
                    <div id="ajaxloadermaingroup" style="display:none;">
                    <div align="center">
                    <img src="{/XML/ADMIN_IMAGE_URL}ajax-loader.gif"/>
                    </div>
                    </div>
                    </td>
                    </tr>
                    <tr>
                    <td><label>Feature Group#1</label></td>
                    <td id="td_select_feature_group_0">
                    <select name="select_feature_group_0" id="select_feature_group_id_0">
                    <option value="">---Select Group---</option>
                    </select>
                    </td>
                    </tr>                           
                    <tr>
                    <td><label>Feature Description#1</label></td>
                    <td >
                    <textarea class="warning"  name="feature_description_0" cols="40" rows="5" id="feature_description_0"></textarea>
                    </td>
                    </tr>
                    <tr>
                    <td><label>Feature Unit#1</label></td>
                    <td >
                    <select name="feature_unit_0" id="feature_unit_0">
                    <option value="">---Select Unit---</option>
                    <xsl:for-each select="XML/FEATURE_UNIT/FEATURE_UNIT_DATA">
                    <option value="{UNIT_ID}">
                    <xsl:value-of select="UNIT_NAME"/>
                    </option>
                    </xsl:for-each>
                    </select>
                    </td>
                    </tr>
                    <tr>
                    <td><label>Upload Feature Image:#1</label></td>
                    <td >
                    <input name="uploadedfile_0" type="file" /><br />
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="feature_status_0" id="feature_status_0">
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
                        <input type="hidden" name="feature_id" id="feature_id"/>
                        <input type="hidden" name="main_group_id" id="main_group_id" value=""/>
                        <input type="hidden" name="sub_group_id" id="sub_group_id" value=""/>
                        <input type="hidden" name="row_count" id="row_count" value="{/XML/POPULAR_BRAND_MASTER/COUNT}"/>
                        <input type="hidden" name="featureboxcnt" id="featureboxcnt" value="1"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="return validateFeature();">Add/Update</button><button class="btn btn-navy">Cancel</button></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

