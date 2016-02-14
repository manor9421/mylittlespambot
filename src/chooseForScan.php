<?php
require_once('func.inc.php');
if( checkCookies() ){
	$nameOfPage = 'Обновление базы';
	require_once('menu_top.php');//подключаем верх меню
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	$query = mysqli_query($link,"SELECT id,girl_login FROM users");
	echo "<form action='scan.php' method='post'>
	<p>Пройдите авторизацию</p><br/>
	<div>Имя аккаунта: </div>";
	while( $k = mysqli_fetch_row($query) ){
		echo '<label for='. $k[0].'>'.$k[1].'</label>
		<input id='.$k[0].' type="radio" name="girlLogin" value='.$k[1].'><br>';
	}
	echo 'Введите пароль: <input type="password" name="pw"><br />
	<div><button type="submit" name="submit">Начать сканирование</button></div>
	</form>';
	require_once('menu_botton.php');//конец меню
}else{
	header('Location: login.php');
}
?>
