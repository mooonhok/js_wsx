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
       $selectStatement = $database->select()
        ->from('route')
        ->where('type','=',$type);
//        ->orderBy('province');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $array1=array();
        for($i=0;$i<count($data);$i++){
            echo $i;
//            $selectStatement = $database->select()
//                ->from('route')
//                ->where('type','=',$type)
//                ->where('province','=',$data[$i]['province'])
//                ->orderBy('id');
//            $stmt = $selectStatement->execute();
//            $data2 = $stmt->fetchAll();
//            array_push($array1,$data2);
        }
             $array1=array_values(array_unset_tt($array1,'id'));
        if($array1!=null){
            return $response->withJson(array("result" => "0", "desc" => "success",'routes'=>$array1));
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"尚未有数据"));
        }
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