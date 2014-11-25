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
            <h2>Feature Overview Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                            <th>Sr no.</th>
                            <th>Category Name</th>
                            <th>Feature Name</th>
                            <th>Feature Title</th>
                            <th>Feature Display Unit</th>
                            <th>Status</th>
                            <th>Create Date</th>
                            <th>Postiion</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <xsl:for-each select="/XML/FEATURE_OVERVIEW_MASTER/FEATURE_OVERVIEW_MASTER_DATA">
                            <tr class="odd gradeX">
                            <td>
                            <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="FEATURE_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="TITLE" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="ABBREVIATION" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="FEATURE_OVERVIEW_STATUS" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:choose>
                            <xsl:when test="position()&gt;1">
                            <a href="javascript:undefined;" onclick="updateUpPos('{CATEGORY_ID}','{OVERVIEW_ID}','{POSITION}','feature_overview_dashboard','{OVERVIEW_SUB_GROUP_ID}');">Up</a>            
                            </xsl:when>
                            <xsl:otherwise>Up</xsl:otherwise>
                            </xsl:choose>
                           |
                            <xsl:choose>
                            <xsl:when test="position()!=last()">
                            <a href="javascript:undefined;" onclick="updateDownPos('{CATEGORY_ID}','{OVERVIEW_ID}','{POSITION}','feature_overview_dashboard','{OVERVIEW_SUB_GROUP_ID}');">Down</a>
                            </xsl:when>
                            <xsl:otherwise>Down</xsl:otherwise>
                            </xsl:choose>
                            </td>
                            <!--<td width="7%">
                            <a href="#Update" onclick="updateBrand('{BRAND_ID}','{JS_BRAND_NAME}','{STATUS}');">Update</a>
                            </td>-->
                            <td>
                            <a href="javascript:undefined;" onclick="deleteFeatureOverview('{OVERVIEW_ID}','{JS_FEATURE_NAME}','feature_overview_dashboard');">Delete</a>
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
        <h2>Feature Overview Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form method="post" name="feature_main_group_frm" action="{/XML/ADMIN_WEB_URL}feature_overview.php">
            <table class="form">
             
            <tr>
              <td><label>Select Main Group</label></td>
              <td>
                <select name="sel_main_group" id="sel_main_group" onchange="javascript:this.form.submit();">
                  <option value="">--select Feature Group--</option>
                  <xsl:for-each select="/XML/FEATURE_GROUP_MASTER/FEATURE_GROUP_MASTER_DATA">
                    <xsl:if test="MAIN_GROUP_NAME!=''">
                      <xsl:choose>
                        <xsl:when test="/XML/SELECTED_MAIN_GROUP_ID=GROUP_ID">
                          <option value="{GROUP_ID}" selected="yes">
                            <xsl:value-of select="MAIN_GROUP_NAME" disable-output-escaping="yes"/>
                          </option>
                        </xsl:when>
                        <xsl:otherwise>
                          <option value="{GROUP_ID}">
                            <xsl:value-of select="MAIN_GROUP_NAME" disable-output-escaping="yes"/>
                          </option>
                        </xsl:otherwise>
                      </xsl:choose>
                    </xsl:if>
                  </xsl:for-each>
                </select>
              </td>
            </tr>
               <tr>
                <td><label>Select Feature</label></td>
                <td>
                  <select name="feature_id" id="feature_id">
                    <option value="">--Select Feature--</option>
                    <xsl:for-each select="/XML/FEATURE_MASTER/FEATURE_MASTER_DATA">
                      <option value="{FEATURE_ID}">
                        <xsl:value-of select="FEATURE_NAME" disable-output-escaping="yes"/>
                      </option>
                    </xsl:for-each>
                  </select>
                </td>
              </tr>
              <tr>
                <td><label>Feature Title</label></td>
                <td>
                  <input class="medium" type="text" name="feature_title" id="feature_title"/>
                </td>
              </tr>
              <tr>
                <td><label>Feature Overview Display Unit</label></td>
                <td>
                  <input class="medium" type="text" name="feature_display_unit" id="feature_display_unit"/>
                </td>
              </tr>
              <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="feature_overview_status" id="feature_overview_status">
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
                         <input type="hidden" name="actiontype" id="actiontype" value="insert"/>
                        <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                        <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                        <input type="hidden" name="overview_id" id="overview_id" value=""/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="javascript:this.form.submit();">Add/Update</button><!-- <button class="btn btn-navy">Cancel</button> --></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

