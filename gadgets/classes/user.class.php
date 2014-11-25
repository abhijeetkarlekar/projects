<?php
/**
* @brief class is used add,update,delete,get user details.
* @author Rajesh Ujade
* @version 1.0
* @created 11-Nov-2010 5:09:31 PM
*/
class user extends DbOperation{
	var $cache;
	var $userkey;
	var $editorkey;
	var $productkey;
	/**Initialize the consturctor.*/
	function user(){
		$this->cache = new Cache;
		$this->userkey = MEMCACHE_MASTER_KEY."user";
		$this->oncarskey = MEMCACHE_MASTER_KEY;
		$this->editorkey = MEMCACHE_MASTER_KEY."editor";
		$this->productkey = MEMCACHE_MASTER_KEY."product";
	}
	/**
	* @note function is used to insert the user details into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $user_profile_id.
	* retun integer.
	*/
        function intInsertUserDetail($insert_param){
                global $utmsrc;
                $insert_param['create_date'] = date('Y-m-d H:i:s');
                $insert_param['update_date'] = date('Y-m-d H:i:s');
                $insert_param['utm_source']   = !empty($_POST['utm_source']) ? $_POST['utm_source'] : $utmsrc['utmcsr'];
                $insert_param['utm_medium']   = !empty($_POST['utm_medium']) ? $_POST['utm_medium'] : $utmsrc['utmcmd'];
                $insert_param['utm_campaign'] = !empty($_POST['utm_campaign']) ? $_POST['utm_medium'] : $utmsrc['utmccn'];
                $insert_param['utm_term']     = !empty($_POST['utm_term']) ? $_POST['utm_term'] : $utmsrc['utmctr'];
                $sSql = $this->getInsertUpdateSql("USER_INFO",array_keys($insert_param),array_values($insert_param));
                $user_profile_id = $this->insertUpdate($sSql);
                $this->cache->searchDeleteKeys($this->userkey);
                return $user_profile_id;
        }
	/**
	* @note function is used to insert the sms acknowledgement details into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $user_profile_id.
	* retun integer.
	*/
	function intInsertSmsAck($insert_param){
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("ONCARS_SMS_DELIVERY_REPORT",array_keys($insert_param),array_values($insert_param));
		$sdr_id = $this->insertUpdate($sSql);
		return $sdr_id;
	}
    /**
    * @note function is used to update the sms acknowledgement details into the database.
    * @param an associative array $update_param.
    * @pre $update_param must be valid associative array.
    * @post an integer $sdr_id.
    * retun integer.
    */

