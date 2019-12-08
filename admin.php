<?php
	session_start();
	ob_start();



	$str = htmlentities(file_get_contents("admin.html"));
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

    $sql = mysqli_query($linkUsr, "SELECT * FROM `users` WHERE `id` = " . $_SESSION['id']);
    $result = mysqli_fetch_array($sql);
    if($result['roots'] != 2){
    	echo('<h1> Нет прав</h1>');
    	exit;
    }  else {
    	//удаление юзера
    	if($_GET['deleteUsr']){
    		$sql = mysqli_query($linkUsr, 'DELETE FROM `users` WHERE id = ' . $_GET['deleteUsr']);
    	}
    	//удаление статьи

    	if($_GET['deletePost']){
    		ECHO 'DROP TABLE `' . $_GET['deletePost'] . '`';
    		$sql = mysqli_query($linkComments, 'DROP TABLE `' . $_GET['deletePost'] . '`');
    		$srl = mysqli_query($link, 'DELETE FROM `assessments` WHERE `assessments`.`postid` = ' . $_GET['deletePost']);
    		$sql = mysqli_query($link, 'DELETE FROM `posts` WHERE `id` = ' . $_GET['deletePost']);
    	}
    	//добавление модератора
    	if($_GET['makeModer']){
    		$sql = mysqli_query($linkUsr, "UPDATE `users` SET `roots`= 1 WHERE `id` = " . $_GET['makeModer']);
    	}
    	//удаление модератора
    	if($_GET['removeModer']){
    		$sql = mysqli_query($linkUsr, "UPDATE `users` SET `roots`= 0 WHERE `id` = " . $_GET['removeModer']);
    	}





    	$str = str_replace ( "%user%" , $result['username'] , $str );
    	//юзеры
    	$usr = isset($_GET['username']) ? $_GET['username'] : "";
    	$post = isset($_GET['post']) ? $_GET['post'] : "";
    	$sql = mysqli_query($linkUsr, "SELECT * FROM `users` WHERE `username` LIKE '%" . $usr . "%'");
    	$users .= "<table>";
    	$users .= "<tr><td>id</td><td>Ник</td><td colspan = 2>Права</td></tr>";
    	while($result = mysqli_fetch_array($sql)){
    		$users .= "<tr><td>" . $result['id'] . " </td><td> " . $result['username'] . "</td>";
    		if($result['roots'] == 0){
    			$users .= "<td>Обычный пользователь</td><td><a href = '?makeModer=". $result['id'] ."'> Сделать модератором </a></td>";
    		}
    		if($result['roots'] == 1){
    			$users .= "<td>Модератор</td><td> <a href = '?removeModer=". $result['id'] ."'> Убрать модератора </a></td>";
    		}
    		if($result['roots'] == 2){
    			$users .= "<td>Администратор</td><td>	</td>";
    		}

    		$users .= "<td class = 'delete'> <a href = '?deleteUsr=". $result['id'] ."'> удалить </a></td></tr>";
    	}

    	$users .= "</table>";
    	
    	//статьи
    	$sql = mysqli_query($link, "SELECT * FROM `posts` WHERE `name` LIKE '%" . $post . "%'");
    	$posts .= "<table>";
    	while($result = mysqli_fetch_array($sql)){
    		$posts .= "<tr><td>" . $result['id'] . " </td><td> <a href = '/post.php?id=" . $result['id'] ."'>" . $result['name'] . "</a></td><td>". $result['userid'] ."</td><td> <a href = '?deletePost=". $result['id'] ."'> удалить </a></td></tr>";
    	}
    	$users .= "</table>";
    }




    $str = str_replace ( "%placeholderuser%" , $_GET['username'] , $str );
    $str = str_replace ( "%placeholderpost%" , $_GET['post'] , $str );

    $str = str_replace ( "%username%" , $users , $str );
    $str = str_replace ( "%post%" , $posts , $str );
    $str = str_replace ( "%rand%" , rand(0, 100000) , $str );
    echo $str;

?>