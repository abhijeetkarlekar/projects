<?php
// setting default margin to local variable
$center_and_zoom_on_markers_margin = $this->map_config['CENTER_AND_ZOOM_ON_MARKERS_DEFAULT_MARGIN'];
// include EGMap extension for google maps rendering on page
Yii::import('ext.EGMap.*');
$egMap = new EGMap();
// map options
$egMap->setWidth($this->width);
$egMap->setHeight($this->height);

if (isset($this->map_config['NO_CLEAR'])) {

    $egMap->noClear = $this->map_config['NO_CLEAR'];
}
if (isset($this->map_config['DISABLE_DOUBLE_CLICK_ZOOM'])) {

    $egMap->disableDoubleClickZoom = $this->map_config['DISABLE_DOUBLE_CLICK_ZOOM'];
}
if (isset($this->map_config['BACKGROUND_COLOR']) && $this->map_config['BACKGROUND_COLOR'] != NULL) {

    $egMap->backgroundColor = '"' . $this->map_config['BACKGROUND_COLOR'] . '"';
}
if (isset($this->map_config['DRAGGABLE'])) {

    $egMap->draggable = $this->map_config['DRAGGABLE'];
    if (isset($this->map_config['DRAGGABLE_CURSOR']) && $this->map_config['DRAGGABLE_CURSOR'] != NULL) {

        $egMap->draggableCursor = '"' . $this->map_config['DRAGGABLE_CURSOR'] . '"';
    }
    if (isset($this->map_config['DRAGGING_CURSOR']) && $this->map_config['DRAGGING_CURSOR'] != NULL) {

        $egMap->draggingCursor = '"' . $this->map_config['DRAGGING_CURSOR'] . '"';
    }
}
if (isset($this->map_config['SCROLLWHEEL'])) {

    $egMap->scrollwheel = $this->map_config['SCROLLWHEEL'];
}
if (isset($this->map_config['KEYBOARD_SHORTCUTS'])) {

    $egMap->keyboardShortcuts = $this->map_config['KEYBOARD_SHORTCUTS'];
}
if (isset($this->map_config['ZOOM']) && $this->map_config['ZOOM'] != NULL) {

    $egMap->zoom = $this->map_config['ZOOM'];
}
if (isset($this->map_config['MAX_ZOOM']) && $this->map_config['MAX_ZOOM'] != NULL) {

    $egMap->maxZoom = $this->map_config['MAX_ZOOM'];
}
if (isset($this->map_config['MIN_ZOOM']) && $this->map_config['MIN_ZOOM'] != NULL) {

    $egMap->minZoom = $this->map_config['MIN_ZOOM'];
}

