<?php
	session_start();
	ob_start();
?>
<p><a href = "library.php">Назад</a></p>
<?php
	echo('<p><a href = "/edit.php?id=' . $_GET["id"] . '">Редактировать</a>');
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

	echo('<img height = 200 src = "' . $way . '" alt = "' . mysqli_fetch_array($sql)["name"] . '">');
	$sql = mysqli_query($link, "SELECT * FROM `posts` WHERE id = '{$_GET['id']}'");
	echo('<h1>' . mysqli_fetch_array($sql)["name"] . '</h1>');
	$sql = mysqli_query($link, "SELECT * FROM `posts` WHERE id = '{$_GET['id']}'");
	echo(' <p>' . mysqli_fetch_array($sql)["text"] . '</p>');
?>