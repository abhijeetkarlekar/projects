<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="iso-8859-1" />
<xsl:template name="incHeader">
<head>
   <script>
    var admin_web_url = '<xsl:value-of select="/XML/ADMIN_WEB_URL" disable-output-escaping="yes"/>';
    var admin_img_url = '<xsl:value-of select="/XML/ADMIN_IMAGE_URL" disable-output-escaping="yes"/>';
</script>
</head>
<div class="leftArea">
 <div class="grid_12 header-repeat">
            <div id="branding">
                <div class="floatleft">
                    <img src="{/XML/ADMIN_IMAGE_URL}gad-logo.png" alt="Logo" /></div>
                <div class="floatright">
                    <div class="floatleft">
                        <img src="{/XML/ADMIN_IMAGE_URL}img-profile.jpg" alt="Profile Pic" /></div>
                    <div class="floatleft marginleft10">
                        <ul class="inline-ul floatleft">
                            <li>Hello Admin</li>
                            <!-- <li><a href="#">Config</a></li>
                            <li><a href="#">Logout</a></li> -->
                        </ul>
                        <br />
                        <!-- <span class="small grey">Last Login: 3 hours ago</span> -->
                    </div>
                </div>
                <div class="clear">
                </div>
            </div>
        </div>
<div class="clear"></div>
</div>
</xsl:template>
</xsl:stylesheet>

