<?php
/**
 * @brief class is used to perform actions on videos
 * @author Sachin(sachin@corp.india.com)  & Bhakti(bhakti@corp.india.com)
 * @version 1.0
 * @created 3rdMar2011
 * @last updated on 08-Mar-2011 13:14:00 PM
 */
class videos extends DbOperation{

	var $cache;
	var $videokey;
	/**Initialize the consturctor.*/
	function videos(){
		$this->cache = new Cache;
		$this->videokey = MEMCACHE_MASTER_KEY."video::";
	}
	/**
	 * @note function is used  add/update video details
	 *
	 * @pre  aParameters is array video details
	 * @pre  sTableName is database table name
	 * @post return video id if successful , 0 if error occurs
	 */
	function addUpdVideosDetails($aParameters,$sTableName){
		$aParameters['create_date'] = date('Y-m-d H:i:s');
		$aParameters['update_date'] = date('Y-m-d H:i:s');
		$video_id = $aParameters['video_id'];
		$status = $aParameters['status'];
		$sql = "update MOST_POPULAR_VIDEOS set status = $status where video_id = $video_id";
		$isUpdate = $this->update($sql);
		$sql = "update FEATURED_VIDEOS set status = $status where video_id = $video_id";
		$isUpdate = $this->update($sql);
		$sql = "update EDITOR_PICK_VIDEOS set status = $status where video_id = $video_id";
		$isUpdate = $this->update($sql);
		$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
		$iRes=$this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $iRes;
	}

	/**
	* @note function is used  delete videos from VIDEO_GALLERY,PRODUCT_VIDEOS,FEATURED_VIDEOS and MOST_POPULAR_VIDEOS tables
	*
	* @param video_id ,sTableName
	* @pre  video id is single video id of integer type
	* @pre  sTableName is database table name
	* @post return true if successful , false if error occurs
	*/
	function booldeleteVideos($video_id,$sTableName=''){
		$sSql="delete from VIDEO_GALLERY where video_id='".$video_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$sSql='';
		$sSql="delete from PRODUCT_VIDEOS where video_id='".$video_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$sSql='';
		$sSql="delete from FEATURED_VIDEOS where video_id='".$video_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$sSql='';
		$sSql="delete from MOST_POPULAR_VIDEOS where video_id='".$video_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $iRes;
	}

	/**
	* @note function is used  delete related videos
	*
	* @param video_id ,table_name
	* @pre  video id is single video id of integer type
	* @pre  table_name is database table name
	* @post return true if successful , false if error occurs
	*/
	function booldeleterelatedVideos($video_id="",$table_name=""){
		$sSql="delete from $table_name where video_id='".$video_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $iRes;
	}

