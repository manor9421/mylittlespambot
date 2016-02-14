<?php
if( isset( $_POST['submit']) ){
	if(preg_match("/^[a-zA-Z0-9]+$/",$_POST['login'])){//проверяем логин
		require_once('func.inc.php');
		if( strip_tags(trim($_POST['login'])) == checkPW()[0] && md5(strip_tags(trim($_POST['pw'] .'stlk94Ankp'))) == checkPW()[1] ){
			setcookie("login",$_POST['login'], time()+60*60*12);//на 12 часов
			setcookie("hash",md5(strip_tags(trim($_POST['pw'] .'stlk94Ankp'))), time()+60*60*12);
			header("Location: index.php");
		}else{
			header("Location: index.php");
		}
	}else{
		header("Location: index.php");
	}
}else{
	header("Location: index.php");
}

?>
