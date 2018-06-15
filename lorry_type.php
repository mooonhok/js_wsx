<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/16
 * Time: 13:57
 */
require 'vendor/autoload.php';
require 'connect.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

$app = new \Slim\App();

$app->get('/getLorryTypes',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('lorry_type');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    return $response->withJson(array("result" => "0", "desc" => "success",'lorry_types'=>$data));
});


$app->run();

function localhost()
{
    return connect();
}
?>