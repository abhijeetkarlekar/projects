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
    <xsl:include href="../components/xsl/user_review.xsl"/>
    <xsl:include href="inc_meta_header.xsl" />
<xsl:include href="inc_header.xsl" />

<xsl:include href="inc_footer.xsl" />
<xsl:include href="inc_breadcrumb.xsl" />
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#"   xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <xsl:call-template name="headMetaDiv"/>
  </head>
  <body>
   <!-- Header inner -->
      <xsl:call-template name="headDiv"/>
    <!-- Header inner End-->       
        <section class="inner-container">
           <section class="container">
         <aside class="inner-container-left col-sm-9 ">
               <section class="h-breadcrumb">
                                <xsl:call-template name="breadcrumb"/>
                <div class="clear"></div>
                         </section>
              <h1 class="h118">Popular Brands</h1>
                                  
                                  <section class="popular-brands">
                                  <section class="popular-brands-inner">
                                 
                                 <xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
                                  <figure class="newarr-box">
                                  <a href="{BRAND_URL}">
                                    <xsl:choose>
                                    <xsl:when test="IMAGE_PATH!=''">
                                        <img src="{IMAGE_PATH}" width="99" height="73"/>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />  
                                    </xsl:otherwise>
                                  </xsl:choose>
                                  </a>
                                  <figcaption>
                                  <h2><a href="{BRAND_URL}"><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/></a></h2>
                                  </figcaption>
                                  <div class="clear"></div>      
                                  </figure>
                                </xsl:for-each>      

                                  <div class="clear"></div>   
                                  </section>

                                  </section>
				
				<xsl:call-template name="userReview" />

                                <!-- h2 class="h218 fl">user reviews</h2><a class="van" href="">Read all user reviews<i class="more-ar"></i></a>
                                <section class="blkdatawrap">
                                
                        <figure class="bgr-uvw-item">
                            <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}popular-brands1.jpg" alt=""/></a>
                            <figcaption class="col-xs-11 bgr-vw-desc">
                                <a href="javascript:void(0)" class="hdttl">Blackberry Z3 review: Aworthy mid-range all rounder in Price, specifications and features</a>
                                <div class="brand-rating">
                                    <span class="rate-ttl">Rating</span>
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="byline">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;By Sambit Satpathy on May 14, 2014 at 4:01 PM</p>
                                <div class="desctxt">When you think of BlackBerry, more often than not, you imagine businessmen and women in formal attire furiously.tapping on the QWERTY keypads, either sending emails.</div>
                            </figcaption>
                            <div class="clear"></div>
                        </figure>
                        <figure class="bgr-uvw-item">
                            <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}popular-brands1.jpg" alt=""/></a>
                            <figcaption class="col-xs-11 bgr-vw-desc">
                                <a href="javascript:void(0)" class="hdttl">Blackberry Z3 review: Aworthy mid-range all rounder in Price, specifications and features</a>
                                <div class="brand-rating">
                                    <span class="rate-ttl">Rating</span>
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="byline">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;By Sambit Satpathy on May 14, 2014 at 4:01 PM</p>
                                <div class="desctxt">When you think of BlackBerry, more often than not, you imagine businessmen and women in formal attire furiously.tapping on the QWERTY keypads, either sending emails.</div>
                            </figcaption>
                            <div class="clear"></div>
                        </figure>
                        <figure class="bgr-uvw-item">
                            <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}popular-brands1.jpg" alt=""/></a>
                            <figcaption class="col-xs-11 bgr-vw-desc">
                                <a href="javascript:void(0)" class="hdttl">Blackberry Z3 review: Aworthy mid-range all rounder in Price, specifications and features</a>
                                <div class="brand-rating">
                                    <span class="rate-ttl">Rating</span>
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="byline">&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;By Sambit Satpathy on May 14, 2014 at 4:01 PM</p>
                                <div class="desctxt">When you think of BlackBerry, more often than not, you imagine businessmen and women in formal attire furiously.tapping on the QWERTY keypads, either sending emails.</div>
                            </figcaption>
                            <div class="clear"></div>
                        </figure>
                    
                                        </section -->
                                
                                <div class="clear"></div>       
                                      <!--   <nav class="gadget-pagination">
                                          <ul class="pagination">
                                                <li><a href="#"><i class="pags-fl"></i>First</a></li>
                                                <li class="active"><a href="#">1</a></li>
                                                <li><a href="#">2</a></li>
                                                <li><a href="#">3</a></li>
                                                <li><a href="#">4</a></li>
                                                <li><a href="#">5</a></li>
                                                <li><a href="#">last<i class="pags-fr"></i></a></li>
                                          </ul>
                                        </nav> -->
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
