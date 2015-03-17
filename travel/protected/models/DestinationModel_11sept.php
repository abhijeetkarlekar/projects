<?php

class DestinationModel extends CModel {

    /**
     * combined file and class names => model
     */
    private static $_models = array();

    /**
     * Model id (key for $_models array)
     */
    private $model;

    /**
     * json response
     */
    private $response;

    /**
     * is error
     */
    private static $is_error = FALSE;

    /**
     * error message
     */
    private static $arr_disp_error_msg = array();

    /**
     * Array Error Messages
     */
    private static $arr_error_msg = array(
        'empty' => 'No result found.',
        'centername' => 'Destination is empty.'
    );

    /**
     * Distance Matrix Api
     */
    private $dma_api = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    /**
     * Distance Matrix Api Key
     */
    private $dma_api_key = 'AIzaSyBrdcXcUiBfZl7lb1__-ewrf_K2LtF-jMM';
    //private $dma_api_key;

    /**
     * Distance Matrix Api travelling mode
     */
    private $dma_mode = 'driving';

    /**
     * Distance Matrix Api travelling mode

      private $dma_mode = array(
      'walking',
      'bicycling',
      'driving'
      );
     */
    /**
     * map center     
      private static $map_center = null;
     */

    /**
     * map center
     */
    private static $center_name = null;

    /**
     * map center lat
     */
    private static $center_lat = null;

    /**
     * map center long
     */
    private static $center_long = null;

    /**
     * distance matrix multidimensional array
     */
    private $distance_matrix = array(
        array()
    );

    /**
     * distance arr
     */
    private $arr_distance = array();

    /**
     * duration arr
     */
    private $arr_duration = array();

    /**
     * distance Matrix Api
     */
    private $distance_matrix_api = null;

    /**
     * Solr Api domain
     */
    private static $solr_api_domain = 'http://dev.v2.travel.india.com/geo.php';

    /**
     * Solr Api
     */
    private static $solr_api = null;

    /**
     * arr required params
     */
    public static $arr_required_params = array(
        'destination'
    );

    /**
     * Solr params map
     */
    private static $solr_params_map = array(
        'destination' => 'destination',
        //'otherdest' => 'purpose',
        'theme' => 'tag',
        'idealtime' => 'month',
        'distance' => 'radius'
    );

    /**
     * map width
     */
    public static $mapWidth;

    /**
     * map height
     */
    public static $mapHeight;


    /**
     * show distance
     */
    public static $showDistance = TRUE;

    /**
     * show duration
     */
    public static $showDuration = TRUE;

    /**
     * display theme name
     */
    public static $dispTheme = '';

    /**
     * page number
     */
    public static $pageNumber = 0;

    /**
     * page number
     */
    public static $count = 0;

    /**
     * page number
     */
    public static $currpage = 0;

    /**
     * paging string
     */
    public static $pagingString = '';

    /**
     * seo url string
     */
    public static $seoUrlString = '';

    /**
     * state
     */
    public static $state = '';

    /**
     * seo url string
     */
    public static $solrResult = '';

    /**
     * map config
     */
    private $map_config = array();

    public function __construct() {

        $this->response = null;
        Yii::app()->params['WP_CONFIG'];
        $this->map_config = Yii::app()->params['MAP_CONFIG'];
        if($this->map_config['DM_API']!=NULL && isset($this->map_config['DM_API'])){

            $this->dma_api = $this->map_config['DM_API'];
        }
        if($this->map_config['DM_API_KEY']!=NULL && isset($this->map_config['DM_API_KEY'])){

            $this->dma_api_key = $this->map_config['DM_API_KEY'];
        }
    }

