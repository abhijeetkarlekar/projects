<?php
	require_once(RESTPLATFORMPATH.'DataStore.php');
	 /**
	 * @brief class data interface management filesystem.
	 * Used to communicate with data store.
	 * @author Rajesh Ujade.
	 * @version 1.0
	 * @created 27-June-2013 4:06:02 PM
	 */
	class DataInterface{
		var $datastore;
		var $infotype;
		var $content_type;
		var $datasystem;
		/**Intialize the consturctor.*/
		function DataInterface($infotype,$datasystem="fs",$content_type="html"){
			$this->infotype = $infotype;
			$this->content_type = $content_type;
			$this->datasystem = $datasystem;
			$this->datastore = new DataStore($this->infotype,$this->datasystem,$this->content_type);

		}
		/**	
		* @note function is used to read the data from system.
		* @param associative array $store_param.
		* @pre $store_param must be valid,non-empty associative array.
		* @post array $result from data base system and string from file system.
		* return associative array from database and decoded string from file system.
		*/
		function read_data(&$store_param)
		{
			if(!$result = $this->datastore->read_data($store_param)){
				return false;
			}
			return $result;
		}

		/**	
		* @note function is used to write data into the filesystem.
		* @param associative array $store_param.
		* @pre $store_param must be valid,non-empty associative array.
		* @post integer from db/string from file system.
		*/
		function write_data(&$store_param)
		{
			if(!$result = $this->datastore->write_data($store_param)){
				return false;
			}
			return $result;
		}

		/**	
		* @note function is used to delete data from the system.
		* @param associative array $store_param.
		* @pre $store_param must be valid,non-empty associative array.
		* @post boolean true/fasle.
		* return boolean.
		*/
		function delete_data(&$store_param)
		{
			if(!$result = $this->datastore->delete_data($store_param)){
				return false;
			}
			return $result;
		}
		/**	
		* @note function is used to update data from the system.
		* @param associative array $store_param.
		* @pre $store_param must be valid,non-empty associative array.
		* @post boolean true/fasle.
		* return boolean.
		*/
		function update_data($store_param)
		{
			if(!$result = $this->datastore->update_data($store_param)){
				return false;
			}
			return $result;
		}

	}
