<?php
/**
 * @brief class is used to perform actions on user reviews
 * @author Sachin
 * @version 1.0
 * @created 11-Nov-2010 5:09:31 PM
 * @last updated on 08-Mar-2011 13:14:00 PM
 */
	class price extends DbOperation{

		var $cache;
		var $priceKey;
		var $productKey;
		/**Intialize the consturctor.*/
		function price(){
			$this->cache = new Cache;
			$this->priceKey = MEMCACHE_MASTER_KEY."price::";
			$this->productKey = MEMCACHE_MASTER_KEY."price_product::";
		}

		/**
		* @note function is used to insert/update variant details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post an integer $variant_id.
		* retun integer.
		*/
		function intInsertUpdateVariantDetail($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$insert_param['update_date'] = date('Y-m-d H:i:s');
			$sSql = $this->getInsertUpdateSql("PRICE_VARIANT_MASTER",array_keys($insert_param),array_values($insert_param));
			$variant_id = $this->insertUpdate($sSql);
			$this->cache->searchDeleteKeys($this->priceKey."_variant");
			return $variant_id;
		}

		function intInsertUpdateVariantDetailTest($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$insert_param['update_date'] = date('Y-m-d H:i:s');
			$sSql = $this->getInsertUpdateSql("PRICE_VARIANT_VALUES",array_keys($insert_param),array_values($insert_param));
			$variant_id = $this->insertUpdate($sSql);
			$this->cache->searchDeleteKeys($this->priceKey."_variant");
			return $variant_id;
		}
		/**
		* @note function is used to insert variant value details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post an integer $price_variant.
		* retun integer.
		*/
		function intInsertVariantValueDetail($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$insert_param['update_date'] = date('Y-m-d H:i:s');
			$sSql = $this->getInsertUpdateSql("PRICE_VARIANT_VALUES",array_keys($insert_param),array_values($insert_param));
			$price_variant = $this->insertUpdate($sSql);
			$this->cache->searchDeleteKeys($this->priceKey);
			$this->cache->searchDeleteKeys($this->productKey);
			return $price_variant;
		}
		/**
		* @note function is used to insert/update variant formula details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post an integer $variant_id.
		* retun integer.
		*/
		function intInsertUpdateVariantFormula($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$insert_param['update_date'] = date('Y-m-d H:i:s');
			$sSql = $this->getInsertUpdateSql("PRICE_VARIANT_FORMULA",array_keys($insert_param),array_values($insert_param));
			$Variant_id = $this->insertUpdate($sSql);
			$this->cache->searchDeleteKeys($this->priceKey."_formula");
			return $Variant_id;
		}
		/**
		* @note function is used to delete variant detail.
		* @param integer $iPid.
		* @pre $iPid must be non-empty/zero valid integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteVariantDetail($iPid){
			$sql = "delete from PRICE_VARIANT_MASTER where variant_id = $iPid";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->priceKey."_variant");
			return $isDelete;
		}
		/**
		* @note function is used to delete variant value detail.
		* @param integer $iRid.
		* @pre $iRid must be non-empty/zero valid integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteVariantValueDetail($iRid){
			$sql = "delete from PRICE_VARIANT_VALUES where price_variant=$iRid";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->priceKey);
			return $isDelete;
		}
		/**
		* @note function is used to delete variant formula detail.
		* @param integer $iFid.
		* @pre $iFid must be non-empty/zero valid integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteVariantFormula($iFid){
			$sql = "delete from PRICE_VARIANT_FORMULA where	variant_formula_id  = $iFid";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->priceKey."_formula");
			$this->arrGetVariantFormulaDetail();
			return $isDelete;
		}
		/**
		* @note function is used to get variant details
		*
		* @param an integer/comma seperated variant ids $variant_id.
		* @param an integer/comma seperated category ids $category_id.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $cnt.
		*
		* @pre not required.
		*
		* @post variant details in associative array.
		* retun an array.
		*/
		function arrGetVariantDetail($variant_id,$category_id,$status="1",$startlimit="",$cnt="",$orderby="order by variant asc"){
			$keyArr[] = $this->priceKey."_variant";
			if(!empty($variant_id)){
				$keyArr[] = $variant_id;
				$whereClauseArr[]=" variant_id in ($variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[]=" category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if($status != ""){
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
			if(!empty($orderby)){
				$keyArr[] = str_replace("","_",$orderby);
				$orderby = $orderby;
			}else{$keyArr[] =-1;}
			if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
			}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			$sql = "select * from PRICE_VARIANT_MASTER $whereClauseStr $orderby $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		function arrGetVariantDetailCount($category_id,$status="1"){
			$keyArr[] = $this->priceKey."_variant_count";
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[]=" category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if($status != ""){
				$keyArr[] = $status;
				$whereClauseArr[] = "status = $status";
			}else{$keyArr[] =-1;}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			$sql = "select count(variant_id) as cnt from PRICE_VARIANT_MASTER $whereClauseStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to get variant details
		*
		* @param an integer/comma seperated product variant ids $product_variant_id.
		* @param an integer/comma seperated variant ids $variant_id.
		* @param an integer/comma seperated product ids/ product ids array $product_id.
		* @param an integer/comma seperated category ids $category_id.
		* @param an integer/comma seperated brand ids $brand_id.
		* @param an integer/comma seperated state ids $state_id.
		* @param an integer/comma seperated city ids $city_id.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $cnt.
		*
		* @pre not required.
		*
		* @post user variant value details in associative array.
		* retun an array.
		*/
		function arrGetVariantValueDetail($price_variant_id="1",$variant_id="",$product_id="",$category_id="",$brand_id="",$state_id="",$city_id="",$status="1",$startlimit="",$cnt="",$default_city="",$orderby="order by price_variant asc",$color_id='0'){
			$keyArr[] = $this->priceKey.'_arrGetVariantValueDetail';
			if(is_array($product_id)){
				$product_id = implode(",",$product_id);
			}
			if(!empty($variant_id)){
				$keyArr[] = $variant_id;
				$whereClauseArr[]=" variant_id in ($variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($price_variant_id)){
				$keyArr[] = $price_variant_id;
				$whereClauseArr[]=" price_variant in ($price_variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($product_id)){
				$keyArr[] = $product_id;
				$whereClauseArr[]=" product_id in ($product_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[]=" category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if(!empty($brand_id)){
				$keyArr[] = $brand_id;
				$whereClauseArr[]=" brand_id in ($brand_id)";
			}else{$keyArr[] =-1;}
			if(!empty($state_id)){
				$keyArr[] = $state_id;
				$whereClauseArr[]=" state_id in ($state_id)";
			}else{$keyArr[] =-1;}
			if(!empty($city_id)){
				$keyArr[] = $city_id;
				$whereClauseArr[]=" city_id in ($city_id)";
			}else{$keyArr[] =-1;}
			if($default_city != ""){
				$keyArr[] = $default_city;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city = $default_city";
			}else{$keyArr[] =-1;}
			if($color_id!=''){
				$keyArr[] = $color_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{$keyArr[] =-1;}
			if($status != ""){
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
				$limitStr = " limit ".implode(" , ",$limitArr);
			}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			$result = $this->cache->get($key);

			if(is_array($result)){return $result;}
			$sql = "select PRICE_VARIANT_VALUES .* from PRICE_VARIANT_VALUES $whereClauseStr $orderby $limitStr";
			#echo "<br/> sql = $sql";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}

		function arrGetDistinctCityFromVariantValueDetail($price_variant_id="1",$variant_id="",$product_id="",$category_id="",$brand_id="",$state_id="",$city_id="",$status="1",$startlimit="",$cnt="",$default_city="",$orderby="order by price_variant asc",$color_id='0'){
				$keyArr[] = $this->priceKey.'_arrGetDistinctCityFromVariantValueDetail';
				if(is_array($product_id)){
						$product_id = implode(",",$product_id);
				}
				if(!empty($variant_id)){
						$keyArr[] = $variant_id;
						$whereClauseArr[]=" variant_id in ($variant_id)";
				}else{$keyArr[] =-1;}
				if(!empty($price_variant_id)){
						$keyArr[] = $price_variant_id;
						$whereClauseArr[]=" price_variant in ($price_variant_id)";
				}else{$keyArr[] =-1;}
				if(!empty($product_id)){
						$keyArr[] = $product_id;
						$whereClauseArr[]=" product_id in ($product_id)";
				}else{$keyArr[] =-1;}
				if(!empty($category_id)){
						$keyArr[] = $category_id;
						$whereClauseArr[]=" category_id in ($category_id)";
				}else{$keyArr[] =-1;}
				if(!empty($brand_id)){
						$keyArr[] = $brand_id;
						$whereClauseArr[]=" brand_id in ($brand_id)";
				}else{$keyArr[] =-1;}
				if(!empty($state_id)){
						$keyArr[] = $state_id;
						$whereClauseArr[]=" state_id in ($state_id)";
				}else{$keyArr[] =-1;}
				if(!empty($city_id)){
						$keyArr[] = $city_id;
						$whereClauseArr[]=" city_id in ($city_id)";
				}else{$keyArr[] =-1;}
				if($default_city != ""){
					$keyArr[] = $default_city;
					$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city = $default_city";
				}else{$keyArr[] =-1;}
				if($color_id!=''){
					$keyArr[] = $color_id;
					$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
				}else{$keyArr[] =-1;}
				if($status != ""){
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
					$limitStr = " limit ".implode(" , ",$limitArr);
				}
				if(sizeof($whereClauseArr) > 0){
					$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
				}
				$key = implode("_",$keyArr);
				if($result = $this->cache->get($key)){return $result;}
				$sql = "select distinct(city_id) from PRICE_VARIANT_VALUES $whereClauseStr $orderby $limitStr";
				$result = $this->select($sql);
				$this->cache->set($key,$result);
				return $result;
		}
		function arrGetVariantValueDetailCount($price_variant_id="1",$variant_id="",$product_id="",$category_id="",$brand_id="",$state_id="",$city_id="",$status="1",$default_city="",$color_id='0'){
			$keyArr[] = $this->priceKey."_varrGetVariantValueDetailCount";
			if(is_array($product_id)){
				$product_id = implode(",",$product_id);
			}
			if(!empty($variant_id)){
				$keyArr[] = $variant_id;
				$whereClauseArr[]=" variant_id in ($variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($price_variant_id)){
				$keyArr[] = $price_variant_id;
				$whereClauseArr[]=" price_variant in ($price_variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($product_id)){
				$keyArr[] = $product_id;
				$whereClauseArr[]=" product_id in ($product_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[]=" category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if(!empty($brand_id)){
				$keyArr[]= $brand_id;
				$whereClauseArr[]=" brand_id in ($brand_id)";
			}else{$keyArr[] =-1;}
			if(!empty($state_id)){
				$keyArr[] = $state_id;
				$whereClauseArr[]=" state_id in ($state_id)";
			}else{$keyArr[] =-1;}
			if(!empty($city_id)){
				$keyArr[] = $city_id;
				$whereClauseArr[]=" city_id in ($city_id)";
			}else{$keyArr[] =-1;}
			if($default_city != ""){
				$keyArr[] = $default_city;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city = $default_city";
			}else{$keyArr[] =-1;}
			if($color_id!=''){
				$keyArr[] = $color_id;
				$whereClauseArr[] ="PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{$keyArr[] =-1;}
			if($status != ""){
				$keyArr[] = $status;
				$whereClauseArr[] = "status = $status";
			}else{$keyArr[] =-1;}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			$sql = "select count(PRICE_VARIANT_VALUES .price_variant) as cnt from PRICE_VARIANT_VALUES $whereClauseStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		function arrGetVariantValueDetailTest($price_variant_id="1",$variant_id="",$product_id="",$category_id="",$brand_id="",$state_id="",$city_id="",$status="1",$startlimit="",$cnt="",$color_id='0'){
			$keyArr[] = $this->priceKey."_arrGetVariantValueDetailTest";
			if(is_array($product_id)){
				$product_id = implode(",",$product_id);
			}
			if(!empty($variant_id)){
				$keyArr[] = $variant_id;
				$whereClauseArr[]=" variant_id in ($variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($price_variant_id)){
				$keyArr[] = $price_variant_id;
				$whereClauseArr[]=" price_variant in ($price_variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($product_id)){
				$keyArr[] = $product_id;
				$whereClauseArr[]=" product_id in ($product_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[]=" category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if(!empty($brand_id)){
				$keyArr[] = $brand_id;
				$whereClauseArr[]=" brand_id in ($brand_id)";
			}else{$keyArr[] =-1;}
			if(!empty($state_id)){
				$keyArr[] = $state_id;
				$whereClauseArr[]=" state_id in ($state_id)";
			}else{$keyArr[] =-1;}
			if(!empty($city_id)){
				$keyArr[] = $city_id;
				$whereClauseArr[]=" city_id in ($city_id)";
			}else{$keyArr[] =-1;}
			if($status != ""){
				$keyArr[] = $status;
				$whereClauseArr[] = "status = $status";
			}else{$keyArr[] =-1;}
			if($color_id!=''){
				$keyArr[] = $color_id;
				$whereClauseArr[] = "color_id = $color_id";
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
			$sql = "select PRICE_VARIANT_VALUES .* from PRICE_VARIANT_VALUES $whereClauseStr order by price_variant $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to get variant formula details
		*
		* @param an integer/comma seperated variant formula ids $variant_formula_id.
		* @param an integer/comma seperated product ids $product_id.
		* @param an integer/comma seperated category ids $category_id.
		* @param an integer/comma seperated brand ids $brand_id.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $cnt.
		*
		* @pre not required.
		*
		* @post user variant formula details in associative array.
		* retun an array.
		*/
		function arrGetVariantFormulaDetail($variant_formula_id="",$product_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt=""){
			$keyArr[] = $this->priceKey."_arrGetVariantFormulaDetail";
			if(!empty($variant_formula_id)){
				$keyArr[] = $variant_formula_id;
				$whereClauseArr[]=" variant_formula_id in ($variant_formula_id)";
			}else{$keyArr[] =-1;}
			if(!empty($brand_id)){
				$keyArr[] = $brand_id;
				$whereClauseArr[]=" brand_id in ($brand_id)";
			}else{$keyArr[] =-1;}
			if(!empty($product_id)){
				$keyArr[] = $product_id;
				$whereClauseArr[]=" product_id in ($product_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[]="category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if($status != ""){
				$keyArr[] = $status;
				$whereClauseArr[] = "status = $status";
			}else{$keyArr[] =-1;}
			if(!empty($startlimit)){
				$keyARr[] = $startlimit;
				$limitArr[] = $startlimit;
			}else{$keyArr[] =-1;}
			if(!empty($cnt)){
				$keyArr[] = $cnt;
				$limitArr[] = $cnt;
			}else{$keyArr[] =-1;}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			$sql = "select PRICE_VARIANT_FORMULA.* from PRICE_VARIANT_FORMULA $whereClauseStr order by variant_formula_id $orderby $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to get price details
		*
		* @param an integer/comma seperated price variant ids $price_variant_id.
		* @param an integer/comma seperated product ids $product_id.
		* @param an integer/comma seperated category ids $category_id.
		* @param an integer/comma seperated brand ids $brand_id.
		* @param an integer/comma seperated state ids $state_id.
		* @param an integer/comma seperated city ids $city_id.
		* @param an integer $default_city.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $cnt.
		*
		* @pre not required.
		*
		* @post user price details in associative array.
		* retun an array.
		*/
		function arrGetPriceDetails($price_variant_id="1",$product_id="",$category_id="",$brand_id="",$state_id="",$city_id="",$status="1",$startlimit="",$cnt="",$default_city="1",$orderby="order by variant_value asc",$color_id='0'){
			$keyArr[] = $this->priceKey."_frontend";
			#$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = PRICE_VARIANT_MASTER.variant_id";
			//$whereClauseArr[] = "PRICE_VARIANT_VALUES.state_id = STATE_MASTER.state_id";
			//$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = CITY_MASTER.city_id";
			if(!empty($price_variant_id)){
				$keyArr[] = $price_variant_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id in($price_variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($product_id)){
				$keyArr[] = $product_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.product_id in($product_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if(!empty($brand_id)){
				$keyArr[] = $brand_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.brand_id in ($brand_id)";
			}else{$keyArr[] =-1;}
			/*if(!empty($state_id)){
				$keyArr[] = $state_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.state_id in ($state_id)";
			}else{$keyArr[] =-1;}*/
			/*if(!empty($city_id)){
				$keyArr[] = $city_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id in ($city_id)";
			}else{$keyArr[] =-1;}*/
			if($color_id!=''){
				$keyArr[] = $color_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{$keyArr[] =-1;}
			if($status != ""){
				$keyArr[] = $status;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = $status";
			}else{$keyArr[] =-1;}
			if($default_city != ""){
				$keyArr[] = $default_city;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city = $default_city";
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
			if(!empty($orderby)) {
				$orderby = $orderby;
			}

			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			//echo $sql = "SELECT PRICE_VARIANT_VALUES.*,STATE_MASTER.*,CITY_MASTER.* FROM PRICE_VARIANT_VALUES,STATE_MASTER,CITY_MASTER $whereClauseStr $orderby $limitStr";
			$sql = "SELECT PRICE_VARIANT_VALUES.* FROM PRICE_VARIANT_VALUES $whereClauseStr $orderby $limitStr";
			$result = $this->select($sql);
			$cnt = sizeof($result);
			$res = $this->arrGetAllVariant($category_id);
			for($i=0;$i<$cnt;$i++){
				$variant_id = $result[$i]['variant_id'];
				$result[$i]['variant'] = $res[$variant_id];
			}
			$this->cache->set($key,$result);
			return $result;
		}
		function arrGetPriceDetailsCount($price_variant_id="1",$product_id="",$category_id="",$brand_id="",$state_id="",$city_id="",$status="1",$startlimit="",$cnt="",$default_city="",$color_id='0'){
			$keyArr[] = $this->priceKey."_frontend_count";
			#$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id = PRICE_VARIANT_MASTER.variant_id";
			//$whereClauseArr[] = "PRICE_VARIANT_VALUES.state_id = STATE_MASTER.state_id";
			//$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id = CITY_MASTER.city_id";
			if(!empty($price_variant_id)){
				$keyArr[] = $price_variant_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.variant_id in($price_variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($product_id)){
				$keyArr[] = $product_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.product_id in($product_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if(!empty($brand_id)){
				$keyArr[] = $brand_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.brand_id in ($brand_id)";
			}else{$keyArr[] =-1;}
			/*if(!empty($state_id)){
				$keyArr[] = $state_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.state_id in ($state_id)";
			}else{$keyArr[] =-1;}*/
			/*if(!empty($city_id)){
				$keyArr[] = $city_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.city_id in ($city_id)";
			}else{$keyArr[] =-1;}*/
			if($color_id!=''){
				$keyArr[] = $color_id;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.color_id = $color_id";
			}else{$keyArr[] =-1;}
			if($status != ""){
				$keyArr[] = $status;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.status = $status";
			}else{$keyArr[] =-1;}
			if($default_city != ""){
				$keyArr[] = $default_city;
				$whereClauseArr[] = "PRICE_VARIANT_VALUES.default_city = $default_city";
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
			//$sql = "SELECT count(PRICE_VARIANT_VALUES.variant_id) as vcnt FROM PRICE_VARIANT_VALUES,STATE_MASTER,CITY_MASTER  $whereClauseStr order by variant_value $limitStr";
			$sql = "SELECT count(PRICE_VARIANT_VALUES.variant_id) as vcnt FROM PRICE_VARIANT_VALUES  $whereClauseStr order by variant_value $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		/**
		* @note function is used to insert variant percentage details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post an integer $price_variant.
		* retun integer.
		*/
		function intInsertVariantPercentageDetail($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$insert_param['update_date'] = date('Y-m-d H:i:s');
			$sSql = $this->getInsertUpdateSql("PRICE_VARIANT_PERCENTAGE",array_keys($insert_param),array_values($insert_param));
			$price_variant = $this->insertUpdate($sSql);
			$this->cache->searchDeleteKeys($this->priceKey."_percentage");
			return $price_percentage;
		}

		/**
		* @note function is used to delete variant percentage detail.
		* @param integer $iRid.
		* @pre $iRid must be non-empty/zero valid integer.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteVariantPercentageDetail($iRid){
			$sql = "delete from PRICE_VARIANT_PERCENTAGE  where id=$iRid";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->priceKey."_percentage");
			return $isDelete;
		}


		/**
		* @note function is used to get variant percentage  details
		*
		* @param an integer/comma seperated product variant ids $product_variant_id.
		* @param an integer/comma seperated variant ids $variant_id.
		* @param an integer/comma seperated product ids/ product ids array $product_id.
		* @param an integer/comma seperated category ids $category_id.
		* @param an integer/comma seperated brand ids $brand_id.
		* @param an integer/comma seperated state ids $state_id.
		* @param an integer/comma seperated city ids $city_id.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $cnt.
		*
		* @pre not required.
		*
		* @post user variant value details in associative array.
		* retun an array.
		*/
		function arrGetVariantPercentageDetail($id="",$category_id="",$brand_id="",$country_id="",$state_id="",$city_id="",$variant_id="",$status="1",$startlimit="",$cnt=""){
			$keyArr[] = $this->priceKey."_arrGetVariantPercentageDetail";
			$whereClauseArr[]=" PRICE_VARIANT_PERCENTAGE.variant_id = PRICE_VARIANT_MASTER.variant_id";
			$whereClauseArr[] = "PRICE_VARIANT_PERCENTAGE.city_id = CITY_MASTER.city_id";
			if(!empty($id)){
				$keyArr[] = $id;
				$whereClauseArr[]=" id in ($id)";
			}else{$keyArr[] =-1;}
			if(!empty($variant_id)){
				$keyArr[] = $variant_id;
				$whereClauseArr[]=" PRICE_VARIANT_PERCENTAGE.variant_id in ($variant_id)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[]=" PRICE_VARIANT_PERCENTAGE.category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if(!empty($brand_id)){
				$keyArr[] = $brand_id;
				$whereClauseArr[]=" PRICE_VARIANT_PERCENTAGE.brand_id in ($brand_id)";
			}else{$keyArr[] =-1;}
			if(!empty($state_id)){
				$keyArr[] = $state_id;
				$whereClauseArr[]=" PRICE_VARIANT_PERCENTAGE.state_id in ($state_id)";
			}else{$keyArr[] =-1;}
			if(!empty($city_id)){
				$keyArr[] = $city_id;
				$whereClauseArr[]=" PRICE_VARIANT_PERCENTAGE.city_id in ($city_id)";
			}else{$keyArr[] =-1;}
			if($status != ""){
				$keyArr[] = $status;
				$whereClauseArr[] = "PRICE_VARIANT_PERCENTAGE.status = $status";
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
			$sql = "select PRICE_VARIANT_PERCENTAGE.*,PRICE_VARIANT_MASTER.*,CITY_MASTER.* from PRICE_VARIANT_PERCENTAGE,PRICE_VARIANT_MASTER,CITY_MASTER  $whereClauseStr order by id $limitStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}

		function arrGetProductWithPriceDetails($category_id,$city_id){
			$key = $this->priceKey."_arrGetProductWithPriceDetails_$category_id_$city_id";
			if($result = $this->cache->get($key)){return $result;}
			$sql = "SELECT *,P.product_id as product_id,P.brand_id as brand_id,P.category_id as category_id FROM PRODUCT_MASTER P LEFT JOIN `PRICE_VARIANT_VALUES` PR ON P.product_id = PR.product_id where PR.city_id =".trim($city_id)." and PR.category_id = $category_id and P.status=1";
			//$sql = "SELECT P.status as p_status,P.product_id as product_id,P.brand_id as brand_id,P.category_id as category_id FROM PRODUCT_MASTER P LEFT JOIN `PRICE_VARIANT_VALUES` PR ON  P.product_id = PR.product_id  where PR.city_id =".trim($city_id)." and P.category_id = $category_id and P.status>0";
			$result = $this->select($sql);
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		function arrGetAllVariant($category_id){
			$result = $this->arrGetVariantDetail("",$category_id);
			$cnt = sizeof($result);
			for($i=0;$i<$cnt;$i++){
				$variant_id = $result[$i]['variant_id'];
				$variant = $result[$i]['variant'];
				$res[$variant_id] = $variant;
			}
			return $res;
		}

        //insert methode for price variant values upload excel
        function insertArchiveVariantValueByColor($insert_param){
            $insert_param['create_date'] = date('Y-m-d H:i:s');
            $insert_param['update_date'] = date('Y-m-d H:i:s');
            $sql=$this->getInsertSql("PRICE_VARIANT_VALUES",array_keys($insert_param),array_values($insert_param));

            $answer_id = $this->insert($sql);

            //echo $answer_id;exit;
            if($answer_id == 'Duplicate entry'){
                unset($getfldArr);unset($where_param);
                $getfldArr[]='price_variant';
                $getfldArr[]='category_id';
                $getfldArr[]='brand_id';
                $getfldArr[]='product_id';
                $getfldArr[]='country_id';
                $getfldArr[]='state_id';
                $getfldArr[]='city_id';
                $getfldArr[]='color_id';
                $getfldArr[]='variant_value';
                $getfldArr[]='variant_id';
                $getfldArr[]='default_city';
                $getfldArr[]='status';
                $getfldArr[]='create_date';
                $getfldArr[]='update_date';

                $where_param=$insert_param;
                unset($where_param['variant_value']);unset($where_param['create_date']);unset($where_param['update_date']);

                $sql1=$this->getInsertSelectSql("PRICE_VARIANT_VALUES_ARCHIVE",$getfldArr,DB_NAME,"PRICE_VARIANT_VALUES",$getfldArr,$where_param,DB_NAME);
                $archive_id=$this->insertSelect($sql1);

                unset($insert_param['create_date']);
                $sql2=$this->getInsertUpdateSql("PRICE_VARIANT_VALUES", array_keys($insert_param), array_values($insert_param));
                $update_id=$this->insertUpdate($sql2);
                //echo $sql2;exit;
            }
            //exit;
			$this->cache->searchDeleteKeys($this->priceKey);
        }
}
