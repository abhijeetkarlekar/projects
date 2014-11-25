<?php
/**
 * @brief class is used add,update,delete,get product details.
 * @author Rajesh Ujade
 * @version 1.0
 * @created 11-Nov-2010 5:09:31 PM
 */
class ProductManagement extends DbOperation
{
	var $newPhoneTypeArr;
	var $newAvailabilityArr;
	var $newFormFactorArr;
	var $newInputMechanismArr;
	var $newRAMArr;
	var $newFeaturesArr;
	var $newNetworkTypeArr;
	var $newNoofSIMArr;
	var $newNetworkArr;
	var $newPrimaryCameraArr;
	var $newOperatingSystemArr;
	var $newProcessorNoofcoresArr;
	var $newScreenSizeArr;
	var $newAnnouncedArr;

	var $cache;
	var $productKey;
	var $usedproductKey;
	/**Intialize the consturctor.*/
	function ProductManagement(){
		$this->cache = new Cache;
		$this->productKey = MEMCACHE_MASTER_KEY."product::";
		$this->productFeatureKey = MEMCACHE_MASTER_KEY."product_featured::";
		$this->featurekey = MEMCACHE_MASTER_KEY."product_feature::";
		$this->compareKey = MEMCACHE_MASTER_KEY."product_compare::";
		$this->usedproductKey = MEMCACHE_MASTER_KEY."used_product::";
		$this->upcoming_product_Key = MEMCACHE_MASTER_KEY.'product_upcoming::';
	}
	/**
	 * @note function is used to insert the product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertProduct($insert_param)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("PRODUCT_MASTER",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $product_id;
	}
	/**
	 * @note function is used to insert the product Feature information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertProductFeature($insert_param)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("PRODUCT_FEATURE",array_keys($insert_param),array_values($insert_param));
		//echo $sql."<br>";
		$feature_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->productKey."_feature");
		return $feature_id;
	}

	function intInsertProductFeatureData($sql)
        {
               // $insert_param['create_date'] = date('Y-m-d H:i:s');
               // $sql = $this->getInsertUpdateSql("PRODUCT_FEATURE",array_keys($insert_param),array_values($insert_param));
               // echo $sql."<br>";
                $feature_id = $this->insertSelect($sql);
                $this->cache->searchDeleteKeys($this->productKey."_feature");
                return $feature_id;
        }

	/**
	* @note function is used to update the product name in the database.
	* @param product_info_name is a string name
	* @param old_product_name is a string name
	* @param status is a boolean value 0,1 or null
	* @post an integer product_id.
	* retun integer.
	*/
	 function boolUdateProductName($product_info_name,$old_product_name,$status=""){
			$str="";
			if($status != ""){$str = " , status='$status'";}
			$sql = "update PRODUCT_MASTER set product_name = '$product_info_name'".$str." where product_name = '$old_product_name'";
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->productKey);
			$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
	                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
			$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
			return $isUpdate;
	 }

	 function updateDiscontinueDate($insert_param,$id){
		$sql = $this->getUpdateSql("PRODUCT_NAME_INFO",array_keys($insert_param),array_values($insert_param),"product_name_id",$id);
        	$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $isUpdate;
	}

	/**
	 * @note function is used to insert the product model information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $model_id.
	 * retun integer.
	 */
	function addUpdProductInfoDetails($insert_param){
		require_once(CLASSPATH.'price.class.php');
		$oPrice	= new price;
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date']=date("Y-m-d H:i:s");
		$sql = $this->getInsertUpdateSql("PRODUCT_NAME_INFO",array_keys($insert_param),array_values($insert_param));
		$model_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys($this->productKey.'_');
		$this->cache->searchDeleteKeys($this->productKey.'_searchProduct_');
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
        $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $model_id;
	}
	function boolUpdateProductField($field_name,$value,$update_param)
	{
		$update_param['update_date'] = date('Y-m-d H:i:s');
		if($update_param['discontinue_date'] == ""){
			$update_param['discontinue_date'] = date('Y-m-d H:i:s');
		}
		$sql = $this->getUpdateSql("PRODUCT_MASTER",array_keys($update_param),array_values($update_param),$field_name,$value);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $isUpdate;
	}
	function boolUpdateProductNameField($field_name,$value,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		if($update_param['discontinue_date'] == ""){
			$update_param['discontinue_date'] = date('Y-m-d H:i:s');
		}
		$sql = $this->getUpdateSql("PRODUCT_NAME_INFO",array_keys($update_param),array_values($update_param),$field_name,$value);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $isUpdate;
	}
	/**
	 * @note function is used to update the product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $product_id.
	 * @pre $update_param must be valid associative array and $product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	function boolUpdateProduct($product_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("PRODUCT_MASTER",array_keys($update_param),array_values($update_param),"product_id",$product_id);
		$isUpdate = $this->update($sql);
		
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys($this->featurekey);
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $isUpdate;
	}
	function boolUpdateProductDiscontinueFlag($product_id,$update_param){
		$sql = $this->getUpdateSql("PRODUCT_MASTER",array_keys($update_param),array_values($update_param),"product_id",$product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $isUpdate;
	}
	 /**
	 * @note function boolUpdateProductFeature is used to update the product feature into the database.
	 * @param an associative array $update_param
	 * @param an integer $iProdFeatureId.
	 * @pre $update_param must be valid associative array and $product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateProductFeature($iProdFeatureId,$update_param)
	 {
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("PRODUCT_FEATURE",array_keys($update_param),array_values($update_param),"product_feature_id",$iProdFeatureId);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey."_feature");
		return $isUpdate;
	 }
	 /**
	 * @note function is used to delete the product.
	 * @param integer $product_id.
	 * @pre $product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteProduct($product_id)
	 {
		$sql = "delete from PRODUCT_MASTER where product_id = $product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey);
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $isDelete;
	 }
	 /**
	 * @note function is used to delete the product.
	 * @param integer $product_id.
	 * @pre $product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteProductFeature($product_feature_id)
	 {
		$sql = "delete from PRODUCT_FEATURE where product_feature_id = $product_feature_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_feature");
		return $isDelete;
	 }
	/**
	* @note function is used to get product details.
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer category_id.
	* @param an integer brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	*
	* @pre not required.
	*
	* @post product details in associative array.
	* retun an array.
	*/
	function arrGetProductDetails($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$count="",$default_city="1",$orderby="",$product_name="",$city_id="",$arrival_date="",$discontinue_flag='',$check_discontinue_date="",$color_id='0',$variant_name=""){
		$keyArr[] = $this->productKey."_arrGetProductDetails_";
		$selectStr = "PRODUCT_MASTER.*";
		if(!empty($startprice)){
			$keyArr[] 			= $startprice;
			$whereClauseArr[] 	= "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] 			= $endprice;
			$whereClauseArr[] 	= "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] 			= $variant_id;
			$whereClauseArr[] 	= "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] 	= "PRICE_VARIANT_VALUES.brand_id !=0";
		}else{$keyArr[] = -1;}
		if(!empty($city_id)){
			$keyArr[] 		  = $city_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = $city_id";
		}else{$keyArr[] = -1;}
		if(!empty($startprice) or !empty($endprice) or !empty($variant_id) or !empty($city_id) or !empty($default_city)){
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			if(!empty($default_city)){	$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";}
			$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		    $whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id in ($color_id)";

		}
		$tablenameArr[] = "PRODUCT_MASTER";
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
				//if(intval($product_ids)!=0){
				$product_ids = intval($product_ids);
				//}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if(is_array($color_id)){
			$color_id = implode(",", $color_id);
		}
		if($status != ''){
			$keyArr[] 			= $status;
			$whereClauseArr[] 	= "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ''){
			$keyArr[] 			= $discontinue_flag;
			$whereClauseArr[] 	= "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if($arrival_date != ''){
			$keyArr[] 			= $arrival_date;
			$whereClauseArr[] 	= "PRODUCT_MASTER.arrival_date!='0000-00-00'";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] 			= $product_ids;
			$whereClauseArr[] 	= "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] 			= $category_id;
			$whereClauseArr[] 	= "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] 			= $brand_id;
			$whereClauseArr[] 	= "PRODUCT_MASTER.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_name)){
			$keyArr[] 			= $product_name;
			$product_name 		= strtolower($product_name);
			$whereClauseArr[] 	= "PRODUCT_MASTER.product_name = '$product_name'";
		}else{$keyArr[] = -1;}
		if($variant_name!=''){
			$keyArr[] = "variant_name_$variant_name";
			$whereClauseArr[] = "PRODUCT_MASTER.variant in ($variant_name)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] 		= $startlimit;
			$limitArr[] 	= $startlimit;
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
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$tableStr = implode(",",$tablenameArr);
		$sql = "select $selectStr from $tableStr $whereClauseStr $orderby $limitStr"; 
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetDiscontinuedProductDetails($discontinue_flag){
		$key = $this->productKey."_$discontinue_flag";
		if($result = $this->cache->get($key)){return $result;}
		$sql = "Select * from PRODUCT_MASTER where discontinue_flag = $discontinue_flag";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get unique product name.
	* @param an integer category_id.
	* @param an integer brand_id.
	* @post an associative array.
	* retun an array.
	*/
	function arrGetUniqueProductName($category_id=-1,$brand_id=-1){
		$keyArr[] = $this->productKey."_unique";
		$keyArr[] = $category_id;
		$keyArr[] = $brand_id;
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "Select * from PRODUCT_MASTER where category_id = $category_id and brand_id = $brand_id group by product_name order by product_name asc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetProductNameByBrand($category_id=-1,$brand_id=-1){
        $keyArr[] = $this->productKey."_unique";
        $keyArr[] = $category_id;
        $keyArr[] = $brand_id;
        $key = implode("_",$keyArr);
        if($result = $this->cache->get($key)){return $result;}
        $sql = "Select * from PRODUCT_NAME_INFO where category_id = $category_id and brand_id = $brand_id order by product_info_name asc";
        $result = $this->select($sql);
        $this->cache->set($key,$result);
        return $result;
   	}
	/**
	* @note function is used to get product details count.
	* @pre not required.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product ids/ product ids array $category_id.
	* @param an integer/comma seperated product ids/ product ids array $brand_id.
	* @param is a boolean value $status.
	* @param $startprice.
	* @param $endprice.
	* @param is an integer value $variant_id.
	* @param is an integer value $startlimit.
	* @param is an integer value $cnt.
	* @param is an integer $default_city.
	* @param is a string $orderby.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductDetailsCount($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$default_city="1",$product_name="",$city_id="",$discontinue_flag='',$check_discontinue_date="",$color_id='0')
	{
		$keyArr[] = $this->productKey."_arrGetProductDetailsCount";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] =$endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.brand_id !=0";
		}else{$keyArr[] = -1;}
		if(!empty($city_id)){
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = $city_id";
		}
		if(!empty($startprice) or !empty($endprice) or !empty($variant_id) or !empty($city_id) or !empty($default_city)){
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			if(!empty($default_city)){
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			}
			$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id in ($color_id)";

		}
		$tablenameArr[] = "PRODUCT_MASTER";
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
				//if(intval($product_ids)!=0){
				$product_ids = intval($product_ids);
				//}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($color_id)){
			$color_id = implode(",",$color_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_name)){
			$keyArr[] = $product_name;
			$product_name = strtolower($product_name);
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = '$product_name'";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		/*if($color_id!=''){
			$keyArr[] = "color_id_$color_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id in ($color_id)";
		}
		*/
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$tableStr = implode(",",$tablenameArr);
		$sql = "select count(PRODUCT_MASTER.product_id) as cnt from $tableStr $whereClauseStr $orderby";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get product feature details.
	* @pre not required.
	* @param an integer/comma seperated product feature ids $product_feature_id.
	* @param an integer/comma seperated feature ids $feature_id.
	* @param an integer/comma seperated product ids $product_id.
	* @param is an integer value $startlimit.
	* @param is an integer value $cnt.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductFeatureDetails($product_feature_id="",$feature_id="",$product_id="",$startlimit="",$cnt=""){
		$keyArr[] = $this->productKey."_feature";
    	if(is_array($product_id)){
				foreach($product_id as $variant_id){
					$i_variant_ids = intval($variant_id);
					if($i_variant_ids!=0){
						$variant_ids[] = $i_variant_ids;
					}
				}
				$product_id = implode(",",$variant_ids);
	    }else{
			if(strpos($product_id,',')==false){
				//if(intval($product_id)!=0){
				$product_id = intval($product_id);
				//}
			}else{
				$arr_variant_ids = explode(",",$product_id);
				foreach($arr_variant_ids as $variant_id){
					$i_variant_ids = intval($variant_id);
					if($i_variant_ids!=0){
						$variant_ids[] = $i_variant_ids;
					}
				}
				$product_id = implode(",",$variant_ids);
	      	}
	    }
		if(!empty($product_feature_id)){
			$keyArr[] = $product_feature_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.product_feature_id in ($product_feature_id)";
		}else{$keyArr[] = -1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.feature_id in ($feature_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.product_id in ($product_id)";
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}

		$whereClauseArr[] = "FEATURE_MASTER.feature_id = PRODUCT_FEATURE.feature_id";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select PRODUCT_FEATURE.*,FEATURE_MASTER.* from PRODUCT_FEATURE,FEATURE_MASTER $whereClauseStr order by PRODUCT_FEATURE.create_date desc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetProductDetailsUsingFeatureid($product_feature_id="",$feature_id="",$product_id="",$startlimit="",$cnt=""){
		$keyArr[] = $this->productKey."_feature_id";
    	if(is_array($product_id)){
      		foreach($product_id as $variant_id){
        		$i_variant_ids = intval($variant_id);
        		if($i_variant_ids!=0){
          			$variant_ids[] = $i_variant_ids;
        		}
      		}
      		$product_id = implode(",",$variant_ids);
    	}else{
      		if(strpos($product_id,',')==false){
        		//if(intval($product_id)!=0){
          		$product_id = intval($product_id);
        		//}
      		}else{
	        	$arr_variant_ids = explode(",",$product_id);
	        	foreach($arr_variant_ids as $variant_id){
	          		$i_variant_ids = intval($variant_id);
	          		if($i_variant_ids!=0){
	            		$variant_ids[] = $i_variant_ids;
	          		}
	        	}
	        	$product_id = implode(",",$variant_ids);
      		}
    	}
		if(!empty($product_feature_id)){
			$keyArr[] = $product_feature_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.product_feature_id in ($product_feature_id)";
		}else{$keyArr[] = -1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.feature_id in ($feature_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "PRODUCT_FEATURE.product_id in ($product_id)";
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = "FEATURE_MASTER.feature_id = PRODUCT_FEATURE.feature_id";
		$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRODUCT_FEATURE.product_id";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select PRODUCT_FEATURE.*,FEATURE_MASTER.*,PRODUCT_MASTER.* from PRODUCT_MASTER,PRODUCT_FEATURE,FEATURE_MASTER $whereClauseStr order by PRODUCT_FEATURE.create_date desc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	 * @note function is used to insert the latest product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertLatestProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("LATEST_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productKey."_latest");
		return $product_id;
	}
	/**
	 * @note function is used to update the latest product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateLatestProduct($latest_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("LATEST_PRODUCT",array_keys($update_param),array_values($update_param),"latest_product_id",$latest_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey."_latest");
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the latest product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteLatestProduct($latest_product_id){
		$sql = "delete from LATEST_PRODUCT where latest_product_id = $latest_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_latest");
		return $isDelete;
	 }
	/**
	 * @note function is used to get latest product details.
	 * @param integer $latest_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetProductLatestDetails($latest_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productKey."_latest";
		if(is_array($latest_product_ids)){
			$latest_product_ids = implode(",",$latest_product_ids);
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
		        //if(intval($product_ids)!=0){
		          $product_ids = intval($product_ids);
		        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($latest_product_ids)){
			$keyArr[] = $latest_product_ids;
			$whereClauseArr[] = "latest_product_id in ($latest_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		$sql = "select * from LATEST_PRODUCT $whereClauseStr $limitStr order by product_position asc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	 /**
	 * @note function is used to insert the upcoming product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertUpComingProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("UPCOMING_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productKey."_upcoming");
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		$this->arrGetProductUpComingDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the UpComing product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateUpComingProduct($upcoming_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("UPCOMING_PRODUCT",array_keys($update_param),array_values($update_param),"upcoming_product_id",$upcoming_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey."_upcoming");
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		$this->arrGetProductUpComingDetails($upcoming_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the upcoming product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteUpComingProduct($upcoming_product_id){
		$sql = "delete from UPCOMING_PRODUCT where upcoming_product_id = $upcoming_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_upcoming");
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $isDelete;
	 }
	/**
	 * @note function is used to get UpComing product details.
	 * @param integer $upcoming_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetProductUpComingDetails($upcoming_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productKey."_upcoming";
		if(is_array($upcoming_product_ids)){
			$upcoming_product_ids = implode(",",$upcoming_product_ids);
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
	        //if(intval($product_ids)!=0){
	          $product_ids = intval($product_ids);
	        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($upcoming_product_ids)){
			$keyArr[] = $upcoming_product_ids;
			$whereClauseArr[] = "upcoming_product_id in ($upcoming_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		$sql = "select * from UPCOMING_PRODUCT $whereClauseStr $limitStr order by product_position asc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	 /**
	 * @note function is used to insert the featured_product product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertFeaturedProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("FEATURED_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetProductFeaturedDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the featured product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateFeaturedProduct($featured_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("FEATURED_PRODUCT",array_keys($update_param),array_values($update_param),"featured_product_id",$featured_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetProductFeaturedDetails($featured_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the featured product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteFeaturedProduct($featured_product_id){
		$sql = "delete from FEATURED_PRODUCT where featured_product_id = $featured_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		return $isDelete;
	 }
	/**
	 * @note function is used to get Featured product details.
	 * @param integer $Featured_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetProductFeaturedDetails($featured_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetProductFeaturedDetails";
		if(is_array($featured_product_ids)){
			$featured_product_ids = implode(",",$featured_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($featured_product_ids)){
			$keyArr[] = $featured_product_ids;
			$whereClauseArr[] = "featured_product_id in ($featured_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		#echo $key;die();
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from FEATURED_PRODUCT $whereClauseStr order by product_position asc $limitStr";
		#echo $sql;die();
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	function arrGetProductFeaturedDetailsCnt($featured_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1')
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetProductFeaturedDetailsCnt";
		if(is_array($featured_product_ids)){
			$featured_product_ids = implode(",",$featured_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($featured_product_ids)){
			$keyArr[] = $featured_product_ids;
			$whereClauseArr[] = "featured_product_id in ($featured_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(featured_product_id) as cnt from FEATURED_PRODUCT $whereClauseStr order by product_position asc";
		#die($sql);
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	/**
	* @note function is used to get product by name.
	* @pre not required.
	* @param a string product name $product_name.
	* @param an integer product id $product_id.
	* @param an integer variant $variant.
	* @param a boolean status $status.
	* @param is an integer value $startlimit.
	* @param is an integer value $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
	 function arrGetProductByName($product_name,$product_id,$variant="",$status="1",$startlimit="",$cnt="",$discontinue_flag='',$check_discontinue_date="",$brand_id=""){
		$keyArr[] = $this->productKey."_by_name";
    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        //if(intval($product_id)!=0){
          $product_id = intval($product_id);
        //}
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_name)){
			$keyArr[] = $product_name;
			$whereClauseArr[] = "product_name = '$product_name'";
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id != $product_id";
		}
		if(!empty($brand_id)){
                        $keyArr[] = $brand_id;
                        $whereClauseArr[] = "brand_id  = '$brand_id'";
                }else{$keyArr[] = -1;}

		if(!empty($variant)){
			$keyArr[] = $variant;
			$whereClauseArr[] = "variant  = '$variant'";
		}else{$keyArr[] = -1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
        		$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
		        $whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
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
	//	if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from PRODUCT_MASTER $whereClauseStr $limitStr order by create_date desc";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	/**
	* @note function is used to get search product details
	*
	* @param an array $result.
	* @param an integer $category_ids.
	* @param an integer $city_id.
	*
	* @post is an associative array.
	* retun an array.
	*/
	 function constantProductDetails($result,$category_id,$city_id="",$price_other_param="",$variantcnt=""){
		global $searchShortDescArr;
		require_once(CLASSPATH.'price.class.php');
		require_once(CLASSPATH.'feature.class.php');
		require_once(CLASSPATH.'brand.class.php');
		$price = new price;
		$feature = new FeatureManagement;
		$brand = new BrandManagement;
		$cnt = sizeof($result);
		if(!empty($category_id)){
			$price_formula = $price->arrGetVariantFormulaDetail("","",$category_id);
			$variant_formula_id = $price_formula[0]['variant_formula_id'];
			$formula = $price_formula[0]['formula'];
		}

		for($i=0;$i<$cnt;$i++){
			$product_id = $result[$i]['product_id'];
			$categoryid = $result[$i]['category_id'];
			$brandid = $result[$i]['brand_id'];
			$product_name = $result[$i]['product_name'];
			unset($productNameArr);
			if(!empty($brandid)){
				$brand_result = $brand->arrGetBrandDetails($brandid);
				$productNameArr[] = $brand_result[0]['brand_name'];
			}
			$productNameArr[] = $result[$i]['product_name'];
			$result[$i]['link_product_name'] = implode(" ",$productNameArr);
			$productNameArr[] = $result[$i]['variant'];
			$display_product_name = implode(" ",$productNameArr);
			$result[$i]['display_product_name'] = $display_product_name;
			if(!empty($categoryid) && !empty($product_id)){
				$sOverviewArray = $feature->arrGetSummary($categoryid,$product_id,$type="array");
			}
			if(is_array($sOverviewArray)){
				unset($productNameArr[0]);		// remove brand name form array.
				//$aTechSpec = $sOverviewArray[implode(" ",$productNameArr)." Technical Specifications"][0];
				//$result[$i]['short_desc'] = implode(",",$aTechSpec);
				foreach($sOverviewArray as $key=>$val){
						if($sOverviewArray[$key][0]){
							$overviewArr[] = implode(",&#160;",$sOverviewArray[$key][0]);
						}
				}
				$result[$i]['short_desc'] = implode(",&#160;",$overviewArr);
				unset($overviewArr);
			}else{
				$result[$i]['short_desc'] = "";
			}

			if(!empty($product_id)){
				$price_result = $price->arrGetPriceDetails("",$product_id,$categoryid,"","",$city_id,"1","","","");
				$aVariant=$price->arrGetVariantDetail("",$categoryid,"1","","");
				$priceCnt = sizeof($price_result);
				//if(!empty($priceCnt) && ($priceCnt == $variantcnt)){
				if(!empty($priceCnt)){
					for($j=0;$j<$priceCnt;$j++){
						$variant_id = $price_result[$j]['variant_id'];
						$variant_value = $price_result[$j]['variant_value'];
						if(in_array(EX_SHOWROOM_STR,$price_result[$j])){
							$result[$i]['exshowroomprice'] = $variant_value ? priceFormat($variant_value) : '';
						}
						$formulaValuesArr[$variant_id] = $variant_value ? $variant_value : 0;
						$aVar[]=$variant_id;
						$result[$i]['price_details'][] = $price_result[$j];
					}
				}
				/*elseif(!empty($priceCnt) && ($priceCnt < $variantcnt)){
					if(!empty($price_other_param)){
							print_r($price_other_param);
							for($k=0;$k<count($price_other_param);$k++){
								$variant_id = $price_other_param[$k]['variant_id'];
								$variant_value = $price_other_param[$k]['variant_value'];
								if(in_array(EX_SHOWROOM_STR,$price_other_param[$k])){
									$result[$k]['exshowroomprice'] = $variant_value ? priceFormat($variant_value) : '';
								}
								$formulaValuesArr[$variant_id] = $variant_value ? $variant_value : 0;
								$aVar[]=$variant_id;

								$result[$i]['price_details'][] = $price_other_param[$k];
							}
					}
				}*/
				else{
					$result[$i]['exshowroomprice'] = 0;
				}
				for($k=0;$k<count($aVariant);$k++){
					if(!in_array($aVariant[$k]['variant_id'],$aVar)){
						$formulaValuesArr[$aVariant[$k]['variant_id']]=0;
					}
				}


				$feature_result = $this->arrGetProductFeatureDetails("","",$product_id);
				$featureCnt = sizeof($feature_result);
				//$result[$i]['feature_result']['count'] = $featureCnt;
				unset($short_desc_array);
				for($j=0;$j<$featureCnt;$j++){
					unset($featureValueArr);
					$feature_id = $feature_result[$j]['feature_id'];
					$feature_name = $feature_result[$j]['feature_name'];
					$feature_value = $feature_result[$j]['feature_value'];
					$featureValueArr[] = $feature_value;
					$feature_unit = $feature_result[$j]['unit_id'];
					if(!empty($feature_unit)){
						$feature_unit = $feature->arrFeatureUnitDetails($feature_unit,$categoryid);
						$unit_name = $feature_unit[0]['unit_name'];
						$featureValueArr[] = $unit_name;
					}


					//echo "short desc = ";print_r($searchShortDescArr);

					/*$key = array_search($feature_name,$searchShortDescArr);
					if($key){
						//print_r($featureValueArr);
						//echo "key = $key<Br/>";
						$feature_value = strtolower($feature_value) == 'yes' ? $feature_name : implode(" ",$featureValueArr);
						$short_desc_array[$key] = $feature_value;

					}*/

					$result[$i]['feature_result'][] = $feature_result[$j];

				}

				if(sizeof($formulaValuesArr) > 0){
					$totalprice = strtr($formula,$formulaValuesArr);
					$totalprice = parse_mathematical_string($totalprice);
				}
				$onroadkey = str_replace(" ","_",ON_RAOD_PRICE_TITLE);
				$result[$i][$onroadkey] = $totalprice ? $totalprice :0;


				//echo "START TIME133----".date('Y-m-d m:i:s')."<br>";
				if(!empty($product_name)){
					$similar_product_result = $this->arrGetProductByName(strtolower($product_name),$product_id,"","1","0","6");
					$similarCnt = sizeof($similar_product_result);
					for($k=0;$k<$similarCnt;$k++){
						$similar_product_id = $similar_product_result[$k]['product_id'];
						$similar_brandid = $similar_product_result[$k]['brand_id'];
						$product_name = $similar_product_result[$k]['product_name'];
						unset($similarproductNameArr);
						if(!empty($similar_brandid)){
							$similar_brand_result = $brand->arrGetBrandDetails($similar_brandid);
							$similarproductNameArr[] = $similar_brand_result[0]['brand_name'];
						}
						$similarproductNameArr[] = $similar_product_result[$k]['product_name'];
						$similarproductNameArr[] = $similar_product_result[$k]['variant'];
						$similar_display_product_name = implode(" ",$similarproductNameArr);
						$similar_product_result[$k]['display_product_name'] = $similar_display_product_name;
						$similar_feature_result = $this->arrGetProductFeatureDetails("","",$similar_product_id);
						$featureCnt = sizeof($similar_feature_result);
						//$result[$i]['feature_result']['count'] = $featureCnt;
						unset($short_desc_array);

						//echo "START TIME123----".date('Y-m-d m:i:s')."<br>";
						for($j=0;$j<$featureCnt;$j++){
							//echo "START TIME124----".date('Y-m-d m:i:s')."<br>";
							unset($featureValueArr);
							$feature_id = $similar_feature_result[$j]['feature_id'];
							$feature_name = $similar_feature_result[$j]['feature_name'];
							$feature_value = $similar_feature_result[$j]['feature_value'];
							$featureValueArr[] = $feature_value;
							$feature_unit = $similar_feature_result[$j]['unit_id'];
							if(!empty($feature_unit)){
								$similar_feature_unit = $feature->arrFeatureUnitDetails($feature_unit,$category_id);
								$unit_name = $similar_feature_unit[0]['unit_name'];
								$featureValueArr[] = $unit_name;
							}
							//echo "short desc = ";print_r($searchShortDescArr);

							/*$key = array_search($feature_name,$searchShortDescArr);
							if($key){
								//print_r($featureValueArr);
								//echo "key = $key<Br/>";
								$feature_value = strtolower($feature_value) == 'yes' ? $feature_name : implode(" ",$featureValueArr);
								$short_desc_array[$key] = $feature_value;

							}*/
							if(!empty($categoryid) && !empty($similar_product_id)){

								$similarSetOverviewArr = $feature->arrGetSummary($categoryid,$similar_product_id,$type="array");

							}

							if(is_array($similarSetOverviewArr)){
								unset($similarproductNameArr[0]);	// remove brand name form array.
								//$aTechSpec = $similarSetOverviewArr[implode(" ",$similarproductNameArr)." Technical Specifications"][0];

								//$similar_product_result[$k]['short_desc'] = implode(",",$aTechSpec);

								foreach($similarSetOverviewArr as $key=>$val){
										if($similarSetOverviewArr[$key][0]){
											$overviewArr[] = implode(",&#160;",$similarSetOverviewArr[$key][0]);
										}
								}
								$similar_product_result[$k]['short_desc'] = implode(",&#160;",$overviewArr);
								unset($overviewArr);
								unset($similarSetOverviewArr);

							}else{
								$similar_product_result[$k]['short_desc'] = "";
							}
							//print_r($similar_product_result);
							//exit;
							//$similar_product_result[$k]['feature_result'][] = $feature_result[$j];

						}
						/*
						if(sizeof($short_desc_array) > 0){
							ksort($short_desc_array);
							$similar_product_result[$k]['short_desc'] = implode(" ",$short_desc_array);
						}else{
							$similar_product_result[$k]['short_desc'] = "";
						}
						*/
						$aVar='';
						$price_result = $price->arrGetPriceDetails("",$similar_product_id,$categoryid,"","",$city_id,"1","","","");
						$aVariant=$price->arrGetVariantDetail("",$categoryid,"1","","");
						$priceCnt = sizeof($price_result);
						if(!empty($priceCnt)){
							for($j=0;$j<$priceCnt;$j++){
								$variant_id = $price_result[$j]['variant_id'];
								$variant_value = $price_result[$j]['variant_value'];
								$formulaValuesArr[$variant_id] = $variant_value ? $variant_value : 0;
								$aSVar[]=$variant_id;
								}
						}
						for($l=0;$l<count($aVariant);$l++){
							if(!in_array($aVariant[$l]['variant_id'],$aSVar)){
								$formulaValuesArr[$aVariant[$l]['variant_id']]=0;
							}
						}
						$aVariant=''; $aSVar='';
						if(sizeof($formulaValuesArr) > 0){
							$similartotalprice = strtr($formula,$formulaValuesArr);
							$similartotalprice = parse_mathematical_string($similartotalprice);
						}
						$onroadkey = str_replace(" ","_",ON_RAOD_PRICE_TITLE);
						$similar_product_result[$k][$onroadkey] = $similartotalprice ? $similartotalprice :0;
						$compare_price_difference = $totalprice - $similartotalprice;
						$similar_product_result[$k]['compare_price_difference'] = $compare_price_difference;

						//$similar_product_result[$k]['short_desc'] = implode(" ",$short_desc_array);

						$price_result = $price->arrGetPriceDetails("1",$similar_product_id,$categoryid);
						$exshowroom_price = $price_result[0]['variant_value'];
						$similar_product_result[$k]['exshowroomprice'] = $exshowroom_price ? $exshowroom_price : 0;
						$result[$i]['similar_product'][] = $similar_product_result[$k];
					}
				}

			}
			//echo "START TIME13----".date('Y-m-d m:i:s')."<br>";

		}
		//die();
		return $result;
	 }
	 function assignPivotToSearch(){

		require_once(CLASSPATH.'pivot.class.php');
		$pivot = new PivotManagement;

		/*
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","3");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->bodyStyleArr[] = $pivot_result[$i]['feature_id'];

		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","1");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->fuelTypeArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","2");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->impFeatureArr[] = $pivot_result[$i]['feature_id'];
		}

		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","4");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->tranmissionArr[] = $pivot_result[$i]['feature_id'];
		}

		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","5");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->seatingCapcityArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","7");
        $pivotcnt = sizeof($pivot_result);
        for($i=0;$i<$pivotcnt;$i++){
                $this->segmentsArr[] = $pivot_result[$i]['feature_id'];
        }
		return array ('body_style' => $this->bodyStyleArr,'fuel_type'=>$this->fuelTypeArr,'imp_feature'=>$this->impFeatureArr,'tranmission'=>$this->tranmissionArr,'seating'=>$this->seatingCapcityArr,'segment'=>$this->segmentsArr);
		*/
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","11");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->PhoneTypeArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","25");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->AvailabilityArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","24");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->FormFactorArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","23");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->InputMechanismArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","22");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->RAMArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","19");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->FeaturesArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","18");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->NetworkTypeArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","17");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->NoofSIMArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","16");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->NetworkArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","15");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->PrimaryCameraArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","14");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->OperatingSystemArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","13");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->ProcessorNoofcoresArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","12");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->ScreenSizeArr[] = $pivot_result[$i]['feature_id'];
		}
		$pivot_result = $pivot->arrGetPivotDetails("",$category_id,"","1","26");
		$pivotcnt = sizeof($pivot_result);
		for($i=0;$i<$pivotcnt;$i++){
			$this->AnnouncedArr[] = $pivot_result[$i]['feature_id'];
		}
		return array ('phone_type' => $this->PhoneTypeArr,'availability'=>$this->AvailabilityArr,'form_factor'=>$this->FormFactorArr,'input_mechanism'=>$this->InputMechanismArr,'ram'=>$this->RAMArr,'features'=>$this->FeaturesArr,'network_type'=>$this->NetworkTypeArr,'noof_sim'=>$this->NoofSIMArr,'network'=>$this->NetworkArr,
		'primary_camera'=>$this->PrimaryCameraArr,'operating_system'=>$this->OperatingSystemArr,
		'processor'=>$this->ProcessorNoofcoresArr,'screen_size'=>$this->ScreenSizeArr,'announced'=>$this->AnnouncedArr);
	 }
	 /**
	* @note function is used to get search product count
	*
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param boolean Active/InActive $status.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post is an integer count.
	* retun an integer.
	*/
	function searchProductCount($category_ids="",$brand_ids="",$product_ids="",$feature_ids="",$status="1",$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		$keyArr[] = $this->productKey."_searchProductCount";
		$this->assignPivotToSearch();
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
	        //if(intval($product_ids)!=0){
	          $product_ids = intval($product_ids);
	        //}
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
		if(is_array($feature_ids)){
			$feature_ids = implode(",",$feature_ids);
		}
		if(!empty($feature_ids)){
			$featureArr = explode(",",$feature_ids);
			foreach($featureArr as $feature_id){
				if(in_array($feature_id,$this->PhoneTypeArr)){
					$keyArr[] = $feature_id;
					$this->newPhoneTypeArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->AvailabilityArr)){
					$keyArr[] = $feature_id;
					$this->newAvailabilityArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->FormFactorArr)){
					$keyArr[] = $feature_id;
					$this->newFormFactorArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->InputMechanismArr)){
					$keyArr[] = $feature_id;
					$this->newInputMechanismArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->RAMArr)){
					$keyArr[] = $feature_id;
					$this->newRAMArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->FeaturesArr)){
					$keyArr[] = $feature_id;
					$this->newFeaturesArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->NetworkTypeArr)){
					$keyArr[] = $feature_id;
					$this->newNetworkTypeArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->NoofSIMArr)){
					$keyArr[] = $feature_id;
					$this->newNoofSIMArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->NetworkArr)){
					$keyArr[] = $feature_id;
					$this->newNetworkArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->PrimaryCameraArr)){
					$keyArr[] = $feature_id;
					$this->newPrimaryCameraArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->OperatingSystemArr)){
					$keyArr[] = $feature_id;
					$this->newOperatingSystemArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->ProcessorNoofcoresArr)){
					$keyArr[] = $feature_id;
					$this->newProcessorNoofcoresArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->ScreenSizeArr)){
					$keyArr[] = $feature_id;
					$this->newScreenSizeArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->AnnouncedArr)){
					$keyArr[] = $feature_id;
					$this->newAnnouncedArr[] = $feature_id;
				}
			}
			$this->newPhoneTypeArr = array_unique($this->newPhoneTypeArr,SORT_REGULAR);
			$this->newAvailabilityArr = array_unique($this->newAvailabilityArr,SORT_REGULAR);
			$this->newFormFactorArr = array_unique($this->newFormFactorArr,SORT_REGULAR);
			$this->newInputMechanismArr = array_unique($this->newInputMechanismArr,SORT_REGULAR);
			$this->newRAMArr = array_unique($this->newRAMArr,SORT_REGULAR);
			$this->newFeaturesArr = array_unique($this->newFeaturesArr,SORT_REGULAR);
			$this->newNetworkTypeArr = array_unique($this->newNetworkTypeArr,SORT_REGULAR);
			$this->newNoofSIMArr = array_unique($this->newNoofSIMArr,SORT_REGULAR);
			$this->newNetworkArr = array_unique($this->newNetworkArr,SORT_REGULAR);
			$this->newPrimaryCameraArr = array_unique($this->newPrimaryCameraArr,SORT_REGULAR);
			$this->newOperatingSystemArr = array_unique($this->newOperatingSystemArr,SORT_REGULAR);
			$this->newProcessorNoofcoresArr = array_unique($this->newProcessorNoofcoresArr,SORT_REGULAR);
			$this->newScreenSizeArr = array_unique($this->newScreenSizeArr,SORT_REGULAR);
			$this->newAnnouncedArr = array_unique($this->newAnnouncedArr,SORT_REGULAR);

			if(sizeof($this->newPhoneTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newPhoneTypeArr).")";
			}
			if(sizeof($this->newAvailabilityArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newAvailabilityArr).")";
			}
			if(sizeof($this->newFormFactorArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newFormFactorArr).")";
			}
			if(sizeof($this->newInputMechanismArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newInputMechanismArr).")";
			}
			if(sizeof($this->newRAMArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newRAMArr).")";
			}
			if(sizeof($this->newFeaturesArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newFeaturesArr).")";
			}
			if(sizeof($this->newNetworkTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newNetworkTypeArr).")";
			}
			if(sizeof($this->newNoofSIMArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newNoofSIMArr).")";
			}
			if(sizeof($this->newNetworkArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newNetworkArr).")";
			}
			if(sizeof($this->newPrimaryCameraArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newPrimaryCameraArr).")";
			}
			if(sizeof($this->newOperatingSystemArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newOperatingSystemArr).")";
			}
			if(sizeof($this->newProcessorNoofcoresArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newProcessorNoofcoresArr).")";
			}
			if(sizeof($this->newScreenSizeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newScreenSizeArr).")";
			}
			if(sizeof($this->newAnnouncedArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newAnnouncedArr).")";
			}
			if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
				if(strlen($sqlStr) > 0){
				$sqlStr .= ' and product_id in('.$featureSql.')';
				}else{
				$sqlStr .= $featureSql;
				}
				}
				if(strlen($sqlStr) > 0){
				$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
		}
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] = '-1';}
		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
			$keyArr[] = $startprice.'_'.$endprice.'_'.$variant_id.'_'.$color_id;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			$tablenameArr[] = "PRODUCT_NAME_INFO";
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
			$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
		}else{$keyArr[] = '-1_-1_-1_-1';}
		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value != 0";
		$tablenameArr[] = "PRODUCT_MASTER";
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
        		$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
		        $whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
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

		$table_name = implode(",",$tablenameArr);
		$sql = "select count(PRODUCT_MASTER.product_id) as cnt FROM  $table_name $whereClauseStr $limitStr";
		//echo $sql."<br>";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	 /**
	* @note function is used to get search product details
	*
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param boolean Active/InActive $status.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $oredrby.
	*
	* @pre not required.
	*
	* @post is an associative array.
	* retun an array.
	*/
	 function searchProduct($category_ids="",$brand_ids="",$product_ids="",$feature_ids="",$status="1",$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$orderby="PRODUCT_MASTER.create_date desc",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		$keyArr[] = $this->productKey."_searchProduct";
		$this->assignPivotToSearch();
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
	        //if(intval($product_ids)!=0){
	          $product_ids = intval($product_ids);
	        //}
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
		if(is_array($feature_ids)){
			$feature_ids = implode(",",$feature_ids);
		}

		if(!empty($feature_ids)){
			$featureArr = explode(",",$feature_ids);
			foreach($featureArr as $feature_id){
				if(in_array($feature_id,$this->PhoneTypeArr)){
					$keyArr[] = $feature_id;
					$this->newPhoneTypeArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->AvailabilityArr)){
					$keyArr[] = $feature_id;
					$this->newAvailabilityArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->FormFactorArr)){
					$keyArr[] = $feature_id;
					$this->newFormFactorArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->InputMechanismArr)){
					$keyArr[] = $feature_id;
					$this->newInputMechanismArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->RAMArr)){
					$keyArr[] = $feature_id;
					$this->newRAMArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->FeaturesArr)){
					$keyArr[] = $feature_id;
					$this->newFeaturesArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->NetworkTypeArr)){
					$keyArr[] = $feature_id;
					$this->newNetworkTypeArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->NoofSIMArr)){
					$keyArr[] = $feature_id;
					$this->newNoofSIMArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->NetworkArr)){
					$keyArr[] = $feature_id;
					$this->newNetworkArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->PrimaryCameraArr)){
					$keyArr[] = $feature_id;
					$this->newPrimaryCameraArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->OperatingSystemArr)){
					$keyArr[] = $feature_id;

					$this->newOperatingSystemArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->ProcessorNoofcoresArr)){
					$keyArr[] = $feature_id;
					$this->newProcessorNoofcoresArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->ScreenSizeArr)){
					$keyArr[] = $feature_id;
					$this->newScreenSizeArr[] = $feature_id;
				}
				if(in_array($feature_id,$this->AnnouncedArr)){
					$keyArr[] = $feature_id;
					$this->newAnnouncedArr[] = $feature_id;
				}
			}
			$this->newPhoneTypeArr = array_unique($this->newPhoneTypeArr,SORT_REGULAR);
			$this->newAvailabilityArr = array_unique($this->newAvailabilityArr,SORT_REGULAR);
			$this->newFormFactorArr = array_unique($this->newFormFactorArr,SORT_REGULAR);
			$this->newInputMechanismArr = array_unique($this->newInputMechanismArr,SORT_REGULAR);
			$this->newRAMArr = array_unique($this->newRAMArr,SORT_REGULAR);
			$this->newFeaturesArr = array_unique($this->newFeaturesArr,SORT_REGULAR);
			$this->newNetworkTypeArr = array_unique($this->newNetworkTypeArr,SORT_REGULAR);
			$this->newNoofSIMArr = array_unique($this->newNoofSIMArr,SORT_REGULAR);
			$this->newNetworkArr = array_unique($this->newNetworkArr,SORT_REGULAR);
			$this->newPrimaryCameraArr = array_unique($this->newPrimaryCameraArr,SORT_REGULAR);
			$this->newOperatingSystemArr = array_unique($this->newOperatingSystemArr,SORT_REGULAR);
			$this->newProcessorNoofcoresArr = array_unique($this->newProcessorNoofcoresArr,SORT_REGULAR);
			$this->newScreenSizeArr = array_unique($this->newScreenSizeArr,SORT_REGULAR);
			$this->newAnnouncedArr = array_unique($this->newAnnouncedArr,SORT_REGULAR);

			if(sizeof($this->newPhoneTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newPhoneTypeArr).")";
			}
			if(sizeof($this->newAvailabilityArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newAvailabilityArr).")";
			}
			if(sizeof($this->newFormFactorArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newFormFactorArr).")";
			}
			if(sizeof($this->newInputMechanismArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newInputMechanismArr).")";
			}
			if(sizeof($this->newRAMArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newRAMArr).")";
			}
			if(sizeof($this->newFeaturesArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newFeaturesArr).")";
			}
			if(sizeof($this->newNetworkTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newNetworkTypeArr).")";
			}
			if(sizeof($this->newNoofSIMArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newNoofSIMArr).")";
			}
			if(sizeof($this->newNetworkArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newNetworkArr).")";
			}
			if(sizeof($this->newPrimaryCameraArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newPrimaryCameraArr).")";
			}
			if(sizeof($this->newOperatingSystemArr) > 0){

				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newOperatingSystemArr).")";
			}
			if(sizeof($this->newProcessorNoofcoresArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newProcessorNoofcoresArr).")";
			}
			if(sizeof($this->newScreenSizeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newScreenSizeArr).")";
			}
			if(sizeof($this->newAnnouncedArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_value='yes' and feature_id in (".implode(",",$this->newAnnouncedArr).")";
			}
			if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
		}
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[]=-1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[]=-1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[]='-1';}
		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
			$keyArr[] = "dc_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			$tablenameArr[] = "PRODUCT_NAME_INFO";
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
			$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
		}else{$keyArr[] = 'dc_-1_-1_-1_1';}
		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[]=-1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in($brand_ids)";
		}else{$keyArr[]=-1;}

		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[]=-1;}
		$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value != 0";
		$tablenameArr[] = "PRODUCT_MASTER";
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[]=-1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[]=-1;}
		if($check_discontinue_date != ""){
        		$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
		        $whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
	    }
        if(!empty($orderby)){
        	#$keyArr[] = str_replace(array("PRODUCT_MASTER","PRICE_VARIANT_VALUES"," ","."),"_",$orderby);
			$keyArr[] =  $orderby;
			$orderby = "order by $orderby";
		}else{	
			$keyArr[]=-1;
		}

		if($startlimit!=''){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[]=-1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[]=-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!is_array($result)){
			$table_name = implode(",",$tablenameArr);
			$sql = "select * FROM $table_name $whereClauseStr $orderby $limitStr";
			//echo $sql."<br>";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
		}
		if(strpos($orderby,"product_name")){
			$orderby="PRICE_VARIANT_VALUES.variant_value asc";
		}
		$result = $this->researchSummaryMobiles($result,$category_ids,$startprice,$endprice,$variant_id,"PRICE_VARIANT_VALUES.variant_value asc",$discontinue_flag,$check_discontinue_date);
		return $result;
	 }
	 /**
	* @note function is used to get global search summary
	*
	* @param is an array $result
	* @param an integer $category_ids.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param is a string $oredrby.
	*
	* @pre not required.
	*
	* @post is an associative array.
	* retun an array.
	*/
	 function globalSearchSummary($result,$category_id,$startprice,$endprice,$variant_id,$brand_ids="",$product_ids="",$feature_ids="",$orderby="PRICE_VARIANT_VALUES.variant_value asc",$discontinue_flag='',$check_discontinue_date=""){
		require_once(CLASSPATH.'feature.class.php');
		require_once(CLASSPATH.'overview.class.php');
		$feature = new FeatureManagement;
		$overview = new OverviewManagement;
		$overviewresult = $overview->arrGetCarFinderFeatureOverview();
		$overviewCnt = sizeof($overviewresult);
		$cnt = sizeof($result);
		for($i=0;$i<$cnt;$i++){
			$product_info = $result[$i]['product_info'];
			$product_result = $this->globalResearchPriceProductDetails($product_info,$category_id,$startprice,$endprice,$variant_id,$brand_ids,$product_ids,$feature_ids,$orderby,"1",$discontinue_flag,$check_discontinue_date);
			$productcnt = sizeof($product_result);
			for($j=0;$j<$productcnt;$j++){
				$product_id = $product_result[$j]['product_id'];
				$categoryid = $product_result[$j]['categoryid'];
				$product_info_name = $product_result[$j]['product_name'];
				$productNameData = $this->arrGetProductNameInfo("",$category_id,"",$product_info_name,"1");
				$sImagePath = $productNameData['0']['image_path'];
				$img_media_id = $productNameData['0']['img_media_id'];
				$product_result[$j]['model_image_path'] = $sImagePath;
				$product_result[$j]['model_image_id'] = $img_media_id;
				if(!empty($category_id) && !empty($product_id)){
					unset($overviewArr);
					for($overview=0;$overview<$overviewCnt;$overview++){
						unset($featureoverviewArr);unset($productfeaturekey);
						$overview_feature_id = $overviewresult[$overview]['feature_id'];
						$overview_title = $overviewresult[$overview]['title'];
						$overview_unit = $overviewresult[$overview]['abbreviation'];
						$productfeaturekey = $this->productKey."_feature_overview_$overview_feature_id"."_product_id".$product_id;
						$overviewfeature_details = $this->cache->get($productfeaturekey);
						if(sizeof($overviewfeature_details) <= 0){
							$sql = "select * from PRODUCT_FEATURE where feature_id = $overview_feature_id and product_id = $product_id";
							$overviewfeature_details = $this->select($sql);
							$this->cache->set($productfeaturekey,$overviewfeature_details);
						}
						$feature_value = $overviewfeature_details[0]['feature_value'];
						if($feature_value == "-"){$feature_value="";}
						if(!empty($feature_value)){
							$featureoverviewArr[] = $feature_value;
						}
						if(!empty($overview_unit) && !empty($feature_value)){
							$featureoverviewArr[] = $overview_unit;
						}
						if(sizeof($featureoverviewArr) > 0){
							$desc = implode(" ",$featureoverviewArr);
							if(!empty($desc) && !empty($overview_title)){
								#$desc = "<span class=\"b\">$overview_title:&#160;</span>$desc";
								$product_result[$j][$overview_title] = $desc;
								$overviewArr[$overview_title] = $desc;

								$desc = ($overview_title == 'Mileage') ? str_replace(array(' kmpl','kmpl'),array('',''),$desc) : $desc;


								$descArr = explode(',',$desc);
								if(is_array($descArr)){
									foreach($descArr as $v){
										if(!in_array($v,$featureArr)){
											if($v!=''){
												$featureArr[$overview_title][] = trim($v);
											}
										}
									}
								}else{
									if($desc!=''){
										$featureArr[$overview_title][] = trim($desc);
									}
								}
							}elseif(!empty($desc) && empty($overview_title)){
								$product_result[$j][$overview_title] = $desc;
								$overviewArr[] = $desc;
								$descArr = explode(',',$desc);
								if(is_array($descArr)){
									foreach($descArr as $v){
										if(!in_array($v,$featureArr)){
											if($v!=''){
												$featureArr[$overview_title][] = trim($v);
											}
										}
									}
								}else{
									if($desc!=''){
										$featureArr[$overview_title][] = trim($desc);
									}
								}
							}
						}
						/*
						if(sizeof($featureoverviewArr) > 0){
							$desc = implode(" ",$featureoverviewArr);
							if(!empty($desc) && !empty($overview_title)){
								#$desc = "<span class=\"b\">$overview_title:&#160;</span>$desc";
								$overviewArr[$overview_title] = $desc;
							}elseif(!empty($desc) && empty($overview_title)){
								$overviewArr[] = $desc;
							}
						}
						*/
					}
					#$product_result[$j]['short_desc'] = implode('<span class="dvder">|</span>',$overviewArr);
					//$product_result[$j]['short_desc'] = $overviewArr;
					$product_result[$j]['short_desc'] = implode(' | ',$overviewArr);
					unset($overviewArr);
				}
				/*
				if(!empty($category_id) && !empty($product_id)){

					$sOverviewArray = $feature->arrGetSummary($category_id,$product_id,$type="array");
				}
				if(is_array($sOverviewArray)){
					unset($productNameArr[0]);		// remove brand name form array.
					foreach($sOverviewArray as $key=>$val){
						if($sOverviewArray[$key][0]){
							$overviewArr[] = implode(",&#160;",$sOverviewArray[$key][0]);
						}
					}
					$product_result[$j]['short_desc'] = implode(",&#160;",$overviewArr);
					unset($overviewArr);
				}else{
					$product_result[$j]['short_desc'] = "";
				}
				*/

			}
			if($j == $productcnt){
				$featureArr['Mileage'] = array_unique($featureArr['Mileage']);
				if($featureArr['Mileage']!=''){
					$mileageArr[] = min($featureArr['Mileage']).' kmpl';
				}
				if($featureArr['Mileage']!=''){
					$mileageArr[] = max($featureArr['Mileage']).' kmpl';
				}
				$mileageArr = array_unique($mileageArr);
				$product_result[0]['model_mileage'] = implode(' - ',$mileageArr);

				$featureArr['Fuel'] = array_unique($featureArr['Fuel']);
				$product_result[0]['model_fuel'] = implode(', ',$featureArr['Fuel']);
				$featureArr['Engine'] = array_unique($featureArr['Engine']);

				unset($featureArr);unset($mileageArr);
			}
			$result[$i] = $product_result;
		}
		return $result;
	}
	 /**
	* @note function is used to get global research price product details
	*
	* @pre not required.
	*
	* @param string $product_info
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param is a string $oredrby.
	*
	* @post is an associative array.
	* retun an array.
	*/

	 function globalResearchPriceProductDetails($product_info,$category_id,$startprice,$endprice,$variant_id,$brand_ids="",$product_ids="",$feature_ids="",$orderby="PRICE_VARIANT_VALUES.variant_value asc",$default_city="1",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		$keyArr[] = $this->productKey."_carfinder_global_search";
		unset($tablenameArr);
		$this->assignPivotToSearch();
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
		if(is_array($feature_ids)){
			$feature_ids = implode(",",$feature_ids);
		}
		if(!empty($feature_ids)){
			$featureArr = explode(",",$feature_ids);
			foreach($featureArr as $feature_id){
				if(in_array($feature_id,$this->bodyStyleArr)){
					$keyArr[] = $feature_id;
					$this->newBodyStyleArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->fuelTypeArr)){
					$keyArr[] = $feature_id;
					$this->newFuelTypeArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->impFeatureArr)){
					$keyArr[] = $feature_id;
					$this->newImpFeatureArr[] = "select product_id from PRODUCT_FEATURE where feature_id in ($feature_id)";
				}elseif(in_array($feature_id,$this->tranmissionArr)){
					$keyArr[] = $feature_id;
					$this->newTranmissionArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->seatingCapcityArr)){
					$keyArr[] = $feature_id;
					$this->newSeatingCapcityArr[] = $feature_id;
				}
			}
			if(sizeof($this->newBodyStyleArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newBodyStyleArr).")";
			}
			if(sizeof($this->newFuelTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newFuelTypeArr).")";
			}
			if(sizeof($this->newImpFeatureArr) > 0){
				$sqlStr = "";
				foreach($this->newImpFeatureArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
				//$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newImpFeatureArr).")";
			}
			if(sizeof($this->newTranmissionArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newTranmissionArr).")";
			}
			if(sizeof($this->newSeatingCapcityArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newSeatingCapcityArr).")";
			}

			if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
		}
		if(!empty($product_info)){
			 $keyArr[] = $product_info;
			 $whereClauseArr[] = "PRODUCT_MASTER.product_name = '".trim($product_info)."'";
			 $whereClauseArr[] = "PRODUCT_MASTER.status=1";
		}else{ $keyArr[] = -1;}
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{ $keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{ $keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{ $keyArr[] = '-1';}
		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
			$keyArr[] = $startprice."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$default_city;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city = $default_city";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
		}else{$keyArr[] = "1_-1_-1_-1_-1";}
		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		$tablenameArr[] = "PRODUCT_MASTER";
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($count)){
			$keyArr[] =$count;
			$limitArr[] = $count;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$table_name = implode(",",$tablenameArr);
		$sql = "select PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.* FROM $table_name $whereClauseStr  order by $orderby";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	/**
	* @note function is used to get  features details.
	*
	* @param $category_id
	*
	* @post is an associative array.
	* retun an array.
	*/
	function arrGetFeaturesDetails($category_id){
		$key = $this->productKey."_feature_details_$category_id";
		if($result = $this->cache->get($key)){return $result;}
		$sSql="SELECT MFG.main_group_name, FSG.sub_group_name, FM.feature_name, MFG.*,FSG.*,FM.* FROM `FEATURE_MASTER` FM, MAIN_FEATURE_GROUP MFG, FEATURE_SUB_GROUP FSG WHERE FM.main_feature_group = MFG.group_id AND FM.feature_group = FSG.sub_group_id AND FM.category_id =1 ORDER BY MFG.main_group_name, FSG.sub_group_name ASC ";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get  features product data.
	*
	* @param $category_id
	* @param $brand_ids
	* @param is an integer product_ids $product_ids
	* @param $feature_ids
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @post is an associative array.
	* retun an array.
	*/
	function arrGetFeaturesProductData($category_id,$brand_ids,$product_ids,$feature_ids,$status,$startlimit,$cnt){
		$key = $this->productKey."_feature_product_$product_ids";
		if($result = $this->cache->get($key)){return $result;}
		$sql = "SELECT * FROM `PRODUCT_FEATURE` PF,`FEATURE_MASTER` FM WHERE PF.product_id =$product_ids and PF.product_id=FM.product_id";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetProductNameInfoCnt($product_name_ids="",$category_id="",$brand_id="",$product_info_name="",$status="1",$orderby="",$arrival_date_flag="",$discontinue_flag="1",$check_discontinue_date="",$search_status="",$upcoming_flag="0"){
		$keyArr[] = $this->productKey."_arrGetProductNameInfoCnt";
	    if(is_array($product_name_ids)){
	      foreach($product_name_ids as $model_id){
	        $i_model_ids = intval($model_id);
	        if($i_model_ids!=0){
	          $model_ids[] = $i_model_ids;
	        }
	      }
	      $product_name_ids = implode(",",$model_ids);
	    }else{
	      if(strpos($product_name_ids,',')==false){
	        //if(intval($product_name_ids)!=0){
	          $product_name_ids = intval($product_name_ids);
	        //}
	      }else{
	        $arr_model_ids = explode(",",$product_name_ids);
	        foreach($arr_model_ids as $model_id){
	          $i_model_ids = intval($model_id);
	          if($i_model_ids!=0){
	            $model_ids[] = $i_model_ids;
	          }
	        }
	        $product_name_ids = implode(",",$model_ids);
	      }
	    }
		if($category_id != ""){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in($category_id)";
		}else{$keyArr[] = -1;}
		if($brand_id != ""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_name_ids)){
			$keyArr[] = $product_name_ids;
			$whereClauseArr[] = "product_name_id in($product_name_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_name)){
			$keyArr[] = $product_info_name;
			$product_info_name = strtolower($product_info_name);
			$whereClauseArr[] = "product_info_name = '$product_info_name'";
		}else{$keyArr[] = -1;}
		if($arrival_date_flag == "1"){
			$keyArr[] = $arrival_date;
			$whereClauseArr[] = "arrival_date !='0000-00-00'";
		}else{$keyArr[] = -1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ""){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "discontinue_flag = $discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(discontinue_date >= '$prev_3_mon_date' OR discontinue_date='0000-00-00 00:00:00')";
		}
		if($search_status != ""){
			if($search_status == "upcoming"){
	            $keyArr[] = $search_status;
                $whereClauseArr[] = "upcoming_flag = 1";
			}else if($search_status == "discontinue"){
				$keyArr[] = $search_status;
                $whereClauseArr[] = "discontinue_flag = 0";
			}
        }else{$keyArr[] = -1;}
		if($upcoming_flag != ""){
			$keyArr[] = $upcoming_flag;
			$whereClauseArr[] = "upcoming_flag = $upcoming_flag";
		}else{$keyArr[] = -1;}

		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if($orderby==''){
			$orderby='order by product_name_id asc';
		}else{
		 	$orderby=$orderby;
		}
#		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){
			return $result;
		}

		$sSql = "select count(product_name_id) as cnt from PRODUCT_NAME_INFO $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$res = $this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get  product name info.
	* @param an integer/comma seperated product name ids/ product name ids array $product_name_ids.
	* @param an integer/comma seperated category_ids  $category_id.
	* @param an integer/comma seperated brand_ids  $brand_id.
	* @param string product_info_name $product_info_name
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post is an associative array.
	* retun an array.
	arrGetCheckUpcomimgProductNameInfo
	*/
	function arrGetProductNameInfo($product_name_ids="",$category_id="",$brand_id="",$product_info_name="",$status="1",$startlimit="",$cnt="",$orderby="",$arrival_date_flag="",$discontinue_flag="1",$check_discontinue_date="",$search_status="",$upcoming_flag="0"){
		$keyArr[] = $this->productKey."_arrGetProductNameInfo";
	    if(is_array($product_name_ids)){
	      foreach($product_name_ids as $model_id){
	        $i_model_ids = intval($model_id);
	        if($i_model_ids!=0){
	          $model_ids[] = $i_model_ids;
	        }
	      }
	      $product_name_ids = implode(",",$model_ids);
	    }else{
	      if(strpos($product_name_ids,',')==false){
	        //if(intval($product_name_ids)!=0){
	          $product_name_ids = intval($product_name_ids);
	        //}
	      }else{
	        $arr_model_ids = explode(",",$product_name_ids);
	        foreach($arr_model_ids as $model_id){
	          $i_model_ids = intval($model_id);
	          if($i_model_ids!=0){
	            $model_ids[] = $i_model_ids;
	          }
	        }
	        $product_name_ids = implode(",",$model_ids);
	      }
	    }
		if($category_id != ""){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in($category_id)";
		}else{$keyArr[] = -1;}
		if($brand_id != ""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_name_ids)){
			$keyArr[] = $product_name_ids;
			$whereClauseArr[] = "product_name_id in($product_name_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_name)){
			$keyArr[] = $product_info_name;
			//$product_info_name = strtolower($product_info_name);
			$whereClauseArr[] = "product_info_name = '$product_info_name'";
		}else{$keyArr[] = -1;}
		if($arrival_date_flag == "1"){
			$keyArr[] = $arrival_date;
			$whereClauseArr[] = "arrival_date !='0000-00-00'";
		}else{$keyArr[] = -1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ""){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "discontinue_flag = $discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(discontinue_date >= '$prev_3_mon_date' OR discontinue_date='0000-00-00 00:00:00')";
			$keyArr[] = $check_discontinue_date."_".$prev_3_mon_date;

		}else{$keyArr[] = '-1_-1';}

		if($search_status != ""){
			if($search_status == "upcoming"){
                $whereClauseArr[] = "upcoming_flag = 1";
			}else if($search_status == "discontinue"){
                $whereClauseArr[] = "discontinue_flag = 0";
			}
	            $keyArr[] = $search_status;
        }else{$keyArr[] = -1;}

		if($upcoming_flag != ""){
			$keyArr[] = $upcoming_flag;
			$whereClauseArr[] = "upcoming_flag = $upcoming_flag";
		}else{$keyArr[] = -1;}
		/*else if(($upcoming_flag == "") || ($upcoming_flag == '0')){
			$keyArr[] = "upcoming_flag_$upcoming_flag";
            $whereClauseArr[] = "upcoming_flag = 0";
		}
		*/
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if($orderby==''){
			$orderby='order by product_info_name asc';
		}else{
		 $orderby=$orderby;
		}
		$keyArr[] = $orderby;
		$key = implode("_",$keyArr);

		if($result = $this->cache->get($key)){return $result;}
		$sSql = "select * from PRODUCT_NAME_INFO $whereClauseStr $orderby $limitStr";
		//echo " sSql - $sSql";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	
	function arrGetCheckUpcomimgProductNameInfo($product_name_ids="",$category_id="",$brand_id="",$product_info_name="",$status="1",$startlimit="",$cnt="",$orderby="",$arrival_date_flag="",$discontinue_flag="1",$check_discontinue_date="",$search_status="",$upcoming_flag="0"){
		$keyArr[] = $this->productKey."_arrGetProductNameInfo";
	    if(is_array($product_name_ids)){
	      foreach($product_name_ids as $model_id){
	        $i_model_ids = intval($model_id);
	        if($i_model_ids!=0){
	          $model_ids[] = $i_model_ids;
	        }
	      }
	      $product_name_ids = implode(",",$model_ids);
	    }else{
	      if(strpos($product_name_ids,',')==false){
	        //if(intval($product_name_ids)!=0){
	          $product_name_ids = intval($product_name_ids);
	        //}
	      }else{
	        $arr_model_ids = explode(",",$product_name_ids);
	        foreach($arr_model_ids as $model_id){
	          $i_model_ids = intval($model_id);
	          if($i_model_ids!=0){
	            $model_ids[] = $i_model_ids;
	          }
	        }
	        $product_name_ids = implode(",",$model_ids);
	      }
	    }
		if($category_id != ""){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in($category_id)";
		}else{$keyArr[] = -1;}
		if($brand_id != ""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_name_ids)){
			$keyArr[] = $product_name_ids;
			$whereClauseArr[] = "product_name_id in($product_name_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_name)){
			$keyArr[] = $product_info_name;
			//$product_info_name = strtolower($product_info_name);
			$whereClauseArr[] = "product_info_name = '$product_info_name'";
		}else{$keyArr[] = -1;}
		if($arrival_date_flag == "1"){
			$keyArr[] = $arrival_date;
			$whereClauseArr[] = "arrival_date !='0000-00-00'";
		}else{$keyArr[] = -1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ""){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "discontinue_flag = $discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(discontinue_date >= '$prev_3_mon_date' OR discontinue_date='0000-00-00 00:00:00')";
			$keyArr[] = $check_discontinue_date."_".$prev_3_mon_date;

		}else{$keyArr[] = '-1_-1';}

		if($search_status != ""){
			if($search_status == "upcoming"){
                $whereClauseArr[] = "upcoming_flag = 1";
			}else if($search_status == "discontinue"){
                $whereClauseArr[] = "discontinue_flag = 0";
			}
	            $keyArr[] = $search_status;
        }else{$keyArr[] = -1;}

		if($upcoming_flag == "1"){
			$keyArr[] = $upcoming_flag;
			$whereClauseArr[] = "upcoming_flag = $upcoming_flag";
		}else{$keyArr[] = -1;}
		/*else if(($upcoming_flag == "") || ($upcoming_flag == '0')){
			$keyArr[] = "upcoming_flag_$upcoming_flag";
            $whereClauseArr[] = "upcoming_flag = 0";
		}
		*/
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if($orderby==''){
			$orderby='order by product_info_name asc';
		}else{
		 $orderby=$orderby;
		}
		$keyArr[] = $orderby;
		$key = implode("_",$keyArr);

		if($result = $this->cache->get($key)){return $result;}
		 $sSql = "select * from PRODUCT_NAME_INFO $whereClauseStr $orderby $limitStr";
		
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}



	function arrGetProductNameInfoTest($product_name_ids="",$category_id="",$brand_id="",$product_info_name="",$status="1",$startlimit="",$cnt="",$orderby="",$arrival_date_flag="",$discontinue_flag="1",$check_discontinue_date="",$search_status="",$upcoming_flag="0"){
$keyArr[] = $this->productKey."_arrGetProductNameInfoTest";
if(is_array($product_name_ids)){
foreach($product_name_ids as $model_id){
$i_model_ids = intval($model_id);
if($i_model_ids!=0){
$model_ids[] = $i_model_ids;
}
}
$product_name_ids = implode(",",$model_ids);
}else{
if(strpos($product_name_ids,',')==false){
//if(intval($product_name_ids)!=0){
$product_name_ids = intval($product_name_ids);
//}
}else{
	$arr_model_ids = explode(",",$product_name_ids);
	foreach($arr_model_ids as $model_id){
	$i_model_ids = intval($model_id);
	if($i_model_ids!=0){
	$model_ids[] = $i_model_ids;
	}
	}
	$product_name_ids = implode(",",$model_ids);
	}
}
if($category_id != ""){
	$keyArr[] = $category_id;
	$whereClauseArr[] = "category_id in($category_id)";
}else{$keyArr[] = -1;}

if($brand_id != ""){
	$keyArr[] = $brand_id;
	$whereClauseArr[] = "brand_id in($brand_id)";
}else{$keyArr[] = -1;}
if(!empty($product_name_ids)){
	$keyArr[] = $product_name_ids;
	$whereClauseArr[] = "product_name_id in($product_name_ids)";
}else{$keyArr[] = -1;}
if(!empty($product_info_name)){
	$keyArr[] = $product_info_name;
	$product_info_name = strtolower($product_info_name);
	$whereClauseArr[] = "product_info_name = '$product_info_name'";
}else{$keyArr[] = -1;}
if($arrival_date_flag == "1"){
	$keyArr[] = $arrival_date;
	$whereClauseArr[] = "arrival_date !='0000-00-00'";
}else{$keyArr[] = -1;}
if($status != ""){
	$keyArr[] = $status;
	$whereClauseArr[] = "status = $status";
}else{$keyArr[] = -1;}
if($discontinue_flag != ""){
	$keyArr[] = $discontinue_flag;
	$whereClauseArr[] = "discontinue_flag = $discontinue_flag";
}else{$keyArr[] = -1;}
if($check_discontinue_date != ""){
$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
$whereClauseArr[] = "(discontinue_date >= '$prev_3_mon_date' OR discontinue_date='0000-00-00 00:00:00')";
}
if($search_status != ""){
	if($search_status == "upcoming"){

	$whereClauseArr[] = "upcoming_flag = 1";
	}else if($search_status == "discontinue"){
	$whereClauseArr[] = "discontinue_flag = 0";
	}
	$keyArr[] = $search_status;
}else{$keyArr[] = -1;}
if($upcoming_flag != ""){
$keyArr[] = $upcoming_flag;
$whereClauseArr[] = "upcoming_flag = $upcoming_flag";
}else{$keyArr[] = -1;}
/*else if(($upcoming_flag == "") || ($upcoming_flag == '0')){
$keyArr[] = "upcoming_flag_$upcoming_flag";
$whereClauseArr[] = "upcoming_flag = 0";
}
*/
if(!empty($startlimit)){
$keyArr[] = $startlimit;
$limitArr[] = $startlimit;
}else{$keyArr[] = -1;}
if(!empty($cnt)){
	$keyArr[] = $cnt;
	$limitArr[] = $cnt;
}else{$keyArr[] = -1;}

if(sizeof($limitArr) > 0){
$limitStr = " limit ".implode(" , ",$limitArr);
}
if(sizeof($whereClauseArr) > 0){
$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
}
if($orderby==''){
$orderby='order by product_info_name asc';

}else{
$orderby=$orderby;
}
$keyArr[] = $orderby;
$key = implode("_",$keyArr);
if($result = $this->cache->get($key)){return $result;}
$sSql = "select * from PRODUCT_NAME_INFO $whereClauseStr $orderby $limitStr";
$result = $this->select($sSql);

$this->cache->set($key,$result);
return $result;
}





	/**
	* @note function is used to insert the product name information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $product_name_id.
	* retun integer.
	*/
	function intInsertProductNameInfo($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("PRODUCT_NAME_INFO",array_keys($insert_param),array_values($insert_param));
		$product_name_id = $this->insert($sql);
		if($product_name_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productKey."_model");
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->arrGetProductNameInfo($product_name_id);
		return $product_name_id;
	}
	/**
	* @note function is used to update product name information in the database.
	* @param an integer $product_name_id.
	* @param an associative array $update_param.
	* @pre $update_param must be valid associative array.
	* @post an integer $product_name_id.
	* retun integer.
	*/
	function boolUpdateProductNameInfo($product_name_id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("PRODUCT_NAME_INFO",array_keys($update_param),array_values($update_param),"product_name_id",$product_name_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productKey."_model");
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		$this->arrGetProductNameInfo($product_name_id);
		return $isUpdate;
	}
	/**
        * @note function is used to insert the Top Competitor information into the database.
        * @param an associative array $insert_param.
        * @pre $insert_param must be valid associative array.
        * @post an integer $competitor_product_id.
        * retun integer.
        */
        function addInsertNewsArticleTopCompetitorDetails($insert_param)
        {
                $insert_param['create_date'] = date('Y-m-d H:i:s');
                $sql = $this->getInsertSql("NEWS_ARTICLE_TOP_COMPETITOR",array_keys($insert_param),array_values($insert_param));
                $competitor_product_id = $this->insert($sql);
			if($competitor_product_id == 'Duplicate entry'){
				return  'exists';
			}
                $this->cache->searchDeleteKeys($this->productKey."news_article_top_cometitor");
                return $competitor_product_id;
        }
	function addUpdTitleNewsArticleTopCompetitorDetails($id,$insert_param)
        {
                $insert_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $this->getUpdateSql("NEWS_ARTICLE_TOP_COMPETITOR",array_keys($insert_param),array_values($insert_param),'id',$id);
#                echo $sql;
                $isUpdate = $this->update($sql);
                $this->cache->searchDeleteKeys($this->productKey."news_article_top_cometitor");
                return $isUpdate;
        }
	function addUpdNewsArticleTopCompetitorDetails($top_competitor_id,$insert_param)
        {
                $insert_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $this->getUpdateSql("NEWS_ARTICLE_TOP_COMPETITOR",array_keys($insert_param),array_values($insert_param),'top_competitor_id',$top_competitor_id);
		#echo $sql;
                $isUpdate = $this->update($sql);
                $this->cache->searchDeleteKeys($this->productKey."news_article_top_cometitor");
                return $isUpdate;
        }
	/**
	* @note function is used to insert the Top Competitor information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $competitor_product_id.
	* retun integer.
	*/
	function addUpdTopCompetitorDetails($insert_param)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("TOP_COMPETITOR",array_keys($insert_param),array_values($insert_param));
		$competitor_product_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->productKey."_top_cometitor");
		return $competitor_product_id;
	}
	/**
        * @note function is used to delete the TopCompetitor Detail.
        * @param integer $competitor_product_id.
        * @pre $competitor_product_id must be non-empty/zero valid integer.
        * @post boolean true/false.
        * return boolean.
        */
        function boolDeleteNewsArticleTopCompetitorDetail($competitor_product_id)
        {
                $sql = "delete from NEWS_ARTICLE_TOP_COMPETITOR where top_competitor_id = $competitor_product_id";
                $isDelete = $this->sql_delete_data($sql);
                $this->cache->searchDeleteKeys($this->productKey."news_article_top_cometitor");
                return $isDelete;
        }
	/**
	* @note function is used to delete the TopCompetitor Detail.
	* @param integer $competitor_product_id.
	* @pre $competitor_product_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteTopCompetitorDetail($competitor_product_id)
	{
		$sql = "delete from TOP_COMPETITOR where competitor_product_id = $competitor_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_top_cometitor");
		return $isDelete;
	}

	/**
	* @note function is used to get  product details.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer category_id.
	* @param an integer brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post product details in associative array.
	* retun an array.
	*/
	function arrGetProductCompetitorDetails($top_competitor_ids="",$product_ids="",$brand_ids="",$category_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->productKey."_top_cometitor";
		if(is_array($top_competitor_ids)){
			$top_competitor_ids = implode(",",$top_competitor_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if($top_competitor_ids != ""){
			$keyArr[] = $top_competitor_ids;
			$whereClauseArr[] = " competitor_product_id in($top_competitor_ids)";
		}else{$keyArr[] = -1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if($brand_ids != ""){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if($product_ids != ""){
			$keyArr[] =$product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql = "select * from TOP_COMPETITOR $whereClauseStr $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get Product Competitor Details.
	* @param an integer/comma seperated top competitor ids/ top competitor ids array $top_competitor_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post result is in associative array.
	* retun an array.
	*/
	function arrGetProdCompetitorDetails($top_competitor_ids="",$product_ids="",$product_info_ids="",$brand_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$skipsamevariant="0",$skipsamemodel="0",$skipsamebrand="0"){
		$keyArr[] = $this->productKey."_arrGetProdCompetitorDetails";
		if(is_array($top_competitor_ids)){
			$top_competitor_ids = implode(",",$top_competitor_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
    if(is_array($product_info_ids)){
      foreach($product_info_ids as $model_id){
        $i_model_ids = intval($model_id);
        if($i_model_ids!=0){
          $model_ids[] = $i_model_ids;
        }
      }
      $product_info_ids = implode(",",$model_ids);
    }else{
      if(strpos($product_info_ids,',')==false){
        if(intval($product_info_ids)!=0){
          $product_info_ids = intval($product_info_ids);
        }
      }else{
        $arr_model_ids = explode(",",$product_info_ids);
        foreach($arr_model_ids as $model_id){
          $i_model_ids = intval($model_id);
          if($i_model_ids!=0){
            $model_ids[] = $i_model_ids;
          }
        }
        $product_info_ids = implode(",",$model_ids);
      }
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		$tableArr = Array('TOP_COMPETITOR as TC','PRICE_VARIANT_VALUES as PV');
                $whereClauseArr[] = "PV.default_city = 1";
                $whereClauseArr[] = "PV.variant_id = 1";
                $whereClauseArr[] = "PV.product_id = TC.product_ids";

		if($top_competitor_ids != ""){
			$keyArr[] = $top_competitor_ids;
			$whereClauseArr[] = "TC.competitor_product_id in($top_competitor_ids)";
		}else{$keyArr[] = -1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "TC.category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if($brand_ids != ""){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "TC.brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($skipsamevariant)){
			$keyArr[] = $product_ids;
			$groupbyArr[] = 'TC.product_ids';
			$whereClauseArr[] = "TC.product_id != TC.product_ids";
        }else{$keyArr[] = -1;}
        if(!empty($skipsamemodel)){
			$keyArr[] = $product_info_ids;
			$groupbyArr[] = 'TC.product_info_ids';
            $whereClauseArr[] = "TC.product_info_id != TC.product_info_ids";
        }else{$keyArr[] = -1;}
        if(!empty($skipsamebrand)){
			$keyArr[] = $brand_ids;
			$groupbyArr[] = 'TC.brand_ids';
			$whereClauseArr[] = "TC.brand_id != TC.brand_ids";
        }else{$keyArr[] = -1;}

		if($product_info_ids != ""){
			$keyArr[] = $product_info_ids;
			$whereClauseArr[] = "TC.product_info_id != TC.product_info_ids";
			$whereClauseArr[] = "TC.product_info_id in($product_info_ids)";
		}else{$keyArr[] = -1;}

		if($product_ids != ""){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "TC.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = "TC.status = $status";
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($groupbyArr) > 0){
			$groupby = ' group by '.implode(',',$groupbyArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}

		$table = implode(', ',$tableArr);

		$sSql="select * from $table $whereClauseStr $groupby order by position asc $limitStr";
		//echo $sSql;
		#$sSql="select * from TOP_COMPETITOR $whereClauseStr order by position asc $limitStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetProdCompetitorDetailsCnt($top_competitor_ids="",$product_ids="",$product_info_ids="",$brand_ids="",$category_ids="",$status="1",$skipsamevariant="0",$skipsamemodel="0",$skipsamebrand="0"){
		$keyArr[] = $this->productKey."_arrGetProdCompetitorDetailsCnt";
		if(is_array($top_competitor_ids)){
			$top_competitor_ids = implode(",",$top_competitor_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
    if(is_array($product_info_ids)){
      foreach($product_info_ids as $model_id){
        $i_model_ids = intval($model_id);
        if($i_model_ids!=0){
          $model_ids[] = $i_model_ids;
        }
      }
      $product_info_ids = implode(",",$model_ids);
    }else{
      if(strpos($product_info_ids,',')==false){
        if(intval($product_info_ids)!=0){
          $product_info_ids = intval($product_info_ids);
        }
      }else{
        $arr_model_ids = explode(",",$product_info_ids);
        foreach($arr_model_ids as $model_id){
          $i_model_ids = intval($model_id);
          if($i_model_ids!=0){
            $model_ids[] = $i_model_ids;
          }
        }
        $product_info_ids = implode(",",$model_ids);
      }
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		$tableArr = Array('TOP_COMPETITOR as TC','PRICE_VARIANT_VALUES as PV');
		$whereClauseArr[] = "PV.default_city = 1";
		$whereClauseArr[] = "PV.variant_id = 1";
		$whereClauseArr[] = "PV.product_id = TC.product_ids";
		/*
		$tableArr = Array('PRODUCT_MASTER as PM','PRODUCT_NAME_INFO as PI','TOP_COMPETITOR as TC');
		$whereClauseArr[] = "PM.status = 1";
		$whereClauseArr[] = "PM.discontinue_flag = 1";
		$whereClauseArr[] = "PI.status = 1";
		$whereClauseArr[] = "PI.discontinue_flag = 1";
		$whereClauseArr[] = "PI.upcoming_flag = 0";
		*/
		if($top_competitor_ids != ""){
			$keyArr[] = $top_competitor_ids;
			$whereClauseArr[] = " TC.competitor_product_id in($top_competitor_ids)";
		}else{$keyArr[] = -1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "TC.category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if($brand_ids != ""){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "TC.brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if($product_info_ids != ""){
			$keyArr[] = $product_info_ids;
			$whereClauseArr[] = "TC.product_info_id in($product_info_ids)";
		}else{$keyArr[] = -1;}
		if($product_ids != ""){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "TC.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($skipsamevariant)){
            $keyArr[] 		= $product_ids;
			$groupbyArr[] 	= 'TC.product_ids';
            $whereClauseArr[] = "TC.product_id != TC.product_ids";
        }else{$keyArr[] = -1;}
        if(!empty($skipsamemodel)){
			$keyArr[] = $product_info_ids;
			$groupbyArr[] = 'TC.product_info_ids';
			$whereClauseArr[] = "TC.product_info_id != TC.product_info_ids";
        }else{$keyArr[] = -1;}
		if(!empty($skipsamebrand)){
			$keyArr[] = $brand_ids;
			$groupbyArr[] = 'TC.brand_ids';
			$whereClauseArr[] = "TC.brand_ids != TC.brand_id ";
		}else{$keyArr[] = -1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = "TC.status = $status";
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($groupbyArr) > 0){
                        $groupby = ' group by '.implode(',',$groupbyArr);
                }
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$table = implode(', ' ,$tableArr);
		$sSql="select count(competitor_product_id) as cnt from $table $whereClauseStr $groupby";
		#echo $sSql;die();
		#$sSql="select count(competitor_product_id) as cnt from TOP_COMPETITOR $whereClauseStr";
		$result=$this->select($sSql);
		if(sizeof($groupbyArr) > 0){
			$cnt = is_array($result) ? sizeof($result) : 0;
			unset($result);
			$result[0]['cnt'] = $cnt;
		}
		$this->cache->set($key,$result);
		return $result;
	}
/**
	* @note function is used to get Product Competitor Details.
	* @param an integer/comma seperated top competitor ids/ top competitor ids array $top_competitor_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	* @post result is in associative array.
	* retun an array.
	*/
	function arrGetNewsArticleCompetitorDetails($top_competitor_ids="",$type="",$id="",$product_ids="",$product_info_ids="",$brand_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$skipsamevariant="0",$orderby='order by top_competitor_id desc',$skipsamemodel="0",$skipsamebrand="0"){
		$keyArr[] = $this->productKey."news_article_top_cometitor";
		if(is_array($top_competitor_ids)){
			$top_competitor_ids = implode(",",$top_competitor_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
    if(is_array($product_info_ids)){
      foreach($product_info_ids as $model_id){
        $i_model_ids = intval($model_id);
        if($i_model_ids!=0){
          $model_ids[] = $i_model_ids;
        }
      }
      $product_info_ids = implode(",",$model_ids);
    }else{
      if(strpos($product_info_ids,',')==false){
        if(intval($product_info_ids)!=0){
          $product_info_ids = intval($product_info_ids);
        }
      }else{
        $arr_model_ids = explode(",",$product_info_ids);
        foreach($arr_model_ids as $model_id){
          $i_model_ids = intval($model_id);
          if($i_model_ids!=0){
            $model_ids[] = $i_model_ids;
          }
        }
        $product_info_ids = implode(",",$model_ids);
      }
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
	if($type != ""){
		$keyArr[] = $type;
		$whereClauseArr[] = " type = $type";
	}else{$keyArr[] = -1;}
	if($id != ""){
		$keyArr[] = $id;
		$whereClauseArr[] = " id in($id)";
	}else{$keyArr[] = -1;}

	if($top_competitor_ids != ""){
		$keyArr[] = $top_competitor_ids;
		$whereClauseArr[] = " top_competitor_id in($top_competitor_ids)";
	}else{$keyArr[] = -1;}

	if($category_ids != ""){
		$keyArr[] = $category_ids;
		$whereClauseArr[] = "category_id in($category_ids)";
	}else{$keyArr[] = -1;}
	if($brand_ids != ""){
		$keyArr[] = $brand_ids;
		$whereClauseArr[] = "brand_id in($brand_ids)";
	}else{$keyArr[] = -1;}
	if(!empty($skipsamevariant)){
		$keyArr[] = $product_ids;
		$groupbyArr[] = 'product_ids';
		$whereClauseArr[] = "product_id != product_ids";
	}else{$keyArr[] = -1;}
	if(!empty($skipsamemodel)){
		$keyArr[] = $product_info_ids;
		$groupbyArr[] = 'product_info_ids';
		$whereClauseArr[] = "product_info_id != product_info_ids";
	}else{$keyArr[] = -1;}
	if(!empty($skipsamebrand)){
		$keyArr[] = $brand_ids;
		$groupbyArr[] = 'brand_ids';
		$whereClauseArr[] = "brand_id != brand_ids";
	}else{$keyArr[] = -1;}

	if($product_info_ids != ""){
		$keyArr[] = $product_info_ids;
		$whereClauseArr[] = "product_info_id != product_info_ids";
		$whereClauseArr[] = "product_info_id in($product_info_ids)";
	}else{$keyArr[] = -1;}
	if($product_ids != ""){
		$keyArr[] = $product_ids;
		$whereClauseArr[] = "product_id in($product_ids)";
	}else{$keyArr[] = -1;}
	if($status != ""){
		$keyArr[] = $status;
		$whereClauseArr[] = "status = $status";
	}else{$keyArr[] = -1;}
	if(!empty($startlimit)){
		$keyArr[] = $startlimit;
		$limitArr[] = $startlimit;
	}else{$keyArr[] = -1;}
	if(!empty($cnt)){
		$keyArr[] = $cnt;
		$limitArr[] = $cnt;
	}else{$keyArr[] = -1;}
	if(sizeof($limitArr) > 0){
		$limitStr = " limit ".implode(" , ",$limitArr);
	}
	if(sizeof($whereClauseArr) > 0){
		$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
	}
	if(sizeof($groupbyArr) > 0){
		$groupby = ' group by '.implode(',',$groupbyArr);
	}
	$key = implode("_",$keyArr);

	if($result = $this->cache->get($key)){return $result;}

	$sSql="select * from NEWS_ARTICLE_TOP_COMPETITOR $whereClauseStr $groupby $orderby $limitStr";
	$result=$this->select($sSql);
	$this->cache->set($key,$result);
	return $result;
	}
	function arrGetNewsArticleCompetitorDetailsCnt($top_competitor_ids="",$type="",$id="",$product_ids="",$product_info_ids="",$brand_ids="",$category_ids="",$status="1",$skipsamevariant="0",$skipsamemodel="0",$skipsamebrand="0"){

		$keyArr[] = $this->productKey."news_article_top_cometitor_cnt";
		if(is_array($top_competitor_ids)){
			$top_competitor_ids = implode(",",$top_competitor_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
    if(is_array($product_info_ids)){
      foreach($product_info_ids as $model_id){
        $i_model_ids = intval($model_id);
        if($i_model_ids!=0){
          $model_ids[] = $i_model_ids;
        }
      }
      $product_info_ids = implode(",",$model_ids);
    }else{
      if(strpos($product_info_ids,',')==false){
        if(intval($product_info_ids)!=0){
          $product_info_ids = intval($product_info_ids);
        }
      }else{
        $arr_model_ids = explode(",",$product_info_ids);
        foreach($arr_model_ids as $model_id){
          $i_model_ids = intval($model_id);
          if($i_model_ids!=0){
            $model_ids[] = $i_model_ids;
          }
        }
        $product_info_ids = implode(",",$model_ids);
      }
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if($top_competitor_ids != ""){
			$keyArr[] = $top_competitor_ids;
			$whereClauseArr[] = " top_competitor_id in($top_competitor_ids)";
		}else{$keyArr[] = -1;}
		if($type != ''){
		    $keyArr[] = $type;
		    $whereClauseArr[] = " type = $type";
		}else{$keyArr[] = -1;}
		if($id != ""){
			$keyArr[] = $id;
			$whereClauseArr[] = " id in($id)";
		}else{$keyArr[] = -1;}

		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if($brand_ids != ""){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if($product_info_ids != ""){
			$keyArr[] = $product_info_ids;
			$whereClauseArr[] = "product_info_id in($product_info_ids)";
		}else{$keyArr[] = -1;}
		if($product_ids != ""){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($skipsamevariant)){
			$keyArr[] = $product_ids;
			$groupbyArr[] = 'product_ids';
			$whereClauseArr[] = "product_id != product_ids";
		}else{$keyArr[] = -1;}
		if(!empty($skipsamemodel)){
			$keyArr[] = $product_info_ids;
			$groupbyArr[] = 'product_info_ids';
			$whereClauseArr[] = "product_info_id != product_info_ids";
		}else{$keyArr[] = -1;}
		if(!empty($skipsamebrand)){
			$keyArr[] = $brand_ids;
			$groupbyArr[] = 'brand_ids';
			$whereClauseArr[] = "brand_ids != brand_id ";
		}else{$keyArr[] = -1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = "status = $status";
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($groupbyArr) > 0){
                        $groupby = ' group by '.implode(',',$groupbyArr);
                }
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select count(top_competitor_id) as cnt from NEWS_ARTICLE_TOP_COMPETITOR $whereClauseStr $groupby";
		$result=$this->select($sSql);
		if(sizeof($groupbyArr) > 0){
			$result[0]['cnt'] = sizeof($result);
		}
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to insert the Top Competitor information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $competitor_product_id.
	* retun integer.
	*/
	function addUpdCompareTopCompetitorDetails($insert_param)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("COMPARE_TOP_COMPETITOR",array_keys($insert_param),array_values($insert_param));
		$competitor_product_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->productKey."_compare_top_cometitor");
		$this->cache->searchDeleteKeys($this->compareKey);

		return $competitor_product_id;
	}

	/**
	* @note function is used to delete the TopCompetitor Detail.
	* @param integer $competitor_product_id.
	* @pre $competitor_product_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteCompareTopCompetitorDetail($competitor_product_id)
	{
		$sql = "delete from COMPARE_TOP_COMPETITOR where competitor_product_id = $competitor_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_compare_top_cometitor");
		$this->cache->searchDeleteKeys($this->compareKey);
		return $isDelete;
	}

	/**
	* @note function is used to get  product details.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer category_id.
	* @param an integer brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post product details in associative array.
	* retun an array.
	*/
	function arrGetProductCompareCompetitorDetails($top_competitor_ids="",$product_ids="",$brand_ids="",$category_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->productKey."_compare_top_cometitor";
		if(is_array($top_competitor_ids)){
			$top_competitor_ids = implode(",",$top_competitor_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
       // }
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
		if($top_competitor_ids != ""){
			$keyArr[] = $top_competitor_ids;
			$whereClauseArr[] = " competitor_product_id in($top_competitor_ids)";
		}else{$keyArr[] = -1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if($brand_ids != ""){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if($product_ids != ""){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
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
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}

		$sSql="select * from COMPARE_TOP_COMPETITOR $whereClauseStr order by create_date desc $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		//print_r($result);
		return $result;
	}


	/**
	* @note function is used to insert the Top Competitor information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $compare_id.
	* retun integer.
	*/
	function addUpdMostPopularSetDetails($insert_param)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("MOST_POPULAR_COMPARE_SET_MASTER",array_keys($insert_param),array_values($insert_param));
		$compare_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->productKey."_popular_compare_set");
		return $compare_id;
	}

	/**
	* @note function is used to delete the TopCompetitor Detail.
	* @param integer $compare_id.
	* @pre $compare_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteMostPopularSetDetail($compare_id)
	{
		$sql = "delete from MOST_POPULAR_COMPARE_SET_MASTER where compare_id = $compare_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_popular_compare_set");
		return $isDelete;
	}

	/**
	* @note function is used to get  most popular set details.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer category_id.
	* @param an integer brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post product details in associative array.
	* retun an array.
	*/
	function arrGetMostPopularSetDetails($compare_ids="",$product_ids="",$brand_ids="",$category_ids="",$status="1",$startlimit="",$count=""){
		$keyArr[] = $this->productKey."_popular_compare_set";
		if(is_array($compare_ids)){
			$compare_ids = implode(",",$compare_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if($compare_ids != ""){
			$keyArr[] = $compare_ids;
			$whereClauseArr[] = " compare_id in($compare_ids)";
		}else{$keyArr[] = -1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if($brand_ids != ""){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if($product_ids != ""){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "compare_set in($product_ids)";
		}else{$keyArr[] = -1;}
		if($startlimit!=''){
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
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select * from MOST_POPULAR_COMPARE_SET_MASTER $whereClauseStr $limitStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to delete model from PRODUCT_NAME_INFO table
	* @param integer $product_name_id
	* @pre $product_name_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function deleteModel($product_name_id){
		$sql = "delete from PRODUCT_NAME_INFO where product_name_id = $product_name_id";
		$result = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_model");
		$this->cache->searchDeleteKeys(GET_ROUTER_VARIANT_KEY);
                $this->cache->searchDeleteKeys(GET_ROUTER_MODEL_KEY);
		$this->cache->searchDeleteKeys(GET_ROUTER_COMPARE_VARIANT);
		return $result;
	}
	/**
	* @note function is used to get Product count by price ascending
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category_ids/ category_id array $category_id.
	* @param an integer/comma seperated  brand ids $brand_id.
	* @param a boolean $status .
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param an integer startlimit $startlimit.
	* @param an integer cnt $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductByPriceAscCount($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$discontinue_flag='',$check_discontinue_date="",$color_id='0',$city_id=""){
		$keyArr[] = $this->productKey."_carfinder_bypriceasc_searchcnt";
    	$selectStr = "PRODUCT_MASTER.*";
	    if(!empty($startprice)){
	      $keyArr[] = $startprice;
	      $whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
	    }else{$keyArr[] = -1;}
	    if(!empty($endprice)){
	      $keyArr[] = $endprice;
	      $whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
	    }else{$keyArr[] = -1;}
	    if(!empty($variant_id)){
	      $keyArr[] = $variant_id;
	      $whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
	      $whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
	      $tablenameArr[] = "PRICE_VARIANT_VALUES";
	    }else{$keyArr[] = -1;}
	    if($city_id!=''){
	    	if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
						$keyArr[] = 'city_'.$startprice."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$city_id;
                        $whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
                        $whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id=$city_id";
                        $selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
                        $tablenameArr[] = "PRODUCT_NAME_INFO";
                        $whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
                        $whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
                        $whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
            }else{
            	$keyArr[] = 'city_'.'-1_-1_-1_-1_-1';
            }
	    }else{
	    	if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = 'dc_'.$startprice."_".$endprice."_".$variant_id.'_'.$color_id;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
				$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
	    	}else{
	    		$keyArr[] = 'dc_-1_-1_-1_-1_-1';
	    	}
	    }
	    if(!empty($default_city)){
	      $keyArr[] = "dc_1";
	      $whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
	    }else{$keyArr[] = 'dc_-1';}
	    $whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value != 0";
	    $tablenameArr[] = "PRODUCT_MASTER";
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
	        //if(intval($product_ids)!=0){
	          $product_ids = intval($product_ids);
	        //}
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
	    if(!empty($category_id)){
	      $keyArr[] = $category_id;
	      $whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
	    }else{$keyArr[] = -1;}

		if($discontinue_flag != ''){
	        $keyArr[] = $discontinue_flag;
	        $whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
	    }else{$keyArr[] = -1;}
	    if($check_discontinue_date != ""){
	        $prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
	 		$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
	    }
	    if($status != ''){
	      $keyArr[] = $status;
	      $whereClauseArr[] = "PRODUCT_MASTER.status=$status";
	    }else{$keyArr[] = -1;}
	    if(!empty($product_ids)){
	      $keyArr[] = $product_ids;
	      $whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
	    }else{$keyArr[] = -1;}
	    /*
	    if(!empty($category_id)){
	      $keyArr[] = $category_id;
	      $whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
	    }else{$keyArr[] = -1;}*/
	    if(!empty($brand_id)){
	      $keyArr[] = $brand_id;
	      $whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
	    }else{$keyArr[] = -1;}
	    if(sizeof($whereClauseArr) > 0){
	      $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
	    }
	    if(!empty($startlimit)){
	      $keyArr[] = $startlimit;
	      $limitArr[] = $startlimit;
	    }else{$keyArr[] = -1;}
	    if(!empty($cnt)){
	      $keyArr[] = $cnt;
	      $limitArr[] = $cnt;
	    }else{$keyArr[] = -1;}
	    if(sizeof($limitArr) > 0){
	      $limitStr = " limit ".implode(" , ",$limitArr);
	    }
	    $key = implode("_",$keyArr);
	    //echo $key;
	    $result = $this->cache->get($key);
	    if(!is_array($result)){
	      $tableStr = implode(",",$tablenameArr);
	      $sql = "select count(PRODUCT_MASTER.product_id) as cnt from $tableStr $whereClauseStr order by PRICE_VARIANT_VALUES.variant_value asc";
	      $result = $this->select($sql);
	      $this->cache->set($key,$result);
	    }
    	return $result;
	}
	/**
	* @note function is used to get Product by price ascending
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category_ids/ category_id array $category_id.
	* @param an integer/comma seperated  brand ids $brand_id.
	* @param a boolean $status .
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param an integer startlimit $startlimit.
	* @param an integer cnt $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductByPriceAsc($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$discontinue_flag='',$check_discontinue_date="",$color_id="0",$city_id="",$orderby=""){
		$keyArr[] = $this->productKey."_carfinder_bypriceasc";
		$selectStr = "PRODUCT_MASTER.*";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] = -1;}

		if($city_id!=''){
               		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
						$keyArr[] = "city_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$city_id;
                        $whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
    	                $whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id=$city_id";
            	        $selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
                    	$tablenameArr[] = "PRODUCT_NAME_INFO";
                        $whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
    	                $whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
            	        $whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
                	}else{
                		$keyArr[] = 'city_-1_-1_-1_-1_-1';
                	}
               }else{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "dc_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
				$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = 'dc_-1_-1_-1_-1';
			}
	      }
		if(!empty($default_city)){
			$keyArr[] = "dc_1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
		}else{$keyArr[] = "dc_-1";}
		$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value != 0";
		$tablenameArr[] = "PRODUCT_MASTER";
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
	       // if(intval($product_ids)!=0){
	          $product_ids = intval($product_ids);
	        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$keyArr[] = $check_discontinue_date;
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}else{$keyArr[] = -1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if($startlimit!=''){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby!=''){
			$orderby = "order by $orderby";
		}else{
			$orderby = " order by PRODUCT_MASTER.create_date asc";
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!is_array($result)){
			$tableStr = implode(",",$tablenameArr);
		 	$sql = "select * from $tableStr $whereClauseStr $orderby $limitStr";
		 	$result = $this->select($sql);
			$this->cache->set($key,$result);
		}
		//$searchArr = $this->arrGetUniqueSearchModel($result,$startlimit,$cnt);
		//print_r($result);
		$result = $this->researchSummaryMobiles($result,$category_id,$startprice,$endprice,$variant_id,"PRICE_VARIANT_VALUES.variant_value asc",$discontinue_flag,$check_discontinue_date);
		//die();
		//print "<pre>"; print_r($result);
		return $result;
	}
	/**
	* @note function is used to get Product by price descending
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category_ids/ category_id array $category_id.
	* @param an integer/comma seperated  brand ids $brand_id.
	* @param a boolean $status .
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param an integer startlimit $startlimit.
	* @param an integer cnt $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductByPriceDesc($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		$keyArr[] = $this->productKey."_carfinder_bypricedesc";
		$selectStr = "PRODUCT_MASTER.*";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] = -1;}

		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
			$keyArr[] = "dc_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
			$tablenameArr[] = "PRODUCT_NAME_INFO";
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
			$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
		}else{$keyArr[] = 'dc_-1_-1_-1_-1';}

		$tablenameArr[] = "PRODUCT_MASTER";
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
	        //if(intval($product_ids)!=0){
	          $product_ids = intval($product_ids);
	        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}

		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
        		$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
		        $whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!is_array($result)){
			$tableStr = implode(",",$tablenameArr);
			$sql = "select PRODUCT_MASTER.product_name as product_info from $tableStr $whereClauseStr order by PRICE_VARIANT_VALUES.variant_value desc";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
		}
		$searchArr = $this->arrGetUniqueSearchModel($result,$startlimit,$cnt);
		$result = $this->researchSummary($searchArr,$category_id,$startprice,$endprice,$variant_id,"PRICE_VARIANT_VALUES.variant_value desc",'','1');
		return $result;
	}
	/**
	* @note function is used to get Product by name ascending
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category_ids/ category_id array $category_id.
	* @param an integer/comma seperated  brand ids $brand_id.
	* @param a boolean $status .
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param an integer startlimit $startlimit.
	* @param an integer cnt $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductByNameAsc($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		$keyArr[] = $this->productKey."_carfinder_bynameasc";
		$selectStr = "PRODUCT_MASTER.*";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] = -1;}

		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
			$keyArr[] = "dc_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
			$tablenameArr[] = "PRODUCT_NAME_INFO";
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
			$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
		}else{$keyArr[] = 'dc_-1_-1_-1_-1';}

		$tablenameArr[] = "PRODUCT_MASTER";
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!is_array($result)){
			$tableStr = implode(",",$tablenameArr);
			$sql = "select PRODUCT_MASTER.product_name as product_info from $tableStr $whereClauseStr order by PRODUCT_MASTER.product_name asc";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
		}

		$searchArr = $this->arrGetUniqueSearchModel($result,$startlimit,$cnt);

		$result = $this->researchSummary($searchArr,$category_id,$startprice,$endprice,$variant_id,"PRICE_VARIANT_VALUES.variant_value asc");
		return $result;
	}
	 /**
	* @note function is used to get Product by name descending
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category_ids/ category_id array $category_id.
	* @param an integer/comma seperated  brand ids $brand_id.
	* @param a boolean $status .
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param an integer startlimit $startlimit.
	* @param an integer cnt $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
	 function arrGetProductByNameDesc($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		$keyArr[] = $this->productKey."_carfinder_bynamedesc";
		$selectStr = "PRODUCT_MASTER.*";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] = -1;}

		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
			$keyArr[] = "dc_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
			$tablenameArr[] = "PRODUCT_NAME_INFO";
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
			$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
		}else{$keyArr[] = 'dc_-1_-1_-1_-1';}
		$tablenameArr[] = "PRODUCT_MASTER";
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] =-1;}

		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] =-1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!is_array($result)){
			$tableStr = implode(",",$tablenameArr);
			$sql = "select PRODUCT_MASTER.product_name as product_info from $tableStr $whereClauseStr order by PRODUCT_MASTER.product_name desc";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
		}

		$searchArr = $this->arrGetUniqueSearchModel($result,$startlimit,$cnt);

		$result = $this->researchSummary($searchArr,$category_id,$startprice,$endprice,$variant_id,"PRICE_VARIANT_VALUES.variant_value asc");
		return $result;
	 }
	/**
	* @note function is used to get research iprice product details
	*
	* @param is string product_info $product_info
	* @param an integer category_id $category_id.
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param a string order by $orderby.
	* @param an integer default_city $default_city.
	*
	* @post an associative array.
	* retun an array.
	*/
	 function researchPriceProductDetails($product_info,$category_id,$startprice,$endprice,$variant_id,$orderby=" PRICE_VARIANT_VALUES.variant_value asc",$default_city="1",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		 $key = $this->productKey."_research_price_startprice_$startprice"."_endprice_$endprice"."_variant_id_$variant_id"."_default_city_$default_city"."_status_1_category_id_$category_id"."_product_info_$product_info"."_discontinue_flag_$discontinue_flag"."check_discontinue_date_$check_discontinue_date"."_color_id_$color_id";
		 if($discontinue_flag != ''){
				$discontinue_flag_str = " and PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		 }
		 if($check_discontinue_date != ""){
				$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
				$discontinue_date_str = " and (PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		 }
		 if($result = $this->cache->get($key)){return $result;}
		 $sql = "select PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.* from PRICE_VARIANT_VALUES,PRODUCT_MASTER where PRICE_VARIANT_VALUES.variant_value>=$startprice and PRICE_VARIANT_VALUES.variant_value<=$endprice and PRICE_VARIANT_VALUES.variant_id = $variant_id and PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id and PRICE_VARIANT_VALUES.default_city=$default_city and PRICE_VARIANT_VALUES.color_id ='0' and PRODUCT_MASTER.status=1 and PRODUCT_MASTER.category_id in ($category_id) and PRODUCT_MASTER.product_name = '".trim($product_info)."' $discontinue_flag_str $discontinue_date_str order by $orderby";
		 $result = $this->select($sql);
		 $this->cache->set($key,$result);
		 return $result;
	 }
	/**
	* @note function is used to get research Summary
	*
	* @param an array $result.
	* @param an integer category_id $category_id.
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param a string order by $orderby.
	*
	* @post an associative array.
	* retun an array.
	*/
	 function researchSummary($result,$category_id,$startprice,$endprice,$variant_id,$orderby="PRICE_VARIANT_VALUES.variant_value asc",$discontinue_flag="",$check_discontinue_date=""){
		require_once(CLASSPATH.'feature.class.php');
		require_once(CLASSPATH.'overview.class.php');
		$feature = new FeatureManagement;
		$overview = new OverviewManagement;
		$overviewresult = $overview->arrGetCarFinderFeatureOverview();

		$overviewCnt = sizeof($overviewresult);
		$cnt = sizeof($result);
		for($i=0;$i<$cnt;$i++){
			$product_info = $result[$i]['product_info'];
			$product_result = $this->researchPriceProductDetails($product_info,$category_id,$startprice,$endprice,$variant_id,$orderby,"1",$discontinue_flag,$check_discontinue_date);
			$productcnt = sizeof($product_result);
			$featureArr = Array();
			for($j=0;$j<$productcnt;$j++){
				$product_id = $product_result[$j]['product_id'];
				$categoryid = $product_result[$j]['categoryid'];
				$product_info_name = $product_result[$j]['product_name'];
				$productNameData = $this->arrGetProductNameInfo("",$category_id,"",$product_info_name,"1");
				$sImagePath = $productNameData['0']['image_path'];
				$img_media_id = $productNameData['0']['img_media_id'];
				$product_result[$j]['model_image_path'] = $sImagePath;
				$product_result[$j]['model_image_id'] = $img_media_id;
				if(!empty($category_id) && !empty($product_id)){
					unset($overviewArr);
					for($overview=0;$overview<$overviewCnt;$overview++){
						unset($featureoverviewArr);
						$overview_feature_id = $overviewresult[$overview]['feature_id'];
						$overview_title = $overviewresult[$overview]['title'];
						$overview_unit = $overviewresult[$overview]['abbreviation'];
						$overviewkey = $this->productKey."_carfinder_researchSummary_feature_id_$overview_feature_id"."_product_id_$product_id";
						$overviewfeature_details = $this->cache->get($overviewkey);
						if(sizeof($overviewfeature_details) <= 0){
							$sql = "select * from PRODUCT_FEATURE where feature_id = $overview_feature_id and product_id = $product_id";
							$overviewfeature_details = $this->select($sql);
							$this->cache->set($overviewkey,$overviewfeature_details);
						}
						$feature_value = $overviewfeature_details[0]['feature_value'];
						if($feature_value == "-"){$feature_value="";}
						if(!empty($feature_value)){
							$featureoverviewArr[] = $feature_value;
						}
						if(!empty($overview_unit) && !empty($feature_value)){
							$featureoverviewArr[] = $overview_unit;
						}
						if(sizeof($featureoverviewArr) > 0){
							$desc = implode(" ",$featureoverviewArr);
							if(!empty($desc) && !empty($overview_title)){
								#$desc = "<span class=\"b\">$overview_title:&#160;</span>$desc";
								$product_result[$j][$overview_title] = $desc;
								$overviewArr[$overview_title] = $desc;
								$desc = ($overview_title == 'Mileage') ? str_replace(array(' kmpl','kmpl'),array('',''),$desc) : $desc;
								$descArr = explode(',',$desc);
								if(is_array($descArr)){
									foreach($descArr as $v){
										if(!in_array($v,$featureArr)){
											$featureArr[$overview_title][] = trim($v);
										}
									}
								}else{
									$featureArr[$overview_title][] = trim($desc);
								}
							}elseif(!empty($desc) && empty($overview_title)){
								$product_result[$j][$overview_title] = $desc;
								$overviewArr[] = $desc;
								$descArr = explode(',',$desc);
								if(is_array($descArr)){
									foreach($descArr as $v){
										if(!in_array($v,$featureArr)){
											$featureArr[$overview_title][] = trim($v);
										}
									}
								}else{
									$featureArr[$overview_title][] = trim($desc);
								}
							}
						}

					}
					//print_r($overviewArr);
					#$product_result[$j]['short_desc'] = implode('<span class="dvder">|</span>',$overviewArr);
					$product_result[$j]['short_desc'] = implode(' | ',$overviewArr);
					unset($overviewArr);
				}

			}
			if($j == $productcnt){
				$featureArr['Mileage'] = array_unique($featureArr['Mileage']);
				$minMileage = min($featureArr['Mileage']);
				$maxMileage = max($featureArr['Mileage']);
				if($minMileage!='' and $minMileage!="-"){
					$mileageArr[] = min($featureArr['Mileage']).' kmpl';
				}
			        if($maxMileage!='' and $maxMileage!="-"){
				  $mileageArr[] = max($featureArr['Mileage']).' kmpl';
				}
				$mileageArr = array_unique($mileageArr);
				$product_result[0]['model_mileage'] = implode(' - ',$mileageArr);

				$featureArr['Fuel'] = array_unique($featureArr['Fuel']);
				$product_result[0]['model_fuel'] = implode(', ',$featureArr['Fuel']);
				$featureArr['Engine'] = array_unique($featureArr['Engine']);

				unset($featureArr);unset($mileageArr);
			}
					#print_r($product_result);die();
			$result[$i] = $product_result;
		}
		return $result;
	}
	/**
	* @note function is used to get product name with price
	*
	* @param a string product_name $product_name.
	* @param an integer category_id $category_id.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductNameWithPrice($product_name,$category_id){
		$key = $this->productKey."_ProductNameWithPrice_product_name_$product_name"."_category_id_$category_id"."_status=$status";
		if($result = $this->cache->get($key)){return $result;}
		$product_name = strtolower($product_name);
		$sql = "select PRODUCT_MASTER.* from PRODUCT_MASTER,PRICE_VARIANT_VALUES where trim(product_name) = '".trim($product_name)."' and PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id and PRODUCT_MASTER.category_id = $category_id and PRODUCT_MASTER.status=1 and PRICE_VARIANT_VALUES.variant_id=1 and PRICE_VARIANT_VALUES.default_city=1 group by PRODUCT_MASTER.product_id";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	/**
	* @note function is used to get product name
	*
	* @param a string product_name $product_name.
	* @param an integer category_id $category_id.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductName($product_name,$category_id,$brand_id){
		$product_name = strtolower($product_name);
		$key = $this->productKey."_ProductNameWithPrice_product_name_$product_name"."_category_id_$category_id"."_status=$status"."_brand_id_$brand_id";
		if($result = $this->cache->get($key)){return $result;}
		$product_name = strtolower($product_name);
		$sql = "select * from PRODUCT_MASTER where product_name = '".trim($product_name)."' and brand_id = $brand_id and category_id = $category_id and status=1";

		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetProductNameByName($model_name,$product_name,$category_id,$brand_id){
		$key = $this->productKey."_ProductNameWithPrice_product_name_$product_name"."_category_id_$category_id"."_status=$status"."_brand_id_$brand_id"."_model_name_$model_name";
		if($result = $this->cache->get($key)){return $result;}
		$product_name = strtolower($product_name);
		$model_name = strtolower($model_name);
		$sql = "select PRODUCT_MASTER.* from PRODUCT_MASTER where trim(variant) = '".trim($product_name)."' and trim(product_name) = '".trim($model_name)."' and PRODUCT_MASTER.brand_id = $brand_id and PRODUCT_MASTER.category_id = $category_id and PRODUCT_MASTER.status=1 group by PRODUCT_MASTER.product_id";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get search product details
	*
	* @param an array $result.
	* @param an integer $category_ids.
	* @param an integer $city_id.
	*
	* @post is an associative array.
	* retun an array.
	*/
	 function constantProductInfoDetails($result,$category_id,$city_id=""){
		global $searchShortDescArr;
		require_once(CLASSPATH.'price.class.php');
		require_once(CLASSPATH.'feature.class.php');
		require_once(CLASSPATH.'brand.class.php');
		$price = new price;
		$feature = new FeatureManagement;
		$brand = new BrandManagement;
		$cnt = sizeof($result);
		if(!empty($category_id)){
			$price_formula = $price->arrGetVariantFormulaDetail("","",$category_id);
			$variant_formula_id = $price_formula[0]['variant_formula_id'];
			$formula = $price_formula[0]['formula'];
		}

		for($i=0;$i<$cnt;$i++){
			$product_id = $result[$i]['product_id'];
			$categoryid = $result[$i]['category_id'];
			$brandid = $result[$i]['brand_id'];
			$product_name = $result[$i]['product_name'];
			unset($productNameArr);
			if(!empty($brandid)){
				$brand_result = $brand->arrGetBrandDetails($brandid);
				$productNameArr[] = $brand_result[0]['brand_name'];
			}
			$productNameArr[] = $result[$i]['product_name'];
			$result[$i]['link_product_name'] = $result[$i]['product_name'];
			$productNameArr[] = $result[$i]['variant'];
			$display_product_name = implode(" ",$productNameArr);
			$result[$i]['display_product_name'] = $display_product_name;
			if(!empty($categoryid) && !empty($product_id)){
				$sOverviewArray = $feature->arrGetSummary($categoryid,$product_id,$type="array");
			}
			if(is_array($sOverviewArray)){
				unset($productNameArr[0]);		// remove brand name form array.
				foreach($sOverviewArray as $key=>$val){
						if($sOverviewArray[$key][0]){
							$overviewArr[] = implode(",&#160;",$sOverviewArray[$key][0]);
						}
				}
				$result[$i]['short_desc'] = implode(",&#160;",$overviewArr);
				unset($overviewArr);
			}else{
				$result[$i]['short_desc'] = "";
			}

			if(!empty($product_id)){
				if(!empty($city_id)){
					$price_result = $price->arrGetPriceDetails("",$product_id,$categoryid,"","",$city_id,"1","","","");
				}else{
					$price_result = $price->arrGetPriceDetails("",$product_id,$categoryid,"","","","1","","","1");
				}
				//print_r($price_result);
				$aVariant=$price->arrGetVariantDetail("",$categoryid,"1","","");
				//print_r($aVariant);
				$priceCnt = sizeof($price_result);
				if(!empty($priceCnt)){
					for($j=0;$j<$priceCnt;$j++){
						$variant_id = $price_result[$j]['variant_id'];
						$variant_value = $price_result[$j]['variant_value'];
						if(in_array(EX_SHOWROOM_STR,$price_result[$j])){
							$result[$i]['exshowroomprice'] = $variant_value ? priceFormat($variant_value) : '';
						}
						$formulaValuesArr[$variant_id] = $variant_value ? $variant_value : 0;
						$aVar[]=$variant_id;
						$result[$i]['price_details'][] = $price_result[$j];


					}
				}else{
					$result[$i]['exshowroomprice'] = 0;
				}

				for($k=0;$k<count($aVariant);$k++){
					if(!in_array($aVariant[$k]['variant_id'],$aVar)){
						$formulaValuesArr[$aVariant[$k]['variant_id']]=0;
					}
				}
				$feature_result = $this->arrGetProductFeatureDetails("","",$product_id);
				$featureCnt = sizeof($feature_result);
				unset($short_desc_array);
				for($j=0;$j<$featureCnt;$j++){
					unset($featureValueArr);
					$feature_id = $feature_result[$j]['feature_id'];
					$feature_name = $feature_result[$j]['feature_name'];
					$feature_value = $feature_result[$j]['feature_value'];
					$featureValueArr[] = $feature_value;
					$feature_unit = $feature_result[$j]['unit_id'];
					if(!empty($feature_unit)){
						$feature_unit = $feature->arrFeatureUnitDetails($feature_unit,$categoryid);
						$unit_name = $feature_unit[0]['unit_name'];
						$featureValueArr[] = $unit_name;
					}

					$result[$i]['feature_result'][] = $feature_result[$j];

				}

				if(sizeof($formulaValuesArr) > 0){
					$totalprice = strtr($formula,$formulaValuesArr);
					$totalprice = parse_mathematical_string($totalprice);
				}
				$onroadkey = str_replace(" ","_",ON_RAOD_PRICE_TITLE);
				$result[$i][$onroadkey] = $totalprice ? $totalprice :0;


				if(!empty($product_name)){
					$similar_product_result = $this->arrGetProductByName(strtolower($product_name),$product_id,"","1","0","6");
					$similarCnt = sizeof($similar_product_result);
					for($k=0;$k<$similarCnt;$k++){
						$similar_product_id = $similar_product_result[$k]['product_id'];
						$similar_brandid = $similar_product_result[$k]['brand_id'];
						$product_name = $similar_product_result[$k]['product_name'];
						unset($similarproductNameArr);
						if(!empty($similar_brandid)){
							$similar_brand_result = $brand->arrGetBrandDetails($similar_brandid);
							$similarproductNameArr[] = $similar_brand_result[0]['brand_name'];
						}
						$similarproductNameArr[] = $similar_product_result[$k]['product_name'];
						$similarproductNameArr[] = $similar_product_result[$k]['variant'];
						$similar_display_product_name = implode(" ",$similarproductNameArr);
						$similar_product_result[$k]['display_product_name'] = $similar_display_product_name;
						$similar_feature_result = $this->arrGetProductFeatureDetails("","",$similar_product_id);
						$featureCnt = sizeof($similar_feature_result);
						unset($short_desc_array);

						$aVar='';
						$price_result = $price->arrGetPriceDetails("",$similar_product_id,$categoryid,"","",$city_id);
						$aVariant=$price->arrGetVariantDetail("",$categoryid,"1","","");
						$priceCnt = sizeof($price_result);
						if(!empty($priceCnt)){
							for($j=0;$j<$priceCnt;$j++){
								$variant_id = $price_result[$j]['variant_id'];
								$variant_value = $price_result[$j]['variant_value'];
								$formulaValuesArr[$variant_id] = $variant_value ? $variant_value : 0;
								$aSVar[]=$variant_id;
								}
						}
						for($l=0;$l<count($aVariant);$l++){
							if(!in_array($aVariant[$l]['variant_id'],$aSVar)){
								$formulaValuesArr[$aVariant[$l]['variant_id']]=0;
							}
						}
						$aVariant=''; $aSVar='';
						if(sizeof($formulaValuesArr) > 0){
							$similartotalprice = strtr($formula,$formulaValuesArr);
							$similartotalprice = parse_mathematical_string($similartotalprice);
						}
						$onroadkey = str_replace(" ","_",ON_RAOD_PRICE_TITLE);
						$similar_product_result[$k][$onroadkey] = $similartotalprice ? $similartotalprice :0;
						$compare_price_difference = $totalprice - $similartotalprice;
						$similar_product_result[$k]['compare_price_difference'] = $compare_price_difference;

						$price_result = $price->arrGetPriceDetails("1",$similar_product_id,$categoryid,"1","","","1");
						$exshowroom_price = $price_result[0]['variant_value'];
						$similar_product_result[$k]['exshowroomprice'] = $exshowroom_price ? $exshowroom_price : 0;
						$result[$i]['similar_product'][] = $similar_product_result[$k];
					}
				}

			}

		}
		return $result;
	 }

	/**
	* @note function is used to insert the oncars compare set information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $oncars_compare_id.
	* retun integer.
	*/
	function addUpdOncarsCompareSetDetails($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("ONCARS_COMPARISON",array_keys($insert_param),array_values($insert_param));
		$oncars_compare_id = $this->insertUpdate($sql);


		$status = $insert_param['status'];
		$oncars_compare_id = $insert_param['oncars_compare_id'];
		if(!empty($oncars_compare_id)){
			$sql = "update FEATURED_ONCARS_COMPARISON set status = $status where oncars_compare_id = $oncars_compare_id";
			$isUpdate = $this->update($sql);
		}
		$this->cache->searchDeleteKeys($this->productKey."_oncars_featured_compare_set");
		$this->cache->searchDeleteKeys($this->productKey."_oncars_compare_set");
		return $oncars_compare_id;
	}

	/**
	* @note function is used to delete the Oncars Compare set Detail.
	* @param integer $oncars_compare_id.
	* @pre $oncars_compare_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteOncarsCompareSetDetail($oncars_compare_id){
		$sql = "delete from ONCARS_COMPARISON where oncars_compare_id = $oncars_compare_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_oncars_compare_set");
		$sql1 = "delete from FEATURED_ONCARS_COMPARISON	where oncars_compare_id = $oncars_compare_id";
		$isDelete1 = $this->sql_delete_data($sql1);
		$this->cache->searchDeleteKeys($this->productKey."_oncars_featured_compare_set");
		return $isDelete;
	}
	/**
	* @note function is used to get oncars compare set details.
	* @param an integer/comma seperated oncars compare ids/ oncars compare ids array $oncars_compare_ids.
	* @param an integer category_id.
	* @param an integer/comma separated string of compare set.
	* @param an integer position.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post product details in associative array.
	* retun an array.
	*/
	function arrGetOncarsCompareSetDetails($oncars_compare_ids="",$category_ids="",$compare_set="",$position="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->productKey."_oncars_compare_set";
		if(is_array($oncars_compare_ids)){
			$oncars_compare_ids = implode(",",$oncars_compare_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(strrpos( $oncars_compare_ids, ',') !== false){
			$oncars_compare_ids = substr($oncars_compare_ids, 0, strrpos( $oncars_compare_ids, ','));
		}
		if($oncars_compare_ids != ""){
			$keyArr[] = $oncars_compare_ids;
			$whereClauseArr[] = " oncars_compare_id in($oncars_compare_ids)";
		}else{$keyArr[] =-1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = " category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if($compare_set != ""){
			$keyArr[] = $compare_set;
			$whereClauseArr[] = " compare_set = $compare_set";
		}else{$keyArr[] =-1;}
		if($position != ""){
			$keyArr[] = $position;
			$whereClauseArr[] = " position = $position";
		}else{$keyArr[] =-1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = " status = $status";
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(empty($orderby)){
			$orderby = " order by create_date desc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select * from ONCARS_COMPARISON $whereClauseStr $orderby $limitStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetOncarsCompareSetDetailsCnt($oncars_compare_ids="",$category_ids="",$compare_set="",$position="",$status="1",$orderby=""){
		$keyArr[] = $this->productKey."_oncars_compare_set_count";
		if(is_array($oncars_compare_ids)){
			$oncars_compare_ids = implode(",",$oncars_compare_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		$oncars_compare_ids = substr($oncars_compare_ids, 0, strrpos( $oncars_compare_ids, ','));
		if($oncars_compare_ids != ""){
			$keyArr[] = $oncars_compare_ids;
			$whereClauseArr[] = " oncars_compare_id in($oncars_compare_ids)";
		}else{$keyArr[] =-1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = " category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if($compare_set != ""){
			$keyArr[] = $compare_set;
			$whereClauseArr[] = " compare_set = $compare_set";
		}else{$keyArr[] =-1;}
		if($position != ""){
			$keyArr[] = $position;
			$whereClauseArr[] = " position = $position";
		}else{$keyArr[] =-1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = " status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(empty($orderby)){
			$orderby = " order by create_date desc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select count(oncars_compare_id) as cnt from ONCARS_COMPARISON $whereClauseStr $orderby $limitStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to insert the featured oncars compare set information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $featured_compare_id.
	* retun integer.
	*/
	function addUpdFeaturedCompareSetDetails($insert_param,$table_name){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		 $sql = $this->getInsertUpdateSql($table_name,array_keys($insert_param),array_values($insert_param));
		$featured_compare_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->productKey."_oncars_featured_compare_set");
		return $featured_compare_id;
	}

	/**
	* @note function is used to delete the Featured Oncars Compare set Detail.
	* @param integer $featured_compare_id.
	* @pre $featured_compare_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteFeaturedCompareSetDetail($featured_compare_id,$table_name){
		$sql = "delete from $table_name where featured_compare_id = $featured_compare_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_oncars_featured_compare_set");
		return $isDelete;
	}
	function arrGetFeaturedCompareSetDetailsCnt($featured_compare_ids="",$oncars_compare_id="",$category_ids="",$status="1",$ordering=""){
		$keyArr[] = $this->productKey."_oncars_featured_compare_set_cnt";
		if(is_array($featured_compare_ids)){
			$featured_compare_ids = implode(",",$featured_compare_ids);
		}
		if(is_array($oncars_compare_ids)){
			$keyArr[] = $oncars_compare_ids;
			$oncars_compare_ids = implode(",",$oncars_compare_ids);
		}else{$keyArr[] =-1;}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($featured_compare_ids != ""){
			$keyArr[] = $featured_compare_ids;
			$whereClauseArr[] = " featured_compare_id in($featured_compare_ids)";
		}else{$keyArr[] =-1;}
		if($oncars_compare_ids != ""){
			$keyArr[] = $oncars_compare_ids;
			$whereClauseArr[] = " oncars_compare_id in($oncars_compare_ids)";
		}else{$keyArr[] =-1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = " category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = " status = $status";
		}else{$keyArr[] =-1;}
		if($ordering != ""){
			$keyArr[] = $ordering;
			$whereClauseArr[] = " ordering = $ordering";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select count(featured_compare_id) as cnt from FEATURED_ONCARS_COMPARISON $whereClauseStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get oncars compare set details.
	* @param an integer/comma seperated oncars compare ids/ oncars compare ids array $oncars_compare_ids.
	* @param an integer category_id.
	* @param an integer/comma separated string of compare set.
	* @param an integer position.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post product details in associative array.
	* retun an array.
	*/
	function arrGetFeaturedCompareSetDetails($featured_compare_ids="",$oncars_compare_id="",$category_ids="",$status="1",$ordering="",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->productKey."_oncars_featured_compare_set";
		if(is_array($featured_compare_ids)){
			$featured_compare_ids = implode(",",$featured_compare_ids);
		}
		if(is_array($oncars_compare_ids)){
			$keyArr[] = $oncars_compare_ids;
			$oncars_compare_ids = implode(",",$oncars_compare_ids);
		}else{$keyArr[] =-1;}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($featured_compare_ids != ""){
			$keyArr[] = $featured_compare_ids;
			$whereClauseArr[] = " featured_compare_id in($featured_compare_ids)";
		}else{$keyArr[] =-1;}
		if($oncars_compare_ids != ""){
			$keyArr[] = $oncars_compare_ids;
			$whereClauseArr[] = " oncars_compare_id in($oncars_compare_ids)";
		}else{$keyArr[] =-1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = " category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = " status = $status";
		}else{$keyArr[] =-1;}
		if($ordering != ""){
			$keyArr[] = $ordering;
			$whereClauseArr[] = " ordering = $ordering";
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(empty($orderby)){
			$orderby = " order by create_date desc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select * from FEATURED_ONCARS_COMPARISON $whereClauseStr $orderby $limitStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetUniqueSearchModel($result,$startlimit="0",$cnt="10"){
		$count = sizeof($result);
		for($i=0;$i<$count;$i++){
			$product_info = $result[$i]['product_info'];
			if(!in_array($product_info,$tmpArr)){
				$tmpArr[] = $product_info;
			}
		}
		$count = sizeof($tmpArr);
		$startlimit = $startlimit ? $startlimit : 0;
		$cnt = $startlimit+$cnt;

		for($i=0;$i<$count;$i++){
			if($i >= $startlimit && $i < $cnt){
				$searchArr[]['product_info'] = $tmpArr[$i];
			}
		}
		return $searchArr;
	}

	function arrGetUniqueSearchByLatestModel($result,$startlimit="0",$cnt="10"){

				$count = sizeof($result);
                for($i=0;$i<$count;$i++){
                        $product_info = $result[$i]['product_info'];
                        if(!in_array($product_info,$tmpArr)){
                                $tmpArr[] = $product_info;
                        }
                }
                $count = sizeof($tmpArr);
                $startlimit = $startlimit ? $startlimit : 0;
                $cnt = $startlimit+$cnt;
                for($i=0;$i<$count;$i++){
                        $searchArr[$i]['product_info'] = $tmpArr[$i];
                        $prod_res = $this->arrGetProductNameInfo("","","",$tmpArr[$i],"");
                        #echo "<br>+++++++++++++++++++++++++++++++<br>";
						#print"<pre>";print_r($prod_res);print"</pre>";
						#echo "<br>---------------------------<br>";
                        $model_create_date = $prod_res[0]["create_date"];
                        $model_arrival_date = $prod_res[0]["arrival_date"];
                        $searchArr[$i]['model_create_date'] = $model_create_date;
                        $searchArr[$i]['model_arrival_date'] = $model_arrival_date;

                }
                $sortArray = array();
				#print_r($searchArr); die();
                foreach($searchArr as $model_date){
                        foreach($model_date as $key=>$value){
                                if(!isset($sortArray[$key])){
                                        $sortArray[$key] = array();
                                }
                                $sortArray[$key][] = $value;
                        }
                }
                $orderby = "create_date";
		array_multisort($sortArray[$orderby],SORT_DESC,$searchArr);

                $searchArr_cnt = sizeof($searchArr);
                for($i=0;$i<$searchArr_cnt;$i++){
                        if($i >= $startlimit && $i < $cnt){
                                $resultArr[]['product_info'] = $searchArr[$i]['product_info'];
                        }
                }
                #print"<pre>";print_r($resultArr);print"</pre>";exit;
                return $resultArr;
        }
         /**
	* @note function is used to get search product count
	*
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param boolean Active/InActive $status.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param integer $cityId.
	* @pre not required.
	*
	* @post is an integer count.
	* retun an integer.
	*/
	 function searchProductCountByCity($category_ids="",$brand_ids="",$product_ids="",$feature_ids="",$status="1",$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$cityId='',$discontinue_flag='',$check_discontinue_date="",$color_id="0"){

		$keyArr[] = $this->productKey."_searchProductCountByCity";
		$this->assignPivotToSearch();

		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
	if(is_array($feature_ids)){
			$feature_ids = implode(",",$feature_ids);
	}

	if(!empty($feature_ids)){
		$featureArr = explode(",",$feature_ids);

			foreach($featureArr as $feature_id){
				if(in_array($feature_id,$this->bodyStyleArr)){
					$keyArr[] = $feature_id;
					$this->newBodyStyleArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->fuelTypeArr)){
					$keyArr[] = $feature_id;
					$this->newFuelTypeArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->impFeatureArr)){
					$keyArr[] = $feature_id;
					$this->newImpFeatureArr[] = "select product_id from PRODUCT_FEATURE where feature_id in ($feature_id)";
				}elseif(in_array($feature_id,$this->tranmissionArr)){
					$keyArr[] = $feature_id;
					$this->newTranmissionArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->seatingCapcityArr)){
					$keyArr[] = $feature_id;
					$this->newSeatingCapcityArr[] = $feature_id;
				}
			}


			if(sizeof($this->newBodyStyleArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newBodyStyleArr).")";
			}
			if(sizeof($this->newFuelTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newFuelTypeArr).")";
			}
			if(sizeof($this->newImpFeatureArr) > 0){
				$sqlStr = "";
				foreach($this->newImpFeatureArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
			if(sizeof($this->newTranmissionArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newTranmissionArr).")";
			}
			if(sizeof($this->newSeatingCapcityArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newSeatingCapcityArr).")";
			}

			if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
		}
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] =-1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] =-1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] =-1;}
		if(!(empty($cityId)))
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "c_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$cityId;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = ".$cityId;
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = 'c_-1_-1_-1_-1_-1';
			}
		}
		else
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "dc_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = 'dc_-1_-1_-1_-1';
			}
		}

		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in($brand_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		$tablenameArr[] = "PRODUCT_MASTER";

		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] = -1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] = -1;}
	    if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
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
		$table_name = implode(",",$tablenameArr);
		$sql = "select count(distinct(PRODUCT_MASTER.product_name)) as cnt FROM  $table_name $whereClauseStr $limitStr";
		//echo $sql."<br>";
        	$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
         /**
	* @note function is used to get search product details
	*
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param boolean Active/InActive $status.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param is a string $oredrby.
	*
	* @pre not required.
	*
	* @post is an associative array.
	* retun an array.
	*/
	 function searchProductByCity($category_ids="",$brand_ids="",$product_ids="",$feature_ids="",$status="1",$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$orderby="PRICE_VARIANT_VALUES.variant_value asc",$cityId="",$discontinue_flag='',$check_discontinue_date="",$check_latest_model="",$color_id='0'){

		$keyArr[] = $this->productKey."_searchProductByCity";
		$this->assignPivotToSearch();

		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($feature_ids)){
			$feature_ids = implode(",",$feature_ids);
		}

		if(!empty($feature_ids)){
			$featureArr = explode(",",$feature_ids);

			foreach($featureArr as $feature_id){
				if(in_array($feature_id,$this->bodyStyleArr)){
					$keyArr[] = $feature_id;
					$this->newBodyStyleArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->fuelTypeArr)){
					$keyArr[] = $feature_id;;
					$this->newFuelTypeArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->impFeatureArr)){
					$keyArr[] = $feature_id;
					$this->newImpFeatureArr[] = "select product_id from PRODUCT_FEATURE where feature_id in ($feature_id)";
				}elseif(in_array($feature_id,$this->tranmissionArr)){
					$keyArr[] = $feature_id;
					$this->newTranmissionArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->seatingCapcityArr)){
					$keyArr[] = $feature_id;
					$this->newSeatingCapcityArr[] = $feature_id;
				}
			}
			$this->newBodyStyleArr = array_unique($this->newBodyStyleArr,SORT_REGULAR);
			$this->newFuelTypeArr = array_unique($this->newFuelTypeArr,SORT_REGULAR);
			$this->newImpFeatureArr = array_unique($this->newImpFeatureArr,SORT_REGULAR);
			$this->newTranmissionArr = array_unique($this->newTranmissionArr,SORT_REGULAR);
			$this->newSeatingCapcityArr = array_unique($this->newSeatingCapcityArr,SORT_REGULAR);
			if(sizeof($this->newBodyStyleArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newBodyStyleArr).")";
			}
			if(sizeof($this->newFuelTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newFuelTypeArr).")";
			}
			if(sizeof($this->newImpFeatureArr) > 0){
				$sqlStr = "";
				foreach($this->newImpFeatureArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
			if(sizeof($this->newTranmissionArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newTranmissionArr).")";
			}
			if(sizeof($this->newSeatingCapcityArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newSeatingCapcityArr).")";
			}

			if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
		}
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] =-1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] =-1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] =-1;}
		if(!empty($cityId))
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = 'c_'.$startprice.'_'.$endprice.'_'.$variant_id.'_'.$color_id.'_'.$cityId;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = ".$cityId;
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = 'c_-1_-1_-1_-1_-1';
			}
		}
		else
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "dc_".$startprice.'_'.$endprice.'_'.$variant_id.'_'.$color_id;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = 'dc_-1_-1_-1_-1';
			}
		}

		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in($brand_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		$tablenameArr[] = "PRODUCT_MASTER";
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] =-1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] =-1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($orderby)){
			$keyArr[] = $orderby;
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		/*if($check_latest_model == 1){
			$orderbymodel = "PRODUCT_NAME_INFO.arrival_date desc";
			$keyArr[] = "orderbymodel_$orderbymodel";
			$keyArr[] = "check_latest_model_$check_latest_model";
		}*/

		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!is_array($result)){
			$table_name = implode(",",$tablenameArr);
			$sql = "select PRODUCT_NAME_INFO.product_info_name as product_info FROM $table_name $whereClauseStr order by $orderby";
			#echo $sql."<br>";
			$result = $this->select($sql);
			$this->cache->set($key,$result);

		}

		if($check_latest_model == 1){

				$searchArr = $this->arrGetUniqueSearchByLatestModel($result,$startlimit,$cnt);
		}else{
				$searchArr = $this->arrGetUniqueSearchModel($result,$startlimit,$cnt);
		}

		if(strpos($orderby,"product_name")){
			$orderby="PRICE_VARIANT_VALUES.variant_value asc";
		}
		$result = $this->globalSearchSummaryByCity($searchArr,$category_ids,$startprice,$endprice,$variant_id,$brand_ids,$product_ids,$feature_ids,$orderby,$cityId,$discontinue_flag,$check_discontinue_date);
		return $result;
	 }
         /**
	* @note function is used to get Product count by price ascending
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category_ids/ category_id array $category_id.
	* @param an integer/comma seperated  brand ids $brand_id.
	* @param a boolean $status .
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param an integer startlimit $startlimit.
	* @param an integer cnt $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
	function arrGetProductByPriceAscCountByCity($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$cityId="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){

		$keyArr[] = $this->productKey."_carfinder_bypriceasc_searchcnt";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] =-1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] =-1;}
		if(!(empty($cityId)))
		{

			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "c_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$cityId;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id=".$cityId;
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = "c_-1_-1_-1_-1_-1";
			}
		}
		else
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "dc_$startprice".'_'.$endprice.'_'.$variant_id.'_'.$color_id;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = "dc_-1_-1_-1_-1";
			}
		}
		$tablenameArr[] = "PRODUCT_MASTER";
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] =-1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] =-1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)) {
			$orderby = $orderby;
		}
		$key = implode("_",$keyArr);
		///echo $key."<br>";
		if($result = $this->cache->get($key)){return $result;}
		$tableStr = implode(",",$tablenameArr);
		$sql = "select count(distinct(PRODUCT_MASTER.product_name)) as cnt from $tableStr $whereClauseStr";
		////echo $sql."<br>";
        $result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
        /**
	* @note function is used to get Product by price ascending
	*
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category_ids/ category_id array $category_id.
	* @param an integer/comma seperated  brand ids $brand_id.
	* @param a boolean $status .
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param an integer startlimit $startlimit.
	* @param an integer cnt $cnt.
	*
	* @pre not required.
	*
	* @post an associative array.
	* retun an array.
	*/
function arrGetProductByPriceAscByCity($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$cityId="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		$keyArr[] = $this->productKey."_carfinder_bypriceasc";
		$selectStr = "PRODUCT_MASTER.*";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] = -1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] = -1;}
		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] = -1;}
		if(!(empty($cityId)))
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "c_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$cityId;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id=".$cityId;
				$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = "c_-1_-1_-1_-1_-1";
			}
		}
		else
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "dc_$startprice".'_'.$endprice.'_'.$variant_id.'_'.$color_id;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
				$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = "dc_-1_-1_-1_-1";
			}
		}
		$tablenameArr[] = "PRODUCT_MASTER";
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] =-1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] =-1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}

		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!is_array($result)){
			$tableStr = implode(",",$tablenameArr);
			$sql = "select PRODUCT_MASTER.product_name as product_info from $tableStr $whereClauseStr order by PRICE_VARIANT_VALUES.variant_value asc";
			//echo $sql."<br>";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
		}
		$searchArr = $this->arrGetUniqueSearchModel($result,$startlimit,$cnt);

		$result = $this->researchSummaryByCity($searchArr,$category_id,$startprice,$endprice,$variant_id,"PRICE_VARIANT_VALUES.variant_value asc",$cityId,$discontinue_flag,$check_discontinue_date);
		///print_r($result);
		//die();
		return $result;
	}
        /**
	* @note function is used to get research Summary
	*
	* @param an array $result.
	* @param an integer category_id $category_id.
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param a string order by $orderby.
	*
	* @post an associative array.
	* retun an array.
	*/
	 function researchSummaryByCity($result,$category_id,$startprice,$endprice,$variant_id,$orderby="PRICE_VARIANT_VALUES.variant_value asc",$cityId,$discontinue_flag='',$check_discontinue_date=""){

		require_once(CLASSPATH.'feature.class.php');
		require_once(CLASSPATH.'overview.class.php');

		$feature = new FeatureManagement;
		$overview = new OverviewManagement;
		$overviewresult = $overview->arrGetCarFinderFeatureOverview();
		$overviewCnt = sizeof($overviewresult);

		$cnt = sizeof($result);

		for($i=0;$i<$cnt;$i++){
			$product_info = $result[$i]['product_info'];

			$product_result = $this->researchPriceProductDetailsByCity($product_info,$category_id,$startprice,$endprice,$variant_id,$orderby,1,$cityId,$discontinue_flag,$check_discontinue_date);

			$productcnt = sizeof($product_result);

			for($j=0;$j<$productcnt;$j++){

				$product_id = $product_result[$j]['product_id'];
				$categoryid = $product_result[$j]['categoryid'];
				if(!empty($category_id) && !empty($product_id)){
					unset($overviewArr);

					for($overview=0;$overview<$overviewCnt;$overview++){
						unset($featureoverviewArr);
						$overview_feature_id = $overviewresult[$overview]['feature_id'];
						$overview_title = $overviewresult[$overview]['title'];
						$overview_unit = $overviewresult[$overview]['abbreviation'];
						$overviewkey = $this->productKey."_carfinder_researchSummary_feature_id_$overview_feature_id"."_product_id_$product_id";
						$overviewfeature_details = $this->cache->get($overviewkey);
						if(sizeof($overviewfeature_details) <= 0){
							$sql = "select * from PRODUCT_FEATURE where feature_id = $overview_feature_id and product_id = $product_id";
							$overviewfeature_details = $this->select($sql);
							$this->cache->set($overviewkey,$overviewfeature_details);
						}
						$feature_value = $overviewfeature_details[0]['feature_value'];
						if($feature_value == "-"){$feature_value="";}
						if(!empty($feature_value)){
							$featureoverviewArr[] = $feature_value;
						}
						if(!empty($overview_unit) && !empty($feature_value)){
							$featureoverviewArr[] = $overview_unit;
						}
						if(sizeof($featureoverviewArr) > 0){
							$desc = implode(" ",$featureoverviewArr);
							if(!empty($desc) && !empty($overview_title)){
								#$desc = "<span class=\"b\">$overview_title:&#160;</span>$desc";
								$overviewArr[$overview_title] = $desc;
							}elseif(!empty($desc) && empty($overview_title)){
								$overviewArr[] = $desc;
							}
						}

					}
					#$product_result[$j]['short_desc'] = implode('<span class="dvder">|</span>',$overviewArr);
					$product_result[$j]['short_desc'] = $overviewArr;
					unset($overviewArr);
				}

			}
			$result[$i] = $product_result;

		}

		return $result;
	}
        /**
	* @note function is used to get research iprice product details
	*
	* @param is string product_info $product_info
	* @param an integer category_id $category_id.
	* @param $startprice.
	* @param $endprice.
	* @param an integer variant_id $variant_id.
	* @param a string order by $orderby.
	* @param an integer default_city $default_city.
	*
	* @post an associative array.
	* retun an array.
	*/
	 function researchPriceProductDetailsByCity($product_info,$category_id,$startprice,$endprice,$variant_id,$orderby=" PRICE_VARIANT_VALUES.variant_value asc",$default_city="1",$cityId="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		 $key = $this->productKey."_research_price_startprice_$startprice"."_endprice_$endprice"."_variant_id_$variant_id"."_default_city_$default_city"."_status_1_category_id_$category_id"."_product_info_$product_info";

		  if($result = $this->cache->get($key)){return $result;}

		 if($discontinue_flag != ''){
				$keyArr[] = $discontinue_flag;
				$discontinue_flag_str = " and PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		 }else{$keyArr[] =-1;}
		 if($check_discontinue_date != ""){
				$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
				$discontinue_date_str = " and (PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		 }

		 if(!(empty($cityId)))
		 {
			$sql = "select PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.* from PRICE_VARIANT_VALUES,PRODUCT_MASTER where PRICE_VARIANT_VALUES.variant_value>=$startprice and PRICE_VARIANT_VALUES.variant_value<=$endprice and PRICE_VARIANT_VALUES.variant_id = $variant_id and PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id and PRICE_VARIANT_VALUES.city_id=$cityId and PRICE_VARIANT_VALUES.color_id = $color_id and PRODUCT_MASTER.status=1 and PRODUCT_MASTER.category_id in ($category_id) and PRODUCT_MASTER.product_name = '".trim($product_info)."'  $discontinue_flag_str $discontinue_date_str order by $orderby";
		 }
		 else
		 {
			 $sql = "select PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.* from PRICE_VARIANT_VALUES,PRODUCT_MASTER where PRICE_VARIANT_VALUES.variant_value>=$startprice and PRICE_VARIANT_VALUES.variant_value<=$endprice and PRICE_VARIANT_VALUES.variant_id = $variant_id and PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id and PRICE_VARIANT_VALUES.default_city=$default_city and PRICE_VARIANT_VALUES.color_id = $color_id and PRODUCT_MASTER.status=1 and PRODUCT_MASTER.category_id in ($category_id) and PRODUCT_MASTER.product_name = '".trim($product_info)."' $discontinue_flag_str $discontinue_date_str order by $orderby";
		 }
		 $result = $this->select($sql);
		 $this->cache->set($key,$result);
		 return $result;
	 }
         /**
	* @note function is used to get global search summary
	*
	* @param is an array $result
	* @param an integer $category_ids.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param is a string $oredrby.
	*
	* @pre not required.
	*
	* @post is an associative array.
	* retun an array.
	*/
	 function globalSearchSummaryByCity($result,$category_id,$startprice,$endprice,$variant_id,$brand_ids="",$product_ids="",$feature_ids="",$orderby="CAST(PRICE_VARIANT_VALUES.variant_value AS UNSIGNED) asc",$cityid="",$discontinue_flag='',$check_discontinue_date=""){

		require_once(CLASSPATH.'feature.class.php');
		require_once(CLASSPATH.'overview.class.php');

		$feature = new FeatureManagement;
		$overview = new OverviewManagement;
		$overviewresult = $overview->arrGetCarFinderFeatureOverview();
		$overviewCnt = sizeof($overviewresult);

		$cnt = sizeof($result);
		#print_r($result);
		for($i=0;$i<$cnt;$i++){
			$product_info = $result[$i]['product_info'];
			#echo "NANE->".$product_info."<br>";
			$product_result = $this->globalResearchPriceProductDetailsByCity($product_info,$category_id,$startprice,$endprice,$variant_id,$brand_ids,$product_ids,$feature_ids,$orderby,1,$cityid,$discontinue_flag,$check_discontinue_date);
			$productcnt = sizeof($product_result);

			for($j=0;$j<$productcnt;$j++){
				#echo $product_result[$j]['product_name']."<br>";
				$product_id = $product_result[$j]['product_id'];
				$categoryid = $product_result[$j]['categoryid'];
				if(!empty($category_id) && !empty($product_id)){
					unset($overviewArr);

					for($overview=0;$overview<$overviewCnt;$overview++){
						unset($featureoverviewArr);unset($productfeaturekey);
						$overview_feature_id = $overviewresult[$overview]['feature_id'];
						$overview_title = $overviewresult[$overview]['title'];
						$overview_unit = $overviewresult[$overview]['abbreviation'];
						$productfeaturekey = $this->productKey."_feature_overview_$overview_feature_id"."_product_id".$product_id;
						$overviewfeature_details = $this->cache->get($productfeaturekey);
						if(sizeof($overviewfeature_details) <= 0){
							$sql = "select * from PRODUCT_FEATURE where feature_id = $overview_feature_id and product_id = $product_id";
							$overviewfeature_details = $this->select($sql);
							$this->cache->set($productfeaturekey,$overviewfeature_details);
						}
						$feature_value = $overviewfeature_details[0]['feature_value'];
						if($feature_value == "-"){$feature_value="";}
						if(!empty($feature_value)){
							$featureoverviewArr[] = $feature_value;
						}
						if(!empty($overview_unit) && !empty($feature_value)){
							$featureoverviewArr[] = $overview_unit;
						}
						if(sizeof($featureoverviewArr) > 0){
							$desc = implode(" ",$featureoverviewArr);
							if(!empty($desc) && !empty($overview_title)){
								#$desc = "<span class=\"b\">$overview_title:&#160;</span>$desc";
								$overviewArr[$overview_title] = $desc;
							}elseif(!empty($desc) && empty($overview_title)){
								$overviewArr[] = $desc;
							}
						}

					}
					#$product_result[$j]['short_desc'] = implode('<span class="dvder">|</span>',$overviewArr);
					$product_result[$j]['short_desc'] = $overviewArr;

					unset($overviewArr);
				}

				/*
				if(!empty($category_id) && !empty($product_id)){

					$sOverviewArray = $feature->arrGetSummary($category_id,$product_id,$type="array");
				}
				if(is_array($sOverviewArray)){
					unset($productNameArr[0]);		// remove brand name form array.
					foreach($sOverviewArray as $key=>$val){
						if($sOverviewArray[$key][0]){
							$overviewArr[] = implode(",&#160;",$sOverviewArray[$key][0]);
						}
					}
					$product_result[$j]['short_desc'] = implode(",&#160;",$overviewArr);
					unset($overviewArr);
				}else{
					$product_result[$j]['short_desc'] = "";
				}
				*/
			}
			$result[$i] = $product_result;

		}
		#print_r($result);exit;
		return $result;
	 }
         /**
	* @note function is used to get global research price product details
	*
	* @pre not required.
	*
	* @param string $product_info
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param string $startprice
	* @param string $endprice
	* @param is an integer $variant_id
	* @param an integer/comma seperated brand ids/ brand ids array $brand_ids.
	* @param is an integer/comma seperated product ids/ product ids array $product_ids.
	* @param is an integer/comma seperated feature ids/ feature ids array $feature_ids.
	* @param is a string $oredrby.
	*
	* @post is an associative array.
	* retun an array.
	*/

	 function globalResearchPriceProductDetailsByCity($product_info,$category_id,$startprice,$endprice,$variant_id,$brand_ids="",$product_ids="",$feature_ids="",$orderby="PRICE_VARIANT_VALUES.variant_value asc",$default_city="1",$cityId="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){

		$keyArr[] = $this->productKey."_carfinder_global_search";
		unset($tablenameArr);
		$this->assignPivotToSearch();

		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
		if(is_array($feature_ids)){
			$feature_ids = implode(",",$feature_ids);
		}
		if(!empty($feature_ids)){
			$featureArr = explode(",",$feature_ids);
			foreach($featureArr as $feature_id){
				if(in_array($feature_id,$this->bodyStyleArr)){
					$keyArr[] = $feature_id;
					$this->newBodyStyleArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->fuelTypeArr)){
					$keyArr[] = $feature_id;
					$this->newFuelTypeArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->impFeatureArr)){
					$keyArr[] = $feature_id;
					$this->newImpFeatureArr[] = "select product_id from PRODUCT_FEATURE where feature_id in ($feature_id)";
				}elseif(in_array($feature_id,$this->tranmissionArr)){
					$keyArr[] = $feature_id;
					$this->newTranmissionArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->seatingCapcityArr)){
					$keyArr[] = $feature_id;
					$this->newSeatingCapcityArr[] = $feature_id;
				}
			}
			if(sizeof($this->newBodyStyleArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newBodyStyleArr).")";
			}
			if(sizeof($this->newFuelTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newFuelTypeArr).")";
			}
			if(sizeof($this->newImpFeatureArr) > 0){
				$sqlStr = "";
				foreach($this->newImpFeatureArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
				//$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newImpFeatureArr).")";
			}
			if(sizeof($this->newTranmissionArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newTranmissionArr).")";
			}
			if(sizeof($this->newSeatingCapcityArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newSeatingCapcityArr).")";
			}

			if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
		}
		if(!empty($product_info)){
			 $keyArr[] = $product_info;
			 $whereClauseArr[] = "PRODUCT_MASTER.product_name = '".trim($product_info)."'";
			 $whereClauseArr[] = "PRODUCT_MASTER.status=1";
		}else{$keyArr[] =-1;}

		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] =-1;}

		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] =-1;}

		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] =-1;}

                if(!(empty($cityId)))
                {
                    if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
						$keyArr[] = "c_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$cityId;
						$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
						$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = ".$cityId;
						$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
                    }else{
                    	$keyArr[] = 'c_-1_-1_-1_-1_-1';
                    }
                }
                else
                {
                    if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
						$keyArr[] = "dc_$startprice".'_'.$endprice.'_'.$variant_id.'_'.$color_id.'_'.$default_city;
						$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
						$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city = $default_city";
						$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
                    }else{
                    	$keyArr[] = 'dc_-1_-1_-1_-1';
                    }
                }

		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[] =-1;}

		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in($brand_ids)";
		}else{$keyArr[] =-1;}

		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] =-1;}

		$tablenameArr[] = "PRODUCT_MASTER";
		if($status != '')
		{
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] =-1;}


		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] =-1;}

		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] =-1;}

		if(!empty($orderby)){
			$keyArr[] = $orderby;
			$orderby = $orderby;
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
		$table_name = implode(",",$tablenameArr);
		$sql = "select PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.* FROM $table_name $whereClauseStr  order by $orderby";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	function arrGetRecommendationRange($price_value){
		$keyArr[] = $this->productKey."_unique";
		$keyArr[] = $price_value;
		$key = implode("_",$keyArr);
		////if($result = $this->cache->get($key)){return $result;}
		$sql = "Select * from RECOMMENDATION_PRICE_RANGE";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}



	 /////used car section start////

	 /**
	* @note function is used to insert the model details into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $used_model_id.
	* retun integer.
	*/
	function intInsertUsedCarModel($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USEDCAR_MODEL_MASTER",array_keys($insert_param),array_values($insert_param));
		$used_model_id = $this->insert($sql);
		if(trim($used_model_id) == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->usedproductKey.'_model');
		return $used_model_id;
	}
	/**
	* @note function is used to update the model details into the database.
	* @param an associative array $update_param.
	* @param an integer $used_model_id.
	* @pre $update_param must be valid associative array and $used_model_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* retun boolean.
	*/
	function boolUpdateUsedCarModel($used_model_id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USEDCAR_MODEL_MASTER",array_keys($update_param),array_values($update_param),"used_model_id",$used_model_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey.'_model');
		return $isUpdate;
	}

	/**
	* @note function is used to get model details.
	* @param an integer/comma seperated model ids/ model ids array $used_model_ids.
	* @param an integer/comma separated category_id $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post brand details in associative array.
	* retun an array.
	*/
	function arrGetUsedCarModelDetails($used_model_ids="",$model_ids="",$brand_ids="",$category_id="",$status="1",$startlimit="",$count="",$model_name="",$orderby="",$year="",$startyear="",$endyear="",$used_brand_ids="")
	{
		$keys[] = $this->usedproductKey.'_model';
		if(is_array($used_model_ids)){
			$used_model_ids = implode(",",$used_model_ids);
		}
		if(!empty($used_model_ids)){
			$whereClauseArr[] = "used_model_id in($used_model_ids)";
			$keys[] = "used_model_id_$used_model_ids";
		}
		if(is_array($model_ids)){
			$model_ids = implode(",",$model_ids);
		}
		if(!empty($model_ids)){
			$whereClauseArr[] = "model_id in($model_ids)";
			$keys[] = "model_id_$model_ids";
		}
		if(is_array($used_brand_ids)){
			$used_brand_ids = implode(",",$used_brand_ids);
		}
		if(!empty($used_brand_ids)){
			$whereClauseArr[] = "used_brand_id in($used_brand_ids)";
			$keys[] = "used_brand_id_$used_brand_ids";
		}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keys[] = "category_id_$category_id";
		}
		if(!empty($year)){
			$whereClauseArr[] = "year in ($year)";
			$keys[] = "year_$year";
		}
		if(!empty($startyear) && !empty($endyear) && empty($year)){
			$whereClauseArr[] = "year >= '$startyear'";
			$whereClauseArr[] = "year <= '$endyear'";
		}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keys[] = "status_$status";
		}
		if(!empty($model_name)){
			$model_name = strtolower($model_name);
			$whereClauseArr[] = "model_name= '$model_name'";
			$keys[] = "model_name_$model_name";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keys[] = "startlimit_".$startlimit;
		}
		if(!empty($count)){
			$limitArr[] = $count;
			$keys[] = "count_".$count;
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by model_name asc";
		}
		$key = implode('_',$keys);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from USEDCAR_MODEL_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used to get model details.
	* @param an integer/comma seperated model ids/ model ids array $used_model_ids.
	* @param an integer/comma separated category_id $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post brand details in associative array.
	* retun an array.
	*/
	function arrGetUsedCarModelDetailsCount($used_model_ids="",$model_ids="",$brand_ids="",$category_id="",$status="1",$startlimit="",$count="",$model_name="",$orderby="",$year="",$startyear="",$endyear="",$used_brand_id="")
	{
		$keys[] = $this->usedproductKey.'_model_cnt';
		if(is_array($used_model_ids)){
			$used_model_ids = implode(",",$used_model_ids);
		}
		if(!empty($used_model_ids)){
			$whereClauseArr[] = "used_model_id in($used_model_ids)";
			$keys[] = "used_model_id_$used_model_ids";
		}
		if(is_array($model_ids)){
			$model_ids = implode(",",$model_ids);
		}
		if(!empty($model_ids)){
			$whereClauseArr[] = "model_id in($model_ids)";
			$keys[] = "model_id_$model_ids";
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(!empty($brand_ids)){
			$whereClauseArr[] = "brand_id in($brand_ids)";
			$keys[] = "brand_id_$brand_ids";
		}
		if(!empty($used_brand_id)){
			$whereClauseArr[] = "used_brand_id in($used_brand_id)";
			$keys[] = "used_brand_id_$used_brand_id";
		}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keys[] = "category_id_$category_id";
		}
		if(!empty($year)){
			$whereClauseArr[] = "year in ($year)";
			$keys[] = "year_$year";
		}
		if(!empty($startyear) && !empty($endyear) && empty($year)){
			$whereClauseArr[] = "year >= '$startyear'";
			$whereClauseArr[] = "year <= '$endyear'";
		}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keys[] = "status_$status";
		}
		if(!empty($model_name)){
			$model_name = strtolower($model_name);
			$whereClauseArr[] = "model_name= '$model_name'";
			$keys[] = "model_name_$model_name";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keys[] = "startlimit_".$startlimit;
		}
		if(!empty($count)){
			$limitArr[] = $count;
			$keys[] = "count_".$count;
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by model_name asc";
		}
		$key = implode('_',$keys);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select count(model_id) as cnt from USEDCAR_MODEL_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used to delete the model.
	* @param integer $used_model_id.
	* @pre $brand_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteUsedCarModel($used_model_id){
		$sql = "delete from USEDCAR_MODEL_MASTER where used_model_id = $used_model_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey.'_variant');
		return $isDelete;
	}

	function intInsertUsedCarVariant($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USEDCAR_VARIANT_MASTER",array_keys($insert_param),array_values($insert_param));
		$used_variant_id = $this->insert($sql);
		if(trim($used_variant_id) == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->usedproductKey.'_variant');
		return $used_variant_id;
	}

	function boolUpdateUsedCarVariant($used_variant_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USEDCAR_VARIANT_MASTER",array_keys($update_param),array_values($update_param),"used_variant_id",$used_variant_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey.'_variant');
		return $isUpdate;
	}
	function boolDeleteUsedCarVariant($used_variant_id){
		$sql = "delete from USEDCAR_VARIANT_MASTER where used_variant_id = $used_variant_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey.'_variant');
		return $isDelete;
	}
	function arrGetUsedCarVariantDetailsCount($category_id="",$used_variant_id="",$used_brand_id="",$used_model_id="",$brand_id ="",$model_id="",$product_id="",$status="1",$startlimit="",$count="",$variant_name="",$orderby="",$year="",$startyear="",$endyear="")
	{
		$keyArr[] = $this->usedproductKey.'_variant_cnt';
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}

		if(!empty($startyear) && !empty($endyear) && empty($year)){
			$whereClauseArr[] = "year >= '$startyear'";
			$whereClauseArr[] = "year <= '$endyear'";
			$keyArr[] = $startyear;
			$keyArr[] = $endyear;
		}else{$keyArr[] ='-1_-1';}

		if(!empty($year)){
			$whereClauseArr[] = "year in ($year)";
			$keyArr[] = $endyear;
		}else{$keyArr[] =-1;}

		if(is_array($used_variant_id)){
			$used_variant_id = implode(",",$used_variant_id);
		}
		if(!empty($used_variant_id)){
			$whereClauseArr[] = "used_variant_id in($used_variant_id)";
			$keyArr[] = $used_variant_id;
		}else{$keyArr[] =-1;}

		if(is_array($used_brand_id)){
			$used_brand_id = implode(",",$used_brand_id);
		}
		if(!empty($used_brand_id)){
			$whereClauseArr[] = "used_brand_id in($used_brand_id)";
			$keyArr[] = $used_brand_id;
		}else{$keyArr[] =-1;}

		if(is_array($used_model_id)){
			$used_model_id = implode(",",$used_model_id);
		}
		if(!empty($used_model_id)){
			$whereClauseArr[] = "used_model_id in($used_model_id)";
			$keyArr[] = $used_model_id;
		}else{$keyArr[] =-1;}

		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}

		if(is_array($model_id)){
			$model_id = implode(",",$model_id);
		}
		if(!empty($model_id)){
			$whereClauseArr[] = "model_id in($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}

    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}

		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}

		if(!empty($variant_name)){
			$variant_name = strtolower($variant_name);
			$whereClauseArr[] = "variant_name= '$variant_name'";
			$keyArr[] = $variant_name;
		}else{$keyArr[] =-1;}

		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}

		if(!empty($count)){
			$limitArr[] = $count;
			$keyArr[] = $count;
		}else{$keyArr[] =-1;}

		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by variant_name asc";
		}
		$keyArr[] = $orderby;
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select count(used_variant_id) as cnt from USEDCAR_VARIANT_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetUsedCarVariantDetails($category_id="",$used_variant_id="",$used_brand_id="",$used_model_id="",$brand_id="",$model_id="",$product_id="",$status="1",$startlimit="",$count="",$variant_name="",$orderby="",$year="",$startyear="",$endyear="")
	{
		$keyArr[] = $this->usedproductKey.'_variant';
		if(!empty($year)){
			$whereClauseArr[] = "year in ($year)";
			$keyArr[] = $year;
		}else{$keyArr[] =-1;}

		if(!empty($startyear) && !empty($endyear) && empty($year)){
			$whereClauseArr[] = "year >= '$startyear'";
			$whereClauseArr[] = "year <= '$endyear'";
			$keyArr[] = $startyear;
			$keyArr[] = $endyear;
		}else{$keyArr[] ='-1_-1';}

		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}

		if(is_array($used_variant_id)){
			$used_variant_id = implode(",",$used_variant_id);
		}
		if(!empty($used_variant_id)){
			$whereClauseArr[] = "used_variant_id in($used_variant_id)";
			$keyArr[] = $used_variant_id;
		}else{$keyArr[] =-1;}

		if(is_array($used_brand_id)){
			$used_brand_id = implode(",",$used_brand_id);
		}
		if(!empty($used_brand_id)){
			$whereClauseArr[] = "used_brand_id in($used_brand_id)";
			$keyArr[] = $used_brand_id;
		}else{$keyArr[] =-1;}

		if(is_array($used_model_id)){
			$used_model_id = implode(",",$used_model_id);
		}
		if(!empty($used_model_id)){
			$whereClauseArr[] = "used_model_id in($used_model_id)";
			$keyArr[] = $used_model_id;
		}else{$keyArr[] =-1;}

		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}

		if(is_array($model_id)){
			$model_id = implode(",",$model_id);
		}
		if(!empty($model_id)){
			$whereClauseArr[] = "model_id in($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}

    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}

		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}

		if(!empty($variant_name)){
			$variant_name = strtolower($variant_name);
			$whereClauseArr[] = "variant_name= '$variant_name'";
			$keyArr[] = $variant_name;
		}else{$keyArr[] =-1;}

		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}

		if(!empty($count)){
			$limitArr[] = $count;
			$keyArr[] = $count;
		}else{$keyArr[] =-1;}

		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by variant_name asc";
		}
		$keyArr[] = $orderby;
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from USEDCAR_VARIANT_MASTER $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function intInsertUsedCarProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		if(empty($insert_param['listing_start_date'])){
			$insert_param['listing_start_date'] = date('Y-m-d').' 00:00:00';
		}
		if(empty($insert_param['listing_end_date'])){
			$insert_param['listing_end_date'] = date('Y-m-d',strtotime("+3 month")).' 23:59:59';
		}
		$sql = $this->getInsertSql("USEDCAR_PRODUCT_MASTER",array_keys($insert_param),array_values($insert_param));
		$used_variant_id = $this->insert($sql);
		if(trim($used_variant_id) == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->usedproductKey);
		return $used_variant_id;
	}

	function boolUpdateUsedCarProduct($used_product_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USEDCAR_PRODUCT_MASTER",array_keys($update_param),array_values($update_param),"used_product_id",$used_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey);
		return $isUpdate;
	}
	function boolDeleteUsedCarProduct($used_product_id,$suggest_product_id){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$update_param['status'] = '-1';
		if(!empty($suggest_product_id)){
			$sql = $this->getUpdateSql("USEDCAR_SUGGEST_PRODUCT_MASTER",array_keys($update_param),array_values($update_param),"suggest_product_id",$suggest_product_id);
			$isUpdate = $this->update($sql);
		}
		$sql = $this->getUpdateSql("USEDCAR_PRODUCT_MASTER",array_keys($update_param),array_values($update_param),"used_product_id",$used_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey);
		return $isUpdate;
		//$sql = "delete from USEDCAR_PRODUCT_MASTER where used_product_id = $used_product_id";
		//return $isDelete = $this->sql_delete_data($sql);
	}
	function arrGetUsedCarProductDetailsCount($request_param){
		list($category_id,$used_product_id,$used_brand_id,$used_model_id,$used_variant_id,$start_year,$end_year,$start_km_running,$end_km_running,$start_price,$end_price,$start_mrp,$end_mrp,$sellerid,$brand_id,$model_id,$product_id,$status,$startlimit,$cnt,$orderby,$chkliststartdate,$chklistenddate) = array($request_param['category_id'],$request_param['used_product_id'],$request_param['used_brand_id'],$request_param['used_model_id'],$request_param['used_variant_id'],$request_param['start_year'],$request_param['end_year'],$request_param['start_km_running'],$request_param['end_km_running'],$request_param['start_price'],$request_param['end_price'],$request_param['start_mrp'],$request_param['end_mrp'],$request_param['sellerid'],$request_param['brand_id'],$request_param['model_id'],$request_param['product_id'],$request_param['status'],$request_param['startlimit'],$request_param['cnt'],$request_param['orderby'],$request_param['chkliststartdate'],$request_param['chklistenddate']);

		$keyArr[] = $this->usedproductKey.'_prd_detail_cnt';
		$keyArr[] = 'status_-1';
		$keyArr[] = 'is_sold_0';
		$whereClauseArr[] ="status != '-1'";
		$whereClauseArr[] ="is_sold = '0'";

		$listing_end_date = date('Y-m-d');
		$chklistenddate = !$chklistenddate ? 'true' : $chklistenddate;
		if($chklistenddate == 'true'){
			$whereClauseArr[] = "listing_end_date >= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}


		if($chkliststartdate == 'true'){
			$whereClauseArr[] = "listing_start_date <= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_start_date;
		}else{$keyArr[] =-1;}


		if(!empty($category_id)){
			$whereClauseArr[] ="category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_product_id)){
			if(is_array($used_product_id)){ $used_product_id = implode(",",$used_product_id);}
			$whereClauseArr[] ="used_product_id in($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_brand_id)){
			if(is_array($used_brand_id)){ $used_brand_id = implode(",",$used_brand_id);}
			$whereClauseArr[] ="used_brand_id in($used_brand_id)";
			$keyArr[] = $used_brand_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_model_id)){
			if(is_array($used_model_id)){ $used_model_id = implode(",",$used_model_id);}
			$whereClauseArr[] ="used_model_id in($used_model_id)";
			$keyArr[] = $used_model_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_variant_id)){
			if(is_array($used_variant_id)){ $used_variant_id = implode(",",$used_variant_id);}
			$whereClauseArr[] ="used_variant_id in($used_variant_id)";
			$keyArr[] = $used_variant_id;
		}else{$keyArr[] =-1;}


		if(!empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year >= '$start_year'";
			$whereClauseArr[] = "year <= '$end_year'";
			$keyArr[] = $start_year;
			$keyArr[] = $end_year;
		}else if(!empty($start_year) && empty($end_year)){
			$whereClauseArr[] = "year = '$start_year'";
			$keyArr[] = $start_year;
			$keyArr[] = -1;
		}else if(empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year = '$end_year'";
			$keyArr[] = -1;
			$keyArr[] = $end_year;
		}

		if(!empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running >= '$start_km_running'";
			$whereClauseArr[] = "km_running <= '$end_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = $end_km_running;
		}else if(!empty($start_km_running) && empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$start_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = -1;
		}else if(empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$end_km_running'";
			$keyArr[] = -1;
			$keyArr[] = $end_km_running;
		}

		if(!empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price >= '$start_price'";
			$whereClauseArr[] = "price <= '$end_price'";
			$keyArr[] = $start_price;
			$keyArr[] = $end_price;
		}else if(!empty($start_price) && empty($end_price)){
			$whereClauseArr[] = "price = '$start_price'";
			$keyArr[] = $start_price;
			$keyArr[] = -1;
		}else if(empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price = '$end_price'";
			$keyArr[] = -1;
			$keyArr[] = $end_price;
		}

		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = $end_mrp;
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = -1;
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
			$keyArr[] = -1;
			$keyArr[] = $end_mrp;
		}


		if(!empty($sellerid)){
			if(is_array($sellerid)){ $sellerid = implode(",",$sellerid);}
			$whereClauseArr[] ="sellerid in($sellerid)";
			$keyArr[] = $sellerid;
		}else{$keyArr[] =-1;}

		if(!empty($brand_id)){
			if(is_array($brand_id)){ $brand_id = implode(",",$brand_id);}
			$whereClauseArr[] ="brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}

		if(!empty($model_id)){
			if(is_array($model_id)){ $model_id = implode(",",$model_id);}
			$whereClauseArr[] ="model_id in($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}
    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] ="product_id in($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] ="status = $status";
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
		if(empty($orderby)){ $orderby = "order by create_date desc"; }
		$keyArr[] = $orderby;

		if(sizeof($whereClauseArr) > 0) { $whereClauseStr = ' where '.implode(" and ",$whereClauseArr); }
		if(sizeof($limitArr) > 0) { $limitStr = ' limit '.implode(",",$limitArr); }
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "select count(used_product_id) as cnt from USEDCAR_PRODUCT_MASTER  $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetUsedCarProductDetails($request_param){
		//print_r($request_param);
		list($category_id,$used_product_id,$used_brand_id,$used_model_id,$used_variant_id,$start_year,$end_year,$start_km_running,$end_km_running,$start_price,$end_price,$start_mrp,$end_mrp,$sellerid,$brand_id,$model_id,$product_id,$status,$startlimit,$cnt,$orderby,$chkliststartdate,$chklistenddate,$city_id,$is_sold) = array($request_param['category_id'],$request_param['used_product_id'],$request_param['used_brand_id'],$request_param['used_model_id'],$request_param['used_variant_id'],$request_param['start_year'],$request_param['end_year'],$request_param['start_km_running'],$request_param['end_km_running'],$request_param['start_price'],$request_param['end_price'],$request_param['start_mrp'],$request_param['end_mrp'],$request_param['sellerid'],$request_param['brand_id'],$request_param['model_id'],$request_param['product_id'],$request_param['status'],$request_param['startlimit'],$request_param['cnt'],$request_param['orderby'],$request_param['chkliststartdate'],$request_param['chklistenddate'],$request_param['city_id'],$request_param['is_sold']);

		$keyArr[] = $this->usedproductKey.'_prd_detail';
		$keyArr[] = "status_-1";
		$whereClauseArr[] ="status != '-1'";
		$listing_end_date = date('Y-m-d');
		$chklistenddate = !$chklistenddate ? 'true' : $chklistenddate;
		if($chklistenddate == 'true'){
			$whereClauseArr[] = "listing_end_date >= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}
		if($chkliststartdate == true){
			$whereClauseArr[] = "listing_start_date <= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$whereClauseArr[] ="category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if(!empty($used_product_id)){
			if(is_array($used_product_id)){ $used_product_id = implode(",",$used_product_id);}
			$whereClauseArr[] ="used_product_id in($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}
		if(!empty($used_brand_id)){
			if(is_array($used_brand_id)){ $used_brand_id = implode(",",$used_brand_id);}
			$whereClauseArr[] ="used_brand_id in($used_brand_id)";
			$keyArr[] = $used_brand_id;
		}else{$keyArr[] =-1;}
		if(!empty($used_model_id)){
			if(is_array($used_model_id)){ $used_model_id = implode(",",$used_model_id);}
			$whereClauseArr[] ="used_model_id in($used_model_id)";
			$keyArr[] = $used_model_id;
		}else{$keyArr[] =-1;}
		if(!empty($used_variant_id)){
			if(is_array($used_variant_id)){ $used_variant_id = implode(",",$used_variant_id);}
			$whereClauseArr[] ="used_variant_id in($used_variant_id)";
			$keyArr[] = $used_variant_id;
		}else{$keyArr[] =-1;}
		if(!empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year >= '$start_year'";
			$whereClauseArr[] = "year <= '$end_year'";
			$keyArr[] = $start_year;
			$keyArr[] = $end_year;
		}else if(!empty($start_year) && empty($end_year)){
			$whereClauseArr[] = "year = '$start_year'";
			$keyArr[] = $start_year;
			$keyArr[] = -1;
		}else if(empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year = '$end_year'";
			$keyArr[] = -1;
			$keyArr[] = $end_year;
		}

		if(!empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running >= '$start_km_running'";
			$whereClauseArr[] = "km_running <= '$end_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = $end_km_running;
		}else if(!empty($start_km_running) && empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$start_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = -1;
		}else if(empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$end_km_running'";
			$keyArr[] = -1;
			$keyArr[] = $end_km_running;
		}
		if(!empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price >= '$start_price'";
			$whereClauseArr[] = "price <= '$end_price'";
			$keyArr[] = $start_price;
			$keyArr[] = $end_price;
		}else if(!empty($start_price) && empty($end_price)){
			$whereClauseArr[] = "price = '$start_price'";
			$keyArr[] = $start_price;
			$keyArr[] = -1;
		}else if(empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price = '$end_price'";
			$keyArr[] = -1;
			$keyArr[] = $end_price;
		}
		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = $end_mrp;
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = -1;
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
			$keyArr[] = -1;
			$keyArr[] = $end_mrp;
		}
		/*
		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
		}
		*/
		if(!empty($sellerid)){
			if(is_array($sellerid)){ $sellerid = implode(",",$sellerid);}
			$whereClauseArr[] ="sellerid in($sellerid)";
			$keyArr[] = $sellerid;
		}else{$keyArr[] =-1;}
		if(!empty($brand_id)){
			if(is_array($brand_id)){ $brand_id = implode(",",$brand_id);}
			$whereClauseArr[] ="brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}
		if(!empty($model_id)){
			if(is_array($model_id)){ $model_id = implode(",",$model_id);}
			$whereClauseArr[] ="model_id in($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}
		if(is_array($product_id)){
      			foreach($product_id as $variant_id){
        			$i_variant_ids = intval($variant_id);
					if($i_variant_ids!=0){
			          $variant_ids[] = $i_variant_ids;
        			}
      			}
				$product_id = implode(",",$variant_ids);
			}else{
				if(strpos($product_id,',')==false){
					if(intval($product_id)!=0){
						$product_id = intval($product_id);
					}
				}else{
					$arr_variant_ids = explode(",",$product_id);
					foreach($arr_variant_ids as $variant_id){
						$i_variant_ids = intval($variant_id);
						if($i_variant_ids!=0){
							$variant_ids[] = $i_variant_ids;
						}
					}
					$product_id = implode(",",$variant_ids);
				}
		   }
		if(!empty($product_id)){
			$whereClauseArr[] ="product_id in($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(!empty($is_sold)){
			$whereClauseArr[] ="is_sold = $is_sold";
		}else{
			$whereClauseArr[] ="is_sold = '0'";
		}
		$keyArr[] = $is_sold;
		if(!empty($city_id)){
			$whereClauseArr[] ="city_id = $city_id";
			$keyArr[] = $city_id;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] ="status = $status";
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
		if(empty($orderby)){ $orderby = "order by create_date desc"; }
		$keyArr[] = $orderby;
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(sizeof($whereClauseArr) > 0) { $whereClauseStr = ' where '.implode(" and ",$whereClauseArr); }
		if(sizeof($limitArr) > 0) { $limitStr = ' limit '.implode(",",$limitArr); }
		$sql = "select * from USEDCAR_PRODUCT_MASTER  $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function checkRegNo($category_id="",$used_product_id="",$reg_no=""){
		$keyArr[] = $this->usedproductKey.'_checkRegNo';
		if(!empty($category_id)){
			$whereClauseArr[] ="category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if(!empty($used_product_id)){
			if(is_array($used_product_id)){ $used_product_id = implode(",",$used_product_id);}
			$whereClauseArr[] ="used_product_id in($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}
		if(!empty($reg_no)){
			$whereClauseArr[] ="registration = '".$reg_no."'";
			$keyArr[] = $reg_no;
		}else{$keyArr[] =-1;}
		$key = implode('_',$keys);
		$result = $this->cache->get($keyArr);
		if(!empty($result)){ return $result;}
		if(sizeof($whereClauseArr) > 0) { $whereClauseStr = ' where '.implode(" and ",$whereClauseArr); }
		$sql = "select * from USEDCAR_PRODUCT_MASTER  $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function intInsertUsedCarProductFeature($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("USEDCAR_PRODUCT_FEATURE",array_keys($insert_param),array_values($insert_param));
		$feature_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey.'_feature');
		return $feature_id;
	}
	function arrGetUsedCarProductFeatureDetails($product_feature_id="",$feature_id="",$product_id="",$startlimit="",$cnt=""){
		$keyArr[] = $this->usedproductKey.'_feature';
		if(!empty($product_feature_id)){
			$whereClauseArr[] = "USEDCAR_PRODUCT_FEATURE.product_feature_id in ($product_feature_id)";
			$keyArr[] = $product_feature_id;
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$whereClauseArr[] = "USEDCAR_PRODUCT_FEATURE.feature_id in ($feature_id)";
			$keyArr[] = $feature_id;
		}else{$keyArr[] =-1;}
    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] = "USEDCAR_PRODUCT_FEATURE.product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$whereClauseArr[] = "USEDCAR_FEATURE_MASTER.feature_id = USEDCAR_PRODUCT_FEATURE.feature_id";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$sql = "select USEDCAR_PRODUCT_FEATURE.*,USEDCAR_FEATURE_MASTER.* from USEDCAR_PRODUCT_FEATURE,USEDCAR_FEATURE_MASTER $whereClauseStr order by USEDCAR_FEATURE_MASTER.create_date desc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function boolDeleteUsedCarProductFeature($product_feature_id)
	{
		$sql = "delete from USEDCAR_PRODUCT_FEATURE where product_feature_id = $product_feature_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey.'_feature');
		return $isDelete;
	}

	function arrGetUsedCarProductFieldsDetails($category_id="",$used_product_id="",$used_brand_id="",$used_model_id="",$brand_id="",$model_id="",$product_id="",$status="1",$startlimit="",$count="",$variant_name="",$orderby=""){
		$key = $this->usedproductKey.'_prd_fld_$orderby_$limitStr';
		$result = $this->cache->get($keyArr);
		if(!empty($result)){ return $result;}
		$sql = "select category_id as Category,used_brand_id as BrandName,used_model_id as ModelName,used_variant_id as VariantName,product_desc as ProductDesc, listing_start_date as ListingStartDate,listing_end_date as ListingEndDate,year as Year,country_id as Country,state_id as State,city_id as City,km_running as KmRunning,mrp as Mrp,price as Price,discount_offer as DiscountOffer,registration as Registration,registerd_at as RegistrationAt from USEDCAR_PRODUCT_MASTER $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function intInsertUsedCarProductImg($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USEDCAR_PRODUCT_IMAGE",array_keys($insert_param),array_values($insert_param));
		$img_id = $this->insert($sql);
		if(trim($img_id) == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->usedproductKey.'_prd_img');
		return $img_id;
	}
	function boolUpdateUsedCarProductImg($img_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USEDCAR_PRODUCT_IMAGE",array_keys($update_param),array_values($update_param),"img_id",$img_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->usedproductKey.'_prd_img');
		return $isUpdate;
	}
	function arrGetUsedCarProductImg($img_id="",$used_product_id="",$startlimit="",$cnt=""){
		$keyArr[] = $this->usedproductKey.'_prd_img';
		if(!empty($img_id)){
			$whereClauseArr[] = "img_id in ($img_id)";
			$keyArr[] = $img_id;
		}else{$keyArr[] =-1;}
		if(!empty($used_product_id)){
			$whereClauseArr[] = "used_product_id in ($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}
    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		$sql = "select * from USEDCAR_PRODUCT_IMAGE $whereClauseStr order by img_id asc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetUsedCarProductImgCnt($used_product_id=""){
		$keyArr[] = $this->usedproductKey.'_prd_img_cnt';
		if(!empty($used_product_id)){
			$whereClauseArr[] = "used_product_id in ($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$sql = "select count(img_id) as cnt from USEDCAR_PRODUCT_IMAGE $whereClauseStr order by img_id asc";
		//error_log("product img cnt = $sql");
		$result = $this->select($sql);
		$cnt = (!empty($result[0]['cnt'])) ? $result[0]['cnt'] : '0';
		$this->cache->set($key, $cnt);
		return $cnt;
	}
	function arrGetMinYear(){
		$key = $this->usedproductKey."_arrGetMinYear_status_1";
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select min(year) as minyear from USEDCAR_PRODUCT_MASTER where status = 1 order by used_product_id asc";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetMaxYear(){
		$key = $this->usedproductKey."_arrGetMaxYear_status_1";
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select max(year) as maxyear from USEDCAR_PRODUCT_MASTER where status = 1 order by used_product_id asc";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetMinMileage(){
		$key = $this->usedproductKey."_arrGetMinMileage_status_1";
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select min(km_running ) as minmileage from USEDCAR_PRODUCT_MASTER where status = 1 order by used_product_id asc";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetMaxMileage(){
		$key = $this->usedproductKey."_arrGetMaxMileage_status_1";
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select max(km_running ) as maxmileage from USEDCAR_PRODUCT_MASTER where status = 1 order by used_product_id asc";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function searchUsedCarProductCount($category_ids="",$brand_ids="",$product_ids="",$feature_ids="",$city_ids="",$status="1",$startprice="",$endprice="",$start_year="",$end_year="",$start_km_running="",$end_km_running,$owner='',$certify=""){
		$keyArr[] = $this->usedproductKey."_searchUsedCarProductCount";
		$this->assignUsedCarPivotToSearch();
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
	if(is_array($feature_ids)){
		$feature_ids = implode(",",$feature_ids);
	}

	if(is_array($city_ids)){
		$city_ids = implode(",",$city_ids);
	}
	if(!empty($feature_ids)){
		$featureArr = explode(",",$feature_ids);
		foreach($featureArr as $feature_id){
			if(in_array($feature_id,$this->bodyStyleArr)){
				$keyArr[] = $feature_id;
				$this->newBodyStyleArr[] = $feature_id;
			}elseif(in_array($feature_id,$this->fuelTypeArr)){
				$keyArr[] = $feature_id;
				$this->newFuelTypeArr[] = $feature_id;
			}elseif(in_array($feature_id,$this->impFeatureArr)){
				$keyArr[] = $feature_id;
				$this->newImpFeatureArr[] =  $feature_id;;
			}
		}

	/*print_r($this->newBodyStyleArr);
	echo "<br>";
	print_r($this->newFuelTypeArr);
	echo "<br>";
	print_r($this->newImpFeatureArr);
	echo "<br>";
	*/

		$this->newBodyStyleArr = array_unique($this->newBodyStyleArr,SORT_REGULAR);
		$this->newFuelTypeArr = array_unique($this->newFuelTypeArr,SORT_REGULAR);
		$this->newImpFeatureArr = array_unique($this->newImpFeatureArr,SORT_REGULAR);

		if(sizeof($this->newBodyStyleArr) > 0){

			$sqlArr[] = "select product_id from USEDCAR_PRODUCT_FEATURE where feature_id in (".implode(",",$this->newBodyStyleArr).")";
		}

		if(sizeof($this->newFuelTypeArr) > 0){
			$sqlArr[] = "select product_id from USEDCAR_PRODUCT_FEATURE where feature_id in (".implode(",",$this->newFuelTypeArr).")";
		}

		if(sizeof($this->newImpFeatureArr) > 0){
			$sqlArr[] = "select product_id from USEDCAR_PRODUCT_FEATURE where feature_id in (".implode(",",$this->newImpFeatureArr).")";
		}

		if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = " USEDCAR_PRODUCT_MASTER.used_product_id in (".$sqlStr.")";
				}
			}

	}else{$keyArr[] = -1;}

	$result_maxmin = $this->getMaxMinMileageInUsedCars();
	$max_km = $result_maxmin[0]['max_km_running'];
	$min_km = $result_maxmin[0]['min_km_running'];
	if(!empty($start_km_running) && !empty($end_km_running)){
		if($start_km_running == 100){
			$start_km_running = $min_km;
		}
		if($end_km_running == 100000){
			$end_km_running = $max_km;
		}
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running >= '$start_km_running'";
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running <= '$end_km_running'";
		$keyArr[] = $start_km_running;
        $keyArr[] = $end_km_running;

	}else if(!empty($start_km_running) && empty($end_km_running)){
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running = '$start_km_running'";
		$keyArr[] = $start_km_running;
		$keyArr[] = -1;
	}else if(empty($start_km_running) && !empty($end_km_running)){
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running = '$end_km_running'";
		$keyArr[] = -1;
		$keyArr[] = $end_km_running;
	}
	if(!empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year >= '$start_year'";
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year <= '$end_year'";
			$keyArr[] = $start_year;
            $keyArr[] = $end_year;
	}else if(!empty($start_year) && empty($end_year)){
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year = '$start_year'";
		$keyArr[] = $start_year;
		$keyArr[] = -1;
	}else if(empty($start_year) && !empty($end_year)){
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year = '$end_year'";
		$keyArr[] = -1;
		$keyArr[] = $end_year;
	}
	if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.price>=$startprice";
	}else{$keyArr[] =-1;}
	if(!empty($endprice)){
		$keyArr[] = $endprice;
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.price<=$endprice";
	}else{$keyArr[] =-1;}

	if(!empty($category_ids)){
		$keyArr[] = $category_ids;
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.category_id in($category_ids)";
	}else{$keyArr[] =-1;}
	if(!empty($brand_ids)){
		$keyArr[] = $brand_ids;
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.used_brand_id in($brand_ids)";
	}else{$keyArr[] =-1;}
	if(!empty($product_ids)){
		$keyArr[] = $product_ids;
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.used_product_id in($product_ids)";
	}else{$keyArr[] =-1;}
	if($owner!=''){
		$keyArr[] = $owner;
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.is_dealer in($owner)";
	}else{$keyArr[] =-1;}
	if($certify!=''){
		$keyArr[] = $certify;
                $certificates = explode(",",$certify);
                $certificates_cnt = sizeof($certificates);
                $search_str = "";
                for($i=0;$i<$certificates_cnt;$i++){
                        $certificate = $certificates[$i];
                        if($search_str == ""){
                                $search_str.="USEDCAR_PRODUCT_MASTER.certify = '$certificate'";
                        }else{
                                $search_str.="or USEDCAR_PRODUCT_MASTER.certify = '$certificate'";
                        }
                }
                $whereClauseArr[] = $search_str;
    }else{$keyArr[] =-1;}
	if(!empty($city_ids)){
		$keyArr[] = $city_ids;
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.city_id in($city_ids)";
	}else{$keyArr[] =-1;}
	$tablenameArr[] = "USEDCAR_PRODUCT_MASTER";
	if($status != ''){
		$keyArr[] = $status;
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.status=$status";
	}else{$keyArr[] =-1;}

	$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.is_sold = '0'";
	$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.active_user = '1'";
	$listing_end_date = date('Y-m-d');
	$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.listing_end_date >= '$listing_end_date 00:00:00'";
	if(sizeof($whereClauseArr) > 0){
		$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
	}
	$key = implode("_",$keyArr);
	if($result = $this->cache->get($key)){
		#print_r($result); die();
		return $result;
	}
	$table_name = implode(",",$tablenameArr);
	$table_name = implode(",",$tablenameArr);
	$sql = "select count(USEDCAR_PRODUCT_MASTER.used_product_id) as cnt FROM $table_name $whereClauseStr ";
	$result = $this->select($sql);
	$this->cache->set($key,$result);
	return $result;

}

	function searchUsedCarProduct($category_ids="",$brand_ids="",$product_ids="",$feature_ids="",$city_ids="",$status="1",$startprice="",$endprice="",$startlimit="",$count="",$start_year="",$end_year="",$start_km_running="",$end_km_running,$owner="",$orderby="order by USEDCAR_PRODUCT_MASTER.create_date desc",$certify=""){
		$keyArr[] = $this->usedproductKey."_search_used_prd";
	$this->assignUsedCarPivotToSearch();
	if(is_array($category_ids)){
		$category_ids = implode(",",$category_ids);
	}
	if(is_array($brand_ids)){
		$brand_ids = implode(",",$brand_ids);
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
	if(is_array($feature_ids)){
		$feature_ids = implode(",",$feature_ids);
	}
	if(is_array($city_ids)){
		$city_ids = implode(",",$city_ids);
	}

	if(!empty($feature_ids)){
		$featureArr = explode(",",$feature_ids);
		foreach($featureArr as $feature_id){
			if(in_array($feature_id,$this->bodyStyleArr)){
				$keyArr[] = $feature_id;
				$this->newBodyStyleArr[] = $feature_id;
			}elseif(in_array($feature_id,$this->fuelTypeArr)){
				$keyArr[] = $feature_id;
				$this->newFuelTypeArr[] = $feature_id;
			}elseif(in_array($feature_id,$this->impFeatureArr)){
				$keyArr[] = $feature_id;
				$this->newImpFeatureArr[] =  $feature_id;;
			}
		}

		$this->newBodyStyleArr = array_unique($this->newBodyStyleArr,SORT_REGULAR);
		$this->newFuelTypeArr = array_unique($this->newFuelTypeArr,SORT_REGULAR);
		$this->newImpFeatureArr = array_unique($this->newImpFeatureArr,SORT_REGULAR);

		if(sizeof($this->newBodyStyleArr) > 0){

			$sqlArr[] = "select product_id from USEDCAR_PRODUCT_FEATURE where feature_id in (".implode(",",$this->newBodyStyleArr).")";
		}

		if(sizeof($this->newFuelTypeArr) > 0){
			$sqlArr[] = "select product_id from USEDCAR_PRODUCT_FEATURE where feature_id in (".implode(",",$this->newFuelTypeArr).")";
		}

		if(sizeof($this->newImpFeatureArr) > 0){
			$sqlArr[] = "select product_id from USEDCAR_PRODUCT_FEATURE where feature_id in (".implode(",",$this->newImpFeatureArr).")";
		}

		if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = " USEDCAR_PRODUCT_MASTER.used_product_id in (".$sqlStr.")";
				}
			}

	}
	$result_maxmin = $this->getMaxMinMileageInUsedCars();
	$max_km = $result_maxmin[0]['max_km_running'];
	$min_km = $result_maxmin[0]['min_km_running'];

	if(!empty($start_km_running) && !empty($end_km_running)){

			if($start_km_running == 100){
				$start_km_running = $min_km;
			}
			if($end_km_running == 100000){
				$end_km_running = $max_km;
			}

			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running >= '$start_km_running'";
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running <= '$end_km_running'";
			$keyArr[] = $start_km_running;
	                $keyArr[] = $end_km_running;

		}else if(!empty($start_km_running) && empty($end_km_running)){
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running = '$start_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = -1;
		}else if(empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.km_running = '$end_km_running'";
			$keyArr[] = -1;
			$keyArr[] = $end_km_running;

		}
	if(!empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year >= '$start_year'";
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year <= '$end_year'";
			 $keyArr[] = $start_year;
             $keyArr[] = $end_year;
	}else if(!empty($start_year) && empty($end_year)){
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year = '$start_year'";
		$keyArr[] = $start_year;
		$keyArr[] = -1;

	}else if(empty($start_year) && !empty($end_year)){
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.year = '$end_year'";
		$keyArr[] = -1;
		$keyArr[] = $end_year;

	}
	if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.price>=$startprice";
		}else{$keyArr[] =-1;}
		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.price<=$endprice";
		}else{$keyArr[] =-1;}

		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.used_brand_id in($brand_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.used_product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($city_ids)){
			$keyArr[] = $city_ids;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.city_id in($city_ids)";
		}else{$keyArr[] =-1;}
		if($owner!=''){
			$keyArr[] = $owner;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.is_dealer in($owner)";
		}else{$keyArr[] =-1;}
		if($certify!=''){
			$keyArr[] = $certify;
	                $certificates = explode(",",$certify);
                	$certificates_cnt = sizeof($certificates);
        	        $search_str = "";
	                for($i=0;$i<$certificates_cnt;$i++){
                        	$certificate = $certificates[$i];
                	        if($search_str == ""){
        	                        $search_str.="USEDCAR_PRODUCT_MASTER.certify = '$certificate'";
	                        }else{
                        	        $search_str.="or USEDCAR_PRODUCT_MASTER.certify = '$certificate'";
                	        }
        	        }
	                $whereClauseArr[] = $search_str;
	    }else{$keyArr[] =-1;}
		$tablenameArr[] = "USEDCAR_PRODUCT_MASTER";
		$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.is_sold = '0'";
		$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.active_user = '1'";
		$listing_end_date = date('Y-m-d');
		$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.listing_end_date >= '$listing_end_date 00:00:00'";
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "USEDCAR_PRODUCT_MASTER.status=$status";
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
		if(!empty($orderby)) {
			$keyArr[] = $orderby;
			$orderby = $orderby;
		}else{$keyArr[] =-1;}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}

		$table_name = implode(",",$tablenameArr);

		$table_name = implode(",",$tablenameArr);
		$sql = "select USEDCAR_PRODUCT_MASTER.* FROM $table_name $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);

		return $result;

}


function assignUsedCarPivotToSearch(){
	require_once(CLASSPATH.'pivot.class.php');
	$pivot = new PivotManagement;

	$pivot_result = $pivot->arrGetUsedCarPivotDetails("",$category_id,"","1","2");
	$pivotcnt = sizeof($pivot_result);
	for($i=0;$i<$pivotcnt;$i++){
		$this->bodyStyleArr[] = $pivot_result[$i]['feature_id'];
	}

	$pivot_result = $pivot->arrGetUsedCarPivotDetails("",$category_id,"","1","1");
	$pivotcnt = sizeof($pivot_result);
	for($i=0;$i<$pivotcnt;$i++){
		$this->fuelTypeArr[] = $pivot_result[$i]['feature_id'];
	}

	$pivot_result = $pivot->arrGetUsedCarPivotDetails("",$category_id,"","1","3");
	$pivotcnt = sizeof($pivot_result);
	for($i=0;$i<$pivotcnt;$i++){
		$this->impFeatureArr[] = $pivot_result[$i]['feature_id'];
	}

 }


   /**
	 * @note function is used to get Max Price for Used cars.
	 * @param an integer category_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function getMaxMinMileageInUsedCars(){
		$key = $this->usedproductKey."_getMaxMinMileageInUsedCars";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "SELECT MAX(km_running ) as max_km_running , MIN(km_running) as min_km_running  FROM USEDCAR_PRODUCT_MASTER WHERE status=1";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetUsedCarRelatedProductDetailsCnt($request_param){

		list($category_id,$used_product_id,$used_brand_id,$used_model_id,$used_variant_id,$start_year,$end_year,$start_km_running,$end_km_running,$start_price,$end_price,$start_mrp,$end_mrp,$sellerid,$brand_id,$model_id,$product_id,$status,$startlimit,$cnt,$orderby,$chkliststartdate,$chklistenddate,$city_id,$used_variant_name,$used_model_name,$used_brand_name,$skipvariant_id,$skipmodel_id,$skipbrand_id) = array($request_param['category_id'],$request_param['used_product_id'],$request_param['used_brand_id'],$request_param['used_model_id'],$request_param['used_variant_id'],$request_param['start_year'],$request_param['end_year'],$request_param['start_km_running'],$request_param['end_km_running'],$request_param['start_price'],$request_param['end_price'],$request_param['start_mrp'],$request_param['end_mrp'],$request_param['sellerid'],$request_param['brand_id'],$request_param['model_id'],$request_param['product_id'],$request_param['status'],$request_param['startlimit'],$request_param['cnt'],$request_param['orderby'],$request_param['chkliststartdate'],$request_param['chklistenddate'],$request_param['city_id'],$request_param['used_variant_name'],$request_param['used_model_name'],$request_param['used_brand_name'],$request_param['skipvariant_id'],$request_param['skipmodel_id'],$request_param['skipbrand_id']);

		$keyArr[] = $this->usedproductKey."_related_used_prd_cnt_data";
		$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.status != '-1'";
		$keyArr[] = "status_-1";
		$whereClauseArr[] ="is_sold = '0'";
		$keyArr[] = "is_sold_0";

		$listing_end_date = date('Y-m-d');
		$chklistenddate = !$chklistenddate ? 'true' : $chklistenddate;
		if($chklistenddate == 'true'){
			$whereClauseArr[] = "listing_end_date >= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}
		if($chkliststartdate == true){
			$whereClauseArr[] = "listing_start_date <= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}

		if(!empty($skipvariant_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_variant_id != $skipvariant_id";
						$keyArr[] = $skipvariant_id;
                }else{$keyArr[] =-1;}
                if(!empty($skipmodel_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_model_id != $skipmodel_id";
						$keyArr[] = $skipmodel_id;
                }else{$keyArr[] =-1;}
                if(!empty($skipbrand_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_brand_id != $skipbrand_id";
						$keyArr[] = $skipbrand_id;
                }else{$keyArr[] =-1;}

		if(!empty($category_id)){
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}

		if(!empty($used_product_id)){
			if(is_array($used_product_id)){ $used_product_id = implode(",",$used_product_id);}
			$whereClauseArr[] ="used_product_id in($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}

		if(!empty($used_brand_id)){
			if(is_array($used_brand_id)){ $used_brand_id = implode(",",$used_brand_id);}
			$whereClauseArr[] ="used_brand_id in($used_brand_id)";
			$keyArr[] = $used_brand_id;
		}else{$keyArr[] =-1;}

		if(!empty($used_model_id)){
			if(is_array($used_model_id)){ $used_model_id = implode(",",$used_model_id);}
			$whereClauseArr[] ="used_model_id in($used_model_id)";
			$keyArr[] = $used_model_id;
		}else{$keyArr[] =-1;}

		if(!empty($used_variant_id)){
			if(is_array($used_variant_id)){ $used_variant_id = implode(",",$used_variant_id);}
			$whereClauseArr[] ="used_variant_id in($used_variant_id)";
			$keyArr[] = $used_variant_id;
		}else{$keyArr[] =-1;}
		if(!empty($used_brand_name)){
			if(is_array($used_brand_name)){ $used_brand_name = implode(",",$used_brand_name);}
			$whereClauseArr[] ="brand_name = '$used_brand_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_brand_id =USEDCAR_BRAND_MASTER.used_brand_id";
			$tablenameArr[] = "USEDCAR_BRAND_MASTER";
			$keyArr[] = $used_brand_name;
		}else{$keyArr[] =-1;}

		if(!empty($used_model_name)){
			if(is_array($used_model_name)){ $used_model_name = implode(",",$used_model_name);}
			$whereClauseArr[] ="model_name = '$used_model_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_model_id = USEDCAR_MODEL_MASTER.used_model_id";
			$tablenameArr[] = " USEDCAR_MODEL_MASTER";
			$keyArr[] = $used_model_name;
		}else{$keyArr[] =-1;}

		if(!empty($used_variant_name)){
			if(is_array($used_variant_name)){ $used_variant_name = implode(",",$used_variant_name);}
			$whereClauseArr[] ="variant_name= '$used_variant_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_variant_id = USEDCAR_VARIANT_MASTER.used_variant_id";
			$tablenameArr[] = "USEDCAR_VARIANT_MASTER";
			$keyArr[] = $used_variant_name;
		}else{$keyArr[] =-1;}

		if(!empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year >= '$start_year'";
			$whereClauseArr[] = "year <= '$end_year'";
			$keyArr[] = $start_year;
			$keyArr[] = $end_year;
		}else if(!empty($start_year) && empty($end_year)){
			$whereClauseArr[] = "year = '$start_year'";
			$keyArr[] = $start_year;
			$keyArr[] = -1;
		}else if(empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year = '$end_year'";
			$keyArr[] = -1;
			$keyArr[] = $end_year;
		}

		if(!empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running >= '$start_km_running'";
			$whereClauseArr[] = "km_running <= '$end_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = $end_km_running;
		}else if(!empty($start_km_running) && empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$start_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = -1;
		}else if(empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$end_km_running'";
			$keyArr[] = -1;
			$keyArr[] = $end_km_running;
		}

		if(!empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price >= '$start_price'";
			$whereClauseArr[] = "price <= '$end_price'";
			$keyArr[] = $start_price;
			$keyArr[] = $end_price;
		}else if(!empty($start_price) && empty($end_price)){
			$whereClauseArr[] = "price = '$start_price'";
			$keyArr[] = $start_price;
			$keyArr[] = -1;
		}else if(empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price = '$end_price'";
			$keyArr[] = -1;
			$keyArr[] = $end_price;
		}

		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = $end_mrp;
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = -1;
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
			$keyArr[] = -1;
			$keyArr[] = $end_mrp;
		}

		if(!empty($sellerid)){
			if(is_array($sellerid)){ $sellerid = implode(",",$sellerid);}
			$whereClauseArr[] ="sellerid in($sellerid)";
			$keyArr[] = $sellerid;
		}else{$keyArr[] =-1;}


		if(!empty($brand_id)){
			if(is_array($brand_id)){ $brand_id = implode(",",$brand_id);}
			$whereClauseArr[] ="brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}


		if(!empty($model_id)){
			if(is_array($model_id)){ $model_id = implode(",",$model_id);}
			$whereClauseArr[] ="model_id in($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}

    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] ="product_id in($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}

		if(!empty($city_id)){
			$whereClauseArr[] ="city_id = $city_id";
			$keyArr[] = $city_id;
		}else{$keyArr[] =-1;}

		if($status != ''){
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}

		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(sizeof($whereClauseArr) > 0) { $whereClauseStr = ' where '.implode(" and ",$whereClauseArr); }
		$tablenameArr[] = "USEDCAR_PRODUCT_MASTER";
		$table_name = implode(",",$tablenameArr);
		$sql = "select count(product_id) as cnt from $table_name  $whereClauseStr";
		//echo $sql."<br>";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function arrGetUsedCarRelatedProductDetails($request_param){

		list($category_id,$used_product_id,$used_brand_id,$used_model_id,$used_variant_id,$start_year,$end_year,$start_km_running,$end_km_running,$start_price,$end_price,$start_mrp,$end_mrp,$sellerid,$brand_id,$model_id,$product_id,$status,$startlimit,$cnt,$orderby,$chkliststartdate,$chklistenddate,$city_id,$used_variant_name,$used_model_name,$used_brand_name,$skipvariant_id,$skipmodel_id,$skipbrand_id) = array($request_param['category_id'],$request_param['used_product_id'],$request_param['used_brand_id'],$request_param['used_model_id'],$request_param['used_variant_id'],$request_param['start_year'],$request_param['end_year'],$request_param['start_km_running'],$request_param['end_km_running'],$request_param['start_price'],$request_param['end_price'],$request_param['start_mrp'],$request_param['end_mrp'],$request_param['sellerid'],$request_param['brand_id'],$request_param['model_id'],$request_param['product_id'],$request_param['status'],$request_param['startlimit'],$request_param['cnt'],$request_param['orderby'],$request_param['chkliststartdate'],$request_param['chklistenddate'],$request_param['city_id'],$request_param['used_variant_name'],$request_param['used_model_name'],$request_param['used_brand_name'],$request_param['skipvariant_id'],$request_param['skipmodel_id'],$request_param['skipbrand_id']);

		$keyArr[] = $this->usedproductKey."_related_used_prd";
		$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.status != '-1'";
		$keyArr[] = "status_-1";
		$whereClauseArr[] ="is_sold = '0'";
		$keyArr[] = "is_sold_0";

		$listing_end_date = date('Y-m-d');
		$chklistenddate = !$chklistenddate ? 'true' : $chklistenddate;
		if($chklistenddate == 'true'){
			$whereClauseArr[] = "listing_end_date >= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}

		if($chkliststartdate == true){
			$whereClauseArr[] = "listing_start_date <= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}


		if(!empty($skipvariant_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_variant_id != $skipvariant_id";
						$keyArr[] = $skipvariant_id;
                }else{$keyArr[] =-1;}

                if(!empty($skipmodel_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_model_id != $skipmodel_id";
						$keyArr[] = $skipmodel_id;
                }else{$keyArr[] =-1;}

                if(!empty($skipbrand_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_brand_id != $skipbrand_id";
						$keyArr[] = $skipbrand_id;
                }else{$keyArr[] =-1;}

		if(!empty($category_id)){
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_product_id)){
			if(is_array($used_product_id)){ $used_product_id = implode(",",$used_product_id);}
			$whereClauseArr[] ="used_product_id in($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_brand_id)){
			if(is_array($used_brand_id)){ $used_brand_id = implode(",",$used_brand_id);}
			$whereClauseArr[] ="used_brand_id in($used_brand_id)";
			$keyArr[] = $used_brand_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_model_id)){
			if(is_array($used_model_id)){ $used_model_id = implode(",",$used_model_id);}
			$whereClauseArr[] ="used_model_id in($used_model_id)";
			$keyArr[] = $used_model_id;
		}

		if(!empty($used_variant_id)){
			if(is_array($used_variant_id)){ $used_variant_id = implode(",",$used_variant_id);}
			$whereClauseArr[] ="used_variant_id in($used_variant_id)";
			$keyArr[] = $used_variant_id;

		}else{$keyArr[] =-1;}

		if(!empty($used_brand_name)){
			if(is_array($used_brand_name)){ $used_brand_name = implode(",",$used_brand_name);}
			$whereClauseArr[] ="brand_name = '$used_brand_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_brand_id =USEDCAR_BRAND_MASTER.used_brand_id";
			$tablenameArr[] = "USEDCAR_BRAND_MASTER";
			$keyArr[] = $used_brand_name;
		}else{$keyArr[] =-1;}


		if(!empty($used_model_name)){
			if(is_array($used_model_name)){ $used_model_name = implode(",",$used_model_name);}
			$whereClauseArr[] ="model_name = '$used_model_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_model_id = USEDCAR_MODEL_MASTER.used_model_id";
			$tablenameArr[] = " USEDCAR_MODEL_MASTER";
			$keyArr[] = $used_model_name;
		}else{$keyArr[] =-1;}


		if(!empty($used_variant_name)){
			if(is_array($used_variant_name)){ $used_variant_name = implode(",",$used_variant_name);}
			$whereClauseArr[] ="variant_name= '$used_variant_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_variant_id = USEDCAR_VARIANT_MASTER.used_variant_id";
			$tablenameArr[] = "USEDCAR_VARIANT_MASTER";
			$keyArr[] = $used_variant_name;
		}else{$keyArr[] =-1;}


		if(!empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year >= '$start_year'";
			$whereClauseArr[] = "year <= '$end_year'";
			$keyArr[] = $start_year;
			$keyArr[] = $end_year;
		}else if(!empty($start_year) && empty($end_year)){
			$whereClauseArr[] = "year = '$start_year'";
			$keyArr[] = $start_year;
			$keyArr[] = -1;
		}else if(empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year = '$end_year'";
			$keyArr[] = -1;
			$keyArr[] = $end_year;
		}

		if(!empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running >= '$start_km_running'";
			$whereClauseArr[] = "km_running <= '$end_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = $end_km_running;
		}else if(!empty($start_km_running) && empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$start_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = -1;
		}else if(empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$end_km_running'";
			$keyArr[] = -1;
			$keyArr[] = $end_km_running;

		}

		if(!empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price >= '$start_price'";
			$whereClauseArr[] = "price <= '$end_price'";
			$keyArr[] = $start_price;
			$keyArr[] = $end_price;
		}else if(!empty($start_price) && empty($end_price)){
			$whereClauseArr[] = "price = '$start_price'";
			$keyArr[] = $start_price;
			$keyArr[] = -1;
		}else if(empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price = '$end_price'";
			$keyArr[] = -1;
			$keyArr[] = $end_price;
		}

		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = $end_mrp;
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = -1;
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
			$keyArr[] = -1;
			$keyArr[] = $end_mrp;
		}

		/*
		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
		}
		*/

		if(!empty($sellerid)){
			if(is_array($sellerid)){ $sellerid = implode(",",$sellerid);}
			$whereClauseArr[] ="sellerid in($sellerid)";
			$keyArr[] = $sellerid;
		}else{$keyArr[] =-1;}


		if(!empty($brand_id)){
			if(is_array($brand_id)){ $brand_id = implode(",",$brand_id);}
			$whereClauseArr[] ="brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}


		if(!empty($model_id)){
			if(is_array($model_id)){ $model_id = implode(",",$model_id);}
			$whereClauseArr[] ="model_id in($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}

    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] ="product_id in($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}

		if(!empty($city_id)){
			$whereClauseArr[] ="city_id = $city_id";
			$keyArr[] = $city_id;
		}else{$keyArr[] =-1;}

		if($status != ''){
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.status = $status";
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

		if(empty($orderby)){ $orderby = "order by USEDCAR_PRODUCT_MASTER.price asc"; }
		$keyArr[] = $orderby;

		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		if(sizeof($whereClauseArr) > 0) { $whereClauseStr = ' where '.implode(" and ",$whereClauseArr); }
		if(sizeof($limitArr) > 0) { $limitStr = ' limit '.implode(",",$limitArr); }
		$tablenameArr[] = "USEDCAR_PRODUCT_MASTER";
		$table_name = implode(",",$tablenameArr);
		$sql = "select * from $table_name  $whereClauseStr $orderby $limitStr";
		//echo $sql."<br>";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	  function arrGetUsedCarRelatedProductDetailsGroupByCity($request_param){

		list($category_id,$used_product_id,$used_brand_id,$used_model_id,$used_variant_id,$start_year,$end_year,$start_km_running,$end_km_running,$start_price,$end_price,$start_mrp,$end_mrp,$sellerid,$brand_id,$model_id,$product_id,$status,$startlimit,$cnt,$orderby,$chkliststartdate,$chklistenddate,$city_id,$used_variant_name,$used_model_name,$used_brand_name,$skipvariant_id,$skipmodel_id,$skipbrand_id) = array($request_param['category_id'],$request_param['used_product_id'],$request_param['used_brand_id'],$request_param['used_model_id'],$request_param['used_variant_id'],$request_param['start_year'],$request_param['end_year'],$request_param['start_km_running'],$request_param['end_km_running'],$request_param['start_price'],$request_param['end_price'],$request_param['start_mrp'],$request_param['end_mrp'],$request_param['sellerid'],$request_param['brand_id'],$request_param['model_id'],$request_param['product_id'],$request_param['status'],$request_param['startlimit'],$request_param['cnt'],$request_param['orderby'],$request_param['chkliststartdate'],$request_param['chklistenddate'],$request_param['city_id'],$request_param['used_variant_name'],$request_param['used_model_name'],$request_param['used_brand_name'],$request_param['skipvariant_id'],$request_param['skipmodel_id'],$request_param['skipbrand_id']);


		$keyArr[] = $this->usedproductKey."_related_used_prdgrp_by_city";
		$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.status != '-1'";
		$keyArr[] = "-1";
		$whereClauseArr[] ="is_sold = '0'";
		$keyArr[] = "0";

		$listing_end_date = date('Y-m-d');
		$chklistenddate = !$chklistenddate ? 'true' : $chklistenddate;
		if($chklistenddate == 'true'){
			$whereClauseArr[] = "listing_end_date >= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}

		if($chkliststartdate == true){
			$whereClauseArr[] = "listing_start_date <= '$listing_end_date 00:00:00'";
			$keyArr[] = $listing_end_date;
		}else{$keyArr[] =-1;}


		if(!empty($category_id)){
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}

		if(!empty($skipvariant_id)){
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_variant_id != $skipvariant_id";
			$keyArr[] = $skipvariant_id;
		}else{$keyArr[] =-1;}

		if(!empty($skipmodel_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_model_id != $skipmodel_id";
						$keyArr[] = $skipmodel_id;
                }else{$keyArr[] =-1;}

		if(!empty($skipbrand_id)){
                        $whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_brand_id != $skipbrand_id";
						$keyArr[] = $skipbrand_id;
                }else{$keyArr[] =-1;}

		if(!empty($used_product_id)){
			if(is_array($used_product_id)){ $used_product_id = implode(",",$used_product_id);}
			$whereClauseArr[] ="used_product_id in($used_product_id)";
			$keyArr[] = $used_product_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_brand_id)){
			if(is_array($used_brand_id)){ $used_brand_id = implode(",",$used_brand_id);}
			$whereClauseArr[] ="used_brand_id in($used_brand_id)";
			$keyArr[] = $used_brand_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_model_id)){
			if(is_array($used_model_id)){ $used_model_id = implode(",",$used_model_id);}
			$whereClauseArr[] ="used_model_id in($used_model_id)";
			$keyArr[] = $used_model_id;
		}else{$keyArr[] =-1;}


		if(!empty($used_variant_id)){
			if(is_array($used_variant_id)){ $used_variant_id = implode(",",$used_variant_id);}
			$whereClauseArr[] ="used_variant_id in($used_variant_id)";
			$keyArr[] = $used_variant_id;
		}else{$keyArr[] =-1;}

		if(!empty($used_brand_name)){
			if(is_array($used_brand_name)){ $used_brand_name = implode(",",$used_brand_name);}
			$whereClauseArr[] ="brand_name = '$used_brand_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_brand_id =USEDCAR_BRAND_MASTER.used_brand_id";
			$tablenameArr[] = "USEDCAR_BRAND_MASTER";
			$keyArr[] = $used_brand_name;
		}else{$keyArr[] =-1;}


		if(!empty($used_model_name)){
			if(is_array($used_model_name)){ $used_model_name = implode(",",$used_model_name);}
			$whereClauseArr[] ="model_name = '$used_model_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_model_id = USEDCAR_MODEL_MASTER.used_model_id";
			$tablenameArr[] = " USEDCAR_MODEL_MASTER";
			$keyArr[] = $used_model_name;
		}else{$keyArr[] =-1;}


		if(!empty($used_variant_name)){
			if(is_array($used_variant_name)){ $used_variant_name = implode(",",$used_variant_name);}
			$whereClauseArr[] ="variant_name= '$used_variant_name'";
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.used_variant_id = USEDCAR_VARIANT_MASTER.used_variant_id";
			$tablenameArr[] = "USEDCAR_VARIANT_MASTER";
			$keyArr[] = $used_variant_name;
		}else{$keyArr[] =-1;}


		if(!empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year >= '$start_year'";
			$whereClauseArr[] = "year <= '$end_year'";
			$keyArr[] = $start_year;
			$keyArr[] = $end_year;
		}else if(!empty($start_year) && empty($end_year)){
			$whereClauseArr[] = "year = '$start_year'";
			$keyArr[] = $start_year;
			$keyArr[] = -1;
		}else if(empty($start_year) && !empty($end_year)){
			$whereClauseArr[] = "year = '$end_year'";
			$keyArr[] = -1;
			$keyArr[] = $end_year;
		}

		if(!empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running >= '$start_km_running'";
			$whereClauseArr[] = "km_running <= '$end_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = $end_km_running;
		}else if(!empty($start_km_running) && empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$start_km_running'";
			$keyArr[] = $start_km_running;
			$keyArr[] = -1;
		}else if(empty($start_km_running) && !empty($end_km_running)){
			$whereClauseArr[] = "km_running = '$end_km_running'";
			$keyArr[] = -1;
			$keyArr[] = $end_km_running;
		}

		if(!empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price >= '$start_price'";
			$whereClauseArr[] = "price <= '$end_price'";
			$keyArr[] = $start_price;
			$keyArr[] = $end_price;
		}else if(!empty($start_price) && empty($end_price)){
			$whereClauseArr[] = "price = '$start_price'";
			$keyArr[] = $start_price;
			$keyArr[] = -1;
		}else if(empty($start_price) && !empty($end_price)){
			$whereClauseArr[] = "price = '$end_price'";
			$keyArr[] = -1;
			$keyArr[] = $end_price;
		}

		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = $end_mrp;
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
			$keyArr[] = $start_mrp;
			$keyArr[] = -1;
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
			$keyArr[] = -1;
			$keyArr[] = $end_mrp;
		}

		/*
		if(!empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp >= '$start_mrp'";
			$whereClauseArr[] = "mrp <= '$end_mrp'";
		}else if(!empty($start_mrp) && empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$start_mrp'";
		}else if(empty($start_mrp) && !empty($end_mrp)){
			$whereClauseArr[] = "mrp = '$end_mrp'";
		}
		*/

		if(!empty($sellerid)){
			if(is_array($sellerid)){ $sellerid = implode(",",$sellerid);}
			$whereClauseArr[] ="sellerid in($sellerid)";
			$keyArr[] = $sellerid;
		}else{$keyArr[] =-1;}


		if(!empty($brand_id)){
			if(is_array($brand_id)){ $brand_id = implode(",",$brand_id);}
			$whereClauseArr[] ="brand_id in($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}


		if(!empty($model_id)){
			if(is_array($model_id)){ $model_id = implode(",",$model_id);}
			$whereClauseArr[] ="model_id in($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] =-1;}

    if(is_array($product_id)){
      foreach($product_id as $variant_id){
        $i_variant_ids = intval($variant_id);
        if($i_variant_ids!=0){
          $variant_ids[] = $i_variant_ids;
        }
      }
      $product_id = implode(",",$variant_ids);
    }else{
      if(strpos($product_id,',')==false){
        if(intval($product_id)!=0){
          $product_id = intval($product_id);
        }
      }else{
        $arr_variant_ids = explode(",",$product_id);
        foreach($arr_variant_ids as $variant_id){
          $i_variant_ids = intval($variant_id);
          if($i_variant_ids!=0){
            $variant_ids[] = $i_variant_ids;
          }
        }
        $product_id = implode(",",$variant_ids);
      }
    }
		if(!empty($product_id)){
			$whereClauseArr[] ="product_id in($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}

		if(!empty($city_id)){
			$whereClauseArr[] ="city_id = $city_id";
			$keyArr[] = $city_id;
		}else{$keyArr[] =-1;}

		if($status != ''){
			$whereClauseArr[] ="USEDCAR_PRODUCT_MASTER.status = $status";
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

		if(empty($orderby)){ $orderby = "order by USEDCAR_PRODUCT_MASTER.price asc"; }
		$keyArr[] = $orderby;

		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		if(sizeof($whereClauseArr) > 0) { $whereClauseStr = ' where '.implode(" and ",$whereClauseArr); }
		if(sizeof($limitArr) > 0) { $limitStr = ' limit '.implode(",",$limitArr); }
		$tablenameArr[] = "USEDCAR_PRODUCT_MASTER";
		$table_name = implode(",",$tablenameArr);
		$sql = "select count(used_product_id) as cnt,city_id from $table_name  $whereClauseStr group by USEDCAR_PRODUCT_MASTER.city_id $orderby $limitStr";
		//echo $sql."<br>";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function GetCarRecommendations($product_ids='',$product_info_ids='',$category_id='',$iSelBrandId='',$price_variant_value='',$productId='',$model_name=''){
		if((sizeof($product_ids)) > 0){
			$recommendation_result_data = $this->arrGetProductDetails($product_ids,$category_id,"",'1',"","","1","","","1","order by PRICE_VARIANT_VALUES.variant_value asc");
			$isbrandmodelthr = array();
			//print_r($recommendation_result_data);die();
			foreach($recommendation_result_data as $rkey=>$rValue){
				//echo trim($rValue['product_name'])."!=".trim($model_name)."<br>";
				if(trim($rValue['product_name']) != trim($model_name)){
					//echo "INSIDE<br>";
					$model_status = $this->arrGetProductNameInfo("",$category_id,"",$rValue['product_name'],"1");
					if($rValue['variant_value'] < $price_variant_value && $model_status[0]['status']==1){
						$recommendation_filter_result_data_lower[$rValue['product_name']][] =  $rValue;
					}elseif($rValue['variant_value'] > $price_variant_value && $model_status[0]['status']==1){
						$recommendation_filter_result_data_higer[$rValue['product_name']][] =  $rValue;
					}
				}
			}
			//die();
			foreach($recommendation_filter_result_data_lower as $lowkey=>$lowValue){
				//if(count($lowValue)>1){ $recommendation_lower_filter[] = $lowValue[1];}
				//else{$recommendation_lower_filter[] = $lowValue[0];}
				$recommendation_lower_filter[] = $lowValue[0];
			}
			foreach($recommendation_filter_result_data_higer as $hghkey=>$hghValue){
				//if(count($hghValue)>1){$recommendation_higher_filter[] = $hghValue[1];}
				//else{$recommendation_higher_filter[] = $hghValue[0];}
				$recommendation_higher_filter[] = $hghValue[0];
			}
			//print_r($recommendation_higher_filter); die();
			$arrlowfValue =array(); $arrhghfValue = array();
			foreach($recommendation_lower_filter as $lowfkey=>$lowfValue){
				//if(!in_array($lowfValue['brand_id'],$arrlowfValue)){
				if($lowfValue['brand_id']!= $iSelBrandId){
					$lower_price_data[$lowfValue['variant_value']] = $lowfValue;
				}else{
					$lower_price_data1[$lowfValue['variant_value']] = $lowfValue;
				}
				$arrlowfValue[] = $lowfValue['brand_id'];
			}
			foreach($recommendation_higher_filter as $hghfkey=>$hghfValue){
				//if(!in_array($hghfValue['brand_id'],$arrhghfValue)){
				if($hghfValue['brand_id']!= $iSelBrandId){
					$higher_price_data[$hghfValue['variant_value']] = $hghfValue;
				}else{$higher_price_data1[$hghfValue['variant_value']] = $hghfValue;}
					$arrhghfValue[] = $hghfValue['brand_id'];
			}
			if(count($lower_price_data)> 2){
				$lower_price_data_final = $lower_price_data;
			}else{
				if(is_array($lower_price_data) && is_array($lower_price_data1)){
					$lower_price_data_final = array_merge($lower_price_data,$lower_price_data1);
				}else if(!is_array($lower_price_data) && is_array($lower_price_data1)){
					$lower_price_data_final = $lower_price_data1;
				}else if(is_array($lower_price_data) && !is_array($lower_price_data1)){
					$lower_price_data_final = $lower_price_data;
				}
			}
			if(count($higher_price_data)> 2){
				$higher_price_data_final = $higher_price_data;
			}else{
				if(is_array($higher_price_data) && is_array($higher_price_data1)){
					$higher_price_data_final = array_merge($higher_price_data,$higher_price_data1);
				}else if(!is_array($higher_price_data) && is_array($higher_price_data1)){
					$higher_price_data_final = $higher_price_data1;
				}else if(is_array($higher_price_data) && !is_array($higher_price_data1)){
					$higher_price_data_final = $higher_price_data;
				}
			}

			foreach($lower_price_data_final as $lowerfinalkey=>$lowerfinalValue){
				$lower_price_data_finaldata[$lowerfinalValue['variant_value']]= $lowerfinalValue ;
			}
			foreach($higher_price_data_final as $hghfinalkey=>$hghfinalValue){
				$higher_price_data_finaldata[$hghfinalValue['variant_value']]= $hghfinalValue ;
			}

			krsort($lower_price_data_finaldata);
			ksort($higher_price_data_finaldata);
		    //print_r($higher_price_data_finaldata); die();
			$recommendation_result1 = array_slice($lower_price_data_finaldata,0,2);
			if(count($recommendation_result1) == 0){
				$getdata=4;
			}else if(count($recommendation_result1) < 2){
				$getdata=3;
			}else{
			    $getdata=2;
			}
			$recommendation_result2 = array_slice($higher_price_data_finaldata,0,$getdata);
			krsort($recommendation_result1);
			foreach($recommendation_result1 as $kt1=>$ktValue1){
				$recommendation_result_1[$ktValue1['product_id']] = $ktValue1;
			}
			foreach($recommendation_result2 as $kt=>$ktValue){
				if(!isset($recommendation_result_1[$ktValue['product_id']])){
					$recommendation_result_2[] = $ktValue;
				}
			}
			if(is_array($recommendation_result1) && is_array($recommendation_result_2)){
				$recommendation_result = array_merge($recommendation_result1,$recommendation_result_2);
			}else if(is_array($recommendation_result1) && !is_array($recommendation_result_2)){
				$recommendation_result = $recommendation_result1;
			}else if(!is_array($recommendation_result1) && is_array($recommendation_result_2)){
				$recommendation_result = $recommendation_result_2;
			}
			//print_r($higher_price_data); die();
			return $recommendation_result;
		}
	}


	function searchProductCountByBodyStyle($category_ids="",$brand_ids="",$product_ids="",$feature_ids="",$status="1",$startprice="",$endprice="",$variant_id="1",$startlimit="",$cnt="",$cityId="",$discontinue_flag='',$check_discontinue_date="",$color_id='0'){
		//echo "bdy_feature_id"; print_r($feature_ids);
		$keyArr[] = $this->productKey."_searchProductCountByBodyStyle";
		$this->assignPivotToSearch();

		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
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
	if(is_array($feature_ids)){
		$feature_ids = implode(",",$feature_ids);
	}
	$this->newBodyStyleArr='';
	if(!empty($feature_ids)){
		$featureArr = explode(",",$feature_ids);

			foreach($featureArr as $feature_id){
				if(in_array($feature_id,$this->bodyStyleArr)){
					$keyArr[] = $feature_id;
					$this->newBodyStyleArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->fuelTypeArr)){
					$keyArr[] = $feature_id;
					$this->newFuelTypeArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->impFeatureArr)){
					$keyArr[] = $feature_id;
					$this->newImpFeatureArr[] = "select product_id from PRODUCT_FEATURE where feature_id in ($feature_id)";
				}elseif(in_array($feature_id,$this->tranmissionArr)){
					$keyArr[] = $feature_id;
					$this->newTranmissionArr[] = $feature_id;
				}elseif(in_array($feature_id,$this->seatingCapcityArr)){
					$keyArr[] = $feature_id;
					$this->newSeatingCapcityArr[] = $feature_id;
				}
			}


			if(sizeof($this->newBodyStyleArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newBodyStyleArr).")";
			}
			if(sizeof($this->newFuelTypeArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newFuelTypeArr).")";
			}
			if(sizeof($this->newImpFeatureArr) > 0){
				$sqlStr = "";
				foreach($this->newImpFeatureArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
			if(sizeof($this->newTranmissionArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newTranmissionArr).")";
			}
			if(sizeof($this->newSeatingCapcityArr) > 0){
				$sqlArr[] = "select product_id from PRODUCT_FEATURE where feature_id in (".implode(",",$this->newSeatingCapcityArr).")";
			}

			if(sizeof($sqlArr) > 0){
				$sqlStr = "";
				foreach($sqlArr as $k=>$featureSql){
					if(strlen($sqlStr) > 0){
						$sqlStr .= ' and product_id in('.$featureSql.')';
					}else{
						$sqlStr .= $featureSql;
					}
				}
				if(strlen($sqlStr) > 0){
					$whereClauseArr[] = "PRODUCT_MASTER.product_id in (".$sqlStr.")";
				}
			}
		}
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] =-1;}

		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] =-1;}

		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = 1";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{$keyArr[] =-1;}

		if(!(empty($cityId)))
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "c_$startprice"."_".$endprice."_".$variant_id.'_'.$color_id.'_'.$cityId;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = ".$cityId;
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = 'c_-1_-1_-1_-1_-1';
			}
		}
		else
		{
			if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
				$keyArr[] = "dc_$startprice".'_'.$endprice.'_'.$variant_id.'_'.$color_id;
				$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
				$tablenameArr[] = "PRODUCT_NAME_INFO";
				$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
				$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{
				$keyArr[] = 'dc_-1_-1_-1_-1';
			}
		}

		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_ids)){
			$keyArr[] = $brand_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in($brand_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		$tablenameArr[] = "PRODUCT_MASTER";
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] =-1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] =-1;}
	    if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date = '0000-00-00 00:00:00')";
		}
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
		///if($result = $this->cache->get($key)){return $result;}

		$table_name = implode(",",$tablenameArr);

		$sql = "select count(distinct(PRODUCT_MASTER.product_name)) as cnt FROM  $table_name $whereClauseStr $limitStr";
		#echo $sql."<br>";
        $result = $this->select($sql);
		$this->cache->set($key,$result);
		unset($this->newBodyStyleArr);
		return $result;
	 }

function intInsertRecentLaunchedProduct($insert_param){
	$insert_param['create_date'] = date('Y-m-d H:i:s');
	$insert_param['update_date'] = date('Y-m-d H:i:s');
	$sql = $this->getInsertSql("RECENT_LAUNCHED_PRODUCTS",array_keys($insert_param),array_values($insert_param));
	$product_id = $this->insert($sql);
	if($id == 'Duplicate entry'){ return 'exists';}
	$this->cache->searchDeleteKeys($this->productKey);
	return $id;
}
function boolUpdateRecentLaunchedProduct($id,$update_param){
	$update_param['update_date'] = date('Y-m-d H:i:s');
	$sql = $this->getUpdateSql("RECENT_LAUNCHED_PRODUCTS",array_keys($update_param),array_values($update_param),"id",$id);
	$isUpdate = $this->update($sql);
        $this->cache->searchDeleteKeys($this->productKey);
        return $isUpdate;
}
function boolDeleteRecentLaunchedProduct($id){
	$sql = "delete from RECENT_LAUNCHED_PRODUCTS where id = $id";
	$isDelete = $this->sql_delete_data($sql);
	$this->cache->searchDeleteKeys($this->productKey);
	return $isDelete;
}
function arrGetRecentLaunchedProductDetailsCnt($ids="",$brand_ids="",$product_name_ids="",$product_ids="",$category_ids="",$position="",$status="1"){
                $keyArr[] = $this->productKey."_recent_launched_products_cnt";
                if(is_array($ids)){
                        $ids = implode(",",$ids);
                }
				if(is_array($brand_ids)){
                        $brand_ids = implode(",",$brand_ids);
                }
				if(is_array($product_name_ids)){
                        $product_name_ids = implode(",",$product_name_ids);
                }
				if(is_array($product_ids)){
                        $product_ids = implode(",",$product_ids);
                }
                if(is_array($category_ids)){
                        $category_ids = implode(",",$category_ids);
                }
               	if($ids != ""){
                        $keyArr[] = $ids;
                        $whereClauseArr[] = " id in($ids)";
                }else{$keyArr[] =-1;}
				if($brand_ids != ""){
                        $keyArr[] = $brand_ids;
                        $whereClauseArr[] = "brand_id in($brand_ids)";
                }else{$keyArr[] =-1;}
				if($product_name_ids != ""){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}
				if($product_ids != ""){
                        $keyArr[] = $product_ids;
                        $whereClauseArr[] = "product_id in($product_ids)";
                }else{$keyArr[] =-1;}
                if($category_ids != ""){
                        $keyArr[] = $category_ids;
                        $whereClauseArr[] = "category_id in($category_ids)";
                }else{$keyArr[] =-1;}
                if($position != ""){
                        $keyArr[] = $position;
                        $whereClauseArr[] = "position = $position";
                }else{$keyArr[] =-1;}
                if($status != ""){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "status = $status";
                }else{$keyArr[] =-1;}
				if($orderby == ""){
					$keyArr[] = "order_create_date_desc";
					$orderby = "order by create_date desc";
                }else{$keyArr[] =-1;}
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                $key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $sSql="select count(id) as cnt from RECENT_LAUNCHED_PRODUCTS $whereClauseStr $orderby $limitStr";
				$result=$this->select($sSql);
                $this->cache->set($key,$result);
                return $result;
        }

function arrGetRecentLaunchedProductDetails($ids="",$brand_ids="",$product_name_ids="",$product_ids="",$category_ids="",$position="",$status="1",$startlimit="",$cnt="",$orderby=""){
                $keyArr[] = $this->productKey."_recent_launched_products";
                if(is_array($ids)){
                        $ids = implode(",",$ids);
                }
				if(is_array($brand_ids)){
                        $brand_ids = implode(",",$brand_ids);
                }
				if(is_array($product_name_ids)){
                        $product_name_ids = implode(",",$product_name_ids);
                }
				if(is_array($product_ids)){
                        $product_ids = implode(",",$product_ids);
                }
                if(is_array($category_ids)){
                        $category_ids = implode(",",$category_ids);
                }
               	if($ids != ""){
                        $keyArr[] = $ids;
                        $whereClauseArr[] = " id in($ids)";
                }else{$keyArr[] =-1;}
				if($brand_ids != ""){
                        $keyArr[] = $brand_ids;
                        $whereClauseArr[] = "brand_id in($brand_ids)";
                }else{$keyArr[] =-1;}
				if($product_name_ids != ""){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}
				if($product_ids != ""){
                        $keyArr[] = $product_ids;
                        $whereClauseArr[] = "product_id in($product_ids)";
                }else{$keyArr[] =-1;}
                if($category_ids != ""){
                        $keyArr[] = $category_ids;
                        $whereClauseArr[] = "category_id in($category_ids)";
                }else{$keyArr[] =-1;}
                if($position != ""){
                        $keyArr[] = $position;
                        $whereClauseArr[] = "position = $position";
                }else{$keyArr[] =-1;}
                if($status != ""){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "status = $status";
                }else{$keyArr[] =-1;}
				if($orderby == ""){
					$keyArr[] = "order_create_date_desc";
					$orderby = "order by create_date desc";
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
                        $limitStr = " limit ".implode(" , ",$limitArr);
                }
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                $key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $sSql="select * from RECENT_LAUNCHED_PRODUCTS $whereClauseStr $orderby $limitStr";
				$result=$this->select($sSql);
                $this->cache->set($key,$result);
                return $result;
        }

		/**
		* @note function is used to insert the sponsered brand model information into the database.
	        * @param an associative array $insert_param.
        	* @pre $insert_param must be valid associative array.
	        * @post an integer $id.
        	* retun integer.
	*/
	function intInsertSponseredBrandModel($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
      		$insert_param['update_date'] = date('Y-m-d H:i:s');
        	$sql = $this->getInsertSql("SPONSERED_BRAND_MODEL",array_keys($insert_param),array_values($insert_param));
	        $id = $this->insert($sql);
        	if($id == 'Duplicate entry'){ return 'exists';}
	        $this->cache->searchDeleteKeys($this->sponsered_brnd_model_Key);
        	return $id;
	}
	/**
         * @note function is used to update the sponsered brand model into the database.
         * @param an associative array $update_param.
         * @param an integer $id.
         * @pre $update_param must be valid associative array and $id must be non-empty/zero valid integer.
         * @post boolean true/false.
         * retun boolean.
         */
         function boolUpdateSponseredBrandModel($id,$update_param)
         {
                $update_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $this->getUpdateSql("SPONSERED_BRAND_MODEL",array_keys($update_param),array_values($update_param),"id",$id);
                $isUpdate = $this->update($sql);
                $this->cache->searchDeleteKeys($this->sponsered_brnd_model_Key);
                return $isUpdate;
         }
	/**
         * @note function is used to delete the sponsered brand model.
         * @param integer $id.
         * @pre $id must be non-empty/zero valid integer.
         * @post boolean true/false.
         * return boolean.
         */
         function boolDeleteSponseredBrandModel($id)
         {
                $sql = "delete from SPONSERED_BRAND_MODEL where id = $id";
                $isDelete = $this->sql_delete_data($sql);
                $this->cache->searchDeleteKeys($this->sponsered_brnd_model_Key);
                return $isDelete;
         }
	 /**
        * @note function is used to get sponsered brand model details count.
        * @pre not required.
        * @param an integer/comma seperated ids array $ids.
        * @param an integer/comma seperated category ids array $category_id.
        * @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param an integer/comma seperated product name ids/ product name ids array $product_name_id.
	* @param is a varchar $bgcolor
	* @param an integer position $position
        * @param is a boolean value $status.
        * @param is an integer value $startlimit.
        * @param is an integer value $cnt.
        * @param is a string $orderby.
        *
        * @post an associative array.
        * retun an array.
        */
        function arrGetSponseredBrandModelDetailsCount($ids="",$category_id="",$brand_id="",$product_name_id="",$bgcolor="",$position="",$status='1',$startlimit="",$count="",$orderby=""){
                $keyArr[] = $this->sponsered_brand_model_Key."_count";
                $tablenameArr[] = "SPONSERED_BRAND_MODEL";
		if(is_array($ids)){
                        $ids = implode(",",$ids);
                }
		if(!empty($ids)){
                        $keyArr[] = $ids;
                        $whereClauseArr[] = "id in($ids)";
                }else{$keyArr[] =-1;}
		if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
		if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "category_id in ($category_id)";
                }else{$keyArr[] =-1;}
		if(is_array($brand_id)){
                        $brand_id = implode(",",$brand_id);
                }
		if(!empty($brand_id)){
                        $keyArr[] = $brand_id;
                        $whereClauseArr[] = "brand_id in($brand_id)";
                }else{$keyArr[] =-1;}
		if(is_array($product_name_id)){
                        $product_name_id = implode(",",$product_name_id);
                }
		if(!empty($product_name_id)){
                        $keyArr[] = $product_name_id;
                        $whereClauseArr[] = "product_name_id in($product_name_id)";
                }else{$keyArr[] =-1;}
		if(!empty($bgcolor)){
                        $keyArr[] = $bgcolor;
                        $bgcolor = strtolower($bgcolor);
                        $whereClauseArr[] = "bgcolor='$bgcolor'";
                }else{$keyArr[] =-1;}
		if($position != ''){
                        $keyArr[] = $position;
                        $whereClauseArr[] = "position=$position";
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
                if(!empty($orderby)){
                        $orderby = $orderby;
		}
                $key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select count(SPONSERED_BRAND_MODEL.id) as cnt from $tableStr $whereClauseStr $orderby $limitStr";
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                return $result;
        }
	/**
        * @note function is used to get sponsered brand model details.
        * @pre not required.
        * @param an integer/comma seperated ids array $ids.
        * @param an integer/comma seperated category ids array $category_id.
        * @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param an integer/comma seperated product name ids/ product name ids array $product_name_id.
	* @param is a varchar $bgcolor
	* @param an integer position $position
        * @param is a boolean value $status.
        * @param is an integer value $startlimit.
        * @param is an integer value $cnt.
        * @param is a string $orderby.
        *
        * @post an associative array.
        * retun an array.
        */
        function arrGetSponseredBrandModelDetails($ids="",$category_id="",$brand_id="",$product_name_id="",$bgcolor="",$position="",$status='1',$startlimit="",$count="",$orderby=""){
                $keyArr[] = $this->sponsered_brand_model_Key;
                $tablenameArr[] = "SPONSERED_BRAND_MODEL";
		if(is_array($ids)){
                        $ids = implode(",",$ids);
                }
		if(!empty($ids)){
                        $keyArr[] = $ids;
                        $whereClauseArr[] = "id in($ids)";
                }else{$keyArr[] =-1;}
		if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
		if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "category_id in ($category_id)";
                }else{$keyArr[] =-1;}
		if(is_array($brand_id)){
                        $brand_id = implode(",",$brand_id);
                }
		if(!empty($brand_id)){
                        $keyArr[] = $brand_id;
                        $whereClauseArr[] = "brand_id in($brand_id)";
                }else{$keyArr[] =-1;}
		if(is_array($product_name_id)){
                        $product_name_id = implode(",",$product_name_id);
                }
		if(!empty($product_name_id)){
                        $keyArr[] = $product_name_id;
                        $whereClauseArr[] = "product_name_id in($product_name_id)";
                }else{$keyArr[] =-1;}
		if(!empty($bgcolor)){
                        $keyArr[] = $bgcolor;
                        $bgcolor = strtolower($bgcolor);
                        $whereClauseArr[] = "bgcolor='$bgcolor'";
                }else{$keyArr[] =-1;}
		if($position != ''){
                        $keyArr[] = $position;
                        $whereClauseArr[] = "position=$position";
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
                if(!empty($orderby)) {
                        $orderby = $orderby;
                }else{
			$orderby = "order by position asc";
		}
                $key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select * from $tableStr $whereClauseStr $orderby $limitStr";
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                return $result;
        }
	function intInsertUpComingProductDetail($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("UPCOMING_PRODUCT_MASTER",array_keys($insert_param),array_values($insert_param));
		$upcoming_product_id = $this->insert($sql);
		if($upcoming_product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->upcoming_product_Key);
		return $upcoming_product_id;
	}

	function boolUpdateUpComingProductDetail($upcoming_product_id,$update_param){
		$update__param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("UPCOMING_PRODUCT_MASTER",array_keys($update_param),array_values($update_param),"upcoming_product_id",$upcoming_product_id);
		//echo $sql;exit;
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->upcoming_product_Key);
		$this->arrGetProductUpComingDetails($upcoming_product_id);
		return $isUpdate;
	}

	function boolDeleteUpComingProductDetail($upcoming_product_id){
		$sql = "delete from UPCOMING_PRODUCT_MASTER where upcoming_product_id = $upcoming_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->upcoming_product_Key);
		return $isDelete;
	}
	function arrGetUpComingProductDetailsCnt($upcoming_product_ids="",$product_name_ids="",$feature_ids="",$expected_month="",$expected_year="",$category_id="",$status='1',$bypos="",$chkmonth="",$chkenddate=""){
		$keyArr[] = $this->upcoming_product_Key."_arrGetUpComingProductDetailsCnt";
		$tablenameArr[] = "UPCOMING_PRODUCT_MASTER";

		//$whereClauseArr[] = "end_date != '0000-00-00 00:00:00'"; // temp condition for 0000-00-00 00:00:00 dates
		if($chkenddate==1){
			$whereClauseArr[] = "(MONTH(end_date) >= ".date('n')." or YEAR(end_date) > ".date('Y').')';
                }

		if(is_array($upcoming_product_ids)){
                        $upcoming_product_ids = implode(",",$upcoming_product_ids);
                }
                if(!empty($upcoming_product_ids)){
                        $keyArr[] = $upcoming_product_ids;
                        $whereClauseArr[] = "upcoming_product_id in($upcoming_product_ids)";
                }else{$keyArr[] =-1;}
		if(is_array($product_name_ids)){
                        $product_name_ids = implode(",",$product_name_ids);
                }
                if(!empty($product_name_ids)){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}
		if(is_array($feature_ids)){
                        $feature_ids = implode(",",$feature_ids);
                }
                if(!empty($feature_ids)){
                        $keyArr[] = $feature_ids;
                        $whereClauseArr[] = "feature_id in($feature_ids)";
                }else{$keyArr[] =-1;}
		if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
                if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "category_id in ($category_id)";
                }else{$keyArr[] =-1;}
		if($status != ''){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "status=$status";
                }else{$keyArr[] =-1;}
		if($bypos != ''){
                       $keyArr[] = $bypos;
                        $whereClauseArr[] = "position!=0";
                }else{$keyArr[] =-1;}
		if($chkmonth != ''){
			$keyArr[] = $chkmonth;
                        //$whereClauseArr[] = "expected_month > '".date('m')."' and expected_year >'".date('Y')."'";
                        $whereClauseArr[] = "expected_month > '".date('m')."'";
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
		if(!empty($orderby)) {
                        $orderby = $orderby;
                }else{
                        $orderby = "order by create_date desc";
                }
		$key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select count(upcoming_product_id) as cnt from $tableStr $whereClauseStr $orderby";
		//echo $sql;
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                return $result;
	}

	function arrGetUpComingProductDetails($upcoming_product_ids="",$product_name_ids="",$feature_ids="",$expected_month="",$expected_year="",$category_id="",$status='1',$startlimit="",$count="",$orderby="",$bypos="",$chkmonth="",$chkenddate=""){
		$keyArr[] = $this->upcoming_product_Key."_arrGetUpComingProductDetails";
                $tablenameArr[] = "UPCOMING_PRODUCT_MASTER";

		//$whereClauseArr[] = "end_date != '0000-00-00 00:00:00'"; // temp condition for 0000-00-00 00:00:00 dates
		if($chkenddate==1){
			$whereClauseArr[] = "(MONTH(end_date) >= ".date('n')." or YEAR(end_date) > ".date('Y').')';
                }

		if(is_array($upcoming_product_ids)){
                        $upcoming_product_ids = implode(",",$upcoming_product_ids);
                }
                if(!empty($upcoming_product_ids)){
                        $keyArr[] = $upcoming_product_ids;
                        $whereClauseArr[] = "upcoming_product_id in($upcoming_product_ids)";
                }else{$keyArr[] =-1;}
		if(is_array($product_name_ids)){
                        $product_name_ids = implode(",",$product_name_ids);
                }
                if(!empty($product_name_ids)){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}
		if(is_array($feature_ids)){
                        $feature_ids = implode(",",$feature_ids);
                }
                if(!empty($feature_ids)){
                        $keyArr[] = $feature_ids;
                        $whereClauseArr[] = "feature_id in($feature_ids)";
                }else{$keyArr[] =-1;}
		if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
                if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "category_id in ($category_id)";
                }else{$keyArr[] =-1;}
		if($status != ''){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "status=$status";
                }else{$keyArr[] =-1;}
		if($bypos != ''){
                       $keyArr[] = $bypos;
                        $whereClauseArr[] = "position!=0";
                }else{$keyArr[] =-1;}
		if($chkmonth != ''){
			$keyArr[] = $chkmonth;
                        //$whereClauseArr[] = "expected_month > '".date('m')."' and expected_year >'".date('Y')."'";
                        $whereClauseArr[] = "expected_month > '".date('m')."'";
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
		if(!empty($orderby)) {
                        $orderby = $orderby;
                }else{
                        $orderby = "order by create_date desc";
                }
		$key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select * from $tableStr $whereClauseStr $orderby $limitStr";
		//echo $sql;
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                return $result;
	}
	function arrGetUploadUpcomingMediaDetails($upcoming_product_id){
                $keysArr[] = $this->upcoming_product_Key."_media_upcoming_product_upload";
                $keysArr[] = "upcoming_product_id_".$upcoming_product_id;
                $key = implode('_',$keysArr);
                $result = $this->cache->get($key);
                if(!empty($result)){ return $result;}
                $sSql="select * from UPCOMING_PRODUCT_VIDEOS UPV, UPCOMING_PRODUCT_MASTER UPM where UPV.upcoming_product_id = UPM.upcoming_product_id and UPV.upcoming_product_id = $upcoming_product_id";
		//echo $sSql;
                $result=$this->select($sSql);
                $this->cache->set($key, $result);
                return $result;
        }
	function boolDeleteUsedCarUpcomingMedia($upcoming_product_id){
                $sql = "delete from UPCOMING_PRODUCT_VIDEOS where upcoming_product_id = $upcoming_product_id";
                $isDelete = $this->sql_delete_data($sql);
                $this->cache->searchDeleteKeys($this->upcoming_product_Key);
                return $isDelete;
        }
	function addUpdUpcomingMediaDetails($aParameters,$sTableName){
                $aParameters['create_date'] = date('Y-m-d H:i:s');
                $aParameters['update_date'] = date('Y-m-d H:i:s');
                $sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
                $iRes=$this->insertUpdate($sSql);
                $this->cache->searchDeleteKeys($this->upcoming_product_Key);
                return $iRes;
        }
	function arrGetBrandBasedUpComingProductDetails($brand_id,$product_name_id,$startlimit="",$count=""){
		$keysArr[] = $this->upcoming_product_Key."_upcoming_product_detail";
		$keysArr[] = "brand_id_".$brand_id;
		if($startlimit != ''){
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
		$key = implode('_',$keysArr);
                $result = $this->cache->get($key);
                if(!empty($result)){ return $result;}
		if($product_name_id != ""){
			$sSql="select * from UPCOMING_PRODUCT_MASTER UPM, PRODUCT_NAME_INFO P where UPM.product_name_id=P.product_name_id and P.brand_id = $brand_id and UPM.product_name_id !=$product_name_id and UPM.status='1' order by UPM.create_date desc $limitStr";
		}else{
			$sSql="select * from UPCOMING_PRODUCT_MASTER UPM, PRODUCT_NAME_INFO P where UPM.product_name_id=P.product_name_id and P.brand_id = $brand_id and UPM.status='1' order by UPM.create_date desc $limitStr";
		}
		//echo $sSql;
		$result=$this->select($sSql);
                $this->cache->set($key, $result);
                return $result;
	}
	function arrSearchUpComingProductDetailsCnt($upcoming_product_ids="",$product_name_ids="",$brand_ids="",$feature_ids="",$expected_month="",$expected_year="",$category_id="",$duration="",$status='1'){
                $keyArr[] = $this->upcoming_product_Key.'_arrSearchUpComingProductDetailsCnt';
                $startdate = date('Y-m-01',strtotime("now"));
                if(!empty($duration)){
                        switch($duration){
                                case 'nextyear':
                                        $startdate = date('Y-01-01',strtotime("next year"));
                                        $enddate = date('Y-12-31',strtotime("next year"));
                                        break;
                                case '1month':
                                        $enddate = date('Y-m-t',strtotime("next month"));
                                        break;
                                case '3months':
                                        $enddate = date('Y-m-t',strtotime("+3month"));
                                        break;
                                case '6months':
                                        $enddate = date('Y-m-t',strtotime("+6month"));
                                        break;
                                case 'this_year':
                                        $enddate = date('Y-12-31');
                                        break;
                        }
					$startdate = "$startdate 00:00:00";
                        $enddate = "$enddate 23:59:59";
                        $whereClauseArr[] = "UPM.end_date >= '$startdate'";
                        $whereClauseArr[] = "UPM.end_date <= '$enddate'";
                        $keyArr[] = $startdate;
                        $keyArr[] = $enddate;
                }else{
						$startdate = "$startdate 00:00:00";
                        $keyArr[] = $startdate;
                        $keyArr[] = -1;
                        $whereClauseArr[] = "UPM.end_date >= '$startdate'";
                }

                $tablenameArr[] = "UPCOMING_PRODUCT_MASTER UPM";
		$tablenameArr[] = "PRODUCT_NAME_INFO P";
		#$whereClauseArr[] = "(MONTH(UPM.end_date) >= ".date('n')." or YEAR(UPM.end_date) > ".date('Y').')';
                if(is_array($upcoming_product_ids)){
                        $upcoming_product_ids = implode(",",$upcoming_product_ids);
                }
                if(!empty($upcoming_product_ids)){
                        $keyArr[] = $upcoming_product_ids;
                        $whereClauseArr[] = "UPM.upcoming_product_id in($upcoming_product_ids)";
                }else{$keyArr[] =-1;}
				$product_name_ids = cleanMySqlInt($product_name_ids);
                if(is_array($product_name_ids)){
                        $product_name_ids = implode(",",$product_name_ids);
                }
                if(!empty($product_name_ids)){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "UPM.product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}
		if(is_array($brand_ids)){
                        $brand_ids = implode(",",$brand_ids);
                }
                if(!empty($brand_ids)){
                        $keyArr[] = $brand_ids;
                        $whereClauseArr[] = "P.brand_id in($brand_ids)";
                }else{$keyArr[] =-1;}
		$whereClauseArr[] = "UPM.product_name_id = P.product_name_id";

                if(is_array($feature_ids)){
                        $feature_ids = implode(",",$feature_ids);
                }
                if(!empty($feature_ids)){
                        $keyArr[] = $feature_ids;
                        $whereClauseArr[] = "UPM.feature_id in($feature_ids)";
                }else{$keyArr[] =-1;}
                if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
		if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "UPM.category_id in ($category_id)";
                }else{$keyArr[] =-1;}
                if($status != ''){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "UPM.status=$status";
                }else{$keyArr[] =-1;}
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                $orderby = "order by UPM.upcoming_product_id desc";
                $key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select count(UPM.upcoming_product_id) as cnt from $tableStr $whereClauseStr $orderby";
                #echo $sql;
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                return $result;
        }
	function arrSearchUpComingProductDetails($upcoming_product_ids="",$product_name_ids="",$brand_ids="",$feature_ids="",$expected_month="",$expected_year="",$category_id="",$duration="",$status='1',$startlimit="",$count="",$orderby=""){
                $keyArr[] = $this->upcoming_product_Key.'_arrSearchUpComingProductDetails';
		$startdate = date('Y-m-01',strtotime("now"));
		if(!empty($duration)){
			switch($duration){
				case 'nextyear':
					$startdate = date('Y-1-01',strtotime("next year"));
					$enddate = date('Y-12-31',strtotime("next year"));
					break;
				case '1month':
					$enddate = date('Y-m-t',strtotime("next month"));
                                	break;
				case '3months':
					$enddate = date('Y-m-t',strtotime("+3month"));
	        	                break;
				case '6months':
					$enddate = date('Y-m-t',strtotime("+6month"));
        	                        break;
				case 'this_year':
					$enddate = date('Y-12-31');
	                                break;
			}
			$startdate = "$startdate 00:00:00";
			$enddate = "$enddate 23:59:59";
			$whereClauseArr[] = "UPM.end_date >= '$startdate'";
			$whereClauseArr[] = "UPM.end_date <= '$enddate'";
			$keyArr[] = $startdate;
			$keyArr[] = $enddate;
		}else{
			$startdate = "$startdate 00:00:00";
			$keyArr[] = $startdate;
			$keyArr[] = -1;
			$whereClauseArr[] = "UPM.end_date >= '$startdate'";
		}
		#die( $duration );

                $tablenameArr[] = "UPCOMING_PRODUCT_MASTER UPM";
		$tablenameArr[] = "PRODUCT_NAME_INFO P";
#		$whereClauseArr[] = "(MONTH(UPM.end_date) >= ".date('n')." or YEAR(UPM.end_date) > ".date('Y').')';
                if(is_array($upcoming_product_ids)){
                        $upcoming_product_ids = implode(",",$upcoming_product_ids);
                }
                if(!empty($upcoming_product_ids)){
                        $keyArr[] = $upcoming_product_ids;
                        $whereClauseArr[] = "UPM.upcoming_product_id in($upcoming_product_ids)";
                }else{$keyArr[] =-1;}

		$product_name_ids = cleanMySqlInt($product_name_ids);
                if(is_array($product_name_ids)){
                        $product_name_ids = implode(",",$product_name_ids);
                }
                if(!empty($product_name_ids)){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "UPM.product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}

		if(is_array($brand_ids)){
                        $brand_ids = implode(",",$brand_ids);
                }
                if(!empty($brand_ids)){
                        $keyArr[] = $brand_ids;
                        $whereClauseArr[] = "P.brand_id in($brand_ids)";
                }else{$keyArr[] =-1;}

		$whereClauseArr[] = "UPM.product_name_id = P.product_name_id";

                if(is_array($feature_ids)){
                        $feature_ids = implode(",",$feature_ids);
                }
                if(!empty($feature_ids)){
                        $keyArr[] = $feature_ids;
                        $whereClauseArr[] = "UPM.feature_id in($feature_ids)";
                }else{$keyArr[] =-1;}

                if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
		if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "UPM.category_id in ($category_id)";
                }else{$keyArr[] =-1;}

                if($status != ''){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "UPM.status=$status";
                }else{$keyArr[] =-1;}

		if($startlimit != ''){
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
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                if(!empty($orderby)) {
                        $orderby = $orderby;
                }else{
                        $orderby = "order by UPM.create_date desc";
                }
		$keyArr[] = $orderby;
                $key = implode("_",$keyArr);

		if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select * from $tableStr $whereClauseStr $orderby $limitStr";
                //echo "SQL--- $sql<br/>";
                $result = $this->select($sql);
		/*
		#print_r($result);die();
		if(!empty($duration)){
			$result = $this->getDurationSearchUpcomingProduct($result,$duration);
		}
		$j=0;
		$new_result=Array();
		if(!empty($count)){
			if(empty($startlimit)){$startlimit = 0;}
			$count = $startlimit+$count;
			if($count > sizeof($result)){
				$count = sizeof($result);
			}
			for($i=$startlimit;$i<$count;$i++){
                        	$new_result[$j] = $result[$i];
                                $j++;
			}
			$result = $new_result;
		}
		#print_r($result);die();
		*/
		$this->cache->set($key,$result);

                return $result;
        }
	function arrSearchUpComingProductDetailsByPriceRange($upcoming_product_ids="",$product_name_ids="",$brand_ids="",$feature_ids="",$expected_month="",$expected_year="",$expected_min_price="",$expected_max_price="",$category_id="",$status='1',$startlimit="",$count="",$orderby=""){
                $keyArr[] = $this->upcoming_product_Key;
                $tablenameArr[] = "UPCOMING_PRODUCT_MASTER UPM";
                $tablenameArr[] = "PRODUCT_NAME_INFO P";
		if(is_array($upcoming_product_ids)){
                        $upcoming_product_ids = implode(",",$upcoming_product_ids);
                }
                if(!empty($upcoming_product_ids)){
                        $keyArr[] = $upcoming_product_ids;
                        $whereClauseArr[] = "UPM.upcoming_product_id in($upcoming_product_ids)";
                }else{$keyArr[] =-1;}

		$product_name_ids = cleanMySqlInt($product_name_ids);
                if(is_array($product_name_ids)){
                        $product_name_ids = implode(",",$product_name_ids);
                }
                if(!empty($product_name_ids)){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "UPM.product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}

                if(is_array($brand_ids)){
                        $brand_ids = implode(",",$brand_ids);
                }
                if(!empty($brand_ids)){
                        $keyArr[] = $brand_ids;
                        $whereClauseArr[] = "P.brand_id in($brand_ids)";
                }else{$keyArr[] =-1;}

                $whereClauseArr[] = "UPM.product_name_id = P.product_name_id";

                if(is_array($feature_ids)){
                        $feature_ids = implode(",",$feature_ids);
                }
                if(!empty($feature_ids)){
                        $keyArr[] = $feature_ids;
                        $whereClauseArr[] = "UPM.feature_id in($feature_ids)";
                }else{$keyArr[] =-1;}

		if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
                if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "UPM.category_id in ($category_id)";
                }else{$keyArr[] =-1;}

                if($status != ''){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "UPM.status=$status";
                }else{$keyArr[] =-1;}

		if($expected_min_price != "" && $expected_max_price != ""){
			$keyArr[] = $expected_min_price."_".$expected_max_price;
			$whereClauseArr[] = "(((UPM.min_expected_price*UPM.min_expected_price_unit) >= $expected_min_price) and ((UPM.max_expected_price*UPM.max_expected_price_unit) <= $expected_max_price))";
		}else{$keyArr[] =-1;}

                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                if(!empty($orderby)) {
                        $orderby = $orderby;
                }else{
                        $orderby = "order by UPM.create_date desc";
                }
                $key = implode("_",$keyArr);
                //if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select * from $tableStr $whereClauseStr $orderby $limitStr";
                //echo $sql;
		$result=$this->select($sql);
                //$this->cache->set($key, $result);
                return $result;
	}

	function getDurationSearchUpcomingProduct($result,$duration){
                $no_of_days_next_month = date('t', strtotime('next month'));
                $next_month = date('n', strtotime('next month'));
                $next_year = date('Y', strtotime('next month'));
                $next_month_end_date = $next_year."-".$next_month."-".$no_of_days_next_month." 23:59:59";
                //echo "next_month_end_date==".$next_month_end_date;

                $no_of_days_next_3months = date('t', strtotime('+3 months'));
                $next_3months = date('n', strtotime('+3 months'));
                $next_3months_year = date('Y', strtotime('+3 months'));
                $next_3months_end_date = $next_3months_year."-".$next_3months."-".$no_of_days_next_3months." 23:59:59";
                //echo "next_3months_end_date==".$next_3months_end_date;

                $no_of_days_next_6months = date('t', strtotime('+6 months'));
                $next_6months = date('n', strtotime('+6 months'));
                $next_6months_year = date('Y', strtotime('+6 months'));
                $next_6months_end_date = $next_6months_year."-".$next_6months."-".$no_of_days_next_6months." 23:59:59";
                //echo "next_6months_end_date==".$next_6months_end_date;

		$this_year = date("Y");
		$this_year_end_date = $this_year."-12-31 23:59:59";

		$next_year = date('Y', strtotime('+1 year'));
		$next_year_start_date = $next_year."-01-01 23:59:59";
		$next_year_end_date = $next_year."-12-31 23:59:59";

	        $cnt = sizeof($result);
                $new_result = Array();
                $j=0;
                for($i=0;$i<$cnt;$i++){
                        $expected_month = $result[$i]["expected_month"];
                        $expected_year = $result[$i]["expected_year"];
                        $no_of_days = date('t',mktime(0,0,0,$expected_month,1,$expected_year));
                        $end_date = $expected_year."-".$expected_month."-".$no_of_days." 23:59:59";
                    	//echo $end_date."<br>";
			if($duration == "1month"){
                                if(strtotime($end_date) >=strtotime("now") && strtotime($end_date) <= strtotime($next_month_end_date)){
                                        $new_result[$j] = $result[$i];
                                        $j++;
                                }
                        }elseif($duration == "3months"){
				if(strtotime($end_date) >=strtotime("now") &&  strtotime($end_date) <= strtotime($next_3months_end_date)){
					//echo "CONVER-->".strtotime($end_date)." <=". strtotime($next_3months_end_date)."<br>";
                                        $new_result[$j] = $result[$i];
                                        $j++;
                                }
			}elseif($duration == "6months"){
                                if(strtotime($end_date) >=strtotime("now") && strtotime($end_date) <= strtotime($next_6months_end_date)){
                                        $new_result[$j] = $result[$i];
                                        $j++;
                                }
			}elseif($duration == "thisyear"){
                                if(strtotime($end_date) >=strtotime("now") && strtotime($end_date) <= strtotime($this_year_end_date)){
                                        $new_result[$j] = $result[$i];
                                        $j++;
                                }
			}elseif($duration == "nextyear"){
                                if((strtotime($end_date) >= strtotime($next_year_start_date)) && (strtotime($end_date) <= strtotime($next_year_end_date))){
                                        $new_result[$j] = $result[$i];
                                        $j++;
                                }
                        }
                }
                //print"<pre>";print_r($new_result);print"</pre>";
                return $new_result;
        }
	/**
        * @note function is used to insert the featured product details into the database.
        * @param an associative array $insert_param.
        * @pre $insert_param must be valid associative array.
        * @post an integer $usedcar_featured_product_id.
        * retun integer.
        */
        function intInsertUsedCarFeaturedProduct($insert_param){
                $insert_param['create_date'] = date('Y-m-d H:i:s');
                $insert_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $this->getInsertSql("USEDCAR_FEATURED_PRODUCT",array_keys($insert_param),array_values($insert_param));
                //echo $sql;
                $usedcar_featured_product_id = $this->insert($sql);
                $this->cache->searchDeleteKeys($this->usedcar_featured_product_Key);

                if($usedcar_featured_product_id == 'Duplicate entry'){ return 'exists';}
                return $usedcar_featured_product_id;
        }
	/**
        * @note function is used to update the featured product details into the database.
        * @param an associative array $update_param.
        * @param an integer $usedcar_featured_product_id.
        * @pre $update_param must be valid associative array and $usedcar_featured_product_id must be non-empty/zero valid integer.
        * @post boolean true/false.
        * retun boolean.
        */
        function boolUpdateUsedCarFeaturedProduct($usedcar_featured_product_id,$update_param){
                $update_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $this->getUpdateSql("USEDCAR_FEATURED_PRODUCT",array_keys($update_param),array_values($update_param),"usedcar_featured_product_id",$usedcar_featured_product_id);
                //echo $sql;
                $isUpdate = $this->update($sql);
                $this->cache->searchDeleteKeys($this->usedcar_featured_product_Key);
                return $isUpdate;
        }
	/**
        * @note function is used to delete the featured product details.
        * @param integer $usedcar_featured_product_id.
        * @pre $usedcar_featured_product_id must be non-empty/zero valid integer.
        * @post boolean true/false.
        * return boolean.
        */
        function boolDeleteUsedCarFeaturedProduct($usedcar_featured_product_id){
                $sql = "delete from USEDCAR_FEATURED_PRODUCT where usedcar_featured_product_id = $usedcar_featured_product_id";
                $isDelete = $this->sql_delete_data($sql);
                $this->cache->searchDeleteKeys($this->usedcar_featured_product_Key);
                return $isDelete;
        }
	function arrGetUsedCarFeaturedProductDetails($usedcar_featured_product_ids="",$used_product_ids="",$used_brand_ids="",$used_model_ids="",$used_variant_ids="",$category_id="",$adminid="",$status='1',$position="",$startlimit="",$count="",$orderby=""){

                $keyArr[] = $this->usedcar_featured_product_Key;

                $tablenameArr[] = "USEDCAR_FEATURED_PRODUCT UFP";
                $tablenameArr[] = "USEDCAR_PRODUCT_MASTER UPM";
                if(is_array($usedcar_featured_product_ids)){
                        $usedcar_featured_product_ids = implode(",",$usedcar_featured_product_ids);
                }
                if(!empty($usedcar_featured_product_ids)){
                        $keyArr[] = $usedcar_featured_product_ids;
                        $whereClauseArr[] = "UFP.usedcar_featured_product_id in($usedcar_featured_product_ids)";
                }else{$keyArr[] =-1;}

                if(is_array($used_product_ids)){
                        $used_product_ids = implode(",",$used_product_ids);
                }
                if(!empty($used_product_ids)){
                        $keyArr[] = $used_product_ids;
                        $whereClauseArr[] = "UFP.used_product_id in($used_product_ids)";
                }else{$keyArr[] =-1;}

                if(is_array($used_brand_ids)){
                        $used_brand_ids = implode(",",$used_brand_ids);
                }
                if(!empty($used_brand_ids)){
                        $keyArr[] = $used_brand_ids;
                        $whereClauseArr[] = "UPM.used_brand_id in($used_brand_ids)";
                }else{$keyArr[] =-1;}

                if(is_array($used_model_ids)){
                        $used_model_ids = implode(",",$used_model_ids);
                }
		if(!empty($used_model_ids)){
                        $keyArr[] = $used_model_ids;
                        $whereClauseArr[] = "UPM.used_model_id in($used_model_ids)";
                }else{$keyArr[] =-1;}

                if(is_array($used_variant_ids)){
                        $used_variant_ids = implode(",",$used_variant_ids);
                }
                if(!empty($used_variant_ids)){
                        $keyArr[] = $used_variant_ids;
                        $whereClauseArr[] = "UPM.used_variant_id in($used_variant_ids)";
                }else{$keyArr[] =-1;}

                if(is_array($category_id)){
                        $category_id = implode(",",$category_id);
                }
                if(!empty($category_id)){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "UFP.category_id in ($category_id)";
                }else{$keyArr[] =-1;}

                if($adminid != ''){
                        $keyArr[] = $adminid;
                        $whereClauseArr[] = "UFP.adminid=$adminid";
                }else{$keyArr[] =-1;}

                if($status != ''){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "UFP.status=$status";
                }else{$keyArr[] =-1;}

		if($position != ''){
                        $keyArr[] = $position;
                        $whereClauseArr[] = "UFP.position=$position";
                }else{$keyArr[] =-1;}

                $whereClauseArr[] = "UFP.used_product_id = UPM.used_product_id";
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                if(!empty($orderby)) {
                        $orderby = $orderby;
                }else{
                        $orderby = "order by UFP.create_date desc";
                }
                $key = implode("_",$keyArr);
                //if($result = $this->cache->get($key)){return $result;}
                $tableStr = implode(",",$tablenameArr);
                $sql = "select *,UFP.status as product_status from $tableStr $whereClauseStr $orderby $limitStr";
                //echo $sql;
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                //print"<pre>";print_r($result);print"</pre>";
                return $result;
        }
	/**
	 * @note function is used to get Product by created date desending
	 *
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer/comma seperated category_ids/ category_id array $category_id.
	 * @param an integer/comma seperated  brand ids $brand_id.
	 * @param a boolean $status .
	 * @param $startprice.
	 * @param $endprice.
	 * @param an integer variant_id $variant_id.
	 * @param an integer startlimit $startlimit.
	 * @param an integer cnt $cnt.
	 *
	 * @pre not required.
	 *
	 * @post an associative array.
	 * retun an array.
	 */
	function arrGetProductDetailsCreatDesc($product_ids="",$category_id="",$brand_id="",$status='1',$startprice="",$endprice="",$variant_id="1",$startlimit="",$count="",$default_city="1",$orderby="",$product_name="",$city_id="",$arrival_date="",$discontinue_flag='',$check_discontinue_date="",$startdate="",$enddate="",$color_id='0'){
		$keyArr[] = $this->productKey;
		//$selectStr = "PRODUCT_MASTER.*";
		if(!empty($startprice)){
			$keyArr[] = $startprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value>=$startprice";
		}else{$keyArr[] =-1;}

		if(!empty($endprice)){
			$keyArr[] = $endprice;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_value<=$endprice";
		}else{$keyArr[] =-1;}

		if(!empty($variant_id)){
			$keyArr[] = $variant_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = $variant_id";
		}else{$keyArr[] =-1;}

		if(!empty($city_id)){
			$keyArr[] = $city_id;
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = $city_id";
		}else{$keyArr[] =-1;}

		if(!empty($startprice) or !empty($endprice) or !empty($variant_id)){
			$keyArr[] = "c_$startprice".'_'.$endprice.'_'.$variant_id.'_'.$color_id;
			//$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			//$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			//$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
			$tablenameArr[] = "PRODUCT_NAME_INFO";
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = PRODUCT_NAME_INFO.product_info_name";
			$whereClauseArr[] = "PRODUCT_NAME_INFO.status=1";
			$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
		}else{$keyArr[] = 'c_-1_-1_-1_-1';}

		if(!empty($startprice) or !empty($endprice) or !empty($variant_id) or !empty($city_id) or !empty($default_city)){
			$keyArr[] = "dc_$startprice".'_'.$endprice.'_'.$variant_id.'_'.$city_id;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id = PRICE_VARIANT_VALUES.product_id";
			if(!empty($default_city)){
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city=1";
			}
			//$selectStr = "PRODUCT_MASTER.*,PRICE_VARIANT_VALUES.*";
			$tablenameArr[] = "PRICE_VARIANT_VALUES";
		}else{
			$keyArr[] = 'dc_-1_-1_-1_-1';
		}
		$tablenameArr[] = "PRODUCT_MASTER";
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
				//if(intval($product_ids)!=0){
				$product_ids = intval($product_ids);
				//}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "PRODUCT_MASTER.status=$status";
		}else{$keyArr[] =-1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$keyArr[] = $startdate;
			$whereClauseArr[] = "PRODUCT_MASTER.create_date >= '$startdate'";
		}else{$keyArr[] =-1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$keyArr[] = $enddate;
			$whereClauseArr[] = "PRODUCT_MASTER.create_date <= '$enddate'";
		}else{$keyArr[] =-1;}
		if($discontinue_flag != ''){
			$keyArr[] = $discontinue_flag;
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_flag=$discontinue_flag";
		}else{$keyArr[] =-1;}
		if($check_discontinue_date != ""){
			$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
			$whereClauseArr[] = "PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date'";
		}
		if($arrival_date != ''){
			$keyArr[] = $arrival_date;
			$whereClauseArr[] = "PRODUCT_MASTER.arrival_date!='0000-00-00'";
		}else{$keyArr[] =-1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PRODUCT_MASTER.product_id in($product_ids)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PRODUCT_MASTER.category_id in ($category_id)";
		}else{$keyArr[] =-1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PRODUCT_MASTER.brand_id in ($brand_id)";
		}else{$keyArr[] =-1;}
		if(!empty($product_name)){
			$keyArr[] = $product_name;
			$product_name = strtolower($product_name);
			$whereClauseArr[] = "PRODUCT_MASTER.product_name = '$product_name'";
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
		if(!empty($orderby)) {
			$orderby = $orderby;
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){
			return $result;
		}
		$tableStr = implode(",",$tablenameArr);
		$sql = "select PRODUCT_MASTER.product_id,PRODUCT_MASTER.category_id,PRODUCT_MASTER.brand_id,PRODUCT_MASTER.uid,PRODUCT_MASTER.product_name,PRODUCT_MASTER.variant,PRODUCT_MASTER.product_desc,PRODUCT_MASTER.image_path,PRODUCT_MASTER.create_date,PRODUCT_NAME_INFO.tags from $tableStr $whereClauseStr $orderby $limitStr";
		//echo $sql;exit;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function getCarName($conn,$select_param){
		list($product_id,$product_name_id) = array($select_param['product_id'],$select_param['product_name_id']);
		$keyArr[] = $this->productKey.'_getCarName';
		$selectArr = array('bm.brand_name');
		array_push($selectArr,'bm.brand_id');
		$tableArr = array('BRAND_MASTER bm','PRODUCT_NAME_INFO pni');
		if(!empty($product_id)){
			$tableArr = array('PRODUCT_MASTER pm');
			$whereClauseArr[] = "pm.product_name = pni.product_info_name";
			$whereClauseArr[] = "bm.brand_id = pm.brand_id";
			array_push($selectArr,'pm.product_name','pm.variant');
		}
		if(!empty($product_name_id)){
			$keyArr[] = $product_name_id;
			$whereClauseArr[] = "bm.brand_id = pni.brand_id";
                        $whereClauseArr[] = "pni.product_name_id = $product_name_id";
			array_push($selectArr,'pni.product_info_name');
        }else{$keyArr[] =-1;}
		if(count($whereClauseArr)>0){
			$whereClauseStr = " where ".implode(' and ', $whereClauseArr);
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select ".implode(',', $selectArr)." from ".implode(',',$tableArr).$whereClauseStr;
		//echo "SQL = $sql";
		$result = $this->select($sql,$conn);
		$this->cache->set($key, $result);
         return $result;
	}

	function arrGetLikeModelName($select_param){
		$keyArr[] = $this->productKey.'_arrGetLikeModelName';
                list($model_name) = array($select_param['model_name']);
                if(!empty($model_name)){
                        $arrWhereClause[] = "product_name like ('$model_name%')";
						$keyArr[] = $product_name;
                }else{$keyArr[] =-1;}
                if(count($arrWhereClause)>0){
                        $strWhereClause = " where ".implode(' and ',$arrWhereClause);
                }
				$key = implode("_",$keyArr);
				$result = $this->cache->get($key);
				if(!empty($result)){ return $result;}
                $sql = "SELECT product_id, product_name FROM PRODUCT_MASTER ".$strWhereClause;
                $result = $this->select($sql,$dbconn);
				$this->cache->set($key, $result);
                return $result;
        }

		function getVariantDetails($select_param){
			$keyArr[] = $this->productKey.'_getVariantDetails';
		list($product_id) = array($select_param['product_id']);
		$tableArr = array('PRODUCT_MASTER pm','PRICE_VARIANT_VALUES pvv','BRAND_MASTER bm');
		$arrWhereClause[] = "pm.product_id=pvv.product_id";
		$arrWhereClause[] = "pm.brand_id=bm.brand_id";
		$arrWhereClause[] = "pm.status = 1";
                $arrWhereClause[] = "pm.discontinue_flag = 1";
		$arrWhereClause[] = "pvv.variant_id=1";
		$arrWhereClause[] = "pvv.default_city=1";
		$arrWhereClause[] = "pvv.variant_value>0";
        $keyArr[] = '1_1_1_1'; //$keyArr[] = "status_1_discontinue_flag_1_variant_id_1default_city_1";
		if(!empty($product_id)){
			$arrWhereClause[] = "pm.product_id=$product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		$sql = "SELECT bm.brand_name, pm.product_name, pm.variant, pm.image_path, pvv.variant_value FROM ".implode(',',$tableArr).$strWhereClause;
		//echo $sql; die;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function getModelName($select_param){
		$keyArr[] = $this->productKey.'_getModelName';
		list($product_id) = array($select_param['product_id']);
		$tableArr = array('PRODUCT_MASTER');
		$arrWhereClause[] = "status = 1";
                #$arrWhereClause[] = "discontinue_flag = 1";
                $keyArr[] = "status_1";
		if(!empty($product_id)){
			$arrWhereClause[] = "product_id=$product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "SELECT brand_id, product_name, variant,arrival_date,discontinue_date FROM ".implode(',',$tableArr).$strWhereClause;
		//echo $sql; die;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function getVariantNotInModel($select_param){
		list($product_id,$model_name,$brand_id) = array($select_param['product_id'],$select_param['model_name'],$select_param['brand_id']);
		$tableArr = array('PRODUCT_MASTER');
		$keyArr[] = $this->productKey.'_getVariantNotInModel';
		$arrWhereClause[] = "status = 1";
        $arrWhereClause[] = "discontinue_flag = 1";
        $keyArr[] = "1_1";
		if(!empty($product_id)){
			$arrWhereClause[] = "product_id=$product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(!empty($model_name)){
			$arrWhereClause[] = "product_name='$model_name'";
			$keyArr[] = $model_name;
		}else{$keyArr[] =-1;}
		if(!empty($brand_id)){
			$arrWhereClause[] = "brand_id=$brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(is_array($result) && count($result) > 0) {
			return $result;
		}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		$sql = "SELECT count(product_id) as cnt FROM ".implode(',',$tableArr).$strWhereClause;
		//echo $sql; //die;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result[0]['cnt'];
	}

	function get_variant_ids_by_model_id($select_param){
		$keyArr[] = $this->productKey.'_get_variant_ids_by_model_id';
		list($product_name_id) = array($select_param['product_name_id']);
		$arrWhereClause[] = "pm.product_name  = pni.product_info_name";
		$arrWhereClause[] = "pm.status = 1";
		$arrWhereClause[] = "pm.discontinue_flag = 1";
		$arrWhereClause[] = "pni.status = 1";
		$keyArr[] = "1_1";
		if(!empty($product_name_id)){
			$arrWhereClause[] = "pni.product_name_id=$product_name_id";
			$keyArr[] = $product_name_id;
		}else{$keyArr[] =-1;}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		$sql = "SELECT pm.product_id FROM PRODUCT_NAME_INFO pni, PRODUCT_MASTER pm ".$strWhereClause;
		//echo $sql; //die;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function get_variant_ids_by_model_name($select_param){
		$keyArr[] = $this->productKey.'_get_variant_ids_by_model_name';
		list($product_name) = array($select_param['product_name']);
		if(!empty($product_name)){
			$arrWhereClause[] = "product_name='$product_name'";
			$keyArr[] = $product_name;
		}else{$keyArr[] =-1;}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		$sql = "SELECT product_id FROM PRODUCT_MASTER ".$strWhereClause;
		//echo $sql; die;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function least_price_variant_id($select_param){
		$keyArr[] = $this->productKey.'_least_price_variant_id';
		list($product_id,$brand_id,$limit) = array($select_param['product_id'],$select_param['brand_id'],$select_param['limit']);
		$arrWhereClause[] = "variant_id=1";
		$arrWhereClause[] = "default_city=1";
		$arrWhereClause[] = "variant_value>0";
		if(is_array($product_id)){
			$product_id = implode(',',$product_id);
		}
		if(!empty($product_id)){
			$arrWhereClause[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if(!empty($brand_id)){
			$arrWhereClause[] = "brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		if(!empty($limit)){
			$limit = " limit 1";
		}
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "SELECT product_id FROM PRICE_VARIANT_VALUES ".$strWhereClause." order by variant_value asc".$limit;
		$result = $this->select($sql);
		$product_id = $result[0]['product_id'];
		$this->cache->set($key, $product_id);
		return $product_id;
	}

	function get_is_product_discontinue($product_id){
		$keyArr[] = $this->productKey.'_get_is_product_discontinue';
		$keyArr[] = $product_id;
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "SELECT discontinue_flag,status from PRODUCT_MASTER where product_id = $product_id";
		//echo "<br/> SQL = $sql";
		$result = $this->select($sql);
		$discontinue = $result[0]['discontinue_flag'];
		$status = $result[0]['status'];
		$discontinue =  ($discontinue == 1 && $status == 1) ? 1 : 0;
		$this->cache->set($key, $discontinue);
		return $discontinue;
	}
	function arrGetModelByName($brand_id,$model_name){
		$keyArr[] = $this->productKey.'_arrGetModelByName';
		unset($modelArr);
		$model_name = urldecode($model_name);
                $model_name = trim($model_name);
		$modelArr[] = "product_info_name = '$model_name'";
                $model_name1 = str_replace(array('-',' - '),' ',$model_name);
		$modelArr[] = "product_info_name = '$model_name1'";
		$modelArr = array_unique($modelArr);
		$model = implode(' or ',$modelArr);
		unset($modelArr);
		$keyArr[] = $model;
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "select * from PRODUCT_NAME_INFO where brand_id = $brand_id and ($model)";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetVariantByName($brand_id,$model_name,$variant){
		$keyArr[] = $this->productKey.'_arrGetVariantByName';
		unset($variantArr);
		$variant = urldecode($variant);
		$variant = trim($variant);
		$variantArr[] = "variant = '$variant'";
                $variant1 = str_replace(array('-',' - '),' ',$variant);
		$variantArr[] = "variant = '$variant1'";
		$variantArr = array_unique($variantArr);
		$variant = implode(' or ',$variantArr);
		unset($variantArr);
		$keyArr[] = $variant;
		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "select * from PRODUCT_MASTER where brand_id = $brand_id and product_name = '$model_name' and ($variant)";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function moreOnCar($category_id="",$brand_id="",$model_id="",$product_id=""){
		require_once(CLASSPATH.'brand.class.php');
		$oBrand = new BrandManagement;
		if(!empty($brand_id)){
			$brandresult = $oBrand->arrGetBrandDetails($brand_id,"","1","","","","","","");
		}
		$brand_name = $brandresult[0]['brand_name'];
		$seo_brand_name = $brandresult[0]['seo_path'];
		unset($result);
		if(!empty($model_id)){
			$result = $this->arrGetProductNameInfo($model_id,$category_id,"","","","","","","","","1");
		}
		$model_name = $result[0]["product_info_name"];
		$seo_model_name = $result[0]["seo_path"];
		if(!empty($model_name)){
        		$product_result = $this->arrGetProductDetails($product_id,"",$brand_id,'1',"","","1","","","1","order by PRICE_VARIANT_VALUES.variant_value asc",$model_name,"","","","1");
		        $variant = $product_result[0]['variant'];
		        $seo_variant = $product_result[0]['seo_path'];
		        $product_id = $product_result[0]['product_id'];
			$arrival_date = $product_result[0]['arrival_date'];
	                $discontinue_date = $product_result[0]['discontinue_date'];
        	        unset($variantUrlYear);
                	$variantUrlYear = buildYear($arrival_date,$discontinue_date);

    		}
    		$arr_productnamedisp[] = $brand_name;
		$arr_productnamedisp[] = $model_name;
		$productnamedisp  = implode(" ",$arr_productnamedisp);

		unset($seoTitleArr);
		$seoTitleArr[] = SEO_WEB_URL;
		$seoTitleArr[] = $_REQUEST['cat_path'];
		$seoTitleArr[] = $seo_brand_name;
		$seoTitleArr[] = $seo_model_name;
		$seoTitleArr[] = $seo_variant;
		if(!empty($variantUrlYear)){
			 $seoTitleArr[] = constructUrl($variantUrlYear);

		}
		$seoTitleArr[] = SEO_PRODUCT_FEATURE;
		$features_url = implode("/",$seoTitleArr);

		unset($result); unset($seoTitleArr);
		$result = $this->arrGetProdCompetitorDetails("",$product_id,$model_id,$brand_id,"1","1",0,3,"",1);
		if(is_array($result)){
			$productids[] = $product_id;
			foreach($result as $k=>$v){
				$productids[]=$v['product_ids'];
			}
			unset($compare_products);
			foreach($productids as $pk=>$pv){
				$product_id = $pv;
                                if(!empty($product_id)){
                                        $product_result = $this->arrGetProductDetails($product_id,"","",'1',"","","1","","","1","order by PRICE_VARIANT_VALUES.variant_value asc");
                                        $comp_variant = $product_result[0]['variant'];
                                        $variant_seo_path = $product_result[0]['seo_path'];
                                        $comp_model_name = $product_result[0]['product_name'];
					if(!empty($model_id)){
						$compresult = $this->arrGetProductNameInfo("",$category_id,"",$comp_model_name,"","","","","","","","","");
                			}
                			$seo_model_name = $compresult[0]["seo_path"];

                                        $comp_product_id = $product_result[0]['product_id'];
                                        $comp_brand_id = $product_result[0]['brand_id'];
					$arrival_date = $product_result[0]['arrival_date'];
        	                	$discontinue_date = $product_result[0]['discontinue_date'];
                	        	unset($comp_variantUrlYear);
                        		$comp_variantUrlYear = buildYear($arrival_date,$discontinue_date);

                                }
                                if(!empty($comp_brand_id)){
                                        $brandresult = $oBrand->arrGetBrandDetails($comp_brand_id,"","1","","","","","","");
                                }
                                $comp_brand_name = $brandresult[0]['brand_name'];
                                $seo_brand_path = $brandresult[0]['seo_path'];
                                #$compare_products[] = constructUrl($comp_brand_name)."-".constructUrl($comp_model_name)."-".constructUrl($comp_variant);
				$comparename='';
				$comparename = $seo_brand_path.'-'.$seo_model_name.'-'.$variant_seo_path;
		                if(!empty($comp_variantUrlYear)){
                	                $comparename = $comparename.'-'.$comp_variantUrlYear;
                        	}
		                $compare_products[] = $comparename;
				//print_r($compare_products);
                                //$productids[]=$v['product_ids'];
                        }

			if(!empty($productids)){
				$str_productids = implode("-Vs-",$compare_products);
				$seoTitleArr[] = SEO_WEB_URL;
				$seoTitleArr[] = $_REQUEST['cat_path'];
				$seoTitleArr[] = SEO_COMPARE_URL;
				$seoTitleArr[] = $str_productids;
				$compare_url = implode("/",$seoTitleArr);
			}
		}
		unset($seoTitleArr);
		$seoTitleArr[] = SEO_WEB_URL;
		$seoTitleArr[] = $_REQUEST['cat_path'];
		$seoTitleArr[] = constructUrl($seo_brand_name);
		$brandpage_url = implode("/",$seoTitleArr);


		$moreon_title[] = array("URL"=>$features_url,"TITLE"=>"Check $productnamedisp Features &amp; Specs");
		if(!empty($compare_url)){
			$moreon_title[] = array("URL"=>$compare_url,"TITLE"=>"Compare $productnamedisp with similar ".$_REQUEST['category_name']);
		}else{
			$moreon_title[] = "";
		}
		$moreon_title[] = array("URL"=>$brandpage_url,"TITLE"=>"View other $brand_name mobiles");

		return $moreon_title;
	}

	function arrGetUpcomingTopBrands($select_param){
		$keyArr[] = $this->productKey.'_arrGetUpcomingTopBrands';
		list($category_id,$status,$groupby,$orderby,$limit) = array($select_param['category_id'],$select_param['status'],$select_param['groupby'],$select_param['orderby'],$select_param['limit']);
		$arrTable = array('UPCOMING_PRODUCT_MASTER UPM', 'PRODUCT_NAME_INFO PNI','BRAND_MASTER B');
		$arrWhereClause[] = 'UPM.product_name_id = PNI.product_name_id';
		$arrWhereClause[] = 'PNI.brand_id = B.brand_id';
		$arrWhereClause[] = '(MONTH(UPM.end_date) >= '.date('n').' or YEAR(UPM.end_date) > '.date('Y').')';
		if(!empty($category_id)){
			$arrWhereClause[] = "UPM.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status!=''){
			$arrWhereClause[] = "UPM.status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(count($arrWhereClause) > 0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		if(!empty($groupby)){
			$strGroupBy = " group by $groupby ";
			$keyArr[] = $groupby;
		}else{$keyArr[] =-1;}
		if(!empty($orderby)){
			$strOrderBy = " order by $orderby ";
			$keyArr[] = $orderby;
		}else{$keyArr[] =-1;}
		if(!empty($limit)){
			$strLimit = " limit $limit ";
			$keyArr[] = $limit;
		}else{$keyArr[] =-1;}

		$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "SELECT distinct(PNI.brand_id) as brand_id, B.brand_name,B.seo_path FROM ".implode(',',$arrTable).$strWhereClause.$strGroupBy.$strOrderBy.$strLimit;
		//echo "<br/> Sql = $sql";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function arrGetUpcomingBodyStyle($select_param){
		$keyArr[] = $this->productKey.'_arrGetUpcomingBodyStyle';
                list($category_id,$status,$groupby,$orderby,$limit) = array($select_param['category_id'],$select_param['status'],$select_param['groupby'],$select_param['orderby'],$select_param['limit']);
		$arrTable = array('UPCOMING_PRODUCT_MASTER');
		$arrWhereClause[] = '(MONTH(end_date) >= '.date('n').' or YEAR(end_date) > '.date('Y').')';
                if(!empty($category_id)){
                        $arrWhereClause[] = "category_id = $category_id";
						$keyArr[] = $category_id;
                }else{$keyArr[] =-1;}
                if($status!=''){
                        $arrWhereClause[] = "status = $status";
						$keyArr[] = $status;
                }else{$keyArr[] =-1;}
                if(count($arrWhereClause) > 0){
                        $strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
                }
                if(!empty($groupby)){
                        $strGroupBy = " group by $groupby ";
						$keyArr[] = $groupby;
                }else{$keyArr[] =-1;}
                if(!empty($orderby)){
                        $strOrderBy = " order by $orderby ";
						$keyArr[] = $orderby;
                }
                if(!empty($limit)){
                        $strLimit = " limit $limit ";
						$keyArr[] = $limit;
                }else{$keyArr[] =-1;}
				$key = implode("_",$keyArr);
		$result = $this->cache->get($key);
		///if(!empty($result)){ return $result;}
                $sql = "SELECT feature_id, count(feature_id) as cnt FROM ".implode(',',$arrTable).$strWhereClause.$strGroupBy.$strOrderBy.$strLimit;
                echo "<br/> Sql = $sql"; die('Body Style');
                $result = $this->select($sql);
				$this->cache->set($key, $result);
                return $result;
	}

        function arrGetUpcomingBodyStyleWithBrand($select_param){
			$keyArr[] = $this->productKey.'_arrGetUpcomingBodyStyleWithBrand';
                list($brand_id,$category_id,$status,$groupby,$orderby,$limit) = array($select_param['brand_id'],$select_param['category_id'],$select_param['status'],$select_param['groupby'],$select_param['orderby'],$select_param['limit']);
				$arrTable = array('UPCOMING_PRODUCT_MASTER UPM', 'PRODUCT_NAME_INFO PNI','FEATURE_MASTER FM');
				$arrWhereClause[] = 'UPM.product_name_id = PNI.product_name_id';
				$arrWhereClause[] = 'UPM.feature_id = FM.feature_id';
				$arrWhereClause[] = '(MONTH(UPM.end_date) >= '.date('n').' or YEAR(UPM.end_date) > '.date('Y').')';
                if(!empty($brand_id)){
                        $arrWhereClause[] = "PNI.brand_id = $brand_id";
						$keyArr[] = $brand_id;
                }else{$keyArr[] =-1;}
                if(!empty($category_id)){
                        $arrWhereClause[] = "UPM.category_id = $category_id";
						$keyArr[] = $category_id;
                }else{$keyArr[] =-1;}
                if($status!=''){
                        $arrWhereClause[] = "UPM.status = $status";
						$keyArr[] = $status;
                }else{$keyArr[] =-1;}
                if(count($arrWhereClause) > 0){
                        $strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
                }
                if(!empty($groupby)){
                        $strGroupBy = " group by $groupby ";
						$keyArr[] = $groupby;
                }else{$keyArr[] =-1;}
                if(!empty($orderby)){
                        $strOrderBy = " order by $orderby ";
						$keyArr[] = $orderby;
                }
                if(!empty($limit)){
                        $strLimit = " limit $limit ";
						$keyArr[] = $limit;
                }else{$keyArr[] =-1;}
				$key = implode("_",$keyArr);
				$result = $this->cache->get($key);
				if(!empty($result)){ return $result;}
                $sql = "SELECT distinct(FM.feature_id),feature_name FROM ".implode(',',$arrTable).$strWhereClause.$strGroupBy.$strOrderBy.$strLimit;
                //echo "<br/> Sql = $sql"; die('Body Style with Brand');
                $result = $this->select($sql);
				$this->cache->set($key, $result);
                return $result;
        }


        function getUpcomingProductCount($category_id="",$selected_brand_id="",$product_name_id="",$feature_id="",$price_value=""){
		require_once(CLASSPATH.'brand.class.php');
		require_once(CLASSPATH.'feature.class.php');
		$oBrand = new BrandManagement;
		$oFeature = new FeatureManagement;
		if($price_value != ""){
			$low_price_range = $price_value - LEAST_PRICE_RELATED_USED;
			$high_price_range = $price_value + MAX_PRICE_RELATED_USED;
		}
		if($feature_id != ""){
			unset($fres);
			$fresd = $oFeature->arrGetFeatureDetails($feature_id,$category_id);
			$selected_feature_name = $fresd[0]['feature_name'];
		}
		if($selected_brand_id != ""){
			unset($brand_result);
			$brand_result = $oBrand->arrGetBrandDetails($selected_brand_id);
			$selected_brand_name = $brand_result[0]['brand_name'];
		}
		if($product_name_id != ""){
			unset($p_res);
			$p_res = $this->arrSearchUpComingProductDetails("",$product_name_id);
			if(sizeof($p_res) > 0){
				$page_upcoming_product_id = $p_res[0]['upcoming_product_id'];
			}
		}
		$upcoming_model_id_arr = Array();
		$final_arr = array();
		#echo "<br/> selected_brand_id = $selected_brand_id & feature_id = $feature_id & low_price_range = $low_price_range & high_price_range = $high_price_range <br/>";
		$is_brand_price_feature=0; $is_brand_feature=0; $is_brand_price=0; $is_brand=0;
		//  BRAND BASED
		if(!empty($selected_brand_id)){
			// fetch record for a given brand
			$arr_brand_result = $this->arrSearchUpComingProductDetailsByPriceRange("","",$selected_brand_id,"","","","","",$category_id,'1',$startlimit,$limit_cnt,$orderby);
			$cnt_brand_result = sizeof($arr_brand_result);
			#print_r($arr_brand_result); die;
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id = $val_brand['brand_id'];
					$brand_feature = $val_brand['feature_id'];
					$min_expected_price = $val_brand['min_expected_price'];
					$amin_expected_price = explode(".",$min_expected_price);
					$min_expected_price_unit = $val_brand['min_expected_price_unit'];
					$max_expected_price = $val_brand['max_expected_price'];
					$amax_expected_price = explode(".",$max_expected_price);
					$max_expected_price_unit = $val_brand['max_expected_price_unit'];
					$car_max_price = $max_expected_price * $max_expected_price_unit;
					$car_min_price = $min_expected_price * $min_expected_price_unit;
					if(($selected_brand_id == $fetch_brand_id) && ($brand_feature == $feature_id) && (((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price))) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price))){
						if(count($final_arr) == 3){ break; }
						$final_arr[] = $val_brand;
						unset($arr_brand_result[$key_brand]);
						$is_brand_price_feature++;
					}
				}
			}
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id = $val_brand['brand_id'];
					$brand_feature = $val_brand['feature_id'];
					$min_expected_price = $val_brand['min_expected_price'];
					$min_expected_price_unit = $val_brand['min_expected_price_unit'];
					$max_expected_price = $val_brand['max_expected_price'];
					$max_expected_price_unit = $val_brand['max_expected_price_unit'];
					$car_max_price = $max_expected_price * $max_expected_price_unit;
					$car_min_price = $min_expected_price * $min_expected_price_unit;
					if(count($final_arr) < 3){
						if(($selected_brand_id == $fetch_brand_id) && ($brand_feature == $feature_id) && $car_min_price>=200000 ){
						if(count($final_arr) == 3){ break; }
							$final_arr[] = $val_brand;
							unset($arr_brand_result[$key_brand]);
							$is_brand_feature++;
						}
					}
				}

			}
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id = $val_brand['brand_id'];
					$min_expected_price = $val_brand['min_expected_price'];
					$min_expected_price_unit = $val_brand['min_expected_price_unit'];
					$max_expected_price = $val_brand['max_expected_price'];
					$max_expected_price_unit = $val_brand['max_expected_price_unit'];
					$car_max_price = $max_expected_price * $max_expected_price_unit;
					$car_min_price = $min_expected_price * $min_expected_price_unit;
					if(count($final_arr) < 3){
					if(($selected_brand_id == $fetch_brand_id) && (((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price))) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price))){
						if(count($final_arr) == 3){ break; }
							$final_arr[] = $val_brand;
							unset($arr_brand_result[$key_brand]);
							$is_brand_price++;
						}
					}
				}
			}
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id = $val_brand['brand_id'];
					if(count($final_arr) < 3){
						if(($selected_brand_id == $fetch_brand_id)){
							if(count($final_arr) == 3){ break; }
								$final_arr[] = $val_brand;
								unset($arr_brand_result[$key_brand]);
								$is_brand++;
							}
						}
					}
				}
			}
			// BODYTYPE BASED
			$is_feature =0; $is_feature_price=0;
			if(!empty($feature_id) && count($final_arr) < 3){
				// fetch record for a given brand
				$arr_bodytype_result = $this->arrSearchUpComingProductDetailsByPriceRange("","","",$feature_id,"","","","",$category_id,'1',$startlimit,$limit_cnt,$orderby);
				$cnt_bodytype_result = sizeof($arr_bodytype_result);
				//print_r($arr_bodytype_result); die;
				if($cnt_bodytype_result > 0){
					foreach($arr_bodytype_result as $key_bodytype=>$val_bodytype){
						$brand_feature = $val_bodytype['feature_id'];
						$min_expected_price = $val_bodytype['min_expected_price'];
						$min_expected_price_unit = $val_bodytype['min_expected_price_unit'];
						$max_expected_price = $val_bodytype['max_expected_price'];
						$max_expected_price_unit = $val_bodytype['max_expected_price_unit'];
						$car_max_price = $max_expected_price * $max_expected_price_unit;
						$car_min_price = $min_expected_price * $min_expected_price_unit;

						if(($brand_feature == $feature_id) && (((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price))) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price))){
							if(count($final_arr) == 3){ break; }
							$final_arr[] = $val_bodytype;
							unset($arr_bodytype_result[$key_bodytype]);
							$is_feature_price++;
						}
					}
				}
				if($cnt_bodytype_result > 0){
					foreach($arr_bodytype_result as $key_bodytype=>$val_bodytype){
						$brand_feature = $val_bodytype['feature_id'];
						$min_expected_price = $val_bodytype['min_expected_price'];
						$min_expected_price_unit = $val_bodytype['min_expected_price_unit'];
						$max_expected_price = $val_bodytype['max_expected_price'];
						$max_expected_price_unit = $val_bodytype['max_expected_price_unit'];
						$car_max_price = $max_expected_price * $max_expected_price_unit;
						$car_min_price = $min_expected_price * $min_expected_price_unit;
						if($brand_feature == $feature_id && $car_min_price >=200000){
							if(count($final_arr) == 3){ break; }
							$final_arr[] = $val_bodytype;
							unset($arr_bodytype_result[$key_bodytype]);
							$is_feature++;
						}
					}
				}
			}
			// PRICE BASED
			$is_price=0;
			if(!empty($low_price_range) && !empty($high_price_range) && count($final_arr) < 3){
				// fetch record for a given brand
				$arr_price_result = $this->arrSearchUpComingProductDetailsByPriceRange("","","","","","",$low_price_range,$high_price_range,$category_id,'1',$startlimit,$limit_cnt,$orderby);
				$cnt_price_result = sizeof($arr_price_result);
				//print_r($arr_price_result); die;
				if($cnt_price_result > 0){
					foreach($arr_price_result as $key_price=>$val_price){
						$min_expected_price = $val_price['min_expected_price'];
						$min_expected_price_unit = $val_price['min_expected_price_unit'];
						$max_expected_price = $val_price['max_expected_price'];
						$max_expected_price_unit = $val_price['max_expected_price_unit'];
						$car_max_price = $max_expected_price * $max_expected_price_unit;
						$car_min_price = $min_expected_price * $min_expected_price_unit;

							//echo "<br/>car_min_price = $car_min_price < low_price_range = $low_price_range && low_price_range = $low_price_range < car_max_price = $car_max_price) || ( car_min_price = $car_min_price < high_price_range = $high_price_range && high_price_range = $high_price_range < car_max_price = $car_max_price  && (low_price_range = $low_price_range < car_min_price = $car_min_price && high_price_range = $high_price_range > car_max_price = $car_max_price)<br/>";

						if((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price)) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price)){
							if(count($final_arr) == 3){ break; }
							$final_arr[] = $val_price;
							unset($arr_price_result[$key_price]);
							$is_price++;
						}
					}
				}
			}
			$result_data = $final_arr;
			if(is_array($result_data)){
				foreach($result_data as $key=>$aValue){
					$upcoming_product_id = $aValue['upcoming_product_id'];
				if(!in_array($upcoming_product_id,$upcoming_product_ids)){
					$result[] = $aValue;
				}
				$upcoming_product_ids[] = $upcoming_product_id;
			}
		}
		//print_r($result);
		return $result_cnt = sizeof($result);
	}

	function arrGetProductNameInfoExcludeDiscontinued($product_name_ids="",$category_id="",$brand_id="",$product_info_name="",$status="1",$startlimit="",$cnt="",$orderby="",$arrival_date_flag="",$discontinue_flag="1",$check_discontinue_date="",$search_status="",$upcoming_flag="0"){
                $keyArr[] = $this->productKey."_arrGetProductNameInfoExcludeDiscontinued";
            if(is_array($product_name_ids)){
              foreach($product_name_ids as $model_id){
                $i_model_ids = intval($model_id);
                if($i_model_ids!=0){
                  $model_ids[] = $i_model_ids;
                }
              }
              $product_name_ids = implode(",",$model_ids);
            }else{
              if(strpos($product_name_ids,',')==false){
                //if(intval($product_name_ids)!=0){
                  $product_name_ids = intval($product_name_ids);
                //}
              }else{
                $arr_model_ids = explode(",",$product_name_ids);
                foreach($arr_model_ids as $model_id){
                  $i_model_ids = intval($model_id);
                  if($i_model_ids!=0){
                    $model_ids[] = $i_model_ids;
                  }
                }
                $product_name_ids = implode(",",$model_ids);
              }
            }
                if($category_id != ""){
                        $keyArr[] = $category_id;
                        $whereClauseArr[] = "PRODUCT_NAME_INFO.category_id in($category_id)";
                }else{$keyArr[] =-1;}
                if($brand_id != ""){
                        $keyArr[] = $brand_id;
                        $whereClauseArr[] = "PRODUCT_NAME_INFO.brand_id in($brand_id)";
                }else{$keyArr[] =-1;}
                if(!empty($product_name_ids)){
                        $keyArr[] = $product_name_ids;
                        $whereClauseArr[] = "PRODUCT_NAME_INFO.product_name_id in($product_name_ids)";
                }else{$keyArr[] =-1;}
               if(!empty($product_info_name)){
                        $keyArr[] = $product_info_name;
                        $product_info_name = strtolower($product_info_name);
                        $whereClauseArr[] = "PRODUCT_NAME_INFO.product_info_name = '$product_info_name'";
                }else{$keyArr[] =-1;}
                if($arrival_date_flag == "1"){
                        $keyArr[] = $arrival_date;
                        $whereClauseArr[] = "arrival_date !='0000-00-00'";
                }else{$keyArr[] =-1;}
                if($status != ""){
                        $keyArr[] = $status;
                        $whereClauseArr[] = "PRODUCT_NAME_INFO.status = $status";
                }else{$keyArr[] =-1;}
                if($discontinue_flag != ""){
                        $keyArr[] = $discontinue_flag;
                        $whereClauseArr[] = "discontinue_flag = $discontinue_flag";
                }else{$keyArr[] =-1;}
                if($check_discontinue_date != ""){
                        $prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
                        $whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date='0000-00-00 00:00:00')";
                        $keyArr[] = $check_discontinue_date."_".$prev_3_mon_date;
                }else{$keyArr[] =-1;}
                if($search_status != ""){
					if($search_status == "upcoming"){
					$whereClauseArr[] = "upcoming_flag = 1";
					}else if($search_status == "discontinue"){
					$whereClauseArr[] = "discontinue_flag = 0";
					}
                    $keyArr[] = $search_status;
        	}else{$keyArr[] =-1;}
                if($upcoming_flag != ""){
                       $keyArr[] = $upcoming_flag;
                        $whereClauseArr[] = "upcoming_flag = $upcoming_flag";
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
                        $limitStr = " limit ".implode(" , ",$limitArr);
                }
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                $whereClauseStr .= "  AND PRODUCT_MASTER.product_name=PRODUCT_NAME_INFO.product_info_name AND PRODUCT_MASTER.discontinue_flag=1 ";

                if($orderby==''){
                        $orderby='order by product_info_name asc';

                }else{
                 $orderby=$orderby;
                }
                        $keyArr[] = $orderby;
                $key = implode("_",$keyArr);

                if($result = $this->cache->get($key)){return $result;}
                $sSql = "select *,PRODUCT_NAME_INFO.image_path as image_path,PRODUCT_NAME_INFO.img_media_id as img_media_id from PRODUCT_NAME_INFO,PRODUCT_MASTER $whereClauseStr GROUP BY product_info_name $orderby $limitStr ";
                $result = $this->select($sSql);
                $this->cache->set($key,$result);
                return $result;
        }


       function arrGetProductNameInfoExcludeDiscontinuedCnt($product_name_ids="",$category_id="",$brand_id="",$product_info_name="",$status="1",$startlimit="",$cnt="",$orderby="",$arrival_date_flag="",$discontinue_flag="1",$check_discontinue_date="",$search_status="",$upcoming_flag="0"){
        $keyArr[] = $this->productKey."_arrGetProductNameInfoExcludeDiscontinuedCnt";
        if(is_array($product_name_ids)){
          foreach($product_name_ids as $model_id){
            $i_model_ids = intval($model_id);
            if($i_model_ids!=0){
              $model_ids[] = $i_model_ids;
            }
          }
          $product_name_ids = implode(",",$model_ids);
        }else{
          if(strpos($product_name_ids,',')==false){
            //if(intval($product_name_ids)!=0){
              $product_name_ids = intval($product_name_ids);
            //}
          }else{
            $arr_model_ids = explode(",",$product_name_ids);
            foreach($arr_model_ids as $model_id){
              $i_model_ids = intval($model_id);
              if($i_model_ids!=0){
                $model_ids[] = $i_model_ids;
              }
            }
            $product_name_ids = implode(",",$model_ids);
          }
        }
        if($category_id != ""){
            $keyArr[] = $category_id;
            $whereClauseArr[] = "PRODUCT_NAME_INFO.category_id in($category_id)";
        }else{$keyArr[] =-1;}
        if($brand_id != ""){
            $keyArr[] = $brand_id;
            $whereClauseArr[] = "PRODUCT_NAME_INFO.brand_id in($brand_id)";
        }else{$keyArr[] =-1;}
        if(!empty($product_name_ids)){
            $keyArr[] = $product_name_ids;
            $whereClauseArr[] = "PRODUCT_NAME_INFO.product_name_id in($product_name_ids)";
        }else{$keyArr[] =-1;}
        if(!empty($product_info_name)){
            $keyArr[] = $product_info_name;
            $product_info_name = strtolower($product_info_name);
            $whereClauseArr[] = "PRODUCT_NAME_INFO.product_info_name = '$product_info_name'";
        }else{$keyArr[] =-1;}
        if($arrival_date_flag == "1"){
            $keyArr[] = $arrival_date;
            $whereClauseArr[] = "arrival_date !='0000-00-00'";
        }else{$keyArr[] =-1;}
        if($status != ""){
            $keyArr[] = $status;
            $whereClauseArr[] = "PRODUCT_NAME_INFO.status = $status";
        }else{$keyArr[] =-1;}
        if($discontinue_flag != ""){
            $keyArr[] = $discontinue_flag;
            $whereClauseArr[] = "discontinue_flag = $discontinue_flag";
        }else{$keyArr[] =-1;}
        if($check_discontinue_date != ""){
            $prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
            $whereClauseArr[] = "(PRODUCT_MASTER.discontinue_date >= '$prev_3_mon_date' OR PRODUCT_MASTER.discontinue_date='0000-00-00 00:00:00')";
            $keyArr[] = $check_discontinue_date."_".$prev_3_mon_date;
        }else{$keyArr[] ='-1_-1';}
        if($search_status != ""){
            if($search_status == "upcoming"){

                $whereClauseArr[] = "upcoming_flag = 1";
            }else if($search_status == "discontinue"){
                $whereClauseArr[] = "discontinue_flag = 0";
            }
                $keyArr[] = $search_status;
        }else{$keyArr[] =-1;}
        if($upcoming_flag != ""){
           $keyArr[] = $upcoming_flag;
            $whereClauseArr[] = "upcoming_flag = $upcoming_flag";
        }else{$keyArr[] =-1;}
        /*else if(($upcoming_flag == "") || ($upcoming_flag == '0')){
            $keyArr[] = "upcoming_flag_$upcoming_flag";
            $whereClauseArr[] = "upcoming_flag = 0";
        }
        */
        if(!empty($startlimit)){
            $keyArr[] = $startlimit;
            $limitArr[] = $startlimit;
        }else{$keyArr[] =-1;}
        if(!empty($cnt)){
            $keyArr[] = $cnt;
            $limitArr[] = $cnt;
        }
        if(sizeof($limitArr) > 0){
            $limitStr = " limit ".implode(" , ",$limitArr);
        }
        if(sizeof($whereClauseArr) > 0){
            $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
        }
        $whereClauseStr .= "  AND PRODUCT_MASTER.product_name=PRODUCT_NAME_INFO.product_info_name AND PRODUCT_MASTER.discontinue_flag=1 ";

        if($orderby==''){
            $orderby='order by product_info_name asc';

        }else{
         $orderby=$orderby;
        }
            $keyArr[] = $orderby;
        $key = implode("_",$keyArr);

        if($result = $this->cache->get($key)){return $result;}
        $sSql = "select count(*) as cnt from PRODUCT_NAME_INFO,PRODUCT_MASTER $whereClauseStr GROUP BY product_info_name $orderby $limitStr ";
        $result = $this->select($sSql);
        $this->cache->set($key,$result);
        return $result;
    }

	function validateProductData($product_name,$type){
		unset($result);
		$keyArr[] = $product_name;
		$prev_3_mon_date = date('Y-m-d',strtotime("-".DISCONTINUE_MONTH_DURATION." month")).' 00:00:00';
                $key = implode("_",$keyArr);
		###if($result = $this->cache->get($key)){return $result;}
		$sSqll = "select * from PRODUCT_MASTER P,PRICE_VARIANT_VALUES PV where (P.product_name = '$product_name' or P.product_name = '".str_replace(" ","-",$product_name)."') and P.product_id=PV.product_id and PV.default_city=1 and PV.variant_id=1 and (P.discontinue_date >= '$prev_3_mon_date' OR P.discontinue_date = '0000-00-00 00:00:00') order by PV.variant_value asc";
		#echo "<br>SQL#1".$sSqll."<br>";
		$result = $this->select($sSqll);
		####$this->cache->set($key,$result);
		#print "<pre>";	print_r($result);
		if(empty($result)){
			$aproduct_name = explode(" ",$product_name);
			unset($keyArr);
			#print "<pre>"; print_r($aproduct_name);
			if(sizeof($aproduct_name)>2){
				$product_name = $aproduct_name[1]." ".$aproduct_name[2];
			}else{
				$product_name = $aproduct_name[1];
			}
			$keyArr[] = $aproduct_name[1]." ".$aproduct_name[2];
	                $key = implode("_",$keyArr);
			unset($result);
        	        #### if($result = $this->cache->get($key)){return $result;}
			$sSql1 = "select * from PRODUCT_MASTER P,PRICE_VARIANT_VALUES PV where (P.product_name = '$product_name' or P.product_name = '".str_replace(" ","-",$product_name)."') and P.product_id=PV.product_id and PV.default_city=1 and PV.variant_id=1 and (P.discontinue_date >= '$prev_3_mon_date' OR P.discontinue_date = '0000-00-00 00:00:00') order by PV.variant_value asc";
			#echo "<br>SQL#2".$sSql1."<br>";
	                $result = $this->select($sSql1);
		}
		if(empty($result)){
			#echo "STR#3".$product_name."<br>";
                        $aproduct_name = explode(" ",$product_name);
                        unset($keyArr);
			#print_r($aproduct_name);  echo "SIZE-->".sizeof($aproduct_name);
			if(sizeof($aproduct_name)>1){
	                        $product_name = $aproduct_name[1];
                        	$keyArr[] = $aproduct_name[1];
			}else{
				$product_name = $aproduct_name[0];
				$keyArr[] = $aproduct_name[0];
			}
                        $key = implode("_",$keyArr);
			unset($result);
                        #### if($result = $this->cache->get($key)){return $result;}
                        $sSql1 = "select * from PRODUCT_MASTER P,PRICE_VARIANT_VALUES PV where (P.product_name = '$product_name' or P.product_name = '".str_replace(" ","-",$product_name)."') and P.product_id=PV.product_id and PV.default_city=1 and PV.variant_id=1 and (P.discontinue_date >= '$prev_3_mon_date' OR P.discontinue_date = '0000-00-00 00:00:00') order by PV.variant_value asc";
			#echo "<br>SQL#3".$sSql1."<br>";
                        $result = $this->select($sSql1);
                }
		if(!empty($result)){
			#print_r($result); die();

			$brand_id = $result[0]['brand_id'];
			$brand_sql = "select * from BRAND_MASTER where brand_id = $brand_id";
			$abrand = $this->select($brand_sql);
			if($abrand){
				$brand_name = $abrand[0]['brand_name'];
			}
			$arrival_date = $result[0]['arrival_date'];
	                $discontinue_date = $result[0]['discontinue_date'];


        	                unset($variantUrlYear);
                	        $variantUrlYear = buildYear($arrival_date,$discontinue_date);
				$pname = $result[0]['product_name'];
				$vname = $result[0]['variant'];


			        $model_sql = "select product_name_id,image_path, seo_path from PRODUCT_NAME_INFO  where product_info_name = '$pname'";
	                        $amodel = $this->select($model_sql);
				$selproductimg = $amodel['0']['image_path'];
                		$img_media_id = $amodel['0']['img_media_id'];
				if(!empty($selproductimg)){
                 		       $selproductimg = resizeImagePath($selproductimg,"87X65",$aModuleImageResize,$img_media_id);
		                        $selproductimg = $selproductimg ? CENTRAL_IMAGE_URL.$selproductimg :"";
                		}

				#$compare_name_url = constructUrl($brand_name)."-".constructUrl($pname)."-".constructUrl($vname);
				$compare_name_url = $abrand[0]['seo_path']."-".$amodel[0]['seo_path']."-".$result[0]['seo_path'];
				if($type=='variant'){
					$comp_name = $brand_name." ".$pname." ".$vname;
				}else{
					$comp_name = $brand_name." ".$pname;
				}
				#$comp_name = constructUrl($brand_name)." ".constructUrl($pname)." ".constructUrl($vname);
				#$comp_name = $brand_name." ".$pname;
				if(!empty($variantUrlYear)){
					 $compare_name_url =  $compare_name_url."-".$variantUrlYear;
				}
			}
		if(!empty($comp_name)){
			$compare_name_arr['compare_name']= $comp_name;
			$compare_name_arr['compare_url'] = $compare_name_url;
			$compare_name_arr['model_name'] = $pname;
			$compare_name_arr['variant_name'] = $vname;
			$compare_name_arr['model_image'] = $selproductimg;
			$compare_name_arr['product_disp_name'] = $brand_name." ".$pname." ".$vname;
		}
		#print "<pre>"; print_r($compare_name_arr); die();
		unset($result);
                return $compare_name_arr;
	}
/*
	function topSearchComparisons($keywords,$keywords2,$type="model",$cat_path=""){
		$file_path_name = BASEPATH."searchxml/".str_replace(array(" ","-"),"_",strtolower($keywords))."_".$type.".xml";
		$file_mod_time = filemtime($file_path_name);
		$curr_date_time = strtotime("now");
		$file_diff_time = $curr_date_time - $file_mod_time;
		if(file_exists($file_path_name) && $file_diff_time < 86400){
			//echo "IN";
			$strCompXML1 = file_get_contents($file_path_name);
		}else{
			//echo "OUT";
			$search_api = "http://www.google.co.in/complete/search?q=".urlencode($keywords)."+vs&output=toolbar";
			$response = file_get_contents($search_api);
			//header('Content-type: text/xml');echo $response; exit;
			$i=0; $fflag=0;$iround=0; $arrThreecompareCarsUrl = array();
			$strCompXML1 ="<TOP_COMPARE_SET_MASTER>";
			if(!empty($response)){
				$xml = simplexml_load_string($response); //die("no data found");
				//print_r($xml);
				if(!empty($xml)){
					foreach($xml->children() as $xml_CompleteSuggestion){
						foreach($xml_CompleteSuggestion->children() as $xml_suggestion => $suggestion){
							//print_r($suggestion);
							$i++; 
							$data = $suggestion['data'];
							//echo $data."<br>";
							if(!empty($data)){
								$parse_data = explode("vs",$data);
								$first_data = trim($parse_data[0]);
								$product_name = trim($parse_data[1]);
								unset($name); unset($urldata);
								if(!empty($first_data)){
									$arrvalidate_fdata =$this->validateProductData($keywords2,$type);
									$name[] = $arrvalidate_fdata['compare_name'];
									$urldata[] = $arrvalidate_fdata['compare_url'];
									if($iround == 0){
										$arrThreecompareCarsUrl[] = $arrvalidate_fdata['compare_url'];
									}
									$fm_name = $arrvalidate_fdata['model_name'];
									$fv_name = $arrvalidate_fdata['variant_name'];
									$fp_dispname = $arrvalidate_fdata['product_disp_name'];
									$fm_img = $arrvalidate_fdata['model_image'];
									$fflag++;
								}
								if(!empty($product_name)){
									$arrsvalidate_data =$this->validateProductData($product_name,$type);
									$name[] = $arrsvalidate_data['compare_name'];
									$urldata[] = $arrsvalidate_data['compare_url'];
									if($iround < 3 && $arrsvalidate_data['compare_name']!=''){
										$arrThreecompareCarsUrl[] = $arrsvalidate_data['compare_url'];
									}
									$iround++;
									$sm_name = $arrsvalidate_data['model_name'];
									$m_name = $arrsvalidate_data['model_name'];
									$sp_dispname = $arrsvalidate_data['product_disp_name'];
									$sv_name = $arrsvalidate_data['variant_name'];
									$sm_img = $arrsvalidate_data['model_image'];
								}
								
								//echo $m_name."NAME<br>" ;
								unset($arrcompareCarsUrl);
								$arrcompareCarsUrl[] = SEO_WEB_URL;
								$arrcompareCarsUrl[] = $cat_path;
								$arrcompareCarsUrl[] = SEO_COMPARE_URL;
								if(!empty($urldata)){
									$arrcompareCarsUrl[] = implode("-Vs-",$urldata);
									
								}
								if(!empty($name)){
									$compare_name= implode(" V/s ",$name);
								}
								$compareCarsUrl = implode("/",$arrcompareCarsUrl);
								if(!empty($arrsvalidate_data)){
									if(!in_array($m_name,$mod_name)){
										$strCompXML1 .="<TOP_COMPARISIONS>";
										$strCompXML1 .="<TOP_COMPARISION_NAME><![CDATA[$compare_name]]></TOP_COMPARISION_NAME>";
										$strCompXML1 .="<TOP_COMPARISION_URL><![CDATA[$compareCarsUrl]]></TOP_COMPARISION_URL>";
										$strCompXML1 .="<TOP_COMPARISION_FIRSTNAME><![CDATA[$fp_dispname]]></TOP_COMPARISION_FIRSTNAME>";
										$strCompXML1 .="<TOP_COMPARISION_FIMG><![CDATA[$fm_img]]></TOP_COMPARISION_FIMG>";
										$strCompXML1 .="<TOP_COMPARISION_SECONDNAME><![CDATA[$sp_dispname]]></TOP_COMPARISION_SECONDNAME>";
										$strCompXML1 .="<TOP_COMPARISION_SIMG><![CDATA[$sm_img]]></TOP_COMPARISION_SIMG>";
										$strCompXML1 .="</TOP_COMPARISIONS>";
										$i++;
										$mod_name[] = $m_name;
									}
								}
							}
						}
					}
					//print_r($arrThreecompareCarsUrl); die();
				}
			}
			if(is_array($arrThreecompareCarsUrl)){ $ThreecompareCarsUrl = implode("-Vs-",$arrThreecompareCarsUrl);}
			$strCompXML1 .="<TOP_THREE_COMPARISION_URL><![CDATA[".WEB_URL.$cat_path."/".SEO_COMPARE_URL."/".$ThreecompareCarsUrl."]]></TOP_THREE_COMPARISION_URL>";
			$strCompXML1 .="<COUNT>$i</COUNT>";
			$strCompXML1 .="</TOP_COMPARE_SET_MASTER>";
			//die();
			//header('Content-type: text/xml');echo $strCompXML1;exit;
			$fp = fopen($file_path_name,"w+");
			fwrite($fp,$strCompXML1);
			fclose($fp);
		}
		return  $strCompXML1;
	}
*/

function topSearchComparisons($keywords,$keywords2,$type="model",$cat_path="",$get_compare_url=false, $cnt="3"){

		$file_path_name = BASEPATH."searchxml/".str_replace(array(" ","-"),"_",strtolower($keywords))."_".$type.".xml";
		$file_mod_time = filemtime($file_path_name);
		$curr_date_time = strtotime("now");
		$file_diff_time = $curr_date_time - $file_mod_time;
			
		if(file_exists($file_path_name) && $file_diff_time < 86400){
			//echo "IN";
			$strCompXML1 = file_get_contents($file_path_name);
		}else{
			//echo "OUT";
			$search_api = "http://www.google.co.in/complete/search?q=".urlencode($keywords)."+vs&output=toolbar";
			$response = file_get_contents($search_api);
			#header('Content-type: text/xml');echo $response; exit;
			$i=0; $fflag=0;$iround=0; $arrThreecompareCarsUrl = array();
			$strCompXML1 ="<TOP_COMPARE_SET_MASTER>";
			if(!empty($response)){
				$xml = simplexml_load_string($response); //die("no data found");
				//print_r($xml); //die;
				if(!empty($xml)){
					$suggestion_counter = 0; // component count
					foreach($xml->children() as $xml_CompleteSuggestion){
						
						#echo " <br/> 1 -- $cnt == $iround ";
						#if($cnt==$iround) { break; }
						if($suggestion_counter == $cnt) break; // component count
						foreach($xml_CompleteSuggestion->children() as $xml_suggestion => $suggestion){

							if($suggestion_counter == $cnt) break; // component count
							#echo "<br/> 2 -- $cnt == $iround ";
							#if($cnt==$iround) { break; }
							$i++;
							$data = $suggestion['data'];
							//echo $data."<br>";
							if(!empty($data)){
								$parse_data = explode("vs",$data);
								$first_data = trim($parse_data[0]);
								$product_name = trim($parse_data[1]);
								unset($name); unset($urldata);                                                                
								if(!empty($first_data)){
									$arrvalidate_fdata =$this->validateProductData($keywords2,$type);
									$name[] = $arrvalidate_fdata['compare_name'];
									$urldata[] = $arrvalidate_fdata['compare_url'];
									if($iround == 0 && empty($arrThreecompareCarsUrl[0])){
										$arrThreecompareCarsUrl[] = $arrvalidate_fdata['compare_url'];
									}
									$fm_name = $arrvalidate_fdata['model_name'];
									$fv_name = $arrvalidate_fdata['variant_name'];
									$fp_dispname = $arrvalidate_fdata['product_disp_name'];
									$fm_img = $arrvalidate_fdata['model_image'];
									$fflag++;
								}                                                                
                                                                
								if(!empty($product_name)){
									$arrsvalidate_data =$this->validateProductData($product_name,$type);
									$name[] = $arrsvalidate_data['compare_name'];
									$urldata[] = $arrsvalidate_data['compare_url']; 
									if($iround < 2 && $arrsvalidate_data['compare_name']!=''){
										$arrThreecompareCarsUrl[] = $arrsvalidate_data['compare_url'];
                                                                                $iround++;
									}
									$sm_name = $arrsvalidate_data['model_name'];
									$m_name = $arrsvalidate_data['model_name'];
									$sp_dispname = $arrsvalidate_data['product_disp_name'];
									$sv_name = $arrsvalidate_data['variant_name'];
									$sm_img = $arrsvalidate_data['model_image'];
									if(count($arrsvalidate_data)>0){
										$suggestion_counter++; // if product name exist in our system count in component widget
									}
								}
                                                                //echo "<pre>"; print_r($arrThreecompareCarsUrl);
                                                                if($get_compare_url === true){
                                                                    continue;
                                                                }
								//echo $m_name."NAME<br>" ;
								unset($arrcompareCarsUrl);
								$arrcompareCarsUrl[] = SEO_WEB_URL;
								$arrcompareCarsUrl[] = $cat_path;
								$arrcompareCarsUrl[] = SEO_COMPARE_URL;
								if(!empty($urldata)){
									$arrcompareCarsUrl[] = implode("-Vs-",$urldata);
									
								}
								if(!empty($name)){
									$compare_name= implode(" V/s ",$name);
								}
								$compareCarsUrl = implode("/",$arrcompareCarsUrl);
								if(!empty($arrsvalidate_data)){
									if(!in_array($m_name,$mod_name)){
										$strCompXML1 .="<TOP_COMPARISIONS>";
										$strCompXML1 .="<TOP_COMPARISION_NAME><![CDATA[$compare_name]]></TOP_COMPARISION_NAME>";
										$strCompXML1 .="<TOP_COMPARISION_URL><![CDATA[$compareCarsUrl]]></TOP_COMPARISION_URL>";
										$strCompXML1 .="<TOP_COMPARISION_FIRSTNAME><![CDATA[$fp_dispname]]></TOP_COMPARISION_FIRSTNAME>";
										$strCompXML1 .="<TOP_COMPARISION_FIMG><![CDATA[$fm_img]]></TOP_COMPARISION_FIMG>";
										$strCompXML1 .="<TOP_COMPARISION_SECONDNAME><![CDATA[$sp_dispname]]></TOP_COMPARISION_SECONDNAME>";
										$strCompXML1 .="<TOP_COMPARISION_SIMG><![CDATA[$sm_img]]></TOP_COMPARISION_SIMG>";
										$strCompXML1 .="</TOP_COMPARISIONS>";
										$i++;
										$mod_name[] = $m_name;
									}
								}
							}
						}
					}
					//print_r($arrThreecompareCarsUrl); die();
				}
			}
			if(is_array($arrThreecompareCarsUrl)){ $ThreecompareCarsUrl = implode("-Vs-",$arrThreecompareCarsUrl);}
			$strCompXML1 .="<TOP_THREE_COMPARISION_URL><![CDATA[".WEB_URL.$cat_path."/".SEO_COMPARE_URL."/".$ThreecompareCarsUrl."]]></TOP_THREE_COMPARISION_URL>";
			$strCompXML1 .="<COUNT>$i</COUNT>";
			$strCompXML1 .="</TOP_COMPARE_SET_MASTER>";
			//die();
			//header('Content-type: text/xml');echo $strCompXML1;exit;
			$fp = fopen($file_path_name,"w+");
			fwrite($fp,$strCompXML1);
			fclose($fp);
		}
               	$file_path_name = BASEPATH."comparelink/".str_replace(array(" ","-"),"_",strtolower($keywords)).".txt";
               	$file_mod_time = filemtime($file_path_name);
               	$curr_date_time = strtotime("now");
               	$file_diff_time = $curr_date_time - $file_mod_time;
               	if(file_exists($file_path_name) && $file_diff_time < 86400){

			$compare_url = file_get_contents($file_path_name);
		} else {
			$compare_url = WEB_URL.$cat_path."/".SEO_COMPARE_URL."/".$ThreecompareCarsUrl;
			$fp = fopen($file_path_name,"w+");
                       	fwrite($fp,$compare_url);
                       	fclose($fp);
		}
		if($get_compare_url === true){
			return $compare_url;
		}
		return  $strCompXML1;
	}

	function getDateRangeForPriceVariant($iCityId,$iProductId,$iCategoryId){

	$key = $this->productKey."getDateRangeForPriceVariant_$iVariantId_$iCityId_$iProductId_$iCategoryId";
	if($result = $this->cache->get($key)){return $result;}
	$sql = "Select * from PRICE_VARIANT_VALUE_DATE_RANGE where category_id=$iCategoryId AND product_id=$iProductId AND city_id=$iCityId ORDER BY variant_id ASC";
	//die($sql);
	$result = $this->select($sql);
	$this->cache->set($key,$result);
	return $result;

}



	function getFeaturedCarsData($category_id,$oBrand,$aModuleImageResize){
		$result = $this->arrGetProductFeaturedDetailsCnt("","",$category_id);
		$total = $result[0]['cnt'];
		unset($result);
		$result = $this->arrGetProductFeaturedDetails("","",$category_id,"",'1',$start,$cnt);
		$mcount = count($result);
		$xml .= "<FEATURED_CARS>";
		for($i=0;$i<$mcount;$i++){
			$brand_id = $result[$i]['brand_id'];
			$product_name_id = $result[$i]['product_info_id'];
			$product_id = $result[$i]['product_id'];
			$position = $result[$i]['position'];
			$status = $result[$i]['status'];
			$categoryid = $result[$i]['category_id'];
			if(!empty($brand_id)){
			        $brand_result = $oBrand->arrGetBrandDetails($brand_id);
			        $brand_name = $brand_result[0]['brand_name'];
			}
			if(!empty($product_name_id)){
			        $productNameInfo = $this->arrGetProductNameInfo($product_name_id,$category_id,"","",1,"","");
			        $model_name = $productNameInfo[0]['product_info_name'];
			        $image_id = $productNameInfo[0]["img_media_id"];
			        $image_path = $productNameInfo[0]["image_path"];
			}
			unset($variantUrlYear);
			if(!empty($product_id)){
			        //$aProductDetail = $this->arrGetProductDetails($product_id,$category_id,"","1");
				if(!empty($city_id)){
			        	$aProductDetail = $this->arrGetProductDetails($product_id,$category_id,"","1","","","1","","","","","",$city_id);
				}
			        $pcnt = sizeof($aProductDetail);
			        if($pcnt==0){
			                 $aProductDetail = $this->arrGetProductDetails($product_id,$category_id,"","1");
			        }
			        $variant = $aProductDetail[0]['variant'];
			        #$image_id = $aProductDetail[0]["img_media_id"];
			        #$image_path = $aProductDetail[0]["image_path"];
			        $price = $aProductDetail[0]['variant_value'];
			        $variantUrlYear = buildYear($aProductDetail[0]['arrival_date'],$aProductDetail[0]['discontinue_date']);
			}
			if(!empty($product_id)){
			        $result[$i]["alt_product_name"] = $brand_name."-".$model_name."-".$variant;
			        $result[$i]["product_name"] = getTruncatedString($brand_name."-".$model_name."-".$variant,26);
			        $result[$i]["alt_product_name"] = str_replace('-',' ',$result[$i]["alt_product_name"]);
			        $result[$i]["product_name"] = str_replace('-',' ',$result[$i]["product_name"]);
			        $aproduct_disp_name[] = $brand_name;
			        $aproduct_disp_name[] = $model_name;
			        $aproduct_disp_name[] = $variant;
			        $result[$i]["product_disp_name"]= implode(" ",$aproduct_disp_name);
			        unset($aproduct_disp_name);
			        $result[$i]['price'] = $price ? priceFormat($price) : '';
			        unset($ModelOnRoadPriceSeoArr);
			        $on_road_price_seo_url="";
			        $ModelOnRoadPriceSeoArr[] = SEO_WEB_URL;
			        $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($brand_name));
			        $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($model_name));
			        $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($variant));
			        if(!empty($variantUrlYear)){
			              $ModelOnRoadPriceSeoArr[] = $variantUrlYear;
			        }
			        $ModelOnRoadPriceSeoArr[] = SEO_GET_ON_ROAD_PRICE;
			        $on_road_price_seo_url = implode("/",$ModelOnRoadPriceSeoArr);
			        $result[$i]['on_road_price_seo_url'] = $on_road_price_seo_url;
			}else{
				 //echo "sdfsdfs";
			        $result[$i]["alt_product_name"] = $brand_name."-".$model_name;
			        $result[$i]["product_name"] = getTruncatedString($brand_name."-".$model_name,26);
			        $result[$i]["alt_product_name"] = str_replace('-',' ',$result[$i]["alt_product_name"]);
			        $result[$i]["product_name"] = str_replace('-',' ',$result[$i]["product_name"]);
			        $aproduct_disp_name[] = $brand_name;
			        $aproduct_disp_name[] = $model_name;
			        $result[$i]["product_disp_name"]= implode(" ",$aproduct_disp_name);
			        unset($aproduct_disp_name);
			        unset($prores);
			        //$prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","","","1","",$model_name);
			        #$prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","","","","",$model_name,$city_id);
				if(!empty($model_name)){
				        if($city_id!=''){
				                $prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","0","1","","order by PRICE_VARIANT_VALUES.variant_value asc",$model_name,$city_id,"",'',"1");
				        }
				        $prores_cnt = sizeof($prores);
			        	if($prores_cnt==0){
			                	#$prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","","","1","",$model_name);
				                $prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","0","1","1","order by PRICE_VARIANT_VALUES.variant_value asc",$model_name,"","",'',"1");
				                $prores_cnt = sizeof($prores);
			        	}
				}
			        unset($aPriceRange);
			        $arr_cnt = 0;
			        for($j=0;$j<$prores_cnt;$j++){
			                $sExShowRoomPrice=$prores[$j]['variant_value'];
			                $aPriceRange[$arr_cnt]['price']=$sExShowRoomPrice;
			                $aPriceRange[$arr_cnt]['product_id']=$prores[$j]['product_id'];
			                $aPriceRange[$arr_cnt]['variant']=$prores[$j]['variant'];
			                $aPriceRange[$arr_cnt]['arrival_date']=$prores[$j]['arrival_date'];
			                $aPriceRange[$arr_cnt]['discontinue_date']=$prores[$j]['discontinue_date'];
			                $arr_cnt++;
			        }
			        $sortArray = array();
				foreach($aPriceRange as $price){
			                foreach($price as $key=>$value){
			                        if(!isset($sortArray[$key])){
			                                $sortArray[$key] = array();
			                        }
			                        $sortArray[$key][] = $value;
			                }
			        }
			        $orderby = "price";
			        array_multisort($sortArray[$orderby],SORT_ASC,$aPriceRange);
			        $lowPrice=$aPriceRange[0]['price'];

			        if(count($aPriceRange)>1){
			                $highPrice=$aPriceRange[count($aPriceRange)-1]['price'];
			        }
			        $lowprice_product_id = $aPriceRange[0]['product_id'];
			        $lowprice_variant_name = $aPriceRange[0]['variant'];
			        $variantUrlYear = buildYear($aPriceRange[0]['arrival_date'],$aPriceRange[0]['discontinue_date']);
			        $result[$i]['price'] = $lowPrice ? priceFormat($lowPrice) : '';

			        unset($ModelOnRoadPriceSeoArr);
			        $on_road_price_seo_url="";
			        $ModelOnRoadPriceSeoArr[] = SEO_WEB_URL;
			        $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($brand_name));
			        $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($model_name));
			        $ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($lowprice_variant_name));
			        if(!empty($variantUrlYear)){
			              $ModelOnRoadPriceSeoArr[] = $variantUrlYear;
			        }
			        $ModelOnRoadPriceSeoArr[] = SEO_GET_ON_ROAD_PRICE;
			        $on_road_price_seo_url = implode("/",$ModelOnRoadPriceSeoArr);
			        $result[$i]['on_road_price_seo_url'] = $on_road_price_seo_url;
			}
			if(!empty($image_path)){
		        	 $image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize,$image_id);
			}
			$result[$i]["image_path"] = $image_path ? CENTRAL_IMAGE_URL.$image_path : '';
			if(empty($product_id)){
			        unset($ModelvariantnameSeoArr);
			        $seo_url="";
			        $ModelvariantnameSeoArr[] = SEO_WEB_URL;
			        $ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
			        $ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
			        $seo_url=implode("/",$ModelvariantnameSeoArr);
			}else{
			        unset($variantnameSeoArr);
			        $seo_url="";
			        $variantnameSeoArr[] = SEO_WEB_URL;
			        $variantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
			        $variantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
			        $variantnameSeoArr[] = seo_title_replace(constructUrl($variant));
			        if(!empty($variantUrlYear)){
			              $variantnameSeoArr[] = $variantUrlYear;
			        }
			        $seo_url=implode("/",$variantnameSeoArr);
			}

			$result[$i]['seo_url']=$seo_url;

			$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
			$xml .= "<FEATURED_CARS_DATA>";
				foreach($result[$i] as $k=>$v){
					$xml .= "<$k><![CDATA[$v]]></$k>";
				}
			$xml .= "</FEATURED_CARS_DATA>";

			}
			$xml .= "</FEATURED_CARS>";
			return $xml;

	}


	function getNewLaunchCarsData($category_id,$oBrand,$aModuleImageResize)	{
		$result = $this->arrGetRecentLaunchedProductDetailsCnt("","","","",$category_id);
		$total = $result[0]['cnt'];
		unset($result);
		$result = $this->arrGetRecentLaunchedProductDetails("","","","",$category_id,"","1",$start,$cnt,$orderby="order by position asc");
		$mcount = count($result);
		$xml .= "<NEWLAUNCH_CARS>";
		for($i=0;$i<$mcount;$i++){
			$product_id=''; $variant=''; $price=''; unset($aProductDetail);
			$brand_id = $result[$i]['brand_id'];
			$product_name_id = $result[$i]['product_name_id'];
			$product_id = $result[$i]['product_id'];
			$position = $result[$i]['position'];
			$status = $result[$i]['status'];
			$categoryid = $result[$i]['category_id'];
			if(!empty($brand_id)){
				$brand_result = $oBrand->arrGetBrandDetails($brand_id);
				$brand_name = $brand_result[0]['brand_name'];
			}
			if(!empty($product_name_id)){
				$productNameInfo = $this->arrGetProductNameInfo($product_name_id,$category_id,"","",1,"","");
				$model_name = $productNameInfo[0]['product_info_name'];
				$image_id = $productNameInfo[0]["img_media_id"];
				$image_path = $productNameInfo[0]["image_path"];
			}
			if(!empty($product_id)){
				if($product_id!=0){
				if(!empty($city_id)){
					$aProductDetail = $this->arrGetProductDetails($product_id,$category_id,"","1","","","1","","","","","",$city_id);
				}
				$pcnt = sizeof($aProductDetail);
				if($pcnt == 0){
					$aProductDetail = $this->arrGetProductDetails($product_id,$category_id,"","1");
				}
				}
				$variant = $aProductDetail[0]['variant'];
				#$image_id = $aProductDetail[0]["img_media_id"];
				#$image_path = $aProductDetail[0]["image_path"];
				$price = $aProductDetail[0]['variant_value'];
				$variantUrlYear = buildYear($aProductDetail[0]['arrival_date'],$aProductDetail[0]['discontinue_date']);
			}
			if(!empty($product_id)){
				$result[$i]["alt_product_name"] = $brand_name."-".$model_name."-".$variant;
				$result[$i]["product_name"] = getTruncatedString($brand_name."-".$model_name."-".$variant,26);
				$result[$i]["alt_product_name"] = str_replace('-',' ',$result[$i]["alt_product_name"]);
				$result[$i]["product_name"] = str_replace('-',' ',$result[$i]["product_name"]);
				$aproduct_dispname[] = $brand_name;
				$aproduct_dispname[] = $model_name;
				$aproduct_dispname[] = $variant;
				$result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
				unset($aproduct_dispname);
				$result[$i]['price'] = $price ? priceFormat($price) : '';
				unset($ModelOnRoadPriceSeoArr);
				$on_road_price_seo_url="";
				$ModelOnRoadPriceSeoArr[] = SEO_WEB_URL;
				$ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($brand_name));
				$ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($model_name));
				$ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($variant));
				if(!empty($variantUrlYear)){
					$ModelOnRoadPriceSeoArr[] = $variantUrlYear;
				}
				$ModelOnRoadPriceSeoArr[] = SEO_GET_ON_ROAD_PRICE;
				$on_road_price_seo_url = implode("/",$ModelOnRoadPriceSeoArr);
				$result[$i]['on_road_price_seo_url'] = $on_road_price_seo_url;
			}else{
				$result[$i]["alt_product_name"] = $brand_name."-".$model_name;
				$result[$i]["product_name"] = getTruncatedString($brand_name."-".$model_name,26);
				$result[$i]["alt_product_name"] = str_replace('-',' ',$result[$i]["alt_product_name"]);
				$result[$i]["product_name"] = str_replace('-',' ',$result[$i]["product_name"]);
				$aproduct_dispname[] = $brand_name;
				$aproduct_dispname[] = $model_name;
				$result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
				unset($aproduct_dispname);
				unset($prores);
				if(!empty($model_name)){
					if(!empty($city_id)){
						$prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","","","","",$model_name,$city_id);
					}
					$prores_cnt = sizeof($prores);
					if($prores_cnt ==0){
						$prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","","","1","",$model_name);
						$prores_cnt = sizeof($prores);
					}
				}
				unset($aPriceRange);
				$arr_cnt = 0;
				for($j=0;$j<$prores_cnt;$j++){
					$sExShowRoomPrice=$prores[$j]['variant_value'];
					$aPriceRange[$arr_cnt]['price']=$sExShowRoomPrice;
					$aPriceRange[$arr_cnt]['product_id']=$prores[$j]['product_id'];
					$aPriceRange[$arr_cnt]['variant']=$prores[$j]['variant'];
					$aPriceRange[$arr_cnt]['discontinue_date']=$prores[$j]['discontinue_date'];
					$aPriceRange[$arr_cnt]['arrival_date']=$prores[$j]['arrival_date'];
					$arr_cnt++;
				}
				$sortArray = array();

				foreach($aPriceRange as $price){
					foreach($price as $key=>$value){
						if(!isset($sortArray[$key])){
							$sortArray[$key] = array();
						}
						$sortArray[$key][] = $value;
					}
				}
				$orderby = "price";
				array_multisort($sortArray[$orderby],SORT_ASC,$aPriceRange);

				$lowPrice=$aPriceRange[0]['price'];

				if(count($aPriceRange)>=1){
					$highPrice=$aPriceRange[count($aPriceRange)-1]['price'];
					$lowprice_product_id = $aPriceRange[0]['product_id'];
					$lowprice_variant_name = $aPriceRange[0]['variant'];
					unset($variantUrlYear);
					$variantUrlYear = buildYear($aPriceRange[0]['arrival_date'],$aPriceRange[0]['discontinue_date']);
					$result[$i]['price'] = $lowPrice ? priceFormat($lowPrice) : '';
					unset($ModelOnRoadPriceSeoArr);
					$on_road_price_seo_url="";
					$ModelOnRoadPriceSeoArr[] = SEO_WEB_URL;
					$ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($brand_name));
					$ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($model_name));
					$ModelOnRoadPriceSeoArr[] = seo_title_replace(constructUrl($lowprice_variant_name));
					if(!empty($variantUrlYear)){
						$ModelOnRoadPriceSeoArr[] = $variantUrlYear;
					}
					$ModelOnRoadPriceSeoArr[] = SEO_GET_ON_ROAD_PRICE;
					$on_road_price_seo_url = implode("/",$ModelOnRoadPriceSeoArr);
					$result[$i]['on_road_price_seo_url'] = $on_road_price_seo_url;
				}
			}
			if(!empty($image_path)){
				$image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize,$image_id);
			}
			$result[$i]["image_path"] = $image_path ? CENTRAL_IMAGE_URL.$image_path : '';
			if(empty($product_id)){
				unset($ModelvariantnameSeoArr);
				$seo_url="";
				$ModelvariantnameSeoArr[] = SEO_WEB_URL;
				$ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
				$ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
				$seo_url=implode("/",$ModelvariantnameSeoArr);
			}else{
				unset($variantnameSeoArr);
				$seo_url="";
				$variantnameSeoArr[] = SEO_WEB_URL;
				$variantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
				$variantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
				$variantnameSeoArr[] = seo_title_replace(constructUrl($variant));
				if(!empty($variantUrlYear)){
					$variantnameSeoArr[] = $variantUrlYear;
				}
				$seo_url=implode("/",$variantnameSeoArr);
			}
			$result[$i]['seo_url']=$seo_url;
			$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
			$xml .= "<NEWLAUNCH_CARS_DATA>";
			foreach($result[$i] as $k=>$v){
				$xml .= "<$k><![CDATA[$v]]></$k>";
			}
			$xml .= "</NEWLAUNCH_CARS_DATA>";
		}
		$xml .= "</NEWLAUNCH_CARS>";
		return $xml;
	}


	function getUpcomingCarsData($category_id,$oBrand,$aModuleImageResize,$selected_brand_id){
	$result = $this->arrSearchUpComingProductDetailsCnt("","",$selected_brand_id,$selected_feature_id,"","",$category_id,$selected_duration,'1');
	$total = $result[0]['cnt'];
	if(!empty($total)){
		unset($result);
		$upcommming_result = $this->arrSearchUpComingProductDetails("","",$selected_brand_id,$selected_feature_id,"","",$category_id,$selected_duration,'1',$start,$cnt,"ORDER BY start_date ASC");
		// for end date sorting
		if(is_array($upcommming_result)){
			foreach($upcommming_result as $k=>$v){
				$start_year = date('Y',strtotime($v['start_date']));
				$start_month = date('n',strtotime($v['start_date']));
				$ts_end_month = strtotime($v['end_date']);
				$arr_monthly_result[$start_year][$start_month][$ts_end_month] = $v;
			}
		}
		foreach($arr_monthly_result as $mrk=>$mrv){
			foreach($mrv as $mrvk=>$mrvv){
				ksort($mrvv);
				$arr_sort_result[] = $mrvv;
			}
		}
		foreach($arr_sort_result as $srk=>$srv){
			foreach($srv as $inner_srv){
				$sort_result[] = $inner_srv;
			}
		}
		$result = $sort_result;
		$cnt = sizeof($upcommming_result);
		$xml .= "<UPCOMING>";
		for($i=0;$i<$cnt;$i++){
			$upcoming_product_id = $result[$i]['upcoming_product_id'];
			$product_name_id = $result[$i]['product_name_id'];
			$feature_id = $result[$i]['feature_id'];
			$min_expected_price = $result[$i]['min_expected_price'];
			$min_expected_price_unit = $result[$i]['min_expected_price_unit'];
			$max_expected_price = $result[$i]['max_expected_price'];
			$max_expected_price_unit = $result[$i]['max_expected_price_unit'];

			$amin_expected_price = explode(".",$min_expected_price);
			if($amin_expected_price[1]== '00' ){
				$min_expected_price = round($min_expected_price);
			}
			$amax_expected_price = explode(".",$max_expected_price);
			if($amax_expected_price[1]== '00' ){
				$max_expected_price = round($max_expected_price);
			}
			if($min_expected_price_unit == "100000"){
				$min_price_unit = "Lakh";
			}elseif($min_expected_price_unit == "10000000"){
				$min_price_unit = "Crore";
			}
			if($max_expected_price_unit == "100000"){
				$max_price_unit = "Lakh";
			}elseif($max_expected_price_unit == "10000000"){
				$max_price_unit = "Crore";
			}
			if($min_expected_price_unit == $max_expected_price_unit){
				$expected_price = $min_expected_price."-".$max_expected_price." ".$min_price_unit;
			}else{
				$expected_price = $min_expected_price." ".$min_price_unit."-".$max_expected_price." ".$max_price_unit;
			}
			if(($min_expected_price == '') && ($max_expected_price == '')){
				$expected_price = "";
			}
			$result[$i]['expected_price'] = $expected_price;
			//$expected_price = $result[$i]['expected_price'] ? priceFormat($result[$i]['expected_price']) : "";
			$expected_price = $result[$i]['expected_price'];
			$result[$i]['expected_price'] = $expected_price;
			$expected_date_text = $result[$i]['expected_date_text'];
			if(!empty($product_name_id)){
				$productNameInfo = $this->arrGetProductNameInfo($product_name_id,$category_id,"","",1,"","","","","","","","1");
				$model_name = $productNameInfo[0]['product_info_name'];
				$brand_id = $productNameInfo[0]['brand_id'];
				if(!empty($brand_id)){
					$brand_result = $oBrand->arrGetBrandDetails($brand_id,"","1","","","","","","");
					$brand_name = $brand_result[0]['brand_name'];
				}
				$product_name = $brand_name." ".$model_name;
				$aproduct_dispname[] = $brand_name;
				$aproduct_dispname[] = $model_name;
				$result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
				unset($aproduct_dispname);
				$image_id = $productNameInfo[0]["img_media_id"];
				$image_path = $productNameInfo[0]["image_path"];
			}
			unset($aproduct_dispname);
			$result[$i]['alt_product_name'] = $product_name;
			$result[$i]["product_name"] = getTruncatedString($product_name,26);
			$result[$i]["alt_product_name"] = str_replace('-',' ',$product_name);
			$result[$i]["product_name"] = str_replace('-',' ',$result[$i]["product_name"]);
			if(!empty($image_path)){
				$image_path = resizeImagePath($image_path,"160X120",$aModuleImageResize,$image_id);
			}
			$result[$i]["image_path"] = $image_path ? CENTRAL_IMAGE_URL.$image_path : '';
			unset($ModelvariantnameSeoArr);
			$seo_url="";
			$ModelvariantnameSeoArr[] = SEO_WEB_URL;
			$ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
			$ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
			$seo_url=implode("/",$ModelvariantnameSeoArr);
			$result[$i]['seo_url']=$seo_url;

			$result[$i]['seo_url']=$seo_url;
			$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
			$xml .= "<UPCOMING_DATA>";
			foreach($result[$i] as $k=>$v){
				$xml .= "<$k><![CDATA[$v]]></$k>";
			}
			$xml .= "</UPCOMING_DATA>";
		}
		$xml .= "</UPCOMING>";
		return $xml;
	}
}
	public function modelTopCompetitor($brand_id,$product_info_id,$variant_id,$type,$skip,$skipsamebrand,$skipsamemodel,$skipsamevariant,$perpage,$total='',$oBrand,$sTag='COMPETITOR_PRODUCT_DETAIL'){

		if(empty($total)){
			$result = $this->arrGetProdCompetitorDetailsCnt("",$variant_id,$product_info_id,$brand_id,"1","1",$skipsamevariant,$skipsamemodel,$skipsamebrand);
			$total = $result[0]['cnt'];
		}
		unset($result);
		$dataArr = Array('price'=>'showprice','on_road_price_seo_url'=>'onroadurl','image_path'=>'imgpath',
		'seo_url'=>'url','alt_product_name'=>'alt_pname','product_name'=>'pname',
		'expected_date_text'=>'exptectedby','expected_price'=>'expectedprice','product_ids'=>'pid',
		'model_seo_url'=>'modellink','alt_model_name'=>'alt_mname','model_name'=>'mname',
		'rangeprice'=>'rangep','smallimg'=>'smallimg','comparename'=>'comparename');
		if($total > 0){
			$result = $this->arrGetProdCompetitorDetails("",$variant_id,$product_info_id,$brand_id,"1","1",$skip,$perpage,$skipsamevariant,$skipsamemodel,$skipsamebrand);
		}
		$count = count($result);
		$compareIdsArr = Array();
		for($i=0;$i<$count;$i++){
		$brand_id = $result[$i]['brand_ids'];
		$product_name_id = $result[$i]['product_info_ids'];
		$product_id = $result[$i]['product_ids'];
		#array_push($compareIdsArr,$product_id);
		$position = $result[$i]['position'];
		$status = $result[$i]['status'];
		$categoryid = $result[$i]['category_id'];
		if(!empty($brand_id)){
		    $brand_result = $oBrand->arrGetBrandDetails($brand_id);
		    $brand_name = $brand_result[0]['brand_name'];
		    $brand_seo_path = $brand_result[0]['seo_path'];
		}
		unset($model_image_path);unset($image_path);
		if(!empty($product_name_id)){
		        $productNameInfo = $this->arrGetProductNameInfo($product_name_id,$category_id,"","",1,"","");
		        $model_name = $productNameInfo[0]['product_info_name'];
		        $model_seo_path = $productNameInfo[0]['model_seo_path'];
		        $image_id = $productNameInfo[0]["img_media_id"];
		        $model_image_path = $productNameInfo[0]["image_path"];
		}
		unset($variantUrlYear);
		if(!empty($product_id)){
		       # $aProductDetail = $oProduct->arrGetProductDetails($product_id,$category_id,"","1","","1","","","1","","","","","","","1");
		    $aProductDetail = $this->arrGetProductDetails($product_id,$category_id,"",'1',"","","1","","","1","","","","",'',"1");
		    $variant = $aProductDetail[0]['variant'];
		    $variant_seo_path = $aProductDetail[0]['seo_path'];
		    unset($variantUrlYear);
		    $variantUrlYear = buildYear($aProductDetail[0]['arrival_date'],$aProductDetail[0]['discontinue_date']);
		    $image_id = $aProductDetail[0]["img_media_id"];
		    $image_path = $aProductDetail[0]["image_path"];
		    $price = $aProductDetail[0]['variant_value'];
		}

		$image_path = !empty($model_image_path) ? $model_image_path : $image_path;
		if($type == 'model'){
		        unset($variantUrlYear);
		        $result[$i]["alt_product_name"] = $brand_name." ".$model_name;
		        $result[$i]["product_name"] = getTruncatedString($brand_name." ".$model_name,26);
		        $aproduct_dispname[] = $brand_name;
		        $aproduct_dispname[] = $model_name;
		        $result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
		        unset($aproduct_dispname);
		        unset($prores);
		        $prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","","","1","",$model_name,"","","","1");

		        $prores_cnt = sizeof($prores);
		        unset($aPriceRange);
		        $arr_cnt = 0;
		        for($j=0;$j<$prores_cnt;$j++){
		                $sExShowRoomPrice=$prores[$j]['variant_value'];
		                $aPriceRange[$arr_cnt]['price']=$sExShowRoomPrice;
		                $aPriceRange[$arr_cnt]['product_id']=$prores[$j]['product_id'];
		                $aPriceRange[$arr_cnt]['variant']=$prores[$j]['variant'];
		                $aPriceRange[$arr_cnt]['arrival_date']=$prores[$j]['arrival_date'];
		                $aPriceRange[$arr_cnt]['discontinue_date']=$prores[$j]['discontinue_date'];
		                $arr_cnt++;
		        }

		        $sortArray = array();
		        foreach($aPriceRange as $price){
		                foreach($price as $key=>$value){
		                        if(!isset($sortArray[$key])){
		                                $sortArray[$key] = array();
		                        }
		                        $sortArray[$key][] = $value;
		                }
		        }
		        $orderby = "price";
		        array_multisort($sortArray[$orderby],SORT_ASC,$aPriceRange);
		        $lowPrice=$aPriceRange[0]['price'];
		        $variantUrlYear = buildYear($aPriceRange[0]['arrival_date'],$aPriceRange[0]['discontinue_date']);
		        if(count($aPriceRange)>1){
		            $highPrice=$aPriceRange[count($aPriceRange)-1]['price'];
		        }
		        $lowprice_product_id = $aPriceRange[0]['product_id'];
		        $lowprice_variant_name = $aPriceRange[0]['variant'];
		        $lowprice_variant_seo_path = $aPriceRange[0]['seo_path'];

		        $result[$i]['price'] = $lowPrice ? priceFormat($lowPrice) : '';
		        $comparename = $brand_seo_path.'-'.$model_seo_path.'-'.$lowprice_variant_seo_path;
		        $result[$i]['comparename'] = $comparename;
		        unset($rangepArr);
		        if(!empty($lowPrice)){
		        	$rangepArr[] = priceFormat($lowPrice);
		        }
		        if(!empty($highPrice)){
		        	$rangepArr[] = priceFormat($highPrice);
		        }

		        $result[$i]['rangeprice'] = implode(' - ',$rangepArr);
		}else{

		        $result[$i]["alt_product_name"] = $brand_name." ".$model_name." ".$variant;
		        if($type == 'viewonroadprice'){
		            $result[$i]["product_name"] = getTruncatedString($brand_name." ".$model_name." ".$variant,15);
		        }else{
		            $result[$i]["product_name"] = getTruncatedString($brand_name." ".$model_name." ".$variant,26);
		        }
		        $aproduct_dispname[] = $brand_name;
		        $aproduct_dispname[] = $model_name;
		        $aproduct_dispname[] = $variant;
		        $result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
		        unset($aproduct_dispname);
		        $result[$i]['price'] = $price ? priceFormat($price) : '';
		        unset($ModelOnRoadPriceSeoArr);
		        $comparename = $brand_seo_path.'-'.$model_seo_path.'-'.$variant_seo_path;
		        if(!empty($variantUrlYear)){
		        	$comparename = $comparename.'-'.$variantUrlYear;
		        }
		        $result[$i]['comparename'] = $comparename;
		}

		array_push($compareIdsArr,$comparename);
		$image_path = str_replace(array(CENTRAL_IMAGE_URL,CENTRAL_MEDIA_URL),"",$image_path);

		global $aModuleImageResize;
		if(!empty($image_path)){
		        //$smallimg = CENTRAL_IMAGE_URL.resizeImagePath($image_path,"87X65",$aModuleImageResize,$image_id);
		        $smallimg = CENTRAL_IMAGE_URL.resizeImagePath($image_path,"73X55",$aModuleImageResize,$image_id);
		        $image_path = CENTRAL_IMAGE_URL.resizeImagePath($image_path,"160X120",$aModuleImageResize,$image_id);
		}else{
		       $image_path = IMAGE_URL.'no-image.png';
		}

		$result[$i]["image_path"] = $image_path;
		$result[$i]["smallimg"] = !empty($smallimg) ? $smallimg : $image_path;
		if($type == 'model'){
		        unset($ModelvariantnameSeoArr);
		        $seo_url="";
		        $ModelvariantnameSeoArr[] = SEO_WEB_URL;
		        $ModelvariantnameSeoArr[] = $_REQUEST['cat_path'];
		        $ModelvariantnameSeoArr[] = $brand_seo_path;
		        $ModelvariantnameSeoArr[] = $model_seo_path;
		        $seo_url=implode("/",$ModelvariantnameSeoArr);
		        $alt_model_name = implode(' ',array($brand_name,$model_name));
		        $result[$i]['alt_model_name'] = $alt_model_name;
		        $result[$i]["model_name"] = getTruncatedString($alt_model_name,26);
		        $result[$i]['model_seo_url'] = $seo_url;
		}else{
		        unset($variantnameSeoArr);
		        $seo_url="";
		        $variantnameSeoArr[] = SEO_WEB_URL;
		        $variantnameSeoArr[] = $_REQUEST['cat_path'];
		        $variantnameSeoArr[] = $brand_seo_path;
		        $variantnameSeoArr[] = $model_seo_path;
		        $variantnameSeoArr[] = $variant_seo_path;
		        if(!empty($variantUrlYear)){
		            $variantnameSeoArr[] = $variantUrlYear;
		        }
		        $seo_url=implode("/",$variantnameSeoArr);
		        $result[$i]['seo_url']=$seo_url;
		}



			foreach($result[$i] as $k=>$v){
				if($dataArr[$k]){
					$jsonArr['results'][$i][$dataArr[$k]] = $v;
					$k = $dataArr[$k];
				}
			}
		}

		$aAlternateCarList = $jsonArr['results'];


		$sAlternateCarListXML.= "<$sTag>";
		$sAlternateCarListXML.= "<TOTAL><![CDATA[".$total."]]></TOTAL>";
		$sAlternateCarListXML.= arraytoxml($aAlternateCarList,$sTag.'_DATA');
		$sAlternateCarListXML.= "</$sTag>";
		return $sAlternateCarListXML;
	}

	public function fetchSameBrandCarListing($category_id,$brand_id,$model_id,$variant_id,$type,$oBrand,$total='',$sTag='',$category_name=""){


		$dataArr = Array('price'=>'showprice','on_road_price_seo_url'=>'onroadurl','image_path'=>'imgpath','seo_url'=>'url',
		'product_name'=>'pname','expected_date_text'=>'exptectedby','expected_price'=>'expectedprice','product_ids'=>'pid',
		'model_seo_url'=>'modellink','alt_model_name'=>'alt_mname','model_name'=>'mname','rangeprice'=>'rangep',
		'smallimg'=>'smallimg','comparename'=>'comparename');

		if($sTag==''){
			$sTag="OTHER_CAR_LIST";
		}

		if(empty($total)){
			#$result = $oProduct->arrGetProductNameInfoCnt("",$category_id,$brand_id,"","1",$orderby,"","","1","");
			$result = $this->arrGetProductNameInfoExcludeDiscontinuedCnt("",$category_id,$brand_id,"","1","","","","","","1",'');
			$total = $result[0]['cnt'];
		}
		#$result = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,"","1",$start,$cnt,$orderby,"","","1","");
		$result = $this->arrGetProductNameInfoExcludeDiscontinued("",$category_id,$brand_id,"","1","","","","","","1",'');

		$count = count($result);
		$compareIdsArr = Array();

		for($i=0;$i<$count;$i++){

			$brand_id = $result[$i]['brand_id'];
			$product_name_id = $result[$i]['product_name_id'];
			$model_seo_path = $result[$i]['seo_path'];
			$status = $result[$i]['status'];
			$categoryid = $result[$i]['category_id'];
			if($model_id==$product_name_id){
				$total = $total-1;
				continue;
			}

			if(!empty($brand_id)){
				$brand_result = $oBrand->arrGetBrandDetails($brand_id);
				$brand_name   = $brand_result[0]['brand_name'];
				$brand_seo_path   = $brand_result[0]['seo_path'];
			}
			$model_name 		= $result[$i]['product_info_name'];
			$image_id 			= $result[$i]["img_media_id"];
			$model_image_path 	= $result[$i]["image_path"];

			unset($image_path); unset($variantUrlYear);
			if(!empty($product_id)){
				$aProductDetail = $this->arrGetProductDetails($product_id,$category_id,"","1");
				$variant 		= $aProductDetail[0]['variant'];
				$variant_seo_path 	= $aProductDetail[0]['seo_path'];
				$image_id 		= $aProductDetail[0]["img_media_id"];
				$image_path 	= $aProductDetail[0]["image_path"];
				$price 			= $aProductDetail[0]['variant_value'];
				$variantUrlYear = buildYear($aProductDetail[0]['arrival_date'],$aProductDetail[0]['discontinue_date']);
			}

			$image_path = !empty($model_image_path) ? $model_image_path : $image_path;
			if($type == 'model'){
			    $result[$i]["product_name"] = $brand_name." ".$model_name;
			    $aproduct_dispname[] = $brand_name;
			    $aproduct_dispname[] = $model_name;
			    $result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
			    unset($aproduct_dispname);
			    unset($prores);
			    $prores = $this->arrGetProductDetails("","",$brand_id,'1',"","","1","","","1","",$model_name);
			    $prores_cnt = sizeof($prores);
			    unset($aPriceRange);
			    $arr_cnt = 0;
			    for($j=0;$j<$prores_cnt;$j++){
			        unset($variantUrlYear);
			        $sExShowRoomPrice=$prores[$j]['variant_value'];
			        $aPriceRange[$arr_cnt]['price']=$sExShowRoomPrice;
			        $aPriceRange[$arr_cnt]['product_id']=$prores[$j]['product_id'];
			        $aPriceRange[$arr_cnt]['variant']=$prores[$j]['variant'];

			        $arrival_date = $prores[$j]['arrival_date'];
			        $discontinue_date = $prores[$j]['discontinue_date'];
			        $variantUrlYear = buildYear($arrival_date,$discontinue_date);
			        $aPriceRange[$arr_cnt]['year']=$variantUrlYear;
			        $arr_cnt++;
			    }
			    $sortArray = array();
			    foreach($aPriceRange as $price){
			            foreach($price as $key=>$value){
			                    if(!isset($sortArray[$key])){
			                            $sortArray[$key] = array();
			                    }
			                    $sortArray[$key][] = $value;
			            }
			    }
			    $orderby = "price";
			    array_multisort($sortArray[$orderby],SORT_ASC,$aPriceRange);
			    $lowPrice=$aPriceRange[0]['price'];
			    if(count($aPriceRange)>1){
			        $highPrice=$aPriceRange[count($aPriceRange)-1]['price'];
			    }
			    $lowprice_product_id = $aPriceRange[0]['product_id'];
			    $lowprice_variant_name = $aPriceRange[0]['variant'];
			    $lowprice_variant_seo_path = $aPriceRange[0]['seo_path'];
			    $variantUrlYear = $aPriceRange[0]['year'];
			    $result[$i]['comparename'] = $brand_seo_path.'-'.$model_seo_path.'-'.$lowprice_variant_seo_path;
			    $result[$i]['price'] = $lowPrice ? priceFormat($lowPrice) : '';
			    unset($rangepArr);
				if(!empty($lowPrice)){
					$rangepArr[] = priceFormat($lowPrice);
				}
				if(!empty($highPrice)){
					$rangepArr[] = priceFormat($highPrice);
				}

			    $result[$i]['rangeprice'] = implode(' - ',$rangepArr);
			}else{
			    $result[$i]["product_name"] = $brand_name." ".$model_name." ".$variant;
			    $aproduct_dispname[] = $brand_name;
			    $aproduct_dispname[] = $model_name;
			    $aproduct_dispname[] = $variant;
			    $result[$i]["product_disp_name"] = implode(" ", $aproduct_dispname);
			    unset($aproduct_dispname);
			    $result[$i]['price'] = $price ? priceFormat($price) : '';
			    $result[$i]['comparename'] = $brand_seo_path.'-'.$model_seo_path.'-'.$variant_seo_path;
			}

			$image_path = str_replace(array(CENTRAL_IMAGE_URL,CENTRAL_MEDIA_URL),"",$image_path);
			global $aModuleImageResize;
			if(!empty($image_path)){
				$smallimg = CENTRAL_IMAGE_URL.resizeImagePath($image_path,"87X65",$aModuleImageResize,$image_id);
				$image_path = CENTRAL_IMAGE_URL.resizeImagePath($image_path,"160X120",$aModuleImageResize,$image_id);
			}else{
			   $image_path = IMAGE_URL.'no-image.png';
			}

			$result[$i]["image_path"] = $image_path;
			$result[$i]["smallimg"] = !empty($smallimg) ? $smallimg : $image_path;
			if($type == 'model'){
				unset($ModelvariantnameSeoArr);
				$seo_url="";
				$ModelvariantnameSeoArr[] = SEO_WEB_URL;
				$ModelvariantnameSeoArr[] = $_REQUEST['cat_path'];
                            	$ModelvariantnameSeoArr[] = $brand_seo_path;
                            	$ModelvariantnameSeoArr[] = $model_seo_path;
				$seo_url=implode("/",$ModelvariantnameSeoArr);
				$alt_model_name = implode(' ',array($brand_name,$model_name));
				$result[$i]['alt_model_name'] = $alt_model_name;
				$result[$i]["model_name"] = getTruncatedString($alt_model_name,26);
				$result[$i]['model_seo_url'] = $seo_url;
			}else{
				unset($variantnameSeoArr);
				$seo_url="";
				$variantnameSeoArr[] = SEO_WEB_URL;
				$variantnameSeoArr[] = $_REQUEST['cat_path'];
                                $variantnameSeoArr[] = $brand_seo_path;
                                $variantnameSeoArr[] = $model_seo_path;
                                $variantnameSeoArr[] = $variant_seo_path;
				if(!empty($variantUrlYear)){
				    $variantnameSeoArr[] = $variantUrlYear;
				}
				$seo_url=implode("/",$variantnameSeoArr);
				$result[$i]['seo_url']=$seo_url;
			}

			foreach($result[$i] as $k=>$v){
				if($dataArr[$k]){
					$jsonArr['results'][$i][$dataArr[$k]] = $v;
					$k = $dataArr[$k];
				}
			}
		}

		$aOtherCarList = array_values($jsonArr['results']);

		$sOtherCarList.= "<$sTag>";
		$sOtherCarList.= "<TOTAL><![CDATA[".$total."]]></TOTAL>";
		$sOtherCarList.= arraytoxml($aOtherCarList,$sTag.'_DATA');
		$sOtherCarList.= "</$sTag>";
		return $sOtherCarList;
	}

	function getUpcomingProductWidgetList($category_id,$selected_brand_id,$product_name_id,$feature_id,$price_value,$startlimit,$limit_cnt,$oBrand,$oFeature){

		$selected_feature_name  = $selected_brand_name = $page_upcoming_product_id = "";

		if($price_value != ""){
			$low_price_range = $price_value - LEAST_PRICE_RELATED_USED;
			$high_price_range = $price_value + MAX_PRICE_RELATED_USED;
		}
		if($feature_id != ""){
			unset($fres);
			$fresd = $oFeature->arrGetFeatureDetails($feature_id,$category_id);
			$selected_feature_name = $fresd[0]['feature_name'];
		}
		if($selected_brand_id != ""){
			unset($brand_result);
			$brand_result = $oBrand->arrGetBrandDetails($selected_brand_id);
			$selected_brand_name = $brand_result[0]['brand_name'];
		}
		if($product_name_id != ""){
			unset($p_res);
			$p_res = $this->arrSearchUpComingProductDetails("",$product_name_id);
			if(sizeof($p_res) > 0){
				$page_upcoming_product_id = $p_res[0]['upcoming_product_id'];
			}
		}

		/*for upcoming product start*/
		$upcoming_model_id_arr = Array();
		$final_arr = array();
		#echo "<br/> selected_brand_id = $selected_brand_id & feature_id = $feature_id & low_price_range = $low_price_range & high_price_range = $high_price_range <br/>";
		$is_brand_price_feature=0; $is_brand_feature=0; $is_brand_price=0; $is_brand=0;
		//  BRAND BASED
		if(!empty($selected_brand_id)){
			// fetch record for a given brand
			$arr_brand_result = $this->arrSearchUpComingProductDetailsByPriceRange("","",$selected_brand_id,"","","","","",$category_id,'1',$startlimit,$limit_cnt,$orderby);
			$cnt_brand_result = sizeof($arr_brand_result);
			#print_r($arr_brand_result); die;
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id 			= $val_brand['brand_id'];
					$brand_feature 				= $val_brand['feature_id'];
					$min_expected_price 		= $val_brand['min_expected_price'];
					$amin_expected_price 		= explode(".",$min_expected_price);
					$min_expected_price_unit	= $val_brand['min_expected_price_unit'];
					$max_expected_price 		= $val_brand['max_expected_price'];
					$amax_expected_price 		= explode(".",$max_expected_price);
					$max_expected_price_unit 	= $val_brand['max_expected_price_unit'];

					$car_max_price = $max_expected_price * $max_expected_price_unit;
					$car_min_price = $min_expected_price * $min_expected_price_unit;

					if(($selected_brand_id == $fetch_brand_id) && ($brand_feature == $feature_id) && (((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price))) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price))){
						if(count($final_arr) == 3){ break; }
						$final_arr[] = $val_brand;
						unset($arr_brand_result[$key_brand]);
						$is_brand_price_feature++;
					}
				}
			}
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id 			= $val_brand['brand_id'];
					$brand_feature 				= $val_brand['feature_id'];
					$min_expected_price 		= $val_brand['min_expected_price'];
					$min_expected_price_unit 	= $val_brand['min_expected_price_unit'];
					$max_expected_price 		= $val_brand['max_expected_price'];
					$max_expected_price_unit 	= $val_brand['max_expected_price_unit'];
					$car_max_price 				= $max_expected_price * $max_expected_price_unit;
					$car_min_price 				= $min_expected_price * $min_expected_price_unit;
					if(count($final_arr) < 3){
						if(($selected_brand_id == $fetch_brand_id) && ($brand_feature == $feature_id) && $car_min_price>=200000 ){
							if(count($final_arr) == 3){ break; }
							$final_arr[] = $val_brand;
							unset($arr_brand_result[$key_brand]);
							$is_brand_feature++;
						}
					}
				}

			}
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id 			= $val_brand['brand_id'];
					$min_expected_price 		= $val_brand['min_expected_price'];
					$min_expected_price_unit 	= $val_brand['min_expected_price_unit'];
					$max_expected_price 		= $val_brand['max_expected_price'];
					$max_expected_price_unit 	= $val_brand['max_expected_price_unit'];
					$car_max_price 				= $max_expected_price * $max_expected_price_unit;
					$car_min_price 				= $min_expected_price * $min_expected_price_unit;
					if(count($final_arr) < 3){
						if(($selected_brand_id == $fetch_brand_id) && (((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price))) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price))){
							if(count($final_arr) == 3){ break; }
								$final_arr[] = $val_brand;
								unset($arr_brand_result[$key_brand]);
								$is_brand_price++;
						}
					}
				}
			}
			if($cnt_brand_result > 0){
				foreach($arr_brand_result as $key_brand=>$val_brand){
					$fetch_brand_id = $val_brand['brand_id'];
					if(count($final_arr) < 3){
						if(($selected_brand_id == $fetch_brand_id)){
							if(count($final_arr) == 3){ break; }
								$final_arr[] = $val_brand;
								unset($arr_brand_result[$key_brand]);
								$is_brand++;
						}
					}
				}
			}

		}
		// BODYTYPE BASED
		$is_feature =0; $is_feature_price=0;
		if(!empty($feature_id) && count($final_arr) < 3){
			// fetch record for a given brand
			$arr_bodytype_result = $this->arrSearchUpComingProductDetailsByPriceRange("","","",$feature_id,"","","","",$category_id,'1',$startlimit,$limit_cnt,$orderby);
			$cnt_bodytype_result = sizeof($arr_bodytype_result);
			//print_r($arr_bodytype_result); die;
			if($cnt_bodytype_result > 0){
				foreach($arr_bodytype_result as $key_bodytype=>$val_bodytype){
					$brand_feature 				= $val_bodytype['feature_id'];
					$min_expected_price 		= $val_bodytype['min_expected_price'];
					$min_expected_price_unit 	= $val_bodytype['min_expected_price_unit'];
					$max_expected_price 		= $val_bodytype['max_expected_price'];
					$max_expected_price_unit 	= $val_bodytype['max_expected_price_unit'];

					$car_max_price = $max_expected_price * $max_expected_price_unit;
					$car_min_price = $min_expected_price * $min_expected_price_unit;

					if(($brand_feature == $feature_id) && (((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price))) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price))){
						if(count($final_arr) == 3){ break; }
						$final_arr[] = $val_bodytype;
						unset($arr_bodytype_result[$key_bodytype]);
						$is_feature_price++;
					}
				}
			}
			if($cnt_bodytype_result > 0){
				foreach($arr_bodytype_result as $key_bodytype=>$val_bodytype){
					$brand_feature = $val_bodytype['feature_id'];
					$min_expected_price = $val_bodytype['min_expected_price'];
					$min_expected_price_unit = $val_bodytype['min_expected_price_unit'];
					$max_expected_price = $val_bodytype['max_expected_price'];
					$max_expected_price_unit = $val_bodytype['max_expected_price_unit'];
					$car_max_price = $max_expected_price * $max_expected_price_unit;
					$car_min_price = $min_expected_price * $min_expected_price_unit;
					if($brand_feature == $feature_id && $car_min_price >=200000){
						if(count($final_arr) == 3){ break; }
						$final_arr[] = $val_bodytype;
						unset($arr_bodytype_result[$key_bodytype]);
						$is_feature++;
					}
				}
			}
		}
		// PRICE BASED
		$is_price=0;
		if(!empty($low_price_range) && !empty($high_price_range) && count($final_arr) < 3){
			// fetch record for a given brand
			$arr_price_result = $this->arrSearchUpComingProductDetailsByPriceRange("","","","","","",$low_price_range,$high_price_range,$category_id,'1',$startlimit,$limit_cnt,$orderby);
			$cnt_price_result = sizeof($arr_price_result);
			//print_r($arr_price_result); die;
			if($cnt_price_result > 0){
				foreach($arr_price_result as $key_price=>$val_price){
					$min_expected_price = $val_price['min_expected_price'];
					$min_expected_price_unit = $val_price['min_expected_price_unit'];
					$max_expected_price = $val_price['max_expected_price'];
					$max_expected_price_unit = $val_price['max_expected_price_unit'];
					$car_max_price = $max_expected_price * $max_expected_price_unit;
					$car_min_price = $min_expected_price * $min_expected_price_unit;

					//echo "<br/>car_min_price = $car_min_price < low_price_range = $low_price_range && low_price_range = $low_price_range < car_max_price = $car_max_price) || ( car_min_price = $car_min_price < high_price_range = $high_price_range && high_price_range = $high_price_range < car_max_price = $car_max_price  && (low_price_range = $low_price_range < car_min_price = $car_min_price && high_price_range = $high_price_range > car_max_price = $car_max_price)<br/>";

					if((($car_min_price < $low_price_range && $low_price_range < $car_max_price) || ($car_min_price < $high_price_range && $high_price_range < $car_max_price)) || ($low_price_range < $car_min_price && $high_price_range > $car_max_price)){
						if(count($final_arr) == 3){ break; }
						$final_arr[] = $val_price;
						unset($arr_price_result[$key_price]);
						$is_price++;
					}
				}
			}
		}
		$result_data = $final_arr;
		if(is_array($result_data)){
			foreach($result_data as $key=>$aValue){
				$upcoming_product_id = $aValue['upcoming_product_id'];
				if(!in_array($upcoming_product_id,$upcoming_product_ids)){
					$result[] = $aValue;
				}
				$upcoming_product_ids[] = $upcoming_product_id;
			}
		}
		//print_r($result);
		$result_cnt = sizeof($result);
		if($result_cnt>3){$result_cnt=3;}

		if($upcoming_landing_page==1 && $result_cnt != 0){
			$result_cnt=1;
		}
		$upcoming_cnt = $result_cnt;
		if($result_cnt > 0){
		$sUpcomingCarUrl = WEB_URL.SEO_UPCOMING_CARS.'/'.constructUrl($selected_brand_name);
		$xml = "<UPCOMING_PRODUCT_MODEL_MASTER>";
		$xml .= "<COUNT><![CDATA[$result_cnt]]></COUNT>";
		$xml .= "<UPCOMING_PAGE_URL><![CDATA[$sUpcomingCarUrl]]></UPCOMING_PAGE_URL>";
		$xml .= "<UPCOMING_PAGE_URL_TEXT><![CDATA[Browse all $selected_brand_name upcoming cars]]></UPCOMING_PAGE_URL_TEXT>";

		for($i=0;$i<$result_cnt;$i++){
				$upcoming_product_id = $result[$i]['upcoming_product_id'];
				$product_name_id = $result[$i]['product_name_id'];
				//$feature_id = $result[$i]['feature_id'];
				$min_expected_price = $result[$i]['min_expected_price'];
				$min_expected_price_unit = $result[$i]['min_expected_price_unit'];
				$max_expected_price = $result[$i]['max_expected_price'];
				$max_expected_price_unit = $result[$i]['max_expected_price_unit'];

				$amin_expected_price = explode(".",$min_expected_price);
				if($amin_expected_price[1]== '00' ){
					$min_expected_price = round($min_expected_price);
				}

				$amax_expected_price = explode(".",$max_expected_price);
				if($amax_expected_price[1]== '00' ){
					$max_expected_price = round($max_expected_price);
				}

				if($min_expected_price_unit == "100000"){
					$min_price_unit = "Lakh";
				}elseif($min_expected_price_unit == "10000000"){
					$min_price_unit = "Crore";
				}
				if($max_expected_price_unit == "100000"){
					$max_price_unit = "Lakh";
				}elseif($max_expected_price_unit == "10000000"){
					$max_price_unit = "Crore";
				}
				if($min_expected_price_unit == $max_expected_price_unit){
					$expected_price = $min_expected_price."-".$max_expected_price." ".$min_price_unit;
				}else{
					$expected_price = $min_expected_price." ".$min_price_unit."-".$max_expected_price." ".$max_price_unit;
				}
				if(($min_expected_price == '') && ($max_expected_price == '')){
					$expected_price = "";
				}
				$result[$i]['expected_price'] = $expected_price;
				$expected_date_text = $result[$i]['expected_date_text'];
				if(!empty($product_name_id)){
					$productNameInfo 	= $this->arrGetProductNameInfo($product_name_id,$category_id,"","",1,"","","","","","","","1");
					$model_name 		= $productNameInfo[0]['product_info_name'];
					$brand_id 			= $productNameInfo[0]['brand_id'];
					if(!empty($brand_id)){
						$brand_result = $oBrand->arrGetBrandDetails($brand_id);
						$brand_name = $brand_result[0]['brand_name'];
					}
					$product_name 	= $brand_name." ".$model_name;
					$image_id 		= $productNameInfo[0]["img_media_id"];
					$image_path 	= $productNameInfo[0]["image_path"];
				}

				$result[$i]['product_name'] = $product_name;
				if(!empty($image_path)){
					$image_path = resizeImagePath($image_path,"73X55",$aModuleImageResize,$image_id);
				}
				$result[$i]["image_path"] = $image_path ? CENTRAL_IMAGE_URL.$image_path : '';
				unset($ModelvariantnameSeoArr);
				$seo_url="";

				$ModelvariantnameSeoArr[] = SEO_WEB_URL;
				$ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($brand_name));
				$ModelvariantnameSeoArr[] = seo_title_replace(constructUrl($model_name));
				$seo_url=implode("/",$ModelvariantnameSeoArr);

				$result[$i]['seo_url']=$seo_url;
				$result[$i] = array_change_key_case($result[$i],CASE_UPPER);
				$xml .= "<UPCOMING_PRODUCT_MODEL_MASTER_DATA>";
					foreach($result[$i] as $k=>$v){
						$xml .= "<$k><![CDATA[$v]]></$k>";
					}
				$xml .= "</UPCOMING_PRODUCT_MODEL_MASTER_DATA>";
			}
		$xml .= "</UPCOMING_PRODUCT_MODEL_MASTER>";
	}else{
			$xml = "<UPCOMING_PRODUCT_MODEL_MASTER>";
			$xml .= "<COUNT><![CDATA[0]]></COUNT>";
			$xml .= "</UPCOMING_PRODUCT_MODEL_MASTER>";
	}
		/*for upcoming product end*/

		return $xml;

	}

		function getDistinctUsedCarCities($start,$limit){
		$keyArr[] = $this->usedproductKey."_getDistinctUsedCarCities_";
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select distinct(city_id) from USEDCAR_PRODUCT_MASTER limit $start,$limit";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function getDistinctUsedCarByBrands($start,$limit){
		$keyArr[] = $this->usedproductKey."_getDistinctUsedCarByBrands_";
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select distinct(used_brand_id) from USEDCAR_PRODUCT_MASTER where used_brand_id!=0 limit $start,$limit";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function getDistinctBrandByCity($city_id,$start,$limit){
		$keyArr[] = $this->usedproductKey."_getDistinctBrandByCity_";
		 if(!empty($city_id)){
                        $sqlStr =" P.city_id=$city_id and ";
                        $keyArr[] = $city_id;
                }else{ $keyArr[] ="-1";}
                $key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $sql = "SELECT distinct(B.brand_name) FROM USEDCAR_PRODUCT_MASTER P, USEDCAR_BRAND_MASTER B where $sqlStr B.used_brand_id=P.used_brand_id limit $start,$limit";
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                return $result;


	}
	function getDistinctUsedCarByFuelType($start,$limit,$city_id,$brand_name){
		$curr_date = date("Y-m-d");
		$keyArr[] = $this->usedproductKey."_getDistinctUsedCarByFuelType_";
		if(!empty($city_id)){
			$sqlStr =" and P.city_id=$city_id";
			$keyArr[] =$city_id;
		}else{ $keyArr[] ="-1";}
		if(!empty($brand_name)){
                        $sqlStr =" and B.brand_name='$brand_name'";
                        $keyArr[] =$brand_name;
                }else{
			$keyArr[] ="-1";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select distinct(F.feature_name) from USEDCAR_PRODUCT_MASTER P,USEDCAR_FEATURE_MASTER F,USEDCAR_PRODUCT_FEATURE PF,USEDCAR_BRAND_MASTER B where P.used_product_id=PF.product_id and F.feature_group=4 and PF.feature_id=F.feature_id and date(P.listing_end_date) >='$curr_date' and P.status=1 and P.used_brand_id=B.used_brand_id $sqlStr limit $start,$limit";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function getDistinctUsedCarByBodyType($start,$limit,$city_id,$brand_name){
		$curr_date = date("Y-m-d");
		$keyArr[] = $this->usedproductKey."_getDistinctUsedCarByBodyType_";
		if(!empty($city_id)){
                        $sqlStr =" and P.city_id=$city_id";
                        $keyArr[] =$city_id;
                }else{
			$keyArr[] ="-1";
		}
		if(!empty($brand_name)){
                        $sqlStr =" and B.brand_name='$brand_name'";
                        $keyArr[] =$brand_name;
                }else{
			$keyArr[] ="-1";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select distinct(F.feature_name) from USEDCAR_PRODUCT_MASTER P,USEDCAR_FEATURE_MASTER F,USEDCAR_PRODUCT_FEATURE PF,USEDCAR_BRAND_MASTER B where P.used_product_id=PF.product_id and F.feature_group=2 and PF.feature_id=F.feature_id and date(P.listing_end_date) >='$curr_date' and P.status=1 and P.used_brand_id=B.used_brand_id $sqlStr limit $start,$limit";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function getDistinctAlternateUsedCar($start="",$limit="",$city_id="",$brand_name="",$start_price="",$end_price=""){
                $curr_date = date("Y-m-d");
                $keyArr[] = $this->usedproductKey."_getDistinctAlternateUsedCar_";
                if(!empty($brand_name)){
                        $sqlStr .=" and B.brand_name!='$brand_name'";
                        $keyArr[] = $brand_name;
                }else{ $keyArr[] ="-1"; }
				if(!empty($start_price) && !empty($end_price)){
					$sqlStr .=" and P.price >='$start_price' and P.price<='$end_price'";
		                        $keyArr[] =$start_price."_".$end_price;
				}else{ $keyArr[] ="-1";}
				if(!empty($city_id)){
                        $sqlStr .=" and P.city_id=$city_id";
                        $keyArr[] =$city_id;
                }else{ $keyArr[] ="-1";}
                $key = implode("_",$keyArr);
                if($result = $this->cache->get($key)){return $result;}
                $sql = "select distinct(B.brand_name) from USEDCAR_PRODUCT_MASTER P,USEDCAR_BRAND_MASTER B where date(P.listing_end_date) >='$curr_date' and P.status=1 and P.used_brand_id=B.used_brand_id $sqlStr limit $start,$limit";
                $result = $this->select($sql);
                $this->cache->set($key,$result);
                return $result;
        }


	 /**
	 * @note function is used to insert the arrival_product product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertNewArrivalProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("ARRIVAL_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetNewArrivalProductDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the featured product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateNewArrivalProduct($arrival_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("ARRIVAL_PRODUCT",array_keys($update_param),array_values($update_param),"arrival_product_id",$arrival_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetNewArrivalProductDetails($arrival_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the featured product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteNewArrivalProduct($arrival_product_id){
		$sql = "delete from ARRIVAL_PRODUCT where arrival_product_id = $arrival_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		return $isDelete;
	 }
	/**
	 * @note function is used to get Featured product details.
	 * @param integer $arrival_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetNewArrivalProductDetails($arrival_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetNewArrivalProductDetails";
		if(is_array($arrival_product_ids)){
			$arrival_product_ids = implode(",",$arrival_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($arrival_product_ids)){
			$keyArr[] = $arrival_product_ids;
			$whereClauseArr[] = "arrival_product_id in ($arrival_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		#echo $key;die();
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from ARRIVAL_PRODUCT $whereClauseStr order by product_position asc $limitStr";
		#echo $sql;die();
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	function arrGetNewArrivalProductDetailsCnt($arrival_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1')
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetNewArrivalProductDetailsCnt";
		if(is_array($arrival_product_ids)){
			$arrival_product_ids = implode(",",$arrival_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($arrival_product_ids)){
			$keyArr[] = $arrival_product_ids;
			$whereClauseArr[] = "arrival_product_id in ($arrival_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(arrival_product_id) as cnt from ARRIVAL_PRODUCT $whereClauseStr order by product_position asc";
		#die($sql);
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }	 


	 	  /**
	 * @note function is used to insert the budget_product product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertBudgetProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("BUDGET_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetBudgetProductDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the featured product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateBudgetProduct($budget_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("BUDGET_PRODUCT",array_keys($update_param),array_values($update_param),"budget_product_id",$budget_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetBudgetProductDetails($budget_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the featured product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteBudgetProduct($budget_product_id){
		$sql = "delete from BUDGET_PRODUCT where budget_product_id = $budget_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		return $isDelete;
	 }
	/**
	 * @note function is used to get Featured product details.
	 * @param integer $budget_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetBudgetProductDetails($budget_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetBudgetProductDetails";
		if(is_array($budget_product_ids)){
			$budget_product_ids = implode(",",$budget_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($budget_product_ids)){
			$keyArr[] = $budget_product_ids;
			$whereClauseArr[] = "budget_product_id in ($budget_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		#echo $key;die();
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from BUDGET_PRODUCT $whereClauseStr order by product_position asc $limitStr";
		#echo $sql;die();
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	function arrGetBudgetProductDetailsCnt($budget_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1')
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetBudgetProductDetailsCnt";
		if(is_array($budget_product_ids)){
			$budget_product_ids = implode(",",$budget_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($budget_product_ids)){
			$keyArr[] = $budget_product_ids;
			$whereClauseArr[] = "budget_product_id in ($budget_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(budget_product_id) as cnt from BUDGET_PRODUCT $whereClauseStr order by product_position asc";
		#die($sql);
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	  /**
	 * @note function is used to insert the top_product product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertTopProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("TOP_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetTrendingProductDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the featured product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateTopProduct($top_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("TOP_PRODUCT",array_keys($update_param),array_values($update_param),"top_product_id",$top_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetTrendingProductDetails($top_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the featured product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteTopProduct($top_product_id){
		$sql = "delete from TOP_PRODUCT where top_product_id = $top_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		return $isDelete;
	 }
	/**
	 * @note function is used to get Featured product details.
	 * @param integer $top_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetTopProductDetails($top_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetTrendingProductDetails";
		if(is_array($top_product_ids)){
			$top_product_ids = implode(",",$top_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($top_product_ids)){
			$keyArr[] = $top_product_ids;
			$whereClauseArr[] = "top_product_id in ($top_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		#echo $key;die();
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from TOP_PRODUCT $whereClauseStr order by product_position asc $limitStr";
		#echo $sql;die();
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	function arrGetTopProductDetailsCnt($top_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1')
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetTrendingProductDetailsCnt";
		if(is_array($top_product_ids)){
			$top_product_ids = implode(",",$top_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($top_product_ids)){
			$keyArr[] = $top_product_ids;
			$whereClauseArr[] = "top_product_id in ($top_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(top_product_id) as cnt from TOP_PRODUCT reClauseStr order by product_position asc";
		#die($sql);
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	  /**
	 * @note function is used to insert the trending_product product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertTrendingProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("TRENDING_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetTrendingProductDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the featured product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateTrendingProduct($trending_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("TRENDING_PRODUCT",array_keys($update_param),array_values($update_param),"trending_product_id",$trending_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetTrendingProductDetails($trending_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the featured product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteTrendingProduct($trending_product_id){
		$sql = "delete from TRENDING_PRODUCT where trending_product_id = $trending_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		return $isDelete;
	 }
	/**
	 * @note function is used to get Featured product details.
	 * @param integer $trending_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetTrendingProductDetails($trending_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetTrendingProductDetails";
		if(is_array($trending_product_ids)){
			$trending_product_ids = implode(",",$trending_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($trending_product_ids)){
			$keyArr[] = $trending_product_ids;
			$whereClauseArr[] = "trending_product_id in ($trending_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		#echo $key;die();
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from TRENDING_PRODUCT $whereClauseStr order by product_position asc $limitStr";
		#echo $sql;die();
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	function arrGetTrendingProductDetailsCnt($trending_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1')
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetTrendingProductDetailsCnt";
		if(is_array($trending_product_ids)){
			$trending_product_ids = implode(",",$trending_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($trending_product_ids)){
			$keyArr[] = $trending_product_ids;
			$whereClauseArr[] = "trending_product_id in ($trending_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(trending_product_id) as cnt from TRENDING_PRODUCT whereClauseStr order by product_position asc";
		#die($sql);
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	  /**
	 * @note function is used to insert the other_product product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertOtherProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("OTHER_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetOtherProductDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the featured product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateOtherProduct($other_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("OTHER_PRODUCT",array_keys($update_param),array_values($update_param),"other_product_id",$other_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetOtherProductDetails($other_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the featured product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteOtherProduct($other_product_id){
		$sql = "delete from OTHER_PRODUCT where other_product_id = $other_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		return $isDelete;
	 }
	/**
	 * @note function is used to get Featured product details.
	 * @param integer $other_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetOtherProductDetails($other_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetOtherProductDetails";
		if(is_array($other_product_ids)){
			$other_product_ids = implode(",",$other_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($other_product_ids)){
			$keyArr[] = $other_product_ids;
			$whereClauseArr[] = "other_product_id in ($other_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		#echo $key;die();
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from OTHER_PRODUCT $whereClauseStr order by product_position asc $limitStr";
		#echo $sql;die();
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	function arrGetOtherProductDetailsCnt($other_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1')
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetOtherProductDetailsCnt";
		if(is_array($other_product_ids)){
			$other_product_ids = implode(",",$other_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($other_product_ids)){
			$keyArr[] = $other_product_ids;
			$whereClauseArr[] = "other_product_id in ($other_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(other_product_id) as cnt from OTHER_PRODUCT whereClauseStr order by product_position asc";
		#die($sql);
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	  /**
	 * @note function is used to insert the best_seller_product product information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $feature_id.
	 * retun integer.
	 */
	function intInsertBestSellerProduct($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("BEST_SELLER_PRODUCT",array_keys($insert_param),array_values($insert_param));
		$product_id = $this->insert($sql);
		if($product_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetBestSellerProductDetails($product_id);
		return $product_id;
	}
	/**
	 * @note function is used to update the featured product into the database.
	 * @param an associative array $update_param.
	 * @param an integer $latest_product_id.
	 * @pre $update_param must be valid associative array and $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function boolUpdateBestSellerProduct($best_seller_product_id,$update_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("BEST_SELLER_PRODUCT",array_keys($update_param),array_values($update_param),"best_seller_product_id",$best_seller_product_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		$this->arrGetBestSellerProductDetails($best_seller_product_id);
		return $isUpdate;
	 }
	/**
	 * @note function is used to delete the featured product.
	 * @param integer $latest_product_id.
	 * @pre $latest_product_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	 function boolDeleteBestSellerProduct($best_seller_product_id){
		$sql = "delete from BEST_SELLER_PRODUCT where best_seller_product_id = $best_seller_product_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productFeatureKey);
		return $isDelete;
	 }
	/**
	 * @note function is used to get Featured product details.
	 * @param integer $best_seller_product_id.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer category_id.
	 * @param an integer brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 * @pre not required.
	 * @post product details in associative array.
	 * retun an array.
	 */
	 function arrGetBestSellerProductDetails($best_seller_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetBestSellerProductDetails";
		if(is_array($best_seller_product_ids)){
			$best_seller_product_ids = implode(",",$best_seller_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($best_seller_product_ids)){
			$keyArr[] = $best_seller_product_ids;
			$whereClauseArr[] = "best_seller_product_id in ($best_seller_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
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
		#echo $key;die();
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from BEST_SELLER_PRODUCT $whereClauseStr order by product_position asc $limitStr";
		//echo $sql;die();
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }
	function arrGetBestSellerProductDetailsCnt($best_seller_product_ids="",$product_ids="",$category_id="",$brand_id="",$status='1')
	 {
		$keyArr[] = $this->productFeatureKey."_arrGetBestSellerProductDetailsCnt";
		if(is_array($best_seller_product_ids)){
			$best_seller_product_ids = implode(",",$best_seller_product_ids);
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
        //if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = $status;
		}else{$keyArr[] = -1;}
		if(!empty($best_seller_product_ids)){
			$keyArr[] = $best_seller_product_ids;
			$whereClauseArr[] = "best_seller_product_id in ($best_seller_product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(best_seller_product_id) as cnt from BEST_SELLER_PRODUCT whereClauseStr order by product_position asc";
		#die($sql);
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	 }

	 /**
	* @note function is used to insert the top oncars compare set information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $top_compare_id.
	* retun integer.
	*/
	function addUpdTopCompareSetDetails($insert_param,$table_name){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		 $sql = $this->getInsertUpdateSql($table_name,array_keys($insert_param),array_values($insert_param));
		$top_compare_id = $this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->productKey."_oncars_top_compare_set");
		return $top_compare_id;
	}

	/**
	* @note function is used to delete the top Oncars Compare set Detail.
	* @param integer $top_compare_id.
	* @pre $top_compare_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteTopCompareSetDetail($top_compare_id,$table_name){
		$sql = "delete from $table_name where top_compare_id = $top_compare_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->productKey."_oncars_top_compare_set");
		return $isDelete;
	}
	function arrGetTopCompareSetDetailsCnt($top_compare_ids="",$oncars_compare_id="",$category_ids="",$status="1",$ordering=""){
		$keyArr[] = $this->productKey."_oncars_top_compare_set_cnt";
		if(is_array($top_compare_ids)){
			$top_compare_ids = implode(",",$top_compare_ids);
		}
		if(is_array($oncars_compare_ids)){
			$keyArr[] = $oncars_compare_ids;
			$oncars_compare_ids = implode(",",$oncars_compare_ids);
		}else{$keyArr[] =-1;}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($top_compare_ids != ""){
			$keyArr[] = $top_compare_ids;
			$whereClauseArr[] = " top_compare_id in($top_compare_ids)";
		}else{$keyArr[] =-1;}
		if($oncars_compare_ids != ""){
			$keyArr[] = $oncars_compare_ids;
			$whereClauseArr[] = " oncars_compare_id in($oncars_compare_ids)";
		}else{$keyArr[] =-1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = " category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = " status = $status";
		}else{$keyArr[] =-1;}
		if($ordering != ""){
			$keyArr[] = $ordering;
			$whereClauseArr[] = " ordering = $ordering";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select count(top_compare_id) as cnt from TOP_ONCARS_COMPARISON $whereClauseStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get oncars compare set details.
	* @param an integer/comma seperated oncars compare ids/ oncars compare ids array $oncars_compare_ids.
	* @param an integer category_id.
	* @param an integer/comma separated string of compare set.
	* @param an integer position.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post product details in associative array.
	* retun an array.
	*/
	function arrGetTopCompareSetDetails($top_compare_ids="",$oncars_compare_id="",$category_ids="",$status="1",$ordering="",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->productKey."_oncars_top_compare_set";
		if(is_array($top_compare_ids)){
			$top_compare_ids = implode(",",$top_compare_ids);
		}
		if(is_array($oncars_compare_ids)){
			$keyArr[] = $oncars_compare_ids;
			$oncars_compare_ids = implode(",",$oncars_compare_ids);
		}else{$keyArr[] =-1;}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($top_compare_ids != ""){
			$keyArr[] = $top_compare_ids;
			$whereClauseArr[] = " top_compare_id in($top_compare_ids)";
		}else{$keyArr[] =-1;}
		if($oncars_compare_ids != ""){
			$keyArr[] = $oncars_compare_ids;
			$whereClauseArr[] = " oncars_compare_id in($oncars_compare_ids)";
		}else{$keyArr[] =-1;}
		if($category_ids != ""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = " category_id in($category_ids)";
		}else{$keyArr[] =-1;}
		if($status != ""){
			$keyArr[] = $status;
			$whereClauseArr[] = " status = $status";
		}else{$keyArr[] =-1;}
		if($ordering != ""){
			$keyArr[] = $ordering;
			$whereClauseArr[] = " ordering = $ordering";
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(empty($orderby)){
			$orderby = " order by create_date desc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select * from TOP_ONCARS_COMPARISON $whereClauseStr $orderby $limitStr";
		$result=$this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	function researchSummaryMobiles($result,$category_id,$startprice,$endprice,$variant_id,$orderby="PRICE_VARIANT_VALUES.variant_value asc",$discontinue_flag="",$check_discontinue_date=""){
		require_once(CLASSPATH.'feature.class.php');
		require_once(CLASSPATH.'overview.class.php');
		$feature = new FeatureManagement;
		$overview = new OverviewManagement;
		$overviewresult = $overview->arrGetCarFinderFeatureOverview();

		$overviewCnt = sizeof($overviewresult);
		//print_r($overviewresult); die();
		$cnt = sizeof($result);
		//echo $overviewCnt; die();
		for($j=0;$j<$cnt;$j++){
			$product_info = $result[$j]['product_name'];
			//$product_result = $this->researchPriceProductDetails($product_info,$category_id,$startprice,$endprice,$variant_id,$orderby,"1",$discontinue_flag,$check_discontinue_date);
			//$productcnt = sizeof($product_result);
			$featureArr = Array();
			//for($j=0;$j<$productcnt;$j++){
				$product_id = $result[$j]['product_id'];
				$categoryid = $result[$j]['categoryid'];
				$product_info_name = $result[$j]['product_name'];
				$productNameData = $this->arrGetProductNameInfo("",$category_id,"",$product_info_name,"1");
				$sImagePath = $productNameData['0']['image_path'];
				$img_media_id = $productNameData['0']['img_media_id'];
				$result[$j]['model_image_path'] = $sImagePath;
				$result[$j]['model_image_id'] = $img_media_id;
				if(!empty($category_id) && !empty($product_id)){
					unset($overviewArr); unset($displayFeature);
					//echo count($overviewresult)."<br>"; 
					 $k=0; 
					for($overview=0;$overview<$overviewCnt;$overview++){
						$overviewfeature_details = 0;
						unset($featureoverviewArr);
						$overview_feature_id = $overviewresult[$overview]['feature_id'];
						$overview_title = $overviewresult[$overview]['title'];
						$overview_unit = $overviewresult[$overview]['abbreviation'];
						$overviewkey = $this->productKey."_carfinder_researchSummary_feature_id_$overview_feature_id"."_product_id_$product_id";
						$overviewfeature_details = $this->cache->get($overviewkey);
						$overview_feature_ids[]  = $overview_feature_id;
						//print "<pre>"; print_r(sizeof($overviewfeature_details));
						if(empty($overviewfeature_details)){
							$sql = "select * from PRODUCT_FEATURE where feature_id = $overview_feature_id and product_id = $product_id";
							$overviewfeature_details = $this->select($sql);
							$this->cache->set($overviewkey,$overviewfeature_details);
						}
						$feature_value = $overviewfeature_details[0]['feature_value'];
						if($feature_value == "-"){$feature_value="";}
						//echo $overview_title."-------------------".$feature_value."============================".$overview_unit."<br>";
						/*	if(!empty($overview_title)){
							$featureoverviewArr[$k] = $overview_title;
						}
						if(!empty($feature_value)){
							$featureoverviewArr[$k] = $feature_value;
						}
						if(!empty($overview_unit) && !empty($feature_value)){
							$featureoverviewArr[$k] = $overview_unit;
						}*/
						$featureoverviewArr = "";
						if(!empty($feature_value)){
							$featureoverviewArr = $overview_title .": <span>". $feature_value.$overview_unit."</span>";
							$displayFeature[] = $featureoverviewArr;
						}
						$k++;
					}
					
					$result[$j]['short_desc'] = $displayFeature;
					unset($overviewArr);
				}

		}
		//print "<pre>"; print_r($result); die();
		return $result;
	}

	function arrGetAutoProductDetails($search_keyword){
		$keyArr[] = $this->productKey."_AutoProductDetails";
		if(is_array($search_keyword)){
			$search_keyword = implode(",",$search_keyword);
		}
		if($search_keyword != ""){
			$keyArr[] = $search_keyword;
			$whereClauseArr[] = " search LIKE '%$search_keyword%'";
		}else{$keyArr[] =-1;}
		
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(empty($orderby)){
			$orderby = " order by search asc";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql="select * from SEARCH $whereClauseStr $orderby ";
		$result= $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

}
