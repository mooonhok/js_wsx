<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/15
 * Time: 9:45
 */
require 'vendor/autoload.php';
require 'connect.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

$app = new \Slim\App();


$app->post('/addUser',function(Request $request,Response $response){
    $response->withHeader('Access-Control-Allow-Origin','*');
    $response->withHeader('Content-Type','application/json');
    $response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response->withAddedHeader('Content-Type','application/json');
    $response = $response->withHeader('Access-Control-Allow-Origin','*');
    $response = $response->withHeader('Content-type', 'application/json');
    $response.header('Access-Control-Allow-Origin','*');
    $response.header('Content-type', 'application/json');
    $response->withoutHeader('Access-Control-Allow-Origin','*');
    $response->withoutHeader('Content-type', 'application/json');
    $response->header('Access-Control-Allow-Origin','*');
    $response->header('Content-type', 'application/json');
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $phone=$body->phone;//获取body中数据
    $name=$body->name;
    $number=$body->number;
    $passwd=$body->passwd;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($phone!=null||$phone!=""){
        if($name!=null||$name!=""){
            if($number!=null||$number!=""){
                if($passwd!=null||$passwd!=""){
                    $selectStatement = $database->select()
                        ->from('user');
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetchAll();
                    $password=encode($passwd , 'cxphp');
                    $array['passwd']=$password;
                    $array['id']=count($data)+1;
                    $insertStatement = $database->insert(array_keys($array))
                        ->into('user')
                        ->values(array_values($array));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array("result" => "0", "desc" => "success"));
                }else{
                    echo  json_encode(array("result"=>"4","desc"=>"缺少密码"));
                }
            }else{
                echo  json_encode(array("result"=>"3","desc"=>"缺少身份证号"));
            }
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"缺少姓名"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少电话"));
    }
});



$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

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