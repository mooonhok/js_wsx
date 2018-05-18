<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/17
 * Time: 16:12
 */
class mycurl
{
    public $url;//请求路径
    public $data;//请求数据
   public  $header;//请求头

    public function changdata($data){
        return urldecode(json_encode($data));
    }
    //使用CURL发送HTTP的典型过程
    public function gethttpl($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $output = curl_exec($ch);
        if($output === FALSE ){
           return curl_error($ch);
        }else{
              return $output;
        }
        curl_close($ch);
    }

   //get方法
    public function getmethod($url,$header){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if($header!=null||$header!="") {
            curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义header
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if($output === FALSE ){
            return curl_error($ch);
        }else{
            $jsoninfo = json_decode($output, true);
            return $jsoninfo;
        }
        curl_close($ch);
    }

    //post方法
    public function postmethod($url,$header,$data){
        $postJson = $this->changdata($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if($header!=null||$header!="") {
            curl_setopt($curl, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);//定义header
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson);
        $output = curl_exec($curl);
        if($output === FALSE ){
            return curl_error($curl);
        }else{
            $jsoninfo=json_decode($output,true);
            return $jsoninfo;
        }
        curl_close($curl);
    }

    //put方法
    public function putmethod($url,$header,$data){
        $postJson = $this->changdata($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url); //定义请求地址
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "put"); //定义请求类型，当然那个提交类型那一句就不需要了
        curl_setopt($curl, CURLOPT_HEADER,0); //定义是否显示状态头 1：显示 ； 0：不显示
        if($header!=null||$header!="") {
            curl_setopt($curl, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);//定义header
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson); //定义提交的数据
        $output = curl_exec($curl);
        if($output === FALSE ){
            return curl_error($curl);
        }else{
            $jsoninfo=json_decode($output,true);
            return $jsoninfo;
        }
        curl_close($curl);
    }

    //delete方法
    public function deletemethod($url,$header,$data){
        $postJson = $this->changdata($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url); //定义请求地址
        curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_HEADER,0); //定义是否显示状态头 1：显示 ； 0：不显示
        if($header!=null||$header!="") {
            curl_setopt($curl, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);//定义header
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson); //定义提交的数据
        $output = curl_exec($curl);
        if($output === FALSE ){
            return curl_error($curl);
        }else{
            $jsoninfo=json_decode($output,true);
            return $jsoninfo;
        }
        curl_close($curl);
    }


}
?>