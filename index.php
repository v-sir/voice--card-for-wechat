<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2015/12/10
 * Time: 16:22
 */


require_once("./lib/code.php");
require_once("./img_processing.php");
require_once("./config/dbconfig.php");
define("TOKEN", "nicaiheheda");

define("APPID","wxbbdc520f5dd733ac");//测试号
define("SECRET","d384da6525e8a02fcb47c77bd8b8b459");//测试号
//define("APPID","wx69c494cc4ee58869");//测试号2
//define("SECRET","d4624c36b6795d1d99dcf0547af5443d");//测试号2
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();


class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //$this->responseMsg();
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }

    }
    public function save_token(){
        include "./cache/token.php";
        if(!$Token['expire_time']>time()){
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
            $token = //$strjson->access_token;
            "5Tl9M0CGadpPYUJ6Ysj-wtxx6MWwFNd58Rz1zTNff2m0QsivfYNCgADPVh7ONsNuNNaVs2HA2uKd6f0dvy65gB4N_-r3_3GFDI2bSx8hbtwNSBiAJAINW";
            $data=array(

                'token'=>$token,
                'expire_time'=>time()
            );
            $dir=dirname(__FILE__)."/cache/";
            $filename="token.php";
            $data="<?php\n  ".'$Token='.var_export($data,true)."\n?>";
            if(!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            file_put_contents($dir.$filename,$data);

        }



    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $MsgType = trim($postObj->MsgType);

            switch($MsgType){

                case "text":
                    $resultStr =$this->responseText($postObj);
                    break;
                case "image":
                    $resultStr =$this->handleImage($postObj);
                    break;
                case "voice":
                    $resultStr =$this->handleVoice($postObj);
                    break;
                default:
                    $resultStr = "Unknow message type: " . $MsgType;
                    break;
            }



            echo $resultStr;




        }else {
            echo "";
            exit;
        }
    }

    private function handleVoice($postObj)
    {
        //获取语音消息媒体id，可以调用多媒体文件下载接口拉取数据
        $mediaID = trim($postObj->MediaId);


        if(!empty($mediaID)){

          //  $contentStr = "MediaId:\n" .$mediaID."\n"."翼宝提醒你，你可以回复如下格式生成精美的语音贺卡！". "\n"."格式：生成语音贺卡,MediaId[注意,是英文的]". "\n"."如：生成语音贺卡,TprHG7akanXjA3lD71H15bu_cyb2285uREdA7DHmFJDI9Expmjg8h9WIkDBXGZT-";
            $contentStr ="生成语音贺卡,".$mediaID.",翼宝提醒你把该文本复制发送给公众号即可生成语音贺卡！";
            //$this->responseText($postObj, $contentStr);
                $this->cache($mediaID,$postObj);


        }else{
            $resultStr = "MediaId is empty.";
        }

        return $resultStr;
    }


    public function responseText($postObj,$contentStr)

    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $msgType="text";
        $time = time();
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        if($contentStr=="") {


                $arr = explode(",",$keyword);

                $keyword=$arr[0];
                $MediaId=$arr[1];




            switch ($keyword) {
                case 圣诞快乐:

                    $this->upload_image($postObj);
                    break;


                default:
                    $contentStr = "你好我是萌萌哒翼宝。回复圣诞快乐将收到来自翼宝的祝福，你也可以直接发送语音定制你自己的语音贺卡，祝你圣诞快乐圣诞快乐。同时部分功能暂停对造成的不便表示抱歉！";
            }
        }


        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        echo $resultStr;

    }
    public function card($MediaId,$postObj,$mediaID){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $msgType="image";
        $time = time();
        $imageTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Image>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $contentStr="翼宝提醒你，翼宝已为你生成精美的语音贺卡！\n但是由于网络因素，你可能接受不到公众号发送给你语音贺卡！你可以点击下载你的语音贺卡哦！\n"."<a href='http://card.sky31.com/card_img/".$mediaID.".jpg'>点我下载</a>";

        $resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, $msgType, $MediaId);
        echo $resultStr;
        //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time,"text",$contentStr);
        //echo $resultStr;
        //$this->downloadfile($mediaID);


    }


    public function cache($mediaID,$postObj){
        session_start();
        define("STR_MD","23nbhjfdb#%#^A!~");
        $token=md5($mediaID.STR_MD.time());
        $data=array(
            'media_id'=>"$mediaID",
            'token'=>"$token"
        );
        $dir=dirname(__FILE__)."/cache/";
        $filename=$mediaID.".php";
        $data="<?php\n  ".'$Token='.var_export($data,true)."\n?>";
        if(!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        file_put_contents($dir.$filename,$data);
        $value="http://card.sky31.com/player.html?ask_method=json&media_id=$mediaID&Token=$token";
        $qr_make=new code();
        $qr_make->code_make($value,$mediaID);
        $sql="insert into card(media_id) values('$mediaID')";
        $conn=new mysqli(HOST,UserName,PassWord,DataBase);
        $conn->query("set names UTF8");
        $conn->query($sql);
        $this->card_make($postObj,$mediaID);
        //$this->downloadfile($mediaID);






    }
    public function card_make($postObj,$mediaID){
      // include "./cache/token.php";
      // $token=$Token['token'];
        $sql2="select* from token";
        $conn2=new mysqli("localhost","root","","card");
        $result2=$conn2->query($sql2);
        $row2=$result2->fetch_array();
        $token=$row2[1];

        $img_processing=new image_Processing();
        $img_processing->random_num($mediaID);
        $dir=dirname(__FILE__)."/card_img/";
        $filename=$mediaID.".jpg";
        $postdata=array("media"=>"@".$dir.$filename);
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=image";
        $ch = curl_init();//新建curl
        curl_setopt($ch, CURLOPT_URL, $url);//url
        curl_setopt($ch, CURLOPT_POST, 1);  //post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);//post内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $a = curl_exec($ch);
        $a=json_decode($a);
        $media_id=$a->media_id;

        $this->card($media_id,$postObj,$mediaID);


    }



    public function upload_image($postObj){
        //include "./cache/token.php";
        // $token=$Token['token'];
        $sql2="select* from token";
       $conn2=new mysqli("localhost","root","","card");
        $result2=$conn2->query($sql2);
       $row2=$result2->fetch_array();
        $token=$row2[1];
        $dir=dirname(__FILE__)."/card_img/";
        $filename="sky31_0".rand(1,11).".jpg";
        $postdata=array("media"=>"@".$dir.$filename);
        $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=image";
        $ch = curl_init();//新建curl
        curl_setopt($ch, CURLOPT_URL, $url);//url
        curl_setopt($ch, CURLOPT_POST, 1);  //post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);//post内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $a = curl_exec($ch);
        $a=json_decode($a);
        $media_id=$a->media_id;
        //$errcode=$a->errcode;
       // if($errcode==40001 || $errcode==42001 || $errcode==41001 ){

         ///   $this->save_token();
           // $this->upload_image($postObj,$errcode="");

      //  }
        $this->card($media_id,$postObj,"");




    }
    public function downloadfile($mediaID){
        include "./cache/token.php";
        $token=$Token['token'];

        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$token&media_id=$mediaID";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $fileInfo = array_merge(array('header' => $httpinfo), array('body' => $package));

        $this->saveWeixinFile($mediaID.".amr", $fileInfo["body"]);







    }
    function saveWeixinFile($filename, $filecontent)

    {
        $dir=dirname(__FILE__)."/recordings/";
        if(!is_dir($dir)){
            mkdir($dir,0777);
        }
        $local_file = fopen($dir=dirname(__FILE__)."/recordings/".$filename, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
        //$this->card($media_id,$postObj);







    }
    public function re_voice($postObj,$MediaId){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $msgType="voice";
        $time = time();
        $voiceTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Voice>
							<FuncFlag>0</FuncFlag>
							</xml>";

        $resultStr = sprintf($voiceTpl, $fromUsername, $toUsername, $time, $msgType, $MediaId);
        echo $resultStr;

    }
    public function test($postObj){

        $media_id="iag0ToxRpLB9eU2IA7TxTiCqh7Au89IBlnVxBukI7amUOUDxTMOnmrwZauN64YTl";
        $this->card($media_id,$postObj,"");

    }


    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}

?>
