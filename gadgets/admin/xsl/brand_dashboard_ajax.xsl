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
            <h2>Brand Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                           <th>Sr no.</th>
                            <th>SEO Path</th>
                            <th>Brand name</th>
                            <th>Category Name</th>
                            <!-- <th>Short Description</th>
                            <th>Long Description</th> -->
                            <th>Image</th>
                            <th>Status</th>
                            <th>Upcoming</th>
                            <th>Discontinue Status</th>
                            <th>Create Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                        <tr class="odd gradeX">
                            <td >
                            <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="SEO_PATH" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="BRAND_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                            </td>
                            <!-- <td>
                            <xsl:value-of select="SHORT_DESC" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="LONG_DESC" diseable-output-esacaping="yes"/>
                            </td> -->
                            <td>
                            <xsl:value-of select="BRAND_IMAGE" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="BRAND_STATUS" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="UPCOMING_BRAND" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="DISCONTINUE_STATUS" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                            </td>
                            <td>
                            <a href="#Update" onclick="updateBrand('brand_dashboard','brandajaxloader','{BRAND_ID}','{CATEGORY_ID}','{/XML/Page}','{/XML/Perpage}','{SEO_PATH}');">Update</a> | 
                            <a href="javascript:undefined;" onclick="deleteBrand('{BRAND_ID}','{JS_BRAND_NAME}');">Delete</a>
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
        <h2>Brand Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action">
            <table class="form">
                <tr>
                    <td>
                        <label>Brand name</label>
                    </td>
                    <td>
                        <input class="warning" type="text" name="brand_name" id="brand_name" value="{XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_NAME}"/>
                    </td>
                </tr>
                <tr>
                    <td>
                       <label>Brand SEO Path</label>
                    </td>
                    <td>
                        <input class="warning" type="text" name="seo_path" id="seo_path" value="{XML/BRAND_DETAIL/BRAND_DETAIL_DATA/SEO_PATH}"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Short Description</label>
                    </td>
                    <td>
                        <textarea  class="warning" name="short_desc" id="short_desc" cols="60" rows="2" >
                            <xsl:value-of select="XML/BRAND_DETAIL/BRAND_DETAIL_DATA/SHORT_DESC"/>
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Long Description</label>
                    </td>
                    <td>
                        <textarea  class="warning" name="long_desc" id="long_desc" cols="60" rows="5" >
                            <xsl:value-of select="XML/BRAND_DETAIL/BRAND_DETAIL_DATA/LONG_DESC"/>
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                     <label>Upload Image</label>
                    </td>
                    <td>
                        <input name="uploadedfile" type="file"/><xsl:value-of select="XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_IMAGE"/>
                        <img src="{XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_IMAGE_PATH}" width="50px" height="50px" />
                    </td>
                </tr>
            <!--     <tr>
                    <td>
                        <label>Upload Research Image</label>
                    </td>
                    <td>
                        <input name="uploadedresearchimagefile" type="file" />
                        <xsl:value-of select="XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_RESEARCH_IMAGE"/>
                        <img src="{XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_RESEARCH_IMAGE_PATH}" width="50px" height="50px" />
                    </td>
                </tr> -->
                <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="brand_status" id="brand_status">
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
                        <input type="hidden" name="brand_id" id="brand_id" value=""/>
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

