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
        <h2>Upload Pivot Features Parameters </h2>
        <div class="block">
            <!-- <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}brand.php" method="post" name="brand_action" id="brand_action" onsubmit="return validateBrand();"> -->
            <form enctype="multipart/form-data" action="{/XML/ADMIN_WEB_URL}product_pivotfeatures_upload.php" method="post" name="top_selling_car_data" id="top_selling_car_data">
            <table class="form">
                <tr>
                    <td colspan="18" align='center'><h3>Upload Pivot Features Parameters</h3></td>
                </tr>
                <tr>
                    <td >Upload file:</td>
                    <td colspan="10"><input type="file" id="xls_file" name="xls_file" />
                    <button class="btn btn-navy" onclick="return validatefile();">Add/Update</button></td>
                    <td><a href="{XML/ADMIN_WEB_URL}download_product_pivot_values.php">Download Pivot Features Parameters Sheet</a></td>
                </tr>
                
            </table>
            </form>

            </div>
        </div>
    </div>


</xsl:template>
</xsl:stylesheet>

