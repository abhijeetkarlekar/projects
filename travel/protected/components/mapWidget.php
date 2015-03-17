<?php

/**
 * map widget class
 */
class Mapwidget extends CWidget {

    public $map_config = array();
    public $width;
    public $height;
    public $documents;
    public $center_name;

    public function run() {

        $this->render('mapWidget');
    }

}

?>