    public static function fetchExploreSolrResult() {
        $seourl = array();
        $near_by_places = null;
        $events = null;
        $month = null;
        $stateparams = array();
        $search_params = array();
        $queryStrParams = null;
        $seourl[] = WEB_URL;
        $seourl[] = DISCOVER_URL;

        $theme = !empty($_REQUEST['theme']) ? $_REQUEST['theme'] : "";
        $themeparams = array();
        $themedisp = null;
        if (!empty($theme) && $theme != "all") {
            $seourl[] = "theme-" . str_replace(",", "_", $theme);
            $themedisp = str_replace(",", " ", $theme);
            foreach (explode(',', $theme) as $k => $v) {
                $themeparams[] = 'tag:"' . $v . '"';
            }
            if (is_array($themeparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $themeparams)) . ')';
            }
        }
        $destination = !empty($_REQUEST['destination']) ? $_REQUEST['destination'] : "";
        $destinationparams = array();
        $destinationdisp = null;
        if (!empty($destination) && $destination != "all") {
            $seourl[] = "destination-" . str_replace(",", "_", $destination);
            $destinationdisp = str_replace(",", " ", $destination);
            foreach (explode(',', $destination) as $k => $v) {
                $destinationparams[] = 'destination:"' . $v . '"';
            }
            if (is_array($destinationparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $destinationparams)) . ')';
            }
        }
        $month = !empty($_REQUEST['month']) ? $_REQUEST['month'] : "";
        $monthparams = array();
        $monthdisp = null;
        if (!empty($month) && $month != "all") {
            $seourl[] = "month-" . str_replace(",", "_", $month);
            $monthdisp = str_replace(",", " ", $month);
            foreach (explode(',', $month) as $k => $v) {
                $monthparams[] = 'best_time_to_visit:' . ucfirst($v) . '*Yes*';
            }
            if (is_array($monthparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $monthparams)) . ')';
            }
        }

        $state = !empty($_REQUEST['state']) ? $_REQUEST['state'] : "";
        $statedisp = Array();
        if ($state != '' && $state != 'India' && $state != 'all') {
//echo $state; 
            $seourl[] = "state-" . str_replace(",", "_", $state);
            $statedisp = str_replace(",", " ", $state);
            $state_arr = explode(',', $state);
            foreach ($state_arr as $k => $v) {
                $stateparams[] = 'state:"' . str_replace("-", " ", $v) . '"';
            }
            if (is_array($stateparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $stateparams)) . ')';
            }
        }
        $queryParams = Array();
        if (is_array($search_params) && sizeof($search_params) > 0) {
            $queryStrParams = implode("+AND+", $search_params);
        } else {
            $queryStrParams = urlencode('*:*');
        }

        $distance = !empty($_REQUEST['distance']) ? $_REQUEST['distance'] : "";
