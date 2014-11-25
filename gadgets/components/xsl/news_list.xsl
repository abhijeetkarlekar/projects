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
    <xsl:template name="newsList">
        <xsl:if test="/XML/COMPONENTS_XML/NEWS_MASTER/COUNT &gt;0">
        <aside class="inner-container-left col-sm-9 ">
                <h2 class="h218 fl">NEWS</h2>
                <a href="{/XML/COMPONENTS_XML/NEWS_MASTER/ALL_NEWS}" class="van">View all News <i class="more-ar"></i></a>
                <section class="col-xs-12 blkBgrReview compare-newspages">
                    <xsl:for-each select="/XML/COMPONENTS_XML/NEWS_MASTER/NEWS_MASTER_DATA">
                    <figure class="bgr-vw-item listing-4-8" >
                        <a href="{SEO_URL}" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{IMAGE_PATH}" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                           <span class="mob-info"><xsl:for-each select="CATEGORIES/CATEGORY">
                                    <xsl:value-of select="." disable-output-escaping="yes" />
                                </xsl:for-each></span>
                           <div class="clear"></div>
                            <a href="{SEO_URL}" class="hdttl"><xsl:value-of select="TITLE" disable-output-escaping="yes" /></a>
                            <p class="byline">By Sambit Satpathy on <xsl:value-of select="DISP_DATE" disable-output-escaping="yes" /></p>
                            <div class="desctxt"><xsl:value-of select="DESCRIPTION" disable-output-escaping="yes" /></div>
                            <a href="{SEO_URL}" class="readmore">Read complete review <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                     </xsl:for-each>
                   <!--  <figure class="bgr-vw-item listing-4-8" >
                        <a href="javascript:void(0)" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgBig.jpg" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                           <span class="mob-info">Business</span>
                           <div class="clear"></div>
                            <a href="javascript:void(0)" class="hdttl">Blackberry Z3 review: Aworthy mid-range all rounder in Price, specifications and features</a>
                            <p class="byline">By Sambit Satpathy on May 14, 2014 at 4:01 PM</p>
                            <div class="desctxt">When you think of BlackBerry, more often than not, you imagine businessmen and women in formal attire furiously.tapping on the QWERTY keypads, either sending emails.</div>
                            <a href="javascript:void(0)" class="readmore">Read complete review <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                    <figure class="bgr-vw-item listing-4-8" >
                        <a href="javascript:void(0)" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgBig.jpg" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                           <span class="mob-info">Business</span>
                           <div class="clear"></div>
                            <a href="javascript:void(0)" class="hdttl">Blackberry Z3 review: Aworthy mid-range all rounder in Price, specifications and features</a>
                            <p class="byline">By Sambit Satpathy on May 14, 2014 at 4:01 PM</p>
                            <div class="desctxt">When you think of BlackBerry, more often than not, you imagine businessmen and women in formal attire furiously.tapping on the QWERTY keypads, either sending emails.</div>
                            <a href="javascript:void(0)" class="readmore">Read complete review <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                    <figure class="bgr-vw-item listing-4-8" >
                        <a href="javascript:void(0)" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgBig.jpg" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                           <span class="mob-info">Business</span>
                           <div class="clear"></div>
                            <a href="javascript:void(0)" class="hdttl">Blackberry Z3 review: Aworthy mid-range all rounder in Price, specifications and features</a>
                            <p class="byline">By Sambit Satpathy on May 14, 2014 at 4:01 PM</p>
                            <div class="desctxt">When you think of BlackBerry, more often than not, you imagine businessmen and women in formal attire furiously.tapping on the QWERTY keypads, either sending emails.</div>
                            <a href="javascript:void(0)" class="readmore">Read complete review <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                    <figure class="bgr-vw-item listing-4-8" >
                        <a href="javascript:void(0)" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgBig.jpg" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                           <span class="mob-info">Business</span>
                           <div class="clear"></div>
                            <a href="javascript:void(0)" class="hdttl">Blackberry Z3 review: Aworthy mid-range all rounder in Price, specifications and features</a>
                            <p class="byline">By Sambit Satpathy on May 14, 2014 at 4:01 PM</p>
                            <div class="desctxt">When you think of BlackBerry, more often than not, you imagine businessmen and women in formal attire furiously.tapping on the QWERTY keypads, either sending emails.</div>
                            <a href="javascript:void(0)" class="readmore">Read complete review <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>     -->               
                </section>
            </aside>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>