<?php
define("TOKEN", "weixin");
 date_default_timezone_set('PRC');
$wechatObj = new JinWechatApi();

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
    private $event;
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
        $this->event=$this->xmlObj->Event;
        include_once("db.php");
        $sql="select nickname from user2 where id='".$this->FromUserName."'";
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
        $ev=$this->event;
        if($ev=='subscribe'){
            $this->restext.="欢迎关注优酱，回复nc设置昵称吧~";
            $this->replytext($this->restext);
            exit;
        }
        if($ev=='CLICK'){
            switch ($this->xmlObj->EventKey) {
                    case 'fn_search':
                    $this->replytext('糟糕！搜索法律条文功能被暂时封印了……');
                    break;
                    case 'fn_case':
                    $title='刑法学教授刘宪权谈“复旦投毒案”二审';
                    $des='“复旦投毒案”宣判后，记者第一时间采访了刘宪权教授。针对林森浩的辩护人提出的故意伤害罪、过失致人死亡罪的说法，刘宪权作了细致分析，并表示这两种说法都难以成立。';
                    $img='http://mmbiz.qpic.cn/mmbiz/TsfFzfGGFpicia9QUAPu70N69GriaiauxkxokARcPCPz3QjYSQldoR6AEtT2h5fXwO2fopqnfGSl51kjgsWVQzKGEw/0?tp=webp';
                    $url='http://mp.weixin.qq.com/s?__biz=MzAwODIxNzcxNA==&mid=202519832&idx=1&sn=c363eca746c6993d2a8faf95e18df562#rd';
                    $this->tuwen($title,$des,$img,$url);
                    break;
                    case 'fn_monthly':
                    $this->replytext('非常抱歉，服务器升级维护中');
                    break;
                    case 'fn_daily':
                    include_once('reply.conf.php');
                    $this->restext=$daily."

回复小写字母答题";
                    $this->replytext($this->restext);
                    break;
                    case 'fn_signin':
                    $this->signin();
                    break;
                    case 'fn_info':
                    $this->getinfo();
                    break;
               
            }
        }
    }
    public function signin(){

        include_once('db.php');
        $id=$this->FromUserName;
        $sql="select*from user2 where id='".$id."'";
        $res=mysql_query($sql);
        $tmp=mysql_fetch_row($res);
        if($tmp==false){
            $this->replytext('请先回复nc设置昵称');
            exit;
        }
        $nickname=$tmp['1'];
        $jb=$tmp['2'];
        $lianxu=$tmp['3'];
        $nd=$tmp['4'];
        $maxqd=$tmp['5'];
        $today=date('Ymd',time());
      $yesterday=date('Ymd',time()-3600*24);
      if($nd==$today){
      $this->replytext('今天已经签过到了，优酱等你明天再来~');
      exit;
      }//今天已经签到过了

      if($nd==$yesterday){
        $lianxu++;
      }else{
        $lianxu=1;
      }
        switch ($lianxu) {
          case '1':
            $jb=1;
            break;
          case '2':
            $jb=2;
            break;
          case '3':
            $jb=3;
            break;
          default:
            $jb=3;
            break;
        }//switch
        
        if($lianxu>=$maxqd){
          $maxqd=$lianxu;
          $sql="update user2 set maxqd=".$lianxu." where id='".$id."'";
          mysql_query($sql);
        }
         $sql="update user2 set jb=jb+".$jb.",lianxu=".$lianxu.",nd=".$today." where id='".$id."'";
         mysql_query($sql);
         $tpl="签到成功，获得".$jb."个抹茶蛋糕。你已连续签到".$lianxu."天，最大连续签到天数".$maxqd."天";
         $this->replytext($tpl);

    }
    public function getinfo(){
        include_once('db.php');
        $id=$this->FromUserName;
        $sql="select*from user2 where id='".$id."'";
        $res=mysql_query($sql);
        $tmp=mysql_fetch_row($res);
        if($tmp==false){
            $this->replytext('回复nc设置昵称以后将看到自己的个人信息');
            exit;
        }
        $nickname=$tmp['1'];
        $jb=$tmp['2'];
        $maxqd=$tmp['5'];
        $img='http://42.159.102.250/youjiang/web/mingpian.jpg';
        $url='http://42.159.102.250/youjiang/web/mingpian.php?name='.$nickname.'&jb='.$jb.'&maxqd='.$maxqd;
        $this->tuwen($nickname.'的名片','点击进入名片',$img,$url);
    }
    public function getconftxt(){//获取默认回复
        include_once('reply.conf.php');
        $rep=$this->Content;
        if($rep=='a'||$rep=='b'||$rep=='c'||$rep=='d'){
            include_once('db.php');
        $id=$this->FromUserName;
        $sql="select*from user2 where id='".$id."'";
        $res=mysql_query($sql);
        $tmp=mysql_fetch_row($res);
        if($tmp==false){
            $this->replytext('回复nc设置昵称以后答题将获得奖励');
            exit;
        }
        $jb=$tmp['2'];
        if($jb==0){
            $this->replytext('很抱歉你已经没有抹茶蛋糕了不能答题,签到可获得抹茶蛋糕');
            exit;
        }
        $today=date('Ymd',time());
        if($today==$tmp['6']){
            $this->replytext('你已经回答正确过问题了~明天继续吧！');
            exit;
        }
        if($rep==$corr_ans){
            $sql="update user2 set jb=jb+2,dailytag=".$today." where id='".$id."'";
            mysql_query($sql);
            $this->restext="恭喜你答对了，优酱送给你两个抹茶蛋糕-v-，来看看解析吧。";
            $this->restext.="

".$analyze;
            $this->replytext($this->restext);
            exit;
        }else{
            $sql="update user2 set jb=jb-1 where id='".$id."'";
            mysql_query($sql);
            $this->replytext('嘿嘿你答错了，优酱很生气地吃掉了一个你的抹茶蛋糕哦~');
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
        $sql="insert into user2(id,nickname) values('".$this->FromUserName."','".$nick."')";
        if(!mysql_query($sql)){
        $sql="update user2 set nickname='".$nick."' where id='".$this->FromUserName."'";
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