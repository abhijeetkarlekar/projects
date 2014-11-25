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

<xsl:include href="inc_header.xsl" />
<xsl:include href="inc_footer.xsl" />
<xsl:include href="inc_breadcrumb.xsl" />
<xsl:include href="model_menu_tab.xsl" />
<xsl:include href="../components/xsl/browse_by_brands.xsl"/>
<xsl:include href="../components/xsl/upcoming_brands.xsl"/>
<xsl:include href="../components/xsl/upcoming_bytype.xsl"/>
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
            <h1 class="h118">Upcoming Mobiles</h1>
            <section class="brand">
                <div class="newarr-box">
                <p>Find all the detailed information of all the upcoming mobiles. These upcoming mobiles are said to go
                on sale in the Indian market in near future. The list includes feature phones, smartphones, tablets.
                So take a look at the future of the Indian mobile industry with the list.</p>
                </div>
                <div class="clear"></div> 
            </section>
      <div class="clear"></div> 
            
            <section class="upcoming-frmwrap" >
              <div class="ttlbx">
                  <h2 class="hdh2">find upcoming mobiles in india</h2>
                    <div class="clear"></div>
                </div>
              
                <form class="upcmg-frm" method="post" name="car_search" id="car_search">
               
                <select class="sltupcmg col-xs-12 col-sm-3"  name="Brand" id="Brand">
                <xsl:choose>
                <xsl:when test="/XML/SELECTED_BRAND_ID=''">
                <option value="" selected='yes'>All Brands</option>
                </xsl:when>
                <xsl:otherwise>
                <option value="">All Brands</option>
                </xsl:otherwise>
                </xsl:choose>
                <xsl:value-of select="SELECTED_BRAND_ID" disable-output-escaping="yes"/>
                <xsl:for-each select="/XML/BRAND_LIST/BRAND_LIST_DATA">
                <xsl:choose>
                <xsl:when test="/XML/SELECTED_BRAND_ID = BRAND_ID">
                <option value="{BRAND_ID}" selected='yes'><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/></option>
                </xsl:when>
                <xsl:otherwise>
                <option value="{BRAND_ID}"><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/></option>
                </xsl:otherwise>
                </xsl:choose>
                </xsl:for-each>
                </select>
                  
            

              <select  class="sltupcmg col-xs-12 col-sm-3" name="Feature" id="Feature">
              <xsl:choose>
              <xsl:when test="/XML/SELECTED_FEATURE_ID=''">
              <option value="" selected='yes'>All Phone Type</option>
              </xsl:when>
              <xsl:otherwise>
              <option value="">All Phone Type</option>
              </xsl:otherwise>
              </xsl:choose>
              <xsl:for-each select="/XML/BODY_STYLE_LIST/BODY_STYLE_LIST_DATA">
              <xsl:choose>
              <xsl:when test="/XML/SELECTED_FEATURE_ID = FEATURE_ID">
              <option value="{FEATURE_ID}" selected='yes'><xsl:value-of select="FEATURE_NAME" disable-output-escaping="yes"/></option>
              </xsl:when>
              <xsl:otherwise>
              <option value="{FEATURE_ID}"><xsl:value-of select="FEATURE_NAME" disable-output-escaping="yes"/></option>
              </xsl:otherwise>
              </xsl:choose>
              </xsl:for-each>
              </select>


          <button type="button" class="btnupcmgSubmit" onclick="javascript:searchUpcomingCars();">Find Mobiles</button>
                    
                    <div class="clear"></div>
                </form>
            </section>
            
            <div class="share-this share-transparent">
                <section class="share-this-in">
                    <span class="fb-r"><i class="fb-i"></i> 2k</span>
                    <span class="tw-r"><i class="tw-i"></i> 5k</span>
                    <span class="gp-r"><i class="gp-i"></i> 6k</span>
                    <div class="clear"></div>
                </section>
            </div>
           <div class="clear"></div>
           <h2 class="hdh2">upcoming mobile phones</h2>
           <section class="upcmgmobwrap">
                <div class="upcmgmobList">
                  <xsl:for-each select="/XML/UPCOMING_PRODUCT_MASTER/UPCOMING_PRODUCT_MASTER_DATA">
                  <figure class="mobile-listing">
                  <a href="{SEO_MODEL_URL}" class="imgwrp col-xs-12 col-sm-3">
                    <xsl:choose>
                      <xsl:when test="IMAGE_PATH!=''">
                        <img src="{IMAGE_PATH}" alt="{PRODUCT_NAME}" title="{PRODUCT_NAME}" />
                      </xsl:when>
                      <xsl:otherwise>
                        <img src="{/XML/IMAGE_URL}no-image.png" alt="{PRODUCT_NAME}" title="{PRODUCT_NAME}" />
                      </xsl:otherwise>
                      </xsl:choose>
                  </a>
                  <figcaption class="col-xs-12 col-sm-9">
                  <h2><a href="{SEO_MODEL_URL}"><xsl:value-of select="PRODUCT_NAME" disable-output-escaping="yes"/></a></h2>
                  <span class="explnch">Expected Launch: <xsl:value-of select="EXPECTED_DATE_TEXT" disable-output-escaping="yes"/></span>
                  <div class="clear"></div>
                  <div class="upcmgdesc"><xsl:value-of select="SHORT_DESCRIPTION" disable-output-escaping="yes"/> .</div>
                  <a class="readmore" href="{SEO_MODEL_URL}">Continue reading <i></i></a>
                  </figcaption>
                  <div class="clear"></div>
                  </figure>
                  </xsl:for-each>

               
                  
                </div>
           </section>
            
            <div class="clear"></div>
            <nav class="gadget-pagination">
              <xsl:value-of select="/XML/PAGING" disable-output-escaping="yes" />
        </nav>      
     </aside>

        <aside class="container-right col-sm-4">
       <div class="ads300">
         <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
       </div>
       <div class="clear"></div>
     <xsl:call-template name="UpcomingBrands"/>
     
     <xsl:call-template name="UpcomingByTypes"/>
            <div class="clear"></div>
            <div class="ads300">
              <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
            </div>
   <xsl:call-template name="browseByBrands" />   
            <div class="clear"></div>
            <div class="ads300">
              <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
            </div>
            <div class="clear"></div>
     </aside>
     
     
    </section>
  </section>
       <xsl:call-template name="footerDiv"/>
       <script>
           var siteURL = '<xsl:value-of select="/XML/WEB_URL" disable-output-escaping="yes"/>';
   
    var seo_web_url = '<xsl:value-of select="/XML/SEO_WEB_URL" diseable-output-esacping="yes"/>';
    var seo_car_finder = '<xsl:value-of select="/XML/SEO_CAR_FINDER" diseable-output-esacping="yes"/>';
    var selected_brand_name = '<xsl:value-of select="/XML/SELECTED_BRAND_NAME" disable-output-escaping="yes"/>';
    var selected_feature_name = '<xsl:value-of select="/XML/SELECTED_FEATURE_NAME" disable-output-escaping="yes"/>';
    var selectedBrandId = '<xsl:value-of select='/XML/SELECTED_BRAND_ID' disable-output-escaping="yes"/>';
  var selectedBodyStyleId = '<xsl:value-of select='/XML/SELECTED_FEATURE_ID' disable-output-escaping="yes"/>';
</script>
      <script src="{/XML/JS_URL}jquery-1.8.3.min.js"></script> 
      <script src="{/XML/JS_URL}gadget.js"></script>

        </body>
</html>
</xsl:template>
</xsl:stylesheet> 