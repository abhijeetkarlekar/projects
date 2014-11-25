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
               <xsl:call-template name="breadcrumb"/>
                </section>

                 
     
            
           
             <h1 class="hdh2">User Reviews</h1>
            <section class="blkdatawrap blkdatainr">
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

                    
                

                    
                 <!--    <figure class="bgr-uvw-item">
                        <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgthumb.jpg" alt=""></a>
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
            <nav class="gadget-pagination">
             <xsl:value-of select="/XML/PAGING" disable-output-escaping="yes" />
            </nav>
            <div class="clear"></div>
		 </aside>

        <aside class="container-right col-sm-4">
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>
            <div class="ads300"><img src="{/XML/IMAGE_URL}ad300-100.jpg" /></div>
            <div class="clear"></div>
            
                <xsl:call-template name="bestSeller"/>
                     <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>
                <xsl:call-template name="featuredCompare"/>

       
           
      
            <div class="ads300"><img src="{/XML/IMAGE_URL}300x250.jpg" /></div>
            <div class="clear"></div>

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