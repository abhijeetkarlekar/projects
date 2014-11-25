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
<xsl:include href="../components/xsl/top_comparisons.xsl"/>
<xsl:include href="../components/xsl/news_list.xsl"/>
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
                <div class="clear"></div>
			 </section>	 

			<xsl:choose>
			<xsl:when test="/XML/DISPLAY_HEADING != ''">
				<h1 class="h1g"><xsl:value-of select="/XML/DISPLAY_HEADING"/></h1>
			</xsl:when>
			<xsl:otherwise>
				<h1 class="h1g">Search Compare Mobile Phones Processor </h1>
			</xsl:otherwise>
			</xsl:choose>


            
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
			<div class="clear"></div>
		 </aside>
		 <div class="clear"></div>
		 <section class="compare-mobile">
		     <div class="gadgetband">
				<h2>Add Mobiles for comparison</h2>
				<a href="javascript:void(0)" class="clearall" onclick="javascript: confirmClearAll();">Clear all<i class="clearall-icon"></i></a>
			 </div>
			  <section class="compare-mobile-inner">
		    <table class="table" cellspacing="0" cellpadding="0" border="0" width="100%">
				<thead>
					<tr>
					  <td class="bdree">
					     <div class="cmp-with">
						      <span>
								Compare with
								<div class="clear"></div>
								 <xsl:if test="/XML/FIRST_COMPARE_PRODUCT_ID!=''">
										<a href="javascript:void(0);" id="competitorslink" style="display:none;">Top competitors</a>
								</xsl:if>
							  </span>
							  <i class="arl"></i>
							  <div class="clear"></div>
						 </div>
						 <div class="clear"></div>
						   <form class="cmp-frm-ftrs">
								 <fieldset>
								   <input id="hide_common" type="checkbox" onclick="HideCommonFeature();"/><label>Hide common features</label>
								 </fieldset>
							</form>
					        
					  </td>
					  <td class="bdree">

						<aside class="combox">
							<xsl:choose>
	                    		<xsl:when test="/XML/FIRST_COMPARE_PRODUCT_ID!=''">
	                    				<img class="imgstyle" src="{/XML/FIRST_COMPARE_PRODUCT_IMG}" alt="{/XML/FIRST_COMPARE_PRODUCT_NAME}" title="{/XML/FIRST_COMPARE_PRODUCT_NAME}"/>
	                    				<h3><xsl:value-of select="/XML/FIRST_COMPARE_PRODUCT_NAME" disable-output-escaping="yes"/></h3>
	                    				<div class="rs-info"><i class="rs"></i> <xsl:value-of select="/XML/FIRST_COMPARE_PRODUCT_PRICE" disable-output-escaping="yes"/></div>
	                    		</xsl:when>
	                    		<xsl:otherwise>	
	                    				<h4 class="fwb mb5">First Mobile</h4>
										 <div class="clear"></div>
										<select class="sel-brand" name="Brand_1" id="Brand_1" onchange="getCompareModelByBrand('1','ajax/get_modelvariant_detail.php','Model_1','')">
				                      		<option value="">-Select brand-</option>
				                      		<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=1">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
											<option value="-1" disabled="yes"> ------------------------------- </option>
											<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=0">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
				                      	</select>
										 <div class="clear"></div>
										<select class="sel-model" name="Model_1" id="Model_1">
                      						<option value="">-Select model-</option></select>
										 <div class="clear"></div>
										  <a href="javascript:void(0);"  onclick="addNewCompareProduct('1');" class="btn-1">Add to Compare</a>
	                    		</xsl:otherwise>
                			</xsl:choose>	
							<!-- <img class="imgstyle" src="{/XML/IMAGE_URL}img-5.jpg" />
							<h3>BlackBerry Z3</h3>
							<div class="rs-info"><i class="rs"></i> 22,990</div> -->

						</aside>

					  </td>
					  <td class="bdree">
						<aside class="combox">
							<xsl:choose>
	                    		<xsl:when test="/XML/SECOND_COMPARE_PRODUCT_ID!=''">
	                    				<img class="imgstyle" src="{/XML/SECOND_COMPARE_PRODUCT_IMG}" alt="{/XML/SECOND_COMPARE_PRODUCT_NAME}" title="{/XML/SECOND_COMPARE_PRODUCT_NAME}"/>
	                    				<h3><xsl:value-of select="/XML/SECOND_COMPARE_PRODUCT_NAME" disable-output-escaping="yes"/></h3>
	                    				<div class="rs-info"><i class="rs"></i> <xsl:value-of select="/XML/SECOND_COMPARE_PRODUCT_PRICE" disable-output-escaping="yes"/></div>
	                    		</xsl:when>
	                    		<xsl:otherwise>	
	                    				<h4 class="fwb mb5">Second Mobile</h4>
										 <div class="clear"></div>
										<select class="sel-brand" name="Brand_2" id="Brand_2" onchange="getCompareModelByBrand('2','ajax/get_modelvariant_detail.php','Model_2','')">
				                      		<option value="">-Select brand-</option>
				                      		<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=1">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
											<option value="-1" disabled="yes"> ------------------------------- </option>
											<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=0">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
				                      	</select>
										 <div class="clear"></div>
										<select class="sel-model" name="Model_2" id="Model_2">
                      						<option value="">-Select model-</option></select>
										 <div class="clear"></div>
										  <a href="javascript:void(0);"  onclick="addNewCompareProduct('2');" class="btn-1">Add to Compare</a>
	                    		</xsl:otherwise>
                			</xsl:choose>	
						</aside>
					  </td>
					  <td class="bdree">
					  <aside class="combox">
							<xsl:choose>
	                    		<xsl:when test="/XML/THIRD_COMPARE_PRODUCT_ID!=''">
	                    				<img class="imgstyle" src="{/XML/THIRD_COMPARE_PRODUCT_IMG}" alt="{/XML/THIRD_COMPARE_PRODUCT_NAME}" title="{/XML/THIRD_COMPARE_PRODUCT_NAME}"/>
	                    				<h3><xsl:value-of select="/XML/THIRD_COMPARE_PRODUCT_NAME" disable-output-escaping="yes"/></h3>
	                    				<div class="rs-info"><i class="rs"></i> <xsl:value-of select="/XML/THIRD_COMPARE_PRODUCT_PRICE" disable-output-escaping="yes"/></div>
	                    		</xsl:when>
	                    		<xsl:otherwise>	
	                    				<h4 class="fwb mb5">Third Mobile</h4>
										 <div class="clear"></div>
										<select class="sel-brand" name="Brand_3" id="Brand_3" onchange="getCompareModelByBrand('3','ajax/get_modelvariant_detail.php','Model_3','')">
				                      		<option value="">-Select brand-</option>
				                      		<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=1">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
											<option value="-1" disabled="yes"> ------------------------------- </option>
											<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=0">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
				                      	</select>
										 <div class="clear"></div>
										<select class="sel-model" name="Model_3" id="Model_3" >
                      						<option value="">-Select model-</option></select>
										 <div class="clear"></div>
										  <a href="javascript:void(0);"  onclick="addNewCompareProduct('3');" class="btn-1">Add to Compare</a>
	                    		</xsl:otherwise>
                			</xsl:choose>	
						</aside>
					  </td>
					  <td>
						<xsl:choose>
	                    		<xsl:when test="/XML/FOURTH_COMPARE_PRODUCT_ID!=''">
	                    				<img class="imgstyle" src="{/XML/FOURTH_COMPARE_PRODUCT_IMG}" alt="{/XML/FOURTH_COMPARE_PRODUCT_NAME}" title="{/XML/FOURTH_COMPARE_PRODUCT_NAME}"/>
	                    				<h3><xsl:value-of select="/XML/FOURTH_COMPARE_PRODUCT_NAME" disable-output-escaping="yes"/></h3>
	                    				<div class="rs-info"><i class="rs"></i> <xsl:value-of select="/XML/FOURTH_COMPARE_PRODUCT_PRICE" disable-output-escaping="yes"/></div>
	                    		</xsl:when>
	                    		<xsl:otherwise>	
	                    				<h4 class="fwb mb5">Fourth Mobile</h4>
										 <div class="clear"></div>
										<select class="sel-brand" name="Brand_4" id="Brand_4" onchange="getCompareModelByBrand('4','ajax/get_modelvariant_detail.php','Model_4','')">
				                      		<option value="">-Select brand-</option>
				                      		<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=1">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
											<option value="-1" disabled="yes"> ------------------------------- </option>
											<xsl:for-each select="/XML/BRAND_MASTER/BRAND_MASTER_DATA">
												<xsl:if test="TOP_BRAND=0">
													<option value="{BRAND_ID}"> <xsl:value-of select="BRAND_NAME"/> </option>
												</xsl:if>
											</xsl:for-each>
				                      	</select>
										 <div class="clear"></div>
										<select class="sel-model" name="Model_4" id="Model_4" >
                      						<option value="">-Select model-</option></select>
										 <div class="clear"></div>
										  <a href="javascript:void(0);"  onclick="addNewCompareProduct('4');" class="btn-1">Add to Compare</a>
	                    		</xsl:otherwise>
                			</xsl:choose>	
						<!-- <h4 class="fwb mb5">Fourth Mobile</h4>
						 <div class="clear"></div>
						<select class="sel-brand">
						  <option value="">- Select Brand -</option>
						  <option value="">Saab</option>
						  <option value="">Opel</option>
						  <option value="">Audi</option>
						</select>
						 <div class="clear"></div>
						<select class="sel-model">
						  <option value="">- Select Model -</option>
						  <option value="">Saab</option>
						  <option value="">Opel</option>
						  <option value="">Audi</option>
						</select>
						 <div class="clear"></div>
						  <a href="" class="btn-1">Add to Compare</a> -->	

					  </td>

					</tr>
				</thead>
				<tbody>
					<tr class="cmp-ftr-ttlbx cur">
					  <td colspan="5">
                          <section class="bgea p5">
								<a class="coll fr" href="javascript:void(0)"></a><span class="fr mr5">Collapse</span>
								<h4 class="fl tu">Ratings</h4>
								<div class="clear"></div>	
						 </section>
					  </td>
					</tr>
					<tr class="cmp-ftr-ddl"> 
					  <td  colspan="5">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
							  <!-- <tr class="bgfb">
							   <td>BGR Rating</td>
							   <td>
									<span class="avg_user_stars">
										 <span style="width:50%" class="rating"> </span>
										 <div class="clear"></div>
									</span>
							   </td>
							   <td>
									<span class="avg_user_stars">
										 <span style="width:50%" class="rating"> </span>
										 <div class="clear"></div>
									</span>									
							   </td>
							   <td>
									<span class="avg_user_stars">
										 <span style="width:50%" class="rating"> </span>
										 <div class="clear"></div>
									</span>							   
							   </td>
							   <td>2</td>
						     </tr> -->
							 <tr>
							   <td>User Rating</td>
							   <td>
									<span class="avg_user_stars">
										 <span style="width:50%" class="rating"> </span>
										 <div class="clear"></div>
									</span>
							   </td>
							   <td>
									<span class="avg_user_stars">
										 <span style="width:50%" class="rating"> </span>
										 <div class="clear"></div>
									</span>									
							   </td>
							   <td>
									<span class="avg_user_stars">
										 <span style="width:50%" class="rating"> </span>
										 <div class="clear"></div>
									</span>							   
							   </td>
							   <td>2</td>
						     </tr>
						 </table>
					  </td>
					  <div class="clear"></div>
					</tr>
					<xsl:for-each select="/XML/GROUP_MASTER/GROUP_MASTER_DATA">
						<xsl:if test="/XML/COMPARE_PRODUCT_SET!=''">
							<xsl:for-each select="SUB_GROUP_MASTER">
								<xsl:if test="PIVOT_FEATURE_ID!=FEATURE_ID">
									<tr class="cmp-ftr-ttlbx cur">
									  <td colspan="5">
				                          <section class="bgea p5">
												<a class="coll fr" href="javascript:void(0)"></a><span class="fr mr5">Collapse</span>
												<h4 class="fl tu"><xsl:value-of select="SUB_GROUP_NAME" disable-output-escaping="yes"/></h4>
												<div class="clear"></div>	
										 </section>
									  </td>
									</tr>
									
                    <tr class="cmp-ftr-ddl"> 
                    
       					  

					  <td  colspan="5">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        	<xsl:for-each select="SUB_GROUP_MASTER_DATA">
                        	  <xsl:variable name="featurerowStyle">
								<xsl:choose>
									<xsl:when test="position() mod 2 = 0">
										cmp-ftr-rw cmp-ftr-gry
									</xsl:when>
									<xsl:otherwise>
										cmp-ftr-rw
									</xsl:otherwise>
								</xsl:choose>
							</xsl:variable>
							<xsl:variable name="hidefeaturerowStyle">
								<xsl:choose>
									<xsl:when test="SAME_FEATURE_VALUE = 1">
										hidedata
									</xsl:when>
									<xsl:otherwise>
										showdata
									</xsl:otherwise>
								</xsl:choose>
							</xsl:variable>	
							<tr class="{$hidefeaturerowStyle}">

							<td>
								<xsl:value-of select="FEATURE_NAME" disable-output-esacaping="yes"/>						
								<!-- <xsl:if test="FEATURE_DESCRIPTION!=''">
								<a href="javascript:void(0)" onmouseover="showHideDiv('Air_{FEATURE_ID}')" onmouseout="Javascript:document.getElementById('Air_{FEATURE_ID}').style.display='none';" class="f11"> [?]</a>
								</xsl:if>
								<xsl:if test="FEATURE_DESCRIPTION!=''">
								<div class="MV-tooltip tooltipR compare-car-details-tp" id="Air_{FEATURE_ID}"  onmouseover="this.style.display='block';" onmouseout="this.style.display='none';">
								<i class="tooltipD sprit-icon posA"></i>
								<xsl:value-of select="FEATURE_DESCRIPTION" disable-output-escaping="yes"/>
								</div>							  
								</xsl:if> -->
							</td>
							<input type="hidden" name="samevalue_{FEATURE_ID}" id="samevalue_{FEATURE_ID}" value="{SAME_FEATURE_VALUE}"/>	
							<xsl:for-each select="PRODUCT_FEATURE_MASTER/PRODUCT_FEATURE_MASTER_DATA">
								<td>
									<xsl:choose>
										<xsl:when test="PRODUCT_FEATURE_VALUE='yes'">
											<i class="sprit-icon cmp-ft-yes"></i>
										</xsl:when>
										<xsl:otherwise>
										<xsl:choose>
										<xsl:when test="PRODUCT_FEATURE_VALUE='no'">
											<i class="sprit-icon cmp-ft-no"></i>
										</xsl:when>
										<xsl:otherwise>
											<xsl:value-of select="PRODUCT_FEATURE_VALUE" disable-output-esacaping="yes"/>
										</xsl:otherwise>
										</xsl:choose>
										</xsl:otherwise>
									</xsl:choose>
								</td>
							</xsl:for-each>
					     </tr>