    function intUpdateSmsAck($update_param,$arr_clause_fields){
            $update_param['update_date'] = date('Y-m-d H:i:s');
            $sSql = $this->getUpdateSql("ONCARS_SMS_DELIVERY_REPORT",array_keys($update_param),array_values($update_param),array_keys($arr_clause_fields),array_values($arr_clause_fields));
            $is_update = $this->update($sSql);
            return $is_update;
    }
	/**
	* @note function is used to update the user info into the database.
	* @param an associative array $update_param.
	* @param an integer $user_profile_id.
	* @pre $update_param must be valid associative array and $user_profile_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* retun boolean.
	*/
	function intUpdateUserDetail($user_profile_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("USER_INFO",array_keys($update_param),array_values($update_param),"user_profile_id",$user_profile_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->userkey);
		return $isUpdate;
	}
	/**
	* @note function is used to insert the editor details into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $editor_id.
	* retun integer.
	*/
	function intInsertEditorDetails($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("EDITOR_INFO",array_keys($insert_param),array_values($insert_param));
		$editor_id = $this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->editorkey);
		return $editor_id;
	}
	/**
	* @note function is used to update the editor information in the database.
	* @param $update_param is an associative array
	* @pre $update_param must be valid associative array.
	* @post an $editor_id.
	* retun integer.
	*/
	function boolUpdateEditorDetails($update_param){
		$update_param['create_date'] = date('Y-m-d H:i:s');
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("EDITOR_INFO",array_keys($update_param),array_values($update_param));
		$res = $this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->editorkey);
		return $res;
	}
	/**
	* @note function is used  delete editor info
	*
	* @param editor_id ,table_name
	* @pre  editor id is single editor id of integer type
	* @pre  table_name is database table name
	* @post return true if successful , false if error occurs
	*/
	function booldeleteEditorInfo($editor_id="",$table_name="EDITOR_INFO"){
		$sSql="delete from $table_name where editor_id = $editor_id";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->editorkey);
		return $iRes;
	}

	/**
	* @note function is used to get editor details
	*
	* @param an integer $editor_id
	* @param a string $editor_name
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetSmsDeliveryDetails($sdr_id="", $user_profile_id="", $a2w_ack_id="", $a2w_status="", $carrier_status="", $create_date="", $update_date="", $startlimit="", $cnt=""){
		$keyArr[] = $this->userkey."_smsdevdetail";
		if($sdr_id != ''){
			$whereClauseArr[] = " sdr_id = $sdr_id ";
			$keyArr[] = $sdr_id;
		}else{$keyArr[] =-1;}
		if($user_profile_id != ''){
			$whereClauseArr[] = " user_profile_id = $user_profile_id ";
			$keyArr[] = $user_profile_id;
		}else{$keyArr[] =-1;}
		if($a2w_ack_id != ""){
			$whereClauseArr[] = " a2w_ack_id = '".trim($a2w_ack_id)."'";
			$keyArr[] = $a2w_ack_id;
		}else{$keyArr[] =-1;}
		if($a2w_status != ""){
			$whereClauseArr[] = " a2w_status = $a2w_status ";
			$keyArr[] = $a2w_status;
		}else{$keyArr[] =-1;}
		if($carrier_status != ""){
			$whereClauseArr[] = " carrier_status = '".$carrier_status ."'";
			$keyArr[] = $carrier_status;
		}else{$keyArr[] =-1;}
		if($create_date != ""){
			$whereClauseArr[] = " date(create_date) = '".$create_date ."'";
			$keyArr[] = $create_date;
		}else{$keyArr[] =-1;}
		if($update_date != ""){
			$whereClauseArr[] = " date(update_date) = '".$update_date ."'";
			$keyArr[] = $update_date;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			 $keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$sSql="SELECT * FROM ONCARS_SMS_DELIVERY_REPORT $whereClauseStr $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get editor details
	*
	* @param an integer $editor_id
	* @param a string $editor_name
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetEditorDetails($editor_id,$editor_name="",$startlimit="",$cnt="",$status="1"){
		$keyArr[] = $this->editorkey."_detail";
		if($editor_id != ''){
			$whereClauseArr[] = " editor_id=$editor_id ";
			$keyArr[] = $editor_id;
		}else{$keyArr[] =-1;}
		if($editor_name != ''){
			$whereClauseArr[] = " editor_name=$editor_name ";
			$keyArr[] = $editor_name;
		}else{$keyArr[] =-1;}
		if($status!=""){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		//echo $key."<br>";
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$sSql="SELECT * FROM EDITOR_INFO $whereClauseStr $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get editor info details
	*
	* @param an integer $editor_id
	* @param a string $editor_name
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function arrGetEditorInfoDetails($editor_ids="",$category_ids="",$editor_name="",$designation="",$phone_no="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->editorkey."_arrGetEditorInfoDetails";
		if(is_array($editor_ids)){
			$editor_ids=implode(",",$editor_ids);
		}
		if(is_array($category_ids)){
			$category_ids=implode(",",$category_ids);
		}
		if($editor_ids != ''){
			$whereClauseArr[] = " editor_id in ($editor_ids) ";
			$keyArr[] = $editor_ids;
		}else{$keyArr[] =-1;}
		if($category_ids!=""){
			$whereClauseArr[] = "category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] =-1;}
		if($editor_name != ''){
			$whereClauseArr[] = " editor_name=$editor_name ";
			$keyArr[] = $editor_name;
		}else{$keyArr[] =-1;}
		if($designation != ''){
			$whereClauseArr[] = " designation=$designation ";
			$keyArr[] = $designation;
		}else{$keyArr[] =-1;}
		if($phone_no != ''){
			$whereClauseArr[] = " phone_no=$phone_no ";
			$keyArr[] = $phone_no;
		}else{$keyArr[] =-1;}
		if($status!=""){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby == ""){
			$orderby = "order by create_date DESC";
			$keyArr[] ="order_".str_replace(" ","_",$orderby);

		}else{$keyArr[] =-1;}
		$key = implode('_',$keyArr);
		if($result = $this->cache->get($key)){ return $result;}
		$sSql="SELECT *,DATE_FORMAT(create_date,'%d/%m/%Y') as disp_date FROM EDITOR_INFO $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get user info details
	*
	* @param an array/a string/an integer $user_profile_ids.
	* @param an array/a string/an integer $category_ids.
	* @param an array/a string/an integer $brand_ids.
	* @param an array/a string/an integer $product_info_ids.
	* @param an array/a string/an integer $product_ids.
	* @param an array/a string/an integer $city_ids.
	* @param an array/a string/an integer $profile_name.
	* @param an integer $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	/*function arrGetUserInfoDetails($user_profile_ids="",$category_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$city_ids="",$profile_name="",$status="1",$startlimit="",$cnt="",$is_verified="",$create_date=""){
	}*/

	function arrGetUserInfoDetails($user_profile_ids="",$category_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$city_ids="",$profile_name="",$status="1",$startlimit="",$cnt="",$is_verified="",$create_date="",$request_type="",$mobileno='',$email='',$utm_source='',$dist_ids='',$start_date="",$end_date=""){
		$keyArr[] = $this->userkey."_infodetail";
		if(is_array($user_profile_ids)){
			$user_profile_ids = implode(",",$user_profile_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($product_info_ids)){
			$product_info_ids = implode(",",$product_info_ids);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($city_ids)){
			$city_ids = implode(",",$city_ids);
		}
		if(is_array($dist_ids)){
            $dist_ids = implode(",",$dist_ids);
        }
		if($user_profile_ids!=""){
			$whereClauseArr[] = "user_profile_id in ($user_profile_ids)";
			$keyArr[] = $user_profile_ids;
		}else{$keyArr[] =-1;}
		if($start_date!=""){
                $whereClauseArr[] = "date(create_date) >='$start_date'";
                $keyArr[] = $start_date;
        }else{$keyArr[] =-1;}
        if($end_date!=""){
                $whereClauseArr[] = "date(create_date) <='$end_date'";
                $keyArr[] = $end_date;
        }else{$keyArr[] =-1;}
		if($category_ids!=""){
			$whereClauseArr[] = "category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] =-1;}
		if($brand_ids!=""){
			$whereClauseArr[] = "brand_id in ($brand_ids)";
			$keyArr[] = $brand_ids;
		}else{$keyArr[] =-1;}
		if($product_info_ids!=""){
			$whereClauseArr[] = "product_info_id in ($product_info_ids)";
			$keyArr[] = $product_info_ids;
		}else{$keyArr[] =-1;}
		if($product_ids!=""){
			$whereClauseArr[] = "product_id in ($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] =-1;}
		if($city_ids!=""){
			$whereClauseArr[] = "city_id in ($city_ids)";
			$keyArr[] = $city_ids;
		}else{$keyArr[] =-1;}
		if($dist_ids!=""){
                $whereClauseArr[] = "district_id in ($dist_ids)";
                $keyArr[] = $dist_ids;
        }else{$keyArr[] =-1;}
		if($profile_name!=""){
			$whereClauseArr[] = "profile_name in ($profile_name)";
			$keyArr[] = $profile_name;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if($request_type != ''){
			$whereClauseArr[] = "request_type = $request_type";
			$keyArr[] = $request_type;
		}else{$keyArr[] =-1;}
		if($mobileno != ''){
			$whereClauseArr[] = " mobile = '$mobileno'";
			$keyArr[] = $mobileno;
		}else{$keyArr[] =-1;}
		if($email != ''){
			$whereClauseArr[] = " email = '$email'";
			$keyArr[] = $email;
		}else{$keyArr[] =-1;}
		if($utm_source != ''){
                $whereClauseArr[] = " utm_source = '$utm_source'";
                $keyArr[] = $utm_source;
        }else{$keyArr[] =-1;}
		if($is_verified != ''){
			$whereClauseArr[] = "is_verified = $is_verified ";
			$keyArr[] = $is_verified;
		}else{$keyArr[] =-1;}
		if($create_date != ''){
			$whereClauseArr[] = "date(create_date) = $create_date ";
			$keyArr[] = $create_date;
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$orderby = "order by create_date DESC ";
		$key = implode('_',$keyArr);
		if($result = $this->cache->get($key)){ return $result;}
		$sSql="SELECT * from USER_INFO $whereClauseStr $orderby $limitStr";
		$result =$this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}
	/**
	* @note function is used to get user info count
	*
	* @param an array/a string/an integer $user_profile_ids.
	* @param an array/a string/an integer $category_ids.
	* @param an array/a string/an integer $brand_ids.
	* @param an array/a string/an integer $product_info_ids.
	* @param an array/a string/an integer $product_ids.
	* @param an array/a string/an integer $city_ids.
	* @param an array/a string/an integer $profile_name.
	* @param an integer $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @pre not required.
	*
	* @post associative array.
	* @post return array if successful , 0 if error occurs
	*
	*/
	function getUserInfoCount($user_profile_ids="",$category_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$city_ids="",$profile_name="",$status="1",$startlimit="",$cnt="",$utm_source='',$dist_ids='',$start_date="",$end_date=""){
		$keyArr[] = $this->userkey."_info_count";
		if(is_array($user_profile_ids)){
			$user_profile_ids = implode(",",$user_profile_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($product_info_ids)){
			$product_info_ids = implode(",",$product_info_ids);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($dist_ids)){
 		       $dist_ids = implode(",",$dist_ids);
		}
		if(is_array($city_ids)){
			$city_ids = implode(",",$city_ids);
		}
		if($user_profile_ids!=""){
			$whereClauseArr[] = "user_profile_id in ($user_profile_ids)";
			$keyArr[] = $user_profile_ids;
		}else{$keyArr[] =-1;}
		if($start_date!=""){
                $whereClauseArr[] = "date(create_date) >='$start_date'";
                $keyArr[] = $start_date;
        }else{$keyArr[] =-1;}
		if($end_date!=""){
                $whereClauseArr[] = "date(create_date) <='$end_date'";
                $keyArr[] = $end_date;
        }else{$keyArr[] =-1;}
		if($category_ids!=""){
			$whereClauseArr[] = "category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] =-1;}
		if($brand_ids!=""){
			$whereClauseArr[] = "brand_id in ($brand_ids)";
			$keyArr[] = $brand_ids;
		}else{$keyArr[] =-1;}
		if($product_info_ids!=""){
			$whereClauseArr[] = "product_info_id in ($product_info_ids)";
			$keyArr[] = $product_info_ids;
		}else{$keyArr[] =-1;}
		if($product_ids!=""){
			$whereClauseArr[] = "product_id in ($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] =-1;}
		if($dist_ids!=""){
			$whereClauseArr[] = "district_id in ($dist_ids)";
			$keyArr[] = $dist_ids;
		}else{$keyArr[] =-1;}
		if($city_ids!=""){
			$whereClauseArr[] = "city_id in ($city_ids)";
			$keyArr[] = $city_ids;
		}else{$keyArr[] =-1;}
		if($profile_name!=""){
			$whereClauseArr[] = "profile_name in ($profile_name)";
			$keyArr[] = $profile_name;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if($utm_source != ''){
                $whereClauseArr[] = " utm_source = '$utm_source'";
                $keyArr[] = $utm_source;
        }else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$orderby = "order by create_date DESC ";
		$key = implode('_',$keyArr);
		if($result = $this->cache->get($key)){ return $result[0]['cnt'];}
		$sSql="SELECT count(user_profile_id) as cnt from USER_INFO $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		$count = $result[0]['cnt'];
		return $count;
	}

	function isVerifiedUserInfoDetails($user_profile_ids="",$category_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$city_ids="",$profile_name="",$status="1",$startlimit="",$cnt="",$mobile="",$attempt_counter="",$verification_code="",$request_type=""){
		$keyArr[] = $this->userkey."_verified_infodetail";
		if(is_array($user_profile_ids)){
			$user_profile_ids = implode(",",$user_profile_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if(is_array($brand_ids)){
			$brand_ids = implode(",",$brand_ids);
		}
		if(is_array($product_info_ids)){
			$product_info_ids = implode(",",$product_info_ids);
		}
		if(is_array($product_ids)){
			$product_ids = implode(",",$product_ids);
		}
		if(is_array($city_ids)){
			$city_ids = implode(",",$city_ids);
		}
		if($user_profile_ids!=""){
			$whereClauseArr[] = "user_profile_id in ($user_profile_ids)";
			$keyArr[] = $user_profile_ids;
		}else{$keyArr[] =-1;}
		if($category_ids!=""){
			$whereClauseArr[] = "category_id in ($category_ids)";
			$keyArr[] = $category_ids;
		}else{$keyArr[] =-1;}
		if($brand_ids!=""){
			$whereClauseArr[] = "brand_id in ($brand_ids)";
			$keyArr[] = $brand_ids;
		}else{$keyArr[] =-1;}
		if($product_info_ids!=""){
			$whereClauseArr[] = "product_info_id in ($product_info_ids)";
			$keyArr[] = $product_info_ids;
		}else{$keyArr[] =-1;}
		if($product_ids!=""){
			$whereClauseArr[] = "product_id in ($product_ids)";
			$keyArr[] = $product_ids;
		}else{$keyArr[] =-1;}
		if($city_ids!=""){
			$whereClauseArr[] = "city_id in ($city_ids)";
			$keyArr[] = $city_ids;
		}else{$keyArr[] =-1;}
		if($profile_name!=""){
			$whereClauseArr[] = "profile_name = '$profile_name'";
			$keyArr[] = $profile_name;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if($mobile != ''){
			$whereClauseArr[] = "mobile= '$mobile'";
			$keyArr[] = $mobile;
		}else{$keyArr[] =-1;}
		if($attempt_counter != ''){
			$whereClauseArr[] = "attempt_counter=$attempt_counter";
			$keyArr[] = $attempt_counter;
		}else{$keyArr[] =-1;}
		if($request_type != ''){
			$whereClauseArr[] = "request_type=$request_type";
			$keyArr[] = $request_type;
		}else{$keyArr[] =-1;}
		if($verification_code != ''){
			$whereClauseArr[] = "verification_code=$verification_code";
			$keyArr[] = $verification_code;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$sSql="SELECT * from LEAD_VERIFICATION $whereClauseStr $limitStr";
		$result =$this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	function intInsertisVerifiedUserDetail($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("LEAD_VERIFICATION",array_keys($insert_param),array_values($insert_param));
		$user_profile_id = $this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->userkey);
		return $user_profile_id;
	}

	function UpdateAttemptCountisVerified($brand_id,$product_name_id,$product_id,$city_id,$sMobile,$request_type){
		//$update_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = "select attempt_counter from  LEAD_VERIFICATION where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
		$result =$this->select($sSql);
		if(!empty($result)){
			$user_profile_id = $result[0]['user_profile_id'];
			$attempt_counter = $result[0]['attempt_counter']+1;
		}else{ $attempt_counter =1;}

		if($attempt_counter>3){

			$sql = "update LEAD_VERIFICATION set attempt_counter=1 where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
			$isUpdate = $this->update($sql);
			return "atmpfull"; exit;
		}else{
			$attempt_counter = 1;
		}
		if(!empty($user_profile_id)){
			$sql = "update LEAD_VERIFICATION set attempt_counter= $attempt_counter,verification_code='$verifycode',is_verified='$is_verified' where user_profile_id=$user_profile_id";
		}else{
			$sql = "update LEAD_VERIFICATION set attempt_counter= $attempt_counter,verification_code='$verifycode',is_verified='$is_verified' where brand_id=$brand_id and product_info_id
			=$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
		}
		$isUpdate = $this->update($sql);
		return $attempt_counter;
	}

	function UpdatePreAttemptCountisVerified($brand_id,$product_name_id,$product_id,$city_id,$sMobile,$request_type){
		//$update_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = "select attempt_counter from  LEAD_VERIFICATION where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
		$result =$this->select($sSql);
		if(!empty($result)){
			$attempt_counter = $result[0]['attempt_counter'];

		$sql = "update LEAD_VERIFICATION set attempt_counter= $attempt_counter where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile";
		$isUpdate = $this->update($sql);
		////$this->cache->searchDeleteKeys($this->userkey);
		return $attempt_counter;
		}
	}

	function UpdatePostAttemptCountisVerified($brand_id,$product_name_id,$product_id,$city_id,$sMobile,$request_type){
		//$update_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = "select * from  LEAD_VERIFICATION where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
		$result =$this->select($sSql);
		if(!empty($result)){
		$attempt_counter = $result[0]['attempt_counter']+1;
		$sql = "update LEAD_VERIFICATION set attempt_counter= $attempt_counter where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
		$isUpdate = $this->update($sql);
		////$this->cache->searchDeleteKeys($this->userkey);
		return $attempt_counter;
		}
	}

	function UpdateisVerifiedStatus($brand_id,$product_name_id,$product_id,$city_id,$sMobile,$request_type,$is_ver){
		//$update_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = "select attempt_counter from  LEAD_VERIFICATION where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
		$result =$this->select($sSql);
		if(!empty($result)){
		 $sql = "update LEAD_VERIFICATION set is_verified = $is_ver where brand_id=$brand_id and product_info_id =$product_name_id and product_id=$product_id and city_id=$city_id and mobile=$sMobile and request_type=$request_type";
		$isUpdate = $this->update($sql);
		////$this->cache->searchDeleteKeys($this->userkey);
		return $attempt_counter;
		}
	}

	function intInsertVerificationDetail($insert_param){
		//$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("VERIFIED_NUMBERS",array_keys($insert_param),array_values($insert_param));
		$user_profile_id = $this->insertUpdate($sSql);
		return $verified_id;
	}


	function isMobileVerifiedDetails($user_verified_ids="",$category_ids="",$mobile="",$verification_code="",$email=''){
		$keyArr[] = $this->userkey."_mobileverified";
		if(is_array($user_verified_ids)){
			$user_verified_ids = implode(",",$user_verified_ids);
		}else{$keyArr[] =-1;}
		if($user_verified_ids!=""){
			$whereClauseArr[] = "verified_id in ($user_verified_ids)";
			$keyArr[] = $user_verified_ids;
		}else{$keyArr[] =-1;}
		if($mobile != ''){
			$whereClauseArr[] = "mobile_number= '$mobile'";
			$keyArr[] = $mobile;
		}else{$keyArr[] =-1;}
		if($email != ''){
			$whereClauseArr[] = "email_id = '$email'";
			$keyArr[] = $email;
		}else{$keyArr[] =-1;}
		if($verification_code != ''){
			$whereClauseArr[] = "verification_code=$verification_code";
			$keyArr[] = $verification_code;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		//$orderby = "order by create_date DESC ";
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$sSql="SELECT * from VERIFIED_NUMBERS $whereClauseStr $limitStr";
		$result =$this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	function intInsertSystemVerificationDetail($insert_param){
		//$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("SYSTEM_VERIFIED_NUMBERS",array_keys($insert_param),array_values($insert_param));
		$user_profile_id = $this->insertUpdate($sSql);
		return $verified_id;
	}


	function isMobileSystemVerifiedDetails($user_verified_ids="",$category_ids="",$mobile=""){
		$keyArr[] = $this->userkey."_ismobilesysverdetail";
		if(is_array($user_verified_ids)){
			$user_verified_ids = implode(",",$user_verified_ids);
		}else{$keyArr[] =-1;}
		if($user_verified_ids!=""){
			$whereClauseArr[] = "verified_id in ($user_verified_ids)";
			$keyArr[] = $user_verified_ids;
		}else{$keyArr[] =-1;}
		if($mobile != ''){
			$whereClauseArr[] = "mobile_number= '$mobile'";
			$keyArr[] = $mobile;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		//$orderby = "order by create_date DESC ";
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$sSql="SELECT * from SYSTEM_VERIFIED_NUMBERS $whereClauseStr $limitStr";
		$result =$this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
	}

	function intInsertSubcribeUserDetail($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("NEWS_LETTER_SUBCRIPTION",array_keys($insert_param),array_values($insert_param));
		$user_profile_id = $this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->userkey);
		return $user_profile_id;
	}
	function intInsertViewedCarInfoDetail($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("VIEWED_CAR_INFO",array_keys($insert_param),array_values($insert_param));
		$viewed_car_id = $this->insertUpdate($sSql);
	}
	function intUpdateViewedCarInfoDetail($viewed_car_id,$update_param){
	    $update_param['update_date'] = date('Y-m-d H:i:s');
	    $sql = $this->getUpdateSql("VIEWED_CAR_INFO",array_keys($update_param),array_values($update_param),"viewed_car_id",$viewed_car_id);
	    $isUpdate = $this->update($sql);
	    $this->cache->searchDeleteKeys($this->userkey);
	    return $isUpdate;
	}

	function arrGetViewedCarInfoDetails($viewed_car_id="", $verified_id="", $brand_id="", $product_name_id="", $product_id="", $city_id="", $category_id="", $status="",$check_date="1", $startlimit="", $cnt=""){
		$keyArr[] = $this->productkey."_viewcardetail";
		if($viewed_car_id != ''){
			$whereClauseArr[] = " viewed_car_id = $viewed_car_id ";
			$keyArr[] = $viewed_car_id;
		}else{$keyArr[] =-1;}
		if($verified_id != ''){
			$whereClauseArr[] = " verified_id = $verified_id ";
			$keyArr[] = $verified_id;
		}else{$keyArr[] =-1;}
		if($brand_id != ""){
			$whereClauseArr[] = " brand_id = '".trim($brand_id)."'";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}
		if($product_name_id != ""){
			$whereClauseArr[] = " product_name_id = $product_name_id ";
			$keyArr[] = $product_name_id;
		}else{$keyArr[] =-1;}
		if($product_id != ""){
			$whereClauseArr[] = " product_id = '".$product_id ."'";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if($city_id != ""){
			$whereClauseArr[] = " city_id = '".$city_id ."'";
			$keyArr[] = $city_id;
		}else{$keyArr[] =-1;}
		if($category_id != ""){
			$whereClauseArr[] = " category_id = '".$category_id ."'";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($status != ""){
			$whereClauseArr[] = " status = '".$status ."'";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		$create_date = date('Y-m-d');
		if($check_date != ""){
			$whereClauseArr[] = "create_date >= '$create_date 00:00:00'";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
		$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$sSql="SELECT * FROM VIEWED_CAR_INFO $whereClauseStr $limitStr";
		$result = $this->select($sSql);
		return $result;
	}

	function IsVerifiedUniqueUserInfoDetails($unique_user_profile_id = "", $user_profile_id = "", $category_id = "",$brand_id = "",$product_info_id = "",$product_id = "",$city_id = "", $profile_name = "", $email = "", $mobile = "", $status = "1", $is_sms_verified = "", $is_valid = "", $is_call_verified = "", $startlimit="", $cnt=""){
		$keyArr[] = $this->userkey."_verified_unquser_detail";
		if($unique_user_profile_id != ''){
			$whereClauseArr[] = " unique_user_profile_id = $unique_user_profile_id ";
			$keyArr[] = $unique_user_profile_id;
		}else{$keyArr[] =-1;}
		if($user_profile_id != ''){
			$whereClauseArr[] = " user_profile_id = $user_profile_id ";
			$keyArr[] = $user_profile_id;
		}else{$keyArr[] =-1;}
		if($brand_id != ""){
			$whereClauseArr[] = " brand_id = '".trim($brand_id)."'";
			$keyArr[] = $brand_id;
		}else{$keyArr[] =-1;}
		if($product_info_id != ""){
			$whereClauseArr[] = " product_info_id = $product_info_id ";
			$keyArr[] = $product_info_id;
		}else{$keyArr[] =-1;}
		if($product_id != ""){
			$whereClauseArr[] = " product_id = '".$product_id ."'";
			$keyArr[] = $product_id;
		}else{$keyArr[] =-1;}
		if($city_id != ""){
			$whereClauseArr[] = " city_id = '".$city_id ."'";
			$keyArr[] = $city_id;
		}else{$keyArr[] =-1;}
		if($category_id != ""){
			$whereClauseArr[] = " category_id = '".$category_id ."'";
			$keyArr[] = $category_id;
		}else{$keyArr[] =-1;}
		if($profile_name != ""){
			$whereClauseArr[] = " profile_name = '".$profile_name ."'";
			$keyArr[] = $profile_name;
		}else{$keyArr[] =-1;}
		if($email != ""){
			$whereClauseArr[] = " email = '".$email ."'";
			$keyArr[] = $email;
		}else{$keyArr[] =-1;}
		if($mobile != ""){
			$whereClauseArr[] = " mobile = '".$mobile ."'";
			$keyArr[] = $mobile;
		}else{$keyArr[] =-1;}
		if($status != ""){
			$whereClauseArr[] = " status = '".$status ."'";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if($is_sms_verified != ""){
			$whereClauseArr[] = " is_sms_verified = '".$is_sms_verified ."'";
			$keyArr[] = $is_sms_verified;
		}else{$keyArr[] =-1;}
		if($is_valid != ""){
			$whereClauseArr[] = " is_valid = '".$is_valid ."'";
			$keyArr[] = $is_valid;
		}else{$keyArr[] =-1;}
		if($is_call_verified != ""){
			$whereClauseArr[] = " is_call_verified = '".$is_call_verified ."'";
			$keyArr[] = $is_call_verified;
		}else{$keyArr[] =-1;}
		$create_date = date('Y-m-d');
		if($check_date != ""){
			$whereClauseArr[] = "create_date >= '$create_date 00:00:00'";
			$keyArr[] = $create_date;
		}else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$sSql="SELECT * FROM UNIQUE_USER_INFO  $whereClauseStr $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key, $result);
		return $result;
  	}

	  function CheckNumberVerified($mobile){
		$sql = "select verified_by from UNIQUE_USER_INFO where mobile='$mobile'";
		$result = $this->select($sql);
		if(!empty($result)){
			foreach($result as $ilkey=>$ilValue){
				$verified_by = $ilValue['verified_by'];
			}
		}
		unset($result);
		if($verified_by==1){
			return 1;
		}else{
			$sql = "select is_call_verified,is_sms_verified from UNIQUE_USER_INFO where mobile='mobile'";
			$result = $this->select($sql);
			if(!empty($result)){
				foreach($result as $igkey=>$igValue){
					$is_call_verified = $igValue['is_call_verified'];
					$is_sms_verified = $igValue['is_sms_verified'];
				}
			}
			if($is_call_verified==1){
				return 3;
			}else if($is_sms_verified==1){
				return 2;
			}else{return 0;}
		}
  	}
	function getPageViewsLmitDetail($page_view_id="",$status="1"){
		if(!empty($page_view_id)){
			$whereClauseArr[] = " page_view_id in ($page_view_id)";
		}
		if(!empty($status)){
			$whereClauseArr[] = " status = $status";
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		$sql = "select * from CAMPAIGN_PAGEVIEW_CONTROL $whereClauseStr";
		$result = $this->select($sql);
		return $result;
	}

	function intInsertPageViewsLimitDetail($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sSql = $this->getInsertUpdateSql("CAMPAIGN_PAGEVIEW_CONTROL",array_keys($insert_param),array_values($insert_param));
		$page_view_id = $this->insertUpdate($sSql);
		return $page_view_id;
	}

	function booldeletePageViewsLimit($page_view_id="",$table_name="CAMPAIGN_PAGEVIEW_CONTROL"){
		$sSql="delete from $table_name where page_view_id = $page_view_id";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->editorkey);
		return $iRes;
	}
	function boolUpdatePageViewLimitDetails($page_view_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("CAMPAIGN_PAGEVIEW_CONTROL",array_keys($update_param),array_values($update_param),"page_view_id",$page_view_id);
		$isUpdate = $this->update($sql);
		return $isUpdate;
	}
  	function CheckDetailsendtoAmex($mobile){
      		$sql = "select count(mobile) as cnt from AMEX_USER_INFO where mobile='$mobile'";
        	$result = $this->select($sql);
        	return $result['0']['cnt'];
  	}
    function intInsertAmexUserDetail($insert_param){
            $insert_param['create_date'] = date('Y-m-d H:i:s');
            $insert_param['update_date'] = date('Y-m-d H:i:s');
            $sSql = $this->getInsertUpdateSql("AMEX_USER_INFO",array_keys($insert_param),array_values($insert_param));
            $user_profile_id = $this->insertUpdate($sSql);
            $this->cache->searchDeleteKeys($this->userkey);
            return $user_profile_id;
    }
    function getUserTestinomial($id=''){
		$keyArr[] = $this->oncarskey."_testinomial_getUserTestinomial_";
		$sql = "select * from testimonial";
		if($id>0){ 
			$sql .= " WHERE tid=$id ";
			$keyArr[] = $id;
		}else{$keyArr[] =-1;}
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
    }

	function getActiveUserTestinomial(){
		$keyArr[] = $this->oncarskey."_testinomial_getActiveUserTestinomial_";
		$key = implode('_',$keyArr);
		$result = $this->cache->get($key);if(!empty($result)){ return $result;}
		$sql = "select * from testimonial WHERE status=1 ";
		$result =$this->select($sql);
		$this->cache->set($key, $result);
		return $result;
	}

	function intInsertUpdateTestinomialWidget($aParameters,$sTableName){
		$aParameters['cdate'] = date('Y-m-d H:i:s');
		$aParameters['udate'] = date('Y-m-d H:i:s');
		$sSql=$this->getInsertUpdateSql($sTableName,array_keys($aParameters),array_values($aParameters));
		$iRes=$this->insertUpdate($sSql);
		$this->cache->searchDeleteKeys($this->oncarskey."_testinomial");
		return $iRes;
	}
	function boolDeleteTestinomialWidget($id=""){
		if(!empty($id)){
			$sSql="delete from testimonial where id='".$id."'";
			$iRes=$this->sql_delete_data($sSql);
		}
		$this->cache->searchDeleteKeys($this->oncarskey."_testinomial");
		return $iRes;
	}

	function getCampaignUserDetails($user_ids="",$user_name="",$mobileno='',$email='',$dist_ids='',$city_ids="",$start_date="",$end_date="",$startlimit="",$cnt="",$campaign_api_hit=""){
                $keyArr[] = $this->userkey."_arrGetCampaignUserDetails";
                if(is_array($user_ids)){
                        $user_ids = implode(",",$user_ids);
                }
                if(is_array($city_ids)){
                        $city_ids = implode(",",$city_ids);
                }
                if(is_array($dist_ids)){
                        $dist_ids = implode(",",$dist_ids);
                }
                if($user_ids!=""){
                        $whereClauseArr[] = "user_id in ($user_ids)";
                        $keyArr[] ="user_id_".$user_ids;
                }else{$keyArr[] =-1;}
                if($start_date!=""){
                        $whereClauseArr[] = "date(create_date) >='$start_date'";
                        $keyArr[] = $start_date;
                }else{$keyArr[] =-1;}
                if($end_date!=""){
                        $whereClauseArr[] = "date(create_date) <='$end_date'";
                        $keyArr[] = $end_date;
                }else{$keyArr[] =-1;}
                if($city_ids!=""){
                        $whereClauseArr[] = "city_id in ($city_ids)";
                        $keyArr[] = $city_ids;
                }else{$keyArr[] =-1;}
                if($dist_ids!=""){
                        $whereClauseArr[] = "district_id in ($dist_ids)";
                        $keyArr[] = $dist_ids;
                }else{$keyArr[] =-1;}

                if($user_name!=""){
                        $whereClauseArr[] = "user_name = '$user_name'";
                        $keyArr[] = $user_name;
                }else{$keyArr[] =-1;}
                if($mobileno != ''){
                        $whereClauseArr[] = " mobile_no = '$mobileno'";
                        $keyArr[] = $mobileno;
                }else{$keyArr[] =-1;}
                if($email != ''){
                        $whereClauseArr[] = " email_id = '$email'";
                        $keyArr[] = $email;
                }else{$keyArr[] =-1;}
                if($campaign_api_hit != ''){
                        $keyArr[] = $campaign_api_hit;
                        $whereClauseArr[] = "campaign_api_hit = $campaign_api_hit ";
                }else{$keyArr[] =-1;}
                if($create_date != ''){
                        $whereClauseArr[] = "date(create_date) = $create_date ";
                        $keyArr[] = $create_date;
                }
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                if(!empty($startlimit)){
                        $limitArr[] = $startlimit;
                        $keyArr[] = $startlimit;
                }else{$keyArr[] =-1;}
                if(!empty($cnt)){
                        $limitArr[] = $cnt;
                        $keyArr[] = $cnt;
                }else{$keyArr[] =-1;}
                if(sizeof($limitArr) > 0){
                        $limitStr = " limit ".implode(" , ",$limitArr);
                }
                $orderby = "order by create_date DESC ";
                $key = implode('_',$keyArr);
                $result = $this->cache->get($key);if(!empty($result)){ return $result;}
                $sSql="SELECT * from CAMPAIGN_USER_DETAIL $whereClauseStr $orderby $limitStr";
                $result =$this->select($sSql);
                $this->cache->set($key, $result);
                return $result;
        }

        function intInsertCampaignUserDetail($insert_param){
                $insert_param['create_date'] = date('Y-m-d H:i:s');
                $insert_param['update_date'] = date('Y-m-d H:i:s');
                $sSql = $this->getInsertUpdateSql("CAMPAIGN_USER_DETAIL",array_keys($insert_param),array_values($insert_param));
                $user_id = $this->insertUpdate($sSql);
                $this->cache->searchDeleteKeys($this->userkey);
                return $user_id;
        }

        function intUpdateCampaignUserDetail($user_id,$update_param){
                $update_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $this->getUpdateSql("CAMPAIGN_USER_DETAIL",array_keys($update_param),array_values($update_param),"user_id",$user_id);
                $isUpdate = $this->update($sql);
                $this->cache->searchDeleteKeys($this->userkey);
                return $isUpdate;
        }

        function intInsertSmsDetail($insert_param){
                $insert_param['create_date'] = date('Y-m-d H:i:s');
                $insert_param['update_date'] = date('Y-m-d H:i:s');
                $sSql = $this->getInsertUpdateSql("DELIVERY_STATUS_DETAIL",array_keys($insert_param),array_values($insert_param));
                $user_id = $this->insertUpdate($sSql);
                $this->cache->searchDeleteKeys($this->userkey);
                return $user_id;
        }
        function intUpdateSMSAckDetail($ack_id,$update_param){
                $update_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $this->getUpdateSql("DELIVERY_STATUS_DETAIL",array_keys($update_param),array_values($update_param),"ack_id",$ack_id);
                $isUpdate = $this->update($sql);
                $this->cache->searchDeleteKeys($this->userkey);
                return $isUpdate;
        }

        function getCampaignDetails($cid="",$cname="",$status="1"){
                $keyArr[] = $this->userkey."_getCampaignDetail";
                if($cid!=""){
                        $whereClauseArr[] = "campaign_id in ($cid)";
                        $keyArr[] = $cid;
                }else{$keyArr[] =-1;}
                if($cname!=""){
                        $whereClauseArr[] = "campaign_name in ('$cname')";
                        $keyArr[] = $cname;
                }else{$keyArr[] =-1;}
                if($status!=""){
                        $whereClauseArr[] = "status = $status";
                        $keyArr[] = $status;
                }else{$keyArr[] =-1;}
                if($start_date!=""){
                        $whereClauseArr[] = "date(start_date) >='$start_date'";
                        $keyArr[] = $start_date;
                }else{$keyArr[] =-1;}
                if($end_date!=""){
                        $whereClauseArr[] = "date(end_date) <='$end_date'";
                        $keyArr[] = $end_date;
                }else{$keyArr[] =-1;}

                if($create_date != ''){
                        $whereClauseArr[] = "date(create_date) = $create_date ";
                        $keyArr[] = $create_date;
                }else{$keyArr[] =-1;}
                if(sizeof($whereClauseArr) > 0){
                        $whereClauseStr = " where ".implode(" and ",$whereClauseArr);
                }
                if(!empty($startlimit)){
                        $limitArr[] = $startlimit;
                        $keyArr[] = $startlimit;
                }else{$keyArr[] =-1;}
                if(!empty($cnt)){
                        $limitArr[] = $cnt;
                        $keyArr[] = $cnt;
                }else{$keyArr[] =-1;}
                if(sizeof($limitArr) > 0){
                        $limitStr = " limit ".implode(" , ",$limitArr);
                }
                $orderby = "order by create_date DESC ";
                $key = implode('_',$keyArr);
                $result = $this->cache->get($key);if(!empty($result)){ return $result;}
                $sSql="SELECT * from CAMPAIGN_MASTER $whereClauseStr $orderby $limitStr";
                $result =$this->select($sSql);
                $this->cache->set($key, $result);
                return $result;

        }


    function arrGetSankpalUserInfoDetails($user_profile_ids="",$category_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$city_ids="",$profile_name="",$status="1",$startlimit="",$cnt="",$is_verified="",$create_date="",$request_type="",$mobileno='',$email='',$utm_source='',$dist_ids='',$start_date="",$end_date="",$campign="",$poll="",$question=""){
		$keyArr[] = $this->userkey."_sankpalinfodetail";
		if(is_array($user_profile_ids)){
			$user_profile_ids = implode(",",$user_profile_ids);
		}
		if($user_profile_ids!=""){
			$whereClauseArr[] = "user_profile_id in ($user_profile_ids)";
			$keyArr[] = $user_profile_ids;
		}else{$keyArr[] =-1;}
		if($start_date!=""){
                $whereClauseArr[] = "date(create_date) >='$start_date'";
                $keyArr[] = $start_date;
        }else{$keyArr[] =-1;}
        if($end_date!=""){
                $whereClauseArr[] = "date(create_date) <='$end_date'";
                $keyArr[] = $end_date;
        }else{$keyArr[] =-1;}
		
		if($profile_name!=""){
			$whereClauseArr[] = "profile_name in ($profile_name)";
			$keyArr[] = $profile_name;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}

		if($campaign != ''){
			$whereClauseArr[] = "campaign=$campaign";
			$keyArr[] = $campaign;
		}else{$keyArr[] =-1;}
		
		if($mobileno != ''){
			$whereClauseArr[] = " mobile = '$mobileno'";
			$keyArr[] = $mobileno;
		}else{$keyArr[] =-1;}
		if($email != ''){
			$whereClauseArr[] = " email = '$email'";
			$keyArr[] = $email;
		}else{$keyArr[] =-1;}

		if($utm_source != ''){
                	$whereClauseArr[] = " utm_source = '$utm_source'";
        	        $keyArr[] = $utm_source;
	        }else{$keyArr[] =-1;}

		if($poll != ''){
                        $whereClauseArr[] = " poll = '$poll'";
                        $keyArr[] = $poll;
                }else{$keyArr[] =-1;}

		if($question != ''){
                        $whereClauseArr[] = " question = '$question'";
                        $keyArr[] = $question;
                }else{$keyArr[] =-1;}

		
		if($create_date != ''){
			$whereClauseArr[] = "date(create_date) = $create_date ";
			$keyArr[] = $create_date;
		}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
			$keyArr[] = $startlimit;
		}else{$keyArr[] =-1;}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
			$keyArr[] = $cnt;
		}else{$keyArr[] =-1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$orderby = "order by create_date DESC ";
		$key = implode('_',$keyArr);
		$sSql="SELECT * from CAMPAIGN_LEAD_INFO $whereClauseStr $orderby $limitStr";
		$result =$this->select($sSql);
		return $result;
	}

	function intUpdateSankpalUserDetail($user_profile_id,$update_param){
		$update_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("CAMPAIGN_LEAD_INFO",array_keys($update_param),array_values($update_param),"user_profile_id",$user_profile_id);
		$isUpdate = $this->update($sql);
		return $isUpdate;
	}

	function intInsertSankpalUserDetail($insert_param){
                global $utmsrc;
                $insert_param['create_date'] = date('Y-m-d H:i:s');
                $insert_param['update_date'] = date('Y-m-d H:i:s');
                $insert_param['utm_source']   = !empty($_POST['utm_source']) ? $_POST['utm_source'] : $utmsrc['utmcsr'];
                $insert_param['utm_medium']   = !empty($_POST['utm_medium']) ? $_POST['utm_medium'] : $utmsrc['utmcmd'];
                $insert_param['utm_campaign'] = !empty($_POST['utm_campaign']) ? $_POST['utm_campaign'] : $utmsrc['utmccn'];
                //$insert_param['utm_term']     = !empty($_POST['utm_term']) ? $_POST['utm_term'] : $utmsrc['utmctr'];
                $sSql = $this->getInsertUpdateSql("CAMPAIGN_LEAD_INFO",array_keys($insert_param),array_values($insert_param));
                $user_profile_id = $this->insertUpdate($sSql);
                return $user_profile_id;
        }

    function getSankpalUserInfoCount($user_profile_ids="",$category_ids="",$brand_ids="",$product_info_ids="",$product_ids="",$city_ids="",$profile_name="",$status="1",$startlimit="",$cnt="",$utm_source='',$dist_ids='',$start_date="",$end_date="",$campaign=""){
		$keyArr[] = $this->userkey."_lead_count";
		if(is_array($user_profile_ids)){
			$user_profile_ids = implode(",",$user_profile_ids);
		}
		
		if($user_profile_ids!=""){
			$whereClauseArr[] = "user_profile_id in ($user_profile_ids)";
			$keyArr[] = $user_profile_ids;
		}else{$keyArr[] =-1;}
		if($start_date!=""){
                $whereClauseArr[] = "date(create_date) >='$start_date'";
                $keyArr[] = $start_date;
        }else{$keyArr[] =-1;}
		if($end_date!=""){
                $whereClauseArr[] = "date(create_date) <='$end_date'";
                $keyArr[] = $end_date;
        }else{$keyArr[] =-1;}
		
		
		if($profile_name!=""){
			$whereClauseArr[] = "profile_name in ($profile_name)";
			$keyArr[] = $profile_name;
		}else{$keyArr[] =-1;}
		if($status != ''){
			$whereClauseArr[] = "status=$status";
			$keyArr[] = $status;
		}else{$keyArr[] =-1;}
		if($utm_source != ''){
                $whereClauseArr[] = " utm_source = '$utm_source'";
                $keyArr[] = $utm_source;
        }else{$keyArr[] =-1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		
		$key = implode('_',$keyArr);
		$sSql="SELECT count(user_profile_id) as cnt from CAMPAIGN_LEAD_INFO $whereClauseStr";
		$result = $this->select($sSql);
		$count = $result[0]['cnt'];
		return $count;
	}    

}
