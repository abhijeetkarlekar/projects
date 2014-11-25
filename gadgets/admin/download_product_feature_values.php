<?php
//error_reporting(1);
ini_set("display_errors","0");
ini_set("memory_limit","600M");
ini_set('max_execution_time', 180);
require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'price.class.php');
require_once(CLASSPATH.'feature.class.php');
require_once(CLASSPATH.'PHPExcel.php');
$dbconn = new DbConn;
$brand = new BrandManagement;
$product = new ProductManagement;

$price = new price;
$feature = new FeatureManagement;
$objPHPExcel= new PHPExcel();


$category_id = $_REQUEST['selected_category_id'] ? $_REQUEST['selected_category_id'] : SITE_CATEGORY_ID;
$objPHPExcel->getProperties()->setCreator("gadgets.in")
							 ->setLastModifiedBy("gadgets team")
							 ->setTitle("product Values")
							 ->setSubject("product Values")
							 ->setDescription("Show product.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("product");



$objPHPExcel->getActiveSheet()->setCellValue('A1', "feature_group");
$objPHPExcel->getActiveSheet()->setCellValue('A2', "feature_subgroup");
$objPHPExcel->getActiveSheet()->setCellValue('A3', "feature_id");
$objPHPExcel->getActiveSheet()->setCellValue('A4', "category");
$objPHPExcel->getActiveSheet()->setCellValue('B4', "subcategory");
$objPHPExcel->getActiveSheet()->setCellValue('C4', "brand");
$objPHPExcel->getActiveSheet()->setCellValue('D4', "model");
$objPHPExcel->getActiveSheet()->setCellValue('E4', "variant");
$objPHPExcel->getActiveSheet()->setCellValue('F4', "description");
$objPHPExcel->getActiveSheet()->setCellValue('G4', "price");
$alphabets = array();
$letters = range('A', 'Z');
$counter = 0; $k=-1;
for($k=-1;$k<26;$k++){
        for($ii=0;$ii<26;$ii++){
                if($letters[$k]!=''){
                        $alphabets[] = $letters[$k].$letters[$ii];
                        #echo "<pre>"; print_r($alphabets);
                }else{
                        $alphabets[] = $letters[$k].$letters[$ii];
                        #echo "<pre>"; print_r($alphabets);
                }
                $counter++;
                if($counter==$cnt){ break; }
        }
                if($counter==$cnt){ break; }
}
$features = $feature->arrGetFeatureAndSubGroupDetails();
$pivotfeatures = $feature->arrGetPivotDetails("","","1");

/*print_r($features);
echo "<br>======================<br>";
print_r($pivotfeatures);
die();*/


$pivotfeaturecnt = sizeof($pivotfeatures);
for ($yi=0; $yi < $pivotfeaturecnt; $yi++) { 
    $pivotfeaturedata[$pivotfeatures[$yi]['feature_id']]  = 1;
}
//echo count($pivotfeaturedata); //die();
//print_r($pivotfeatures); //die();
$featurecnt = sizeof($features);
$featurecount = (int)$featurecnt - (int)$pivotfeaturecnt;
//echo $featurecount; die();
//$featurecount = 77;
$fk =0;

for ($i=0; $i < $featurecount; $i++) { 
    $fk = $i+7;
    $feature_subgroup = $features[$i]['sub_group_name'];
    $feature_id = $features[$i]['feature_id'];
    $feature_name = $features[$i]['feature_name'];
    
    if(is_array($pivotfeaturedata) && isset($pivotfeaturedata[$feature_id])){
            $feature_group = "Pivot Search Parameter";
    }else{
            $feature_group = "Technical Specification";
    }
    //echo "FES---".$feature_id ."==". $pivotfeatures[$ik]['feature_id']."-----------".$feature_group."<br>";
    $objPHPExcel->getActiveSheet()->setCellValue($alphabets[$fk]."1", $feature_group);
    $objPHPExcel->getActiveSheet()->setCellValue($alphabets[$fk]."2", $feature_subgroup);
    $objPHPExcel->getActiveSheet()->setCellValue($alphabets[$fk]."3", $feature_id);
    $objPHPExcel->getActiveSheet()->setCellValue($alphabets[$fk]."4", $feature_name);
}
//die();
$result = $product->arrGetProductDetails("",$category_id,"","1","","","1","","","1",'','','','','',1);
$countres = sizeof($result);

$fk = 0; $jk =0;
for($j=0;$j<$countres;$j++){
    $jk = $j+5; 
    $product_id = $result[$j]['product_id'];
    $product_name= $result[$j]['product_name'];
    $variant = $result[$j]['variant'];
    $description = $result[$j]['description'];
    $variant_value = $result[$j]['variant_value'];
    $product_id = $result[$j]['product_id'];
    $brand_id = $result[$j]['brand_id'];
    $brand_id = $result[$j]['brand_id'];
    if(!empty($brand_id)){
        $brand_result = $brand->arrGetBrandDetails($brand_id);
        $brandname = $brand_result[0]['brand_name'];
    }
    $category = "gadgets";
    $subcategory = "mobiles";
    
    $objPHPExcel->getActiveSheet()->setCellValue("A".$jk, $category);
    $objPHPExcel->getActiveSheet()->setCellValue("B".$jk, $subcategory);
    $objPHPExcel->getActiveSheet()->setCellValue("C".$jk, $brandname);
    $objPHPExcel->getActiveSheet()->setCellValue("D".$jk, $product_name);
    $objPHPExcel->getActiveSheet()->setCellValue("E".$jk, $variant);
    $objPHPExcel->getActiveSheet()->setCellValue("F".$jk, $description);
    $objPHPExcel->getActiveSheet()->setCellValue("G".$jk, $variant_value);

    $productfeatures = $feature->arrGetProductFeatureDataDetails($product_id,1,"","","");
    $productfeaturescnt  = sizeof($productfeatures);
    for ($yi=0; $yi < $productfeaturescnt; $yi++) { 
         $productfeaturescntdata[$product_id][$productfeatures[$yi]['feature_id']]  = $productfeatures[$yi];
    } 
    //print "<pre>"; print_r($productfeaturescntdata[$product_id]); //die();
    $feature_value = "";
    for ($i=0;$i<$featurecount;$i++) { 
        $fk = $i+7; 
        //$feature_id =  $productfeatures[$i]['feature_id'];
        $feature_id = $features[$i]['feature_id'];
        $feature_name = $features[$i]['feature_name'];
        if(isset($productfeaturescntdata[$product_id][$feature_id])){
            $feature_value = $productfeaturescntdata[$product_id][$feature_id]['feature_value'];
        }else{$feature_value = "";}
        //echo $product_id."====product_name".$product_name."-----------".$feature_id."==yyyyyy".$feature_name."uuuuuuuuuuuuuuuu==".$feature_value."<br>";
        $objPHPExcel->getActiveSheet()->setCellValue($alphabets[$fk].$jk, $feature_value);
    }  

}
//die();
//print_r($product_name); die();



        
       

        // Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('list 1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

//echo date('H:i:s') , " Write to Excel5 format";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers
header('Content-Type: application/ms-excel');
header('Content-Disposition: attachment; filename="product_value_'.date('YmdHis'). '.xls";');
header('Content-Transfer-Encoding: binary');
ob_clean();
flush();
$objWriter->save('php://output');        
?>
