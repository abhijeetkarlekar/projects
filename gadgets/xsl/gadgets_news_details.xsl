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
                <a class="blinks" href="javascript:void(0);">&nbsp;&nbsp;Blackberry Mobiles</a> <span class="brdcrum-arr"></span> Blackberry Z3
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
            
           
            <h2 class="hdh2"><xsl:value-of select="/XML/PRODUCT_BRAND_NAME" disable-output-escaping="yes"/>&#160;<xsl:value-of select="XML/PRODUCT_MODEL_NAME" disable-output-escaping="yes"/> News</h2>
            <section class="blkdatawrap blkdatainr">
              <div class="clear"></div>
                <section class="col-xs-12 blkBgrReview blkrw-innr">
                  <!-- <h3 class="vw-hdr"><xsl:value-of select="/XML/PRODUCT_BRAND_NAME" disable-output-escaping="yes"/>&#160;<xsl:value-of select="XML/PRODUCT_MODEL_NAME" disable-output-escaping="yes"/> News</h3> -->
                 <xsl:for-each select="/XML/NEWS_LIST_MASTER/NEWS_LIST_MASTER_DATA">
                  <figure class="bgr-vw-item">
                        <a href="{SEO_URL}" class="col-xs-12 col-sm-4 bgr-vw-img"><img src="{IMAGE_PATH}" alt=""/></a>
                        <figcaption class="col-xs-12 col-sm-8 bgr-vw-desc">
                            <a href="{SEO_URL}" class="hdttl" target="_new"><xsl:value-of select="TITLE" disable-output-escaping="yes" /></a>
                            <!-- <div class="brand-rating">
                                <span class="rate-ttl">Rating</span>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:80%"> </span>
                                 </span>
                                 <div class="clear"></div>
                            </div> -->
                            <p class="byline">By <xsl:value-of select="AUTHOR" disable-output-escaping="yes" /> on <xsl:value-of select="DISP_DATE" disable-output-escaping="yes" /></p>
                            <div class="desctxt"><xsl:value-of select="DESCRIPTION" disable-output-escaping="yes" /></div>
                            <a href="{SEO_URL}" class="readmore">Continue reading <i></i></a>
                        </figcaption>
                        <div class="clear"></div>
                    </figure>
                    </xsl:for-each>
            
                </section>
                <div class="clear"></div>
            </section>
            <div class="clear"></div>
         <!--    <nav class="gadget-pagination">
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
     </aside>

        <aside class="container-right col-sm-4">
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>
            <div class="ads300"><img src="{/XML/IMAGE_URL}ad300-100.jpg" /></div>
            <div class="clear"></div>
            <xsl:call-template name="news"/>
       <!--      <section class="blksidebar">
              <h2 class="hdh2">BlackBerry Z3 News</h2>
                <div class="blksdbar-news">
                  <figure class="blksdbar-news-item">
                    <a class="imgwrp col-xs-5" href="javascript:void(0)"><img src="{/XML/IMAGE_URL}img-sidebar.jpg" width="110" height="82"/></a>
                        <figcaption class="col-xs-7">
                          <a href="javascript:void(0)" class="catname">Business</a>
                            <a href="javascript:void(0)" class="ttl-link">Blackberry Z3 review: Aworthy mid-range all rounder in bit Price...</a>
                        </figcaption>
                        <div class="clear"></div>
                     </figure>
                  <figure class="blksdbar-news-item">
                    <a class="imgwrp col-xs-5" href="javascript:void(0)"><img src="{/XML/IMAGE_URL}img-sidebar.jpg" width="110" height="82"/></a>
                        <figcaption class="col-xs-7">
                          <a href="javascript:void(0)" class="catname">Business</a>
                            <a href="javascript:void(0)" class="ttl-link">Blackberry Z3 review: Aworthy mid-range all rounder in bit Price...</a>
                        </figcaption>
                        <div class="clear"></div>
                     </figure>
                  <figure class="blksdbar-news-item">
                    <a class="imgwrp col-xs-5" href="javascript:void(0)"><img src="{/XML/IMAGE_URL}img-sidebar.jpg" width="110" height="82"/></a>
                        <figcaption class="col-xs-7">
                          <a href="javascript:void(0)" class="catname">Business</a>
                            <a href="javascript:void(0)" class="ttl-link">Blackberry Z3 review: Aworthy mid-range all rounder in bit Price...</a>
                        </figcaption>
                        <div class="clear"></div>
                     </figure>
                </div>
                <a href="javascript:void(0)" class="sdbar-viewall">View all News <i></i></a>
                <div class="clear"></div>
            </section> -->
           <xsl:call-template name="moreon" />
           <xsl:call-template name="compare"/>
            <!-- <section class="blksidebar blksidecompare">
              <h2 class="hdsd-blue">More on BlackBerry Z3</h2>
                <ul class="sdtopcompare">
          <li>
              <h2>BlackBerry Z3 Vs Samsung Galaxy s5</h2>
              <aside class="col-sm-12 col-md-5">
                            <img src="{/XML/IMAGE_URL}img-4.jpg" />
                            <h3>BlackBerry Z3</h3>
            </aside>
              <aside class="col-sm-12 col-md-2"><span class="vs">Vs</span></aside>
            <aside class="col-sm-12 col-md-5">
                            <img src="{/XML/IMAGE_URL}img-5.jpg" />
                            <h3>Samsung Galaxy S5</h3>
            </aside>
                        <div class="clear"></div>
          </li>
                    <li>
                            <h2>BlackBerry Z3 Vs Samsung Galaxy s5</h2>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-4.jpg" />
                                <h3>BlackBerry Z3</h3>
                            </aside>
                            <aside class="col-sm-12 col-md-2"><span class="vs">Vs</span></aside>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-5.jpg" />
                                <h3>Samsung Galaxy S5</h3>
                            </aside>
                            <div class="clear"></div>
                        </li>
                    <li>
                            <h2>BlackBerry Z3 Vs Samsung Galaxy s5</h2>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-4.jpg" />
                                <h3>BlackBerry Z3</h3>
                            </aside>
                            <aside class="col-sm-12 col-md-2"><span class="vs">Vs</span></aside>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-5.jpg" />
                                <h3>Samsung Galaxy S5</h3>
                            </aside>
                            <div class="clear"></div>
                        </li>         
                    <li>
                            <h2>BlackBerry Z3 Vs Samsung Galaxy s5</h2>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-4.jpg" />
                                <h3>BlackBerry Z3</h3>
                            </aside>
                            <aside class="col-sm-12 col-md-2"><span class="vs">Vs</span></aside>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-5.jpg" />
                                <h3>Samsung Galaxy S5</h3>
                            </aside>
                            <div class="clear"></div>
                        </li>
                    <li>
                            <h2>BlackBerry Z3 Vs Samsung Galaxy s5</h2>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-4.jpg" />
                                <h3>BlackBerry Z3</h3>
                            </aside>
                            <aside class="col-sm-12 col-md-2"><span class="vs">Vs</span></aside>
                            <aside class="col-sm-12 col-md-5">
                                <img src="{/XML/IMAGE_URL}img-5.jpg" />
                                <h3>Samsung Galaxy S5</h3>
                            </aside>
                            <div class="clear"></div>
                        </li>         
         </ul>
                <div class="clear"></div>
            </section> -->
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>
              <xsl:call-template name="similar" />
              <xsl:call-template name="trendingNow" />

            <!-- <section class="blksidebar blksimilar">
                <h2 class="hdsd-blue">Similar Mobiles</h2>
                <ul class="simList">
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">Samsung Galaxy S5</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">BlackBerry Classic</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">BlackBerry 9720</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>         
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">Porsche P'9982</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>
               </ul>
                <div class="clear"></div>
          </section>
            <section class="blksidebar">
                    <h2 class="hdsd-blue">Trending Now</h2>
                    <ul class="sidebarlisting">
                        <li><i></i><a href="javascript:void(0)">Check BlackBerry Z3 specifications</a></li>
                        <li><i></i><a href="javascript:void(0)">Check BlackBerry Z3 specifications</a></li>
                        <li><i></i><a href="javascript:void(0)">Check BlackBerry Z3 specifications</a></li>
                        <li><i></i><a href="javascript:void(0)">Check BlackBerry Z3 specifications</a></li>
                        <li><i></i><a href="javascript:void(0)">Check BlackBerry Z3 specifications</a></li>
                    </ul>
                    <div class="clear"></div>
                </section> -->
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>

             <xsl:call-template name="other" />

            <!-- <section class="blksidebar blksimilar">
                <h2 class="hdsd-blue">Other mobiles</h2>
                <ul class="simList">
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">Samsung Galaxy S5</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">BlackBerry Classic</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">BlackBerry 9720</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>         
                    <li class="col-sm-6 simItem">
                        <figure>
                            <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                            <figcaption>
                                 <h3><a href="javascript:void(0)" class="mobttl">Porsche P'9982</a></h3>
                                <div class="brand-rating">
                                    <span class="avg_user_stars">
                                         <span class="rating" style="width:80%"> </span>
                                     </span>
                                     <div class="clear"></div>
                                </div>
                                <p class="brd-amt"><i></i>10,490</p>
                            </figcaption>
                        </figure>
                        <div class="clear"></div>
                    </li>
               </ul>
                <div class="clear"></div>
          </section> -->
         </aside>
     
    </section>
  </section>
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
