<?php
	session_start();
	$str = htmlentities(file_get_contents("lib.html"));
	if($_GET['exit']){
		$_SESSION = array();
	}
	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);
    if($_SESSION['id'] != NULL){
    	$sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
    	$result1 = mysqli_fetch_array($sql);
		$str = str_replace ( "%username%" , "<a href = ''>". $result1['username'] . "</a><a href = '/library.php?exit=Выход'>выход</a>" , $str );
	} else {
		$str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    	$_SESSION['url'] = '/library.php';
	}

?>





<?php

	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
    $sql = mysqli_query($link, "SELECT * FROM posts");
    $str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );

    $text = $text . "<h1>Все</h1>";
    while ($result = mysqli_fetch_array($sql)) {
      $text = $text . "<p><a href = 'post.php?id={$result['id']}' >{$result['id']}. {$result['name']}</a></p>";
  	}
  	$str = str_replace('%content%', $text, $str);
  	echo($str);
?>