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
    <xsl:template name="budget">
        <xsl:if test="/XML/COMPONENTS_XML/BUDGET/COUNT &gt; 0">
            <h2 class="hdh2 ihdr">budget mobile phones</h2>
            <a href="{/XML/SEO_WEB_URL}/{/XML/COMPONENTS_XML/CAT_PATH}/{/XML/BUDGET_MOBILES}" class="ireadmore">More Budget Phones <i></i></a>
            <div class="clear"></div>
            <section class="iupcmgPhn">
                <section class="iupcmgPhn-inner">
                    <xsl:for-each select="/XML/COMPONENTS_XML/BUDGET/BUDGET_DATA">
                        <figure class="newarr-box col-xs-6">
                            <a href="{LINK}" class="imgwrp">
                                <img src="{IMAGE_PATH}" />
                            </a>
                            <figcaption>
                                <h2>
                                    <a href="{LINK}">
                                        <xsl:value-of select="BRAND_NAME" />
                                        <xsl:text> </xsl:text>
                                        <xsl:value-of select="PRODUCT_NAME" />
                                    </a>
                                </h2>
                                <xsl:if test="EXPERT_RATING &gt;0">
                                <span class="avg_user_stars">
                                    <span class="rating" style="width:{EXPERT_RATING}%"> </span>
                                </span>
                                </xsl:if>
                                <div class="clear"></div>
                                <p class="price">
                                    <i></i> 
                                    <xsl:value-of select="PRICE" disable-output-escaping="yes" />
                                </p>
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