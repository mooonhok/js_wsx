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

$app->get('/getRates',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $transfer_type=$request->getParam('transfer_type');//获取请求路径后数据
    $selectStatement = $database->select()
        ->from('rate')
        ->where('transfer_type','=',$transfer_type);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry_type')
                ->where('id','=',$data[$i]['lorry_type']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $data[$i]['lorry_type']=$data2;
            $selectStatement = $database->select()
                ->from('distance')
                ->where('id','=',$data[$i]['distance']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $data[$i]['distance']=$data3;
        }
    }
    return $response->withJson(array("result" => "0", "desc" => "success",'rates'=>$data));
});


$app->options('/alterRate',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
    return $response;
});


$app->put('/alterRate',function(Request $request,Response $response){
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
            ->table('rate')
            ->where('id','=',$id);
        $affectedRows = $updateStatement->execute();
        return $response->withJson(array("result" => "0", "desc" => "success"));
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"id为空"));
    }
});

$app->get('/getRate',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $transfer_type=$request->getParam('transfer_type');//获取请求路径后数据
    $lorry_type=$request->getParam('lorry_type');
    $distance=$request->getParam('distance');
    $selectStatement = $database->select()
        ->from('rate')
        ->where('distance','=',$distance)
        ->where('lorry_type','=',$lorry_type)
        ->where('transfer_type','=',$transfer_type);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
//    if($data!=null){
//        for($i=0;$i<count($data);$i++){
//            $selectStatement = $database->select()
//                ->from('lorry_type')
//                ->where('id','=',$data[$i]['lorry_type']);
//            $stmt = $selectStatement->execute();
//            $data2= $stmt->fetch();
//            $data[$i]['lorry_type']=$data2;
//            $selectStatement = $database->select()
//                ->from('distance')
//                ->where('id','=',$data[$i]['distance']);
//            $stmt = $selectStatement->execute();
//            $data3= $stmt->fetch();
//            $data[$i]['diatance']=$data3;
//        }
//    }
    return $response->withJson(array("result" => "0", "desc" => "success",'rate'=>$data));
});


$app->run();


function localhost()
{
    return connect();
}
?>