	/**
	* @note function is used  delete most popular videos
	*
	* @param video_id
	* @pre  video id is single video id of integer type
	* @post return true if successful , false if error occurs
	*/
	function booldeleteMostPopularVideos($id=""){
		$sSql="delete from MOST_POPULAR_VIDEOS where id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $iRes;
	}
	/**
	* @note function is used to insert Related Video details.
	*
	* @param associative array $insert_param.
	* @param database table name $table_name.
	* @pre $insert_param must be valid non-empty associative array.
	* @post integer $source_id.
	* return integer.
	*/
	function intInsertRelatedVideo($insert_param,$table_name){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($table_name,array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $result;
    }

	/**
	* @note function is used to insert Most Popular Video details.
	*
	* @param associative array $insert_param.
	* @pre $insert_param must be valid non-empty associative array.
	* @post integer $source_id.
	* return integer.
	*/
	function intInsertMostPopularVideo($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("MOST_POPULAR_VIDEOS",array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $result;
    }
	/**
	* @note function is used to insert most viewed Video details.
	*
	* @param associative array $insert_param.
	* @param database table name $table_name.
	* @pre $insert_param must be valid non-empty associative array.
	* @post integer $source_id.
	* return integer.
	*/
	function intInsertMostViewedVideo($insert_param,$table_name){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($table_name,array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $result;
	}
	/**
	* @note function is used to insert Related Video details.
	*
	* @param associative array $insert_param.
	* @pre $insert_param must be valid non-empty associative array.
	* @post integer $source_id.
	* return integer.
	*/
	function intInsertClinckVideo($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("CLINCK_VIDEOS",array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $result;
	}
	/**
	* @note function is used  add/update language video details
	*
	* @pre  aParameters is array video details
	* @pre  sTableName is database table name
	* @post return video id if successful , 0 if error occurs
	*/
	function addUpdLanguageVideosDetails($aParameters,$sTableName){
		$aParameters['create_date'] = date('Y-m-d H:i:s');
		$aParameters['update_date'] = date('Y-m-d H:i:s');
		$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
		//echo $sSql;exit;
		$iRes=$this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->videokey."_language");
		return $iRes;
	}
	/**
	* @note function is used  add/update language video details
	*
	* @pre  aParameters is array video details
	* @pre  sTableName is database table name
	* @post return video id if successful , 0 if error occurs
	*/
	function boolUpdateLanguageVideosDetails($aParameters,$sTableName,$language_video_id){
		$aParameters['update_date'] = date('Y-m-d H:i:s');
		$sSql=$this->getUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters),"language_video_id",$language_video_id);
		//echo $sSql;exit;
		$iRes=$this->update($sSql);
		$this->cache->searchDeleteKeys($this->videokey."_language_media_process");
		return $iRes;
	}

	/**
	* @note function is used  delete language video details.
	*
	* @param video_id
	* @pre  video id is single video id of integer type
	* @post return true if successful , false if error occurs
	*/
	function booldeleteLanguageVideos($id=""){
		$sSql="delete from LANGUAGE_VIDEO_GALLERY where language_video_id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey."_language");
		return $iRes;
	}
	/**
	* @note function is used  delete clinck videos
	*
	* @param video_id
	* @pre  video id is single video id of integer type
	* @post return true if successful , false if error occurs
	*/
	function booldeleteClinckVideos($id=""){
		$sSql="delete from CLINCK_VIDEOS where id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey."_clinck");
		return $iRes;
	}

	/**
	* @note function is used  fetch video group for video details
	*
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated category names/ category ids array $group_names.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post return group detail if successful , 0 if error occurs
	*
	*/
	function arrGetVideoGroupDetails($group_ids="",$group_names="",$category_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->videokey."_group_detail";
		if(is_array($group_ids)){
			$group_ids=implode(",",$group_ids);
		}
		if(is_array($group_names)){
			$group_names=implode(",",$group_names);
		}
		if(is_array($category_ids)){
			$category_ids=implode(",",$category_ids);
		}
		if($group_names!=""){
			$whereClauseArr[] = "group_name in ($group_names)";
			$keyArr[] = $group_names;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = "group_id in ($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($status!=""){
			$whereClauseArr[] = "status in ($status)";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from VIDEO_GROUP $whereClauseStr $limitStr";
		//echo $sSql."<br>";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used  fetch video type for video details
	*
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated type names/ type ids array $type_names.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @pre not required.
	* @post return type detail if successful , 0 if error occurs
	*
	*/
	function arrGetVideoTypeDetails($type_ids="",$type_names="",$category_ids="",$status="1"){
		$keyArr[] = $this->videokey."_type_detail";
		if(is_array($type_ids)){
			$type_ids=implode(",",$type_ids);
		}
		if(is_array($type_names)){
			$type_names=implode(",",$type_names);
		}
		if(is_array($category_ids)){
			$category_ids=implode(",",$category_ids);
		}
		if($group_names!=""){
			$whereClauseArr[] = "type_name in ($type_names)";
			$keyArr[] = $type_names;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = "type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($status!=""){
			$whereClauseArr[] = "status in ($status)";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from VIDEO_TYPE $whereClauseStr $limitStr";
		//echo $sSql."<br>";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used  get video detail list count
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
        * @param an integer/comma seperated group ids/ group ids array $group_ids.
        * @param an integer/comma seperated type ids/ type ids array $type_ids.
        * @param an integer/comma seperated product ids/ product ids array $product_ids.
        * @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
        * @param an integer/comma seperated category ids/ category ids array $category_ids.
        * @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
        * @param boolean Active/InActive $status.
        * @pre not required.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function getVideosDetailsCount($video_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1"){
		$iCnt=$aParamaters['cnt'];
		$keyArr[] = $this->videokey."_detail_data_cnt";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
		}
		if(is_array($product_info_id)){
			$product_info_id = implode(",",$product_info_id);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=''){
		   $whereClauseArr[] = " PV.product_id in($product_ids)";
		   $keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PV.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PV.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " V.type_id in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}

		if($category_id!=""){
			$whereClauseArr[] = " PV.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PV.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select count(V.video_id) as cnt from VIDEO_GALLERY V, PRODUCT_VIDEOS PV $whereClauseStr";
		//echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used  get video detail list
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function getVideosDetails($video_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey."_getVideosDetails";
	    $iCnt=$aParamaters['cnt'];
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
		}
		if(is_array($product_info_id)){
			$product_info_id = implode(",",$product_info_id);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=''){
		  $whereClauseArr[] = " PV.product_id in($product_ids)";
		  $keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PV.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PV.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " V.type_id in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PV.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PV.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select *, DATE_FORMAT(V.create_date,'%d/%m/%Y') as disp_date from VIDEO_GALLERY V, PRODUCT_VIDEOS PV $whereClauseStr $orderby $limitStr";
		//echo $sSql; die();

		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used  get language video detail list (With Product table join)
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated video types/ video types array $video_types.
	* @param an integer/comma seperated language ids/ language ids array $language_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/

	function getLanguageVideosDetails($video_ids="",$video_type="",$language_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_ids="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$tablenameArr = Array("1"=>"PRODUCT_VIDEOS","2"=>"PRODUCT_VIDEOS","3"=>"UPLOAD_MEDIA_REVIEWS","4"=>"UPLOAD_MEDIA_ARTICLE","5"=>"UPLOAD_MEDIA_NEWS");
		$colomnArr = Array("1"=>"video_id","2"=>"video_id","3"=>"upload_media_id","4"=>"upload_media_id","5"=>"upload_media_id");
		$root_tbl = "LANGUAGE_VIDEO_GALLERY";
		$tableArr[] = $root_tbl;
		$keyArr[] = $this->videokey."_language_detail";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(!empty($video_type)){
			$whereClauseArr[] = $root_tbl.".video_type = $video_type";
			$refer_tbl = $tablenameArr[$video_type];
			$whereClauseArr[] = $root_tbl.".video_id = ".$refer_tbl.".".$colomnArr[$video_type];
			$tableArr[] = $refer_tbl;
			$keyArr[] = $video_type;
		}else{$keyArr[] = -1;}
		if(is_array($language_ids)){
			$language_ids = implode(",",$language_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
		}
		if(is_array($product_info_id)){
			$product_info_id = implode(",",$product_info_id);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = $root_tbl.".status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = $root_tbl.".video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($language_ids!=""){
			$whereClauseArr[] = $root_tbl.".language_id in ($language_ids)";
			$keyArr[] = $language_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=''){
			$whereClauseArr[] = $root_tbl.".product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = $root_tbl.".product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = $root_tbl.".category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = $root_tbl.".brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = $root_tbl.".content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby = "order by ".$root_tbl.".create_date DESC";
			 $keyArr[] = str_replace(" ","_","order by create_date DESC");
		}else{$keyArr[] = -1;}
		$table_name = implode(",",$tableArr);

		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select $root_tbl.* from $table_name $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		//echo $sSql;
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used  get language video detail list(Without product table join)
	*
	* @param an integer/comma seperated language video ids/ language video ids array $language_video_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer$video_type.
	* @param an integer/comma seperated language ids/ language ids array $language_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetLangVideoList($language_video_ids="",$video_ids="",$video_type="",$language_ids="",$category_ids="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey."_languagelist_data";
		if(is_array($language_video_ids)){
			$language_video_ids = implode(",",$language_video_ids);
		}
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($language_ids)){
			$language_ids = implode(",",$language_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($language_video_ids!=""){
			$whereClauseArr[] = "language_video_id in ($language_video_ids)";
			$keyArr[] = $language_video_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
				$whereClauseArr[] = "video_id in ($video_ids)";
				$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($video_type!=""){
				$whereClauseArr[] = "video_type = $video_type";
				$keyArr[] = $video_type;
		}else{$keyArr[] = -1;}
		if($language_ids!=""){
				$whereClauseArr[] = "language_id  in($language_ids)";
				$keyArr[] = $language_ids;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
				$whereClauseArr[] = "category_id  in($category_ids)";
				$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($status != ''){
				$whereClauseArr[] = "status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
				$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
				$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
				$orderby="order by create_date DESC";
				$keyArr[] = "order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from LANGUAGE_VIDEO_GALLERY $whereClauseStr $orderby $limitStr";
		//echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used  get most liked video detail list
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated types/ types array $type
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetMostLikeVideosDetails($video_ids="",$type_ids="",$type="",$category_id=""){
		$iCnt=$aParamaters['cnt'];
		$keyArr[] = $this->videokey."_mostlike_detail";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($type)){
			$type = implode(",",$type);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($video_ids!=""){
			$whereClauseArr[] = "video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " type_id in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($type!=""){
			$whereClauseArr[] = " type in($type)";
			$keyArr[] = $type;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}

		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result[0]['cnt'];}
		$sSql="select count(video_id) as cnt from MOST_LIKE_VIDEOS $whereClauseStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		$cnt=$result[0]['cnt'];
		return $cnt;
	}

	/**
	* @note function is used to get Featured video detail
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetFeaturedVideosDetails($section_ids="",$video_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_featured_detail";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "FV.status=$status";
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "FV.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " FV.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FV.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FV.video_id=V.video_id ";
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
			$orderby = "order by FV.".$orderby." DESC";
			$keyArr[] = "order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT *, FV.status as status FROM VIDEO_GALLERY V,PRODUCT_VIDEOS PV,FEATURED_VIDEOS FV $whereClauseStr $orderby $limitStr";
	       // echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetHomeVideosDetails($section_ids="",$video_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_homedet_data";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "FV.status=$status";
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "FV.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " FV.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FV.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FV.video_id=V.video_id ";
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
				$orderby = " order by FV.".$orderby." DESC ";
				$keyArr[] = "order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT *, FV.status as status FROM VIDEO_GALLERY V,PRODUCT_VIDEOS PV,HOME_VIDEOS FV $whereClauseStr $orderby $limitStr";
        //echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get Featured video details count
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetFeaturedVideosDetailsCount($section_ids="",$video_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_featured_data_cnt";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "FV.status=$status";
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "FV.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " FV.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FV.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FV.video_id=V.video_id ";
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
            $orderby = "order by FV.create_date ".$orderby;
			$keyArr[] = "order_".str_replace(" ","_",$orderby);
        }else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result[0]['cnt'];}
		$sSql="SELECT count(V.video_id) as cnt FROM VIDEO_GALLERY V,PRODUCT_VIDEOS PV,FEATURED_VIDEOS FV $whereClauseStr ";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		$count = $result[0]['cnt'];
		return $count;
	}

	/**
	* @note function is used to get Related video details list
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetRelatedVideosDetails($section_ids="",$video_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_relateddet_data";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "RV.status=$status";
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			//$whereClauseArr[] = "RV.category_id in ($category_ids)";
		}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " RV.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " V.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " RV.video_id=V.video_id ";
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($orderby)) {
            $orderby= " order by V.".$orderby." DESC ";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
        }else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT *, RV.status as status FROM VIDEO_GALLERY V,PRODUCT_VIDEOS PV,RELATED_VIDEOS RV $whereClauseStr $orderby $limitStr";
		//echo $sSql;
		$aRes=$this->select($sSql);
		$this->cache->set($key,$result);
		return $aRes;
	}

	/**
	* @note function is used to get Related video details list
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated product video ids/ product video ids array $product_video_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/

	function arrGetRelatedVideos($video_ids="",$type_ids="",$category_ids="",$product_video_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_related_data";
		if(is_array($video_ids)){
				$video_ids = implode(",",$video_ids);
		}
		if(is_array($type_ids)){
				$type_ids = implode(",",$type_ids);
		}
		if(is_array($category_ids)){
				$category_ids = implode(",",$category_ids);
		}
		if(is_array($product_video_ids)){
				$product_video_ids = implode(",",$product_video_ids);
		}
		if(is_array($brand_ids)){
				$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($product_info_ids)){
				$product_info_ids = implode(",",$product_info_ids);
		}
		if(is_array($product_ids)){
				$product_ids = implode(",",$product_ids);
		}
		if($status != ''){
				$whereClauseArr[] = "V.status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids != ""){
			$whereClauseArr[] = "V.video_id !=$video_ids";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($type_ids != ""){
				$whereClauseArr[] = " V.type_id in ($type_ids)";
				$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($category_ids != ""){
				$whereClauseArr[] = "PV.category_id in ($category_ids)";
				$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($product_video_ids != ""){
				$whereClauseArr[] = "PV.product_video_id in ($product_video_ids)";
				$keyArr[] = $product_video_ids;
		}else{$keyArr[] = -1;}
		if($brand_ids != ""){
				$whereClauseArr[] = "PV.brand_id in ($brand_ids)";
				$keyArr[] = $brand_ids;
		}else{$keyArr[] = -1;}
		if($product_info_ids != ""){
				$whereClauseArr[] = "PV.product_info_id in ($product_info_ids)";
		}
		if($product_ids != ""){
				$whereClauseArr[] = "PV.product_info_id in ($product_ids)";
		}
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($orderby)) {
				$orderby= " order by V.".$orderby." DESC ";
		}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
		}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
		}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT * FROM VIDEO_GALLERY V,PRODUCT_VIDEOS PV $whereClauseStr $orderby $limitStr";
		//echo $sSql;
		$aRes=$this->select($sSql);
		$this->cache->set($key,$result);
		return $aRes;
   }

	/**
	* @note function is used to get Related video details list without  perticular video id(s)
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetRelatedVideosDetailsWithoutCurrent($section_ids="",$video_ids="",$type_ids="",$category_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_relatedwithoutcurrent_video";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($product_info_ids)){
			$product_info_ids = implode(",",$product_info_ids);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "RV.status=$status";
			$whereClauseArr[] = "V.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			//$whereClauseArr[] = "RV.category_id in ($category_ids)";
		}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id not in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($brand_ids!=""){
			$whereClauseArr[] = "PV.brand_id in ($brand_ids)";
			$keyArr[] = $brand_ids;
		}else{$keyArr[] = -1;}
		if($product_info_ids!=""){
			$whereClauseArr[] = "PV.product_info_id in ($product_info_ids)";
			$keyArr[] = $product_info_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=""){
			$whereClauseArr[] = "PV.product_id in ($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " RV.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FV.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " RV.video_id=V.video_id ";
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		$whereClauseArr[] = " V.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($orderby)) {
            $orderby = " order by V.".$orderby." DESC ";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
        }else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT *, RV.status as status FROM VIDEO_GALLERY V,PRODUCT_VIDEOS PV,RELATED_VIDEOS RV $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get editor pick video detail
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetEditorPickVideosDetails($section_ids="",$video_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){

		$keyArr[] = $this->videokey."_editorpickdetdata";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}

		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "RV.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "RV.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "V.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " RV.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FV.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " RV.video_id=V.video_id ";
		$whereClauseArr[] = " PV.video_id=V.video_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT *, RV.status as status FROM VIDEO_GALLERY V,PRODUCT_VIDEOS PV,EDITOR_PICK_VIDEOS RV $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
        * @note function is used to get most viewed video details list
        *
        * @param an integer/comma seperated video ids/ video ids array $video_ids.
        * @param an integer/comma seperated category ids/ category ids array $category_ids.
        * @pre not required.
        *
        * @post return array if successful , 0 if error occurs
        *
        */
	function arrGetMostViewedVideosDetails($video_ids="",$category_ids=""){
		$keyArr[] = $this->videokey."_mostviewed_detail";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($category_ids!=""){
			$whereClauseArr[] = "MOST_VIEWED_VIDEOS.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "MOST_VIEWED_VIDEOS.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT count(video_id) as cnt FROM MOST_VIEWED_VIDEOS $whereClauseStr $orderby $limitStr order by cnt desc";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get most popular video details
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer $content_type
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetMostPopularVideosDetails($video_ids="",$type_ids,$category_ids="",$content_type,$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_mostpop_detail";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($category_ids!=""){
			$whereClauseArr[] = "MPV.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = "VG.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "MPV.video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "VG.status=$status";
			$whereClauseArr[] = "MPV.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($content_type!=""){
			$whereClauseArr[] = " VG.content_type=1 ";
			$keyArr[] = 1;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " VG.video_id=MPV.video_id ";
		$whereClauseArr[] = " PV.video_id=VG.video_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($orderby)) {
			$orderby= " order by VG.".$orderby." DESC ";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql="SELECT MPV.video_id,VG.title,VG.tags,VG.meta_description,VG.type_id,VG.media_id,VG.media_path,VG.video_img_id,VG.video_img_path,VG.content_type,VG.is_media_process,MPV.status,MPV.tbl_type,VG.ordering,VG.create_date,VG.update_date,PV.brand_id,PV.product_info_id,PV.product_id FROM VIDEO_GALLERY VG,MOST_POPULAR_VIDEOS MPV,PRODUCT_VIDEOS PV $whereClauseStr $orderby $limitStr";
		//echo  $sql;
		$aRes=$this->select($sql);
		$this->cache->set($key,$result);
		return $aRes;
	}

	/**
	* @note function is used to get related video details list
	*
	* @param an integer/comma seperated section video ids/ section video ids array $section_video_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer $content_type
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $ordering.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetRelatedVideoList($section_video_id="",$video_id="",$status="1",$ordering="",$startlimit="",$cnt=""){
		$keyArr[] = $this->videokey."_relatedlist_data";
		if(!empty($status)){
			$whereClauseArr[] = "status in ($status)";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($section_video_id)){
			$whereClauseArr[] = "section_video_id in ($section_video_id)";
			$keyArr[] = $section_video_id;
		}else{$keyArr[] = -1;}
		if($video_id != ""){
			$whereClauseArr[] = "video_id = $video_id";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		if($ordering != ""){
			$whereClauseArr[] = "ordering = $ordering";
			$keyArr[] = $ordering;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " a.video_id=b.video_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from PRODUCT_VIDEOS a, VIDEO_GALLERY b $whereClauseStr $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used  to insert external media details.
	*
	* @param associative array $insert_param.
	* @pre $insert_param must be valid non-empty associative array.
	*
	*@post integer $source_id.
	*return integer.
     */
	function intInsertExternalMedia($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql('EXTERNAL_VIDEO_SOURCE',array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($result);
		$this->cache->searchDeleteKeys($this->videokey);
		return $result;
	}
	/**
	* @note function is used to get external media details list
	*
	* @param an integer/comma seperated source id/ source id array $source_id.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param a string $source_url
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetExternalMediaDetails($source_id="",$category_id="",$source_url="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->videokey."_external_media_detail";
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in (".implode(",",$category_id).")";
			$keyArr[] = implode(",",$category_id);
		}else{$keyArr[] = -1;}
		if(!empty($source_id)){
			$whereClauseArr[] = "source_id in (".implode(",",$source_id).")";
			$keyArr[] = implode(",",$source_id);
		}else{$keyArr[] = -1;}
		if(!empty($source_url)){
			$whereClauseArr[] = "lower(source_url) = ".strtolower($source_url);
			$keyArr[] = strtolower($source_url);
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from EXTERNAL_VIDEO_SOURCE $whereClauseStr $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $sql;
	}
	/**
	* @note function is used to get tab details list
	*
	* @param an integer/comma seperated tab ids/ tab ids array $tab_ids.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetTabDetails($tab_ids="",$startlimit="",$cnt="",$orderby="",$status=""){
		$keyArr[] = $this->videokey."_tabdetaildata";
		if(is_array($tab_ids)){
			$tab_ids = implode(",",$tab_ids);
		}
		if($tab_ids != ''){
			$whereClauseArr[] = " VIDEO_TAB.tab_id in ($tab_ids) ";
			$keyArr[] = $tab_ids;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = " VIDEO_TAB.status='$status'";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
			$orderby=$orderby;
			$keyArr[] = "order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql="Select * from VIDEO_TAB $whereClauseStr $limitStr $orderby";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get video details list
	*
	* @param an integer $video_id.
	* @param an integer/comma seperated type ids/ type ids array $type_id.
	* @param is an integer $content_type
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetVideoDetails($video_id="",$type_id="",$content_type="",$status="1",$startlimit="",$cnt="9"){
		$keyArr[] = $this->videokey."_arrGetVideoDetails";
		if($status != ''){
			$whereClauseArr[] = " VG.status=$status ";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_id!=""){
			$whereClauseArr[] = " VG.video_id=$video_id ";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		if($type_id!=""){
			if(is_array($type_id)){
				$whereClauseArr[] = " VG.type_id in (".implode(",",$type_id).")";
				$keyArr[] = implode(",",$type_id);
			}else{
				$whereClauseArr[] = " VG.type_id=$type_id ";
				$whereClauseArr[] = " VG.type_id=VT.tab_id ";
				$keyArr[] =$type_id;
			}
		}else{$keyArr[] = -1;}
		if($content_type!=""){
			$whereClauseArr[] = " VG.content_type=$content_type";
			$keyArr[] = $content_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql="Select *,VG.create_date as create_date from VIDEO_GALLERY VG, VIDEO_TAB VT $whereClauseStr $limitStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get most popular videos list
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated tbl_types/ tbl_types array $tbl_types.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $ordering.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function getarrMostPopularVideos($video_ids="",$category_ids="",$tbl_types="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey."_getarrMostPopularVideos";
		if(is_array($video_ids)){
				$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_id)){
				$category_id = implode(",",$category_id);
		}
		if(is_array($tbl_types)){
				$tbl_type = implode(",",$tbl_types);
		}
		if($status != ''){
				$whereClauseArr[] ="status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
				$whereClauseArr[] = "video_id in ($video_ids)";
				$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
				$whereClauseArr[] = " category_id in ($category_id)";
				$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($tbl_type != ""){
				$whereClauseArr[] = " tbl_type in ($tbl_type)";
				$keyArr[] = $tbl_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
				$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
				$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby="order by create_date DESC";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from MOST_POPULAR_VIDEOS $whereClauseStr $orderby $limitStr";
		#echo $sSql.'<Br/>';
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
   }
	function getarrMostPopularVideosCnt($video_ids="",$category_ids="",$tbl_types="",$status="1",$orderby=""){
		$keyArr[] = $this->videokey."_getarrMostPopularVideosCnt";
		if(is_array($video_ids)){
				$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_id)){
				$category_id = implode(",",$category_id);
		}
		if(is_array($tbl_types)){
				$tbl_type = implode(",",$tbl_types);
		}
		if($status != ''){
				$whereClauseArr[] ="status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
				$whereClauseArr[] = "video_id in ($video_ids)";
				$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
				$whereClauseArr[] = " category_id in ($category_id)";
				$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($tbl_type != ""){
				$whereClauseArr[] = " tbl_type in ($tbl_type)";
				$keyArr[] = $tbl_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
				$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
				$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby="order by create_date DESC";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select count(id) as cnt from MOST_POPULAR_VIDEOS $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
   }

	/**
	* @note function is used to get clinck videos list
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated tbl_types/ tbl_types array $tbl_types.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $ordering.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
    function getarrClinckVideos($video_ids="",$category_ids="",$tbl_types="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey."_clinckdata";
		if(is_array($video_ids)){
				$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_id)){
				$category_id = implode(",",$category_id);
		}
		if(is_array($tbl_types)){
				$tbl_type = implode(",",$tbl_types);
		}
		if($status != ''){
				$whereClauseArr[] = "status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
				$whereClauseArr[] = "video_id in ($video_ids)";
				$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
				$whereClauseArr[] = " category_id in ($category_id)";
				$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($tbl_type != ""){
				$whereClauseArr[] = " tbl_type in ($tbl_type)";
				$keyArr[] = $tbl_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
				$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
				$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
				$orderby=" order by create_date DESC ";
				$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from CLINCK_VIDEOS $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
   }
	/**
	* @note function is used to get Langguage details list
	*
	* @param an integer/comma seperated language ids/ section ids array $language_ids.
	* @param string $language_name.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetLanguageDetails($language_ids="",$category_ids="",$language_name="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_languagedetail";
		if(is_array($language_ids)){
			$language_ids = implode(",",$language_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($language_ids!=""){
			$whereClauseArr[] = "language_id in ($language_ids)";
			$keyArr[] = $language_ids;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($language_name != ''){
			$whereClauseArr[] = "language_name=$language_name";
			$keyArr[] = $language_name;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(empty($orderby)) {
			$orderby= " order by create_date DESC ";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT * FROM LANGUAGE_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to generate array with standard key elements
	*
	* @param is an array $result_list
	* @param is an integer $type
	* @param is an integer $cat_type_id
	* @param is an integer $tbl_type
	*
	* @post associative array.
	* @post return array
	*
	*/
	function arrGenerate($result_list,$type,$cat_type_id="",$tbl_type="",$start="",$end=""){
		$result_list_arr =Array();
		if(empty($start)){
			$start = 0;
		}
		if(empty($end)){
		      $end = sizeof($result_list);
    		}
		$j=0;
		for($i=$start;$i<$end;$i++){
			$result_list_arr[$j]=Array();
			$result_list_arr[$j]["video_id"]=$result_list[$i]["video_id"];
			$result_list_arr[$j]["product_info_id"]=$result_list[$i]["product_info_id"];
			$video_id = $result_list[$i]["video_id"];
			$result_list_arr[$j]["title"]=$result_list[$i]["title"];
			$result_list_arr[$j]["media_title"]=$result_list[$i]["media_title"];
			$result_list_arr[$j]["tags"]=$result_list[$i]["tags"];
			$result_list_arr[$j]["media_id"]=$result_list[$i]["media_id"];
			$result_list_arr[$j]["media_path"]=$result_list[$i]["media_path"];
			$result_list_arr[$j]["video_img_id"]=$result_list[$i]["video_img_id"];
			$result_list_arr[$j]["video_img_path"]=$result_list[$i]["video_img_path"];
			$result_list_arr[$j]["content_type"]=$result_list[$i]["content_type"];
			$result_list_arr[$j]["is_media_process"]=$result_list[$i]["is_media_process"];
			$result_list_arr[$j]["media_source_flag"]=$result_list[$i]["media_source_flag"];
			$result_list_arr[$j]["external_media_source"]=$result_list[$i]["external_media_source"];
			$result_list_arr[$j]["status"]=$result_list[$i]["status"];
			$result_list_arr[$j]["create_date"]=$result_list[$i]["create_date"];
			$result_list_arr[$j]["uipdate_date"]=$result_list[$i]["update_date"];
			$result_list_arr[$j]["type_id"]=$result_list[$i]["type_id"];
			$result_list_arr[$j]["article_type"]=$result_list[$i]["article_type"];
			//if($type != ""){
				$result_list_arr[$j]["type"]= $type; //? $type ; //: $result_list[$i]["tbl_type"];
			//}
			$result_list_arr[$j]["tbl_type"] = $tbl_type ? $tbl_type : '';
			$result_list_arr[$j]["cat_type_id"]= $cat_type_id ? $cat_type_id : $result_list[$i]["type_id"];
		  $j++;
		}
		return $result_list_arr;
  	}

	/**
	* @note function is used to sort an array
	*
	* @param is an array $array
	* @param is  a string $index
	* @param is  a string $order
	* @param is  a boolean $natsort
	* @param is  a boolean $case_sensitive
	*
	* @post associative array.
	* @post return array
	*
	*/
	function sort2d ($array, $index, $order='asc', $natsort=FALSE, $case_sensitive=FALSE){
                if(is_array($array) && count($array)>0){
                        foreach(array_keys($array) as $key)
                                $temp[$key]=$array[$key][$index];
                        if(!$natsort)
                                ($order=='asc')? asort($temp) : arsort($temp);
                        else {
                                ($case_sensitive)? natsort($temp) : natcasesort($temp);
                                if($order!='asc')
                                        $temp=array_reverse($temp,TRUE);
                        }
                        foreach(array_keys($temp) as $key)
                                (is_numeric($key))? $sorted[]=$array[$key] : $sorted[$key]=$array[$key];
                        return $sorted;
                }
                return $array;
        }

	function arrGenerateSameFormatArray($result_list,$select_section_id="",$select_type_id="",$content_type_id="",$image_path=""){
		    $result_list_arr =Array();
			for($i=0;$i<sizeof($result_list);$i++){
				$result_list_arr[$i]=Array();
				$result_list_arr[$i]["id"]=$result_list[$i]["id"];

				if($select_type_id==2){
					if($select_section_id=="VIDEOS"){
						$result_list_arr[$i]["item_id"]= $result_list[$i]["video_id"];
					}elseif($select_section_id=="REVIEWS"){
						$result_list_arr[$i]["item_id"]= $result_list[$i]["review_id"];
					}
					elseif($select_section_id=="ARTICLES"){
						$result_list_arr[$i]["item_id"]= $result_list[$i]["article_id"];
					}
					elseif($select_section_id=="NEWS"){
						$result_list_arr[$i]["item_id"]= $result_list[$i]["article_id"];
					}else{
						$result_list_arr[$i]["item_id"]= $result_list[$i]["video_id"];
					}
				}elseif($select_type_id==1){
					$result_list_arr[$i]["item_id"]= $result_list[$i]["video_id"];
				}
				else{
					$result_list_arr[$i]["item_id"]= $result_list[$i]["item_id"];
				}

				$item_id = $result_list[$i]["item_id"];
				$result_list_arr[$i]["title"] = $result_list[$i]["title"];

				$result_list_arr[$i]["brand_id"] = $result_list[$i]["brand_id"];
				$result_list_arr[$i]["product_info_id"] = $result_list[$i]["product_info_id"];
				$result_list_arr[$i]["product_id"] = $result_list[$i]["product_id"];
				$result_list_arr[$i]["group_id"] = $result_list[$i]["group_id"];
				$result_list_arr[$i]["abstract"] = $result_list[$i]["abstract"];
				$result_list_arr[$i]["media_id"] = $result_list[$i]["media_id"];
				$result_list_arr[$i]["media_path"]= $result_list[$i]["media_path"];

				$result_list_arr[$i]["video_img_id"] = $result_list[$i]["video_img_id"];
				$result_list_arr[$i]["video_img_path"] = $result_list[$i]["image_path"] ? $result_list[$i]["image_path"] :$result_list[$i]["video_img_path"];
				//$result_list_arr[$i]["video_img_path"] = $result_list[$i]["image_path"];

				$result_list_arr[$i]["content_type"] = $result_list[$i]["content_type"];
				$result_list_arr[$i]["content_type_id"] = $content_type_id;
				$result_list_arr[$i]["is_media_process"] = $result_list[$i]["is_media_process"];
				$result_list_arr[$i]["status"] = $result_list[$i]["status"];
				$result_list_arr[$i]["create_date"] = $result_list[$i]["create_date"];
				$result_list_arr[$i]["uipdate_date"] = $result_list[$i]["update_date"];
				$result_list_arr[$i]["type"]= $type ? $type : $result_list[$i]["tbl_type"];
				$result_list_arr[$i]["tbl_type"] = $result_list[$i]["tbl_type_id"];
				//$result_list_arr[$i]["tbl_type_id"]= $cat_type_id ? $cat_type_id : $result_list[$i]["tbl_type_id"];
				$result_list_arr[$i]["img_path"]= $image_path ? $image_path : $result_list[$i]["img_path"];
				$result_list_arr[$i]["type_id"]=$result_list[$i]["type_id"];
			}
		   return $result_list_arr;
   }

	function getArrItemData($item_ids="",$category_ids="",$content_type_id="",$tbl_types="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey."_itemdatahome";
		if(is_array($item_ids)){
				$item_ids = implode(",",$item_ids);
		}
		if(is_array($category_id)){
				$category_id = implode(",",$category_id);
		}
		if(is_array($tbl_types)){
				$tbl_type = implode(",",$tbl_types);
		}
		if($status != ''){
				$whereClauseArr[] = "status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($item_ids!=""){
				$whereClauseArr[] = "item_id in ($item_ids)";
				$keyArr[] = $item_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
				$whereClauseArr[] = " category_id in ($category_id)";
				$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($tbl_type != ""){
				$whereClauseArr[] = " tbl_type in ($tbl_type)";
				$keyArr[] = $tbl_type;
		}else{$keyArr[] = -1;}
		if($content_type_id != ""){
				$whereClauseArr[] = " content_type_id in ($content_type_id)";
				$keyArr[] = $content_type_id;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
				$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
				$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
				$orderby=" order by create_date DESC ";
				$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from ONCARS_INDIA_HOMEPAGE_ITEMS $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
   }

	function booldeleteItemData($id="",$table_name=""){
		$sSql="delete from $table_name where id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $iRes;
	}

	/**
	* @note function is used to insert Video Type details.
	*
	* @param associative array $insert_param.
	* @pre $insert_param must be valid non-empty associative array.
	* @post integer $id.
	* return integer.
	*/
	function intInsertVideoType($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("VIDEO_TYPE_GALLERY",array_keys($insert_param),array_values($insert_param));
		//echo $sql;
		$id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->videokey."_typegallerydet");
		if($id == 'Duplicate entry'){ return 'exists';}
		return $id;
	}
	/**
	* @note function is used to update the video type details into the database.
	* @param an associative array $update_param.
	* @param an integer $id.
	* @pre $update_param must be valid associative array and $id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* retun boolean.
	*/
	function boolUpdateVideoType($id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("VIDEO_TYPE_GALLERY",array_keys($update_param),array_values($update_param),"id",$id);
		//echo $sql;
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->videokey."_typegallerydet");
		return $isUpdate;
	}

	/**
	* @note function is used  delete video type
	*
	* @param id ,table_name
	* @pre  id is single id of integer type
	* @pre  table_name is database table name
	* @post return true if successful , false if error occurs
	*/
	function booldeleteVideoType($id="",$table_name=""){
		$sSql="delete from $table_name where id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey."_typegallerydet");
		return $iRes;
	}

	/**
	* @note function is used to get video type details list
	*
	* @param an integer/comma seperated ids/ ids array $ids.
	* @param an integer/comma seperated video type ids/ video type ids array $video_type_ids.
	* @param an integer/comma seperated video sub type ids/ video sub type ids array $video_sub_type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetVideoTypeGalleryDetails($ids="",$video_type_ids="",$video_sub_type_ids="",$category_ids="",$video_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_typegallerydet_arrGetVideoTypeGalleryDetails";
		if(is_array($ids)){
				$ids = implode(",",$ids);
		}
		if(is_array($video_type_ids)){
				$video_type_ids = implode(",",$video_type_ids);
		}
		if(is_array($video_sub_type_ids)){
				$video_sub_type_ids = implode(",",$video_sub_type_ids);
		}
		if(is_array($video_ids)){
				$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_ids)){
				$category_ids = implode(",",$category_ids);
		}
		if($ids!=""){
				$whereClauseArr[] = "id in ($ids)";
				$keyArr[] = $ids;
		}else{$keyArr[] = -1;}
		if($video_type_ids!=""){
				$whereClauseArr[] = "video_type_id in ($video_type_ids)";
				$keyArr[] = $video_type_ids;
		}else{$keyArr[] = -1;}
		if($video_sub_type_ids!=""){
				$whereClauseArr[] = "video_sub_type_id in ($video_sub_type_ids)";
				$keyArr[] = $video_sub_type_ids;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
				$whereClauseArr[] = "video_id in ($video_ids)";
				$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
				$whereClauseArr[] = "category_id in ($category_ids)";
				$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($status != ''){
				$whereClauseArr[] = "status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(empty($orderby)) {
				$orderby= " order by create_date DESC ";
				$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
				$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
				$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT * FROM  VIDEO_TYPE_GALLERY $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to insert Video Sub Type details.
	*
	* @param associative array $insert_param.
	* @pre $insert_param must be valid non-empty associative array.
	* @post integer $video_sub_type_id.
	* return integer.
	*/
	function intInsertVideoSubType($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("VIDEO_SUB_TYPE",array_keys($insert_param),array_values($insert_param));
		//echo $sql;
		$video_sub_type_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->videokey."_subtypedet");
		if($video_sub_type_id == 'Duplicate entry'){ return 'exists';}
		return $video_sub_type_id;
	}
	/**
	 * @note function is used to update the video type details into the database.
	 * @param an associative array $update_param.
	 * @param an integer $id.
	 * @pre $update_param must be valid associative array and $id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateVideoSubType($video_sub_type_id,$update_param){
		 $update_param['create_date'] = date('Y-m-d H:i:s');
		 $sql = $this->getUpdateSql("VIDEO_SUB_TYPE",array_keys($update_param),array_values($update_param),"video_sub_type_id",$video_sub_type_id);
		 //echo $sql;
		 $isUpdate = $this->update($sql);
		 $this->cache->searchDeleteKeys($this->videokey."_subtypedet");
		 return $isUpdate;
	 }
	/**
	* @note function is used  delete video sub type
	*
	* @param video_sub_type_id,table_name
	* @pre  video_sub_type_id is single id of integer type
	* @pre  table_name is database table name
	* @post return true if successful , false if error occurs
	*/
	function booldeleteVideoSubType($video_sub_type_id="",$table_name=""){
			$sSql="delete from $table_name where video_sub_type_id='".$video_sub_type_id."'";
			$iRes=$this->sql_delete_data($sSql);
			$this->cache->searchDeleteKeys($this->videokey."_subtypedet");
			return $iRes;
	}

	/**
	* @note function is used to get video sub type details list
	*
	* @param an integer/comma seperated video sub type ids/ video sub type ids array $video_sub_type_ids.
	* @param an integer/comma seperated video type ids/ video type ids array $video_type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param is a string $sub_type_name.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
    function arrGetVideoSubTypeDetails($video_sub_type_ids="",$video_type_ids="",$category_ids="",$sub_type_name="",$status="",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_subtypedet_arrGetVideoSubTypeDetails";
		if(is_array($video_sub_type_ids)){
				$video_sub_type_ids = implode(",",$video_sub_type_ids);
		}
		if(is_array($video_type_ids)){
				$video_type_ids = implode(",",$video_type_ids);
		}
		if(is_array($category_ids)){
				$category_ids = implode(",",$category_ids);
		}
		 if($video_sub_type_ids != ''){
				$whereClauseArr[] = " video_sub_type_id in ($video_sub_type_ids) ";
				$keyArr[] = $video_sub_type_ids;
		}else{$keyArr[] = -1;}
		if($video_type_ids != ''){
				$whereClauseArr[] = " video_type_id in ($video_type_ids) ";
				$keyArr[] = $video_type_ids;
		}else{$keyArr[] = -1;}
		if($category_ids != ''){
				$whereClauseArr[] = " category_id in ($category_ids) ";
				$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if(!empty($sub_type_name)){
				$whereClauseArr[] = "lower(sub_type_name) = ".strtolower($sub_type_name);
				$keyArr[] = strtolower($sub_type_name);
		}else{$keyArr[] = -1;}
		if($status != ''){
				$whereClauseArr[] = "status=$status";
				$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
				$limitArr[] = $startlimit;
				$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
				$limitArr[] = $cnt;
				$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
				$orderby= "order by create_date desc";
				$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql="Select * from VIDEO_SUB_TYPE $whereClauseStr $limitStr $orderby";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
    }

	function intInsertHomeVideo($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("HOME_VIDEOS",array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $result;
	}

	function booldeleteHomeVideos($id=""){
		$sSql="delete from HOME_VIDEOS where id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey);
		return $iRes;
	}

	function intInsertPICKVIDEOS($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("EDITOR_PICK_VIDEOS",array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey.'_editorpick');
		return $result;
	}


	function booldeletePICKVIDEOS($id=""){
		$sSql="delete from EDITOR_PICK_VIDEOS where id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey.'_editorpick');
		return $iRes;
	}


	function getarrHomeVideos($video_ids="",$category_ids="",$tbl_types="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey."_homedata";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($tbl_types)){
			$tbl_type = implode(",",$tbl_types);
		}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($tbl_type != ""){
			$whereClauseArr[] = " tbl_type in ($tbl_type)";
			$keyArr[] = $tbl_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby=" order by create_date DESC ";
			$keyArr[] = "order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from HOME_VIDEOS $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	function intInsertMostRecentVideo($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("MOST_RECENT_VIDEOS",array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey."_most_recent");
		return $result;
	}

	function booldeleteMostRecentVideos($id=""){
		$sSql="delete from MOST_RECENT_VIDEOS where id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey."_most_recent");
		return $iRes;
	}

	function getarrMostRecentVideos($video_ids="",$category_ids="",$tbl_types="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey."_most_recentdata";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($tbl_types)){
			$tbl_type = implode(",",$tbl_types);
		}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($tbl_type != ""){
			$whereClauseArr[] = " tbl_type in ($tbl_type)";
			$keyArr[] = $tbl_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);

		}
		if($orderby == ""){
			$orderby=" order by create_date DESC ";
			$keyArr[] = "order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from MOST_RECENT_VIDEOS $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}


	/**
	* @note function is used  get language video detail list (With Product table join)
	*
	* @param an integer/comma seperated video ids/ video ids array $video_ids.
	* @param an integer/comma seperated video types/ video types array $video_types.
	* @param an integer/comma seperated language ids/ language ids array $language_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param boolean Active/InActive $status
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $orderby.
	* @pre not required.
	*
	* @post return array if successful , 0 if error occurs
	*
	*/

	function getLatestLanguageVideosDetails($video_ids="",$video_type="",$language_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_ids="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$tablenameArr = Array("1"=>"PRODUCT_VIDEOS","2"=>"PRODUCT_VIDEOS","3"=>"UPLOAD_MEDIA_REVIEWS","4"=>"UPLOAD_MEDIA_ARTICLE","5"=>"UPLOAD_MEDIA_NEWS");
		$colomnArr = Array("1"=>"video_id","2"=>"video_id","3"=>"upload_media_id","4"=>"upload_media_id","5"=>"upload_media_id");
		$root_tbl = "LANGUAGE_VIDEO_GALLERY";
		$tableArr[] = $root_tbl;
		$keyArr[] = $this->videokey."_latest_langauge_data";
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(!empty($video_type)){
			$whereClauseArr[] = $root_tbl.".video_type = $video_type";
			$refer_tbl = $tablenameArr[$video_type];
			$whereClauseArr[] = $root_tbl.".video_id = ".$refer_tbl.".".$colomnArr[$video_type];
			$tableArr[] = $refer_tbl;
		}
		if(is_array($language_ids)){
			$language_ids = implode(",",$language_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
		}
		if(is_array($product_info_id)){
			$product_info_id = implode(",",$product_info_id);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		$whereClauseArr[] ="is_media_process =1";
		if($status != ''){
			$whereClauseArr[] = $root_tbl.".status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = $root_tbl.".video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($language_ids!=""){
			$whereClauseArr[] = $root_tbl.".language_id in ($language_ids)";
			$keyArr[] = $language_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=''){
			$whereClauseArr[] = $root_tbl.".product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = $root_tbl.".product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}

		if($category_id!=""){
			$whereClauseArr[] = $root_tbl.".category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = $root_tbl.".brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = $root_tbl.".content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby="order by ".$root_tbl.".create_date DESC";
			$keyArr[] = "order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$table_name = implode(",",$tableArr);
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select $root_tbl.* from $table_name $whereClauseStr $orderby $limitStr";
		//echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

   function getarrPICKVIDEOS($video_ids="",$category_ids="",$tbl_types="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->videokey.'_editorpick_getarrPICKVIDEOS';
		if(is_array($video_ids)){
			$video_ids = implode(",",$video_ids);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($tbl_types)){
			$tbl_type = implode(",",$tbl_types);
		}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($video_ids!=""){
			$whereClauseArr[] = "video_id in ($video_ids)";
			$keyArr[] = $video_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($tbl_type != ""){
			$whereClauseArr[] = " tbl_type in ($tbl_type)";
			$keyArr[] = $tbl_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
			$orderby="order by create_date DESC ";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from EDITOR_PICK_VIDEOS $whereClauseStr $orderby $limitStr";
		//echo $sSql;//exit;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to insert Video Tab details.
	*
	* @param associative array $insert_param.
	* @pre $insert_param must be valid non-empty associative array.
	* @post integer $tab_id.
	* return integer.
	*/
	function intInsertVideoTab($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("VIDEO_TAB",array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->videokey."_tab");
		return $result;
	}
	/**
	@note function is used  delete video tab
	*
	* @param tab_id
	* @pre  tab id is single tab id of integer type
	* @post return true if successful , false if error occurs
	*/
	function booldeleteVideoTab($id=""){
		$sSql="delete from VIDEO_TAB where tab_id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->videokey."_tab");
		return $iRes;
	}

	/**
	* @note function is used to get tab details list
	*
	* @param an integer/comma seperated tab ids/ tab ids array $tab_ids.
	* @param an integer/comma seperated category ids/ tab ids array $category_ids.
	* @param an integer/comma seperated order tabs/ order tabs array $order_tabs.
	* @param a string $tab_name.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function getTabDetails($tab_ids="",$category_ids="",$tab_name="",$order_tabs="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->videokey."_tabdetail";
		if(is_array($tab_ids)){
			$tab_ids = implode(",",$tab_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($order_tabs)){
			$order_tabs = implode(",",$order_tabs);
		}
		if($tab_ids != ''){
			$whereClauseArr[] = " tab_id in ($tab_ids) ";
			$keyArr[] = $tab_ids;
		}else{$keyArr[] = -1;}
		if($category_ids != ''){
			$whereClauseArr[] = " category_id in ($category_ids) ";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($order_tabs != ''){
			$whereClauseArr[] = " order_tab in ($order_tabs) ";
			$keyArr[] = $order_tabs;
		}else{$keyArr[] = -1;}
		if(!empty($tab_name)){
			$whereClauseArr[] = "lower(tab_name) = ".strtolower($tab_name);
			$keyArr[] = strtolower($tab_name);
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
			$orderby= "order by create_date desc";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql="Select * from VIDEO_TAB $whereClauseStr $limitStr $orderby";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function getVideoUrl($cat_type_id,$category_id){
		require_once('reviews.class.php');
		require_once('article.class.php');
		$reviews = new reviews();
		$article = new article();
		unset($seoTitleArr);
		$seoTitleArr[] = SEO_WEB_URL;
		if($cat_type_id == "2"){
			$result_list=$reviews->arrGetReviewsVideoDetails("","","","",$category_id,"","1","0","1");
			$result = $this->arrGenerate($result_list);
			$seoTitleArr[] = SEO_CAR_VIDEOS_REVIEW; //Car-Video-Reviews
		}elseif($cat_type_id == "7"){
			$result = $this->arrGetVideoDetails("",$cat_type_id,"1","1","0","1");
			$seoTitleArr[] = SEO_CAR_VIDEOS_FIRST_DRIVE; //Car-Video-First-Drive
		}elseif($cat_type_id == "4"){
			$result = $this->arrGetVideoDetails("",$cat_type_id,"1","1","0","1");
			$seoTitleArr[] = SEO_CAR_VIDEOS_INTERNATIONAL; //Car-Video-International
		}elseif($cat_type_id == "5"){
			$result = $this->getVideosDetails("","",$cat_type_id,"","",$category_id,"","1","0","1","order by V.create_date desc");
			$seoTitleArr[] = SEO_CAR_VIDEOS_OTHERS; //Car-Video-Others
		}else if($cat_type_id == "6"){
			$result_list=$article->arrGetArticleVideoDetails("","","","",$category_id,"","1");
			$result = $this->arrGenerate($result_list);
			$seoTitleArr[] = SEO_CAR_VIDEOS_MAINTAINANCE; //Car-Video-DIY
		}else if($cat_type_id == "9"){
			$result_list=$article->arrGetNewsVideoDetails("","","","",$category_id,"","1");
			$result = $this->arrGenerate($result_list);
			$seoTitleArr[] = SEO_CAR_VIDEOS_NEWS; //Car-Video-News
		}else if($cat_type_id == "8"){
			$result = $this->arrGetVideoDetails("",$cat_type_id,"1","1","0","1");
			$seoTitleArr[] = SEO_CAR_VIDEOS_QUICK_TEST; //Car-Quick -Test
		}else if($cat_type_id == "3"){
			$result_list = $this->arrGetVideoDetails("",$cat_type_id,"1","1","0","1");
			$result = $this->arrGenerate($result_list);
			$seoTitleArr[] =  SEO_CAR_VIDEOS_AUTO_PORN; //Auto Poron
		}else if($cat_type_id == "10"){
			$result = $this->getLanguageVideosDetails("",$vid_type,"","","","","",$category_id,"","1","","","");
		}
		if($cat_type_id != "10"){
			$title = $result[0]["title"];
			$video_id = $result[0]["video_id"];
			$type = $result[0]["type"];
			$tbl_type = $result[0]["tbl_type"];

			if(!empty($title)){
			$title = urlencode($title);
			$title = str_replace("+","-",$title);
			$seoTitleArr[] = trim(urlencode($title));
			}
			if(!empty($video_id)){$seoTitleArr[] = $video_id;}
			if($type !=""){$seoTitleArr[] = $type;}
			if($tbl_type !=""){$seoTitleArr[] = $tbl_type;}
		}else{
			unset($seoTitleArr);
			$seoTitleArr[] = HINDI_VIDEO_URL;
		}
		$url = implode("/",$seoTitleArr);
		return $url;
	}


	function getarrMostPopularVideosList($category_id,$start,$perpage){
		require_once(CLASSPATH.'reviews.class.php');
		require_once(CLASSPATH.'article.class.php');
		$reviews = new reviews();
		$article = new article();
		$result=$this->getarrMostPopularVideos("",$category_id,"","1",$start,$perpage,"");
		for($i=0;$i<sizeof($result);$i++){
			unset($res);
			$result[$i]['id'] = $result[$i]['id'];
			$video_id = $result[$i]['video_id'];
			$tbl_type = $result[$i]['tbl_type'];
			if($tbl_type == 1){
				//Videos
				$res = $this->getVideosDetails($video_id,"","","","",$category_id,"","1","","","");
			}else if($tbl_type == 2){
				//Reviews
				$res = $reviews->arrGetReviewsVideoDetails($video_id,"","","",$category_id,"","1","","");
			}else if($tbl_type == 3){
				//Articles
				$res = $article->arrGetArticleVideoDetails($video_id,"","","",$category_id,"","1");
			}else if($tbl_type == 4){
				//News
				$res = $article->arrGetNewsVideoDetails($video_id,"","","",$category_id,"","1");
			}
			$res = $this->arrGenerate($res,"","",$tbl_type);
			$result_list[$i] = $res["0"];
		}
		return $result_list;
	}

	function getarrMostRecentVideosList($category_id,$start,$perpage){
		require_once(CLASSPATH.'reviews.class.php');
		require_once(CLASSPATH.'article.class.php');
		$reviews = new reviews();
		$article = new article();
		$result=$this->getarrMostRecentVideos("",$category_id,"","1",$start,$perpage,"");
		for($i=0;$i<sizeof($result);$i++){
			unset($res);
			$result[$i]['id'] = $result[$i]['id'];
			$video_id = $result[$i]['video_id'];
			$tbl_type = $result[$i]['tbl_type'];
			if($tbl_type == 1){
				//Videos
				$res = $this->getVideosDetails($video_id,"","","","",$category_id,"","1","","","");
			}else if($tbl_type == 2){
				//Reviews
				$res=$reviews->arrGetReviewsVideoDetails($video_id,"","","",$category_id,"","1","","");
			}else if($tbl_type == 3){
				//Articles
				$res=$article->arrGetArticleVideoDetails($video_id,"","","",$category_id,"","1");
			}else if($tbl_type == 4){
				//News
				$res=$article->arrGetNewsVideoDetails($video_id,"","","",$category_id,"","1");
			}
			$res = $this->arrGenerate($res,"","",$tbl_type);
			$result_list[$i] = $res["0"];
		}
		return $result_list;
	}

	function getarrMostRecentVideosHeaderTabLink($category_id,$start,$perpage,$array_result){
	    	require_once(CLASSPATH.'Utility.php');
		$array_result = $this->getarrMostRecentVideosList("",$category_id,$start,$perpage);
		$most_recent_one_title = $array_result[0]['title'];
		$most_recent_one_video_id = $array_result[0]['video_id'];
		$most_recent_one_tbl_type = $array_result[0]["tbl_type"];
		$most_recent_one_type = $array_result[0]["type"];
		$most_recent_one_type_id = $array_result[0]["type_id"];
		$most_recent_one_cat_type_id = $array_result[0]["cat_type_id"];
		$article_type=$array_result[0]["article_type"];
		$stitle = html_entity_decode($most_recent_one_title,ENT_QUOTES,'UTF-8');
		//$stitle = removeSlashes($stitle);
		//$stitle = seo_title_replace($stitle);
	        $stitle = constructUrl($stitle);
		if($most_recent_one_tbl_type == "1"){
			if($most_recent_one_type_id=="3"){
				$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_AUTO_PORN;
			}elseif($most_recent_one_type_id=="4"){
				$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_INTERNATIONAL;
			}elseif($most_recent_one_type_id=="5"){
				$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_FEATURED;
			}elseif($most_recent_one_type_id=="7"){
				$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_FIRST_DRIVE;
			}elseif($most_recent_one_type_id=="8"){
				$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_QUICK_TEST;
			}
		}
		elseif($most_recent_one_tbl_type == "2"){
			$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_REVIEW;
		}
		elseif($most_recent_one_tbl_type == "3"){
			if($article_type==3){
			$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_MAINTAINANCE;
			}else{
			  $seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_FEATURED;
			}
		}
		elseif($most_recent_one_tbl_type == "4"){
			$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_NEWS;
		}
		$seoTitleArr[] = $stitle;
		$seoTitleArr[] = $most_recent_one_video_id;
		$most_recent_one_video_url = implode("/",$seoTitleArr);
		return $most_recent_one_video_url;
	}

	function getarrPICKVIDEOSList($video_ids="",$category_ids="",$tbl_types="",$status="1",$startlimit="",$cnt="", $orderby=""){
		require_once(CLASSPATH.'reviews.class.php');
		require_once(CLASSPATH.'article.class.php');
		$reviews = new reviews();
		$article = new article();
		$result = $this->getarrPICKVIDEOS("",$category_ids,"","1",$startlimit,$cnt, $orderby);
		for($i=0;$i<sizeof($result);$i++){
			unset($res);
			$result[$i]['id'] = $result[$i]['id'];
			$video_id = $result[$i]['video_id'];
			$tbl_type = $result[$i]['tbl_type'];
			if($tbl_type == 1){
				//Videos
				$res = $this->getVideosDetails($video_id,"","","","",$category_id,"","1","","","");
			}else if($tbl_type == 2){
				//Reviews
				$res = $reviews->arrGetReviewsVideoDetails($video_id,"","","",$category_id,"","1","","");
			}else if($tbl_type == 3){
				//Articles
				$res = $article->arrGetArticleVideoDetails($video_id,"","","",$category_id,"","1");
			}else if($tbl_type == 4){
				//News
				$res = $article->arrGetNewsVideoDetails($video_id,"","","",$category_id,"","1");
			}
			$res = $this->arrGenerate($res,"","",$tbl_type);
			$result_list[$i] = $res["0"];
		}
		return $result_list;
	}

	function getarrReviewsVideosHeaderTabLink($category_id,$start,$perpage){
		require_once(CLASSPATH.'Utility.php');
		require_once(CLASSPATH.'reviews.class.php');
		$reviews = new reviews();
		$result = $reviews->arrGetReviewsVideoDetails("","","","",$category_id,"","1",$start,$perpage,"order by update_date desc");
		$result_list = $this->arrGenerate($result,"","",$tab_selected);
		unset($seoTitleArr);
		if(sizeof($result_list) > 0){
			$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_REVIEW; //Car-Video-Reviews
			$stitle = html_entity_decode($result_list["0"]["title"],ENT_QUOTES,'UTF-8');
	                $stitle = constructUrl($stitle);
        	        $stitle = removeSlashes($stitle);

                	$seoTitleArr[] = urlencode($stitle);
                	$seoTitleArr[] = $result_list["0"]["video_id"];
	                $seo_play_url = implode("/",$seoTitleArr);
		}
		return $seo_play_url;
	}

	function getarrNewsVideosMoreLink($category_id,$start,$perpage){
		require_once(CLASSPATH.'Utility.php');
		require_once(CLASSPATH.'article.class.php');
		$oArticle =  new article;
		$result = $oArticle->arrGetNewsVideoDetails("","","","",$category_id,"","1","",0,1,"order by update_date desc");
		$result_list = $this->arrGenerate($result,"","",$tab_selected);
		unset($seoTitleArr);
		if(sizeof($result_list) > 0){
			$seoTitleArr[] = WEB_URL.SEO_CAR_VIDEOS_NEWS; //Car-Video-Reviews
			$stitle = html_entity_decode($result_list["0"]["title"],ENT_QUOTES,'UTF-8');
	                $stitle = constructUrl($stitle);
        	        $stitle = removeSlashes($stitle);

                	$seoTitleArr[] = urlencode($stitle);
                	$seoTitleArr[] = $result_list["0"]["video_id"];
	                $seo_play_url = implode("/",$seoTitleArr);
		}
		$seo_play_url;
		return $seo_play_url;
	}

	/**
        * @note function is used to get video slug details list
        *
        * @param an integer/comma seperated video slug ids/ video slug ids array $video_slug_ids.
        * @param an integer/comma seperated video ids/ video ids array $video_ids.
        * @param a string slug_title string $slug_title.
        * @param an integer category id integer $category_id.
        * @param boolean Active/InActive $status
        * @param integer $startlimit.
        * @param integer $cnt.
        * @param is a string $orderby.
        * @pre not required.
        *
        * @post associative array.
        * @post return array if successful , 0 if error occurs
        *
        */
	function arrGetVideoSlugDetails($video_slug_ids="",$video_ids="",$slug_title="",$category_id="",$status="1",$startlimit="",$cnt="",$orderby=""){
                $keyArr[] = $this->videokey."_slug_det";

                if(is_array($video_slug_ids)){
                    $video_slug_ids = implode(",",$video_slug_ids);
                }
                if(is_array($video_ids)){
                    $video_ids = implode(",",$video_ids);
                }
                if(!empty($video_slug_ids)){
                        $whereClauseArr[] = " video_slug_id in ($video_slug_ids) ";
                        $keyArr[] = $video_slug_ids;
                }else{$keyArr[] = -1;}
                if(!empty($video_ids)){
                        $whereClauseArr[] = " video_id in ($video_ids) ";
                        $keyArr[] = $video_ids;
                }else{$keyArr[] = -1;}
                if(!empty($slug_title)){
                        $whereClauseArr[] = "lower(slug_title) = '".strtolower($slug_title)."'";
                        $keyArr[] = strtolower($slug_title);
                }else{$keyArr[] = -1;}
                if(!empty($category_id)){
                        $whereClauseArr[] = " category_id = $category_id";
                        $keyArr[] = $category_id;
                }else{$keyArr[] = -1;}
                if($status != ''){
                        $whereClauseArr[] = " status=$status";
                        $keyArr[] = $status;
                }else{$keyArr[] = -1;}
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
		if(!empty($startlimit)){
                        $limitArr[] = $startlimit;
                        $keyArr[] = $startlimit;
                }else{$keyArr[] = -1;}
                if(!empty($count)){
                        $limitArr[] = $count;
                        $keyArr[] = $count;
                }else{$keyArr[] = -1;}
                if(sizeof($limitArr) > 0){
                        $limitStr = " limit ".implode(" , ",$limitArr);
                }
                if(empty($orderby)){
                        $orderby ="order by create_date desc";
                }
                $key = implode('_',$keyArr);
                $result = $this->cache->get($key);
                if(!empty($result)){ return $result;}
                $sSql = "SELECT * FROM VIDEO_SLUG_HISTORY $whereClauseStr $orderby $limitStr";
                $result = $this->select($sSql);
                $this->cache->set($key, $result);
                return $result;
        }
	function getSearchByTagVideosResultCount($tags="",$category_id="",$status="1"){
		$keyArr[] = $this->videokey."_searchtagres_cnt";
                if(!empty($tags)){
                        if($category_id!=""){
                                //$whereClauseArr[] = " V.category_id = $category_id";
                                $keyArr[] = $category_id;
                        }else{$keyArr[] = -1;}
                        if($status != ''){
                                $whereClauseArr[] = " V.status = $status";
                                $keyArr[] = $status;
                        }else{$keyArr[] = -1;}
                        if($tags != ""){
							$whereClauseArr[] = "V.tags LIKE '%".$tags."%'";
							$keyArr[] = $tags;
                        }else{$keyArr[] = -1;}
                        $whereClauseArr[] = "V.video_id=PV.video_id";
                        $whereClauseArr[] = "V.type_id=VT.tab_id";
                        if(sizeof($whereClauseArr) > 0){
                                $whereClauseStr = " ".implode(" and ",$whereClauseArr);
                        }
                        $key = implode('_',$keyArr);
                        $sql = "select count(*) as cnt from VIDEO_GALLERY V,PRODUCT_VIDEOS PV,VIDEO_TAB VT where $whereClauseStr";
                        $result = $this->cache->get($key);
                        if(!empty($result)){
                                $result_cnt = $result;
                                return $result_cnt;
                        }
                        $result = $this->select($sql);
                        $result_cnt = $result[0]['cnt'];
                        $this->cache->set($key, $result_cnt);
                        return $result_cnt;
                }
        }
	function getSearchByTagVideosResult($tags="",$category_id="",$status="1",$startlimit="",$count="",$orderby="order by V.create_date desc"){
		$keyArr[] = $this->videokey."_searchtagres";
                if(!empty($tags)){
                        if($category_id!=""){
                                //$whereClauseArr[] = " V.category_id = $category_id";
                                $keyArr[] = $category_id;
                        }else{$keyArr[] = -1;}
                        if($status != ''){
                                $whereClauseArr[] = " V.status = $status";
                                $keyArr[] = $status;
                        }else{$keyArr[] = -1;}
                        if($tags != ""){
                                $whereClauseArr[] = "V.tags LIKE '%".$tags."%'";
				$keyArr[] = $tags;
                        }else{$keyArr[] = -1;}
                        $whereClauseArr[] = "V.video_id=PV.video_id";
                        $whereClauseArr[] = "V.type_id=VT.tab_id";

                        if(sizeof($whereClauseArr) > 0){
                                        $whereClauseStr = " ".implode(" and ",$whereClauseArr);
                        }
                        if(!empty($startlimit)){
                                $limitArr[] = $startlimit;
                                $keyArr[] = $startlimit;
                        }else{$keyArr[] = -1;}
                        if(!empty($count)){
                                $limitArr[] = $count;
                                $keyArr[] = $count;
                        }else{$keyArr[] = -1;}
                        if(sizeof($limitArr) > 0){
                                        $limitStr = " limit ".implode(" , ",$limitArr);
                        }
                        $key = implode('_',$keyArr);
                        $sql = "select *,V.video_id as article_id from VIDEO_GALLERY V,PRODUCT_VIDEOS PV,VIDEO_TAB VT where $whereClauseStr $orderby $limitStr";
			$result = $this->cache->get($key);
                        if(!empty($result)){ return $result;}
                        $result = $this->select($sql);
                        $this->cache->set($key, $result);
                        return $result;
                }
        }

	/**
	 * @note function is used to generate array with standard key elements
	 * @on 05-02-2013 by Nayan darekar
	 * @param is an array $result_list
	 * @param is an integer $type
	 * @param is an integer $cat_type_id
	 * @param is an integer $tbl_type
	 *
	 * @post associative array.
	 * @post return array
	 *
	 */
	function arrGenerateSolr($result_list,$type,$cat_type_id="",$tbl_type="",$start="",$end=""){
		$result_list_arr =Array();
		if(empty($start)){
			$start = 0;
		}
		if(empty($end)){
			$end = sizeof($result_list);
		}
		$j=0;
		for($i=$start;$i<$end;$i++){
			$result_list_arr[$j]=Array();
			$result_list_arr[$j]["video_id"]=$result_list[$i]["video_id"];
			$result_list_arr[$j]["product_info_id"]=$result_list[$i]["product_info_id"];
			$video_id = $result_list[$i]["video_id"];
			$result_list_arr[$j]["title"]=$result_list[$i]["title"];
			$result_list_arr[$j]["media_title"]=$result_list[$i]["media_title"];
			$result_list_arr[$j]["tags"]=$result_list[$i]["tags"];
                        $result_list_arr[$j]["description"]=$result_list[$i]["abstract"];
                        $result_list_arr[$j]["content"]=$result_list[$i]["content"];
			$result_list_arr[$j]["media_id"]=$result_list[$i]["media_id"];
			$result_list_arr[$j]["media_path"]=$result_list[$i]["media_path"];
			$result_list_arr[$j]["video_img_id"]=$result_list[$i]["video_img_id"];
			$result_list_arr[$j]["video_img_path"]=$result_list[$i]["video_img_path"];
			$result_list_arr[$j]["content_type"]=$result_list[$i]["content_type"];
			$result_list_arr[$j]["is_media_process"]=$result_list[$i]["is_media_process"];
			$result_list_arr[$j]["media_source_flag"]=$result_list[$i]["media_source_flag"];
			$result_list_arr[$j]["status"]=$result_list[$i]["status"];
			$result_list_arr[$j]["create_date"]=strtotime($result_list[$i]["create_date"]);
			$result_list_arr[$j]["uipdate_date"]=$result_list[$i]["update_date"];
			$result_list_arr[$j]["type_id"]=$result_list[$i]["type_id"];
			$result_list_arr[$j]["article_type"]=$result_list[$i]["article_type"];
			$result_list_arr[$j]["type"]= $type;
			$result_list_arr[$j]["tbl_type"] = $tbl_type ? $tbl_type : '';
			$result_list_arr[$j]["cat_type_id"]= $cat_type_id ? $cat_type_id : $result_list[$i]["type_id"];
			$j++;
		}
		return $result_list_arr;
	}

	function arrGetProductVideosDetailsCnt($product_video_ids="",$group_ids="",$product_ids="",$product_info_id="",$category_ids="",$brand_id="",$status="",$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->wallpaperKey.'_arrGetProductVideosDetailsCnt';
		if(is_array($product_video_ids)){
			$product_video_ids = implode(",",$product_video_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
		}
    if(is_array($product_ids)){
      foreach($product_ids as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_ids = implode(",",$variant_ids);
    }else{
      if(strpos($product_ids,',')==false){
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
      }else{
        $arr_variant_ids = explode(",",$product_ids);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_ids = implode(",",$variant_ids);
      }
    }
    if(is_array($product_info_id)){
      foreach($product_info_id as $model_id){
        $i_model_ids = intval($model_id);
        if($i_model_ids!=0){
          $model_ids[] = $i_model_ids;
        }
      }
      $product_info_id = implode(",",$model_ids);
    }else{
      if(strpos($product_info_id,',')==false){
        if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        }
      }else{
        $arr_model_ids = explode(",",$product_info_id);
        foreach($arr_model_ids as $model_id){
          $i_model_ids = intval($model_id);
          if($i_model_ids!=0){
            $model_ids[] = $i_model_ids;
          }
        }
        $product_info_id = implode(",",$model_ids);
      }
    }
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($status != ''){
			$keyArr[] 			= $status;
			$whereClauseArr[] 	= "status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_video_ids)){
			$keyArr[] 			= $product_video_ids;
			$whereClauseArr[] 	= "product_video_id in ($product_video_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($group_ids)){
			$keyArr[] 			= $group_ids;
			$whereClauseArr[] 	= "group_id in ($group_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] 			= $product_ids;
			$whereClauseArr[] 	= "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "product_info_id=$product_info_id";
		}else{$keyArr[] = -1;}
		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in ($category_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "status=$status";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(PRODUCT_VIDEOS.product_info_id) as cnt from PRODUCT_VIDEOS $whereClauseStr group by product_info_id";
		//echo "$sql";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetProductVideosDetails($product_video_ids="",$group_ids="",$product_ids="",$product_info_id="",$category_ids="",$brand_id="",$status="",$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->wallpaperKey.'_arrGetProductVideosDetails';
		if(is_array($product_video_ids)){
			$product_video_ids = implode(",",$product_video_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
		}
    if(is_array($product_ids)){
      foreach($product_ids as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_ids = implode(",",$variant_ids);
    }else{
      if(strpos($product_ids,',')==false){
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
      }else{
        $arr_variant_ids = explode(",",$product_ids);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_ids = implode(",",$variant_ids);
      }
    }
    if(is_array($product_info_id)){
      foreach($product_info_id as $model_id){
        $i_model_ids = intval($model_id);
        if($i_model_ids!=0){
          $model_ids[] = $i_model_ids;
        }
      }
      $product_info_id = implode(",",$model_ids);
    }else{
      if(strpos($product_info_id,',')==false){
        if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        }
      }else{
        $arr_model_ids = explode(",",$product_info_id);
        foreach($arr_model_ids as $model_id){
          $i_model_ids = intval($model_id);
          if($i_model_ids!=0){
            $model_ids[] = $i_model_ids;
          }
        }
        $product_info_id = implode(",",$model_ids);
      }
    }
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($status != ''){
			$keyArr[] 			= $status;
			$whereClauseArr[] 	= "status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_video_ids)){
			$keyArr[] 			= $product_video_ids;
			$whereClauseArr[] 	= "product_video_id in ($product_video_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($group_ids)){
			$keyArr[] 			= $group_ids;
			$whereClauseArr[] 	= "group_id in ($group_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] 			= $product_ids;
			$whereClauseArr[] 	= "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "product_info_id=$product_info_id";
		}else{$keyArr[] = -1;}
		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in ($category_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "status=$status";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
			$orderby = "order by ".$orderby." DESC";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from PRODUCT_VIDEOS $whereClauseStr  group by product_info_id $orderby $limitStr ";
		//echo "$sql";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}	


}
