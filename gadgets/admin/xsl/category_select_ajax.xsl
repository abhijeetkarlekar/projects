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
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>:: Ajax - Admin Category Management ::</title>
                <!--link rel="stylesheet" type="text/css" href="{/XML/CSS_URL}main.css" /-->
                <SCRIPT LANGUAGE="JavaScript" SRC="{XML/ADMIN_JS_URL}common.js"></SCRIPT>
            </head>
            <body>
                <table>
                    <tr>
                        <td>
                                <xsl:if test="/XML/ROOT_LVL_CAEGORY/COUNT&gt;0">
                                    <table>
                                        <tr>
                                            <td>
                                            Your Selection :
                                                <xsl:value-of select="/XML/BREAD_CRUMB" disable-output-escaping="yes"/>
                                            </td>
                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <td>
                                                <select id='categoryOptionsLevel_0' size='4' onchange="javascript:category_level('categoryOptionsLevel_0','{/XML/DIV_ID}','{/XML/AJAX_LOADER_ID}');">
                                                    <option value="">---------------- Select Category ----------------</option>
                                                    <xsl:for-each select="/XML/ROOT_LVL_CAEGORY/ROOT_LVL_DATA">
                                                        <xsl:choose>
                                                            <xsl:when test="SELECTED_CATEGORY=CATEGORY_ID">
                                                                <option value="{CATEGORY_ID}" selected="selected">
                                                                    <xsl:value-of select="CATEGORY_NAME" disable-output-escaping="yes"/>
                                                                </option>
                                                            </xsl:when>
                                                            <xsl:otherwise>
                                                                <option value="{CATEGORY_ID}">
                                                                    <xsl:value-of select="CATEGORY_NAME" disable-output-escaping="yes"/>
                                                                </option>
                                                            </xsl:otherwise>
                                                        </xsl:choose>
                                                    </xsl:for-each>
                                                </select>
                                            </td>
                                            <xsl:for-each select="/XML/CATEGORY_LVL_SELECT/CATEGORY_LVL_SELECT_BOX">
                                                <td>
                                                    <xsl:value-of select="." disable-output-escaping="yes"/>
                                                </td>
                                            </xsl:for-each>
                                            <!--start code to add hidden form element-->
                                            <tr>
                                                <td colspan="{/XML/TOTAL_CAT_BOX}">
                                                    <xsl:for-each select="/XML/CATEGORY_LVL_SELECT/CATEGORY_LVL_HIDDEN_DATA">
                                                        <xsl:value-of select="." disable-output-escaping="yes"/>
                                                    </xsl:for-each>
                                                    <!-- total category tree fetched from database -->
                                                    <input type="hidden" name="catboxcnt" id="catboxcnt" value="{/XML/TOTAL_CAT_BOX}"/>
                                                    <!-- user selected category box count -->
                                                    <input type="hidden" name="selectedboxcnt" id="selectedboxcnt" value="{/XML/SELECTED_CAT_BOX}"/>
                                                    <!-- use to get current selected category id -->
                                                    <input type="hidden" name="selected_category_id" id="selected_category_id" value="{/XML/SELECTED_CAT_ID}"/>
                                                </td>
                                            </tr>
                                            <!-- start code to add hidden form element-->
                                        </tr>
                                    </table>
                                </xsl:if>
                        </td>
                        <!-- main area  END -->
                    </tr>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
