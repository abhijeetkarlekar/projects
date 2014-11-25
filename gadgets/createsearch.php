<?php
require_once('./include/config.php');
require_once(CLASSPATH . 'DbConn.php');
require_once(CLASSPATH.'DbOp.php');
$dbconn = new DbConn;
$dbop= new DbOperation;
$sql = "select * from CATEGORY_MASTER order by category_id asc";
$result = $dbop->select($sql);
$cnt = sizeof($result);
for($i=0;$i<$cnt;$i++){
	$category_name = constructRouterUrl($result[$i]['category_name']);
	$seo_path = $result[$i]['seo_path'];
	$category_id = $result[$i]['category_id'];
	if(empty($seo_path)){
		$sql = "update CATEGORY_MASTER set seo_path = '$category_name' where category_id = $category_id";
		$isUpdate = $dbop->update($sql);
	}
}
$sql = "select BM.status,brand_id,brand_name,BM.seo_path,BM.category_id,category_name,CM.seo_path as category_seo_path from BRAND_MASTER as BM,CATEGORY_MASTER CM where CM.category_id = BM.category_id order by brand_id asc";
$result = $dbop->select($sql);
$cnt = sizeof($result);
for($i=0;$i<$cnt;$i++){
	$category_id = $result[$i]['category_id'];
	$brand_id = $result[$i]['brand_id'];
	$brand_name = $result[$i]['brand_name'];
	$category_name = $result[$i]['category_name'];
	$seo_brand_name = constructRouterUrl($brand_name);
	$category_seo_path = $result[$i]['category_seo_path'];
	$seo_path = $result[$i]['seo_path'];
	$status = $result[$i]['status'];
	if(empty($seo_path)){
		$sql = "update BRAND_MASTER set seo_path = '$seo_brand_name' where brand_id = $brand_id";
		$isUpdate = $dbop->update($sql);
		$seo_path = $seo_brand_name;
	}
	unset($request_param);
	if(!empty($category_seo_path) && !empty($seo_path)){
		$request_param['search'] = implode(' ',createSearch(array($brand_name)));
		$request_param['category_id'] = $category_id;
		$request_param['category_name'] = $category_name;
		$request_param['permalink'] = implode('/',createSearchUrl(array($category_seo_path,$seo_path)));
		$request_param['is_brand'] = 1;
		$request_param['is_model'] = 0;
		$request_param['is_variant'] = 0;
		$request_param['status'] = $status;
	        $request_param['update_date'] = date('Y-m-d H:i:s');
		$sql = $dbop->getInsertUpdateSql('SEARCH',array_keys($request_param),array_values($request_param));	
		#echo "$sql \n";
		$dbop->insertUpdate($sql);
	}
}
$sql = "select PI.status,PI.product_name_id,PI.seo_path,PI.product_info_name,PI.category_id,PI.brand_id,BM.seo_path as brand_seo_path,CM.seo_path as category_seo_path,CM.category_name,BM.brand_name from PRODUCT_NAME_INFO as PI,BRAND_MASTER as BM,CATEGORY_MASTER CM where CM.category_id=PI.category_id and PI.brand_id=BM.brand_id order by product_name_id asc";
$result = $dbop->select($sql);
$cnt = sizeof($result);
for($i=0;$i<$cnt;$i++){
	$status = $result[$i]['status'];
	$product_name_id = $result[$i]['product_name_id'];
	$seo_path = $result[$i]['seo_path'];
	$product_info_name = $result[$i]['product_info_name'];
	$category_id = $result[$i]['category_id'];
	$category_name = $result[$i]['category_name'];
	$brand_seo_path = $result[$i]['brand_seo_path'];
	$category_seo_path = $result[$i]['category_seo_path'];
	$brand_name = $result[$i]['brand_name'];
	$seo_model_name = constructRouterUrl($product_info_name);

	if(empty($seo_path)){
                $sql = "update PRODUCT_NAME_INFO set seo_path = '$seo_model_name' where product_name_id = $product_name_id";
                $isUpdate = $dbop->update($sql);
                $seo_path = $seo_model_name;

	}
	unset($request_param);
        if(!empty($category_seo_path) && !empty($seo_path) && !empty($brand_seo_path)){
                $request_param['search'] = implode(' ',createSearch(array($brand_name,$product_info_name)));
                $request_param['category_id'] = $category_id;
		$request_param['category_name'] = $category_name;
                $request_param['permalink'] = implode('/',createSearchUrl(array($category_seo_path,$brand_seo_path,$seo_path)));
                $request_param['is_brand'] = 0;
                $request_param['is_model'] = 1;
                $request_param['is_variant'] = 0;
                $request_param['status'] = $status;
                $request_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $dbop->getInsertUpdateSql('SEARCH',array_keys($request_param),array_values($request_param));
                #echo "$sql \n";
                $dbop->insertUpdate($sql);
        }
}
$sql = "select PM.status,PM.product_id,PM.seo_path,PM.product_name,PM.variant,PI.seo_path as model_seo_path,PI.product_info_name,PM.category_id,PM.brand_id,BM.seo_path as brand_seo_path,CM.seo_path as category_seo_path,CM.category_name,BM.brand_name from PRODUCT_NAME_INFO as PI,BRAND_MASTER as BM,CATEGORY_MASTER as CM,PRODUCT_MASTER as PM where CM.category_id=PM.category_id and PM.brand_id=BM.brand_id and PM.product_name=PI.product_info_name order by product_name_id asc";
$result = $dbop->select($sql);
$cnt = sizeof($result);
for($i=0;$i<$cnt;$i++){
	$status = $result[$i]['status'];
        $product_id = $result[$i]['product_id'];
        $seo_path = $result[$i]['seo_path'];
        $variant = $result[$i]['variant'];
        $model_seo_path = $result[$i]['model_seo_path'];
        $product_info_name = $result[$i]['product_info_name'];
        $category_id = $result[$i]['category_id'];
        $category_name = $result[$i]['category_name'];
        $brand_seo_path = $result[$i]['brand_seo_path'];
        $category_seo_path = $result[$i]['category_seo_path'];
        $brand_name = $result[$i]['brand_name'];
        $seo_model_name = constructRouterUrl($product_info_name);
        $seo_product_name = constructRouterUrl($product_name);
	$seo_variant_name = constructRouterUrl($variant);

        if(empty($seo_path)){		
		if(empty($variant)){
                	$sql = "update PRODUCT_MASTER set seo_path = '$seo_product_name' where product_id = $product_id";			
                	$seo_path = $seo_product_name;
		}else{
                	$sql = "update PRODUCT_MASTER set seo_path = '$seo_variant_name' where product_id = $product_id";			
                	$seo_path = $seo_variant_name;
		}
                $isUpdate = $dbop->update($sql);
        }
        unset($request_param);
        if(!empty($category_seo_path) && !empty($seo_path) && !empty($brand_seo_path)){		
                $request_param['search'] = implode(' ',createSearch(array($brand_name,$product_info_name,$variant)));
                $request_param['category_id'] = $category_id;
                $request_param['category_name'] = $category_name;
                $request_param['permalink'] = implode('/',createSearchUrl(array($category_seo_path,$brand_seo_path,$model_seo_path,$seo_path)));
                $request_param['is_brand'] = 0;
                $request_param['is_model'] = 0;
                $request_param['is_variant'] = 1;
                $request_param['status'] = $status;
                $request_param['update_date'] = date('Y-m-d H:i:s');
                $sql = $dbop->getInsertUpdateSql('SEARCH',array_keys($request_param),array_values($request_param));
                #echo "$sql \n";
                $dbop->insertUpdate($sql);
        }

}
function createSearch($arr){
	$arr = array_filter($arr);
	$arr = array_unique($arr);
	return $arr;
}
function createSearchUrl($arr){
	$arr = array_filter($arr);
        $arr = array_unique($arr);
        return $arr;
}
function constructRouterUrl($name){
                $name = html_entity_decode($name,ENT_QUOTES,'UTF-8');
                $name = removeSlashes($name);
                $name = str_replace("."," ",$name);
                $pos = strpos($name, "-");
                if ($pos != "") {
                                $name = str_replace(" -","-",$name);
                        $name = str_replace("- ","-",$name);
                }
                $spc = array(" ","  ","   ","    ");
                $name = str_replace($spc," ",$name);
                $name = str_replace("%20"," ",$name);
                $name = str_replace(" ","-",$name);

                //$name = str_replace(array("/",",","$","?","%","#","+","*","_","!","@","'",":","&",";"),"",$name);
                $chars = array("%21","%22","%23","%24","%25","%26","%27","%28","%29","%2A","%2B","%2C","%2D","%2E","%2F","%30","%31","%32","%33","%34","%35","%36","%37","%38","%39","%3A","%3B","%3C","%3D","%3E","%3F","%40","%41","%42","%43","%44","%45","%46","%47","%48","%49","%4A","%4B","%4C","%4D","%4E","%4F","%50","%51","%52","%53","%54","%55","%56","%57","%58","%59","%5A","%5B","%5C","%5D","%5E","%5F","%60","%61","%62","%63","%65","%66","%67","%68","%69","%6A","%6B","%6C","%6D","%6E","%6F","%70","%71","%72","%73","%74","%75","%76","%77","%78","%79","%7A","%7B","%7C","%7D","%7E","%7F","/",",","$","?","%3E","%","#","+","*","_","!","@","'",":",";","&","(",")",".","~","<",">","{","}","|","[","]","=","^","`",'"');
                $cnt_chars = sizeof($chars);
                for($i=0;$i<$cnt_chars;$i++){
                                $char = $chars[$i];
                                $char1 = "-".$chars[$i];
                                $char2 = $chars[$i]."-";
                                $cha_arr = array($char1,$char2,$char);
                                $name = trim(str_ireplace($cha_arr,"",$name));
                }
                $hyp_arr = array("-----","----","---","--","-");
                $name = str_replace($hyp_arr,"-",$name);
                return trim(strtolower($name));
   }

