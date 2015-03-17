<?php

class DestinationController extends Controller {

    /**
     * search map
     */
    public function actionMapSearch(){
        // include map config
        $map_config = Yii::app()->params['MAP_CONFIG'];
        // predifined params
        $json = null;
        $view = 'mapSearch';
        // map width and height
        $mapWidth = isset($_REQUEST['mapWidth']) ? $_REQUEST['mapWidth'] : $map_config['MAP_SEARCH_WIDTH'] ;
        $mapHeight = isset($_REQUEST['mapHeight']) ? $_REQUEST['mapHeight'] : $map_config['MAP_SEARCH_HEIGHT'] ;
        DestinationModel::setMapWidth($mapWidth);
        DestinationModel::setMapHeight($mapHeight);
        // show distance
        DestinationModel::$showDistance = isset($map_config['SHOW_SEARCH_DISTANCE']) ? $map_config['SHOW_SEARCH_DISTANCE'] : FALSE ;
        // show duration
        DestinationModel::$showDuration = isset($map_config['SHOW_SEARCH_DURATION']) ? $map_config['SHOW_SEARCH_DURATION'] : FALSE ;
        DestinationModel::fetchWeekendSolrResult();
        // pass request params to model
        $model = DestinationModel::model($json);
        // render view file
        $this->render(
                $view, array(
            'model' => $model
                )
        );
    }

    /**
     * map widget
     */
    public function actionMapWidget(){
        // include map config
        $map_config = Yii::app()->params['MAP_CONFIG'];
        // predifined params
        $json = null;
        $view = 'mapWidget';
        // map width and height
        $mapWidth = isset($_REQUEST['mapWidth']) ? $_REQUEST['mapWidth'] : $map_config['MAP_WIDGET_WIDTH'] ;
        $mapHeight = isset($_REQUEST['mapHeight']) ? $_REQUEST['mapHeight'] : $map_config['MAP_WIDGET_HEIGHT'] ;
        DestinationModel::setMapWidth($mapWidth);
        DestinationModel::setMapHeight($mapHeight);
        // show distance
        DestinationModel::$showDistance = isset($map_config['SHOW_WIDGET_DISTANCE']) ? $map_config['SHOW_WIDGET_DISTANCE'] : FALSE ;
        // show duration
        DestinationModel::$showDuration = isset($map_config['SHOW_WIDGET_DURATION']) ? $map_config['SHOW_WIDGET_DURATION'] : FALSE ;
        // fetch result from solr
        DestinationModel::fetchWeekendSolrResult();
        // pass request params to model
        $model = DestinationModel::model($json);
        //echo "<pre>"; print_r($model); die();
        // render view file
        $this->render(
                $view, array(
            'model' => $model
                )
        );
    }

    /**
     * weekend action
     */
    public function actionWeekendFrom() {
        #print_r($_REQUEST); die();
        $json = null;
        $_REQUEST['sub_path'] = Yii::app()->params['WEEKENDFROM_URL'];

        DestinationModel::fetchWeekendSolrResult();
        // show distance
        DestinationModel::$showDistance = FALSE;
        // show duration
        DestinationModel::$showDuration = FALSE;
        // pass request params to model
        $model = DestinationModel::model($json);
        // render view file 'protected/views/destination/destination.php'
        $arr = array('model' => $model, 'theme' => DestinationModel::getTheme(),'count' => DestinationModel::getCount(),'currpage' => DestinationModel::getCurrPage(), 'pages' => DestinationModel::getPageNumber(), 'sample' => DestinationModel::getPagingString(), 'seourl' => DestinationModel::getSeoUrl());
        //$arr = array_merge($arr,$arrerr);
        //print "<pre>"; print_r($arr); die();
        $this->render('weekendfrom', $arr);
    }

    /**
     * weekend action
     */
    public function actionDiscover() {
        $json = null;
        $_REQUEST['sub_path'] = Yii::app()->params['DISCOVER_URL'];
        DestinationModel::fetchDiscoverSolrResult();
        // show distance
        DestinationModel::$showDistance = FALSE;
        // show duration
        DestinationModel::$showDuration = FALSE;
        // pass request params to model        
        $model = DestinationModel::model($json);
        
        // render view file 'protected/views/destination/destination.php'
        //$arr = array('model' => $model, 'state' => $statedisp,'theme' => $themedisp,'distance' => $distance, 'item_count' => $yiiitem_count, 
        //'page_size' => $yiiperpage, 'items_count' => $yiiitem_count, 'pages' => $yiipages, 'sample' => $paging,'seourl'=>$seourlstr);
        $arr = array('model' => $model, 'state' => DestinationModel::getState(), 'count' => DestinationModel::getCount(),'currpage' => DestinationModel::getCurrPage(),'theme' => DestinationModel::getTheme(), 'pages' => DestinationModel::getPageNumber(),
            'sample' => DestinationModel::getPagingString(), 'seourl' => DestinationModel::getSeoUrl());
        //$arr = array_merge($arr,$arrerr);
        //print "<pre>"; print_r($arr); die();
        $this->render('discover', $arr);
    }

      /**
     * weekend action
     */
    public function actionExplore() {
        
        $json = null;
        $_REQUEST['sub_path'] = Yii::app()->params['EXPLORE_URL'];
        DestinationModel::fetchExploreSolrResult();
        // show distance
        DestinationModel::$showDistance = FALSE;
        // show duration
        DestinationModel::$showDuration = FALSE;
        // pass request params to model        
        $model = DestinationModel::model($json);
        // render view file 'protected/views/destination/destination.php'
        //$arr = array('model' => $model, 'state' => $statedisp,'theme' => $themedisp,'distance' => $distance, 'item_count' => $yiiitem_count, 
        //'page_size' => $yiiperpage, 'items_count' => $yiiitem_count, 'pages' => $yiipages, 'sample' => $paging,'seourl'=>$seourlstr);
        $arr = array('model' => $model, 'state' => DestinationModel::getState(), 'theme' => DestinationModel::getTheme(), 'seourl' => DestinationModel::getSeoUrl());
        //$arr = array_merge($arr,$arrerr);
        //print "<pre>"; print_r($arr); die();
        $this->render('explore', $arr);
    }

}
?>