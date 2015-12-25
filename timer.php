<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2015/12/15
 * Time: 23:20
 */

class card{
    public function timer(){
        $sql="select* from card";
        $conn=new mysqli("localhost","root","","card");
        $result=$conn->query($sql);
        $sql2="select* from token";
        $conn2=new mysqli("localhost","root","","card");
        $result2=$conn2->query($sql2);
        $row2=$result2->fetch_array();
        $token=$row2[1];
        $log=file_get_contents("run.log");

        $log=$log."\n"."于".date("Y-m-d H:i:s")."执行";



        file_put_contents(dirname(__FILE__) ."/run.log",$log);

        while($row=$result->fetch_array()) {
            if (!file_exists(dirname(__FILE__) . "/recordings/" . $row[1]. ".amr")){


                    $this->downloadfile($row[1],$token);



            }



        }

    }
function to_mp3($mediaID){
    exec("ffmpeg  -i ".dirname(__FILE__) ."/recordings/".$mediaID.".amr"." ".dirname(__FILE__) ."/recordings/".$mediaID.".mp3");
}
    public function downloadfile($mediaID,$token){

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

        $this->saveWeixinFile($mediaID.".amr", $fileInfo["body"],$mediaID);







    }
    function saveWeixinFile($filename, $filecontent,$mediaID)

    {
        $dir=dirname(__FILE__)."/recordings/";
        if(!is_dir($dir)){
            mkdir($dir,0777);
        }
        $local_file = fopen(dirname(__FILE__)."/recordings/".$filename, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
        $this->to_mp3($mediaID);








    }








}
$show= new card();
$show->timer();




?>