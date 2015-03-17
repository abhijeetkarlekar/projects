<?php

echo $model->getMapWidth(); die('shdkshdkshdks');

if ($model->getResultCount() > 0) {
    $document = $model->getDocumentWithDistDur();
    ?>
    <aside class="map-view-l col-sm-2 ">
        <div class="map-h">
            <h2>Destinations to explore:</h2>
            <p><?php echo $model->getResultCount() ?></p>
        </div>
        <?php
        foreach ($document['documents'] as $key => $destination) {
            ?>
            <figure>
                <a href="<?php echo $destination['url']; ?>">
                    <img src="<?php echo $destination['image_small'] ?>" />
                </a>
                <figcaption>
                    <?php echo $destination['destination']; ?>
                </figcaption>					
            </figure>
        <?php } ?>
    </aside>

    <?php
    $site_url = Yii::app()->params['site_url'];
    $image_url = $site_url . Yii::app()->params['image_path'];

    require_once ( dirname(__FILE__) . '/../../config/map.php' );
    Yii::import('ext.EGMap.*');
    $egMap = new EGMap ();
// map options
    $egMap->setWidth($config['width']);
    $egMap->setHeight($config['height']);
//$egMap->zoom 		= $config[ 'zoom' ] ;
    $default_image = $config['default_image'];
    $sourceHtml = $config['sourceHtml'];
    $otherLocHtml = $config['otherLocHtml'];
    $sourceIcon = $config['sourceIcon'];
    $otherLocIcon = $config['otherLocIcon'];
//    $document = $model->getDocumentWithDistDur();
//     echo "<pre>";
//     print_r($document);

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
        $objInfoBox->pixelOffset = new EGMapSize('-20', '0');
// $objInfoBox->maxWidth 	 = 0 ;
        $objInfoBox->boxStyle = array(
            'width' => '"500px"'
            , 'height' => '"120px"'
//, 'background'=>'"url(http://google-maps-utility-library-v3.googlecode.com/svn/tags/infobox/1.1.9/examples/tipbox.gif) no-repeat"'
        );
        $objInfoBox->closeBoxMargin = '"20px 0px -12px 2px"';
// $objInfoBox->infoBoxClearance = new EGMapSize(1,1);
// $objInfoBox->enableEventPropagation = '"floatPane"' ;
// infobox - end
// comparing location name
        if ($model->getCenterName() === strtolower(trim($destination['destination']))) {
            $objEGMapCoord->setLatitude($centerlat);
            $objEGMapCoord->setLongitude($centerLong);

//            $destination['description'] = 'Jaipur is the capital and largest city of the Indian state of Rajasthan in Northern India. It was founded on 18 November 1727 by Maharaja Sawai Jai Singh II, the ruler of Amber, after whom the city is named.';
//            $destination['distance'] = '100 km';
//            $destination['duration'] = '1 hour';

            $infoArr = array(
                //'destinationTitle' => 'Destination',
                'destination' => $destination['destination'],
                //'desturl' => $site_url . str_replace(" ", "_", strtolower($destination['destination'])),
                'desturl' => $destination['url'],
                //'descriptionTitle' => 'Description',
                'description' => $destination['short_description'],
                //'imageTitle' => '',
                'image' => empty($destination['image']) ? $default_image : $destination['image'],
                //'tagsTitle' => "Tags",
                'subtags' => isset($destination['subtag']) ? str_replace(" ", "_", $destination['subtag']) : ''
            );
            $html = createHtml($sourceHtml, $infoArr, $destination['destination']);
            $icon = $icon = $destination['tag'][0] . ".png";
        } else {

            $objEGMapCoord->setLatitude($destination['lat']);
            $objEGMapCoord->setLongitude($destination['long']);

//            $destination['description'] = 'Jaipur is the capital and largest city of the Indian state of Rajasthan in Northern India. It was founded on 18 November 1727 by Maharaja Sawai Jai Singh II, the ruler of Amber, after whom the city is named.';
//            $destination['distance'] = '100 km';
//            $destination['duration'] = '1 hour';

            $infoArr = array(
                //'destinationTitle' => 'Destination',
                'destination' => $destination['destination'],
                'desturl' => $destination['url'],
                //'descriptionTitle' => 'Description',
                'description' => $destination['short_description'],
                //'distanceTitle' => 'Distance',
                'distance' => empty($destination['distance']) ? '' : $destination['distance'] . " from " . $model->getCenterName(),
                //'durationTitle' => 'Duration',
                'duration' => empty($destination['duration']) ? '' : $destination['duration'] . " to reach",
                //'imageTitle' => '',
                'image' => empty($destination['image']) ? $default_image : $destination['image'],
                //'tagsTitle' => "Tags",
                'subtags' => isset($destination['subtag']) ? str_replace(" ", "_", $destination['subtag']) : ''
            );
            $html = createHtml($otherLocHtml, $infoArr, $destination['destination']);
            $icon = $destination['tag'][0] . ".png";
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
//                        $tagurl = Yii::app()->params['site_url'] . strtolower(str_replace(" ", "_", $destination)) . '/' . strtolower(str_replace(" ", "_", $v));
//                        $tagContent .= "<a href='" . $tagurl . "' class='placeholder'>$v</a> ";
                        $tagContent .= "<a href='javascript:void(0)' class='placeholder'>$v</a> ";
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
    $icon = str_replace(' ', '_', strtolower($icon));
    $iconPath = Yii::app()->params['site_url'] . Yii::app()->params['image_path'] . $icon;
    $icon = new EGMapMarkerImage($iconPath);
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
// $info_box 	   = new EGMapInfoBox ($html) ;
    $objInfoBox->setContent($html);
// Setting up an icon for marker.
// $icon = new EGMapMarkerImage( $icon ) ;
    $icon = str_replace(' ', '_', strtolower($icon));
    $iconPath = Yii::app()->params['site_url'] . Yii::app()->params['image_path'] . $icon;
    $icon = new EGMapMarkerImage($iconPath);
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
