<?php
	function cleanInt($str){
		$str = trim($str);
		$str = preg_replace('/[^0-9]/', '', $str);
		$str = preg_replace('/\s+/', '', $str);
		return $str;
	}
	function cleanStr($str){
		$str = trim($str);
		$str = preg_replace('/[^a-zA-Z0-9]/', '', $str);
		$str = preg_replace('/\s+/', '', $str);
		return $str;
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
