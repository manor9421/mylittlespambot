<?php
ignore_user_abort(true);//выполнять скрипт при закрытии браузера

define("DB_SERVER", "localhost");
define("DB_USER", "person");//myadmin   person
define("DB_PW", "VgH19PQt21");//qq2397GhP92    p1923      VgH19PQt21
define("DB_NAME", "/*MyDB*/");
$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);


function checkPW(){
	return explode(':',file_get_contents('.htpasswd'));
}

function checkCookies(){
	$info = explode(':',file_get_contents('.htpasswd'));
	if($_COOKIE['login'] == $info[0] && $_COOKIE['hash'] == $info[1] )
		return true;
}

function checkGirl($log,$pw){
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysqli_error());
	$query = mysqli_query($link,"SELECT girl_login,girl_pw FROM users WHERE girl_login ='".$log."'");
	$girlInfo = mysqli_fetch_row($query);
	if( $girlInfo[0] == $log && $girlInfo[1] == md5($pw .'stlk94Ankp') )
		return TRUE;
}

function menScan($html,$arr){
	$start = 0;
	for($i = 0; $i <= 14; $i++){//пользователи на странице
		$a = [];//массив для каждого пользователя
		$first_info_pos = strpos($html, 'class=\'la\'',$start);
		$id_start = strpos($html, 'href=',$first_info_pos) + 7;//начало айди
		$id_end = strpos($html,'.html',$id_start);//конец айди
		$main_id = substr($html, $id_start, $id_end - $id_start) * 1;//айди
		array_push($a,$main_id);
		
		$name_start = strpos($html,'>',$id_end) + 1;//начало имени
		$name_end = strpos($html,'<',$name_start);//Конец имени
		$name = substr($html, $name_start, $name_end - $name_start);//Имя
		array_push($a,$name);

		$first_pos = strpos($html,'?receiver=',$name_end);//начало айди для рассылки
		$last_pos = strpos($html,'\'',$first_pos);//конец айди
		$id_len = $last_pos - $first_pos - 10;//длина айди
		$mess_id = substr($html,$first_pos+10,$id_len) * 1;//айди для рассылки
		array_push($a,$mess_id);
		
		if($mess_id == 0)//если нет больше пользователей
				break;
		array_push($arr,$a);
		$start = $last_pos;
	}
	return $arr;
}

function menDataSave($arr){
	$link = mysqli_connect(DB_SERVER, DB_USER, DB_PW,DB_NAME);
	
	if (!$link)
		die("Невозможно подключиться к MySQL: " . mysql_error());
	
	//
	$qt = mysqli_query($link,"SELECT COUNT(id) FROM men");
	$qtRes = mysqli_fetch_array($qt)[0];
	//
	
	$len = count($arr);
	$k = 0;
	$new = 0;
	$old = 0;
	while($k <= $len){
		$query = mysqli_query($link,"SELECT COUNT(main_id) FROM men WHERE main_id='".$arr[$k][0]."'");
		
		
		//проверяем есть ли такой пользователь в базе
		if(mysqli_fetch_array($query)[0]>0){
			$stmt = mysqli_prepare($link, "UPDATE men SET last_entrance = ? WHERE main_id = '".$arr[$k][0]."'");
			$time = time();
			mysqli_stmt_bind_param($stmt, 'i',$time);
			
			mysqli_stmt_execute($stmt);//обновляем ему время
			$old++;
		}else{//если такого пользователя нет
			$stmt = mysqli_prepare($link, "INSERT INTO men VALUES(?,?,?,?,?)");
			mysqli_stmt_bind_param($stmt, 'iiisi',$usrId,$mainId,$mailId,$name,$time);
			
			$usrId = NULL;
			$mainId = $arr[$k][0];
			$mailId = $arr[$k][2];
			$name = $arr[$k][1];
			$time = time();
			
			mysqli_stmt_execute($stmt);//добавляем пользователя
			$new++;
		}
		$k++;
	}
	
	//обновляем данные о пользователях
	$stmt = mysqli_prepare($link, "INSERT INTO `scan result` VALUES(?,?,?,?,?)");
	mysqli_stmt_bind_param($stmt, 'iiiii',$newId,$newTime,$newCount,$newNew,$oldNew);
	
	$newId = NULL;
	$newTime = time();
	$newCount = $new + $qtRes;
	$newNew = $new;
	$oldNew = $old;
	
	mysqli_stmt_execute($stmt);
	
	
}

function sendMailcheck($log,$helloPhrase,$mainText,$textEnd,$pass){//проверка для отправки
	if($log && $helloPhrase && $mainText && $textEnd && $pass)
		return true;
}

function enterToSite($login,$password){
	$ch = curl_init(); //инициализация библиотеки
	curl_setopt($ch, CURLOPT_URL,'/*https*/');//КУДА
	//curl_setopt($ch, CURLOPT_PROXY,'31.28.11.166:3128');//Прокси
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//cURL должен переходить по редиректам
	curl_setopt($ch, CURLOPT_REFERER, '/*http*/');//Откуда
	curl_setopt($ch, CURLOPT_POST, TRUE);//отправляем данные методом post
	
	$postLoginFields = [];
	$postLoginFields['login'] = $login;
	$postLoginFields['password'] = $password;
	$postLoginFields['clienttime'] = time();
	$postLoginFields['__tcAction'] = 'loginMember';
	$postLoginFields['submit.x'] = 52;
	$postLoginFields['submit.y'] = 5;
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postLoginFields));//добавляем строку с POST данными
	//сохраняем куки
	$file = dirname(__FILE__).'/'.$login.'.txt';
	curl_setopt($ch, CURLOPT_COOKIEJAR, $file);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $file);
	//echo curl_exec($ch);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
}

function sendMail($helloPhrase,$name,$mailText,$mailId,$girlLog){
	$mail = '<p>'.$helloPhrase.', '.$name.'<br />'.$mailText;
	//Входим
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'/*http*/'.$mailId);//КУДА
	//curl_setopt($ch, CURLOPT_PROXY,'31.28.11.166:3128');//Прокси
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0');
	curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));///////////
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//cURL должен переходить по редиректам
	curl_setopt($ch, CURLOPT_REFERER, '/*http*/receiver='.$mailId);//Откуда
	curl_setopt($ch, CURLOPT_POST, TRUE);//отправляем данные методом POST
	$file = dirname(__FILE__).'/'.$girlLog.'.txt';
	//curl_setopt($ch, CURLOPT_COOKIEJAR, $file);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $file);
	
	$d = array(
		'blockGirl'=>'0',
		'draftid' => rand(100000000, 999999999),
		'receiver'=> $mailId,
		'sender'=>'2482398',
		'replyId'=>'',
		'which_message'=>'advanced_message',
		'plain_message'=>'',
		"message"=> $mail,
		'selected_photo'=>'',
		'attachment'=>'',
		'video_attachment' =>'',
		'__tcAction[Send]'=>'Send'
		);//случаное число в 9-10 знаков
		
	curl_setopt($ch, CURLOPT_POSTFIELDS, $d);//готовим к отправке запрос
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);//отправляем запрос
	
	
	
}

?>