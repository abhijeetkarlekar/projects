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
            <h2>New Arrival Product Management</h2>
            <div class="block">
                <table class="data display datatable" id="example">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Category Name</th>
                            <th>Brand Name</th>
                            <th>Product Name</th>
                            <th>Position</th>
                            <th>Status</th>
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
                                                        <xsl:value-of select="PRODUCT_POSITION" diseable-output-esacaping="yes"/>
                                                    </td>
                                                    <td>
                                                        <xsl:value-of select="PRODUCT_STATUS" diseable-output-esacaping="yes"/>
                                                    </td>
                                                      <td>
                                                        <xsl:value-of select="CREATE_DATE" diseable-output-esacaping="yes"/>
                                                    </td>
                                                     <td>
                                                        <a href="#Update" id="updateMe" onclick="updateArrivalProduct('prod_article_dashboard','productajaxloader','{ARRIVAL_PRODUCT_ID}','{PRODUCT_ID}','{CATEGORY_ID}','{BRAND_ID}','{PRODUCT_INFO_ID}','','');">Update</a>
                                                    |
                                                        <a href="javascript:undefined;" onclick="deleteArrivalProduct('{ARRIVAL_PRODUCT_ID}');">Delete</a>
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
        <h2>New Arrival Add/Update</h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" method="post" action="{XML/ADMIN_WEB_URL}newarrival_product.php" name="product_manage" id="product_manage">
            <table class="form">
                <tr>
                    <td><label>Brand Name</label></td>
                    <td colspan="10">
                    <select name="select_brand_id" id="select_brand_id" onchange="getModelByBrandDashboard('','select_model_variant.php','Model','');">
                    <option value="0">---Select Brand---</option>
                    <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                    <xsl:choose>
                    <xsl:when test="/XML/PRODUCT_DATA/BRAND_ID=BRAND_ID">
                    <option value="{BRAND_ID}" selected='yes'>
                    <xsl:value-of select="BRAND_NAME"/>
                    </option>
                    </xsl:when>
                    <xsl:otherwise>
                    <option value="{BRAND_ID}">
                    <xsl:value-of select="BRAND_NAME"/>
                    </option>
                    </xsl:otherwise>                                                             </xsl:choose>                                                          </xsl:for-each> 
                    </select>
                    </td>
                </tr>
                <tr>
                                                     <td><label>Model Name</label></td>
                                                     <td id="Model">
                                        <select name="select_model_id" id="select_model_id">
                                                <option value="">--All Models--</option>
                                          </select>                             
                                                        </td>
                            
                                                    </tr>
                <tr>
                                                        <td><label>Product Position</label></td>
                                                        <td colspan="10">
                                                            <select id="product_position" name="product_position">
                                                                <option value="0">---Select Position---</option>
                                                                <xsl:for-each select="/XML/POSITION_MASTER/POSITION_MASTER_DATA">
                                                                    <xsl:if test="/XML/PRODUCT_DATA/PRODUCT_POSITION=POSITION">
                                                                        <option value="{POSITION}" selected="yes">
                                                                            <xsl:value-of select="POSITION" />
                                                                        </option>
                                                                    </xsl:if>
                                                                    <xsl:if test="not(/XML/PRODUCT_DATA/POSITION=POSITION)">
                                                                        <option value="{POSITION}">
                                                                            <xsl:value-of select="POSITION" />
                                                                        </option>
                                                                    </xsl:if>                                                                    
                                                                </xsl:for-each>
                                                            </select>
                                                        </td>
                                                    </tr>
              
              
               
               
                <tr>
                    <td>
                        <label>Status</label>
                    </td>
                    <td>
                        <select name="product_status" id="product_status">
                            <xsl:choose>
                                                                <xsl:when test="XML/PRODUCT_DATA/PRODUCT_STATUS='Active'">
                                                                    <option value="1" selected='yes'>Active</option>
                                                                </xsl:when>
                                                                <xsl:otherwise>
                                                                    <option value="1">Active</option>
                                                                </xsl:otherwise>
                                                                </xsl:choose>
                                                                <xsl:choose>
                                                                <xsl:when test="XML/PRODUCT_DATA/PRODUCT_STATUS='InActive'">
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
                       
                       <input type="hidden" name="arrival_product_id" id="arrival_product_id" value="{XML/PRODUCT_DATA/ARRIVAL_PRODUCT_ID}"/>
                        <input type="hidden" name="actiontype" id="actiontype" value="insert"/>
                        <input type="hidden" name="featureboxcnt" id="featureboxcnt" value="{/XML/FEATURE_MASTER/COUNT}"/>
                    </td>
                </tr>
                <tr><td></td><td><button class="btn btn-navy" onclick="return validateProduct();">Add/Update</button><!-- <button class="btn btn-navy">Cancel</button> --></td></tr>
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