if (isset($this->map_config['ZOOM_CONTROL'])) {

    $egMap->zoomControl = $this->map_config['ZOOM_CONTROL'];
    if (isset($this->map_config['ZOOM_CONTROL_STYLE']) && $this->map_config['ZOOM_CONTROL_STYLE'] != NULL) {

        $egMap->zoomControlStyle = '"' . $this->map_config['ZOOM_CONTROL_STYLE'] . '"';
    }

    if (isset($this->map_config['ZOOM_CONTROL_OPTIONS'])) {

        $egMap->zoomControlOptions = $this->map_config['ZOOM_CONTROL_OPTIONS'];
    }
}
if (isset($this->map_config['STREET_VIEW_CONTROL'])) {

    $egMap->streetViewControl = $this->map_config['STREET_VIEW_CONTROL'];
    if (isset($this->map_config['STREET_VIEW_CONTROL_OPTIONS'])) {

        $egMap->streetViewControlOptions = $this->map_config['STREET_VIEW_CONTROL_OPTIONS'];
    }
}
if (isset($this->map_config['DISABLE_DEFAULT_UI'])) {

    $egMap->disableDefaultUI = $this->map_config['DISABLE_DEFAULT_UI'];
}
if (isset($this->map_config['MAP_TYPE_ID']) && $this->map_config['MAP_TYPE_ID'] != NULL) {

    $egMap->mapTypeId = $this->map_config['MAP_TYPE_ID'];
}
if (isset($this->map_config['MAP_TYPE_CONTROL'])) {

    $egMap->mapTypeControl = $this->map_config['MAP_TYPE_CONTROL'];
    if (isset($this->map_config['MAP_TYPE_CONTROL_OPTIONS'])) {

        $egMap->mapTypeControlOptions = $this->map_config['MAP_TYPE_CONTROL_OPTIONS'];
    }
}
if (isset($this->map_config['PAN_CONTROL'])) {

    $egMap->panControl = $this->map_config['PAN_CONTROL'];
    if (isset($this->map_config['PAN_CONTROL_OPTIONS'])) {

        $egMap->panControlOptions = $this->map_config['PAN_CONTROL_OPTIONS'];
    }
}
if (isset($this->map_config['SCALE_CONTROL'])) {

    $egMap->scaleControl = $this->map_config['SCALE_CONTROL'];
    if (isset($this->map_config['SCALE_CONTROL_OPTIONS'])) {

        $egMap->scaleControlOptions = $this->map_config['SCALE_CONTROL_OPTIONS'];
    }
}
if (isset($this->map_config['NAVIGATION_CONTROL'])) {

    $egMap->navigationControl = $this->map_config['NAVIGATION_CONTROL'];
    if (isset($this->map_config['NAVIGATION_CONTROL_OPTIONS'])) {

        $egMap->navigationControlOptions = $this->map_config['NAVIGATION_CONTROL_OPTIONS'];
    }
}
// map page options
$sourceTemplate = $this->map_config['SOURCE_HTML'];
$destinationTemplate = $this->map_config['DESTINATION_HTML'];
//$default_image = $this->map_config['DEFAULT_IMAGE'];
//$sourceIcon = $this->map_config['SOURCE_ICON'];
//$destinationIcon = $this->map_config['DESTINATION_ICON'];
// model documents
//echo "here -- <pre>"; print_r($this->documents); die("documents");
foreach ($this->documents['documents'] as $key => $destination) {

// comparing location name
    if ($this->center_name === strtolower(trim($destination['destination']))) {

// set center lat long as per passed destination
        $centerlat = $destination ['lat'];
        $centerLong = $destination ['long'];
        $egMap->setCenter($centerlat, $centerLong);
    }
    // egmap coordinate object initialisation
    $objEGMapCoord = new EGMapCoord();
// infobox - start
    $objInfoBox = new EGMapInfoBox('');
    // set infobox properties
    $objInfoBox->pixelOffset = new EGMapSize($this->map_config['INFOBOX_PIXELOFFSET_HEIGHT'], $this->map_config['INFOBOX_PIXELOFFSET_WIDTH']);
    if (isset($this->map_config['INFOBOX_MAXWIDTH'])) {

        $objInfoBox->maxWidth = '"' . $this->map_config['INFOBOX_MAXWIDTH'] . '"';
    }
    $boxStyle = array();
    if (isset($this->map_config['INFOBOX_BOXSTYLE_WIDTH'])) {

        $boxStyle['width'] = '"' . $this->map_config['INFOBOX_BOXSTYLE_WIDTH'] . '"';
    }
    if (isset($this->map_config['INFOBOX_BOXSTYLE_HEIGHT'])) {

        $boxStyle['height'] = '"' . $this->map_config['INFOBOX_BOXSTYLE_HEIGHT'] . '"';
    }
    if (isset($this->map_config['INFOBOX_BOXSTYLE_BACKGROUND'])) {

        $boxStyle['background'] = '"' . $this->map_config['INFOBOX_BOXSTYLE_BACKGROUND'] . '"';
    }
    $objInfoBox->boxStyle = $boxStyle;

    if (isset($this->map_config['INFOBOX_CLOSEBOXMARGIN'])) {

        $objInfoBox->closeBoxMargin = '"' . $this->map_config['INFOBOX_CLOSEBOXMARGIN'] . '"';
    }
    if (isset($this->map_config['INFOBOX_INFOBOXCLEARANCE_HEIGHT']) && isset($this->map_config['INFOBOX_INFOBOXCLEARANCE_WIDTH'])) {

        $objInfoBox->infoBoxClearance = new EGMapSize($this->map_config['INFOBOX_INFOBOXCLEARANCE_HEIGHT'], $this->map_config['INFOBOX_INFOBOXCLEARANCE_WIDTH']);
    }
    if (isset($this->map_config['INFOBOX_ENABLEEVENTPROPAGATION'])) {
        $objInfoBox->enableEventPropagation = '"' . $this->map_config['INFOBOX_ENABLEEVENTPROPAGATION'] . '"';
    }
// infobox - end
// comparing location name
// source    
    $infoArr = array();
    if ($this->center_name === strtolower(trim($destination['destination']))) {

        $objEGMapCoord->setLatitude($centerlat);
        $objEGMapCoord->setLongitude($centerLong);
        $htmlTemplate = $sourceTemplate;
    } else { // other locations
        $objEGMapCoord->setLatitude($destination['lat']);
        $objEGMapCoord->setLongitude($destination['long']);
        $infoArr['distance'] = empty($destination['distance']) ? '' : $destination['distance'] . " from " . $this->center_name;
        $infoArr['duration'] = empty($destination['duration']) ? '' : $destination['duration'] . " to reach";
        $htmlTemplate = $sourceTemplate;
    }
    $tags = array_merge((array) $destination['tag'], (array) $destination['subtag']);
    $tags = array_filter(
            $tags, function($val) {
        return (!empty($val));
    }
    );
    $infoArr['destination'] = empty($destination['destination']) ? 'destination' : $destination['destination'];
    $infoArr['desturl'] = empty($destination['url']) ? 'javascript:void(0);' : $destination['url'];
    $infoArr['description'] = empty($destination['description']) ? '' : substr($destination['description'], $this->map_config['DESCRIPTION_START'], $this->map_config['DESCRIPTION_LENGTH']);
    $infoArr['image'] = (strlen(get_preset_post_meta($destination['destination_id'], 1)) > 0) ? get_preset_post_meta($destination['destination_id'], 1) : $this->map_config['DEFAULT_IMAGE'];
    $infoArr['subtags'] = empty($tags) ? '' : $tags;
    $html = replaceTemplatePHText($htmlTemplate, $infoArr);
    $icon = $destination['tag_image'];
    if ($this->map_config['ENABLE_INFO_WINDOW'] === TRUE) {
        // info window
        $oMarker = bindInfoWindowToMarker($objEGMapCoord, $html, $icon, $destination['destination'], $this->map_config);
    } else if ($this->map_config['ENABLE_INFO_BOX'] === TRUE) {
        // info box
        $oMarker = bindInfoBoxToMarker($objInfoBox, $objEGMapCoord, $html, $icon, $destination['destination'], $this->map_config);
    } else {
        // info box
        $oMarker = bindInfoBoxToMarker($objInfoBox, $objEGMapCoord, $html, $icon, $destination['destination'], $this->map_config);
    }
    $egMap->addMarker($oMarker);
}
// to center and zoom on markers
if (isset($this->map_config['CENTER_AND_ZOOM_ON_MARKERS_MARGIN'])) {
    
    $center_and_zoom_on_markers_margin = $this->map_config['CENTER_AND_ZOOM_ON_MARKERS_MARGIN'];
}
$egMap->centerAndZoomOnMarkers( $center_and_zoom_on_markers_margin, $this->map_config['CENTER_AND_ZOOM_ON_MARKERS_ZOOM'] );
$param = array();
if (isset($this->map_config['API_KEY']) && $this->map_config['API_KEY'] !== NULL) {
    
    $params = array('apiKey' => $this->map_config['API_KEY']);
}
$egMap->renderMap($params);

