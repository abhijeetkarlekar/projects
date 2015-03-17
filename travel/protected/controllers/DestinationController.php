<?php

class DestinationController extends Controller {

    /**
     * search weekend getaway map
     */
    public function actionWGMap(){
        // include map config
        $map_config = Yii::app()->params['MAP_CONFIG'];
        // method name
        $method = 'fetchWeekendSolrResult';
        // view page
        $view = 'WGMap';
        // map width and height
        $mapWidth = isset($map_config['MAP_WG_WIDTH']) ? $map_config['MAP_WG_WIDTH'] : $_REQUEST['mapWidth'] ;
        $mapHeight = isset($map_config['MAP_WG_HEIGHT']) ? $map_config['MAP_WG_HEIGHT'] : $_REQUEST['mapHeight'] ;
        $params = array(

            'method' => $method,
            'map_width' => $mapWidth,
            'map_height' => $mapHeight,
            'show_distance' => $map_config['SHOW_WG_DISTANCE'],
            'show_duration' => $map_config['SHOW_WG_DURATION']
        );
        $model = DestinationModel::model($params);
        // render view file
        $this->render(
            $view, array(
                'model' => $model
            )
        );
    }

    /**
     * Destination Map
     */
    public function actionDestinationMap(){
        // include map config
        $map_config = Yii::app()->params['MAP_CONFIG'];
        // method name
        $method = 'fetchWeekendSolrResult';
        // view page
        $view = 'destinationMap';
        // map width and height
        $mapWidth = isset($map_config['MAP_WIDGET_WIDTH']) ? $map_config['MAP_WIDGET_WIDTH'] : $_REQUEST['mapWidth'] ;
        $mapHeight = isset($map_config['MAP_WIDGET_HEIGHT']) ? $map_config['MAP_WIDGET_HEIGHT'] : $_REQUEST['mapHeight'] ;
        $params = array(

            'method' => $method,
            'map_width' => $mapWidth,
            'map_height' => $mapHeight,
            'show_distance' => $map_config['SHOW_WIDGET_DISTANCE'],
            'show_duration' => $map_config['SHOW_WIDGET_DURATION']
        );
        $model = DestinationModel::model($params);
//        echo "<pre>"; print_r($model); die('actionDestinationMap');
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

        $_REQUEST['sub_path'] = Yii::app()->params['WEEKENDFROM_URL'];
        // method name
        $method = 'fetchWeekendSolrResult';
        // view page
        $view = 'weekendfrom';
        $params = array(

            'method' => $method,
            'show_distance' => $map_config['SHOW_WEEKENDFROM_DISTANCE'],
            'show_duration' => $map_config['SHOW_WEEKENDFROM_DURATION']
        );
        $model = DestinationModel::model($params);
        $arr = array('model' => $model, 'theme' => DestinationModel::getTheme(),'count' => DestinationModel::getCount(),'currpage' => DestinationModel::getCurrPage(), 'pages' => DestinationModel::getPageNumber(), 'sample' => DestinationModel::getPagingString(), 'seourl' => DestinationModel::getSeoUrl());
        // render view file
        $this->render(
                $view, $arr
        );
    }

    /**
     * weekend action
     */
    public function actionDiscover() {

        $_REQUEST['sub_path'] = Yii::app()->params['DISCOVER_URL'];
        // method name
        $method = 'fetchDiscoverSolrResult';
        // view page
        $view = 'discover';

        $params = array(

            'method' => $method,
            'show_distance' => $map_config['SHOW_DISCOVER_DISTANCE'],
            'show_duration' => $map_config['SHOW_DISCOVER_DURATION']
        );
        $model = DestinationModel::model($params);

        $arr = array('model' => $model, 'state' => DestinationModel::getState(), 'count' => DestinationModel::getCount(),'currpage' => DestinationModel::getCurrPage(),'theme' => DestinationModel::getTheme(), 'pages' => DestinationModel::getPageNumber(),
            'sample' => DestinationModel::getPagingString(), 'seourl' => DestinationModel::getSeoUrl());
        // render view file
        $this->render(
                $view, $arr
        );
    }

      /**
     * weekend action
     */
    public function actionExplore() {

        $_REQUEST['sub_path'] = Yii::app()->params['EXPLORE_URL'];

        // method name
        $method = 'fetchExploreSolrResult';
        // view page
        $view = 'explore';
        $params = array(

            'method' => $method,
            'show_distance' => $map_config['SHOW_EXPLORE_DISTANCE'],
            'show_duration' => $map_config['SHOW_EXPLORE_DURATION']
        );
        $model = DestinationModel::model($params);
        $arr = array('model' => $model, 'state' => DestinationModel::getState(), 'theme' => DestinationModel::getTheme(), 'seourl' => DestinationModel::getSeoUrl());
        // render view file
        $this->render(
                $view, $arr
        );
    }

        /**
         * This is the action to handle external exceptions.
         */
        public function actionError()
        {
                if($error=Yii::app()->errorHandler->error)
                {
                        if(Yii::app()->request->isAjaxRequest)
                                echo $error['message'];
                        else
                                $this->render('error', $error);
                }
        }

}
?>
