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
    <xsl:include href="../components/xsl/upcoming.xsl"/>
    <xsl:include href="../components/xsl/more_on_brands.xsl"/>
    <xsl:include href="../components/xsl/browse_by_brands.xsl"/>
    <xsl:include href="inc_header.xsl" />
    <xsl:include href="inc_meta_header.xsl" />
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
                            </section>
                            <h1 class="h118">
                                <xsl:value-of select="/XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_NAME" /> 
                                <xsl:text> </xsl:text>
                                <xsl:value-of select="/XML/SELECTED_CATEGORY_NAME" disable-output-escaping="yes"/>
                            </h1>

                            <section class="brand">
                                <figure class="newarr-box">
                                    <a href="" class="imgwrap">
                                        <img src="{/XML/IMAGE_URL}{/XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_IMAGE}" />
                                    </a>
                                    <figcaption>
                                        <!-- h2>Samsung Telecommunications is one of five business units within  Samsung Electronics belonging to the Samsung Group and consists of the Mobile Communications Division Telecommunication Systems Division Computer Division MP3 Business Team Mobile Solution Centre.</h2 -->
					<h2>
						<xsl:value-of select="/XML/BRAND_DETAIL/BRAND_DETAIL_DATA/LONG_DESC" disable-output-escaping="yes" />
					</h2>
                                        <!--div class="share-this">
                                            <section class="share-this-in">
                                                <span class="fb-r">
                                                    <i class="fb-i"></i> 2k</span>
                                                <span class="tw-r">
                                                    <i class="tw-i"></i> 5k</span>
                                                <span class="gp-r">
                                                    <i class="gp-i"></i> 6k</span>
                                                <div class="clear"></div>
                                            </section>
                                        </div-->							
					<div id="isocial" class="isocial" data-url="{/XML/CURRENT_URL}" data-title="{/XML/SEO_TITLE}"></div>
                                    </figcaption>
                                    <div class="clear"></div>	
                                </figure>
                                <div class="clear"></div>	
                            </section>
                            <div class="clear"></div>
                            <xsl:call-template name="upcoming"/>
                          
                            <div class="clear"></div>
           
                            <h2 class="hdh2">new <xsl:value-of select="/XML/BRAND_DETAIL/BRAND_DETAIL_DATA/BRAND_NAME" /> mobiles in india</h2>
                            <section class="newmobwrap">
                                <div class="mobile-ph">
                                    <h3 class="fl">Mobile Phones</h3>
                                    <aside class="fr">
                                        Sort By: <span class="cur" onClick="showHideDiv('div1')">Newest First <i class="low-p"></i></span>
                                        <div onClick="this.style.display='block';" class="drp-list" id="div1">
                                            <a href="">Newest First</a>
                                            <div class="clear"></div>
                                            Price - High to Low
                                            <div class="clear"></div>
                                            Price - Low to High
                                            <div class="clear"></div>
                                            BGR Rating
                                        </div>
                                    </aside>
                                    <div class="clear"></div>
                                </div>
                                <div class="newmobList">
                                    <xsl:for-each select="/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA">
                                        <figure class="mobile-listing">
                                            <div class="imgt">
                                                <a href="{SEO_URL}">
                                                    <img src="{IMAGE_PATH}" />
                                                </a>
                                            </div>
                                            <figcaption>

                                                <aside class="col-sm-12 col-lg-7">
                                                    <h2>
                                                        <a href="{SEO_URL}">
                                                            <xsl:value-of select="DISPLAY_PRODUCT_NAME" disable-output-escaping="yes"/>
                                                        </a>
                                                    </h2>
                                                    <div class="clear"></div>
                                                    
                                                    <xsl:if test="EXPERT_RATING &gt;0">
                                                    <span class="avg_user_stars">
                                                    <span class="rating" style="width:{EXPERT_RATING}%"> </span>
                                                    <div class="clear"></div>
                                                    </span>
                                                    </xsl:if>
                                       

                                                    <p class="plinks">
                                                        <a href="javascript:void(0)">News</a>
                                                        <a href="javascript:void(0)">Reviews</a>
                                                        <a href="javascript:void(0)">Photos</a>
                                                        <a href="javascript:void(0)">Videos</a>
                                                    </p>
                                                </aside>
                                                <aside class="col-sm-12 col-lg-2"></aside>
                                                <aside class="read-exp col-sm-12 col-lg-3">
                                                    <p>
                                                        <i class="rs"></i> 
                                                        <xsl:value-of select="EXSHOWROOMPRICE" disable-output-escaping="yes"/>
                                                    </p>
                                                    <div class="addcomp">
                                                        <input type="checkbox" name="" value="" class="addtocom" onclick="AddToCompareWidget('{PRODUCT_ID}','{DISPLAY_PRODUCT_NAME}','{/XML/IMAGE_URL}img-3.jpg','{COMPARENAME}','{/XML/SELECTED_CATEGORY_ID}')"/> 
                                                        <a href="">Add to Compare</a>
                                                    </div>
                                                </aside>
                                            </figcaption>
                                            <div class="clear"></div>
                                        </figure>
                                    </xsl:for-each>>
                                    <!-- <figure class="mobile-listing">
                                       <div class="imgt">
                        <a href="javascript:void(0)"><img src="{/XML/IMAGE_URL}newMobiles.jpg" /></a>
                                            </div>
                                            <figcaption>
                                               <aside class="col-sm-12 col-lg-7">
                           <h2>Samsung Galaxy Core 2 Duos</h2>
                           <div class="clear"></div>
                           <span class="avg_user_stars">
                                <span class="rating" style="width:50%"> </span>
                                <div class="clear"></div>
                            </span> 
                            <p class="plinks">
                                    <a href="javascript:void(0)">News</a>
                                <a href="javascript:void(0)">Reviews</a>
                                <a href="javascript:void(0)">Photos</a>
                                <a href="javascript:void(0)">Videos</a>
                            </p>
                                               </aside>
                                               <aside class="col-sm-12 col-lg-2"></aside>
                                               <aside class="read-exp col-sm-12 col-lg-3">
                            <p><i class="rs"></i> 22,990</p>
                            <div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
                                               </aside>
                                            </figcaption>
                                             <div class="clear"></div>
                                    </figure>
                    <figure class="mobile-listing">
                                       <div class="imgt">
                        <a href="javascript:void(0)"><img src="{/XML/IMAGE_URL}newMobiles.jpg" /></a>
                                            </div>
                                            <figcaption>
                                               <aside class="col-sm-12 col-lg-7">
                           <h2>Samsung Galaxy Core 2 Duos</h2>
                           <div class="clear"></div>
                           <span class="avg_user_stars">
                                <span class="rating" style="width:50%"> </span>
                                <div class="clear"></div>
                            </span> 
                            <p class="plinks">
                                    <a href="javascript:void(0)">News</a>
                                <a href="javascript:void(0)">Reviews</a>
                                <a href="javascript:void(0)">Photos</a>
                                <a href="javascript:void(0)">Videos</a>
                            </p>
                                               </aside>
                                               <aside class="col-sm-12 col-lg-2"></aside>
                                               <aside class="read-exp col-sm-12 col-lg-3">
                            <p><i class="rs"></i> 22,990</p>
                            <div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
                                               </aside>
                                            </figcaption>
                                             <div class="clear"></div>
                                    </figure>
                    <figure class="mobile-listing">
                                       <div class="imgt">
                        <a href="javascript:void(0)"><img src="{/XML/IMAGE_URL}newMobiles.jpg" /></a>
                                            </div>
                                            <figcaption>
                                               <aside class="col-sm-12 col-lg-7">
                           <h2>Samsung Galaxy Core 2 Duos</h2>
                           <div class="clear"></div>
                           <span class="avg_user_stars">
                                <span class="rating" style="width:50%"> </span>
                                <div class="clear"></div>
                            </span> 
                            <p class="plinks">
                                    <a href="javascript:void(0)">News</a>
                                <a href="javascript:void(0)">Reviews</a>
                                <a href="javascript:void(0)">Photos</a>
                                <a href="javascript:void(0)">Videos</a>
                            </p>
                                               </aside>
                                               <aside class="col-sm-12 col-lg-2"></aside>
                                               <aside class="read-exp col-sm-12 col-lg-3">
                            <p><i class="rs"></i> 22,990</p>
                            <div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
                                               </aside>
                                            </figcaption>
                                             <div class="clear"></div>
                                    </figure>
                    <figure class="mobile-listing">
                                       <div class="imgt">
                        <a href="javascript:void(0)"><img src="{/XML/IMAGE_URL}newMobiles.jpg" /></a>
                                            </div>
                                            <figcaption>
                                               <aside class="col-sm-12 col-lg-7">
                           <h2>Samsung Galaxy Core 2 Duos</h2>
                           <div class="clear"></div>
                           <span class="avg_user_stars">
                                <span class="rating" style="width:50%"> </span>
                                <div class="clear"></div>
                            </span> 
                            <p class="plinks">
                                    <a href="javascript:void(0)">News</a>
                                <a href="javascript:void(0)">Reviews</a>
                                <a href="javascript:void(0)">Photos</a>
                                <a href="javascript:void(0)">Videos</a>
                            </p>
                                               </aside>
                                               <aside class="col-sm-12 col-lg-2"></aside>
                                               <aside class="read-exp col-sm-12 col-lg-3">
                            <p><i class="rs"></i> 22,990</p>
                            <div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
                                               </aside>
                                            </figcaption>
                                             <div class="clear"></div>
                                    </figure>
                    <figure class="mobile-listing">
                                       <div class="imgt">
                        <a href="javascript:void(0)"><img src="{/XML/IMAGE_URL}newMobiles.jpg" /></a>
                                            </div>
                                            <figcaption>
                                               <aside class="col-sm-12 col-lg-7">
                           <h2>Samsung Galaxy Core 2 Duos</h2>
                           <div class="clear"></div>
                           <span class="avg_user_stars">
                                <span class="rating" style="width:50%"> </span>
                                <div class="clear"></div>
                            </span> 
                            <p class="plinks">
                                    <a href="javascript:void(0)">News</a>
                                <a href="javascript:void(0)">Reviews</a>
                                <a href="javascript:void(0)">Photos</a>
                                <a href="javascript:void(0)">Videos</a>
                            </p>
                                               </aside>
                                               <aside class="col-sm-12 col-lg-2"></aside>
                                               <aside class="read-exp col-sm-12 col-lg-3">
                            <p><i class="rs"></i> 22,990</p>
                            <div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
                                               </aside>
                                            </figcaption>
                                             <div class="clear"></div>
                                    </figure> -->
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
				<xsl:call-template name="moreOnBrands" />
                            <!-- section class="blksidebar">
                                <h2 class="hdsd-blue">More on BlackBerry Z3</h2>
                                <ul class="sidebarlisting">
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Check BlackBerry Z3 specifications</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Check BlackBerry Z3 specifications</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Check BlackBerry Z3 specifications</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Check BlackBerry Z3 specifications</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Check BlackBerry Z3 specifications</a>
                                    </li>
                                </ul>
                                <div class="clear"></div>
                            </section -->
           			<xsl:call-template name="browseByBrands" /> 
                            <!-- section class="blksidebar">
                                <h2 class="hdsd-blue">browse mobiles by brand</h2>
                                <ul class="sidebarlisting2">
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Apply</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Sony</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Samsung</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Motarola</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Nokia</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">LG</a>
                                    </li>  
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">HTC</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Karbon</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Micromax</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Lava</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">BlackBerry</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Spice</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Sony</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Apple</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Motarola</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Samsung</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">LG</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Nokia</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Karbon</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">HTC</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Lava</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Micromax</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">Spice</a>
                                    </li>
                                    <li>
                                        <i></i>
                                        <a href="javascript:void(0)">BlackBerry</a>
                                    </li>
                                    <div class="clear"></div>
                                </ul>
                                <div class="clear"></div>
                            </section -->   
                            <div class="clear"></div>
                            <div class="ads300">
                                <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
                            </div>
                            <div class="clear"></div>
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
