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
<xsl:include href="../components/xsl/news.xsl"/>
<xsl:include href="../components/xsl/more_on.xsl"/>
<xsl:include href="../components/xsl/trending_now.xsl"/>
<xsl:include href="../components/xsl/other.xsl"/>
<xsl:include href="../components/xsl/similar.xsl"/>
<xsl:include href="inc_header.xsl" />
<xsl:include href="inc_footer.xsl" />
<xsl:include href="inc_breadcrumb.xsl" />
<xsl:include href="model_menu_tab.xsl" />
<xsl:include href="write_review.xsl" />
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
                <a class="blinks" href="javascript:void(0);">&nbsp;&nbsp;<xsl:value-of select="/XML/MODEL_BRAND_NAME" /> Mobiles</a> <span class="brdcrum-arr"></span> <xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" />
                <div class="clear"></div>
       </section>

      <section class="mobile-details mdetails-inner">
        <aside class="mobile-details-l">
        <img src="{/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/IMAGE_PATH}" />
        </aside>
        <aside class="mobile-details-r">
    
           <h1><xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" /></h1>
          <p class="brand-name">Brand: <a href=""><xsl:value-of select="/XML/MODEL_BRAND_NAME" /></a></p>
          <p class="ann-rel"><span>Announced</span>: <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/ANNOUNCED_DATE" disable-output-escaping="yes"/>&nbsp;&nbsp;|&nbsp;&nbsp;<span>Released</span>: <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/ARRIVAL_DATE" disable-output-escaping="yes"/></p>
          <p class="rsm"><i class="rs"></i> <xsl:value-of select="/XML/PRODUCT_INFO_DETAIL/PRODUCT_INFO_DETAIL_DATA/LOW_PRICE"/></p>
          <div class="bdr-be9"></div>
                    
                    <div class="brand-rating brand-user">
                      <xsl:if test="/XML/AVERAGE_USER_RATING_API/COUNT &gt;0">
                        <span class="rate-ttl">User Rating</span>
                          <span class="avg_user_stars">
                               <span class="rating" style="width:{/XML/AVERAGE_USER_RATING_API/ALL_REVIEWS_AVG_RATING_PROPERTION}%"> </span>
                           </span>
                           <span class="lnkrvw">|&nbsp;&nbsp;<a href="javascript:void(0)"><xsl:value-of select="/XML/AVERAGE_USER_RATING_API/COUNT" disable-output-escaping="yes"/>  Review</a></span>
                       </xsl:if>
                        <xsl:if test="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_TITLE!=''">
                         <span class="lnkrvw">|&nbsp;&nbsp;<a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}">Read Expert Review</a></span>
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
            
           
            <h2 class="hdh2"><xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" /> Review</h2>
				<section class="blkdatawrap blkdatainr">
						<div class="clear"></div>
                   <xsl:if test="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_TITLE!=''">
                <section class="col-xs-12 blkBgrReview">
                  <h3 class="vw-hdr">BGR REVIEW</h3>
                  <figure class="bgr-vw-item">
                        <a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_IMAGE}" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                            <a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" class="hdttl"><xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_TITLE" disable-output-escaping="yes"/></a>
                            <div class="brand-rating">
                                <span class="rate-ttl">Rating</span>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING}%"> </span>
                                 </span>
                                 <div class="clear"></div>
                            </div>
                            <p class="byline">By <xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_AUTHOR" disable-output-escaping="yes"/> on <xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_PUB_DATE" disable-output-escaping="yes"/></p>
                            <div class="desctxt"><xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_ABSTRACT" disable-output-escaping="yes"/>.</div>
                            <a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" class="readmore">Read complete review <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                </section>
                </xsl:if>
                <div class="clear"></div>
				
			 <section class="col-xs-12 blkUsrReview">
                    <div class="vw-hdr1 hdrvw">
                    	<h3 class="vw-hd">USER REVIEWS</h3>
                        <div class="usr-writeRvw">
                            <span class="own-mbl-txt">Own this mobile?</span>
                            <a href="javascript:void(0)" class="btn-vw-write">Write a Review</a>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="blk-uvw-list">
                       <xsl:if test="/XML/MODEL_USER_REVIEW/LATEST_USER_REVIEW_MASTER/COUNT &gt;0">
						<xsl:for-each select="/XML/MODEL_USER_REVIEW/LATEST_USER_REVIEW_MASTER/LATEST_USER_REVIEW_MASTER_DATA">
						<figure class="bgr-uvw-item">
                            <a href="{USER_REVIEW_URL}" class="col-xs-1 bgr-vw-img"><img src="{IMAGE_PATH}" alt=""/></a>
                            <figcaption class="col-xs-11 bgr-vw-desc">
                               <h2 class="hdh2"> <a href="{USER_REVIEW_URL}" class="hdttl">
									<xsl:value-of select="TITLE" disable-output-escaping="yes"/>
								</a></h2>
                                <div class="brand-rating">
                                    <span class="rate-ttl">Rating</span>
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:{AVERAGE_USER_RATING_API/ALL_REVIEWS_AVG_RATING_PROPERTION}%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                               <!--  <p class="byline"> -->&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;By <xsl:value-of select="USER_NAME" disable-output-escaping="yes" /> on <xsl:value-of select="CREATE_DATE" disable-output-escaping="yes" /><!-- </p> -->
                                <div class="desctxt">
										<xsl:for-each select="USER_REVIEW_COMMENT_ANSWER_MASTER/USER_REVIEW_COMMENT_ANSWER_MASTER_DATA">
														<xsl:if test="QUENAME = 'Other Comments'">
																<div  itemprop="description" class="mt10">
																		<p><xsl:value-of select="ANSWER" disable-output-escaping="yes"/></p>
																		
																	</div>
															</xsl:if>
                                                        </xsl:for-each>
								</div>
                            </figcaption>
                            <div class="clear"></div>
                        </figure>
						</xsl:for-each>
						</xsl:if>
						<!-- 
                        <figure class="bgr-uvw-item">
                            <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="images/reviewImgthumb.jpg" alt=""/></a>
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
                            <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="images/reviewImgthumb.jpg" alt=""/></a>
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
                            <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="images/reviewImgthumb.jpg" alt=""/></a>
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
                            <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="images/reviewImgthumb.jpg" alt=""/></a>
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
                        </figure> -->
                    </div>
                    <div class="clear"></div>
                </section>
				<div class="clear"></div>
				</section>
            
       
     </aside>

        <aside class="container-right col-sm-4">
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>
            <div class="ads300"><img src="{/XML/IMAGE_URL}ad300-100.jpg" /></div>
            <div class="clear"></div>
            <xsl:call-template name="news"/>
  
           <xsl:call-template name="moreon" />
           <xsl:call-template name="compare"/>
   
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>
              <xsl:call-template name="similar" />
              <xsl:call-template name="trendingNow" />

         
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>

             <xsl:call-template name="other" />

       
         </aside>
     
    </section>
  </section>
  <xsl:call-template name="WriteReview"/>
    <xsl:call-template name="footerDiv"/>
               <script src="{/XML/JS_URL}jquery-1.8.3.min.js"></script> 
    <script src="{/XML/JS_URL}gadget.js"></script>
      <script src="{/XML/JS_URL}social.js"></script>
<script type="text/javascript">
                $(document).ready(function(){
                        $('.isocial').isocial();
                });
        </script>

        </body>
</html>
</xsl:template>
</xsl:stylesheet>
