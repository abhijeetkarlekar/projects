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
            <h2>Model Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                           <th>Sr.No</th>
                            <th>SEO Path</th>
                            <th>Model Title</th>
                            <th>Category Name</th>
                            <th>Brand Name</th>
                            <th>Status</th>
                            <th>Discontinue Status</th>
                            <th>Upcoming Status</th>
                            <th>Create date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/MODEL_DETAIL/MODEL_DETAIL_DATA">
                        <tr class="odd gradeX">
                                                    <td>
                                                        <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="SEO_PATH" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="PRODUCT_INFO_NAME" diseable-output-esacaping="yes"/>
                                                    </td>

                                                    <td>
                                                        <xsl:value-of select="CATEGORY_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="BRAND_NAME" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="DISCONTINUE_STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="UPCOMING_STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <a href="#Update" id="updateMe" onclick="updateProductModel('prod_article_dashboard','productajaxloader','{PRODUCT_NAME_ID}','{CATEGORY_ID}','{BRAND_ID}','','','{/XML/SELECTED_SEARCH_STATUS}');">Update</a> | 
                                                    
                                                        <a href="javascript:undefined;" onclick="deleteProductModel('{PRODUCT_NAME_ID}');">Delete</a>
                                                   
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
        <h2>Model Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}model.php" name="product_manage" id="product_manage">
            <table class="form">
                <tr>
                    <td>
                        <label>Brand name</label>
                    </td>
                    <td>
                        <select name="select_brand_id" id="select_brand_id">
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
                       <label>Model Name</label>
                    </td>
                    <td>
                        <input class="warning" type="text" name="model_title" id="model_title" size="80" value="{/XML/MODEL_MASTER/MODEL_MASTER_DATA/PRODUCT_INFO_NAME}"/>
                    </td>
                </tr>
                <tr>
                    <td>
                       <label>Seo Path </label>
                    </td>
                    <td>
                        <input class="warning" type="text" name="seo_path" id="seo_path" size="80" value="{/XML/MODEL_MASTER/MODEL_MASTER_DATA/SEO_PATH}"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Model Tags</label>
                    </td>
                    <td>
                        <input type="text" name="model_tags" id="model_tags" size="80" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/TAGS}"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Description</label>
                    </td>
                    <td>
                       <textarea name="model_description" id="model_description" cols="80" rows="5" class="tinymce">
                            <xsl:value-of select="XML/MODEL_MASTER/MODEL_MASTER_DATA/PRODUCT_NAME_DESC"/>
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                     <label>Upload Main Image:</label>
                    </td>
                    <td>
                        
                            <input name="media_id" type="hidden" size="40" id="media_id" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/MEDIA_ID}"/>
                            <input name="img_upload_1" type="hidden" size="40" id="img_upload_id_1" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/VIDEO_PATH}"/>
                            <input type="text" name="title_upload_file" id="title_upload_file" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/VIDEO_PATH}" readonly="yes"/>
                            <input type="hidden" name="video_content_type" id="video_content_type" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/CONTENT_TYPE}"/>
                            <input type="button"  value="media upload" onclick="getUploadData('product_manage','title_upload_file','media_id','img_upload_id_1','image','video_content_type');"/>
                            <input type="button"  value="search" onclick="getUploadedDataList('product_manage','title_upload_file','media_id','img_upload_id_1','image','video_content_type');"/>
                        
                    </td>
                </tr>
                <tr>
                    <td>Upload Thumb Image:</td>
                    <td>
                        <input name="img_media_id" type="hidden" size="40" id="img_media_id" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/IMG_MEDIA_ID}"/>
                        <input name="img_upload_thm" type="hidden" size="40" id="img_upload_id_thm" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/IMAGE_PATH}"/>
                        <input type="text" name="thumb_title" id="thumb_title" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/IMAGE_PATH}" readonly="yes"/>
                        <input type="hidden" name="content_type" id="content_type" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/CONTENT_TYPE}"/>
                        <input type="button" value="image upload" onclick="getUploadData('product_manage','thumb_title','img_media_id','img_upload_id_thm','image','content_type');"/>
                        <input type="button" value="search" onclick="getUploadedDataList('product_manage','thumb_title','img_media_id','img_upload_id_thm','image','content_type');"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Model Status</label>
                    </td>
                    <td>
                     <xsl:if test="/XML/WALLCNT!=0">
                        <input name="display_rows" id="display_rows" value="{/XML/WALLCNT}" type="hidden" />
                        </xsl:if>
                        <xsl:if test="/XML/WALLCNT=0">
                        <input name="display_rows" id="display_rows" value="1" type="hidden" />
                        </xsl:if>
                            <select name="model_status" id="model_status">
                            <xsl:choose>
                                <xsl:when test="XML/MODEL_MASTER/MODEL_MASTER_DATA/STATUS='1'">
                                    <option value="1" selected='yes'>Active</option>
                                </xsl:when>
                                <xsl:otherwise>
                                    <option value="1">Active</option>
                                </xsl:otherwise>
                            </xsl:choose>
                            <xsl:choose>
                                <xsl:when test="XML/MODEL_MASTER/MODEL_MASTER_DATA/STATUS='0'">
                                    <option value="0" selected='yes'>InActive</option>
                                </xsl:when>
                                <xsl:otherwise>
                                    <option value="0">InActive</option>
                                </xsl:otherwise>
                            </xsl:choose>
                        </select>
                         <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                        <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                        <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                        <input type="hidden" name="product_name_id" id="product_name_id" value="{/XML/MODEL_MASTER/MODEL_MASTER_DATA/PRODUCT_NAME_ID}"/>
                        <input type="hidden" name="actiontype" id="actiontype" value="{/XML/SELECTED_ACTION_TYPE}"/>
                        <input type="hidden" name="featureboxcnt" id="featureboxcnt" value="{/XML/FEATURE_MASTER/COUNT}"/>
                    </td>
                </tr>
                <tr>
                                                    <td>Arrival Date :</td><td>
                                                    <input type="text" name="start_date" id="start_date" size="15" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/ARRIVAL_DATE}"/></td>
                                                    </tr>
                                                    <tr>
                                                    <td>Discontinue Model :</td><td>
                                                    <xsl:choose>
                                                    <xsl:when test="/XML/MODEL_MASTER/MODEL_MASTER_DATA/DISCONTINUE_FLAG='0'">
                                                        <input type="checkbox" name="discontinue_flag" id="discontinue_flag" checked="yes"/>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <input type="checkbox" name="discontinue_flag" id="discontinue_flag"/>
                                                    </xsl:otherwise>
                                                    </xsl:choose>

                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td>Discontinued Date :</td><td>
                                                    <input type="text" name="end_date" id="end_date" size="15" value="{XML/MODEL_MASTER/MODEL_MASTER_DATA/DISCONTINUE_DATE}"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Upcoming Model :</td><td>
                                                        <xsl:choose>
                                                        <xsl:when test="/XML/MODEL_MASTER/MODEL_MASTER_DATA/UPCOMING_FLAG='1'">
                                                                <input type="checkbox" name="upcoming_flag" id="upcoming_flag" checked="yes"/>
                                                        </xsl:when>
                                                        <xsl:otherwise>
                                                                <input type="checkbox" name="upcoming_flag" id="upcoming_flag"/>
                                                        </xsl:otherwise>
                                                        </xsl:choose>

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

