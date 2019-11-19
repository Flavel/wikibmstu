<?php
	session_start();
	ob_start();
	echo('<h1> Регистрация</h1>');
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

	$name = $_POST['name'];
	$passw1 = $_POST['password1'];
	$passw2 = $_POST['password2'];
	$err = false;
	if($_POST['submit'] != NULL){
		$sql = mysqli_query($link, "SELECT * FROM users WHERE username = '" . $name . "'");
		if(mysqli_fetch_array($sql)){
			echo('Пользователь с таким именем уже существует');
			$err = true;
		}

		if ($name == ""){
			echo('<p>Введите имя</p>');
			$err = true;
		}
		if ($passw1 == ""){
			echo('<p>Введите пароль</p>');
			$err = true;
		} else {
			if ($passw1 != $passw2){
				echo('<p>Пароли не совпадают</p>');
				$err = true;
			}
		}

	}

	if(($err == true) || ($_POST['submit'] == NULL)){
		echo(
	"<form action='register.php' method = 'POST'>
		Имя пользователя</br>
		<input type='text' name='name' value = '$name'></br>
		Пароль<br>
		<input type='password1' name='password1' value = '$passw1'></br>
		Пароль<br>
		<input type='password2' name='password2' value = '$passw2'></br>
		<input type='submit' value = 'OK' name = 'submit'></br>
		<a href = '/login.php'>У меня уже есть аккаунт.</a>
	</form>");
	} else {

		$sql = mysqli_query($link, "INSERT INTO `users` (`username`, `password`) VALUES ('{$name}', '{$passw2}')");
		if ($sql) {
      		echo ('Успех');
      		header('Location: '. 'login.php');
			ob_end_flush();
    	} else {
      		echo mysqli_error($link);
    	}
	}
?>


