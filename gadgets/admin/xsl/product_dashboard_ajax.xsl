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
            <h2>Product Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Category Name</th>
                            <th>Brand Name</th>
                            <th>Product name</th>
                            <th>Variant</th>
                            <th>SEO Path</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Discontinue Status</th>
                            <th>create date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA">
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
                            <xsl:value-of select="SEO_PATH" diseable-output-esacaping="yes"/>
                          </td>
                          <td>
                            <xsl:value-of select="PRICE" diseable-output-esacaping="yes"/>
                          </td>
                       
                          <td>
                            <xsl:value-of select="PRODUCT_STATUS" diseable-output-esacaping="yes"/>
                          </td>
                          <td>
                            <xsl:value-of select="DISCONTINUE_STATUS" diseable-output-esacaping="yes"/>
                          </td>
                          <td>
                            <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                          </td>
                          
                          <td>
                            <a href="#Update" id="updateMe" onclick="updateProduct('{PRODUCT_ID}','{BRAND_ID}','{CATEGORY_ID}','myPopUpAjaxloader','myPopUp');">Update</a>
                           | 
                            <a href="javascript:undefined;" onclick="deleteProduct('{PRODUCT_ID}','{JS_PRODUCT_NAME}');">Delete</a>
                          </td>
                        </tr>
                        
                        </xsl:for-each>
                       
                       

                    </tbody>
                </table>
           </div>
        </div>
    </div>
    <div class="grid_10">
        <div class="box round first grid">
        <h2>Product Add/Update</h2>
        <div class="block">
          
            <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}product.php" name="product_manage" id="product_manage">
            <table class="form" id="Update">
                <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                <input type="hidden" name="product_id" id="product_id"/>
                <input type="hidden" name="actiontype" id="actiontype" value="insert"/>
                <input type="hidden" name="featureboxcnt" id="featureboxcnt" value="{/XML/FEATURE_MASTER/COUNT}"/>
                <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                <tr>
                  <td>
                    <label>Brand Name</label>
                  </td>
                  <td>
                    <select name="select_brand_id" id="select_brand_id" onchange="getModelByBrand('ajaxloader','0');">
                      <option value="">---Select Brand---</option>
                      <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                        <xsl:if test="/XML/MODEL_MASTER/MODEL_MASTER_DATA/BRAND_ID=BRAND_ID">
                        <option value="{BRAND_ID}" selected='yes'>
                        <xsl:value-of select="BRAND_NAME"/>
                        </option>
                        </xsl:if>
                        <xsl:if test="not(/XML/MODEL_MASTER/MODEL_MASTER_DATA/BRAND_ID=BRAND_ID)">
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
                <tr>
                  <td>
                    <label>Variant</label>
                  </td>
                  <td>
                      <input class="warning" type="text" name="varient" id="varient" size="40"/>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>SEO Path</label>
                  </td>
                  <td>
                    <input class="warning" type="text" name="seo_path" id="seo_path" size="40"/>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Product Description</label>
                  </td>
                  <td>
                    <textarea class="warning" name="product_description" id="product_description" cols="30"></textarea>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Upload Thumb:</label>
                  </td>
                  <td colspan="10">
                      <input name="img_media_id" type="hidden" size="40" id="img_media_id" value=""/>
                      <input name="img_upload_thm" type="hidden" size="40" id="img_upload_id_thm" value=""/>
                      <input type="text" name="thumb_title" id="thumb_title" value="" readonly="yes"/>
                      <input type="hidden" name="content_type" id="content_type" value=""/>
                      <input type="button" value="image upload" onclick="getUploadData('product_manage','thumb_title','img_media_id','img_upload_id_thm','image','content_type');"/>
                      <input type="button" value="search" onclick="getUploadedDataList('product_manage','thumb_title','img_media_id','img_upload_id_thm','image','content_type');"/>
                  </td>
                </tr>
                <tr>
                  <td><label>Product Price</label></td>
                  <td>
                    <input class="warning" type="text" name="price" id="price" value=""/>
                  </td>
                </tr>
                <tr>
                  <td><label>Product Status</label></td>
                  <td colspan="14">
                    <select name="product_status" id="product_status">
                      <option value="1">Active</option>
                      <option value="0">InActive</option>
                    </select>
                  </td>
                </tr>
                <input type="hidden" name="groupmastercnt" id="groupmastercnt" value="{count(/XML/GROUP_MASTER/GROUP_MASTER_DATA)}"/>
                <xsl:for-each select="/XML/GROUP_MASTER/GROUP_MASTER_DATA">
                <xsl:variable name="groupmasterposition">
                <xsl:value-of select="position()"/>
                </xsl:variable>
                
                <!-- <tr>
                <td colspan="11">
                <span style="color:#0000A0">
                <h3><xsl:value-of select="MAIN_GROUP_NAME" disable-output-escaping="yes"/></h3>
                </span>
                </td>
                </tr> -->
                <tr>
                <td colspan="11">
                <div class="message info">
                <h3><xsl:value-of select="MAIN_GROUP_NAME" disable-output-escaping="yes"/></h3>
                </div>
                </td>
                </tr>
 


                <input type="hidden" name="subgroupmastercnt_{$groupmasterposition}" id="subgroupmastercnt_{$groupmasterposition}" value="{count(SUB_GROUP_MASTER)}"/>
                <xsl:for-each select="SUB_GROUP_MASTER">
                <xsl:variable name="subgroupmasterposition">
                <xsl:value-of select="position()"/>
                </xsl:variable>
                <tr>
                <td colspan="11">
                <div class="message success">
                <h3><xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/></h3>
                </div>
                </td>
                </tr>
                <input type="hidden" name="subgroupmaster_data_cnt_{$groupmasterposition}_{$subgroupmasterposition}" id="subgroupmaster_data_cnt_{$groupmasterposition}_{$subgroupmasterposition}" value="{count(SUB_GROUP_MASTER_DATA)}"/>
                <xsl:for-each select="SUB_GROUP_MASTER_DATA">
                <xsl:variable name="subgroupmaster_dataposition">
                <xsl:value-of select="position()"/>
                </xsl:variable>
                <tr>
                <xsl:choose>
                
                <xsl:when test="PIVOT_FEATURE_ID=FEATURE_ID">
                <xsl:choose>
                <xsl:when test="STATUS='1'">
                
                <td bgcolor="#16ADE9">
                <label><xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/>
                (<xsl:value-of select="FEATURE_STATUS" disable-output-esacaping="yes"/>)</label>
                </td>
                <td colspan="10" bgcolor="#16E99B">
                
                <input type="checkbox" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}"  value="No" onclick="setFeatureValue('feature_value_id_{PIVOT_FEATURE_ID}')" />
                <input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>
                </td>
                </xsl:when>
                <xsl:otherwise>
                
                <td bgcolor="#adadad">
                <label><xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/>
                (<xsl:value-of select="FEATURE_STATUS" disable-output-esacaping="yes"/>)</label>
                </td>
                <td colspan="10" bgcolor="#adadad">
                
                <input type="checkbox" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}"  value="No" onclick="setFeatureValue('feature_value_id_{PIVOT_FEATURE_ID}')" />
                <input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>

                </td>
                </xsl:otherwise>
                </xsl:choose>
                </xsl:when>
                
                <xsl:otherwise>
                <xsl:choose>
                <xsl:when test="STATUS='1'">
                
                <td bgcolor="gray">
                <label><xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/></label>
                </td>
                <td colspan="10" bgcolor="gray">
                <input class="warning" type="text" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" />
                <input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>
                </td>
                </xsl:when>
                <xsl:otherwise>
                
                <td bgcolor="#adadad">
                <label><xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/>
                (
                <xsl:value-of select="FEATURE_STATUS" disable-output-esacaping="yes"/></label>
                )
                </td>
                <td colspan="10" bgcolor="#adadad">
                <input  class="warning" type="text" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" />
                <input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>
                </td>
                </xsl:otherwise>
                </xsl:choose>
                </xsl:otherwise>
                </xsl:choose>
                </tr>
                </xsl:for-each>
                </xsl:for-each>
                </xsl:for-each>
                <tr>
                  <td>
                    <label>Arrival Date :</label>
                  </td>
                  <td>
                    <input type="text" name="start_date" id="start_date"  value=""/>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Announced Date :</label>
                  </td>
                  <td>
                    <input type="text" name="announced_date" id="announced_date" size="15" value=""/>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Discontinue Variant :</label>
                  </td>
                  <td>
                    <input type="checkbox" name="discontinue_flag" id="discontinue_flag"/>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label>Discontinued Date :</label>
                  </td>
                  <td>
                     <input type="text" name="end_date" id="end_date" size="15" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/DISCONTINUE_DATE}"/>
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