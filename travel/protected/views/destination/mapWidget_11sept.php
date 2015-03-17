<?php

if ($model->getResultCount() > 0) {
    $document = $model->getDocumentWithDistDur();
    // include map config 
    $map_config = Yii::app()->params['MAP_CONFIG'];
    // include wp config
    Yii::app()->params['WP_CONFIG'];
    Yii::import('ext.EGMap.*');
    $egMap = new EGMap ();
// map options
    $map_width = $model->getMapWidth();
    $map_height = $model->getMapHeight();
    $width = isset($map_width) ? $map_width : $map_config['MAP_WIDTH'];
    $height = isset($map_height) ? $map_height : $map_config['MAP_HEIGHT'];
    $egMap->setWidth($width);
    $egMap->setHeight($height);

        if (isset($map_config['NO_CLEAR'])) {

            $egMap->noClear = $map_config['NO_CLEAR'];
        }
        if (isset($map_config['DISABLE_DOUBLE_CLICK_ZOOM'])) {

            $egMap->disableDoubleClickZoom = $map_config['DISABLE_DOUBLE_CLICK_ZOOM'];
        }
        if (isset($map_config['BACKGROUND_COLOR']) && $map_config['BACKGROUND_COLOR'] != NULL) {

            $egMap->backgroundColor = '"' . $map_config['BACKGROUND_COLOR'] . '"';
        }
        if (isset($map_config['DRAGGABLE'])) {

            $egMap->draggable = $map_config['DRAGGABLE'];
            if (isset($map_config['DRAGGABLE_CURSOR']) && $map_config['DRAGGABLE_CURSOR'] != NULL) {

                $egMap->draggableCursor = '"' . $map_config['DRAGGABLE_CURSOR'] . '"';
            }
            if (isset($map_config['DRAGGING_CURSOR']) && $map_config['DRAGGING_CURSOR'] != NULL) {

                $egMap->draggingCursor = '"' . $map_config['DRAGGING_CURSOR'] . '"';
            }
        }
        if (isset($map_config['SCROLLWHEEL'])) {

            $egMap->scrollwheel = $map_config['SCROLLWHEEL'];
        }
        if (isset($map_config['KEYBOARD_SHORTCUTS'])) {

            $egMap->keyboardShortcuts = $map_config['KEYBOARD_SHORTCUTS'];
        }
        if (isset($map_config['ZOOM']) && $map_config['ZOOM'] != NULL) {

            $egMap->zoom = $map_config['ZOOM'];
        }
        if (isset($map_config['MAX_ZOOM']) && $map_config['MAX_ZOOM'] != NULL) {

            $egMap->maxZoom = $map_config['MAX_ZOOM'];
        }
        if (isset($map_config['MIN_ZOOM']) && $map_config['MIN_ZOOM'] != NULL) {

            $egMap->minZoom = $map_config['MIN_ZOOM'];
        }

        if (isset($map_config['ZOOM_CONTROL'])) {

            $egMap->zoomControl = $map_config['ZOOM_CONTROL'];
            if (isset($map_config['ZOOM_CONTROL_STYLE']) && $map_config['ZOOM_CONTROL_STYLE'] != NULL) {

                $egMap->zoomControlStyle = '"' . $map_config['ZOOM_CONTROL_STYLE'] . '"';
            }

            if (isset($map_config['ZOOM_CONTROL_OPTIONS'])) {

                $egMap->zoomControlOptions = $map_config['ZOOM_CONTROL_OPTIONS'];
            }
        }
        if (isset($map_config['STREET_VIEW_CONTROL'])) {

            $egMap->streetViewControl = $map_config['STREET_VIEW_CONTROL'];
            if (isset($map_config['STREET_VIEW_CONTROL_OPTIONS'])) {

                $egMap->streetViewControlOptions = $map_config['STREET_VIEW_CONTROL_OPTIONS'];
            }
        }
        if (isset($map_config['DISABLE_DEFAULT_UI'])) {

            $egMap->disableDefaultUI = $map_config['DISABLE_DEFAULT_UI'];
        }
        if (isset($map_config['MAP_TYPE_ID']) && $map_config['MAP_TYPE_ID'] != NULL) {

            $egMap->mapTypeId = $map_config['MAP_TYPE_ID'];
        }
        if (isset($map_config['MAP_TYPE_CONTROL'])) {

            $egMap->mapTypeControl = $map_config['MAP_TYPE_CONTROL'];
            if (isset($map_config['MAP_TYPE_CONTROL_OPTIONS'])) {

                $egMap->mapTypeControlOptions = $map_config['MAP_TYPE_CONTROL_OPTIONS'];
            }
        }
        if (isset($map_config['PAN_CONTROL'])) {

            $egMap->panControl = $map_config['PAN_CONTROL'];
            if (isset($map_config['PAN_CONTROL_OPTIONS'])) {

                $egMap->panControlOptions = $map_config['PAN_CONTROL_OPTIONS'];
            }
        }
        if (isset($map_config['SCALE_CONTROL'])) {

            $egMap->scaleControl = $map_config['SCALE_CONTROL'];
            if (isset($map_config['SCALE_CONTROL_OPTIONS'])) {

                $egMap->scaleControlOptions = $map_config['SCALE_CONTROL_OPTIONS'];
            }
        }
//        if (isset($map_config['NAVIGATION_CONTROL'])) {
//
//            $egMap->navigationControl = $map_config['NAVIGATION_CONTROL'];
//            if (isset($map_config['NAVIGATION_CONTROL_OPTIONS'])) {
//
//                $egMap->navigationControlOptions = $map_config['NAVIGATION_CONTROL_OPTIONS'];
//            }
//        }
//map page options
        $default_image = $map_config['DEFAULT_IMAGE'];
        $sourceHtml = $map_config['SOURCE_HTML'];
        $otherLocHtml = $map_config['DESTINATION_HTML'];
        $sourceIcon = $map_config['SOURCE_ICON'];
        $otherLocIcon = $map_config['DESTINATION_ICON'];
//  echo "<pre>";
//  print_r($document);
//  die('documents');
    foreach ($document['documents'] as $key => $destination) {

// comparing location name

        if ($model->getCenterName() === strtolower(trim($destination['destination']))) {

// set center lat long as per passed destination
            $centerlat = $destination ['lat'];
            $centerLong = $destination ['long'];
            setCenters($config, $egMap, $centerlat, $centerLong);
        }

        $objEGMapCoord = new EGMapCoord ();
// infobox - start
            $objInfoBox = new EGMapInfoBox('');
            // set infobox properties
            $objInfoBox->pixelOffset = new EGMapSize($map_config['INFOBOX_PIXELOFFSET_HEIGHT'], $map_config['INFOBOX_PIXELOFFSET_WIDTH']);
            if (isset($map_config['INFOBOX_MAXWIDTH'])) {

                $objInfoBox->maxWidth = '"' . $map_config['INFOBOX_MAXWIDTH'] . '"';
            }
	    $boxStyle = array();
            if (isset($map_config['INFOBOX_BOXSTYLE_WIDTH'])) {

                $boxStyle['width'] = '"' . $map_config['INFOBOX_BOXSTYLE_WIDTH'] . '"';
            }
            if (isset($map_config['INFOBOX_BOXSTYLE_HEIGHT'])) {

                $boxStyle['height'] = '"' . $map_config['INFOBOX_BOXSTYLE_HEIGHT'] . '"';
            }
            if (isset($map_config['INFOBOX_BOXSTYLE_BACKGROUND'])) {

                $boxStyle['background'] = '"' . $map_config['INFOBOX_BOXSTYLE_BACKGROUND'] . '"';
            }
	    $objInfoBox->boxStyle = $boxStyle;

            if (isset($map_config['INFOBOX_CLOSEBOXMARGIN'])) {

                $objInfoBox->closeBoxMargin = '"' . $map_config['INFOBOX_CLOSEBOXMARGIN'] . '"';
            }
            if (isset($map_config['INFOBOX_INFOBOXCLEARANCE_HEIGHT']) && isset($map_config['INFOBOX_INFOBOXCLEARANCE_WIDTH'])) {

                $objInfoBox->infoBoxClearance = new EGMapSize($map_config['INFOBOX_INFOBOXCLEARANCE_HEIGHT'], $map_config['INFOBOX_INFOBOXCLEARANCE_WIDTH']);
            }
            if (isset($map_config['INFOBOX_ENABLEEVENTPROPAGATION'])) {
                $objInfoBox->enableEventPropagation = '"' . $map_config['INFOBOX_ENABLEEVENTPROPAGATION'] . '"';
            }
// infobox - end
// comparing location name
// source
        if ($model->getCenterName() === strtolower(trim($destination['destination']))) {

            $objEGMapCoord->setLatitude($centerlat);
            $objEGMapCoord->setLongitude($centerLong);
		$tags = array_merge((array)$destination['tag'],(array)$destination['subtag']);
                $tags = array_filter(
                        $tags,
                        function($val){
                                return (!empty($val));
                        }
                );
            $infoArr = array(
                'destination' => $destination['destination'],
                'desturl' => $destination['url'],
                'description' => $destination['short_description'],
                'image' => get_preset_post_meta( $destination['destination_id'], 1 ),
		        'subtags' => isset($tags) ? $tags : ''
            );
            $html = createHtml($sourceHtml, $infoArr, $destination['destination']);
            $icon = $destination['tag_image'];
        } else { // other locations

            $objEGMapCoord->setLatitude($destination['lat']);
            $objEGMapCoord->setLongitude($destination['long']);
                $tags = array_merge((array)$destination['tag'],(array)$destination['subtag']);
                $tags = array_filter(
                        $tags,
                        function($val){
                                return (!empty($val));
                        }
                );
            $infoArr = array(
                'destination' => $destination['destination'],
                'desturl' => $destination['url'],
                'description' => $destination['short_description'],
                'distance' => empty($destination['distance']) ? '' : $destination['distance'] . " from " . $model->getCenterName(),
                'duration' => empty($destination['duration']) ? '' : $destination['duration'] . " to reach",
                'image' => get_preset_post_meta( $destination['destination_id'], 1 ),
                #'subtags' => isset($destination['subtag']) ? str_replace(" ", "_", $destination['subtag']) : ''
                'subtags' => isset($tags) ? $tags : ''
            );
            $html = createHtml($otherLocHtml, $infoArr, $destination['destination']);
            $icon = $destination['tag_image'];
        }
        $oMarker = bindInfoBoxToMarker($objInfoBox, $objEGMapCoord, $html, $icon, $destination['destination']); // info box
//        $oMarker = bindInfoWindowToMarker($objEGMapCoord, $html, $icon, $destination['destination']); // info window
        $egMap->addMarker($oMarker);
    }
// to center and zoom on markers
    $egMap->centerAndZoomOnMarkers(0.2);
    $egMap->renderMap();
} else {
    ?>
    <figure class="col-sm-12">
        No Result Found.
        <div class="clear"></div> 
    </figure>
<?php } 

