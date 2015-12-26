# voice--card-for-wechat
a voice card for-wechat!
thank you for your attention！
Now I want to share you some Reviews about the wechat development projects based on PHP!
First you need read the api documents in http://mp.weixin.qq.com/wiki/home/index.html  and download the  example code in http://mp.weixin.qq.com/mpres/htmledition/res/wx_sample.20140819.zip 
of course,you need a server and domain for yourself.if it's ok,you can begin the trip for coding!here some reviews i want to share you.
1.make sure the config is right,after you finish the config you can test it just write the reply text like 'test' ,if your config is right
you  can receive the 'test' in your wechat app!
2.when you design the objects for wechat ,one thing you must be attention ,Remember not to let your code corresponding to more than 5 seconds,
because if you cann't finish in 5s,the wechat app cann,t receive the information you want the wechat to reply!
3.The php version,it's very important to think more about the version,because there are  some bug in some version to cause the wrong in wechat.
I can tell you some case when I code my projects.the api for upload,you can work well in php 5.4 use the code 
"$ch = curl_init ();  
        $fields = $params;  
        $fields ['file'] = '@' . $file;  
        curl_setopt ( $ch, CURLOPT_URL, $url );  
        curl_setopt ( $ch, CURLOPT_POST, 1 );  
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );  
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );  
        $response = curl_exec ( $ch );  " ,
        but if your version is php 5.6 you will fail to upload with a error message "the media data missing!", you need add a row code  'curl_setopt ( $ch, CURLOPT_SAFE_UPLOAD, false)',so when you write your project whit a right code but can't work ,think over the version ,maybe it's the reason.
        
        
  4.about token save 
  it also very important to think of the way to save the token ,the api shows the token will Expired 7200s(2h).you must think if you have
  more users so you can't waste the token frequency.
  
  
  
  Now I want to say something about my voice card project 
  how the Voice greeting how birth,i use the class 'phpqrcode' to make the qrcode to recording the mediaID the wechat return to me,and
  i download the voice from wechat server use the download api.but we will have a problem ,how to make the voice play in web ,the voice file which we download from the wechat server is amr,if we want it to play in web ,you need convert amr to some file like mp3 and so on.how to do this,there is a way you can use the linux command 'ffmpeg' to convert .but how can php work.there is a function 'exec( )' .you can use this function to run the 'ffmpeg' command like 'exec("ffmpeg  -i ".dirname(__FILE__) ."/recordings/".$mediaID.".amr"." ".dirname(__FILE__) ."/recordings/".$mediaID.".mp3")'.after you finish those things,one thing you must to solve,if you want the wechat finish card make , download the voice files and covert to mp3 .the time will beyond 5 s.you need
  a way to avoid.i'm fool so i use a fool way to solve the problem.i make a timer in the linux,i made a crontab task to make sure the time short.but the crontab Shortest only run every minute,you have to run a script to let the task run every second,so i use the code like '#!/bin/bash

step=2 

for((i=0;i<60;i=(i+step)));do
		$(php '/sky31/www/card.sky31.com/timer.php')
		sleep $step
done
exit 0

'
if you want a project for the voice card you also need konw how to merge two pictures,it 's the same to add logo in a image.
in php you can use the function 'imagecopymerge'.
that 's all ,i hope you can enjoy the project .
