<?php
/**
 * @desc : google map search is a route map between source, way points and destinations having address, time to visit, time needed to see, distance to travel, 
 * total time taken, and total distance travelled.
 */
Yii::import ( 'ext.EGMap.*' ) ;
$egMap = new EGMap () ;
// map options
$egMap->setWidth ( '400' ) ;
$egMap->setHeight ( '300' ) ;
$egMap->zoom = 10 ;
$egMap->setCenter ( 18.9750, 72.8258 ) ; // mumbai coords
$mapTypeControlOptions = array (
	'position' => EGMapControlPosition::RIGHT_TOP ,
	'style' => EGMap::MAPTYPECONTROL_STYLE_HORIZONTAL_BAR
) ;
$egMap->mapTypeControlOptions = $mapTypeControlOptions ;
$egMap->mapTypeId = EGMap::TYPE_ROADMAP ;
// locations to map
// source
$source = new EGMapCoord ( 19.0180, 72.8448 ) ;
$html = "<div class='gmaps-label' style='color: #000;'>Source</div>" ;
$icon = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=S|FFFF00|000000" ;
$title = "Source";
$smarker = addCustomInfoWindow( $source, $html, $icon, $title ) ;
$egMap->addMarker( $smarker ) ;
// destination
$destination = new EGMapCoord ( 19.0800, 73.0100 ) ;
$html = "<div class='gmaps-label' style='color: #000;'>Destination</div>" ;
$icon = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=D|0FFA00|000000" ;
$title = "Destination";
$dmarker = addCustomInfoWindow( $destination, $html, $icon, $title ) ;
$egMap->addMarker( $dmarker ) ;
// way points
$arrWayPoints = array (

	array( 'lat' => 19.0600, 'lng' => 72.8900 ) , // kurla
	array( 'lat' => 19.1550, 'lng' => 73.0070 )   // airoli
) ;

if( count( $arrWayPoints ) > 0 ) {

	foreach( $arrWayPoints as $numIndex => $arrlatlng ) {

		$lat 		 			 = $arrlatlng[ 'lat' ] ;
		$lng 		 			 = $arrlatlng[ 'lng' ] ;
		$latLng 				 = new EGMapCoord( $lat, $lng ) ;
		$arrObjLatLngWayPoints[] = new EGMapDirectionWayPoint( $latLng ) ;
		$html = "<div class='gmaps-label' style='color: #000;'>Way Points</div>" ;
		$icon = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=W|FE7569|000000" ;
		$title = "Way Points" ;
		$waypoints_marker = addCustomInfoWindow( $latLng, $html, $icon, $title ) ;
		$egMap->addMarker( $waypoints_marker ) ;
	}
}
// Initialize EGMapDirection
$direction = new EGMapDirection (
	$source ,
	$destination ,
	'js_egmap_dir' ,
	array (
			'waypoints' => $arrObjLatLngWayPoints
		//, 	'travelMode' => DRIVING
	)
) ;
$direction->optimizeWaypoints = true ;
$direction->provideRouteAlternatives = true ;
//$direction->travelMode = DRIVING ; // TRAVEL_MODE_WALKING
// rederer
$renderer = new EGMapDirectionRenderer ( ) ;
$renderer->draggable = true ;
$renderer->panel = 'direction_pane' ;
$renderer->infoWindow = true ;
//$renderer->suppressMarkers = true ;
$direction->setRenderer( $renderer ) ;
$egMap->addDirection( $direction ) ;
$egMap->renderMap() ;

function addCustomInfoWindow( $objLatLng, $html, $icon, $title ) {
	
	// Preparing InfoWindow with information about our marker.
	$info_window = new EGMapInfoWindow( $html ) ;
	// Setting up an icon for marker.
	//$source_icon = new EGMapMarkerImage("http://google-maps-icons.googlecode.com/files/car.png");
	$icon = new EGMapMarkerImage( $icon ) ;
	// $source_icon->setSize(32, 37);
	// $source_icon->setAnchor(16, 16.5);
	// $source_icon->setOrigin(0, 0);

	$marker = new EGMapMarker (
		$objLatLng->getLatitude() ,
		$objLatLng->getLongitude() ,
		array(
			'title' => $title ,
			'icon' => $icon
		)
	) ;
	$marker->draggable = true ;
	$marker->addHtmlInfoWindow( $info_window ) ;
	return $marker;
	//$egMap->addMarker( $marker ) ;
}

?>
<div id='direction_pane'></div>
