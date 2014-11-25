<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet  [
    <!ENTITY nbsp   "&#160;">
    <!ENTITY copy   "&#169;">
    <!ENTITY reg    "&#174;">
    <!ENTITY trade  "&#8482;">
    <!ENTITY mdash  "&#8212;">
    <!ENTITY ldquo  "&#8220;">
    <!ENTITY rdquo  "&#8221;">
    <!ENTITY raquo  "&#187;">
    <!ENTITY pound  "&#163;">
    <!ENTITY yen    "&#165;">
    <!ENTITY euro   "&#8364;">
]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="iso-8859-1" />
    <xsl:template name="incFooter">
        <div id="site_info">
        <p align="right">
            Copyright <a href="#">Gadgets Admin</a>. All Rights Reserved.
        </p>
    </div>
    </xsl:template>
</xsl:stylesheet>


