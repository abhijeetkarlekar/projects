<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet  [
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
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" />
<xsl:template name="headDiv">
<script>	var siteURL = '<xsl:value-of select="/XML/WEB_URL" disable-output-escaping="yes"/>';</script>
<div class="banner-hide">
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
						<a href="{/XML/WEB_URL}{/XML/CAT_PATH}/" class="logo"><img title="" alt="" src="{/XML/IMAGE_URL}gad-logo.png"/></a>
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
					<li ><a href="{/XML/WEB_URL}{/XML/CAT_PATH}/{/XML/TOP_MOBILES}">TOP MOBILES</a></li>
					<li ><a href="{/XML/WEB_URL}{/XML/CAT_PATH}/{/XML/BRANDS}">BRANDS </a></li>
					<li><a href="{/XML/WEB_URL}{/XML/CAT_PATH}/{/XML/PHONE_FINDER}">PHONE FINDER</a>
					 <!-- <ul role="menu" class="dropdown-menu">
						<li><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li><a href="#">5</a></li>
					  </ul> -->
					
					</li>
					<li><a href="{/XML/WEB_URL}{/XML/CAT_PATH}/{/XML/PHONE_COMPARE}">COMPARE</a></li>
					<li><a href="{/XML/WEB_URL}{/XML/CAT_PATH}/{/XML/USER_REVIEWS}">USER REVIEWS</a></li>
					<li><a href="{/XML/BGR_NEWS_URL}">NEWS</a></li>
				</ul>
				</div> 
                 <aside class="searchbox">
					<div class="login"><!-- <i class="arc"></i>Log In --></div>
					<div class="searchin cur">
						<a href="javascript:void(0)" class="icon-src"></a>
			      <div class="serc-box">
							<!-- <input type="text" placeholder="Search" class="inp"/>
							<input type="submit" value="Search" class="sub"/> -->
							<form action=""><input type="text" name="word" id="word" class="inp"/>
								<input type="submit" value="Search" class="sub"/>
							</form>
							<div id="auto"></div>
						</div>
					</div>
				 
				 </aside> 				
              </nav>
        </aside> 
        <div class="clear"></div>
    </div>
 </section>
</header>
</xsl:template>
</xsl:stylesheet>
