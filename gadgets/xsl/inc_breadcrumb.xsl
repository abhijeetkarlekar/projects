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
<!-- <xsl:include href="inc_top_ad.xsl" /> -->
 
<xsl:template name="breadcrumb">
<!-- <xsl:call-template name="TopAd"/> -->
<xsl:if test="/XML/BREAD_CRUMB!=''">
 <xsl:value-of select="/XML/BREAD_CRUMB" disable-output-escaping="yes"/>
</xsl:if>

</xsl:template>
</xsl:stylesheet>
