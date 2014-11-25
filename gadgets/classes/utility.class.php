<?php
/**
 * @brief class is used to perform actions on the utility
 * @author Sachin
 * @version 1.0
 * @created 11-Nov-2010 6:29:23 PM
 * @last updated on 09-Mar-2011 13:14:00 PM
*/
//require_once("./include/config.php");
/**
 * include database connection class
*/
//require_once(CLASSPATH.'dbconn.php');
/**
 * include database operation class
*/
//require_once(CLASSPATH.'dbop.php');

class utility {
	function arraytoxml($arr,$node="main")
	{

		for($b=0;$b<count($arr);$b++){
			$arrData = $arr[$b];
			$nodes .="<".$node.">";
			if(is_array($arrData)){
				$keys = array_keys($arrData);
				$values = array_values($arrData);
				for($i=0;$i<sizeof($keys);$i++){

					if($keys[$i]=='abstract'){
						//echo $values[$i];
						$values[$i]=$this->getCompactString($values[$i],80)."...";
						$values[$i]=stripslashes($values[$i]);


					}
					if($keys[$i]=='title'){
						$sTitle=$this->getCompactString(stripslashes($values[$i]),30);
						$nodes.="<ShortTitle><![CDATA[".$sTitle."]]></ShortTitle>";
					}
					if($keys[$i]=='content'){
						$values[$i]=stripslashes($values[$i]);
					}
					$nodes.="<".$keys[$i]."><![CDATA[".$values[$i]."]]></".$keys[$i].">";
				}
			}
			$nodes .="</".$node.">";
		}
		return $nodes;
	}




	function sItemXmlCreation($arr,$node="main"){
		for($b=0;$b<count($arr);$b++){
			$arrData = $arr[$b];
			$nodes .="<".$node.">";
			if(is_array($arrData)){
				$keys = array_keys($arrData);
				$values = array_values($arrData);
				for($i=0;$i<sizeof($keys);$i++){
					if($keys[$i]=='publish_time'){
						$aDateFormat=explode('-',$values[$i]);
						$sDateTime=substr($aDateFormat[2],-8);
						$aDateTimeFormat=explode(':',$sDateTime);
						$sDispDate=date("d M Y H:i:s", mktime($aDateTimeFormat[0], $aDateTimeFormat[1], $aDateTimeFormat[2], $aDateFormat[1] , $aDateFormat[2], $aDateFormat[0]));
							$values[$i]=$sDispDate;
						}
					$nodes.="<".$keys[$i]."><![CDATA[".$values[$i]."]]></".$keys[$i].">";
				}
			}
			$nodes .="</".$node.">";
		}
		return $nodes;
	}


	function arrGetDateMonth(){
		  for($m=1;$m<=12;$m++){
			 $month   = date("m", mktime(0, 0, 0, $m, 1, 0));
			 $monthname = date("M",mktime(0, 0, 0, $m, 1, 0));
			 $result[$monthname] = $month;
		   }
			return $result;
	 }

    function magabytesConvert($mb){
		$kb= $mb*1024;
		return $byte = $kb*1024;
	}

    function UploadFileValidation($sType,$sFileSize,$sExt,$aExtension){
        if($sType=='image')
            $iSize=10;
        else
            $iSize=150;

        $sUploadSize =$this->magabytesConvert($iSize);
        if($sExt){
            if(!in_array($sExt,$aExtension)){
                $aMessage[]="uploaded file was not of the correct format";
            }
        }
        if($sFileSize >$sUploadSize){
              $aMessage[]="only 150 mb video size allowed for upload";
        }
        return $aMessage;
    }


