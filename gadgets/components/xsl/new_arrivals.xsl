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
    <xsl:template name="newArrivals">
        
        <xsl:if test="/XML/COMPONENTS_XML/NEW_ARRIVAL/COUNT &gt; 0">
            <h2 class="hdh2 ihdr">new mobiles in india</h2>
            <a href="{/XML/SEO_WEB_URL}/{/XML/COMPONENTS_XML/CAT_PATH}/{/XML/NEW_ARRIVALS}" class="ireadmore">More New Mobiles <i></i></a>
            <div class="clear"></div>
            <section class="upcoming-mp" >
                <div class="upcmg-lst">
                    <xsl:for-each select="/XML/COMPONENTS_XML/NEW_ARRIVAL/NEW_ARRIVAL_DATA">
                        <a href="{LINK}" class="upcmg-itm">
                            <figure>
                                <div class="imgwrp">
                                    <img src="{IMAGE_PATH}" />
                                </div>
                                <figcaption>
                                    <xsl:value-of select="BRAND_NAME" disable-output-escaping="yes" />
                                    <xsl:text> </xsl:text>
                                    <xsl:value-of select="PRODUCT_NAME" disable-output-escaping="yes" />
                                </figcaption>
                            </figure>
                        </a>
                    </xsl:for-each>
                   
                    <div class="clear"></div>
                </div>
            </section>
        </xsl:if>
        
    </xsl:template>
</xsl:stylesheet>