<?php
	session_start();
	ob_start();
	$str = htmlentities(file_get_contents("reg.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );

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
			$warning = $warning . '<p>Введите имя</p>';
			$err = true;
		}
		if ($passw1 == ""){
			$warning = $warning . '<p>Введите пароль</p>';
			$err = true;
		} else {
			if ($passw1 != $passw2){
				$warning = $warning . '<p>Пароли не совпадают</p>';
				$err = true;
			}
		}
	}
	$str = str_replace ( "%warning%" , $warning , $str );

	if(($err == true) || ($_POST['submit'] == NULL)){
		$str = str_replace ( "%name%" , $name , $str );
		$str = str_replace ( "%passw1%" , $passw1 , $str );
		$str = str_replace ( "%passw2%" , $passw2 , $str );
		$str = str_replace ( "&quot;" , '"' , $str );

		echo($str);
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


