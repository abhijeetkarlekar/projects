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
	<xsl:include href="../components/xsl/user_review.xsl"/>
<xsl:include href="inc_header.xsl" />
<xsl:include href="inc_footer.xsl" />
<xsl:include href="inc_breadcrumb.xsl" />
<xsl:include href="write_review.xsl" />
<xsl:include href="model_menu_tab.xsl" />
 <xsl:include href="disqus.xsl" />
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#"   xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Gadget</title>
    <link rel="canonical" href="{/XML/CURRENT_URL}" />
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

      <section class="mobile-details">
       <aside class="mobile-details-l">
                                    <div class="mPic-big col-xs-12">
                                        <img src="{/XML/GALLERY/GALLERY_MAIN_IMAGE_DETAILS}" width="159" height="266" />
                                    </div>
                                    <xsl:if test="/XML/GALLERY/TOTAL &gt; 0">
                                        <div class="mPic-thumb col-xs-12">
                                            <ul>
                                                <xsl:for-each select="/XML/GALLERY/GALLERY_DETAILS">
                                                    <li>
                                                        <a href="javascript:void(0)" data-url="{VIDEO_IMG_PATH}">
                                                            <img src="{THUMB_VIDEO_IMG_PATH}" />
                                                        </a>
                                                    </li>
                                                </xsl:for-each>                                            
                                            </ul>
                                        </div>
                                    </xsl:if>
                                </aside>
        <aside class="mobile-details-r">
    
           <h1><xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" /></h1>
          <p class="brand-name">Brand: <a href="{/XML/MODELBRAND_SEO_URL}"><xsl:value-of select="/XML/MODEL_BRAND_NAME" /></a></p>
          <p class="ann-rel"><span>Announced</span>: <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/ANNOUNCED_DATE" disable-output-escaping="yes"/>&nbsp;&nbsp;|&nbsp;&nbsp;<span>Released</span>:  <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/ARRIVAL_DATE" disable-output-escaping="yes"/></p>
          <p class="rsm"><i class="rs"></i> <xsl:value-of select="/XML/PRODUCT_INFO_DETAIL/PRODUCT_INFO_DETAIL_DATA/LOW_PRICE"/></p>
          <div class="bdr-be9"></div>
                    <xsl:if test="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_TITLE!=''">
                    <div class="brand-rating">
                      <span class="rate-ttl">BGR Rating</span>
                        <span class="avg_user_stars">
                             <span class="rating" style="width:{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING}%"> </span>
                         </span>
                         <span class="lnkrvw">|&nbsp;&nbsp;<a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" target="_new">Read Expert Review</a></span>
                         <div class="clear"></div> 
                    </div>
                    </xsl:if> 
                    <xsl:if test="/XML/AVERAGE_USER_RATING_API/COUNT &gt;0">
                    <div class="brand-rating brand-user">
                      <span class="rate-ttl">User Rating</span>
                        <span class="avg_user_stars">
                             <span class="rating" style="width:{/XML/AVERAGE_USER_RATING_API/ALL_REVIEWS_AVG_RATING_PROPERTION}%"> </span>
                         </span>
                         <span class="lnkrvw">|&nbsp;&nbsp;<a href="{/XML/MODEL_USER_REVIEW_URL}"><xsl:value-of select="/XML/AVERAGE_USER_RATING_API/COUNT" disable-output-escaping="yes"/>  Review</a></span>
                         <div class="clear"></div>
                    </div>
                    <xsl:if test="/XML/MODEL_USER_REVIEW/LATEST_USER_REVIEW_MASTER/COUNT &gt;0">
                      <xsl:for-each select="/XML/MODEL_USER_REVIEW/LATEST_USER_REVIEW_MASTER/LATEST_USER_REVIEW_MASTER_DATA">
                        <p class="brand-cmnt">
                          <a href="{USER_REVIEW_URL}"><xsl:value-of select="TITLE" disable-output-escaping="yes"/></a>
                          <xsl:value-of select="CREATE_DATE" disable-output-escaping="yes"/></p>
                      </xsl:for-each>
                    </xsl:if>
                    </xsl:if>

                    

                    <div class="bdr-be9"></div>
                    <a href="javascript:void(0)" class="btn btn-wreview">Write a Review</a>
                    <a href="javascript:void(0)" class="btn btn-add2compare">Add to Compare</a>
                    <div class="clear"></div>
		    <div id="isocial" class="isocial"  data-url="{/XML/CURRENT_URL}" data-title="{/XML/SEO_TITLE}">
                        <!--section class="share-this-in">
                            <span class="fb-r"><i class="fb-i"></i> 2k</span>
                            <span class="tw-r"><i class="tw-i"></i> 5k</span>
                            <span class="gp-r"><i class="gp-i"></i> 6k</span>
                            <div class="clear"></div>
                        </section-->
                    </div>
		   <!--div id="isocial" class="isocial" data-url="{/XML/CURRENT_URL}" data-title="{/XML/SEO_TITLE}"></div-->
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
            <section class="branddetailswrap">
                                <h2 class="hdh2">About <xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" /></h2>
                                <div class="clear"></div>
                                <section class="col-xs-12 blksummarywarp">
                                    <aside class="col-xs-12 col-sm-6 sumtext">
                                        <xsl:value-of select="/XML/PRODUCT_DETAIL/PRODUCT_DETAIL_DATA/PRODUCT_DESC" disable-output-escaping="yes"/>
                                    </aside>
                                    <aside class="col-xs-12 col-sm-6 impstats">
                                        <p class="hdttl">Important Stats</p>
                                        <ul class="statslist">
                                            <xsl:for-each select="/XML/OVERVIEW/FEATURE_SPEC_SHORT_DESC/FEATURE_SPEC_DATA">
                                                <li class="statsitem">
                                                    <div class="iconblk ibattery">
                                                        <i></i>
                                                    </div>
                                                    <div class="stat-ttl">
                                                        <span class="st-text">
                                                            <xsl:value-of select="FEATURE_TITLE" disable-output-escaping="yes"/>
                                                        </span>
                                                        <span class="st-desc">
                                                            <xsl:choose>
                                                                <xsl:when test="FEATURE_VALUE!=''">
                                                                    <xsl:choose>
                                                                        <xsl:when test="FEATURE_VALUE='yes' or FEATURE_VALUE='YES'">
                                                                            <i class="R sprit-icon"></i>
                                                                        </xsl:when>
                                                                        <xsl:otherwise>
                                                                            <xsl:choose>
                                                                                <xsl:when test="FEATURE_VALUE='no' or FEATURE_VALUE='NO'">
                                                                                    <i class="W sprit-icon"></i>
                                                                                </xsl:when>
                                                                                <xsl:otherwise>
                                                                                    <xsl:value-of select="FEATURE_VALUE" disable-output-esacaping="yes"/>
                                                                                </xsl:otherwise>
                                                                            </xsl:choose>
                                                                        </xsl:otherwise>
                                                                    </xsl:choose>
                                                                </xsl:when>
                                                                <xsl:otherwise>
                                                                                        &#160;
                                                                </xsl:otherwise>
                                                            </xsl:choose>
                                                        </span>
                                                    </div>
                                                    <div class="clear"></div>
                                                </li>
                                            </xsl:for-each>
                                        </ul>
                                    </aside>
                                </section>
                            </section>
           

            
           <div class="clear"></div>
           
            <h2 class="hdh2"><xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" /> Review</h2>
            <section class="blkdatawrap">
              <div class="clear"></div>
                <xsl:if test="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_TITLE!=''">
                <section class="col-xs-12 blkBgrReview">
                  <h3 class="vw-hdr">BGR REVIEW</h3>
                  <figure class="bgr-vw-item">
                        <a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_IMAGE}" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                            <a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" class="hdttl" target="_new"><xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_TITLE" disable-output-escaping="yes"/></a>
                            <div class="brand-rating">
                                <span class="rate-ttl">Rating</span>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING}%"> </span>
                                 </span>
                                 <div class="clear"></div>
                            </div>
                            <p class="byline">By <xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_AUTHOR" disable-output-escaping="yes"/> on <xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_PUB_DATE" disable-output-escaping="yes"/></p>
                            <div class="desctxt"><xsl:value-of select="/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_ABSTRACT" disable-output-escaping="yes"/>.</div>
                            <a href="{/XML/EXPERT_RATING_DETAIL/EXPERT_RATING_LINK}" class="readmore" target="_new">Read complete review <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                </section>
                </xsl:if>
                <div class="clear"></div>
                
            <xsl:call-template name="userReview" />
                
                <div class="clear"></div>
            </section>
            
            <h2 class="hdh2"><xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" /> All variants</h2>
            <section class="blkdatawrap">
                <ul class="col-xs-12 blkvarnt">
					<xsl:for-each select="/XML/PRODUCT_VERSION_DETAIL/PRODUCT_VERSION_DETAIL_DATA">
								<xsl:sort select="EXSHOWROOM_ORGINAL" data-type="number" order="ascending"/>
                    <li>
                        <aside>
                            <h3 class="hdh3"><a href="{OVERVIEW_SEO_URL}"><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/>&#160;<xsl:value-of select="PRODUCT_NAME"/>&#160;<xsl:value-of select="VARIANT"/></a></h3>
                            <p class="desc">
									<xsl:for-each select="TECH_SPEC_SHORT_DESC/TECH_SPEC_DATA">
								<xsl:if test="position()&lt;=3">
								<xsl:value-of select="FEATURE_VALUE" disable-output-escaping="yes"/>
								<xsl:if test="position() mod 3 != 0 and position()!=last()">
								<i>|</i></xsl:if>
								<xsl:if test="position() mod 3 = 0"><xsl:text disable-output-escaping="yes"></xsl:text></xsl:if>
								</xsl:if>
								</xsl:for-each>
							</p>
                        </aside>
                        <aside class="vr-amt price"><i></i><xsl:value-of select="EXSHOWROOM"/></aside>
                        <div class="clear"></div>
                    </li>
					</xsl:for-each>
					<!-- 
                    <li>
                        <aside>
                            <h3 class="hdh3">BlackBerry Z3 32 GB</h3>
                            <p class="desc">16 GB  |  3G, 4G  |  Quad-core 2.5 GHZ</p>
                        </aside>
                        <aside class="vr-amt price"><i></i>32,000</aside>
                        <div class="clear"></div>
                    </li>
                    <li>
                        <aside>
                            <h3 class="hdh3">BlackBerry Z3 32 GB</h3>
                            <p class="desc">16 GB  |  3G, 4G  |  Quad-core 2.5 GHZ</p>
                        </aside>
                        <aside class="vr-amt price"><i></i>40,000</aside>
                        <div class="clear"></div>
                    </li> -->
                </ul>
                <div class="clear"></div>
            </section>
            <div class="clear"></div>
           
            <h2 class="hdh2">Full Specifications of <xsl:value-of select="/XML/MODEL_BRAND_NAME" />&#160;<xsl:value-of select="/XML/MODEL_NAME" /></h2>
            <section class="blkdatawrap">
              <div class="clear"></div>
                <xsl:for-each select="/XML/GROUP_MASTER/GROUP_MASTER_DATA">
                                    <xsl:for-each select="SUB_GROUP_MASTER">
                                        <section class="col-xs-12 blkSpecs">
                                            <xsl:if test="PIVOT_FEATURE_ID!=FEATURE_ID">
                                                <h3 class="vw-hdr">
                                                    <xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/>                                                
                                                </h3>
                                                <ul class="blkspeclist">
                                                    <xsl:for-each select="SUB_GROUP_MASTER_DATA">
                                                        <xsl:if test="FEATURE_VALUE!='' and FEATURE_VALUE!='-'">
                                                            <li class="blkspec-item">
                                                                <span class="col-xs-12 col-sm-5 spec-lbl">
                                                                    <xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/> 
                                                                </span>
                                                                <span class="col-xs-12 col-sm-7 spec-val">
                                                                    <xsl:choose>
                                                                        <xsl:when test="FEATURE_VALUE!=''">
                                                                            <xsl:choose>
                                                                                <xsl:when test="FEATURE_VALUE='yes'">
                                                                                    <i class="R sprit-icon"></i>
                                                                                </xsl:when>
                                                                                <xsl:otherwise>
                                                                                    <xsl:choose>
                                                                                        <xsl:when test="FEATURE_VALUE='no'">
                                                                                            <i class="W sprit-icon"></i>
                                                                                        </xsl:when>
                                                                                        <xsl:otherwise>
                                                                                            <xsl:value-of select="FEATURE_VALUE" disable-output-esacaping="yes"/>
                                                                                        </xsl:otherwise>
                                                                                    </xsl:choose>
                                                                                </xsl:otherwise>
                                                                            </xsl:choose>
                                                                        </xsl:when>
                                                                        <xsl:otherwise>
                                                                                        &#160;
                                                                        </xsl:otherwise>
                                                                    </xsl:choose>
                                                                </span>
                                                                <div class="clear"></div>
                                                            </li>
                                                        </xsl:if>
                                                    </xsl:for-each>
                                                </ul>
                                            </xsl:if>
                                        </section>
                                    </xsl:for-each>
                                </xsl:for-each>
				
				
				
                <div class="clear"></div>
            </section>
            
            <div class="discuss">
              <xsl:call-template name="Disqustmplt"/>
                                <!-- <img src="{/XML/IMAGE_URL}discuss.jpg" width="660" height="406" style="width:100%; height:auto;"/> -->
            </div>
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
		var gplus = <xsl:value-of select="/XML/GPLUS_COUNT" disable-output-escaping="yes"/>;
                $(document).ready(function(){
                        $('.isocial').isocial();

                });
        </script>
        </body>
</html>
</xsl:template>
</xsl:stylesheet>  
