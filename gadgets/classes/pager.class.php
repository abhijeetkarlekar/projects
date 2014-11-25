<?php
/**************************************************************************************
 * Class: Pager
 * Author:
 * Methods:
 *         findStart
 *         findPages
 *         pageList
 *         nextPrev
 * Redistribute as you see fit.
 **************************************************************************************/
 class Pager
  {
	/***********************************************************************************
	* int findStart (int limit)
	* Returns the start offset based on $_GET['page'] and $limit
	***********************************************************************************/
	function findStart($limit){
		if ((!isset($_REQUEST['page'])) || ($_REQUEST['page'] <= "1")){
			$start = 0;
			$_REQUEST['page'] = 1;
		}
		else{
			$start = ($_REQUEST['page']-1) * $limit;
		}

		return $start;
	}

	function findStartPage($limit){
		if ((!isset($_REQUEST['pagec'])) || ($_REQUEST['pagec'] <= "1")){
			$start = 0;
			$_REQUEST['pagec'] = 1;
		}
		else{
			$start = ($_REQUEST['pagec']-1) * $limit;
		}
		//echo "START".$start;
		return $start;
	}
	/***********************************************************************************
	* int findPages (int count, int limit)
	* Returns the number of pages needed based on a count and a limit
	***********************************************************************************/
	function findPages($count, $limit){
		$pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
		return $pages;
	}
	/***********************************************************************************
	* string pageNumNextPrev (int curpage, int pages)
	* Returns "Previous  Next" for individual pagination
	***********************************************************************************/
	/*public function pageNumNextPrev($curpage, $pages, $siteurl="", $link_type = "")
	{
		$iLimitPages=3;
		if($curpage==1 ){
		      $iStartNo=$curpage;
		}
		elseif($curpage==2 ){
		      $iStartNo=$curpage-1;
		}else{
		    $iStartNo=$curpage-2;
		}

		$iEndNo =($iStartNo+$iLimitPages)-1;
	        if($iEndNo>$pages) $iEndNo=$pages;
		$iDiff =$iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
		    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
		}
		if($iStartNo<1) $iStartNo=1;
        	$next_prev  = "";
		//Adding the optional parameters that have to be passed to the JS function - 2006-Sep-12
		/*if ($qryparams){
		    $qryparams = trim($qryparams);
		    if ($qryparams{0} != ","){
		        $qryparams = "," . $qryparams;
		    }
		}
		$qrparamsarr=explode(',',$qryparams);
		$qryparams=implode("','",$qrparamsarr);*/
		//	Setting up Previous / Next text or image links -
		/*
		$first_link = ($link_type == "text") ? "First" : "First";
		$prev_link = ($link_type == "text") ? "Prev" :" Prev";
		$next_link = ($link_type == "text") ? "Next" : "Next";
		$last_link = ($link_type == "text") ? "Last" : "Last";
		/

		$first_link = "";
		$prev_link = "Prev";
		$next_link = "Next";
	        $last_link = "";

		if (($curpage-1) <= 0)
		{
		    //$next_prev .= "<span class=\"pagination_text\">".$first_link."</span>";
		    if($curpage >1 )
		    {
		        $next_prev .=  "<span class=\"pagination_text\">".$prev_link."</span>";
		    }
		}
		else
		{
		    //$next_prev .= "<a href=\"".$siteurl."?page=1\" title=\"First\" class=\"pagination_text\">" . $first_link . "</a>";
		    $next_prev .="<a href=\"".$siteurl."?page=".($curpage-1)."\" title=\"Previous\" class=\"vwMr fl bG2e3137\"><span class=\"cfff plr5\">" . $prev_link . "</span></a>";
		}
		/* Print the numeric page list; make the current page unlinked and bold /
		if($curpage+4 < $pages)
		    $pk=$curpage+4;
		else
		    $pk=$pages;
	       //echo $iStartNo;
		for ($i=$iStartNo; $i<=$iEndNo; $i++){
		    if ($i == $curpage){
		        $next_prev .= "<b><span class='sel'>".$i."</span></b>";
		    }
		    else {
		        $next_prev .= "<a href=\"javascript:void(0);\" title=\"Page $i\"  onClick=\"Javascript:" . $jsfunc . "('" . $i . $qryparams . "')" . ";return false;\">" . $i . "</a> ";
		    }
	            $next_prev .= " ";
	        }/
		if (($curpage+1) > $pages)
		{
		    if($curpage < $pages){
		        $next_prev .= "<span class=\"pagination_text\" >".$next_link."</span>";
		    }
		    //$next_prev .=  "<span class=\"pagination_text\">".$last_link."</span> ";
		}
		else
		{
			$next_prev .=  "<a href=\"".$siteurl."?page=".($curpage+1)."\" title=\"Next\" class=\"vwMr fr bG2e3137\"><span class=\"cfff plr5\">" . $next_link . "</span></a> ";
		    	//$next_prev .=  "<a href=\"".$siteurl."?page=".$pages."\" title=\"Last\">" . $last_link . "</a> ";
		}
		$next_prev .= "<div class=\"cb\"></div>";
		return $next_prev;
    }*/
	public function pageNumNextPrev($curpage, $pages, $siteurl="", $param = "")
	{
		//echo $pages."PAGES";
		$param = str_replace('?','&',$param);
		if(!empty($param)){$param='&tid='.$param;}
		$iLimitPages=3;
		if($curpage==1 ){
		      $iStartNo=$curpage;
		}
		elseif($curpage==2 ){
		      $iStartNo=$curpage-1;
		}else{
		    $iStartNo=$curpage-2;
		}

		$iEndNo =($iStartNo+$iLimitPages)-1;
	        if($iEndNo>$pages) $iEndNo=$pages;
		$iDiff =$iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
		    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
		}
		if($iStartNo<1) $iStartNo=1;
        	$next_prev  = "";

		$first_link = "";
		$prev_link = "";
		$next_link = "";
	        $last_link = "";

		if (($curpage-1) <= 0)
		{
		    if($curpage >1 )
		    {
		        $next_prev .=  "<a class=\"nxt fl\">".$prev_link."</a>";
		    }
		}
		else
		{
		    $next_prev .="<a href=\"".$siteurl."?page=".($curpage-1).$param."\" title=\"Previous\" class=\"pre fl\">" . $prev_link . "</a>";
		}
		/* Print the numeric page list; make the current page unlinked and bold */
		$next_prev .= "<div class=\"fl\">";
		if($curpage+4 < $pages)
		    $pk=$curpage+4;
		else
		    $pk=$pages;

		for ($i=$iStartNo; $i<=$iEndNo; $i++){
		    if ($i == $curpage){
		        $next_prev .= "<a class='b'>".$i."</a>";
		    }
		    else {
		        $next_prev .= "<a href=\"".$siteurl."?page=".$i.$param."\" title=\"Page $i\" >" . $i . "</a> ";
		    }
	            $next_prev .= " ";
		}
		$next_prev .= "</div>";
		//echo $curpage."----".$pages;
		if (($curpage+1) > $pages)
		{
			if($curpage < $pages){
				$next_prev .= "<a class=\"nxt fl\" >".$next_link."</a>";
			}
		}
		else
		{
			$next_prev .=  "<a href=\"".$siteurl."?page=".($curpage+1).$param."\" title=\"Next\" class=\"nxt fl\">" . $next_link . "</a> ";
		}
		$next_prev .= "<div class=\"cb\"></div>";
		return $next_prev;
    }

	public function pageNumNextPrevUrlDealer($curpage, $pages, $siteurl="", $param = ""){
		//echo $pages."PAGES";
		$param = str_replace('?','&',$param);
		if(!empty($param)){$param='&tid='.$param;}
		$iLimitPages=3;
		if($curpage==1 ){ $iStartNo=$curpage;}
		elseif($curpage==2 ){
			$iStartNo=$curpage-1;
		}else{
			$iStartNo=$curpage-2;
		}
		$iEndNo =($iStartNo+$iLimitPages)-1;
		if($iEndNo>$pages) $iEndNo=$pages;
		$iDiff =$iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
			$iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
		}
		if($iStartNo<1) $iStartNo=1;
			$next_prev  = ""; 	$first_link = ""; 	$prev_link = ""; 	$next_link = ""; 	$last_link = "";
		$next_prev .= "<ul class=\"pagination\">";
		if (($curpage-1) <= 0){
			if($curpage >1 ){
				$next_prev .=  "<li><a class=\" pagearrow pback-unActive\">".$prev_link."</a></li>";
			}
		}else{
			$next_prev .="<li><a href=\"".$siteurl."/page-".($curpage-1).$param."\" title=\"Previous\"  class=\" pagearrow pageback\">" . $prev_link . "</a></li>";
		}
		/* Print the numeric page list; make the current page unlinked and bold */
		if($curpage+4 < $pages)
			$pk=$curpage+4;
		else
			$pk=$pages;

		if($pages > 1){
			for ($i=$iStartNo; $i<=$iEndNo; $i++){
				if ($i == $curpage){
					$next_prev .= "<li><a class='current'>".$i."</a> </li>";
				}else {
					$next_prev .= "<li><a href=\"".$siteurl."/page-".$i.$param."\" title=\"Page $i\"  >" . $i . "</a> </li>";
				}
				$next_prev .= " ";
			}
		}else{
			$next_prev .= " ";
		}
		if (($curpage+1) <= $pages){
			if(($curpage < $pages) && ($pages > 1)){
				//$next_prev .= "<li><a class=\"pagearrow pagenext\" >".$next_link." </a> </li>";
				$next_prev .= "<li><a class=\"pagearrow pagenext\" href=\"".$siteurl."/page-".($curpage+1).$param."\" title=\"Next\">".$next_link." </a> </li>";
			}
		}else{
			if($pages > 1){
				//$next_prev .=  "<li><a href=\"".$siteurl."/".($curpage+1).$param."\" title=\"Next\"  class=\"pagearrow pnext-unActive\">" . $next_link . "</a> </li>";
				$next_prev .=  "<li><a title=\"Next\"  class=\"pagearrow pnext-unActive\">" . $next_link . "</a> </li>";
			}
		}
		$next_prev .= "</ul>";
		$next_prev .= "<div class=\"clear\"></div>";
		return $next_prev;
	}

	public function postPageNumNextPrevUrl($curpage, $pages, $siteurl="", $param = "")
        {
                //echo $pages."PAGES";
		$param = str_replace('?','&',$param);
                if(!empty($param)){$param='&tid='.$param;}
                $iLimitPages=3;
                if($curpage==1 ){
                      $iStartNo=$curpage;
                }
                elseif($curpage==2 ){
                      $iStartNo=$curpage-1;
                }else{
                    $iStartNo=$curpage-2;
                }

                $iEndNo =($iStartNo+$iLimitPages)-1;
                if($iEndNo>$pages) $iEndNo=$pages;
                $iDiff =$iEndNo-$iStartNo;
                if($iDiff<($iLimitPages-1)) {
                    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
                }
                if($iStartNo<1) $iStartNo=1;
                $next_prev  = "";
		$first_link = "";
                $prev_link = "";
                $next_link = "";
                $last_link = "";
		$next_prev .= "<ul class=\"pagination\">";
                if (($curpage-1) <= 0)
                {
                    if($curpage >1 )
                    {
                        $next_prev .=  "<li><a class=\" pagearrow pback-unActive\">".$prev_link."</a></li>";
                    }
                }
                else
                {
                    $next_prev .="<li><a onclick='javascript:postDealerDetail($curpage-1)' href='javascript:void(0);' title=\"Previous\"  class=\" pagearrow pageback\">" . $prev_link . "</a></li>";
                }
                /* Print the numeric page list; make the current page unlinked and bold */

                if($curpage+4 < $pages)
                    $pk=$curpage+4;
                else
                    $pk=$pages;

		if($pages > 1){
			for ($i=$iStartNo; $i<=$iEndNo; $i++){
        	            if ($i == $curpage){
                	        $next_prev .= "<li><a class='current'>".$i."</a> </li>";
	                    }
        	            else {
                	        $next_prev .= "<li><a onclick='javascript:postDealerDetail($i)' href='javascript:void(0);' title=\"Page $i\"  >" . $i . "</a> </li>";
	                    }
        	            $next_prev .= " ";
                	}
		}else{
        	        $next_prev .= " ";
		}

                //echo $curpage."----".$pages;
                if (($curpage+1) <= $pages){
                        if(($curpage < $pages) && ($pages > 1)){
                                //$next_prev .= "<li><a class=\"pagearrow pagenext\" >".$next_link." </a> </li>";
                                $next_prev .= "<li><a class=\"pagearrow pagenext\" href='javascript:void(0);' onclick='javascript:postDealerDetail($curpage+1)' title=\"Next\">".$next_link." </a> </li>";
                        }
                }else{
		     if($pages > 1){
	                     //$next_prev .=  "<li><a href=\"".$siteurl."/".($curpage+1).$param."\" title=\"Next\"  class=\"pagearrow pnext-unActive\">" . $next_link . "</a> </li>";
	                     $next_prev .=  "<li><a title=\"Next\"  class=\"pagearrow pnext-unActive\">" . $next_link . "</a> </li>";
		      }
                }

	        $next_prev .= "</ul>";
                $next_prev .= "<div class=\"clear\"></div>";

                return $next_prev;
    }

	public function pageNumNextPrevUrl($curpage, $pages, $siteurl="", $param = ""){
		//echo "SSSSSS".$pages."PAGES";
		$param = str_replace('?','&',$param);
		if(!empty($param)){$param='&tid='.$param;}
		$iLimitPages = 3;
		if($curpage==1 ){
			$iStartNo=$curpage;
		}
		elseif($curpage==2 ){
			$iStartNo=$curpage-1;
		}else{
			$iStartNo=$curpage-2;
		}
		$iEndNo =($iStartNo+$iLimitPages)-1;
		if($iEndNo > $pages) { $iEndNo = $pages; }
		$iDiff = $iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
			$iStartNo = $iStartNo-($iLimitPages-$iDiff-1);
		}
		
		if($iStartNo<1) $iStartNo=1;
		$next_prev  = "";
		$first_link = "";
		$prev_link = "Prev";
		$next_link = "Next";
		$last_link = "";
		//echo "START===".$iStartNo."---------END--".$iEndNo;  die();

		$next_prev .= "<ul class=\"pagination\">";
		if (($curpage-1) <= 0){
			if($curpage >1 ){
				$next_prev .=  "<li><a><i class=\"pags-fl\"></i>".$prev_link."</a></li>";
			}
		}else{
			$next_prev .="<li><a href=\"".$siteurl."page/".($curpage-1).$param."\" title=\"Previous\" ><i class=\"pags-fl\"></i>" . $prev_link . "</a></li>";
		}
		/* Print the numeric page list; make the current page unlinked and bold */
		if($curpage+4 < $pages)
		$pk=$curpage+4;
		else
		$pk=$pages;
		if($pages > 1){
		for ($i=$iStartNo; $i<=$iEndNo; $i++){
		if ($i == $curpage){
		$next_prev .= "<li><a class='active'>".$i."</a> </li>";
		}
		else {
		$next_prev .= "<li><a href=\"".$siteurl."page/".$i.$param."\" title=\"Page $i\"  >" . $i . "</a> </li>";
		}
		$next_prev .= " ";
		}
		}else{
		$next_prev .= " ";
		}
		//echo $curpage."----".$pages;
		if (($curpage+1) <= $pages){
			if(($curpage < $pages) && ($pages > 1)){
				//$next_prev .= "<li><a class=\"pagearrow pagenext\" >".$next_link." </a> </li>";
				$next_prev .= "<li><a href=\"".$siteurl."page/".($curpage+1).$param."\" title=\"Next\"><i class=\"pags-fr\"></i>".$next_link." </a> </li>";
			}
		}else{
			if($pages > 1){
				//$next_prev .=  "<li><a href=\"".$siteurl."/".($curpage+1).$param."\" title=\"Next\"  class=\"pagearrow pnext-unActive\">" . $next_link . "</a> </li>";
				//$next_prev .=  "<li><a title=\"Next\"><i class=\"pags-fr\">" . $next_link . "</a> </li>";
			}
		}
		$next_prev .= "</ul>";
		$next_prev .= "<div class=\"clear\"></div>";
		return $next_prev;
	}

	public function pageNumNextPrevVideo($curpage, $pages, $siteurl="", $param = "")
        {
                //if(!empty($param)){$param='&tid='.$param;}
                $iLimitPages=3;
                if($curpage==1 ){
                      $iStartNo=$curpage;
                }
                elseif($curpage==2 ){
                      $iStartNo=$curpage-1;
                }else{
                    $iStartNo=$curpage-2;
                }

                $iEndNo =($iStartNo+$iLimitPages)-1;
                if($iEndNo>$pages) $iEndNo=$pages;
                $iDiff =$iEndNo-$iStartNo;
                if($iDiff<($iLimitPages-1)) {
                    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
                }
                if($iStartNo<1) $iStartNo=1;
                $next_prev  = "";

                $first_link = "";
                $prev_link = "";
                $next_link = "";
                $last_link = "";

		if (($curpage-1) <= 0)
                {
                    if($curpage >1 )
                    {
                        $next_prev .=  "<span class=\"pagination_text\">".$prev_link."</span>";
                    }
                }
                else
                {
                    $next_prev .="<a href=\"".$siteurl."&page=".($curpage-1).$param."\" title=\"Previous\" class=\"pre fl\">" . $prev_link . "</a>";
                }
                /* Print the numeric page list; make the current page unlinked and bold */
                $next_prev .= "<div class=\"fl\">";
                if($curpage+4 < $pages)
                    $pk=$curpage+4;
                else
                    $pk=$pages;

                for ($i=$iStartNo; $i<=$iEndNo; $i++){
                    if ($i == $curpage){
                        $next_prev .= "<a class='b'>".$i."</a>";
                    }
                    else {
                        $next_prev .= "<a href=\"".$siteurl."&page=".$i.$param."\" title=\"Page $i\" >" . $i . "</a> ";
                    }
                    $next_prev .= " ";
                }
                $next_prev .= "</div>";
		if (($curpage+1) > $pages)
                {
                    if($curpage < $pages){
                        $next_prev .= "<span class=\"pagination_text\" >".$next_link."</span>";
                    }
                }
                else
                {
                        $next_prev .=  "<a href=\"".$siteurl."&page=".($curpage+1).$param."\" title=\"Next\" class=\"nxt fl\">" . $next_link . "</a> ";
                }
                $next_prev .= "<div class=\"cb\"></div>";
                return $next_prev;
    }



	public function pageNumNextNewPrevVideo($curpage, $pages, $siteurl="", $param = "")
        {
                //if(!empty($param)){$param='&tid='.$param;}
                $iLimitPages=3;
                if($curpage==1 ){
                      $iStartNo=$curpage;
                }
                elseif($curpage==2 ){
                      $iStartNo=$curpage-1;
                }else{
                    $iStartNo=$curpage-2;
                }

                $iEndNo =($iStartNo+$iLimitPages)-1;
                if($iEndNo>$pages) $iEndNo=$pages;
                $iDiff =$iEndNo-$iStartNo;
                if($iDiff<($iLimitPages-1)) {
                    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
                }
                if($iStartNo<1) $iStartNo=1;
                $next_prev  = "";

                $first_link = "";
                $prev_link = "";
                $next_link = "";
                $last_link = "";

		if (($curpage-1) <= 0)
                {
                    if($curpage >1 )
                    {
                        $next_prev .=  "<span class=\"pagination_text\">".$prev_link."</span>";
                    }
                }
                else
                {
                    $next_prev .="<a href=\"".$siteurl."&pagec=".($curpage-1).$param."\" title=\"Previous\" class=\"pre fl\">" . $prev_link . "</a>";
                }
                /* Print the numeric page list; make the current page unlinked and bold */
                $next_prev .= "<div class=\"fl\">";
                if($curpage+4 < $pages)
                    $pk=$curpage+4;
                else
                    $pk=$pages;

                for ($i=$iStartNo; $i<=$iEndNo; $i++){
                    if ($i == $curpage){
                        $next_prev .= "<a class='b'>".$i."</a>";
                    }
                    else {
                        $next_prev .= "<a href=\"".$siteurl."&pagec=".$i.$param."\" title=\"Page $i\" >" . $i . "</a> ";
                    }
                    $next_prev .= " ";
                }
                $next_prev .= "</div>";
		if (($curpage+1) > $pages)
                {
                    if($curpage < $pages){
                        $next_prev .= "<span class=\"pagination_text\" >".$next_link."</span>";
                    }
                }
                else
                {
                        $next_prev .=  "<a href=\"".$siteurl."&pagec=".($curpage+1).$param."\" title=\"Next\" class=\"nxt fl\">" . $next_link . "</a> ";
                }
                $next_prev .= "<div class=\"cb\"></div>";
                return $next_prev;
    }

	/***********************************************************************************
	* string jsPageNumNextPrev (int curpage, int pages)
	* Returns "Previous | 1 | 2 |......| Next" for individual pagination (as an image with an onClick js function call!)
	***********************************************************************************/
	public function jsPageNumNextPrev($curpage, $pages, $jsfunc, $qryparams="", $link_type = "")
	{
		$iLimitPages=5;
		if($curpage==1 ){
		      $iStartNo=$curpage;
		}
		elseif($curpage==2 ){
		      $iStartNo=$curpage-1;
		}else{
		    $iStartNo=$curpage-2;
		}

		$iEndNo =($iStartNo+$iLimitPages)-1;
	        if($iEndNo>$pages) $iEndNo=$pages;
		$iDiff =$iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
		    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
		}
		if($iStartNo<1) $iStartNo=1;
        	$next_prev  = "";
		//Adding the optional parameters that have to be passed to the JS function - 2006-Sep-12
		if ($qryparams){
		    $qryparams = trim($qryparams);
		    if ($qryparams{0} != ","){
		        $qryparams = "," . $qryparams;
		    }
		}
		$qrparamsarr=explode(',',$qryparams);
		$qryparams=implode("','",$qrparamsarr);
		//	Setting up Previous / Next text or image links -

		$first_link = ($link_type == "text") ? "First" : "First";
		$prev_link = ($link_type == "text") ? "Prev" :" Prev";
		$next_link = ($link_type == "text") ? "Next" : "Next";
		$last_link = ($link_type == "text") ? "Last" : "Last";


		$first_link = "First";
		$prev_link = "prev";
		$next_link = "Next";
	        $last_link = "Last";

		if (($curpage-1) <= 0)
		{
		    $next_prev .= "<span class=\"pagination_text\">".$first_link."</span>";
		    if($curpage >1 )
		    {
		        $next_prev .=  "<span class=\"pagination_text\">".$prev_link."</span>";
		    }
		}
		else
		{
		    $next_prev .= "<a href=\"javascript:void(0);\" title=\"First\" class=\"pagination_text\" onClick=\"Javascript:" . $jsfunc . "('1" . $qryparams . "')" . ";return false;\">" . $first_link . "</a>";
		    $next_prev .="<a href=\"javascript:void(0);\" title=\"Previous\" class=\"pagination_text\" onClick=\"Javascript:" . $jsfunc . "('" . ($curpage-1) . $qryparams . "')" . ";return false;\">" . $prev_link . "</a>";
		}
		/* Print the numeric page list; make the current page unlinked and bold */
		if($curpage+4 < $pages)
		    $pk=$curpage+4;
		else
		    $pk=$pages;
	       //echo $iStartNo;
		for ($i=$iStartNo; $i<=$iEndNo; $i++){
		    if ($i == $curpage){
		        $next_prev .= "<span class='currentpage'>".$i."</span>";
		    }
		    else {
		        $next_prev .= "<a href=\"javascript:void(0);\" title=\"Page $i\"  onClick=\"Javascript:" . $jsfunc . "('" . $i . $qryparams . "')" . ";return false;\" class='pagedigit'>" . $i . "</a> ";
		    }
	            $next_prev .= " ";
	        }
		if (($curpage+1) > $pages)
		{
		    if($curpage < $pages){
		        $next_prev .= "<span class=\"pagination_text\" >".$next_link."</span>";
		    }
		    $next_prev .=  "<span class=\"pagination_text\">".$last_link."</span> ";
		}
		else
		{
		    $next_prev .=  "<a href=\"javascript:void(0);\" title=\"Next\" class=\"pagination_text\"  onClick=\"Javascript:" . $jsfunc . "('" . ($curpage+1) . $qryparams . "')" . ";return false;\">" . $next_link . "</a> ";
		    $next_prev .=  "<a href=\"javascript:void(0);\" title=\"Last\" class=\"pagination_text\"   onClick=\"Javascript:" . $jsfunc . "('" . $pages . $qryparams . "')" . ";return false;\">" . $last_link . "</a> ";
		}
		return $next_prev;
    }



	/***********************************************************************************
	* string jsPageNumNextPrev (int curpage, int pages)
	* Returns "Previous | 1 | 2 |......| Next" for individual pagination (as an image with an onClick js function call!)
	***********************************************************************************/
