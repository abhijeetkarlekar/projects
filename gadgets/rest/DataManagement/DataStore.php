<?php
	require_once(RESTPLATFORMPATH.'DataFileSystem.php');
	require_once(RESTPLATFORMPATH.'DataMemorySystem.php');

	/**
         * @brief class data store.
         * Used to manage the work flow of data.
         * @author Rajesh Ujade.
         * @version 1.0
         * @created 27-June-2013 4:06:02 PM
         */
	class DataStore{
		var $datasystem;
		var $infotype;
		var $content_type;
		var $datainstance;
		var $tablename;
		/**Intialize the consturctor.*/
		function DataStore($infotype,$datasystem="",$content_type=""){
			$this->infotype = $infotype;
			$this->content_type = $content_type;
			$this->set_default_system($datasystem);
			if($this->datasystem == "fs"){
				$this->datainstance = new DataFileSystem($this->infotype,$this->content_type);
			}else{
				print "here goes to memcache\n<br/>";
			}
		}
		function set_default_system($datasystem=""){
			if($datasystem){ return $this->datasystem = $datasystem; }
			if(!empty($this->infotype)){
				$this->datasystem = "fs";
			}else{
				$this->datasystem = "memcache";
			}
			return $this->datasystem;
		}
		/**	
		*
		* @param data
		* @param id
		*/

		function read_data($platform_param)
		{
			list($filename,$src_path,$dest_path) = array($platform_param['filename'],$platform_param['src_path'],$platform_param['dest_path']);
			
			if($this->datasystem = "fs"){
				$result = $this->datainstance->read_data($id,$filename,$src_path,$dest_path);
			}else{
				print "read data for memcache\n";
			}
			return $result;
		}
		/**	
		* @note function is used to insert data into the filesystem database.
		* @param associative array $insert_param. 
		* @pre $insert_param must be valid associative arrray and key must be match with the database field name.
		* @post integer $result.
		* return integer.
		*/

		function write_data($platform_param)
		{
			list($id,$filename, $src_path,$insert_param) = array($platform_param['id'],$platform_param['filename'],$platform_param['src_path'],$platform_param['insert_param']);
			if($this->datasystem == "fs"){
				$result = $this->datainstance->write_data($id,$filename,$src_path);
			}else{
				print "write data for memcache\n";
			}
			return $result;
		}
		/**	
		* @note function is used to delete the item from the database.
		* @param string $field_name. 
		* @param integer $id.
		* @pre $field_name must be valid and matched with the table field name.And $id must be valid,non-empty,non-zero integer value.
		* @post boolean true/false.
		* return boolean. 
		*/
		function delete_data($platform_param)
		{
			list($id,$filename, $src_path,$fieldname) = array($platform_param['id'],$platform_param['filename'],$platform_param['src_path'],$platform_param['field_name']);
			if($this->datasystem == "fs"){
				$result = $this->datainstance->delete_data($id,$filename,$src_path);
			}else{
				print "delete data from memcache\n";
			}
			return $result;
		}
		/**
		* @note function is used to update the database of the file system.
		* @param associative array $update_param.
		* @param string $field_name.
		* @param integer $id.
		* @pre $field_name must be valid and matched with the table field name.And $id must be valid,non-empty,non-zero integer value.
		* @post boolean true/false.
		* return boolean. 
		*/
		function update_data($platform_param)
		{
			list($update_param,$field_name,$id) = array($platform_param['update_param'],$platform_param['field_name'],$platform_param['id']);
			if($this->datasystem == "fs"){
				$result = $this->datainstance->update_data($update_param,$field_name,$id);
			}else{
				print "update data from memcache\n";
			}
			return $result;
		}
	}
