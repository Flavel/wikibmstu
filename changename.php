<?php
	session_start();
	ob_start();



	$str = htmlentities(file_get_contents("changename.html"));
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
        $sqlnot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `new` = 1 AND `userid` = " . $_SESSION['id']);
        $notifications = mysqli_num_rows($sqlnot);
        $str = str_replace ( "%username%" , "<a href = '/account.php?id=".$_SESSION['id']."'>" . $result1['username'] . "(".$notifications.")</a><a href = '/library.php?exit=Выйти'>выход</a>" , $str );


        if($_POST['change']){
            if($result1['password'] = $_POST['password']) {
                $sql = mysqli_query($linkUsr, "SELECT * FROM `users` WHERE username = '". $_POST['newname'] ."'");
                if(mysqli_num_rows($sql) == 0){
                    if($_POST['newname'] != ""){
                        mysqli_query($linkUsr, "UPDATE `users` SET `username` = '" . $_POST['newname'] . "' WHERE `id` = " . $_SESSION['id']);
                        mysqli_query($linkUsr, "INSERT INTO `notifications` (`userid`, `text`) VALUES (". $_SESSION['id'] .", 'Имя пользователя успешно изменено')");
                        header('Location: /account.php?id=' . $_SESSION['id']);
                        ob_end_flush();
                    } else {
                        $warning = 'Введите новое имя пользователя';
                    }
                } else {
                    $warning = 'Такое имя пользователя уже существует';
                }
            } else {
                $warning = 'Неверный пароль';
            }
        }
    } else {
        header('Location: /library.php');
        ob_end_flush();
        $str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    }
    $str = str_replace('%warning%', $warning, $str);
    echo $str;
?>