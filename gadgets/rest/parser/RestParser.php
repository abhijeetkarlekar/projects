<?php
	require_once(UTILITY_PATH.'utility.php');
	require_once(CLASSPATH.'category.class.php');
	class RestParser extends dataStore{
		var $domain = WEB_URL;
		var $seo_url = SEO_WEB_URL;

		var $url;
		var $page_200;
		var $requestArr;
		var $urlArr;
		var $paramsArr; // used to build 301 request url
		var $duplicateUrl = '0';
		var $key301;
		var $processedMatches;
		var $rules = Array(
			'301'=>Array(
				'variant'=>Array(
					'/^(.*)\/(.*)\/([0-9]+)$/'
				),
				'search' => Array(
					'/(^Mobile-finder)*\/(.*)/',
					'/(^Mobile-finder)*$/',				
										
				),
				'compare' => Array(			
					'/(^Compare-mobiles)*\/(.*)\/1\/(.*)\/1/',
					'/(^Compare-mobiles)*$/',
				),
			),
			/*'200'=>Array(
				'cardealers'=>Array(
					'/^dealers\/(.*)\/(.*)\/(.*)$/',
					'/^dealers\/(.*)\/(.*)$/',
					'/^dealers\/(.*)$/',
					'/^dealers$/',
				),
				'dealerdetails'=>Array(
					//'/^dealer\/(.*)\/(.*)\/(.*)$/',
					//'/^dealer\/(.*)\/(.*)$/'
				)
			)*/
		);

		function RestParser(){
			$category = new CategoryManagement;
			$url = rawurldecode($_SERVER['REQUEST_URI']);
			if($url === '/'){ $url = '';}
			if(empty($url)) return false;
			$url= ltrim ($url,'/');
			$url = explode("/", $url);

			$cat_name = $url[0];
			$is301 = false;
			$_301CatArr = Array('Mobile','Mobile-finder','Compare-mobiles');
			$tmpKey = array_search($cat_name,$_301CatArr,true);
			if($tmpKey !== false){
				$cat_name = 'mobile-phones';
				$_SERVER ['SCRIPT_NAME'] = str_replace($_301CatArr[$tmpKey],$cat_name,$_SERVER ['SCRIPT_NAME']);
				$is301 = true;
			}

			if(!empty($cat_name)){
				$result = $category->intCategoryUsingCatPAth($cat_name);
				if(sizeof($result) > 0){
					$category_id = $result[0]['category_id'];
					$category_name = $result[0]['category_name'];
					$cat_path = $result[0]['seo_path'];
					if(empty($is301)){
						unset($url[0]);
					}
					unset($result);
					if(!empty($category_id)){
						$_SERVER['SCRIPT_NAME'] = str_replace("/$cat_path/", "", $_SERVER ['SCRIPT_NAME']);
					}
				}else{
					$_SERVER['SCRIPT_NAME'] = str_replace($_SERVER['SCRIPT_NAME'],'',$_SERVER['SCRIPT_URI']); 
				}
			}
			$url = implode('/', $url);
			$_REQUEST['category_id'] = $category_id;
			$_REQUEST['category_name'] = $category_name;
			$_REQUEST['cat_path'] = $cat_path;
			
			if(strstr($url,'?')){
				$url = explode('?',$url);
				$url = $url[0];
			}else if(strstr($url,'#')){
				$url = explode('#',$url);
                $url = $url[0];
			}
			#print_r($_SERVER);
			#echo $is301.' & '.$cat_path.' & '.$url.' & '.$_SERVER['SCRIPT_NAME'];die();
			if(empty($is301) && $url !== rawurldecode($_SERVER ['SCRIPT_NAME'])){
				if(!empty($_SERVER['SCRIPT_NAME'])){
					$this->redirect301Url(WEB_URL); //used to check for extra slashes between url i.e. http://betav2.oncars.in//maruti-suzuki/omni	
				}		
            }
            #die("HERE === ".WEB_URL);
			$this->url = $this->cleanUrl($url);
			#die($this->url);

			if(!empty($is301)){
				$_301Url = $this->is301Url();
			}else if(empty($_301Url)){
				return $this->get200UrlType();
			}
			
		}
		function parse200Url($type,$searchArr){
			$searchArr = explode('/',$this->url);
			if(strpos($this->url, 'brands')  !== false){
				//used to land on all brand page.
				$this->buildBrandParam('allbrands',$searchArr);
			}else if(strpos($this->url, 'popular')  !== false){
				$popularcar = $this->buildPopularCarComparison($type,$searchArr);
			}else if(strpos($this->url, 'compare')  !== false){
				$comparecar = $this->buildCompareParam($type,$searchArr);
			}else{
				$this->buildBrandParam('',$searchArr);
				$this->buildModelParam('',$searchArr);
				$this->buildVariantParam('',$searchArr);
				$this->buildModelReviewParam('',$searchArr);
				$this->buildMediaParam($this->requestArr['action'],$searchArr);
			}
			$brand_id = $this->requestArr['router_brand_id'];
			$model_id = $this->requestArr['router_model_id'];
			$product_id = $this->requestArr['router_product_id'];

			#echo '<pre>';
			#print_r($this->requestArr);
			#print_r($searchArr);
			#print_r($this->paramsArr);
			#$result = array_diff($searchArr, $this->paramsArr);
			#echo $this->url."here ?<Br/>";
			#print_r($result);
			#print_r($_REQUEST);
			#die;

			if($searchArr !== $this->paramsArr){
				#echo "here";
				$this->duplicateUrl = '1';
			}

			if(!empty($this->duplicateUrl) or sizeof($this->paramsArr) <= 0){
				$this->build301Url($type,$matches);
			}


			if(empty($this->page_200)){
				unset($this->paramsArr);unset($this->requestArr);unset($_REQUEST['q']);
				$this->build301Url();
			}
			unset($this->processedMatches);
			#die($this->page_200.' & '.sizeof($this->paramsArr));
			return $this->page_200;
		}
		function get200UrlType(){
			$arr1 = $this->rules['200'];
			if(is_array($arr1)){
				foreach($arr1 as $key=>$arr){
					foreach($arr as $regex){
					#echo $regex.'<br/>url-'.$this->url,'<br/>';
						if(preg_match_all($regex,$this->url,$matches,PREG_SET_ORDER)){
							#echo "$key <Br/>";print_r($matches);die();
							$matches = $this->validateMatches($matches);
							return $this->parse200Url($key,$matches);
						}
					}
				}
			}
			return $this->parse200Url('',explode('/',$this->url));
		}
		function is301Url(){

			if(empty($this->key301)){ return '0'; }
			$arr = $this->rules['301'][$this->key301];
			if(is_array($arr)){
				foreach($arr as $key => $regex){
					echo $regex.'<br/>url-'.$this->url,'<br/>';
					if(preg_match_all($regex,$this->url,$matches,PREG_SET_ORDER)){
						#echo "$regex <Br/>";print_r($matches);#die();
						$matches = $this->validateMatches($matches);
						#echo "$regex <Br/>";print_r($matches);die();
						return $this->build301Url($this->key301,$matches);
					}

				}
			}

			return '0';
		}
		function build301Url($type,$matches){
			#print_r($matches);
			#echo "tyype ==== $type";die();
			switch($type){
				case 'variant':
					$this->build301VariantParam($type,$matches);
					break;
				case 'search':
					$this->build301SearchParam();
					break;
				case 'compare':
					$this->build301CompareParam($matches);
					break;
			}
			#echo "here === $type <Br/>& ".$this->url.'<Br/> & '.$this->page_200;die();
#			print_r($this->paramsArr);die();
			$this->paramsArr = array_filter($this->paramsArr);
			if(sizeof($this->paramsArr) > 0){
				$params = implode('/',$this->paramsArr);
			}
			$url = $this->buildUrl($params);	
			#die("301 parser == $url");
			unset($this->paramsArr);
			if(!empty($url)){
				$this->redirect301Url($url);
			}
			return $type;
		}
		function build301SearchParam(){
			$url = $_SERVER['SCRIPT_URL'];
			$url = strtolower(str_replace('Mobile-finder','search',$url));
			return $this->paramsArr = array_merge(array('mobile-phones'),explode('/',$url));
		}
		function build301CompareParam($matches){
			$matches = $matches[0];
			if(sizeof($matches) < 3){
				return $this->paramsArr = array('mobile-phones','compare');
			}
			$product_ids = $matches[3];
			$product_ids = explode(',',$product_ids);
			$product_ids = array_unique($product_ids);
			$product_ids = implode(',',$product_ids);

			$category_id = $_REQUEST['category_id'];

			$key = !empty($category_id) ? GET_COMPARE_VARIANT.$category_id : GET_COMPARE_VARIANT;
			$key = $key.'_301_'.$variant_id;
			
			$result = $this->getRouterCache($key);
		    if(!empty($result)){ return $result; }

			$sql = "select PM.brand_id,product_id,variant,product_name_id as model_id,PM.discontinue_flag,PM.discontinue_date,PM.arrival_date,PM.seo_path as seo_path,PI.seo_path as model_seo_path,BM.seo_path as brand_seo_path from PRODUCT_MASTER as PM,PRODUCT_NAME_INFO as PI,BRAND_MASTER as BM where PM.product_id in ($product_ids) and BM.brand_id = PM.brand_id and PM.product_name = PI.product_info_name and PM.status=1 order by product_id asc";
			$result = $this->select($sql);

            $cnt = sizeof($result);
			$compareArr =Array();
            for($i=0;$i<$cnt;$i++){
				$seo_path = trim($result[$i]['seo_path']);
				$brand_seo_path = trim($result[$i]['brand_seo_path']);
				$model_seo_path = trim($result[$i]['model_seo_path']);
				$seo_path = strtolower(html_entity_decode($seo_path,ENT_QUOTES,'UTF-8'));
				$brand_seo_path = strtolower(html_entity_decode($brand_seo_path,ENT_QUOTES,'UTF-8'));
				$model_seo_path = strtolower(html_entity_decode($model_seo_path,ENT_QUOTES,'UTF-8'));
				unset($nameArr);unset($cleannameArr);	
				if(!empty($brand_seo_path)){			
					$nameArr[] = constructRouterUrl($brand_seo_path);
				}
				if(!empty($model_seo_path)){
					$nameArr[] = constructRouterUrl($model_seo_path);
				}
				if(!empty($seo_path) && $model_seo_path !== $seo_path){
					$nameArr[] = constructRouterUrl($seo_path);
				}

				if($result[$i]['discontinue_flag'] == '0'){
					$year =  buildYear($result[$i]['arrival_date'],$result[$i]['discontinue_date']);
					if(!empty($year)){
						$nameArr[] = $year;					
					}
				}
				$nameArr = array_filter($nameArr);
				$nameArr = array_unique($nameArr);
				$compareArr[] = constructRouterUrl(implode('-',$nameArr));
				
			}
			#print_r($compareArr);
			$compareUrl = implode('-Vs-',$compareArr);
			$this->paramsArr[] = 'compare';
			$this->paramsArr[] = $compareUrl;
			return true;
		}
		function build301VariantParam($type,$matches){

			$variant_id = $matches[2];

			if(empty($variant_id)) return false;

            $category_id = $_REQUEST['category_id'];

			$key = !empty($category_id) ? GET_VARIANT_KEY.$category_id : GET_VARIANT_KEY;
			$key = $key.'_301_'.$variant_id;
			
			$result = $this->getRouterCache($key);
		    if(!empty($result)){ return $result; }

		    $sql = "select PM.brand_id,product_id,variant,product_name_id as model_id,PM.discontinue_flag,PM.discontinue_date,PM.arrival_date,PM.seo_path as seo_path,PI.seo_path as model_seo_path,BM.seo_path as brand_seo_path from PRODUCT_MASTER as PM,PRODUCT_NAME_INFO as PI,BRAND_MASTER as BM where PM.product_id = $variant_id and BM.brand_id = PM.brand_id and PM.product_name = PI.product_info_name and PM.status=1 order by product_id asc";


			$result = $this->select($sql);
			$cnt = sizeof($result);
			for($i=0;$i<$cnt;$i++){
				$seo_path = $result[$i]['seo_path'];
				$brand_seo_path = $result[$i]['brand_seo_path'];
				$model_seo_path = $result[$i]['model_seo_path'];
				$seo_path = strtolower(html_entity_decode($seo_path,ENT_QUOTES,'UTF-8'));
				$brand_seo_path = strtolower(html_entity_decode($brand_seo_path,ENT_QUOTES,'UTF-8'));
				$model_seo_path = strtolower(html_entity_decode($model_seo_path,ENT_QUOTES,'UTF-8'));
				unset($nameArr);unset($cleannameArr);
				$nameArr[] = constructRouterUrl($_REQUEST['cat_path']);
				if(!empty($brand_seo_path)){
					$nameArr[] = constructRouterUrl($brand_seo_path);
				}
				if(!empty($model_seo_path)){
					$nameArr[] = constructRouterUrl($model_seo_path);
				}
				if(!empty($seo_path) && $model_seo_path !== $seo_path){
					$nameArr[] = $seo_path;
				}

				$cleannameArr[] = $nameArr;
				if($result[$i]['discontinue_flag'] == '0'){
					$year =  buildYear($result[$i]['arrival_date'],$result[$i]['discontinue_date']);
					if(!empty($year)){
						$nameArr[] = $year;
						$cleannameArr[] = cleanStr($year);
					}
				}
				$this->paramsArr = $nameArr;
			}
            unset($result);
           	$result = $this->paramsArr;
			$this->setRouterCache($key,$result);
            return $result;
		}
		function buildCompareParam($type,$matches){
			$reviewArr = Array('1'=>'compare');
            $validReviewArr = Array('1'=>'compare');
            $review301Arr = Array('compare'=>'compare');
			$result = $this->getCompareVariant();
			
			$variantArr = $result['compare'];
			$validVariantArr = $result['validcompare'];			
			$cnt = sizeof($matches);
            for($i=0;$i<$cnt;$i++){
            	if(in_array($matches[$i],$this->processedMatches)){ continue; }
                    $review_name = $matches[$i];
					if(empty($review_name)){ continue; }
                    $reviewname1 = cleanStr(strtolower($review_name));

                    if($key = array_search($reviewname1,$reviewArr)){
                    	$this->processedMatches[] = $matches[$i];
                            $reviewname = $validReviewArr[$key];
                            if($this->validateRequestParam($review_name,$key,$validReviewArr) == '0'){
                            	$reviewname = $validReviewArr[$key];
                            }
							$this->paramsArr[] = $reviewname;
                        	$this->page_200 = 'comparepage';
                            continue;
                    }

			       	if($type === 'compare' && $i < 2){
						continue;	//mandatory condition for 301 urls.
			       	}
			       	$products = rawurldecode($review_name);
			       	if(!strstr($products,',') && !strstr($products,'-Vs-')){
						//used to convert single compare items into an array.
						if (is_numeric($products)) {
							$products = $products.',';
						}else{
							$products = $products.'-Vs-';
						}
			       	}
			       	if(strstr($products,',')){
                        $productArr = explode(',',$products);
						#print_r($productArr);
						if(!is_array($productArr)){
							$productArr[] = $products;
						}
						if(sizeof($productArr) > 4){
							array_pop($productArr);
						}
						//used to move 301 urls.
		                $variantArr = array_flip($variantArr);
						foreach($productArr as $product_id){
							if(empty($product_id)) continue;
							$product_id = strtolower($product_id);
							if (is_numeric($product_id)) {
								if(!empty($validVariantArr[$product_id])){
									$compareArr[] = $validVariantArr[$product_id];
									$compareproductIdsArr[] = $product_id;
								}
							}
						}
						$productname = implode('-Vs-',$compareArr);
						$productids = implode(',',$compareproductIdsArr);
						unset($compareArr);unset($compareproductIdsArr);
						break;
                    }else{
						if(strstr($products,'-Vs-')){
	                        $productArr = explode('-Vs-',$products);
							if(is_array($productArr)){
								if(sizeof($productArr) > 4){
	                                array_pop($productArr);
	                            }
								foreach($productArr as $product_name){
									if(empty($product_name)) continue;
									$product_name1 = cleanStr(strtolower($product_name));
									#echo "product_name1 === $product_name1 <Br/>";print_R($variantArr);die();
									if($product_id = array_search($product_name1,$variantArr)){
	        			                $this->processedMatches[] = $product_name;
	                                	$productname = $validVariantArr[$product_id];
	            			       		if($this->validateRequestParam($product_name,$product_id,$validVariantArr) == '0'){
		                                        $productname = $validVariantArr[$product_id];
	            		                }
										$compareArr[] = $productname;
										$compareproductIdsArr[] = $product_id;
					        	    }
								}
								$productname = implode('-Vs-',$compareArr);
			                    $productids = implode(',',$compareproductIdsArr);
	        	                unset($compareArr);unset($compareproductIdsArr);
							}
							break;
	                    }
					}
            	}
			if(!empty($reviewname) && !empty($productids)){
               	$this->paramsArr[] = $productname;
				$this->requestArr['router_compare_id'] = $productids;
            }
			
			return $productname;

		}

		function buildPopularCarComparison($type,$matches){
			$reviewArr = Array('1'=>'popularcarcomparisons');
			$validReviewArr = Array('1'=>'popular-car-comparisons');
			$review301Arr = Array('choosecomparecars'=>'popular-car-comparisons');
			if(!empty($type)){
                if(!in_array($type,$this->processedMatches)){
                        $this->page_200 = 'popularcarcomparisons';
                        $this->processedMatches[] = $type;
                        $this->paramsArr[] = $review301Arr[$type];
                }
            }else{
				$cnt = sizeof($matches);
                for($i=0;$i<$cnt;$i++){
                    if(in_array($matches[$i],$this->processedMatches)){ continue; }
                        $review_name = $matches[$i];
						if(empty($review_name)){ continue; }
                        $reviewname1 = cleanStr(strtolower($review_name));
                        if($key = array_search($reviewname1,$reviewArr)){
                            $this->processedMatches[] = $matches[$i];
                            $reviewname = $validReviewArr[$key];
                            if($this->validateRequestParam($review_name,$key,$validReviewArr) == '0'){
                                $reviewname = $validReviewArr[$key];
                            }
                            break;
                        }
                }
                if(!empty($reviewname)){
                    $this->page_200 = 'popularcarcomparisons';
                    if(!in_array($reviewname)){
                        $this->paramsArr[] = $reviewname;
                    }
                }
			}
			return $reviewname;

		}


		function buildModelReviewParam($type,$matches){
			$reviewArr = Array('1'=>'reviews','2'=>'userreviews','3'=>'expertreviews','4'=>'writereview','5'=>'news','6'=>'features','7'=>'videos','8'=>'photos','9'=>'wallpapers');
			$validReviewArr = Array('1'=>'reviews','2'=>'user-reviews','3'=>'expert-reviews','4'=>'write-review','5'=>'news','6'=>'features','7'=>'videos','8'=>'photos','9'=>'wallpapers');

			$review301Arr = Array('modelallreview'=>'reviews','modeluserreviews'=>'user-reviews','modelexpertreviews'=>'expert-reviews','modelwritecarreviews'=>'write-review','news'=>'news','variantfeature'=>'features','variantexpertreviews'=>'expert-reviews','variantuserreviews'=>'user-reviews','variantallreviews'=>'reviews');
			$cnt = sizeof($matches);
			if(!empty($type)){
				if(!in_array($type,$this->processedMatches)){
					if(stripos($type,'write')){
						$this->page_200 = 'writereviewpage';
					}
					$this->processedMatches[] = $type;
					$this->paramsArr[] = $review301Arr[$type];
					$this->requestArr['action'] = str_replace('-','_',$review301Arr[$type]);
				}
			}else{
	            for($i=0;$i<$cnt;$i++){
					if(in_array($matches[$i],$this->processedMatches)){ continue; }
					$review_name = $matches[$i];
					if(empty($review_name)){ continue; }
					$reviewname = cleanStr(strtolower($review_name));
					if($reviewname == 'writereview'){
						if(!empty($this->requestArr['router_product_id'])){
							$this->page_200 = 'writeuserreviewpage';
						}else{
							$this->page_200 = 'writereviewpage';
						}
					}

					if($key = array_search($reviewname,$reviewArr)){
						$this->processedMatches[] = $matches[$i];
						#$key = $reviewArr[$key];
    	                $reviewname = $validReviewArr[$key];
                        if($this->validateRequestParam($review_name,$key,$validReviewArr) == '0'){
                            $reviewname = $validReviewArr[$key];
                        }
                    	break;
                    }else{
						unset($review_name);unset($reviewname);
					}
				}
				if(!empty($reviewname)){
                	$this->paramsArr[] = $reviewname;
        	        $this->requestArr['action'] = str_replace('-','_',$reviewname);
					if($reviewname == 'expert-reviews' && !empty($this->requestArr['router_product_id'])){
                        $this->buildExpertReviewTitleParam($type,$matches);
                    }
                }
			}
			#echo "here";print_r($this->paramsArr);die();
			return $reviewname;
		}

		function buildVariantParam($type,$matches){

			$isyearArr = $this->buildYearParam($type,$matches);

			$isyear = $isyearArr['isyear'];
			$foundyear = $isyearArr['year'];
            $result = $this->getVariant();

			$variantArr = $result['variant'][$this->requestArr['router_brand_id']][$this->requestArr['router_model_id']];
            $validVariantArr = $result['validvariant'][$this->requestArr['router_brand_id']][$this->requestArr['router_model_id']];
            $cnt = sizeof($matches);
			$isvalidyear = '0';
			
			for($i=0;$i<$cnt;$i++){
				$variant_name = str_ireplace(array($this->requestArr['router_brand_name']),'',$matches[$i]);
				if(empty($variant_name)){ continue; }
				if(!empty($foundyear)){
					$variant_name = $variant_name.'_'.$foundyear;
				}
				$variant_name = trim($variant_name);
				#echo "$i ) $matches[$i] ==== WTF === $variant_name <Br/>";
				#echo "<pre>";print_r($variantArr);
				if($variant_id = array_search($variant_name,$variantArr)){
					$this->processedMatches[] = $matches[$i];
					$variantname = $validVariantArr[$variant_id];
					break;
				}
			}
			#print_r($result);die("$variant_id === WTF === $variantname");
			if(!empty($variantname) && !empty($variant_id)){
				$this->page_200 = 'variantpage';				
				$this->requestArr['action'] = 'variant';
				$this->requestArr['router_product_id'] = trim($variant_id);
				if(!empty($isyear)){
					$this->requestArr['router_year_id'] = trim($foundyear);
					$this->paramsArr[] = trim($foundyear);
				}
				if($this->requestArr['router_model_name'] !== $variantname) {
					$this->requestArr['router_product_name'] = trim($variantname);
					$this->paramsArr[] = trim(str_replace('_'.$foundyear,'',$variantname));
				}				
				$this->requestArr['router_product_name'] = '';
			}
			#print_r($this->requestArr);die("HERE");
            return $variantname;
        }
        function buildYearParam($type,$matches){
			$yearArr = $this->getYear();
			$yearArr = $yearArr['year'];
			$cnt = sizeof($matches);
			$isyear = '0';
			for($i=0;$i<$cnt;$i++){
				if(in_array($matches[$i],$this->processedMatches)){ continue; }
				$year = str_ireplace($this->requestArr['router_brand_name'],'',$matches[$i]);
				if($year_id = array_search($year,$yearArr)){
					$validyear = $year;
					$isyear = '1';
					$this->processedMatches[] = $matches[$i];
					break;
				}
			}
			return array('isyear'=>$isyear,'year'=>$validyear);
		}
		function buildModelParam($type,$matches){
			#print_r($this->processedMatches);die();
			$result = $this->getModel();
            $modelArr = $result['model'][$this->requestArr['router_brand_id']];
            $validModelArr = $result['validmodel'][$this->requestArr['router_brand_id']];
            $cnt = sizeof($matches);
            for($i=0;$i<$cnt;$i++){

				if(in_array($matches[$i],$this->processedMatches)){ continue; }
				$modelname = str_ireplace($this->requestArr['router_brand_name'],'',$matches[$i]);
				if(empty($modelname)){ continue; }
				if($model_id = array_search($modelname,$modelArr)){
					$this->processedMatches[] = $matches[$i];
					$modelname = $validModelArr[$model_id];
					break;
				}
			}
			if(!empty($modelname) && !empty($model_id)){
				$this->page_200 = 'modelpage';
				$this->paramsArr[] = $modelname;
				$this->requestArr['action'] = 'model';
				$this->requestArr['router_model_id'] = $model_id;
				$this->requestArr['router_model_name'] = $modelname;
			}
            return $modelname;
		}
		function buildMediaParam($action,$matches){
			if($action === 'photos'){
				$result = $this->getSlideshowSlug();
			}else if($action === 'videos'){
				$result = $this->getVideoSlug();
			}else{
				return false;
			}
			
			$mediaArr = $result['media'][$_REQUEST['category_id']][$this->requestArr['router_brand_id']][$this->requestArr['router_model_id']];
            $validMediaArr = $result['validmedia'][$_REQUEST['category_id']][$this->requestArr['router_brand_id']][$this->requestArr['router_model_id']];
            $cnt = sizeof($matches);            
            for($i=0;$i<$cnt;$i++){
				if(in_array($matches[$i],$this->processedMatches)){ continue; }
				$mediatitle = $matches[$i];
				if(empty($mediatitle)){ continue; }				
				if($media_id = array_search($mediatitle,$mediaArr)){
					$this->processedMatches[] = $matches[$i];
					$mediatitle = $mediaArr[$media_id];
					break;
				}
			}
			if(!empty($mediatitle) && !empty($media_id)){
				$this->paramsArr[] = $mediatitle;
				$this->requestArr['router_media_id'] = $media_id;
				$this->requestArr['router_media_name'] = $mediatitle;
			}
			return $mediatitle;
		}
		function buildBrandParam($type,$matches,$getpage="1"){

			if($type == 'allbrands'){
				$this->paramsArr[] = 'brands';
				if(!empty($getpage)){
                	$this->page_200 = 'allbrandpage';
                }
			}
			$result = $this->getBrands($type);


			$brandArr = $result['brand'];
			$validBrandArr = $result['validbrand'];
			$cnt = sizeof($matches);
			for($i=0;$i<$cnt;$i++){
				if(in_array($matches[$i],$this->processedMatches)){ continue; }
				$brand_name = $matches[$i];
				if(empty($brand_name)){ continue; }
				$bname = cleanStr(strtolower($brand_name));

				if($brand_id = array_search($bname,$brandArr)){
					$this->processedMatches[] = $matches[$i];
					$bname = $validBrandArr[$brand_id];
					break;
				}
			}
#			print_r($matches);die("$bname & $brand_id");
			if(!empty($bname) && !empty($brand_id)){
				if(!in_array($bname,$this->paramsArr)){
					#echo "brand";
					$this->paramsArr[] = $bname;
					if(isset($matches[1]) && $matches[1] === 'page'){
						$this->paramsArr[] = $matches[1];
						$this->paramsArr[] = $matches[2];
					}
					if(!empty($getpage)){
						$this->page_200 = 'brandpage';
					}
					$this->requestArr['page'] = (isset($matches[2]) && !empty($matches[2])) ? $matches[2] : 1;
					$this->requestArr['router_brand_id'] = $brand_id;
					$this->requestArr['router_brand_name'] = $bname;
				}
			}

			return $bname;

		}

		function redirect301Url($url){
			header('Location: '.$url,TRUE,301);
			exit;
		}
		function buildUrl($params){
			$urlArr[] = !empty($this->seo_url) ? $this->seo_url : $this->url;
			if(!empty($params)){
				$tmpurlArr = explode('/',$params);
				$urlArr = array_merge($urlArr,$tmpurlArr);
				unset($tmpurlArr);
			}
			$url = implode('/',$urlArr);
			unset($urlArr);
			return $url;
		}
		function validateMatches($arr){
			
			foreach ($arr as $matches){

				$key = array_search($this->url,$matches);

				if($key !== ''){
					unset($matches[$key]);
				}
				#sort($matches,SORT_ASC);
			}
			#echo $this->url."<Br><pre>";print_r($matches);
			$matches = array_values($matches);
			$checkArr = Array('-cars');
			$matches1 = Array();
			foreach($matches as $key => &$val){
				$val = trim($val);
				if(!is_numeric($val)){
					$val = str_replace($checkArr,'',$val);
					$checkArr[] = "$val-";
					$checkArr[] = $val;
				}
				if(!empty($val)){
					$matches1[] = $val;
				}

			}
			return $matches1;
		}
		function validateRequestParam($val,$key,$arr){
			#echo "$val === ".$arr[$key].'<Br/>';die();
			if(strcmp($val,$arr[$key])){
				$this->duplicateUrl = 1;
				return '0';
			}
			return '1';
		}
		function cleanUrl($url){
			$url = str_replace(array($this->domain),array(''),$url);
			$pos = strpos($url, '/');
			if($pos === 0){
				$url = substr($url, 1);
			}
			$this->urlArr = explode('/',$url);
			$this->set301Key();
			return $url;
		}
		function set301Key(){
			$array1 = $this->urlArr;
			foreach($array1 as &$val){
				$val = str_replace(array('Mobile-finder','Mobile','Compare-mobiles'),array('search','variant','compare'),$val);
			}
			$array2 = array_keys($this->rules['301']);
			#print_r($array1);die();
			$res = array_uintersect($array1, $array2, "strcasecmp");
			#print_r($res);die();
			sort($res);
			if(sizeof($res) == 1){
				$this->key301 = $res[0];

			}
			return $this->key301;
		}
	}
	class dataStore extends DbOperation{
		function dataStore(){

		}
		function getBrands($type){
			$category_id = $_REQUEST['category_id'];
			$key = !empty($category_id) ? GET_BRAND_KEY.$category_id : GET_BRAND_KEY;
			$result = $this->getRouterCache($key);
            if(!empty($result)){ return $result; }
            if(!empty($category_id)){
            	$sql = "select brand_id,brand_name,seo_path from BRAND_MASTER where category_id = $category_id order by brand_id asc";
            }else{
            	$sql = "select brand_id,brand_name,seo_path from BRAND_MASTER";
            }

			$result = $this->select($sql);

    		$cnt = sizeof($result);
    		for($i=0;$i<$cnt;$i++){
	                $seo_path = $result[$i]['seo_path'];
	                $brandArr[$result[$i]['brand_id']] = constructRouterUrl($seo_path);
            		$validBrandArr[$result[$i]['brand_id']] = constructRouterUrl($seo_path);
	        }
			unset($result);
			$result = array('brand' => $brandArr,'validbrand'=>$validBrandArr);

			$this->setRouterCache($key,$result);
            return $result;
		}
		function getStyles($type){
			$result = $this->getRouterCache(GET_BODY_STYLE_KEY);
            if(!empty($result)){ return $result; }
			$sql = "SELECT feature_id,feature_name  FROM `FEATURE_MASTER` WHERE `feature_group` = 18 ORDER BY `feature_display_order` ASC";
			$result = $this->select($sql);
			$cnt = sizeof($result);
    		for($i=0;$i<$cnt;$i++){
        		$feature_name = $result[$i]['feature_name'];
                $clean_feature_name = cleanStr($feature_name);
        		$clean_feature_name = strtolower(html_entity_decode($clean_feature_name,ENT_QUOTES,'UTF-8'));
                $styleArr[$result[$i]['feature_id']] = $clean_feature_name;
        		$validStyleArr[$result[$i]['feature_id']] = strtolower(constructRouterUrl($feature_name));
	        }
			unset($result);
			$result = array('style' => $styleArr,'stylevalid'=>$validStyleArr);
			$this->setRouterCache(GET_BODY_STYLE_KEY,$result);
            return $result;
		}

		function getModel(){
			$category_id = $_REQUEST['category_id'];
			$brand_id = $this->requestArr['router_brand_id'];

			$key = !empty($category_id) ? GET_MODEL_KEY.$category_id : GET_MODEL_KEY;
			$key = !empty($brand_id) ? $key.$brand_id : $key;
			$result = $this->getRouterCache($key);
            if(!empty($result)){ return $result; }
            if(!empty($category_id) && !empty($brand_id)){
				$sql = "select product_name_id as model_id,product_info_name as model_name,brand_id,seo_path from PRODUCT_NAME_INFO where category_id = $category_id and brand_id = $brand_id order by model_id asc";
			}else if(!empty($category_id)){
				$sql = "select product_name_id as model_id,product_info_name as model_name,brand_id,seo_path from PRODUCT_NAME_INFO where category_id = $category_id order by model_id asc";
			}if(!empty($brand_id)){
				$sql = "select product_name_id as model_id,product_info_name as model_name,brand_id,seo_path from PRODUCT_NAME_INFO where brand_id = $brand_id order by model_id asc";
			}else{
				$sql = "select product_name_id as model_id,product_info_name as model_name,brand_id,seo_path from PRODUCT_NAME_INFO order by model_id asc";
			}
			$result = $this->select($sql);
			$cnt = sizeof($result);
            for($i=0;$i<$cnt;$i++){
                $model_name = $result[$i]['model_name'];
                $seo_path = $result[$i]['seo_path'];
                $modelArr[$result[$i]['brand_id']][$result[$i]['model_id']] = constructRouterUrl($seo_path);
                $validModelArr[$result[$i]['brand_id']][$result[$i]['model_id']] = constructRouterUrl($seo_path);
        	}
            unset($result);
            $result = array('model' => $modelArr,'validmodel'=>$validModelArr);
			$this->setRouterCache($key,$result);
            return $result;

		}
		function getVariant($type=""){
			$category_id = $_REQUEST['category_id'];
			$brand_id = $this->requestArr['router_brand_id'];
			$model_id = $this->requestArr['router_model_id'];

			$key = !empty($category_id) ? GET_VARIANT_KEY.$category_id : GET_VARIANT_KEY;
			$key = !empty($brand_id) ? $key.$brand_id : $key;
			$key = !empty($model_id) ? $key.$model_id : $key;
			$result = $this->getRouterCache($key);
            if(!empty($result)){ return $result; }
            if(!empty($category_id) && !empty($brand_id) && !empty($model_id)){
            	$sql = "select PM.brand_id,product_id,variant,product_name_id as model_id,PM.discontinue_flag,PM.discontinue_date,PM.arrival_date,PM.seo_path as seo_path,PI.seo_path as model_seo_path from PRODUCT_MASTER as PM,PRODUCT_NAME_INFO as PI where PM.product_name = PI.product_info_name and PM.status=1 and PM.brand_id = $brand_id and PM.category_id = $category_id and PI.product_name_id = $model_id order by product_id asc";
            }else{
            	$sql = "select PM.brand_id,product_id,variant,product_name_id as model_id,PM.discontinue_flag,PM.discontinue_date,PM.arrival_date,PM.seo_path as seo_path,PI.seo_path as model_seo_path from PRODUCT_MASTER as PM,PRODUCT_NAME_INFO as PI where PM.product_name = PI.product_info_name and PM.status=1 order by product_id asc";
            }

			$result = $this->select($sql);

			$cnt = sizeof($result);
			for($i=0;$i<$cnt;$i++){
				$seo_path = $result[$i]['seo_path'];
				$seo_path = strtolower(html_entity_decode($seo_path,ENT_QUOTES,'UTF-8'));
				unset($nameArr);unset($cleannameArr);
				
				if(!empty($seo_path)){
					$nameArr[] = $seo_path;
				}				
				$cleannameArr = $nameArr;
				if($result[$i]['discontinue_flag'] == '0'){
					$year =  buildYear($result[$i]['arrival_date'],$result[$i]['discontinue_date']);
					if(!empty($year)){
						$nameArr[] = $year;
						$cleannameArr[] = cleanStr($year);
					}
				}

				$variantArr[$result[$i]['brand_id']][$result[$i]['model_id']][$result[$i]['product_id']] = constructRouterUrl(implode('',$cleannameArr));
				$validVariantArr[$result[$i]['brand_id']][$result[$i]['model_id']][$result[$i]['product_id']] = constructRouterUrl(implode('_',$nameArr));
				unset($nameArr);unset($cleannameArr);
			}
            unset($result);
           	$result = array('variant' => $variantArr,'validvariant'=>$validVariantArr);
			$this->setRouterCache($key,$result);
            return $result;
		}
		function getCompareVariant(){
			$key = GET_COMPARE_VARIANT.'_'.$_REQUEST['category_id'];
			$result = $this->getRouterCache($key);
            if(!empty($result)){ return $result; }
			#$sql = "select PM.brand_id,product_id,variant,product_name_id as model_id,product_info_name as model_name from PRODUCT_MASTER as PM,PRODUCT_NAME_INFO as PI where PM.product_name = PI.product_info_name and PM.discontinue_flag = 1 and PM.status = 1 and PI.status = 1 and PI.discontinue_flag = 1 order by product_id asc";
			$sql = "select PM.discontinue_flag,PM.arrival_date,PM.discontinue_date,PM.brand_id,product_id,variant,product_name_id as model_id,product_info_name as model_name,PI.seo_path as model_seo_path,PM.seo_path as seo_path from PRODUCT_MASTER as PM,PRODUCT_NAME_INFO as PI where PM.product_name = PI.product_info_name and PM.status = 1 and PI.status = 1 order by product_id asc";
			$result = $this->select($sql);
            $cnt = sizeof($result);
            for($i=0;$i<$cnt;$i++){
				unset($year);unset($cleanyear);unset($arrival_date);unset($discontinue_date);unset($discontinue_flag);
				$brand_id = $result[$i]['brand_id'];
				$product_id = $result[$i]['product_id'];
				$variant = $result[$i]['variant'];
				$model_id = $result[$i]['model_id'];
				$model_name = $result[$i]['model_name'];
				$model_seo_path = $result[$i]['model_seo_path'];
				$seo_path = $result[$i]['seo_path'];
				$discontinue_flag = $result[$i]['discontinue_flag'];
				$year = "";
				if(empty($discontinue_flag)){
					$arrival_date = $result[$i]['arrival_date'];
					$discontinue_date = $result[$i]['discontinue_date'];
					$year = buildYear($arrival_date,$discontinue_date);
				}
				if(empty($brand_id)) continue;
				unset($res);unset($sql);
				$sql = "select brand_name,seo_path from BRAND_MASTER where brand_id = $brand_id order by brand_id asc limit 1";
				$res = $this->select($sql);
				$brand_name = $res[0]['brand_name'];
				$brand_seo_path = $res[0]['seo_path'];
				$clean_brand_name = cleanStr(strtolower($brand_name));
				$cleanProductArr = Array();
				if(!empty($clean_brand_name)){
					$cleanProductArr[] = $clean_brand_name;
				}
				$clean_model_name = cleanStr(strtolower($model_name));
				if(!empty($clean_model_name)){
					$cleanProductArr[] = $clean_model_name;
				}				
				$clean_variant = cleanStr(strtolower($variant));
				if(!empty($clean_variant)){
					$cleanProductArr[] = $clean_variant;
				}
				$cleanyear = cleanStr(strtolower($year));
				if(!empty($cleanyear)){
					$cleanProductArr[] = $cleanyear;
				}				
				$cleanProductArr = array_filter($cleanProductArr);
				$cleanProductArr = array_unique($cleanProductArr);
				$variantArr[$result[$i]['product_id']] = cleanStr(implode('',$cleanProductArr));
				unset($cleanProductArr);
				if(!empty($result[$i]))
				$productArr = Array($brand_seo_path,$model_seo_path,$seo_path);
				if(!empty($year)){
                    array_push($productArr,$year);
                }
                $productArr = array_filter($productArr);
                $productArr = array_unique($productArr);
				$product_name = implode(' ',$productArr);
				unset($productArr);
				$validVariantArr[$result[$i]['product_id']] = strtolower(constructRouterUrl($product_name));
				unset($product_name);
			}
			$result = array('compare'=>$variantArr,'validcompare'=>$validVariantArr);
			$this->setRouterCache(GET_COMPARE_VARIANT,$result);
            return $result;
		}

		function getYear(){
			$result = $this->getRouterCache(GET_DISCONTINUE_YEAR_KEY);
            if(!empty($result)){ return $result; }

			$sql = "select product_id,discontinue_date,arrival_date from PRODUCT_MASTER where discontinue_flag = 0 and status = 1";
			$result = $this->select($sql);
			$cnt = sizeof($result);
			for($i=0;$i<$cnt;$i++){
				$product_id = $result[$i]['product_id'];
				$discontinue_date = $result[$i]['discontinue_date'];
				if($discontinue_date == '0000-00-00 00:00:00') continue;
				$arrival_date = $result[$i]['arrival_date'];
				if($arrival_date == '0000-00-00') continue;
				unset($yearArr);
				if(!empty($arrival_date)){
					$yearArr[] = date('Y',strtotime($arrival_date));
				}
				if(!empty($discontinue_date)){
                    $yearArr[] = date('Y',strtotime($discontinue_date));
                }
				$yearcnt = sizeof($yearArr);
				if(!empty($product_id) && !empty($yearcnt)){
					$newyear = implode('-',$yearArr);
					if(!in_array($newyear,$getProductYearArr[$product_id])){
						$getProductYearArr[$product_id] = constructRouterUrl(implode('-',$yearArr));
					}
					if(!in_array($newyear,$getYear)){
						$getYear[] = constructRouterUrl(implode('-',$yearArr));
					}

				}
				unset($yearArr);
			}
			unset($result);
			$result = array('productYear'=>$getProductYearArr,'year'=>$getYear);
			$this->setRouterCache(GET_DISCONTINUE_YEAR_KEY,$result);
            return $result;
		}
		function getVideoSlug(){
			$category_id = $_REQUEST['category_id'];
			$model_id = $this->requestArr['router_model_id'];
			$brand_id = $this->requestArr['router_brand_id'];
			if(empty($model_id)){ return false; }
			$key = GET_VIDEO_TITLE_KEY.'_'.$category_id.'_'.$model_id.'_'.$brand_id;
			$result = $this->getRouterCache($key);
            if(!empty($result)){ return $result; }            
            $sql = "select V.slug,V.video_id,PV.brand_id,PV.product_info_id as model_id,PV.product_id from VIDEO_GALLERY V, PRODUCT_VIDEOS PV where V.status=1 and PV.product_info_id in($model_id) and PV.category_id in ($category_id) and PV.brand_id in ($brand_id) and PV.video_id=V.video_id and V.content_type=1 and PV.category_id = 1 order by V.video_id asc";
            $result = $this->select($sql);
			$cnt = sizeof($result);
			for($i=0;$i<$cnt;$i++){
				$slug = constructRouterUrl($result[$i]['slug']);
				$video_id = !empty($result[$i]['video_id']) ? $result[$i]['video_id'] : 0;
				$brand_id = !empty($result[$i]['brand_id']) ? $result[$i]['brand_id'] : 0;
				$model_id = !empty($result[$i]['model_id']) ? $result[$i]['model_id'] : 0;
				$product_id = !empty($result[$i]['product_id']) ? $result[$i]['product_id'] : 0;
				$videoArr[$category_id][$brand_id][$model_id][$video_id] = $slug;
				$validVideoArr[$category_id][$brand_id][$model_id][$video_id] = $slug;
			}
			$result = array('media'=>$videoArr,'validmedia'=>$validVideoArr);
			$this->setRouterCache($key,$result);
            return $result;
		}
		function getSlideshowSlug(){
			$category_id = $_REQUEST['category_id'];
			$model_id = $this->requestArr['router_model_id'];
			$brand_id = $this->requestArr['router_brand_id'];
			if(empty($model_id)){ return false; }
			$key = GET_SLIDESHOW_TITLE_KEY.'_'.$category_id.'_'.$model_id;
			$result = $this->getRouterCache($key);
            if(!empty($result)){ return $result; }                        
            $sql = "select S.slug as slug,S.slideshow_id as slideshow_id,PS.category_id,PS.brand_id,PS.product_info_id as model_id,PS.product_id from SLIDESHOW S , PRODUCT_SLIDES PS,PRODUCT_NAME_INFO PI where PI.product_name_id = PS.product_info_id and PI.upcoming_flag = 0 and PI.discontinue_flag = 1 and S.status=1 and PS.status=1 and S.product_slide_id=PS.product_slide_id and PS.brand_id in ($brand_id) and PS.product_info_id in ($model_id) and S.video_img_path !='' and PS.category_id = $category_id order by S.slideshow_id asc";
            $result = $this->select($sql);
			$cnt = sizeof($result);
			for($i=0;$i<$cnt;$i++){
				$slug = constructRouterUrl($result[$i]['slug']);
				$slideshow_id = !empty($result[$i]['slideshow_id']) ? $result[$i]['slideshow_id'] : 0;
				$brand_id = !empty($result[$i]['brand_id']) ? $result[$i]['brand_id'] : 0;
				$model_id = !empty($result[$i]['model_id']) ? $result[$i]['model_id'] : 0;
				$product_id = !empty($result[$i]['product_id']) ? $result[$i]['product_id'] : 0;
				$photoArr[$category_id][$brand_id][$model_id][$slideshow_id] = $slug;
				$validPhotoArr[$category_id][$brand_id][$model_id][$slideshow_id] = $slug;
			}
			$result = array('media'=>$photoArr,'validmedia'=>$validPhotoArr);
			$this->setRouterCache($key,$result);
            return $result;
		}
		function setRouterCache($key,$result){
			global $cache;
			$result = serialize($result);
	        $cache->set($key, $result);
			return $result;
    	}
    	function getRouterCache($key){
			global $cache;
			$result = $cache->get($key);
			$result = unserialize($result);
			return $result;
    	}
	}
