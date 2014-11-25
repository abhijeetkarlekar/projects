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
    <xsl:param name="gallery_product_id" />
    <xsl:template name="ModelMenuTab">
            <ul class="nav nav-tabs nav-details">
              <xsl:choose>
                    <xsl:when test="/XML/CURRTAB_SEL=1">
                        <li class="active"><a href="javascript:void(0)">Summary</a></li>
                        <li><a href="{/XML/MODELNEWS_SEO_URL}">News</a></li>
                        <li><a href="{/XML/MODELREVIEWS_SEO_URL}">Reviews</a></li>
                        <li><a href="{/XML/MODELPHOTOS_SEO_URL}">Photos</a></li>
                        <li><a href="{/XML/MODELVIDEOS_SEO_URL}">Videos</a></li>
                        <li><a href="{/XML/COMPARE_TAB_URL}">Compare</a></li>
                    </xsl:when>
                     <xsl:when test="/XML/CURRTAB_SEL=2">
                        <li><a href="{/XML/MODEL_SEO_URL}">Summary</a></li>
                        <li class="active"><a href="javascript:void(0)">News</a></li>
                        <li><a href="{/XML/MODELREVIEWS_SEO_URL}">Reviews</a></li>
                        <li><a href="{/XML/MODELPHOTOS_SEO_URL}">Photos</a></li>
                        <li><a href="{/XML/MODELVIDEOS_SEO_URL}">Videos</a></li>
                        <li><a href="{/XML/COMPARE_TAB_URL}">Compare</a></li> 
                    </xsl:when>
                     <xsl:when test="/XML/CURRTAB_SEL=3">
                        <li><a href="{/XML/MODEL_SEO_URL}">Summary</a></li>
                        <li><a href="{/XML/MODELNEWS_SEO_URL}">News</a></li>
                        <li class="active"><a href="javascript:void(0)">Reviews</a></li>
                        <li><a href="{/XML/MODELPHOTOS_SEO_URL}">Photos</a></li>
                        <li><a href="{/XML/MODELVIDEOS_SEO_URL}">Videos</a></li>
                        <li><a href="{/XML/COMPARE_TAB_URL}">Compare</a></li>  
                    </xsl:when>
                     <xsl:when test="/XML/CURRTAB_SEL=4">
                        <li><a href="{/XML/MODEL_SEO_URL}">Summary</a></li>
                        <li><a href="{/XML/MODELNEWS_SEO_URL}">News</a></li>
                        <li><a href="{/XML/MODELREVIEWS_SEO_URL}">Reviews</a></li>
                        <li class="active"><a href="javascript:void(0)">Photos</a></li>
                        <li><a href="{/XML/MODELVIDEOS_SEO_URL}">Videos</a></li>
                        <li><a href="{/XML/COMPARE_TAB_URL}">Compare</a></li>  
                    </xsl:when>
                    <xsl:when test="/XML/CURRTAB_SEL=5">
                        <li><a href="{/XML/MODEL_SEO_URL}">Summary</a></li>
                        <li><a href="{/XML/MODELNEWS_SEO_URL}">News</a></li>
                        <li><a href="{/XML/MODELREVIEWS_SEO_URL}">Reviews</a></li>
                        <li><a href="{/XML/MODELPHOTOS_SEO_URL}">Photos</a></li>
                        <li class="active"><a href="javascript:void(0)">Videos</a></li>
                        <li><a href="{/XML/COMPARE_TAB_URL}">Compare</a></li>  
                    </xsl:when>
                    <xsl:otherwise>
                        <li class="active"><a href="javascript:void(0)">Summary</a></li>
                        <li><a href="{/XML/MODELNEWS_SEO_URL}">News</a></li>
                        <li><a href="{/XML/MODELREVIEWS_SEO_URL}">Reviews</a></li>
                        <li><a href="{/XML/MODELPHOTOS_SEO_URL}">Photos</a></li>
                        <li><a href="{/XML/MODELVIDEOS_SEO_URL}">Videos</a></li>
                        <li><a href="{/XML/COMPARE_TAB_URL}">Compare</a></li>
                    </xsl:otherwise>                        
              </xsl:choose>  

           </ul>
    </xsl:template>
</xsl:stylesheet>