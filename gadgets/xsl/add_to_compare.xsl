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
<xsl:output method="html" encoding="utf-8"/>
<xsl:template match="/">
	<section class="clear-list" >
	<xsl:for-each select="/XML/COMPAREPRODUCT_MASTER/COMPAREPRODUCT_MASTER_DATA">						
		<figure class="com-box">
		<img src="{IMAGE_PATH}" />
		<figcaption>
		<a href="" class="mb-cl"></a>
		<xsl:value-of select="DISPLAY_PRODUCT_NAME" disable-output-escaping="yes"/> 
		</figcaption>
		<div class="clear"></div>
		</figure>
	</xsl:for-each>
	<aside class="complist">
	<a href="">Clear list</a>
	<a href="{/XML/COMPAREPRODUCT_MASTER/COMPAREPRODUCT_URL_LINK}" class="btn-1">Compare</a>
	</aside>
	<div class="clear"></div>
</section>
</xsl:template>
</xsl:stylesheet>