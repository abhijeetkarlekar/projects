<?php
require_once(CLASSPATH."campus_discussion.class.php");
/**
* @brief class is used add,update,delete,get product details.
* @author Rajesh Ujade
* @version 1.0
* @created 16-Feb-2011 5:09:31 PM
*/
class report extends DbOperation{
	/**Intialize the consturctor.*/
	var $oCampusDiscussion;
	function report(){
		$this->oCampusDiscussion  =  new campus_discussion();
		$this->cache = new Cache;
		$this->quicklinkkey = MEMCACHE_MASTER_KEY."quicklink::";
		$this->newsletterKey = MEMCACHE_MASTER_KEY."newsletter::";
		$this->reportKey = MEMCACHE_MASTER_KEY."report::";
		$this->articlekey = MEMCACHE_MASTER_KEY."article::";
		$this->newskey = MEMCACHE_MASTER_KEY."news::";
		$this->videokey = MEMCACHE_MASTER_KEY."video::";
		$this->reviewkey = MEMCACHE_MASTER_KEY."review::";
	}
	function arrGetOverAllUserReviewByModel(){
	}
	function arrCalculateUserReview(){
	}
	function intInsertUpdateCommentCount($request_param){
		$result = $this->oCampusDiscussion->addCampusDiscussion($request_param);
		if(sizeof($result) > 0){
			$iTId = $result['tid'];
			$id = $request_param["title"];
			$service_id = $request_param['sid'];
			$comment_type_id = $request_param['cid'];
			$sRequestUrl=WEB_URL.substr($_SERVER['REQUEST_URI'],1);
			$aParameters = Array("title"=>$id,"turl"=>$sRequestUrl,"cid"=>$comment_type_id,"sid"=>$service_id);
			$comment_result = $this->oCampusDiscussion->getMBDetails($aParameters);
			$comment_count = $comment_result['reply_cnt'];
		}
		$result = Array("iTId"=>$iTId,"comment_count"=>$comment_count);
		return $result;
	}

