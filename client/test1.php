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

	use GuzzleHttp\Client;

	$client = new Client();
	//$response = $client->get('http://httpbin.org/get')->json();
	//echo "\n response: \n";
	//print_r($response);

	$request = $client->createRequest('GET', 'http://httpbin.org/get');
	$response = $client->send($request)->json();
	echo "\n response: \n";
	print_r($response);
