<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	if( $_POST['blacklist']){
		$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
		if (!$link)
			die("Невозможно подключиться к MySQL: " . mysql_error());
		$query = mysqli_query($link,"SELECT blacklist FROM users WHERE girl_login='".$_POST['login']."'");//получаем черный список
		$blacklist = mysqli_fetch_row($query)[0];//получаем строку с черным списком
		
		
		if( $blacklist != NULL ){//если черный список есть
			$array = explode(',',$blacklist);//получаем массив с черным списком
			$toDelete = $_POST['blacklist'];//получаем массив с теми, кого нужно удалить
				foreach( $toDelete as $q ){
					$key = array_search($q,$array);//узнаем ключ такого элемента
					unset($array[$key]);//удаляем элемент с таким ключом из массива
				}
			
			$newBlacklist = implode(',',$array);//формируем строку с новым черным списком
			$query = mysqli_query($link,"UPDATE users SET blacklist='".$newBlacklist."' WHERE girl_login='".$_POST['login']."'");
				
			header('Location: blacklist.php');
		}else{//если нет ЧС
			header('Location: blacklist.php');
		}
	}else{//если нет необходимых для удаления людей
		
	}
}else{
	header('Location: login.php');
}
?>