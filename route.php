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
        ->where('type','=',$type)
//        ->orderBy('province')
        ->orderBy('id','ASC');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//    if($data!=null) {
//        foreach ( $data as $key => $row ){
//            $id[$key] = $row ['province'];
//            $name[$key]=$row['id'];
//        }
//        array_multisort($id, SORT_ASC, $name, SORT_ASC, $data);
//    }
        $array1=array();
        if($data!=null){
            for($i=0;$i<count($data);$i++){
                $a=$data[$i]['province'];
                for($j=$i;$j<count($data);$j++){
                    if($data[$j]['province']!=$a){
                        $array1[$j]=$data[$j];
                        $data[$j]="";
                    }
                }
                $data=array_filter($data);
                if($array1!=null){
                  $data=array_merge($data,$array1);
                }
            }
        }
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