/**
 * get solr request path
 */
function getRequestPath($config, $postParams) {

// creating request params
    if (!empty($postParams['maploc'])) {
        $arrQuery[] = 'destination=' . $postParams['maploc'];
    }
    if (!empty($postParams['purpose'])) {
        $arrQuery[] = 'purpose=' . $postParams['purpose'];
    }
    if (!empty($postParams['tag'])) {
        $arrQuery[] = 'tag=' . $postParams['tag'];
    }
    $arrQuery[] = 'radius=' . $config ['radius'];
    $arrQuery[] = 'wt=' . $config ['responseType'];
    $requestQuery = implode('&', $arrQuery);

    return $config ['apiDomain'] . '?' . $requestQuery;
}

/**
 * set center on map
 *
 */
function setCenters($config, $obj, $centerlat, $centerLong) {

    $obj->setCenter($centerlat, $centerLong); // mumbai coords
    $mapTypeControlOptions = array(
        'position' => EGMapControlPosition::RIGHT_TOP,
        'style' => EGMap::MAPTYPECONTROL_STYLE_HORIZONTAL_BAR
    );
    $obj->mapTypeControlOptions = $mapTypeControlOptions;
    $obj->mapTypeId = EGMap::TYPE_ROADMAP;
}

function createHtml($html, $infoArr, $destination) {
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

function bindInfoWindowToMarker($objLatLng, $html, $icon, $title) {

// Preparing InfoWindow with information about our marker.
    $info_window = new EGMapInfoWindow($html);
    $info_window->disableAutoPan = true;
// Setting up an icon for marker.
// $icon = new EGMapMarkerImage( $icon ) ;
    //$icon = str_replace(' ', '_', strtolower($icon));
    //$iconPath = Yii::app()->params['site_url'] . Yii::app()->params['image_path'] . $icon;
    $icon = new EGMapMarkerImage($icon);
// $icon = new EGMapMarkerImage("http://google-maps-icons.googlecode.com/files/car.png") ;
// $icon->setSize(32, 37);
// $icon->setAnchor(16, 16.5);
// $icon->setOrigin(0, 0);

    $marker = new EGMapMarker(
            $objLatLng->getLatitude(), $objLatLng->getLongitude(), array(
        'title' => $title,
        'icon' => $icon
            )
    );
    $marker->draggable = false;
    $marker->addHtmlInfoWindow($info_window);
    return $marker;
}

function bindInfoBoxToMarker($objInfoBox, $objLatLng, $html, $icon, $title) {

// infobox
    	$objInfoBox->setContent($html);
	#echo "<br/> $html";
	#echo "<br/> $icon";
// Setting up an icon for marker.
   	$icon = new EGMapMarkerImage($icon);
	//$icon->setSize(32, 37);
	//$icon->setAnchor(16, 16.5);
	//$icon->setOrigin(0, 0);

    $marker = new EGMapMarker(
            $objLatLng->getLatitude(), $objLatLng->getLongitude(), array(
        'title' => $title,
        'icon' => $icon
            )
    );
    $marker->draggable = false;
    $marker->addHtmlInfoBox($objInfoBox);
    return $marker;
}

?>