/**
 * replace placeholder text in a template
 * @param String template with placeholder text
 * @param Array placeholder replacement text
 * @return String template with placeholder text replaced
 */
function replaceTemplatePHText($html, $infoArr) {

    $tagContent = '';
    if (count($infoArr) > 0) {

        foreach ($infoArr as $title => $content) {

            if (is_array($content) && count($content) > 0) {

                if ($title == 'subtags') {

                    foreach ($content as $k => $v) {

                        $tagContent .= "<span class='placeholder'>$v</span> ";
                    }
                }
                $html = str_replace($title, $tagContent, $html);
            } else {

                $html = str_replace($title, $content, $html);
            }
            $html = preg_replace('/[{}]+/', '', $html);
        }
    }
    return $html;
}

/**
 * bind info window to map marker
 * @param Object map coordinate
 * @param String template
 * @param String icon image path
 * @param String title
 * @param Array map configuration details
 * @return Object marker object
 */
function bindInfoWindowToMarker($objLatLng, $html, $icon, $title, $map_config) {

    // Preparing InfoWindow with information about our marker.
    $info_window = new EGMapInfoWindow($html);
    // Setting up an icon for marker
    $icon = new EGMapMarkerImage($icon);
    if (isset($map_config['IW_MARKER_IMAGE_WIDTH']) && $map_config['IW_MARKER_IMAGE_WIDTH'] != NULL && isset($map_config['IW_MARKER_IMAGE_HEIGHT']) && $map_config['IW_MARKER_IMAGE_HEIGHT'] != NULL) {
        $icon->setSize($map_config['IW_MARKER_IMAGE_WIDTH'], $map_config['IW_MARKER_IMAGE_HEIGHT']);
    }
    if (isset($map_config['IW_MARKER_IMAGE_ANCHOR_X_COORDS']) && $map_config['IW_MARKER_IMAGE_ANCHOR_X_COORDS'] != NULL && isset($map_config['IW_MARKER_IMAGE_ANCHOR_Y_COORDS']) && $map_config['IW_MARKER_IMAGE_ANCHOR_Y_COORDS'] != NULL) {
        $icon->setAnchor($map_config['IW_MARKER_IMAGE_ANCHOR_X_COORDS'], $map_config['IW_MARKER_IMAGE_ANCHOR_Y_COORDS']);
    }
    if (isset($map_config['IW_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS']) && $map_config['IW_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS'] != NULL && isset($map_config['IW_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS']) && $map_config['IW_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS'] != NULL) {
        $icon->setOrigin($map_config['IW_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS'], $map_config['IW_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS']);
    }
    $marker = new EGMapMarker(
            $objLatLng->getLatitude(), $objLatLng->getLongitude(), array(
        'title' => $title,
        'icon' => $icon
            )
    );
    $marker->draggable = $map_config['IW_MARKER_DRAGGABLE'];
    $marker->addHtmlInfoWindow($info_window);
    return $marker;
}

