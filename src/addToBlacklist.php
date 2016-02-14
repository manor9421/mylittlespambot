<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	$query = mysqli_query($link,"SELECT blacklist FROM users WHERE girl_login='".$_POST['login']."'");//получаем черный список
	$blacklist = mysqli_fetch_row($query)[0];//получаем строку с черным списком
	
	if( $blacklist ){//если черный список есть
		$array = explode(',',$blacklist);//получаем массив с черным списком
		if( in_array((int)$_POST['newBlackId'],$array) ){//если уже есть такой АйДи
			header('Location: blacklist.php');
		}else{//если еще нет такого пользователя в черном списке
			$newBlacklist = $blacklist.','.(int)$_POST['newBlackId'];//дописываем строку в черный список
			$query = mysqli_query($link,"UPDATE users SET blacklist='".$newBlacklist."' WHERE girl_login='".$_POST['login']."'");
			header('Location: blacklist.php');
		}
	}else{//если нет ЧС
		$query = mysqli_query($link,"UPDATE users SET blacklist='".(int)$_POST['newBlackId']."' WHERE girl_login='".$_POST['login']."'");
		header('Location: blacklist.php');
	}
	
}else{
	header('Location: login.php');
}

?>