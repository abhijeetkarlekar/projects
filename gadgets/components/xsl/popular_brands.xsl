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
    <xsl:template name="popularBrands">
        
        <xsl:if test="/XML/COMPONENTS_XML/POPULAR_BRANDS/COUNT &gt; 0">
            <h2 class="hdh2 ihdr">Popular Brands</h2>
            <a href="{/XML/COMPONENTS_XML/POPULAR_BRANDS/ALL_BRANDS_URL}" class="ireadmore">View all Brands <i></i></a>
            <div class="clear"></div>
            <section class="popular-brands ipopular-brands">
                <section class="popular-brands-inner">
                    <xsl:for-each select="/XML/COMPONENTS_XML/POPULAR_BRANDS/POPULAR_BRANDS_DATA">
                    <figure class="newarr-box">
                        <a href="{LINK}">
                            <img src="{/XML/IMAGE_URL}{BRAND_IMAGE}" width="99px" height="73px" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="{LINK}"><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes" /></a>
                            </h2>
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>
                    </xsl:for-each>
<!--                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>	
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>	
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                        <a href="">
                            <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                        </a>
                        <figcaption>
                            <h2>
                                <a href="">Nokia</a>
                            </h2>
                    
                        </figcaption>
                        <div class="clear"></div>	
                    </figure>-->
                    <div class="clear"></div>	
                </section>
            </section>
        </xsl:if>
        
    </xsl:template>
</xsl:stylesheet>