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
<xsl:include href="../components/xsl/featured_mobile_phones.xsl"/>
<xsl:include href="../components/xsl/top_comparisons.xsl"/>
<xsl:include href="inc_header.xsl" />
<xsl:include href="inc_footer.xsl" />
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
	<!-- <div class="banner-hide">
			<div class="ads-banner-970" ><img src="{/XML/IMAGE_URL}banner.jpg" /></div>
	</div>
	 <div class="clear"></div>
   <div class="clear"></div>
	<header class="headerwrap">
	  <section class="headerwrap-inner">
		<div class="container">
        <aside class="headerRight">
              <nav class="navbar navbar-inverse" role="navigation">
            	<div class="navbar-header">
					<h1 class="navbar-brand">
						<a href="#" class="logo"><img title="" alt="" src="{/XML/IMAGE_URL}gad-logo.png"/></a>
					</h1>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-1-collapse">
					<span class="sr-only"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
                </div> 
				<div class="collapse navbar-collapse navbar-1-collapse" id="primarymenu">
				<ul class="nav navbar-nav" >
					<li ><a href="#">TOP MOBILES</a></li>
					<li ><a href="#">BRANDS </a></li>
					<li><a href=""  data-toggle="dropdown" class="dropdown-toggle">PHONE FINDER</a>
					 <ul role="menu" class="dropdown-menu">
						<li><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li><a href="#">5</a></li>
					  </ul>
					
					</li>
					<li><a href="#">COMPARE</a></li>
					<li><a href="#">USER REVIEWS</a></li>
					<li><a href="#">NEWS</a></li>
				</ul>
				</div> 
                 <aside class="searchbox">
					<div class="login"><i class="arc"></i>Log In</div>
					<div class="searchin cur">
						<a href="javascript:void(0)" class="icon-src"></a>
			      <div class="serc-box">
							<input type="text" placeholder="Search" class="inp"/>
							<input type="submit" value="Search" class="sub"/>
						</div>
					</div>
				 
				 </aside> 				
              </nav>
        </aside> 
        <div class="clear"></div>
    </div>
 </section>
