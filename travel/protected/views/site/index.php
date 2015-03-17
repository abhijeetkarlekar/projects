<?php

/**
 * @Usage
 * Params:
 * destination ( String ) : Name
 * purpose ( String ) : spatial, near_by_places, places_to_visit, events, activities
 * tag ( String ) : beach, zoos, temple
 * wt ( String ) : xml / json
 * @URLS
 * http://local.v2.travel.com/geo.php?destination=Jaipur&radius=300&wt=json
 * http://local.v2.travel.com/geo.php?destination=Jaipur&radius=300&purpose=near_by_places&wt=json
 * http://local.v2.travel.com/geo.php?destination=Jaipur&radius=300&purpose=spatial&wt=json
 * http://local.v2.travel.com/geo.php?destination=Jaipur&radius=300&purpose=places_to_visit&wt=json
 * http://local.v2.travel.com/geo.php?destination=Goa&radius=300&purpose=places_to_visit&tag=church&wt=json
 */
/**
 * @desc : google map search is a route map between source, way points and destinations having address, time to visit, time needed to see, distance to travel, 
 * total time taken, and total distance travelled.
 */

require_once ( dirname(__FILE__).'/../../config/map.php' ) ;
Yii::import ( 'ext.EGMap.*' ) ;
$egMap = new EGMap () ;
// map options
$egMap->setWidth ( $config[ 'width' ] ) ;
$egMap->setHeight ( $config[ 'height' ] ) ;
//$egMap->zoom 		= $config[ 'zoom' ] ;
$default_image = $config[ 'default_image' ] ;
$sourceHtml	   = $config[ 'sourceHtml' ] ;
$otherLocHtml  = $config[ 'otherLocHtml' ] ;
$sourceIcon    = $config[ 'sourceIcon' ] ;
$otherLocIcon  = $config[ 'otherLocIcon' ] ;
//$sourceTitle 	= "Source";
//$otherLocTitle= "Other Locations";

$centerlat 	= '' ;
$centerLong = '' ;
// get params
global $qs;
$qs_data 	= explode('/',$qs);
print_r ( $qs_data ) ; 
// destination
$_POST [ 'maploc' ] = empty ( $_POST [ 'maploc' ] ) ? 'Jaipur' : $_POST [ 'maploc' ] ;
$centerName = $_POST [ 'maploc' ] ;



/*
$expCenter 	= explode( ',', $centerName);
$centerlat 	= trim($expCenter[ 0 ]) ;
$centerLong = trim($expCenter[ 1 ]) ;
$centerlat 	= !empty($centerlat) ? $centerlat : 26.912434 ;
$centerLong = !empty($centerLong) ? $centerLong : 75.787271 ;
*/
// fetching solr data
$requestPath = getRequestPath( $config, $_POST ) ;

//// echo " requestPath -- $requestPath " ; //die;

//$path = $config[ 'apiDomain' ] . '?sort=score%20asc&q={!func}geodist%28%29&fq={!geofilt}&sfield=geo&pt=' . $centerlat . ',' . $centerLong . '&d='. $config[ 'radius' ] . '&fl=destination,geo,lat,long,near_by_places,score,description&wt=' . $config[ 'responseType' ] ;
$strLocDetails = file_get_contents ( $requestPath ) ;
$arrLocDetails = json_decode( $strLocDetails, true ) ;
//// echo "<pre>"; print_r( $arrLocDetails ) ; //die () ;

