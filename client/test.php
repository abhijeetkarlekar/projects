<?php

define('LIB_DIR', '/var/www/projects/client/lib/');
define('GUZZLE_LIB_DIR', '/var/www/projects/client/lib/HttpClients/GuzzleHttpClient/');
define('REACT_LIB_DIR', '/var/www/projects/client/lib/HttpClients/GuzzleHttpClient/React/');
/* used for single namespace */
#require __DIR__ . '/autoload.php';
/* used for single namespace */

/* used for multiple namespaces */
require LIB_DIR . "Psr4AutoloaderClass.php";
// instantiate the loader
$loader = new Cricket\Psr4AutoloaderClass;
// register the autoloader
$loader->register();
// add namespace
$loader->addNamespace('Cricket', LIB_DIR);
$loader->addNamespace('GuzzleHttp', GUZZLE_LIB_DIR);
$loader->addNamespace('React', REACT_LIB_DIR);
/* used for multiple namespaces */

use Cricket\Request;

$request_method = 'POST';

$request_params = array(
    'method' => 'archive.fixed.getNuggest', // method call
    'match_id' => '12888', // match id
    'service' => 'cricbuzz', // service name
    'format' => 'json' // optional
);

try {
    $response = (new Request(
            $request_method, $request_params
            ))->execute();
    // response
    echo "\n $response \n";
} catch (RequestException $e) {
    // The API returned an error
    echo "The API returned an error: $e";
} catch (\Exception $e) {
    // Some other error occurred
    echo "Some other error occurred: $e";
}
