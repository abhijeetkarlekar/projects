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
	 <xsl:include href="../components/xsl/upcoming.xsl"/>
         <xsl:include href="../components/xsl/home_featured.xsl"/>
         <xsl:include href="../components/xsl/budget.xsl"/>
         <xsl:include href="../components/xsl/new_arrivals.xsl"/>
         <xsl:include href="../components/xsl/popular_brands.xsl"/>
         <xsl:include href="../components/xsl/search_box.xsl"/>
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
                
                    <xsl:call-template name="homeFeatured" />
		          
                <!-- <section class="hpsliderwrap">
                <section class="hpslider col-xs-12">
                    <figure class="hpitem col-xs-12">
                        <a href="javascript:void(0)" class="imgwrp col-xs-12 col-sm-5"><img src="{/XML/IMAGE_URL}hpslider.jpg"/></a>
                        <figcaption class="hpslid-desc col-xs-12 col-sm-7">
                            <h2 class="hdh2">Sony Xperia T3</h2>
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
                    </figure>
                </section>
            </section> -->
            <div class="clear"></div>
            <xsl:call-template name="searchBox" />
<!--			<section class="upcoming-mp" >
            	<div class="ttlbx">
                	<h2 class="hdh2">Let's Find A Mobile For You!</h2>
                    <div class="clear"></div>
                </div>
            	<form class="frmfindmob">
                	<aside class="sltbxwrp col-xs-12 col-sm-3">
                    	<select class="sltbx col-xs-12" >
                            <option value="">-Select Brand-</option>
                            <option value="">Samsung</option>
                            <option value="">iPhone</option>
                        </select>
                        <select class="sltbx col-xs-12" >
                            <option value="">-Select Phone Type-</option>
                            <option value="">Android</option>
                            <option value="">Windows</option>
                        </select>
                    </aside>
                    <aside class="sltrng col-xs-12 col-sm-6">
                         2. Write markup for the slider 
                        <p class="dtspr">Drag to set price range</p>
                        <div class="nstSlider" data-range_min="1000" data-range_max="100000" data-cur_min="1000"  data-cur_max="100000">     
                            <div class="highlightPanel"></div>
                            <div class="panelbg"></div>        
                            <div class="bar"></div>                  
                            <div class="leftGrip"></div>              
                            <div class="rightGrip"></div>
                            <div class="clear"></div>							
                        </div>
                            
                        <section class="rs-min-max-pr">
                            <i class="rs rs-1"></i>
                            <div class="min"><input type="text" class="leftLabel" onKeyPress="searchKeyPress(event);" onBlur="updatefilter()" /></div>
                            <div class="fl ds"> - </div>
                            <div class="max"><input type="text" class="rightLabel" onKeyPress="searchKeyPress(event);" onBlur="updatefilter()" /></div>
                            <div class="clear"></div>
                        </section>
                    </aside>
                    <aside class="fndmob col-xs-12 col-sm-3">
                    	<button type="submit" class="hpfindmob">Find Mobile</button>
                        <a href="javascript:void(0)" class="ireadmore">More Options <i></i></a>
                    </aside>
                	<div class="clear"></div>
                </form>
            </section>-->
           <div class="clear"></div>
           <xsl:call-template name="newArrivals" />
           
<!--		   <h2 class="hdh2 ihdr">new samsung mobiles in india</h2>
			<a href="javascript:void(0)" class="ireadmore">More Upcoming Phones <i></i></a>
            <div class="clear"></div>
            <section class="upcoming-mp" >
            	<div class="upcmg-lst">
                	<a href="javascript:void(0)" class="upcmg-itm">
                        <figure>
                            <div class="imgwrp"><img src="{/XML/IMAGE_URL}upcoming-mobile.jpg" width="82" height="163"/></div>
                            <figcaption>Nokia Lumia 930</figcaption>
                        </figure>
                    </a>
                	<a href="javascript:void(0)" class="upcmg-itm">
                        <figure>
                            <div class="imgwrp"><img src="{/XML/IMAGE_URL}upcoming-mobile.jpg" width="82" height="163"/></div>
                            <figcaption>Nokia Lumia 930</figcaption>
                        </figure>
                    </a>
                	<a href="javascript:void(0)" class="upcmg-itm">
                        <figure>
                            <div class="imgwrp"><img src="{/XML/IMAGE_URL}upcoming-mobile.jpg" width="82" height="163"/></div>
                            <figcaption>Nokia Lumia 930</figcaption>
                        </figure>
                    </a>
                	<a href="javascript:void(0)" class="upcmg-itm">
                        <figure>
                            <div class="imgwrp"><img src="{/XML/IMAGE_URL}upcoming-mobile.jpg" width="82" height="163"/></div>
                            <figcaption>Nokia Lumia 930</figcaption>
                        </figure>
                    </a>
                    <div class="clear"></div>
                </div>
            </section> -->			
            <div class="clear"></div>
            <xsl:call-template name="popularBrands" />
