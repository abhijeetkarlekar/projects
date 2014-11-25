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
    <xsl:template name="homeFeatured">
        <xsl:if test="/XML/COMPONENTS_XML/FEATURED_MOBILE_PHONES/COUNT &gt; 0">
            <section class="hpsliderwrap">
                <section class="hpslider col-xs-12">
                    <xsl:for-each select="/XML/COMPONENTS_XML/FEATURED_MOBILE_PHONES/FEATURED_MOBILE_PHONES_DATA">
                        <figure class="hpitem col-xs-12">
                            <a href="{SPEC_LINK}" class="imgwrp col-xs-12 col-sm-5">
                                <img src="{IMAGE_PATH}"/>
                            </a>
                            <figcaption class="hpslid-desc col-xs-12 col-sm-7">
                                <h2 class="hdh2">
                                    <xsl:value-of select="BRAND_NAME" />
                                    <xsl:text> </xsl:text>
                                    <xsl:value-of select="PRODUCT_NAME" />
                                </h2>
                                <xsl:if test="EXPERT_RATING &gt;0">
                                <span class="avg_user_stars">
                                    <span class="rating" style="width:{EXPERT_RATING}%"> </span>
                                </span>
                                </xsl:if>                              
                                <xsl:if test="FEATURE_SPECS_COUNT &gt; 0">
                                    <ul class="hpslid-features">
                                        <li>
                                            <i></i>
                                            <xsl:value-of select="OS" disable-output-escaping="yes" />
                                        </li>
                                        <li>
                                            <i></i>
                                            <xsl:value-of select="DISPLAY" disable-output-escaping="yes" />
                                        </li>
                                        <li>
                                            <i></i>
                                            <xsl:value-of select="PROCESSOR" disable-output-escaping="yes" />
                                        </li>
                                        <li>
                                            <i></i>
                                            <xsl:value-of select="CAMERA" disable-output-escaping="yes" />
                                        </li>
                                    </ul>
                                    <a href="{SPEC_LINK}" class="btnhpspec">View specs</a>
                                </xsl:if>
                            </figcaption>
                            <div class="clear"></div>
                        </figure>
                    </xsl:for-each>
                    <!--    <figure class="hpitem col-xs-12">
                        <a href="javascript:void(0)" class="imgwrp col-xs-12 col-sm-5"><img src="{/XML/IMAGE_URL}hpslider.jpg"/></a>
                        <figcaption class="hpslid-desc col-xs-12 col-sm-7">
                            <h2 class="hdh2">2 Sony Xperia T3</h2>
                            <span class="avg_user_stars">
                                 <span class="rating" style="width:80%"> </span>
                             </span>
                             <ul class="hpslid-features">
                                <li><i></i>Android v4.4.2 (KitKat)</li>
                                <li><i></i>5.5 Inch 720x1280 px display</li>
                                <li><i></i>Quad Core 1600 MHz processor</li>
                                <li><i></i>13 MP Primary Camera, 5 MP Secondary</li>
                            </ul>
                            <a href="javascript:void(0)" class="btnhpspec">View specs</a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                    <figure class="hpitem col-xs-12">
                        <a href="javascript:void(0)" class="imgwrp col-xs-12 col-sm-5"><img src="{/XML/IMAGE_URL}hpslider.jpg"/></a>
                        <figcaption class="hpslid-desc col-xs-12 col-sm-7">
                            <h2 class="hdh2">3 Sony Xperia T3</h2>
                            <span class="avg_user_stars">
                                 <span class="rating" style="width:80%"> </span>
                             </span>
                             <ul class="hpslid-features">
                                <li><i></i>Android v4.4.2 (KitKat)</li>
                                <li><i></i>5.5 Inch 720x1280 px display</li>
                                <li><i></i>Quad Core 1600 MHz processor</li>
                                <li><i></i>13 MP Primary Camera, 5 MP Secondary</li>
                            </ul>
                            <a href="javascript:void(0)" class="btnhpspec">View specs</a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                    <figure class="hpitem col-xs-12">
                        <a href="javascript:void(0)" class="imgwrp col-xs-12 col-sm-5"><img src="{/XML/IMAGE_URL}hpslider.jpg"/></a>
                        <figcaption class="hpslid-desc col-xs-12 col-sm-7">
                            <h2 class="hdh2">3 Sony Xperia T3</h2>
                            <span class="avg_user_stars">
                                 <span class="rating" style="width:80%"> </span>
                             </span>
                             <ul class="hpslid-features">
                                <li><i></i>Android v4.4.2 (KitKat)</li>
                                <li><i></i>5.5 Inch 720x1280 px display</li>
                                <li><i></i>Quad Core 1600 MHz processor</li>
                                <li><i></i>13 MP Primary Camera, 5 MP Secondary</li>
                            </ul>
                            <a href="javascript:void(0)" class="btnhpspec">View specs</a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                    <figure class="hpitem col-xs-12">
                        <a href="javascript:void(0)" class="imgwrp col-xs-12 col-sm-5"><img src="{/XML/IMAGE_URL}hpslider.jpg"/></a>
                        <figcaption class="hpslid-desc col-xs-12 col-sm-7">
                            <h2 class="hdh2">3 Sony Xperia T3</h2>
                            <span class="avg_user_stars">
                                 <span class="rating" style="width:80%"> </span>
                             </span>
                             <ul class="hpslid-features">
                                <li><i></i>Android v4.4.2 (KitKat)</li>
                                <li><i></i>5.5 Inch 720x1280 px display</li>
                                <li><i></i>Quad Core 1600 MHz processor</li>
                                <li><i></i>13 MP Primary Camera, 5 MP Secondary</li>
                            </ul>
                            <a href="javascript:void(0)" class="btnhpspec">View specs</a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure> -->
                </section>
            </section>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
