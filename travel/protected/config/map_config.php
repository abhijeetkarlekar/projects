<?php

return array(
// map
    // domain
    'DOMAIN' => 'v2.travel.india.com',
    // only one CLIENT_ID or API_KEY must use 
    // client id for enterprise
    'CLIENT_ID' => NULL,
    // API key free service
    #'API_KEY' => 'AIzaSyBrdcXcUiBfZl7lb1__-ewrf_K2LtF-jMM', // abhijeet@corp.india.com
    'API_KEY' => 'AIzaSyBwCcWb89qvq3Ns0QHq3noEw8ngkfi8dGY', // mdeveloper@corp.india.com
    // boolean  If true, do not clear the contents of the Map div.
    'NO_CLEAR ' => TRUE,
    // Enables/disables zoom and center on double click. Enabled by default.
    'DISABLE_DOUBLE_CLICK_ZOOM' => TRUE,
    // string Color used for the background of the Map div. This color will be visible when tiles have not yet loaded as a user pans.
    'BACKGROUND_COLOR' => '#FFFFFF',
    // boolean If false, prevents the map from being dragged. Dragging is enabled by default.  
    'DRAGGABLE' => TRUE,
    // string The name or url of the cursor to display on a draggable object. type - DEFAULT, CROSSHAIR, POINTER, MOVE
    'DRAGGABLE_CURSOR' => 'CROSSHAIR',
    // string The name or url of the cursor to display when an object is dragging.  
    'DRAGGING_CURSOR' => 'MOVE',
    // boolean If true, enables scrollwheel zooming on the map. The scrollwheel is disabled by default.  
    'SCROLLWHEEL' => TRUE,
    // boolean If false, prevents the map from being controlled by the keyboard. Keyboard shortcuts are enabled by default.  
    'KEYBOARD_SHORTCUTS' => TRUE,
    // LatLng The initial Map center. Required.
    #'CENTER' => null,
    // number The initial Map zoom level. Required.  
    'ZOOM' => 12,
    // The maximum zoom level which will be displayed on the map. If omitted, or set to 
    // null, the maximum zoom from the current map type is used instead.
    'MAX_ZOOM' => NULL,
    // The minimum zoom level which will be displayed on the map. If omitted, or set to 
    // null, the minimum zoom from the current map type is used instead.
    'MIN_ZOOM' => NULL,
    // The enabled/disabled state of the zoom control.
    // true by default
    'ZOOM_CONTROL' => TRUE,
    // http://code.google.com/intl/en-EN/apis/maps/documentation/javascript/reference.html#ZoomControlStyle
    'ZOOM_CONTROL_STYLE' => 'RIGHT_BOTTOM',
    // Of type named array
    // http://code.google.com/intl/en-EN/apis/maps/documentation/javascript/reference.html#ZoomControlOptions
    'ZOOM_CONTROL_OPTIONS' => array (
	'position' => 'google.maps.ControlPosition.LEFT_CENTER',
	'style' => 'google.maps.ZoomControlStyle.ZOOMCONTROL_STYLE_DEFAULT'
    ),
    // The initial enabled/disabled state of the Street View pegman control.
    'STREET_VIEW_CONTROL' => TRUE,
    // The initial display options for the Street View pegman control.
    // Of type named array
    // http://code.google.com/intl/en-EN/apis/maps/documentation/javascript/reference.html#streetViewControlOptions
    'STREET_VIEW_CONTROL_OPTIONS' => array (
        'position' => 'google.maps.ControlPosition.LEFT_BOTTOM',
    ),
    // boolean Enables/disables all default UI. May be overridden individually.  
    'DISABLE_DEFAULT_UI' => FALSE,
    // string The initial Map mapTypeId. Required. Defaults to ROADMAP.
    'MAP_TYPE_ID' => 'google.maps.MapTypeId.ROADMAP',
    // boolean The initial enabled/disabled state of the Map type control.  
    'MAP_TYPE_CONTROL' => TRUE,
    // MapTypeControl options The initial display options for the Map type control.  
    // Of type named array 
    // http://code.google.com/intl/en-EN/apis/maps/documentation/javascript/reference.html#MapTypeControlOptions
    'MAP_TYPE_CONTROL_OPTIONS' => array(
	'position' => 'google.maps.ControlPosition.RIGHT_TOP',
        'style' => 'google.maps.MapTypeControlStyle.DROPDOWN_MENU'
    ),
    // The enabled/disabled state of the pan control.
    'PAN_CONTROL' => TRUE,
    // The display options for the pan control.
    // http://code.google.com/intl/en-EN/apis/maps/documentation/javascript/reference.html#PanControlOptions
    'PAN_CONTROL_OPTIONS' => array (
        'position' => 'google.maps.ControlPosition.LEFT_TOP',
    ),
    // boolean The initial enabled/disabled state of the scale control.  
    'SCALE_CONTROL' => FALSE,
    // ScaleControl options The initial display options for the scale control.  
    // http://code.google.com/intl/en-EN/apis/maps/documentation/javascript/reference.html#ScaleControlOptions
    // Of type named array
    'SCALE_CONTROL_OPTIONS' => array (
        'style' => 'google.maps.ScaleControlStyle.DEFAULT',
    ),
    // boolean The initial enabled/disabled state of the navigation control.  
    #'NAVIGATION_CONTROL' => TRUE,
    // NavigationControl options The initial display options for the navigation control.  
    // http://code.google.com/intl/en-EN/apis/maps/documentation/javascript/3.2/reference.html#NavigationControlOptions
    // Of type named array
    'NAVIGATION_CONTROL_OPTIONS' => NULL,
# comment settings to allow page level width and height
    'MAP_WG_WIDTH' => 775,
    'MAP_WG_HEIGHT' => 600,
    'MAP_WIDGET_WIDTH' => 625,
    'MAP_WIDGET_HEIGHT' => 425,
    
    'CENTER_AND_ZOOM_ON_MARKERS_DEFAULT_MARGIN' => 0.5,
    'CENTER_AND_ZOOM_ON_MARKERS_DEFAULT_ZOOM' => 14,
    
    'MAP_WG_CENTER_AND_ZOOM_ON_MARKERS' => NULL,
    'MAP_WIDGET_CENTER_AND_ZOOM_ON_MARKERS' => 0,
// info box template and icons
    'DEFAULT_IMAGE' => 'http://staging.travel.india.com/wp-content/themes/travel2014/images/dest-preset1.jpg',
    'SOURCE_HTML' => '<section class="placemap"><a href="{desturl}"><h2>{destination}</h2></a><aside class="col-sm-12"><figure><a href="{desturl}" class="imgt" title=""><img src="{image}" height="100px" width="100px" /></a><figcaption><p>{description}</p><section class="tags" style="margin:0 0 10px 15px;"><span class="tagi"></span>{subtags}<div class="clear"></div></section></figcaption><div class="clear"></div></figure><div class="clear"></div></aside><div class="clear"></div></section>',
    'DESTINATION_HTML' => '<section class="placemap"><a href="{desturl}"><h2>{destination}</h2></a><aside class="col-sm-12"><figure><a href="{desturl}" class="imgt" title=""><img src="{image}" height="100px" width="100px"/></a><figcaption><p>{description}</p><p>{distance}</p><p>{duration}</p><section class="tags" style="margin:0 0 10px 15px;"><span class="tagi"></span>{subtags}<div class="clear"></div></section></figcaption><div class="clear"></div></figure><div class="clear"></div></aside><div class="clear"></div></section>',
    #'SOURCE_ICON' => 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|FFFF00|000000',
    #'DESTINATION_ICON' => 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|FE7569|000000',
// weekend getaways map - start
    'LEFT_PANE_RECORDS' => 4,
// infobox
    'INFOBOX_PIXELOFFSET_HEIGHT' => -20,
    'INFOBOX_PIXELOFFSET_WIDTH' => 0,
    #'INFOBOX_MAXWIDTH' => '400px',
    'INFOBOX_BOXSTYLE_WIDTH' => '400px',
    'INFOBOX_BOXSTYLE_HEIGHT' => '120px',
    #'INFOBOX_BOXSTYLE_BACKGROUND' => 'url(http://google-maps-utility-library-v3.googlecode.com/svn/tags/infobox/1.1.9/examples/tipbox.gif) no-repeat',
    'INFOBOX_CLOSEBOXMARGIN' => '20px 0px -12px 2px',
    'INFOBOX_INFOBOXCLEARANCE_HEIGHT' => 120,
    'INFOBOX_INFOBOXCLEARANCE_WIDTH' => 10,
    'INFOBOX_ENABLEEVENTPROPAGATION' => 'floatPane',
// google distance matrix api
    'DMA_API' => 'https://maps.googleapis.com/maps/api/distancematrix/json',
    'DMA_API_KEY' => 'AIzaSyBrdcXcUiBfZl7lb1__-ewrf_K2LtF-jMM',
    // boolean - fetch distance for map search
    // default - false
    'SHOW_WG_DISTANCE' => FALSE,
    // boolean - fetch duration for map search
    // default - false
    'SHOW_WG_DURATION' => FALSE,
    // boolean - fetch distance for widget search
    // default - false
    'SHOW_WIDGET_DISTANCE' => FALSE,
    // boolean - fetch duration for widget search
    // default - false
    'SHOW_WIDGET_DURATION' => FALSE,
    // boolean - fetch distance for weekendfrom search
    // default - false
    'SHOW_WEEKENDFROM_DISTANCE' => FALSE,
    // boolean - fetch duration for weekendfrom search
    // default - false
    'SHOW_WEEKENDFROM_DURATION' => FALSE,
    // boolean - fetch distance for discover search
    // default - false
    'SHOW_DISCOVER_DISTANCE' => FALSE,
    // boolean - fetch duration for discover search
    // default - false
    'SHOW_DISCOVER_DURATION' => FALSE,
    // boolean - fetch distance for explore search
    // default - false
    'SHOW_EXPLORE_DISTANCE' => FALSE,
    // boolean - fetch duration for explore search
    // default - false
    'SHOW_EXPLORE_DURATION' => FALSE,
// map info box marker image
    'IB_MARKER_IMAGE_WIDTH' => 32,
    'IB_MARKER_IMAGE_HEIGHT' => 37,
    'IB_MARKER_IMAGE_ANCHOR_X_COORDS' => 16,
    'IB_MARKER_IMAGE_ANCHOR_Y_COORDS' => 16.5,
    'IB_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS' => 0,
    'IB_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS' => 0,
// map info window marker image
    'IW_MARKER_IMAGE_WIDTH' => 32,
    'IW_MARKER_IMAGE_HEIGHT' => 37,
    'IW_MARKER_IMAGE_ANCHOR_X_COORDS' => 16,
    'IW_MARKER_IMAGE_ANCHOR_Y_COORDS' => 16.5,
    'IW_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS' => 0,
    'IW_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS' => 0,
// markers
    'IW_MARKER_DRAGGABLE' => FALSE,
    'IB_MARKER_DRAGGABLE' => FALSE,
// info popup - default info box enabled
    'ENABLE_INFO_WINDOW' => FALSE,
    'ENABLE_INFO_BOX' => FALSE,
// popup information
    'DESCRIPTION_START' => 0,
    'DESCRIPTION_LENGTH' => 100,
// weekend page distance search - default 300
    'WEEKEND_DEFAULT_DISTANCE' => 500,
);
?>
