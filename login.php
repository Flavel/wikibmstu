<?php
session_start();
ob_start();
?>
<H1> ВХОД </H1>


<?php

	if($_SESSION['id'] != NULL){
		header('Location: '.$_SESSION['url']);
		ob_end_flush();
	}

	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $link = mysqli_connect($host, $user, $pass, $db_name);
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
  	}
  	$name = $_POST['username'];
	$passw = $_POST['password'];
	$err = false;

	if($_POST['submit'] != NULL){
		$sql = mysqli_query($link, "SELECT * FROM `users` WHERE username = '$name' AND password = '$passw'");
		if(mysqli_num_rows($sql) == 1){
			$new_url = $_SESSION['url'];
			if(!$_SESSION['url']){
				$_SESSION['url'] = '/library.php';
			}
			echo($new_url);
			$_SESSION['id'] = mysqli_fetch_array($sql)['id'];
			header('Location: '.$_SESSION['url']);
			ob_end_flush();

		} else {
			echo('Неверный пароль');
			$err = true;
		}
	}

	if (($err == true) || ($_POST['submit'] == NULL)){
		echo "	<form action='login.php' method='POST'>
				Имя пользователя</br>
				<input type='text' name='username' value = '$name'></br>
				Пароль<br>
				<input type='password' name='password' value = '$passw'></br>
				<input type='submit' name='submit' value='войти''>
				</form>
				<a href = '/register.php'> У меня нет аккаунта. </a>
				";
	}
?>