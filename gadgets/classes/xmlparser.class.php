<?php
/**
 * @brief class is used for xmlparsing
 * @author Sachin Valanju
 * @version 1.0
 * @created 11-Nov-2010 5:09:31 PM
 * @last updated on 09-Mar-2011 13:14:00 PM
*/
class XMLParser  {

    /**
	 * raw xml
	 * xml string
	 * @var string
	*/
    public $rawXML;
    /**
	 * xml parser
	 * xml string
	 * @var string
	*/
    private $parser = null;
    /**
	 * value array returned by the xml parser
	 * @var array
	*/
    private $valueArray = array();
    /**
	 * key array returned by the xml parser
	 * @var array
	*/
    private $keyArray = array();
    /**
	 * arrays for dealing with duplicate keys
	 * @var array
	*/
    private $duplicateKeys = array();

    /**
	 * arrays for return data
	 * @var array
	*/
    private $output = array();
    /**
	 * status
	 * @var integer
	*/
    private $status;
	/**
     * xml parser
	 *  parses an xml into an array of values
	 *
     * @param string Xml file content
     * @return bool returns true or false
	 * @access public
	 * @uses parse() to parse an xml into array of values
	*/
    public function XMLParse($xml){
        $this->rawXML = $xml;
        $this->parser = xml_parser_create();
        return $this->parse();
    }

	/**
     * parse
	 *  parses an xml into an array of values
	 *
     * @return bool returns true or false
	 * @access private
	 * @uses findDuplicateKeys() to find duplicate keys withing the xml
	*/

    private function parse()
	{

        $parser = $this->parser;

        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); // Dont mess with my cAsE sEtTings
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);     // Dont bother with empty info
        if(!xml_parse_into_struct($parser, $this->rawXML, $this->valueArray, $this->keyArray)){
            $this->status = 'error: '.xml_error_string(xml_get_error_code($parser)).' at line '.xml_get_current_line_number($parser);
            return false;
        }
        xml_parser_free($parser);

        $this->findDuplicateKeys();

        // tmp array used for stacking
        $stack = array();
        $increment = 0;
		//print "<pre>"; print_r($this->valueArray);
        foreach($this->valueArray as $val) {
            if($val['type'] == "open") {
                //if array key is duplicate then send in increment
                if(array_key_exists($val['tag'], $this->duplicateKeys)){
                    array_push($stack, $this->duplicateKeys[$val['tag']]);
					$this->duplicateKeys[$val['tag']]++;
                }

                else{
                    // else send in tag
                    array_push($stack, $val['tag']);
                }
            } elseif($val['type'] == "close") {
                array_pop($stack);
                // reset the increment if they tag does not exists in the stack
                if(array_key_exists($val['tag'], $stack)){
                    $this->duplicateKeys[$val['tag']] = 0;
                }
            } elseif($val['type'] == "complete") {
                //if array key is duplicate then send in increment
                if(array_key_exists($val['tag'], $this->duplicateKeys)){
                    array_push($stack, $this->duplicateKeys[$val['tag']]);

                    $this->duplicateKeys[$val['tag']]++;
                }
                else{
                    // else send in tag
                    array_push($stack,  $val['tag']);

                }
                $this->setArrayValue($this->output, $stack, $val['value']);
                array_pop($stack);
            }
            $increment++;
        }

        $this->status = 'success: xml was parsed';
        return true;

    }

	/**
     * findDuplicateKeys
	 *  Find duplicate keys within an array of values
	 *
     * @return array returns an array of values
	 * @access private
	*/
    private function findDuplicateKeys(){

        for($i=0;$i < count($this->valueArray); $i++) {
            // duplicate keys are when two complete tags are side by side
            if($this->valueArray[$i]['type'] == "complete"){
                if( $i+1 < count($this->valueArray) ){
                    if($this->valueArray[$i+1]['tag'] == $this->valueArray[$i]['tag'] && $this->valueArray[$i+1]['type'] == "complete"){
                        $this->duplicateKeys[$this->valueArray[$i]['tag']] = 0;
                    }
                }
            }
            // also when a close tag is before an open tag and the tags are the same
            if($this->valueArray[$i]['type'] == "close"){
                if( $i+1 < count($this->valueArray) ){
                    if(    $this->valueArray[$i+1]['type'] == "open" && $this->valueArray[$i+1]['tag'] == $this->valueArray[$i]['tag'])
                        $this->duplicateKeys[$this->valueArray[$i]['tag']] = 0;
                }
            }

        }

    }

	/**
     * setArrayValue
	 *  Formatting an array of values
	 *
     * @return array returns an array of values
	 * @access private
	*/
    private function setArrayValue(&$array, $stack, $value){
        if ($stack) {
            $key = array_shift($stack);
            $this->setArrayValue($array[$key], $stack, $value);
            return $array;
        } else {
            $array = $value;
        }
    }

	/**
     * getOutput
	 *  Retrieve the output of parsing an xml
	 *
     * @return array returns an array of values
	 * @access private
	*/
     public function getOutput(){
        return $this->output;
    }
	/**
     * clearOutput
	 *  Clear the output of parsing an xml
	 *
	 * @access private
	*/

     public function clearOutput(){
        unset($this->output);
    }

	/**
     * getStatus
	 *  Retrieve the status of xml parsing
	 *
     * @return bool returns true or false
	 * @access private
	*/
    public function getStatus(){
        return $this->status;
    }
	/**
	 * createResultsetXML
     * Create an data manipulation response xml file
	 *
     * @param array $aTagValue array of values
	 * @return  string returns an response xml file content
	 * @access public
	*/
	public function createResultsetXML($aTagValue)
	{
		//@ob_end_clean();
		//header('Content-type: text/xml',true);
		$this->rawXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
							<response>";
		if ($aTagValue) {
			foreach ($aTagValue as $iKey =>$aData) {
				$this->rawXML.="<item>";
				foreach ($aData as $sTagName =>$sTagValue) {
					$this->rawXML.="<".$sTagName."><![CDATA[".$sTagValue."]]></".$sTagName.">";
				}
				$this->rawXML.="</item>";
			}
		}
		$this->rawXML.="</response>";
		$this->rawXML = trim($this->rawXML);
	}
	/**
	 * createResponseXML
     * Create an response xml file
	 *
     * @param string $sStatus status
     * @param string $sRemark Status remark
	 * @return  string returns an response xml file content
	 * @access public
	*/
	public function createResponseXML($sStatus, $sRemark)
	{
		@ob_end_clean();
		header('Content-type: text/xml',true);
		$this->rawXML="<?xml version=\"1.0\" encoding=\"UTF-8\"?><response>";
		$this->rawXML.="<status>".$sStatus."</status><remark>".$sRemark."</remark>";
		$this->rawXML.="</response>";
		$this->rawXML = trim($this->rawXML);
	}
	/**
	 * generateListXml
     * Create an response xml for the array of message boards
     * @param $aMBList array of MB list
	 * @return  string returns an response xml file content
	 * @access public
	*/
    public function generateListXml($aMBList,$sParentTag="mbdata") {
		if(is_array($aMBList)){
			$sResponseXml="";
			foreach($aMBList as $iKey =>$aData){
				$sResponseXml.="<".$sParentTag.">";
				if(is_array($aData)){
					foreach($aData as $sTagName =>$sTagValue){
						$sResponseXml.="<".$sTagName."><![CDATA[".$sTagValue."]]></".$sTagName.">";
					}
				}
				$sResponseXml.="</".$sParentTag.">";
			}
		}
		return $sResponseXml;
	}
}