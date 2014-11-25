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
    <xsl:template name="upcoming">
    	<xsl:if test="/XML/COMPONENTS_XML/UPCOMING/COUNT &gt; 0">
            <h2 class="hdh2 ihdr">upcoming mobile phones</h2>
            <a href="{/XML/SEO_WEB_URL}/{/XML/COMPONENTS_XML/CAT_PATH}/{/XML/UPCOMING_MOBILES}" class="ireadmore">More Upcoming Mobiles <i></i></a>
            <div class="clear"></div>
            <section class="iupcmgPhn">
                <section class="iupcmgPhn-inner">
                    <xsl:for-each select="/XML/COMPONENTS_XML/UPCOMING/UPCOMING_DATA">
                        <figure class="newarr-box col-xs-6">
                            <a href="{LINK}" class="imgwrp">
                                <img src="{IMAGE_PATH}" />
                            </a>
                            <figcaption>
                                <xsl:value-of select="PRODUCT_DISP_NAME" disable-output-escaping="yes" />
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>                
                    </xsl:for-each>
                    <div class="clear"></div>
                </section>
            </section>
        </xsl:if>
    
        
    </xsl:template>
</xsl:stylesheet>