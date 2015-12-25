<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2015/12/11
 * Time: 18:51
 */
class image_Processing{
    public function random_num($MediaId){
        $image_id=rand(1, 17);
        $this->get_Original_Picture($image_id,$MediaId);
    }
    public function get_Original_Picture($image_id,$MediaId){
        $dir=dirname(__FILE__)."/wx_img";
        $image = imagecreatefromjpeg($dir.'/0'.$image_id.'.jpg');
        $font=dirname(__FILE__)."ttf.ttf";
        $image_caption="just a test!";
        imagettftext($image, 12 , 0 , 20 , 20 , 0 , $font , $image_caption);
        list($width,$height) = getimagesize($dir.'/0'.$image_id.'.jpg');
        $this->get_qrPicture($width,$height,$image_id,$MediaId);

    }
    public function get_qrPicture($width,$height,$image_id,$MediaId){
        list($qr_width , $qr_height) = getimagesize('qr_img/'.$MediaId.'.jpg');
        $this->Processing($qr_width , $qr_height,$width,$height,$image_id,$MediaId);

    }
    public function Processing($qr_width , $qr_height,$width,$height,$image_id,$MediaId){
        $dir2=dirname(__FILE__)."/card_img";
        $dir=dirname(__FILE__)."/wx_img";
        $x = ($width-$qr_width) / 2;
       // $y = ($height-$qr_height)/2;
        $y=400;
        $wmk = imagecreatefrompng('qr_img/'.$MediaId.'.jpg');
        $image = imagecreatefromjpeg($dir.'/0'.$image_id.'.jpg');
        //把水印图片和原图片合并在一起
        imagecopymerge($image , $wmk , $x , $y , 0 , 0 , $qr_width , $qr_height , 90);
        //清除水印图片
        imagedestroy($wmk);

        imagejpeg($image , $dir2.'/'.$MediaId.'.jpg' , 100);

    }



}


$show=new image_Processing();
$show->random_num();



?>