<?php
/**
 * @brief class is used to perform actions on user reviews
 * @author Sachin
 * @version 1.0
 * @created 11-Nov-2010 5:09:31 PM
 * @last updated on 08-Mar-2011 13:14:00 PM
 */
class USERREVIEW extends DbOperation
{

	var $cache;
	var $categoryid;
	var $userreviewkey;
	var $quicklinkkey;
	var $reviewdesckey;
	var $userfeedbackkey;
	/**Initialize the consturctor.*/
	function USERREVIEW(){
		$this->cache = new Cache;
		$this->userreviewkey = MEMCACHE_MASTER_KEY."userreview::";
		$this->userfeedbackkey = MEMCACHE_MASTER_KEY."feedback::";
		$this->reviewdesckey = MEMCACHE_MASTER_KEY."reviewdesckey::";
		$this->quicklinkkey = MEMCACHE_MASTER_KEY."quicklinkkey::";
	}
	/**
	* @note function is used to insert the user review information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $answer_id.
	* retun integer.
	*/
	function intInsertUserReviewInfo($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USER_REVIEW",array_keys($insert_param),array_values($insert_param));
		$answer_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		if($answer_id == 'Duplicate entry'){ return 'exists';}
		return $answer_id;
	}
	/**
	 * @note function is used to insert the user review answer into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $answer_id.
	 * retun integer.
	 */
	function intInsertUserReviewAnswer($insert_param)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USER_REVIEW_ANSWER",array_keys($insert_param),array_values($insert_param));
		//echo $sql;
		$answer_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		if($answer_id == 'Duplicate entry'){ return 'exists';}
		return $answer_id;
	}
	/**
	 * @note function is used to update user review answer into the database.
	 * @param an associative array $update_param.
	 * @param is an integer $usr_review_ans_id.
	 * @pre $insert_param must be valid associative array.
	 * retun integer.
	 */
	function intUpdateUserReviewAnswer($update_param,$usr_review_ans_id){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USER_REVIEW_ANSWER",array_keys($update_param),array_values($update_param),'usr_review_ans_id',$usr_review_ans_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $isUpdate;
	}
	/**
	* @note function is used to insert/update the user review answer into the database.
	* @param an associative array $aParameters.
	* @param is a string $sTableName.
	* @pre $aParameters must be valid associative array.
	* retun integer.
	*/
	function addUpdUserReviewsDetails($aParameters,$sTableName){
		$aParameters['create_date'] = date('Y-m-d H:i:s');
		$aParameters['update_date'] = date('Y-m-d H:i:s');
		$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
		//echo "TEST---".$sSql."<br>";    die();
		$iRes=$this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $iRes;
	}
	/**
	 * @note function is used to update the user review into the database.
	 * @param an associative array $update_param.
	 * @param an integer $ans_id.
	 * @param $algoritham.
	 * @pre $update_param must be valid associative array and $brand_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * retun boolean.
	 */
	 function Update_USER_Review($ans_id,$algoritham,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$update_param['algoritham'] = $algoritham;
		$sql = $this->getUpdateSql("USER_REVIEW_ANSWER",array_keys($update_param),array_values($update_param),"ans_id",$ans_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $isUpdate;
	 }
	/**
	* @note function is used to get question details list
	*
	* @param an integer/comma seperated question ids $queid.
	* @param an integer/comma seperated category ids/ category ids array $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	*
	* @pre not required.
	*
	* @post question details in associative array.
	* retun an array.
	*/
	function arrGetQuestions($queid="",$category_id="",$status="1",$startlimit="",$count=""){
		$keyArr[] = $this->userreviewkey."_arrGetQuestions";
		if(is_array($queid)){
	 		$queid = implode(",",$queid);
	 	}
		if(!empty($queid)){
	 		$whereClauseArr[] = "queid in ($queid)";
			$keyArr[] = $queid;
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
	 	if(!empty($count)){
	 		$limitArr[] = $count;
			$keyArr[] = $count;
	 	}else{$keyArr[] = -1;}
	 	if(sizeof($limitArr) > 0){
	 		$limitStr = " limit ".implode(" , ",$limitArr);
	 	}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "select * from USER_REVIEW_QUESTIONAIRE $whereClauseStr order by queid asc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to get question answer details list
	 *
	 * @param an integer/comma seperated answer ids $ans_id.
	 * @param an integer/comma seperated question ids $queid.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $count.
	 *
	 * @pre not required.
	 *
	 * @post question answer details in associative array.
	 * retun an array.
	 */

	function arrGetQueAnswer($ans_id="",$queid="",$status="1",$startlimit="",$count=""){
		$keyArr[] = $this->userreviewkey."_arrGetQueAnswer";
		if(is_array($ans_id)){
	 		$ans_id = implode(",",$ans_id);
	 	}
		if(!empty($ans_id)){
	 		$whereClauseArr[] = "ans_id in ($ans_id)";
			$keyArr[] = $ans_id;
	 	}else{$keyArr[] = -1;}
		if(is_array($queid)){
	 		$queid = implode(",",$queid);
	 	}
		if(!empty($queid)){
	 		$whereClauseArr[] = "queid in ($queid)";
			$keyArr[] = $queid;
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
	 	if(!empty($count)){
	 		$limitArr[] = $count;
			$keyArr[] = $count;
	 	}else{$keyArr[] = -1;}
	 	if(sizeof($limitArr) > 0){
	 		$limitStr = " limit ".implode(" , ",$limitArr);
	 	}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from USER_REVIEW_QUESTIONAIRE_ANSWER $whereClauseStr order by ans_id asc $limitStr";
		//echo $sql;exit;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get user review details list
	*
	* @param an integer/comma seperated user review ids/user review ids array $user_review_id.
	* @param an integer/comma seperated uids $uid.
	* @param string $user_name.
	* @param string $email.
	* @param string $location.
	* @param an integer/comma seperated brand_ids $brand_id.
	* @param an integer/comma seperated category_ids $category_id.
	* @param an integer/comma seperated product_info_ids $product_info_id.
	* @param an integer/comma seperated product_ids $product_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	*
	* @pre not required.
	*
	* @post user review details in associative array.
	* retun an array.
	*/
	function arrGetUserReviewDetails($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$startlimit="",$cnt="",$orderby="",$groupby=""){
		//echo "DDDDDDDDDDDDDDDDDD"; //die();
		//echo "BRBRBBR============ $brand_id $product_info_id";
		$keyArr[] = $this->userreviewkey.'_arrGetUserReviewDetails';
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}

		if(!empty($uid)){
			$whereClauseArr[] = "uid in ($uid)";
			$keyArr[] = $uid;
		}else{$keyArr[] = -1;}

		if(!empty($user_name)){
			$whereClauseArr[] = "user_name = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "locate = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($orderby != ''){
			$orderby = $orderby;
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
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by create_date desc";
			$keyArr[] = str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}

		$key = implode('_',$keyArr);
		//$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(!empty($groupby)){
			$sql = "select * from (select * from USER_REVIEW $whereClauseStr $orderby) as tmp $groupby $orderby $limitStr";
		}else{
			$sql = "select * from USER_REVIEW $whereClauseStr $orderby $limitStr";
		}
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	function arrGetUserReviewDetailsByFuel($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$startlimit="",$cnt="",$orderby="",$groupby="",$feature_value="",$usr_rev_id=""){
		$keyArr[] = $this->userreviewkey.'_arrGetUserReviewDetailsByFuel';
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}

		if(!empty($uid)){
			$whereClauseArr[] = "uid in ($uid)";
			$keyArr[] = $uid;
		}else{$keyArr[] = -1;}

		if(!empty($user_name)){
			$whereClauseArr[] = "user_name = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "locate = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($orderby != ''){
			$orderby = $orderby;
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
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by create_date desc";
			$keyArr[] = str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		if($feature_value!=''){
			$feature_sql = " and user_review_id != $usr_rev_id and product_id in ( select product_id from PRODUCT_FEATURE where feature_value='$feature_value')";
			$keyArr[] = $usr_rev_id;
			$keyArr[] = $feature_value;
		}else{$keyArr[] = '-1_-1';}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from USER_REVIEW $whereClauseStr $feature_sql $groupby $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function getUniqueModelLatestUserReviewsDetails($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$startlimit="",$cnt="",$orderby=""){

		$keyArr[] = $this->userreviewkey.'_getUniqueModelLatestUserReviewsDetails';
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}

		if(!empty($uid)){
			$whereClauseArr[] = "uid in ($uid)";
			$keyArr[] = $uid;
		}else{$keyArr[] = -1;}

		if(!empty($user_name)){
			$whereClauseArr[] = "user_name = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "locate = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($orderby != ''){
			$orderby = $orderby;
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
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		if(empty($orderby)){
			$orderby = "order by create_date desc";
			$keyArr[] = str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "select distinct(product_info_id) from USER_REVIEW $whereClauseStr $orderby $limitStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to get distinct model user review details list
	 *
	 * @param an integer/comma seperated user review ids/user review ids array $user_review_id.
	 * @param an integer/comma seperated uids $uid.
	 * @param string $user_name.
	 * @param string $email.
	 * @param string $location.
	 * @param an integer/comma seperated brand_ids $brand_id.
	 * @param an integer/comma seperated category_ids $category_id.
	 * @param an integer/comma seperated product_info_ids $product_info_id.
	 * @param an integer/comma seperated product_ids $product_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 * @param string $orderby.
	 *
	 * @pre not required.
	 *
	 * @post distinct model user review details in associative array.
	 * retun an array.
	 */
	function getDistinctModelUserReviews($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$startlimit="",$cnt="",$orderby="order by user_review_id",$model_status="1"){
		$keyArr[] = $this->userreviewkey."_getDistinctModelUserReviews";
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}

		if(!empty($uid)){
			$whereClauseArr[] = "uid in ($uid)";
			$keyArr[] = $uid;
		}else{$keyArr[] = -1;}

		if(!empty($user_name)){
			$whereClauseArr[] = "user_name = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "locate = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "R.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "R.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "R.product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "R.product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "R.status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($model_status != ''){
			$whereClauseArr[] = "PR.status = $model_status";
			$keyArr[] = $model_status;
		}else{$keyArr[] = -1;}
		if($orderby != ''){
			$orderby = $orderby;
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
		$whereClauseArr[] = "R.product_info_id = PR.`product_name_id`";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}

		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select DISTINCT (product_info_id), PR.`product_info_name`,PR.`brand_id`,R.create_date FROM USER_REVIEW R, PRODUCT_NAME_INFO PR $whereClauseStr $orderby $limitStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	function getModelUserReviewsArr($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$startlimit="",$cnt="",$orderby="order by user_review_id",$model_status="1"){
		$keyArr[] = $this->userreviewkey."_getModelUserReviewsArr";
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
				$product_id = intval($product_id);
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}

		if(!empty($uid)){
				$whereClauseArr[] = "uid in ($uid)";
				$keyArr[] = $uid;
		}else{$keyArr[] = -1;}
		if(!empty($user_name)){
				$whereClauseArr[] = "user_name = '".strtolower($user_name)."'";
				$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
				$whereClauseArr[] = "email = '".$email."'";
				$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
				$whereClauseArr[] = "locate = '".strtolower($location)."'";
				$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
				$whereClauseArr[] = "R.brand_id in ($brand_id)";
				$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
				$whereClauseArr[] = "R.category_id in ($category_id)";
				$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
					$whereClauseArr[] = "R.product_info_id in ($product_info_id)";
					$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
					$whereClauseArr[] = "R.product_id in ($product_id)";
					$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
					$whereClauseArr[] = "R.status = $status";
					$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($model_status != ''){
					$whereClauseArr[] = "PR.status = $model_status";
					$keyArr[] = $model_status;
		}else{$keyArr[] = -1;}
		if($orderby != ''){
					$orderby = $orderby;
					$keyArr[] = str_replace(" ","_",$orderby);
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = "R.product_info_id = PR.`product_name_id`";
		if(sizeof($whereClauseArr) > 0){
				$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}

		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select R.product_info_id, PR.`product_info_name`,PR.`brand_id`,R.create_date FROM USER_REVIEW R, PRODUCT_NAME_INFO PR $whereClauseStr $orderby $limitStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);

		$res_cnt = sizeof($result);
		$product_info_ids_arr = Array();
		$res_arr = Array();
		$j=0;
		for($i=0;$i<$res_cnt;$i++){
			$product_info_id = $result[$i]["product_info_id"];
			if(!in_array($product_info_id , $product_info_ids_arr)){
				array_push($product_info_ids_arr,$product_info_id);
				if($cnt > 0){
					if($j < $cnt){
						$res_arr[$j]["product_info_id"]= $product_info_id;
						$res_arr[$j]["product_info_name"]= $result[$i]["product_info_name"];
						$res_arr[$j]["brand_id"]= $result[$i]["brand_id"];
						$res_arr[$j]["create_date"]= $result[$i]["create_date"];
						$j++;
					}
				}else{
					$res_arr[$j]["product_info_id"]= $product_info_id;
					$res_arr[$j]["product_info_name"]= $result[$i]["product_info_name"];
					$res_arr[$j]["brand_id"]= $result[$i]["brand_id"];
					$res_arr[$j]["create_date"]= $result[$i]["create_date"];
					$j++;
				}
			}
		}
        return $res_arr;
    }
	/**
	 * @note function is used to get distinct model user review count
	 *
	 * @param an integer/comma seperated user review ids/user review ids array $user_review_id.
	 * @param an integer/comma seperated uids $uid.
	 * @param string $user_name.
	 * @param string $email.
	 * @param string $location.
	 * @param an integer/comma seperated brand_ids $brand_id.
	 * @param an integer/comma seperated category_ids $category_id.
	 * @param an integer/comma seperated product_info_ids $product_info_id.
	 * @param an integer/comma seperated product_ids $product_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 * @param string $orderby.
	 *
	 * @pre not required.
	 *
	 * @post integer distinct model user review count
	 * retun an integer.
	 */
	function getDistinctModelUserReviewsCount($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$startlimit="",$cnt="",$orderby="order by user_review_id",$model_status="1"){
		$keyArr[] = $this->userreviewkey."_getDistinctModelUserReviewsCount";
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}
		if(!empty($uid)){
			$whereClauseArr[] = "uid in ($uid)";
			$keyArr[] = $uid;
		}else{$keyArr[] = -1;}
		if(!empty($user_name)){
			$whereClauseArr[] = "user_name = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "locate = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "R.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "R.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "R.product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "R.product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "R.status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($model_status != ''){
			$whereClauseArr[] = "PR.status = $model_status";
			$keyArr[] = $model_status;
		}else{$keyArr[] = -1;}
		if($orderby != ''){
			$orderby = $orderby;
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
		$whereClauseArr[] = "R.product_info_id = PR.`product_name_id`";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){  return $result[0]['cnt'];}

		$sql = "select count(DISTINCT (product_info_id)) as cnt FROM USER_REVIEW R, PRODUCT_NAME_INFO PR $whereClauseStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		$count =  $result[0]['cnt'];
		//die();
		return $count;
	}

	/**
	 * @note function is used to get user reviews details count
	 *
	 * @param an integer/comma seperated user review ids/user review ids array $user_review_id.
	 * @param an integer/comma seperated uids $uid.
	 * @param string $user_name.
	 * @param string $email.
	 * @param string $location.
	 * @param an integer/comma seperated brand_ids $brand_id.
	 * @param an integer/comma seperated category_ids $category_id.
	 * @param an integer/comma seperated product_info_ids $product_info_id.
	 * @param an integer/comma seperated product_ids $product_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 * @param string $orderby.
	 *
	 * @pre not required.
	 *
	 * @post integer user reviews details count
	 * retun an integer.
	 */
	function arrGetUserReviewDetailsCount($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1"){
		$keyArr[] = $this->userreviewkey."_arrGetUserReviewDetailsCount";
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}

		if(!empty($uid)){
			$whereClauseArr[] = "uid in ($uid)";
			$keyArr[] = $uid;
		}else{$keyArr[] = -1;}

		if(!empty($user_name)){
			$whereClauseArr[] = "user_name = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "locate = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}

		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}

		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result[0]['cnt'];}

		$sql = "select count(user_review_id) as cnt from USER_REVIEW $whereClauseStr";
		//echo $sql."<br>"; //exit;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		//print_r($result); die();
		return $result[0]['cnt'];
		//return $resultcnt;
	}

	function arrGetUserReviewDetailsByFuelCount($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$feature_value="",$usr_rev_id=""){
		$keyArr[] = $this->userreviewkey."_arrGetUserReviewDetailsByFuelCount";
		if(is_array($user_review_id)){
			$user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}

		if(!empty($uid)){
			$whereClauseArr[] = "uid in ($uid)";
			$keyArr[] = $uid;
		}else{$keyArr[] = -1;}

		if(!empty($user_name)){
			$whereClauseArr[] = "lower(user_name) = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "lower(locate) = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if($feature_value!=''){
			$feature_sql =" and user_review_id!=$usr_rev_id and product_id in ( select product_id from PRODUCT_FEATURE where feature_value='$feature_value')";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}

		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result[0]['cnt'];}

		$sql = "select count(user_review_id) as cnt from USER_REVIEW  $whereClauseStr $feature_sql";
		//echo $sql."<br>";exit;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result[0]['cnt'];
		//return $resultcnt;
	}
	/**
	* @note function is used to get user question and answer details
	*
	* @param an integer/comma seperated user review answers ids $usr_review_ans_id.
	* @param an integer/comma seperated questions ids $que_id.
	* @param an integer/comma seperated user review ids $user_review_id.
	* @param an integer $is_rating.
	* @param an integer $is_comment_ans.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post user question and answer details in associative array.
	* retun an array.
	*/
	function arrGetUserQnA($usr_review_ans_id='',$que_id='',$user_review_id='',$is_rating="",$is_comment_ans="",$startlimit="",$cnt=""){
		$keyArr[] = $this->userreviewkey."_arrGetUserQnA";
		if(!empty($usr_review_ans_id)){
			$whereClauseArr[] = "usr_review_ans_id in ($usr_review_ans_id)";
			$keyArr[] = $usr_review_ans_id;
		}else{$keyArr[] = -1;}
		if(!empty($que_id)){
			$whereClauseArr[] = "que_id in ($que_id)";
			$keyArr[] = $que_id;
		}else{$keyArr[] = -1;}
		if(!empty($user_review_id)){
			$whereClauseArr[] = "user_review_id in ($user_review_id)";
			$keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}
		if(!empty($is_rating)){
			$whereClauseArr[] = "is_rating = $is_rating";
			$keyArr[] = $is_rating;
		}else{$keyArr[] = -1;}
		if(!empty($is_comment_ans)){
			$whereClauseArr[] = "is_comment_ans = $is_comment_ans";
			$keyArr[] = $is_comment_ans;
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "select * from USER_REVIEW_ANSWER $whereClauseStr order by usr_review_ans_id $usr_review_ans_id $limitStr";
		//echo $sql;//exit;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to insert overall ratings into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $answer_id.
	 * retun integer.
	 */
	function intInsertOverallRating($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("USER_OVERALL_RATING",array_keys($insert_param),array_values($insert_param));
		//echo $sql;exit;
		$answer_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		if($answer_id == 'Duplicate entry'){ return 'exists';}
		return $answer_id;
	}
	/**
	* @note function is used to get overall grade details
	*
	* @param an integer $category_id.
	* @param an integer $brand_id.
	* @param an integer $product_id.
	* @param an integer $model_id.
	* @param an integer $user_review_id.
	* @param boolean Active/InActive $status.
	*
	* @pre not required.
	*
	* @post overall grade details in associative array.
	* retun an array.
	*/
	function arrGetOverallGrade($category_id,$brand_id='',$product_id='',$model_id='',$status="1",$user_review_id=""){
		$result = $this->arrGetUserReviewDetails($user_review_id,"","","","",$brand_id,$category_id,$model_id,$product_id,$status);
		//print"<pre>";print_r($result);print"</pre>";exit;
		$cnt = sizeof($result);
		$usrReviewIdsArr = array();
		if(is_array($result) && count($result)>0){
			for($i=0;$i<$cnt;$i++){
				if($result[$i]['user_review_id']>0)
					$usrReviewIdsArr[] = $result[$i]['user_review_id'];
			}
		}

		if(is_array($usrReviewIdsArr) && count($usrReviewIdsArr)>0){
			$user_review_ids = implode(",",$usrReviewIdsArr);
		}



		if(!empty($user_review_ids)){
			$keyArr[] = $this->userreviewkey."_user_overall_grade";
			$keyArr[] = $user_review_ids;
			$key = implode('_',$keyArr);
			$result = $this->cache->get($key);
			if(!empty($result)){return $result;}

			$sql = "select sum(overallgrade) AS totaloverallcnt, avg(`overallgrade`) AS overallavg from USER_OVERALL_RATING where user_review_id in ($user_review_ids)";			
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			return $result;
		}
		return $result;
	}

	/**
	* @note function is used to get admin overall grade details
	*
	* @param an integer/comma seperated category ids $category_id.
	* @param an integer/comma seperated brand ids $brand_id.
	* @param an integer/comma seperated product ids $product_id
	* @param an integer/comma seperated model ids $model_id
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post admin overall grade details in associative array.
	* retun an array.
	*/
	function arrGetAdminOverallGrade($category_id,$brand_id='',$product_id='',$model_id='',$status='1',$startlimit="",$cnt=""){
		$keyArr[] = $this->userreviewkey."_admin_overall_grade";
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
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($product_id != ''){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if(!empty($model_id)){
			$whereClauseArr[] = "product_info_id in ($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from ADMIN_OVERALL_RATING $whereClauseStr $limitStr";
		//echo $sql."<br>";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	 * @note function is used to delete admin overall ratings.
	 * @param integer $admin_rating_id
	 * @pre $admin_rating_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	function boolAdminOverallRating($admin_rating_id){
		$sql = "delete from ADMIN_OVERALL_RATING where admin_rating_id = $admin_rating_id";
		$result = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $result;
	}
	/**
	 * @note function is used to update admin overall ratings in the database.
	 * @param an associative array $update_param.
	 * @param an integer $admin_rating_id.
	 * @pre $update_param must be valid associative array.
	 * @post an integer $admin_rating_id.
	 * retun integer.
	 */
	function boolUpdateAdminOverallRating($admin_rating_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("ADMIN_OVERALL_RATING",array_keys($update_param),array_values($update_param),"admin_rating_id",$admin_rating_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $isUpdate;
	}
	/**
	 * @note function is used to insert admin overall ratings into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $answer_id.
	 * retun integer.
	 */
	function intInsertAdminOverallRating($insert_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("ADMIN_OVERALL_RATING",array_keys($insert_param),array_values($insert_param));
		$answer_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		if($answer_id == 'Duplicate entry'){ return 'exists';}
		return $answer_id;
	}
	 /**
	 * @note function is used to get user review questions details
	 *
	 * @param an integer/comma seperated questions ids/questions ids array $queid.
	 * @param an integer/comma seperated category ids $category_id.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 *
	 * @pre not required.
	 *
	 * @post user review questions details in associative array.
	 * retun an array.
	 */
	 function arrGetUserReviewQue($queid="",$category_id="",$startlimit="",$count="") {
	 	$keyArr[] = $this->userreviewkey."_arrGetUserReviewQue";
		if(is_array($queid)){
	 		$queid = implode(",",$queid);
	 	}
	 	if(!empty($queid)){
	 		$whereClauseArr[] = "USER_REVIEW_QUESTIONAIRE_ANSWER.queid in ($queid)";
			$whereClauseArr[] = "USER_REVIEW_QUESTIONAIRE.queid = USER_REVIEW_QUESTIONAIRE_ANSWER.queid";
			$keyArr[] = $queid;

	 	}else{$keyArr[] = -1;}
	 	if(!empty($category_id)){
	 		$whereClauseArr[] = "USER_REVIEW_QUESTIONAIRE.category_id in ($category_id)";
			$keyArr[] = $category_id;
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
		//$sql = "select Q.queid,Q.category_id,Q.quename,Q.uid,Q.create_date,Q.update_date,A.ans_id,A.ans from USER_REVIEW_QUESTIONAIRE Q left join USER_REVIEW_QUESTIONAIRE_ANSWER A on  Q.queid=A.queid $whereClauseStr $limitStr order by Q.queid";

		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}

		$sql = "select USER_REVIEW_QUESTIONAIRE.*,USER_REVIEW_QUESTIONAIRE_ANSWER.* from USER_REVIEW_QUESTIONAIRE,USER_REVIEW_QUESTIONAIRE_ANSWER $whereClauseStr order by USER_REVIEW_QUESTIONAIRE.queid asc $limitStr";
		//echo $sql;exit;
	 	$result = $this->select($sql);
		$this->cache->set($key,$result);
	 	return $result;
	 }
	 /**
	* @note function is used to get feedback subjects details
	*
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post feedback subjects details in associative array.
	* retun an array.
	*/
	 function arrGetFeedbackSubject($status="1",$startlimit="",$count=""){
		$keyArr[] = $this->userfeedbackkey."_arrGetFeedbackSubject";
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
	 	if(!empty($count)){
	 		$limitArr[] = $count;
			$keyArr[] = $count;
	 	}else{$keyArr[] = -1;}
	 	if(sizeof($limitArr) > 0){
	 		$limitStr = " limit ".implode(" , ",$limitArr);
	 	}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "SELECT subject_id,subject FROM FEEDBACK_SUBJECT $whereClauseStr order by subject_id asc $limitStr";
		$result =$this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}


  	/**
	 * @note function is used to insert feedback information into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $answer_id.
	 * retun integer.
	 */
	function intInsertFeedbackInfo($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("FEEDBACK",array_keys($insert_param),array_values($insert_param));
		$answer_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userfeedbackkey);
		if($answer_id == 'Duplicate entry'){ return 'exists';}
		return $answer_id;
	}

	function intInsertUserReviewOptions($review_id,$category_id,$flag){
		$review_type="user_review";
		$likeflag = ($flag == 'y') ? 'like_yes' : 'like_no';
		$sql="select $likeflag from USER_REVIEW_LIKES where category_id= $category_id and review_id=$review_id";
		$result = $this->select($sql);
		if(sizeof($result) > 0){
			//$flagcount = $result['0']['$likeflag']+1;
			$sql = "INSERT INTO USER_REVIEW_LIKES(`category_id`,`review_id`,`create_date`,`update_date`) VALUES ($category_id,$review_id,now(),now()) ON DUPLICATE KEY UPDATE $likeflag = $likeflag+1";
		}else{
			$sql = "INSERT INTO USER_REVIEW_LIKES(`category_id`,`review_id`,`create_date`,`update_date`,$likeflag) VALUES ($category_id,$review_id,now(),now(),1)";
			//$flagcount = 1;
		}
		$result = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey."_userthumb");

		if(!empty($result)){
			$keyArr[] = $this->userreviewkey."_userthumb";
			if(!empty($category_id)){
				$keyArr[] = $category_id;
			}else{$keyArr[] = -1;}
			if(!empty($review_id)){
				$keyArr[] = $review_id;
			}else{$keyArr[] = -1;}
			$key = implode('_',$keyArr);
			//echo $key."<br>";
			$result = $this->cache->get($key);
			if(!empty($result)){
				$total_like = $result['0']['like_yes'] + $result['0']['like_no'];
				$flagcount = $result['0']['like_yes'];
				return $flagcount."/".$total_like;
			}

			$sql="select * from USER_REVIEW_LIKES where category_id= $category_id and review_id=$review_id";
			$result = $this->select($sql);
			$this->cache->set($key,$result);
			//print_r($result);
			//$key   = ($review_type.$review_id.$category_id);
			//$result = serialize($result);
			$total_like = $result['0']['like_yes'] + $result['0']['like_no'];
		}
		$flagcount = $result['0']['like_yes'];
		return $flagcount."/".$total_like;
	}

	function GetUserReviewOptions($id="",$review_id="",$category_id="",$flag="",$startlimit="",$count=""){
		$keyArr[] = $this->userreviewkey."_option";
		$review_type="user_review";
		if(is_array($review_id)){
			$review_id = implode(",",$review_id);
		}
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
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		//$orderby = "order by create_date";
		$sql="SELECT * , DATE_FORMAT(create_date,'%d %b %Y') as disp_date FROM USER_REVIEW_LIKES $whereClauseStr $limitStr";
		//echo $sql."<br>";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	 * @note function is used to update user review answer into the database.
	 * @param an associative array $update_param.
	 * @param is an integer $usr_review_ans_id.
	 * @pre $insert_param must be valid associative array.
	 * retun integer.
	 */

	function intInsertUpdateUserReviewWidget($aParameters,$sTableName){
		$aParameters['create_date'] = date('Y-m-d H:i:s');
		$aParameters['update_date'] = date('Y-m-d H:i:s');
		$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
		//echo "TEST---".$sSql."<br>";    //die();
		$iRes=$this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $iRes;
     }

	/**
	* @note function is used to delete user review widget.
	* @param integer $id.
	* @pre $id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteUserReviewWidget($id=""){
		if(!empty($id)){
			$sSql="delete from USER_REVIEW_WIDGET where id='".$id."'";
			$iRes=$this->sql_delete_data($sSql);
			$this->cache->searchDeleteKeys($this->userreviewkey);
			return $iRes;
		}
	}

	function arrGetUserReviewWidget($id="",$brand_id="",$product_info_id="",$category_id="",$status="1",$startlimit="",$cnt=""){
		$keyArr[] = $this->userreviewkey."_widget";
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
		if(!empty($id)){
			$whereClauseArr[] = "id = $id";
			$keyArr[] = $id;
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = " brand_id = $brand_id";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id = $category_id";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "product_info_id= $product_info_id";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = " status = $status";
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
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$orderby = "order by create_date desc";
		$sSql = "SELECT * , DATE_FORMAT(create_date,'%d %b %Y') as disp_date FROM USER_REVIEW_WIDGET $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	 * @note function is used to delete admin expert ratings.
	 * @param integer $admin_rating_id
	 * @pre $admin_rating_id must be non-empty/zero valid integer.
	 * @post boolean true/false.
	 * return boolean.
	 */
	function boolDeleteAdminExpertRating($expert_rating_id){
		$sql = "delete from EXPERT_OVERALL_RATING where expert_rating_id = $expert_rating_id";
		$result = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $result;
	}
	/**
	 * @note function is used to update admin expert ratings in the database.
	 * @param an associative array $update_param.
	 * @param an integer $admin_rating_id.
	 * @pre $update_param must be valid associative array.
	 * @post an integer $admin_rating_id.
	 * retun integer.
	 */
	function boolUpdateAdminExpertRating($expert_rating_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("EXPERT_OVERALL_RATING",array_keys($update_param),array_values($update_param),"expert_rating_id",$expert_rating_id);

		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		return $isUpdate;
	}
	/**
	 * @note function is used to insert admin expert ratings into the database.
	 * @param an associative array $insert_param.
	 * @pre $insert_param must be valid associative array.
	 * @post an integer $answer_id.
	 * retun integer.
	 */
	function intInsertAdminExpertRating($insert_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("EXPERT_OVERALL_RATING",array_keys($insert_param),array_values($insert_param));
		//	echo $sql;exit;
		$answer_id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->userreviewkey);
		if($answer_id == 'Duplicate entry'){ return 'exists';}
		return $answer_id;
	}
	/**
	* @note function is used to get admin expert grade details
	* @param an integer/comma seperated category ids $category_id.
	* @param an integer/comma seperated brand ids $brand_id.
	* @param an integer/comma seperated product ids $product_id
	* @param an integer/comma seperated model ids $model_id
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	*
	* @pre not required.
	*
	* @post admin overall grade details in associative array.
	* retun an array.
	*/
	function arrGetAdminExpertGrade($category_id,$brand_id='',$product_id='',$model_id='',$status='1',$startlimit="",$cnt=""){
		$keyArr[] = $this->userreviewkey."_adminexpertgrade";
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
		if(!empty($category_id)){
			$whereClauseArr[] = "category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if($product_id != ''){
			$whereClauseArr[] = "product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if(!empty($model_id)){
			$whereClauseArr[] = "product_info_id in ($model_id)";
			$keyArr[] = $model_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$whereClauseArr[] = "status = $status";
			$keyArr[] = $status;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select *,format((design_rating+performance_rating+user_rating)/3,1) as overallgrade from EXPERT_OVERALL_RATING $whereClauseStr  order by create_date desc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	/**
	 * @note function is used to get count of most reviewed product
	 *
	 * @param an integer/comma seperated user review ids/user review ids array $user_review_id.
	 * @param an integer/comma seperated uids $uid.
	 * @param string $user_name.
	 * @param string $email.
	 * @param string $location.
	 * @param an integer/comma seperated brand_ids $brand_id.
	 * @param an integer/comma seperated category_ids $category_id.
	 * @param an integer/comma seperated product_info_ids $product_info_id.
	 * @param an integer/comma seperated product_ids $product_id.
	 * @param boolean Active/InActive $status.
	 * @param integer $startlimit.
	 * @param integer $cnt.
	 * @param string $orderby.
	 *
	 * @pre not required.
	 *
	 * @post most reviewed details in associative array.
	 * retun an array.
	 */
	function getMostReviewedCount($user_review_id="",$uid="",$user_name="",$email="",$location="",$brand_id="",$category_id="",$product_info_id="",$product_id="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->userreviewkey."_getMostReviewedCount";
		if(is_array($user_review_id)){
		   $user_review_id = implode(",",$user_review_id);
		}
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
		if(!empty($user_review_id)){
		   $whereClauseArr[] = "A.user_review_id in ($user_review_id)";
		   $keyArr[] = $user_review_id;
		}else{$keyArr[] = -1;}
		if(!empty($uid)){
		   $whereClauseArr[] = "A.uid in ($uid)";
		   $keyArr[] = $uid;
		}else{$keyArr[] = -1;}
		if(!empty($user_name)){
			$whereClauseArr[] = "A.user_name = '".strtolower($user_name)."'";
			$keyArr[] = strtolower($user_name);
		}else{$keyArr[] = -1;}
		if(!empty($email)){
			$whereClauseArr[] = "A.email = '".$email."'";
			$keyArr[] = $email;
		}else{$keyArr[] = -1;}
		if(!empty($location)){
			$whereClauseArr[] = "A.locate = '".strtolower($location)."'";
			$keyArr[] = strtolower($location);
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$whereClauseArr[] = "A.brand_id in ($brand_id)";
			$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$whereClauseArr[] = "A.category_id in ($category_id)";
			$keyArr[] = $category_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$whereClauseArr[] = "A.product_info_id in ($product_info_id)";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
			$whereClauseArr[] = "A.product_id in ($product_id)";
			$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
		   $whereClauseArr[] = "A.status = $status";
		   $keyArr[] = $status;
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = "A.product_info_id=B.product_name_id";
		$whereClauseArr[] = "B.status='1'";

		if($orderby != ''){
		   $orderby = $orderby;
		   $keyArr[] = str_replace("","_",$orderby);
		}else{$keyArr[] = -1;}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
		   $limitArr[] = $cnt;
		   $keyArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		if(empty($orderby)){
			$orderby = "ORDER BY cnt DESC";
			$keyArr[] = str_replace("","_",$orderby);
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "SELECT count(A.user_review_id) as cnt, A.product_info_id,A.brand_id from USER_REVIEW A,PRODUCT_NAME_INFO B $whereClauseStr group by A.product_info_id $orderby $limitStr";

		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
    	}

	/**
	 * @note function is used to get highest rated product
	 *
	 * @param an integer $category_id.
	 * @param an integer $brand_id.
	 * @param an integer $product_id.
	 * @param an integer $model_id.
	 * @param an integer $user_review_id.
	 * @param boolean Active/InActive $status.
	 *
	 * @pre not required.
	 *
	 * @post overall grade details in associative array.
	 * retun an array.
	 */
	function getHighestRatedProduct($category_id='',$brand_id='',$product_id='',$model_id='',$status="1",$user_review_ids=""){
		$keyArr[] = $this->userreviewkey."_highestrated_$status";
		//if(!empty($user_review_ids)){
		//$keyArr[] = $user_review_ids;
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql ="SELECT sum(overallgrade) AS totaloverallcnt, avg(overallgrade) AS overallavg, B.product_info_id FROM USER_OVERALL_RATING A, USER_REVIEW B WHERE A.user_review_id = B.user_review_id and B.status = $status GROUP BY B.product_info_id order by overallavg DESC";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		//print"<pre>";print_r($result);print"</pre>";die();
		//}
		return $result;
	}
	function getHighestRatedProductArr($category_id='',$brand_id='',$product_id='',$model_id='',$status="1",$user_review_ids=""){
		$keyArr[] = $this->userreviewkey."_highestrated_product_array_$status";
		$product_arr = Array();
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
        if(!empty($result)){ return $result;}
		$sql = "select * from USER_REVIEW A,PRODUCT_NAME_INFO B where A.status = $status and A.product_info_id=B.product_name_id and B.status='1' GROUP BY A.product_info_id";
		$res = $this->select($sql);
		$this->cache->set($key, $result);
		//print"<pre>";print_r($res);print"</pre>";die();
		$cnt = sizeof($res);
		for($i=0;$i<$cnt;$i++){
			$average_rating_count = Array();
			$product_info_id = $res[$i]['product_info_id'];
			$product_arr["product_info_id"][$i] = $product_info_id;

			unset($user_review_result);
			$user_review_result = $this->arrGetUserReviewDetails("","","","","","",$category_id,$product_info_id,"","1");
			$user_review_result_cnt = sizeof($user_review_result);
			for($j=0;$j<$user_review_result_cnt;$j++){
				$user_review_id = $user_review_result[$j]['user_review_id'];
				if(!empty($user_review_id)){
					$ratingresult = $this->arrGetUserQnA('','',$user_review_id,"1");
				}
				$ratingcnt = sizeof($ratingresult);
				$avg_rating_count = 0;
				for($rating=0;$rating<$ratingcnt;$rating++){
					$que_id = $ratingresult[$rating]['que_id'];
					$que_result = $this->arrGetQuestions($que_id);
					$ratingresult[$rating]['quename'] = $que_result[0]['quename'];
					$answer = $ratingresult[$rating]['answer'];
					$ansArr = explode(",",$answer);
					$gradeCnt = $ratingresult[$rating]['grade'];
					$avg_rating_count = $avg_rating_count+$gradeCnt;
				}
				if($avg_rating_count > 0){
					$avg_rating_count = $avg_rating_count/$ratingcnt;
					$avg_rating_count = $this->reviewRatingslab($avg_rating_count);
				}
				array_push($average_rating_count, $avg_rating_count);
			}
			$all_reviews_tot_rating = 0;
		        $all_reviews_avg_rating = 0;
			for($m=0;$m<$user_review_result_cnt;$m++){
				$all_reviews_tot_rating = $all_reviews_tot_rating+$average_rating_count[$m];
			}
			if($all_reviews_tot_rating > 0){
				$all_reviews_avg_rating = $all_reviews_tot_rating/$user_review_result_cnt;
				$all_reviews_avg_rating = $this->reviewRatingslab($all_reviews_avg_rating);
			}
			$product_arr["rating_cnt"][$i] = $all_reviews_avg_rating;
		}
		//print"<pre>";print_r($product_arr);print"</pre>";die();
		$product_new_arr = Array();
		arsort($product_arr["rating_cnt"]);
		$k=0;
		foreach ($product_arr["rating_cnt"] as $key => $val) {
			$product_new_arr[$k]['product_info_id'] = $product_arr["product_info_id"][$key];
			$product_new_arr[$k]['rating_cnt'] = $val;
			$k++;
		}
		//print"<pre>";print_r($product_new_arr);print"</pre>";die();
		return $product_new_arr;
	}
  /**
  * @note function is used to insert the quick link details into the database.
  * @param an associative array $insert_param.
  * @pre $insert_param must be valid associative array.
  * @post an integer $id.
  * retun integer.
  */
  function intInsertQuickLink($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertSql("QUICK_LINK_MASTER",array_keys($insert_param),array_values($insert_param));
		$id = $this->insert($sql);
		$this->cache->searchDeleteKeys($this->quicklinkkey);
		if($id == 'Duplicate entry'){ return 'exists';}
		return $id;
  }

	/**
	* @note function is used to update the quick link details into the database.
	* @param an associative array $update_param.
	* @param an integer $id.
	* @pre $update_param must be valid associative array and $id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* retun boolean.
	*/
   function boolUpdateQuickLink($id,$update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("QUICK_LINK_MASTER",array_keys($update_param),array_values($update_param),"id",$id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->quicklinkkey);
		return $isUpdate;
   }
   /**
   * @note function is used to delete the quick link.
   * @param integer $id.
   * @pre $id must be non-empty/zero valid integer.
   * @post boolean true/false.
   * return boolean.
   */
   function boolDeleteQuickLink($id){
		$sql = "delete from QUICK_LINK_MASTER where id = $id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->quicklinkkey);
		return $isDelete;
   }
	/**
	* @note function is used to get quick link details.
	* @param an integer/comma seperated ids/ ids array $ids.
	* @param an integer/comma separated category_id $category_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post brand details in associative array.
	* retun an array.
	*/
   function arrGetQuickLinkDetails($ids="",$name="",$link="",$category_id="",$status="1",$startlimit="",$count="",$orderby=""){
		$keys[] = $this->quicklinkkey.'_arrGetQuickLinkDetails';
		if(is_array($ids)){
		  $ids = implode(",",$ids);
		}
		if(!empty($ids)){
		  $whereClauseArr[] = "id in($ids)";
		  $keys[] = "id_$ids";
		}
		if(!empty($name)){
		  $name = strtolower($name);
		  $whereClauseArr[] = "name= '".strtolower($name)."'";
		  $keys[] = "name_$name";
		}
		if(!empty($link)){
		  $link = strtolower($link);
		  $whereClauseArr[] = "link= '".strtolower($link)."'";
		  $keys[] = "link_$link";
		}
		if(!empty($category_id)){
		  $whereClauseArr[] = "category_id in ($category_id)";
		  $keys[] = "category_id_$category_id";
		}
		if($status != ''){
		  $whereClauseArr[] = "status=$status";
		  $keys[] = "status_$status";
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
		  $orderby = "order by name asc";
		}
		$keys[] = $orderby;
		$key = implode('_',$keys);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql = "select * from QUICK_LINK_MASTER $whereClauseStr $orderby $limitStr";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
   }
	function getProductRating($category_id='',$brand_id='',$product_id='',$model_id='',$status="1",$user_review_ids="",$startlimit="",$cnt="",$orderby=""){
		$keys[] = $this->userreviewkey."_highestrated_details";
		if(is_array($brand_id)){
        		$brand_id = implode(",",$brand_id);
		}
		if(is_array($model_id)){
        		$model_id = implode(",",$model_id);
		}
		if(is_array($product_id)){
        		$product_id = implode(",",$product_id);
		}
		if(is_array($user_review_ids)){
        		$user_review_ids = implode(",",$user_review_ids);
		}
		if(!empty($category_id)){
		      $whereClauseArr[] = "B.category_id in ($category_id)";
		      $keys[] = "category_id_$category_id";
		}
		if(!empty($brand_id)){
			$whereClauseArr[] = "B.brand_id in ($brand_id)";
		    $keys[] = "brand_id_$brand_id";
		}
		if(!empty($product_id)){
			$whereClauseArr[] = "B.product_id in ($product_id)";
		     $keys[] = "product_id_$product_id";
		}
		if(!empty($model_id)){
			$whereClauseArr[] = "B.product_info_id in ($model_id)";
		     $keys[] = "model_id_$model_id";
		}
		if(!empty($user_review_ids)){
			$whereClauseArr[] = "B.user_review_id in ($user_review_ids)";
		    $keys[] = "user_review_id_$user_review_ids";
		}
		if(!empty($status)){
			$whereClauseArr[] = "B.status=$status";
		}
		$whereClauseArr[] = "A.user_review_id = B.user_review_id";
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keys[] = "startlimit_".$startlimit;
		}
		if(!empty($cnt)){
		   $limitArr[] = $cnt;
		   $keys[] = "cnt_".$cnt;
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = ' where '.implode(' and ',$whereClauseArr);
		}
		if(sizeof($limitArr) > 0){
			$limitStr = ' limit '.implode(',',$limitArr);
		}
		if($orderby != ''){
			$orderby = $orderby;
		}else{
			$orderby = "order by A.overallgrade DESC";
		}
		$keyArr[] = str_replace("","_",$orderby);

		//$keys[] = "user_review_id_".$user_review_ids;
		$key = implode('_',$keys);
		//echo $key."<br>";
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		$sql ="SELECT B.user_review_id, A.overall_id, A.overallgrade, B.brand_id, B.product_info_id, B.product_id,B.create_date FROM USER_OVERALL_RATING A, USER_REVIEW B $whereClauseStr $orderby $limitStr ";
		//echo $sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

   function getUserRatings($user_review_id="",$brand_id="",$category_id="",$product_name_id="",$product_id=""){
		$cnt=0;
		$exterior_rating_count = Array();
		$interior_rating_count = Array();
		$performance_rating_count = Array();
		$service_rating_count = Array();
		$average_rating_count = Array();
		//echo "<br>".$user_review_id."---brand_id--".$brand_id."--category_id---".$category_id."---product_name_id--".$product_name_id."---product_id--".$product_id;
		$result = $this->arrGetUserReviewDetails($user_review_id,"","","","",$brand_id,$category_id,$product_name_id, $product_id,"1");
		//print_r($result);
		$cnt = sizeof($result);
		if($cnt>0){
			for($i=0;$i<$cnt;$i++){
				$user_review_id = $result[$i]['user_review_id'];
				if(!empty($user_review_id)){
					$ratingresult = $this->arrGetUserQnA('','',$user_review_id,"1");
				}
			       $avg_rating_count='';
				if(is_array($ratingresult)){
					$rating_cnt = sizeof($ratingresult);
					for($j=0;$j<$rating_cnt;$j++){
						$que_id = $ratingresult[$j]['que_id'];
						$que_result = $this->arrGetQuestions($que_id);
						$ratingresult[$j]['quename'] = $que_result[0]['quename'];
						$answer = $ratingresult[$j]['answer'];
						$ansArr = explode(",",$answer);
						$gradeCnt = $ratingresult[$j]['grade'];
						if($que_id == 1){
							array_push($exterior_rating_count, $gradeCnt);
						}else if($que_id == 2){
							array_push($interior_rating_count, $gradeCnt);
						}else if($que_id == 3){
							array_push($performance_rating_count, $gradeCnt);
						}else if($que_id == 4){
							array_push($service_rating_count, $gradeCnt);
						}
						$avg_rating_count = $avg_rating_count+$gradeCnt;
					}
				}
				//echo "<br>REVI".$user_review_id."====".$avg_rating_count."-----".$rating_cnt;
				if(!empty($avg_rating_count)){
					$avg_rating_count1 = $avg_rating_count/$rating_cnt;
					//unset($avg_rating_count);
					$avg_rating_count = $this->reviewRatingslab($avg_rating_count1);
				}
				array_push($average_rating_count, $avg_rating_count);
			}
		//	print_r($avg_rating_count);
			$all_reviews_tot_rating = 0; $all_reviews_avg_rating = 0;
			$all_reviews_tot_rating = array_sum($average_rating_count);
			if($all_reviews_tot_rating > 0){
				$all_reviews_avg_rating = $all_reviews_tot_rating/$cnt;
				$all_reviews_avg_rating = $this->reviewRatingslab($all_reviews_avg_rating);
					$all_reviews_avg_rating_proportion = (($all_reviews_avg_rating*100)/10)*2;
			}
			$ext_reviews_tot_rating = 0; $ext_reviews_avg_rating = 0;
			$ext_cnt = sizeof($exterior_rating_count);
			$ext_reviews_tot_rating = array_sum($exterior_rating_count);
			if($ext_reviews_tot_rating > 0){
				$ext_reviews_avg_rating = $ext_reviews_tot_rating/$ext_cnt;
				$ext_reviews_avg_rating = $this->reviewRatingslab($ext_reviews_avg_rating);
				$ext_reviews_avg_rating_proportion = (($ext_reviews_avg_rating*100)/10)*2;
			}
			$int_reviews_tot_rating = 0; $int_reviews_avg_rating = 0;
			$int_cnt = sizeof($interior_rating_count);
			$int_reviews_tot_rating = array_sum($interior_rating_count);
			if($int_reviews_tot_rating > 0){
				$int_reviews_avg_rating = $int_reviews_tot_rating/$int_cnt;
				$int_reviews_avg_rating = $this->reviewRatingslab($int_reviews_avg_rating);
				$int_reviews_avg_rating_proportion = (($int_reviews_avg_rating*100)/10)*2;
			}
			$perf_reviews_tot_rating = 0;	$perf_reviews_avg_rating = 0;
			$perf_cnt = sizeof($performance_rating_count);
			$perf_reviews_tot_rating = array_sum($performance_rating_count);
			if($perf_reviews_tot_rating > 0){
				$perf_reviews_avg_rating = $perf_reviews_tot_rating/$perf_cnt;
				$perf_reviews_avg_rating = $this->reviewRatingslab($perf_reviews_avg_rating);
				$perf_reviews_avg_rating_proportion = (($perf_reviews_avg_rating*100)/10)*2;
			}
			$serv_reviews_tot_rating = 0; $serv_reviews_avg_rating = 0;
			$serv_cnt = sizeof($service_rating_count);
			$serv_reviews_tot_rating = array_sum($service_rating_count);
			if($serv_reviews_tot_rating > 0){
				$serv_reviews_avg_rating = $serv_reviews_tot_rating/$serv_cnt;
				$serv_reviews_avg_rating = $this->reviewRatingslab($serv_reviews_avg_rating);
				$serv_reviews_avg_rating_proportion = (($serv_reviews_avg_rating*100)/10)*2;
			}
			$ext_reviews_avg_rating = ($ext_reviews_avg_rating > 0) ? $ext_reviews_avg_rating : 0;
			$ext_reviews_avg_rating_proportion = ($ext_reviews_avg_rating_proportion > 0) ? $ext_reviews_avg_rating_proportion : 0;
			$int_reviews_avg_rating = ($int_reviews_avg_rating > 0) ? $int_reviews_avg_rating :0;
			$int_reviews_avg_rating_proportion = ($int_reviews_avg_rating_proportion > 0) ? $int_reviews_avg_rating_proportion : 0;
			$perf_reviews_avg_rating = ($perf_reviews_avg_rating > 0) ? $perf_reviews_avg_rating : 0;
			$perf_reviews_avg_rating_proportion = ($perf_reviews_avg_rating_proportion > 0) ? $perf_reviews_avg_rating_proportion : 0;
			$serv_reviews_avg_rating = ($serv_reviews_avg_rating > 0) ? $serv_reviews_avg_rating :0;
			$serv_reviews_avg_rating_proportion = ($serv_reviews_avg_rating_proportion > 0) ? $serv_reviews_avg_rating_proportion : 0;
			$all_reviews_avg_rating = ($all_reviews_avg_rating > 0) ? $all_reviews_avg_rating : 0;
			$all_reviews_avg_rating_proportion = ($all_reviews_avg_rating_proportion > 0) ? $all_reviews_avg_rating_proportion : 0;
			if($all_reviews_avg_rating < 2){
				$all_reviews_avg_grade = "Poor";
			}elseif($all_reviews_avg_rating >= 2 and $all_reviews_avg_rating < 3){
				$all_reviews_avg_grade = "Fair";
			}elseif($all_reviews_avg_rating >= 3 and $all_reviews_avg_rating < 4){
				$all_reviews_avg_grade = "Average";
			}elseif($all_reviews_avg_rating >= 4 and $all_reviews_avg_rating < 5){
				$all_reviews_avg_grade = "Good";
			}elseif($all_reviews_avg_rating == 5){
				$all_reviews_avg_grade = "Excellent";
			}
			$res['ext_reviews_avg_rating'] = $ext_reviews_avg_rating;
			$res['ext_reviews_avg_rating_proportion'] = $ext_reviews_avg_rating_proportion;
			$res['int_reviews_avg_rating'] = $int_reviews_avg_rating;
			$res['int_reviews_avg_rating_proportion'] = $int_reviews_avg_rating_proportion;
			$res['perf_reviews_avg_rating'] = $perf_reviews_avg_rating;
			$res['perf_reviews_avg_rating_proportion'] = $perf_reviews_avg_rating_proportion;
			$res['serv_reviews_avg_rating'] = $serv_reviews_avg_rating;
			$res['serv_reviews_avg_rating_proportion'] = $serv_reviews_avg_rating_proportion;
			$res['all_reviews_avg_rating'] = $all_reviews_avg_rating;
			$res['all_reviews_avg_rating_proportion'] = $all_reviews_avg_rating_proportion;
			$res['all_reviews_avg_grade'] = $all_reviews_avg_grade;
			if(empty($cnt) and $cnt==""){ $cnt=0;}
			$res['overall_cnt'] = $cnt ? $cnt : 0;

			return $res;
		}
    }

	function intInsertUpdateReviewDescWidget($aParameters,$sTableName){
		$aParameters['create_date'] = date('Y-m-d H:i:s');
		$aParameters['update_date'] = date('Y-m-d H:i:s');
		$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
		$iRes=$this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->reviewdesckey);
		return $iRes;
	}


	function boolDeleteReviewDescWidget($id=""){
		if(!empty($id)){
			$sSql="delete from REVIEW_DESCRIPTION where id='".$id."'";
			$iRes=$this->sql_delete_data($sSql);
			$this->cache->searchDeleteKeys($this->reviewdesckey);
			return $iRes;
		}
	}


	function arrGetReviewDesc($select_param){
		list($id,$category_id,$brand_id,$product_info_id,$product_id,$description,$status,$orderby,$groupby,$start,$limit) = array($select_param['id'],$select_param['category_id'],$select_param['brand_id'],$select_param['product_info_id'],$select_param['product_id'],$select_param['description'],$select_param['status'],$select_param['orderby'],$select_param['groupby'],$select_param['start'],$select_param['limit']);
		$keyArr[] = $this->reviewdesckey.'_arrGetReviewDesc';
		$arrWhereClause[] = "category_id=$category_id";
		$keyArr[] = $category_id;
		if(is_array($id)){
				$id = implode(",",$id);
		}
		if(!empty($id)){
				$arrWhereClause[] = "id in ($id)";
				$keyArr[] = $id;
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
				$arrWhereClause[] = "brand_id=".$brand_id;
				$keyArr[] = $brand_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
				$arrWhereClause[] = "product_info_id=$product_info_id";
				$keyArr[] = $product_info_id;
		}else{$keyArr[] = -1;}
		if(!empty($product_id)){
				$arrWhereClause[] = "product_id=$product_id";
				$keyArr[] = $product_id;
		}else{$keyArr[] = -1;}
		if($status != ''){
			$arrWhereClause[] = "status=$status";
            $keyArr[] = $status;
		}else{$keyArr[] = -1;}		
		/*
		if($status==0 || $status==1){
				$arrWhereClause[] = "status=$status";
				$keyArr[] = $status;
		}else if($status != ''){
				$arrWhereClause[] = "status=1";
				$keyArr[] = "status_1";
		}
		*/
		if(count($arrWhereClause)>0){
			$strWhereClause = " WHERE ".implode(' and ',$arrWhereClause);
		}
		if($orderby){
				$strOrderBy = $orderby;
		}else{
				$strOrderBy = " order by create_date desc ";
		}
		$keyArr[] = $strOrderBy;
		if(!empty($start)){
				$arrlimit[] = $start;
				$keyArr[] = $start;
		}else{$keyArr[] = -1;}
		if(!empty($limit)){
				$arrlimit[] = $limit;
				$keyArr[] = $limit;
		}else{$keyArr[] = -1;}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);
		if(!empty($result)){ return $result;}
		if(count($arrlimit)>0){
			$strLimit = " limit ".implode(',',$arrlimit);
		}
		$sql = "SELECT * FROM REVIEW_DESCRIPTION $strWhereClause $strOrderBy $strLimit";
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	 function reviewRatingslab($rating){
		if($rating == ""){
				$res = 0;
		}else{
				if(($rating>=1.0) && ($rating < 1.5)){
						$res = 1;
				}else if(($rating >=1.5) && ($rating < 2.0)){
						$res = 1.5;
				}else if(($rating >=2.0) && ($rating < 2.5)){
						$res = 2;
				}else if(($rating >=2.5) && ($rating < 3.0)){
						$res = 2.5;
				}else if(($rating >=3.0) && ($rating < 3.5)){
						$res = 3;
				}else if(($rating >=3.5) && ($rating < 4.0)){
						$res = 3.5;
				}else if(($rating >=4.0) && ($rating < 4.5)){
						$res = 4;
				}else if(($rating >=4.5) && ($rating < 5.0)){
						$res = 4.5;
				}else if($rating >= 5.0 ) {
						$res = 5;
				}
		}
		return $res;
	}
}
