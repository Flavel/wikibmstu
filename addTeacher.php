<?php
<form action = "add.php" method = "POST" enctype="multipart/form-data">
	фото<br>
	<input type = "file" name = "img" accept="image/png">
	<br>имя<br>
	<input type="text" name = "name">
	<br>
	<textarea name = "Text" cols = "40" rows = "40"></textarea>
	<input type="submit" value="Добавить">
</form>
?>