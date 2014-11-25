<?php
//http://auto-dev.int.india.com/api/average_rating_api.php?brand_id=x&product_name_id=y&product_id=z&user_review_id=m&limit=k
//ini_set("display_errors",1);
require_once('/var/www/projects/gadgets/include/config.php');
#require_once('/var/www/projects/betav2.oncars.in/include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'reviews.class.php');
require_once(CLASSPATH.'user.class.php');
require_once(CLASSPATH.'user_review.class.php');
require_once(CLASSPATH.'Utility.php');

$dbconn         =       new DbConn;
$oBrand         =       new BrandManagement;
$category       =       new CategoryManagement;
$oProduct       =       new ProductManagement;
$oReview        =       new reviews;
$obj_user            =  new user;
$userreview          =  new USERREVIEW;

/*foreach($_REQUEST as $key=>$value){
        error_log($key."===".$value);
}*/
//print"<pre>";print_r($_REQUEST);print"</pre>";die();
if(is_array($argv)){
        argvtoRequest($argv);
}
$category_id = SITE_CATEGORY_ID;
$brand_id = $_REQUEST['brand_id'] ? $_REQUEST['brand_id'] : "";
$product_name_id = $_REQUEST['product_name_id'] ? $_REQUEST['product_name_id'] : "";
$product_id = $_REQUEST['product_id'] ? $_REQUEST['product_id'] : "";
$user_review_id = $_REQUEST['user_review_id'] ? $_REQUEST['user_review_id'] : "";
$limit = $_REQUEST['limit'] ? $_REQUEST['limit'] : "";
$xml = "";
if(!empty($brand_id)){
        $aBrandDetail = $oBrand->arrGetBrandDetails($brand_id,$category_id);
}
$brand_name=$aBrandDetail[0]['brand_name'];
if(!empty($product_name_id)){
        $productNameInfo = $oProduct->arrGetProductNameInfo($product_name_id,$category_id,"","",1,"","");
}
$model_name=$productNameInfo[0]['product_info_name'];
if(!empty($product_id)){
        $aProductDetail=$oProduct->arrGetProductDetails($product_id,$category_id,"","1");
}
$variant_name= $aProductDetail[0]['variant'];
unset($variantUrlYear);
$variantUrlYear = buildYear($aProductDetail[0]['arrival_date'],$aProductDetail[0]['discontinue_date']);
if((empty($product_name_id)) && (empty($product_id))){
        $xml.="<AVERAGE_USER_RATING_API>";
        $xml.="<MESSAGE>Data Insufficient</MESSAGE>";
        $xml.="</AVERAGE_USER_RATING_API>";
        header('Content-type: text/xml');
        echo $xml;
        exit;
}
$message = "";
$xml.="<AVERAGE_USER_RATING_API>";
unset($result);
$user_review_result = $userreview->arrGetUserReviewDetails($user_review_id,"","","","",$brand_id,$category_id,$product_name_id, $product_id,"1");

//print"<pre>";print_r($user_review_result);print"</pre>";die();

