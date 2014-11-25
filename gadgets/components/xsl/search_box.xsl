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
    <xsl:template name="searchBox">
        <section class="upcoming-mp" >
            <div class="ttlbx">
                <h2 class="hdh2">Let's Find A Mobile For You!</h2>
                <div class="clear"></div>
            </div>
            <form class="frmfindmob">
                <aside class="sltbxwrp col-xs-12 col-sm-3">
                    <select class="sltbx col-xs-12" id="sb_bid">
                        <option value="">-Select Brand-</option>
                        <xsl:for-each select="/XML/COMPONENTS_XML/BRAND_MASTER/BRAND_MASTER_DATA">
                            <option value="{BRAND_NAME}">
                                <xsl:value-of select="BRAND_NAME" disable-output-escaping="yes" />
                            </option>
                        </xsl:for-each>
                    </select>
                    <select class="sltbx col-xs-12"  id="sb_ptid">
                        <option value="">-Select <xsl:value-of select="/XML/COMPONENTS_XML/PIVOT_MASTER/PIVOT_MASTER_DATA/SUB_GROUP_NAME" disable-output-escaping="yes" />-</option>
                        <xsl:for-each select="/XML/COMPONENTS_XML/PIVOT_MASTER/PIVOT_MASTER_DATA/SUB_PIVOT_MASTER/SUB_PIVOT_MASTER_DATA">
                            <option value="{FEATURE_NAME}">
                                <xsl:value-of select="FEATURE_DISPLAY_NAME" disable-output-escaping="yes" />
                            </option>
                        </xsl:for-each>
                    </select>
                </aside>
                <aside class="sltrng col-xs-12 col-sm-6">
                    <!-- 2. Write markup for the slider -->
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
                        <div class="min">
                            <input type="text" class="leftLabel" onKeyPress="searchKeyPress(event);" onBlur="updatefilter()" />
                        </div>
                        <div class="fl ds"> - </div>
                        <div class="max">
                            <input type="text" class="rightLabel" onKeyPress="searchKeyPress(event);" onBlur="updatefilter()" />
                        </div>
                        <div class="clear"></div>
                    </section>
                </aside>
                <aside class="fndmob col-xs-12 col-sm-3">
                    <button type="submit" class="hpfindmob">Find Mobile</button>
                    <a href="{/XML/SEO_WEB_URL}/{/XML/COMPONENTS_XML/CAT_PATH}/{/XML/PHONE_FINDER}" class="ireadmore">More Options <i></i></a>
                </aside>
                <div class="clear"></div>
            </form>
        </section>
    </xsl:template>
</xsl:stylesheet>
