<?php
namespace Cricket;

use Cricket\HttpClients\Httpable;
use Cricket\HttpClients\CurlHttpClient;
use Cricket\HttpClients\StreamHttpClient;

/**
 * Class Request
 * @package Cricket
 * @author Abhijeet K.
 */
class Request
{

  /**
   * @const string Version number.
   */
  const CLIENT_VERSION = '1.0.0';

  /**
   * @const string API version for requests
   */
  const API_VERSION = 'v1.0';

  /**
   * @const string API URL
   */
  const BASE_URL = 'http://stats.cricketcountry.com';

  /**
   * @var string The HTTP method for the request
   */
  private $method;

  /**
   * @var string The path for the request
   */
  private $path = '/restserver.php';

  /**
   * @var array The parameters for the request
   */
  private $params;

  /**
   * @var string The API version for the request
   */
  private $version;

  /**
   * @var string ETag sent with the request
   */
  private $etag;

  /**
   * @var Httpable HTTP client handler
   */
  private static $httpClientHandler;

  /**
   * @var int The number of calls that have been made to API.
   */
  public static $requestCount = 0;
  
  /**
   * @var string check Basic Auth default 0
   */
  private $chkbasicAuth = 1;
  
  /**
   * @var string Basic Auth Username
   */
  private static $username = 'ccnuggets';
  
  /**
   * @var string Basic Auth Password
   */
  private static $passwd = 'CCnugg3!s';

  /**
   * getPath - Returns the associated path.
   *
   * @return string
   */
  public function getPath()
  {
    return $this->path;
  }

  /**
   * getParameters - Returns the associated parameters.
   *
   * @return array
   */
  public function getParameters()
  {
    return $this->params;
  }

  /**
   * getMethod - Returns the associated method.
   *
   * @return string
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * getETag - Returns the ETag sent with the request.
   *
   * @return string
   */
  public function getETag()
  {
    return $this->etag;
  }

  /**
   * setHttpClientHandler - Returns an instance of the HTTP client
   * handler
   *
   * @param \Cricket\HttpClients\Httpable
   */
  public static function setHttpClientHandler(Httpable $handler)
  {
    static::$httpClientHandler = $handler;
  }

  /**
   * getHttpClientHandler - Returns an instance of the HTTP client
   * data handler
   *
   * @return Httpable
   */
  public static function getHttpClientHandler()
  {
    if (static::$httpClientHandler) {
      return static::$httpClientHandler;
    }
    ////return function_exists('curl_init') ? new CurlHttpClient() : new StreamHttpClient();
    $options = array( 'defaults' =>
    			array(
                        	// HTTP Basic auth header, username is api key, password is blank
                                'auth'    => array(self::$username, self::$passwd),
                        )
                );
    return new HttpClients\GuzzleHttpClient($options);
  }

  /**
   * Request - Returns a new request using the given session.  optional
   *   parameters hash will be sent with the request.  This object is
   *   immutable.
   *
   * @param string $method
   * @param string $path
   * @param array|null $parameters
   * @param string|null $version
   * @param string|null $etag
   */
  public function __construct(
    $method, $parameters = null, $version = null, $etag = null
  )
  {
    $this->method = $method;
    //$this->path = $path;
    if ($version) {
      $this->version = $version;
    } else {
      $this->version = static::API_VERSION;
    }

    $this->etag = $etag;

    $params = ($parameters ?: array());    
    $this->params = $params;
  }

  /**
   * Returns the base URL.
   *
   * @return string
   */
  protected function getRequestURL()
  {
    //return static::BASE_URL . '/' . $this->version . $this->path;
    return static::BASE_URL . $this->path;
  }

  /**
   * execute - Makes the request to Cricket and returns the result.
   *
   * @return Response
   *
   * @throws Exception
   * @throws RequestException
   */
  public function execute()
  {
    $url = $this->getRequestURL();
    $params = $this->getParameters();

    //echo "url: $url & params: "; print_r($params); die("\n");

    if ($this->method === "GET") {
      $url = self::appendParamsToUrl($url, $params);
      $params = array();
    }

    $connection = self::getHttpClientHandler();

    $connection->addRequestHeader('User-Agent', 'idc-' . self::CLIENT_VERSION);
    $connection->addRequestHeader('Accept-Encoding', '*'); // Support all available encodings.    

    // Should throw `Exception` exception on HTTP client error.
    // Don't catch to allow it to bubble up.
    $result = $connection->send(
            $url,
            $this->method,
            $params,
            array(
                'chkbasicAuth' => $this->chkbasicAuth,
                //'username' => $this->username,
                //'passwd' => $this->passwd,
                'username' => self::$username,
                'passwd' => self::$passwd,
            )
    );
    static::$requestCount++;
    //$etagHit = 304 == $connection->getResponseHttpStatusCode();
    $headers = $connection->getResponseHeaders();
    return $result;
  }

  /**
   * appendParamsToUrl - Gracefully appends params to the URL.
   *
   * @param string $url
   * @param array $params
   *
   * @return string
   */
  public static function appendParamsToUrl($url, $params = array())
  {
    if (!$params) {
      return $url;
    }

    if (strpos($url, '?') === false) {
      return $url . '?' . http_build_query($params, null, '&');
    }

    list($path, $query_string) = explode('?', $url, 2);
    parse_str($query_string, $query_array);

    // Favor params from the original URL over $params
    $params = array_merge($params, $query_array);

    return $path . '?' . http_build_query($params, null, '&');
  }

}
