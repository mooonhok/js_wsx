<?php
require 'vendor/autoload.php';
require 'connect.php';
require 'mycurl.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;
$app = new Slim\App();
$curl=new mycurl();

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


//$checkProxyHeaders = true;
//$trustedProxies = ['10.0.0.1', '10.0.0.2'];
//$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

$app->options('/alterUser0',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
    return $response;
});
$app->put('/alterUser0',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $passwd=$body->passwd;
    $array=array();
    if($id!=null||$id!=""){
        if($passwd!=null||$passwd!=""){
            $selectStatement = $database->select()
                ->from('user')
                ->where('id','=',$id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $array['passwd']=encode($passwd , 'cxphp');
                $updateStatement = $database->update($array)
                    ->table('user')
                    ->where('id','=',$id);
                $affectedRows = $updateStatement->execute();
                return $response->withJson(array("result" => "0", "desc" => "success"));
            }else{
                return $response->withJson(array("result"=>"2","desc"=>"用户不存在"));
            }
        }else{
            return $response->withJson(array("result"=>"3","desc"=>"缺少密码"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"缺少id"));
    }
});



$app->post('/mycurldemo',function(Request $request,Response $response)use($curl){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $url='https://api.uminfo.cn/adminall.php/sign';
    $header=array(
    "application/json"=>"charset=utf-8"
);
    $array = array(
        "name" =>"admin",
        "password"=>"123456"
    );
    $admin=$curl->postmethod($url,$header,$array);
//    $city2=$admin['admin']['username'];
    return $response->withJson(array("result"=>"1","desc"=>$admin));
});


$app->post('/mycurldemo2',function()use($curl,$app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $url='https://api.uminfo.cn/adminall.php/sign';
    $header=array(
        "application/json"=>"charset=utf-8"
    );
    $array = array(
        "name" =>"admin",
        "password"=>"123456"
    );
    $admin=$curl->postmethod($url,$header,$array);
//    $city2=$admin['admin']['username'];
    return $app->response->withJson(array("result"=>"1","desc"=>$admin));
});



$app->run();

function localhost()
{
    return connect();
}

?>
