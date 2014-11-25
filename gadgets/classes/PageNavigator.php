<?php
        /**
        * @brief class is used for pagination.
        * @author Rajesh.
        * @date 23-12-2009.
        * @version 1.0
        */
        class PageNavigator{
                //below 7 variable expects integer values.

                private $pagename;
                private $totalpages;
                private $recordsperpage;
                private $startpagesshown;
                private $currentstartpage;
                private $currentendpage;
                private $currentpage;
        private $maxpagesshown;
        private $totalrecords;
                //below 4 variable expects string values.
                //next and previous inactive
                private $spannextinactive;
                private $spanpreviousinactive;

                //first and last inactive
                private $firstinactivespan;
                private $lastinactivespan;

                //must match $_GET['offset'] in calling page
                private $firstparamname = OFFSET;
                private $resultStr = RESULTSTRING;

                private $params;

                //css class names
                private $divwrappername = "pgB ar blk";
                private $divwrapperstyle="";
                private $ulstyle="fr";
                private $page_links = "page_links";
                private $activespanname = "active";
                private $inactivespanname = "fl dactive";
        //text for navigation
        private $stractive = "active";
        private $strinactive = "txt_def";
                private $strfirst = "";
                private $strnext = "";
                private $strprevious = "";
                private $strlast = "";

                //for error reporting
                private $errorstring;
                public function __construct($pagename, $totalrecords, $recordsperpage,$recordoffset, $startpagesshown = 4, $maxpagesshown=7,$params = ""){

            $this->pagename = $pagename;
            $this->totalrecords = $totalrecords;
                        $this->recordsperpage = $recordsperpage;
                        $this->startpagesshown = $startpagesshown;
                        $this->maxpagesshown = $maxpagesshown;
                        $this->params = $params;
                        //check recordoffset a multiple of recordsperpage
                        $this->boolCheckRecordOffset($recordoffset, $recordsperpage) or die($this->errorstring);
                        $this->intSetTotalPages($totalrecords, $recordsperpage);
                        $this->intCalculateCurrentPage($recordoffset, $recordsperpage);
                        $this->strCreateInactiveSpans();
                        $this->intCalculateCurrentEndPage();
                        $this->intCalculateCurrentStartPage();
                        $this->strSetFirstParamName(OFFSET);
                }
                /**
                * @note function used to check record offset.
                * @param integer recordoffset.
                * @param integer recordsperpage.
                * @pre recordoffset and recordsperpage must be non-empty integer.
                * @post boolean true/false.
                * return boolean.
                */
                private function boolCheckRecordOffset($recordoffset, $recordsperpage){
                        if($recordoffset%$recordsperpage != 0){
                                $this->errorstring = "Error - not a multiple of records per page.";
                                return  false;
                        }
                        return true;
                }
                /**
                * @note function used to get total number of pages.
                * @param integer totalrecords.
                * @param integer recordsperpage.
                * @pre totalrecords and recordsperpage must be non-empty integer.
                * @post integer total pages.
                * return integer.
                */
                private function intSetTotalPages($totalrecords, $recordsperpage){
                        $this->totalpages = ceil($totalrecords/$recordsperpage);
                }
                /**
                * @note function used to get current page value.
                * @param integer recordoffset.
                * @param integer recordsperpage.
                * @pre recordoffset and recordsperpage must be non-empty integer.
                * @post integer current page.
                * return integer.
                */
                private function intCalculateCurrentPage($recordoffset, $recordsperpage){
                        $this->currentpage = $recordoffset/$recordsperpage;
                        $this->currentpage = $this->currentpage == 0?1:$this->currentpage;
                }
                /**
                * @note function used to get html paging links.
                * @pre not required.
                * @post assigned values to class variables.
                */
                private function strCreateInactiveSpans(){
                        $this->spannextinactive = "\n";
                        $this->lastinactivespan = "$this->strlast\n";
                        $this->spanpreviousinactive = "$this->strprevious\n";
                        $this->firstinactivespan = "$this->strfirst\n";
                }
                /**
                * @note function used to calculate the current start page.
                * @pre not required.
                * @post assigned values to class variables.
                */
                private function intCalculateCurrentStartPage(){

                        if($this->currentendpage < $this->maxpagesshown){
                                $this->currentstartpage = 1;
                        }elseif($this->currentendpage == $this->totalpages){

                                if($this->currentpage < $this->totalpages){
                                        //condition used to check reverse pagination.
                                        $temp = $this->currentendpage-$this->currentpage; // used to get the page difference.
                                        $temp = $this->startpagesshown+$temp;

                                        if($temp <= $this->maxpagesshown){
                                                $this->currentendpage = $this->currentendpage+1;
                                                $this->currentstartpage = $this->currentendpage-$temp;
                                        }else{
                                                $this->currentstartpage = $this->currentendpage-$this->maxpagesshown;
                                        }

                                }else{
                                        $this->currentendpage = $this->currentendpage+1;
                                        $this->currentstartpage = $this->currentendpage-$this->startpagesshown;
                                }

                        }else{
                                $this->currentstartpage = $this->currentendpage-$this->maxpagesshown;
                        }

                        $this->currentstartpage = $this->currentstartpage == 0 ? 1 : $this->currentstartpage;
                }
                /**
                * @note function used to calculate the current end page.
                * @pre not required.
                * @post assigned values to class variables.
                */
                private function intCalculateCurrentEndPage(){
                        if($this->totalpages <= $this->startpagesshown){
                                $this->currentendpage =  $this->totalpages+1;
                        }else{
                                $this->currentendpage = $this->currentpage + $this->startpagesshown;
                                if($this->currentendpage > $this->totalpages){
                                        $this->currentendpage = $this->totalpages;
                                }
                        }
                }
                /**
                * @param used to set querystring first parameter.
                */
                private function strSetFirstParamName($offset){
                        return $this->firstparamname = $offset;
                }
                /**
                * @note function used to create the hyperlink.
                * @param integer offeset.
                * @param string strdisplay.
                * @pre offset must be non-empty integer. And strdisplay must be non-empty string.
                * @post string html hyperlink.
                * return string.
                */
        private function createLink($offset,$strdisplay){
                        $strlink = "<a href=\"$this->pagename?$this->firstparamname=";
                        $strlink .= $offset;
            $strlink .= "$this->params\">$strdisplay</a>\n";
                        return $strlink;
                }
                /**
                * @note function used to display the page number.
                * @pre not required,
                * @post string html.
                * return string.
                */
                private function getPageNumberDisplay(){
                        $str = "<div class=\"$this->pagedisplaydivname\">\nPage ";
                        $str .= $this->currentpage + 1;
                        $str .= " of $this->totalpages";
                        $str .= "</div>\n";
                        return $str;
                }
                /**
                * @note function used to get the html navigator.
                * @pre not required.
                * @post string html encoded navigator.
                * return string.
                */
                public function getNavigator(){

                        $strnavigator = "<div class=\"$this->divwrappername\"><ul class=\"$this->ulstyle\">\n";
            //$strnavigator .= "<li class=\"$this->page_links\">$this->resultStr <strong>$this->totalrecords</strong>&nbsp;&nbsp;</li>\n";
                        //output moveprevious button
            if($this->currentpage == 1){
                //$strnavigator .= "<li class=\"fl pre\">\n";
                //$strnavigator .=  "<a href='javascript:undefined;'>".$this->spanpreviousinactive."</a>\n";
                //$strnavigator .= "</li>\n";
                        }else{
                $strnavigator .= "<li class=\"\">\n";
                                $img_path = "Previous";
                                $strnavigator .= $this->createLink($this->currentpage-1, $img_path)."\n";
                $strnavigator .= "</li>\n";
                        }
                        //output movenext button
                        //echo "$this->currentendpage > $this->maxpagesshown && $this->currentpage <= $this->totalpages == ".$this->currentendpage-1;

                        if($this->currentpage == 1){
                //$strnavigator .= "<li class=\"$this->strinactive\">\n";
                                //$strnavigator .= "<a href='javascript:undefined;'>".$this->firstinactivespan."</a>\n";
                //$strnavigator .= "</li>\n";
                        }elseif($this->currentpage == $this->maxpagesshown){
                                if(!empty($this->strfirst)){
                                        $strnavigator .= "<li class=\"$this->stractive\">\n";
                                        $strnavigator .= $this->createLink(1, $this->strfirst)."\n";
                                        $strnavigator .= "</li>\n";
                                }
                        }elseif(($this->currentendpage-1) > $this->maxpagesshown && $this->currentpage <= $this->totalpages){
                                if(!empty($this->strfirst)){
                        $strnavigator .= "<li class=\"$this->stractive\">\n";
                                        $strnavigator .= $this->createLink(1, $this->strfirst)."\n";
                                $strnavigator .= "</li>\n";
                                }
                        }

                        //loop through displayed pages from $currentstart
            for($i = $this->currentstartpage; $i < $this->currentendpage; $i++){
                                //make current page inactive
                                if($i == $this->currentpage){
                                        $strnavigator .= "<li class=\"$this->activespanname\">";
                                        $strnavigator .= $this->createLink($i, $i)."\n";
                                        //$strnavigator .= $i."\n";
                                        $strnavigator .= "</li>\n";
                                }else{
                                        $strnavigator .= "<li class=\"$this->inactivespanname\">";
                                        $strnavigator .= $this->createLink($i, $i)."\n";
                                        $strnavigator .= "</li>\n";
                                }
                        }


                        //move last button

                        if($this->currentpage == $this->totalpages){
                $strnavigator .= "<li class=\"$this->strinactive\">\n";
                                $strnavigator .= "<a href='javascript:undefined;'>".$this->lastinactivespan."</a>\n";
                                $strnavigator .= "</li>\n";
                        }elseif(($this->totalpages-$this->currentstartpage) >= $this->maxpagesshown){
                                if(!empty($this->strlast)){
                                        $strnavigator .= "<li class=\"$this->stractive\">\n";
                                        $strnavigator .= $this->createLink($this->totalpages , $this->strlast)."\n";
                                        $strnavigator .= "</li>\n";
                                }
                        }

                        //next button
                        if($this->currentpage == $this->totalpages){
                //$strnavigator .= "<li class=\" nxt\">\n";
                                //$strnavigator .= "<a href='javascript:undefined;'>".$this->spannextinactive."</a>\n";
                                //$strnavigator .= "</li>\n";
                        }else{
                $strnavigator .= "<li class=\"\">\n";
                                $img_path = "Next";
                                $strnavigator .= $this->createLink($this->currentpage + 1, $img_path)."\n";
                                $strnavigator .= "</li>\n";
                        }

                        $strnavigator .= "</ul><div class=\"cl\"></div></div>\n";
                        //$strnavigator .= $this->getPageNumberDisplay();
                        return $strnavigator;
                }
        }