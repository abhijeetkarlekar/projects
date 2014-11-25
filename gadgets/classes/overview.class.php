<?php
/**
* @brief class is used to perform actions on overview management
* @author
* @version 1.0
* @created 11-Nov-2010 5:09:31 PM
* @last updated on 08-Mar-2011 13:14:00 PM
*/
class OverviewManagement extends DbOperation{

	var $overviewKey;
	var $compareKey;
	var $cache;
	/**Intialize the consturctor.*/
	function OverviewManagement(){
		$this->cache = new Cache;
		$this->overviewKey = MEMCACHE_MASTER_KEY."featureoverview::";
		$this->compareKey = MEMCACHE_MASTER_KEY."compare::";
		$this->featurekey = MEMCACHE_MASTER_KEY."feature::";
	}
	/**
	* @note function is used to insert the feature overview into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $overview_id
	* retun integer.
	*/
	function intInsertFeaturedOverview($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$overview_sub_group_id = $insert_param['overview_sub_group_id'];
		$sql = "select max(position) as position from FEATURE_OVERVIEW_MASTER where overview_sub_group_id = $overview_sub_group_id";
		$result = $this->select($sql);
		$position = $result[0]['position'] ? $result[0]['position'] : '0';
		$insert_param['position'] = ($position+1);
		$sql = $this->getInsertSql("FEATURE_OVERVIEW_MASTER",array_keys($insert_param),array_values($insert_param));
		$overview_id = $this->insert($sql);
		//if(is_int($overview_id)){
			$this->cache->searchDeleteKeys($this->overviewKey."_version_overview");
			$this->cache->searchDeleteKeys($this->featurekey);
			$this->cache->searchDeleteKeys($this->compareKey);
			//$this->arrGetFeatureOverview($overview_id);
		//}
		return $overview_id;
	}
	/**
	* @note function is used to update the feature overview into the database.
	* @param an associative array $update_param.
	* @param an integer $overview_id.
	* @pre $insert_param must be valid associative array.
	* @re $overview_id must be non-empty/zero valid integer.
	* @post an integer id
	* retun integer.
	*/
	function boolUpdateFeaturedOverview($overview_id,$update_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("FEATURE_OVERVIEW_MASTER",array_keys($insert_param),array_values($insert_param));
		$isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->overviewKey."_version_overview");
			$this->cache->searchDeleteKeys($this->featurekey);
			$this->cache->searchDeleteKeys($this->compareKey);
			$this->arrGetFeatureOverview($overview_id);
		}
		return $isUpdate;
	}
	/**
	* @note function is used to delete feature overview.
	* @param integer $overview_id.
	* @pre $overview_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteFeaturedOverview($overview_id){
		$sql = "delete from FEATURE_OVERVIEW_MASTER where overview_id = $overview_id";
		$isDelete = $this->sql_delete_data($sql);
		if(!empty($isDelete)){
			$this->cache->searchDeleteKeys($this->overviewKey."_version_overview");
			$this->cache->searchDeleteKeys($this->featurekey);
			$this->cache->searchDeleteKeys($this->compareKey);

			$this->arrGetFeatureOverview($overview_id);
		}
		return $result;
	}
	/**
	* @note function is used to get feture overview details
	* @param an integer $overview_id.
	* @param an integer $overview_sub_group_id.
	* @param an integer $category_id.
	* @param an string $title.
	* @param an integer $feature_id.
	* @param an string $abbreviation.
	* @param an integer $position.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post feture overview details in associative array.
	* retun an array.
	*/
	function arrGetFeatureOverview($overview_id="",$overview_sub_group_id="",$category_id="",$title="",$feature_id="",$abbreviation="",$position="",$status="",$startlimit="",$cnt=""){
		$keyArr[] = $this->overviewKey."_version_overview";
		if(!empty($overview_id)){
			$keyArr[] = $overview_id;
			$whereClauseArr[] = "overview_id = $overview_id";
		}else{$keyArr[] =-1;}
		if(!empty($overview_sub_group_id)){
			$keyArr[] = $overview_sub_group_id;
			$whereClauseArr[] = "overview_sub_group_id = $overview_sub_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if(!empty($title)){
			$keyArr[] = $title;
			$whereClauseArr[] = "lower(title) = '$title'";
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "feature_id = $feature_id";
		}else{$keyArr[] =-1;}
		if(!empty($abbreviation)){
			$keyArr[] = $abbreviation;
			$whereClauseArr[] = "lower(abbreviation) = '$abbreviation'";
		}else{$keyArr[] =-1;}
		if(!empty($position)){
			$keyArr[] = $position;
			$whereClauseArr[] = "position = $position";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] =  $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from FEATURE_OVERVIEW_MASTER $whereClauseStr order by position asc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to insert the icar finder feature overview into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $overview_id
	* retun integer.
	*/
	function intInsertCarFinderFeaturedOverview($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("CAR_FINDER_FEATURE_OVERVIEW_MASTER",array_keys($insert_param),array_values($insert_param));
		$overview_id = $this->insert($sql);
		if(is_int($overview_id)){
			$this->cache->searchDeleteKeys($this->overviewKey."_carfinder_overview");
			$this->arrGetCarFinderFeatureOverview($overview_id);
		}
		return $overview_id;
	}
	/**
	* @note function is used to update the car finder feature overview into the database.
	* @param an associative array $update_param.
	* @param an integer $overview_id.
	* @pre $insert_param must be valid associative array.
	* @re $overview_id must be non-empty/zero valid integer.
	* @post an integer id
	* retun integer.
	*/
	function boolUpdateCarFinderFeaturedOverview($overview_id,$update_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("CAR_FINDER_FEATURE_OVERVIEW_MASTER",array_keys($insert_param),array_values($insert_param));
		$isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->overviewKey."_carfinder_overview");
			$this->arrGetCarFinderFeatureOverview($overview_id);
		}
		return $isUpdate;
	}
	/**
	* @note function is used to delete car finder feature overview.
	* @param integer $overview_id.
	* @pre $overview_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteCarFinderFeaturedOverview($overview_id){
		$sql = "delete from CAR_FINDER_FEATURE_OVERVIEW_MASTER where overview_id = $overview_id";
		$isDelete = $this->sql_delete_data($sql);
		if(!empty($isDelete)){
			$this->cache->searchDeleteKeys($this->overviewKey."_carfinder_overview");
			$this->arrGetCarFinderFeatureOverview($overview_id);
		}
		return $result;
	}
	/**
	* @note function is used to get car finder feture overview count
	* @param an integer $overview_id.
	* @param an integer $overview_sub_group_id.
	* @param an integer $category_id.
	* @param an string $title.
	* @param an integer $feature_id.
	* @param an string $abbreviation.
	* @param an integer $position.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post feture overview details in associative array.
	* retun an array.
	*/
	function arrGetCarFinderFeatureOverviewCount($overview_sub_group_id="",$category_id=""){
		$keyArr[] = $this->overviewKey."_carfinder_overview"."_count";
		if(!empty($overview_sub_group_id)){
			$keyArr[] = $overview_sub_group_id;
			$whereClauseArr[] = "overview_sub_group_id = $overview_sub_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(overview_id) as cnt from CAR_FINDER_FEATURE_OVERVIEW_MASTER $whereClauseStr order by overview_id asc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get car finder feture overview details
	* @param an integer $overview_id.
	* @param an integer $overview_sub_group_id.
	* @param an integer $category_id.
	* @param an string $title.
	* @param an integer $feature_id.
	* @param an string $abbreviation.
	* @param an integer $position.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post feture overview details in associative array.
	* retun an array.
	*/
	function arrGetCarFinderFeatureOverview($overview_id="",$overview_sub_group_id="",$category_id="",$title="",$feature_id="",$abbreviation="",$position="",$status="",$startlimit="",$cnt=""){
		$keyArr[] = $this->overviewKey."_carfinder_overview";
		if(!empty($overview_id)){
			$keyArr[] = $overview_id;
			$whereClauseArr[] = "overview_id = $overview_id";
		}else{$keyArr[] =-1;}
		if(!empty($overview_sub_group_id)){
			$keyArr[] = $overview_sub_group_id;
			$whereClauseArr[] = "overview_sub_group_id = $overview_sub_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if(!empty($title)){
			$keyArr[] = $title;
			$whereClauseArr[] = "lower(title) = '$title'";
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "feature_id = $feature_id";
		}else{$keyArr[] =-1;}
		if(!empty($abbreviation)){
			$keyArr[] = $abbreviation;
			$whereClauseArr[] = "lower(abbreviation) = '$abbreviation'";
		}else{$keyArr[] =-1;}
		if(!empty($position)){
			$keyArr[] = $position;
			$whereClauseArr[] = "position = $position";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] =  $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from CAR_FINDER_FEATURE_OVERVIEW_MASTER $whereClauseStr order by position asc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to insert the compare feature overview into the database.
	* @param an integer $feature_id.
	* @pre $feature_id must be a non-empty/zero valid integer.
	* @post an integer id.
	* retun integer.
	*/
	function intInsertCompareFeatureOverview($feature_id){
		$sql = "insert into `COMPARE_OVERVIEW_MASTER`(`main_feature_group`,`feature_group`,`category_id`,`feature_id`)select `main_feature_group`,`feature_group`,`category_id`,`feature_id` from FEATURE_MASTER where feature_id = $feature_id";
		$overview_id = $this->insertSelect($sql);
		if(!empty($overview_id)){
			$this->cache->searchDeleteKeys($this->overviewKey."_compare_overview");
			$this->cache->searchDeleteKeys($this->compareKey);
			$this->arrGetCarFinderFeatureOverview($overview_id);
		}
		return $overview_id;
	}
	/**
	* @note function is used to update the compare feature overview into the database.
	* @param an associative array $update_param.
	* @param an integer $overview_id.
	* @pre $insert_param must be valid associative array.
	* @re $overview_id must be non-empty/zero valid integer.
	* @post an integer id
	* retun integer.
	*/
	function boolUpdateCompareFeatureOverview($overview_id,$update_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateql("COMPARE_OVERVIEW_MASTER",array_keys($insert_param),array_values($insert_param));
		$isUpdate = $this->insert($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->overviewKey."_compare_overview");
			//$this->cache->searchDeleteKeys($this->compareKey."_overview");
			$this->cache->searchDeleteKeys($this->compareKey);
			$this->arrGetCarFinderFeatureOverview($overview_id);
		}
		return $isUpdate;
	}
	/**
	* @note function is used to delete compare feature overview.
	* @param integer $overview_id.
	* @pre $overview_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteCompareFeatureOverview($overview_id){
		$sql = "delete from COMPARE_OVERVIEW_MASTER where overview_id = $overview_id";
		$result = $this->sql_delete_data($sql);
		if(!empty($result)){
			$this->cache->searchDeleteKeys($this->overviewKey."_compare_overview");
			$this->cache->searchDeleteKeys($this->compareKey);

			$this->arrGetCarFinderFeatureOverview($overview_id);
		}
		return $result;
	}
	/**
	* @note function is used to get compare feture overview details
	* @param an integer $overview_id.
	* @param an integer $main_feature_group.
	* @param an integer $feature_group.
	* @param an integer $category_id.
	* @param an integer $feature_id.
	* @param an integer $position.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post compare feture overview details in associative array.
	* retun an array.
	*/
	function arrGetCompareFeatureOverview($overview_id="",$main_feature_group="",$feature_group="",$category_id="",$feature_id="",$position="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->overviewKey."_compare_overview";
		if(!empty($overview_id)){
			$whereClauseArr[] = "overview_id = $overview_id";
			$keyArr[] = $overview_id;
		}else{$keyArr[] =-1;}
		if(!empty($main_feature_group)){
			$whereClauseArr[] = "main_feature_group = $main_feature_group";
			$keyArr[] = $main_feature_group;
		}else{$keyArr[] =-1;}
		if(!empty($main_feature_group)){
			$whereClauseArr[] = "overview_id = $overview_id";
			$keyArr[] = $overview_id;
		}else{$keyArr[] =-1;}
		if(!empty($feature_group)){
			$whereClauseArr[] = "feature_group = $feature_group";
			$keyArr[] = $feature_group;
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$whereClauseArr[] = "feature_id = $feature_id";
			$keyArr[] = $feature_id;
		}else{$keyArr[] =-1;}
		if(!empty($position)){
			$whereClauseArr[] = "position = $position";
			$keyArr[] = $position;
		}else{$keyArr[] =-1;}
		if($status != ''){
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
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from COMPARE_OVERVIEW_MASTER $whereClauseStr order by position $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to set overview up postion.
	* @param integer $overview_id
	* @param integer $pos
	* @param integer $overview_sub_group_id
	* @pre $overview_id,$pos,$overview_sub_group_id must be valid,non-empty integer.
	* @post boolean true/false.
	*/
	function updateVariantModelOverviewPosUp($overview_id,$pos,$overview_sub_group_id){
		$key = $this->overviewKey."_version_overview"."_posup_$overview_id";
		$prevpos = $pos-1;
		$sql = "select overview_id from FEATURE_OVERVIEW_MASTER where position = $prevpos and overview_sub_group_id = $overview_sub_group_id";

		if($result = $this->cache->get($key)){return $result;}
		$result = $this->select($sql);
		$this->cache->set($key,$result);

		$prev_overview_id = $result[0]['overview_id'];
		$sql = "update FEATURE_OVERVIEW_MASTER set position = $pos where overview_id = $prev_overview_id";
		$result = $this->update($sql);
		$sql = "update FEATURE_OVERVIEW_MASTER set position = $prevpos where overview_id = $overview_id";
		$result = $this->update($sql);

		$this->cache->searchDeleteKeys($this->overviewKey);
		$this->cache->searchDeleteKeys($this->featurekey);
		$this->arrGetFeatureOverview($overview_id);
		return true;
	}
	/**
	* @note function is used to set overview down postion.
	* @param integer $overview_id
	* @param integer $pos
	* @param integer $overview_sub_group_id
	* @pre $overview_id,$pos,$overview_sub_group_id must be valid,non-empty integer.
	* @post boolean true/false.
	*/
	function updateVariantModelOverviewPosDown($overview_id,$pos,$overview_sub_group_id){
		$key = $this->overviewKey."_version_overview"."_posdown_$overview_id";
		$nextpos = $pos+1;
		$sql = "select overview_id from FEATURE_OVERVIEW_MASTER where position = $nextpos and overview_sub_group_id = $overview_sub_group_id";

		if($result = $this->cache->get($key)){return $result;}
		$result = $this->select($sql);
		$this->cache->set($key,$result);

		$next_overview_id = $result[0]['overview_id'];
		$sql = "update FEATURE_OVERVIEW_MASTER set position = $pos where overview_id = $next_overview_id";
		$result = $this->update($sql);
		$sql = "update FEATURE_OVERVIEW_MASTER set position = $nextpos where overview_id = $overview_id";
		$result = $this->update($sql);

		$this->cache->searchDeleteKeys($this->overviewKey);
		$this->cache->searchDeleteKeys($this->featurekey);
		$this->arrGetFeatureOverview($overview_id);
		return true;
	}
}