/*kamlesh edit*/
	public function jsPageNumNextPrevVideoCat($curpage, $pages, $jsfunc, $qryparams="", $link_type = "")
	{
		$iLimitPages=5;
		if($curpage==1 ){
		      $iStartNo=$curpage;
		}
		elseif($curpage==2 ){
		      $iStartNo=$curpage-1;
		}else{
		    $iStartNo=$curpage-2;
		}

		$iEndNo =($iStartNo+$iLimitPages)-1;
	        if($iEndNo>$pages) $iEndNo=$pages;
		$iDiff =$iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
		    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
		}
		if($iStartNo<1) $iStartNo=1;
        	$next_prev  = "";
		//Adding the optional parameters that have to be passed to the JS function - 2006-Sep-12
		if ($qryparams){
		    $qryparams = trim($qryparams);
		    if ($qryparams{0} != ","){
		        $qryparams = "," . $qryparams;
		    }
		}
		$qrparamsarr=explode(',',$qryparams);
		$qryparams=implode("','",$qrparamsarr);
		//	Setting up Previous / Next text or image links -

		$first_link = ($link_type == "text") ? "First" : "First";
		$prev_link = ($link_type == "text") ? "Prev" :" Prev";
		$next_link = ($link_type == "text") ? "Next" : "Next";
		$last_link = ($link_type == "text") ? "Last" : "Last";


		$first_link = "";
		$prev_link = "Prev";
		$next_link = "Next";
        $last_link = "";
		$next_prev.="<ul class=\"pagination\">";
		if (($curpage-1) <= 0)
		{
		    $next_prev .= "<li><span class=\"pagination_text\">".$first_link."</span></li>";
		    if($curpage >1 )
		    {
		        $next_prev .= "<li><span class=\"pagination_text\">".$prev_link."</span></li>";
		    }
		}
		else
		{
		   // $next_prev .= "<li><a href=\"javascript:void(0);\" title=\"First\" class=\"pagination_text\" onClick=\"Javascript:" . $jsfunc . "('1" . $qryparams . "')" . ";return false;\">" . $first_link . "</a></li>";
		    $next_prev .="<li><a href=\"javascript:void(0);\" title=\"Previous\" class=\"pagearrow pageback\" onClick=\"Javascript:" . $jsfunc . "('" . ($curpage-1) . $qryparams . "')" . ";return false;\"></a></li>";
		}
		/* Print the numeric page list; make the current page unlinked and bold */
		if($curpage+4 < $pages)
		    $pk=$curpage+4;
		else
		    $pk=$pages;
	       //echo $iStartNo;
		for ($i=$iStartNo; $i<=$iEndNo; $i++){
		    if ($i == $curpage){
		        $next_prev .= "<li><a href=\"#\" class=\"current\">".$i."</a></li>";
		    }
		    else {
		        $next_prev .= "<li><a href=\"javascript:void(0);\" title=\"Page $i\"  onClick=\"Javascript:" . $jsfunc . "('" . $i . $qryparams . "')" . ";return false;\">" . $i . "</a></li> ";
		    }
	            $next_prev .= " ";
	        }
		if (($curpage+1) > $pages)
		{
		    if($curpage < $pages){
		        $next_prev .= "<li><a href=\"#\">".$next_link."</a></li>";
		    }
		    $next_prev .=  "<li><a href=\"#\">".$last_link."</a></li>";
		}
		else
		{
		    $next_prev .=  "<li><a href=\"javascript:void(0);\" title=\"Next\" class=\" pagearrow pagenext \"  onClick=\"Javascript:" . $jsfunc . "('" . ($curpage+1) . $qryparams . "')" . ";return false;\"></a> </li>";
		    //$next_prev .=  "<a href=\"javascript:void(0);\" title=\"Last\"  onClick=\"Javascript:" . $jsfunc . "('" . $pages . $qryparams . "')" . ";return false;\">" . $last_link . "</a> ";
		}
		$next_prev.="</ul>";
		return $next_prev;
    }


	public function jsAjaxPageNumNextPrev($curpage, $pages, $jsfunc, $qryparams="", $link_type = ""){
		$iLimitPages=5;
		if($curpage==1 ){
			$iStartNo=$curpage;
		}
		elseif($curpage==2 ){
			$iStartNo=$curpage-1;
		}else{
			$iStartNo=$curpage-2;
		}
		$iEndNo =($iStartNo+$iLimitPages)-1;
		if($iEndNo>$pages) $iEndNo=$pages;
		$iDiff =$iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
			$iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
		}
		if($iStartNo<1) $iStartNo=1;
			$next_prev  = "";
			//Adding the optional parameters that have to be passed to the JS function - 2006-Sep-12
			if ($qryparams){
				$qryparams = trim($qryparams);
				if ($qryparams{0} != ","){
					$qryparams = "," . $qryparams;
				}
			}
			$qrparamsarr=explode(',',$qryparams);
			$qryparams=implode("','",$qrparamsarr);
			//	Setting up Previous / Next text or image links -
			/*
			$first_link = ($link_type == "text") ? "First" : "First";
			$prev_link = ($link_type == "text") ? "Prev" :" Prev";
			$next_link = ($link_type == "text") ? "Next" : "Next";
			$last_link = ($link_type == "text") ? "Last" : "Last";
			*/
			$first_link = "";
			$prev_link = "<";
			$next_link = ">";
			$last_link = "";
			/*if (($curpage-1) <= 0)
			{
				$next_prev .= "<span class=\"pagination_text\">".$first_link."</span>";
				if($curpage >1 )
				{
					$next_prev .=  "<span class=\"pagination_text\">".$prev_link."</span>";
				}
			}
			else
			{
				$next_prev .= "<a href=\"javascript:void(0);\" title=\"First\" class=\"pagination_text\" onClick=\"Javascript:" . $jsfunc . "('1" . $qryparams . "')" . ";return false;\">" . $first_link . "</a>";
				$next_prev .="<a href=\"javascript:void(0);\" title=\"Previous\" class=\"pagination_text\" onClick=\"Javascript:" . $jsfunc . "('" . ($curpage-1) . $qryparams . "')" . ";return false;\">" . $prev_link . "</a>";
			}
			*/
			/* Print the numeric page list; make the current page unlinked and bold */
			if($curpage+4 < $pages)
			$pk=$curpage+4;
			else
			$pk=$pages;
			//echo $iStartNo;
			for ($i=$iStartNo; $i<=$iEndNo; $i++){
			if ($i == $curpage){
				$next_prev .= "<b class=\"pg\"><span class='sel cfff plr5'>".$i."</span></b>";
			}
			else {
				$next_prev .= "<a href=\"javascript:void(0);\" title=\"Page $i\"  onClick=\"Javascript:" . $jsfunc . "('" . $i . $qryparams . "')" . ";return false;\">" . $i . "</a> ";
			}
			$next_prev .= " ";
		}
		/*if (($curpage+1) > $pages)
		{
			if($curpage < $pages){
				$next_prev .= "<span class=\"pagination_text\" >".$next_link."</span>";
			}
			$next_prev .=  "<span class=\"pagination_text\">".$last_link."</span> ";
		}
		else
		{
			$next_prev .=  "<a href=\"javascript:void(0);\" title=\"Next\"  onClick=\"Javascript:" . $jsfunc . "('" . ($curpage+1) . $qryparams . "')" . ";return false;\">" . $next_link . "</a> ";
			$next_prev .=  "<a href=\"javascript:void(0);\" title=\"Last\"  onClick=\"Javascript:" . $jsfunc . "('" . $pages . $qryparams . "')" . ";return false;\">" . $last_link . "</a> ";
		}
		*/
		return $next_prev;
	}