</header>
 -->	
	<section class="inner-container">
	   <section class="container">
	     	 <section class="h-breadcrumb">
				<a class="home" href="javascript:void(0);"></a><span class="brdcrum-arr"></span>
                <a class="blinks" href="javascript:void(0);">Search Results</a>
                <div class="clear"></div>
			 </section>
         <aside class="inner-container-left col-sm-9 ">
			 <h1 class="h1g"><xsl:value-of select="/XML/SUB_TITLE" disable-output-escaping="yes"/></h1>
			 <xsl:call-template name="featuredMobilePhones"/>
			<!-- <section class="gadget-slider">
			  <div class="gadband">
					<h2>featured Mobile Phones</h2>
			  </div>
			  <section class="searchpage-slider">
			   <ul class="searchpage">
					<li>
					    <img src="{/XML/IMAGE_URL}img-1.jpg" />
						<div class="brand">LG Google Nexus 4</div>
						<div class="rs-info"><i class="rs"></i> 22,990</div>
						<div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
					</li>
				    <li>
					    <img src="{/XML/IMAGE_URL}img-1.jpg" />
						<div class="brand">LG Google Nexus 4</div>
						<div class="rs-info"><i class="rs"></i> 22,990</div>
						<div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
					</li>
				    <li>
					    <img src="{/XML/IMAGE_URL}img-1.jpg" />
						<div class="brand">LG Google Nexus 4</div>
						<div class="rs-info"><i class="rs"></i> 22,990</div>
						<div class="addcomp"><input type="checkbox" name="" value="" class="addtocom" /> <a href="">Add to Compare</a></div>
					</li>	
					
			   </ul>
			   <div class="clear"></div>
			   </section>
			   <div class="clear"></div>	
			</section> -->
           <div class="clear"></div>			
		 </aside>
        <aside class="container-right col-sm-4">
		   <div class="share-this">
		      <p class="share-this-b">Share This</p>
			   <section class="share-this-in">
			  <span class="fb-r"><i class="fb-i"></i> 2k</span>
			  <span class="tw-r"><i class="tw-i"></i> 5k</span>
			  <span class="gp-r"><i class="gp-i"></i> 6k</span>
			   <div class="clear"></div>
			   </section>
		   </div>
		   <div class="ads300">
		     <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
		   </div>

				   
			<div class="clear"></div>
		 </aside>
		</section>
        <div class="clear"></div>
        <div class="row">
            <section class="container gdtresult-container">
            	<form method="post" action="{/XML/PAGE_NAME}" name="findcars" id="findcars">
                <aside class="gdtfilter col-xs-12 col-md-3">
                    <h2 class="filterhead">Filter Your Search</h2>
                    <div class="filterwrap">
					  <section class="filterinfo"> 
							<div class="cmp-ftr-ttlbx">
								<a class="coll fl" href="javascript:void(0)"></a>
								<h4 class="fl">Price Range</h4>
								<div class="clear"></div>
							</div>
					<div class="cmp-ftr-ddl">
		             <!-- 2. Write markup for the slider -->
				  		<div class="nstSlider" data-range_min="100" data-range_max="100000" data-cur_min="{/XML/MIN_PRICE}"  data-cur_max="{/XML/MAX_PRICE}">     
							<div class="highlightPanel"></div>        
							<div class="bar"></div>                  
							<div class="leftGrip"></div>              
							<div class="rightGrip"></div>
                            <div class="clear"></div>							
						</div>
											
							<p class="dtspr">Drag to set price range</p>
                           <section class="rs-min-max-pr">
							 <i class="rs rs-1"></i>
							 <div class="min"><input type="text" id="minprice" class="leftLabel" onkeypress="searchKeyPress(event);" onBlur="updatefilter()"/></div>
							  <div class="fl ds"> - </div>
							  
							  <div class="max"><input type="text" id="maxprice" class="rightLabel" onkeypress="searchKeyPress(event);" onBlur="updatefilter()"/></div>
							   <div class="clear"></div>
						   </section>
						</div>
						 </section>
                    	 

                    	 <section class="filterinfo"> 
							<div class="cmp-ftr-ttlbx">
								<a class="coll fl" href="javascript:void(0)"></a>
								<h4 class="fl">Mobile Brands</h4>
								<div class="clear"></div>
							</div>
							<div class="cmp-ftr-ddl">
							   <xsl:choose>
								<xsl:when test="/XML/SELECTED_BRAND_ID&lt;=0">
									<p><input type="checkbox" value="1" name="sel_all_brand" id="sel_all_brand" checked="yes" /><span>All Brands</span></p>
								</xsl:when>
								<xsl:otherwise>
									<p><input type="checkbox" value="1" name="unsel_all_brand" id="sel_all_brand" onclick="javascript:removeAllBrands();" /><span>All Brands</span></p>
								</xsl:otherwise>
								</xsl:choose>
								
								<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
									<xsl:if test="position() &lt; 15">
										<xsl:choose>
										<xsl:when test="SELECTED_BRAND_ID=BRAND_ID">
											<p><input type="checkbox" value="{BRAND_ID}" name="branddetails[]" id="checkbox_brand_id_{BRAND_ID}" checked="yes" onclick="removeBrandChecked('{JS_BRAND_NAME}','{BRAND_ID}',1);" /><span><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/></span></p>
										</xsl:when>
										<xsl:otherwise>
											<p><input type="checkbox" value="{BRAND_ID}" name="branddetails[]" id="checkbox_brand_id_{BRAND_ID}" onclick="addBrandChecked('{JS_BRAND_NAME}','{BRAND_ID}');" /><span><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/></span></p>
										</xsl:otherwise>
										</xsl:choose>
										<div class="clear"></div>
									</xsl:if>
								</xsl:for-each>

								<div class="cmp-mor">	
									<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
										<xsl:if test="position() &gt;=15">
											<xsl:choose>
											<xsl:when test="SELECTED_BRAND_ID=BRAND_ID">
												<p><input type="checkbox" value="{BRAND_ID}" name="branddetails[]" id="checkbox_brand_id_{BRAND_ID}" checked="yes" onclick="removeBrandChecked('{JS_BRAND_NAME}','{BRAND_ID}',1);" /><span><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/></span></p>
											</xsl:when>
											<xsl:otherwise>
												<p><input type="checkbox" value="{BRAND_ID}" name="branddetails[]" id="checkbox_brand_id_{BRAND_ID}" onclick="addBrandChecked('{JS_BRAND_NAME}','{BRAND_ID}');" /><span><xsl:value-of select="BRAND_NAME" disable-output-escaping="yes"/></span></p>
											</xsl:otherwise>
											</xsl:choose>
											<div class="clear"></div>
										</xsl:if>
									</xsl:for-each>
								</div>


							</div>
							<a href="javascript:void(0)" class="mr-brand">More Brands <i class="more-ar"></i></a>
						 </section>
						 <xsl:for-each select="/XML/PIVOT_MASTER/PIVOT_MASTER_DATA">
						 	<xsl:if test="position() &lt;=6">
							<section class="filterinfo"> 
							<div class="cmp-ftr-ttlbx">
							<a class="coll fl" href="javascript:void(0)"></a>
							<h4 class="fl"><xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/></h4>
							<div class="clear"></div>
							</div>
							<div class="cmp-ftr-ddl">
							<xsl:variable name="featureType">
							<xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/>
							</xsl:variable>
							<xsl:for-each select="SUB_PIVOT_MASTER/SUB_PIVOT_MASTER_DATA">	
								<xsl:choose>
									<xsl:when test="SELECTED_FEATURE_ID=FEATURE_ID">
									<p><input type="{PIVOT_DISPLAY_TYPE}" value="{FEATURE_ID}" name="featuredetails[]" id="checkbox_feature_id_{FEATURE_ID}" checked="yes" onclick="removeFeatureChecked('{JS_FEATURE_NAME}','{$featureType}');" /><span><xsl:value-of select="FEATURE_DISPLAY_NAME" disable-output-escaping="yes"/></span></p>
									</xsl:when>
									<xsl:otherwise>
									<p><input type="{PIVOT_DISPLAY_TYPE}"  value="{FEATURE_ID}" name="featuredetails[]" id="checkbox_feature_id_{FEATURE_ID}" class="left" onclick="addFeatureChecked('{JS_FEATURE_NAME}','{$featureType}');" /><xsl:value-of select="FEATURE_DISPLAY_NAME" disable-output-escaping="yes"/></p>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:for-each>
							</div>
							</section>
						</xsl:if>
						</xsl:for-each>
						 <!--
                    	 	-->

						 <div class="clear"></div>
						 
						 	<div id="button-top" class="hide-mobile cur ">
								<div class="adva"><span class="moreat">Advanced Filters</span><i class="marrow-r"></i></div> 
                              <div class="clear"></div>								
							<div class="slide innerTop" >
								<xsl:for-each select="/XML/PIVOT_MASTER/PIVOT_MASTER_DATA">
								<xsl:if test="position() &gt;6">	
								<section class="filterinfo"> 
								<div class="cmp-ftr-ttlbx">
								<a class="coll fl" href="javascript:void(0)"></a>
								<h4 class="fl"><xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/></h4>
								<div class="clear"></div>
								</div>
								<div class="cmp-ftr-ddl">
								<xsl:variable name="featureType">
								<xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/>
								</xsl:variable>
								<xsl:for-each select="SUB_PIVOT_MASTER/SUB_PIVOT_MASTER_DATA">	
								<xsl:choose>
								<xsl:when test="SELECTED_FEATURE_ID=FEATURE_ID">
								<p><input type="{PIVOT_DISPLAY_TYPE}" value="{FEATURE_ID}" name="featuredetails[]" id="checkbox_feature_id_{FEATURE_ID}" checked="yes" onclick="removeFeatureChecked('{JS_FEATURE_NAME}','{$featureType}');" /><span><xsl:value-of select="FEATURE_DISPLAY_NAME" disable-output-escaping="yes"/></span></p>
								</xsl:when>
								<xsl:otherwise>
								<p><input type="{PIVOT_DISPLAY_TYPE}"  value="{FEATURE_ID}" name="featuredetails[]" id="checkbox_feature_id_{FEATURE_ID}" class="left" onclick="addFeatureChecked('{JS_FEATURE_NAME}','{$featureType}');" /><xsl:value-of select="FEATURE_DISPLAY_NAME" disable-output-escaping="yes"/></p>
								</xsl:otherwise>
								</xsl:choose>
								</xsl:for-each>
								</div>
								</section>
								</xsl:if>
								</xsl:for-each>			
							<div class="clear"></div>	
							</div>																												
	                    <div class="clear"></div>
						 </div>	


						  <div class="clear"></div> 
                    </div>
                </aside>
                	<input type="hidden" id="sortproduct" name="sortproduct" value="{/XML/SELECTED_SORT_PRODUCT_BY}" />
					<input type="hidden" name="catid" id="catid" value="{/XML/SITE_CATEGORY_ID}"/>
					<input type="hidden" name="{/XML/PAGE_OFFSET}" id="pageoffset" value=""/>
					<input type="hidden" id="mxprice" name="mxprice" value="{/XML/MAX_PRICE}" />
					<input type="hidden" id="mxpriceunit" name="mxpriceunit" value="{/XML/MAX_PRICE_UNIT}" />
					<input type="hidden" id="mnprice" name="mnprice" value="{/XML/MIN_PRICE}" />
					<input type="hidden" id="mnpriceunit" name="mnpriceunit" value="{/XML/MIN_PRICE_UNIT}" />
            </form>
                <section class="gdtresult-wrap col-xs-12 col-md-9">
                    <section class="your-selection">
					  <div class="ys-band">
							Your Selection <a href="javascript:void(0)" onclick="removeAllChecked('All','all','1');">Remove All Selections</a>
					  </div>
					  	<xsl:for-each select="/XML/SELECTEDBRANDS/SELECTEDBRANDVALUE">
					  	<div class="mob-brd">
						<xsl:value-of select="LABEL" disable-output-escaping="yes"/>:
							 <xsl:for-each select="SELECTEDBRANDVALUEDATA">
								<span class="mbr"><xsl:value-of select="LABELVALUE" disable-output-escaping="yes"/> <a href="javascript:void(0)" onclick="removeBrandChecked('{JSLABELVALUE}','{LABELNAME}','1');" class="mb-cl"></a></span>
							</xsl:for-each> 
						 	<!-- <span class="mbr">BlackBerry <a href="" class="mb-cl"></a></span> -->					  
					    </div>
						</xsl:for-each>
					  	<xsl:for-each select="/XML/SELECTEDFEATURES/SELECTEDFEATURESVALUE">
					  	<div class="mob-brd">
						<xsl:value-of select="LABEL" disable-output-escaping="yes"/>:
							 <xsl:for-each select="SELECTEDFEATURESVALUEDATA">
								<span class="mbr"><xsl:value-of select="LABELVALUE" disable-output-escaping="yes"/> <a href="javascript:void(0)" onclick="removeFeatureChecked('{JSLABELVALUE}','{LABELNAME}','1');" class="mb-cl"></a></span>
							</xsl:for-each> 
						 	<!-- <span class="mbr">BlackBerry <a href="" class="mb-cl"></a></span> -->					  
					    </div>
						</xsl:for-each>
					  <div class="clear"></div>
					</section>
					<div class="clear"></div>
					<div id="addtocompare" style="display:none;">
					Ad Compare
					</div>
					
					<p class="match"><xsl:value-of select="/XML/PRODUCT_MASTER/TOTAL_SEARCH_ITEM_FOUND" disable-output-escaping="yes"/> Matching Mobile Phones</p>
					<section class="mobile-ph">
					  <h3 class="fl">Mobile Phones</h3>
					  <aside class="fr">
					    Sort By: <span class="cur" onclick="showHideDiv('div1')"><div id="showsort">
					    	<xsl:choose>
					    	<xsl:when test="/XML/SELECTED_SORT_PRODUCT_BY='priceasc'">
					    			Price - Low to High
					    	</xsl:when>	
					    	<xsl:when test="/XML/SELECTED_SORT_PRODUCT_BY='pricedesc'">
					    			Price - High to Low
					    	</xsl:when>	
					    	<xsl:when test="/XML/SELECTED_SORT_PRODUCT_BY='bgrrating'">
					    			BGR Rating
					    	</xsl:when>	
					    	<xsl:otherwise>
					    			Newest First
					    	</xsl:otherwise>
					    	</xsl:choose>
					    	
						</div> <i class="low-p"></i></span>
						<div onclick="this.style.display='block';" class="drp-list" id="div1">
						  <a href="javascript:void(0)" onclick="getSortByResultList('latest')">Newest First</a>
						  <div class="clear"></div>
							<a href="javascript:void(0)" onclick="getSortByResultList('pricedesc')">Price - High to Low</a>
							<div class="clear"></div>
							<a href="javascript:void(0)" onclick="getSortByResultList('priceasc')">Price - Low to High</a>
							<div class="clear"></div>
							<a href="javascript:void(0)" onclick="getSortByResultList('bgrrating')">BGR Rating</a>
						</div>
					  </aside>
					  <div class="clear"></div>
					</section>
					<xsl:for-each select="/XML/PRODUCT_MASTER/PRODUCT_MASTER_DATA">
					<figure class="mobile-listing">
					   <div class="imgt">
					    <a href="{SEO_URL}">
							<img src="{IMAGE_PATH}" />
							<!-- <img src="{/XML/IMAGE_URL}img-3.jpg" /> -->
						</a>
						<div class="addcomp mt10"><input type="checkbox" name="" value="" class="addtocom" onclick="AddToCompareWidget('{PRODUCT_ID}','{DISPLAY_PRODUCT_NAME}','{/XML/IMAGE_URL}img-3.jpg','{COMPARENAME}','{/XML/SELECTED_CATEGORY_ID}')"/> Add to Compare</div>
						<div class="clear"></div>
						</div>
						<figcaption>
						   <aside class="col-sm-12 col-lg-7">
						   <h2><a href="{SEO_URL}"><xsl:value-of select="DISPLAY_PRODUCT_NAME" disable-output-escaping="yes"/></a></h2>
						    <ul>
						    	<xsl:for-each select="PRODUCT_FEATURE_MASTER_DATA/PRODUCT_FEATURE_SUMMERY_DATA">
								<li> <i class="three-d"></i> <xsl:value-of select="." disable-output-escaping="yes"/> </li>
							    </xsl:for-each>
							  <!-- <li> <i class="three-d"></i> screen size: <span>4.5 inches</span> </li>
							  <li> <i class="three-d"></i>Processor: <span>Quad Core 1.2 GHz</span></li>
							  <li> <i class="three-d"></i>Operating System: <span>Android V4.4.2 (Kitkat)</span></li>
							  <li> <i class="three-d"></i>Camera: <span>5 MP</span></li>
							  <li> <i class="three-d"></i>Battery:<span>2000 mAh, Li-ion</span></li>
							  <li> <i class="three-d"></i>Network:<span>3G</span></li> -->

							</ul>
							
						   </aside>
						   <aside class="col-sm-12 col-lg-2"><i class="rs"></i> <xsl:value-of select="EXSHOWROOMPRICE" disable-output-escaping="yes"/></aside>
						   <aside class="read-exp col-sm-12 col-lg-3">
						     <span class="avg_user_stars">
							     <span class="rating" style="width:50%"> </span>
								 <div class="clear"></div>
							 </span>
							  <a href="">Read Expert Review</a>
							 <div class="clear"></div>
						    <a href="">10 User Reviews</a>
						   </aside>
						</figcaption>
						 <div class="clear"></div>
					</figure>
					 <div class="clear"></div>
					</xsl:for-each>
					 
					<!-- 					 -->
					
					<!-- <a href="" class="next-pre">Last</a><a href="" class="next-pre">Next</a> -->
					<div class="clear"></div>

					<nav class="gadget-pagination">
						<span class="mobile10"><xsl:value-of select="/XML/SHOWSTART" disable-output-escaping="yes" /> - <xsl:value-of select="/XML/SHOWEND" disable-output-escaping="yes" /> Mobiles</span> 
						<xsl:value-of select="/XML/PAGING" disable-output-escaping="yes" />
					</nav>
                </section>
				
				
				
				   <div class="clear"></div>	
				   <xsl:call-template name="topComparison"/>
				<!-- <section class="topcaompare-slider">
			  <div class="gadband">
					<h2>Top Comparisons</h2>
			  </div>
			  <section class="topcaompage-slider">
			   <ul class="topcaompare">
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
					</li>					
			   </ul>
			   <div class="clear"></div>
			   </section>	
			</section> -->
				<div class="clear mb20"></div>
				
				
            </section>
        </div>
	</section>
	<xsl:call-template name="footerDiv"/>
