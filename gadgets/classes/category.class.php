<?php
	/**
	 * @brief class is used to add,update,delete,get category details.
	 * @author Rajesh Ujade
	 * @version 1.0
	 * @created 11-Nov-2010 6:29:23 PM
	 * @last updated on 09-Mar-2011 13:14:00 PM
	 */
	class CategoryManagement extends DbOperation
	{
		var $cache;
		var $categorykey;
		/**Initialize the constructor.*/
		function CategoryManagement()
		{
			$this->cache = new Cache;
			$this->categorykey = MEMCACHE_MASTER_KEY.'category::';
		}
		/**
		* @note function is used to insert the category details into the database.
		* @param an associative array $insert_param.
		* @pre $insert_param must be valid associative array.
		* @post integer category id.
		* return integer.
		*/
		function intInsertCategory($insert_param){
			$insert_param['create_date'] = date('Y-m-d H:i:s');
			$insert_param['update_date'] = date('Y-m-d H:i:s');
			$sql = $this->getInsertSql("CATEGORY_MASTER",array_keys($insert_param),array_values($insert_param));
			$category_id = $this->insert($sql);
			if($category_id == 'Duplicate entry'){ return 'exists';}
			$this->cache->searchDeleteKeys($this->categorykey);
			$this->arrGetCategoryDetails($category_id);
			return $category_id;
		}
		/**
		* @note function is used to update the category information.
		* @param integer $category_id.
		* @param an associative array $update_param.
		* @pre $category_id must be valid non empty/zero integer value and $update_param must be valid associative array.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolUpdateCategory($category_id,$update_param){
			$insert_param['update_date'] = date('Y-m-d H:i:s');
			$sql = $this->getUpdateSql("CATEGORY_MASTER",array_keys($update_param),array_values($update_param),"category_id",$category_id);
			$isUpdate = $this->update($sql);
			$this->cache->searchDeleteKeys($this->categorykey);
			$this->arrGetCategoryDetails($category_id);
			return $isUpdate;
		}
		/**
		* @note function is use to delete the category.
		* @param integer $category_id.
		* @pre $category_id must be valid non empty/zero integer value.
		* @post boolean true/false.
		* return boolean.
		*/
		function boolDeleteCategory($category_id){
			$sql = "delete from CATEGORY_MASTER where category_id = $category_id";
			$isDelete = $this->sql_delete_data($sql);
			$this->cache->searchDeleteKeys($this->categorykey);
			return $isDelete;
		}
		/**
		* @note function is used to get category details.
		* @param integer $category_id.
		* @param integer $category_level i.e. 0 is used for root(1st) level category and its decending category is child of the root category.
		* @param boolean Active/InActive $status.
		* @param integer $startlimit.
		* @param integer $count.
		* @pre not required.
		* @post an associative array of category details.
		* return category details.
		*/
		function arrGetCategoryDetails($category_id="",$category_level="",$status="",$startlimit="",$count="",$cat_path=""){
			$keyArr[] = $this->categorykey;
			if(!empty($status)){
				$keyArr[] = $status;
				$whereClauseArr[] = "$status in ($status)";
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "category_id in ($category_id)";
			}else{$keyArr[] =-1;}
			if($category_level != ""){
				$keyArr[] = $category_level;
				$whereClauseArr[] = "category_level = $category_level";
			}else{$keyArr[] =-1;}
			if($cat_path != ""){
				$keyArr[] = $cat_path;
				$whereClauseArr[] = "seo_path = $cat_path";
			}else{$keyArr[] =-1;}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
			}
			if(!empty($startlimit)){
				$limitArr[] = $startlimit;
			}
			if(!empty($count)){
				$limitArr[] = $count;
			}
			if(sizeof($limitArr) > 0){
				$limitStr = " limit ".implode(" , ",$limitArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			 $sql = "select * from CATEGORY_MASTER $whereClauseStr $limitStr";
			$result = $this->select($sql);
			//print_r($result); die();
			$this->cache->set($key,$result);
			return $result;
		}

		/**
		* @note function is used to get category tree.
		* @param integer $category_id.
		* @param integer $category_level i.e. 0 is used for root(1st) level category and its decending category is child of the root category.
		* @pre $category_id must be valid non empty/zero integer value.
		* @post category tree array.It is sort out with the root level category.
		* return array.
		*/
		function arrGetCategoryLevel($category_id="",$category_level='0',$status='1'){
			$keyArr[] = $this->categorykey.'level';
			if(!empty($status)){
				$whereClauseArr[] = "status = $status";
				$keyArr[] = $status;
			}else{$keyArr[] =-1;}
			if(!empty($category_id)){
				$keyArr[] = $category_id;
				$whereClauseArr[] = "category_id = $category_id";
			}else{$keyArr[] =-1;}
			if(!empty($category_level)){
				$whereClauseArr[] = "category_level = $category_level";
				$keyArr[] = $category_level;
			}else{$keyArr[] =-1;}
			if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
			}
			$key = implode("_",$keyArr);
			if($result = $this->cache->get($key)){return $result;}
			#echo "here";die();
			$sql = "select * from CATEGORY_MASTER $whereClauseStr";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;

		}
		/**
		* @note function is used to get category tree.
		* @param integer $category_id.
		* @param integer $category_level i.e. 0 is used for root(1st) level category and its decending category is child of the root category.
		* @pre $category_id must be valid non empty/zero integer value.
		* @post category tree array.It is sort out with the root level category.
		* return array.
		*/
		function arrGetCategoryBreadCrumb($categoryid,&$catTreeArr=''){
			$result = $this->arrGetCategoryLevel($categoryid);
			if(sizeof($result) > 0){
				$category_id = $result[0]['category_id'];
				$category_level = $result[0]['category_level'];
				$category_name = $result[0]['category_name'];
				$catTreeArr[$category_id] = $category_name;
				if($category_level != 0 && $category_id != $category_level){ $this->arrGetCategoryBreadCrumb($category_level,$catTreeArr); }
			}
			ksort($catTreeArr);
			return $catTreeArr;
		}
		function intCategoryUsingCatPAth($cat_path){
			if($result = $this->cache->get('intCategoryUsingCatPAth_'.$cat_path)){ return $result; }
			$sql = "select category_id,seo_path,category_name from CATEGORY_MASTER WHERE seo_path = '$cat_path' order by category_id asc limit 1";
			//echo $sql;
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
	}
