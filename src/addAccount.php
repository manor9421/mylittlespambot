<?php

header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');
if( checkCookies() ){
	if( $_POST['pw'] && $_POST['pw2'] && $_POST['login'] && $_POST['girlMailId']){
		$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);

		if (!$link)
			die("Невозможно подключиться к MySQL: " . mysql_error());
		
		$stmt = mysqli_prepare($link, "INSERT INTO users VALUES(?,?,?,?,?)");
		mysqli_stmt_bind_param($stmt, 'isiss',$girlId,$girlLogin,$girlMailId,$girlPW,$blacklist);
		
		$girlId = NULL;
		$girlLogin = $_POST['login'];
		$girlMailId = $_POST['girlMailId'];
		$girlPW = md5(strip_tags(trim($_POST['pw'].'stlk94Ankp')));
		$blacklist = NULL;
		
		mysqli_stmt_execute($stmt);//добавляем пользователя
		$newFile = dirname(__FILE__).'/'.$_POST['login'].'.txt';
		fopen($newFile, "w");//создаем для пользователя файл для кук
		header('Location: accounts.php');
		
	}else{
		echo 'Не все данные верны';
	}
}else{
	header("Location: login.php");
}

?>