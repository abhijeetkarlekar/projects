<?php
/**
 *
 * Setup:
 *
    edit the singleton() metod
    and define the list of memcached servers in a 2-d array
    in the format
    array(
        array('192.168.0.1'=>'11211'),
        array('192.168.0.2'=>'11211'),
    );
 *
 *
 * Usage:
 *
<?php
More information can be obtained here:
http://www.danga.com/memcached/
http://www.php.net/memcache

*/
/** @ingroup classes
 *  This is the Classes group
 *  @{
*/
/**
 * @brief class Cache The class makes it easier to work with memcached servers and provides hints in the IDE like Zend Studio
 * @version 1
 *
 */

class Cache {
/**
 * Resources of the opend memcached connections
 * @var array [memcache objects]
 */
var $mc_servers = array();
/**
 * Quantity of servers used
 * @var int
 */
var $mc_servers_count;


var $servers = array(
	//	array('172.16.1.136'=>'11211'),
	//    array('127.0.0.1'=>'11311'),  // for live
	//	array('127.0.0.1'=>'11211')  // for live
        array('192.168.1.50'=>'11211')  // for live
    );


/**
 * Accepts the 2-d array with details of memcached servers
 *
 * @param array $servers
 */
function Cache(){

/*
    for ($i = 0, $n = count($this->servers); $i < $n; ++$i){
        ( $con = memcache_connect(key($this->servers[$i]), current($this->servers[$i])) )&&
            $this->mc_servers[] = $con;
    }
    $this->mc_servers_count = count($this->mc_servers);
    if (!$this->mc_servers_count){
        $this->mc_servers[0]=null;
    }
*/
}
/**
 * Returns the resource for the memcache connection
 *
 * @param string $key
 * @return object memcache
 */
function getMemcacheLink($key){
    if ( $this->mc_servers_count <2 ){
        //no servers choice
        return $this->mc_servers[0];
    }
    return $this->mc_servers[(crc32($key) & 0x7fffffff)%$this->mc_servers_count];
}

/**
 * Clear the cache
 *
 * @return void
 */
function flush() {
    $x = $this->mc_servers_count;
    for ($i = 0; $i < $x; ++$i){
        $a = $this->mc_servers[$i];
        $a->flush();
    }
}
function getNameSpace($key){
	$keyArr = explode("::",$key);
	$ns = $keyArr[0]."_ns";
	$ns = $this->cleanNS($ns);
	$ns_key = $this->getns($ns);
	
	if ($ns_key === false){
		#$this->set($ns, rand(1, 10000));	
		#$ns_key = $this->get($ns);
		#$ns_key = $ns_key['var'];
		$this->setns($ns, 1);	
		$ns_key = '1';
	}
	return $ns_key;
}
function cleanNS($ns){
	$ns = preg_replace('/[^a-zA-Z]/', '', $ns);
        $ns = preg_replace('/\s+/', '', $ns);
        $ns = trim($ns);
	return $ns;
}
function setNameSpace($key){
	$keyArr = explode("::",$key);
        $ns = $keyArr[0]."_ns";
	$ns = $this->cleanNS($ns);
	if (!$key) return false;
        $num = 0;
        foreach ($this->servers[$num] as $host => $port) {
                $memcache_obj = memcache_pconnect($host, $port);
        }
        if (!$memcache_obj) return false;
	$current_value = $memcache_obj->increment($ns, 1);
	return $current_value;
}
function getNSKey($key){
	$ns_key = $this->getNameSpace($key);
	$keyArr = explode("::",$key);	
	$keyArr[0] = $keyArr[0].$ns_key;
	$key = implode('::',$keyArr);
	return $key;
}
function getns($key) {
        #return false;
        #return $arr;
        if (!$key) return false;
        $num = 0;
        foreach ($this->servers[$num] as $host => $port) {
                $memcache_obj = memcache_pconnect($host, $port);
        }
        if (!$memcache_obj) return false;
        $ns = memcache_get($memcache_obj,$key);
	return $ns;
}
function setns($key, $var) {
        #return false;
        if (!$key) return false;
        $num = 0;
        foreach ($this->servers[$num] as $host => $port) {
                $memcache_obj = memcache_pconnect($host, $port);
        }
        if (!$memcache_obj) return false;
        return memcache_set($memcache_obj,$key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);
}/*
function get($key, $timeout="") {
	//return false;
	//return $arr;
	if (!$key) return false;
	$key = $this->getNSKey($key);
	#echo "key === $key <Br/>";
	//$key = gzdeflate($key,9);
	$num = 0;
	foreach ($this->servers[$num] as $host => $port) {
		$memcache_obj = memcache_pconnect($host, $port);
	}
	if (!$memcache_obj) return false;
	#$arr = unserialize(memcache_get($memcache_obj,$key));
	#$arr = gzinflate($arr);
	
    //$arr = gzinflate(memcache_get($memcache_obj,$key));
        //$arr = unserialize($arr);
	$arr = memcache_get($memcache_obj,$key);
	if(empty($timeout)){
		if(is_array($arr["var"])){
			if(sizeof($arr["var"]) > 0){
				return $arr["var"];
			}
			return false;
		}else{
			return $arr["var"];
		}
	}
	if((time()-$arr["time"])<$timeout){
		return $arr["var"];
	}else{
		return false;
	}
}*/

/**
 * Store the value in the memcache memory (overwrite if key exists)
 *
 * @param string $key
 * @param mix $var
 * @param bool $compress
 * @param int $expire (seconds before item expires)
 * @return bool
 */
/*
function set($key, $var, $compress=0, $expire=21600) {
	#die();
	#return false;
	if (!$key) return false;
	$key = $this->getNSKey($key);

	$key = gzdeflate($key,9);
	$num = 0;
	foreach ($this->servers[$num] as $host => $port) {
		$memcache_obj = memcache_pconnect($host, $port);
	}
	if (!$memcache_obj) return false;

	$arr["time"]=time();
	$arr["var"] = $var;

	//$var = gzdeflate(serialize($arr),9);
	return memcache_set($memcache_obj,$key, $arr, $compress?MEMCACHE_COMPRESSED:null, $expire);
}
*/
function flushAll() {
        foreach ($this->servers as $iK => $aData) {
                foreach ($aData as $host => $port) {
                        $memcache_obj = memcache_pconnect($host, $port);
                        $memcache_obj->flush();
                }
        }
}

function get($key, $timeout=30) {
	return false;
        if (!$key) return false;
        foreach ($this->servers as $iK => $aData) {
                foreach ($aData as $host => $port) {
                        $memcache_obj = memcache_pconnect($host, $port);
                }
        }
        if ($memcache_obj) {
                return $arr = memcache_get($memcache_obj,$key);
        }else {
                error_log("Memcache server down at ".date("Y-m-d H:i:s")."====\n");
                return false;
        }
}

function set($key, $var, $compress=0, $expire=0) {
	return false;
        if (!$key) return false;
        foreach ($this->servers as $iK => $aData) {
                foreach ($aData as $host => $port) {
                        $memcache_obj = memcache_pconnect($host, $port);
                        if ($memcache_obj) {
                                $iRes=memcache_set($memcache_obj,$key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);
                        }else {
                                error_log ("Memcache server down at ".date("Y-m-d H:i:s")."====\n");
                                return false;
                        }
                }
        }
        return $iRes;
}


function get_memcache($key, $timeout="") {
	if (!$key) return false;
        foreach ($this->servers as $iK => $aData) {
                foreach ($aData as $host => $port) {
                        $memcache_obj = memcache_pconnect($host, $port);
                }
        }
        if ($memcache_obj) {
                return $arr = memcache_get($memcache_obj,$key);
        }else {
                error_log("Memcache server down at ".date("Y-m-d H:i:s")."====\n");
                return false;
        }
}

/**
 * Store the value in the memcache memory (overwrite if key exists)
 *
 * @param string $key
 * @param mix $var
 * @param bool $compress
 * @param int $expire (seconds before item expires)
 * @return bool
 */
function set_memcache($key, $var, $compress=0, $expire=0) {
	if (!$key) return false;
        foreach ($this->servers as $iK => $aData) {
                foreach ($aData as $host => $port) {
                        $memcache_obj = memcache_pconnect($host, $port);
                        if ($memcache_obj) {
                                $iRes=memcache_set($memcache_obj,$key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);
                        }else {
                                error_log ("Memcache server down at ".date("Y-m-d H:i:s")."====\n");
                                return false;
                        }
                }
        }
        return $iRes;
}
/**
 * Set the value in memcache if the value does not exist; returns FALSE if value exists
 *
 * @param sting $key
 * @param mix $var
 * @param bool $compress
 * @param int $expire
 * @return bool
 */
function add($key, $var, $compress=0, $expire=0) {
	$a = $this->getMemcacheLink($key);
    return $a->add($key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);
}
function getMemcacheKeys(){
	$num = 0;
	foreach ($this->servers[$num] as $host => $port) {
		$memcache_obj = memcache_pconnect($host, $port);
	}
	$memcacheKeyArr = Array();
	$allSlabs = $memcache_obj->getExtendedStats('slabs');
	foreach($allSlabs as $server => $slabs) {
		foreach($slabs AS $slabId => $slabMeta) {
			$cdump = $memcache_obj->getExtendedStats('cachedump',(int)$slabId);
				foreach($cdump AS $keys => $arrVal) {
					foreach($arrVal AS $k => $v) {
						$memcacheKeyArr[] = $k;
					}
				}
		}
	}
	return $memcacheKeyArr;
}
function searchDeleteKeys($searchkey){
	$cnt = sizeof($this->getMemcacheKeys());
	foreach($this->getMemcacheKeys() as $key => $memcacheKey){		
		$this->delete($memcacheKey);
	}
	return true;
}
function isKeySet($searchKey){
	foreach($this->getMemcacheKeys() as $memcacheKey){
		$memcacheKey = gzinflate($memcacheKey);
		if(strpos($memcacheKey,$searchkey) !== false){
			return '1';
		}
	}
	return '0';
}
/**
 * Replace an existing value
 *
 * @param string $key
 * @param mix $var
 * @param bool $compress
 * @param int $expire
 * @return bool
 */
function replace($key, $var, $compress=0, $expire=0) {
   	return $a->replace($key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);
}
/**
 * Delete a record or set a timeout
 *
 * @param string $key
 * @param int $timeout
 * @return bool
 */
function delete($key, $timeout=0) {
	//error_log("KEY=>".$key);
	if (!$key) return false;
	$num = 0;
	foreach ($this->servers[$num] as $host => $port) {
		$memcache_obj = memcache_pconnect($host, $port);
	}
	if (!$memcache_obj) return false;
	$memcache_obj->delete($key,$timeout);
	$memcache_obj->delete($key);
}
/**
 * Increment an existing integer value
 *
 * @param string $key
 * @param mix $value
 * @return bool
 */
function increment($key, $value=1) {
	$a = $this->getMemcacheLink($key);
	print_r($a);die();
    return $a->increment($key, $value);
}

/**
 * Decrement an existing value
 *
 * @param string $key
 * @param mix $value
 * @return bool
 */
function decrement($key, $value=1) {
	$a = $this->getMemcacheLink($key);
    return $a->decrement($key, $value);
}

function gettest($key, $timeout="") {
        #return false;
        #return $arr;
        if (!$key) return false;
        $key = $this->getNSKey($key);
        #echo "key === $key <Br/>";
        $key = gzdeflate($key,9);
        $num = 0;
        foreach ($this->servers[$num] as $host => $port) {
                $memcache_obj = memcache_pconnect($host, $port);
        }
        if (!$memcache_obj) return false;
        /////$arr = unserialize(memcache_get($memcache_obj,$key));
        //////$arr = gzinflate($arr);
        $arr1 = gzinflate(memcache_get($memcache_obj,$key));
        $arr = unserialize($arr1);

	if(empty($timeout)){
                if(is_array($arr["var"])){
                        if(sizeof($arr["var"]) > 0){
                                return $arr["var"];
                        }
                        return false;
                }else{
                        return $arr["var"];
                }
        }
        if((time()-$arr["time"])<$timeout){
                return $arr["var"];
        }else{
                return false;
        }
}

/**
 * Store the value in the memcache memory (overwrite if key exists)
 *
 * @param string $key
 * @param mix $var
 * @param bool $compress
 * @param int $expire (seconds before item expires)
 * @return bool
 */
function settest($key, $var, $compress=0, $expire=0) {
        #die();
        #return false;
        if (!$key) return false;
        $key = $this->getNSKey($key);

        $key = gzdeflate($key,9);
        $num = 0;
        foreach ($this->servers[$num] as $host => $port) {
                $memcache_obj = memcache_pconnect($host, $port);
        }
        if (!$memcache_obj) return false;

        $arr["time"]=time();
        $arr["var"] = $var;

        $var = gzdeflate(serialize($arr),9);
        return memcache_set($memcache_obj,$key, $var, $compress?MEMCACHE_COMPRESSED:null, $expire);
}



//class end
}
