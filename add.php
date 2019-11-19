<?php
	session_start();
	ob_start();

	if ($_SESSION['id'] == NULL){
		$new_url = '/login.php';
		header('Location: '.$new_url);
		ob_end_flush();
		$_SESSION['url'] = 'add.php';
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
		echo 'Имя или текст не введен';
	}

echo('
<form action = "add.php" method = "POST" enctype="multipart/form-data">
	фото<br>
	<input type = "file" name = "img" accept="image/png">
	<br>имя<br>
	<input type="text" name = "name"  value = ' . $name . '>
	<br>
	<textarea name = "Text" cols = "40" rows = "40">'. $text .'</textarea>
	<input type="submit"  name = "submit" value="Добавить">
</form>
');
?>
