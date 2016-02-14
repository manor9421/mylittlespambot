<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	header('Location: index.php');
}else{
	$nameOfPage = 'Авторизация';
	echo '<!DOCTYPE HTML>
			<html lang="en-US">
			<head>
				<meta charset="UTF-8">
				<title>'.$nameOfPage.'</title>
				<link href="style.css" type="text/css" rel="stylesheet">
				<link rel="shortcut icon" href="favicon.ico">
			</head>
			<body>
			<div id="maindiv">
				<form id="login" action="cookie.php" method="post">
					<p>Пройдите авторизацию:</p>
					<div>
						<label for="txtUser">Логин</label>
						<input id="txtUser" type="text" name="login" />
					</div>
					<div>
						<label for="txtString">Пароль</label>
						<input id="txtString" type="password" name="pw" />
					</div>
					<div>
						<button type="submit" name="submit">Войти</button>
					</div>	
				</form>
			</div></body></html>';
	}

?>