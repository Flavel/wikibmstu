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
    if($_POST['anonim'] == 'on'){
      $userid = 21;
    }
  		$t = date("Y/m/d H:i:s");
  		echo($_POST['anonim']);
  		if($comment != ""){
     		$sql = mysqli_query($link, "INSERT INTO `" . $postid . "` (`text`, `userid`, `trn_date`) VALUES ('{$comment}', '{$userid}', '{$t}')" );
     	}

      $sql1 = mysqli_query($link, "SELECT * FROM `last`");

      $result = mysqli_fetch_array($sql1);
      for($i = 1; $i < 10; $i++){
        $result[$i] = $result[$i + 1];
      }
      $result[10] = $postid;
      $sql1 = mysqli_query($link, "UPDATE `last` SET `1`=$result[1],`2`=$result[2],`3`=$result[3],`4`=$result[4],`5`=$result[5],`6`=$result[6],`7`=$result[7],`8`=$result[8],`9`=$result[9],`10`=$result[10] WHERE 1");
      


  		header('Location: /post.php?id=' . $postid);
		ob_end_flush();
  	

?>