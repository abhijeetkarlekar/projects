<?php
/**
 * @brief class feature management.This class is use to get,update,insert feature
 * management data.
 * @author Rajesh Ujade.
 * @version 1.0
 * @created 11-Nov-2010 4:06:02 PM
 */
class FeatureManagement extends DbOperation
{

	 /**Initialize the consturctor.*/
	var $featurekey;
	var $modelcolorskey;
	var $variantcolorskey;
	var $usedcarfeaturekey;
	var $cache;
	function FeatureManagement()
	{
		$this->cache = new Cache;
		$this->featurekey = MEMCACHE_MASTER_KEY.'feature::';
		$this->modelcolorskey = MEMCACHE_MASTER_KEY.'mcolor::';
		$this->variantcolorskey = MEMCACHE_MASTER_KEY.'vcolor::';
		$this->usedcarfeaturekey = MEMCACHE_MASTER_KEY.'usedfeature::';

	}

	/**
	 * @note function is used to insert the feature into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertFeature($insert_param)
	{
		$result = $this->intMaxFeatureDisplayOrder();
		$insert_param['feature_display_order'] = $result[0]['feature_display_order'];
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("FEATURE_MASTER",array_keys($insert_param),array_values($insert_param));
		$feature_id = $this->insert($sql);
		if($feature_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($feature_id)){
			$this->cache->searchDeleteKeys($this->featurekey);
			$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
			$this->arrGetFeatureDetails($feature_id);
		}
		return $feature_id;
	}

	/**
	 * @note function is used to update the feature into the database.
	 * @param an associative array $update_param.
	 * @param an integer $feature_id.
	 * @pre $update_param must be valid associative array and $feature_id must be non empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateFeature($feature_id,$update_param)
	 {
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("FEATURE_MASTER",array_keys($update_param),array_values($update_param),"feature_id",$feature_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->featurekey);
		$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
		$this->arrGetFeatureDetails($feature_id);
		return $isUpdate;
	 }
	function arrGetFeatureDetailsCnt($feature_ids="",$category_id="",$main_group_id="",$sub_group_id="",$status="1",$feature_name="")
	 {
		$keyArr[] = $this->featurekey.'_arrGetFeatureDetailsCnt';
	 	if(is_array($feature_ids)){
	 		$feature_ids = implode(",",$feature_ids);
	 	}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($main_group_id)){
			$main_group_id = implode(",",$main_group_id);
		}
		if(is_array($sub_group_id)){
			$sub_group_id = implode(",",$sub_group_id);
		}
		if(!empty($feature_name)){
			$keyArr[] = $feature_name;
	 		$whereClauseArr[] = "lower(feature_name)= '$feature_name'";
	 	}else{$keyArr[] = -1;}
	 	if($status != ''){
			$keyArr[] = $status;
	 		$whereClauseArr[] = "status=$status";
	 	}else{$keyArr[] = -1;}
	 	if(!empty($category_id)){
			$keyArr[] = $category_id;
	 		$whereClauseArr[] = "category_id in ($category_id)";
	 	}else{$keyArr[] = -1;}
	 	if(!empty($feature_ids)){
			$keyArr[] = $feature_ids;
	 		$whereClauseArr[] = "feature_id in ($feature_ids)";
	 	}else{$keyArr[] = -1;}
		if(!empty($main_group_id)){
			$keyArr[] = $main_group_id;
			$whereClauseArr[] = "main_feature_group in ($main_group_id)";
		}else{$keyArr[] = -1;}
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "feature_group in ($sub_group_id)";
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
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
	 	$sql = "select count(feature_id) as cnt from FEATURE_MASTER $whereClauseStr order by feature_display_order asc $limitStr" ;
	 	$result = $this->select($sql);
		$this->cache->set($key,$result);
	 	return $result;
	 }

	 /**
	 * @note function is used to get feature details.
	 * @param an integer/comma seperated feature ids/feature ids array $feature_ids.
	 * @param an integer category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post feature details in associative array.
	 * retun an array.
	 */
	 function arrGetFeatureDetails($feature_ids="",$category_id="",$main_group_id="",$sub_group_id="",$status="1",$startlimit="",$count="",$feature_name="")
	 {
	 	unset($result);
		$keyArr[] = $this->featurekey.'_arrGetFeatureDetails';
	 	if(is_array($feature_ids)){
	 		$feature_ids = implode(",",$feature_ids);
	 	}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($main_group_id)){
			$main_group_id = implode(",",$main_group_id);
		}
		if(is_array($sub_group_id)){
			$sub_group_id = implode(",",$sub_group_id);
		}
		if(!empty($feature_name)){
			$keyArr[] = $feature_name;
	 		$whereClauseArr[] = "lower(feature_name)= '$feature_name'";
	 	}else{$keyArr[] = -1;}
	 	if($status != ''){
			$keyArr[] = $status;
	 		$whereClauseArr[] = "status=$status";
	 	}else{$keyArr[] = -1;}
	 	if(!empty($category_id)){
			$keyArr[] = $category_id;
	 		$whereClauseArr[] = "category_id in ($category_id)";
	 	}else{$keyArr[] = -1;}
	 	if(!empty($feature_ids)){
			$keyArr[] = $feature_ids;
	 		$whereClauseArr[] = "feature_id in ($feature_ids)";
	 	}else{$keyArr[] = -1;}
		if(!empty($main_group_id)){
			$keyArr[] = $main_group_id;
			$whereClauseArr[] = "main_feature_group in ($main_group_id)";
		}else{$keyArr[] = -1;}
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "feature_group in ($sub_group_id)";
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
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){	return $result;	}
	 	$sql = "select * from FEATURE_MASTER $whereClauseStr order by feature_display_order asc $limitStr" ;
		$result = array();
	 	$result = $this->select($sql);
	 	$this->cache->set($key,$result);
	 	return $result;
	 }

	/**
	 * @note function is used to get product feature details.
	 * @param an integer/comma seperated product ids/product ids array $product_ids.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre $product_ids must be non-empty valid an integer/comma seperated product ids/product ids array.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetProductFeatureDetails($product_ids,$status=1,$startlimit="",$count="",$feature_id="")
	 {
		$keyArr[] = $this->featurekey."_arrGetProductFeatureDetails";
	 	if(is_array($product_ids)){
	 		$product_ids = implode(",",$product_ids);
	 	}
	 	$whereClauseArr[] = "FEATURE_MASTER.feature_id=PRODUCT_FEATURE.feature_id";
	 	if($status != ''){
			$keyArr[] = $status;
	 		$whereClauseArr[] = "FEATURE_MASTER.status=$status";
	 	}else{$keyArr[] = -1;}
	 	if(!empty($product_ids)){
			$keyArr[] = $product_ids;
	 		$whereClauseArr[] = "PRODUCT_FEATURE.product_id in($product_ids)";
	 	}else{$keyArr[] = -1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.feature_id in($feature_id)";
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
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
	 	$sql = "select PRODUCT_FEATURE.*,FEATURE_MASTER.* from PRODUCT_FEATURE,FEATURE_MASTER $whereClauseStr $limitStr group by FEATURE_MASTER.feature_group order by FEATURE_MASTER.feature_name asc";
	 	$result = $this->select($sql);
		$this->cache->set($key,$result);
	 	return $result;
	 }

	  function arrGetProductFeatureDataDetails($product_ids,$status=1,$startlimit="",$count="",$feature_id="")
	 {
		$keyArr[] = $this->featurekey."_arrGetProductFeatureDetails";
	 	if(is_array($product_ids)){
	 		$product_ids = implode(",",$product_ids);
	 	}
	 	$whereClauseArr[] = "FEATURE_MASTER.feature_id=PRODUCT_FEATURE.feature_id";
	 	if($status != ''){
			$keyArr[] = $status;
	 		$whereClauseArr[] = "FEATURE_MASTER.status=$status";
	 	}else{$keyArr[] = -1;}
	 	if(!empty($product_ids)){
			$keyArr[] = $product_ids;
	 		$whereClauseArr[] = "PRODUCT_FEATURE.product_id in($product_ids)";
	 	}else{$keyArr[] = -1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.feature_id in($feature_id)";
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
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
	 	 $sql = "select PRODUCT_FEATURE.*,FEATURE_MASTER.* from PRODUCT_FEATURE,FEATURE_MASTER $whereClauseStr $limitStr  order by FEATURE_MASTER.feature_id asc";
	 	 //echo  $sql."<br>";
	 	$result = $this->select($sql);
		$this->cache->set($key,$result);
	 	return $result;
	 }

	 /**
	 * @note function is used to delete the feature.
	 * @param integer $feature_id.
	 * @pre $feature_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteFeature($feature_id){
	 	$sql = "delete from FEATURE_MASTER where feature_id = $feature_id";
	 	$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->featurekey);
		$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
	 	return $isDelete;
	 }

	 /**
	 * @note function is used to insert feature unit.
	 * @param an associate array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post integer $unit_id.
	 * return integer.
	 */
	 function intInsertFeatureUnit($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("FEATURE_UNIT",array_keys($insert_param),array_values($insert_param));
		$unit_id = $this->insert($sql);
		if($unit_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($unit_id)){
			$this->cache->searchDeleteKeys($this->featurekey."_unit");
			$this->arrFeatureUnitDetails($unit_id);
		}
		return $unit_id;
	 }
	 /**
	 * @note function is used to update the feature unit into the database.
	 * @param an associative array $update_param.
	 * @param an integer $unit_id.
	 * @pre $update_param must be valid associative array and $unit_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateFeatureUnit($unit_id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("FEATURE_UNIT",array_keys($update_param),array_values($update_param),"unit_id",$unit_id);
		$isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->featurekey."_unit");
			$this->arrFeatureUnitDetails($unit_id);
		}
	 	return $isUpdate;
	 }

	 /**
	 * @note function is used to delete the feature.
	 * @param integer $unit_id.
	 * @pre $unit_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteFeatureUnit($unit_id){
	 	$sql = "delete from FEATURE_UNIT where unit_id = $unit_id";
	 	$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->featurekey."_unit");
	 	return $isDelete;
	 }

	 /**
	 * @note function is used to feature unit details.
	 * @param integer $feature_id.
	 * @param integer $unit_id.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post an associative array of feature unit.
	 * return an array.
	 */
	 function arrFeatureUnitDetails($unit_id="",$category_id="",$status="1",$startlimit="",$count="") {
		$keyArr[] = $this->featurekey."_unit";
	 	if(!empty($unit_id)){
			$keyArr[] = $unit_id;
	 		$whereClauseArr[] = "unit_id in($unit_id)";
	 	}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
	 		$whereClauseArr[] = "category_id in($category_id)";
	 	}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
	 	if(sizeof($whereClauseArr) > 0){
	 		$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
	 	}
	 	if(!empty($startlimit)){
			$keyArr[] = $startlimit;
	 		$limitArr[] = $startlimit;
	 	}else{$keyArr[] =-1;}
	 	if(!empty($count)){
			$keyArr[] = $count;
	 		$limitArr[] = $count;
	 	}else{$keyArr[] =-1;}
	 	if(sizeof($limitArr) > 0){
	 		$limitStr = " limit ".implode(" , ",$limitArr);
	 	}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
	 	$sql = "select * from FEATURE_UNIT $whereClauseStr order by unit_name asc $limitStr";
	 	$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	/**
	* @note function is used to get unique feature group.
	* @pre not required.
	* @post an array of feature group.
	* return array.
	*/
	function arrGetFeatureGroup(){
		$key = $this->featurekey."_group";
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select distinct(feature_group) as feature_group from FEATURE_MASTER where feature_group != '' and feature_group != 'NULL' order by feature_group asc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get max pivot display order id.
	* @param integer $category_id.
	* @param integer $feature_id.
	* @param integer $pivot_id.
	* @pre $category_id and $feature_id must be valid,non-empty,non-zero integer.
	* @post integer max display order.
	* return integer.
	*/
	function intMaxFeatureDisplayOrder($category_id="",$feature_id=""){
		$keyArr[] = $this->featurekey."_display_order";
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "feature_id = $feature_id";
		}else{$keyArr[] =-1;}

		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select max(feature_display_order) as feature_display_order from FEATURE_MASTER $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to insert main feature group into the db.
	* @param associative array $insert_param.
	* @pre $insert_param must be valid,non-empty associative array.
	* @post integer feature group id.
	* return integer.
	*/
	function insertFeatureMainGroup($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
        $sql = $this->getInsertSql("MAIN_FEATURE_GROUP",array_keys($insert_param),array_values($insert_param));
        $feature_group_id = $this->insert($sql);
        if($feature_group_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($feature_group_id)){
			$this->cache->searchDeleteKeys($this->featurekey."_main_group");
			$this->arrGetFeatureMainGroupDetails($feature_group_id);
		}
        return $feature_group_id;
	}
	 /**
	 * @note function is used to update the feature main group into the database.
	 * @param an associative array $update_param.
	 * @param an integer $feature_group_id.
	 * @pre $update_param must be valid associative array and $feature_group_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateFeatureMainGroup($feature_group_id,$update_param)
	 {
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("MAIN_FEATURE_GROUP",array_keys($update_param),array_values($update_param),"group_id",$feature_group_id);
			$isUpdate = $this->update($sql);
			if(!empty($isUpdate)){
				$this->cache->searchDeleteKeys($this->featurekey."_main_group");
				$this->arrGetFeatureMainGroupDetails($feature_group_id);
			}
			return $isUpdate;
	 }

	 /**
	 * @note function is used to delete the main feature group.
	 * @param integer $feature_group_id.
	 * @pre $feature_group_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteFeatureMainGroup($feature_group_id)
	 {
		$sql = "delete from MAIN_FEATURE_GROUP where group_id = $feature_group_id";
		$isDelete = $this->sql_delete_data($sql);
		if(!empty($isDelete)){
			$this->cache->searchDeleteKeys($this->featurekey."_main_group");
		}
		return $isDelete;
	 }
	/**
	* @note function is used to get mail feature group.
	* @param integer $feature_group_id.
	* @param integer $category_id.
	* @param boolean $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post associative array of feature group details
	* return array.
	*/
	function arrGetFeatureMainGroupDetails($feature_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->featurekey."_main_group_arrGetFeatureMainGroupDetails";
		if(is_array($feature_group_id)){
			$feature_group_id = implode(",",$feature_group_id);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(!empty($feature_group_id)){
			$keyArr[] = $feature_group_id;
			$whereClauseArr[] = "group_id in ($feature_group_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
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
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from MAIN_FEATURE_GROUP $whereClauseStr order by position ASC $limitStr ";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to insert the feature sub group details into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_group_id.
	 * retun integer.
	 */
	function intInsertFeatureSubGroupDetails($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
        $sql = $this->getInsertSql("FEATURE_SUB_GROUP",array_keys($insert_param),array_values($insert_param));
        $feature_group_id = $this->insert($sql);
        if($feature_group_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($feature_group_id)){
			$this->cache->searchDeleteKeys($this->featurekey."_sub_group");
			$this->arrFeatureSubGroupDetails($feature_group_id);
		}
        return $feature_group_id;
	}
	/**
	 * @note function is used to update the feature sub group details into the database.
	 * @param an associative array $update_param.
	 * @param an integer $sub_group_id.
	 * @pre $update_param must be valid associative array and $sub_group_id must be non empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	function boolUpdateFeatureSubGroupDetails($sub_group_id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
        $sql = $this->getUpdateSql("FEATURE_SUB_GROUP",array_keys($update_param),array_values($update_param),"sub_group_id",$sub_group_id);
        $isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->featurekey."_sub_group");
		if(!empty($isUpdate)){

			$this->arrFeatureSubGroupDetails($sub_group_id);
		}
        return $isUpdate;
	}
	/**
	 * @note function is used to delete the feature sub group details.
	 * @param integer $sub_group_id.
	 * @pre $sub_group_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	function boolDeleteFeatureSubGroupDetails($sub_group_id){
		$sql = "delete from FEATURE_SUB_GROUP where sub_group_id = $sub_group_id";
        $isDelete = $this->sql_delete_data($sql);
		if(!empty($isDelete)){
			$this->cache->searchDeleteKeys($this->featurekey."_sub_group");
		}
        return $isDelete;
	}
	/**
	 * @note function is used to get feature sub group details
	 *
	 * @param an integer $sub_group_id.
	 * @param an integer $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 *
	 * @pre not required.
	 *
	 * @post feature sub group details in associative array.
	 * retun an array.
	 */
	function arrFeatureSubGroupDetails($sub_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->featurekey."_sub_group_arrFeatureSubGroupDetails";

		if(is_array($sub_group_id)){
			$sub_group_id = implode(",",$sub_group_id);
		}
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "sub_group_id in ($sub_group_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from FEATURE_SUB_GROUP $whereClauseStr order by sub_group_position asc $limitStr ";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to get feature sub group details
	 *
	 * @param an integer $sub_group_id.
	 * @param an integer $main_group_id.
	 * @param an integer $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 *
	 * @pre not required.
	 *
	 * @post feature sub group details in associative array.
	 * retun an array.
	 */
	function arrFetchFeatureSubGroupDetails($sub_group_id="",$main_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->featurekey."_arrFetchFeatureSubGroupDetails";
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "sub_group_id = $sub_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($main_group_id)){
			$keyArr[] = $main_group_id;
			$whereClauseArr[] = "main_group_id = $main_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from FEATURE_SUB_GROUP $whereClauseStr order by sub_group_position asc $limitStr ";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to get summary.
	 *
	 * @param an integer $category_id.
	 * @param an integer $product_id.
	 *
	 *
	 * @post xml string.
	 * retun string.
	 */
	function arrGetSummary($category_id,$product_id,$type="xml"){
		require_once(CLASSPATH.'price.class.php');
		require_once(CLASSPATH.'product.class.php');
		$product = new ProductManagement;
		$price = new price;
		$product_result = $product->arrGetProductDetails($product_id,$category_id,"","1","","","1","","","1");
		$product_name = $product_result[0]['product_name'];
		$variant = $product_result[0]['variant'];
		if(!empty($product_name)){
			$productArr[] = $product_name;
		}
		if(!empty($variant)){
			$productArr[] = $variant;
		}
		$product_name = implode(" ",$productArr);
		$all_variant_result = $price->arrGetVariantDetail();
		$cnt = sizeof($all_variant_result);
		$variantArr = Array();
		for($i=0;$i<$cnt;$i++){
			$variant = $all_variant_result[$i]['variant'];
			$variant_id = $all_variant_result[$i]['variant_id'];
			if(!stripos($variant,'showroom')){
				$variantArr[] = $variant;
			}
		}
		$exshowroom_price  = $product_result[0]['variant_value'];
		if(!empty($exshowroom_price)){
			$exshowroom_price = "Rs.".priceFormat($exshowroom_price);
			$extra_variant = " (".implode(",",$variantArr)." and other taxes/charges extra)";
		}
		$aFeatureData=array();
		$aFeatureData["$product_name Price"]["Avg Ex-Showroom Price"] = $exshowroom_price.$extra_variant;
		$sSql="SELECT * FROM FEATURE_OVERVIEW_MASTER FM, MAIN_FEATURE_GROUP SB WHERE FM.category_id = $category_id AND 	FM.overview_sub_group_id = SB.group_id order by FM.position asc";
		$key = $this->featurekey."_model_page_overview";
		$feature_result = $this->cache->get($key);
		if(empty($feature_result)){
			$feature_result = $this->select($sSql);
			$this->cache->set($key,$feature_result);
		}
		foreach($feature_result as $ikey=>$aValueData){
			$feature_id = $aValueData['feature_id'];
			if($feature_id!='' && !empty($product_id)){
				$sql = "select PRODUCT_FEATURE.*,FEATURE_MASTER.* from PRODUCT_FEATURE,FEATURE_MASTER where PRODUCT_FEATURE.feature_id = $feature_id and FEATURE_MASTER.feature_id = PRODUCT_FEATURE.feature_id and PRODUCT_FEATURE.product_id=$product_id";
				$key = $this->featurekey."_$feature_id"."_$product_id"."_model_page_summary";
				$product_feature = $this->cache->get($key);
				if(empty($product_feature)){
					$product_feature = $this->select($sql);
					$this->cache->set($key,$product_feature);
				}
				$feature_value = trim($product_feature[0]['feature_value']);
				if($feature_value == "-"){$feature_value="";}
				$feature_name = trim($product_feature[0]['feature_name']);
				$abbreviation = $aValueData['abbreviation'];

				/*if(strtolower($feature_value) == 'yes'){
					$feature_value = $feature_name;
				}elseif(strtolower($feature_value) == 'no'){
					$feature_value = '';
				}*/
			}
			unset($featureWithUnitArr);
			if(!empty($feature_value)){
				$featureWithUnitArr[] = $feature_value;
			}
			if(!empty($abbreviation) && !empty($feature_value)){
				$featureWithUnitArr[] = $abbreviation;
			}
			$overview_display_name = $aValueData['overview_display_name'] ? $aValueData['overview_display_name'] : $aValueData['main_group_name'];

			if($aValueData['title']!=""){
				if(sizeof($featureWithUnitArr) > 0){
					$aFeatureData[$product_name." ".$overview_display_name][$aValueData['title']][]= implode("",$featureWithUnitArr);
				}
			}else{
				if(!empty($feature_value) && $feature_value != '-'){
					$titleArr[] = $feature_value;
				}
				if(!empty($abbreviation) && !empty($feature_value) && $feature_value != '-'){
					$titleArr[] = $abbreviation;
				}
				if(sizeof($titleArr) > 0){
					$aFeatureData[$product_name." ".$overview_display_name][0][] = implode("",$titleArr);
					$aFeatureData[$product_name." ".$overview_display_name]['main_group_id'] = $aValueData['overview_sub_group_id'];
					$aFeatureData[$product_name." ".$overview_display_name]['main_group_name'] = $overview_display_name;
				}
				unset($titleArr);
			}
		}
		if($type != 'xml'){
			return $aFeatureData;
		}
		if(is_array($aFeatureData)){
			$xml .= "<GROUP_MASTER>";
			foreach($aFeatureData as $group_name=>$groupArr){
				$main_group_id = $groupArr['main_group_id'];
				$main_group_name = $groupArr['main_group_name'];
				if(is_array($groupArr)){
					$xml .= "<GROUP_MASTER_DATA>";
					$xml .= "<MAIN_GROUP_ID><![CDATA[$main_group_id]]></MAIN_GROUP_ID>";
					$xml .= "<MAIN_GROUP_NAME><![CDATA[$main_group_name]]></MAIN_GROUP_NAME>";
					unset($groupArr['main_group_id']);unset($groupArr['main_group_name']);
					$xml .= "<GROUP_NAME><![CDATA[$group_name]]></GROUP_NAME>";
					foreach($groupArr as $featuretitle=>$featurevalArr){
						if($featuretitle!=''){
						$xml .="<SUB_GROUP_DATA>";
						$featuretitle = empty($featuretitle) ? '' : $featuretitle;
						$xml .= "<FEATURE_TITLE><![CDATA[$featuretitle]]></FEATURE_TITLE>";
						$xml .="<FEATURE_VALUE><![CDATA[".implode(",&#160;",$featurevalArr)."]]></FEATURE_VALUE>";
						$xml .="</SUB_GROUP_DATA>";
						}
					}
					$xml .= "</GROUP_MASTER_DATA>";
				}
			}
			$xml .= "</GROUP_MASTER>";
		}
		return $xml;
	}
	function arrGetPivotFeatureDetails($feature_name,$category_id,$status="1"){
		$keyArr[] = $this->featurekey."_pivot_details";
		$whereClauseArr[] = "FEATURE_MASTER.feature_id = PIVOT_MASTER.feature_id";
		if(!empty($feature_name)){
			$keyArr[] = $feature_name;
			$whereClauseArr[] = "lower(FEATURE_MASTER.feature_name) = '$feature_name'";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "FEATURE_MASTER.category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "FEATURE_MASTER.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from FEATURE_MASTER,PIVOT_MASTER $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetPivotDetails($feature_name,$category_id,$status="1"){
		$keyArr[] = $this->featurekey."_pivotdet_details";
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PIVOT_MASTER.category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PIVOT_MASTER.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from PIVOT_MASTER $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	 * @note function is used to insert the popular feature car details into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $popular_feature_id.
	 * retun integer.
	 */
	function intInsertPopularFeatureCars($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("POPULAR_FEATURE_CARS",array_keys($insert_param),array_values($insert_param));
		$popular_feature_id = $this->insert($sql);
		if($popular_feature_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($popular_feature_id)){
			$this->cache->searchDeleteKeys($this->featurekey."_popular");
			$this->arrGetPopularFeatureCarDetails($popular_feature_id);
		}
		return $popular_feature_id;
	}

	/**
	 * @note function is used to update the popular feature car details into the database.
	 * @param an associative array $update_param.
	 * @param an integer $popular_feature_id.
	 * @pre $update_param must be valid associative array and $popular_feature_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdatePopularFeatureCars($popular_feature_id,$update_param){
		 $update_param['create_date'] = date('Y-m-d H:i:s');
		 $update_param['update_date'] = date('Y-m-d H:i:s');
		 $sql = $this->getUpdateSql("POPULAR_FEATURE_CARS",array_keys($update_param),array_values($update_param),"popular_feature_id",$popular_feature_id);
		 $isUpdate = $this->update($sql);
		 if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->featurekey."_popular");
			$this->arrGetPopularFeatureCarDetails($popular_feature_id);
		}
		 return $isUpdate;
	 }

	 /**
	 * @note function is used to delete the popular feature car.
	 * @param integer $popular_feature_id.
	 * @pre $popular_feature_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeletePopularFeatureCars($popular_feature_id)
	 {
		$sql = "delete from POPULAR_FEATURE_CARS where popular_feature_id = $popular_feature_id";
		$isDelete = $this->sql_delete_data($sql);
		if(!empty($isDelete)){
			$this->cache->searchDeleteKeys($this->featurekey."_popular");
		}
		return $isDelete;
	 }

	 /**
	 * @note function is used to get popular feature car.
	 * @param an integer/comma seperated popular feature ids/ popular feature ids array $popular_feature_ids.
	 * @param an integer/comma seperated pivot groups/ pivot groups array $pivot_groups.
	 * @param an integer/comma seperated pivot ids/ pivot ids array $pivot_ids.
	 * @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	 * @param an integer/comma seperated model ids/ model ids array $model_ids.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer/comma separated category_id $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post popular feature car details in associative array.
	 * retun an array.
	 */
	function arrGetPopularFeatureCarDetails($popular_feature_ids="", $pivot_groups="", $pivot_ids="", $brand_ids="", $model_ids="", $product_ids="", $category_ids="", $status="1",$startlimit="",$count="",$orderby="",$feature_ids="")
    {
		$keyArr[] = $this->featurekey."_popular";
		if(is_array($popular_feature_ids)){
				$popular_feature_ids = implode(",",$popular_feature_ids);
		}
		if(is_array($pivot_groups)){
				$pivot_groups = implode(",",$pivot_groups);
		}
		if(is_array($pivot_ids)){
				$pivot_ids = implode(",",$pivot_ids);
		}
		if(is_array($brand_ids)){
				$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($model_ids)){
				$model_ids = implode(",",$model_ids);
		}
		if(is_array($feature_ids)){
				$feature_ids = implode(",",$feature_ids);
		}
		if(is_array($product_ids)){
				$product_ids = implode(",",$product_ids);
		}
		if(is_array($category_ids)){
				$category_ids = implode(",",$category_ids);
		}
		if(!empty($popular_feature_ids)){
			$keyArr[] = $popular_feature_ids;
			$whereClauseArr[] = "popular_feature_id in($popular_feature_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($pivot_groups)){
			$keyArr[] = $pivot_groups;
			$whereClauseArr[] = "pivot_group in($pivot_groups)";
		}else{$keyArr[] =-1;}
		if(!empty($pivot_ids)){
			$keyArr[] = $pivot_ids;
			$whereClauseArr[] = "pivot_id in($pivot_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($feature_ids)){
			$keyArr[] = $feature_ids;
			$whereClauseArr[] = "feature_id in($feature_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "brand_id in($brand_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($model_ids)){
			$keyArr[] = $model_ids;
			$whereClauseArr[] = "model_id in($model_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in ($category_ids)";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status=$status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by create_date desc";
		}
		$sql = "select * from POPULAR_FEATURE_CARS $whereClauseStr $orderby $limitStr";
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
     }
	/**
	* @note function is used to get variant page summary.
	*
	* @param an integer $category_id.
	* @param an integer $product_id.
	*
	*
	* @post xml string.
	* retun string.
	*/
	function arrGetVariantPageSummary($category_id,$product_id,$default_city="",$city_id="",$flag="overview",$quickviewresponse="xml"){
		require_once(CLASSPATH.'price.class.php');
		require_once(CLASSPATH.'product.class.php');
		require_once(CLASSPATH.'brand.class.php');
		$product = new ProductManagement;
		$price = new price;
		$brand = new BrandManagement;
		$product_result = $product->arrGetProductDetails($product_id,$category_id,"","1","","","1","","",$default_city,"","",$city_id);
		$product_name = $product_result[0]['product_name'];
		$productinfo_result = $product->arrGetProductNameInfo("","","",$product_name);

		$model_id = $productinfo_result[0]['product_name_id'];
		$image_path = $productinfo_result[0]['image_path'];
		if(!empty($image_path)){
			$image_path = resizeImagePath($image_path,"87X65",$aModuleImageResize);
			$image_path = CENTRAL_IMAGE_URL.str_replace(array(CENTRAL_IMAGE_URL),"",$image_path);
		}
			$variant = $product_result[0]['variant'];
		$brand_id = $product_result[0]['brand_id'];
		$product_desc = $product_result[0]['product_desc'];
		$exshowroom_price  = $product_result[0]['variant_value'];
		$conditional_price  = $product_result[0]['variant_value'];
		unset($varianUrlYear);
   		$varianUrlYear = buildYear($product_result[0]['arrival_date'],$product_result[0]['discontinue_date']);
		$brand_result = $brand->arrGetBrandDetails($brand_id,$category_id);
		$brand_name = trim($brand_result[0]['brand_name']);
		if(!empty($brand_name)){
			$productArr[] = $brand_name;
		}
		if(!empty($product_name)){
			$productArr[] = $product_name;
		}
		if(!empty($variant)){
			$productArr[] = $variant;
		}
		$product_name = implode(" ",$productArr);
		$variantnameSeoArr[] = SEO_WEB_URL;
		$variantnameSeoArr[] = constructUrl($brand_name);
		$variantnameSeoArr[] = constructUrl($product_result[0]['product_name']);
		$variantnameSeoArr[] = constructUrl($variant);
		if(!empty($varianUrlYear)){
              $variantnameSeoArr[] = $varianUrlYear;
        }
		$variantnameSeoArr[] = 'features';
		#$variantnameSeoArr[] = $product_id;
		$seo_url = implode("/",$variantnameSeoArr);
		unset($variantnameSeoArr);
		$variantnameSeoArr[] = SEO_WEB_URL;
        $variantnameSeoArr[] = constructUrl($brand_name);
        $variantnameSeoArr[] = constructUrl($product_result[0]['product_name']);
        $variantnameSeoArr[] = constructUrl($variant);
		if(!empty($varianUrlYear)){
              $variantnameSeoArr[] = $varianUrlYear;
        }
        $variantnameSeoArr[] = SEO_GET_ON_ROAD_PRICE;
        #$variantnameSeoArr[] = $product_id;
        $on_road_seo_url = implode("/",$variantnameSeoArr);
		$all_variant_result = $price->arrGetVariantDetail();
		$cnt = sizeof($all_variant_result);
		$variantArr = Array();
		for($i=0;$i<$cnt;$i++){
			$variant = $all_variant_result[$i]['variant'];
			$variant_id = $all_variant_result[$i]['variant_id'];
			if(!stripos($variant,'showroom')){
				$variantArr[] = $variant;
			}
		}
		if(!empty($exshowroom_price)){
			$exshowroom_price = priceFormat($exshowroom_price);
			//$extra_variant = " (".implode(",",$variantArr)." and other taxes/charges extra)";
		}
		$aFeatureData=array();
		$aFeatureData["$product_name Price"]["Ex-Showroom Price"]= $exshowroom_price.$extra_variant;
		$aFeatureData["$product_name Price"]["product_desc"]= $product_desc;
		$sSql="SELECT * FROM FEATURE_OVERVIEW_MASTER FM, MAIN_FEATURE_GROUP SB WHERE FM.category_id = $category_id AND 	FM.overview_sub_group_id = SB.group_id order by FM.position asc";
		$key = $this->featurekey."_variant_page_overview_category_id_$category_id";
		$feature_result = $this->cache->get($key);
		if(empty($feature_result)){
			$feature_result = $this->select($sSql);
			$this->cache->set($key,$feature_result);
		}
		
		if($flag=="overview"){
			foreach($feature_result as $ikey=>$aValueData){
				if($aValueData['title']!="Transmission" && $aValueData['title']!="Ideal for" &&  $aValueData['title']!="No. of cylinders" && $aValueData['title']!="Body Style" && $aValueData['title']!="Safety tech" && $aValueData['title']!="Creature comforts" && $aValueData['title']!="Warranty" && $aValueData['title']!="Engine capacity" && $aValueData['title']!="Fuel Economy (ARAI)" && $aValueData['title']!="Segment"){
					$feature_id = $aValueData['feature_id'];
					if($feature_id != '' && !empty($product_id)){
						unset($feature_img_path);unset($feature_description);unset($abbreviation);
						$sql = "select PRODUCT_FEATURE.*,FEATURE_MASTER.* from PRODUCT_FEATURE,FEATURE_MASTER where PRODUCT_FEATURE.feature_id = $feature_id and FEATURE_MASTER.feature_id = PRODUCT_FEATURE.feature_id and PRODUCT_FEATURE.product_id=$product_id";
						$key = $this->featurekey."_$feature_id"."_product_$product_id"."_variant_page_summary";
						$product_feature = $this->cache->get($key);
						if(empty($product_feature)){
							$product_feature = $this->select($sql);
							$this->cache->set($key,$product_feature);
						}
						$feature_value = trim($product_feature[0]['feature_value']);
						$feature_name = trim($product_feature[0]['feature_name']);
						$abbreviation = $aValueData['abbreviation'];
						$feature_description = trim($product_feature[0]['feature_description']);
						$feature_img_path = trim($product_feature[0]['feature_img_path']);
					}
					if($feature_value == "-"){$feature_value="";}

					unset($featureWithUnitArr);
					if(!empty($feature_value)){
						$featureWithUnitArr[] = $feature_value;
					}
					if(!empty($abbreviation) && !empty($feature_value)){
						$featureWithUnitArr[] = $abbreviation;
					}
					$overview_display_name = $aValueData['overview_display_name'] ? $aValueData['overview_display_name'] : $aValueData['main_group_name'];
					if($aValueData['title'] != ""){
						if(sizeof($featureWithUnitArr) > 0){
							$aFeatureData[$product_name." ".$overview_display_name][$aValueData['title']]['feature_value']= implode(" ",$featureWithUnitArr);
							$aFeatureData[$product_name." ".$overview_display_name][$aValueData['title']]['feature_description']= $feature_description;
							$aFeatureData[$product_name." ".$overview_display_name][$aValueData['title']]['feature_img_path']= $feature_img_path;
						}
					}else{
						if(!empty($feature_value) && $feature_value != '-'){
							$titleArr[] = $feature_value;
						}
						if(!empty($abbreviation) && !empty($feature_value) && $feature_value != '-'){
							$titleArr[] = $abbreviation;
						}
						if(sizeof($titleArr) > 0){
							$aFeatureData[$product_name." ".$overview_display_name][0][] = implode("",$titleArr);
							$aFeatureData[$product_name." ".$overview_display_name]['main_group_id'] = $aValueData['overview_sub_group_id'];
							$aFeatureData[$product_name." ".$overview_display_name]['main_group_name'] = $overview_display_name;
						}
						unset($titleArr);
					}
				}
			}
			return $aFeatureData;
		}else{
			foreach($feature_result as $ikey=>$aValueData){
				$feature_id = $aValueData['feature_id'];
				if($feature_id != '' && !empty($product_id)){
					unset($feature_img_path);unset($feature_description);unset($abbreviation);
					$sql = "select PRODUCT_FEATURE.*,FEATURE_MASTER.* from PRODUCT_FEATURE,FEATURE_MASTER where PRODUCT_FEATURE.feature_id = $feature_id and FEATURE_MASTER.feature_id = PRODUCT_FEATURE.feature_id and PRODUCT_FEATURE.product_id=$product_id";
					$key = $this->featurekey."_$feature_id"."_product_$product_id"."_variant_page_summary";
					$product_feature = $this->cache->get($key);
					if(empty($product_feature)){
						$product_feature = $this->select($sql);
						$this->cache->set($key,$product_feature);
					}
					//print_r($product_feature); //die();
					$feature_value = trim($product_feature[0]['feature_value']);
					$feature_name = trim($product_feature[0]['feature_name']);
					$abbreviation = $aValueData['abbreviation'];
					$feature_description = trim($product_feature[0]['feature_description']);
					$feature_img_path = trim($product_feature[0]['feature_img_path']);

					if($aValueData['title']=="Screen Size"){
						//"FeatureName--".$feature_name."<br>";
						if($feature_value=="YES"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['Screen Size']['feature_value'][] = $feature_name;
						}else if($feature_value!="" and $feature_value!="NO" and $feature_value!="No"){
								if($abbreviation!=''){
										$feature_name = $feature_name." ".$abbreviation;
								}
								$arr_features['Screen Size']['feature_value'][] = $feature_value;
						}

					}
					else if($aValueData['title']=="OS"){
						if($feature_value=="YES"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['OS']['feature_value'][] = $feature_name;
						}else if($feature_value!="" and $feature_value!="NO" and $feature_value!="No"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['OS']['feature_value'][] = $feature_value;
						}
					}else if($aValueData['title']=="Speed"){
						if($feature_value=="YES"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['Speed']['feature_value'][] = $feature_name;
						}else if($feature_value!="" and $feature_value!="NO" and $feature_value!="No"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['Speed']['feature_value'][] = $feature_value;
						}
					}else if($aValueData['title']=="Internal Memory"){
						if($feature_value=="YES"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['Internal Memory']['feature_value'][] = $feature_name;
						}else if($feature_value!="" and $feature_value!="NO" and $feature_value!="No"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['Internal Memory']['feature_value'][] = $feature_value;
						}
					}
					/*else if($aValueData['title']=="Engine capacity"){
						if($abbreviation!=''){
							$feature_value = $feature_value." ".$abbreviation;
						}
						$arr_features['Engine capacity']['feature_value'] = $feature_value;
					}else if($aValueData['title']=="No. of cylinders"){
						if($abbreviation!=''){
							$feature_value = $feature_value." ".$abbreviation;
						}
						$arr_features['No. of cylinders']['feature_value'] = $feature_value;
					}else if($aValueData['title']=="Fuel Economy (ARAI)"){
						if($abbreviation!='' && $feature_value!='' &&  $feature_value!='-'){
							$feature_value = $feature_value." ".$abbreviation;
						}else{
							 $feature_value = '';
						}
						$arr_features['Fuel Economy (ARAI)']['feature_value'] = $feature_value;
					}else if($aValueData['title']=="Power"){
						if($abbreviation!='' && $feature_value!=''){
							$feature_value = $feature_value." ".$abbreviation;
						}
						$arr_features['Power']['feature_value'] = $feature_value;
					}else if($aValueData['title']=="Torque"){
						if($abbreviation!=''){
							$feature_value = $feature_value." ".$abbreviation;
						}
						$arr_features['Torque']['feature_value'] = $feature_value;
					}else if($aValueData['title']=="Body Style"){
						if($feature_value=="YES"){
							if($abbreviation!=''){
								$feature_name = $feature_name." ".$abbreviation;
							}
							$arr_features['Body Style']['feature_value'] = $feature_name;
						}
					}else if($aValueData['title']=="Segment"){
						if($abbreviation!=''){
							$feature_value = $feature_value." ".$abbreviation;
						}
						$arr_features['Segment']['feature_value'] = $feature_value;
					}else if($aValueData['title']=="Ideal for"){
						if($abbreviation!=''){
							$feature_value = $feature_value." ".$abbreviation;
						}
						$arr_features['Ideal for']['feature_value'] = $feature_value;
					}

					if($quickviewresponse != 'xml' && $aValueData['title']=="Fuel Type"){
						if($abbreviation!=''){
							$feature_value = $feature_value." ".$abbreviation;
						}
						$arr_features['Fuel Type']['feature_value'] = $feature_value;
					}
					*/

				}
			}
			foreach($arr_features as $title=>$aValue){
				if(is_array($aValue['feature_value'])){
					$arr_disp_feature[$title] = implode(",",$aValue['feature_value']);
				}else{
					$arr_disp_feature[$title] = $aValue['feature_value'];
				}
			}
			//$aDataPos1 = array("Engine capacity","Fuel Economy (ARAI)","Power","Torque","Body Style","Segment","Ideal for","Safety tech","Creature comforts","Warranty");
			if($conditional_price < '2500000'){
				$aDataPos1 = array("Screen Size","Speed","Internal Memory","OS");
			}else if($conditional_price >='2500000'){
				$aDataPos1 = array("Screen Size","Speed","Internal Memory","OS");
			}
			if($quickviewresponse != 'xml'){ $aDataPos1[] = 'Fuel Type'; }

			$cnt = sizeof($arr_disp_feature);
			foreach($aDataPos1 as $key=>$value){

				$features_result_arr[$value] = $arr_disp_feature[$value];
			}

			if($quickviewresponse == 'xml'){
				$quickXml.= "<VARIANT_SUMMERY>";
				$quickXml.= "<PRODUCT_DISP_NAME>$product_name</PRODUCT_DISP_NAME>";
				$quickXml.= "<PRODUCT_ID>$product_id</PRODUCT_ID>";
				$quickXml.= "<MODEL_ID>$model_id</MODEL_ID>";
				$quickXml.= "<SEO_PRODUCT_URL>$seo_url</SEO_PRODUCT_URL>";
				$quickXml.= "<ONROAD_SEO_URL>$on_road_seo_url</ONROAD_SEO_URL>";
				$quickXml.= "<PRICE>$exshowroom_price</PRICE>";
				$quickXml.= "<ORG_PRICE>$conditional_price</ORG_PRICE>";
				$quickXml.= "<IMAGE_PATH>$image_path</IMAGE_PATH>";
				foreach($features_result_arr as $lkey=>$DataValue){
						if($DataValue=='') {
							$DataValue = "N/A";
						}
						$quickXml.= "<VARIANT_SUMMERY_DATA>";
						$quickXml.= "<FEATURE_NAME>$lkey</FEATURE_NAME>";
						$quickXml.= "<FEATURE_VALUE>$DataValue</FEATURE_VALUE>";
						$quickXml.= "</VARIANT_SUMMERY_DATA>";
				}
				$quickXml.= "</VARIANT_SUMMERY>";
				return $quickXml;
			}else{
				return $features_result_arr;
			}
		}


	}//end

	/**
	 * @note function is used to insert feature unit.
	 * @param an associate array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post integer $unit_id.
	 * return integer.
	 */
	 function intInsertUsedCarFeatureUnit($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USEDCAR_FEATURE_UNIT",array_keys($insert_param),array_values($insert_param));
		$unit_id = $this->insert($sql);
		if($unit_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($unit_id)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_unit");
			$this->arrFeatureUnitDetails($unit_id);
		}
		return $unit_id;
	 }
	 /**
	 * @note function is used to update the feature unit into the database.
	 * @param an associative array $update_param.
	 * @param an integer $unit_id.
	 * @pre $update_param must be valid associative array and $unit_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateUsedCarFeatureUnit($unit_id,$update_param)
	 {
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USEDCAR_FEATURE_UNIT",array_keys($update_param),array_values($update_param),"unit_id",$unit_id);
		$isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_unit");
			$this->arrFeatureUnitDetails($unit_id);
		}
	 	return $isUpdate;
	 }

	 /**
	 * @note function is used to delete the feature.
	 * @param integer $unit_id.
	 * @pre $unit_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteUsedCarFeatureUnit($unit_id)
	 {
	 	$sql = "delete from USEDCAR_FEATURE_UNIT where unit_id = $unit_id";
	 	$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_unit");
	 	return $isDelete;
	 }

	 /**
	 * @note function is used to feature unit details.
	 * @param integer $feature_id.
	 * @param integer $unit_id.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post an associative array of feature unit.
	 * return an array.
	 */
	 function arrUsedCarFeatureUnitDetails($unit_id="",$category_id="",$status="1",$startlimit="",$count="")
	 {
		$keyArr[] = $this->usedcarfeaturekey."_unit";
	 	if(!empty($unit_id)){
			$keyArr[] = $unit_id;
	 		$whereClauseArr[] = "unit_id in($unit_id)";
	 	}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
	 		$whereClauseArr[] = "category_id in($category_id)";
	 	}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
	 	if(sizeof($whereClauseArr) > 0){
	 		$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
	 	}
	 	if(!empty($startlimit)){
			$keyArr[] = $startlimit;
	 		$limitArr[] = $startlimit;
	 	}else{$keyArr[] =-1;}
	 	if(!empty($count)){
			$keyArr[] = $count;
	 		$limitArr[] = $count;
	 	}else{$keyArr[] =-1;}
	 	if(sizeof($limitArr) > 0){
	 		$limitStr = " limit ".implode(" , ",$limitArr);
	 	}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
	 	$sql = "select * from USEDCAR_FEATURE_UNIT $whereClauseStr order by unit_name asc $limitStr";
	 	$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	/**
	* @note function is used to insert main feature group into the db.
	* @param associative array $insert_param.
	* @pre $insert_param must be valid,non-empty associative array.
	* @post integer feature group id.
	* return integer.
	*/
	function insertUsedCarFeatureMainGroup($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
        $sql = $this->getInsertSql("USEDCAR_MAIN_FEATURE_GROUP",array_keys($insert_param),array_values($insert_param));
        $feature_group_id = $this->insert($sql);
        if($feature_group_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($feature_group_id)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_main_group");
			$this->arrGetFeatureMainGroupDetails($feature_group_id);
		}
        return $feature_group_id;
	}
	 /**
	 * @note function is used to update the feature main group into the database.
	 * @param an associative array $update_param.
	 * @param an integer $feature_group_id.
	 * @pre $update_param must be valid associative array and $feature_group_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateUsedCarFeatureMainGroup($feature_group_id,$update_param)
	 {
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("USEDCAR_MAIN_FEATURE_GROUP",array_keys($update_param),array_values($update_param),"group_id",$feature_group_id);
			$isUpdate = $this->update($sql);
			if(!empty($isUpdate)){
				$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_main_group");
				$this->arrGetFeatureMainGroupDetails($feature_group_id);
			}
			return $isUpdate;
	 }

	 /**
	 * @note function is used to delete the main feature group.
	 * @param integer $feature_group_id.
	 * @pre $feature_group_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteUsedCarFeatureMainGroup($feature_group_id)
	 {
		$sql = "delete from USEDCAR_MAIN_FEATURE_GROUP where group_id = $feature_group_id";
		$isDelete = $this->sql_delete_data($sql);
		if(!empty($isDelete)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_main_group");
		}
		return $isDelete;
	 }
	/**
	* @note function is used to get mail feature group.
	* @param integer $feature_group_id.
	* @param integer $category_id.
	* @param boolean $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post associative array of feature group details
	* return array.
	*/
	function arrGetUsedCarFeatureMainGroupDetails($feature_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->usedcarfeaturekey."_main_group_arrGetUsedCarFeatureMainGroupDetails";
		if(is_array($feature_group_id)){
			$feature_group_id = implode(",",$feature_group_id);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(!empty($feature_group_id)){
			$keyArr[] = $feature_group_id;
			$whereClauseArr[] = "group_id in ($feature_group_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
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
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from USEDCAR_MAIN_FEATURE_GROUP $whereClauseStr order by position ASC $limitStr ";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to insert the feature sub group details into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_group_id.
	 * retun integer.
	 */
	function intInsertUsedCarFeatureSubGroupDetails($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
        $sql = $this->getInsertSql("USEDCAR_FEATURE_SUB_GROUP",array_keys($insert_param),array_values($insert_param));
        $feature_group_id = $this->insert($sql);
        if($feature_group_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($feature_group_id)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_sub_group");
			$this->arrFeatureSubGroupDetails($feature_group_id);
		}
        return $feature_group_id;
	}
	/**
	 * @note function is used to update the feature sub group details into the database.
	 * @param an associative array $update_param.
	 * @param an integer $sub_group_id.
	 * @pre $update_param must be valid associative array and $sub_group_id must be non empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	function boolUpdateUsedCarFeatureSubGroupDetails($sub_group_id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
        $sql = $this->getUpdateSql("USEDCAR_FEATURE_SUB_GROUP",array_keys($update_param),array_values($update_param),"sub_group_id",$sub_group_id);
        $isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_sub_group");
			$this->arrFeatureSubGroupDetails($sub_group_id);
		}
        return $isUpdate;
	}
	/**
	 * @note function is used to delete the feature sub group details.
	 * @param integer $sub_group_id.
	 * @pre $sub_group_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	function boolDeleteUsedCarFeatureSubGroupDetails($sub_group_id){
		$sql = "delete from USEDCAR_FEATURE_SUB_GROUP where sub_group_id = $sub_group_id";
        $isDelete = $this->sql_delete_data($sql);
		if(!empty($isDelete)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey."_sub_group");
		}
	}

	 /**
	 * @note function is used to get feature sub group details
	 *
	 * @param an integer $sub_group_id.
	 * @param an integer $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 *
	 * @pre not required.
	 *
	 * @post feature sub group details in associative array.
	 * retun an array.
	 */
	function arrUsedCarFeatureSubGroupDetails($sub_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->usedcarfeaturekey."_sub_group_arrUsedCarFeatureSubGroupDetails";
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "sub_group_id = $sub_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from USEDCAR_FEATURE_SUB_GROUP $whereClauseStr order by sub_group_position asc $limitStr ";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to get feature sub group details
	 *
	 * @param an integer $sub_group_id.
	 * @param an integer $main_group_id.
	 * @param an integer $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 *
	 * @pre not required.
	 *
	 * @post feature sub group details in associative array.
	 * retun an array.
	 */
	function arrFetchUsedCarFeatureSubGroupDetails($sub_group_id="",$main_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->usedcarfeaturekey."_sub_group_arrFetchUsedCarFeatureSubGroupDetails";
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "sub_group_id = $sub_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($main_group_id)){
			$keyArr[] = $main_group_id;
			$whereClauseArr[] = "main_group_id = $main_group_id";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from USEDCAR_FEATURE_SUB_GROUP $whereClauseStr order by sub_group_position asc $limitStr ";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to insert the feature into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertUsedCarFeature($insert_param)
	{
		$result = $this->intMaxFeatureDisplayOrder();
		$insert_param['feature_display_order'] = $result[0]['feature_display_order'];
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USEDCAR_FEATURE_MASTER",array_keys($insert_param),array_values($insert_param));
		$feature_id = $this->insert($sql);
		if($feature_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($feature_id)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey);
			$this->arrGetFeatureDetails($feature_id);
		}
		return $feature_id;
	}

	/**
	 * @note function is used to update the feature into the database.
	 * @param an associative array $update_param.
	 * @param an integer $feature_id.
	 * @pre $update_param must be valid associative array and $feature_id must be non empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateUsedCarFeature($feature_id,$update_param)
	 {
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USEDCAR_FEATURE_MASTER",array_keys($update_param),array_values($update_param),"feature_id",$feature_id);
		$isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->usedcarfeaturekey);
			$this->arrGetFeatureDetails($feature_id);
		}
		return $isUpdate;
	 }
	 /**
	 * @note function is used to delete the feature.
	 * @param integer $feature_id.
	 * @pre $feature_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteUsedCarFeature($feature_id)
	 {
	 	$sql = "delete from USEDCAR_FEATURE_MASTER where feature_id = $feature_id";
	 	$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->usedcarfeaturekey);
	 	return $isDelete;
	 }

	 /**
	 * @note function is used to get feature details.
	 * @param an integer/comma seperated feature ids/feature ids array $feature_ids.
	 * @param an integer category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post feature details in associative array.
	 * retun an array.
	 */
	 function arrGetUsedCarFeatureDetails($feature_ids="",$category_id="",$main_group_id="",$sub_group_id="",$status="1",$startlimit="",$count="",$feature_name="",$orderby="")
	 {
		$keyArr[] = $this->usedcarfeaturekey.'_arrGetUsedCarFeatureDetails';
	 	if(is_array($feature_ids)){
	 		$feature_ids = implode(",",$feature_ids);

	 	}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($main_group_id)){
			$main_group_id = implode(",",$main_group_id);
		}
		if(is_array($sub_group_id)){
			$sub_group_id = implode(",",$sub_group_id);
		}
		if(!empty($feature_name)){
			$keyArr[] = $feature_name;
	 		$whereClauseArr[] = "lower(feature_name)= '$feature_name'";
	 	}else{$keyArr[] =-1;}
	 	if($status != ''){
			$keyArr[] = $status;
	 		$whereClauseArr[] = "status=$status";
	 	}else{$keyArr[] =-1;}
	 	if(!empty($category_id)){
			$keyArr[] = $category_id;
	 		$whereClauseArr[] = "category_id in ($category_id)";
	 	}else{$keyArr[] =-1;}
	 	if(!empty($feature_ids)){
			$keyArr[] = $feature_ids;
	 		$whereClauseArr[] = "feature_id in ($feature_ids)";
	 	}else{$keyArr[] =-1;}
		if(!empty($main_group_id)){
			$keyArr[] = $main_group_id;
			$whereClauseArr[] = "main_feature_group in ($main_group_id)";
		}else{$keyArr[] =-1;}
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "feature_group in ($sub_group_id)";
		}else{$keyArr[] =-1;}
	 	if(sizeof($whereClauseArr) > 0){
	 		$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
	 	}
	 	if(!empty($startlimit)){
			$keyArr[] = $startlimit;
	 		$limitArr[] = $startlimit;
	 	}else{$keyArr[] =-1;}
	 	if(!empty($count)){
			$keyArr[] = $count;
	 		$limitArr[] = $count;
	 	}else{$keyArr[] =-1;}
	 	if(sizeof($limitArr) > 0){
	 		$limitStr = " limit ".implode(" , ",$limitArr);
	 	}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
	 	$sql = "select * from USEDCAR_FEATURE_MASTER $whereClauseStr $orderby $limitStr" ;
	 	$result = $this->select($sql);
		$this->cache->set($key,$result);
	 	return $result;
	 }
	 function arrGetUsedCarFeatureWithoutPivot($category_id){
		$key = $this->usedcarfeaturekey."_arrGetUsedCarFeatureWithoutPivot_$category_id";
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select USEDCAR_FEATURE_MASTER.* from USEDCAR_FEATURE_MASTER,USEDCAR_PIVOT_MASTER where USEDCAR_FEATURE_MASTER.category_id = $category_id and USEDCAR_FEATURE_MASTER.status = 1 and USEDCAR_FEATURE_MASTER.feature_id != USEDCAR_PIVOT_MASTER.feature_id group by USEDCAR_FEATURE_MASTER.feature_name order by USEDCAR_FEATURE_MASTER.feature_name asc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetUsedCarFeatureWithPivot($category_id){
		$key = $this->usedcarfeaturekey."_arrGetUsedCarFeatureWithPivot$category_id";
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select USEDCAR_FEATURE_MASTER.* from USEDCAR_FEATURE_MASTER,USEDCAR_PIVOT_MASTER where USEDCAR_FEATURE_MASTER.category_id = $category_id and USEDCAR_FEATURE_MASTER.status = 1 and USEDCAR_FEATURE_MASTER.feature_id = USEDCAR_PIVOT_MASTER.feature_id group by USEDCAR_FEATURE_MASTER.feature_name order by USEDCAR_FEATURE_MASTER.feature_name asc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetUsedCarPivotFeatureDetails($feature_name,$category_id,$status="1"){
		$keyArr[] = $this->usedcarfeaturekey."_arrGetUsedCarPivotFeatureDetails";
		$whereClauseArr[] = "USEDCAR_FEATURE_MASTER.feature_id = USEDCAR_PIVOT_MASTER.feature_id";
		if(!empty($feature_name)){
			$keyArr[] = $feature_name;
			$whereClauseArr[] = "lower(USEDCAR_FEATURE_MASTER.feature_name) = '$feature_name'";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "USEDCAR_FEATURE_MASTER.category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "USEDCAR_FEATURE_MASTER.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from USEDCAR_FEATURE_MASTER,USEDCAR_PIVOT_MASTER $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	//Model color section start
	function intInsertModelColorDetails($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("COLOR_MASTER",array_keys($insert_param),array_values($insert_param));
		$model_color_id = $this->insert($sql);
		if($model_color_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($model_color_id)){
			$this->cache->searchDeleteKeys($this->modelcolorskey);
			$this->arrGetModelColorDetails($model_color_id);
		}
		return $model_color_id;
	}
	function boolUpdateModelColorDetails($model_color_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("COLOR_MASTER",array_keys($update_param),array_values($update_param),"color_id",$model_color_id);
		$isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->modelcolorskey);
			$this->arrGetFeatureDetails($model_color_id);
		}
		return $isUpdate;
	}
	function booldeleteModelColorInfo($model_color_id){
		$sql = "delete from COLOR_MASTER where model_color_id = $model_color_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->modelcolorskey);
		return $isDelete;
	}
	function arrGetModelColorDetails($color_id="",$color_code="",$category_id="",$status="1",$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->modelcolorskey.'_arrGetModelColorDetails';
		if(is_array($model_color_id)){
			$color_id = implode(",",$color_id);
		}
		if(is_array($color_code)){
			$color_code = implode(",",$color_code);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(!empty($color_id)){
			$keyArr[] = $color_id;
			$whereClauseArr[] = "color_id in($color_id)";
		}else{$keyArr[] =-1;}
		if(!empty($color_code)){
			$keyArr[] = $color_code;
			$whereClauseArr[] = "color_code in($color_code)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby = "order by create_date desc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from COLOR_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function intInsertModelColors($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("MODEL_COLORS",array_keys($insert_param),array_values($insert_param));
		$color_id = $this->insert($sql);
		if($color_id == 'Duplicate entry'){ return 'exists';}
		if(is_int($color_id)){
			$this->cache->searchDeleteKeys($this->modelcolorskey);
			$this->arrGetModelColorDetails($color_id);
		}
		return $color_id;
	}

	function intInsertUpdateModelColors($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("MODEL_COLORS",array_keys($insert_param),array_values($insert_param));
		$color_id = $this->insert($sql);
		if($color_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->modelcolorskey);
		return $color_id;
	}

	function boolUpdateModelColors($color_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("MODEL_COLORS",array_keys($update_param),array_values($update_param),"color_id",$color_id);
		$isUpdate = $this->update($sql);
		if(!empty($isUpdate)){
			$this->cache->searchDeleteKeys($this->modelcolorskey);
			$this->arrGetFeatureDetails($color_id);
		}
		return $isUpdate;
	}

	 function booldeleteModelColors($model_id){
                $sql = "delete from MODEL_COLORS where product_name_id = $model_id";
                $isDelete = $this->sql_delete_data($sql);
                $this->cache->searchDeleteKeys($this->modelcolorskey);
                return $isDelete;
        }

	function booldeleteVariantColors($variant_id){
                $sql = "delete from VARIANT_COLORS where product_id = $variant_id";
                $isDelete = $this->sql_delete_data($sql);
                $this->cache->searchDeleteKeys($this->modelcolorskey);
                return $isDelete;
        }

	function arrGetModelColors($model_color_ids="",$product_name_id="",$color_ids="",$category_id="",$status="1",$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->modelcolorskey.'_arrGetModelColors';
		if(is_array($model_color_id)){
			$model_color_id = implode(",",$model_color_id);
		}
		if(is_array($color_ids)){
			$color_ids = implode(",",$color_ids);
		}
		if(is_array($product_name_id)){
			$product_name_id = implode(",",$product_name_id);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(!empty($model_color_id)){
			$keyArr[] = $model_color_id;
			$whereClauseArr[] = "MC.model_color_id in($model_color_id)";
		}else{$keyArr[] =-1;}
		if(!empty($color_ids)){
			$keyArr[] = $color_ids;
			$whereClauseArr[] = "MC.color_id in($color_ids)";
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " MCM.color_id =  MC.color_id";
		if(!empty($product_name_id)){
			$keyArr[] = $product_name_id;
			$whereClauseArr[] = "product_name_id in($product_name_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "MC.category_id in($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "MC.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby = "order by MC.create_date desc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from MODEL_COLORS as MC ,COLOR_MASTER MCM $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetModelColorsCnt($model_color_ids="",$product_name_id="",$color_ids="",$category_id="",$status="1"){
		$keyArr[] = $this->modelcolorskey."_arrGetModelColorsCnt";
		if(is_array($model_color_id)){
			$model_color_id = implode(",",$model_color_id);
		}
		if(is_array($color_ids)){
			$color_ids = implode(",",$color_ids);
		}
		if(is_array($product_name_id)){
			$product_name_id = implode(",",$product_name_id);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(!empty($model_color_id)){
			$keyArr[] = $model_color_id;
			$whereClauseArr[] = "MC.model_color_id in($model_color_id)";
		}else{$keyArr[] =-1;}
		if(!empty($color_ids)){
			$keyArr[] = $color_ids;
			$whereClauseArr[] = "MC.color_id in($color_ids)";
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " MCM.color_id =  MC.color_id";
		if(!empty($product_name_id)){
			$keyArr[] = $product_name_id;
			$whereClauseArr[] = "product_name_id in($product_name_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "MC.category_id in($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "MC.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(model_color_id) as cnt from MODEL_COLORS as MC ,COLOR_MASTER MCM $whereClauseStr ";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetVariantColors($variant_color_ids="",$brand_id="",$product_name_id="",$product_id="",$color_ids="",$category_id="",$status="1",$startlimit="",$count="",$orderby=""){

		$keyArr[] = $this->variantcolorskey.'_arrGetVariantColors';
		if(is_array($variant_color_id)){
			$variant_color_id = implode(",",$variant_color_id);
		}
		if(is_array($color_ids)){
			$color_ids = implode(",",$color_ids);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if(is_array($product_id)){
			$product_id = implode(",",$product_id);
		}
		if(is_array($product_name_id)){
			$product_name_id = implode(",",$product_name_id);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(!empty($variant_color_id)){
			$keyArr[] = $variant_color_id;
			$whereClauseArr[] = "VC.variant_color_id in($variant_color_id)";
		}else{$keyArr[] =-1;}
		if(!empty($color_ids)){
			$keyArr[] = $color_ids;
			$whereClauseArr[] = "VC.color_id in($color_ids)";
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " MCM.color_id =  VC.color_id";
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in($brand_id)";
		}else{$keyArr[] =-1;}
		if(!empty($product_id)){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "product_id in($product_id)";
		}else{$keyArr[] =-1;}
		if(!empty($product_name_id)){
			$keyArr[] = $product_name_id;
			$whereClauseArr[] = "product_name_id in($product_name_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "VC.category_id in($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "VC.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby = "order by VC.create_date desc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from VARIANT_COLORS as VC ,COLOR_MASTER MCM $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetVariantColorsCnt($variant_color_ids="",$brand_id="",$product_name_id="",$product_id="",$color_ids="",$category_id="",$status="1"){
		$keyArr[] = $this->variantcolorskey."_arrGetVariantColorsCnt";
		if(is_array($variant_color_id)){
			$variant_color_id = implode(",",$variant_color_id);
		}
		if(is_array($color_ids)){
			$color_ids = implode(",",$color_ids);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if(is_array($product_id)){
			$product_id = implode(",",$product_id);
		}
		if(is_array($product_name_id)){
			$product_name_id = implode(",",$product_name_id);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(!empty($variant_color_id)){
			$keyArr[] = $variant_color_id;
			$whereClauseArr[] = "VC.variant_color_id in($variant_color_id)";
		}else{$keyArr[] =-1;}
		if(!empty($color_ids)){
			$keyArr[] = $color_ids;
			$whereClauseArr[] = "VC.color_id in($color_ids)";
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = " MCM.color_id =  VC.color_id";
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in($brand_id)";
		}else{$keyArr[] =-1;}
		if(!empty($product_id)){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "product_id in($product_id)";
		}else{$keyArr[] =-1;}
		if(!empty($product_name_id)){
			$keyArr[] = $product_name_id;
			$whereClauseArr[] = "product_name_id in($product_name_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "VC.category_id in($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "VC.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(variant_color_id) as cnt from VARIANT_COLORS as VC ,COLOR_MASTER MCM $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function intInsertUpdateVariantColors($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("VARIANT_COLORS",array_keys($insert_param),array_values($insert_param));
		$color_id = $this->insert($sql);
		if($color_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->variantcolorskey);
		return $color_id;
	}

	function get_features($select_param){
		list($feature_group,$variant_id,$type) = array($select_param['feature_group'],$select_param['variant_id'],$select_param['type']);
		$keyArr[] = $this->featurekey.'_get_features';
		if($type=='segment'){
			$arrFieldName[] = "pf.feature_id";
			$arrFieldName[] = "pf.feature_value";
		}else{
			$arrFieldName[] = "pf.feature_id";
			$arrFieldName[] = "fm.feature_name";
		}
		$keyArr[] = "type_$type";
		if(count($arrFieldName)>0){
			$strFieldName = implode(',',$arrFieldName);
		}
		$arrWhereClause[] = "fm.feature_id = pf.feature_id";

		if(!empty($feature_group)){
			$arrWhereClause[] = "fm.feature_group=$feature_group";
			$keyArr[] = $feature_group;
		}else{$keyArr[] =-1;}
		if(!empty($variant_id)){
			$arrWhereClause[] = "pf.product_id=$variant_id";
			$keyArr[] = $variant_id;
		}else{$keyArr[] =-1;}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "SELECT $strFieldName FROM PRODUCT_FEATURE pf, FEATURE_MASTER fm $strWhereClause";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetProductFeatureByValue($select_params){
		list($product_feature_id,$product_id,$feature_id,$feature_value) = array($select_params['product_feature_id'],$select_params['product_id'],$select_params['feature_id'],$select_params['feature_value']);
		$keyArr[] = $this->featurekey.'_arrGetProductFeatureByValue';
		if(!empty($product_feature_id)){
			$arrWhereClause[] = "product_feature_id=$product_feature_id";
			$keyArr[] = $product_feature_id;
		}else{$keyArr[] =-1;}
		$product_id = cleanMySqlInt($product_id);	
		if(!empty($product_id)){
			if(is_array($product_id)){
				#print_r($product_id);die();
				$product_id = implode(',',$product_id);
			}
			$arrWhereClause[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$arrWhereClause[] = "feature_id=$feature_id";
			$keyArr[] = $feature_id;
		}else{$keyArr[] =-1;}
		if(!empty($feature_value)){
			$arrWhereClause[] = "feature_value='$feature_value'";
			$keyArr[] = $feature_value;
		}else{$keyArr[] =-1;}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql ="SELECT product_id FROM PRODUCT_FEATURE ".$strWhereClause;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetBodyStyleByProduct($request_string){
		list($brand_id,$model_id,$product_id,$feature_id) = array($request_string['brand_id'],$request_string['model_id'],$request_string['product_id'],$request_string['feature_id']);
		$keyArr[] = $this->featurekey.'_arrGetBodyStyleByProduct';

		$whereClauseArr[] = 'PF.product_id = PM.product_id';
		if(!empty($brand_id)){
			$whereClauseArr[] = "PM.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}
		if(!empty($mode_id)){
			$whereClauseArr[] = "PM.product_name_id in ($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "PM.product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$whereClauseArr[] = "PF.feature_id in ($feature_id)";
			$keyArr[] = $feature_id;
		}else{$keyArr[] =-1;}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		$sql = "select distinct(feature_id) as feature_id from PRODUCT_FEATURE AS PF,PRODUCT_MASTER AS PM $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;

    }

    function arrGetFeatureAndSubGroupDetails($feature_name="",$category_id="",$status="1"){
		/*SELECT sub_group_name, feature_id, feature_name
		FROM  `FEATURE_SUB_GROUP` S, FEATURE_MASTER F
		WHERE F.feature_group = S.sub_group_id
		ORDER BY feature_id*/
		$keyArr[] = $this->featurekey."_featuresub_details";
		$whereClauseArr[] = "FEATURE_MASTER.feature_group = FEATURE_SUB_GROUP.sub_group_id";
		if(!empty($feature_name)){
			$keyArr[] = $feature_name;
			$whereClauseArr[] = "lower(FEATURE_MASTER.feature_name) = '$feature_name'";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "FEATURE_MASTER.category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "FEATURE_MASTER.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select sub_group_name, feature_id, feature_name from FEATURE_MASTER,FEATURE_SUB_GROUP $whereClauseStr order by FEATURE_MASTER.feature_id asc";

		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetPivotFeatureAndSubGroupDetails($feature_name="",$category_id="",$status="1"){
		/*SELECT sub_group_name, feature_id, feature_name
		FROM  `FEATURE_SUB_GROUP` S, FEATURE_MASTER F
		WHERE F.feature_group = S.sub_group_id
		ORDER BY feature_id*/
		$keyArr[] = $this->featurekey."_featuresub_details";
		$whereClauseArr[] = "FEATURE_MASTER.feature_group = FEATURE_SUB_GROUP.sub_group_id";
		$whereClauseArr[] = "FEATURE_MASTER.feature_id = PIVOT_MASTER.feature_id";
		if(!empty($feature_name)){
			$keyArr[] = $feature_name;
			$whereClauseArr[] = "lower(FEATURE_MASTER.feature_name) = '$feature_name'";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "FEATURE_MASTER.category_id = $category_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "FEATURE_MASTER.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		 $sql = "select sub_group_name, FEATURE_MASTER.feature_id, feature_name from FEATURE_MASTER,FEATURE_SUB_GROUP,PIVOT_MASTER $whereClauseStr order by FEATURE_MASTER.feature_id asc";
		
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}


}