/**
 * bind info box to map marker
 * @param Object map box
 * @param Object map coordinate
 * @param String template
 * @param String icon image path
 * @param String title
 * @param Array map configuration details
 * @return Object marker object
 */
function bindInfoBoxToMarker($objInfoBox, $objLatLng, $html, $icon, $title, $map_config) {

    // infobox
    $objInfoBox->setContent($html);
    // Setting up an icon for marker.
    $icon = new EGMapMarkerImage($icon);
    if (isset($map_config['IB_MARKER_IMAGE_WIDTH']) && $map_config['IB_MARKER_IMAGE_WIDTH'] != NULL && isset($map_config['IB_MARKER_IMAGE_HEIGHT']) && $map_config['IB_MARKER_IMAGE_HEIGHT'] != NULL) {
        $icon->setSize($map_config['IB_MARKER_IMAGE_WIDTH'], $map_config['IB_MARKER_IMAGE_HEIGHT']);
    }
    if (isset($map_config['IB_MARKER_IMAGE_ANCHOR_X_COORDS']) && $map_config['IB_MARKER_IMAGE_ANCHOR_X_COORDS'] != NULL && isset($map_config['IB_MARKER_IMAGE_ANCHOR_Y_COORDS']) && $map_config['IB_MARKER_IMAGE_ANCHOR_Y_COORDS'] != NULL) {
        $icon->setAnchor($map_config['IB_MARKER_IMAGE_ANCHOR_X_COORDS'], $map_config['IB_MARKER_IMAGE_ANCHOR_Y_COORDS']);
    }
    if (isset($map_config['IB_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS']) && $map_config['IB_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS'] != NULL && isset($map_config['IB_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS']) && $map_config['IB_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS'] != NULL) {
        $icon->setOrigin($map_config['IB_MARKER_IMAGE_ORIGIN_ANCHOR_X_COORDS'], $map_config['IB_MARKER_IMAGE_ORIGIN_ANCHOR_Y_COORDS']);
    }
    $marker = new EGMapMarker(
            $objLatLng->getLatitude(), $objLatLng->getLongitude(), array(
        'title' => $title,
        'icon' => $icon
            )
    );
    $marker->draggable = $map_config['IB_MARKER_DRAGGABLE'];
    $marker->addHtmlInfoBox($objInfoBox);
    return $marker;
}

?>