<?php
/**
* @brief class is used to perform actions on wallpapers and slideshows.
* @author Sachin
* @version 1.0
* @created 11-Nov-2010 5:09:31 PM
* @last updated on 08-Mar-2011 13:14:00 PM
*/
class Wallpapers extends DbOperation
{
	var $cache;
	var $wallpaperKey;
	/**Intialize the consturctor.*/
	function Wallpapers(){
		$this->cache = new Cache;
		$this->wallpaperKey = MEMCACHE_MASTER_KEY."wallpaper::";
	}

	/**
	* @note function is used to insert the Wallpapers information into the database.
	* @param an associative array $insert_param.
	* @param is a string $tablename.
	* @pre $insert_param must be valid associative array.
	* @post an integer $wallpaper_id.
	* retun integer.
	*/
	function intInsertWallpapers($insert_param,$tablename)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($tablename,array_keys($insert_param),array_values($insert_param));
		$wallpaper_id = $this->insert($sql);
		if($wallpaper_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->wallpaperKey);
		return $wallpaper_id;
	}
	/**
	* @note function is used to insert the Featured Wallpapers information into the database.
	* @param an associative array $insert_param.
	* @param is a string $table_name.
	* @pre $insert_param must be valid associative array.
	* @post an integer $wallpaper_id.
	* retun integer.
	*/
	function intInsertFeaturedWallpaper($insert_param,$table_name){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($table_name,array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->wallpaperKey.'_featured');
		return $result;
	}	

	/**
	* @note function is used to insert the ProductOption color information into the database.
	* @param an associative array $insert_param.
	* @pre $insert_param must be valid associative array.
	* @post an integer $Product_color_id.
	* retun integer.
	*/
	function intInsertProductOptionColor($insert_param){
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("PRODUCT_COLOR",array_keys($insert_param),array_values($insert_param));
		$wallpaper_id = $this->insert($sql);
		if($product_color_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->wallpaperKey.'_product_color');
		return $product_color_id;
	}

	/**
	* @note function is used to insert the slide show information into the database.
	* @param an associative array $insert_param.
	* @param is a string $tablename.
	* @pre $insert_param must be valid associative array.
	* @post an integer $slideshow_id.
	* retun integer.
	*/
	function intInsertSlideshow($insert_param,$tablename)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($tablename,array_keys($insert_param),array_values($insert_param));
		$slideshow_id = $this->insert($sql);
		if($slideshow_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->wallpaperKey);
		return $slideshow_id;
	}

	/**
	* @note function is used to insert the  product slide show information into the database.
	* @param an associative array $insert_param.
	* @param is a string $tablename.
	* @pre $insert_param must be valid associative array.
	* @post an integer $product_slide_id.
	* retun integer.
	*/
	function intInsertProductSlides($insert_param,$tablename)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($tablename,array_keys($insert_param),array_values($insert_param));
		$product_slide_id = $this->insert($sql);
		if($product_slide_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->wallpaperKey);
		return $product_slide_id;
	}
	/**
	* @note function is used to insert the  Featured slides information into the database.
	* @param an associative array $insert_param.
	* @param is a string $tablename.
	* @pre $insert_param must be valid associative array.
	* @post an integer $slide_id.
	* retun integer.
	*/
	function intInsertFeaturedSlides($insert_param,$tablename)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql($tablename,array_keys($insert_param),array_values($insert_param));
		$slide_id = $this->insert($sql);
		if($slide_id == 'Duplicate entry'){ return 'exists';}
		$this->cache->searchDeleteKeys($this->wallpaperKey.'_featured_slideshow');
		return $slide_id;
	}
	/**
	* @note function is used to insert the  Clinck slides information into the database.
	* @param an associative array $insert_param.
	* @param is a string $tablename.
	* @pre $insert_param must be valid associative array.
	* @post an integer $slide_id.
	* retun integer.
	*/
	function intInsertClinckSlides($insert_param)
	{
		$insert_param['create_date'] = date('Y-m-d H:i:s');
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getInsertUpdateSql("CLINCK_SLIDESHOW",array_keys($insert_param),array_values($insert_param));
		$result=$this->insertUpdate($sql);
		$this->cache->searchDeleteKeys($this->wallpaperKey.'_clinck_slideshow');
		return $result;
	}
	/**
	* @note function is used to delete Clinck Slides.
	* @param integer $id.
	* @pre $id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function booldeleteClinckSlides($id=""){
		$sSql="delete from CLINCK_SLIDESHOW where section_slide_id='".$id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->wallpaperKey.'_clinck_slideshow');
	}
	/**
	* @note function is used to delete featured wallpapers.
	* @param integer $wallpaper_id.
	* @param is a string $tablename.
	* @pre $wallpaper_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function booldeleteFeaturedVideos($wallpaper_id="",$table_name=""){
		$sSql="delete from $table_name where wallpaper_id='".$wallpaper_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->wallpaperKey);
	}	
	/**
	* @note function is used to delete featured slideshows.
	* @param integer $slide_id.
	* @param is a string $table_name.
	* @pre $slide_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/ 
	function boolDeleteFeaturedSlides($slide_id,$table_name)
	{
		$sql = "delete from $table_name where slide_id = $slide_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->wallpaperKey);
		return $isDelete;
	}
	/**
	* @note function is used to update the Wallpapers into the database.
	* @param an associative array $update_param.
	* @param an integer $Wallpaper_id.
	* @pre $update_param must be valid associative array and $wallpaper_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* retun boolean.
	*/
	function boolUpdateWallpapersProduct($wallpaper_id,$update_param)
	{
		$insert_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $this->getUpdateSql("WALLPAPER",array_keys($update_param),array_values($update_param),"wallpaper_id",$wallpaper_id);
		$isUpdate = $this->update($sql);
		$this->cache->searchDeleteKeys($this->wallpaperKey);
		return $isUpdate;
	}
	/**
	* @note function is used to delete the Wallpapers.
	* @param integer $wallpaper_id
	* @pre $wallpaper_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteWallpaper($wallpaper_id)
	{
		$sSql="delete from WALLPAPER where wallpaper_id='".$wallpaper_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$sSql='';
		$sSql="delete from PRODUCT_WALLPAPERS where wallpaper_id='".$wallpaper_id."'";
		$iRes=$this->sql_delete_data($sSql);
		$this->cache->searchDeleteKeys($this->wallpaperKey);
	}

	/**
	* @note function is used to delete the Slideshow.
	* @param integer $product_slideshow_id
	* @pre $product_slideshow_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDeleteSlideshow($product_slideshow_id)
	{
		$sql = "delete from PRODUCT_SLIDES where product_slide_id = $product_slideshow_id";
		$isDelete = $this->sql_delete_data($sql);
		if($isDelete == "1"){
			$res = $this->boolDelSlideshow($product_slideshow_id);
		}
		$this->cache->searchDeleteKeys($this->wallpaperKey);
		return $isDelete;
	}
	/**
	* @note function is used to delete the slideshow.
	* @param integer $product_slide_id
	* @pre $product_slide_id must be non-empty/zero valid integer.
	* @post boolean true/false.
	* return boolean.
	*/
	function boolDelSlideshow($product_slide_id)
	{
		$sql = "delete from SLIDESHOW where product_slide_id = $product_slide_id";
		$isDelete = $this->sql_delete_data($sql);
		$this->cache->searchDeleteKeys($this->wallpaperKey);
		return $isDelete;
	}

	/**
	* @note function is used to get Wallpapers details.
	* @param an integer/comma seperated wallpaper_id/ wallpaper_id array $wallpaper_ids.
	* @param an integer/comma seperated product_id/product_id array $product_ids.
	* @param an integer/comma seperated product_info_id/product_info_id array $product_info_id.
	* @param an integer/comma seperated category_id/category_id array $category_ids.
	* @param an integer/comma seperated $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @param string $orderby.
	* @pre not required.
	* @post Wallpapers details in associative array.
	* retun an array.
	*/
	function arrGetWallpapersDetails($wallpaper_ids="",$product_ids="",$product_info_id="",$category_ids="",$brand_id="",$status="",$startlimit="",$count="",$orderby="")
	{
			
		$keyArr[] = $this->wallpaperKey."_arrGetWallpapersDetails";
		if(is_array($wallpaper_ids)){
			$wallpaper_ids = implode(",",$wallpaper_ids);
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
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
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
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}

		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "W.status=$status";
		}else{$keyArr[] = -1;}
		if($wallpaper_ids!=""){
			$keyArr[] = $wallpaper_ids;
			$whereClauseArr[] = "W.wallpaper_id in ($wallpaper_ids)";
		}else{$keyArr[] = -1;}

		if($product_ids!=''){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = " PW.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if($product_info_id!=""){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = " PW.product_info_id in($product_info_id)";
		}else{$keyArr[] = -1;}
		if($group_ids!=""){
			$keyArr[] = $group_ids;
			$whereClauseArr[] = " PW.group_id in($group_ids)";
		}else{$keyArr[] = -1;}

		if($category_id!=""){
			$keyArr[] = $category_id;
			$whereClauseArr[] = " PW.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if($brand_id!=""){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = " PW.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " PW.wallpaper_id=W.wallpaper_id ";

		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if($orderby != ""){
			$orderby = " order by W.".$orderby." DESC";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql="select *, DATE_FORMAT(W.create_date,'%d/%m/%Y') as disp_date from WALLPAPER W, PRODUCT_WALLPAPERS PW $whereClauseStr $orderby $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get slideshow details.
	* @param an integer/comma seperated slideshow id/ slideshow ids array $slideshow_ids.
	* @param an integer/comma seperated product id/product ids array $product_ids.
	* @param an integer/comma seperated product info id/product info ids array $product_info_id.
	* @param an integer $product_slide_id.
	* @param an integer/comma seperated category id/ category ids array $category_ids.
	* @param an integer/comma seperated $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post slideshow details in associative array.
	* retun an array.
	*/
	function arrGetSlideShowDetails($slideshow_ids="",$product_ids="",$product_info_id="",$product_slide_id="",$category_ids="",$brand_id="",$status="",$startlimit="",$count="",$orderby="")
	{
		$keyArr[] = $this->wallpaperKey.'_slideshow_detail';
		if(is_array($slideshow_ids)){
			$slideshow_ids = implode(",",$slideshow_ids);
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
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
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
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_slide_id)){
			$keyArr[] = $product_slide_id;
			$whereClauseArr[] = " product_slide_id=$product_slide_id ";
		}else{$keyArr[] = -1;}
		if(!empty($slideshow_ids)){
			$keyArr[] = $slideshow_ids;
			$whereClauseArr[] = "slideshow_id in ($slideshow_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " video_img_path!='' ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from SLIDESHOW $whereClauseStr $orderby $limitStr";
		#echo "<br/>".$sql; die;
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get product slide details.
	* @param an integer/comma seperated product slide id/ product slide ids array $product_slide_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated product id/product ids array $product_ids.
	* @param an integer $product_info_id.
	* @param an integer/comma seperated category id/ category ids array $category_ids.
	* @param an integer/comma seperated $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @param string $orderby.
	* @pre not required.
	* @post product slide details in associative array.
	* retun an array.
	*/
	function arrGetProductSlideDetails($product_slide_ids="",$group_ids="",$product_ids="",$product_info_id="",$category_ids="",$brand_id="",$status="",$startlimit="",$count="",$orderby=""){
		$keyArr[] = $this->wallpaperKey.'_arrGetProductSlideDetails';
		if(is_array($product_slide_ids)){
			$product_slide_ids = implode(",",$product_slide_ids);
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
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
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
        if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        }
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
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($status != ''){
			$keyArr[] 			= $status;
			$whereClauseArr[] 	= "status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_slide_ids)){
			$keyArr[] 			= $product_slide_ids;
			$whereClauseArr[] 	= "product_slide_id in ($product_slide_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($group_ids)){
			$keyArr[] 			= $group_ids;
			$whereClauseArr[] 	= "group_id in ($group_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] 			= $product_ids;
			$whereClauseArr[] 	= "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "product_info_id=$product_info_id";
		}else{$keyArr[] = -1;}
		if(!empty($category_ids)){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "category_id in ($category_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($status)){
			$keyArr[] = $status;
			$whereClauseArr[] = "status=$status";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
			$orderby = "order by ".$orderby." DESC";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from PRODUCT_SLIDES $whereClauseStr $orderby $limitStr";
		#echo "$sql";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get featured slides details.
	* @param an integer/comma seperated section id/ section ids array $section_ids.
	* @param an integer/comma seperated slide id/ slide ids array $slide_ids.
	* @param an integer/comma seperated category id/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	* @pre not required.
	* @post featured slides details in associative array.
	* retun an array.
	*/
	function arrGetFeaturedSlidesDetails($section_ids="",$slide_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->wallpaperKey.'_featured_slideshow_data';
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}

		if(is_array($slide_ids)){
			$slide_ids = implode(",",$slide_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "FS.status=$status";
			$whereClauseArr[] = "PS.status=$status";
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "FS.category_id in ($category_ids)";
		}else{$keyArr[] = -1;}
		if($slide_ids!=""){
			$keyArr[] = $slide_ids;
			$whereClauseArr[] = "FS.slide_id in ($slide_ids)";
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$keyArr[] = $section_ids;
			$whereClauseArr[] = " FS.section_slide_id in ($section_ids)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FS.slide_id=PS.product_slide_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
			$orderby = "order by FS.".$orderby." DESC ";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql = "SELECT *, FS.status as status from PRODUCT_SLIDES PS,FEATURED_SLIDES FS $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get Clinck slides details.
	* @param an integer/comma seperated section id/ section ids array $section_ids.
	* @param an integer/comma seperated slide id/ slide ids array $slide_ids.
	* @param an integer/comma seperated category id/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	* @pre not required.
	* @post featured slides details in associative array.
	* retun an array.
	*/
	function arrGetClinckSlidesDetails($section_ids="",$slide_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->wallpaperKey.'_clinck_slideshow_detail';
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}
		if(is_array($slide_ids)){
			$slide_ids = implode(",",$slide_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "CS.status=$status";
			$whereClauseArr[] = "PS.status=$status";
		}
		if($category_ids!=""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "CS.category_id in ($category_ids)";
		}else{$keyArr[] = -1;}
		if($slide_ids!=""){
			$keyArr[] = $slide_ids;
			$whereClauseArr[] = "CS.slide_id in ($slide_ids)";
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$keyArr[] = $section_ids;
			$whereClauseArr[] = " CS.section_slide_id in ($section_ids)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " CS.slide_id=PS.product_slide_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
			$orderby = "order by CS.".$orderby." DESC ";
		}else{
			$orderby = "order by CS.create_date DESC ";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql = "SELECT *, CS.status as status from PRODUCT_SLIDES PS,CLINCK_SLIDESHOW CS $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get featured wallpapers details.
	* @param an integer/comma seperated section id/ section ids array $section_ids.
	* @param an integer/comma seperated wallpaper id/ wallpaper ids array $wallpaper_ids.
	* @param an integer/comma seperated category id/ category ids array $category_ids.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $cnt.
	* @param string $orderby.
	* @pre not required.
	* @post featured wallpapers details in associative array.
	* retun an array.
	*/
	function arrGetFeaturedWallpaperDetails($section_ids="",$wallpaper_ids="",$category_ids="",$status="1",$startlimit="",$cnt="",$orderby=""){
		$keyArr[] = $this->wallpaperKey.'_featured_detail';
		if(is_array($section_ids)){
			$section_ids = implode(",",$section_ids);
		}

		if(is_array($wallpaper_ids)){
			$wallpaper_ids = implode(",",$wallpaper_ids);
		}
		if(is_array($category_ids)){
			$category_ids = implode(",",$category_ids);
		}	
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "FW.status=$status";
			$whereClauseArr[] = "W.status=$status";
		}else{$keyArr[] = -1;}
		if($category_ids!=""){
			$keyArr[] = $category_ids;
			$whereClauseArr[] = "FW.category_id in ($category_ids)";
		}else{$keyArr[] = -1;}
		if($wallpaper_ids!=""){
			$keyArr[] = $wallpaper_ids;
			$whereClauseArr[] = "FW.wallpaper_id in ($wallpaper_ids)";
		}else{$keyArr[] = -1;}
		if($section_ids!=""){
			$keyArr[] = $section_ids;
			$whereClauseArr[] = " FW.section_wallpaper_id in ($section_ids)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " FW.wallpaper_id=W.wallpaper_id ";
		$whereClauseArr[] = " PW.wallpaper_id=W.wallpaper_id ";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($cnt)){
			$keyArr[] = $cnt;
			$limitArr[] = $cnt;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		if(!empty($orderby)){
			$orderby = "order by FW.".$orderby." DESC";
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sSql = "SELECT *, FW.status as status from WALLPAPER W, PRODUCT_WALLPAPERS PW,FEATURED_WALLPAPERS FW $whereClauseStr $orderby $limitStr";
		$result = $this->select($sSql);
		$this->cache->set($key,$result);
		return $result;
	}

	/**
	* @note function is used to get product option details.
	* @param an integer/comma seperated product color ids/ product color ids array $product_color_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated category id/ category id array $category_id.
	* @param an integer/comma seperated brand id $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @pre not required.
	* @post product option details in associative array.
	* retun an array.
	*/
	function arrGetProductOptionDetails($product_color_ids="",$product_ids="",$category_id="",$brand_id="",$status='1',$startlimit="",$count="")
	{
		$keyArr[] = $this->wallpaperKey.'_product_color_data';
		if(is_array($product_color_ids)){
			$product_color_ids = implode(",",$product_color_ids);
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
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
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
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_color_ids)){
			$keyArr[] = $product_color_ids;
			$whereClauseArr[] = " color_id in ($product_color_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select * from PRODUCT_COLOR $whereClauseStr  order by position asc $limitStr";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
	/**
	* @note function is used to get photos videos details.
	* @post photos videos tab details in associative array.
	* retun an array.
	*/
	function arrGetPhotosVideosTab(){
		$key = $this->wallpaperKey."videophoto_tab";
		if($result = $this->cache->get($key)){return $result;}
		$sql=" select * from VIDEO_GROUP";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}	
	/**
	* @note function is used to get slideshow details.
	* @param an integer/comma seperated slideshow ids/ slideshow ids array $slideshow_ids.
	* @param an integer/comma seperated group ids/ group ids array $group_ids.
	* @param an integer/comma seperated product ids/ product ids array $product_ids.
	* @param an integer/comma seperated product info ids/ product info ids array $product_info_id.
	* @param an integer $product_slide_id.
	* @param an integer/comma seperated category id/ category id array $category_id.
	* @param an integer/comma seperated brand id $brand_id.
	* @param boolean Active/InActive $status.
	* @param integer $startlimit.
	* @param integer $count.
	* @param string $orderby.
	* @pre not required.
	* @post slideshow details in associative array.
	* retun an array.
	*/
	function arrSlideShowDetails($slideshow_ids="",$group_ids="",$product_ids="",$product_info_id="",$product_slide_id="",$category_ids="",$brand_id="",$status="",$startlimit="",$count="",$orderby="",$groupby="",$upcoming="0",$continue="1")
	{
		$keyArr[] = $this->wallpaperKey.'_slideshow_product';
		$whereClauseArr[] = "PI.product_name_id = PS.product_info_id";
		if($upcoming != ''){
			$whereClauseArr[] = "PI.upcoming_flag = $upcoming";
			$keyArr[] =$upcoming;
		}else{$keyArr[] = -1;}

		if($continue != ''){
			$whereClauseArr[] = "PI.discontinue_flag = $continue";
			$keyArr[] =$continue;
		}else{$keyArr[] = -1;}

		if(is_array($slideshow_ids)){
			$slideshow_ids = implode(",",$slideshow_ids);
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
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
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
        if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        }
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
		if($status != ''){
			$keyArr[] = $status;
			$whereClauseArr[] = "S.status=$status";
			$whereClauseArr[] = "PS.status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_slide_id)){
			$keyArr[] = $product_slide_id;
			$whereClauseArr[] = " S.product_slide_id=$product_slide_id ";
		}else{$keyArr[] = -1;}
		if(!empty($slideshow_ids)){
			$keyArr[] = $slideshow_ids;
			$whereClauseArr[] = "S.slideshow_id in ($slideshow_ids)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = "S.product_slide_id=PS.product_slide_id ";
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PS.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($group_ids)){
			$keyArr[] = $group_ids;
			$whereClauseArr[] = "PS.group_id in($group_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PS.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PS.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "PS.product_info_id in ($product_info_id)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " S.video_img_path !=''";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}
		if(!empty($startlimit)){
			$keyArr[] = $startlimit;
			$limitArr[] = $startlimit;
		}else{$keyArr[] = -1;}
		if(!empty($count)){
			$keyArr[] = $count;
			$limitArr[] = $count;
		}else{$keyArr[] = -1;}
		if(sizeof($limitArr) > 0){
			$limitStr = " limit ".implode(" , ",$limitArr);
		}
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		if(!empty($groupby)){
			$groupby = str_replace(array('PS.','S.'),'',$groupby);
			$orderby1 = str_replace(array('PS.','S.'),'',$orderby);
			$sql = "select * from (select S.*,S.title as image_title,PS.title as slideshow_title,PS.product_slide_id as slideshow_product_slide_id,PS.abstract,PS.group_id as slideshow_group_id,PS.category_id,PS.brand_id,PS.product_info_id,PS.product_id,S.media_id as slideshow_media_id,S.media_path as slideshow_media_path,PS.status as slideshow_status,PS.publish_time,PS.create_date as slideshow_create_date,PS.update_date as slideshow_udpate_date from SLIDESHOW S , PRODUCT_SLIDES PS,PRODUCT_NAME_INFO PI  $whereClauseStr $orderby) as tmp $groupby $orderby1 $limitStr";
		}else{
			$sql = "select *,S.title image_title,PS.title slideshow_title,PS.product_slide_id as slideshow_product_slide_id,PS.abstract,PS.group_id as slideshow_group_id,PS.category_id,PS.brand_id,PS.product_info_id,PS.product_id,S.media_id as slideshow_media_id,S.media_path as slideshow_media_path,PS.status as slideshow_status,PS.publish_time,PS.create_date as slideshow_create_date,PS.update_date as slideshow_udpate_date from SLIDESHOW S , PRODUCT_SLIDES PS,PRODUCT_NAME_INFO PI  $whereClauseStr $orderby $limitStr";
		}
		//echo "<br/> sql = $sql"; die();
		$result = $this->select($sql);
		#echo "<pre>"; print_r($result); die;
		$this->cache->set($key,$result);
		return $result;
	}

	function arrSlideShowDetailsCount($slideshow_ids="",$group_ids="",$product_ids="",$product_info_id="",$product_slide_id="",$category_ids="",$brand_id="",$status="",$upcoming="0",$continue="1")
	{
		$whereClauseArr[] = "PI.product_name_id = PS.product_info_id";
		if($upcoming != ''){
        		$whereClauseArr[] = "PI.upcoming_flag = $upcoming";
		}
		if($continue != ''){
       	 		$whereClauseArr[] = "PI.discontinue_flag = $continue";
		}
		$keyArr[] = $this->wallpaperKey.'_data_cnt_slideshow_product';
		if(is_array($slideshow_ids)){
			$slideshow_ids = implode(",",$slideshow_ids);
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
        if(intval($product_ids)!=0){
          $product_ids = intval($product_ids);
        }
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
        if(intval($product_info_id)!=0){
          $product_info_id = intval($product_info_id);
        }
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
		if($status != '')
		{
			$keyArr[] = $status;
			$whereClauseArr[] = "S.status=$status";
			$whereClauseArr[] = "PS.status=$status";
		}else{$keyArr[] = -1;}
		if(!empty($product_slide_id)){
			$keyArr[] = $product_slide_id;
			$whereClauseArr[] = " S.product_slide_id=$product_slide_id ";
		}else{$keyArr[] = -1;}
		if(!empty($slideshow_ids)){
			$keyArr[] = $slideshow_ids;
			$whereClauseArr[] = "S.slideshow_id in ($slideshow_ids)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = "S.product_slide_id=PS.product_slide_id ";
		if(!empty($product_ids)){
			$keyArr[] = $product_ids;
			$whereClauseArr[] = "PS.product_id in($product_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($group_ids)){
			$keyArr[] = $group_ids;
			$whereClauseArr[] = "PS.group_id in($group_ids)";
		}else{$keyArr[] = -1;}
		if(!empty($category_id)){
			$keyArr[] = $category_id;
			$whereClauseArr[] = "PS.category_id in ($category_id)";
		}else{$keyArr[] = -1;}
		if(!empty($brand_id)){
			$keyArr[] = $brand_id;
			$whereClauseArr[] = "PS.brand_id in ($brand_id)";
		}else{$keyArr[] = -1;}
		if(!empty($product_info_id)){
			$keyArr[] = $product_info_id;
			$whereClauseArr[] = "PS.product_info_id in ($product_info_id)";
		}else{$keyArr[] = -1;}
		$whereClauseArr[] = " S.video_img_path !=''";
		if(sizeof($whereClauseArr) > 0){
			$whereClauseStr = " where ".implode(" and ",$whereClauseArr);
		}		
		$key = implode("_",$keyArr);
		if($result = $this->cache->get($key)){return $result;}
		$sql = "select count(*) as cnt from SLIDESHOW S , PRODUCT_SLIDES PS, PRODUCT_NAME_INFO PI $whereClauseStr";
		//echo "<br/> sql = $sql";
		$result = $this->select($sql);
		$this->cache->set($key,$result);
		return $result;
	}
        function getSearchByTagSlideshowResultCount($tags="",$category_id="",$status="1"){
		$keyArr[] = $this->wallpaperKey."_tagslide_detail_data_cnt";
                if(!empty($tags)){
                        if($category_id!=""){
                                //$whereClauseArr[] = " S.category_id = $category_id";
                                $keyArr[] = $category_id;
                        }else{$keyArr[] = -1;}
                        if($status != ''){
                                $whereClauseArr[] = " S.status = $status";
                                $keyArr[] = $status;
                        }else{$keyArr[] = -1;}
                        if($tags != ""){
                                $whereClauseArr[] = "S.tags LIKE '%".$tags."%'";
								$keyArr[] = $tags;
                        }else{$keyArr[] = -1;}
                        $whereClauseArr[] = "S.product_slide_id=PS.product_slide_id";

                        if(sizeof($whereClauseArr) > 0){
                                $whereClauseStr = " ".implode(" and ",$whereClauseArr);
                        }

                        $key = implode('_',$keyArr);
                        $sql = "select * from SLIDESHOW S,PRODUCT_SLIDES PS where $whereClauseStr group by S.product_slide_id";
                        //echo "sql==".$sql;exit;
                        $result = $this->cache->get($key);
                        if(!empty($result)){
                                $result_cnt = sizeof($result);
                                return $result_cnt;
                        }
                        $result = $this->select($sql);
                        $result_cnt = sizeof($result);
                        $this->cache->set($key, $result_cnt);
                        return $result_cnt;
                }
        }
	function getSearchByTagSlideshowResult($tags="",$category_id="",$status="1",$startlimit="",$count="",$orderby="order by S.create_date desc"){
		$keyArr[] = $this->wallpaperKey."_tagslide_detail";
                if(!empty($tags)){
                        if($category_id!=""){
                                //$whereClauseArr[] = " S.category_id = $category_id";
                                $keyArr[] = $category_id;
                        }else{$keyArr[] = -1;}
                        if($status != ''){
                                $whereClauseArr[] = " S.status = $status";
                                $keyArr[] = $status;
                        }else{$keyArr[] = -1;}
                        if($tags != ""){
                            $whereClauseArr[] = "S.tags LIKE '%".$tags."%'";
							$keyArr[] = "tags_".$tags;
                        }else{$keyArr[] = -1;}

                        $whereClauseArr[] = "S.product_slide_id = PS.product_slide_id";

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
                        $sql = "select * from SLIDESHOW S,PRODUCT_SLIDES PS where $whereClauseStr  group by S.product_slide_id $orderby $limitStr";
                        //echo "sql==".$sql;
						$result = $this->cache->get($key);
                        if(!empty($result)){ return $result;}
                        $result = $this->select($sql);
                        $this->cache->set($key, $result);
                        return $result;
                }
        }

	function get_ext_int_models($select_param){
		$keyArr[] = $this->wallpaperKey."_ext_int_model";
		list($brand_id,$category_id) = array($select_param['brand_id'],$select_param['category_id']);
        	$whereClauseArr[] = "pni.category_id = $category_id";
        	$whereClauseArr[] = "ps.product_info_id = pni.product_name_id";
        	$whereClauseArr[] = "ps.group_id in (1,2)";
			if(!empty($brand_id)){
			$whereClauseArr[] = "ps.brand_id = $brand_id";
			$keyArr[] ="brand_id_".$brand_id;
			}else{$keyArr[] = -1;}
        	if(sizeof($whereClauseArr)>0){
        		$whereClauseStr = " where ".implode(' and ', $whereClauseArr);
        	}
		$key = implode('_',$keyArr);	
	        $result = $this->cache->get($key);
                if(!empty($result)){ return $result;}
        	$sql = "select ps.group_id, ps.product_info_id, pni.product_info_name, count(ps.product_slide_id) cnt from PRODUCT_SLIDES ps, PRODUCT_NAME_INFO pni $whereClauseStr group by ps.group_id, ps.product_info_id having cnt > 0";
        	//echo "sql==".$sql;
		$result = $this->select($sql);
		$this->cache->set($key, $result);
		return $result;
        }
}
?>
