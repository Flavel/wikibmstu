<?php
	session_start();

	if($_GET['exit']){
		session_destroy();
		$_SESSION = array();
	}
	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);
    if($_SESSION['id'] != NULL){
    	$sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
    	$result = mysqli_fetch_array($sql);
    	echo($result['username']);
    	echo("<form action = 'library.php' method = 'GET'> <input type = 'submit' name = 'exit' value = 'Выйти'> </form>");
    } else {
    	echo("<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>");
    	$_SESSION['url'] = '/library.php';
    }

?>



<p> Все <a href = "add.php"> Добавить </a></p>

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
    while ($result = mysqli_fetch_array($sql)) {
      echo "<p><a href = 'post.php?id={$result['id']}' >{$result['id']}. {$result['name']}</a></p>";
  	}
?>