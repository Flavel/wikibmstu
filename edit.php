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

    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'auth';
    $linkUsr = mysqli_connect($host, $user, $pass, $db_name);

    if($_POST['submit']){

    	$sql = mysqli_query($link, "SELECT * FROM `posts`");
            //echo($_FILES['img'] != NULL);
            $sql = mysqli_query($link, "SELECT * FROM `posts` WHERE id = '{$_GET['id']}'");
            $result = mysqli_fetch_array($sql);

            $sql = mysqli_query($linkUsr, "INSERT INTO `notifications`(`userid`, `text`) VALUES (" . $_SESSION['id'] . ", 'Ваши правки(". $result['name'] .") отправлены на модерацию.')");

            $sql = mysqli_query($link, "INSERT INTO `moderation` (`text`, `name`, `userid`, `department`, `edit`) VALUES ('{$_POST['Text']}', '{$_POST["name"]}', '{$_SESSION['id']}', '{$_POST["department"]}', '{$_GET['id']}')");
            

            if ($_FILES['img'] != NULL){
                $sql = mysqli_query($link, "SELECT * FROM `moderation` ORDER BY id DESC LIMIT 1");
                move_uploaded_file($_FILES["img"]["tmp_name"], "mimg/" . (mysqli_fetch_array($sql)['id']) . ".png");
            }   

            $sql = mysqli_query($link, "SELECT * FROM `posts` ORDER BY id DESC LIMIT 1");
            $postid = mysqli_fetch_array($sql)['id'];
            echo 'postid= ' . $postid;

            $new_url = '/post.php?id=' . $_GET['id'];
            header('Location: '.$new_url);
            ob_end_flush();
            exit();
    }

?>
<?php
	
    $sql = mysqli_query($link, "SELECT * FROM `posts` WHERE id = '{$_GET['id']}'");


    if(file_exists('img/' . $_GET["id"] . '.png')){
    	$way = '/img/' . $_GET["id"] . '.png?no_cache=' . rand(0,1000000);
    } else {
    	$way = '/img/whoisit.png';
    }
    $sql = mysqli_query($link, "SELECT * FROM `posts` WHERE id = '{$_GET['id']}'");

    $result = mysqli_fetch_array($sql);

    if($_SESSION['id'] != NULL){
    	$sql = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $_SESSION['id']);
    	$result1 = mysqli_fetch_array($sql);
        $sqlnot = mysqli_query($linkUsr, "SELECT * FROM `notifications` WHERE `new` = 1 AND `userid` = " . $_SESSION['id']);
        $notifications = mysqli_num_rows($sqlnot);
		$str = str_replace ( "%username%" , "<a href = '/account.php?id=".$_SESSION['id']."'>". $result1['username'] . "(".$notifications.")</a><a href = '/post.php?exit=Выйти&id=". $_GET['id'] . "'>выход</a>" , $str );
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


	