<?php
/**
 * @brief Contains all the properties and methods related to campus discussion api actions
 * @version 1.0
 */
//require_once("./include/config.php");
/**
 * include  xmlparser class
*/
require_once(CLASSPATH.'xmlparser.class.php');
/**
 * include  xmlparser class
*/
require_once(CLASSPATH.'utility.class.php');

class campus_discussion {
	/**
	 * @note function is used  to add campus discussion thread
	 * @pre  aParameters is array of details
	 * values  -title,turl,cid
	 * @post return status xml response
	 *
	 *
	 * @param aParameters
	 */
	public function addCampusDiscussion($aParameters){
		$oXmlparser = new XMLParser;
		$sTitle=$aParameters['title'];
		//settype($sTitle,"integer");
		$sUrl=$aParameters['turl'];
		$iCategory=$aParameters['cid'];
		$iServiceId=$aParameters['sid'];

		if(!isset($iCategory)) $iCategory=0;
		if(strlen($sTitle)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Title not specified");
		}else if(strlen($sUrl)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Url not specified");
		}
		if(count($aXmlTag)==0){
			//get the topic id
			$sContent="action=addMBoard&title=".$sTitle."&turl=".$sUrl."&cid=".$iCategory."&sid=".$iServiceId;
			$sResultXML =utility::curlaccess($sContent,MB_API_HOST);

			$oXmlparser->XMLParse($sResultXML);
			$aResultXML =$oXmlparser->getOutput();
			$oXmlparser->clearOutput();
			if(is_array($aResultXML) && count($aResultXML)>0){
				if($aResultXML['response']['status']==1){
					$iTId=$aResultXML['response']['tid'];
					$aXmlTag=Array("status"=>"1","remark"=>"Campus Discussion added successfully","tid"=>$iTId);
				}else {
					$aXmlTag=Array("status"=>"0","remark"=>"Problem adding Campus Discussion");
				}

			}else {
				$aXmlTag=Array("status"=>"0","remark"=>"Problem adding Campus Discussion");
			}
		}
		return $aXmlTag;
	}
	/**
	 * @note function is used  to add campus discussion reply
	 * @pre  aParameters is array of details
	 * values  -tid,username,profile,subject,content,parentid,password,session_id,uid
	 * @post return status xml response
	 *
	 *
	 * @param aParameters
	 */
	public function addReply($aParameters){
		$oXmlparser = new XMLParser;
		$iTId = $aParameters['tid'];
		$sUsername = $aParameters['username'];
		$sProfile = $aParameters['profile'];
		$sSubject = $aParameters['subject'];
		$sContent= $aParameters['content'];
		$iParentId=$aParameters['parentid'];
		$iUserId=$aParameters['uid'];

		if(strlen($iTId)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Message Board id not specified");
		}else if(strlen($sContent)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Reply content not specified");
		}else if(strlen($iUserId)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"User id not specified");
		}

