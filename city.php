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
    $body = $request->getBody();
    $body=json_decode($body);
    $url=$body->url;//获取body中数据
    $type=$body->type;
    $array=array();
    if($body!=null){
    foreach($body as $key=>$value){
        if($key!="url"&&$key!="type"){
            $array[$key]=$value;
        }
    }
    }
    $array1=array();
    $header=$request->getHeaders();
    if($header!=null){
    foreach($header as $key=>$value){
        $array1[$key]=$value;
    }
    }
    if($type!=null||$type!="") {
        if ($type == 0) {
            $re = $curl->gethttpl($url);//获取html文字
            return $response->withJson($re);
        } else if ($type == 1) {
            $re = $curl->getmethod($url, $array1);
            return $response->withJson($re);
        } else if ($type == 2) {
            $re = $curl->postmethod($url, $array1, $array);
            return $response->withJson($re);
        } else if ($type == 3) {
            $re = $curl->putmethod($url, $array1, $array);
            return $response->withJson($re);
        } else if ($type == 4) {
            $re = $curl->deletemethod($url, $array1, $array);
            return $response->withJson($re);
        }
    }else{
        return $response->withJson(array('desc'=>"缺少请求类型"));
    }
});



$app->get('/testwhile',function(Request $request,Response $response)use($curl){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $x=0;
   do {
       $re = $curl->gethttpl("http://api.uminfor.cn/city_nedb.php/getCity1?city_id=".$x);//获取html文字
//       return $response->withJson($re);
       echo $re;
       $x++;
     } while ($x<=50);
});




$app->run();

function localhost()
{
    return connect();
}

?>
