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
    <xsl:template name="compare">
        <xsl:if test="/XML/COMPONENTS_XML/TOP_COMPARE_SET_MASTER/COUNT &gt; 0">
            <section class="blksidebar blksidecompare">
                <h2 class="hdsd-blue">Compare <xsl:value-of select="/XML/COMPONENTS_XML/PRODUCT_NAME" disable-output-escaping="yes" /></h2>
                <ul class="sdtopcompare">
                    <xsl:for-each select="/XML/COMPONENTS_XML/TOP_COMPARE_SET_MASTER/TOP_COMPARISIONS">
                        <li>
                            <a href="{TOP_COMPARISION_URL}">
                                <h2>
                                    <xsl:value-of select="TOP_COMPARISION_NAME" disable-output-escaping="yes" />
                                </h2>
                                <aside class="col-sm-12 col-md-5">
                                    <img src="{TOP_COMPARISION_FIMG}" />
                                    <h3>
                                        <xsl:value-of select="TOP_COMPARISION_FIRSTNAME" disable-output-escaping="yes" />
                                    </h3>
                                </aside>
                                <aside class="col-sm-12 col-md-2">
                                    <span class="vs">Vs</span>
                                </aside>
                                <aside class="col-sm-12 col-md-5">
                                    <img src="{TOP_COMPARISION_SIMG}" />
                                    <h3>
                                        <xsl:value-of select="TOP_COMPARISION_SECONDNAME" disable-output-escaping="yes" />
                                    </h3>
                                </aside>
                            </a>
                            <div class="clear"></div>                            
                        </li>
                    </xsl:for-each>
                </ul>
                <div class="clear"></div>
            </section>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