		if(count($aXmlTag)==0){

			 $sContent="action=addReply&tid=".$iTId."&uid=".$iUserId."&profile=".$sProfile."&subject=".$sSubject."&content=".$sContent."&parentid=".$iParentId;

			// error_log("TETETETETE---".$sContent);
			$sResultXML =utility::curlaccess($sContent,MB_API_HOST);
			$oXmlparser->XMLParse($sResultXML);
			$aResultXML =$oXmlparser->getOutput();
			$oXmlparser->clearOutput();
			$iReplyId=-2;
			if(is_array($aResultXML) && count($aResultXML)>0){
				$iReplyId=$aResultXML['response']['replyid'];
			}
			if($iReplyId !=-2){
				$aXmlTag=Array("status"=>"1","remark"=>"Message Board reply added successfully","tid"=>$iTId,"replyid"=>$iReplyId);
			}else {
				$aXmlTag=Array("status"=>"0","remark"=>"Problem adding message board reply");
			}
		}
		return $aXmlTag;
	}



	/**
	 * @note function is used  to get discussion replies
	 * @pre  aParameters is array of details
	 * values  -tid,username,type,replyid
	 * @post return status xml response
	 *
	 *
	 * @param aParameters
	 */
	public function getReply($aParameters){
		$oXmlparser = new XMLParser;
		$iTId=$aParameters['tid'];
		$iRecPerPage=$aParameters['rowcnt'];
		$iStart=$aParameters['start'];
		if(strlen($iTId)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Campus id not specified");
		}
		if(count($aXmlTag)==0){
			//get the topic replies
			$sContent="action=getReply&tid=".$iTId."&rowcnt=".$iRecPerPage."&start=".$iStart;
			$sResultXML = utility::curlaccess($sContent,MB_API_HOST);
			$sResultXML = implode("<br/>",explode("<br />\n",$sResultXML));
			$sResultXML = implode("<br/>",explode("\n",$sResultXML));
			return $sResultXML;
		}
		return $aXmlTag;
	}
	/**
	 * @note function is used  to get the count of all the replies on Campus discusssion thread
	 * @pre  aParameters is array of details
	 * values  -serviceid,rowcnt,cid
	 * @post return status xml response
	 *
	 *
	 * @param aParameters
	 */
	public function MBReplyCnt($aParameters){
		$oXmlparser = new XMLParser;
		$iTid=$aParameters['tid'];
		$aXmlTag =Array();
		if(strlen($iTid)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Mesaage board id not specified");
		}
		if(count($aXmlTag)==0){
			if($iTid>0){
				$sContent="action=MBReplyCnt&tid=".$iTid;
				$sResultXML =utility::curlaccess($sContent,MB_API_HOST);
				$oXmlparser->XMLParse($sResultXML);
				$aResultXML =$oXmlparser->getOutput();
				$oXmlparser->clearOutput();
				if(is_array($aResultXML) && count($aResultXML)>0){
					//generate xml and print
					$aXmlTag=Array("status"=>"1","replycnt"=>$aResultXML['response']['replycnt']);
				}else {
					$aXmlTag=Array("status"=>"1","replycnt"=>0);
				}
			}else {
				$aXmlTag=Array("status"=>"0","remark"=>"There exists no mesaage board with the id specified");
			}
		}
		return $aXmlTag;
	}
	/**
	 * @note function is used  to get the count of all the replies on array of Campus discusssion threads
	 * @pre  aParameters is array of details
	 * values  -serviceid,rowcnt,cid
	 * @post return status xml response
	 *
	 *
	 * @param aParameters
	 */
	public function getMulThreadParentReplyCnt($aParameters){
		$oXmlparser = new XMLParser;
		if(isset($aParameters['title']) && strlen($aParameters['title'])>0){
			$sTitle=$aParameters['title'];
		}
		$iCategory=-1;
		if(isset($aParameters['cid']) && $aParameters['cid']>-1){
			$iCategory=$aParameters['cid'];
		}
		$iServiceId=-1;
		if(isset($aParameters['sid']) && $aParameters['sid']>-1){
			$iServiceId=$aParameters['sid'];
		}
		$aXmlTag =Array();
		if(strlen($sTitle)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Mesaage board id not specified");
		}
		if(count($aXmlTag)==0){
			if($sTitle>0){
			 $sContent="action=getMulThreadParentReplyCnt&title=".$sTitle."&cid=".$iCategory."&serviceid=".$iServiceId;
				//print MB_API_HOST."?".$sContent;

				$sResultXML =utility::curlaccess($sContent,MB_API_HOST);
				$oXmlparser->XMLParse($sResultXML);
				$aResultXML =$oXmlparser->getOutput();
				$oXmlparser->clearOutput();

				if(is_array($aResultXML) && count($aResultXML['response'])>0){
					$aReplyData=Array();

					//print_r($aResultXML['response']);
					//error_log("===============".$aResultXML['response']);
					foreach($aResultXML['response'] as $iK =>$aData){
						//error_log("ARTICLEID===============".$aData['title']."------------------".$aData['cnt']."---".$aData['category_id']."+++++".$aData['serviceid']);

						$aReplyData[$aData['title']][$aData['category_id']][$aData['serviceid']]=$aData['cnt'];
					}
					//generate xml and print
					$aXmlTag=Array("status"=>"1","data"=>$aReplyData);
				}else {
					$aXmlTag=Array("status"=>"1","data"=>array());
				}
			}else {
				$aXmlTag=Array("status"=>"0","remark"=>"There exists no mesaage board with the id specified");
			}
		}
		return $aXmlTag;
	}

	/**
	 * @note function is used  to mark reply as abused
	 * @pre  aParameters is array of details
	 * values  -replyid
	 * @post return status xml response
	 *
	 *
	 * @param aParameters
	 */
	public function abuseReply($aParameters){
		$oXmlparser = new XMLParser;
		$iReplyid=$aParameters['replyid'];
		$iUserId=$aParameters['uid'];

		$aXmlTag =Array();
		if(strlen($iReplyid)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Mesaage board reply id not specified");
		}
		if(count($aXmlTag)==0){
			if($iReplyid>0){
				$sContent="action=abuseReply&replyid=".$iReplyid."&uid=".$iUserId;
				$sResultXML =utility::curlaccess($sContent,MB_API_HOST);
				$oXmlparser->XMLParse($sResultXML);
				$aResultXML =$oXmlparser->getOutput();
				$oXmlparser->clearOutput();
				if(is_array($aResultXML) && count($aResultXML)>0){
					//generate xml and print
					$aXmlTag=Array("status"=>"1","rabuseid"=>$aResultXML['response']['rabuseid']);
				}else {
					$aXmlTag=Array("status"=>"1","rabuseid"=>0);
				}
			}else {
				$aXmlTag=Array("status"=>"0","remark"=>"There exists no mesaage board reply with the id specified");
			}
		}
		return $aXmlTag;
	}
	/**
	 * @note function is used  to get campus discussion thread details
	 * @pre  aParameters is array of details
	 * values  -title,turl,cid
	 * @post return status xml response
	 *
	 *
	 * @param aParameters
	 */
	public function getMBDetails($aParameters){
		$oXmlparser = new XMLParser;
		$sTitle=$aParameters['title'];
		$iCategory=$aParameters['cid'];
		$iServiceId=$aParameters['sid'];
		$aXmlTag =Array();
		if(strlen($sTitle)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"Title not specified");
		}else if(strlen($iServiceId)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"service id not specified");
		}else if(strlen($iCategory)==0){
			$aXmlTag=Array("status"=>"0","remark"=>"category id not specified");
		}
		if(count($aXmlTag)==0){
			//get the topic id
			$sContent="action=MBDetails&title=".$sTitle."&cid=".$iCategory."&sid=".$iServiceId;
			$sResultXML =utility::curlaccess($sContent,MB_API_HOST);
			$oXmlparser->XMLParse($sResultXML);
			$aResultXML =$oXmlparser->getOutput();
			$oXmlparser->clearOutput();
			if(is_array($aResultXML['response']) && count($aResultXML['response'])>0){
				$aXmlTag=$aResultXML['response'];
			}
		}
		return $aXmlTag;
	}


}