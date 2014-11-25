<?php
//ini_set("display_errors","1" );
ini_set("memory_limit","600M");
ini_set('max_execution_time', 120); //300 seconds = 5 minutes
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'PHPExcel/IOFactory.php');
//require_once(CLASSPATH.'top_selling_car.class.php');
require_once(CLASSPATH.'category.class.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'price.class.php');


$oCategory      = new CategoryManagement;
$oBrand         = new BrandManagement;
$oProduct       = new ProductManagement;
$oFeatureFuel   = new FeatureManagement;
$oPrice         = new price;
$dbconn = new DbConn;
//print_r($_FILES); die();
$category_id = $_REQUEST['selected_category_id'];
if($_POST && !empty($_FILES["xls_file"]["name"])){

    $allowedExts = array("xls");
    $extension = end(explode(".", $_FILES["xls_file"]["name"]));
    if(in_array($extension, $allowedExts)){
        define("UPLOAD_ADMIN_PATH","/var/www/projects/gadgets_oncars/admin/upload_data/");
        $filePath =UPLOAD_ADMIN_PATH."product_features/product_features_".date('YmdHis').".xls";
        if(!move_uploaded_file($_FILES["xls_file"]["tmp_name"],$filePath)){
            error_log(" after Action: product data .xls file not moved"); 
        }
	/*define("UPLOAD_ADMIN_PATH","/var/www/projects/oncars_v2/admin/upload_data/");
        $filePath =UPLOAD_ADMIN_PATH."top_selling_car/top_selling_car_".date('YmdHis').".xls";
        if(!move_uploaded_file($_FILES["xls_file"]["tmp_name"],$filePath)){
            error_log(" after Action: top_selling_car data .xls file not moved");
        }*/

    }else{
        error_log(" after Action: product uploaded file is not .xls"); 
    
}
    if(file_exists($filePath)){

       $inputFileName=$filePath;
	#echo $filePath; die();	
        try{
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objReader->setReadDataOnly(true);	
            $objPHPExcel = $objReader->load($inputFileName);

        }catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn  = $sheet->getHighestColumn(); // e.g 'F'
                

        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++){ 
        //  Read a row of data into an array
            $rowData[$row] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
        }

        //print"<pre>"; print_r($rowData); exit;
        $first_day   = date('01-m-Y');
        $last_day    = date('t-m-Y');
        $rowCnt=count($rowData);
	//echo $rowCnt;exit;
//	print_r($rowData); die();
        if(!empty($error_arr)){
            header('Pragma: public');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Cache-Control: private', false); // required for certain browsers
            header('Content-Type: application/txt');
            header('Content-Disposition: attachment; filename="top_selling_car_'.date('YmdHis'). '.txt";');
            header('Content-Transfer-Encoding: binary');
            ob_clean();
            flush();
            file_put_contents("php://output",$error_arr);exit;
        }else{
            //echo"No error found";
            $rcnt=count($rowData);
            $features = $oFeatureFuel->arrGetFeatureAndSubGroupDetails();
            $pivotfeatures = $oFeatureFuel->arrGetPivotDetails("","","1");
            $featurecnt = sizeof($features);
            $pivotfeaturecnt = sizeof($pivotfeatures);
            $featurecount = (int)$featurecnt - (int)$pivotfeaturecnt;
            for($i=5;$i<=$rcnt;$i++){
                $category_name=trim($rowData[$i][0][1]);
                //echo "<br>CATEGORYNAME---".$category_name."<br>";
                $categories = $oCategory->arrGetCategoryDetails("","","","","",$category_name);
                $category_id = $categories[0]['category_id'];
                $brand_name=trim($rowData[$i][0][2]);
		        //echo "<br>BRANDNAME---".$brand_name."<br>";
                $brandres = $oBrand->arrGetBrandDetails("",$category_id,"","","",$brand_name);
                if(sizeof($brandres) <= 0){
                   // echo "insert brand<br>";
                    unset($insert_param);
                    $insert_param['brand_name'] = htmlentities($brand_name,ENT_QUOTES,'UTF-8');
                    $insert_param['category_id'] = htmlentities($category_id,ENT_QUOTES,'UTF-8');
                    $insert_param['seo_path'] = htmlentities(strtolower(str_replace(" ", "-", trim($brand_name))),ENT_QUOTES,'UTF-8');
                    $brand_id = $oBrand->intInsertBrand($insert_param);
                }else{ $brand_id = $brandres[0]['brand_id']; }
                $model_name=trim($rowData[$i][0][3]);
                //echo "<br>MODELNAME---".$model_name."<br>";
                $description =trim($rowData[$i][0][5]);
                $product_res = $oProduct->arrGetProductNameInfo("",$category_id,$brand_id,$model_name);
                unset($insert_param);
                if(sizeof($product_res) <=0){
                    //echo "insert model<br>";
                    $insert_param['brand_id'] = htmlentities($brand_id,ENT_QUOTES,'UTF-8');
                    $insert_param['product_info_name'] = htmlentities($model_name,ENT_QUOTES,'UTF-8');
                    $insert_param['product_name_desc'] = htmlentities($description,ENT_QUOTES,'UTF-8');
                    $insert_param['seo_path'] = htmlentities(strtolower(str_replace(" ", "-", trim($model_name))),ENT_QUOTES,'UTF-8');
                    $insert_param['category_id'] = htmlentities($category_id,ENT_QUOTES,'UTF-8');
                    $product_name_id = $oProduct->addUpdProductInfoDetails($insert_param);
                }
                $variant_name=trim($rowData[$i][0][4]);
                //echo "<br>VARIANTNAME---".$variant_name."<br>";
                //$model_product_result = $oProduct->arrGetProductDetails("","","",'1',"","","1","","","1","",$model_name,"","",'',"",'0',$variant_name);
                $model_product_result = $oProduct->arrGetProductDetails("",$category_id,$brand_id,'',"","","","","","","",$model_name,"","",'',"",'',$variant_name);
		        if(sizeof($model_product_result) <= 0){
                   // echo "insert product<br>";
                    $insert_param['brand_id'] = htmlentities($brand_id,ENT_QUOTES,'UTF-8');
                    $insert_param['product_name'] = htmlentities($model_name,ENT_QUOTES,'UTF-8');
                    $insert_param['variant'] = htmlentities($variant_name,ENT_QUOTES,'UTF-8');
                    $insert_param['seo_path'] = htmlentities(strtolower(str_replace(" ", "-", trim($variant_name))),ENT_QUOTES,'UTF-8');
                    $insert_param['category_id'] = htmlentities($category_id,ENT_QUOTES,'UTF-8');
                    $product_id = $oProduct->intInsertProduct($insert_param);
                }
                $price=trim($rowData[$i][0][6]);
                if($price !=''){
                    //echo "insert product price<br>";
                    $insert_param['product_id'] = htmlentities($product_id,ENT_QUOTES,'UTF-8');
                    $insert_param['brand_id'] = htmlentities($brand_id,ENT_QUOTES,'UTF-8');
                    $insert_param['category_id'] = htmlentities($category_id,ENT_QUOTES,'UTF-8');
                    $insert_param['product_id'] = htmlentities($product_id,ENT_QUOTES,'UTF-8');
                    $insert_param['variant_value'] = htmlentities($price,ENT_QUOTES,'UTF-8');
                    $insert_param['variant_id'] = 1;
                    $price_id = $oPrice->intInsertVariantValueDetail($insert_param);
                }
                
                if(!empty($model_name)){
                    $model_product_result = $oProduct->arrGetProductDetails("",$category_id,$brand_id,'',"","","","","","","",$model_name,"","",'',"",'',$variant_name);
                   // $model_product_result = $oProduct->arrGetProductDetails("","","",'1',"","","1","","","1","",$model_name,"","",'',"",'0',$variant_name);
                    //echo "COUNT--".$featurecount."<br>";
                    foreach ($model_product_result as $mpKey=>$mpData){
			         $query_str='';
                        $product_id = $mpData['product_id'];
                        if(!empty($product_id)){
                            //echo "DDDDDDDDDDDDD";
            			    for($ii=7;$ii<=$featurecount;$ii++){
                				$feature_id = trim($rowData[3][0][$ii]);
                				$feature_value= trim($rowData[$i][0][$ii]);
                				//echo "FEATUREID----".$model_name."-----".$variant_name."-----".$feature_id."===VALLUE===".$feature_value."<br>";
                				$feature_param = array();
                				if(!empty($feature_value)){
                                       //insert product feature.
                                      $feature_param['feature_value'] = $feature_value;
                                      $feature_param['feature_id'] = $feature_id;
                                      $feature_param['product_id'] = $product_id;
                				      $query_str .= "('".$feature_value."','".$feature_id."','".$product_id."',now())";
                				      //if($ii<81){ $query_str .=",";}		
                                      $product_feature_id = $oProduct->intInsertProductFeature($feature_param);
                					  //echo "RESULT---".$product_feature_id."<br>";
                					  unset($feature_param);
                                 }
            			    }
            			     /*if($query_str!=''){	
                			    $sql = "INSERT INTO  PRODUCT_FEATURE (feature_value,feature_id,product_id,create_date) VALUES  $query_str";	
                			    echo $sql."<br>";	
            			        $product_feature_ids = $oProduct->intInsertProductFeatureData($sql);
                                echo "RESULT---".$product_feature_ids."<br>";
            			    }*/
	                    }
                    }
                }
            }
	   }
    }else{
        error_log(" after Action: data uploaded file doesn't exists ".$filePath);
    }
}
//die();
$config_details = get_config_details();
$strXML = "<XML>";
$strXML .= "<MSG><![CDATA[$msg]]></MSG>";
$strXML .= "<SELECTED_CATEGORY_ID><![CDATA[$category_id]]></SELECTED_CATEGORY_ID>";
$strXML .= "<STARTLIMIT><![CDATA[$startlimit]]></STARTLIMIT>";
$strXML .= "<SELECTED_ACTION_TYPE><![CDATA[$actiontype]]></SELECTED_ACTION_TYPE>";
$strXML .= "<CNT><![CDATA[$limitcnt]]></CNT>";
$strXML .= $config_details;
$strXML .= $xml;
$strXML .= "</XML>";

$strXML = mb_convert_encoding($strXML, "UTF-8");
if($_GET['debug']==1) { header('Content-type: text/xml');echo $strXML;exit; }
//header('Content-type: text/xml');echo $strXML;exit;

$doc = new DOMDocument();
$doc->loadXML($strXML);
$doc->saveXML();

$xslt = new xsltProcessor;
$xsl = DOMDocument::load('xsl/product_features_upload.xsl');

$xslt->importStylesheet($xsl);
print $xslt->transformToXML($doc);
?>