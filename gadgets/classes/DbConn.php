<?php
/**
 * @brief class is used to establish connection to mysql database.
 * @author Rajesh Ujade
 * @version 1.0
 * @created 01-Nov-2010 2:23:29 PM
 * @last updated on 09-Mar-2011 13:14:00 PM
*/
class DbConn {

	/**
	* costructor used to set connection resource
	*/
	function __construct(){
		$this->getConnect();
		return $this->conn;
	}

	/**
	 * set mysql database server connection
	 * first define constant host, username, password  db name in config.php file
	 */
	function getConnect(){
		$this->conn = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);

		if (is_resource($this->conn)) {
		if (!mysql_ping($this->conn))
            		$this->conn(DB_HOST, DB_USER, DB_PASSWORD,false);
		mysql_select_db(DB_NAME);
		return false;
		}
		return $this->conn;
	}
}