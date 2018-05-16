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
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');

//    $response->withAddedHeader('Access-Control-Allow-Origin','*');
//    $response->withAddedHeader('Content-Type','application/json');
//    $response = $response->withHeader('Access-Control-Allow-Origin','*');
//    $response = $response->withHeader('Content-type', 'application/json');
//    $response.header('Access-Control-Allow-Origin','*');
//    $response.header('Content-type', 'application/json');
//    $response->withoutHeader('Access-Control-Allow-Origin','*');
//    $response->withoutHeader('Content-type', 'application/json');
//    $response->header('Access-Control-Allow-Origin','*');
//    $response->header('Content-type', 'application/json');
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
//                    echo json_encode(array("result" => "0", "desc" => "success"));
                    return $response->withJson(array("result" => "0", "desc" => "success"));
                }else{
//                    echo  json_encode(array("result"=>"4","desc"=>"缺少密码"));
                    return $response->withJson(array("result"=>"4","desc"=>"缺少密码"));
                }
            }else{
                return $response->withJson(array("result"=>"3","desc"=>"缺少身份证号"));
            }
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"缺少姓名"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"缺少电话"));
    }
});

$app->get('/getUser0',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $phone=$request->getParam('phone');
    if($phone!=null||$phone!=""){
        $selectStatement = $database->select()
            ->from('user')
            ->where('phone','=',$phone);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
             return $response->withJson(array("result" => "0", "desc" => "success",'user'=>$data));
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"用户不存在"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"缺少电话"));
    }
});

$app->get('/getUser1',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $phone=$request->getParam('phone');
    $passwd=$request->getParam('passwd');
    if($phone!=null||$phone!=""){
        if($passwd!=null||$passwd!=""){
        $selectStatement = $database->select()
            ->from('user')
            ->where('passwd','=',encode($passwd , 'cxphp'))
            ->where('phone','=',$phone);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            return $response->withJson(array("result" => "0", "desc" => "success",'user'=>$data));
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"用户不存在"));
        }
        }else{
            return $response->withJson(array("result"=>"3","desc"=>"缺少密码"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"缺少电话"));
    }
});

$app->get('/getUser2',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $name=$request->getParam('name');
    $number=$request->getParam('number');
    if($name!=null||$name!=""){
        if($number!=null||$number!=""){
            $selectStatement = $database->select()
                ->from('user')
                ->where('number','=',$number)
                ->where('name','=',$name);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                return $response->withJson(array("result" => "0", "desc" => "success",'user'=>$data));
            }else{
                return $response->withJson(array("result"=>"2","desc"=>"用户不存在"));
            }
        }else{
            return $response->withJson(array("result"=>"3","desc"=>"缺少身份号"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"缺少姓名"));
    }
});

//$app->options('/alterUser0',function(Request $request,Response $response){
//    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
//    $response=$response->withAddedHeader('Content-Type','application/json');
//    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
//    return $response;
//});

$app->get('/getUser3',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $id=$request->getParam('id');
    if($id!=null||$id!=""){
        $selectStatement = $database->select()
            ->from('user')
            ->where('id','=',$id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            return $response->withJson(array("result" => "0", "desc" => "success",'user'=>$data));
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"用户不存在"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"缺少id"));
    }
});

$app->post('/alterUser0',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
//    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
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


$app->post('/alterUser1',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
//    $response=$response->withAddedHeader("Access-Control-Allow-Methods", "PUT");
    $database=localhost();
    $body = $request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $phone=$body->phone;//获取body中数据
    $name=$body->name;
    $number=$body->number;
    $passwd=$body->passwd;
    $array=array();
    foreach($body as $key=>$value){
        if($key!="id"){
        $array[$key]=$value;
        }
    }
    if($id!=null||$id!=""){
    if($phone!=null||$phone!=""){
        if($name!=null||$name!=""){
            if($number!=null||$number!=""){
                if($passwd!=null||$passwd!=""){
                    $selectStatement = $database->select()
                        ->from('user')
                        ->where('id','=',$id);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetch();
                    if($data!=null){
                    $password=encode($passwd , 'cxphp');
                    $array['passwd']=$password;
                        $updateStatement = $database->update($array)
                            ->table('user')
                            ->where('id', '=',$id);
                        $affectedRows = $updateStatement->execute();
                    return $response->withJson(array("result" => "0", "desc" => "success"));
                    }else{
                        return $response->withJson(array("result"=>"6","desc"=>"用户不存在"));
                    }
                }else{
//                    echo  json_encode(array("result"=>"4","desc"=>"缺少密码"));
                    return $response->withJson(array("result"=>"4","desc"=>"缺少密码"));
                }
            }else{
                return $response->withJson(array("result"=>"3","desc"=>"缺少身份证号"));
            }
        }else{
            return $response->withJson(array("result"=>"2","desc"=>"缺少姓名"));
        }
    }else{
        return $response->withJson(array("result"=>"1","desc"=>"缺少电话"));
    }
    }else{
        return $response->withJson(array("result"=>"5","desc"=>"缺少id"));
    }
});

$app->get('/getUser4',function(Request $request,Response $response){
    $response=$response->withAddedHeader('Access-Control-Allow-Origin','*');
    $response=$response->withAddedHeader('Content-Type','application/json');
    $database=localhost();
    $id=$request->getParam('id');
    $passwd=$request->getParam('passwd');
    if($id!=null||$id!=""){
        if($passwd!=null||$passwd!=""){
            $selectStatement = $database->select()
                ->from('user')
                ->where('passwd','=',encode($passwd , 'cxphp'))
                ->where('id','=',$id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                return $response->withJson(array("result" => "0", "desc" => "success",'user'=>$data));
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
//$checkProxyHeaders = true;
//$trustedProxies = ['10.0.0.1', '10.0.0.2'];
//$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

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