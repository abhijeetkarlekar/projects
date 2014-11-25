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
<xsl:template name="footerDiv">
<footer>
		<script type="text/javascript">
		/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
		var disqus_shortname = 'newgadgetsin'; // required: replace example with your forum shortname

		/* * * DON'T EDIT BELOW THIS LINE * * */
		(function () {
		var s = document.createElement('script'); s.async = true;
		s.type = 'text/javascript';
		s.src = '//' + disqus_shortname + '.disqus.com/count.js';
		(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
		}());
		</script>	
	   <section class="container">
			<div class="flink">Apple    Android    BlackBerry    Business    Exclusives    Mobile    Reviews</div>
			<div class="ftext">* Prices mentioned in this buying guide are indicative and have been sourced from multiple sources.
			Buyers are advised to check with their local retailer as prices are bound to vary between dealers and cities.
			We are not responsible for variations of any kind. Buyers are also advised to cross-check the specifications of products mentioned here.</div>
			Copyright 2014 gadget.india.com
			</section>
	</footer></xsl:template>
</xsl:stylesheet>
