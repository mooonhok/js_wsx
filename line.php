<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/7
 * Time: 17:15
 */
require 'vendor/autoload.php';
require 'connect.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

$app = new \Slim\App();

$app->get('/getLines',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
        $selectStatement = $database->select()
            ->from('line');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($x=0;$x<count($data);$x++){
            $selectStatement = $database->select()
                ->from('line_city')
                ->where('line_id','=',$data[$x]['id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            $data[$x]['line-citys']=$data2;
            $data[$x]['count']=count($data2);
        }
        return $response->withJson(array("result" => "0", "desc" => "success",'lines'=>$data));

});


$app->get('/getLine',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $id=$request->getParam('id');
    if($id!=null||$id!=""){
        $selectStatement = $database->select()
            ->from('line')
            ->where('id','=',$id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('line_city')
            ->where('line_id','=',$id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $data['line-citys']=$data2;
        $data['count']=count($data2);
    return $response->withJson(array("result" => "0", "desc" => "success",'line'=>$data));
    }else{
        return $response->withJson(array("result" => "1", "desc" => "缺少id"));
    }
});

$app->run();

function localhost()
{
    return connect();
}

function encode($string , $skey ) {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

//解密
function decode($string, $skey) {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

?>