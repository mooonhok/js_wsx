<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/28
 * Time: 16:27
 */
use Slim\PDO\Database;
function connect(){
//    $serverName = env("MYSQL_PORT_3306_TCP_ADDR", "	db2.daocloudinternal.io");
//    $databaseName = env("MYSQL_INSTANCE_NAME", "temp_db");
//    $username = env("MYSQL_USERNAME", "root");
//    $password = env("MYSQL_PASSWORD", "Gd6lhOSsY");
    $serverName = env("MYSQL_PORT_3306_TCP_ADDR", "172.21.0.15");
    $databaseName = env("MYSQL_INSTANCE_NAME", "official_website");
    $username = env("MYSQL_USERNAME", "root");
    $password = env("MYSQL_PASSWORD", "wsx20040225");
    $port=env("MYSQL_PORT_3306_TCP_PORT","3306");
    $database=new database("mysql:host=".$serverName.";port=".$port.";dbname=".$databaseName.";charset=utf8",$username,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
    return  $database;
}
function env($key, $default = null)
{
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}

?>