/***********************************************************************************
	* int findStartArticle (int limit)
	* Returns the start offset based on $_GET['page'] and $limit
	***********************************************************************************/
	function findStartArticle($limit){

		if ((!isset($_REQUEST['page'])) || ($_REQUEST['page'] <= "1")){
			$start = 0;
			$_REQUEST['page'] = 1;
		}
		else{
			$start = ($_REQUEST['page']-1) * $limit;
		}

		return $start;
	}

  function findStartArticle_new($limit){

                if ((!isset($_REQUEST['pg'])) || ($_REQUEST['pg'] <= "1")){
                        $start = 0;
                        $_REQUEST['pg'] = 1;
                }
                else{
                        $start = ($_REQUEST['pg']-1) * $limit;
                }

                return $start;
        }

	public function pageNumNextPrevArticle($curpage, $pages, $siteurl="", $param = ""){

		if(!empty($param)){$param='&tid='.$param;}
		$iLimitPages=3;
		if($curpage==1 ){
		      $iStartNo=$curpage;
		}
		elseif($curpage==2 ){
		      $iStartNo=$curpage-1;
		}else{
		    $iStartNo=$curpage-2;
		}

		$iEndNo =($iStartNo+$iLimitPages)-1;
	        if($iEndNo>$pages) $iEndNo=$pages;
		$iDiff =$iEndNo-$iStartNo;
		if($iDiff<($iLimitPages-1)) {
		    $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
		}
		if($iStartNo<1) $iStartNo=1;
        	$next_prev  = "";

		$first_link = "&lt;&#160;Previous";
		$prev_link = "&lt;&#160;Previous";
		$next_link = "Next&#160;&gt;";
        $last_link = "Next&#160;&gt;";

		if (($curpage-1) <= 0)
		{
		    if($curpage >1 )
		    {
		        $next_prev .=  "<a class=\"fl\">".$prev_link."</a>";
		    }
		}
		else
		{
		    //$next_prev .="<a href=\"".$siteurl."?pg=".($curpage-1).$param."\" title=\"Previous\" class=\"fl\">" . $prev_link . "</a>";
		    $next_prev .="<a href=\"".$siteurl."/".($curpage-1).$param."\" title=\"Previous\" class=\"fl\">" . $prev_link . "</a>";
		}
		/* Print the numeric page list; make the current page unlinked and bold */
		$next_prev .= "<div class=\"dtTxt fl\">";
		if($curpage+4 < $pages)
		    $pk=$curpage+4;
		else
		    $pk=$pages;

		for ($i=$iStartNo; $i<=$iEndNo; $i++){
		    if ($i == $curpage){
              if($i==$pages){
				   $next_prev .= "<a class='b'>".$i."</a>";
				 }else{
					if($iEndNo > $i){
						$next_prev .= "<a class='b'>".$i."</a>&#160;|&#160;";
					}else{
						$next_prev .= "<a class='b'>".$i."</a>";
					}
			   }


		    }
		    else{

				 if($iEndNo > $i){
					 //$next_prev .= "<a href=\"".$siteurl."?pg=".$i.$param."\" title=\"Page $i\" >".$i."</a>&#160;|&#160;";
					 $next_prev .= "<a href=\"".$siteurl."/".$i.$param."\" title=\"Page $i\" >".$i."</a>&#160;|&#160;";
				 }else{
					 //$next_prev .= "<a href=\"".$siteurl."?pg=".$i.$param."\" title=\"Page $i\" >".$i."</a>";
					 $next_prev .= "<a href=\"".$siteurl."/".$i.$param."\" title=\"Page $i\" >".$i."</a>";
				 }

			}
	            $next_prev .= " ";
		}
		$next_prev .= "</div>";
		//echo $curpage."----".$pages;
		if (($curpage+1) > $pages)
		{
			if($curpage < $pages){
				$next_prev .= "<a class=\"fl\" >".$next_link." </a>";
			}
		}
		else
		{
			//$next_prev .=  "<a href=\"".$siteurl."?pg=".($curpage+1).$param."\" title=\"Next\" class=\"fl\">" . $next_link . "</a> ";
			$next_prev .=  "<a href=\"".$siteurl."/".($curpage+1).$param."\" title=\"Next\" class=\"fl\">" . $next_link . "</a> ";
		}
		$next_prev .= "<div class=\"cb\"></div>";

		return $next_prev;
    }

	   function pagination($total, $per_page = 10,$page = 1, $url = ''){
    	/*$query = "SELECT COUNT(*) as `num` FROM {$query}";
    	$row = mysql_fetch_array(mysql_query($query));
    	$total = $row['num'];
		*/
		$total = $total;
        $adjacents = "2";

    	$page = ($page == 0 ? 1 : $page);
    	$start = ($page - 1) * $per_page;
		$per_page = $per_page;
    	$prev = $page - 1;
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	$pagination = "";
		$nexturl = $url.$next;
		$lastpageurl = $url.$lastpage;
		$prevurl = $url.$prev;
		$firsturl = $url.'1';

    	if($lastpage > 1){
    		 $pagination .= "<ul class='pagination'>";
         //$pagination .= "<li class='details'>Page $page of $lastpage</li>";
  			if ($page > 1){
	  			$pagination.= "<li><a href='{$firsturl}'  class='pagearrow pagepreview'></a></li>";
          $pagination.= "<li><a href='{$prevurl}' class='pagearrow pageback'></a></li>";
    		}else{
		  		$pagination.= "<li><a class='pagearrow ppreview-unActive'></a></li>";
    			$pagination.= "<li><a class='pagearrow pback-unActive'></a></li>";
        }

    		if ($lastpage <= (4 + ($adjacents * 2))){
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page){
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				}else{
						$pageurl = $url.$counter;
    					$pagination.= "<li><a href='{$pageurl}'>$counter</a></li>";
						unset($$pageurl);
					}
    			}
    		}elseif($lastpage > (4 + ($adjacents * 2))){
    			if($page < (1 + ($adjacents * 2))){
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page){
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					}else{
							$pageurl = $url.$counter;
    						$pagination.= "<li><a href='{$pageurl}'>$counter</a></li>";
							unset($$pageurl);
						}
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>";
    			}elseif((($lastpage - ($adjacents * 2)) > $page) && ($page > ($adjacents * 2))){
    				$pagination.= "<li><a href='{$url}1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}2'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page){
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					}else{
							$pageurl = $url.$counter;
    						$pagination.= "<li><a href='{$pageurl}'>$counter</a></li>";
							unset($$pageurl);
						}
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>";
    			}else{
    				$pagination.= "<li><a href='{$url}1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}2'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page){
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					}else{
							$pageurl = $url.$counter;
    						$pagination.= "<li><a href='{$pageurl}'>$counter</a></li>";
							unset($$pageurl);
						}
    				}
    			}
    		}
    		if ($page < $counter - 1){
    			$pagination.= "<li><a href='{$nexturl}' class=' pagearrow pagenext'></a></li>";
                $pagination.= "<li><a href='{$lastpageurl}' class=' pagearrow pageforword'></a></li>";
    		}else{
    			$pagination.= "<li><a class='pagearrow pnext-unActive' ></a></li>";
                $pagination.= "<li><a class='pagearrow pforword-unActive'></a></li>";
            }

    		$pagination.= "</ul>\n";
    	}

		//echo $pagination;die();
        return $pagination;
    }

	function searchpagination($total, $per_page = 10,$page = 1, $url = ''){
    	/*$query = "SELECT COUNT(*) as `num` FROM {$query}";
    	$row = mysql_fetch_array(mysql_query($query));
    	$total = $row['num'];
		*/
	$total = $total;
        $adjacents = "2";

    	$page = ($page == 0 ? 1 : $page);
    	$start = ($page - 1) * $per_page;
		$per_page = $per_page;
    	$prev = $page - 1;
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	$pagination = "";
		$nexturl = $next;
		$lastpageurl = $lastpage;
		$prevurl = $prev;
		$firsturl = '1';

    	if($lastpage > 1)
    	{
    		 $pagination .= "<ul class='pagination'>";
                    //$pagination .= "<li class='details'>Page $page of $lastpage</li>";
			if ($page > 1){
				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$firsturl})\" class='pagearrow pagepreview'></a></li>";
                $pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$prevurl})\" class='pagearrow pageback'></a></li>";
    		}else{
				 $pagination.= "<li ><a class='pagearrow ppreview-unActive'></a></li>";
    			$pagination.= "<li ><a class='pagearrow pback-unActive'></a></li>";
            }
    		if ($lastpage < 4 + ($adjacents * 2))
    		{
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page){
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				}else{
						$pageurl = $counter;
    					$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$pageurl})\">$counter</a></li>";
						unset($$pageurl);
					}
    			}
    		}
    		elseif($lastpage > 4 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page){
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					}else{
							$pageurl = $counter;
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$pageurl})\">$counter</a></li>";
							unset($$pageurl);
						}
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$lpm1})\">$lpm1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$lastpage})\">$lastpage</a></li>";
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage(1)\">1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage(2)\">2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page){
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					}else{
							$pageurl = $url.$counter;
    						$pagination.= "<li><a  href='javascript:void(0);' onclick=\"submitPage({$pageurl})\">$counter</a></li>";
							unset($$pageurl);
						}
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$lpm1})\">$lpm1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$lastpage})\">$lastpage</a></li>";
    			}
    			else
    			{
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage(1)\">1</a></li>";
    				$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage(2)\">2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page){
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					}else{
							$pageurl = $url.$counter;
    						$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$pageurl})\">$counter</a></li>";
							unset($pageurl);
						}
    				}
    			}
    		}
    		if ($page < $counter - 1){
    			$pagination.= "<li><a href='javascript:void(0);' onclick=\"submitPage({$nexturl})\" class=' pagearrow pagenext'></a></li>";
                $pagination.= "<li><a href='javascript:void(0);'  onclick=\"submitPage({$lastpageurl})\" class=' pagearrow pageforword'></a></li>";
    		}else{
    			$pagination.= "<li><a class='pagearrow pnext-unActive'></a></li>";
                $pagination.= "<li><a class='pagearrow pforword-unActive'></a></li>";
            }
    		$pagination.= "</ul>\n";
    	}

		//echo $pagination;
        return $pagination;
    }

    /***********************************************************************************
  * string jsPageNumNextPrevUsedCar (int curpage, int pages)
  * Returns "Previous | 1 | 2 |......| Next" for individual pagination (as an image with an onClick js function call!)
  ***********************************************************************************/
  public function jsPageNumNextPrevUsedCar($curpage, $pages, $jsfunc, $qryparams="", $link_type = ""){
    $iLimitPages=5;
    if($curpage==1 ){
          $iStartNo=$curpage;
    }
    elseif($curpage==2 ){
          $iStartNo=$curpage-1;
    }else{
        $iStartNo=$curpage-2;
    }

    $iEndNo =($iStartNo+$iLimitPages)-1;
          if($iEndNo>$pages) $iEndNo=$pages;
    $iDiff =$iEndNo-$iStartNo;
    if($iDiff<($iLimitPages-1)) {
        $iStartNo=$iStartNo-($iLimitPages-$iDiff-1);
    }
    if($iStartNo<1) $iStartNo=1;
          $next_prev  = "";
    //Adding the optional parameters that have to be passed to the JS function - 2006-Sep-12
    if ($qryparams){
        $qryparams = trim($qryparams);
        if ($qryparams{0} != ","){
            $qryparams = "," . $qryparams;
        }
    }
    $qrparamsarr=explode(',',$qryparams);
    $qryparams=implode("','",$qrparamsarr);
    //  Setting up Previous / Next text or image links -

    $first_link = ($link_type == "text") ? "First" : "First";
    $prev_link = ($link_type == "text") ? "Prev" :" Prev";
    $next_link = ($link_type == "text") ? "Next" : "Next";
    $last_link = ($link_type == "text") ? "Last" : "Last";


    $first_link = "";
    $prev_link = "<img  src=\"".IMAGE_URL."view_arr2_on.png\" align=\"absmiddle\"/>";
    $next_link = "<img  src=\"".IMAGE_URL."view_arr_on.png\" align=\"absmiddle\"/>";
          $last_link = "";

    if (($curpage-1) <= 0)
    {
        $next_prev .= "<span class=\"pagination_text\">".$first_link."</span>";
        if($curpage >1 )
        {
            $next_prev .=  "<span class=\"pagination_text\">".$prev_link."</span>";
        }
    }
    else
    {

        $next_prev .="<a href=\"javascript:void(0);\" title=\"Previous\" class=\"prev\" onClick=\"Javascript:" . $jsfunc . "('" . ($curpage-1) . $qryparams . "')" . ";return false;\"> </a>";
    }
    /* Print the numeric page list; make the current page unlinked and bold */
    if($curpage+4 < $pages)
        $pk=$curpage+4;
    else
        $pk=$pages;
         //echo $iStartNo;
    for ($i=$iStartNo; $i<=$iEndNo; $i++){
        if ($i == $curpage){
            $next_prev .= "<b><span class='b'>".$i."</span></b>";
        }
        else {
            $next_prev .= "<a href=\"javascript:void(0);\" title=\"Page $i\"  onClick=\"Javascript:" . $jsfunc . "('" . $i . $qryparams . "')" . ";return false;\">" . $i . "</a> ";
        }
              $next_prev .= " ";
          }
  if (($curpage+1) > $pages)
    {
        if($curpage < $pages){
            $next_prev .= "<span class=\"pagination_text\" >".$next_link."</span>";
        }
        $next_prev .=  "<span class=\"pagination_text\">".$last_link."</span> ";
    }
    else
    {
        $next_prev .=  "<a href=\"javascript:void(0);\" title=\"Next\"  onClick=\"Javascript:" . $jsfunc . "('" . ($curpage+1) . $qryparams . "')" . ";return false;\" class=\"next\"></a> ";

    }
    return $next_prev;
    }
}