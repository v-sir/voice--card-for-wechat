<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2015/12/10
 * Time: 16:22
 */
class wx_upload{
   public function token(){
        //$token="5Tl9M0CGadpPYUJ6Ysj-wtxx6MWwFNd58Rz1zTNff2m0QsivfYNCgADPVh7ONsNuNNaVs2HA2uKd6f0dvy65gB4N_-r3_3GFDI2bSx8hbtwNSBiAJAINW";
       include "./cache/token.php";
       $token=$Token['token'];
       echo time();
       $this->upload_image($token);

    }
    public function upload_image($token){
        $dir=dirname(__FILE__)."/card_img/";
        $filename=rand(1, 10).".jpg";
        $postdata=array("media"=>"@".$dir.$filename);
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=image";
        $ch = curl_init();//ÐÂ½¨curl
        curl_setopt($ch, CURLOPT_URL, $url);//url
        curl_setopt($ch, CURLOPT_POST, 1);  //post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);//postÄÚÈÝ
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $a = curl_exec($ch);
        print_r($a);




    }
}
$show=new wx_upload();
$show->token();

















?>