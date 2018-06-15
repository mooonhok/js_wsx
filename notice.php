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

$app->get('/getNotices',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('notice');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    return $response->withJson(array("result" => "0", "desc" => "success",'notices'=>$data));
});


$app->options('/alterNotice',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
    return $response;
});


$app->put('/alterNotice',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $array=array();
    foreach($body as $key=>$value){
        if($key!="id"){
            $array[$key]=$value;
        }
    }
    if($id!=null||$id!=""){
        $updateStatement = $database->update($array)
            ->table('notice')
            ->where('id','=',$id);
        $affectedRows = $updateStatement->execute();
        return $response->withJson(array("result" => "0", "desc" => "success"));
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"id为空"));
    }
});

$app->post('/addNotice',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $insertStatement = $database->insert(array_keys($array))
        ->into('notice')
        ->values(array_values($array));
    $insertId = $insertStatement->execute(false);
    return $response->withJson(array("result"=>"0","desc"=>"添加成功"));
});


$app->options('/deleteNotice',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "DELETE");
    return $response;
});

$app->delete('/deleteNotice',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $id=$request->getParam('id');//获取请求路径后数据
    $deleteStatement = $database->delete()
        ->from('notice')
        ->where('id', '=', $id);
    $affectedRows = $deleteStatement->execute();
    return $response->withJson(array("result" => "0", "desc" => "success"));
});

$app->get('/getNotice',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $id=$request->getParam('id');
    $selectStatement = $database->select()
        ->from('notice')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    return $response->withJson(array("result" => "0", "desc" => "success",'notice'=>$data));
});


$app->run();


function localhost()
{
    return connect();
}
?>