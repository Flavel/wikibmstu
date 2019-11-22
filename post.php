<?php
	session_start();
	ob_start();

	$str = htmlentities(file_get_contents("post.html"));
	$str = str_replace ( "&lt;" , "<" , $str );
    $str = str_replace ( "&gt;" , ">" , $str );
    $str = str_replace ( "&quot;" , '"' , $str );
    
    if($_GET['exit']){
		$_SESSION = array();
    }

?>
<?php
	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'post';
    $link = mysqli_connect($host, $user, $pass, $db_name);
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
    	$_SESSION['url'] = '/post.php?id='.$_GET['id'];
	}
    //Оценка
    if(($_GET['knowledge'] || $_GET['teaching_skills'] || $_GET['communication_skills'] || $_GET['easiness'] || $_GET['assessment']) && (!$_SESSION['id'])){
        $_SESSION['url'] = '/post.php?id='.$_GET['id'];
        $_SESSION['warning'] = 'Чтобы оставить оценку, пожалуйста, авторизируйтесь.';
        $new_url = '/login.php';
        header('Location: '.$new_url);
        ob_end_flush();
    }

    if($_GET['knowledge']){
        
        $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `знание`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['knowledge']}')");
    }
    if($_GET['teaching_skills']){
        $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `умение`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['teaching_skills']}')");
    }
    if($_GET['communication_skills']){
        $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `общение`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['communication_skills']}')");
    }
    if($_GET['easiness']){
        $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `халявность`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['easiness']}')");
    }
    if($_GET['assessment']){
        $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `оценка`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['assessment']}')");
    }

    //комментарии
    $host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'comments';
    $linkcomment = mysqli_connect($host, $user, $pass, $db_name);
    $sql = mysqli_query($linkcomment, "SELECT * FROM `" . $_GET['id'] . "`");

    while($result2 = mysqli_fetch_array($sql)){
        $comment = htmlentities(file_get_contents("comment.html")) . $comment;
        $comment = str_replace ( "&lt;" , "<" , $comment );
        $comment = str_replace ( "&gt;" , ">" , $comment );
        $comment = str_replace ( "&quot;" , '"' , $comment );
    
        $comment = str_replace ( "%text%" , $result2['text'] , $comment);
        $comment = str_replace ( "%date%" , $result2['trn_date'] , $comment);

        $sqlUsr = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $result2['userid']);
        $comment = str_replace ( "%username%" , mysqli_fetch_array($sqlUsr)['username'] , $comment);
    }


    $str = str_replace ( "%src%" , $way , $str );
    $str = str_replace ( "%text%" , $result["text"] , $str );
    $str = str_replace ( "%name%" , $result["name"] , $str );
    $str = str_replace ( "%id%" , $_GET['id'] , $str );
    $str = str_replace ( "%comments%" , $comment , $str );


	echo $str;
?>