//$ltlongpt = !empty($_REQUEST['latlong']) ? $_REQUEST['latlong'] : "";
        $yiipage = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $limitParams = Array();
        $themeparam = null;
        $queryParams[] = 'sort=' . urlencode('destination asc');
        $queryParams[] = 'fl=*,score';
        $queryParams[] = 'wt=json';
        $queryParams[] = 'indent=true';
        if (($queryStrParams != null) && !empty($queryStrParams)) {
            $queryParams[] = 'q=' . $queryStrParams;
            $limitParams[] = 'start=0';
            $limitParams[] = 'rows=1';
            $travelSolrCnt = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
        }
        // echo $travelSolrCnt ; die();
        unset($limitParams);
        $xmlStrCnt = @file_get_contents($travelSolrCnt);
        $aSolrDataCnt = json_decode($xmlStrCnt, true);
        $arrerr = array();
        $model = NULL;
        $yiiitem_count = 0;
        $yiiperpage = 0;
        $paging = NULL;
        $yiipages = NULL;

        if ($aSolrDataCnt['response']['numFound'] > 0) {

            foreach ($aSolrDataCnt as $index => $sSolrCnt) {
                $resultCount[] = $sSolrCnt;
            }
            $yiiitem_count = $aSolrDataCnt['response']['numFound'];
            if ($yiiitem_count != 0) {
                $limitParams[] = "start=0";
                $limitParams[] = "";
                if (($queryStrParams != null) && !empty($queryStrParams)) {
                    $travelSolr = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
                }
                $xmlStr = @file_get_contents($travelSolr);
                self::$solrResult = json_decode($xmlStr, true);
                self::$dispTheme = $themedisp;
                self::$state = $state;
                self::$seoUrlString = implode("/", $seourl);
            }
        } else {
            self::setPredefinedErr('empty');
        }
    }

    public static function fetchDiscoverSolrResult() {
	#print_r($_REQUEST); die();
        $seourl = array();
        $near_by_places = null;
        $events = null;
        $month = null;
        $stateparams = array();
        $search_params = array();
        $queryStrParams = null;
        $seourl[] = WEB_URL;
        $seourl[] = DISCOVER_URL;

        $theme = !empty($_REQUEST['theme']) ? $_REQUEST['theme'] : "";
        $themeparams = array();
        $themedisp = null;
        if (!empty($theme) && $theme != "all") {
            $seourl[] = "theme-" . str_replace(",", "_", $theme);
            $themedisp = str_replace(",", " ", $theme);
            foreach (explode(',', $theme) as $k => $v) {
                $themeparams[] = 'tag:"' . str_replace( '-', ' ', $v ) . '"';
            }
            if (is_array($themeparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $themeparams)) . ')';
            }
        }
        $destination = !empty($_REQUEST['destination']) ? $_REQUEST['destination'] : "";
        $destinationparams = array();
        $destinationdisp = null;
        if (!empty($destination) && $destination != "all") {
            $seourl[] = "destination-" . str_replace(",", "_", $destination);
            $destinationdisp = str_replace(",", " ", $destination);
            foreach (explode(',', $destination) as $k => $v) {
                $destinationparams[] = 'destination_slug:"' . str_replace(' ', '-', $v ) . '"';
            }
            if (is_array($destinationparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $destinationparams)) . ')';
            }else{
		$search_params[] = '(' . urlencode($destinationparams) . ')';

	   }
        }
        $month = !empty($_REQUEST['month']) ? $_REQUEST['month'] : "";
        $monthparams = array();
        $monthdisp = null;
        if (!empty($month) && $month != "all") {
            $seourl[] = "month-" . str_replace(",", "_", $month);
            $monthdisp = str_replace(",", " ", $month);
            foreach (explode(',', $month) as $k => $v) {
                $monthparams[] = 'best_time_to_visit:' . ucfirst($v) . '*Yes*';
            }
            if (is_array($monthparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $monthparams)) . ')';
            }
        }

        $state = !empty($_REQUEST['state']) ? $_REQUEST['state'] : "";
        $statedisp = Array();
        if ($state != '' && $state != 'India' && $state != 'all') {
            $seourl[] = "state-" . str_replace(",", "_", $state);
            $statedisp = str_replace(",", " ", $state);
            $state_arr = explode(',', $state);
            foreach ($state_arr as $k => $v) {
                $stateparams[] = 'state:"' . str_replace("-", " ", $v) . '"';
            }
            if (is_array($stateparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $stateparams)) . ')';
            }
        }
        $queryParams = Array();
        if (is_array($search_params) && sizeof($search_params) > 0) {
            $queryStrParams = implode("+AND+", $search_params);
        } else {
            $queryStrParams = urlencode('*:*');
        }

        $distance = !empty($_REQUEST['distance']) ? $_REQUEST['distance'] : "";
        $yiipage = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $limitParams = Array();
        $themeparam = null;
        $queryParams[] = 'sort=' . urlencode('destination asc');
        $queryParams[] = 'fl=*,score';
        $queryParams[] = 'wt=json';
        $queryParams[] = 'indent=true';
        if (($queryStrParams != null) && !empty($queryStrParams)) {
            $queryParams[] = 'q=' . $queryStrParams;
            $limitParams[] = 'start=0';
            $limitParams[] = 'rows=1';
            $travelSolrCnt = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
        }
        //echo " travelSolrCnt - ". $travelSolrCnt ; die();
        unset($limitParams);
        $xmlStrCnt = @file_get_contents($travelSolrCnt);

        $aSolrDataCnt = json_decode($xmlStrCnt, true);
        $arrerr = array();
        $model = NULL;
        $yiiitem_count = 0;
        $yiiperpage = 0;
        $paging = NULL;
        $yiipages = NULL;

        if ($aSolrDataCnt['response']['numFound'] > 0) {
            foreach ($aSolrDataCnt as $index => $sSolrCnt) {
                $resultCount[] = $sSolrCnt;
            }
            $yiiitem_count = $aSolrDataCnt['response']['numFound'];
            if ($yiiitem_count != 0) {
                $yiiperpage = PERPAGE;

                $yiipages = new CPagination($yiiitem_count);
                //$yiipages->route = "/destination-jaipur";
                //echo "<pre>"; print_r($yiipages); die;
                $yiipages->setPageSize($yiiperpage)-1;
                $yiipages->setCurrentPage($yiipage - 1); 
                //$getcur = $yiipages->getCurrentPage(false);
                $getcur = $yiipage;
                $yiistart = $yiipages->getOffset();
                $yiiend = ($yiipages->offset + $yiipages->limit <= $yiiitem_count ? $yiipages->offset + $yiipages->limit : $yiiitem_count);
                
                $paging = range($yiipages->offset + 1, $yiiend);
                $limitParams[] = "start=$yiistart";
                $limitParams[] = "rows=$yiiperpage";
                
                if (($queryStrParams != null) && !empty($queryStrParams)) {
                    $travelSolr = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
                }
                //echo " travelSolr - " . $travelSolr; die();
                //print_r($yiipages);
                $xmlStr = @file_get_contents($travelSolr);
                #print_r($xmlStr); die("END2");
                self::$solrResult = json_decode($xmlStr, true);
                self::$dispTheme = $themedisp;
                self::$state = $state;
                self::$count = $yiiitem_count;
                self::$currpage = $getcur;
                self::$pageNumber = $yiipages;
                self::$pagingString = $paging;
                self::$seoUrlString = implode("/", $seourl);
            }
        } else {
            self::setPredefinedErr('empty');
        }
    }

    public static function fetchWeekendSolrResult() {
        //print_r($_REQUEST); die("ssd");
        $ltlongpt = null;
        $seourl = array();
        $near_by_places = null;
        $events = null;
        $month = null;
        $stateparams = array();
        $search_params = array();
        $queryStrParams = null;
        $seourl[] = WEB_URL;
        $seourl[] = WEEKENDFROM_URL;

    $destination = !empty($_REQUEST['post_name']) ? $_REQUEST['post_name'] : "";

	if(empty($_REQUEST['lat']) && empty($_REQUEST['long'])) {
        
        $destinationlatlong = SOLR_PATH . '?q=destination:"' . urlencode(str_replace("-", " ", $destination)) . '"&wt=json&indent=true&fl=geo&spatial=true';        
        
        $destinationStrlatlong = @file_get_contents($destinationlatlong);
        //echo $destinationStrlatlong;  //die("sssss");
        if ($destinationStrlatlong === false) {
            $arrerr['err'] = 'No result found';
        } else {
            $aSolrDataLat = json_decode($destinationStrlatlong, true);
            $ltlongpt = $aSolrDataLat['response']['docs'][0]['geo'][0];
            // set center lat long for calculating distance and duration
            self::setCenterLat($aSolrDataLat['response']['docs'][0]['lat']);
            self::setCenterLong($aSolrDataLat['response']['docs'][0]['long']);
        }
	} else {

		$ltlongpt = trim($_REQUEST['lat']) . ',' . trim($_REQUEST['long']) ;
        // set center lat long for calculating distance and duration
        self::setCenterLat(trim($_REQUEST['lat']));
        self::setCenterLong(trim($_REQUEST['long']));
	}

        $destinationparams = array();
        $destinationdisp = null;
        if (!empty($destination) && $destination != "all") {
            $seourl[] = "from-" . str_replace(",", "_", $destination);
            $destinationdisp = str_replace(",", " ", $destination);
            foreach (explode(',', $destination) as $k => $v) {
                $destinationparams[] = 'destination:"' . $v . '"';
            }
            if (is_array($destinationparams)) {
//$search_params[] = '('.urlencode(implode(" OR ", $destinationparams)).')';
            }
        }
	$theme = !empty($_REQUEST['theme']) ? $_REQUEST['theme'] : "";
        $themeparams = array();
        $themedisp = null;
        if (!empty($theme) && $theme != "all") {
            $seourl[] = "theme-" . str_replace(",", "_", $theme);
            $themedisp = str_replace(",", " ", $theme);
            foreach (explode(',', $theme) as $k => $v) {
                $themeparams[] = 'tag:"' . str_replace("-"," ",$v) . '"';
            }
            if (is_array($themeparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $themeparams)) . ')';
            }
        }

        $month = !empty($_REQUEST['month']) ? $_REQUEST['month'] : "";
        $monthparams = array();
        $monthdisp = null;
        if (!empty($month) && $month != "all") {
            $seourl[] = "month-" . str_replace(",", "_", $month);
            $monthdisp = str_replace(",", " ", $month);
            foreach (explode(',', $month) as $k => $v) {
                $monthparams[] = 'best_time_to_visit:' . ucfirst($v) . '*Yes*';
            }
            if (is_array($monthparams)) {
                $search_params[] = '(' . urlencode(implode(" OR ", $monthparams)) . ')';
            }
        }
        $search_params[] = '{!geofilt}';
        $queryParams = Array();
        if (is_array($search_params) && sizeof($search_params) > 0) {
            $queryStrParams = implode("+AND+", $search_params);
        } else {
            $queryStrParams = urlencode('*:*');
        }
        $distance = !empty($_REQUEST['distance']) ? $_REQUEST['distance'] : 300;
        $seourl[] = "distance-$distance-km";
        $yiipage = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        if($yiipage==1){$yiipage='';}
        $limitParams = Array();
        $themeparam = null;
        $queryParams[] = 'q={!func}geodist()';
        if ($ltlongpt != '') {
            $queryParams[] = 'pt=' . $ltlongpt;
        }
        if ($distance != '') {
            $queryParams[] = 'd=' . $distance;
        }
        $queryParams[] = 'sort=' . urlencode('score asc');
        $queryParams[] = 'fl=*,score';
        $queryParams[] = 'sfield=geo';
        $queryParams[] = 'wt=json';
        $queryParams[] = 'indent=true';
        if (($queryStrParams != null) && !empty($queryStrParams)) {
            if($_REQUEST['v'] == 'mapwidget'){
                $queryParams[] = 'fq=(contained_in:'.urlencode($destination).urlencode(' OR ').'destination:'.urlencode($destination).')'.urlencode(' AND ').$queryStrParams ;
            } else {
                $queryParams[] = 'fq=destination_type:destinations'.urlencode(' AND ').'-contained_in:'.urlencode($destination).urlencode(' OR ').'-destination:'.urlencode($destination).urlencode(' AND ').$queryStrParams ;
            }
            /*
            if($_REQUEST['v'] == 'mapsearch'){
                $queryParams[] = 'fq=' . $queryStrParams;
            } else {
                $queryParams[] = 'fq=-destination:' . urlencode( $destination . ' AND ') . $queryStrParams ;
                //$queryParams[] = 'fq=' . $queryStrParams;
            }
            */
            $limitParams[] = 'start=0';
            $limitParams[] = 'rows=1';
            $travelSolrCnt = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
          // echo $travelSolrCnt ; die();
        }
        unset($limitParams);
        $xmlStrCnt = @file_get_contents($travelSolrCnt);
        //echo "<pre>"; print_r($xmlStrCnt);
        //die('www');
        $aSolrDataCnt = json_decode($xmlStrCnt, true);
        $arrerr = array();
        $model = NULL;
        $yiiitem_count = 0;
        $yiiperpage = 0;
        $paging = NULL;
        $yiipages = NULL;

        if ($aSolrDataCnt['response']['numFound'] > 0) {
            foreach ($aSolrDataCnt as $index => $sSolrCnt) {
                $resultCount[] = $sSolrCnt;
            }
            $yiiitem_count = $aSolrDataCnt['response']['numFound'];
            #echo $yiiitem_count."COUNT";
            if ($yiiitem_count != 0) {
                $yiiperpage = PERPAGE;

                $yiipages = new CPagination($yiiitem_count);
//$yiipages->route = "/destination-jaipur";
                $yiipages->setPageSize($yiiperpage)-1;
                //echo $yiipage."---CURPAGE<br>";
                $yiipages->setCurrentPage($yiipage - 1); 
                //$getcur = $yiipages->getCurrentPage(false);
                $getcur = $yiipage;
                $yiistart = $yiipages->getOffset();
                $yiiend = ($yiipages->offset + $yiipages->limit <= $yiiitem_count ? $yiipages->offset + $yiipages->limit : $yiiitem_count);
//$paging = range($yiipages->offset + 1, $yiiend);
                $paging = range($yiipages->offset + 1, $yiiend);
                if($_REQUEST['v'] == 'mapwidget'){
                    $limitParams[] = "start=$yiistart";
                    $limitParams[] = "rows=$yiiitem_count";
                }elseif($_REQUEST['v'] == 'mapsearch') {
                    $limitParams[] = "start=$yiistart";
                    $limitParams[] = "rows=14";
                }
		else {
                    $limitParams[] = "start=$yiistart";
                    $limitParams[] = "rows=$yiiperpage";
                }
//print_r($queryStrParams);
                if (($queryStrParams != null) && !empty($queryStrParams)) {
                    $travelSolr = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
                }
               // echo $travelSolr; die();
                $xmlStr = @file_get_contents($travelSolr);
                //print_r(json_decode($xmlStr, true)); die();
                self::$solrResult = json_decode($xmlStr, true);
                self::$count = $yiiitem_count;
                self::$currpage = $getcur;
                self::$dispTheme = $themedisp;
                self::$pageNumber = $yiipages;
                self::$pagingString = $paging;
                self::$seoUrlString = implode("/", $seourl);
            }
        } else {
            #echo "BLANK";
            self::setPredefinedErr('empty');
        }
    }

	public static function getSolrDestination(){

		$aSolrResult = array();
		$query = array();
        	$destination = !empty($_REQUEST['post_name']) ? $_REQUEST['post_name'] : "";
		    $query[] = 'wt=json';
            $query[] = 'indent=true';
		if(!empty($destination)){
			$query[] = 'q=destination:"'.urlencode(str_replace("-", " ", $destination)) .'"';
			$destinationSolrUrl = SOLR_PATH . '?' . implode( '&', $query );
    		$destinationJsonResult = @file_get_contents($destinationSolrUrl);
            if ($destinationJsonResult !== false) {
                    $aSolrResult = json_decode($destinationJsonResult, true);
            }
		} else if (!empty($_REQUEST['lat']) && !empty($_REQUEST['long'])) {
			$query[] = 'q=lat:' . trim($_REQUEST['lat']) . ' AND ' . trim($_REQUEST['long']);
            $destinationSolrUrl = SOLR_PATH . '?' . implode( '&', $query ) ;
            $destinationJsonResult = @file_get_contents($destinationSolrUrl);            
            if ($destinationJsonResult !== false) {
                    $aSolrResult = json_decode($destinationJsonResult, true);
            }
		}
		if ($aSolrResult['response']['numFound'] > 0) {
			self::$solrResult = $aSolrResult;
		} else {
			self::setPredefinedErr('empty');	
		}
	}


    public static function getState() {
        return self::$state;
    }

    public static function getCount() {
        return self::$count;
    }

    public static function getCurrPage() {
        return self::$currpage;
    }

    public static function getTheme() {
        return self::$dispTheme;
    }

    public static function getPageNumber() {
        return self::$pageNumber;
    }

    public static function getPagingString() {
        return self::$pagingString;
    }

    public static function getSeoUrl() {
        return self::$seoUrlString;
    }

    public static function model($json, $className = __CLASS__) {

        $key = $className . '::' . (is_file(self::$solr_api) ? $json : md5(self::$solr_api));
        if (isset(self::$_models[$key])) {

            return self::$_models[$key]->$className;
        } else {            

	    if(!empty($_REQUEST['post_name'])){
	    	// set center name
	    	self::setCenterName(trim($_REQUEST['post_name']));
	    }
            $model = self::$_models[$key] = new $className();
            $model->response = self::$solrResult;
            return $model;
        }
    }

	public static function setMapWidth($mapWidth){
		self::$mapWidth = $mapWidth;
	}

	public function getMapWidth(){
		return self::$mapWidth;
	}

	public static function setMapHeight($mapHeight){
		self::$mapHeight = $mapHeight;
	}

	public function getMapHeight(){
		return self::$mapHeight;
	}

    public static function setCenterName($centerName) {

        self::$center_name = strtolower(trim($centerName));
    }

    public function getCenterName() {

        return self::$center_name;
    }

    public static function setCenterLat($centerLat) {

        self::$center_lat = strtolower(trim($centerLat));
    }

    public function getCenterLat() {

        return self::$center_lat;
    }

    public static function setCenterLong($centerLong) {

        self::$center_long = strtolower(trim($centerLong));
    }

    public function getCenterLong() {

        return self::$center_long;
    }

    public static function setPredefinedErr($error_key) {

        self::$is_error = TRUE;
        self::$arr_disp_error_msg[] = self::$arr_error_msg[$error_key];
        error_log(Yii::app()->name . ": " . self::$arr_error_msg[$error_key]);
    }

    public static function setCustomErr($error_msg) {

        self::$is_error = TRUE;
        self::$arr_disp_error_msg[] = $error_msg;
        error_log(Yii::app()->name . ": " . $error_msg);
    }

    public function getErr() {

        return array_unique(self::$arr_disp_error_msg);
    }

    public function isError() {

        return self::$is_error;
    }

    public function getResultCount() {
        if ($this->isError() === TRUE) {
            $this->response['response']['numFound'] = 0;
        }
        return $this->response['response']['numFound'];
    }

    public function getResult() {

        $result = array();
        if (isset($this->response['error'])) {

            self::setCustomErr($this->response['error']['code'] . ' - ' . $this->response['error']['msg']);
        } else {
            foreach ($this->response as $key => $value) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function getDocuments() {

        $result = array();

        if (isset($this->response['error'])) {

            self::setCustomErr($this->response['error']['code'] . ' - ' . $this->response['error']['msg']);
        } else {

            if ($this->isError() === FALSE) {
                foreach ($this->response as $key => $value) {
                    if ($key == 'response') {
                        $numFound = $this->response[$key]['numFound'];
                        if ($numFound > 0) {
                            foreach ($value['docs'] as $numkey => $destination) {
                                $result['documents'][] = $destination;
                                if (!empty($destination['description'])) {
                                    $result['documents'][$numkey]['short_description'] = substr($destination['description'], 0, 200) . '...';
                                }
                            }
                        } else {
                            self::setPredefinedErr('empty');
//$result['error_msg'] = $this->error_messages['empty'];
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function getDistanceMatrix() {

        $result = $this->getDocuments();

        if (self::$showDistance === FALSE && self::$showDuration === FALSE) {
            return $result;
        }

        $documents = $result['documents'];

        if (count($documents) > 0) {

            foreach ($documents as $destination) {

                $latLng = $destination ['lat'] . "," . $destination ['long'];
// comparing location name
                $destinationName = strtolower(trim($destination['destination']));
                if ($this->getCenterName() === $destinationName) {

                    $this->distance_matrix['origin'][$destinationName] = $latLng;
                } else {

                    $this->distance_matrix['destination'][$destinationName] = $latLng;
                }
            }
            $this->setDMApi();
        } else {

            self::setPredefinedErr('empty');
//$result['error_msg'] = $this->error_messages['empty'];
        }
        return $result;
    }

    public function setDMApi() {
// reset if exists
        $this->distance_matrix_api = null;
        if (count($this->distance_matrix) > 0) {
            if(count($this->distance_matrix['origin']) > 1){

                $origin = implode("|", $this->distance_matrix['origin']);
            } else {

                if(count($this->distance_matrix['origin']) <= 0 ){
                    $this->distance_matrix['origin'] = self::getCenterLat() . "," . self::getCenterLong();
                }

                $origin = $this->distance_matrix['origin'];
            }
            if(count($this->distance_matrix['destination']) > 1){
                $destination = implode("|", $this->distance_matrix['destination']);
            } else {
                $destination = $this->distance_matrix['destination'];
            }
            $this->distance_matrix_api = "$this->dma_api?key=$this->dma_api_key&origins=$origin&destinations=$destination&mode=$this->dma_mode";            
        }
    }

    public function fetchDMApiResult() {

        $result = $this->getDistanceMatrix();

        if ($this->distance_matrix_api != null) {

            $dma_jsonString = Yii::app()->curl->run($this->distance_matrix_api);
            if ($dma_jsonString->hasErrors()) {

                self::setCustomErr($dma_arrResult['status'] . ' - ' . $dma_arrResult['error_message']);
                $error = $dma_jsonString->getErrors();
                self::setCustomErr($error->code . ' - ' . $error->message);
            } else {

                $dma_arrResult = json_decode($dma_jsonString->getData(), true);
                if (isset($dma_arrResult['error_message'])) {

                    self::setCustomErr($dma_arrResult['status'] . ' - ' . $dma_arrResult['error_message']);
                } else {

                    foreach ($dma_arrResult as $k => $v) {

                        if ($k == 'rows') {

                            foreach ($v [0] ['elements'] as $element) {

                                if (isset($element['distance']) && isset($element['duration'])) {

                                    $arrDistance['distance'][] = $element['distance']['text'];
                                    $arrDuration['duration'][] = $element['duration']['text'];
                                } else {

                                    $arrDistance['distance'][] = '';
                                    $arrDuration['duration'][] = '';
                                }
                            }
                            if (self::$showDistance === TRUE) {

                                $this->setDistanceArr($arrDistance);
                            }
                            if (self::$showDuration === TRUE) {

                                $this->setDurationArr($arrDuration);
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function setDurationArr($durationArr) {
        $this->arr_duration = $durationArr;
    }

    public function getDurationArr() {
        return $this->arr_duration;
    }

    public function setDistanceArr($distanceArr) {
        $this->arr_distance = $distanceArr;
    }

    public function getDistanceArr() {
        return $this->arr_distance;
    }

    public function getDocumentWithDistDur() {

        $result = $this->fetchDMApiResult();
        if ($this->isError() === FALSE && count($result['documents']) > 0) {
            $distanceCounter = 0;
            $durationCounter = 0;
            foreach ($result['documents'] as $key => $value) {
                $result['documents'][$key]['distance'] = '';
                $result['documents'][$key]['duration'] = '';
                if ($this->getCenterName() !== strtolower(trim($value['destination']))) {                    
                    if(isset($this->arr_distance['distance'])){
                        $result['documents'][$key]['distance'] = $this->arr_distance['distance'][$distanceCounter];
                    }
                    if(isset($this->arr_duration['duration'])){
                        $result['documents'][$key]['duration'] = $this->arr_duration['duration'][$durationCounter];
                    }
                    $distanceCounter++;
                    $durationCounter++;
                }
            }
        }
        return $result;
    }

    public function attributeNames() {
        
    }

    public function __destruct() {
        unset($this);
    }

}
?>
