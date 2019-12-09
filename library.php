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

      $sqlnot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `new` = 1 AND `userid` = " . $_SESSION['id']);
      $notifications = mysqli_num_rows($sqlnot);
      $user = "<a href = '/account.php?id=".$_SESSION['id']."'>". $result1['username'] . "(".$notifications.")</a><a href = '/library.php?exit=Выход'>выход</a>";
      if($result1['roots'] == '2'){
        $user .= "<a href = 'admin.php'>Админочная</a>";
      }
      if($result1['roots'] == '1'){

        $host = 'localhost';
        $usr = 'root';
        $pass = 'admin';
        $db_name = 'post';
        $linkMod = mysqli_connect($host, $usr, $pass, $db_name);
        $sql = mysqli_query($linkMod, "SELECT * FROM `moderation`");
        $n = mysqli_num_rows($sql);
        $user .= "<a href = 'moderatornaya.php'>Модераторная(".$n.")</a>";
      }
		$str = str_replace ( "%username%" , $user , $str );
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
    
    $str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );

    //халявные
    $sql = mysqli_query($link, "SELECT * from posts ORDER BY `posts`.`халявность` DESC LIMIT 3");
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(халявность) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%e' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['халявность'] . '(' . mysqli_fetch_row($sql1)[0] . ' голосов)', $str);
      $i++;
    }

    //строгие
    $sql = mysqli_query($link, 'SELECT * FROM `posts` WHERE `халявность` != 0 ORDER BY `халявность` ASC LIMIT 3');
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(халявность) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%s' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['халявность'] . '(' . mysqli_fetch_row($sql1)[0] . ' голосов)', $str);
      $i++;
    }
    //последние комментарии
    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'comments';
    $linkComments = mysqli_connect($host, $user, $pass, $db_name);

    $sql = mysqli_query($linkComments, 'SELECT * FROM `last`');
    $result = mysqli_fetch_array($sql);
    for($i = 1; $i < 11; $i++){
        $sql = mysqli_query($link, 'SELECT * FROM `posts` WHERE `id` = ' . $result[$i]);
        $str = str_replace('%last' . (11 - $i) . '%', '<a href = "/post.php?id=' . $result[$i] . '">' . mysqli_fetch_array($sql)['name'] . '</a>', $str);
    }
    //последние страницы
    $sql = mysqli_query($link, "SELECT * FROM `posts` ORDER BY `posts`.`id` DESC LIMIT 10");
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(халявность) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%new' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a>', $str);
      $i++;
    }



    $str = str_replace('%rand%', rand(0, 100000), $str);
  	echo($str);
?>