<!--			  <h2 class="hdh2 ihdr">Popular Brands</h2>
			<a href="javascript:void(0)" class="ireadmore">View all Brands <i></i></a>
            <div class="clear"></div>
            <section class="popular-brands ipopular-brands">
               <section class="popular-brands-inner">
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>	
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>	
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                    </figcaption>
                     <div class="clear"></div>	
                    </figure>
                    <figure class="newarr-box">
                    <a href="">
                        <img src="{/XML/IMAGE_URL}popular-brands.jpg" />
                    </a>
                    <figcaption>
                        <h2><a href="">Nokia</a></h2>
                    
                        </figcaption>
                         <div class="clear"></div>	
                        </figure>
                        <div class="clear"></div>	
                    </section>
                            </section>-->
                            <div class="clear"></div>
                            <!-- 			<h2 class="hdh2 ihdr">upcoming mobile phones</h2>
                            <a href="javascript:void(0)" class="ireadmore">More Upcoming Phones <i></i></a>
                <div class="clear"></div>
                <section class="iupcmgPhn">
                   <section class="iupcmgPhn-inner">
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Samsung Galaxy Note 4</a></h2>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Huawei Ascend P7</a></h2>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Nokia</a></h2>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Nokia</a></h2>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Micromax Canvas Win W121</a></h2>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <div class="clear"></div>	
                    </section>
                            </section> -->
                            <xsl:call-template name="upcoming"/>
                            <div class="clear"></div>
                            <xsl:call-template name="budget"/>	
                            <!-- h2 class="hdh2 ihdr">budget mobile phones</h2>
                            <a href="javascript:void(0)" class="ireadmore">More Budget Phones <i></i></a>
                <div class="clear"></div>
                <section class="iupcmgPhn">
                   <section class="iupcmgPhn-inner">
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Nokia Lumia 930</a></h2>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:80%"> </span>
                                 </span>
                                 <div class="clear"></div>
                                 <p class="price"><i></i> 8,590</p>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Nokia Lumia 930</a></h2>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:80%"> </span>
                                 </span>
                                 <div class="clear"></div>
                                 <p class="price"><i></i> 8,590</p>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Nokia Lumia 930</a></h2>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:80%"> </span>
                                 </span>
                                 <div class="clear"></div>
                                 <p class="price"><i></i> 8,590</p>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Nokia Lumia 930</a></h2>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:80%"> </span>
                                 </span>
                                 <div class="clear"></div>
                                 <p class="price"><i></i> 8,590</p>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <figure class="newarr-box col-xs-6">
                            <a href="javascript:void(0)" class="imgwrp">
                                    <img src="{/XML/IMAGE_URL}newMobiles.jpg" />
                            </a>
                            <figcaption>
                                    <h2><a href="">Nokia Lumia 930</a></h2>
                                <span class="avg_user_stars">
                                     <span class="rating" style="width:80%"> </span>
                                 </span>
                                 <div class="clear"></div>
                                 <p class="price"><i></i> 8,590</p>
                            </figcaption>
                            <div class="clear"></div>	
                        </figure>
                        <div class="clear"></div>	
                    </section>
                            </section -->
                            <div class="clear"></div>
                            <!-- <h2 class="hdh2 ihdr">user reviews</h2>
                                        <a href="javascript:void(0)" class="ireadmore">Read all user reviews <i></i></a>
                            <div class="clear"></div>
                            <section class="blkdatawrap blkdatainr imb30">
                                <div class="blk-uvw-list">
                                     <figure class="bgr-uvw-item">
                                        <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgthumb.jpg" alt=""/></a>
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
                                        <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgthumb.jpg" alt=""/></a>
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
                                        <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgthumb.jpg" alt=""/></a>
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
                                        <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgthumb.jpg" alt=""/></a>
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
                                        <a href="javascript:void(0)" class="col-xs-1 bgr-vw-img"><img src="{/XML/IMAGE_URL}reviewImgthumb.jpg" alt=""/></a>
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
                                </div>
                                <div class="clear"></div>
                            </section> -->
                            <xsl:call-template name="userReview" />
                            <div class="clear"></div>
            
            		
                        </aside>
                        <aside class="container-right col-sm-4">
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" />
                            </div>
                            <div class="clear"></div>
                            <xsl:call-template name="bestSeller"/>
                            <!--  <section class="blksidebar blksimilar">
                            <h2 class="hdsd-blue">best sellers</h2>
                            <ul class="simList">
                                <li class="col-sm-6 simItem">
                                    <figure>
                                        <a href="javascript:void(0)" class="imgwrp"><img src="{/XML/IMAGE_URL}img-4.jpg" /></a>
                                        <figcaption>
                                             <h3><a href="javascript:void(0)" class="mobttl">BlackBerry Z30</a></h3>
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
                            <div class="clear"></div>
            
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" />
                            </div>
                            <div class="clear"></div>
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
                                           </ul>
                                <div class="clear"></div>
                            </section> -->
                            <xsl:call-template name="featuredCompare"/>
                            <div class="clear"></div>
                        </aside>
                    </section>
                </section>
                <xsl:call-template name="footerDiv"/>
                <script src="{/XML/JS_URL}jquery-1.8.3.min.js"></script>
                <script>
                    	var urlArr = new Array();
			var web_url = '<xsl:value-of select="/XML/WEB_URL" disable-output-escaping="yes"/>';
			var cat_path = '<xsl:value-of select="/XML/CAT_PATH" disable-output-escaping="yes"/>';
			var slider_price_range = '';
                </script>
                <script src="{/XML/JS_URL}gadget.js"></script>
            </body>
        </html>

    </xsl:template>
</xsl:stylesheet>	  
