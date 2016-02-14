<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	$nameOfPage = 'Черный список';
	require_once('menu_top.php');//подключаем верх меню
	
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	$query = mysqli_query($link,"SELECT id,girl_login,blacklist FROM users");
	echo '<div id="blackInfo">';
	while( $k = mysqli_fetch_row($query) ){
		echo '<div class="info">
				<div class="girl">'.$k[1].'</div>
					<div class="blacklist">
						<form action="addToBlacklist.php" method="POST">
							<input class="invisible" type="checkbox" name="login" value="'.$k[1].'" checked >
							<label for="newBlackId'.$k[0].'">Введите ID</label>
							<input id="newBlackId'.$k[0].'" type="text" name="newBlackId">
								<button class="button" type="submit" name="submit">Добавить в Черный список</button>
						</form>';
		
			if( $k[2] ){//если есть черный список
				$blacklist = explode(',',$k[2]);//разбить строку с черным списком
				//Выводим ЧС/удаляем из ЧС
				echo '<form action="deleteFromBlacklist.php" method="POST">
						<input class="invisible" type="checkbox" name="login" value="'.$k[1].'" checked >';//невидимый чекбокс для отправки айди пользователя
				
					foreach($blacklist as $a){
						echo '<label for="'.$a.'">'.$a.'</label>
						<input id="'.$a.'" type="checkbox" name="blacklist['.$a.']" value="'.$a.'"><br>';
					}
					echo '<button type="submit" name="submit">Удалить</button>
					</form>';
				
				
			}
			echo '</div>
			</div>';
	}
	echo '</div>';
	require_once('menu_botton.php');//конец меню

}else{
	header('Location: login.php');
}

?>