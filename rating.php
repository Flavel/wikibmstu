<?php
	session_start();
	ob_start();


	$str = htmlentities(file_get_contents("rating.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );


    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);
    if($_SESSION['id'] != NULL){
    	$sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
    	$result1 = mysqli_fetch_array($sql);
		$str = str_replace ( "%username%" , "<a href = ''>". $result1['username'] . "</a><a href = '/rating.php?exit=Выход'>выход</a>" , $str );
	} else {
		$str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    	$_SESSION['url'] = '/library.php';
	}


	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }	
    //самые умные
    $sql = mysqli_query($link, "SELECT * from posts ORDER BY `posts`.`знания` DESC LIMIT 10");
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(знание) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%у' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['знание'] . '(' . mysqli_fetch_row($sql1)[0]  . ' голосов)', $str);
      $i++;
    }

    //лучше всего преподают
     $sql = mysqli_query($link, "SELECT * from posts ORDER BY `posts`.`умение` DESC LIMIT 10");
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(умение) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%з' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['умение'] . '(' . mysqli_fetch_row($sql1)[0]  . ' голосов)', $str);
      $i++;
    }

    //лучше всего в общении
     $sql = mysqli_query($link, "SELECT * from posts ORDER BY `posts`.`общение` DESC LIMIT 10");
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(общение) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%о' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['общение'] . '(' . mysqli_fetch_row($sql1)[0]  . ' голосов)', $str);
      $i++;
    }

    //самые халявные
    $sql = mysqli_query($link, "SELECT * from posts ORDER BY `posts`.`халявность` DESC LIMIT 10");
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(халявность) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%х' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['халявность'] . '(' . mysqli_fetch_row($sql1)[0] . ' голосов)', $str);
      $i++;
    }

    //самые строгие
    $sql = mysqli_query($link, 'SELECT * FROM `posts` WHERE `халявность` != 0 ORDER BY `халявность` ASC LIMIT 10');
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(халявность) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%с' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['халявность'] . '(' . mysqli_fetch_row($sql1)[0] . ' голосов)', $str);
      $i++;
    }

    //самые-самые
    $sql = mysqli_query($link, "SELECT * from posts ORDER BY `posts`.`оценка` DESC LIMIT 10");
    $i = 1;
    while($result = mysqli_fetch_array($sql)){
      $sql1 = mysqli_query($link, "SELECT count(оценка) FROM `assessments` WHERE postid = " . $result['id']);
      $str = str_replace('%оц' . $i . '%', '<a href = "/post.php?id=' . $result['id'] .'">' . $result['name'] . '</a> ' . $result['оценка'] . '(' . mysqli_fetch_row($sql1)[0]  . ' голосов)', $str);
      $i++;
    }



    echo $str;

?>