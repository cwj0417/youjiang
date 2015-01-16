<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
	<title></title>
	<style>
	body{background: #efffff;}
	.container{width: 100%;}
	.info{width: 80%;margin:0 auto;font-size: 22px;}
	.jb{width: 100%;color: #9ACD32;text-indent: 2em;font-size: 18px;}
	.pic{margin:0 auto;width: 100%;}
	.dg{float: left;width: 55px;height: 55px;}
	.dg1{background: url('1.png') no-repeat;background-size: 55px;}
	.dg16{background: url('16.png') no-repeat;background-size: 55px;}
	</style>
</head>
<?php 
$nickname=$_GET['name'];
$jb=$_GET['jb'];
$maxqd=$_GET['maxqd'];
 ?>
<body>
	<input type="hidden" class='nickname' value='<?php echo $nickname; ?>'>
	<input type="hidden" class='jb' value='<?php echo $jb; ?>'>
	<input type="hidden" class='maxqd' value='<?php echo $maxqd; ?>'>
	<!-- 分割线 -->
	<div class="container">
		<div class="info">
			<?php echo $nickname; ?>(最大连续签到<?php echo $maxqd; ?>天)
		</div>
		<div class="jb">
			<p>你拥有的抹茶蛋糕：<?php echo $jb; ?></p>
			<div class="pic"></div>
		</div>
	</div>
	
</body>
</html>
<script>
	var nickname=$('.nickname').val();
	var jb=$('.jb').val();
	var maxqd=$('.maxqd').val();
	$(function(){
		document.title=nickname+'的名片';
		if (jb<16) {
			for (var i=0;i<jb;i++) {
				$('<div></div>').addClass('dg dg1').appendTo($('.pic'));
			};
		}else{
			var big=parseInt(jb/16);
			var single=jb%16;
			for (var i=0;i<big;i++) {
				$('<div></div>').addClass('dg dg16').appendTo($('.pic'));
			};
			for (var i=0;i<single;i++) {
				$('<div></div>').addClass('dg dg1').appendTo($('.pic'));
			};
		};
		
	})
	
</script>