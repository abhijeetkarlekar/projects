<?xml version="1.0" ?>
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
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" version="4.0" encoding="UTF-8" indent="yes"/>
    
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Typography | BlueWhale Admin</title>
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}reset.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}text.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}grid.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}layout.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{XML/ADMIN_CSS_URL}nav.css" media="screen" />
    <!--[if IE 6]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen" /><![endif]-->
    <!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" /><![endif]-->
    <link href="css/table/demo_page.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN: load jquery -->
    <script src="{XML/ADMIN_JS_URL}jquery-1.6.4.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.core.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.widget.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.accordion.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.core.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.effects.slide.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.mouse.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}jquery-ui/jquery.ui.sortable.min.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}table/jquery.dataTables.min.js" type="text/javascript"></script>
    <!-- END: load jquery -->
    <script src="{XML/ADMIN_JS_URL}table/table.js" type="text/javascript"></script>
    <script src="{XML/ADMIN_JS_URL}setup.js" type="text/javascript"></script>
    <script type="text/javascript" src="{XML/ADMIN_JS_URL}common.js"></script>
    <script type="text/javascript" src="{XML/ADMIN_JS_URL}category.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            setupLeftMenu();

            $('.datatable').dataTable();
            setSidebarHeight();


        });
        </script>
        </head>
        <body>
    test
        </body>
       
        </html>
    </xsl:template>
</xsl:stylesheet>

