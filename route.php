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
    $type=$request->getParam('type');//获取请求路径后数据
    $array2=array();
       $selectStatement = $database->select()
        ->from('route')
        ->where('type','=',$type);
//        ->orderBy('province');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $array1=array();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('route')
                ->where('type','=',$type)
                ->where('province','=',$data[$i]['province'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            $array1=array_merge($array1,$data2);
        }
             $array1=array_values(array_unset_tt($array1,'id'));
        if($array1!=null){
//            for($i=0;$i<count($array1);$i++){
//                $num=$i+1;
//                $array1[$i]['number']=$num;
//            }
            return $response->withJson(array("result" => "0", "desc" => "success",'routes'=>$array2));
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"尚未有数据"));
        }
});


$app->post('/addRoute',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $line=$body->line;
    $province=$body->province;
    $num=$body->num;
    $band=$body->band;
    $type=$body->type;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($line!=null||$line!=""){
        if($province!=null||$province!=""){
                $insertStatement = $database->insert(array_keys($array))
                    ->into('route')
                    ->values(array_values($array));
                $insertId = $insertStatement->execute(false);
                return $response->withJson(array("result"=>"0","desc"=>"添加成功"));
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"省份为空"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"路线为空"));
    }
});

$app->get('/getRoutes1',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $type=$request->getParam('type');//获取请求路径后数据
    $province=$request->getParam('province');
    $array1=array();
    $selectStatement = $database->select()
        ->from('route')
        ->where('type','=',$type)
        ->where('province','=',$province) ;
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    $array1=$data;
    return $response->withJson(array("result" => "0", "desc" => "success",'routes'=>$array1));
});

$app->options('/alterRoute0',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
    return $response;
});



$app->put('/alterRoute0',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $num=$body->num;
    if($id!=null||$id!=""){
        $array1=array();
        $array1['num']=$num;
        $updateStatement = $database->update($array1)
            ->table('route')
            ->where('id','=',$id);
        $affectedRows = $updateStatement->execute();
        return $response->withJson(array("result" => "0", "desc" => "success"));
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"id为空"));
    }
});

$app->options('/alterRoute1',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
    return $response;
});

$app->put('/alterRoute1',function(Request $request,Response $response){
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
            ->table('route')
            ->where('id','=',$id);
        $affectedRows = $updateStatement->execute();
        return $response->withJson(array("result" => "0", "desc" => "success"));
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"id为空"));
    }
});

$app->get('/getRoute1',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $id=$request->getParam('id');//获取请求路径后数据
    $selectStatement = $database->select()
        ->from('route')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    return $response->withJson(array("result" => "0", "desc" => "success",'routes'=>$data));
});
$app->options('/deleteRoute',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "DELETE");
    return $response;
});

$app->delete('/deleteRoute',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $id=$request->getParam('id');//获取请求路径后数据
    $deleteStatement = $database->delete()
        ->from('route')
        ->where('id', '=', $id);
    $affectedRows = $deleteStatement->execute();
    return $response->withJson(array("result" => "0", "desc" => "success"));
});


$app->run();


function array_unset_tt($arr,$key){
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项
        if(isset($res[$value[$key]])){
            //有：销毁
            unset($value[$key]);
        }
        else{
            $res[$value[$key]] = $value;
        }
    }
    return $res;
}

function localhost()
{
    return connect();
}
?>