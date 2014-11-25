<?php
        require_once('./../include/config.php');
        require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');

        $dbconn = new DbConn;
	$oFeature = new FeatureManagement;

	$selected_feature_id = $_REQUEST['feature_id'];
	$selected_feature_name = "";

	if(!empty($selected_feature_id)){
                unset($feature_result);
                $feature_result = $oFeature->arrGetFeatureDetails($selected_feature_id,$category_id,"","","1");
                $selected_feature_name = constructUrl($feature_result[0]['feature_name']);
        }
	echo $selected_feature_name;
        exit;
