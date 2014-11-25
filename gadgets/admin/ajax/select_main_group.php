<?php
	require_once('../../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'feature.class.php');
	$dbconn = new DbConn;
	$feature = new FeatureManagement;
	
	$main_group_id = $_REQUEST['main_group_id'];
	$category_id = $_REQUEST['category_id'];
	$featuregroupnameid = $_REQUEST['featuregroupnameid'];
	$featured_id = $_REQUEST['featured_id'];
	$featuregroup = $_REQUEST['featuregroup'];
	
	$result=$feature->arrFetchFeatureSubGroupDetails("",$main_group_id,$category_id,$status);
	$cnt = sizeof($result);
	
	$html .= '<select name="'.$featuregroupnameid.'" id="'.$featuregroupnameid.'"><option value="">---Select Group---</option>';
	for($i=0;$i<$cnt;$i++){
		$sub_group_id = $result[$i]['sub_group_id'];
		$sub_group_name = html_entity_decode($result[$i]['sub_group_name'],ENT_QUOTES);
		if($featuregroup==$sub_group_id){
			$html .= "<option value='$sub_group_id' selected='yes'>$sub_group_name</option>";
		}else{$html .= "<option value='$sub_group_id'>$sub_group_name</option>";}
	}
	$html .= "</select>";
	echo $html;	
?>