	function typevalidation($uploadfiletype,$mediatype,$tmpfilename="")
	{
	if ($tmpfilename)
	{
	$sfiletype = shell_exec("mimetype -bi $tmpfilename");
	//echo "In".$sfiletype;
	$typearr=explode("/",$sfiletype);
	$filetype=$typearr[0];
	$fileext=$typearr[1];
	}

	if($uploadfiletype=='thumb')
	{
		if($filetype!='image')
		{
			$errmsg="errormessage1";
		}
		else{
		$errmsg="ok";
		}
	}
	else if($uploadfiletype=='media')
	{
		if($mediatype=="image")
		{
		if($filetype!='image')
		{
			$errmsg="errormessage2";
		}
		else
			{$errmsg="ok";
			}
		}
		else if($mediatype=="video")
		{
		if($filetype!='video')
		{
			$errmsg="errormessage3";
		}
		elseif(strpos($fileext,"flv")===false)
		{
		$errmsg="errormessage4";
		}
		else
			{$errmsg="ok";
			}
		}
	}
	return $errmsg;
	}

 /**
	 * nonxmlcharreplace()
     * Replace the invalid no-xml chars with space
	 *
	 * @param string $str  content string
     * @return string returns sring with all non xml characters replace with space
	 * @access public
	*/
	public function nonxmlcharreplace($str)
	{
		$str = str_replace("‘","'", $str);
		$str = str_replace("’","'", $str);
		$str = str_replace("“",'"', $str);
		$str = str_replace("”",'"', $str);
		$str=str_replace("–","-",$str);
		$str = str_replace("<![CDATA[","", $str);
		$str = str_replace("]]>","", $str);
		$aOutsideRangeAllowedChar=Array(162,163,165,166,169,173,174,177,183,180,187,247);
		 for($i=0;$i<strlen($str);$i++) {
			if(ord($str{$i}) >= 33 && ord($str{$i}) <= 126 ) {
					$newstr.=$str{$i};
			}else {
				if(in_array(ord($str{$i}),$aOutsideRangeAllowedChar) ){
					$newstr.=$str{$i};
				}else {
					$newstr.=" ";
				}
			}
		}
        return $newstr;
	}

	public static function curlaccess($post_string, $sHost) {

		if (function_exists ( 'curl_init' )) {
			// Use CURL if installed...
			$curl_resource = curl_init ();
            curl_setopt ( $curl_resource, CURLOPT_URL, $sHost );
			//curl_setopt($curl_resource, CURLOPT_SSL_VERIFYPEER, false);
			//curl_setopt($curl_resource, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt ( $curl_resource, CURLOPT_POST, 1 );
			curl_setopt ( $curl_resource, CURLOPT_POSTFIELDS, $post_string );
			curl_setopt ( $curl_resource, CURLOPT_RETURNTRANSFER, 1 );
			$output = curl_exec ( $curl_resource );
			/*
			if(!curl_errno($curl_resource)){
  				$info = curl_getinfo($curl_resource);
  				echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
			} else {
  				echo 'Curl error: ' . curl_error($curl_resource);
			}
			*/
		    	curl_close ( $curl_resource );
		} else {
			// Non-CURL based version...
			$context = array ('http' => array ('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded' . "\r\n" . 'User-Agent: API PHP5 Client 1.1 (non-curl) ' . phpversion () . "\r\n" . 'Content-length: ' . strlen ( $post_string ), 'content' => $post_string ) );

			$contextid = stream_context_create ( $context );
			$sock = fopen ($sHost, 'r', false, $contextid );
			if ($sock) {
				$output = '';
				while ( ! feof ( $sock ) )
				$output .= fgets ( $sock, 4096 );
				fclose ( $sock );
			}
		}

       return $output;
	}
	function getSelectedDropDownlising($aListing,$sListId){
		$aListingId = -1;
		if(!empty($sListId)){
			$aListingId = explode(',',$sListId);
		}
		if(is_array($aListing) && count($aListing)>0){
			foreach($aListing as $iLisingkey=>$sListingVal){
				if(in_array($iLisingkey,$aListingId)){
					$strOptions.="<option value='$iLisingkey' selected='selected'>".$sListingVal."</option>";
				}else{
					$strOptions.="<option value='$iLisingkey'>".$sListingVal."</option>";
				}
			}
		}
		return $strOptions;
	}
	function getImageDetails($iMediaId,$iServiceId,$action='api'){
		$sString = file_get_contents(IMAGE_READER_FILE."?service_id=$iServiceId&action=api&media_id=$iMediaId");
		/*header('content-type:text/xml');
		echo IMAGE_READER_FILE."?service_id=$iServiceId&action=api&media_id=$iMediaId";die;*/
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->loadXML($sString);
		$MainImg = $doc->getElementsByTagName('IMG_PATH')->item(0)->nodeValue;
		$ThumbImg = $doc->getElementsByTagName('IMG_PATH')->item(1)->nodeValue;
		$aImage = array('main_image'=>$MainImg,'thumb_image'=>$ThumbImg);
		return $aImage;

	}
	function getMovieWeekDateListing($iNoOFDays,$sSelectDate=''){
		$sUnitTime = strtotime(date('Y-m-d'));
		for($i=0;$i<$iNoOFDays;$i++){
			$retDispVal = date('d M Y', mktime(0,0,0,date('m',$sUnitTime),date('d',$sUnitTime)+$i,date('Y',$sUnitTime)));
			$retDispOptVal = date('Y-m-d', mktime(0,0,0,date('m',$sUnitTime),date('d',$sUnitTime)+$i,date('Y',$sUnitTime)));
			if($sSelectDate==$retDispOptVal){
				$strOptions.="<option value='$retDispOptVal' selected='selected'>".$retDispVal."</option>";
			}else{
				$strOptions.="<option value='$retDispOptVal'>".$retDispVal."</option>";
			}
		}
		return $strOptions;
	}

