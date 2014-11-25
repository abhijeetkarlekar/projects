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
    <xsl:template name="UpcomingByTypes">
        <xsl:if test="/XML/COMPONENTS_XML/MORE_ON_UPCMBYTYPESGADGET/COUNT &gt; 0">
            <section class="blksidebar">
                <h2 class="hdsd-blue">Upcoming Mobiles By Type</h2>
                <ul class="sidebarlisting">
                    <xsl:for-each select="/XML/COMPONENTS_XML/MORE_ON_UPCMBYTYPESGADGET/MORE_ON_GADGET_DATA">
                        <xsl:if test="MORE_ON_GADGET_DATATITLE != ''">
                            <li>
                                <i></i>
                                <a href="{MORE_ON_GADGET_DATALINK}">
                                    <xsl:value-of select="MORE_ON_GADGET_DATATITLE" disable-output-escaping="yes" />
                                </a>
                            </li>
                        </xsl:if>
                    </xsl:for-each>                
                </ul>
                <div class="clear"></div>
            </section>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>