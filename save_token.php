<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2015/12/18
 * Time: 14:46
 */

define("APPID","wxbbdc520f5dd733ac");//²âÊÔºÅ
define("SECRET","d384da6525e8a02fcb47c77bd8b8b459");//²âÊÔºÅ
//define("APPID","wx69c494cc4ee58869");//²âÊÔºÅ2
//define("SECRET","d4624c36b6795d1d99dcf0547af5443d");//²âÊÔºÅ2
include "./cache/token.php";

    $appid=APPID;
    $secret=SECRET;
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $a = curl_exec($ch);
    $strjson=json_decode($a);
    echo $token = $strjson->access_token;
        //"5Tl9M0CGadpPYUJ6Ysj-wtxx6MWwFNd58Rz1zTNff2m0QsivfYNCgADPVh7ONsNuNNaVs2HA2uKd6f0dvy65gB4N_-r3_3GFDI2bSx8hbtwNSBiAJAINW";
    $data=array(

        'token'=>$token,
        'expire_time'=>date("Y-m-d H:i:s",time()+7200)
    );
    $dir=dirname(__FILE__)."/cache/";
    $filename="token.php";
    $data="<?php\n  ".'$Token='.var_export($data,true)."\n?>";
    if(!is_dir($dir)) {
        mkdir($dir, 0777);
    }
    file_put_contents($dir.$filename,$data);
    $sql="update token set token='$token' where id=1";
    $conn=new mysqli("localhost","root","","card");
    $result=$conn->query($sql);




?>