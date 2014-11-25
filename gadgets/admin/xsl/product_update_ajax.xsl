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
<xsl:include href="../xsl/inc_header.xsl" /><!-- include header-->
<xsl:include href="../xsl/inc_footer.xsl" /><!-- include footer-->
<xsl:include href="../xsl/inc_leftnavigation.xsl" /><!-- include left navigation-->
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gadgets Admin</title>

<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}reset.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}text.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}grid.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}layout.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}nav.css" media="screen" />

<link href="{XML/ADMIN_CSS_URL}fancy-button/fancy-button.css" rel="stylesheet" type="text/css" media="screen"/>
 <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.core.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.resizable.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.selectable.css" media="screen" />
<!-- <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.accordion.css" media="screen" /> -->
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.autocomplete.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.button.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.dialog.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.slider.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.tabs.css" media="screen" /> 
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.datepicker.css"  />
<link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.progressbar.css" media="screen" />
<!--Jquery UI CSS-->
<!-- <link href="{XML/ADMIN_CSS_URL}themes/base/jquery.ui.all.css" rel="stylesheet" type="text/css" /> -->
<!--[if IE 6]><link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}ie6.css" media="screen" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}ie.css" media="screen" /><![endif]-->

<link href="{XML/ADMIN_CSS_URL}table/demo_page.css" rel="stylesheet" type="text/css" />
<!-- BEGIN: load jquery -->
<script src="{XML/ADMIN_JS_URL}jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.core.min.js"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.widget.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.accordion.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.core.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.slide.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.mouse.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.sortable.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}table/jquery.dataTables.min.js" type="text/javascript"></script>
<!-- END: load jquery -->
<!--script type="text/javascript" src="{XML/ADMIN_JS_URL}table/table.js"></script-->
<script src="{XML/ADMIN_JS_URL}setup.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.widget.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.datepicker.min.js" type="text/javascript"></script>
<script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.progressbar.min.js" type="text/javascript"></script>
<script>
var admin_web_url = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';
var image_url = '<xsl:value-of select="/XML/ADMIN_IMAGE_URL" disable-output-escaping="yes"/>';
</script>
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}common.js"></script>
<script LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}category.js"></script>
<!-- <script src="{XML/ADMIN_JS_URL}jquery.ui.datepicker.js"></script> -->
<script language="javascript" src="{XML/ADMIN_JS_URL}product.js"></script>
<script src="js/tiny-mce/jquery.tinymce.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
	setupLeftMenu();
	$('.datatable').dataTable();
	setSidebarHeight();
	//setupTinyMCE();
   // setupProgressbar('progress-bar');
    setDatePicker('date-picker');
});
</script>
</head>
<body>
	<div class="container_12">
		<div class="grid_12 header-repeat">
			<xsl:call-template name="incHeader"/>
		</div>
		<div class="clear"></div>
		
		<div class="grid_12">
			<div class="box round first fullpage"> 
				<h2>Product Managment</h2>
				<div class="block">
					   <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}product.php" name="product_update_manage" id="product_update_manage" onsubmit="return validateProduct();return false;">
                                  
                        <table  id="Update" class="form" >
                          <tr>
                            <td><label>Brand Name</label></td>
                            <td colspan="10">
                            	<input type="hidden" name="startlimit" id="startlimit" value="{/XML/STARTLIMIT}"/>
                              <input type="hidden" name="cnt" id="cnt" value="{/XML/CNT}"/>
                              <input type="hidden" name="product_id" id="product_id" value="{/XML/SELECTED_PRODUCT_ID}"/>
                              <input type="hidden" name="actiontype" id="actiontype" value="update"/>
                              <input type="hidden" name="featureboxcnt" id="featureboxcnt" value="{/XML/FEATURE_MASTER/COUNT}"/>
                              <select name="select_brand_id" id="select_brand_id" onchange="getModelByBrand('ajaxloader','0');">
                                <option value="">---Select Brand---</option>
                                <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                                  <xsl:choose>
                                    <xsl:when test="/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/BRAND_ID=BRAND_ID">
                                      <option value="{BRAND_ID}" selected="yes">
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
								<img src="{/XML/ADMIN_IMAGE_URL}ajax-loader.gif"/>
								</div>
								</div>
                            </td>
                          </tr>
			 <tr id="updateproduct" style="">

                            <td><label>Product Name</label></td>
                            <td colspan="10">
                              <!--<input type="text" name="product_name" id="product_name" size="50" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/PRODUCT_NAME}" readonly="true"/>-->
							 <select name="product_name" id="product_name">
                                <option value="">---Select Model---</option>
                                <xsl:for-each select="/XML/MODEL_MASTER/MODEL_MASTER_DATA">
                                  <xsl:choose>
									<xsl:when test="PRODUCT_INFO_NAME=/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/PRODUCT_NAME">
										 <option value="{PRODUCT_NAME_ID}" selected="yes">
											<xsl:value-of select="PRODUCT_INFO_NAME"/>
										</option>
									</xsl:when>
									<xsl:otherwise>
										 <option value="{PRODUCT_NAME_ID}">
											<xsl:value-of select="PRODUCT_INFO_NAME"/>
										 </option>
									</xsl:otherwise>
								  </xsl:choose>
                                </xsl:for-each>
                              </select>
                            </td>
                          </tr>

                          <tr>
                            <td><label>Variant</label></td>
                            <td colspan="10">
                              <input class="warning" type="text" name="varient" id="varient" size="40" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/VARIANT}"/>
                            </td>
                          </tr>
                          <tr>
                            <td><label>SEO Path</label></td>
                            <td colspan="10">
                              <input class="warning" type="text" name="seo_path" id="seo_path" size="40" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/SEO_PATH}"/>
                            </td>
                          </tr>
                          <tr>
                            <td><label>Product Description</label></td>
                            <td colspan="10">
                              <textarea class="warning" name="product_description" id="product_description" cols="30">
                                <xsl:value-of select="/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/PRODUCT_DESC" disable-output-escaping="yes"/>
                              </textarea>
                            </td>
                          </tr>

			    <tr>
				<td><label>Upload Thumb:</label></td>
				<td >
					<input name="img_media_id" type="hidden" size="40" id="img_media_id" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/IMG_MEDIA_ID}"/>
					<input name="img_upload_thm" type="hidden" size="40" id="img_upload_id_thm" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/IMAGE_PATH}"/>
					<input type="text" name="thumb_title" id="thumb_title" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/IMAGE_PATH}" readonly="yes"/>
					<input type="hidden" name="content_type" id="content_type" value=""/>
					
					<input type="button" name="btn_get" id="btn_get"  value="Image upload" onclick="getUploadData('product_manage','thumb_title','img_media_id','img_upload_id_thm','image','content_type');"/>
					<input type="button" value="Search" name="btn_search" id="btn_search"  onclick="getUploadedDataList('product_manage','thumb_title','img_media_id','img_upload_id_thm','image','content_type');"/>
				</td>
				<td><img src="{/XML/CENTRAL_IMAGE_URL}/{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/IMAGE_PATH}"/></td>
			    </tr>
          <tr>
                              <td><label>Product Price</label></td>
                              <td colspan="14">
                                <input class="warning" type="text" name="price" id="price" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/VARIANT_VALUE}"/>
                              </td>
                            </tr>
                          <tr>
                            <td><label>Product Status</label></td>
                            <td colspan="14">
                              <select name="product_status" id="product_status">
								<xsl:choose>
									<xsl:when test="1=/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/STATUS">
		                                <option value="1" selected="yes">Active</option>
									</xsl:when>
									<xsl:otherwise>
										<option value="1">Active</option>
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="0=/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/STATUS">
		                                <option value="0" selected="yes">InActive</option>
										</xsl:when>
									<xsl:otherwise>
										<option value="0">InActive</option>
									</xsl:otherwise>
								</xsl:choose>
                              </select>
                            </td>
                          </tr>

                          <input type="hidden" name="groupmastercnt" id="groupmastercnt" value="{count(/XML/GROUP_MASTER/GROUP_MASTER_DATA)}"/>
                            <xsl:for-each select="/XML/GROUP_MASTER/GROUP_MASTER_DATA">
				<xsl:variable name="groupmasterposition">
					<xsl:value-of select="position()"/>
				</xsl:variable>
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
                                  <!--start condition is used to check complusary product feature-->
                                  <xsl:when test="PIVOT_FEATURE_ID=FEATURE_ID">
                                    <xsl:choose>
                                      <xsl:when test="STATUS='1'">
                                        <!--start condition is used to check active product-->
                                        <td bgcolor="#16ADE9">
                                          <xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/>
                                          (<xsl:value-of select="FEATURE_STATUS" disable-output-esacaping="yes"/>)
                                        </td>
                                        <td colspan="10" bgcolor="#16E99B">
