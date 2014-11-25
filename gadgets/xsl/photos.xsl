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
    <xsl:include href="../components/xsl/compare.xsl"/>
    <xsl:include href="../components/xsl/more_on.xsl"/>
    <xsl:include href="../components/xsl/similar.xsl"/>
    <xsl:include href="../components/xsl/trending_now.xsl"/>
    <xsl:include href="../components/xsl/other.xsl"/>
     <xsl:include href="model_menu_tab.xsl" />
      <xsl:include href="inc_header.xsl" />
    <xsl:include href="inc_footer.xsl" />
    <xsl:template match="/">
        <html>
            <head>
                <title>Gadget</title>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <meta name="description" content="" />
                <meta name="keywords" content="" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                                <a class="blinks" href="javascript:void(0);">&nbsp;&nbsp;Blackberry Mobiles</a> 
                                <span class="brdcrum-arr"></span> Blackberry Z3
                                <div class="clear"></div>
                            </section>

                               <section class="mobile-details mdetails-inner">
        <aside class="mobile-details-l">
        <img src="{XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/IMAGE_PATH}" />
        </aside>
        <aside class="mobile-details-r">
    
           <h1><xsl:value-of select="XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/DISPLAY_PRODUCT_NAME"/></h1>
          <p class="brand-name">Brand: <a href="{/XML/WEB_URL}{/XML/CATEGORY_PATH}/{/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/BRAND_NAME}"><xsl:value-of select="XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/BRAND_NAME"/></a></p>
             <p class="ann-rel"><span>Announced</span>: <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/ANNOUNCED_DATE" disable-output-escaping="yes"/>&nbsp;&nbsp;|&nbsp;&nbsp;<span>Released</span>:  <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/ARRIVAL_DATE" disable-output-escaping="yes"/></p>
          <p class="rsm"><i class="rs"></i> <xsl:value-of select="XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/EXSHOWROOMPRICE"/></p>
          <div class="bdr-be9"></div>
                    <div class="brand-rating brand-user">
                      <xsl:if test="/XML/AVERAGE_USER_RATING_API/COUNT &gt;0">
                        <span class="rate-ttl">User Rating</span>
                          <span class="avg_user_stars">
                               <span class="rating" style="width:{/XML/AVERAGE_USER_RATING_API/ALL_REVIEWS_AVG_RATING_PROPERTION}%"> </span>
                           </span>
                           <span class="lnkrvw">|&nbsp;&nbsp;<a href="{/XML/VARIANT_USER_REVIEW_URL}"><xsl:value-of select="/XML/AVERAGE_USER_RATING_API/COUNT" disable-output-escaping="yes"/>  Review</a></span>
                       </xsl:if>
                        <xsl:if test="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_TITLE!=''">
                         <span class="lnkrvw">|&nbsp;&nbsp;<a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" target="_new">Read Expert Review</a></span>
                       </xsl:if>

                         <div class="clear"></div>
                    </div>
                    <div class="bdr-be9"></div>
                    <!--div class="share-this">
                        <section class="share-this-in">
                            <span class="fb-r"><i class="fb-i"></i> 2k</span>
                            <span class="tw-r"><i class="tw-i"></i> 5k</span>
                            <span class="gp-r"><i class="gp-i"></i> 6k</span>
                            <div class="clear"></div>
                        </section>
                    </div-->
		   <div id="isocial" class="isocial" data-url="{/XML/CURRENT_URL}" data-title="{/XML/SEO_TITLE}"></div>
        </aside>
        <div class="clear"></div>
      </section>
                            <!-- will be used for mobile -->        
                            <div class="navheader-details">
                                <button class="navbar-toggle" data-target=".nav-details" data-toggle="collapse" type="button">
                                    <span class="sr-only"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <div class="clear"></div>
                            </div>
                            <!--  ends --> 
     
                            <xsl:call-template name="ModelMenuTab"/>	 
                            <div class="clear"></div>
            
           
                            <h2 class="hdh2">
                                <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/DISPLAY_PRODUCT_NAME" disable-output-escaping="yes" /> Photo</h2>
                            <section class="blkdatawrap blkphoto">
                                <xsl:for-each select="/XML/SLIDESHOW_MASTER/SLIDESHOW_MASTER_DATA">
                                    <xsl:choose>
                                    <xsl:when test="/XML/VARIANT_PHOTO_SLUG!=''">    
                                        <xsl:if test="SLUG=/XML/VARIANT_PHOTO_SLUG">
                                            <aside class="col-xs-12 col-sm-7 blkBigPic">
                                                <img src="{MEDIA_PATH}" />
                                            </aside>
                                        </xsl:if> 
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:if test="position() = 1">
                                            <aside class="col-xs-12 col-sm-7 blkBigPic">
                                                <img src="{MEDIA_PATH}" />
                                            </aside>
                                        </xsl:if>
                                    </xsl:otherwise>
                                    </xsl:choose>
                                </xsl:for-each>
                                <!--                                        </xsl:when>
                                <xsl:otherwise>-->
                                <aside class="col-xs-12 col-sm-5 blkthumbs">
                                    <div id="scrollbar1" class="scroll1">
                                        <div class="scrollbar">
                                            <div class="track">
                                                <div class="thumb">
                                                    <div class="end"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="viewport">
                                            <div class="overview blkphotolist col-xs-12">
                                                <xsl:for-each select="/XML/SLIDESHOW_MASTER/SLIDESHOW_MASTER_DATA">
                                                    <xsl:choose>
                                                        <xsl:when test="/XML/VARIANT_PHOTO_SLUG!=''">       
                                                            <xsl:if test="SLUG!=/XML/VARIANT_PHOTO_SLUG">
                                                                <a href="{SEO_SLIDE_URL}" class="col-xs-6 blkphotoitem">
                                                                    <img src="{MEDIA_PATH}" />
                                                                </a>
                                                            </xsl:if> 
                                                        </xsl:when>
                                                        <xsl:otherwise>
                                                            <xsl:if test="position()&gt; 1">
                                                                <a href="{SEO_SLIDE_URL}" class="col-xs-6 blkphotoitem">
                                                                    <img src="{MEDIA_PATH}" />
                                                                </a>
                                                        </xsl:if>
                                                        </xsl:otherwise>
                                                    </xsl:choose>
                                                    

                                                </xsl:for-each>
                                            </div>
                                        </div>
                                    </div>
                                </aside>
                                <!--                                        </xsl:otherwise>
                                </xsl:choose>-->                            
                            <div class="clear"></div>
                            <div class="photodesc col-xs-6">
                                <div class="phdsc-ttl">Description</div>
                                <div class="phdsc-text">
                                    <xsl:for-each select="/XML/SLIDESHOW_MASTER/SLIDESHOW_MASTER_DATA">
                                    <!-- <p>The BlackBerry Z3 is an all-touch handset with a 5-inch 540x960 display, dual-core 1.2GHz CPU, 1.5GB RAM, 5MP rear camera and 1.1MP front camera, 8GB of storage and a generous 2500mAh battery. It runs BlackBerry OS 10.</p> -->
                                    <xsl:if test="SLUG=/XML/MODEL_PHOTO_SLUG">
                                        <p><xsl:value-of select="META_DESCRIPTION" disable-output-escaping="yes"/></p>
                                    </xsl:if>
                                    </xsl:for-each>
                                </div>
                            </div>
                            <div class="photoad col-xs-6">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" />
                            </div>
                            <div class="clear"></div>
                            </section>
                            <div class="clear"></div>
                        </aside>

                        <aside class="container-right col-sm-4">
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" />
                            </div>
                            <div class="clear"></div>
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}ad300-100.jpg" />
                            </div>
                            <div class="clear"></div>
          
                            <xsl:call-template name="moreon" /> 
                           
                            <xsl:call-template name="compare"/>	
                        
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" />
                            </div>
                            <div class="clear"></div>
                            <xsl:call-template name="similar" />
                       
                            <xsl:call-template name="trendingNow" />
                         
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" />
                            </div>
                            <div class="clear"></div>
                            <xsl:call-template name="other" />
                         
                        </aside>
		 
                    </section>
                </section>
              <xsl:call-template name="footerDiv"/>
                <script src="{/XML/JS_URL}jquery-1.8.3.min.js"></script>	
                <script src="{/XML/JS_URL}gadget.js"></script>
                <script src="{/XML/JS_URL}jquery.tinyscrollbar.min.js"></script>
		<script src="{/XML/JS_URL}social.js"></script>
                <script type="text/javascript">
                    $(document).ready(function(){
                    	var topicadded = $('.scroll1');
                    	topicadded.tinyscrollbar();						   
                	$('.isocial').isocial(); 
                    });
                </script>
        
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
