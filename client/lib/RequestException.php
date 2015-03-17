<?php

namespace Cricket;

/**
 * Class RequestException
 * @package Cricket
 * @author Abhijeet K
 */
class RequestException extends Exception
{

  /**
   * @var int Status code for the response causing the exception
   */
  private $statusCode;

  /**
   * @var string Raw response
   */
  private $rawResponse;

  /**
   * @var array Decoded response
   */
  private $responseData;

  /**
   * Creates a RequestException.
   *
   * @param string $rawResponse The raw response from API
   * @param array $responseData The decoded response from API
   * @param int $statusCode
   */
  public function __construct($rawResponse, $responseData, $statusCode)
  {
    $this->rawResponse = $rawResponse;
    $this->statusCode = $statusCode;
    $this->responseData = self::convertToArray($responseData);
    parent::__construct(
      $this->get('message', 'Unknown Exception'), $this->get('code', -1), null
    );
  }

  /**
   * Process an error payload from API and return the appropriate
   *   exception subclass.
   *
   * @param string $raw the raw response from API
   * @param array $data the decoded response from API
   * @param int $statusCode the HTTP response code
   *
   * @return RequestException
   */
  public static function create($raw, $data, $statusCode)
  {
    $data = self::convertToArray($data);
    if (!isset($data['error']['code']) && isset($data['code'])) {
      $data = array('error' => $data);
    }
    $code = (isset($data['error']['code']) ? $data['error']['code'] : null);

    if (isset($data['error']['error_subcode'])) {
      switch ($data['error']['error_subcode']) {
        // Other authentication issues
        case 458:
        case 459:
        case 460:
        case 463:
        case 464:
        case 467:
          return new AuthorizationException($raw, $data, $statusCode);
          break;
      }
    }

    switch ($code) {
      // Login status or token expired, revoked, or invalid
      case 100:
      case 102:
      case 190:
        return new AuthorizationException($raw, $data, $statusCode);
        break;

      // Server issue, possible downtime
      case 1:
      case 2:
        return new ServerException($raw, $data, $statusCode);
        break;

      // API Throttling
      case 4:
      case 17:
      case 341:
        return new ThrottleException($raw, $data, $statusCode);
        break;

      // Duplicate Post
      case 506:
        return new ClientException($raw, $data, $statusCode);
        break;
    }

    // Missing Permissions
    if ($code == 10 || ($code >= 200 && $code <= 299)) {
      return new PermissionException($raw, $data, $statusCode);
    }

    // OAuth authentication error
    if (isset($data['error']['type'])
      and $data['error']['type'] === 'OAuthException') {
      return new AuthorizationException($raw, $data, $statusCode);
    }

    // All others
    return new OtherException($raw, $data, $statusCode);
  }

  /**
   * Checks isset and returns that or a default value.
   *
   * @param string $key
   * @param mixed $default
   *
   * @return mixed
   */
  private function get($key, $default = null)
  {
    if (isset($this->responseData['error'][$key])) {
      return $this->responseData['error'][$key];
    }
    return $default;
  }

  /**
   * Returns the HTTP status code
   *
   * @return int
   */
  public function getHttpStatusCode()
  {
    return $this->statusCode;
  }

  /**
   * Returns the sub-error code
   *
   * @return int
   */
  public function getSubErrorCode()
  {
    return $this->get('error_subcode', -1);
  }

  /**
   * Returns the error type
   *
   * @return string
   */
  public function getErrorType()
  {
    return $this->get('type', '');
  }

  /**
   * Returns the raw response used to create the exception.
   *
   * @return string
   */
  public function getRawResponse()
  {
    return $this->rawResponse;
  }

  /**
   * Returns the decoded response used to create the exception.
   *
   * @return array
   */
  public function getResponse()
  {
    return $this->responseData;
  }

  /**
   * Converts a stdClass object to an array
   *
   * @param mixed $object
   *
   * @return array
   */
  private static function convertToArray($object)
  {
    if ($object instanceof \stdClass) {
      return get_object_vars($object);
    }
    return $object;
  }

}
