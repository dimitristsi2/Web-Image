<?php
	session_start();
	$id = $_POST['userlog'];
	delete_directory($id);
	function delete_directory($id) {
	if (is_dir($id))
           $dir_handle = opendir($id);
	 if (!$dir_handle)
	      return false;
	 while($file = readdir($dir_handle)) {
	       if ($file != "." && $file != "..") {
	            if (!is_dir($id."/".$file))
	                 unlink($id."/".$file);
	            else
	                 delete_directory($id.'/'.$file);
	       }
	 }
	 closedir($dir_handle);
	 rmdir($id);
}
	session_unset();
	session_destroy();
	header('Location: http://192.168.1.194:8000/www/index.html');
?>