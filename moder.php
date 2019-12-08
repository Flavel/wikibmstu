<?php
	session_start();
	ob_start();

    
    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);

    $sql = mysqli_query($linkUsr, "SELECT * FROM `users` WHERE `id` = " . $_SESSION['id']);
    $result = mysqli_fetch_array($sql);
    if($result['roots'] < 1){
        echo('<h1> Нет прав</h1>');
        exit;
    }
    
    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'comments';
    $linkComment = mysqli_connect($host, $user, $pass, $db_name);

	$str = htmlentities(file_get_contents("moder.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );


    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);

    if($_POST['submit']){

        $result = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM `moderation` WHERE `id` = " . $_GET['id']));
    	$sql = mysqli_query($link, "INSERT INTO `posts`(`userid`, `text`, `name`, `department`) VALUES (" . $result['userid'] . ", '" . $result['text'] . "', '" . $result['name'] . "', '" . $result['department'] . "')");

        $sql = mysqli_query($link, "SELECT * FROM `posts` ORDER BY id DESC LIMIT 1");
        $result = mysqli_fetch_array($sql);
        $postid = $result['id'];
        $query ="CREATE Table IF NOT EXISTS `$postid` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `userid` int(11) NOT NULL,
                `text` varchar(512) NOT NULL,
                `trn_date` datetime NOT NULL,
                PRIMARY KEY (`id`))";

            $sql = mysqli_query($linkComment, $query);

        if ($_FILES['img']['size'] != 0){
            move_uploaded_file($_FILES["img"]["tmp_name"], "img/" . $postid . ".png");
        } else {
            $file = "mimg/" . $_GET['id'] . ".png";
            $newfile = "img/" . $postid . ".png";
            rename($file, $newfile);
            
        }
        $sql = mysqli_query($link, "DELETE FROM `moderation` WHERE `id` = " . $_GET['id']);

	 	$new_url = '/post.php?id=' . $result['id'];
 	 	header('Location: '.$new_url);
	 	ob_end_flush();
    }
    if($_POST['no']){
        $sql = mysqli_query($link, "DELETE FROM `moderation` WHERE `id` = " . $_GET['id']);
        $new_url = '/moderatornaya.php';
        header('Location: '.$new_url);
        ob_end_flush();
    }

?>
<?php
	
    if(file_exists('mimg/' . $_GET["id"] . '.png')){
    	$way = '/mimg/' . $_GET["id"] . '.png?no_cache=' . rand(0,1000000);
    } else {
    	$way = '/mimg/whoisit.png';
    }
    $sql = mysqli_query($link, "SELECT * FROM `moderation` WHERE id = '{$_GET['id']}'");

    $result = mysqli_fetch_array($sql);


    
    if($_SESSION['id'] != NULL){
    	$sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
    	$result1 = mysqli_fetch_array($sql);
		$str = str_replace ( "%username%" , "<a href = ''>" . $result1['username'] . "</a><a href = '/library.php?exit=Выйти&id=". $_GET['id'] . "'>выход</a>" , $str );
	} else {
		$str = str_replace('%username%', "<a href = '/login.php'>Войти</a> <a href = 'register'>Регистрация</a>", $str);
    	$_SESSION['url'] = '/edit.php?id='.$_GET['id'];
    	$_SESSION['warning'] = 'Чтобы редактировать, пожалуйста, представьтесь.';
    	$new_url = '/login.php';
		header('Location: '.$new_url);
		ob_end_flush();
	}
    $str = str_replace ( 'option value = "' . $result["department"] . '"' , 'option selected value = "' . $result["department"] . '"' , $str );

    $str = str_replace ( "%src%" , $way , $str );
    $str = str_replace ( "%text%" , $result["text"] , $str );
    $str = str_replace ( "%name%" , $result["name"] , $str );
    $str = str_replace ( "%id%" , $_GET['id'] , $str );
    $str = str_replace('%rand%', rand(0, 10000), $str);
    echo $str;

	?>