<?php

namespace Cricket\HttpClients;

use Cricket\Exception;

/**
 * Class CurlHttpClient
 * @package Cricket
 * @author  Abhijeet K
 */
class CurlHttpClient implements Httpable {

    /**
     * @var array The headers to be sent with the request
     */
    protected $requestHeaders = array();

    /**
     * @var array The headers received from the response
     */
    protected $responseHeaders = array();

    /**
     * @var int The HTTP status code returned from the server
     */
    protected $responseHttpStatusCode = 0;

    /**
     * @var string The client error message
     */
    protected $curlErrorMessage = '';

    /**
     * @var int The curl client error code
     */
    protected $curlErrorCode = 0;

    /**
     * @var string|boolean The raw response from the server
     */
    protected $rawResponse;

    /**
     * @var Curl Procedural curl as object
     */
    protected static $Curl;

    /**
     * @var boolean If IPv6 should be disabled
     */
    protected static $disableIPv6;

    /**
     * @const Curl Version which is unaffected by the proxy header length error.
     */
    const CURL_PROXY_QUIRK_VER = 0x071E00;

    /**
     * @const "Connection Established" header text
     */
    const CONNECTION_ESTABLISHED = "HTTP/1.0 200 Connection established\r\n\r\n";

    /**
     * @param Curl|null Procedural curl as object
     */
    public function __construct(Curl $Curl = null) {
        self::$Curl = $Curl ? : new Curl();
        self::$disableIPv6 = self::$disableIPv6 ? : false;
    }

    /**
     * Disable IPv6 resolution
     */
    public function disableIPv6() {
        self::$disableIPv6 = true;
    }

    /**
     * The headers we want to send with the request
     *
     * @param string $key
     * @param string $value
     */
    public function addRequestHeader($key, $value) {
        $this->requestHeaders[$key] = $value;
    }

    /**
     * The headers returned in the response
     *
     * @return array
     */
    public function getResponseHeaders() {
        return $this->responseHeaders;
    }

    /**
     * The HTTP status response code
     *
     * @return int
     */
    public function getResponseHttpStatusCode() {
        return $this->responseHttpStatusCode;
    }

    /**
     * Sends a request to the server
     *
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @param array  $parameters The key value pairs to be sent in the body
     *
     * @return string Raw response from the server
     *
     * @throws \Cricket\Exception
     */
    public function send($url, $method = 'GET', $parameters = array(), $basicAuth = array()) {
        
        $this->openConnection($url, $method, $parameters, $basicAuth);
        $this->tryToSendRequest();

        if ($this->curlErrorCode) {
            throw new Exception($this->curlErrorMessage, $this->curlErrorCode);
        }

        // Separate the raw headers from the raw body
        list($rawHeaders, $rawBody) = $this->extractResponseHeadersAndBody();

        //print_r($rawHeaders);
        //print_r($rawBody);
        //die("\n");

        $this->responseHeaders = self::headersToArray($rawHeaders);

        $this->closeConnection();

        return $rawBody;
    }

    /**
     * Opens a new curl connection
     *
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @param array  $parameters The key value pairs to be sent in the body
     */
    public function openConnection($url, $method = 'GET', $parameters = array(), $basicAuth = array()) {
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true, // Follow 301 redirects
            CURLOPT_HEADER => true, // Enable header processing
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
                //CURLOPT_CAINFO         => __DIR__ . '/certs/DigiCertHighAssuranceEVRootCA.pem',            
        );
        
        if($basicAuth['chkbasicAuth']==1)
        {
            $options[CURLOPT_USERPWD] = $basicAuth['username'].":".$basicAuth['passwd'];
            //$options[CURLOPT_HTTPAUTH] = 'CURLAUTH_BASIC';
        }

        if ($method !== "GET") {
            $options[CURLOPT_POSTFIELDS] = $parameters;
        }
        if ($method === 'DELETE' || $method === 'PUT') {
            $options[CURLOPT_CUSTOMREQUEST] = $method;
        }

        if (!empty($this->requestHeaders)) {
            $options[CURLOPT_HTTPHEADER] = $this->compileRequestHeaders();
        }

        if (self::$disableIPv6) {
            $options[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        }
        
        self::$Curl->init();
        self::$Curl->setopt_array($options);
    }

    /**
     * Closes an existing curl connection
     */
    public function closeConnection() {
        self::$Curl->close();
    }

    /**
     * Try to send the request
     */
    public function tryToSendRequest() {
        $this->sendRequest();
        $this->curlErrorMessage = self::$Curl->error();
        $this->curlErrorCode = self::$Curl->errno();
        $this->responseHttpStatusCode = self::$Curl->getinfo(CURLINFO_HTTP_CODE);
    }

    /**
     * Send the request and get the raw response from curl
     */
    public function sendRequest() {
        $this->rawResponse = self::$Curl->exec();
    }

    /**
     * Compiles the request headers into a curl-friendly format
     *
     * @return array
     */
    public function compileRequestHeaders() {
        $return = array();

        foreach ($this->requestHeaders as $key => $value) {
            $return[] = $key . ': ' . $value;
        }

        return $return;
    }

    /**
     * Extracts the headers and the body into a two-part array
     *
     * @return array
     */
    public function extractResponseHeadersAndBody() {
        $headerSize = self::getHeaderSize();

        $rawHeaders = mb_substr($this->rawResponse, 0, $headerSize);
        $rawBody = mb_substr($this->rawResponse, $headerSize);

        return array(trim($rawHeaders), trim($rawBody));
    }

    /**
     * Converts raw header responses into an array
     *
     * @param string $rawHeaders
     *
     * @return array
     */
    public static function headersToArray($rawHeaders) {
        $headers = array();

        // Normalize line breaks
        $rawHeaders = str_replace("\r\n", "\n", $rawHeaders);

        // There will be multiple headers if a 301 was followed
        // or a proxy was followed, etc
        $headerCollection = explode("\n\n", trim($rawHeaders));
        // We just want the last response (at the end)
        $rawHeader = array_pop($headerCollection);

        $headerComponents = explode("\n", $rawHeader);
        foreach ($headerComponents as $line) {
            if (strpos($line, ': ') === false) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    /**
     * Return proper header size
     *
     * @return integer
     */
    private function getHeaderSize() {
        $headerSize = self::$Curl->getinfo(CURLINFO_HEADER_SIZE);
        // This corrects a Curl bug where header size does not account
        // for additional Proxy headers.
        if (self::needsCurlProxyFix()) {
            // Additional way to calculate the request body size.
            if (preg_match('/Content-Length: (\d+)/', $this->rawResponse, $m)) {
                $headerSize = mb_strlen($this->rawResponse) - $m[1];
            } elseif (stripos($this->rawResponse, self::CONNECTION_ESTABLISHED) !== false) {
                $headerSize += mb_strlen(self::CONNECTION_ESTABLISHED);
            }
        }

        return $headerSize;
    }

    /**
     * Detect versions of Curl which report incorrect header lengths when
     * using Proxies.
     *
     * @return boolean
     */
    private static function needsCurlProxyFix() {
        $ver = self::$Curl->version();
        $version = $ver['version_number'];

        return $version < self::CURL_PROXY_QUIRK_VER;
    }

}
