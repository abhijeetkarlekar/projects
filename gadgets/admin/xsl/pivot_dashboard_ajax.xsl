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
    <script>
                    var admin_web_url = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';
                    var pivotGroupArr = Array();
                    var pivotGroupIdsArr = Array();
                    <xsl:for-each select="/XML/PIVOT_GROUP/PIVOT_GROUP_DATA">
                    pivotGroupIdsArr.push('<xsl:value-of select="SUB_GROUP_ID" disable-output-escaping="yes"/>');
                    pivotGroupArr.push('<xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/>');
                    </xsl:for-each>
                    <xsl:for-each select="XML/PIVOT_UNIT/PIVOT_UNIT_DATA">
                    pivotUnitArr.push('<xsl:value-of select="UNIT_NAME" disable-output-escaping="yes"/>');
                    pivotUnitIdArr.push('<xsl:value-of select="UNIT_ID" disable-output-escaping="yes"/>');
                    </xsl:for-each>
                </script>
    <div class="grid_10">
        <div class="box round first grid">
            <h2>Pivot Dashboard Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                           <th>Sr.No</th>
                            <th>Pivot name</th>
                            <th>Category Name</th>
                            <th>Pivot group</th>
                            <th>Pivot Display type</th>
                            <th>Status</th>
                            <th>create date</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/PIVOT_MASTER/PIVOT_MASTER_DATA">
                        <tr class="odd gradeX">
                            <td>
                                <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                <xsl:value-of select="PIVOT_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                <xsl:value-of select="PIVOT_GROUP_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                <xsl:value-of select="PIVOT_DISPLAY_TYPE" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                <xsl:value-of select="PIVOT_STATUS" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                                <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                            </td>
                            
                            <td>
                                <a href="#Update" onclick="updatePivot('{PIVOT_ID}','{FEATURE_ID}','{JS_PIVOT_GROUP}','{JS_PIVOT_DESC}','{PIVOT_DISPLAY_ID}','{STATUS}');">Update</a>
                            |
                                <a href="javascript:undefined;" onclick="deletePivot('{PIVOT_ID}','{JS_PIVOT_NAME}');">Delete</a>
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
        <h2>Pivot Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form method="post" enctype="multipart/form-data" action="{XML/ADMIN_WEB_URL}pivot.php" name="pivot_manage" id="pivot_manage" >
            <table class="form" id="add_pivot_table">
               <tr>
                    <td><label>Pivot Name#1</label></td>
                    <td>
                        <select name="select_pivot_name_0" id="select_pivot_name_0">
                            <option value="">---Select Pivot---</option>
                        <xsl:for-each select="/XML/FEATURE_MASTER/FEATURE_MASTER_DATA">
                            <option value="{FEATURE_ID}">
                                <xsl:value-of select="FEATURE_NAME"/>
                            </option>
                        </xsl:for-each>
                        </select>
                    </td>
                </tr>
                
                <tr>
                        <td><label>Pivot Group#1</label></td>
                        <td>
                            <select name="select_pivot_group_0" id="select_pivot_group_0">
                                <option value="">---Select Group---</option>
                                <xsl:for-each select="/XML/PIVOT_GROUP/PIVOT_GROUP_DATA">
                                    <option value="{SUB_GROUP_ID}">
                                        <xsl:value-of select="SUB_GROUP_NAME"/>
                                    </option>
                                </xsl:for-each>
                            </select>
                        </td>
                        
                    </tr>
                    <tr>
                        <td><label>Pivot Description#1</label></td>
                        <td>
                            <textarea name="pivot_description_0" cols="40" rows="5" id="pivot_description_0"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Pivot Display Style#1</label></td>
                        <td>
                            <select name="pivot_style_0" id="pivot_style_0" style="display:block;">
                                <option value="">---Select Style---</option>
                                <xsl:for-each select="/XML/PIVOT_DISPLAY_TYPES/PIVOT_DISPLAY_TYPES_DATA">
                                    <option value="{PIVOT_DISPLAY_ID}">
                                        <xsl:value-of select="PIVOT_DISPLAY_NAME"/>
                                    </option>
                                </xsl:for-each>
                            </select>
                        </td>
                    </tr>
                    <tr>
                <td><label>Upload Pivot Image:#1</label></td>
                    <td>
                            <input name="uploadedfile_0" type="file" /><br />
                    </td>
            </tr>
                    
                <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="pivot_status_0" id="pivot_status_0">
                            <xsl:choose>
                                    <xsl:when test="XML/BRAND_DETAIL/BRAND_DETAIL_DATA/STATUS='Active'">
                                            <option value="1" selected='yes'>Active</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                            <option value="1">Active</option>
                                    </xsl:otherwise>
                            </xsl:choose>
                            <xsl:choose>
                                    <xsl:when test="XML/BRAND_DETAIL/BRAND_DETAIL_DATA/STATUS='InActive'">
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
                        <input type="hidden" name="pivot_id" id="pivot_id"/>
                        <input type="hidden" name="actiontype" id="actiontype" value="insert"/>
                        <input type="hidden" name="pivotboxcnt" id="pivotboxcnt" value="1"/>
                        <input type="hidden" name="rowCount" id="rowCount" value="6"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                        <input type="hidden" name="pivot_group_str" id="pivot_group_str" value="{/XML/PIVOT_GROUP_ARR_STR}"/>
                        <input type="hidden" name="pivot_subgroup_str" id="pivot_subgroup_str" value="{/XML/PIVOT_SUB_GROUP_ARR_STR}"/>
                        <input type="hidden" name="pivot_subgrp_id_str" id="pivot_subgrp_id_str" value="{/XML/PIVOT_SUB_GROUP_ID_ARR_STR}"/>
                        <input type="hidden" name="pivot_style_id_str" id="pivot_style_id_str" value="{/XML/PIVOT_STYLE_ID_ARR_STR}"/>
                        <input type="hidden" name="pivot_style_value_str" id="pivot_style_value_str" value="{/XML/PIVOT_STYLE_VALUE_ARR_STR}"/>
                        <input type="hidden" name="feature_name_str" id="feature_name_str" value="{/XML/FEATURE_NAME_ARR_STR}"/>
                        <input type="hidden" name="feature_id_str" id="feature_id_str" value="{/XML/FEATURE_IDS_ARR_STR}"/>
                    </td>
                </tr>
                
                <tr><td></td><td><button class="btn btn-navy" onclick="return validatePivot();">Add/Update</button><!-- <button class="btn btn-navy">Cancel</button> --></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

