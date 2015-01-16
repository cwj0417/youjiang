<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>文本/语音消息回复配置</title>
   <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
   <style>
   .container{width:1000px;margin: 0 auto;background:#efffff;}
   .content{width:1000px;min-height: 500px;resize:none;}
   </style>
</head>
<body>
   <div class="container">
      <div><h1>文本/语音消息回复配置</h1></div>
      <textarea class="content"><?php echo file_get_contents('reply.conf.php'); ?></textarea>
      <button class="submit">修改</button>
   </div>
</body>
</html>
<script>
   $(function(){
      $('.submit').click(function(){
         var tmp=prompt('你QQ是多少啊');
         if(tmp!=498929578){
            alert('滚去看a片吧');
            location.href='http://porn.com';
            return;
         }
         var cont=$('.content').val();
         console.log(cont);
         $.post('changeconf.php',{cont:cont},function(data){
            if(data=='success'){
               alert('成功了');
            }else{
               alert('失败了，请联系15001945465');
            }
         })
      })
   })
</script>