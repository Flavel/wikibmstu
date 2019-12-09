<?php
	session_start();
	ob_start();


	$str = htmlentities(file_get_contents("search.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );


    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);
    if($_GET['exit'] == 'Выход'){
    	$_SESSION['id'] = NULL;
    }
    if($_SESSION['id'] != NULL){
    	$sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
    	$result1 = mysqli_fetch_array($sql);

    	$sqlnot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `new` = 1 AND `userid` = " . $_SESSION['id']);
      	$notifications = mysqli_num_rows($sqlnot);
		$str = str_replace ( "%username%" , "<a href = '/account.php?id=".$_SESSION['id']."'>". $result1['username'] . "(".$notifications.")</a><a href = '/search.php?exit=Выход&request=" . $_GET['request'] . "'>выход</a>" , $str);
	} else {
		$str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    	$_SESSION['url'] = '/search.php?request=' . $_GET['request'];
	}



	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);
    if(!$link){
    	echo "Все пропало";
    	die;
    }
	
	if($_GET['request'] == ""){
		$str = str_replace ( "%ко-во результатов%" , 'Пустой запрос' , $str );
		$str = str_replace ( "%результаты поиска%" , "" , $str );
	} else {
		$sql = mysqli_query($link, "SELECT * FROM `posts` WHERE `name` LIKE '%" . $_GET['request'] ."%'");
		while($result = mysqli_fetch_array($sql)){
		$response = $response . '<div><a href = "/post.php?id=' . $result['id']. '">' . $result['name'] . '</a></div>';
	}
	}
	if(mysqli_num_rows($sql) == 0){
		$str = str_replace ( "%ко-во результатов%" , 'Нет совпадений' , $str );
	}
	$str = str_replace ( "%ко-во результатов%" , 'По данному запросу найдено ' . mysqli_num_rows($sql) . ' результатов' , $str );
	$str = str_replace ( "%результаты поиска%" , $response , $str );
	$str = str_replace ( "%s%" , $_GET['request'] , $str );
	echo $str;

?>