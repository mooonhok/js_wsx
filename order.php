<?php
require 'vendor/autoload.php';
require 'connect.php';


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;


$app = new Slim\App(['settings' => ['displayErrorDetails' => true]]);


$app->get('/orders', function (Request $request,Response $response)  {
//$app->response->headers->set('Access-Control-Allow-Origin','*');
//$app->response->headers->set('Content-Type','application/json');
//$page = $app->request->get('page');
    $page=0;
    $tenant_id =1000000001;
    $per_page = 1;
    $order_id='';
    $database = localhost();
    $selectStatement = $database->select()
        ->from('orders')
        ->whereLike('order_id',"%".$order_id."%")
        ->whereLike('tenant_id',"%".$tenant_id."%")
        ->whereNotNull('tenant_id');
    $stmt = $selectStatement->execute();
    $count=$stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereLike('order_id',"%".$order_id."%")
                ->whereLike('tenant_id',"%".$tenant_id."%")
                ->whereNotNull('tenant_id')
                ->orderBy('exist')
                ->orderBy('id','DESC')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', "=", $data[$i]['tenant_id'])
                    ->where('customer_id', "=", $data[$i]['sender_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', "=", $data[$i]['tenant_id'])
                    ->where('customer_id', "=", $data[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', "=", $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['from_city_id']);
                $stmt = $selectStatement->execute();
                $data4= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data2['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data5= $stmt->fetch();
                $data[$i]['tenant']=$data3;
                $data[$i]['receiver']=$data2;
                $data[$i]['receiver']['receiver_city']=$data5['name'];
                $data[$i]['sender']=$data1;
                $data[$i]['from_city']=$data4;
            }
            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data,'count'=>count($count)));
});


//$app->get('/hello/{name}', function ($request, $response, $args) {
//   $data = array('name' => 'Bob', 'age' => 40);
//$newResponse = $oldResponse->withJson($data);
//echo $newResponse;
//})->setName('hello');

$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

$app->get('/helloo', function ($request, $response, $args) {
    $ipAddress = $request->getAttribute('ip_address');

    return $ipAddress;
});

$app->get('/foo', function ($req, $res, $args) {
    return $res->withHeader(
        'Content-Type',
        'application/json'
    );
});

// 添加路由回调
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Hello World!');
});

$app->get('/get_html',function($app){
 return '<div style="font-size:20px;text-align:center;">甲方的权利与义务</div>
    <div>1.甲方须如实填写货物信息，严禁夹带法律禁运物品，造成后果由甲方负责。</div>
    <div>2.甲方应对所托货物按照行业标准妥善包装，使其适合运输。</div>
    <div>3.甲方应确保所提供收货人信息准确，电话通畅，如因此造成收货延误，乙方不负违约责任。乙方确认交货后，甲方如有异议须在三天内以书面方式提出，否则视同乙方运输义务完成。运输完成后，甲方应主动按约定支付运费。</div>
    <div style="font-size:20px;text-align:center;">乙方的权利与义务</div>
    <div>1.所运货物经乙方验点准确捆扎牢固后方可行驶，路途一切费用均由乙方负承担。</div>
    <div>2.在合同规定的期限内，将货物运到指定的地点，按时向收货人发出货物到达的通知，对托运的货物要负责安全，保证货物无短缺，无损坏，否则应承担由此引起的一切赔偿责任。</div>
    <div>3.乙方保证运输途中通讯畅通，如遇异常与甲方及时联系。</div>
    <div>4.货物途中如被查超载造成罚款，由乙方承担，货物途中如被查超载造成卸货费用及货物到达卸货地点前一切费用由乙方负全责，甲方不予列支。</div>
    <div>5.因发生自然灾害等不可抗力造成货物无法按期运达目的地时，乙方应将情况及时通知甲方并取得相关证明，以便甲方与客户协调；非因自然灾害等不可抗力造成货物无法按时到达，乙方须在最短时间内运至甲方指定的收货地点并交给收货人，且赔偿逾期承运给甲方造成的全部经济损失。
    本合同不得无故违约，如有法律纠纷，在甲方所在地人民法院受理。</div>
    </div><div class="pop_close">关闭</div>';
});

$app->run();

function localhost()
{
    return connect();
}

?>