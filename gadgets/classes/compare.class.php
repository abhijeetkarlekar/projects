<?php
/**
 * @brief class is used to perform actions for compare management
 * @author
 * @version 1.0
 * @created 11-Nov-2010 5:09:31 PM
 * @last updated on 08-Mar-2011 13:14:00 PM
 */
class compare extends DbOperation{
	var $cache;
	var $compareKey;
	/**Intialize the consturctor.*/
	function compare(){
		$this->cache = new Cache;
		$this->compareKey = MEMCACHE_MASTER_KEY."compare::";
	}
	function intInsertCompareSet($insert_param){
	}
	function boolUpdateComareSet($compare_id,$update_param){
	}		
	function arrGetHotCompareSet(){
	}
	function MostPopularCar(){
	}
	/**
	* @note function is used to get top competitors details 
	*
	* @param an integer $category_id.
	* @param an integer $competitor_product_id.
	* @param an integer $brand_id.
	* @param an integer $product_id.
	* @param an integer $product_info_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post top competitors details in associative array.
	* retun an array.
	*/
	function arrGetTopCompitators($category_id,$competitor_product_id="",$brand_id="",$product_id="",$product_info_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->compareKey."_arrGetTopCompitators";
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id  = $category_id";
		}else{$keyArr[] =-1;}
		if($competitor_product_id!=""){
			$keyArr[] = $competitor_product_id;
			$whereClauseArr[] = "competitor_product_id  = $competitor_product_id";
		}else{$keyArr[] =-1;}
		if($brand_id != ""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id  = $brand_id";
		}else{$keyArr[] =-1;}
		if($product_id != ""){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "product_id = $product_id";
		}else{$keyArr[] =-1;}
		if($product_info_id != ""){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "product_info_id = $product_info_id";
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
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from COMPARE_TOP_COMPETITOR $whereClauseStr order by create_date desc $limitStr";	
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function arrGetTopCompitatorsByFeatureCnt($category_id,$competitor_product_id="",$brand_id="",$product_id="",$product_info_id="",$status="1",$feature_id=""){
		$keyArr[] = $this->compareKey."_arrGetTopCompitatorsByFeatureCnt";
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "C.category_id  = $category_id";
		}else{$keyArr[] =-1;}
		if($competitor_product_id!=""){
			$keyArr[] = $competitor_product_id;
			$whereClauseArr[] = "C.competitor_product_id  = $competitor_product_id";
		}else{$keyArr[] =-1;}
		if($brand_id != ""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "C.brand_id  = $brand_id";
		}else{$keyArr[] =-1;}
		if($product_id != ""){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "C.product_id = $product_id";
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = "C.product_ids = F.product_id";
		if($feature_id!=''){
            $whereClauseArr[] = "feature_id = $feature_id";
             $keyArr[] = $feature_id;
        }else{$keyArr[] =-1;}
		if($product_info_id != ""){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "C.product_info_id = $product_info_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "C.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(competitor_product_id) as cnt from COMPARE_TOP_COMPETITOR C,PRODUCT_FEATURE F $whereClauseStr";	
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetTopCompitatorsByFeatureBodytype($category_id,$competitor_product_id="",$brand_id="",$product_id="",$product_info_id="",$status="1",$feature_id=""){
		$keyArr[] = $this->compareKey."_arrGetTopCompitatorsByFeatureBodytype";
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "C.category_id  = $category_id";
		}else{$keyArr[] =-1;}
		if($competitor_product_id!=""){
			$keyArr[] = $competitor_product_id;
			$whereClauseArr[] = "C.competitor_product_id  = $competitor_product_id";
		}else{$keyArr[] =-1;}
		if($brand_id != ""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "C.brand_id  = $brand_id";
		}else{$keyArr[] =-1;}
		if($product_id != ""){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "C.product_id = $product_id";
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = "C.product_ids = F.product_id";
		if($feature_id!=''){
                $whereClauseArr[] = "feature_id = $feature_id";
                 $keyArr[] = $feature_id;
        }else{$keyArr[] =-1;}
		if($product_info_id != ""){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "C.product_info_id = $product_info_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "C.status = $status";
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select distinct(feature_id) from COMPARE_TOP_COMPETITOR C,PRODUCT_FEATURE F $whereClauseStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
    }
	function arrGetTopCompitatorsByFeature($category_id,$competitor_product_id="",$brand_id="",$product_id="",$product_info_id="",$status="1",$startlimit="",$cnt="",$feature_id=""){
		$keyArr[] = $this->compareKey."_arrGetTopCompitatorsByFeature";
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "C.category_id  = $category_id";
		}else{$keyArr[] =-1;}
		if($competitor_product_id!=""){
			$keyArr[] = $competitor_product_id;
			$whereClauseArr[] = "C.competitor_product_id  = $competitor_product_id";
		}else{$keyArr[] =-1;}
		if($brand_id != ""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "C.brand_id  = $brand_id";
		}else{$keyArr[] =-1;}
		if($product_id != ""){
			$keyArr[] = $product_id;
			$whereClauseArr[] = "C.product_id = $product_id";
		}else{$keyArr[] =-1;}
		$whereClauseArr[] = "C.product_ids = F.product_id";
		if($feature_id!=''){
			$whereClauseArr[] = "feature_id = $feature_id";
			 $keyArr[] = $feature_id;
		}else{$keyArr[] =-1;}
		if($product_info_id != ""){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "C.product_info_id = $product_info_id";
		}else{$keyArr[] =-1;}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "C.status = $status";
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
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from COMPARE_TOP_COMPETITOR C,PRODUCT_FEATURE F $whereClauseStr order by C.create_date desc $limitStr";	
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get most popular competitor set details 
	*
	* @param an integer/comma separated category ids $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post top most popular competitor set details in associative array.
	* retun an array.
	*/

	function arrGetMostPopularCompareSet($category_id,$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->compareKey."_arrGetMostPopularCompareSet";
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
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
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from MOST_POPULAR_COMPARE_SET_MASTER $whereClauseStr order by position asc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to insert compare overview information into the database.
	* @param an integer $feature_id.
	* @pre $feature_id must be non-empty/zero valid integer.
	* @post an integer id.
	* retun integer.
	*/
	function intInsertCompareOverview($feature_id){
		$sql = "insert into `COMPARE_OVERVIEW_MASTER`(`main_feature_group`,`feature_group`,`category_id`,`feature_id`)select `main_feature_group`,`feature_group`,`category_id`,`feature_id` from FEATURE_MASTER where feature_id = $feature_id";
		$result = $this->insertSelect($sql);
		$this->cache->searchDeleteKeys($this->compareKey."_overview");
		return $result;
	}
	/**
	* @note function is used to get compare overview details.
	*
	* @param an integer/comma seperated overview ids $overview_id.
	* @param an integer/comma seperated feature _ids $feature_id.
	* @param an integer/comma seperated main group ids $main_group_id.
	* @param an integer/comma seperated sub group ids $sub_group_id.
	* @param an integer/comma seperated category ids $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post compare overview details in associative array.
	* retun an array.
	*/
	function arrGetCompareOverview($overview_id="",$feature_id="",$main_group_id="",$sub_group_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->compareKey."_overview";
		if(!empty($overview_id)){
			$keyArr[] = $overview_id;
			$whereClauseArr[] = "overview_id in ($overview_id)";
		}else{$keyArr[] =-1;}
		if(!empty($main_group_id)){
			$keyArr[] = $main_group_id;
			$whereClauseArr[] = "main_feature_group in ($main_group_id)";
		}else{$keyArr[] =-1;}
		if(!empty($sub_group_id)){
			$keyArr[] = $sub_group_id;
			$whereClauseArr[] = "feature_group in ($sub_group_id)";
		}else{$keyArr[] =-1;}
		if(!empty($feature_id)){
			$keyArr[] = $feature_id;
			$whereClauseArr[] = "feature_id in ($feature_id)";
		}else{$keyArr[] =-1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
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
			$limitArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from COMPARE_OVERVIEW_MASTER $whereClauseStr order by position asc $limitStr";			
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to insert comparison details into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $saved_compare_id.
	* retun integer.
	*/
	function saveComparision($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("SAVED_COMPARISION",array_keys($insert_param),array_values($insert_param));
		$saved_compare_id = $this->insert($sql);
		return $saved_compare_id;
	}
}
