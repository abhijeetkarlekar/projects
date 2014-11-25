<?php
	/**
	 * @brief class data file system management filesystem.
	 * Responsible to create hash based directory structure and read,write,update and delete of file.
	 * @author Rajesh Ujade.
	 * @version 1.0
	 * @created 27-June-2013 4:06:02 PM
	 */
	class DataFileSystem{
		var $basepath = FILE_BASE_PATH;
		var $infotype; // is used for service ex.-autos,bollywood,education etc.	
		var $content_type=""; // is used for uploaded content type i.e.html etc.
		/**Intialize the consturctor.*/
		function DataFileSystem($infotype,$content_type=""){
			$this->infotype = $infotype;
			$this->content_type = $content_type;
		}
		/**
		* @note function is used to create dir path and used to create directorys structures.
		* @param integer $id.
		* @param string $path.
		* @pre $id must be valid,non-zero integer.
		* @post string $path.
		* return string.
		*/
		function create_path($id,$path=""){
			if(!$path){ $path = $this->get_path($id);}	
			$path_dirs = explode("/",$path);
			array_splice($path_dirs, 0, 1);
			foreach($path_dirs as $dir){
				if($dir == "") {continue;}
				$currpath .= "/".$dir;
				if(!is_dir($currpath)){
					@mkdir($currpath,0775,true);
					shell_exec("chmod 775 $currpath");
				}
			}
			return $path;
		}
		/**
		* @note function is used to get directory path.
		* @param integer $id.
		* @pre $id must be valid,non-zero integer.
		* @post string directory path.
		* return string.
		*/
		function get_path($id){
			$dir = $id%10;
			$pathArr[] = "$this->basepath"."$this->infotype";
			if(!empty($this->content_type)){
				$pathArr[] = $this->content_type;
			}
			$pathArr[] = $dir;
			$pathArr[] = $id;
			$dir_path = implode("/",$pathArr);
			$this->create_path($id,$dir_path);
			return $dir_path;
		}
		/**	
		* @note function is used to read the encoded data from file system.
		* @param integer $id.
		* @param string $filename.
		* @pre integer $id must be valid,non-zero integer and string $filename must be valid and exist file name in file system.
		* @post string file data.
		* return string.
		*/
		function read_data($id,$filename)
		{
			$file_path = $this->get_path($id)."/".$filename;
			$data = file_get_contents($data);
			return $data;
		}

		/**	
		* @note function is used to encode and write data into the file system.
		* @param integer $id.
		* @param string $filename.
		* @param string $src_path.
		* @pre $id must be valid,non-zero,non-empty integer and string $filename must be valid,non-empty filename and $src_path must be valid non-empty string path.
		* @post string destination path.
		* return string.
		*/
		function write_data($id,$filename, $data)
		{
			$file_path = $this->get_path($id)."/".$filename;
			$file_path = file_put_contents($file, $data, LOCK_EX);
			return $file_path;
		}

		/**
		* @note function is used to delete from file system.
		* @param integer $id. 
		* @param string $filename.
		* @param string $src_path.
		* @pre $id must be valid,non-empty,non-zero integer and string $filename must be valid,non-empty string.
		* @post boolean true/false.
		* return boolean.
		*/
		function delete_data($id,$filename, $src_path="")
		{
			$src_path = ($src_path) ? $src_path : $this->get_path($id)."/".$filename;
			$res = shell_exec("rm -f ".escapeshellarg($src_path));
			return '1';

		}

		/**
		*@note function is used to update the file system.
		* @param data
		* @param id
		*/
		function update_data($data,$id)
		{
		}
	}