	function arrGetCommentCount($id="",$service_id="",$comment_type_id="",$category_id=""){
		$keyArr[] = $this->reportKey."_arrGetCommentCount";
		if(!empty($category_id)){
			$whereClauseArr[] = " category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if(!empty($service_id)){
			$whereClauseArr[] = " service_id = $service_id";
			$keyArr[] = $service_id;
		}else{$keyArr[] =-1;}
		if(!empty($id)){
			$whereClauseArr[] = " id = $id";
			$keyArr[] = $id;
		}else{$keyArr[] =-1;}
		if(!empty($comment_type_id)){
			$whereClauseArr[] = " comment_type_id = $comment_type_id";
			$keyArr[] = $comment_type_id;
		}else{$keyArr[] =-1;}
		if(!empty($id) && !empty($comment_type_id) && !empty($service_id)){
			$md5id = md5($id.$comment_type_id.$service_id);
			$whereClauseArr[] = " md5id = '$md5id'";
			$keyArr[] = $md5id;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$sql = "select * from COMMENT_REPORT $whereClauseStr order by category_id asc";
		$result = $this->select($sql);
		return $result;
	}

	function arrGetArticleCommentCount($article_id){
		$result = $this->arrGetCommentCount($article_id,SERVICEID,ARTICLE_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetNewsCommentCount($news_id){
		$result = $this->arrGetCommentCount($news_id,SERVICEID,NEWS_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetOnCarsReviewCommentCount($review_id){
		$result = $this->arrGetCommentCount($review_id,SERVICEID,ONCARS_REVIEW_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetUserReviewCommentCount($user_review_id){
		$result = $this->arrGetCommentCount($user_review_id,SERVICEID,USER_REVIEW_MODEL_CATEGORY_ID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetOncarsUserReviewCommentCount($user_review_id){
		$result = $this->arrGetCommentCount($user_review_id,SERVICEID,USER_REVIEW_VARIANT_CATEGORY_ID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoCommentCount($video_id){
		$result = $this->arrGetCommentCount($video_id,SERVICEID,VIDEO_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoReviewCommentCount($video_id){
		$result = $this->arrGetCommentCount($video_id,SERVICEID,VIDEO_REVIEW_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoArticleCommentCount($video_id){
		$result = $this->arrGetCommentCount($video_id,SERVICEID,VIDEO_ARTICLE_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoNewsCommentCount($video_id){
		$result = $this->arrGetCommentCount($video_id,SERVICEID,VIDEO_NEWS_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetSlideShowCommentCount($slideshow_id){
		$result = $this->arrGetCommentCount($slideshow_id,SERVICEID,SLIDESHOW_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function UpdateCommentCount($array_param){
		$iTId = $array_param['iTId'];
		$id = $array_param['id'];
		$comment_type_id = $array_param['cid'];
		$iServiceId = $array_param['sid'];
		$aParameters = Array("title"=>$id,"cid"=>$comment_type_id,"sid"=>$iServiceId);
		$comment_result = $this->oCampusDiscussion->getMBDetails($aParameters);
		$comment_count = $comment_result['reply_cnt'];
		$insert_param = Array("id"=>$id,"category_id"=>SITE_CATEGORY_ID,"comment_type_id"=>$comment_type_id,"service_id"=>$iServiceId,"md5id"=>md5(implode("",$aParameters)),"comment_board_id"=>$iTId,"comment_count"=>$comment_count,"create_date"=>date('Y-m-d H:i:s'),"update_date"=>date('Y-m-d H:i:s'));
		$sSql = $this->getUpdateSql("COMMENT_REPORT",array_keys($insert_param),array_values($insert_param),"comment_board_id",$iTId);
		$count_result = $this->update($sSql);
	}

	function intInsertUpdateViewsCount($request_param){
		$item_id = $request_param["item_id"];
		$service_id = $request_param['sid'];
		$views_type_id = $request_param['cid'];
		$category_id = SITE_CATEGORY_ID;
		$aParameters = Array("item_id"=>$item_id,"cid"=>$views_type_id,"sid"=>$service_id);
		$insert_param = Array("item_id"=>$item_id,"category_id"=>SITE_CATEGORY_ID,"views_type_id"=>$views_type_id,"service_id"=>$service_id,"md5id"=>md5(implode("",$aParameters)),"create_date"=>date('Y-m-d H:i:s'),"update_date"=>date('Y-m-d H:i:s'));
		$md5id = md5(implode("",$aParameters));
		$sql = "select views_count from VIEWS_REPORT where md5id ='".$md5id."' and category_id = $category_id";
		$result = $this->select($sql);
		if($result[0]['views_count'] > 0){
			$view_count = $result[0]['views_count']+1;
		}else{$view_count = 1;}
		$insert_param["views_count"] = $view_count;
		$sSql = $this->getInsertUpdateSql("VIEWS_REPORT",array_keys($insert_param),array_values($insert_param));
		$count_result = $this->insertUpdate($sSql);
		return $view_count;
	}

	function arrGetViewsCount($item_id="",$service_id="",$views_type_id="",$category_id=""){
		$keyArr[] = $this->reportKey."_arrGetViewsCount";
		if(!empty($category_id)){
			$whereClauseArr[] = " category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($service_id)){
			$whereClauseArr[] = " service_id = $service_id";
			$keyArr[] = $service_id;
		}else{$keyArr[] = -1;}
		if(!empty($item_id)){
			$whereClauseArr[] = " item_id = $item_id";
			$keyArr[] = $item_id;
		}else{$keyArr[] = -1;}
		if(!empty($comment_type_id)){
			$whereClauseArr[] = " views_type_id = $views_type_id";
			$keyArr[] = $views_type_id;
		}else{$keyArr[] = -1;}
		if(!empty($item_id) && !empty($views_type_id) && !empty($service_id)){
			$md5id = md5($item_id.$views_type_id.$service_id);
			$whereClauseArr[] = " md5id = '$md5id'";
			$keyArr[] = $md5id;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$sql = "select * from VIEWS_REPORT $whereClauseStr order by category_id asc";
		$result = $this->select($sql);
		return $result;
	}

	function arrGetArticleViewsCount($article_id){
		$result = $this->arrGetViewsCount($article_id,SERVICEID,ARTICLE_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetNewsViewsCount($news_id){
		$result = $this->arrGetViewsCount($news_id,SERVICEID,NEWS_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetOnCarsReviewViewsCount($review_id){
		$result = $this->arrGetViewsCount($review_id,SERVICEID,ONCARS_REVIEW_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetUserReviewViewsCount($user_review_id){
		$result = $this->arrGetViewsCount($user_review_id,SERVICEID,USER_REVIEW_MODEL_CATEGORY_ID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetOncarsUserReviewViewsCount($user_review_id){
		$result = $this->arrGetViewsCount($user_review_id,SERVICEID,USER_REVIEW_MODEL_CATEGORY_ID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoViewsCount($video_id){
		$result = $this->arrGetViewsCount($video_id,SERVICEID,VIDEO_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoReviewViewsCount($video_id){
		$result = $this->arrGetViewsCount($video_id,SERVICEID,VIDEO_REVIEW_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoArticleViewsCount($video_id){
		$result = $this->arrGetViewsCount($video_id,SERVICEID,VIDEO_ARTICLE_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetVideoNewsViewsCount($video_id){
		$result = $this->arrGetViewsCount($video_id,SERVICEID,VIDEO_NEWS_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function arrGetSlideShowViewsCount($slideshow_id){
		$result = $this->arrGetViewsCount($slideshow_id,SERVICEID,SLIDESHOW_CATEGORYID,SITE_CATEGORY_ID);
		return $result;
	}

	function getPageViews($aViewCntUrl,$aEncViewCntUrl=""){
		if(count($aViewCntUrl)>0){
			require_once(CLASSPATH.'utility.class.php');
			require_once(CLASSPATH.'xmlparser.class.php');
			$oXmlparser = new XMLParser;
			$sUrlList=implode(",",array_keys($aViewCntUrl));
			$sContent = "action=get&vurl=$sUrlList&sid=".SERVICEID;
			$sPageCountXML =utility::curlaccess($sContent,VIEW_TRACKER_API_PATH);
			$oXmlparser->XMLParse($sPageCountXML);
			$aResultXML =$oXmlparser->getOutput();
			$oXmlparser->clearOutput();
			$iViews=0;
			if(is_array($aResultXML) && count($aResultXML)>0){
				foreach($aResultXML['response'] as $iK =>$aData){
					$aViewCnt[$aEncViewCntUrl[trim($aData['view_url'])]]=$aData['view_count'];
				}
			}
		}
		return $aViewCnt;
	}
	/**
	* @note function is used to get date for solar article search.
	* @createdate 07-07-2011
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolarArticleDetails($category_id="",$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->articlekey."_arrSolarArticleDetails";
		if(!empty($category_id)){
			$whereClauseArr[] = " PA.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "A.status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "A.create_date >= '$startdate'";
			$keyArr[] = $startdate;
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "A.create_date <= '$enddate'";
			$keyArr[] = $enddate;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " PA.editor_id=A.uid ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(",",$limitArr);
		}
		$sql = "select * from EDITOR_INFO PA, ARTICLE A $whereClauseStr order by A.create_date desc  $limitStr";
		$result = $this->select($sql);
		return $result;
	}
	/**
	* @note function is used to get date for solar news search.
	* @createdate 07-07-2011
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolarNewsDetails($category_id="",$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->newskey."arrSolarNewsDetails";
		if(!empty($category_id)){
			$whereClauseArr[] = " category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "create_date >= '$startdate'";
			$keyArr[] = $startdate;
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "create_date <= '$enddate'";
			$keyArr[] = $enddate;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(",",$limitArr);
		}
		$sql = "select * from  NEWS  $whereClauseStr order by create_date desc $limitStr ";
		$result = $this->select($sql);
		return $result;
	}
	/**
	* @note function is used to get date for solar reviews search.
	* @createdate 07-07-2011
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolarReviewsDetails($category_id="",$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->reviewkey."_arrSolarReviewsDetails";
		if(!empty($category_id)){
			$whereClauseArr[] = " PR.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "R.status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "R.create_date >= '$startdate'";
			$keyArr[] = $startdate;
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "R.create_date <= '$enddate'";
			$keyArr[] = $enddate;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " EI.editor_id=R.uid ";
		$whereClauseArr[] = " PR.review_id=R.review_id";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(",",$limitArr);
		}
		$sql = "select * from EDITOR_INFO EI, REVIEWS R, PRODUCT_REVIEWS PR $whereClauseStr order by PR.create_date desc  $limitStr";
		$result = $this->select($sql);
		return $result;
	}
	/**
	* @note function is used to get date for solar video search.
	* @createdate 07-07-2011
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolarVideoDetails($category_id="",$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->videokey."_arrSolarVideoDetails";
		if(!empty($category_id)){
			$whereClauseArr[] = "PV.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "create_date >= '$startdate'";
			$keyArr[] = $startdate;
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "create_date <= '$enddate'";
			$keyArr[] = $enddate;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = "content_type=1";
		$whereClauseArr[] = "is_media_process=1";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(",",$limitArr);
		}
		$sql = "select * from VIDEO_GALLERY V, PRODUCT_VIDEOS PV $whereClauseStr order by PV.create_date desc  $limitStr";
		$result = $this->select($sql);
		return $result;
	}
	/**
	* @note function is used to get date for solar car search.
	* @createdate 07-07-2011
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolarCarDetails($category_id,$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->productKey."_arrSolarCarDetails";
		if(is_array($product_name_ids)){
			$product_name_ids = implode(",",$product_name_ids);
		}
		if($category_id != ""){
			$whereClauseArr[] = "category_id in($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($brand_id != ""){
			$whereClauseArr[] = "brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}
		if(!empty($product_name_ids)){
			$whereClauseArr[] = "product_name_id in($product_name_ids)";
			$keyArr[] = $product_name_ids;
		}else{$keyArr[] =-1;}
		if(!empty($product_info_name)){
			$product_info_name = strtolower($product_info_name);
			$whereClauseArr[] = "lower(product_info_name) = '$product_info_name'";
			$keyArr[] = $product_info_name;
		}else{$keyArr[] =-1;}
		if($status != ""){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$sSql = "select * from PRODUCT_NAME_INFO $whereClauseStr order by  create_date desc $limitStr";
		$result = $this->select($sSql);
		return $result;
	}

	function intInsertUpdatenlsubscription($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("NEWS_LETTER_SUBCRIPTION",array_keys($insert_param),array_values($insert_param));
		$id = $this->insert($sql);
		if($id == 'Duplicate entry'){ return 'exists';}
		return $id;
	}

	function arrGetNewsletterDetails($id="",$email="",$service_id="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->newsletterKey;
		if(!empty($id)){
			$keyArr[] = $id;
			$whereClauseArr[] = "id in ($id)";
		}else{$keyArr[] =-1;}
		if(!empty($email)){
			$keyArr[] = $email;
			$whereClauseArr[] = "email_id=$email";
		}else{$keyArr[] =-1;}
		if(!empty($service_id)){
			$keyArr[] = $service_id;
			$whereClauseArr[] = "service_id=$service_id";
		}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "status=$status";
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by id asc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from NEWS_LETTER_SUBCRIPTION $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get date for solar news search.
	* @createdate 29-01-2013
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolrNewsDetails($category_id="",$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->newskey."_arrSolrNewsDetails";
		if(!empty($category_id)){
			$whereClauseArr[] = " PN.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "N.create_date >= '$startdate'";
			$keyArr[] = $startdate;
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "N.create_date <= '$enddate'";
			$keyArr[] = $enddate;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " UMN.content_type=2";
		$whereClauseArr[] = " PN.article_id=N.article_id";
		$whereClauseArr[] = " PN.product_article_id=UMN.product_article_id";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(",",$limitArr);
		}
		$sql = "select * from  PRODUCT_NEWS PN, NEWS N, UPLOAD_MEDIA_NEWS UMN  $whereClauseStr GROUP BY PN.product_article_id order by N.create_date desc $limitStr ";
		$result = $this->select($sql);
		return $result;
	}
	/**
	* @note function is used to get date for solar video search.
	* @createdate 29-01-2013
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolrVideoDetails($category_id="",$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->videokey."_arrSolrVideoDetails";
		if(!empty($category_id)){
			$whereClauseArr[] = "PV.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "V.create_date >= '$startdate'";
			$keyArr[] = $startdate;
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "V.create_date <= '$enddate'";
			$keyArr[] = $enddate;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = "content_type=1";
		$whereClauseArr[] = "is_media_process=1";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(",",$limitArr);
		}
		$sql = "select V.video_id,V.category_id,V.title,V.tags,V.meta_description as content,V.type_id,V.media_id,V.media_path,V.external_media_source,V.media_source_flag,V.video_img_id,V.video_img_path,V.content_type,V.is_media_process,V.status,V.ordering,V.publish_time,UNIX_TIMESTAMP(V.create_date) as create_date ,V.update_date,PV.product_video_id, PV.video_id, PV.group_id, PV.category_id, PV.brand_id, PV.product_info_id, PV.product_id from VIDEO_GALLERY V, PRODUCT_VIDEOS PV $whereClauseStr order by V.create_date desc  $limitStr";
		$result = $this->select($sql);
		return $result;
	}

	/**
	* @note function is used to get date for solar article search.
	* @createdate 29-01-2013
	* @param integer $category_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string date format $startdate.
	* @param string date format $enddate.
	* @param boolean $status.
	* @pre $category_id must be non-empty/non-zero valid integer.
	* @post array article details.
	* return array.
	*/
	function arrSolrArticleDetails($category_id="",$startlimit="",$cnt="",$startdate="",$enddate="",$status="1"){
		$keyArr[] = $this->articlekey."_arrSolrArticleDetails";
		if(!empty($category_id)){
			$whereClauseArr[] = " PA.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "A.create_date >= '$startdate'";
			$keyArr[] = $startdate;
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "A.create_date <= '$enddate'";
			$keyArr[] = $enddate;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " UMA.content_type=2";
		$whereClauseArr[] = " PA.article_id=A.article_id";
		$whereClauseArr[] = " PA.product_article_id=UMA.product_article_id";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(",",$limitArr);
		}
		$sql = "select * from  PRODUCT_ARTICLE PA, ARTICLE A, UPLOAD_MEDIA_ARTICLE UMA  $whereClauseStr GROUP BY PA.product_article_id order by A.create_date desc $limitStr ";
		$result = $this->select($sql);
		return $result;
	}
}
