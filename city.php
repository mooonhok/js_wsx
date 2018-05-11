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

//$app->get('/city', function (Request $req,  Response $res, $args = []) {
//    $myvar1 = $req->getParam('pid'); //检查 _GET 和 _POST [不遵循 PSR 7]
//    $myvar2 = $req->getParsedBody()['pid']; //检查 _POST  [遵循 PSR 7]
//    $myvar3 = $req->getQueryParams()['pid']; //检查 _GET [遵循 PSR 7]
//    echo $myvar1.'xxxx'.$myvar2.'xxxx'.$myvar3;
//});



$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

$app->run();

function localhost()
{
    return connect();
}

?>
