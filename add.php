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


    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);


    $host = 'localhost';
    $user = 'root';
   	$pass = 'admin';
   	$db_name = 'comments';

    $linkComment = mysqli_connect($host, $user, $pass, $db_name);
    if (!$linkComment) {
     	echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
   	  	exit;
  	}


    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
    }
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
		$_SESSION['warning'] = 'Чтобы добавить, пожалуйста, представьтесь.';
		$new_url = '/login.php';
		header('Location: '.$new_url);
		ob_end_flush();
	}
	$name = $_POST['name'];
	$text = $_POST['Text'];






	

    if(($name != "") && ($text != "") && ($_POST['submit'] != NULL)){
    	if($_FILES['img']['size'] > 3*1024*1024){
    		echo "Размер картинки превышает 3 Мб";
    	} else {
    		echo("Успех");
    		$sql = mysqli_query($link, "SELECT * FROM `posts`");
    		//echo($_FILES['img'] != NULL);

    		$sql = mysqli_query($link, "INSERT INTO `posts` (`text`, `name`, `userid`, `department`) VALUES ('{$text}', '{$name}', '{$_SESSION['id']}', '{$_POST['department']}')");
            

 	   		if ($_FILES['img'] != NULL){
 	   			$sql = mysqli_query($link, "SELECT * FROM `posts` ORDER BY id DESC LIMIT 1");
    			move_uploaded_file($_FILES["img"]["tmp_name"], "img/" . (mysqli_fetch_array($sql)['id']) . ".png");
    		}	

    		$sql = mysqli_query($link, "SELECT * FROM `posts` ORDER BY id DESC LIMIT 1");
    		$postid = mysqli_fetch_array($sql)['id'];
			echo 'postid= ' . $postid;


			$query ="CREATE Table IF NOT EXISTS `$postid` (
 				`id` int(11) NOT NULL AUTO_INCREMENT,
 				`userid` int(11) NOT NULL,
 				`text` varchar(512) NOT NULL,
 				`trn_date` datetime NOT NULL,
 				PRIMARY KEY (`id`))";

    		$sql = mysqli_query($linkComment, $query);
    		$new_url = '/post.php?id=' . $postid;
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


