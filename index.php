<?php

//
// 接收用户消息
// 微信公众账号接收到用户的消息类型判断
//

define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
						$Eventkey =$postObj->EventKey
            //用户发送的消息类型判断
            switch ($RX_TYPE)
            {
                case "text":    //文本消息
                    $result = $this->receiveText($postObj);
                    break;
                case "image":   //图片消息
                    $result = $this->receiveImage($postObj);
                    break;

                case "voice":   //语音消息
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":   //视频消息
                    $result = $this->receiveVideo($postObj);
                    break;
                case "location"://位置消息
                    $result = $this->receiveLocation($postObj);
                    break;
                case "link":    //链接消息
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
       
    }
     private function receiveEvent($object)
    {
        $contentStr = "";
         if($type=="CLICK" and $EventKey=="normal"){
         	$msgType="text";
        	$contentStr[] = array("Title" =>"普通科室", 
                        "Description" =>"本科室为普通病人提供在线咨询服务,这里使用图灵机器人代我回答", 
                        "PicUrl" =>"http://1.wxphpcheck.sinaapp.com/普通科室.jpg", 
                        "Url" =>"http://www.tuling123.com/openapi/cloud/proexp.jsp");
        	}
        	 if($type=="CLICK" and $EventKey=="children"){
        	 	$msgType="text";
        	$contentStr[] = array("Title" =>"儿科", 
                        "Description" =>"本科室为儿童患者提供在线咨询服务,这里使用图灵机器人代我回答", 
                        "PicUrl" =>"http://1.wxphpcheck.sinaapp.com/儿科.jpg", 
                        "Url" =>"http://www.tuling123.com/openapi/cloud/proexp.jsp");
        	}
        	 if($type=="CLICK" and $EventKey=="normal"){
        	 	$msgType="text";
        	$contentStr[] = array("Title" =>"妇科", 
                        "Description" =>"本科室为妇女患者提供在线咨询服务,这里使用图灵机器人代我回答", 
                        "PicUrl" =>"http://1.wxphpcheck.sinaapp.com/妇科.jpg", 
                        "Url" =>"http://www.tuling123.com/openapi/cloud/proexp.jsp");
        	}
        
        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
}
?>