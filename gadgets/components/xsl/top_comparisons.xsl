<?xml version="1.0" encoding="utf-8"?><!DOCTYPE xsl:stylesheet  [
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
    <xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
    <xsl:param name="gallery_product_id" />
    <xsl:template name="topComparison">
        
        <section class="topcaompare-slider">
            <div class="gadband">
                <h2>Top Comparisons</h2>
            </div>
            <section class="topcaompage-slider">
                <ul class="topcaompare">
                    <xsl:for-each select="/XML/COMPONENTS_XML/TOP_COMPARISION/TOP_COMPARISION_DATA">
                        <a href="{LINK}">
                            <li>
                                <h2>
                                    <xsl:value-of select="TITLE" disable-output-escaping="yes" />
                                </h2>
                                <xsl:for-each select="PRODUCTS/PRODUCT">
                                    <xsl:choose>
                                        <xsl:when test="(position() mod 2) = 0">
                                            <aside class="col-sm-12 col-md-2">
                                                <span class="vs">Vs</span>
                                            </aside>
                                            <aside class="col-sm-12 col-md-5">
                                                <img src="{IMAGE_PATH}" />
                                                <h3>
                                                    <xsl:value-of select="PRODUCT_NAME" disable-output-escaping="yes" />
                                                </h3>
                                            </aside>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <aside class="col-sm-12 col-md-5">
                                                <img src="{IMAGE_PATH}" />
                                                <h3>
                                                    <xsl:value-of select="PRODUCT_NAME" disable-output-escaping="yes" />
                                                </h3>
                                            </aside>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </xsl:for-each>
                            </li>
                        </a>
                    </xsl:for-each>			
                </ul>
                <div class="clear"></div>
            </section>	
        </section>
    </xsl:template>
</xsl:stylesheet>