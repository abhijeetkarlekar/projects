<?php
	require_once('../include/config.php');
	require_once(CLASSPATH.'DbConn.php');
	require_once(CLASSPATH.'user_review.class.php');
	$dbconn = new DbConn;
	$userreview = new USERREVIEW;

	$category_id = $_REQUEST['catid'] ? $_REQUEST['catid'] : SITE_CATEGORY_ID;		
	$totalquestion = $_REQUEST['totalquestion'];
	$fbid = $_REQUEST['fbid'];
	$uname = "";
	$uname = $_REQUEST["rev_username"];
	// $emailid = $_REQUEST["rev_emailid"];
	/*	if($uname == ""){
		if(!empty($fbid)){
			$uname = trim($_REQUEST['username']);
		}else{
			$uname1[] = trim($_COOKIE['first_name']) ? trim($_COOKIE['first_name']) : trim($_REQUEST['fname']);
			$uname1[] = trim($_COOKIE['last_name']) ? trim($_COOKIE['last_name']) : trim($_REQUEST['lname']);
			$uname = implode(" ",$uname1);
			$uname = trim($_REQUEST['username']);
		}
	}*/
	$uname = trim($_REQUEST['username']);
	$emailid = ($_REQUEST["rev_emailid"]=='' || $_REQUEST["rev_emailid"]=='undefined') ? $_REQUEST['email'] : $_REQUEST["rev_emailid"] ;
	$title = trim($_REQUEST['title']);
	$add_review = $_REQUEST['add_review'];
	$uid = $_REQUEST['uid'] ? $_REQUEST['uid'] : '0';
	$brand_id = !empty($_REQUEST['router_brand_id']) ? $_REQUEST['router_brand_id'] : $_REQUEST['brand_id'];
	$product_name_id = !empty($_REQUEST['router_model_id']) ? $_REQUEST['router_model_id'] : $_REQUEST['product_info_id'];
	$product_id = !empty($_REQUEST['router_product_id']) ? $_REQUEST['router_product_id'] : $_REQUEST['product_id'];
	$rev_product_review_url = $_REQUEST['user_rev_url'];
	$rev_user_review_id = $_REQUEST['user_rev_id'];
	$is_review_added = $_REQUEST['reviewAdded'];

	if(!empty($uname) && !empty($add_review)){
			$request_param['title'] = htmlentities($title,ENT_QUOTES);
			$request_param['user_name'] = htmlentities($uname,ENT_QUOTES);
			$request_param['email'] = htmlentities($emailid,ENT_QUOTES);
			$brand_id = trim($_REQUEST['brand_id']);
			$request_param['brand_id'] = htmlentities($brand_id,ENT_QUOTES);
			$product_info_id = trim($_REQUEST['product_info_id']);
			$request_param['product_info_id'] = htmlentities($product_info_id,ENT_QUOTES);
			$product_id = trim($_REQUEST['product_id']);
			$request_param['product_id'] = htmlentities($product_id,ENT_QUOTES);
			$request_param['uid'] = $_REQUEST['uid'];
			$request_param['category_id'] = $category_id;
			$request_param['review_agree'] = $_REQUEST['review_agreed_hd'];
			// print"<pre>";print_r($request_param);print"</pre>";echo $product_info_id ."!=". $is_review_added; die();
			///if($product_info_id != $is_review_added){
				$user_review_id = $userreview->intInsertUserReviewInfo($request_param);
				setcookie("reviewAdded",$product_info_id);
				if(!empty($uname)){
					//$emailid = trim($_REQUEST['usr_review_emailid']);
					setcookie('rev_username',$uname, time()+3600*24,'/',$domain);
					setcookie('rev_emailid',$emailid, time()+3600*24,'/',$domain);
				}
			//}
			unset($request_param);
			$msg = "";
			if(!empty($user_review_id)){
				$msg = "Your Review has been successfully posted.It will go live after moderation.<br /><br />";
				if(empty($rev_product_review_url)){
					$rev_product_review_url = WEB_URL;
				}
				
			}
			$request_param['user_review_id'] = htmlentities($user_review_id,ENT_QUOTES);
			for($i=1;$i<= 8;$i++){
				$que_id = $_REQUEST['que_id_'.$i];
				if(!empty($que_id)){
				$request_param['que_id'] = htmlentities($que_id,ENT_QUOTES);
				$result = $userreview->arrGetQuestions($que_id);
				$formula = $result[0]['algorithm'];
				$totalanscnt = $_REQUEST['total_ans_ques_'.$que_id];
				for($ans=1;$ans<=$totalanscnt;$ans++){
					$ans_id = $_REQUEST['ans_'.$que_id.'_'.$ans];
					$usrReviewedArr['{ans'.$ans.'}'] = $_REQUEST['user_review_'.$que_id.'_'.$ans_id];				
				}

				$grade = 0;
				if(sizeof($usrReviewedArr) > 0){                                                			   
				   $request_param['answer'] = implode(",",$usrReviewedArr);
				   $grade = strtr($formula,$usrReviewedArr);
				  // echo $grade; die();
				   $grade = round(parse_mathematical_string($grade));
				   $gradeArr[] = $grade."*{overallans".$i."}";
				   $request_param['grade'] = $grade;
				   $request_param['is_rating'] = '1';
				   $request_param['is_comment_ans'] = '0';
				}else{
					$answer = trim($_REQUEST['comment_'.$que_id]);
					$request_param['answer'] = htmlentities($answer,ENT_QUOTES);
					$request_param['grade'] = $grade;
					$request_param['is_rating'] = '0';
					$request_param['is_comment_ans'] = '1';
				}
				unset($usrReviewedArr);
				//print_r($request_param); #die();
				$user_review_ans_id = $userreview->intInsertUserReviewAnswer($request_param);
				}
			}
			//start code to calculate over all grade and insert into the db.
			$overall_formulaArr = array("{overallans1}"=>"2","{overallans2}"=>"4","{overallans3}"=>"2","{overallans4}"=>"3");
			$gradeformulaStr = implode('+',$gradeArr);
			$overallformulaStr = implode('+',$overall_formulaArr);
			$overall_formula = '(('.$gradeformulaStr.')/('.$overallformulaStr.'))';
			$overallgrade = strtr($overall_formula,$overall_formulaArr);
			$overallgrade = round(parse_mathematical_string($overallgrade));
			$overall_param = array('uid'=>$uid,'overallgrade'=>$overallgrade,'user_review_id'=>$user_review_id);
			
			$overall_id = $userreview->intInsertOverallRating($overall_param);
			#die();
			echo $msg;
		}
?>
	
