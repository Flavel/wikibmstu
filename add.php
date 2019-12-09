<?php
	session_start();
	ob_start();


	$str = htmlentities(file_get_contents("add.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );
    $str = str_replace ( "%rand%" , rand(0, 10000) , $str );


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
        $sqlnot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `new` = 1 AND `userid` = " . $_SESSION['id']);
        $notifications = mysqli_num_rows($sqlnot);
		$str = str_replace ( "%username%" , "<a href = '/account.php?id=".$_SESSION['id']."'>". $result1['username'] . "(".$notifications.")</a><a href = '/library.php?exit=Выход'>выход</a>" , $str );
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
    		echo("Успех");
    		$sql = mysqli_query($link, "SELECT * FROM `posts`");
    		//echo($_FILES['img'] != NULL);

    		$sql = mysqli_query($link, "INSERT INTO `moderation` (`text`, `name`, `userid`, `department`) VALUES ('{$text}', '{$name}', '{$_SESSION['id']}', '{$_POST['department']}')");
            

 	   		if ($_FILES['img'] != NULL){
 	   			$sql = mysqli_query($link, "SELECT * FROM `moderation` ORDER BY id DESC LIMIT 1");
    			move_uploaded_file($_FILES["img"]["tmp_name"], "mimg/" . (mysqli_fetch_array($sql)['id']) . ".png");
    		}	

    		$sql = mysqli_query($link, "SELECT * FROM `posts` ORDER BY id DESC LIMIT 1");
    		$postid = mysqli_fetch_array($sql)['id'];
			echo 'postid= ' . $postid;
            $sql = mysqli_query($linkUsr, "INSERT INTO `notifications`(`userid`, `text`) VALUES (" . $_SESSION['id'] . ", 'Ваша статья(". $name .") отправлена на модерацию.')");
    		$new_url = '/library.php';
			header('Location: '.$new_url);
			ob_end_flush();
    		exit();
    	
	} else {
		$warning = 'Имя или текст не введен';
	}

	$str = str_replace ( "%name%" , $name , $str );
	$str = str_replace ( "%text%" , $text , $str );
	$str = str_replace ( "%warning%" , $warning , $str );
echo $str;
?>


