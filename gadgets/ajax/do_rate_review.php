<?php
	// include
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'user_review.class.php');
	// object declaration
	$dbconn = new DbConn;
	$userreview = new USERREVIEW;
	$domain = DOMAIN;
	$category_id = $_REQUEST['category_id'] ? $_REQUEST['category_id'] : SITE_CATEGORY_ID;
	$review_id = $_REQUEST['review_id'];
	$flag = $_REQUEST['flag'];
	$reviewed_status = $_REQUEST['reviewed'] ? $_REQUEST['reviewed'] : 0 ;

	if(!empty($review_id) && !empty($flag) && $reviewed_status!=$review_id){
		$result = $userreview->intInsertUserReviewOptions($review_id,$category_id,$flag);
		setcookie('reviewed',$review_id, time()+3600*24,'/',$domain);
		//print_r($result);
	}else{
		$result = "done";
	}
	echo $result;
	exit;
?>
