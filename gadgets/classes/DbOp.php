<?php

/**
*  @defgroup mainclass
*/
/**
* @mainpage  oncars.in documentation Home
*
*/
/**
 * @brief class has been developed for all mySQL database operations data processing
 * @author Rajesh Ujade
 * @version 1.0
 * @created 01-Nov-2010 2:26:35 PM
 * @last updated on 09-Mar-2011 13:14:00 PM
*/
class DbOperation
{
	/**
	 * used for getting database connection
	 */
	var $conn;

    /**
    * Contructor used to set default values if needed
    */
	var $setUnicode;
	var $err;
	function __construct(){
	}

    public function error($msg) {
		$this->err = $msg;
        //error_log(mysql_error());
		error_log("mysql error - $msg & mysql_error = ".mysql_error()); // show the error message which is in parameter
    }

	public function replace_mysql_constants($sql){
		return $sql = str_replace(array("'now()'"),array('now()'),$sql);
	}

	public function select($sql){

		#if(!preg_match("/^select/", trim($sql),$matches,PREG_OFFSET_CAPTURE)){ // its check that only select query should be passed
		#	return false;
		#}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql."\r\n".mysql_error()." Problem In Query Execution!");

		if (!$result){
			return false;
		}

		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$data[]	=	$row;
		}

		mysql_free_result($result);

