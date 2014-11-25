<?php

/*$array  = array('microsoft','micromax', 'miniclip','michael jackson','million','milky way');
$input  = urldecode($_GET['word']); //Get input word/phrase (decode in case of spaces etc.)
$length = strlen($input);           //Get length of input word
// $min    = $length - 1;              //Length of word - 1
// $max    = $length + 1;              //Length of word + 1

$returned = preg_grep('/^(['.$input.']{'.$length.'}.*)$/i', $array); //Find matches in $array and return as array
$returned = array_values($returned);                                //Re-index from 0

echo json_encode($returned); //Returm json string to ajax call*/

require_once('../include/config.php');
require_once(CLASSPATH.'DbConn.php');
require_once(CLASSPATH.'product.class.php');
$dbconn = new DbConn;
$oProduct = new ProductManagement;



$k=0;
$search_keyword  = urldecode($_GET['word']); //Get input word/phrase (decode in case of spaces etc.)
if(!empty($search_keyword)){
	$result = $oProduct->arrGetAutoProductDetails($search_keyword);
	foreach($result as $ikey=>$aValue){
		//$results[] = "<a href='".WEB_URL.$aValue['permalink']."'>".$aValue['search']."</a>";
		//$returned[] = "<a href='".$aValue['permalink']."'>".$aValue['search']."</a>";
		$is_brand = !empty($aValue['is_brand']) ? 'brand' : "";
		$is_model = !empty($aValue['is_model'])  ? 'model' : "";
		$is_variant = !empty($aValue['is_variant']) ? 'variant' : "";
		unset($textArr);
		$textArr[] = $aValue['search'];
		if(!empty($is_brand)){
			$textArr[] = $is_brand;
		}
		if(!empty($is_model)){
			$textArr[] = $is_model;
		}
		if(!empty($is_variant)){
			$textArr[] = $is_variant;
		}
		$returned[$k]['title'] = implode("-", $textArr);
		$returned[$k]['link'] = WEB_URL.$aValue['permalink'] ;
		$k++;
	}

	//print "<pre>"; print_r($returned);
	//$returned = array_values($returned);                                //Re-index from 0
	echo json_encode($returned); //Returm json string to ajax call*/
}
?>