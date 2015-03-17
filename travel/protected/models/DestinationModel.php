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
    private $is_error = FALSE;

    /**
     * error message
     */
    private $arr_disp_error_msg = array();

    /**
     * Array Error Messages
     */
    private $arr_error_msg = array(
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
     */
     /* 
     private $dma_mode = array(
        'walking',
        'bicycling',
        'driving'
     );
     */
    /**
     * map center
     */
     // private $map_center = NULL;
     
    /**
     * map center
     */
    private $center_name = NULL;

    /**
     * map center lat
     */
    private $center_lat = NULL;

    /**
     * map center long
     */
    private $center_long = NULL;

    /**
     * distance matrix multidimensional array
     */
    private $distance_matrix = array();

    /**
     * distance arr
     */
    private $arr_distance = array();

    /**
     * duration arr
     */
    private $arr_duration = array();

    /**
     * distance Matrix Api with get params
     */
    private $distance_matrix_api = NULL;

    /**
     * map width
     */
    private $map_width = 400;

    /**
     * map height
     */
    private $map_height = 300;

    /**
     * show distance
     */
    public $show_distance = FALSE;

    /**
     * show duration
     */
    public $show_duration = FALSE;

    /**
     * display theme name
     */
    private static $disp_theme = '';

    /**
     * page number
     */
    private static $page_number = 0;

    /**
     * page number
     */
    private static $count = 0;

    /**
     * page number
     */
    private static $currpage = 0;

    /**
     * paging string
     */
    private static $paging_string = '';

    /**
     * seo url string
     */
    private static $seo_url_string = '';

    /**
     * state
     */
    private static $state = '';

    /**
     * solr result
     */
    private $solr_result = '';

    /**
     * map config
     */
    private $map_config = array();
    
    /**
     * weekend default distance
     */
    private $weekend_default_distance = 300;

    public function __construct($params) {

        Yii::app()->params['WP_CONFIG'];
        $this->map_config = Yii::app()->params['MAP_CONFIG'];
        
        if($this->map_config['WEEKEND_DEFAULT_DISTANCE']!=NULL && isset($this->map_config['WEEKEND_DEFAULT_DISTANCE'])){
            $this->weekend_default_distance = $this->map_config['WEEKEND_DEFAULT_DISTANCE'];
        }
        $method = $params['method'];
        $map_width = $params['map_width'];
        $map_height = $params['map_height'];
        $show_distance = $params['show_distance'];
        $show_duration = $params['show_duration'];

        if(empty($map_width) && $map_width==NULL){

            $map_width = $this->map_width;
        }
        if(empty($map_height) && $map_height==NULL){

            $map_height = $this->map_height;
        }
        $this->setMapWidth($map_width);
        $this->setMapHeight($map_height);
        // show distance
        if($show_distance===TRUE){

            $this->show_distance = $show_distance ;
        }
        // show duration
        if($show_duration===TRUE){

            $this->show_duration = $show_duration ;
        }
        // check if passed method exists
        if(!method_exists($this, $method)){

            $method = 'fetchWeekendSolrResult';
        }
        #die( "<br/> method " . $dataMethod . " exists." );
        call_user_func( array( $this, $method ) );
        $this->response = NULL;        
        if($this->map_config['DM_API']!=NULL && isset($this->map_config['DM_API'])){

            $this->dma_api = $this->map_config['DM_API'];
        }
        if($this->map_config['DM_API_KEY']!=NULL && isset($this->map_config['DM_API_KEY'])){

            $this->dma_api_key = $this->map_config['DM_API_KEY'];
        }
        // setting center name
        if(!empty($_REQUEST['post_name'])){
            // set center name
            $this->setCenterName(trim($_REQUEST['post_name']));
        }
    }

    public function fetchExploreSolrResult() {
        $seourl = array();
        $near_by_places = NULL;
        $events = NULL;
        $month = NULL;
        $stateparams = array();
        $search_params = array();
        $queryStrParams = NULL;
        $seourl[] = WEB_URL;
        $seourl[] = DISCOVER_URL;

        $theme = !empty($_REQUEST['theme']) ? $_REQUEST['theme'] : "";
        $themeparams = array();
        $themedisp = NULL;
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
        $destinationdisp = NULL;
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
        $monthdisp = NULL;
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
        $themeparam = NULL;
        $queryParams[] = 'sort=' . urlencode('destination asc');
        $queryParams[] = 'fl=*,score';
        $queryParams[] = 'wt=json';
        $queryParams[] = 'indent=true';
        if (($queryStrParams != NULL) && !empty($queryStrParams)) {
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
                if (($queryStrParams != NULL) && !empty($queryStrParams)) {
                    $travelSolr = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
                }
                $xmlStr = @file_get_contents($travelSolr);
                $this->solr_result = json_decode($xmlStr, true);
                self::$disp_theme = $themedisp;
                self::$state = $state;
                self::$seo_url_string = implode("/", $seourl);
            }
        } else {
            $this->setErr('empty');
        }
    }

    public function fetchDiscoverSolrResult() {
	#print_r($_REQUEST); die();
        $seourl = array();
        $near_by_places = NULL;
        $events = NULL;
        $month = NULL;
        $stateparams = array();
        $search_params = array();
        $queryStrParams = NULL;
        $seourl[] = WEB_URL;
        $seourl[] = DISCOVER_URL;

        $theme = !empty($_REQUEST['theme']) ? $_REQUEST['theme'] : "";
        $themeparams = array();
        $themedisp = NULL;
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
        $destinationdisp = NULL;
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
        $monthdisp = NULL;
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
        $themeparam = NULL;
        $queryParams[] = 'sort=' . urlencode('destination asc');
        $queryParams[] = 'fl=*,score';
        $queryParams[] = 'wt=json';
        $queryParams[] = 'indent=true';
        if (($queryStrParams != NULL) && !empty($queryStrParams)) {
            $queryParams[] = 'q=' . $queryStrParams;
            $limitParams[] = 'start=0';
            $limitParams[] = 'rows=1';
            $travelSolrCnt = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
        }
       // echo " travelSolrCnt - ". $travelSolrCnt ; die();
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

                $yiipages->setPageSize($yiiperpage)-1;
                $yiipages->setCurrentPage($yiipage - 1); 
                //$getcur = $yiipages->getCurrentPage(false);
                $getcur = $yiipage;
                $yiistart = $yiipages->getOffset();
                $yiiend = ($yiipages->offset + $yiipages->limit <= $yiiitem_count ? $yiipages->offset + $yiipages->limit : $yiiitem_count);
                
                $paging = range($yiipages->offset + 1, $yiiend);
                $limitParams[] = "start=$yiistart";
                $limitParams[] = "rows=$yiiperpage";
                
                if (($queryStrParams != NULL) && !empty($queryStrParams)) {
                    $travelSolr = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
                }
                //echo " travelSolr - " . $travelSolr; die();
                //print_r($yiipages);
                $xmlStr = @file_get_contents($travelSolr);
                //print_r($xmlStr); die("END2");
                $this->solr_result = json_decode($xmlStr, true);
                self::$disp_theme = $themedisp;
                self::$state = $state;
                self::$count = $yiiitem_count;
                self::$currpage = $getcur;
                self::$page_number = $yiipages;
                self::$paging_string = $paging;
                self::$seo_url_string = implode("/", $seourl);
            }
        } else {
            $this->setErr('empty');
        }
    }

    public function fetchWeekendSolrResult() {
        #print_r($_REQUEST); die("ssd");
        $ltlongpt = NULL;
        $seourl = array();
        $near_by_places = NULL;
        $events = NULL;
        $month = NULL;
        $stateparams = array();
        $search_params = array();
        $queryStrParams = NULL;
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

                if(strpos($ltlongpt, ',')){
                        $arrltlongpt = explode(",", $ltlongpt);
                        $this->setCenterLat(trim($arrltlongpt[0]));
                        $this->setCenterLong(trim($arrltlongpt[1]));
                } else {
                        // set center lat long for calculating distance and duration
                        $this->setCenterLat($aSolrDataLat['response']['docs'][0]['lat']);
                        $this->setCenterLong($aSolrDataLat['response']['docs'][0]['long']);
                }
        }
	} else {

		$ltlongpt = trim($_REQUEST['lat']) . ',' . trim($_REQUEST['long']) ;
        // set center lat long for calculating distance and duration
        $this->setCenterLat(trim($_REQUEST['lat']));
        $this->setCenterLong(trim($_REQUEST['long']));
	}

        $destinationparams = array();
        $destinationdisp = NULL;
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
        $monthdisp = NULL;
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
        $distance = !empty($_REQUEST['distance']) ? $_REQUEST['distance'] : $this->weekend_default_distance;
        $seourl[] = "distance-$distance-km";
        $yiipage = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        if($yiipage==1){$yiipage='';}
        $limitParams = Array();
        $themeparam = NULL;
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
        if (($queryStrParams != NULL) && !empty($queryStrParams)) {
            if($_REQUEST['v'] == 'destinationMap'){
                $queryParams[] = 'fq=(contained_in:"' . urlencode(str_replace("-", " ", $destination)) . '"'.urlencode(' OR ').'destination:"' . urlencode(str_replace("-", " ", $destination)) . '")'.urlencode(' AND ').$queryStrParams ;
            } else {
                $queryParams[] = 'fq=destination_type:destinations'.urlencode(' AND ').'-contained_in:"' . urlencode(str_replace("-", " ", $destination)) . '"' . urlencode(' OR ').'-destination:"' . urlencode(str_replace("-", " ", $destination)) . '"'.urlencode(' AND ').$queryStrParams ;
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
            #echo $travelSolrCnt ; die("here");
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
                if($_REQUEST['v'] == 'destinationMap'){
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
                if (($queryStrParams != NULL) && !empty($queryStrParams)) {
                    $travelSolr = SOLR_PATH . '?' . implode('&', array_merge($queryParams, $limitParams));
                }
                #echo $travelSolr; //die();
                $xmlStr = @file_get_contents($travelSolr);
                #print_r(json_decode($xmlStr, true)); die('fetchWeekendSolrResult');
                $this->solr_result = json_decode($xmlStr, true);
                self::$count = $yiiitem_count;
                self::$currpage = $getcur;
                self::$disp_theme = $themedisp;
                self::$page_number = $yiipages;
                self::$paging_string = $paging;
                self::$seo_url_string = implode("/", $seourl);
            }
        } else {

            $this->setErr('empty');
        }
    }

	public function getSolrDestination(){

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

			$this->solr_result = $aSolrResult;
		} else {

			$this->setErr('empty');
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
        return self::$disp_theme;
    }

    public static function getPageNumber() {
        return self::$page_number;
    }

    public static function getPagingString() {
        return self::$paging_string;
    }

    public static function getSeoUrl() {
        return self::$seo_url_string;
    }

    public static function model($params, $className = __CLASS__) {

        $method = $params['method'];
        $key = $className . '::' . md5($method);

        if (isset(self::$_models[$key])) {

            return self::$_models[$key];
        } else {
	    
            $model = self::$_models[$key] = new $className($params);
            $model->response = $model->solr_result;
            return $model;
        }
    }

	public function setMapWidth($mapWidth){

		$this->map_width = $mapWidth;
	}

	public function getMapWidth(){

		return $this->map_width;
	}

	public function setMapHeight($mapHeight){

		$this->map_height = $mapHeight;
	}

	public function getMapHeight(){

		return $this->map_height;
	}

    public function setCenterName($centerName) {

        $this->center_name = strtolower(trim(str_replace('-',' ',$centerName)));
    }

    public function getCenterName() {

        return $this->center_name;
    }

    public function setCenterLat($centerLat) {

        $this->center_lat = strtolower(trim($centerLat));
    }

    public function getCenterLat() {

        return $this->center_lat;
    }

    public function setCenterLong($centerLong) {

        $this->center_long = strtolower(trim($centerLong));
    }

    public function getCenterLong() {

        return $this->center_long;
    }

    private function setErr($error) {

        $this->is_error = TRUE;
        if($this->arr_error_msg[$error]){

            $this->arr_disp_error_msg[] = $error = $this->arr_error_msg[$error];

        } else {

            $this->arr_disp_error_msg[] = $error;
        }
        error_log( Yii::app()->name . ": " . $error ) ;
    }

    public function getErr() {

        return array_unique($this->arr_disp_error_msg);
    }

    public function isError() {

        return $this->is_error;
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

            $this->setErr($this->response['error']['code'] . ' - ' . $this->response['error']['msg']);
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

            $this->setErr($this->response['error']['code'] . ' - ' . $this->response['error']['msg']);
        } else {

            if ($this->isError() === FALSE) {
                foreach ($this->response as $key => $value) {
                    if ($key == 'response') {
                        $numFound = $this->response[$key]['numFound'];
                        if ($numFound > 0) {
                            foreach ($value['docs'] as $numkey => $destination) {
                                $result['documents'][] = $destination;                                
                            }
                        } else {
                            $this->setErr('empty');
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function getDistanceMatrix() {

        $result = $this->getDocuments();

        if ($this->show_distance === FALSE && $this->show_duration === FALSE) {

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

            $this->setErr('empty');
//$result['error_msg'] = $this->error_messages['empty'];
        }
        return $result;
    }

    public function setDMApi() {
// reset if exists
        $this->distance_matrix_api = NULL;
        if (count($this->distance_matrix) > 0) {

            if(count($this->distance_matrix['origin']) > 0){

                $origin = implode("|", $this->distance_matrix['origin']);
            } else {

                if(count($this->distance_matrix['origin']) <= 0 ){
                    $this->distance_matrix['origin'] = $this->getCenterLat() . "," . $this->getCenterLong();
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

        if ($this->distance_matrix_api != NULL) {

            $dma_jsonString = Yii::app()->curl->run($this->distance_matrix_api);
            if ($dma_jsonString->hasErrors()) {

                $this->setErr($dma_arrResult['status'] . ' - ' . $dma_arrResult['error_message']);
                $error = $dma_jsonString->getErrors();
                $this->setErr($error->code . ' - ' . $error->message);
            } else {

                $dma_arrResult = json_decode($dma_jsonString->getData(), true);
                if (isset($dma_arrResult['error_message'])) {

                    $this->setErr($dma_arrResult['status'] . ' - ' . $dma_arrResult['error_message']);
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
                            if ($this->show_distance === TRUE) {

                                $this->setDistanceArr($arrDistance);
                            }
                            if ($this->show_duration === TRUE) {

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