<!--input type="text" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" size="40" value="{FEATURE_VALUE}"/-->
					<xsl:choose>
					<xsl:when test="FEATURE_VALUE='Yes'">
<input type="checkbox" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" size="40" value="{FEATURE_VALUE}" onclick="setFeatureValue('feature_value_id_{PIVOT_FEATURE_ID}')" checked="checked"/>
<input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>
					</xsl:when>
					<xsl:otherwise>
						<input type="checkbox" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" size="40" value="{FEATURE_VALUE}" onclick="setFeatureValue('feature_value_id_{PIVOT_FEATURE_ID}')"/>
<input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>
					</xsl:otherwise>
					</xsl:choose>	
                                        </td>
                                      </xsl:when>
                                      <xsl:otherwise>
                                        <!--start condition is used to check inactive product-->
                                        <td bgcolor="#adadad">
                                          <xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/>
                                          (<xsl:value-of select="FEATURE_STATUS" disable-output-esacaping="yes"/>)
                                        </td>
                                        <td colspan="10" bgcolor="#adadad">
<!--input type="text" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" size="40" value="{FEATURE_VALUE}"/-->
<input type="checkbox" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" size="40" value="{FEATURE_VALUE}" onclick="setFeatureValue('feature_value_id_{PIVOT_FEATURE_ID}')"/>
<input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>

                                        </td>
                                      </xsl:otherwise>
                                    </xsl:choose>
                                  </xsl:when>
                                  <!--start condition is used to check not complusary product feature-->
                                  <xsl:otherwise>
                                    <xsl:choose>
                                      <xsl:when test="STATUS='1'">
                                        <!--start condition is used to check active product-->
                                        <td bgcolor="gray">
                                          <label><xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/></label>
                                        </td>
                                        <td>
