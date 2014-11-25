<?php
/**
 * @brief class is used to perform actions on the reviews
 * @author Sachin
 * @version 1.0
 * @created 11-Nov-2010 5:09:31 PM
 * @last updated on 08-Mar-2011 11:40:00 AM
 */
class reviews extends DbOperation{
	
	var $cache;
	var $categoryid;
	var $reviewkey;
	var $reviewtypekey;
	var $reviewgroupkey;
	var $revieweditorkey;
	var $reviewexpertkey;
	

	/**Initialize the consturctor.*/
	function reviews(){
		$this->cache = new Cache;
		$this->reviewkey = MEMCACHE_MASTER_KEY."review::";
		$this->reviewtypekey = MEMCACHE_MASTER_KEY."review_type::";
		$this->reviewgroupkey = MEMCACHE_MASTER_KEY."review_group::";
		$this->revieweditorkey = MEMCACHE_MASTER_KEY."review_editor::";
		$this->reviewexpertkey = MEMCACHE_MASTER_KEY."review_expert::";
	}
	/**
	* @note function is used to add/update reviews details
	*
	* @pre  aParameters is array review details
	*
	* @param an associative array $aParameters
	* @param is a string $sTableName
	*
	* @pre $aParameters must be valid associative array.
	*
	* @post an integer $review_id.
	* retun integer.
	*/
	function addUpdReviewsDetails($aParameters,$sTableName){
		if($sTableName == "REVIEWS"){
			$r_id = $aParameters['review_id'] ? $aParameters['review_id'] : "";
			if($r_id == ""){
			$aParameters['create_date'] = date('Y-m-d H:i:s');
			}
		}else{
			$aParameters['create_date'] = date('Y-m-d H:i:s');
		}
		$aParameters['update_date'] = date('Y-m-d H:i:s');
	 	$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));	
		$iRes=$this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->reviewkey);
		$this->cache->searchDeleteKeys($this->reviewkey."_featured");
		$this->cache->searchDeleteKeys($this->reviewkey."_latest");
		$this->cache->searchDeleteKeys($this->reviewkey."_related");
		$this->cache->searchDeleteKeys($this->reviewexpertkey);
		$this->cache->searchDeleteKeys(GET_ROUTER_REVIEW_TITLE_KEY);
		return $iRes;
	}
	/**
	* @note function is used to delete review
	* @param integer $iRId
	* @param string $sTableName
	* @pre $iRId must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function booldeleteReviews($iRId,$sTableName){
		$sSql="delete from REVIEWS where review_id='".$iRId."'";      
		$iRes=$this->sql_delete_data($sSql);
		$sSql='';
		$sSql="delete from PRODUCT_REVIEWS where review_id='".$iRId."'";      
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->reviewkey);
		$this->cache->searchDeleteKeys(GET_ROUTER_REVIEW_TITLE_KEY);
	}
	/**
	* @note function is used to delete related review
	* @param integer $iRId
	* @param string $sTableName
	* @pre $iRId must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function booldeleteRelatedReviews($iRId,$sTableName){
		$sSql="delete from $sTableName where section_review_id='".$iRId."'";      
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->reviewkey."_featured");
		$this->cache->searchDeleteKeys($this->reviewkey."_latest");
		$this->cache->searchDeleteKeys($this->reviewkey."_related");
	}
	/**
	* @note function is used to fetch review group for reviews details
	*
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param a string/comma seperated group names/ group names array $group_names.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	*
	* @pre not required.
	*
	* @post reviews group details in associative array.
	* retun an array.
	*/
	function arrGetReviewsGroupDetails($group_ids="",$group_names="",$category_ids="",$status="1"){
		$keyArr[] = $this->reviewgroupkey."_detail";
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
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select * from REVIEW_GROUP $whereClauseStr $limitStr order by group_id asc";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to fetch review type for reviews details
	*
	* @param an integer/comma seperated article type ids/ article type ids array $article_type_ids.
	* @param a string/comma seperated type names/ type names array $type_names.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post reviews type details in associative array.
	* retun an array.
	*/
	function arrGetReviewsTypeDetails($article_type_ids="",$type_names="",$category_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->reviewtypekey."_detail";
		if(is_array($article_type_ids)){
			$article_type_ids=implode(",",$article_type_ids);
		}
		if(is_array($type_names)){
			$type_names=implode(",",$type_names);
		}
		if(is_array($category_ids)){
			$category_ids=implode(",",$category_ids);
		}
		if($article_type_ids!=""){
			$whereClauseArr[] = "article_type_id in ($article_type_ids)";
			$keyArr[] = $article_type_ids;
		}else{$keyArr[] = -1;}
		if($type_names!=""){
			$whereClauseArr[] = "type_name in ($type_names)";
			$keyArr[] = $type_names;
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
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql = "select * from ARTICLE_TYPE $whereClauseStr $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get review detail list
	*
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @pre not required.
	*
	* @post reviews details in associative array.
	* retun an array.
	*/
	function getReviewsDetails($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby="",$groupby="",$position=""){
		$keyArr[] = $this->reviewkey."_getReviewsDetails";
		$iCnt=$aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
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
				$product_info_id = intval($product_info_id);
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
				$product_ids = intval($product_ids);
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
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($position != ''){
			$whereClauseArr[] = "R.position=$position";
			$keyArr[] = $position;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}

		if($product_ids!=''){
			$whereClauseArr[] = " PR.product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
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
		if(!empty($groupby)){
			$groupby = str_replace(array('PR.','R.'),'',$groupby);
			$orderby1 = str_replace(array('PR.','R.'),'',$orderby);
            $sql = "select * from (select R.*,PR.product_review_id,PR.group_id,PR.category_id,PR.brand_id,PR.product_id,PR.product_info_id,PR.ordering,PR.create_date AS pr_create_date,PR.update_date as pr_update_date,PR.review_id as pr_review_id, DATE_FORMAT(R.create_date,'%d/%m/%Y') as disp_date from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr $orderby) as tmp $groupby $orderby1 $limitStr";
        }else{
			$sql="select *, DATE_FORMAT(R.create_date,'%d/%m/%Y') as disp_date from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr $orderby $limitStr";
        }
        $result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function getReviewsDetailsCnt($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$groupby="",$position=""){
		$keyArr[] = $this->reviewkey."_getReviewsDetailsCnt";
		$iCnt=$aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if(is_array($group_ids)){
			$group_ids = implode(",",$group_ids);
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
				$product_info_id = intval($product_info_id);
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
				$product_ids = intval($product_ids);
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
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($position != ''){
			$whereClauseArr[] = "R.position=$position";
			$keyArr[] = $position;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}

		if($product_ids!=''){
			$whereClauseArr[] = " PR.product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(!empty($groupby)){
			$groupby1 = str_replace(array('group by'),'',$groupby);
			$groupby1 = trim($groupby1);
                        $sql = "select PR.product_info_id,count($groupby1) as cnt from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr $groupby";
			$result = $this->select($sql);
			$cnt = sizeof($result);
			unset($result);
			$result[0]['cnt'] = $cnt;
                }else{
			$sql="select count(PR.review_id) as cnt from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr";
			$result = $this->select($sql);
                }
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get review detail list by editor
	*
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @pre not required.
	*
	* @post reviews details in associative array.
	* retun an array.
	*/
     function arrGetReviewsByEditor($review_ids="",$uids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby=""){
		    $keyArr[] = $this->revieweditorkey;
			$iCnt=$aParamaters['cnt'];
			if(is_array($review_ids)){
				$review_ids = implode(",",$review_ids);
			}
			if(is_array($uids)){
				$uids = implode(",",$uids);
			}
			if(is_array($type_ids)){
				$type_ids = implode(",",$type_ids);
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
      				    $product_ids = intval($product_ids);
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
      			    $product_info_id = intval($product_info_id);
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
	if(is_array($category_id)){
		$category_id = implode(",",$category_id);
			}
			if(is_array($brand_id)){
				$brand_id = implode(",",$brand_id);
			}
			if($status != ''){
				$whereClauseArr[] = "R.status=$status";
				$keyArr[] = $status;
			}else{$keyArr[] = -1;}
			if($review_ids!=""){
				$whereClauseArr[] = "R.review_id in ($review_ids)";
				$keyArr[] = $review_ids;
			}else{$keyArr[] = -1;}
			if($uids!=""){
				$whereClauseArr[] = "R.uid in ($uids)";
				$keyArr[] = $uids;
			}else{$keyArr[] = -1;}

			if($product_ids!=''){
				$whereClauseArr[] = " PR.product_id in($product_ids)";
				$keyArr[] = $product_ids;
			}else{$keyArr[] = -1;}
			if($product_info_id!=""){
				$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
				$keyArr[] = $product_info_id;
			}else{$keyArr[] = -1;}
			if($group_ids!=""){
				$whereClauseArr[] = " PR.group_id in($group_ids)";
				$keyArr[] = $group_ids;
			}else{$keyArr[] = -1;}
			if($type_ids!=""){
				$whereClauseArr[] = " R.review_type in($type_ids)";
				$keyArr[] = $type_ids;
			}else{$keyArr[] = -1;}
			if($category_id!=""){
				$whereClauseArr[] = " PR.category_id in ($category_id)";
				$keyArr[] = $category_id;
			}else{$keyArr[] = -1;}
			if($brand_id!=""){
				$whereClauseArr[] = " PR.brand_id in ($brand_id)";
				$keyArr[] = $brand_id;
			}else{$keyArr[] = -1;}
			$whereClauseArr[] = " PR.review_id=R.review_id ";
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
			$sSql="select *, DATE_FORMAT(R.create_date,'%d/%m/%Y') as disp_date from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr $orderby $limitStr";
			$result=$this->select($sSql);
			$this->cache->set($key, $result);
			return $result;
     }
	/**
	 * @note function is used to get review detail list without featured reviews 
	 *
	 * @param an integer/comma seperated review ids/ review ids array $review_ids.
	 * @param an integer/comma seperated group ids/ group ids array $group_ids.
	 * @param an integer/comma seperated type ids/ type ids array $type_ids.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	 * @param an integer/comma seperated category ids/ category ids array $category_id.
	 * @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 * @param string $orderby.
	 *
	 * @pre not required.
	 *
	 * @post reviews details without featured reviews in associative array.
	 * retun an array.
	 */
	function getReviewsDetailsWithoutFeatured($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->reviewkey."_without_featured_latest";
		$iCnt = $aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
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
        //if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id not in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		
		if($product_ids!=''){
		  $whereClauseArr[] = " PR.product_id in($product_ids)";
		  $keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
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
		$sSql="select *, DATE_FORMAT(R.create_date,'%d %b %Y') as disp_date from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get review detail count
	*
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param boolean Active/InActive $status.
	*
	* @pre not required.
	*
	* @post integer count.
	* retun an integer.
	*/
	function getReviewsDetailsCount($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1"){
		$keyArr[] = $this->reviewkey."_count";
		$iCnt=$aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
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
        //if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=""){
			$whereClauseArr[] = " PR.product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result[0]['count'];}
		$sSql="select count(*) as count from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr";
		$result=$this->select($sSql);	
		$this->cache->set($key, $result);
		return $result[0]['count'];
	}
	/**
	* @note function is used to get review detail count without featured reviews.
	*
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param boolean Active/InActive $status.
	*
	* @pre not required.
	*
	* @post integer count.
	* retun an integer.
	*/
	function getReviewsDetailsCountWithoutFeatured($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1"){
		$keyArr[] = $this->reviewkey."_without_featured_latest_cnt";
		$iCnt=$aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
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
        //if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = " R.review_id not in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=""){
			$whereClauseArr[] = " PR.product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result[0]['count'];}
		$sSql="select count(*) as count from PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result[0]['count'];
	}
	/**
	* @note function is used to get upload media reviews details.
	*
	* @param an integer $prdRid.
	*
	* @post get upload media reviews details without featured reviews in associative array.
	* retun an array.
	*/	
	function arrGetUploadMediaReviewsDetails($prdRid,$content_type=""){
		 $keyArr[] = $this->reviewkey."_upload_media";
		 if(!empty($prdRid)){
			$whereClauseArr[] = "product_review_id = $prdRid";
			$keyArr[] = $prdRid;
		 }else{$keyArr[] = -1;}
		 if(!empty($content_type)){
			 $whereClauseArr[] = "content_type = $content_type";
			 $keyArr[] = $content_type;
		 }else{$keyArr[] = -1;}
		 if(sizeof($whereClauseArr) > 0){
			 $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		 }
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql = "select * from UPLOAD_MEDIA_REVIEWS $whereClauseStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get expert reviews details list.
	*
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @post get expert reviews details without featured reviews in associative array.
	* retun an array.
	*/
	function getExpertReviewsDetails($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->reviewexpertkey."_detail";
		$iCnt = $aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
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
        //if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=""){
			$whereClauseArr[] = " PR.product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";	
			$keyArr[] = $product_info_id;	
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
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
		if($orderby==""){
			$orderby=" order by R.create_date desc";
		}

		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select *, DATE_FORMAT(R.create_date,'%d/%m/%Y') as disp_date from PRODUCT_EXPERT_REVIEWS PR, EXPERT_REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get expert reviews details count.
	*
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @post integer expert reviews details count.
	* retun an array.
	*/
	function getExpertReviewsDetailsCount($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="", $orderby=""){
		$keyArr[] = $this->reviewexpertkey."_cnt";
		$iCnt=$aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
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
        //if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        //}
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=""){
			$whereClauseArr[] = " PR.product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode('_',$keyArr);
		//echo "KEY---".$key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="select count(*) as count from PRODUCT_EXPERT_REVIEWS PR, EXPERT_REVIEWS R $whereClauseStr";
		//echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	
	/**
	* @note function is used to delete expert reviews.
	* @param integer $iRId.
	* @param string $sTableName
	* @pre $iRId must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function booldeleteExpertReviews($iRId,$sTableName){
		$sSql="delete from EXPERT_REVIEWS where review_id='".$iRId."'";      
		$iRes=$this->sql_delete_data($sSql);
		$sSql='';
		$sSql="delete from PRODUCT_EXPERT_REVIEWS where review_id='".$iRId."'";      
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->reviewexpertkey);
	}
	
	/**
	* @note function is used  get latest reviews details 
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post latest reviews details in associative array.
	* retun an array.
	*/
	function arrGetLatestReviewsDetails($section_ids="",$review_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->reviewkey."_latest_detail";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "LR.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " LR.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " LR.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " LR.review_id=R.review_id ";
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

		$sSql="SELECT * , LR.status as status  FROM `LATEST_REVIEWS` LR,REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	
	/**
	* @note function is used to get Featured reviews details
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @pre not required.
	*
	* @post featured reviews details in associative array.
	* retun an array.
	*/
	function arrGetFeaturedReviewsDetails($section_ids="",$review_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->reviewkey."_featured_detail";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "FR.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " FR.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FR.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FR.review_id=R.review_id ";
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
		$sSql="SELECT *, FR.status as status FROM `FEATURED_REVIEWS` FR,REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	* @note function is used to get Featured reviews name details
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param an integer/comma seperated category ids/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @pre not required.
	*
	* @post featured reviews name details in associative array.
	* retun an array.
	*/
	function arrGetFeaturedReviewsNameDetails($section_ids="",$review_ids="",$type_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby="",$group_ids=""){
		$keyArr[] = $this->reviewkey."_featured_name_detail";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = " FR.status=$status";
			$whereClauseArr[] = " R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "FR.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
                        $whereClauseArr[] = "PR.group_id in ($group_ids)";
                        $keyArr[] = $group_ids;
                }else{$keyArr[] = -1;}

		if($section_ids!=""){
			$whereClauseArr[] = " FR.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FR.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FR.review_id=R.review_id and PR.review_id=R.review_id ";
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

		$sSql="SELECT *,DATE_FORMAT(R.create_date,'%d %b %Y') as disp_date, FR.status as status FROM `FEATURED_REVIEWS` FR,REVIEWS R, PRODUCT_REVIEWS PR $whereClauseStr $orderby $limitStr";
	        $result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	
	/**
	* @note function is used to get related reviews details
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post related reviews details in associative array.
	* retun an array.
	*/
	function arrGetRelatedReviewsDetails($section_ids="",$review_ids="",$type_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->reviewkey."_related_detail";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$whereClauseArr[] = "RR.category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " RR.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " RR.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " RR.review_id=R.review_id ";
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

		$sSql="SELECT *,DATE_FORMAT(R.create_date,'%d/%m/%Y') as disp_date ,RR.status as status FROM `RELATED_REVIEWS` RR,REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	
	/**
	* @note function is used to get latest expert reviews details
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post latest expert reviews details in associative array.
	* retun an array.
	*/
	function arrGetLatestExpertReviewDetails($section_ids="",$review_ids="",$type_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->reviewexpertkey."_latest_detail";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " PR.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " PR.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PR.review_id=R.review_id ";
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

		$sSql="SELECT * FROM `LATEST_EXPERT_REVIEWS` LR,EXPERT_REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	
	/**
	* @note function is used to get featured expert reviews details
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post latest featured expert reviews details in associative array.
	* retun an array.
	*/
	function arrGetFeaturedExpertReviewDetails($section_ids="",$review_ids="",$type_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->reviewexpertkey."_featued_detail";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " FR.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " FR.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FR.review_id=R.review_id ";
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

		$sSql="SELECT * FROM `FEATURED_EXPERT_REVIEWS` FR,EXPERT_REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	
	/**
	* @note function is used to get related expert reviews details
	*
	* @param an integer/comma seperated section ids/ section ids array $section_ids.
	* @param an integer/comma seperated review ids/ review ids array $review_ids.
	* @param an integer/comma seperated type ids/ type ids array $type_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post latest related expert reviews details in associative array.
	* retun an array.
	*/

	function arrGetRelatedExpertReviewDetails($section_ids="",$review_ids="",$type_ids="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->reviewexpertkey."_related";
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$whereClauseArr[] = " RR.section_review_id in ($section_ids)";
			$keyArr[] = $section_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " RR.type_id in ($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " RR.review_id=R.review_id ";
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

		$sSql="SELECT * FROM `RELATED_EXPERT_REVIEWS` RR,EXPERT_REVIEWS R $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get product reviews list details
	*
	* @param an integer/comma seperated  $sreviewsIds.
	* @param an integer $sType.
	* @param a string $sTableName.
	*
	* @post latest product reviews list details in associative array.
	* retun an array.
	*/	
	function getProductReviewsList($sreviewsIds,$sType,$sTableName){
		$sRequest="";
		$aConstraintsArr=$aParamaters;
		$sOrderfield=$aParamaters['orderfield'];
		$sOrder=$aParamaters['order'];
		$iStartlimit=$aParamaters['start'];
		$iCnt=$aParamaters['cnt'];
		$keyArr[] = $this->reviewkey."_list";
		
		if($sreviewsIds!=''){ $keyArr[] = $sreviewsIds; }else{$keyArr[] = -1;}
		if($sType!=''){ $keyArr[] = $sType; }else{$keyArr[] = -1;}
		
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}	

		$sSql="select * from REVIEWS where ";
		if($sreviewsIds!=''){ $sSql .=" review_id in ($sreviewsIds) "; }
		if($sreviewsIds!='' && $sType!=''){ $sSql .=" and";}
		if($sType!=''){ $sSql .=" review_type=$sType"; }
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	 * @note function is used to get reviews video details list
	 *
	 * @param an integer $video_id.
	 * @param an integer $group_id.
	 * @param an integer $product_id.
	 * @param an integer $product_info_id.
	 * @param an integer $category_id.
	 * @param an integer $brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 *
	 * @pre not required.
	 *
	 * @post reviews video details in associative array.
	 * retun an array.
	 */	
	function arrGetReviewsVideoDetails($video_id="",$group_id="",$product_id="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="",$orderby="",$groupby=""){
		$keyArr[] = $this->reviewkey."_arrGetReviewsVideoDetails";
       		if($status != ''){
		  $whereClauseArr[] = "R.status = $status";
		  $keyArr[] = $status;
		}else{$keyArr[] = -1;}
 
		if($group_id!=''){
			$whereClauseArr[] = "PR.group_id = $group_id";
			$keyArr[] = $group_id;
		}else{$keyArr[] = -1;}
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
		if($product_id!=''){
			$whereClauseArr[] = "PR.product_id = $product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
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
                //if(intval($product_info_id)!=0){
                   $product_info_id = intval($product_info_id);
                //}
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
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($video_id!=""){
			$whereClauseArr[] = " UMR.upload_media_id=$video_id ";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		/*if($groupby ==''){
			$groupby = "group by UMR.upload_media_id";			
		}*/
		$whereClauseArr[] = " R.review_id = PR.review_id ";
		$whereClauseArr[] = " PR.product_review_id = UMR.product_review_id ";
		$whereClauseArr[] = " UMR.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		//echo $startlimit."STSRATAT";
		if($startlimit!=''){
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
		if($orderby !="ORDER BY RAND()"){
			$result = $this->cache->get($key);
			if(!empty($result)){ return $result;}
		}
		if(!empty($groupby)){
			$groupby = str_replace(array('PR.','R.'),'',$groupby);
			$orderby1 = str_replace(array('PR.','UM.','UMR.','R.'),'',$orderby);
			$sql = "select * from (SELECT R.review_id,R.title,R.create_date as review_date ,R.abstract,R.uid,UMR.upload_media_id as video_id,R.tags,UMR.media_id,UMR.media_path,UMR.video_img_id,UMR.video_img_path,UMR.content_type,UMR.is_media_process,R.status,UMR.create_date,UMR.update_date,UMR.external_media_source,UMR.media_title,UMR.media_source_flag,PR.brand_id,PR.product_id,PR.product_info_id FROM REVIEWS R,PRODUCT_REVIEWS PR,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr $orderby) as tmp $groupby $orderby1 $limitStr";
		}else{
			$sql="SELECT R.review_id,R.title,R.create_date as review_date ,R.abstract,R.uid,UMR.upload_media_id as video_id,R.title,R.tags,UMR.media_id,UMR.media_path,UMR.video_img_id,UMR.video_img_path,UMR.content_type,UMR.is_media_process,R.status,UMR.create_date,UMR.update_date,UMR.external_media_source,UMR.media_title,UMR.media_source_flag,PR.brand_id,PR.product_id,PR.product_info_id FROM REVIEWS R,PRODUCT_REVIEWS PR,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr $orderby $limitStr ";
		}
		//echo $sql."<br/>";exit;
		$result = $this->select($sql);
		//print_r($result);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetReviewsVideoDetailswithoutfeature($reviewids="",$video_id="",$group_id="",$product_id="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="",$orderby="",$groupby=""){
		$keyArr[] = $this->reviewkey."_video_without_feature";
        
		if($status != ''){
		  $whereClauseArr[] = "R.status = $status";
		  $keyArr[] = $status;
		}else{$keyArr[] = -1;}
 		if(is_array($reviewids)){
				$reviewids = implode(",",$reviewids);
		}
		if($group_id!=''){
			$whereClauseArr[] = "PR.group_id = $group_id";
			$keyArr[] = $group_id;
		}else{$keyArr[] = -1;}
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
		if($product_id!=''){
			$whereClauseArr[] = "PR.product_id = $product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
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
                //if(intval($product_info_id)!=0){
                   $product_info_id = intval($product_info_id);
                //}
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
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($video_id!=""){
			$whereClauseArr[] = " UMR.upload_media_id=$video_id ";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		if($groupby ==''){
			$groupby = "group by UMR.upload_media_id";			
		}
		$whereClauseArr[] = " R.review_id = PR.review_id ";
		if($reviewids!=''){
			$whereClauseArr[] = " R.review_id not in ($reviewids)";
		}
		$whereClauseArr[] = " PR.product_review_id = UMR.product_review_id ";
		$whereClauseArr[] = " UMR.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		//echo $startlimit."STSRATAT";
		if($startlimit!=''){
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

		$sSql="SELECT R.review_id,R.title,R.create_date as review_date ,R.abstract,R.uid,UMR.upload_media_id as video_id,R.title,R.tags,UMR.media_id,UMR.media_path,UMR.video_img_id,UMR.video_img_path,UMR.content_type,UMR.is_media_process,R.status,UMR.create_date,UMR.update_date,UMR.external_media_source,UMR.media_title,UMR.media_source_flag,PR.brand_id,PR.product_id,PR.product_info_id FROM REVIEWS R,PRODUCT_REVIEWS PR,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr $groupby $orderby $limitStr ";
		//echo $sSql."<br/>";exit;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

		function arrGetUniqueModelReviewsVideoDetails($video_id="",$group_id="",$product_id="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->reviewkey."_video_unqmodel";
        
		if($status != ''){
		  $whereClauseArr[] = "R.status = $status";
		  $keyArr[] = $status;
		}else{$keyArr[] = -1;}
 
		if($group_id!=''){
			$whereClauseArr[] = "PR.group_id = $group_id";
			$keyArr[] = $group_id;
		}else{$keyArr[] = -1;}
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
		if($product_id!=''){
			$whereClauseArr[] = "PR.product_id = $product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
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
                //if(intval($product_info_id)!=0){
                   $product_info_id = intval($product_info_id);
                //}
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
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($video_id!=""){
			$whereClauseArr[] = " UMR.upload_media_id=$video_id ";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " R.review_id = PR.review_id ";
		$whereClauseArr[] = " PR.product_review_id = UMR.product_review_id ";
		$whereClauseArr[] = " UMR.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		//echo $startlimit."STSRATAT";
		if($startlimit!=''){
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
		//if(!empty($result)){ return $result;}

		$sSql="SELECT distinct(PR.product_info_id) FROM REVIEWS R,PRODUCT_REVIEWS PR,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr group by UMR.upload_media_id $orderby $limitStr ";
		//echo $sSql."<br/>";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	function arrGetReviewsVideoDetailsCnt($video_id="",$group_id="",$product_id="",$product_info_id="",$category_id="",$brand_id="",$status="1"){
		$keyArr[] = $this->reviewkey."_video_reviews_cnt";
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($group_id!=''){
			$whereClauseArr[] = "PR.group_id = $group_id";
			$keyArr[] = $group_id;
		}else{$keyArr[] = -1;}
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
		if($product_id!=''){
			$whereClauseArr[] = "PR.product_id = $product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
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
        //if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        //}
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
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id = $product_info_id";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}

		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}

		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($video_id!=""){
			$whereClauseArr[] = " UMR.upload_media_id = $video_id";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " R.review_id = PR.review_id ";
		$whereClauseArr[] = " PR.product_review_id = UMR.product_review_id ";
		$whereClauseArr[] = " UMR.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode('_',$keyArr);
		///echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sSql="SELECT count(UMR.upload_media_id) as cnt FROM REVIEWS R,PRODUCT_REVIEWS PR,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr group by UMR.upload_media_id $limitStr";
		//echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	function arrGetReviewsVideoDetailswithoutfeatureCnt($reviewids="",$video_id="",$group_id="",$product_id="",$product_info_id="",$category_id="",$brand_id="",$status="1"){
		$keyArr[] = $this->reviewkey."_wo_feature_video_reviews_cnt";
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(is_array($reviewids)){
				$reviewids = implode(",",$reviewids);
		}
		if($group_id!=''){
			$whereClauseArr[] = "PR.group_id = $group_id";
			$keyArr[] = $group_id;
		}else{$keyArr[] = -1;}
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
		if($product_id!=''){
			$whereClauseArr[] = "PR.product_id = $product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
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
        //if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        //}
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
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id = $product_info_id";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($video_id!=""){
			$whereClauseArr[] = " UMR.upload_media_id = $video_id";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " R.review_id = PR.review_id ";
		if($reviewids!=''){
			$whereClauseArr[] = " R.review_id not in ($reviewids)";
		}
		$whereClauseArr[] = " PR.product_review_id = UMR.product_review_id ";
		$whereClauseArr[] = " UMR.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}

		$key = implode('_',$keyArr);
		///echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sSql="SELECT count(UMR.upload_media_id) as cnt FROM REVIEWS R,PRODUCT_REVIEWS PR,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr group by UMR.upload_media_id $limitStr";
		//echo $sSql;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to insert ask expert questions into the database.
	* @param an associative array $insert_param.
	* @param is a string $table_name.
	* @pre $insert_param must be valid associative array.
	* @post an integer $question_id.
	* retun integer.
	*/
	function intInsertAskExpert($insert_param,$table_name){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($table_name,array_keys($insert_param),array_values($insert_param));
		//echo $sql;
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys("ask_expert");
		return $result;
     }
	/**
	* @note function is used to get question details list
	*
	* @param an integer/comma seperated question ids/ question ids array $question_ids.
	* @param an integer/comma seperated email ids/ email ids array $email_ids.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @pre not required.
	*
	* @post question details in associative array.
	* retun an array.
	*/
	function arrGetQuestionDetails($question_ids="",$email_ids="",$startlimit="",$cnt="",$orderby=""){
	
		$keyArr[] = "ask_expert";
		if(is_array($question_ids)){
			$question_ids = implode(",",$question_ids);
		}
		if(is_array($email_ids)){
			$email_ids = implode(",",$email_ids);
		}
		if($question_ids != ""){
			$whereClauseArr[] = "question_id in ($question_ids)";
			$keyArr[] = $question_ids;
		}else{$keyArr[] = -1;}

		if($email_ids != ""){
			$whereClauseArr[] = "email_id in ($email_ids)";
			$keyArr[] = $email_ids;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if($orderby !=""){
			$orderby ="order by ".$orderby." DESC";
			$keyArr[] = str_replace(" ","_",$orderby);
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
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql="SELECT * from ASK_EXPERT $whereClauseStr $orderby $limitStr";

		$sSql;
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
    }


	function getUniqueModelLatestReviewsDetails($product_info_id="",$brand_id="",$category_id="",$status="1",$startlimit="",$cnt="",$orderby="ORDER BY R.create_date DESC",$content_type=""){
		$keyArr[] = "review_unq_model_id";
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
                //if(intval($product_info_id)!=0){
                   $product_info_id = intval($product_info_id);
                //}
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
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if($model_id != ""){
			$whereClauseArr[] = "product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($brand_id != ""){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = "product_info_id!=0";
		$whereClauseArr[] = " PR.review_id=R.review_id ";
		$whereClauseArr[] = " PR.product_review_id = UMR.product_review_id ";
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($content_type != ''){
			$whereClauseArr[] = "content_type = $content_type";
			$keyArr[] = $content_type;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if($orderby !=""){
			$orderby ="order by ".$orderby." DESC";
			$keyArr[] = str_replace(" ","_",$orderby);
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
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sSql = "SELECT distinct(product_info_id) FROM PRODUCT_REVIEWS PR, REVIEWS R,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	function addUpdReviewsTags($aParameters,$sTableName){
        	$aParameters['create_date'] = date('Y-m-d H:i:s');
	        $aParameters['update_date'] = date('Y-m-d H:i:s');
        	$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
        	$iRes=$this->insertUpdate($sSql);
	        $this->cache->searchDeleteKeys($this->articlekey);
        	return $iRes;
	}
	function deleteReviewsTags($iAId){
        	$sSql="delete from REVIEWS_TAG where review_id='".$iAId."'";
	        $iRes=$this->sql_delete_data($sSql);
        	$this->cache->searchDeleteKeys($this->articlekey);
	}
	function getReviewsTagDetails($tag_ids="",$review_ids="",$review_tag="",$review_tag_href="",$category_id="",$status="1",$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->reviewkey."_tag_detail";
	        if(is_array($tag_ids)){
        	     $tag_ids = implode(",",$tag_ids);
	        }
        	if(is_array($review_ids)){
	             $review_ids = implode(",",$review_ids);
        	}
	        if($tag_ids!=""){
        	     $whereClauseArr[] = "tag_id in ($tag_ids)";
	             $keyArr[] = $tag_ids;
        	}else{$keyArr[] = -1;}
	        if($review_ids!=""){
        	     $whereClauseArr[] = "review_id in ($review_ids)";
	             $keyArr[] = $review_ids;
        	}else{$keyArr[] = -1;}
	        if($review_tag!=""){
        	     $whereClauseArr[] = "review_tag = $review_tag";
	             $keyArr[] = $review_tag;
        	}else{$keyArr[] = -1;}
	        if($review_tag_href!=""){
        	     $whereClauseArr[] = "review_tag_href = $review_tag_href";
	             $keyArr[] = 1;
        	}else{$keyArr[] = -1;}
	        if(is_array($category_id)){
                	$category_id = implode(",",$category_id);
        	}
	        if($category_id!=""){
                	$whereClauseArr[] = " category_id in ($category_id)";
        	        $keyArr[] = $category_id;
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
        	$key = implode('_',$keyArr);
        	$result = $this->cache->get($key);
	        if(!empty($result)){ return $result;}
        	$sSql="select * from REVIEWS_TAG $whereClauseStr order by create_date desc $limitStr";
        	$result=$this->select($sSql);
	        $this->cache->set($key, $result);
	        return $result;
	}
	function getSearchByTagReviewsResultCount($tags="",$category_id="",$status="1"){
		$keyArr[] = $this->reviewkey."_tag_result_cnt";
                if(!empty($tags)){
                        if($category_id!=""){
                                //$whereClauseArr[] = " R.category_id = $category_id";
                                $keyArr[] = $category_id;
                        }else{$keyArr[] = -1;}
                        if($status != ''){
                                $whereClauseArr[] = " R.status = $status";
                                $keyArr[] = $status;
                        }else{$keyArr[] = -1;}
                        if($tags != ""){
								$keyArr[] = $tags;
                                $whereClauseArr[] = "R.tags LIKE '%".$tags."%'";
                        }else{$keyArr[] = -1;}
                        $whereClauseArr[] = "R.review_id=PR.review_id";

                        if(sizeof($whereClauseArr) > 0){
                                $whereClauseStr = " ".implode(" and ",$whereClauseArr);
                        }
                        $key = implode('_',$keyArr);
                        $sql = "select count(*) as cnt from REVIEWS R,PRODUCT_REVIEWS PR where $whereClauseStr";
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
	function getSearchByTagReviewsResult($tags="",$category_id="",$status="1",$startlimit="",$count="",$orderby="order by N.create_date desc"){
		$keyArr[] = $this->reviewkey."_tag_result";
                if(!empty($tags)){
                        if($category_id!=""){
                                //$whereClauseArr[] = " R.category_id = $category_id";
                                $keyArr[] = $category_id;
                        }else{$keyArr[] = -1;}
                        if($status != ''){
                                $whereClauseArr[] = " R.status = $status";
                                $keyArr[] = $status;
                        }else{$keyArr[] = -1;}
                        if($tags != ""){
				$keyArr[] = $tags;	
                                $whereClauseArr[] = "R.tags LIKE '%".$tags."%'";
                        }else{$keyArr[] = -1;}
                        $whereClauseArr[] = "R.review_id=PR.review_id";

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
                        $sql = "select *,R.review_id as article_id from REVIEWS R,PRODUCT_REVIEWS PR where $whereClauseStr $orderby $limitStr";
                        //echo "sql==".$sql;
                        $result = $this->cache->get($key);
                        if(!empty($result)){ return $result;}
                        $result = $this->select($sql);
                        $this->cache->set($key, $result);
                        return $result;
		}
	}

	/**
	 * @note function is used to get reviews video details list for solr search
	 * @on 05-02-2013 by Nayan Darekar
	 * @param an integer $video_id.
	 * @param an integer $group_id.
	 * @param an integer $product_id.
	 * @param an integer $product_info_id.
	 * @param an integer $category_id.
	 * @param an integer $brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 *
	 * @pre not required.
	 *
	 * @post reviews video details in associative array.
	 * retun an array.
	 */
	function arrGetSolrReviewsVideoDetails($video_id="",$group_id="",$product_id="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="",$startdate="",$enddate="",$orderby=""){
		$keyArr[] = $this->reviewkey."_video_solrdetail";

		if($status != ''){
			$whereClauseArr[] = "R.status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}

		if($group_id!=''){
			$whereClauseArr[] = "PR.group_id = $group_id";
			$keyArr[] = $group_id;
		}else{$keyArr[] = -1;}
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
		if($product_id!=''){
			$whereClauseArr[] = "PR.product_id = $product_id";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
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
				//if(intval($product_info_id)!=0){
				$product_info_id = intval($product_info_id);
				//}
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
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($video_id!=""){
			$whereClauseArr[] = " UMR.upload_media_id=$video_id ";
			$keyArr[] = $video_id;
		}else{$keyArr[] = -1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$whereClauseArr[] = "UMR.create_date >= '$startdate'";
		}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$whereClauseArr[] = "UMR.create_date <= '$enddate'";
		}
		$whereClauseArr[] = " R.review_id = PR.review_id ";
		$whereClauseArr[] = " PR.product_review_id = UMR.product_review_id ";
		$whereClauseArr[] = " UMR.content_type=1 ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		//echo $startlimit."STSRATAT";
		if($startlimit!=''){
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
		if(!empty($result)){
			return $result;
		}
		$sSql="SELECT UMR.upload_media_id as video_id,UMR.content,R.title,R.tags,R.abstract,UMR.media_id,UMR.media_path,UMR.video_img_id,UMR.video_img_path,UMR.content_type,UMR.is_media_process,R.status,UMR.create_date,UMR.update_date,UMR.external_media_source,UMR.media_title,UMR.media_source_flag,PR.brand_id,PR.product_id,PR.product_info_id FROM REVIEWS R,PRODUCT_REVIEWS PR,UPLOAD_MEDIA_REVIEWS UMR $whereClauseStr group by UMR.upload_media_id $orderby $limitStr ";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	 * @note function is used to get review detail list without featured reviews
	 *
	 * @param an integer/comma seperated review ids/ review ids array $review_ids.
	 * @param an integer/comma seperated group ids/ group ids array $group_ids.
	 * @param an integer/comma seperated type ids/ type ids array $type_ids.
	 * @param an integer/comma seperated product ids/ product ids array $product_ids.
	 * @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	 * @param an integer/comma seperated category ids/ category ids array $category_id.
	 * @param an integer/comma seperated brand ids/ brand ids array $brand_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 * @param string $orderby.
	 *
	 * @pre not required.
	 *
	 * @post reviews details without featured reviews in associative array.
	 * retun an array.
	 */
	function getReviewsDetailsSolr($review_ids="",$group_ids="",$type_ids="",$product_ids="",$product_info_id="",$category_id="",$brand_id="",$status="1",$startlimit="",$cnt="", $startdate="",$enddate="",$orderby=""){
		$keyArr[] = $this->reviewkey."_without_featured_solr";
		$iCnt = $aParamaters['cnt'];
		if(is_array($review_ids)){
			$review_ids = implode(",",$review_ids);
		}
		if(is_array($type_ids)){
			$type_ids = implode(",",$type_ids);
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
				$product_ids = intval($product_ids);
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
				$product_info_id = intval($product_info_id);
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
		if(is_array($category_id)){
			$category_id = implode(",",$category_id);
		}
		if(is_array($brand_id)){
			$brand_id = implode(",",$brand_id);
		}
		if($status != ''){
			$whereClauseArr[] = "R.status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($review_ids!=""){
			$whereClauseArr[] = "R.review_id not in ($review_ids)";
			$keyArr[] = $review_ids;
		}else{$keyArr[] = -1;}
		if($product_ids!=''){
			$whereClauseArr[] = " PR.product_id in($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$whereClauseArr[] = " PR.product_info_id in($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$whereClauseArr[] = " PR.group_id in($group_ids)";
			$keyArr[] = $group_ids;
		}else{$keyArr[] = -1;}
		if($type_ids!=""){
			$whereClauseArr[] = " R.review_type in($type_ids)";
			$keyArr[] = $type_ids;
		}else{$keyArr[] = -1;}
	
		if($category_id!=""){
			$whereClauseArr[] = " PR.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$whereClauseArr[] = " PR.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($startdate)){
			$startdate = $startdate.' 00:00:00';
			$keyArr[] = $startdate;
			$whereClauseArr[] = "R.create_date >= '$startdate'";
		}else{$keyArr[] = -1;}
		if(!empty($enddate)){
			$enddate = $enddate.' 23:23:59';
			$keyArr[] = $enddate;
			$whereClauseArr[] = "R.create_date <= '$enddate'";
		}else{$keyArr[] = -1;}
                $whereClauseArr[] = " UMR.content_type=2";
		$whereClauseArr[] = " PR.review_id=R.review_id ";
                $whereClauseArr[] = " PR.product_review_id=UMR.product_review_id";
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
		$sSql="select UMR.*, R.*, PR.product_review_id, PR.group_id, PR.category_id, PR.brand_id, PR.product_id, PR.product_info_id, PR.ordering from UPLOAD_MEDIA_REVIEWS UMR, PRODUCT_REVIEWS PR, REVIEWS R $whereClauseStr GROUP BY PR.product_review_id $orderby $limitStr";
		$result = $this->select($sSql);
		return $result;
	}

	function getExpertRatings($category_id="",$brand_id="",$model_id="",$product_id="",$format="xml",$rangeArr="",$ratingAlgoArr=""){
		require_once(CLASSPATH.'user_review.class.php');
                $userreview = new USERREVIEW;
                $result = $userreview->arrGetAdminExpertGrade($category_id,$brand_id,$product_id,$model_id);
                $design_rating = $result[0]['design_rating'];
                $performance_rating = $result[0]['performance_rating'];
                $user_rating = $result[0]['user_rating'];
                $design_rating_proportion = ($design_rating*100)/10;
                $performance_rating_proportion = ($performance_rating*100)/10;
                $user_rating_proportion = ($user_rating*100)/10;
                $overallgrade = $result[0]['overallgrade'];
                $rating_algo_key = "";
                foreach($rangeArr as $key => $range){
                    if($overallgrade >= $range[0] && $overallgrade <= $range[1]){
                        $rating_algo_key = $key;
                        break;
                    }
                }
                $expertratinghtml .= $ratingAlgoArr[$rating_algo_key] ? $ratingAlgoArr[$rating_algo_key] : 0;
                $rating_algo_key = $rating_algo_key ? $rating_algo_key : 'Not Yet Rated';
                if($format=="array"){
                        $expert_ratings['overallgrade'] =$overallgrade ;
                        $expert_ratings['rating'] = $expertratinghtml;
                        $expert_ratings['rating_text'] = $rating_algo_key;
                        return $expert_ratings;
                }
                $expertratingxml .= "<STAR_EXPERT_OVERALLGRADE><![CDATA[$overallgrade]]></STAR_EXPERT_OVERALLGRADE>";
                $expertratingxml .= "<STAR_EXPERT_GRAPH_RATING_STR><![CDATA[$expertratinghtml]]></STAR_EXPERT_GRAPH_RATING_STR>";
                $expertratingxml .= "<STAR_EXPERT_GRAPH_RATING_MSG><![CDATA[$rating_algo_key]]></STAR_EXPERT_GRAPH_RATING_MSG>";
                return $expertratingxml;
        }
	
	function intInsertExpertReviewOptions($review_id="",$category_id="",$model_id="",$product_id="",$flag=""){
		$review_type="expert_review";
		$likeflag = ($flag == 'y') ? 'like_yes' : 'like_no';
		$sql="select $likeflag from EXPERT_REVIEW_LIKES where category_id= $category_id and review_id=$review_id";
		$result = $this->select($sql);
		if(sizeof($result) > 0){
			$sql = "INSERT INTO EXPERT_REVIEW_LIKES(`category_id`,`review_id`,`model_id`,`product_id`,`create_date`,`update_date`) VALUES ($category_id,$review_id,$model_id,$product_id,now(),now()) ON DUPLICATE KEY UPDATE $likeflag = $likeflag+1";
		}else{
			$sql = "INSERT INTO EXPERT_REVIEW_LIKES(`category_id`,`review_id`,`model_id`,`product_id`,`create_date`,`update_date`,$likeflag) VALUES ($category_id,$review_id,$model_id,$product_id,now(),now(),1)";
		}
		$result = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey."_userthumb");
		if(!empty($result)){
			$keyArr[] = $this->expertreviewkey."_userthumb";
			if(!empty($category_id)){
				$keyArr[] = $category_id;
			}else{$keyArr[] = -1;}
			if(!empty($review_id)){
				$keyArr[] = $review_id;
			}else{$keyArr[] = -1;}
			$key = implode('_',$keyArr);
			$result = $this->cache->get($key);
			if(!empty($result)){ return $result['0'][$likeflag];}	
			$sql="select * from EXPERT_REVIEW_LIKES where category_id= $category_id and review_id=$review_id";
			$result = $this->select($sql);
			$total_like = $result['0']['like_yes'] + $result['0']['like_no'];
		}
		$flagcount = $result['0']['like_yes'];
		return $flagcount."/".$total_like;	
	}

	function GetExpertReviewOptions($id="",$review_id="",$category_id="",$model_id="",$product_id="",$flag="",$startlimit="",$count=""){
		$keyArr[] = $this->expertreviewkey."_optiondata";
		$review_type="user_review";
		if(is_array($review_id)){
			$review_id = implode(",",$review_id);				
		}
		if(!empty($model_id)){
			$whereClauseArr[] = "model_id in ( $model_id )";
			$keyArr[] = $model_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in ( $product_id )";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if(!empty($review_id)){
			$whereClauseArr[] = "review_id in ( $review_id )";
			$keyArr[] = $review_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($flag)){
			$whereClauseArr[] = "flag = $flag";
			$keyArr[] = $flag;
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
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}	
		$sql="SELECT * , DATE_FORMAT(create_date,'%d %b %Y') as disp_date FROM EXPERT_REVIEW_LIKES $whereClauseStr $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}
	function helpfulCnt($review_id){
		$sql = "select sum(like_yes) as yes_cnt from EXPERT_REVIEW_LIKES where review_id = $review_id group by review_id order by id desc";
		$result = $this->select($sql);
		$yes_cnt = $result[0]['yes_cnt'];
		return !empty($yes_cnt) ? $yes_cnt : '0';
	}	

	function intUpdateQuestionDetails($iQId){

		$sql = "UPDATE ASK_EXPERT SET ques_reply_status=1 WHERE question_id=$iQId ";
		$iResult = $this->update($sql);
		return $iResult;

	}

	function getBgrExpertReviews($expert_review_param,$israting=0){
		if($expert_review_param!=''){
			//echo EXPERT_REVIEW_API.rawurlencode(strtolower($expert_review_param))."<br>";
			$expert_review_rating = file_get_contents(EXPERT_REVIEW_API.rawurlencode(strtolower($expert_review_param)));
			$expert_data =  json_decode($expert_review_rating);
			$expert_review_cnt = sizeof($expert_data);
		//	print_r($expert_data); 
			//echo $expert_data->error."<br>";
			if($expert_data->error==''){ 
				if(is_array($expert_data)){
					for ($i=0; $i < $expert_review_cnt ; $i++) { 
						$ratings[] = $expert_data[$i]->rating[0];
					}
					if(sizeof($ratings) > 0 ){
						$rating = (array_sum($ratings)/sizeof($ratings) )*20;
					}
					$link = $expert_data[0]->link;
					$title = $expert_data[0]->title;
					if($title!=""){  $is_review=1;}
					$abstract = getCompactString($expert_data[0]->abstract, 95).' ...';;
					$image = $expert_data[0]->image;
					$author = $expert_data[0]->author;
					$datetime = date("d F Y", strtotime($expert_data[0]->datetime));
					//}
					if($israting==1){
						return $rating;
					}else if($israting==2){
						return array("rating"=>$rating,"is_review"=>$is_review);
					}else{
						$expert_rating .="<EXPERT_RATING_DETAIL>";
						$expert_rating .="<EXPERT_RATING>$rating</EXPERT_RATING>";
						$expert_rating .="<EXPERT_RATING_LINK><![CDATA[$link]]></EXPERT_RATING_LINK>";
						$expert_rating .="<EXPERT_RATING_TITLE><![CDATA[$title]]></EXPERT_RATING_TITLE>";
						$expert_rating .="<EXPERT_RATING_ABSTRACT><![CDATA[$abstract]]></EXPERT_RATING_ABSTRACT>";
						$expert_rating .="<EXPERT_RATING_IMAGE><![CDATA[$image]]></EXPERT_RATING_IMAGE>";
						$expert_rating .="<EXPERT_RATING_AUTHOR><![CDATA[$author]]></EXPERT_RATING_AUTHOR>";
						$expert_rating .="<EXPERT_RATING_PUB_DATE><![CDATA[$datetime]]></EXPERT_RATING_PUB_DATE>";
						$expert_rating .="</EXPERT_RATING_DETAIL>";
					}
				}
			}
		}
		return $expert_rating;
	}
}
?>
