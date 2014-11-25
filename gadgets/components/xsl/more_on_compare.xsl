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
    <xsl:template name="featuredMobilePhones">                
        <section class="gadget-slider">
            <div class="gadband">
                <h2>featured Mobile Phones</h2>
            </div>
            <section class="searchpage-slider">
                <ul class="searchpage">                    
                    <xsl:for-each select="/XML/COMPONENTS_XML/FEATURED_MOBILE_PHONES/FEATURED_MOBILE_PHONES_DATA">
                    <li>
                        <img src="{IMAGE_PATH}" />
                        <div class="brand"><xsl:value-of select="BRAND_NAME" /> <xsl:value-of select="PRODUCT_NAME" /></div>
                        <div class="rs-info">
                            <i class="rs"></i> <xsl:value-of select="VARIANT_VALUE" /></div>
                        <div class="addcomp">
                            <input type="checkbox" name="" value="" class="addtocom" /> 
                            <a href="">Add to Compare</a>
                        </div>
                    </li>
                    </xsl:for-each>
                </ul>
                <div class="clear"></div>
            </section>
            <div class="clear"></div>	
        </section>
    </xsl:template>
</xsl:stylesheet>