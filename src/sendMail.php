<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() && sendMailcheck($_POST['girlLogin'],$_POST['helloPhrase'],$_POST['mainText'],$_POST['textEnd'],$_POST['pass']) ){
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	$query = mysqli_query($link,"SELECT girl_login,mail_id,girl_pw,blacklist FROM users WHERE girl_login='".$_POST['girlLogin']."'");
	$k = mysqli_fetch_row($query);
	
	if( md5(strip_tags(trim($_POST['pass'] .'stlk94Ankp'))) == $k[2] ){
		//echo 'Подождите немного';
		$mailText = $_POST['mainText'].'<br />'.$_POST['textEnd'].'</p>';//создаем конец сообщения
		
		$searchQuery = mysqli_query($link,"SELECT mail_id,name FROM men WHERE main_id <> '".$k[3]."' ORDER BY last_entrance DESC LIMIT 4000");//отбираем пользователей сайта, сортируя по дате, до 4000;
		
		$allMen = mysqli_fetch_all($searchQuery, MYSQLI_NUM);//массив с пользователями сайта для отправки
		////////////////////////////////

		
		////////////////////////
		enterToSite($k[0],$_POST['pass']);//входим на сайт
		
		$mails = 0;
		foreach($allMen as $qq){//для каждого пользователя
			//echo $qq[0]."<br>";
			sendMail($_POST['helloPhrase'],$qq[1],$mailText,$qq[0],$_POST['girlLogin']);//отправляем сообщение для каждого пользователя
			//echo "<br/>";
			sleep(2);//засыпаем на 2 секунды
			$mails++;
		}
		
		//заносим результат в базу
		$stmt = mysqli_prepare($link, "INSERT INTO `send info` VALUES(?,?,?)");
		mysqli_stmt_bind_param($stmt, 'iii',$id,$last_time,$count);
		
		$id = NULL;
		$last_time = time();
		$count = $mails;
		
		mysqli_stmt_execute($stmt);//добавляем пользователя
		
		
		
		
		//curl_close($ch);
		echo '<p>Готово</p>
		<p><a href="index.php">Вернуться на главную</a></p>';
	}else{
		echo 'Проверьте пароль';
	}
	
}else{
	echo 'Напишите все необходимые данные';
}
?>