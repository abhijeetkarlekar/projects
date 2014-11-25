<?php
/**
 * @brief class is used to perform actions on brand details.
 * @author Rajesh Ujade
 * @version 1.0
 * @created 11-Nov-2010 5:09:31 PM
 * @last updated on 08-Mar-2011 15:05:00 PM
 */
 require_once(CLASSPATH.'product.class.php');
class BrandManagement extends DbOperation
{

	var $cache;
	var $categoryid;
	var $brandkey;
	var $usedbrandkey;
	var $brand_position;
	/**Initialize the consturctor.*/
	function BrandManagement(){
		$this->cache = new Cache;
		$this->oProduct = new ProductManagement;
		$this->brandkey = MEMCACHE_MASTER_KEY."brand::";
		$this->usedbrandkey = MEMCACHE_MASTER_KEY."usedbrand::";
		$this->brand_position = MEMCACHE_MASTER_KEY."brandposition::";
	}

	/**
	 * @note function is used to insert the brand details into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $brand_id.
	 * retun integer.
	 */
	function intInsertBrand($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("BRAND_MASTER",array_keys($insert_param),array_values($insert_param));
		$brand_id = $this->insert($sql);
		if($insert_param['discontinue_flag'] == "0"){
			unset($update_param);
			$update_param['discontinue_flag'] = "0";
			$update_param['discontinue_date'] = $insert_param['discontinue_date'];
		}else{
			unset($update_param);
			$update_param['discontinue_flag'] = "1";
			$update_param['discontinue_date'] = "0000-00-00 00:00:00";
		}
		#$this->oProduct->boolUpdateProductNameField('brand_id',$brand_id,$update_param);
		#$this->oProduct->boolUpdateProductField('brand_id',$brand_id,$update_param);
		$this->cache->searchDeleteKeys($this->brandkey);
		$this->cache->searchDeleteKeys(GET_ROUTER_BRAND_KEY);
		if($brand_id == 'Duplicate entry'){ return 'exists';}
		return $brand_id;
	}
	/**
	 * @note function is used to update the brand details into the database.
	 * @param an associative array $update_param.
	 * @param an integer $brand_id.
	 * @pre $update_param must be valid associative array and $brand_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateBrand($brand_id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("BRAND_MASTER",array_keys($update_param),array_values($update_param),"brand_id",$brand_id);
		$isUpdate = $this->update($sql);
		if($update_param['discontinue_flag'] == "0"){
				unset($uparam);
				$uparam['discontinue_flag'] = "0";
				$uparam['discontinue_date'] = $update_param['discontinue_date'];
		}else{
				unset($uparam);
				$uparam['discontinue_flag'] = "1";
				$uparam['discontinue_date'] = "0000-00-00 00:00:00";
		}
		#$this->oProduct->boolUpdateProductNameField('brand_id',$brand_id,$uparam);
		#$this->oProduct->boolUpdateProductField('brand_id',$brand_id,$uparam);
		$this->cache->searchDeleteKeys($this->brandkey);
		$this->cache->searchDeleteKeys(GET_ROUTER_BRAND_KEY);
		return $isUpdate;
	 }

	 /**
		* @note function is used to get brand details.
		* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
		* @param an integer/comma separated category_id $category_id.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $count.
		* @pre not required.
		* @post brand details in associative array.
		* retun an array.
		*/
		function arrGetBrandDetailsCount($brand_ids="",$category_id="",$status="1",$startlimit="",$count="",$brand_name="",$orderby="",$discontinue_flag="",$upcoming_brand="")
		{
			$keyArr[] = $this->brandkey.'_arrGetBrandDetailsCount';
			if(is_array($brand_ids)){
				$brand_ids = implode(",",$brand_ids);
			}
			if($status != ''){
				$whereClauseArr[] = "status=$status";
				$keyArr[] = $status;
			}else{$keyArr[] = -1;}
			if(!empty($brand_ids)){
				$whereClauseArr[] = "brand_id in($brand_ids)";
				$keyArr[] = $brand_ids;
			}else{$keyArr[] = -1;}
			if(!empty($category_id)){
				$whereClauseArr[] = "category_id in ($category_id)";
				$keyArr[] = $category_id;
			}else{$keyArr[] = -1;}
			if(!empty($brand_name)){
				$brand_name = strtolower($brand_name);
				$whereClauseArr[] = "lower(brand_name)= '$brand_name'";
				$keyArr[] = $brand_name;
			}else{$keyArr[] = -1;}
			if($discontinue_flag != ''){
					$whereClauseArr[] = "discontinue_flag=$discontinue_flag";
					$keyArr[] = $discontinue_flag;
			}else{$keyArr[] = -1;}
			if($upcoming_brand != ''){
				$whereClauseArr[] = "upcoming_brand = $upcoming_brand";
				$keyArr[] = $upcoming_brand;
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
				$orderby = "order by brand_name asc";
			}
			$key = implode('_',$keys);
			$result = $this->cache->get($key);
			if(!empty($result)){ return $result;}
			$sql = "select count(brand_id) as cnt from BRAND_MASTER $whereClauseStr $orderby $limitStr";
			$result = $this->select($sql);

			$this->cache->set($key, $result);
			return $result;
		}
	 /**
	 * @note function is used to get brand details.
	 * @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	 * @param an integer/comma separated category_id $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post brand details in associative array.
	 * retun an array.
	 */
	 function arrGetBrandDetails($brand_ids="",$category_id="",$status="1",$startlimit="",$count="",$brand_name="",$orderby="",$discontinue_flag="",$upcoming_brand="0")
	 {
  		$keyArr[] = $this->brandkey.'_arrGetBrandDetails';
	 	if(is_array($brand_ids)){
	 		$brand_ids = implode(",",$brand_ids);
	 	}
	 	if($status != ''){
	 		$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
	 	}else{$keyArr[] = -1;}
	 	if(!empty($brand_ids)){
	 		$whereClauseArr[] = "brand_id in($brand_ids)";
			$keyArr[] = $brand_ids;
	 	}else{$keyArr[] = -1;}
	 	if(!empty($category_id)){
	 		$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
	 	}else{$keyArr[] = -1;}
		if(!empty($brand_name)){
			$brand_name = strtolower($brand_name);
	 		$whereClauseArr[] = "lower(brand_name)= '$brand_name'";
			$keyArr[] = $brand_name;
	 	}else{$keyArr[] = -1;}
		if($discontinue_flag != ''){
			$whereClauseArr[] = "discontinue_flag=$discontinue_flag";
			$keyArr[] = $discontinue_flag;
		}else{$keyArr[] = -1;}
		if($upcoming_brand!=''){
			$whereClauseArr[] = "upcoming_brand = $upcoming_brand";
			$keyArr[] = $upcoming_brand;
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
		$strOrderBY = '';
		if($orderby=='none'){
		}else if(empty($orderby)){
			$strOrderBY = "order by brand_name asc";
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result; }
		$sql = "select * from BRAND_MASTER $whereClauseStr $strOrderBY $limitStr";
		#echo "SQL->".$sql;
		#error_log("SQL->".$sql);
		$result = $this->select($sql);
		$this->cache->set($key, $result);
	 	return $result;
	 }

	function arrGetBrandName($category_id,$brand_name){
		$key = $this->brandkey."arrGetBrandName_$category_id_$brand_name";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		unset($brandArr);
		$brand_name = urldecode($brand_name);
		$brand_name = trim($brand_name);
		$brandArr[] = "seo_path = '$brand_name'";
		$brand_name1 = str_replace(array('-',' - '),' ',$brand_name);
		$brandArr[] = "seo_path = '$brand_name1'";
		$brandArr = array_unique($brandArr);
		$brand = implode(' or ',$brandArr);
		unset($brandArr);
		$sql = "select * from BRAND_MASTER where category_id =$category_id and ($brand)";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}


	 /**
	 * @note function is used to delete the brand.
	 * @param integer $brand_id.
	 * @pre $brand_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteBrand($brand_id){
	 	$sql = "delete from BRAND_MASTER where brand_id = $brand_id";
	 	$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->brandkey);
	 	return $isDelete;
	 }

	/**
	 * @note function is used to insert the popular brand details into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $popular_id.
	 * retun integer.
	 */
	function intInsertPopularBrand($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("POPULAR_BRAND",array_keys($insert_param),array_values($insert_param));
		$brand_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->brandkey);
		if($brand_id == 'Duplicate entry'){ return 'exists';}
		return $brand_id;
	}
	 /**
	 * @note function is used to update the popular brand details into the database.
	 * @param an associative array $update_param.
	 * @param an integer $popular_id.
	 * @pre $update_param must be valid associative array and $popular_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdatePopularBrand($popular_id,$update_param){
		 $update_param['create_date'] = date('Y-m-d H:i:s');
		 $update_param['update_date'] = date('Y-m-d H:i:s');
		 $sql = $this->getUpdateSql("POPULAR_BRAND",array_keys($update_param),array_values($update_param),"popular_id",$popular_id);
		 //echo $sql;
		 $isUpdate = $this->update($sql);
		 $this->cache->searchDeleteKeys($this->brandkey);
		 return $isUpdate;
	 }
	/**
	 * @note function is used to delete the popular brand.
	 * @param integer $popular_id.
	 * @pre $popular_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeletePopularBrand($popular_id){
		$sql = "delete from POPULAR_BRAND where popular_id = $popular_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->brandkey);
		return $isDelete;
	 }

	/**
	 * @note function is used to get popular brand details.
	 * @param an integer/comma seperated popular ids/ popular ids array $popular_ids.
	 * @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	 * @param an integer/comma seperated popular model ids/ popular model ids array $popular_model_ids.
	 * @param an integer/comma separated category_id $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post popular brand details in associative array.
	 * retun an array.
	 */
	function arrGetPopularBrandDetails($popular_ids="", $brand_ids="", $popular_model_ids="", $category_ids="", $status="1",$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->brandkey."_arrGetPopularBrandDetails";
		if(is_array($popular_ids)){
			$popular_ids = implode(",",$popular_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($popular_model_ids)){
		   $popular_model_ids = implode(",",$popular_model_ids);
		}
		if(is_array($category_ids)){
		   $category_ids = implode(",",$category_ids);
		}
		if(!empty($popular_ids)){
		   $whereClauseArr[] = "popular_id in($popular_ids)";
			$keyArr[] = $popular_ids;
		}else{$keyArr[] = -1;}
		if(!empty($brand_ids)){
		   $whereClauseArr[] = "brand_id in($brand_ids)";
		   $keyArr[] = $brand_ids;
		}else{$keyArr[] = -1;}
		if(!empty($popular_model_ids)){
			$whereClauseArr[] = "popular_model_id in($popular_model_ids)";
			$keyArr[] = $popular_model_ids;
		}else{$keyArr[] = -1;}
		if(!empty($category_ids)){
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
			$orderby = "order by create_date desc";
			$keyArr[] = " order_".str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keys);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from POPULAR_BRAND $whereClauseStr $orderby $limitStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	 }

	/**
	 * @note function is used to get popular brand details count.
	 * @param an integer/comma seperated popular ids/ popular ids array $popular_ids.
	 * @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	 * @param an integer/comma seperated popular model ids/ popular model ids array $popular_model_ids.
	 * @param an integer/comma separated category_id $category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post integer count.
	 * retun an array.
	 */
	 function arrGetPopularBrandCount($popular_ids="", $brand_ids="", $popular_model_ids="", $category_ids="", $status="1") {
		$keyArr[] = $this->brandkey."_arrGetPopularBrandCount";
		if(is_array($popular_ids)){
			$popular_ids = implode(",",$popular_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($popular_model_ids)){
			$popular_model_ids = implode(",",$popular_model_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(!empty($popular_ids)){
			$whereClauseArr[] = "popular_id in($popular_ids)";
			$keyArr[] = $popular_ids;
		}else{$keyArr[] = -1;}
		if(!empty($brand_ids)){
			$whereClauseArr[] = "brand_id in($brand_ids)";
			$keyArr[] = $brand_ids;
		}else{$keyArr[] = -1;}
		if(!empty($popular_model_ids)){
			$whereClauseArr[] = "popular_model_id in($popular_model_ids)";
			$keyArr[] = $popular_model_ids;
		}else{$keyArr[] = -1;}
		if(!empty($category_ids)){
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
		$key = implode('_',$keys);
		$result = $this->cache->get($key);
		if(!empty($result)){return $result;}
		$sql = "select count(popular_id) as cnt from POPULAR_BRAND $whereClauseStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to insert the brand details into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $brand_id.
	* retun integer.
	*/
	function intInsertUsedCarBrand($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USEDCAR_BRAND_MASTER",array_keys($insert_param),array_values($insert_param));
		$used_brand_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->usedbrandkey);
		if($used_brand_id == 'Duplicate entry'){ return 'exists';}
		return $used_brand_id;
	}
	/**
	* @note function is used to update the brand details into the database.
	* @param an associative array $update_param.
	* @param an integer $brand_id.
	* @pre $update_param must be valid associative array and $brand_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* retun boolean.
	*/
	function boolUpdateUsedCarBrand($used_brand_id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USEDCAR_BRAND_MASTER",array_keys($update_param),array_values($update_param),"used_brand_id",$used_brand_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->usedbrandkey);
		return $isUpdate;
	}

	/**
	* @note function is used to get brand details.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param an integer/comma separated category_id $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post brand details in associative array.
	* retun an array.
	*/
	function arrGetUsedCarBrandDetails($request_param){

		list($used_brand_ids,$brand_ids,$category_id,$status,$startlimit,$count,$brand_name,$orderby,$year,$startyear,$endyear) = array($request_param['used_brand_ids'],$request_param['brand_ids'],$request_param['category_id'],$request_param['status'],$request_param['startlimit'],$request_param['count'],$request_param['brand_name'],$request_param['orderby'],$request_param['year'],$request_param['startyear'],$request_param['endyear']);

		$keyArr[] = $this->usedbrandkey.'_arrGetUsedCarBrandDetails';
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(!empty($startyear) && !empty($endyear) && empty($year)){
			$whereClauseArr[] = "year >= '$startyear'";
			$whereClauseArr[] = "year <= '$endyear'";
			$keyArr[] = $startyear;
			$keyArr[] = $endyear;
		}else{$keyArr[] = '-1_-1';}
		if(!empty($year)){
			$whereClauseArr[] = "year in($year)";
			$keyArr[] = $year;
		}else{$keyArr[] = -1;}
		if(!empty($brand_ids)){
			$whereClauseArr[] = "brand_id in($brand_ids)";
			$keyArr[] = $brand_ids;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}

		if(is_array($used_brand_ids)){
			$used_brand_ids = implode(",",$used_brand_ids);
		}
		if(!empty($used_brand_ids)){
			$whereClauseArr[] = "used_brand_id in($used_brand_ids)";
			$keyArr[] = $used_brand_ids;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($brand_name)){
			$brand_name = strtolower($brand_name);
			$whereClauseArr[] = "lower(brand_name)= '$brand_name'";
			$keyArr[] = $brand_name;
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
			$orderby = "order by brand_name asc";
		}
		$keyArr[] = $orderby;
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from USEDCAR_BRAND_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used to get brand details.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param an integer/comma separated category_id $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post brand details in associative array.
	* retun an array.
	*/
	function arrGetUsedCarBrandDetailsCount($request_param)
	{
		list($used_brand_ids,$brand_ids,$category_id,$status,$startlimit,$count,$brand_name,$orderby,$year,$startyear,$endyear) = array($request_param['used_brand_ids'],$request_param['brand_ids'],$request_param['category_id'],$request_param['status'],$request_param['startlimit'],$request_param['count'],$request_param['brand_name'],$request_param['orderby'],$request_param['year'],$request_param['startyear'],$request_param['endyear']);

		$keyArr[] = $this->usedbrandkey.'arrGetUsedCarBrandDetailsCount';
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($startyear) && !empty($endyear) && empty($year)){
			$whereClauseArr[] = "year >= '$startyear'";
			$whereClauseArr[] = "year <= '$endyear'";
			$keyArr[] = $startyear;
			$keyArr[] = $endyear;
		}else{$keyArr[] = '-1_-1';}
		if(!empty($year)){
			$whereClauseArr[] = "year in($year)";
			$keyArr[] = $year;
		}else{$keyArr[] = -1;}
		if(!empty($brand_ids)){
			$whereClauseArr[] = "brand_id in($brand_ids)";
			$keyArr[] = $brand_ids;
		}else{$keyArr[] = -1;}
		if(is_array($used_brand_ids)){
			$used_brand_ids = implode(",",$used_brand_ids);
		}else{$keyArr[] = -1;}
		if(!empty($used_brand_ids)){
			$whereClauseArr[] = "used_brand_id in($used_brand_ids)";
			$keyArr[] = $used_brand_ids;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($brand_name)){
			$brand_name = strtolower($brand_name);
			$whereClauseArr[] = "lower(brand_name)= '$brand_name'";
			$keyArr[] = $brand_name;
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
			$orderby = "order by brand_name asc";
		}
		$key = implode('_',$keys);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select count(brand_id) as cnt from USEDCAR_BRAND_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used to delete the brand.
	* @param integer $brand_id.
	* @pre $brand_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteUsedCarBrand($brand_id){
		$sql = "delete from USEDCAR_BRAND_MASTER where used_brand_id = $brand_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->usedbrandkey);
		return $isDelete;
	}
	/**
	* @note function is used to insert the brand position information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $id.
	* retun integer.
	*/
	function intInsertBrandPosition($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("BRAND_POSITION",array_keys($insert_param),array_values($insert_param));
		$id = $this->insert($sql);
		if($id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->brand_position);
		return $id;
	}
	/**
	* @note function is used to update the brand position into the database.
	* @param an associative array $update_param.
	* @param an integer $id.
	* @pre $update_param must be valid associative array and $id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* retun boolean.
	*/
	function boolUpdateBrandPosition($id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("BRAND_POSITION",array_keys($update_param),array_values($update_param),"id",$id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->brand_position);
		return $isUpdate;
	}
	/**
	* @note function is used to delete the brand position.
	* @param integer $id.
	* @pre $id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteBrandPosition($id){
		$sql = "delete from BRAND_POSITION where id = $id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->brand_position);
		return $isDelete;
	}
	/**
	* @note function is used to get brand position details count.
	* @pre not required.
	* @param an integer/comma seperated ids array $ids.
	* @param an integer/comma seperated category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param an integer position $position
	* @param is a boolean value $status.
	* @param is an integer value $startlimit.
	* @param is an integer value $cnt.
	* @param is a string $orderby.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetBrandPositionDetailsCount($ids="",$category_id="",$brand_id="",$position="",$status='1',$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->brand_position."_arrGetBrandPositionDetailsCount";
		$tablenameArr[] = "BRAND_POSITION";
		if(is_array($ids)){	$ids = implode(",",$ids);}
		if(!empty($ids)){
			$keyArr[] = $ids;
			$whereClauseArr[] = "id in($ids)";
		}else{$keyArr[] = -1;}
		if(is_array($category_id)){$category_id = implode(",",$category_id);}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(is_array($brand_id)){$brand_id = implode(",",$brand_id);}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in($brand_id)";
		}else{$keyArr[] = -1;}
		if($position != ''){
			$keyArr[] = $position;
			$whereClauseArr[] = "position=$position";
		}else{$keyArr[] = -1;}
		if($status != ''){
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
		if(sizeof($limitArr) > 0){$limitStr = " limit ".implode(" , ",$limitArr);}
		if(!empty($orderby)){$orderby = $orderby;}
		$keyArr[] = $orderby;
		$key = implode("_",$keyArr);
		if($result = $this->cache->get_memcache($key)){return $result;}
		$tableStr = implode(",",$tablenameArr);
		$sql = "select count(BRAND_POSITION.id) as cnt from $tableStr $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set_memcache($key,$result);
		return $result;
	}
	/**
	* @note function is used to get brand position details.
	* @pre not required.
	* @param an integer/comma seperated ids array $ids.
	* @param an integer/comma seperated category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param an integer position $position
	* @param is a boolean value $status.
	* @param is an integer value $startlimit.
	* @param is an integer value $cnt.
	* @param is a string $orderby.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetBrandPositionDetails($ids="",$category_id="",$brand_id="",$position="",$status='1',$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->brand_position."_arrGetBrandPositionDetails";
		$tablenameArr[] = "BRAND_POSITION";
		if(is_array($ids)){	$ids = implode(",",$ids);}
		if(!empty($ids)){
			$keyArr[] = $ids;
			$whereClauseArr[] = "id in($ids)";
		}else{$keyArr[] = -1;}
		if(is_array($category_id)){	$category_id = implode(",",$category_id);}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(is_array($brand_id)){$brand_id = implode(",",$brand_id);}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in($brand_id)";
		}else{$keyArr[] = -1;}
		if($position != ''){
			$keyArr[] = $position;
			$whereClauseArr[] = "position=$position";
		}else{$keyArr[] = -1;}
		if($status != ''){
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
		if(!empty($orderby)) {
			$orderby = $orderby;
		}else{
			$orderby = "order by position asc";
		}
		$keyArr[] = $orderby;
		$key = implode("_",$keyArr);
		if($result = $this->cache->get_memcache($key)){return $result;}
		$tableStr = implode(",",$tablenameArr);
		$sql = "select * from $tableStr $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set_memcache($key,$result);
		return $result;
	}
	function arrGetBrandByName($brand_name){
		$key = $this->brandkey."_arrGetBrandByName_$brand_name";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		unset($brandArr);
		$brand_name = urldecode($brand_name);
		$brand_name = trim($brand_name);
		$brandArr[] = $brand_name;
		$brand_name1 = str_replace(array('-',' - '),' ',$brand_name);
		$brandArr[] = $brand_name1;
		$brandArr = array_unique($brandArr);
		$brand_name = implode(' or ',$brandArr);
		unset($brandArr);
		$sql = "select * from BRAND_MASTER where $brand_name";
		$result = $this->select($sql);
		$this->cache->set_memcache($key,$result);
		return $result;
	}
	function arrGetUsedBrandByName($arr_brand_name){
		$brand_name = $arr_brand_name[0];
                $key = $this->brandkey."_arrGetUsedBrandByName_$brand_name";
                $result = $this->cache->get($key);
                if(!empty($result)){ return $result;}
                unset($brandArr);
                $brand_name = urldecode($brand_name);
                $brand_name = trim($brand_name);
                $brandArr[] = $brand_name;
                $brand_name1 = str_replace(array('-',' - '),' ',$brand_name);
                $brandArr[] = $brand_name1;
                $brandArr = array_unique($brandArr);
                $brand_name = implode(' or ',$brandArr);
                unset($brandArr);
                $sql = "select * from USEDCAR_BRAND_MASTER where $brand_name";
                $result = $this->select($sql);
                $this->cache->set_memcache($key,$result);
                return $result;
        }

/*
	function arrGetLikeBrandName($select_param){
		list($brand_name) = array($select_param['brand_name']);
		if(!empty($brand_name)){
			$arrWhereClause[] = "brand_name like ('$brand_name%')";
		}
		if(count($arrWhereClause)>0){
			$strWhereClause = " where ".implode(' and ',$arrWhereClause);
		}
		$sql = "SELECT brand_id, brand_name FROM BRAND_MASTER ".$strWhereClause;
		$result = $this->select($sql,$dbconn);
		return $result;
	}
*/
}
