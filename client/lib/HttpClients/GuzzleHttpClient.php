<?php

namespace Cricket\HttpClients;

use Cricket\Exception;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\AdapterException;
use GuzzleHttp\Exception\RequestException;

class GuzzleHttpClient implements Httpable {

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
   * @var \GuzzleHttp\Client The Guzzle client
   */
  protected static $guzzleClient;

  /**
   * @param \GuzzleHttp\Client|null The Guzzle client
   */
  public function __construct(Client $guzzleClient = null)
  {
	if($basicAuth['chkbasicAuth']==1)
    	{
		//$options = array('auth' => array($basicAuth['username'], $basicAuth['passwd'], 'Basic'));
    	}
		$options = array( 'defaults' => 
				array(
        				// HTTP Basic auth header, username is api key, password is blank
        				'auth'    => array('ccnuggets', 'CCnugg3!s'),
    				)
		);

    	self::$guzzleClient = $guzzleClient ?: new Client($options);
  }

  /**
   * The headers we want to send with the request
   *
   * @param string $key
   * @param string $value
   */
  public function addRequestHeader($key, $value)
  {
    	$this->requestHeaders[$key] = $value;
  }

  /**
   * The headers returned in the response
   *
   * @return array
   */
  public function getResponseHeaders()
  {
    return $this->responseHeaders;
  }

  /**
   * The HTTP status response code
   *
   * @return int
   */
  public function getResponseHttpStatusCode()
  {
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
  public function send($url, $method = 'GET', $parameters = array(), $basicAuth = array())
  {

    $options = array();
    if ($parameters) {
      $options = array('body' => $parameters);
    }
	//print_r($options); die;

	/*
    $options['verify'] = __DIR__ . '/certs/DigiCertHighAssuranceEVRootCA.pem';
	*/
    $request = self::$guzzleClient->createRequest($method, $url, $options);

    foreach($this->requestHeaders as $k => $v) {
      $request->setHeader($k, $v);
    }

    try {
      $rawResponse = self::$guzzleClient->send($request);
    } catch (RequestException $e) {
      if ($e->getPrevious() instanceof AdapterException) {
        throw new Exception($e->getMessage(), $e->getCode());
      }
      $rawResponse = $e->getResponse();
    }

    $this->responseHttpStatusCode = $rawResponse->getStatusCode();
    $this->responseHeaders = $rawResponse->getHeaders();

    return $rawResponse->getBody();
  }

}