	function getCompactString($sStr,$stringCharLimit){
		$stringCharLimit=$stringCharLimit+10;
		//echo $sStr."<br>";
		$sString=substr($sStr,0,$stringCharLimit);
		//echo $sString."<br>";
		$aString=explode(" ",$sString);
		$aRetString=array_pop($aString);
		$sFinalString=implode(" ",$aString);
		return $sFinalString;
	}
	function getAlbhabeticalLink(){
		 $aAlphabets = range('A', 'Z');
		 $iCnt = count($aAlphabets);
		 $i=1;
		 foreach($aAlphabets as $sAlphabet){
			$sLink.= "<a href='javascript:void()' onclick=validatecelelbrity(2,'".$sAlphabet."') >$sAlphabet</a>";
			if($i<$iCnt) $sLink.=' | ';
			$i++;
		 }
		return $sLink;

	}
	/**
	 * @note function is used  calculate time elapsed from the date specified
	 * @pre  date date from where to calculate the time elapsed
	 * @post return  time elapsed
	 *
	 *
	 * @param date
	 */
	function calculateTimeElapsed($date){
		if(empty($date)) {
			return "No date provided";
		}
		$periods         = array("Second", "Minute", "Hour", "Day", "Week", "Month", "Year", "Decade");
		$lengths         = array("60","60","24","7","4.35","12","10");
		$now             = time();
		$unix_date         = strtotime($date);
		// check validity of date
		if(empty($unix_date)) {
			return "Bad date";
		}
		// is it future date or past date
		if($now > $unix_date) {
			$difference     = $now - $unix_date;
			$tense         = "Ago";
		} else {
			$difference     = $unix_date - $now;
			$tense         = "from now";
		}
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
		$difference = round($difference);
		if($difference != 1) {
			$periods[$j].= "s";
		}
		return "$difference $periods[$j] {$tense}";
	}
	public function getDOBString($iDay='',$iMonth='',$iYear=''){
		//die($iDay.'=='.$iMonth.'=='.$iYear);
		$strDOB  = $this->getDayString($iDay);
		$strDOB .= $this->getMonthString($iMonth);
		$strDOB .= $this->getYearString($iYear);
		return $strDOB;
	}
	public function getDayString($iDay){
		$sDayString  = "<DAY><![CDATA[";
		for($i=1;$i<=31;$i++){
			if($i==$iDay)
				$sDayString .= "<option value='$i' selected='true'>$i</option>";
			else
				$sDayString .= "<option value='$i'>$i</option>";
		}
		$sDayString .= "]]></DAY>";
		return $sDayString;
	}
	public function getMonthString($iMonth){
		$sMonthString  = "<MONTH><![CDATA[";
		for($i=1;$i<=12;$i++){
			if($i==$iMonth)
				$sMonthString .= "<option value='$i' selected='true'>$i</option>";
			else
				$sMonthString .= "<option value='$i'>$i</option>";
		}
		$sMonthString .= "]]></MONTH>";
		return $sMonthString;
	}
	public function getYearString($iSelYear,$iFlag=0){
		$iYear 	      = date('Y');
		$iYearLimit   = $iYear - 100;

		if(!empty($iFlag) && $iFlag>0){
					$iYearLimit   = $iYear - $iFlag;
					$iYear 	      = date('Y')+5;
		}

		$sYearString  = "<YEAR><![CDATA[";
		for($i=$iYear;$i>=$iYearLimit;$i--){
			if($i==$iSelYear){
				$sYearString.= "<option value='$i' selected='true'>$i</option>";
			}else{
				$sYearString.= "<option value='$i'>$i</option>";
			}

		}
		$sYearString .= "]]></YEAR>";
		return $sYearString;
	}
	/*public function getGenderString($sGender){
		global $aGender;
		$sGenderString  = "<GENDER><![CDATA[";
		foreach($aGender as $key=>$val){
			if($key==$sGender){
				$sGenderString.= "<option value='$key' selected='true'>$val</option>";
			}else{
				$sGenderString.= "<option value='$key' >$val</option>";
			}

		}
		$sGenderString .= "]]></GENDER>";
		return $sGenderString;
	}*/
	public function getOptionString($sTag,$aOptionArray,$sVal){
		$sOptString  = "<$sTag><![CDATA[";
		foreach($aOptionArray as $key=>$val){
			if($sVal==$key){
				$sOptString .= "<option value='$key' selected='true'>$val</option>";
			}
			else{
				$sOptString .= "<option value='$key'>$val</option>";
			}
		}
		$sOptString .= "]]></$sTag>";
		return $sOptString;
	}
    /**
	 * closetags
     * used to close html tags incase not closed properly
     * @param string $html - html string
	 * @access public
	*/
	function closetags($html){
		$arr_single_tags = array('meta','img','br','link','area');
		preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])\s*>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];
		$len_opened = count($openedtags);
		if (count($closedtags) == $len_opened){
			return $html;
		}
		$openedtags = array_reverse($openedtags);
		//re arrange open tags and closed tags for count
		$aOpenedtagsCnt=Array();
		$aClosedtagsCnt=Array();
		if(is_array($openedtags)){
			foreach($openedtags as $iK =>$sTag){
				if(!isset($aOpenedtagsCnt[$sTag])){
					$aOpenedtagsCnt[$sTag]=1;
				}else{
					$aOpenedtagsCnt[$sTag]++;
				}
			}
		}
		if(is_array($closedtags)){
			foreach($closedtags as $iK =>$sTag){
				if(!isset($aClosedtagsCnt[$sTag])){
					$aClosedtagsCnt[$sTag]=1;
				}else{
					$aClosedtagsCnt[$sTag]++;
				}
			}
		}
		for ($i=0; $i < $len_opened; $i++){
			if (!in_array($openedtags[$i],$arr_single_tags)){
				if ($aOpenedtagsCnt[$openedtags[$i]]!=$aClosedtagsCnt[$openedtags[$i]]){
					$html .= '</'.$openedtags[$i].'>';
					if(!isset($aClosedtagsCnt[$openedtags[$i]])){
						$aClosedtagsCnt[$openedtags[$i]]=1;
					}else{
						$aClosedtagsCnt[$openedtags[$i]]++;
					}
				}
			}
		}
		return $html;
	}


}