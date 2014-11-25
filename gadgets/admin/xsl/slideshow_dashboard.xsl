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
            <h2>SlideShow Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                           <th>Sr.No</th>
                            <th>Slideshow Title</th>
                            <th>Category Name</th>
                            <th>Brand Name</th>
                            <th>Product Name</th>
                            <!-- <th>Variant</th> -->
                            <th>Status</th>
                            <th>Create date</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/SLIDESHOW_MASTER/SLIDESHOW_MASTER_DATA">
                        <tr class="odd gradeX">
                                        <td>
                                        <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                                        </td>
                                        <td>
                                        <xsl:value-of select="TITLE" diseable-output-esacaping="yes"/>
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
                                        <!-- <td>
                                        <xsl:value-of select="PRODUCT_VARIANT" diseable-output-esacaping="yes"/>
                                        </td> -->
                                        <td>
                                        <xsl:value-of select="STATUS" diseable-output-esacaping="yes"/>
                                        </td> 

                                        <td>
                                        <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                        </td>
                                        <td>
                                        <a href="javascript:void(0);" id="updateMe" onclick="updateProductSlideshow('prod_article_dashboard','productajaxloader','','{CATEGORY_ID}','{PRODUCT_ID}','{PRODUCT_INFO_ID}','{BRAND_ID}',{PRODUCT_SLIDE_ID},'','');">Update</a>
                                        |
                                        <a href="javascript:undefined;" onclick="deleteProductSlideshow('{PRODUCT_SLIDE_ID}');">Delete</a>
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
        <h2>SlideShow Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}slideshow.php" name="product_manage" id="product_manage">
            <table class="form" id="Update">
                <tbody id="slideshowtbody">
                <tr>
                  <td>
                    <label>Brand Name</label>
                  </td>
                  <td>
                    <select name="select_brand_id" id="select_brand_id" onchange="getModelByBrand('ajaxloader','0');">
                      <option value="">---Select Brand---</option>
                      <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                      <xsl:choose>
                            <xsl:when test="/XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/BRAND_ID=BRAND_ID">
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
                    <div id="ajaxloader" style="display:none;">
                      <div align="center">
                        <img src="{/XML/IMAGE_URL}ajax-loader.gif"/>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                    <td>
                       <label>Slideshow Title</label>
                    </td>
                    <td>
                        <input class="medium" type="text" name="slide_title" id="slide_title" value="{XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/PRODUCT_TITLE}"/>
                        <input type="hidden" name="hd_product_slide_id" id="hd_product_slide_id" value="{XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/PRODUCT_SLIDE_ID}"/>
                    </td>
                </tr>
                <tr>
                    <td>
                       <label>Upload Slide Thumbnail Image:</label>
                    </td>
                    <td>
                            <input name="abstract_img_id" type="hidden" size="40" id="abstract_img_id" value="{XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/PRODUCT_SLIDE_MEDIA_ID}"/>
                            <input name="abstract_img_path" type="hidden" size="40" id="abstract_img_path" value="{XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/PRODUCT_SLIDE_MEDIA_PATH}"/>
                            <input type="hidden" name="content_type" id="content_type" value=""/>
                            <input class="medium" type="text" name="thumb_title" id="thumb_title" value="{XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/PRODUCT_SLIDE_MEDIA_PATH}" readonly="yes"/>
                           <!-- <button class="btn-icon btn-grey btn-check" onclick="getUploadData('product_manage','thumb_title','abstract_img_id','abstract_img_path','image','content_type');">image upload</button>
                             -->
                            <input type="button" name="btn_get" id="btn_get" value="image upload" onclick="getUploadData('product_manage','thumb_title','abstract_img_id','abstract_img_path','image','content_type');"/>

                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Abstract</label>
                    </td>
                    <td>
                        <textarea name="product_slide_abstract" id="product_slide_abstract" cols="60" rows="2" >
                            <xsl:value-of select="XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/PRODUCT_SLIDE_ABSTRACT"/>
                        </textarea>
                    </td>
                </tr>
                

                <xsl:if test="count(/XML/SLIDE_UPLOAD_DETAIL/SLIDE_UPLOAD_DATA)=0">
                        <tr style="border:1px solid #d5d5d5;">
                            <td><label>Slide Image 1</label></td>     
                            <td>
                                <label>Title </label><br/> <input class="medium" type="text" name="title_1" id="title_1" value=""/><br/>
                                <label>Slug </label><br/> <input class="medium" type="text" name="slug_1" id="slug_1" value=""/><br/>
                                <label>Tags </label><br/><input class="medium" type="text" name="tags_1" id="tags_1" value=""/><br/>
                                <label>Meta description </label><br/>
                                <textarea class="medium" name="meta_description_1" id="meta_description_1" cols="60" rows="2" ></textarea>
                                <br/><label>Upload Media (video/image) 1</label><br/>
                                <input class="medium" type="text" name="title_upload_file_1" id="title_upload_file_1" value="" />
                                <!-- <button class="btn btn-navy" name="btn_get" id="btn_get" onclick="getUploadData('product_manage','title_upload_file_1','media_id_1','img_upload_id_1_1','image','video_content_type_1');">media upload</button> -->
                                <input type="button" name="btn_get" id="btn_get" value="media upload" onclick="getUploadData('product_manage','title_upload_file_1','media_id_1','img_upload_id_1_1','image','video_content_type_1');"/>
                                <br/>
                                <span style="color:red;font-family:bold;">OR</span><br/>
                                <label>Upload Thumbnail Image 1</label><br/>
                                <input name="img_media_id_1" type="hidden" size="40" id="img_media_id_1" value=""/>
                                <input name="img_upload_id_thm_1" type="hidden" size="40" id="img_upload_id_thm_1" value=""/>
                                <input class="medium" type="text" name="thumb_title_1" id="thumb_title_1" value="" />  
                                <!-- <button class="btn btn-navy" name="btn_get" id="btn_get" onclick="getUploadData('product_manage','thumb_title_1','img_media_id_1','img_upload_id_thm_1','image','content_type_1');">image upload</button> -->
                                <input type="button" name="btn_get" id="btn_get" value="image upload" onclick="getUploadData('product_manage','thumb_title_1','img_media_id_1','img_upload_id_thm_1','image','content_type_1');"/>

                                <input type='checkbox' name='box_1' id='box_1' value='' /> Delete
                                <input name="media_id_1" type="hidden" size="40" id="media_id_1" value=""/>
                                <input name="media_upload_1_1" type="hidden" size="40" id="img_upload_id_1_1" value=""/>
                                <input type="hidden" name="video_content_type_1" id="video_content_type_1" value=""/>
                                <input type="hidden" name="content_type_1" id="content_type_1" value=""/>
                                <input type='hidden' name='check_flag_1' id='check_flag_1' value=''/>
                                <br/>
                                <label>Postion 1</label>
                                <input type='text' class="mini" name='ordering_1' id='ordering_1' value=''/>
                            </td>
                        </tr>
                        </xsl:if>
                        <!--xsl:if test="count(/XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA)>0"-->
                        <xsl:if test="count(/XML/SLIDE_UPLOAD_DETAIL/SLIDE_UPLOAD_DATA)>0">
                        <xsl:for-each select="/XML/SLIDE_UPLOAD_DETAIL/SLIDE_UPLOAD_DATA">
                        <tr style="border:1px solid #d5d5d5;">
                            <td> <label>Slide Image</label> <xsl:value-of select="position()"/></td>
                            <td style="border:1px solid #d5d5d5;">
                            <label>title :</label><br/><input class="medium" type="text" name="title_{position()}" id="title_{position()}" value="{TITLE}"/><br/>
                            <label>slug :</label><br/><input class="medium" type="text" name="slug_{position()}" id="slug_{position()}" value="{SLUG}"/><br/>
                            <label>tags :</label><br/><input class="medium" type="text" name="tags_{position()}" id="tags_{position()}" value="{TAGS}"/><br/>
                            <br/><label>meta description :</label><br/>
                            <textarea class="medium" name="meta_description_{position()}" id="meta_description_{position()}" cols="60" rows="2" >
                            <xsl:value-of select="META_DESCRIPTION"/>
                            </textarea>
                            <br/>
                            <label>Upload Media (upload video/audio/image) 1:</label> <br/> 
                            <input name="media_id_{position()}" type="hidden" size="40" id="media_id_{position()}" value="{MEDIA_ID}"/>
                            <input name="media_upload_1_{position()}" type="hidden" size="40" id="media_upload_1_{position()}" value="{VIDEO_IMG_PATH}"/>
                            <input class="medium" type="text" name="title_upload_file_{position()}" id="title_upload_file_{position()}" value="{VIDEO_IMG_PATH}" />
                            <input type="hidden" name="video_content_type_{position()}" id="video_content_type_{position()}" value="{CONTENT_TYPE}"/>
                           <!--  <button class="btn btn-navy"  name="btn_get" id="btn_get" onclick="getUploadData('product_manage','title_upload_file_{position()}','media_id_{position()}','media_upload_1_{position()}','image','video_content_type_{position()}');">media upload</button> -->
                         <input type="button" name="btn_get" id="btn_get" value="media upload" onclick="getUploadData('product_manage','title_upload_file_{position()}','media_id_{position()}','media_upload_1_{position()}','image','video_content_type_{position()}');"/>
                            <br/>
                            <span style="color:red;font-family:bold;">OR</span><br/>
                            <label>Upload thumbnail Image <xsl:value-of select="position()"/>:</label><br/>
                            <input name="img_media_id_{position()}" type="hidden" size="40" id="img_media_id_{position()}" value="{VIDEO_IMG_ID}"/>
                            <input name="img_upload_id_thm_{position()}" type="hidden" size="40" id="img_upload_id_thm_{position()}" value="{VIDEO_IMG_PATH}"/>
                            <input class="medium" type="text" name="thumb_title_{position()}" id="thumb_title_{position()}" value="{VIDEO_IMG_PATH}" />    
                            <input type="hidden" name="content_type_{position()}" id="content_type_{position()}" value="{CONTENT_TYPE}"/>
                            <!-- <button class="btn btn-navy"  name="btn_get" id="btn_get" onclick="getUploadData('product_manage','thumb_title_{position()}','img_media_id_{position()}','img_upload_id_thm_{position()}','image','content_type_{position()}');">image upload</button> -->
                            <input type="button" name="btn_get" id="btn_get" value="image upload" onclick="getUploadData('product_manage','thumb_title_{position()}','img_media_id_{position()}','img_upload_id_thm_{position()}','image','content_type_{position()}');"/>
                            <input type='checkbox' name="box_{position()}" id="box_{position()}" value='' /> Delete
                            <input type='hidden' name="check_flag_{position()}" id="check_flag_{position()}" value=''/>
                            <input name="upload_media_id_{position()}" id="upload_media_id_{position()}" value="{UPLOAD_MEDIA_ID}" type="hidden" /> 
                            <input type="hidden" name="slideshow_id_{position()}" id="slideshow_id_{position()}" value="{SLIDESHOW_ID}"/>
                            <input type="hidden" name="product_slide_id_{position()}" id="product_slide_id_{position()}" value="{PRODUCT_SLIDE_ID}"/>
                            <br/>
                            <label>Postion <xsl:value-of select="position()"/></label>
                            <input type='text' class="mini" name='ordering_{position()}' id='ordering_{position()}' value='{ORDERING}'/>
                            </td>
                        </tr>
                        </xsl:for-each>
                        </xsl:if>
                        <tr id="trAddRemove">
                            <td></td>
                            <td colspan="0" align="right">
                                <input id="add" value="Add More" onclick="addRemoveFileBrowseElements(1);" type="button" />&nbsp;
                                <input id="remove" value="Remove" onclick="addRemoveFileBrowseElements(0);" type="button" />
                            </td>
                        </tr>

                      <!--   <tr>
                        <td><label>Publish Time</label></td>
                        <td colspan="14">
                          <input type="text" name="publish_time" id="publish_time" length="15" value="{XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/PUBLISH_TIME}"/>(Format Required: YYYY-MM-DD HH:MM:SS)
                        </td>
                        </tr> -->
                
                <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                    <xsl:if test="/XML/WALLCNT!=0">
                            <input name="display_rows" id="display_rows" value="{/XML/WALLCNT}" type="hidden" />
                        </xsl:if>
                        <xsl:if test="/XML/WALLCNT=0">
                            <input name="display_rows" id="display_rows" value="1" type="hidden" />
                        </xsl:if>
                        
                        <select name="status" id="status">
                        <xsl:choose>
                            <xsl:when test="XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/STATUS='Active'">
                                <option value="1" selected='yes'>Active</option>
                            </xsl:when>
                            <xsl:otherwise>
                                <option value="1">Active</option>
                            </xsl:otherwise>
                        </xsl:choose>
                        <xsl:choose>
                            <xsl:when test="XML/SLIDESHOW_DETAIL/SLIDESHOW_DETAIL_DATA/STATUS='InActive'">
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
                            <input type="hidden" name="actiontype" id="actiontype" value="{/XML/SELECTED_ACTION_TYPE}"/>
                            <input type="hidden" name="slideshow_id" id="slideshow_id" value="{/XML/SLIDESHOW_ID}"/>
                            <input type="hidden" name="product_slideshow_id" id="product_slideshow_id" value=""/>
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

