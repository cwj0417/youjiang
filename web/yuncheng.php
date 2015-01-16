<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
	<title>运程测试结果页面(此标题无效)</title>
	<style>
	body{background: url('./bg_img.png');}
	.container{width: 100%;}
	.uptonow{color: red;font-size: 1.4em;text-align: center;}
	p{margin: 1%;}
	.uptotime{color: #FF4500;font-size: 1.7em;}
	.yc p{margin: 5%;color:#FFEFD5;}
	</style>
</head>
<?php 
$info=$_GET['info'];
$name=$_GET['name'];
if($name==''){$name='我';}
$ts=strtotime($info);//生日时间戳
$uptonow=time()-$ts;//活到现在的秒数
/*算命*/
$tss=strval($ts);
$tmp=0+$tss[2]+$tss[5];
$tmp=strval($tmp);
$a=$tmp[strlen($tmp)-1];
$tmp=0+$tss[2]+$tss[5]+$tss[7]+$tss[8]+$tss[1];
$tmp=strval($tmp);
$b=$tmp[strlen($tmp)-1];
$tmp=0+$tss[6]+$tss[4]+$tss[3]+$tss[1];
$tmp=strval($tmp);
$c=$tmp[strlen($tmp)-1];
$tmp=0+$tss[2]+$tss[5]+$tss[7]+$tss[8]+$tss[6]+$tss[4]+$tss[3]+$tss[1];
$tmp=strval($tmp);
$d=$tmp[strlen($tmp)-1];
switch ($a) {
	case '1':
	$ares="亡羊得牛，失小得大";
	break;
	case '2':
	$ares="饿虎扑羊，迅猛者得";
	break;
	case '3':
	$ares="羝羊触藩，进退两难";
	break;
	case '4':
	$ares="羚羊挂角，祸患避开";
	break;
	case '5':
	$ares="歧路亡羊，方向难寻";
	break;
	case '6':
	case '0':
	$ares="问羊知马，有迹可循";
	break;
	case '7':
	$ares="十羊九牧，无所适从";
	break;
	case '8':
	$ares="三羊开泰，平步青云";
	break;
	case '9':
	$ares="贵人助力，安定祥和";
	break;
}
switch ($b) {
	case '1':
	$bres="愈是能以谦虚的态度待人，愈能顺利的完成你工作目标，得善用你的沟通才可以改善。";
	break;
	case '2':
	case '0':
	$bres="工作上的事都能成功的执行，让大伙儿看到你计划具体可行的一面，信任你的力度更强了。";
	break;
	case '3':
	$bres="你不必立即着手就进行重要的工作计划，其中的变数还多着。";
	break;
	case '4':
	$bres="本周工作运势平平，并不会有太惊人的表现, 多作观察与计划，内心可以得到许多新的领悟。";
	break;
	case '5':
	$bres="多观察下周遭的事务变化，就是小事，如果是运用得当的话是可以给自己带来益处的。";
	break;
	case '6':
	$bres="要注意工作中接触的人，要多避免传出事非，多注意语言的问题。";
	break;
	case '7':
	$bres="愈是能以谦虚的态度待人，愈能顺利的完成你工作目标，本周多有良好的沟通。";
	break;
	case '8':
	$bres="在工作上需注意到友人的不同意见，愈是亲密伙伴，想法上愈容易有偏差，最好是多加沟通。";
	break;
	case '9':
	$bres="做好时间管理，掌握住每一个细节流程，虽然还是会受到外界的困扰，但一定会改变。";
	break;
}
switch ($c) {
	case '1':
	case '6':
	$cres="有的人在这个冬天，爱情天气糟糕透顶。不过，你却恰好相反，因为在这个冬天，恋情会迅速地进入你的生命，你容易遇到一见钟情。刹那间你的天空暖阳高照，如金似蜜的阳光铺满你的人生大道。你也可能会收到意外的惊喜，恋情将会充满了诸多甜蜜色彩。可能偶尔会有一些阴天与雨天，但那只是偶尔的小插曲。";
	break;
	case '2':
	case '7':
	$cres="一进入冬天，你可能就开始自我感觉恋爱状况极佳无比，于是可能会在一开始就进入热恋状态，觉得自己仿佛置身天堂。然而，实际上你会错过爱情的几率却特别高，那些美好的景象可能只是你的想象。过了你一瞬的好感期，爱情就会受到考验啦。是大晴天成了阴雨天，其实你还是需要擦亮眼睛的，不要轻易陷入，任何时候都要走稳一些。";
	break;
	case '3':
	case '8':
	$cres="虽然说有些人，在冬天的爱情天气是阳光万丈，但是不知道是不是你已经进入了一个感情冰河期了，不管遇到什么样的人，不管你喜欢对方还是对方喜欢你，你的感情总是冰天雪地一片，就像冬眠了一般，没有一点儿发展的可能。有时候想一想，你也会有太多东西要考虑，而无法下定决定热情地去接受去爱，去单纯地爱一场。";
	break;
	case '4':
	case '9':
	$cres="这个冬天，你的爱情天气以寒风呼号为主。虽然你在冬天的时候，自认为恋爱运会很不错，结识异性的机缘也挺多，但是因为种种，比如你心里会有忘不了的人，也或许是因为你忙于其他，也可能感情受到了其他外来因素的阻挡。总之你在冬天的爱情天气，是寒风呼号，让你感觉到寒冷、心冷的。";
	break;
	case '5':
	case '0':
	$cres="你一直在寻寻觅觅地找那个人，你也十分渴望爱情，这个冬天，你会遇到一些追求者，也会遇到一些让你心动的人。但是期间，因为不确定对方是否是正确的人那个，由于感情在短时间内的不稳定，你也会抓狂不已，爱情天气将会变得阴晴不定，你的生活更加有可能，会变得忽悲忽喜。";
	break;
}
if($d=='0'){
	$dres="100%";
}else{
$dres=$d."0%";
}
/*算命*/
 ?>
<body>
	<input type="hidden" class='time' value='<?php echo $uptonow; ?>'>
	<input type="hidden" class='name' value='<?php echo $name; ?>'>
	<input type="hidden" class='sum' value='<?php echo $ares; ?>'>
	<!-- 分割线 -->
	<div class="container">
		<div class="uptonow">
			<p><span><?php echo $name; ?>已经在世上度过了</span></p>
			<p class="uptotime"><span></span></p>
		</div>
		<div class="yc">
			<p class="a"><span style="color:white;"><?php echo $name; ?>的2015总体运程是</span><br><center style="font-size:1.6em;color:#EEEE00;font-weight:bolder;"><?php echo $ares; ?></center></p>
			<p class="b"><?php echo $name; ?>在工作上：<?php echo $bres; ?></p>
			<p class="c"><?php echo $name; ?>在感情上：<?php echo $cres; ?><br>被暗恋指数：<?php echo $dres; ?></p>
		</div>
	</div>
	<center><a href="http://mp.weixin.qq.com/s?__biz=MzA5OTgzNTIyMA==&mid=201606115&idx=1&sn=4d9da60664a01e8d6a6f2b7517f9795c#rd" style="text-decoration:none;color:#FF34B3;font-size:1.5em;">点我进行测试</a>
	<img src="yang.png" style="width:100%;"></center>
</body>
</html>
<script>
	var time=$('.time').val();
	var name=$('.name').val();
	var sum=$('.sum').val();
	var e=time.substr(0,time.length-8);
	var w=time.substr(e.length,4);
	var g=time.substr(e.length+4,4);

	$(function(){
		if(parseInt(time)>10000000){
			calsec();
			setInterval("calsec()", 1000);
		}else{
			document.title="我竟然是个妖精！";
			$('.uptonow').html('优酱计算不出你的生辰，你很可能是一个妖精');
		}
		
	})
	function calsec(){
		if(g++==10000){
			w++;g=0000;
			if(w==10000){
				e++;
				w=0000;
			}
		}
		var res=e+'亿'+w+'万'+g+'秒';//res为结果秒数
		document.title=name+'2015的运程是\''+sum+'\'，他已经活了'+res+'了！';
		$('.uptotime').html(res);
	}
</script>