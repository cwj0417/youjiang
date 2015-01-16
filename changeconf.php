<?php 
if(file_put_contents('reply.conf.php', $_POST['cont'])){
	echo 'success';
}else{
	echo 'fail';
}
 ?>