$cnt = sizeof($user_review_result);
$xml.="<COUNT><![CDATA[$cnt]]></COUNT>";
if($cnt == 0){
        $message = "Sorry ! There are no user reviews currently for this particular variant. If you are the owner/ driver of this car, then be the first one to write a review";
        $xml.="<MESSAGE><![CDATA[$message]]></MESSAGE>";
	$xml.="<LIST></LIST>";
}else{
	$exterior_rating_count = Array();
	$interior_rating_count = Array();
	$performance_rating_count = Array();
	$service_rating_count = Array();
	$average_rating_count = Array();
	for($i=0;$i<$cnt;$i++){
		$user_review_id = $user_review_result[$i]["user_review_id"];
		if(!empty($user_review_id)){
			$ratingresult = $userreview->arrGetUserQnA('','',$user_review_id,"1");
		}
                $ratingcnt = sizeof($ratingresult);
                //$xml .= "<USER_RATING_MASTER>";
		$avg_rating_count = 0;
                for($rating=0;$rating<$ratingcnt;$rating++){
			$que_id = $ratingresult[$rating]['que_id'];
                        $que_result = $userreview->arrGetQuestions($que_id);
                        $ratingresult[$rating]['quename'] = $que_result[0]['quename'];
                        $answer = $ratingresult[$rating]['answer'];
                        $ansArr = explode(",",$answer);
                        $gradeCnt = $ratingresult[$rating]['grade'];
			if($que_id == 1){
				array_push($exterior_rating_count, $gradeCnt);
			}else if($que_id == 2){
				array_push($interior_rating_count, $gradeCnt);
			}else if($que_id == 3){
				array_push($performance_rating_count, $gradeCnt);
			}else if($que_id == 4){
				array_push($service_rating_count, $gradeCnt);
			}
			$avg_rating_count = $avg_rating_count+$gradeCnt;
                        $ratingresult[$rating]['grade'] = $gradeCnt;
                        /*$xml .= "<USER_RATING_MASTER_DATA>";
                        $ratingresult[$rating] = array_change_key_case($ratingresult[$rating],CASE_UPPER);
                        foreach($ratingresult[$rating] as $k=>$v){
                        	$xml .= "<$k><![CDATA[$v]]></$k>";
                        }
			$xml .= "</USER_RATING_MASTER_DATA>";*/
		}
		if($avg_rating_count > 0){
			$avg_rating_count = $avg_rating_count/$ratingcnt;
			$avg_rating_count = $userreview->reviewRatingslab($avg_rating_count);
		}
		array_push($average_rating_count, $avg_rating_count);
		//$xml .= "</USER_RATING_MASTER>";
	}
	//print"<pre>";print_r($average_rating_count);print"</pre>";exit;
	$all_reviews_tot_rating = 0;
	$all_reviews_avg_rating = 0;
	for($i=0;$i<$cnt;$i++){
		$all_reviews_tot_rating = $all_reviews_tot_rating+$average_rating_count[$i];
	}
	if($all_reviews_tot_rating > 0){
		$all_reviews_avg_rating = $all_reviews_tot_rating/$cnt;
		$all_reviews_avg_rating = $userreview->reviewRatingslab($all_reviews_avg_rating);
          	$all_reviews_avg_rating_proportion = (($all_reviews_avg_rating*100)/10)*2;
	}
	$ext_reviews_tot_rating = 0;
        $ext_reviews_avg_rating = 0;
	$ext_cnt = sizeof($exterior_rating_count);
	for($i=0;$i<$ext_cnt;$i++){
		$ext_reviews_tot_rating = $ext_reviews_tot_rating+$exterior_rating_count[$i];
	}
	if($ext_reviews_tot_rating > 0){
                $ext_reviews_avg_rating = $ext_reviews_tot_rating/$ext_cnt;
		$ext_reviews_avg_rating = $userreview->reviewRatingslab($ext_reviews_avg_rating);
		$ext_reviews_avg_rating_proportion = (($ext_reviews_avg_rating*100)/10)*2;
        }
	$int_reviews_tot_rating = 0;
        $int_reviews_avg_rating = 0;
        $int_cnt = sizeof($interior_rating_count);
        for($i=0;$i<$int_cnt;$i++){
                $int_reviews_tot_rating = $int_reviews_tot_rating+$interior_rating_count[$i];
        }
        if($int_reviews_tot_rating > 0){
                $int_reviews_avg_rating = $int_reviews_tot_rating/$int_cnt;
		$int_reviews_avg_rating = $userreview->reviewRatingslab($int_reviews_avg_rating);
		$int_reviews_avg_rating_proportion = (($int_reviews_avg_rating*100)/10)*2;
        }
	$perf_reviews_tot_rating = 0;
        $perf_reviews_avg_rating = 0;
        $perf_cnt = sizeof($performance_rating_count);
        for($i=0;$i<$perf_cnt;$i++){
                $perf_reviews_tot_rating = $perf_reviews_tot_rating+$performance_rating_count[$i];
        }
        if($perf_reviews_tot_rating > 0){
                $perf_reviews_avg_rating = $perf_reviews_tot_rating/$perf_cnt;
		$perf_reviews_avg_rating = $userreview->reviewRatingslab($perf_reviews_avg_rating);
		$perf_reviews_avg_rating_proportion = (($perf_reviews_avg_rating*100)/10)*2;
        }
	$serv_reviews_tot_rating = 0;
        $serv_reviews_avg_rating = 0;
        $serv_cnt = sizeof($service_rating_count);
        for($i=0;$i<$serv_cnt;$i++){
                $serv_reviews_tot_rating = $serv_reviews_tot_rating+$service_rating_count[$i];
        }
        if($serv_reviews_tot_rating > 0){
                $serv_reviews_avg_rating = $serv_reviews_tot_rating/$serv_cnt;
		$serv_reviews_avg_rating = $userreview->reviewRatingslab($serv_reviews_avg_rating);
		$serv_reviews_avg_rating_proportion = (($serv_reviews_avg_rating*100)/10)*2;
        }
}
/*echo "Exterior--";
print"<pre>";print_r($exterior_rating_count);print"</pre>";
echo "Interior---";
print"<pre>";print_r($interior_rating_count);print"</pre>";
echo "Performance---";
print"<pre>";print_r($performance_rating_count);print"</pre>";
echo "service---";
print"<pre>";print_r($service_rating_count);print"</pre>";
echo "average user rating---";
print"<pre>";print_r($average_rating_count);print"</pre>";
echo "===ext_reviews_avg_rating===".$ext_reviews_avg_rating;
echo "===int_reviews_avg_rating==".$int_reviews_avg_rating;
echo "====perf_reviews_avg_rating===".$perf_reviews_avg_rating;
echo "===serv_reviews_avg_rating===".$serv_reviews_avg_rating;
echo "===all_reviews_avg_rating===".$all_reviews_avg_rating;*/
$ext_reviews_avg_rating = ($ext_reviews_avg_rating > 0) ? $ext_reviews_avg_rating : 0;
$ext_reviews_avg_rating_proportion = ($ext_reviews_avg_rating_proportion > 0) ? $ext_reviews_avg_rating_proportion : 0;

