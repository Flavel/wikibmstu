<?php
	session_start();
	ob_start();



	$str = htmlentities(file_get_contents("account.html"));
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

    

    if($_SESSION['id'] != NULL){
        $sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
        $result1 = mysqli_fetch_array($sql);
        if($_SESSION['id'] != $_GET['id']){
            $sqlnot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `new` = 1 AND `userid` = " . $_SESSION['id']);
            $notifications = mysqli_num_rows($sqlnot);
        } else {
            $notifications = 0;
        }
        $str = str_replace ( "%username%" , "<a href = '/account.php?id=".$_SESSION['id']."'>" . $result1['username'] . "(".$notifications.")</a><a href = '/library.php?exit=Выйти&id=". $_GET['id'] . "'>выход</a>" , $str );
    } else {
        $str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    }
    if(!isset($_GET['id'])){
        header('Location: library.php');
        ob_end_flush();
    }

    if($_GET['id'] == $_SESSION['id']){
        $actions .= "<form action = '/changename.php'><input style = 'width : 90%;' type = 'submit' name = 'changename' value = 'Изменить имя'></form><form action = '/changepassword.php'><input style = 'width : 90%;' type = 'submit' name = 'changepassword' value = 'Изменить пароль'></form>";
        $sqlNot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `userid` = " . $_SESSION['id'] . " ORDER BY `id` DESC");
        $i = 0;
        $notifications = "";
        while($notification = mysqli_fetch_array($sqlNot)){
            if($notification['new'] == 1){
                $notifications .= '<b>';
            }
            if($i % 2 == 0){
                $notifications .= "<div class = 'notification'>[".$notification['date']."] " . $notification['text'] . "</div>";
            } else {
                $notifications .= "<div style = 'background-color: lightgray' class = 'notification'>[".$notification['date']."] " . $notification['text'] . "</div>";
            }
            if($notification['new'] == 1){
                $notifications .= ' (новое)</b>';
            }
            $i++;
        }
        $sqlNot = mysqli_query($linkUsr, "UPDATE `notifications` SET `new` = 0 WHERE `userid` = " . $_SESSION['id']);
    } else {
        $str = str_replace('<h2> Уведомления </h2>', '<h2> Статьи </h2>', $str);
        $sqlNot = mysqli_query($link, "SELECT * FROM `posts` WHERE `userid` = " . $_GET['id']);
        $i = 0;
        while($notification = mysqli_fetch_array($sqlNot)){
            if($i % 2 == 0){
                $notifications .= "<div class = 'notification'><a href = 'post.php?id=". $notification['id']."'>" . $notification['name'] . "</a></div>";
            } else {
                $notifications .= "<div style = 'background-color: lightgray' class = 'notification'><a href = 'post.php?id=". $notification['id']."'>" . $notification['name'] . "</a></div>";
            }
            $i++;
        }
        if ($i == 0){
            $notifications = 'Нет уведомлений';
        }
    }
    $sql = mysqli_query($linkUsr, "SELECT * FROM `users` WHERE `id`=" . $_GET['id']);
    $result = mysqli_fetch_array($sql);
    $str = str_replace('%Действия%', $actions, $str);
    $str = str_replace('%rand%', rand(0, 10000), $str);
    $str = str_replace ( "%src%" , "https://api.adorable.io/avatars/180/". sha1($result['username']) , $str );
    $str = str_replace('%Лента уведомлений%', $notifications, $str);

    $sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_GET['id']);
        $result = mysqli_fetch_array($sql);
    $str = str_replace('%usrname%', $result['username'] ? $result['username'] : 'Удаленный пользователь', $str);
    echo $str;
?>