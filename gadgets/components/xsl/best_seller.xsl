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
    <xsl:template name="bestSeller">
        <xsl:if test="/XML/COMPONENTS_XML/BEST_SELLER/COUNT &gt; 0">
            <section class="blksidebar blksimilar">
                <h2 class="hdsd-blue">Best Sellers</h2>
                <ul class="simList">
                    <xsl:for-each select="/XML/COMPONENTS_XML/BEST_SELLER/BEST_SELLER_DATA">                    
                        <li class="col-sm-6 simItem">
                            <figure>
                                <a href="{LINK}" class="imgwrp">
                                    <img src="{IMAGE_PATH}" />
                                </a>
                                <figcaption>
                                    <h3>
                                        <a href="{LINK}" class="mobttl">
                                            <xsl:value-of select="BRAND_NAME" disable-output-escaping="yes" /> 
                                            <xsl:value-of select="PRODUCT_NAME" disable-output-escaping="yes" />
                                        </a>
                                    </h3>
                                    <div class="brand-rating">
                                        <xsl:if test="EXPERT_RATING &gt;0">
                                            <span class="avg_user_stars">
                                                <span class="rating" style="width:{EXPERT_RATING}%"> </span>
                                            </span>
                                        </xsl:if>
                                        <div class="clear"></div>
                                    </div>
                                    <p class="brd-amt">
                                        <i></i>
                                        <xsl:value-of select="PRICE" disable-output-escaping="yes" />
                                    </p>
                                </figcaption>
                            </figure>
                            <div class="clear"></div>
                        </li>                    
                    </xsl:for-each>
                </ul>
                <div class="clear"></div>
            </section>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>