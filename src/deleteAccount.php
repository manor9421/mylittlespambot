<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');
if( checkCookies() ){
	$log = $_GET['login'];
	
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);

	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	
	$query = mysqli_query($link,"DELETE FROM users WHERE girl_login='".$log."'" );
	$newFile = dirname(__FILE__).'/'.$_GET['login'].'.txt';
	unlink($newFile);//удаляем файл для кук
	
	header('Location: accounts.php');
	
}else{
	header("Location: login.php");
}
?>