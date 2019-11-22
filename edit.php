<?php
	session_start();
	ob_start();

	$str = htmlentities(file_get_contents("edit.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );


    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);

    if($_POST['submit']){

    	if ($_FILES['img'] != NULL){
    		move_uploaded_file($_FILES["img"]["tmp_name"], "img/" . $_GET['id'] . ".png");
    	}	

    	$sql = mysqli_query($link, "UPDATE `posts` SET `name` = '" . $_POST['name'] . "', `text` ='" . $_POST['Text'] . "' WHERE `id` = '".$_GET['id']."'");
	 	$new_url = '/post.php?id=' . $_GET['id'];
 	 	header('Location: '.$new_url);
	 	ob_end_flush();
    }

?>
<?php
	
    $sql = mysqli_query($link, "SELECT * FROM `posts` WHERE id = '{$_GET['id']}'");


    if(file_exists('img/' . $_GET["id"] . '.png')){
    	$way = '/img/' . $_GET["id"] . '.png';
    } else {
    	$way = '/img/whoisit.png';
    }
    $sql = mysqli_query($link, "SELECT * FROM `posts` WHERE id = '{$_GET['id']}'");

    $result = mysqli_fetch_array($sql);


    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);
    if($_SESSION['id'] != NULL){
    	$sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
    	$result1 = mysqli_fetch_array($sql);
		$str = str_replace ( "%username%" , "<a href = ''>". $result1['username'] . "</a><a href = '/post.php?exit=Выйти&id=". $_GET['id'] . "'>выход</a>" , $str );
	} else {
		$str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    	$_SESSION['url'] = '/edit.php?id='.$_GET['id'];
    	$_SESSION['warning'] = 'Чтобы редактировать, пожалуйста, авторизируйтесь.';
    	$new_url = '/login.php';
		header('Location: '.$new_url);
		ob_end_flush();
	}

    $str = str_replace ( "%src%" , $way , $str );
    $str = str_replace ( "%text%" , $result["text"] , $str );
    $str = str_replace ( "%name%" , $result["name"] , $str );
    $str = str_replace ( "%id%" , $_GET['id'] , $str );
    echo $str;

	?>
	