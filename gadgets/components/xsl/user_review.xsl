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
    <xsl:template name="userReview">
        <xsl:if test="/XML/COMPONENTS_XML/LATEST_USER_REVIEW_MASTER/COUNT &gt; 0">
            <h2 class="h218 fl">user reviews</h2>
            <a class="van" href="{/XML/SEO_WEB_URL}/{/XML/COMPONENTS_XML/CAT_PATH}/{/XML/USER_REVIEWS}">Read all user reviews<i class="more-ar"></i></a>
            <section class="blkdatawrap">
                <xsl:for-each select="/XML/COMPONENTS_XML/LATEST_USER_REVIEW_MASTER/LATEST_USER_REVIEW_MASTER_DATA">
                    <figure class="bgr-uvw-item">
                        <a href="{USER_REVIEW_URL}" class="col-xs-1 bgr-vw-img">
                            <img src="{IMAGE_PATH}" alt=""/>
                        </a>
                        <figcaption class="col-xs-11 bgr-vw-desc">
                            <h2 class="hdh2">
                                <a href="{USER_REVIEW_URL}" class="hdttl">
                                    <xsl:value-of select="TITLE" disable-output-escaping="yes" />
                                </a>
                            </h2>
                            
                            <div class="brand-rating">
                                <span class="rate-ttl">Rating</span>
                                <span class="avg_user_stars">
                                    <span class="rating" style="width:{AVERAGE_USER_RATING_API/ALL_REVIEWS_AVG_RATING_PROPERTION}%"> </span>
                                </span>
                                <div class="clear"></div>
                            </div>
                            
                            <p class="byline">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;By <xsl:value-of select="USER_NAME" disable-output-escaping="yes" /> on <xsl:value-of select="CREATE_DATE" disable-output-escaping="yes" /></p>
                            <div class="desctxt">
                                <xsl:for-each select="USER_REVIEW_COMMENT_ANSWER_MASTER/USER_REVIEW_COMMENT_ANSWER_MASTER_DATA">
                                    <xsl:if test="QUENAME = 'Other Comments'">
                                        <div  itemprop="description">
                                            <p>
                                                <xsl:value-of select="ANSWER" disable-output-escaping="yes"/>
                                            </p>
                                        </div>
                                    </xsl:if>
                                </xsl:for-each>
                            </div>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                </xsl:for-each>
            </section>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>