if(count($arrLocDetails) > 0) {

	foreach( $arrLocDetails as $k => $v ) {

		if( $k == 'response' ) {

			$numFound = $arrLocDetails[ $k ][ 'numFound' ] ;
			if( $numFound > 0 ) {

				// get origin and destination lat long details for calculating distance and duration
				foreach( $v[ 'docs' ] as $destination ) {
					// origin
					$latLng = $destination [ 'lat' ].",".$destination [ 'long' ] ;
					if ( strtolower ( trim ( $centerName ) ) === strtolower ( trim ( $destination[ 'destination' ] ) ) ) { // comparing location name

						$distanceMatrix['origin'][] = $latLng ;
					} else {

						$distanceMatrix['destination'][] = $latLng ;
					}
				}

				//print_r( $distanceMatrix ) ; die('here') ;

				// destination name
				foreach( $v[ 'docs' ] as $destination ) {

					if ( strtolower ( trim ( $centerName ) ) === strtolower ( trim ( $destination[ 'destination' ] ) ) ) { // comparing location name

						// set center lat long as per passed destination
						$centerlat 	= $destination [ 'lat' ] ;
						$centerLong = $destination [ 'long' ] ;
						setCenters( $config, $egMap, $centerlat, $centerLong ) ;
					}
					
					$objEGMapCoord = new EGMapCoord () ;
					// infobox - start
					$objInfoBox    = new EGMapInfoBox ( '' ) ;
					// set infobox properties
					$objInfoBox->pixelOffset = new EGMapSize ( '-20', '0' ) ;
					// $objInfoBox->maxWidth 	 = 0 ;
					$objInfoBox->boxStyle 	 = array (
					    'width'=>'"500px"'
					    , 'height'=>'"120px"'
					    //, 'background'=>'"url(http://google-maps-utility-library-v3.googlecode.com/svn/tags/infobox/1.1.9/examples/tipbox.gif) no-repeat"'
					) ;
					$objInfoBox->closeBoxMargin = '"20px 0px -12px 2px"' ;
					// $objInfoBox->infoBoxClearance = new EGMapSize(1,1);
					// $objInfoBox->enableEventPropagation = '"floatPane"' ;
					// infobox - end

					// info window - start

					// info window - end
					
					// if( $centerlat == $destination[ 'lat' ] && $centerLong == $destination[ 'long' ] ) { // comparing location latlong
					if ( strtolower ( trim ( $centerName ) ) === strtolower ( trim ( $destination[ 'destination' ] ) ) ) { // comparing location name
						
						$objEGMapCoord->setLatitude ( $centerlat ) ;
						$objEGMapCoord->setLongitude ( $centerLong ) ;

						$infoArr = array(

							'destinationTitle' 	=> 'Destination',
							'destination' 		=> $destination[ 'destination' ] ,
							'descriptionTitle' 	=> 'Description' ,
							'description' 		=> $destination[ 'description' ] ,
							//'distance'  		=> $destination[ 'score' ] . " km" ,
							'imageTitle'		=> '' ,
							'image'		  		=> empty( $destination[ 'image' ] ) ? $default_image : $destination[ 'image' ] ,
							'tagsTitle'	  		=> "Tags" ,
							'tags'	 	  		=> $destination[ 'subtag' ]
						);
						$html = createHtml ( $sourceHtml, $infoArr ) ;
						$icon = $icon =  $destination[ 'tag' ][ 0 ] . ".png" ;
					} else {

						$objEGMapCoord->setLatitude ( $destination[ 'lat' ] ) ;
						$objEGMapCoord->setLongitude ( $destination[ 'long' ] ) ;
						// distance from source lat long round upto 2 decimal places
						$distance = round( $objEGMapCoord->distanceFrom( new EGMapCoord ( $centerlat, $centerLong ) ), 2 ) ;
												
						$infoArr = array (

							'destinationTitle' 	=> 'Destination',
							'destination' 		=> $destination[ 'destination' ] ,
							'descriptionTitle' 	=> 'Description' ,
							'description' 		=> $destination[ 'description' ] ,
							//'distance'  		=> $destination[ 'score' ] . " km" ,
							'distanceTitle' 	=> 'Distance' ,
							'distance' 	  		=> $distance . " km" ,
							'imageTitle'		=> '' ,
							'image'		  		=> empty( $destination[ 'image' ] ) ? $default_image : $destination[ 'image' ] ,
							'tagsTitle'	  		=> "Tags" ,
							'tags'	 	  		=> $destination[ 'subtag' ]
						) ;
						$html = createHtml ( $otherLocHtml, $infoArr ) ;
						$icon =  $destination[ 'tag' ][ 0 ] . ".png" ;
					}
					//$oMarker = bindInfoBoxToMarker( $objInfoBox, $objEGMapCoord, $html, $icon, $destination[ 'destination' ] ) ; // info box
					$oMarker = bindInfoWindowToMarker( $objEGMapCoord, $html, $icon, $destination[ 'destination' ] ) ; // info window
					$egMap->addMarker( $oMarker ) ;
					// http://maps.googleapis.com/maps/api/distancematrix/json?key=API_KEY&mode=bicycling&origins=&destinations=
					$distanceMatrixApiUrl = "http://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyATnB2VTH-k8-68aZOKDbFv3zWXSf9eNOs&mode=bicycling&";
					echo " distanceMatrixApiUrl -- $distanceMatrixApiUrl " ;
				}
			}
		}
	}
	// to center and zoom on markers
	$egMap->centerAndZoomOnMarkers( 0.2 ) ;
	$egMap->renderMap() ;
}

/**
 * get solr request path
 */
