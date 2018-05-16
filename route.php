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

$app->get('/getRoutes',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('route');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            return $response->withJson(array("result" => "0", "desc" => "success",'routes'=>$data));
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"尚未有数据"));
        }
});




$app->run();

function localhost()
{
    return connect();
}
?>