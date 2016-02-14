<?php
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
					<div id="menu">
						<a href="index.php"><div>Главная</div></a>
						<a href="chooseForScan.php"><div>Быстрое</div></a>
						<a href="chooseForSend.php"><div>Отправка писем</div></a>
						<a href="scanInfo.php"><div>Отчёт</div></a>
						<a href="accounts.php"><div>Добавление/Удаление аккаунтов</div></a>
						<a href="blacklist.php"><div>Чёрный список</div></a>
						<a href="logout.php"><div>Выйти</div></a>
					</div>';
?>