<!-- 	<footer>
	   <section class="container">
			<div class="flink">Apple    Android    BlackBerry    Business    Exclusives    Mobile    Reviews</div>
			<div class="ftext">* Prices mentioned in this buying guide are indicative and have been sourced from multiple sources.
			Buyers are advised to check with their local retailer as prices are bound to vary between dealers and cities.
			We are not responsible for variations of any kind. Buyers are also advised to cross-check the specifications of products mentioned here.</div>
			Copyright 2014 gadget.india.com
			</section>
	</footer> -->
		<script>
				var seo_web_url = '<xsl:value-of select="/XML/SEO_WEB_URL" />';
				var web_url = '<xsl:value-of select="/XML/WEB_URL" />';
				var urlArr = Array();
				var submiturl = '1';
				var seo_car_finder = '<xsl:value-of select="/XML/SEO_CAR_FINDER" disable-output-escaping="yes"/>';
				var comparedIds = '';
				var web_url = '<xsl:value-of select="/XML/WEB_URL" disable-output-escaping="yes"/>';
				var catid = '<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" disable-output-escaping="yes"/>';
				var cat_seo_path = '<xsl:value-of select="/XML/SELECTED_CATEGORY_PATH" disable-output-escaping="yes"/>';
				var currenttab = '<xsl:value-of select="/XML/SELECTEDTABID" disable-output-escaping="yes"/>';
				var pricestr = '<xsl:value-of select="/XML/SEO_PRICE_STR" disable-output-escaping="yes"/>';
				var page_name = 'car_finder';
				
		</script>	
		<script src="{/XML/JS_URL}jquery-1.8.3.min.js"></script>	
		<script src="{/XML/JS_URL}gadget.js"></script>
		<script>
	var getshare = '1';
	var brandArr = Array();
	<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
		<xsl:if test="SELECTED_BRAND_ID=BRAND_ID">
			removeBrandChecked('<xsl:value-of select="JS_BRAND_NAME" disable-output-escaping="yes"/>','<xsl:value-of select="BRAND_ID" disable-output-escaping="yes"/>');
			addBrandChecked('<xsl:value-of select="JS_BRAND_NAME" disable-output-escaping="yes"/>','<xsl:value-of select="BRAND_ID" disable-output-escaping="yes"/>');
		</xsl:if>
	</xsl:for-each>
	var phoneTypeArr = Array();
	var OperatingSystemArr = Array();
	var FormFactorArr  = Array();
	var AvailabilityArr  = Array();
	var FormFactorArr  = Array();
	var InputMechanismArr  = Array();
	var RAMArr  = Array();
	var FeaturesArr  = Array();
	var NetworkTypeArr  = Array();
	var NoSimArr  = Array();
	var NetworkArr  = Array();
	var PrimaryCameraArr  = Array();
	var ProcessorArr  = Array();
	var ScreenSizeArr  = Array();
	var AnnouncedArr  = Array();
	<xsl:for-each select="/XML/PIVOT_MASTER/PIVOT_MASTER_DATA">
	    <xsl:variable name="featureType">
	            <xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/>
	    </xsl:variable>
	    <xsl:for-each select="SUB_PIVOT_MASTER/SUB_PIVOT_MASTER_DATA">
	            <xsl:if test="SELECTED_FEATURE_ID=FEATURE_ID">
					removeFeatureChecked('<xsl:value-of select="JS_FEATURE_NAME" disable-output-escaping="yes"/>','<xsl:value-of select="$featureType" disable-output-escaping="yes"/>');
					addFeatureChecked('<xsl:value-of select="JS_FEATURE_NAME" disable-output-escaping="yes"/>','<xsl:value-of select="$featureType" disable-output-escaping="yes"/>');
	            </xsl:if>
	    </xsl:for-each>
	</xsl:for-each>
	AddToCompareWidget('','','','',catid);
	</script>
	</body>
</html>
</xsl:template>
</xsl:stylesheet>