		return $data;
	}
	public function create_view($sql){

		/*
		if(!eregi("^create view", trim($sql))){ // its check that only select query should be passed
			return false;
		}
		*/
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql."\r\n".mysql_error()." Problem In Query Execution!");
		if (!$result){
			return false;
		}
		mysql_free_result($result);

		return $result;
	}
	public function drop_view($sql){

		if(!eregi("^drop view", trim($sql))){ // its check that only select query should be passed
			return false;
		}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql."\r\n".mysql_error()." Problem In Query Execution!");

		if (!$result){
			return false;
		}

		mysql_free_result($result);

		return $data;
	}

	public function insert($sql,$conn){

		if(!eregi("^insert", trim($sql))){ // its check that only insert query should be passed
			return false;
		}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql."Problem In Query Execution!");
		if(mysql_errno() == 1062){
			return 'Duplicate entry';
		}
	        $id =  mysql_insert_id();
        	return $id;
	}

    public function getInsertSql($tbl, $fldArr, $valArr) {

		if (!is_array($fldArr) || !is_array($valArr)) {
			error_log("Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values must be passed as array.Entered info = $tbl");
		}

		if (sizeof($fldArr) !== sizeof($valArr)) {
			error_log( "<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values arrays size must be same.<hr></pre>");
		}

		$valArr = (get_magic_quotes_gpc()) ? array_map('stripslashes',$valArr) : array_map('mysql_escape_string',$valArr);

		$sql	= "INSERT INTO $tbl(";

		$sql .= '`'.implode("`,`",$fldArr).'`';

		$sql .=	") VALUES (";

		$sql .= "'".implode("','",$valArr)."'";

		$sql .=	")";

		$sql = $this->replace_mysql_constants($sql);

		return $sql;
	}

	public function update($sql){
		if(!eregi("^update", trim($sql))){ // its check that only update query should be passed
			return false;
		}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql." Problem In Query Execution!");
		//echo "SQL--".$sql."<br>";
		//print_r($result);
		//echo "---TEST<br>";
		return mysql_affected_rows();
	}

	public function getUpdateSql($tbl, $fldArr, $valArr, $fldName="", $value="") {
		if (!is_array($fldArr) || !is_array($valArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values must be passed as array.<hr></pre>");
		}
		if (sizeof($fldArr) !== sizeof($valArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values arrays size must be same.<hr></pre>");
		}
		$valArr = (get_magic_quotes_gpc()) ? array_map('stripslashes',$valArr) : array_map('mysql_escape_string',$valArr);
		//start code to create key pair array .Its used for update sql.
		$updateArr = array_combine($fldArr, $valArr);
		$unsetArr = Array('create_date','createdate','cdate');
		foreach($unsetArr as $key){	unset($updateArr[$key]);}
		array_walk($updateArr, create_function('&$val,$key','$val=" $key=\'$val\'";')); // create new array.
		$sql	= "UPDATE $tbl SET ";
		$sql .= implode(",",$updateArr);
		if(!is_array($fldName) && !empty($fldName)){
			$whereClauseArr[$fldName] = $value;
		}else{
			$whereClauseArr = array_combine($fldName,$value);
		}
		$whereClauseArr = (get_magic_quotes_gpc()) ? array_map('stripslashes',$whereClauseArr) : array_map('mysql_escape_string',$whereClauseArr);
		array_walk($whereClauseArr, create_function('&$val,$key','$val=" $key=\'$val\'";')); // create new array.
		$count = sizeof($whereClauseArr);
		if(!empty($count)){	$whereClause = implode(' and ',$whereClauseArr);}
		if($whereClause) $whereClause = ' where '.$whereClause;
		$sql = $sql.$whereClause;
		$sql = $this->replace_mysql_constants($sql);
		return $sql;
	}

	public function insertUpdate($sql){
		if(!eregi("^insert", trim($sql))){	return false;}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql."Problem In insertUpdate Query Execution!");
        if(mysql_affected_rows() != 2){
            return  $id =  mysql_insert_id(); //insert new row
		}else{
            return 0; // update existing row.
		}
	}
	public function getInsertUpdateSql($tbl, $fldArr, $valArr) {
		if (!is_array($fldArr) || !is_array($valArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values must be passed as array.<hr></pre>");
		}
		if (sizeof($fldArr) !== sizeof($valArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values arrays size must be same.<hr></pre>");
		}
		$valArr = (get_magic_quotes_gpc()) ? array_map('stripslashes',$valArr) : array_map('mysql_escape_string',$valArr);
		//start code to create key pair array .Its used for update sql.
		$updateArr = array_combine($fldArr, $valArr);
		$unsetArr = Array('create_date','createdate','cdate');
		foreach($unsetArr as $key){	unset($updateArr[$key]);}
		array_walk($updateArr, create_function('&$val,$key','$val=" $key=\'$val\'";')); // create new array.
		$sql	= "INSERT INTO $tbl(";
		$sql .= '`'.implode("`,`",$fldArr).'`';
		$sql .=	") VALUES (";
		$sql .= "'".implode("','",$valArr)."'";
		$sql .=	") ON DUPLICATE KEY ";
		$sql .= "UPDATE ";
		$sql .= implode(",",$updateArr);
		$sql = $this->replace_mysql_constants($sql);
		return $sql;
	}
	public function insertSelect($sql){
		if(!eregi("^insert", trim($sql))){	return false;}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error("Problem In insertSelect Query Execution - <br/>".$sql);
		if(mysql_errno() == 1062){	return 'Duplicate entry';}
		if(!empty($this->err)){
			//echo $this->err;
		}else{
		if(mysql_affected_rows() != 2){
            return  $id =  mysql_insert_id(); //insert new row
		}else{
            return 0; // update existing row.
		}
	    }
	}
	public function getInsertSelectSql($insert_tbl,$insertfldArr,$insert_db_name,$select_tbl,$selectfldArr,$whereClauseArr='',$select_db_name=''){
		if(empty($select_db_name) && !empty($insert_db_name)){
			$select_db_name = $insert_db_name;
		}elseif(empty($insert_db_name)){
			die("<pre>\n<h1>Problem In 'DATABASE NAME'!</h1>Param insert_db_name required.<hr></pre>");
		}
		if (!is_array($insertfldArr) || !is_array($selectfldArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values must be passed as array.<hr></pre>");
		}

		if (sizeof($insertfldArr) !== sizeof($selectfldArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values arrays size must be same.<hr></pre>");
		}
		$inserttblArr = array('`'.$insert_db_name.'`',$insert_tbl);
		$insert_tbl = implode(".",$inserttblArr);
		$inserttblArr = array('`'.$select_db_name.'`',$select_tbl);
		$select_tbl = implode(".",$inserttblArr);
		$sql	= "INSERT INTO $insert_tbl(";
		$sql .= '`'.implode("`,`",$insertfldArr).'`';
		$sql .=	")";
		$sql .= "SELECT ";
		$sql .= implode(",",$selectfldArr);
		$sql .= " FROM $select_tbl";
		$count = sizeof($whereClauseArr);
		if(!empty($count)){
			$whereClauseArr = (get_magic_quotes_gpc()) ? array_map('stripslashes',$whereClauseArr) : array_map('mysql_escape_string',$whereClauseArr);
			array_walk($whereClauseArr, create_function('&$val,$key','$val=" $key=\'$val\'";')); // create new array.
			$whereClause = implode(" and ",$whereClauseArr);
		}
		if($whereClause) $whereClause = ' where '.$whereClause;
		$sql = $sql.$whereClause;
		$sql = $this->replace_mysql_constants($sql);
		return $sql;
	}
	public function insertSelectUpdate($sql){

		if(!eregi("^insert", trim($sql))){
			return false;
		}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql."Problem In insertSelectUpdate Query Execution!");
        if(mysql_affected_rows() != 2){
            return  $id =  mysql_insert_id(); //insert new row
		}else{
            return 0; // update existing row.
		}
	}


	public function selectFieldName($sql){

		if(!eregi("^select", trim($sql))){
			return false;
		}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql."Problem In select Query Execution!");
        if(mysql_affected_rows() != 2){
			$i = 0;
			while ($i < mysql_num_fields($result)) {
				$meta = mysql_fetch_field($result, $i);
			}
		}else{
            return 0; // update existing row.
		}
	}
	public function getInsertSelectUpdateSql($insert_tbl,$insertfldArr,$insert_db_name,$select_tbl,$selectfldArr,$whereClauseArr='',$select_db_name=''){

		if(empty($select_db_name) && !empty($insert_db_name)){
			$select_db_name = $insert_db_name;
		}elseif(empty($insert_db_name)){
			die("<pre>\n<h1>Problem In 'DATABASE NAME'!</h1>Param insert_db_name required.<hr></pre>");
		}
		if (!is_array($insertfldArr) || !is_array($selectfldArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values must be passed as array.<hr></pre>");
		}

		if (sizeof($insertfldArr) !== sizeof($selectfldArr)) {
			die("<pre>\n<h1>Problem In 'FIELD AND VALUE ARRAY'!</h1>Fields and Values arrays size must be same.<hr></pre>");
		}

		$inserttblArr = array($insert_db_name,$insert_tbl);
		$insert_tbl = implode(".",$inserttblArr);

		$inserttblArr = array($select_db_name,$select_tbl);
		$select_tbl = implode(".",$inserttblArr);

		$sql	= "INSERT INTO $insert_tbl(";

		$sql .= '`'.implode("`,`",$insertfldArr).'`';

		$sql .=	")";

		$sql .= "SELECT ";

		$sql .= implode(",",$selectfldArr);

		$sql .= " FROM $select_tbl";

		$count = sizeof($whereClauseArr);

		if(!empty($count)){
			$whereClauseArr = (get_magic_quotes_gpc()) ? array_map('stripslashes',$whereClauseArr) : array_map('mysql_escape_string',$whereClauseArr);

			array_walk($whereClauseArr, create_function('&$val,$key','$val=" $key=\'$val\'";')); // create new array.

			$whereClause = implode(" and ",$whereClauseArr);

		}

		if($whereClause) $whereClause = ' where '.$whereClause;

		$sql = $sql.$whereClause;

		//start code for update on duplicate key.
			$selectfldArr = "$select_tbl.".implode(",$select_tbl.",$selectfldArr);
			$selectfldArr = explode(",",$selectfldArr);

			$insertfldArr = "$insert_tbl.".implode(",$insert_tbl.",$insertfldArr);
			$insertfldArr = explode(",",$insertfldArr);

			$updateArr = array_combine($insertfldArr,$selectfldArr);
			//array_walk($updateArr, create_function('&$val,$key','$val=" $key=$val";')); // create new array.

			array_walk($updateArr, create_function('&$val,$key','if(strpos($val,\'now\')==0){$val=" $key=$val";}else{$val="$key=now()";}')); // create new array.

		$sql .=	" ON DUPLICATE KEY ";

		$sql .= "UPDATE ";

		$sql .= implode(",",$updateArr);

		//end code for update on duplicate key.
		$sql = $this->replace_mysql_constants($sql);

		return $sql;
	}
	/**
	 * @param sql    first parameter used as sql query for deletion
	 */
	public function sql_delete_data($sql){

		if(!eregi("^delete", trim($sql))){ // its check that only delete query should be passed
			return false;
		}
		$setName = mysql_query("set names 'utf8'");
		$result	= mysql_query($sql) or $this->error($sql." Problem delete query!");
		return $result;
	}

	public function select_xml($sql,$element){
		$root_element = strtoupper($element);
		$child_element = "GET_".$root_element."_DATA";
		$result = $this->select($sql);

		$xml = "<$root_element>";

		$resultCount = count($result);

		for($i=0; $i<$resultCount; $i++){
			$xml .="<$child_element>";
			$xml .= "<COUNT><![CDATA[$resultCount]]></COUNT>";
			$result[$i] = array_change_key_case($result[$i], CASE_UPPER);

			foreach($result[$i] as $k=>$v){
				$xml .= "<". $k ."><![CDATA[$v]]></". $k.">";
			}
			$xml .="</$child_element>";
		}

		$xml .= "</$root_element>";

		return $xml;
	}

	/**
	*
	* @param data
	* @param id
	*/
	function getSelectSql($table_name,$request="",$constraintsArr="",$orderfield="",$order="",$startlimit="",$cnt=""){
		if(!empty($startlimit)){
			$limitArr[] = $startlimit;
		}
		if(!empty($cnt)){
			$limitArr[] = $cnt;
		}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(",",$limitArr);
		}
		if(!empty($orderfield)){
			$orderbyArr[] = $orderfield;
		}
		if(!empty($order)){
			$orderbyArr[] = $order;
		}
		if(sizeof($orderbyArr) > 0){
			$orderbyStr = " order by ".implode(" ",$orderbyArr);
		}

		$countreaddata = is_array($constraintsArr) ? sizeof($constraintsArr) : 0;
		if (!$request){
			$request = "*";
		}else if (is_array($request)) {

			$request = implode(",",$request);
		}
		else {
			$request = stripslashes($request);
		}

		if($countreaddata == 0)
		{
			$sql = "select ".$request." from ".$table_name.$orderbyStr.$limitStr;

		}
		else
		{
			$sql = "select ".$request." from ".$table_name." where ".$this->array2contraints($constraintsArr).$orderbyStr.$limitStr;

		}
		return $sql;
	}

	/**
	* This function take the data in an array for where condition of select query
	* @param in_array
	*/
	function array2string($in_array)
	{
		return implode(",",$in_array);
	}
	/**
	* This function is use to seperate data of an array and use it in where condition
	* @param in_array
	*/
	function array2contraints($in_array){
		foreach($in_array as $key => $value){
			if (is_array($value)){				//If the value is an array, then make it like key=val1 OR key=val2
				if(is_array(array_values($value[0]))){	//If the value is an array of arrays...0=>mediaid2=>12,1=>mediaid2=>123
					for ($row = 0; $row<count($value); $row++)
						foreach($value[$row] as $k=>$v)
							$temp[] = $v;
					$temparray[]= $key." = ".implode(" OR $key = ",$temp);
					continue;
				}
				$temparray[]= $key." = ".implode(" OR $key = ",$value);
				continue;
			}
			$value = explode(",",$value);
			$value = implode(" AND ",$value);
			$temparray[] = $key."=".$value;
		}
		return implode(" AND ", $temparray);
	}
	// Quote variable to make safe
	public function quote_smart($value){
		// Stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		// Quote if not a number or a numeric string
		if (!is_numeric($value)) {
			$value = "'" . mysql_real_escape_string($value) . "'";
		}
		return $value;
	}

}
