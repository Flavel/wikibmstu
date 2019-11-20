<?php
session_start();
ob_start();
?>


<?php
	$str = htmlentities(file_get_contents("login.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );

    $warning = "<p>" . $_SESSION['warning'] . "</p>"; 
    $_SESSION['warning'] = "";
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
			$warning = 'Неверный пароль';
			$err = true;
		}
	}

	$str = str_replace ( "%name%" , $name , $str );
	$str = str_replace ( "%passw%" , $passw , $str );
	$str = str_replace ("%warning%", $warning, $str );
	$str = str_replace ( "&quot;" , '"' , $str );
	if (($err == true) || ($_POST['submit'] == NULL)){
		echo $str;
	}
?>
</body>
</html>