</xsl:for-each>
						</table>
					  </td>
					  <div class="clear"></div>
					  
					</tr>
					
					</xsl:if>
							</xsl:for-each>
						</xsl:if>
					</xsl:for-each>
					<tr>
						<td colspan="5"><div class="ads728"><img src="{/XML/IMAGE_URL}ads728.jpg" /></div></td>
					</tr>
					
				</tbody>			
			</table>
			 <div class="clear"></div>
		 </section>
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
			<div class="clear"></div>
		 </section>	
			<div class="clear mt20"></div>
			<xsl:call-template name="newsList"/>
			<aside class="container-right col-sm-4">
			   <div class="ads300">
				 <img src="{/XML/IMAGE_URL}300x250.jpg" /> 
			   </div>
			   	<div class="ads300">
				 <img src="{/XML/IMAGE_URL}ad300-100.jpg" /> 
			   </div>
			 
			</aside>		 
		</section>
	</section>
	<xsl:call-template name="footerDiv"/>

		<script src="{/XML/JS_URL}jquery-1.8.3.min.js"></script>	
		<script src="{/XML/JS_URL}gadget.js"></script>
		<script>
		    var getshare = '1';
		    var siteURL = '<xsl:value-of select="/XML/WEB_URL" disable-output-escaping="yes"/>';
		
		var comparedIds = '<xsl:value-of select="/XML/COMPARE_PRODUCT_SET" disable-output-escaping="yes"/>';
		var comparename = '<xsl:value-of select="/XML/COMPARE_MODEL_NAME_SET" disable-output-escaping="yes"/>';
		var web_url =  '<xsl:value-of select="/XML/WEB_URL" disable-output-escaping="yes"/>';
		var catid = '<xsl:value-of select="/XML/SELECTED_CATEGORY_ID" disable-output-escaping="yes"/>';
		var catpath = '<xsl:value-of select="/XML/CAT_PATH" disable-output-escaping="yes"/>';
		var currenttab = '<xsl:value-of select="/XML/SELECTEDTABID" disable-output-escaping="yes"/>';
		var first_prd = '<xsl:value-of select="/XML/FIRST_COMPARE_PRODUCT_ID" disable-output-escaping="yes"/>';
		var first_brand = '<xsl:value-of select="/XML/FIRST_COMPARE_BRAND_ID" disable-output-escaping="yes"/>';
		var first_model = '<xsl:value-of select="/XML/FIRST_COMPARE_MODEL_ID" disable-output-escaping="yes"/>';
		var second_prd = '<xsl:value-of select="/XML/SECOND_COMPARE_PRODUCT_ID" disable-output-escaping="yes"/>';
		var third_prd = '<xsl:value-of select="/XML/THIRD_COMPARE_PRODUCT_ID" disable-output-escaping="yes"/>';
		var fourth_prd = '<xsl:value-of select="/XML/FOURTH_COMPARE_PRODUCT_ID" disable-output-escaping="yes"/>';
		var recent_view_car = '<xsl:value-of select="/XML/RECENT_VIEW_CAR" disable-output-escaping="yes"/>';
		var page_name = 'compare';
		compareIdsArr = comparedIds.split(",");
		function getCompare(id,pidpos){
				var newid = $('#prodid_'+pidpos).val();
				var product = constructURL($('#prodid_'+(i+1)+' option:selected').text());
				var compareIdsReplaceArr = replace(compareIdsArr,id,product);
				compareIdsReplaceArr = compareIdsReplaceArr.join(',');
				compareIdsReplaceArr = getCompareParamsUsingIds(compareIdsReplaceArr);
				var newurl = web_url+catpath+"/compare/"+compareIdsReplaceArr;
				//alert(newurl);
				location.href= newurl;
		}

		<xsl:text disable-output-escaping="yes">
		<![CDATA[
		function getCompareParamsUsingIds(ids){
			var idsArr = ids.split(',');
			var cnt = idsArr.length;
			var nameArr = comparename.split('|');
			var seoArr = Array();
			for(i=0;i<cnt;i++){
				var product = constructURL($('#prodid_'+(i+1)+' option:selected').text());
				var seo_str = Array(nameArr[i],product);
				seo_str = seo_str.join('-');
				seoArr.push(seo_str);
			}
			return seo_str = seoArr.join('-Vs-');
		}
		function replace(prdarray,replaceTo, replaceWith)
		{
		  for(var i=0; i<prdarray.length;i++ )
		  {
		  	if(prdarray[i]==replaceTo){
		  		prdarray.splice(i,1,replaceWith);
		  	}
		  }
		  return prdarray; 
		}
		]]>
		</xsl:text>
		function HideCommonFeature(){
			if(compareIdsArr.length>1){	
				if(document.getElementById('hide_common').checked==true){
					$('.hidedata').hide();		
				}else{
					$('.hidedata').show();		
				}
			}
		}
		function confirmClearAll(){
		if( confirm('Are you sure you want to reset the selected mobiles?')==true){
			window.location.href = web_url+catpath+'/compare'; 
		}
	}
	</script>
	</body>
</html>

</xsl:template>
</xsl:stylesheet>