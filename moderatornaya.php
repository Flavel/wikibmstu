<?php
	session_start();
	ob_start();



	$str = htmlentities(file_get_contents("moderatornaya.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );



	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);

    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);

    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'comments';
    $linkComments = mysqli_connect($host, $user, $pass, $db_name);

    if($_SESSION['id'] != NULL){
        $sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
        $result1 = mysqli_fetch_array($sql);
        $sqlnot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `new` = 1 AND `userid` = " . $_SESSION['id']);
        $notifications = mysqli_num_rows($sqlnot);
        $str = str_replace ( "%username%" , "<a href = '/account.php?id=".$_SESSION['id']."'>" . $result1['username'] . "(".$notifications.")</a><a href = '/library.php?exit=Выйти&id=". $_GET['id'] . "'>выход</a>" , $str );
    } else {
        $str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
        $_SESSION['url'] = '/edit.php?id='.$_GET['id'];
        $_SESSION['warning'] = 'Чтобы редактировать, пожалуйста, представьтесь.';
        $new_url = '/login.php';
        header('Location: '.$new_url);
        ob_end_flush();
    }

    $sql = mysqli_query($linkUsr, "SELECT * FROM `users` WHERE `id` = " . $_SESSION['id']);
    $result = mysqli_fetch_array($sql);
    if($result['roots'] < 1){
    	echo('<h1> Нет прав</h1>');
    	exit;
    }  else {
        //новые статьи
    	$sql = mysqli_query($link, 'SELECT * FROM `moderation` WHERE `edit` = 0');
        $i = 0;
        while($result = mysqli_fetch_array($sql)){
            $post .= "<div><a href = '/moder.php?id=".$result["id"]."'>" . $result['name'] . '</a></div>' . "\n";
            $i++;
        }

        //редактированные
        $sql = mysqli_query($link, 'SELECT * FROM `moderation` WHERE `edit` != 0');
        $j = 0;
        while($result = mysqli_fetch_array($sql)){
            $edit .= "<div><a href = '/moder.php?id=".$result["id"]."'>" . $result['name'] . '</a></div>' . "\n";
            $j++;
        }

    }
    $str = str_replace ( "%n%" , $i , $str );
    $str = str_replace ( "%n1%" , $j , $str );
    $str = str_replace ( "%username%" , $users , $str );
    $str = str_replace ( "%Статьи%" , $post , $str );
    $str = str_replace ( "%Редактированные cтатьи%" , $edit , $str );
    $str = str_replace ( "%rand%" , rand(0, 100000) , $str );
    echo $str;

?>