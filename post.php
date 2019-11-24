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
    	$way = '/img/' . $_GET["id"] . '.png?no_cache=' . rand(0,1000000);
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
        $_SESSION['warning'] = 'Чтобы оставить оценку, пожалуйста, представьтесь.';
        $new_url = '/login.php';
        header('Location: '.$new_url);
        ob_end_flush();
    }

    if(($_GET['knowledge']) && ($_GET['knowledge'] <=5)){
        $sql1 = mysqli_query($link, "SELECT * FROM `assessments` WHERE `postid` = '" . $_GET['id'] . "' and `userid` = '" . $_SESSION['id'] . "'");
        if(mysqli_num_rows($sql1) >= 1){
           $sql = mysqli_query($link, "UPDATE `assessments` SET `знание` = '" . $_GET['knowledge'] . "' WHERE `postid` = '".$_GET['id']."' and `userid` = '".$_SESSION['id']."'");
        } else {
            $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `знание`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['knowledge']}')");
        }
    }
    if(($_GET['teaching_skills'])  && ($_GET['teaching_skills'] <=5)){
            //$sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `умение`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['teaching_skills']}')");

        $sql1 = mysqli_query($link, "SELECT * FROM `assessments` WHERE `postid` = '" . $_GET['id'] . "' and `userid` = '" . $_SESSION['id'] . "'");
        if(mysqli_num_rows($sql1) >= 1){
           $sql = mysqli_query($link, "UPDATE `assessments` SET `умение` = '" . $_GET['teaching_skills'] . "' WHERE `postid` = '".$_GET['id']."' and `userid` = '".$_SESSION['id']."'");
        } else {
            $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `умение`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['teaching_skills']}')");
        }
    }
    if(($_GET['communication_skills']) &&($_GET['communication_skills'] <=5)){
        //$sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `общение`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['communication_skills']}')");
        $sql1 = mysqli_query($link, "SELECT * FROM `assessments` WHERE `postid` = '" . $_GET['id'] . "' and `userid` = '" . $_SESSION['id'] . "'");
        if(mysqli_num_rows($sql1) >= 1){
           $sql = mysqli_query($link, "UPDATE `assessments` SET `общение` = '" . $_GET['communication_skills'] . "' WHERE `postid` = '".$_GET['id']."' and `userid` = '".$_SESSION['id']."'");
        } else {
            $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `общение`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['communication_skills']}')");
        }
    }
    if(($_GET['easiness']) && ($_GET['easiness'] <=5)){
        //$sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `халявность`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['easiness']}')");
        $sql1 = mysqli_query($link, "SELECT * FROM `assessments` WHERE `postid` = '" . $_GET['id'] . "' and `userid` = '" . $_SESSION['id'] . "'");
        if(mysqli_num_rows($sql1) >= 1){
           $sql = mysqli_query($link, "UPDATE `assessments` SET `халявность` = '" . $_GET['easiness'] . "' WHERE `postid` = '".$_GET['id']."' and `userid` = '".$_SESSION['id']."'");
        } else {
            $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `халявность`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['easiness']}')");
        }
    }
    if(($_GET['assessment']) && ($_GET['easiness'] <=5)){
        //$sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `оценка`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['assessment']}')");
        $sql1 = mysqli_query($link, "SELECT * FROM `assessments` WHERE `postid` = '" . $_GET['id'] . "' and `userid` = '" . $_SESSION['id'] . "'");
        if(mysqli_num_rows($sql1) >= 1){
           $sql = mysqli_query($link, "UPDATE `assessments` SET `оценка` = '" . $_GET['assessment'] . "' WHERE `postid` = '".$_GET['id']."' and `userid` = '".$_SESSION['id']."'");
        } else {
            $sql = mysqli_query($link, "INSERT INTO `assessments` (`userid`, `postid`, `оценка`) VALUES ('{$_SESSION['id']}', '{$_GET['id']}', '{$_GET['assessment']}')");
        }
    }
    $sum_knowledge = 0;
    $sum_teaching_skills = 0;
    $sum_communication_skills = 0;
    $sum_easiness = 0;
    $sum_assessment = 0;

    $sql = mysqli_query($link, "SELECT count(знание) FROM `assessments` WHERE postid = " . $_GET['id']);
    $row = mysqli_fetch_row($sql);
    $c_knowledge = $row[0];

    $sql = mysqli_query($link, "SELECT count(умение) FROM `assessments` WHERE postid = " . $_GET['id']);
    $row = mysqli_fetch_row($sql);
    $c_teaching_skills = $row[0];

    $sql = mysqli_query($link, "SELECT count(общение) FROM `assessments` WHERE postid = " . $_GET['id']);
    $row = mysqli_fetch_row($sql);
    $c_communication_skills = $row[0];

    $sql = mysqli_query($link, "SELECT count(халявность) FROM `assessments` WHERE postid = " . $_GET['id']);
    $row = mysqli_fetch_row($sql);
    $c_easiness = $row[0];

    $sql = mysqli_query($link, "SELECT count(оценка) FROM `assessments` WHERE postid = " . $_GET['id']);
    $row = mysqli_fetch_row($sql);
    $c_assessment = $row[0];
    //}

    $sql1 = mysqli_query($link, "SELECT * FROM `assessments` WHERE `postid` = '" . $_GET['id'] . "'");
    while($res = mysqli_fetch_array($sql1)){
        $sum_knowledge += $res['знание'];
        $sum_teaching_skills += $res['умение'];
        $sum_communication_skills += $res['общение'];
        $sum_easiness += $res['халявность'];
        $sum_assessment += $res['оценка'];
    }
        $str = str_replace ( "%оценкаЗ%" , ($c_knowledge > 0) ? round($sum_knowledge / $c_knowledge, 1) : "нет" , $str );
        $str = str_replace ( "%оценкаУ%" , ($c_teaching_skills > 0) ? round($sum_teaching_skills / $c_teaching_skills,1) : "нет" , $str );
        $str = str_replace ( "%оценкаВ%" , ($c_communication_skills > 0) ? round($sum_communication_skills / $c_communication_skills,1) : "нет" , $str );
        $str = str_replace ( "%оценкаХ%" , ($c_easiness > 0) ? round($sum_easiness / $c_easiness,1) : "нет" , $str );
        $str = str_replace ( "%оценкаО%" , ($c_assessment > 0) ? round($sum_assessment / $c_assessment,1) : "нет" , $str );
        
        $str = str_replace ( "%количествоЗ%" , $c_knowledge , $str );
        $str = str_replace ( "%количествоУ%" , $c_teaching_skills, $str );
        $str = str_replace ( "%количествоВ%" , $c_communication_skills , $str );
        $str = str_replace ( "%количествоХ%" , $c_easiness , $str );
        $str = str_replace ( "%количествоО%" , $c_assessment , $str );  
        $query = "UPDATE `posts` SET `знания` = '" . (($c_knowledge > 0) ? round($sum_knowledge / $c_knowledge, 1) : 0) . "', `умение` ='" . (($c_teaching_skills > 0) ? round($sum_teaching_skills / $c_teaching_skills,1) : 0) . "', `общение` = '" . (($c_communication_skills > 0) ? round($sum_communication_skills / $c_communication_skills,1) : 0) . "', `халявность` = '" . (($c_easiness > 0) ? round($sum_easiness / $c_easiness,1) : 0) . "', `оценка` = '" . (($c_assessment > 0) ? round($sum_assessment / $c_assessment,1) : 0). "' WHERE `id` = '" . $_GET['id'] . "'";
        //echo $query;
        $sqlUpd = mysqli_query($link, $query);
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
        $sqlUsr = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $result2['userid']);
        $comment = str_replace ( "%text%" , $result2['text'] , $comment);
        $comment = str_replace ( "%date%" , $result2['trn_date'] , $comment);
        
        $userid = mysqli_fetch_array($sqlUsr)['username'];
        if($userid == 'Аноним'){
            $comment = str_replace ( "%src%" , "https://api.adorable.io/avatars/180/". $result2['trn_date'], $comment);
        } else {
            $comment = str_replace ( "%src%" , "https://api.adorable.io/avatars/180/". sha1($userid), $comment);
        }

        $sqlUsr = mysqli_query($linkUsr, "SELECT * FROM users WHERE `id` = ".  $result2['userid']);
        $comment = str_replace ( "%username%" , mysqli_fetch_array($sqlUsr)['username'] , $comment);
    }


    $str = str_replace ( "%src%" , $way , $str );
    $str = str_replace ( "%text%" , $result["text"] , $str );
    $str = str_replace ( "%name%" , $result["name"] , $str );
    $str = str_replace ( "%Кафедра%" , $result["department"] , $str );
    $str = str_replace ( "%id%" , $_GET['id'] , $str );
    $str = str_replace ( "%comments%" , $comment , $str );
    $str = str_replace('%rand%', rand(0, 100000), $str);
    if($_SESSION['id']){
        $str = str_replace ( "%annotation%" , '<input type = "checkbox" name = "anonim"> Анонимно'  , $str );
    } else {
        $str = str_replace ( "%annotation%" , 'На сайте комментарии могут оставлять все пользователи, если вы не хотите оставаться анонимным, пожалуйста, <a href = "/register.php">зарегистрируйтесь</a> или <a href = "/login.php">представьтесь</a>'  , $str );
    }

	echo $str;
?>