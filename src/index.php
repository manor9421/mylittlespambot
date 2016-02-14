<?php

require_once('func.inc.php');

if( checkCookies() ){
	$nameOfPage = 'Главная';
	require_once('menu_top.php');
	echo '<a id="savedb" href="sxd/index.php"><div>Скачать базу</div></a>';
	require_once('menu_botton.php');
	
	
	
	
}else{
	header("Location: login.php");
}
?>