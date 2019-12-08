<?php
	session_start();
  
	$str = htmlentities(file_get_contents("lib.html"));
  $menu = htmlentities(file_get_contents('menu.html'));
  $str = str_replace ( "&lt;" , "<" , $str );
  $str = str_replace ( "&gt;" , ">" , $str );
  $str = str_replace ( "&quot;" , '"' , $str );
  $menu = str_replace ( "&lt;" , "<" , $menu );
  $menu = str_replace ( "&gt;" , ">" , $menu );
  $menu = str_replace ( "&quot;" , '"' , $menu );

  $str = str_replace("<body>", "<body>". $menu, $str);
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
      $user = "<a href = ''>". $result1['username'] . "</a><a href = '/library.php?exit=Выход'>выход</a>";
      if($result1['roots'] == '2'){
        $user .= "<a href = 'admin.php'>Админочная</a>";
      }
		$str = str_replace ( "%username%" , $user , $str );
	} else {
		$str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    	$_SESSION['url'] = '/library.php';
	}
?>