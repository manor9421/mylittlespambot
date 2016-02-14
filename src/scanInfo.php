<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	$nameOfPage = 'Отчет о сканированиях';
	require_once('menu_top.php');//подключаем верх меню
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	
	
	//Отправка
	$query = mysqli_query($link,"SELECT id,time_end,sended FROM `send info` ORDER BY id DESC LIMIT 4");
	
	$result = mysqli_fetch_all($query,MYSQLI_NUM);
	echo "Отправлено <br />
			<table id='sended'>
			<tr> 
				<th>Номер</th><th>Время окончания</th><th>Писем отправлено</th>
			</tr>";
	
	foreach( $result as $a ){
		echo '<tr>
				<td>'.$a[0].'</td><td>'.date("Y.m.d H:i:s",$a[1]).'</td><td>'.$a[2].'</td>
			</tr>';
	}
	echo '</table><br />';
	
	//Сканирования
	$query = mysqli_query($link,"SELECT id,time,count,new,old FROM `scan result` ORDER BY id DESC LIMIT 8");
	
	$result = mysqli_fetch_all($query,MYSQLI_NUM);
	
	//var_dump($result);
	echo 'Сканирования <br />
			<table>
				<tr> 
					<th>Номер</th><th>Время</th><th>Количество в базе</th><th>Новых</th><th>Обновленных</th>
				</tr>';
	foreach( $result as $a ){
		echo '<tr>
				<td>'.$a[0].'</td><td>'.date("Y-m-d H:i:s",$a[1]).'</td><td>'.$a[2].'</td><td>'.$a[3].'</td><td>'.$a[4].'</td>
				
			</tr>';
		
	}
	echo '</table>';
	
	require_once('menu_botton.php');//конец меню
}else{
	header('Location: login.php');
}



?>

	