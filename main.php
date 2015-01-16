<?php
define("TOKEN", "weixin");
$wechatObj = new JinWechatApi();
//$wechatObj->valid();
$wechatObj->jieshou();

class JinWechatApi
{   
    private $ToUserName;
    private $FromUserName;
    private $CreateTime;
    private $MsgType;
    private $Content;
    private $voice;
    private $MsgId;
    private $xmlObj;
    private $restext;
    private $nickname;
    public function __construct(){
        $postxml=$GLOBALS['HTTP_RAW_POST_DATA'];
        $this->xmlObj=simplexml_load_string($postxml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->ToUserName=$this->xmlObj->ToUserName;
        $this->FromUserName=$this->xmlObj->FromUserName;
        $this->CreateTime=$this->xmlObj->CreateTime;
        $this->MsgType=$this->xmlObj->MsgType;
        $this->Content=$this->xmlObj->Content;
        $this->MsgId=$this->xmlObj->MsgId;
        $this->voice=$this->xmlObj->Recognition;
        include_once("db.php");
        $sql="select nickname from user where id='".$this->FromUserName."'";
        $this->nickname=mysql_fetch_row(mysql_query($sql));
        $this->nickname=$this->nickname[0];
    }

    public function jieshou(){
        if($this->nickname){
            $this->restext=$this->nickname."你好，";
        }else{
            $this->nickname='';
            $this->restext="(回复nc获得昵称)";
        }
        switch ($this->MsgType) {
            case 'text':
            $this->responsetext();
                break;
            case 'voice':
            $this->responsevoice();
                break;
            case 'event':
            $this->event();
            break;
            
        }
        
    }
    public function replytext($content){
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $this->FromUserName, $this->ToUserName, time(), "text", $content);
                    echo $resultStr;

    }
     public function tuwen($title,$des,$img,$url){
        $tuwenTpl=   "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                        <item>
                        <Title><![CDATA[%s]]></Title> 
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl> 
                        <Url><![CDATA[%s]]></Url>
                        </item>
                        </Articles>
                        <FuncFlag>0</FuncFlag>
                        </xml> ";
        $resultStr = sprintf($tuwenTpl, $this->FromUserName, $this->ToUserName, time(),$title,$des,$img,$url);
        echo $resultStr;
    }
    public function responsetext(){
        $this->getrestext();
        $this->replytext($this->restext);
    }
    public function responsevoice(){
        $tmp=$this->voice;
        $this->restext.="优酱听到了你说:".$tmp."。";
        $this->getconfvoi();
        $this->replytext($this->restext);
    }
    public function getrestext(){
        $rep=$this->Content;
        $tmp=explode(' ', $rep);
        $lable=$tmp[0];
        switch ($lable) {
            case '昵称': 
                $this->nickname();//传入昵称
                break;
            case '咨询':
                $this->replytext("您的问题已受到，专业律师将会在一个工作日内做出回复");
                break;
            case '运程':
                $this->yuncheng();
                break;
            case 'h':
                $this->restext="回复nc设置昵称，回复yc分析运程，回复zx进行免费法律咨询";
                break;
            case 'zx':
                $this->restext="回复以下格式进行咨询：
咨询+空格+咨询内容
如：咨询 老板拖欠工资怎么办？";
                break;
            case 'nc':
                $this->restext="回复以下格式获得昵称：
昵称+空格+你的昵称
如：昵称 小马的爸爸";
                break;
            case 'yc':
                $this->restext="回复以下格式进行分析：
运程+空格+咨询内容
如：运程 19530602";
                break;
            default:
                $this->getconftxt();
                break;
        }
    }
    public function event(){
        $ev=$this->xmlObj->Event;
        if($ev=='subscribe'){
            $this->restext.="欢迎关注优酱，回复nc设置昵称吧~";
            $this->replytext($this->restext);
        }
    }
    public function getconftxt(){//获取默认回复
        include_once('reply.conf.php');
        foreach ($txtcon as $k => $v) {
            if($this->Content==$k){
                $this->restext=$v;
                $this->replytext($this->restext);
                exit;
            }
        }
        $this->restext.='回复h获得帮助';
        $this->replytext($this->restext);
        
    }
    public function getconfvoi(){
        include_once('reply.conf.php');
        foreach ($voicon as $k => $v) {
            if($this->voice==$k){
                $this->restext.=$v;
            }
        }
        if($this->restext==$this->nickname."你好，优酱听到了你说:".$this->voice."。"){
            $this->restext.='没有听清楚您的判决，请再告诉我一边吧~';
        }
        
    }
    public function nickname(){
        $nick=substr($this->Content, 7);
        if ($nick==''||$nick==' '||$nick=='  ') {
            $this->replytext('昵称不能为空请重新设置');
            exit;
        }
        include_once("db.php");
        $sql="insert into user(id,nickname) values('".$this->FromUserName."','".$nick."')";
        if(!mysql_query($sql)){
        $sql="update user set nickname='".$nick."' where id='".$this->FromUserName."'";
        mysql_query($sql);
        $this->restext="修改昵称成功";
        }else{$this->restext="设置昵称成功";}
        $this->replytext($this->restext);
    }
    public function yuncheng(){
        $dateinfo=substr($this->Content, 7);
        if (strlen($dateinfo)!=8) {
            $this->replytext("你输入的格式不正确，请输入：运程 yyyymmdd");
        }
        $title=$this->nickname."-3-分析完毕结果不错哦，点击查看结果吧-w-";
        $des="优酱不会收集任何个人信息";
        $img="http://42.159.102.250/youjiang/web/linkimg.jpg";
        $url="http://42.159.102.250/youjiang/web/yuncheng.php?info=".$dateinfo."&name=".$this->nickname;
        $this->tuwen($title,$des,$img,$url);
    }
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

	private function checkSignature(){
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