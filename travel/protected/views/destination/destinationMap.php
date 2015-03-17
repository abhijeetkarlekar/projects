<?php
if ($model->getResultCount() > 0) {
    // include map config
    $map_config = Yii::app()->params['MAP_CONFIG'];
    // include wp config
    Yii::app()->params['WP_CONFIG'];
    // map widget config
    $arr_map_config = array();
    if (is_array($map_config)) {

        foreach ($map_config as $key => $value) {

            if ($key === 'MAP_WIDGET_CENTER_AND_ZOOM_ON_MARKERS') {

                if (isset($map_config['MAP_WIDGET_CENTER_AND_ZOOM_ON_MARKERS']) && $map_config['MAP_WIDGET_CENTER_AND_ZOOM_ON_MARKERS'] !== NULL) {

                    $arr_map_config['CENTER_AND_ZOOM_ON_MARKERS_MARGIN'] = $value;
                }
                continue;
            }
            $arr_map_config[$key] = $value;
        }

        // map widget
        $this->widget(
                'application.components.mapWidget', array(
            //'model' => $model,
            'map_config' => $arr_map_config,
            'width' => $model->getMapWidth(),
            'height' => $model->getMapHeight(),
            'documents' => $model->getDocumentWithDistDur(),
            'center_name' => $model->getCenterName()
                )
        );
    }
} else {
    ?>
    <figure class="col-sm-12">
        No Result Found.
        <div class="clear"></div> 
    </figure>
    <?php
}
?>