$int_reviews_avg_rating = ($int_reviews_avg_rating > 0) ? $int_reviews_avg_rating :0;
$int_reviews_avg_rating_proportion = ($int_reviews_avg_rating_proportion > 0) ? $int_reviews_avg_rating_proportion : 0;

$perf_reviews_avg_rating = ($perf_reviews_avg_rating > 0) ? $perf_reviews_avg_rating : 0;
$perf_reviews_avg_rating_proportion = ($perf_reviews_avg_rating_proportion > 0) ? $perf_reviews_avg_rating_proportion : 0;

$serv_reviews_avg_rating = ($serv_reviews_avg_rating > 0) ? $serv_reviews_avg_rating :0;
$serv_reviews_avg_rating_proportion = ($serv_reviews_avg_rating_proportion > 0) ? $serv_reviews_avg_rating_proportion : 0;

$all_reviews_avg_rating = ($all_reviews_avg_rating > 0) ? $all_reviews_avg_rating : 0;
$all_reviews_avg_rating_proportion = ($all_reviews_avg_rating_proportion > 0) ? $all_reviews_avg_rating_proportion : 0;

$xml.="<EXT_REVIEWS_AVG_RATING>".$ext_reviews_avg_rating."</EXT_REVIEWS_AVG_RATING>";
$xml.="<EXT_REVIEWS_AVG_RATING_PROPERTION>".$ext_reviews_avg_rating_proportion."</EXT_REVIEWS_AVG_RATING_PROPERTION>";
$xml.="<INT_REVIEWS_AVG_RATING>".$int_reviews_avg_rating."</INT_REVIEWS_AVG_RATING>";
$xml.="<INT_REVIEWS_AVG_RATING_PROPERTION>".$int_reviews_avg_rating_proportion."</INT_REVIEWS_AVG_RATING_PROPERTION>";
$xml.="<PERF_REVIEWS_AVG_RATING>".$perf_reviews_avg_rating."</PERF_REVIEWS_AVG_RATING>";
$xml.="<PERF_REVIEWS_AVG_RATING_PROPERTION>".$perf_reviews_avg_rating_proportion."</PERF_REVIEWS_AVG_RATING_PROPERTION>";
$xml.="<SERV_REVIEWS_AVG_RATING>".$serv_reviews_avg_rating."</SERV_REVIEWS_AVG_RATING>";
$xml.="<SERV_REVIEWS_AVG_RATING_PROPERTION>".$serv_reviews_avg_rating_proportion."</SERV_REVIEWS_AVG_RATING_PROPERTION>";

$xml.="<ALL_REVIEWS_AVG_RATING>".$all_reviews_avg_rating."</ALL_REVIEWS_AVG_RATING>";
$xml.="<ALL_REVIEWS_AVG_RATING_PROPERTION>".$all_reviews_avg_rating_proportion."</ALL_REVIEWS_AVG_RATING_PROPERTION>";

if($all_reviews_avg_rating < 2){
	$all_reviews_avg_grade = "Poor";
}elseif($all_reviews_avg_rating >= 2 and $all_reviews_avg_rating < 3){
	$all_reviews_avg_grade = "Fair";
}elseif($all_reviews_avg_rating >= 3 and $all_reviews_avg_rating < 4){
	$all_reviews_avg_grade = "Average";
}elseif($all_reviews_avg_rating >= 4 and $all_reviews_avg_rating < 5){
	$all_reviews_avg_grade = "Good";
}elseif($all_reviews_avg_rating == 5){
	$all_reviews_avg_grade = "Excellent";
}
$xml.="<ALL_REVIEWS_AVG_GRADE>".$all_reviews_avg_grade."</ALL_REVIEWS_AVG_GRADE>";
$xml.="</AVERAGE_USER_RATING_API>";
header('Content-type: text/xml');
echo $xml;
exit;
