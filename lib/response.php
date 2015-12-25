<?php
/**
 * Created by HuangWei.
 * User: Administrator
 * Date: 2015/10/18
 * Time: 16:15
 */
class api_response{
    /**according to the api request method output the data
     * @param $code
     * @param string $msg
     * @param array $data
     * @return string
     */


    public static function api_method($type,$code,$msg='',$data=array()){
            if(!is_numeric($code)){
                return "system error";
            }
             $result=array(
                'code'=>$code,
                'msg'=>$msg,
                'data'=>$data
            );
            if($type=="json"){
                self::json($code,$msg,$data);
                //exit;
            }
            else if($type=="xml"){
                self::xml_encode($code,$msg,$data);
                exit;

            }
            else if($type=="array"){
                var_dump($result);
            }
            else if($type==""){
                $code="408";
                $msg="error:Invalid ask_method value!";
                $data="null";
                self::json($code,$msg,$data);
                exit;
            }


    }

    /**json method output the data
     * @param $code
     * @param string $msg
     * @param array $data
     * @return string
     */
    public static function json($code,$msg='',$data=array()){

            if(!is_numeric($code)){

                return "system error";

            }
            else{


                $result = array(
                    'code' => $code,
                    'msg' => $msg,
                    'data' => $data
                 );
                echo json_encode($result);
                //exit;
            }
    }

    /**xml method output the data
     * @param $code
     * @param string $msg
     * @param array $data
     * @return string
     */
    public static function xml_encode($code,$msg='',$data=array()){
        if (!is_numeric($code)) {

            return "system error";

        }
        else{
                $result = array(
                    'code' => $code,
                    'msg' => $msg,
                    'data' => $data

                );
            }
        header("Content-Type:text/xml");
        $xml = "<?xml version=1.0 encoding=Utf-8 ?>";
        $xml.= "<project>";
        $xml.=self::xml($result);
        $xml.= "</project>";
        return $xml;
    }
    public static function xml($result){
        foreach($result as $key=>$value) {
            $xml.="<{$key}>";
            $xml.=is_array($value)?self::xml($value):$value;
            $xml.="</{$key}>\n";

        }

        return $xml;
    }


}


?>