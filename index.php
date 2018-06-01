<?php
/**
 * Step 1: Require the Slim Framework using Composer's autoloader
 *
 * If you are not using Composer, you need to load Slim Framework with your own
 * PSR-4 autoloader.
 */
require 'vendor/autoload.php';
/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new Slim\App();
/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */
//header("Location: http://www.jswsx56.cn");


$app->get('/', function ($request, $response, $args) {
    $response->write("<javascript>window.location.href='www.jswsx56.cn';  </javascript>");
    return $response;

});
$app->get('/hello[/{name}]', function ($request, $response, $args) {
    $response->write("Hello, " . $args['name']);
    return $response;
})->setArgument('name', 'World!');

$app->get('/datetime',function(){
    date_default_timezone_set("PRC");
    $time=date('Y-m-d H:i:s');
    echo  json_encode(array("result"=>"0","desc"=>"success","time"=>$time));
});

$app->run();