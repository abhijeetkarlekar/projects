<?php
ini_set("display_errors","1" );
require_once('../../include/config.php');
require_once(CLASSPATH.'PHPExcel.php');

require_once(CLASSPATH.'top_selling_car.class.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'brand.class.php');
require_once(CLASSPATH.'product.class.php');
require_once(CLASSPATH.'feature.class.php');

$dbconn         = new DbConn;
$objPHPExcel    = new PHPExcel();
$top_sell       = new TopSellingCar;
$oBrand         = new BrandManagement;
$oProduct       = new ProductManagement;
$oFeatureFuel   = new FeatureManagement;

$feature_result=$oFeatureFuel->arrGetFeatureDetails($feature_ids, $category_id = "1", $main_group_id = "3", $sub_group_id = "19", $status = "1", $startlimit = "", $count = "", $feature_name);
unset($fuelData);
foreach ($feature_result as $frKey=>$frData){
    $fuelData[$frData['feature_id']]=$frData['feature_name'];
}

$brand_result=$oBrand->arrGetBrandDetails();
unset($brandData);
foreach ($brand_result as $brKey=>$brData){
    $brandData[$brData['brand_id']]=$brData['brand_name'];
}

$product_result=$oProduct->arrGetProductNameInfo("","1","","","1","","","order by brand_id","","1");//print"<pre>";print_r($product_result);exit;
unset($final_Data);
$j=0;
foreach ($product_result as $mrKey=>$mrData){
    $brand_name=$brandData[$mrData['brand_id']];
    if(!empty($brand_name)){
        $brand_id=$mrData['brand_id'];
        $product_name=$mrData['product_info_name'];
        unset($product_id);
        $product_result=$oProduct->arrGetProductDetails("",$category_id="1",$brand_id,$status='1',"","",$variant_id="1","","",$default_city="1",$orderby="",$product_name,"","",'',"");
        foreach($product_result as $prKey=>$prData){
            $product_id[]=$prData['product_id'];
        }

        $k=0;
        foreach($fuelData as $fdKey=>$fdData){
            if($k==0){
                $final_Data[$j]['brand_name']=$brandData[$brand_id];
                $final_Data[$j]['model_name']=$product_name;
                $final_Data[$j]['fuel_type']="All";
                $j++;
            }
            $fuel_result=$oProduct->arrGetProductFeatureDetails("",$fdKey,$product_id,"","");
            if(!empty ($fuel_result)){
                $final_Data[$j]['brand_name']=$brandData[$brand_id];
                $final_Data[$j]['model_name']=$product_name;
                $final_Data[$j]['fuel_type']=$fdData;
                $j++;
            }
            $k++;
        }
        
    }
    
}

$first_day   = date('01-m-Y');
$last_day    = date('t-m-Y');


$objPHPExcel->getProperties()->setCreator("oncars.in")
							 ->setLastModifiedBy("Oncars team")
							 ->setTitle("top selling car")
							 ->setSubject("top selling car list")
							 ->setDescription("show top selling car to user.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("oncars");

	$objPHPExcel->getActiveSheet()->setCellValue('A1', "Sr.No");
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Brand Name");
	$objPHPExcel->getActiveSheet()->setCellValue('C1', "Model Name");
	$objPHPExcel->getActiveSheet()->setCellValue('D1', "Fuel Type");
	$objPHPExcel->getActiveSheet()->setCellValue('E1', "Start Date");
	$objPHPExcel->getActiveSheet()->setCellValue('F1', "End Date");
	$objPHPExcel->getActiveSheet()->setCellValue('G1', "Count");
        
        $i=2;
        foreach($final_Data as $fKey=>$fData){
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $i-1);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $fData['brand_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $fData['model_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $fData['fuel_type']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $first_day);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $last_day);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, "");
            $i++;
        }
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
header('Content-Disposition: attachment; filename="top_selling_car_'.date('YmdHis'). '.xls";');
header('Content-Transfer-Encoding: binary');
ob_clean();
flush();
$objWriter->save('php://output');

?>