function getRequestPath( $config, $postParams ) {

	echo " <pre> " ; print_r ( $postParams ) ;

	// creating request params
	if ( !empty ( $postParams['maploc'] ) ) {
		$arrQuery[] = 'destination=' . $postParams['maploc'] ;
	}
	if ( !empty ( $postParams['purpose'] ) ) {
		$arrQuery[] = 'purpose='.$postParams['purpose'] ;
	}
	if ( !empty ( $postParams['tag'] ) ) {
		$arrQuery[] = 'tag='.$postParams['tag'] ;
	}
	$arrQuery[] = 'radius=' . $config [ 'radius' ] ;
	$arrQuery[] = 'wt=' . $config [ 'responseType' ] ;
	$requestQuery = implode ( '&', $arrQuery ) ;

	return $config [ 'apiDomain' ] . '?' . $requestQuery ;
}
/**
 * set center on map
 *
 */
function setCenters ( $config, $obj, $centerlat, $centerLong ) {

	$obj->setCenter ( $centerlat, $centerLong ) ; // mumbai coords
	$mapTypeControlOptions = array (

		'position' => EGMapControlPosition::RIGHT_TOP ,
		'style' => EGMap::MAPTYPECONTROL_STYLE_HORIZONTAL_BAR
	) ;
	$obj->mapTypeControlOptions = $mapTypeControlOptions ;
	$obj->mapTypeId = EGMap::TYPE_ROADMAP ;
	
}

function createHtml ( $html, $infoArr ) {

	if( count( $infoArr ) > 0 ) {

		foreach( $infoArr as $title => $content ) {
			
			if ( is_array ( $content ) && count ( $content ) > 0 ) {
				
				if ( $title == 'tags' ) {

					foreach ( $content as $k => $v ) {

						$tagContent .= "<a href='' class='placeholder'>$v</a> " ;						
					}
				}
				$html = str_replace( $title, $tagContent, $html ) ;
			} else {
				$html = str_replace( $title, $content, $html ) ;
			}
			$html = preg_replace( '/[{}]+/', '', $html ) ;
			/*
			$displayTitle = ucfirst( $title ) ;
			$replaceContent = "<b>". $displayTitle .":</b> ".$content ;
			if( $title == 'image' ) {

				$html = str_replace( $title, $content, $html ) ;
			} else {

				$html = str_replace( $title, $replaceContent, $html ) ;
			}			
			$html = preg_replace( '/[{}]+/', '', $html ) ;
			*/
		}
	}
	return $html ;
}

function bindInfoWindowToMarker ( $objLatLng, $html, $icon, $title ) {
	
	// Preparing InfoWindow with information about our marker.
	$info_window = new EGMapInfoWindow( $html ) ;
	$info_window->disableAutoPan = true ;
	// Setting up an icon for marker.
	// $icon = new EGMapMarkerImage( $icon ) ;
	$iconPath = "http://local-wordpress.in/wp-content/themes/twentyfourteen/images/" . $icon ;
	$icon = new EGMapMarkerImage( $iconPath ) ;
	// $icon = new EGMapMarkerImage("http://google-maps-icons.googlecode.com/files/car.png") ;
	// $icon->setSize(32, 37);
	// $icon->setAnchor(16, 16.5);
	// $icon->setOrigin(0, 0);

	$marker = new EGMapMarker (

		$objLatLng->getLatitude() ,
		$objLatLng->getLongitude() ,
		array(
			'title' => $title ,
			'icon' => $icon
		)
	) ;
	$marker->draggable = false ;
	$marker->addHtmlInfoWindow( $info_window ) ;
	return $marker;
}

function bindInfoBoxToMarker ( $objInfoBox, $objLatLng, $html, $icon, $title ) {

	// infobox
	// $info_box 	   = new EGMapInfoBox ($html) ;
	$objInfoBox->setContent ( $html ) ;
	// Setting up an icon for marker.
	// $icon = new EGMapMarkerImage( $icon ) ;
	$iconPath = "http://local-wordpress.in/wp-content/themes/twentyfourteen/images/" . $icon ;
	$icon = new EGMapMarkerImage( $iconPath ) ;
	//$icon->setSize(32, 37);
	//$icon->setAnchor(16, 16.5);
	//$icon->setOrigin(0, 0);
	
	$marker = new EGMapMarker (

		$objLatLng->getLatitude() ,
		$objLatLng->getLongitude() ,
		array(
			'title' => $title ,
			'icon' => $icon
		)
	) ;
	$marker->draggable = false ;
	$marker->addHtmlInfoBox( $objInfoBox ) ;
	return $marker;
}

?>
