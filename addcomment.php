<?php
	session_start();
	ob_start();

	$comment = $_POST['comment'];
	$postid = $_GET['id'];

	$host = 'localhost';
    $user = 'root';
    $pass = 'admin';
    $db_name = 'comments';
    $link = mysqli_connect($host, $user, $pass, $db_name);
    if (!$link) {
      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
      exit;
  	}

  	if($_SESSION['id'] == NULL){
  		$userid = 21;
  	} else {
      $userid = $_SESSION['id'];
    }
  		$t = date("Y/m/d H:i:s");
  		echo($t);
  		if($comment != ""){
     		$sql = mysqli_query($link, "INSERT INTO `" . $postid . "` (`text`, `userid`, `trn_date`) VALUES ('{$comment}', '{$userid}', '{$t}')" );
     	}
  		header('Location: /post.php?id=' . $postid);
		ob_end_flush();
  	

?>