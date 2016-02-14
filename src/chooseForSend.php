<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	$nameOfPage = 'Отправка писем';
	require_once('menu_top.php');//подключаем верх меню
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	$query = mysqli_query($link,"SELECT id,girl_login FROM users");
	echo '<div id="mainDiv">
			<div>
				<form id="forSend" action="sendMail.php" method="POST">
					<div>Имя аккаунта: </div>';
					while( $k = mysqli_fetch_row($query) ){
						echo '<label for='. $k[0].'>'.$k[1].'</label>
						<input id='.$k[0].' type="radio" name="girlLogin" value='.$k[1].'><br>';
					}
				echo '<label for="pass">Пароль: </label>
						<input type="password" id="pass" name="pass"><br/>
						<label for="helloPhrase">Фраза приветствия: </label>
						<input type="text" id="helloPhrase" name="helloPhrase"><span>, Name.</span><br />
						<label for="mainText">Сообщение: </label><br />
						<textarea id="mainText" name="mainText" cols="80" rows="12"></textarea><br />
						<label for="textEnd">Подпись: </label>
						<input type="text" id="textEnd" name="textEnd">
					<div><button type="submit" name="submit">Отправить</button></div>
				</form>
			</div>
		</div>';
		require_once('menu_botton.php');//конец меню
}else{
	header('Location: login.php');
}
?>