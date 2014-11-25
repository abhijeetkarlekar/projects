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
    <xsl:template name="trendingNow">
        <section class="blksidebar">
            <h2 class="hdsd-blue">Trending Now</h2>
            <ul class="sidebarlisting">
                <xsl:for-each select="/XML/COMPONENTS_XML/TRENDING_NOW/TRENDING_NOW_DATA">
                    <li>
                        <i></i>
                        <a href="{LINK}">
                            <xsl:value-of select="BRAND_NAME" disable-output-escaping="yes" />
                            <xsl:text> </xsl:text>
                            <xsl:value-of select="PRODUCT_NAME" disable-output-escaping="yes" />
                        </a>
                    </li>
                </xsl:for-each>
            </ul>
            <div class="clear"></div>
        </section>
    </xsl:template>
</xsl:stylesheet>