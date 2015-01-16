<!doctype html>
<html lang="en">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
      <link rel="shortcut icon" type="image/x-icon" href="http://res.wx.qq.com/mmbizwap/zh_CN/htmledition/images/icon/common/favicon22c41b.ico">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="black">
      <meta name="format-detection" content="telephone=no">
   <title>Document</title>
</head>
<body>
   <div style='width:500px;margin:0 auto;'>
      <h1 style="text-align:center;">test h1 title</h1>
      <?php 
      
        error_reporting(E_ALL);
            include_once('db.php');
            date_default_timezone_set('PRC');
        $id='ocsJNs_Yc5XnlLgejfy7wvY2B4Ng';
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
      echo $today;
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
            $jb=10;
            break;
          case '2':
            $jb=20;
            break;
          case '3':
            $jb=30;
            break;
          default:
            $jb=30;
            break;
        }//switch
        
        if($lianxu>=$maxqd){
          $data['maxqd']=$lianxu;
          $maxqd=$lianxu;
          $sql="update user2 set maxqd='".$lianxu."where id='".$id."'";
          mysql_query($sql);
        }
         $sql="update user2 set jb=jb+1,lianxu=".$lianxu.",nd=".$today." where id='".$id."'";
         //mysql_query($sql);
         $tpl="签到成功，获得".$jb."金币。你已连续签到".$lianxu."天，最大连续签到天数".$maxqd;
         echo '1';
         echo $tpl;
        

       
         
      ?>
   </div>
</body>
</html>