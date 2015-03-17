<?php

namespace Cricket\HttpClients;

/**
 * Interface Httpable
 * @package Cricket
 * @author  Abhijeet K
 */
interface Httpable
{

  /**
   * The headers we want to send with the request
   *
   * @param string $key
   * @param string $value
   */
  public function addRequestHeader($key, $value);

  /**
   * The headers returned in the response
   *
   * @return array
   */
  public function getResponseHeaders();

  /**
   * The HTTP status response code
   *
   * @return int
   */
  public function getResponseHttpStatusCode();

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
  public function send($url, $method = 'GET', $parameters = array());

}