<input class="warning" type="text" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" size="40" value="{FEATURE_VALUE}"/>
<input type="hidden" name="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  id="feature_id_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}"  value="{FEATURE_ID}"/>
                                        </td>
                                      </xsl:when>
                                      <xsl:otherwise>
                                        <!--start condition is used to check inactive product-->
                                        <td bgcolor="#adadad">
                                          <label><xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/>
(
                                          <xsl:value-of select="FEATURE_STATUS" disable-output-esacaping="yes"/></label>
)
                                        </td>
                                        <td>
<input class="warning" type="text" name="feature_value_{$groupmasterposition}_{$subgroupmasterposition}_{$subgroupmaster_dataposition}" id="feature_value_id_{PIVOT_FEATURE_ID}" size="40" value="{FEATURE_VALUE}"/>
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
                            <td colspan="11">
                              <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CATEGORY_ID}"/>
                              <input type="hidden" name="select_brand" id="select_brand" value="{/XML/SELECTED_BRAND_ID}"/>
                              <input type="hidden" name="Model" id="Model" value="{/XML/SELECTED_MODEL_ID}"/>
                              <input type="hidden" name="Variant" id="Variant" value="{/XML/SELECTED_VARIANT_ID}"/>
                            </td>
                          </tr>
                         <tr>
				<td>Arrival Date :</td><td><input type="text" name="start_date" id="start_date" size="15" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/ARRIVAL_DATE}"/></td>
			</tr>
			<tr>
                                <td>Announced Date :</td>
                                <td>
                                <input type="text" name="announced_date" id="announced_date" size="15" value="{/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/ANNOUNCED_DATE}"/>
                                </td>
                                </tr>
			<tr>
													<td>Discontinue Variant :</td><td>
													<xsl:choose>
													<xsl:when test="/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/DISCONTINUE_FLAG='0'">
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
													<input type="text" name="end_date" id="end_date" size="15" value="{XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA/DISCONTINUE_DATE}"/></td>
													</tr>
			  <tr>
                            <td colspan="9">&nbsp;</td>
                            <td>
                              <div align="center">
                                <button class="btn btn-navy" name="save"  onclick="javascript:updateSubmit();">Update</button>
                              </div>
                            </td>
                            <td>
                              <div align="center">
                                <button class="btn btn-navy" name="cancel"  onclick="javascript:disp_product_details('{/XML/SELECTED_CATEGORY_ID}','{/XML/SELECTED_BRAND_ID}','{/XML/SELECTED_MODEL_ID}','{/XML/SELECTED_VARIANT_ID}');">Cancel</button>
                              </div>
                            </td>
                          </tr>
                        </table>
                     
                </form>
				</div>
			</div>
		</div>
		  
                           
		<div class="clear">
		</div>
	</div>
	
	<xsl:call-template name="incFooter"/>
</body>
   
</html>
</xsl:template>
</xsl:stylesheet>