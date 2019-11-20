<?php
	session_start();
	ob_start();


	$str = htmlentities(file_get_contents("add.html"));
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
		$str = str_replace ( "%username%" , "<a href = ''>". $result1['username'] . "</a><a href = '/library.php?exit=Выход'>выход</a>" , $str );
	} else {
		$str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    	$_SESSION['url'] = '/library.php';
	}

	if ($_SESSION['id'] == NULL){
		$_SESSION['url'] = 'add.php';
		$_SESSION['warning'] = 'Чтобы добавить, пожалуйста, авторизируйтесь.';
		$new_url = '/login.php';
		header('Location: '.$new_url);
		ob_end_flush();
	}
	$name = $_POST['name'];
	$text = $_POST['Text'];

	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }




	

    if(($name != "") && ($text != "") && ($_POST['submit'] != NULL)){
    	if($_FILES['img']['size'] > 3*1024*1024){
    		echo "Размер картинки превышает 3 Мб";
    	} else {
    		echo("Успех");
    		$sql = mysqli_query($link, "SELECT * FROM `posts`;");
    		echo($_FILES['img'] != NULL);
    		if ($_FILES['img'] != NULL){
    			move_uploaded_file($_FILES["img"]["tmp_name"], "img/" . (mysqli_num_rows($sql) + 1) . ".png");
    		}	

    		$sql = mysqli_query($link, "INSERT INTO `posts` (`text`, `name`, `userid`) VALUES ('{$text}', '{$name}', '{$_SESSION['id']}')");
    		$new_url = '/library.php';
			header('Location: '.$new_url);
			ob_end_flush();
    		exit();
    	}
	} else {
		$warning = 'Имя или текст не введен';
	}

	$str = str_replace ( "%name%" , $name , $str );
	$str = str_replace ( "%text%" , $text , $str );
	$str = str_replace ( "%warning%" , $warning , $str );
echo $str;
?>
