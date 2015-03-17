<?php
if ($model->getResultCount() > 0) {
    // include map config
    $map_config = Yii::app()->params['MAP_CONFIG'];
    // include wp config
    Yii::app()->params['WP_CONFIG'];
    // documents
    $document = $model->getDocumentWithDistDur();
    ?>
    <!-- left listing -->
    <aside class="map-view-l col-sm-2 ">
        <div class="map-h">
            <h2>Destinations to explore:</h2>
            <p><?php echo $model->getResultCount() ?></p>
        </div>
        <?php
        $show_in_left_pane = $map_config['LEFT_PANE_RECORDS'];
        for ($dest_list_counter = 0; $dest_list_counter < $show_in_left_pane; $dest_list_counter++) {
            ?>
            <figure>
                <a href="<?php echo $document['documents'][$dest_list_counter]['url']; ?>">
                    <img src="<?php echo $document['documents'][$dest_list_counter]['image_small']; ?>" />
                </a>
                <figcaption>
                    <?php echo $document['documents'][$dest_list_counter]['destination']; ?>
                </figcaption>
            </figure>
        <?php } ?>
    </aside>

    <aside class="map-view-r col-sm-10">
        <?php
        // map widget config
        $arr_map_config = array();
        if (is_array($map_config)) {

            foreach ($map_config as $key => $value) {

                if ($key === 'MAP_WG_CENTER_AND_ZOOM_ON_MARKERS') {

                    if (isset($map_config['MAP_WG_CENTER_AND_ZOOM_ON_MARKERS']) && $map_config['MAP_WG_CENTER_AND_ZOOM_ON_MARKERS'] !== NULL) {

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
                'documents' => $document,
                'center_name' => $model->getCenterName()
                    )
            );
        }
        ?>
    </aside>
    <?php
} else {
    ?>
    <figure class="col-sm-12">
        No Result Found.
        <div class="clear"></div> 
    </figure>
    <?php
}
?>
<?php
// below map destination listing
if ($model->getResultCount() > 0) {
    ?>
    <div class="clear"></div>
    <section class="map-list-bottom">
        <aside class="map-view-l col-sm-2 ">
            <?php
            for ($dest_list_counter; $dest_list_counter < count($document['documents']); $dest_list_counter++) {
                ?>
                <figure>
                    <a href="<?php echo $document['documents'][$dest_list_counter]['url']; ?>">
                        <img src="<?php echo $document['documents'][$dest_list_counter]['image_small'] ?>" />
                    </a>
                    <figcaption>
                        <?php echo $document['documents'][$dest_list_counter]['destination']; ?>
                    </figcaption>
                </figure>
                <?php
            }
            ?>
        </aside>
        <div class="clear"></div>
    </section>
<?php } ?>