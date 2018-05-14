<?php
require 'vendor/autoload.php';
require 'connect.php';


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;
$app = new Slim\App();

$app->get('/province',function(Request $request,Response $response){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('province');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data));
});

$app->get('/city',function(Request $request,Response $response){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $pid=$request->getParam('pid');
    if($pid!=null||$pid!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('pid','=',$pid);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data,"pid"=>$pid));
    }else{
//        $uri = $request->getServerParams('pid');
        echo  json_encode(array("result"=>"0","desc"=>"lose pid".$pid));
    }
});


$app->post('/citybyp',function(Request $request,Response $response){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $pid=$body->pid;
    if($pid!=null||$pid!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('pid','=',$pid);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data,"pid"=>$pid));
    }else{
//        $uri = $request->getServerParams('pid');
        echo  json_encode(array("result"=>"0","desc"=>"lose pid".$pid));
    }
});



$app->post('/getvaluedemo',function(Request $request,Response $response){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
//    $database=localhost();
    $tenant_id = $request->getHeaderLine('tenant');//获取header中数据
    $tid=$request->getParam('tid');//获取请求路径后数据
    $body = $request->getBody();
    $body=json_decode($body);
    $pid=$body->pid;//获取body中数据
    echo  json_encode(array("result"=>"0","desc"=>"",'header'=>$tenant_id,"body"=>$pid,'urlp'=>$tid));
});


$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

$app->run();

function localhost()
{
    return connect();
}

?>
