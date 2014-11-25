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
    <xsl:include href="../components/xsl/best_seller.xsl"/>
    <xsl:include href="../components/xsl/featured_compare.xsl"/>
    <xsl:include href="inc_header.xsl" />
    <xsl:include href="inc_footer.xsl" />
    <xsl:include href="inc_breadcrumb.xsl" />
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#"   xmlns:fb="http://www.facebook.com/2008/fbml">
            <head>
                <title>Gadget</title>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <meta name="description" content="" />
                <meta name="keywords" content="" />
                <link rel="stylesheet" href="{/XML/CSS_URL}gadget.css" />
            </head>
            <body>
                <!-- Header inner -->
                <xsl:call-template name="headDiv"/>
                <!-- Header inner End-->      
                <section class="inner-container">
                    <section class="container">
                        <aside class="inner-container-left col-sm-9 ">
                            <section class="h-breadcrumb">
                                <a class="home" href="javascript:void(0);"></a>
                                <span class="brdcrum-arr"></span> Top Mobiles
                                <div class="clear"></div>
                            </section>
                            <h1 class="h118">Top Mobiles</h1>
                            <section class="new-arrivals">
                                <section class="new-arrivals-inner">
                                    <xsl:for-each select="/XML/TOP_PRODUCT_MASTER/TOP_PRODUCT_MASTER_DATA">
                                        <figure class="newarr-box col-xs-12 col-md-3">
                                            <a href="{SEO_URL}" class="imgwrap-big">
                                                <img src="{IMAGE_PATH}" />
                                            </a>
                                            <figcaption>
                                                <h2>
                                                    <a href="{SEO_URL}">
                                                        <xsl:value-of select="PRODUCT_DISPLAY_NAME" disable-output-escaping="yes"/>
                                                    </a>
                                                </h2>
                                                <div class="rs-info">
                                                    <i class="rs"></i> 
                                                    <xsl:value-of select="VARIANT_PRICE" disable-output-escaping="yes"/>
                                                </div>
                                                <a href="{SEO_URL}">View Specs</a>
                                            </figcaption>
                                            <div class="clear"></div>  
                                        </figure>
                                    </xsl:for-each>
                                    <!-- <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>  
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>  
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>  
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>
                                    <figure class="newarr-box col-xs-12 col-md-3">
                                       <a href="" class="imgwrap-big">
                                       <img src="{/XML/IMAGE_URL}new-arrivals.jpg" />
                                     </a>
                                     <figcaption>
                                       <h2><a href="">Nokia Lumia 930</a></h2>
                                       <div class="rs-info"><i class="rs"></i> 22,990</div>
                                       <a href="">View Specs</a>
                                     </figcaption>
                                      <div class="clear"></div>  
                                    </figure>    -->          
                                    <div class="clear"></div> 
                                </section>
                            </section>
                            <div class="clear"></div> 
                            <nav class="gadget-pagination">
                                <ul class="pagination">
                                    <li>
                                        <a href="#">
                                            <i class="pags-fl"></i>First</a>
                                    </li>
                                    <li class="active">
                                        <a href="#">1</a>
                                    </li>
                                    <li>
                                        <a href="#">2</a>
                                    </li>
                                    <li>
                                        <a href="#">3</a>
                                    </li>
                                    <li>
                                        <a href="#">4</a>
                                    </li>
                                    <li>
                                        <a href="#">5</a>
                                    </li>
                                    <li>
                                        <a href="#">last<i class="pags-fr"></i></a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="clear"></div>      
                        </aside>
                        <aside class="container-right col-sm-4">
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
                            </div>
                            <div class="clear"></div>
                            <xsl:call-template name="bestSeller"/>
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
                            </div>
                            <div class="clear"></div>
                            <xsl:call-template name="featuredCompare"/>
                        </aside>
                    </section>
                </section>
                <xsl:call-template name="footerDiv"/>
                <script src="{/XML/JS_URL}jquery-1.8.3.min.js"></script> 
                <script src="{/XML/JS_URL}gadget.js"></script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>