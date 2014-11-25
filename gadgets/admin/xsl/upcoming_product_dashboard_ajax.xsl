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
            <h2>Upcoming Product Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Brand Name</th>
                            <th>Product Name</th>
                            <th>Expected Price</th>
                            <th>Expected Launch Date</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Position</th>
                            <th>create date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="/XML/UPCOMING_PRODUCT_LIST/UPCOMING_PRODUCT_LIST_DATA">
                            <tr class="odd gradeX">
                                <td>
                                    <xsl:value-of select="position()" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="BRAND_NAME" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="MODEL_NAME" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="EXPECTED_PRICE" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="EXPECTED_DATE_TEXT" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="START_DATE" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="END_DATE" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="STATUS" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <xsl:value-of select="POSITION" diseable-output-esacaping="yes"/>
                                </td>
                                <td>    
                                    <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                </td>
                                <td>
                                    <a href="#Update" id="updateMe" onclick="updateUpComingProduct('prod_article_dashboard','productajaxloader','{UPCOMING_PRODUCT_ID}','{PRODUCT_NAME_ID}','{CATEGORY_ID}','','');">Update</a>
                                    |
                                    <a href="javascript:undefined;" onclick="deleteUpComingProduct('{UPCOMING_PRODUCT_ID}');">Delete</a>
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
            <h2>Upcoming Product Add/Update</h2>
            <div class="block">
                <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}upcoming_product.php" name="product_manage" id="product_manage">
                    <table class="form" id="add_product_table">
                        <tbody id="slideshowtbody">
                        <tr>
                            <td><label>Upcoming Product List</label></td>
                            <td>
                                <select name="select_model_id" id="select_model_id" >
                                    <option value="">---Select Upcoming Product---</option>
                                    <xsl:for-each select="/XML/UPCOMING_PRODUCT_MASTER/UPCOMING_PRODUCT_MASTER_DATA">
                                        <xsl:choose>
                                            <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/PRODUCT_NAME_ID = PRODUCT_NAME_ID">
                                                <option value="{PRODUCT_NAME_ID}" selected='yes'>
                                                <xsl:value-of select="UPCOMING_PRODUCT_NAME"/>
                                                </option>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <option value="{PRODUCT_NAME_ID}">
                                                <xsl:value-of select="UPCOMING_PRODUCT_NAME"/>
                                                </option>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:for-each>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Phone Type</label></td>
                            <td>
                                <select name="select_feature_id" id="select_feature_id" >
                                    <option value="">---Select Phone Type---</option>
                                    <xsl:for-each select="/XML/PIVOT_MASTER/PIVOT_MASTER_DATA">
                                        <xsl:if test="SUB_GROUP_NAME='Phone Type'">
                                            <xsl:for-each select="SUB_PIVOT_MASTER/SUB_PIVOT_MASTER_DATA">
                                                <xsl:choose>
                                                    <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/FEATURE_ID = FEATURE_ID">
                                                        <option value="{FEATURE_ID}" selected='yes'>
                                                        <xsl:value-of select="FEATURE_NAME"/>
                                                        </option>
                                                    </xsl:when>
                                                    <xsl:otherwise>
                                                        <option value="{FEATURE_ID}">
                                                        <xsl:value-of select="FEATURE_NAME"/>
                                                        </option>
                                                    </xsl:otherwise>
                                                </xsl:choose>
                                            </xsl:for-each>
                                        </xsl:if>
                                    </xsl:for-each>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Expected Price</label></td>
                            <td>
                                Min Price
                                <input type="text" name="min_exp_price" id="min_exp_price" size="10" value="{/XML/UPCOMING_PRODUCT_DETAILS/MIN_EXPECTED_PRICE}"/>
                                <select name="select_min_price_unit" id="select_min_price_unit">
                                    <xsl:choose>
                                        <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/MIN_EXPECTED_PRICE_UNIT = '1000'">
                                            <option value="1000" selected='yes'>thousand</option>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <option value="1000">thousand</option>
                                        </xsl:otherwise>
                                        </xsl:choose>
                                        <xsl:choose>
                                            <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/MIN_EXPECTED_PRICE_UNIT = '100000'">
                                                <option value="10000" selected='yes'>lacs</option>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <option value="100000">lacs</option>
                                            </xsl:otherwise>
                                    </xsl:choose>
                                </select>
                                Max Price
                                <input type="text" name="max_exp_price" id="max_exp_price" size="10" value="{/XML/UPCOMING_PRODUCT_DETAILS/MAX_EXPECTED_PRICE}"/>
                                <select name="select_max_price_unit" id="select_max_price_unit">
                                    <xsl:choose>
                                        <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/MAX_EXPECTED_PRICE_UNIT = '1000'">
                                            <option value="1000" selected='yes'>thousand</option>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <option value="1000">thousand</option>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                    <xsl:choose>
                                        <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/MAX_EXPECTED_PRICE_UNIT = '100000'">
                                            <option value="100000" selected='yes'>lacs</option>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <option value="100000">lacs</option>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Expected Launch Date</label></td>
                            <td>
                                <input type="text" class="mini" name="exp_launch_text" id="exp_launch_text" size="30" value="{/XML/UPCOMING_PRODUCT_DETAILS/EXPECTED_DATE_TEXT}"/>(Text to be displayed across front end)
                            </td>
                        </tr>
                        <tr>
                            <td><label>Expected Launch Month</label></td>
                            <td>
                                <select name="select_exp_month" id="select_exp_month">
                                    <option value="">---Select Expected Month---</option>
                                    <xsl:for-each select="/XML/MONTH_MASTER/MONTH_MASTER_DATA">
                                        <xsl:choose>
                                            <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/EXPECTED_MONTH = MONTH_TEXT">
                                                <option value="{MONTH_TEXT}" selected='yes'>
                                                    <xsl:value-of select="MONTH_VAL"/>
                                                </option>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <option value="{MONTH_TEXT}">
                                                <xsl:value-of select="MONTH_VAL"/>
                                                </option>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:for-each>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Expected Launch Year</label></td>
                            <td>
                                <select name="select_exp_year" id="select_exp_year">
                                    <option value="">---Select Expected Year---</option>
                                    <xsl:for-each select="/XML/YEAR_MASTER/YEAR_MASTER_DATA">
                                        <xsl:choose>
                                            <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/EXPECTED_YEAR = YEAR_VAL">
                                            <option value="{YEAR_VAL}" selected='yes'>
                                            <xsl:value-of select="YEAR_VAL"/>
                                            </option>
                                            </xsl:when>
                                            <xsl:otherwise>
                                            <option value="{YEAR_VAL}">
                                            <xsl:value-of select="YEAR_VAL" />
                                            </option>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:for-each>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Start Date</label></td>
                            <td>
                                <xsl:choose>
                                <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/START_DATE!=''">
                                <input type="text" name="start_date" id="start_date" value="{/XML/UPCOMING_PRODUCT_DETAILS/START_DATE}" class="input-b-f-t input-b-f-t-M two" onfocus="myFocus(this);" onblur="myBlur(this);" />
                                </xsl:when>
                                <xsl:otherwise>
                                <input type="text" name="start_date" id="start_date" value="Date From" class="input-b-f-t input-b-f-t-M two" onfocus="myFocus(this);" onblur="myBlur(this);" />
                                </xsl:otherwise>
                                </xsl:choose>
                            </td>
                        </tr>
                        <tr>
                            <td><label>End Date</label></td>
                            <td>
                            <xsl:choose>
                                <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/END_DATE!=''">
                                    <input type="text" name="end_date" id="end_date" value="{/XML/UPCOMING_PRODUCT_DETAILS/END_DATE}" class="input-b-f-t two"  onfocus="myFocus(this);" onblur="myBlur(this);" />
                                </xsl:when>
                                <xsl:otherwise>
                                    <input type="text" name="end_date" id="end_date" value="Date To" class="input-b-f-t two"  onfocus="myFocus(this);" onblur="myBlur(this);" />
                                </xsl:otherwise>
                            </xsl:choose>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Short Description</label></td>
                            <td>
                                <textarea name="short_desc" id="short_desc" cols="80" rows="2" class="tinymce">
                                <xsl:value-of select="/XML/UPCOMING_PRODUCT_DETAILS/SHORT_DESCRIPTION"/>
                                </textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Content</label></td>
                            <td>
                                <textarea name="content" id="content" cols="80" rows="5" class="tinymce">
                                <xsl:value-of select="/XML/UPCOMING_PRODUCT_DETAILS/CONTENT"/>
                                </textarea>
                            </td>
                        </tr>
                        <xsl:choose>
                            <xsl:when test="count(/XML/MEDIA_UPLOAD_DETAIL/MEDIA_UPLOAD_DATA)=0">
                                <tr>
                                    <td><label>Add Video #1</label></td>
                                    <td>
                                        <label>Upload Media 1:</label><br/>
                                        <input name="media_id_1" type="hidden" size="40" id="media_id_1" value="{MEDIA_ID}"/>
                                        <input name="media_upload_1_1" type="hidden" size="40" id="media_upload_1_1" value="{VIDEO_IMG_PATH}"/>
                                        <input type="text" class="medium" name="title_upload_file_1" id="title_upload_file_1" value="{VIDEO_IMG_PATH}" />
                                        <input type="hidden" name="video_content_type_1" id="video_content_type_1" value="{CONTENT_TYPE}"/>
                                        <input type="button" name="btn_get" id="btn_get" value="media upload" onclick="getUploadData('product_manage','title_upload_file_1','media_id_1','media_upload_1_1','image','video_content_type_1');"/>
                                        <br/>
                                        <label>Add Embed Media Code 1:</label><br/>
                                        <input type="text" class="medium" name="external_media_source_1" id="external_media_source_1" size="90" style="padding:8px;" value="{EXTERNAL_MEDIA_SOURCE}"/>
                                        <br/>
                                        <label>Media Title 1:</label><br/>
                                        <xsl:choose>
                                            <xsl:when test="MEDIA_TITLE!=''">
                                                <input type="text" name="media_title_1" id="media_title_1" size="80" value="{MEDIA_TITLE}"/>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <input type="text" name="media_title_1" id="media_title_1" size="80" value=""/>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                        <br/>
                                        <span style="color:red;font-family:bold;">OR</span>
                                        <br/>
                                        <label>Upload Video default Image 1:</label><br/>
                                        <input name="img_media_id_1" type="hidden" size="40" id="img_media_id_1" value="{VIDEO_IMG_ID}"/>
                                        <input name="img_upload_id_thm_1" type="hidden" size="40" id="img_upload_id_thm_1" value="{VIDEO_IMG_PATH}"/>
                                        <input type="text" name="thumb_title_1" id="thumb_title_1" value="{VIDEO_IMG_PATH}" />
                                        <input type="hidden" name="content_type_1" id="content_type_1" value="{CONTENT_TYPE}"/>
                                        <input type="button" name="btn_get" id="btn_get" value="image upload" onclick="getUploadData('product_manage','thumb_title_1','img_media_id_1','img_upload_id_thm_1','image','content_type_1');"/>
                                        <input type='checkbox' name="box_1" id="box_1" value='' /> Delete
                                        <input type='hidden' name="check_flag_1" id="check_flag_1" value=''/>
                                        <input name="upcoming_product_video_id_1" id="upcoming_product_video_id_1" value="{UPCOMING_PRODUCT_VIDEO_ID}" type="hidden" />                                                                       
                                        <br/>
                                        <label>Image Caption 1:</label><br/>
                                        <input type="text" name="image_title_1" id="image_title_1" size="80" value="{IMAGE_TITLE}"/>
                                    </td>
                                </tr>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:for-each select="/XML/MEDIA_UPLOAD_DETAIL/MEDIA_UPLOAD_DATA">
                                    <tr>
                                        <td><label>Add Video #<xsl:value-of select="position()"/></label></td>
                                        <td colspan="10">
                                            <label>Upload Media <xsl:value-of select="position()"/></label>
                                            <input name="media_id_{position()}" type="hidden" size="40" id="media_id_{position()}" value="{MEDIA_ID}"/>
                                            <input name="media_upload_1_{position()}" type="hidden" size="40" id="media_upload_1_{position()}" value="{VIDEO_IMG_PATH}"/>
                                            <input type="text" name="title_upload_file_{position()}" id="title_upload_file_{position()}" value="{VIDEO_IMG_PATH}" />
                                            <input type="hidden" name="video_content_type_{position()}" id="video_content_type_{position()}" value="{CONTENT_TYPE}"/>
                                            <input type="button" name="btn_get" id="btn_get" value="media upload" onclick="getUploadData('product_manage','title_upload_file_{position()}','media_id_{position()}','media_upload_1_{position()}','image','video_content_type_{position()}');"/>
                                            <br/>
                                            <label>Add Embed Media Code <xsl:value-of select="position()"/>:</label>
                                            <input type="text" name="external_media_source_{position()}" id="external_media_source_{position()}" size="90" style="padding:8px;" value="{EXTERNAL_MEDIA_SOURCE}"/>
                                            <br/>
                                            <label>Media Title <xsl:value-of select="position()"/>:</label>
                                            <xsl:choose>
                                            <xsl:when test="MEDIA_TITLE!=''">
                                            <input type="text" name="media_title_{position()}" id="media_title_{position()}" size="80" value="{MEDIA_TITLE}"/>
                                            </xsl:when>
                                            <xsl:otherwise>
                                            <input type="text" name="media_title_{position()}" id="media_title_{position()}" size="80" value=""/>
                                            </xsl:otherwise>
                                            </xsl:choose>
                                            <br/>
                                            <span style="color:red;font-family:bold;">OR</span>
                                            <br/>
                                            <label>Upload Video default Image <xsl:value-of select="position()"/>:</label>
                                            <input name="img_media_id_{position()}" type="hidden" size="40" id="img_media_id_{position()}" value="{VIDEO_IMG_ID}"/>
                                            <input name="img_upload_id_thm_{position()}" type="hidden" size="40" id="img_upload_id_thm_{position()}" value="{VIDEO_IMG_PATH}"/>
                                            <input type="text" name="thumb_title_{position()}" id="thumb_title_{position()}" value="{VIDEO_IMG_PATH}" />
                                            <input type="hidden" name="content_type_{position()}" id="content_type_{position()}" value="{CONTENT_TYPE}"/>
                                            <input type="button" name="btn_get" id="btn_get" value="image upload" onclick="getUploadData('product_manage','thumb_title_{position()}','img_media_id_{position()}','img_upload_id_thm_{position()}','image','content_type_{position()}');"/>
                                            <input type='checkbox' name="box_{position()}" id="box_{position()}" value='' /> Delete
                                            <input type='hidden' name="check_flag_{position()}" id="check_flag_{position()}" value=''/>
                                            <input name="upcoming_product_video_id_{position()}" id="upcoming_product_video_id_{position()}" value="{UPCOMING_PRODUCT_VIDEO_ID}" type="hidden" />
                                            <br/>
                                            <label>Image Caption <xsl:value-of select="position()"/>:</label>
                                            <input type="text" name="image_title_{position()}" id="image_title_{position()}" size="80" value="{IMAGE_TITLE}"/>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                            </xsl:otherwise>
                        </xsl:choose>
                        <tr id="trAddRemove">
                            <td></td>
                            <td colspan="0" align="right">
                                <input id="add" value="Add More" onclick="addRemoveFileBrowseElements(1);" type="button" />&nbsp;
                                <input id="remove" value="Remove" onclick="addRemoveFileBrowseElements(0);" type="button" />
                            </td>
                        </tr>    
                        <tr>
                            <td><label>Latest Product Status#1</label></td>
                            <td>
                                <xsl:choose>
                                    <xsl:when test="/XML/WALLCNT!=0">
                                        <input name="display_rows" id="display_rows" value="{/XML/WALLCNT}" type="hidden" />
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <input name="display_rows" id="display_rows" value="1" type="hidden" />
                                    </xsl:otherwise>
                                </xsl:choose>
                                <select name="product_status" id="product_status">
                                    <xsl:choose>
                                    <xsl:when test="XML/UPCOMING_PRODUCT_DETAILS/STATUS='Active'">
                                        <option value="1" selected='yes'>Active</option>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <option value="1">Active</option>
                                    </xsl:otherwise>
                                    </xsl:choose>
                                    <xsl:choose>
                                        <xsl:when test="XML/UPCOMING_PRODUCT_DETAILS/STATUS='InActive'">
                                            <option value="0" selected='yes'>InActive</option>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <option value="0">InActive</option>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </select>
                            </td>
                        </tr>   
                        <tr>
                            <td><label>Position</label></td>
                            <td>
                                <select name="position" id="position">
                                    <option value="">--select order--</option>
                                    <xsl:for-each select="/XML/UPCOMING_PRODUCT_LIST/UPCOMING_PRODUCT_LIST_POSITION/UPCOMING_PRODUCT_POSITION">
                                        <xsl:choose>
                                            <xsl:when test="/XML/UPCOMING_PRODUCT_DETAILS/POSITION = POSITION">
                                                <option value="{POSITION}" selected="yes"><xsl:value-of select="POSITION"/></option>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <option value="{POSITION}"><xsl:value-of select="POSITION"/></option>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </xsl:for-each> 
                                </select>
                                <input type="hidden" name="actiontype" id="actiontype" value="Insert"/>
                                <input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                                <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                                <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                                <input type="hidden" name="upcoming_product_id" id="upcoming_product_id" value="{XML/UPCOMING_PRODUCT_DETAILS/UPCOMING_PRODUCT_ID}"/>
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