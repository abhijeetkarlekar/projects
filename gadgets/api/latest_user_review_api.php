<?php
//http://auto-dev.int.india.com/api/latest_user_review_api.php?brand_id=x&product_name_id=y&product_id=z&limit=k
//ini_set('display_errors','On');
require_once('/var/www/projects/gadgets/include/config.php');
#require_once('/var/www/projects/betav2.oncars.in/include/config.php');
//require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'reviews.class.php');
require_once(CLASSPATH.'videos.class.php');

require_once(CLASSPATH.'user.class.php');
require_once(CLASSPATH.'user_review.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'Utility.php');

require_once(CLASSPATH.'curl.class.php');



$dbconn = new DbConn;
$oBrand = new BrandManagement;
$category = new CategoryManagement;
$oProduct = new ProductManagement;
$oReview = new reviews;
$videoGallery = new videos();
$user = new user;
$userreview = new USERREVIEW;
$feature = new FeatureManagement;
$oCurl = new curl;
if(is_array($argv)){
        argvtoRequest($argv);
}


$category_id = SITE_CATEGORY_ID;
$brand_id = $_REQUEST['brand_id'] ? $_REQUEST['brand_id'] : "";
$curr_user_review_id = $_REQUEST['user_review_id'] ? $_REQUEST['user_review_id'] : "";
$product_info_id = $_REQUEST['product_name_id'] ? $_REQUEST['product_name_id'] : "";
$product_id = $_REQUEST['product_id'] ? $_REQUEST['product_id'] : "";
$limit = $_REQUEST['limit'] ? $_REQUEST['limit'] : "";
$start = $_REQUEST['start'] ? $_REQUEST['start'] : "";
$selected_city_id = $_REQUEST['selected_city_id'] ? $_REQUEST['selected_city_id'] : "";
$selected_city_id = $selected_city_id ? $selected_city_id : $_REQUEST["cookie_city_id"];
$xml = "";

unset($user_review_result);
//echo "FFFFFFFFFFFFFFF"; die();
$user_review_result =  $userreview->arrGetUserReviewDetails($curr_user_review_id,"","","","",$brand_id, $category_id, $product_info_id, $product_id, "1",$start,$limit);
//print_r($user_review_result); die();
$count =  $userreview->arrGetUserReviewDetailsCount($curr_user_review_id,"","","","",$brand_id,$category_id,$product_info_id,$product_id);
$resultCnt = sizeof($user_review_result);
$xml .= "<LATEST_USER_REVIEW_MASTER>";
$xml .= "<COUNT>$count</COUNT>";
for($i=0;$i<$resultCnt;$i++){
   $aMBList[] = $user_review_result[$i]["user_review_id"];
}
//$aComParameters = array(); $aMBData = array();
//$aComParameters = Array("title"=>implode(",",$aMBList),"cid"=>USER_REVIEW_VARIANT_CATEGORY_ID,"sid"=>SERVICEID);
//$aMBData = $oCampusDiscussion->getMulThreadParentReplyCnt($aComParameters);
for($i=0;$i<$resultCnt;$i++){
  $user_review_id = $user_review_result[$i]["user_review_id"];
  $uid = $user_review_result[$i]["uid"];
  $title = $user_review_result[$i]["title"];
  $user_name = $user_review_result[$i]["user_name"];
  $email = $user_review_result[$i]["email"];
  $location = $user_review_result[$i]["location"];
  $brand_id = $user_review_result[$i]["brand_id"];
  $category_id = $user_review_result[$i]["category_id"];
  $product_info_id = $user_review_result[$i]["product_info_id"];
  $product_id = $user_review_result[$i]["product_id"];
  $running = $user_review_result[$i]["running"];
  $year_manufacture = $user_review_result[$i]["year_manufacture"];
  $color = $user_review_result[$i]["color"];
  $SERVICEID = SERVICEID;
  $USER_REVIEW_VARIANT_CATEGORY_ID = USER_REVIEW_VARIANT_CATEGORY_ID;
  //$comment_count = $aMBData['data'][$user_review_id][$USER_REVIEW_VARIANT_CATEGORY_ID][$SERVICEID];
  //if(!empty($comment_count) || $comment_count!=0){
    $user_review_result[$i]['comment_count'] = $comment_count;
  //}
  $create_date = $user_review_result[$i]["create_date"];


  if($create_date != "" || $create_date != "0000-00-00 00:00:00"){
    $create_date = date("d F Y",strtotime($create_date));
   $user_review_result[$i]["create_date"] = $create_date;
  }else{
   $user_review_result[$i]["create_date"] = "";
  }
  $res = $oProduct->arrGetProductNameInfo($product_info_id,$category_id,"","","1","","");
    $product_info_name = $res[0]['product_info_name'];
    $category_id = $res[0]['category_id'];
    if (!empty($category_id)) {
        $category_result = $category->arrGetCategoryDetails($category_id);
    }
    $category_seo_path = $category_result[0]['seo_path'];
    $user_review_result[$i]["product_info_name"] = $product_info_name;
    /*$image_path=$res["0"]["image_path"];
    if(!empty($image_path)){
      $image_path = resizeImagePath($image_path,"87X65",$aModuleImageResize,$video_img_id);
      $image_path = $image_path ? CENTRAL_IMAGE_URL.$image_path : '';
    }*/
    if(!empty($brand_id)){
      $brand_result = $oBrand->arrGetBrandDetails($brand_id,$category_id);
      $brand_name = $brand_result[0]['brand_name'];
    }
    $user_review_result[$i]["brand_name"] = $brand_name;

    if(!empty($selected_city_id)){
      $pro_detail = $oProduct->arrGetProductDetails($product_id,$category_id,"",'1',"","","1","","","","","",$selected_city_id);
    }else{
      $pro_detail = $oProduct->arrGetProductDetails($product_id,$category_id,"",'1',"","","1","","","1");
    }

    $image_path=$pro_detail["0"]["image_path"];
    if(!empty($image_path)){
      $image_path = resizeImagePath($image_path,"114X152",$aModuleImageResize,$video_img_id);
      $image_path = $image_path ? CENTRAL_IMAGE_URL.$image_path : '';
      $user_review_result[$i]["image_path"] = $image_path;
    }
    //echo $product_id."<br>";  print_r($pro_detail);   echo "<br>========================<br>";

  if(is_array($pro_detail)){
    $variant_product_name = $pro_detail[0]['variant'];
	unset($variantUrlYear);
	$variantUrlYear = buildYear($pro_detail[0]['arrival_date'],$pro_detail[0]['discontinue_date']);
  }
  $user_review_result[$i]["variant_product_name"] = $variant_product_name;
  if($variant_product_name == ""){
    $user_review_result[$i]["display_product_name"] = $brand_name." ".$product_info_name;
  }else{
    $user_review_result[$i]["display_product_name"] = $brand_name." ".$product_info_name." ".$variant_product_name;
  }
  $fuel_type = "";
    if(!empty($product_id)){
    unset($aProductDetail);
  
       $aProductDetail=$oProduct->arrGetProductDetails($product_id,$category_id,"",'1',"","","1","","","1");
   
	$arrival_date = $aProductDetail[0]['arrival_date'];
	$discontinue_date = $aProductDetail[0]['discontinue_date'];
	$variantUrlYear = buildYear($arrival_date,$discontinue_date);	
    $aOverview = $feature->arrGetSummary($category_id,$product_id,$type="array");
    foreach($aOverview as $key=>$val){
      if(!strpos($key,'Price') && !strpos($key,'Feature') ){
        unset($overviewArr);
        unset($summery);
        foreach($aOverview[$key] as $overviewtitle=>$overviewvalueArr){
        $overviewvalueArr = array_change_key_case($overviewvalueArr,CASE_UPPER);
            if(strtolower($overviewtitle) == strtolower('Fuel type')){
            	$fuel_type = $overviewvalueArr[0];
            }
        }
        unset($overviewvalueArr);
      }
    }
  }
  $user_review_result[$i]['fuel_type'] = $fuel_type;
  unset($seoTitleArr);
  unset($user_review_url);

  $seoTitleArr[] = SEO_WEB_URL;
  $seoTitleArr[] = $category_seo_path;
  $seoTitleArr[] = constructUrl($brand_name);
  $seoTitleArr[] = constructUrl($product_info_name);
  $seoTitleArr[] = constructUrl($variant_product_name);
  if(!empty($variantUrlYear)){
	$seoTitleArr[] = $variantUrlYear;
  }
  $seoTitleArr[] = "user-reviews";
  $user_review_url = implode("/",$seoTitleArr);
  if(!empty($user_review_id)){
     $user_review_url = "$user_review_url?urevid=$user_review_id";
  }
  $user_review_result[$i]["user_review_url"] = $user_review_url;



  //get the reply count
  //$aParameters=Array("title"=>$user_review_id,"cid"=>USER_REVIEW_VARIANT_CATEGORY_ID,"sid"=>SERVICEID);
  //$aMBReplyCnt = $oCampusDiscussion->getMBDetails($aParameters);
  //echo "Data = <pre>"; print_r($aMBReplyCnt); die;
  //$sReplyXml=$oCampusDiscussion->getReply(array("tid"=>$aMBReplyCnt['tid'],"rowcnt"=>1,"start"=>0));
  //$pos = strpos($sReplyXml, "<response>");
  //$sReplyXml=substr($sReplyXml,$pos);
  //header('Content-type: text/xml'); echo $sReplyXml; die;
  // number of comments
  //$iRecCnt = $aMBReplyCnt['reply_cnt'];

  $xml .="<LATEST_USER_REVIEW_MASTER_DATA>";
  //$xml .= $sReplyXml;
  $user_review_result[$i] = array_change_key_case($user_review_result[$i],CASE_UPPER);

  foreach($user_review_result[$i] as $k=>$v){
    $xml .= "<$k><![CDATA[$v]]></$k>";
  }
  $ratingresult = $userreview->arrGetUserQnA('','',$user_review_id,"1");
  $ratingcnt = sizeof($ratingresult);
  $xml .= "<USER_RATING_MASTER>";
  for($rating=0;$rating<$ratingcnt;$rating++){
    $que_id = $ratingresult[$rating]['que_id'];
    $que_result = $userreview->arrGetQuestions($que_id);
    $ratingresult[$rating]['quename'] = $que_result[0]['quename'];
    $answer = $ratingresult[$rating]['answer'];
    $ansArr = explode(",",$answer);
    $gradeCnt = $ratingresult[$rating]['grade'];
    $html = "";
    for($grade=1;$grade<=5;$grade++){
      if($grade <= $gradeCnt){
        $html .= '<img src="'.IMAGE_URL.'spacer.gif" class="vsblStr"/>';
      }else{
        $html .= '<img src="'.IMAGE_URL.'spacer.gif" class="dsblStr"/>';
      }
    }
    $ratingresult[$rating]['grade'] = $html;
    $rating_proportion = (($gradeCnt*100)/10)*2;
    $ratingresult[$rating]['grade_proportion'] = $rating_proportion;
    $xml .= "<USER_RATING_MASTER_DATA>";
    $ratingresult[$rating] = array_change_key_case($ratingresult[$rating],CASE_UPPER);
    foreach($ratingresult[$rating] as $k=>$v){
      $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $xml .= "</USER_RATING_MASTER_DATA>";
  }
  $xml .= "</USER_RATING_MASTER>";
  $reviewresult = $userreview->arrGetUserQnA('','',$user_review_id,"0","1"); // for comment
  $reviewcnt = sizeof($reviewresult);
  $xml .= "<USER_REVIEW_COMMENT_ANSWER_MASTER>";
  for($review=0;$review<$reviewcnt;$review++){
    $que_id = $reviewresult[$review]['que_id'];
    $answer = $reviewresult[$review]['answer'];
    $answer = removeSlashes($answer);
    $answer = html_entity_decode($answer,ENT_QUOTES);
    if($curr_user_review_id!=""){
      $answer = $answer;
    }else{
        if(strlen($answer)>200){ $answer = getCompactString($answer, 200).' ...'; }
     }

    $reviewresult[$review]['answer'] = $answer;
    $que_result = $userreview->arrGetQuestions($que_id);
    $reviewresult[$review]['quename'] = $que_result[0]['quename'];
    $reviewresult[$review] = array_change_key_case($reviewresult[$review],CASE_UPPER);
    $xml .= "<USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
    foreach($reviewresult[$review] as $k=>$v){
      $xml .= "<$k><![CDATA[$v]]></$k>";
    }
    $xml .= "</USER_REVIEW_COMMENT_ANSWER_MASTER_DATA>";
  }
  $xml .= "</USER_REVIEW_COMMENT_ANSWER_MASTER>";

  $result = $userreview->arrGetOverallGrade($category_id,$brand_id,$product_id,$product_info_id,'1',$user_review_id);
  $overallcnt = $result[0]['totaloverallcnt'];
  $overallavg = round($result[0]['overallavg']);
  $html = "";
  for($grade=1;$grade<=5;$grade++){
    if($grade <= $overallavg){
      $html .= '<img src="'.IMAGE_URL.'spacer.gif" class="vsblStr"/>';
    }else{
     $html .= '<img src="'.IMAGE_URL.'spacer.gif" class="dsblStr"/>';
    }
  }
  $html_proportion = (($overallavg*100)/10)*2;
  /*************added for user review rating************/
  $url = "";
  $url_postString = "";
  $xml_output = ""; 
  $cmd = PHP_PATH.' '.BASEPATH."api/average_rating_api.php"." brand_id=$brand_id product_name_id=$product_info_id product_id=$product_id user_review_id=$user_review_id";
//echo $cmd;
  $xml_output = shell_exec($cmd);

//echo $xml_ouput;
  /*************added for user review rating************/
  $xml .= $xml_output;
  $xml .= "<OVERALL_AVG_HTML><![CDATA[$html]]></OVERALL_AVG_HTML>";
  $xml .= "<OVERALL_TOTAL_CNT><![CDATA[$overallcnt]]></OVERALL_TOTAL_CNT>";

  if(!empty($user_review_id)){
    $resultoption = $userreview->GetUserReviewOptions("",$user_review_id,$category_id);
  }
  $oCount=sizeof($resultoption);
  $xml .= "<REVIEW_RATE_OPTION>";
  $like_yes = 0 ;
  $like_no = 0;
  $tot_cnt = 0;
  if($oCount>0){
    $like_yes = $resultoption[0]['like_yes'];
    $like_no = $resultoption[0]['like_no'];
    $tot_cnt = $like_yes+$like_no;
  }
  $xml .= "<REVIEW_RATE_OPTION_TOTAL_COUNT>$tot_cnt</REVIEW_RATE_OPTION_TOTAL_COUNT>";
  $xml .= "<REVIEW_RATE_OPTION_YES>$like_yes</REVIEW_RATE_OPTION_YES>";
  $xml .= "<REVIEW_RATE_OPTION_NO>$like_no</REVIEW_RATE_OPTION_NO>";
  $xml .= "</REVIEW_RATE_OPTION>";
  unset($seoTitleArr);

  $seoTitleArr[] = SEO_WEB_URL;
  $seoTitleArr[] = $category_seo_path;
  $seoTitleArr[] = "Reviews";
  $seoTitleArr[] = "Write-User-Reviews";
  $seoTitleArr[] = constructUrl($brand_name);
  $seoTitleArr[] = constructUrl($brand_name)."-".constructUrl($product_info_name);
  $seoTitleArr[] = $brand_id;
  $seoTitleArr[] = $product_info_id;

  $seo_write_review_url = implode("/",$seoTitleArr);
  $xml .= "<SEO_WRITE_REVIEW_URL><![CDATA[".$seo_write_review_url."]]></SEO_WRITE_REVIEW_URL>";
  $xml .= "</LATEST_USER_REVIEW_MASTER_DATA>";
}
unset($seoTitleArr);
$seoTitleArr[] = SEO_WEB_URL;
$seoTitleArr[] = "Car-User-Reviews";
$user_review_seo_moreurl = implode("/",$seoTitleArr);
$xml .= "<USER_REVIEW_SEO_MOREURL><![CDATA[".$user_review_seo_moreurl."]]></USER_REVIEW_SEO_MOREURL>";
$xml .= "</LATEST_USER_REVIEW_MASTER>";

header('Content-type: text/xml');
echo $xml;
exit;
?>
