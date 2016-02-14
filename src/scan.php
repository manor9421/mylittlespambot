<?php
header("Content-Type: text/html; charset=utf-8");
require_once('func.inc.php');

if( checkCookies() ){
	
	if( checkGirl($_POST['girlLogin'],$_POST['pw']) ){
		$ch = curl_init(); //инициализация библиотеки
		curl_setopt($ch, CURLOPT_URL,'/*https*/');//КУДА
		//curl_setopt($ch, CURLOPT_PROXY,'31.28.11.166:3128');//Прокси
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);//cURL должен переходить по редиректам
		curl_setopt($ch, CURLOPT_REFERER, '/*http*/');//Откуда
		curl_setopt($ch, CURLOPT_POST, TRUE);//отправляем данные методом post
		
		$postLoginFields = [];
		$postLoginFields['login'] = $_POST['girlLogin'];
		$postLoginFields['password'] = $_POST['pw'];
		$postLoginFields['clienttime'] = time();
		$postLoginFields['__tcAction'] = 'loginMember';
		$postLoginFields['submit.x'] = 52;
		$postLoginFields['submit.y'] = 5;
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postLoginFields));//добавляем строку с POST данными
		//сохраняем куки
		$file = dirname(__FILE__).'/'.$_POST['girlLogin'].'.txt';//берем пользовательский аккаунт для кук
		//$file = dirname(__FILE__).'/cookie.txt';
		curl_setopt($ch, CURLOPT_COOKIEJAR, $file);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		
		curl_setopt($ch, CURLOPT_URL,'/*http*/');//Куда
		//curl_setopt($ch, CURLOPT_PROXY,'31.28.11.166:3128');//Прокси
		
		$search_page = curl_exec($ch);
		
		//получаем количество страниц
		$last_enter = strrpos($search_page,'online_dropdown=');//позиция последнего вхождения
		$num = strpos($search_page,'page=',$last_enter);
		$num_len = strpos($search_page,'&',$num);
		$last_page_num = substr($search_page,$num+5,$num_len-$num) * 1;//количество страниц
		
		
		
		$menData = [];
		for($j = 1; $j <= $last_page_num; $j++){//сканируем от 1-й до последней страницы
			curl_setopt($ch, CURLOPT_URL,'/*http:*/?online=men&page='.$j.'&');//КУДА
			//curl_setopt($ch, CURLOPT_PROXY,'31.28.11.166:3128');//Прокси
			
			
			
			$html = curl_exec($ch);
			$menData = menScan($html,$menData);//сканируем пользователей сайта
		}
		curl_close($ch);
		//echo $html;
		menDataSave($menData);//сохраняем/обновляем данные о пользователе
		fopen($file, "w");//удаляем куки пользователя
		echo "Все готово";
		
	}else{
		header('Location: chooseForScan.php?page=Oshibka');
	}
	
}else{
	header("Location: login.php");
}