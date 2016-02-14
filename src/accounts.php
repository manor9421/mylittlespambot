<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	$nameOfPage = 'Добавление/Удаление аккаунтов';
	require_once('menu_top.php');//подключаем верх меню
	
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	
	$query = mysqli_query($link,"SELECT girl_login FROM users");
		echo '';//меню
	while( $k = mysqli_fetch_row($query) ){
		echo "<div id='delete'>".$k[0]." <a href='deleteAccount.php?login=".$k[0]."' class='toDelete'> Удалить аккаунт</a></div> <br />";
	}
	echo '<form action="addAccount.php" method="POST">
				<p>Добавить аккаунт</p>
				<div>
					<label for="girlLogin">Логин: </label>
					<input id="girlLogin" type="text" name="login" />
				</div>
				<div>
					<label for="girlMailId">Айди для Рассылки: </label>
					<input id="girlMailId" type="text" name="girlMailId" />
				</div>
				<div>
					<label for="girlPW">Пароль: </label>
					<input id="girlPW" type="password" name="pw" />
				</div>
				<div>
					<label for="girlPW2">Повторите пароль: </label>
					<input id="girlPW2" type="password" name="pw2" />
				</div>
				<div>
					<button type="submit" name="submit">Добавить</button>
				</div>	
			</form>';
		require_once('menu_botton.php');//конец меню
	
	
}else{
	header("Location: login.php");
}
?>