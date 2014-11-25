<?php
/**
 * @brief class is used to manage pivot information.
 * @author Rajesh Ujade
 * @version 1.0
 * @created 12-Nov-2010 2:23:04 PM
 */
class PivotManagement extends DbOperation
{
		var $cache;
		var $pivotKey;
		var $usedcarpivotKey;
		function PivotManagement(){
			$this->cache = new Cache;
			$this->pivotKey = MEMCACHE_MASTER_KEY."pivot::";
			$this->usedcarpivotKey = MEMCACHE_MASTER_KEY."usedcarpivot::";
		}
		/**
		* @note function is used to insert the pivot display details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post integer $display_id.
		* return integer.
		*/
		function intInsertPivotDisplayType($insert_param){
			//$result = $this->intMaxPivotDisplayOrder();
			//$insert_param['pivot_display_order'] = $result[0]['pivot_display_order'];
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getInsertSql("PIVOT_DISPLAY_TYPE",array_keys($insert_param),array_values($insert_param));
			$display_id = $this->insert($sql);
			if($display_id == 'Duplicate entry'){ return 'exists';}
			$this->cache->searchDeleteKeys($this->pivotKey."_display_type");
			$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
			$this->arrPivotDisplayDetails($display_id);
			return $display_id;
		}
		/**
		* @note function is used to update the pivot display details.
		* @param integer $pivot_display_id.
		* @param an associative array $update_param.
		* @pre $pivot_display_id must be valid non empty/zero integer value and $update_param must be valid associative array.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolUpdatePivotDisplayType($pivot_display_id,$update_param){
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("PIVOT_DISPLAY_TYPE",array_keys($update_param),array_values($update_param),"pivot_display_id",$pivot_display_id);
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->pivotKey."_display_type");
			$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
			$this->arrPivotDisplayDetails($pivot_display_id);
			return $isUpdate;
		}

		/**
		* @note function is use to delete the pivot display details.
		* @param integer $pivot_display_id.
		* @pre $pivot_display_id must be valid non empty/zero integer value.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeletePivotDisplayType($pivot_display_id){
			$sql = "delete from PIVOT_DISPLAY_TYPE where pivot_display_id = $pivot_display_id";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->pivotKey."_display_type");
			$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
			$this->arrPivotDisplayDetails($pivot_display_id);
			return $isDelete;
		}
		/**
		* @note function is used to get pivot display type details.
		* @param integer $pivot_display_id.
		* @pre $pivot_display_id must be valid non empty/zero integer value.
		* @post array of pivot display details.
		* return array.
		*/
		function arrPivotDisplayDetails($pivot_display_id="",$status="",$startlimit="",$cnt=""){
			$keyArr[] = $this->pivotKey."_arrPivotDisplayDetails";
			if(!empty($pivot_display_id)){
				$keyArr[] = $pivot_display_id;
				$whereClauseArr[] = "pivot_display_id in($pivot_display_id)";
			}else{$keyArr[] =-1;}
			if(!empty($status)){
				$keyArr[] = $status;
				$whereClauseArr[] = "status = $status";
			}else{$keyArr[] =-1;}
			if(!empty($startlimit)){
				$keyArr[] = $startlimit;
				$limitArr[] = $startlimit;
			}else{$keyArr[] =-1;}
			if(!empty($cnt)){
				$keyArr[] = $cnt;
				$limitArr[] = $cnt;
			}else{$keyArr[] =-1;}
			if(sizeof($limitArr) > 0){
				$limitStr = ' limit '.implode(",",$limitArr);
			}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			#echo $key."<br>";
			if($result = $this->cache->get($key)){return $result;}
			$sql = "select * from PIVOT_DISPLAY_TYPE $whereClauseStr order by pivot_display_name asc $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to insert the pivot details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post integer pivot id.
		* return integer.
		*/
		function intInsertPivotDetails($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getInsertSql("PIVOT_MASTER",array_keys($insert_param),array_values($insert_param));
			$pivot_id = $this->insert($sql);
			$this->cache->searchDeleteKeys($this->pivotKey);
			$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
			$this->arrGetPivotDetails($pivot_id);
			return $pivot_id;
		}
		/**
		* @note function is used to update the pivot details into the database.
		* @param an associative array $update_param.
		* @pre $update_param must be valid associative array.And $pivot_id must be valid,non-empty,non-zero integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolUpdatePivotDetails($pivot_id,$update_param){
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("PIVOT_MASTER",array_keys($update_param),array_values($update_param),"pivot_id",$pivot_id);
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->pivotKey);
			$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
			$this->arrGetPivotDetails($pivot_id);
			return $isUpdate;
		}
		/**
		* @note function is use to delete the pivot details.
		* @param integer $pivot_id.
		* @pre $pivot_id must be valid non empty/zero integer value.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeletePivotDetails($pivot_id){
			$sql = "delete from PIVOT_MASTER where pivot_id = $pivot_id";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->pivotKey);
			$this->cache->searchDeleteKeys(GET_ROUTER_BODY_STYLE_KEY);
			$this->arrGetPivotDetails($pivot_id);
			return $isDelete;
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
  		function intMaxPivotDisplayOrder($category_id="",$feature_id="",$pivot_id=""){
			$keyArr[] = $this->pivotKey."_intMaxPivotDisplayOrder";
		 	if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "category_id = $category_id";
			}else{$keyArr[] =-1;}
			if(!empty($feature_id)){
				$keyArr[] = $feature_id;
				$whereClauseArr[] = "feature_id = $feature_id";
			}else{$keyArr[] =-1;}
			if(!empty($pivot_id)){
				$keyArr[] = $pivot_id;
				$whereClauseArr[] = "pivot_id = $pivot_id";
			}else{$keyArr[] =-1;}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			$sql = "select max(pivot_display_order) as pivot_display_order from PIVOT_MASTER $whereClauseStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to get unique pivot group.
		* @pre not required.
		* @post an array of feature group.
		* return array.
		*/
		function arrGetPivotGroup(){
			$sql = "select distinct(pivot_group) as pivot_group from PIVOT_MASTER where pivot_group != '' and pivot_group != 'NULL' order by pivot_group asc";
			$key = $this->pivotKey."_unique_group";
			if($result = $this->cache->get($key)){return $result;}
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to get pivot details.
		* @pre not required.
		* @post array of pivot details.
		* return array.
		*/
		function arrGetPivotDetails($pivot_ids="",$category_id="",$feature_id="",$status="1",$pivot_group_id="",$startlimit="",$count=""){
			$keyArr[] = $this->pivotKey."_arrGetPivotDetails";
			if(is_array($pivot_ids)){
				$pivot_ids = implode(",",$pivot_ids);
	        }
        	if($status != ''){
				$keyArr[] = $status;
				$whereClauseArr[] = "status=$status";
        	}else{$keyArr[] =-1;}
            if(!empty($pivot_group_id)){
				$keyArr[] = $pivot_group_id;
				$whereClauseArr[] = "pivot_group in ($pivot_group_id)";
	        }else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "category_id in ($category_id)";
	        }else{$keyArr[] =-1;}
			if(!empty($pivot_ids)){
				$keyArr[] = $pivot_ids;
				$whereClauseArr[] = "pivot_id in ($pivot_ids)";
			}else{$keyArr[] =-1;}
        	if(!empty($feature_id)){
				$keyArr[] = $feature_id;
               $whereClauseArr[] = "feature_id in ($feature_id)";
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
			$sql = "select * from PIVOT_MASTER $whereClauseStr  order by pivot_display_order asc $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to insert pivot sub group details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post an integer $feature_group_id.
		* retun integer.
		*/
		function intInsertPivotSubGroupDetails($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getInsertSql("PIVOT_SUB_GROUP",array_keys($insert_param),array_values($insert_param));
			$feature_group_id = $this->insert($sql);
			if($feature_group_id == 'Duplicate entry'){ return 'exists';}
			$this->cache->searchDeleteKeys($this->pivotKey);
			$this->arrPivotSubGroupDetails($feature_group_id);
			return $feature_group_id;
		}
		/**
		* @note function is used to update pivot sub group details into the database.
		* @param an associative array $update_param.
		* @param an integer $sub_group_id.
		* @pre $update_param must be valid associative array.
		* @post an integer id.
		* retun integer.
		*/
		function boolUpdatePivotSubGroupDetails($sub_group_id,$update_param){
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("PIVOT_SUB_GROUP",array_keys($update_param),array_values($update_param),"sub_group_id",$sub_group_id);
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->pivotKey);
			$this->arrPivotSubGroupDetails($sub_group_id);
			return $isUpdate;
		}
		/**
		* @note function is used to delete pivot sub group details.
		* @param integer $sub_group_id.
		* @pre $sub_group_id must be non-empty/zero valid integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeletePivotSubGroupDetails($sub_group_id){
			$sql = "delete from PIVOT_SUB_GROUP where sub_group_id = $sub_group_id";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->pivotKey);
			$this->arrPivotSubGroupDetails($sub_group_id);
			return $isDelete;
		}
		/**
		* @note function is used to get pivot sub group details
		*
		* @param an integer $sub_group_id.
		* @param an integer $category_id.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $cnt.
		*
		* @pre not required.
		*
		* @post pivot sub group details in associative array.
		* retun an array.
		*/
		function arrPivotSubGroupDetails($sub_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
			$keyArr[] = $this->pivotKey."_arrPivotSubGroupDetails";
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
			$sql = "select * from PIVOT_SUB_GROUP $whereClauseStr order by sub_group_position asc $limitStr ";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}

		/**
		* @note function is used to insert the pivot display details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post integer $display_id.
		* return integer.
		*/
		function intInsertUsedCarPivotDisplayType($insert_param){
			//$result = $this->intMaxPivotDisplayOrder();
			//$insert_param['pivot_display_order'] = $result[0]['pivot_display_order'];
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getInsertSql("USEDCAR_PIVOT_DISPLAY_TYPE",array_keys($insert_param),array_values($insert_param));
			$display_id = $this->insert($sql);
			if($display_id == 'Duplicate entry'){ return 'exists';}
			$this->cache->searchDeleteKeys($this->usedcarpivotKey."_display_type");
			$this->arrPivotDisplayDetails($display_id);
			return $display_id;
		}
		/**
		* @note function is used to update the pivot display details.
		* @param integer $pivot_display_id.
		* @param an associative array $update_param.
		* @pre $pivot_display_id must be valid non empty/zero integer value and $update_param must be valid associative array.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolUpdateUsedCarPivotDisplayType($pivot_display_id,$update_param){
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("USEDCAR_PIVOT_DISPLAY_TYPE",array_keys($update_param),array_values($update_param),"pivot_display_id",$pivot_display_id);
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->usedcarpivotKey."_display_type");
			$this->arrPivotDisplayDetails($pivot_display_id);
			return $isUpdate;
		}

		/**
		* @note function is use to delete the pivot display details.
		* @param integer $pivot_display_id.
		* @pre $pivot_display_id must be valid non empty/zero integer value.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteUsedCarPivotDisplayType($pivot_display_id){
			$sql = "delete from USEDCAR_PIVOT_DISPLAY_TYPE where pivot_display_id = $pivot_display_id";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->usedcarpivotKey."_display_type");
			$this->arrPivotDisplayDetails($pivot_display_id);
			return $isDelete;
		}
		/**
		* @note function is used to get pivot display type details.
		* @param integer $pivot_display_id.
		* @pre $pivot_display_id must be valid non empty/zero integer value.
		* @post array of pivot display details.
		* return array.
		*/
		function arrUsedCarPivotDisplayDetails($pivot_display_id="",$status="",$startlimit="",$cnt=""){
			$keyArr[] = $this->usedcarpivotKey."_arrUsedCarPivotDisplayDetails";
			if(!empty($pivot_display_id)){
				$keyArr[] = $pivot_display_id;
				$whereClauseArr[] = "pivot_display_id in($pivot_display_id)";
			}else{$keyArr[] =-1;}
			if(!empty($status)){
				$keyArr[] = $status;
				$whereClauseArr[] = "status = $status";
			}else{$keyArr[] =-1;}
			if(!empty($startlimit)){
				$keyArr[] = $startlimit;
				$limitArr[] = $startlimit;
			}else{$keyArr[] =-1;}
			if(!empty($cnt)){
				$keyArr[] = $cnt;
				$limitArr[] = $cnt;
			}else{$keyArr[] =-1;}
			if(sizeof($limitArr) > 0){
				$limitStr = ' limit '.implode(",",$limitArr);
			}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			$sql = "select * from USEDCAR_PIVOT_DISPLAY_TYPE $whereClauseStr order by pivot_display_name asc $limitStr";
    		$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to insert pivot sub group details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post an integer $feature_group_id.
		* retun integer.
		*/
		function intInsertUsedCarPivotSubGroupDetails($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getInsertSql("USEDCAR_PIVOT_SUB_GROUP",array_keys($insert_param),array_values($insert_param));
			$feature_group_id = $this->insert($sql);
			if($feature_group_id == 'Duplicate entry'){ return 'exists';}
			$this->cache->searchDeleteKeys($this->usedcarpivotKey);
			$this->arrPivotSubGroupDetails($feature_group_id);
			return $feature_group_id;
		}
		/**
		* @note function is used to update pivot sub group details into the database.
		* @param an associative array $update_param.
		* @param an integer $sub_group_id.
		* @pre $update_param must be valid associative array.
		* @post an integer id.
		* retun integer.
		*/
		function boolUpdateUsedCarPivotSubGroupDetails($sub_group_id,$update_param){
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("USEDCAR_PIVOT_SUB_GROUP",array_keys($update_param),array_values($update_param),"sub_group_id",$sub_group_id);
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->usedcarpivotKey);
			$this->arrPivotSubGroupDetails($sub_group_id);
			return $isUpdate;
		}
		/**
		* @note function is used to delete pivot sub group details.
		* @param integer $sub_group_id.
		* @pre $sub_group_id must be non-empty/zero valid integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteUsedCarPivotSubGroupDetails($sub_group_id){
			$sql = "delete from USEDCAR_PIVOT_SUB_GROUP where sub_group_id = $sub_group_id";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->usedcarpivotKey);
			$this->arrPivotSubGroupDetails($sub_group_id);
			return $isDelete;
		}
		/**
		* @note function is used to get pivot sub group details
		*
		* @param an integer $sub_group_id.
		* @param an integer $category_id.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $cnt.
		*
		* @pre not required.
		*
		* @post pivot sub group details in associative array.
		* retun an array.
		*/
		function arrUsedCarPivotSubGroupDetails($sub_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
			$keyArr[] = $this->usedcarpivotKey."_arrUsedCarPivotSubGroupDetails";
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
			$sql = "select * from USEDCAR_PIVOT_SUB_GROUP $whereClauseStr order by sub_group_position asc $limitStr ";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to insert the pivot details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post integer pivot id.
		* return integer.
		*/
		function intInsertUsedCarPivotDetails($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getInsertSql("USEDCAR_PIVOT_MASTER",array_keys($insert_param),array_values($insert_param));
			$pivot_id = $this->insert($sql);
			if($pivot_id == 'Duplicate entry'){ return 'exists';}
			$this->cache->searchDeleteKeys($this->usedcarpivotKey);
			$this->arrGetPivotDetails($pivot_id);
			return $pivot_id;
		}
		/**
		* @note function is used to update the pivot details into the database.
		* @param an associative array $update_param.
		* @pre $update_param must be valid associative array.And $pivot_id must be valid,non-empty,non-zero integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolUpdateUsedCarPivotDetails($pivot_id,$update_param){
			$update_param['create_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("USEDCAR_PIVOT_MASTER",array_keys($update_param),array_values($update_param),"pivot_id",$pivot_id);
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->usedcarpivotKey);
			$this->arrGetPivotDetails($pivot_id);
			return $isUpdate;
		}
		/**
		* @note function is use to delete the pivot details.
		* @param integer $pivot_id.
		* @pre $pivot_id must be valid non empty/zero integer value.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteUsedCarPivotDetails($pivot_id){
			$sql = "delete from USEDCAR_PIVOT_MASTER where pivot_id = $pivot_id";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->usedcarpivotKey);
			$this->arrGetPivotDetails($pivot_id);
			return $isDelete;
		}
		/**
		* @note function is used to get unique pivot group.
		* @pre not required.
		* @post an array of feature group.
		* return array.
		*/
		function arrGetUsedCarPivotGroup(){
			$sql = "select distinct(pivot_group) as pivot_group from USEDCAR_PIVOT_MASTER where pivot_group != '' and pivot_group != 'NULL' order by pivot_group asc";
			$key = $this->usedcarpivotKey."_unique_group";
			if($result = $this->cache->get($key)){return $result;}
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to get pivot details.
		* @pre not required.
		* @post array of pivot details.
		* return array.
		*/
		function arrGetUsedCarPivotDetails($pivot_ids="",$category_id="",$feature_id="",$status="1",$pivot_group_id="",$startlimit="",$count=""){
			$keyArr[] = $this->usedcarpivotKey."_arrGetUsedCarPivotDetails";
			if(is_array($pivot_ids)){
				$pivot_ids = implode(",",$pivot_ids);
	        }
        	if($status != ''){
				$keyArr[] = $status;
				$whereClauseArr[] = "status=$status";
        	}else{$keyArr[] =-1;}
            if(!empty($pivot_group_id)){
				$keyArr[] = $pivot_group_id;
				$whereClauseArr[] = "pivot_group in ($pivot_group_id)";
	        }else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "category_id in ($category_id)";
	        }else{$keyArr[] =-1;}
			if(!empty($pivot_ids)){
				$keyArr[] = $pivot_ids;
				$whereClauseArr[] = "pivot_id in ($pivot_ids)";
			}else{$keyArr[] =-1;}
        	if(!empty($feature_id)){
				$keyArr[] = $feature_id;
               $whereClauseArr[] = "feature_id in ($feature_id)";
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
			$sql = "select * from USEDCAR_PIVOT_MASTER $whereClauseStr  order by pivot_display_order asc $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to get pivot details.
		* @pre not required.
		* @post array of pivot details.
		* return array.
		*/
		function arrGetUsedCarPivotDetailsCount($pivot_ids="",$category_id="",$feature_id="",$status="1",$pivot_group_id="",$startlimit="",$count=""){
			$keyArr[] = $this->usedcarpivotKey.'_arrGetUsedCarPivotDetailsCount';
			if(is_array($pivot_ids)){
				$pivot_ids = implode(",",$pivot_ids);
	        }
        	if($status != ''){
				$keyArr[] = $status;
				$whereClauseArr[] = "status=$status";
        	}else{$keyArr[] =-1;}
            if(!empty($pivot_group_id)){
				$keyArr[] = $pivot_group_id;
				$whereClauseArr[] = "pivot_group in ($pivot_group_id)";
	        }else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "category_id in ($category_id)";
	        }else{$keyArr[] =-1;}
			if(!empty($pivot_ids)){
				$keyArr[] = $pivot_ids;
				$whereClauseArr[] = "pivot_id in ($pivot_ids)";
			}else{$keyArr[] =-1;}
        	if(!empty($feature_id)){
				$keyArr[] = $feature_id;
               $whereClauseArr[] = "feature_id in ($feature_id)";
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
			$sql = "select count(pivot_id) as cnt from USEDCAR_PIVOT_MASTER $whereClauseStr  order by pivot_display_order asc $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
}
