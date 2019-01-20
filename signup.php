<?php

$username=$_POST['name'];
if(file_exists($username)){
	header('Location: http://192.168.1.194:8000/www/index.html?error=name_exists');
}
else{
	mkdir("$username");
copy("download.php","$username/download.php");
copy("download_log.php","$username/download_log.php");

header('Location: http://192.168.1.194:8000/www/signin.html');
}
?>
