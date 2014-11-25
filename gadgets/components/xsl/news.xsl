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
    <xsl:template name="news">
        <xsl:if test="/XML/COMPONENTS_XML/NEWS_MASTER/COUNT &gt; 0">
            <section class="blksidebar">
                <h2 class="hdh2">
                    <xsl:value-of select="/XML/COMPONENTS_XML/PRODUCT_NAME" disable-output-escaping="yes" /> News</h2>
                <div class="blksdbar-news">
                    <xsl:for-each select="/XML/COMPONENTS_XML/NEWS_MASTER/NEWS_MASTER_DATA">
                        <figure class="blksdbar-news-item">
                            <a class="imgwrp col-xs-5" href="{SEO_URL}">
                                <img src="{IMAGE_PATH}" width="110" height="82"/>
                            </a>
                            <figcaption class="col-xs-7">
                                <a href="javascript:void(0)" class="catname">
                                    <xsl:for-each select="CATEGORIES/CATEGORY">
                                        <xsl:value-of select="." disable-output-escaping="yes" />
                                    </xsl:for-each>
                                </a>
                                <a href="{SEO_URL}" class="ttl-link">
                                    <xsl:value-of select="TITLE" disable-output-escaping="yes" />
                                </a>
                            </figcaption>
                            <div class="clear"></div>
                        </figure>
                    </xsl:for-each>
                </div>
                <a href="{/XML/COMPONENTS_XML/NEWS_MASTER/VIEW_ALL_NEWS}" class="sdbar-viewall">View all News <i></i></a>
                <div class="clear"></div>
            </section>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
