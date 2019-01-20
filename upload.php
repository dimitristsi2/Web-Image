<?php
//header('Access-Control-Allow-Origin: *');
$id = $_POST['userlog'];
$fileName = $_FILES['file']['name'];
	$file = $_FILES['file'];
	$fileTmpName = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];
	$fileError = $_FILES['file']['error'];
	$fileType = $_FILES['file']['type'];
	$platform = $_POST['platform'];
	$nameapp=$_POST['nameapp'];

$fileExt = explode('.', $fileName);	
$fileActualExt = strtolower(end($fileExt));
$fileN = $fileExt[0];

$allowed = array('zip');
$build = "$id/build_log.txt";
if(file_exists($build)){
	unlink($build);
}
if(in_array($fileActualExt, $allowed)){

	if($fileError === 0){

		if($fileSize < 1000000){

			$fileNameNew = "www".".".$fileActualExt;
			$fileDestination = $id.'/'.$fileNameNew;
			move_uploaded_file($fileTmpName, $fileDestination);
			$zip = new ZipArchive();
$zip -> open($fileDestination);
for($num=0; $num < $zip->numFiles; $num++){
	$fileInfo = $zip->statIndex($num);
	$zip->extractTo($id.'/'.'www');
	}
$zip->close();
unlink($id.'/'.$fileNameNew);
	
$fp1 = fopen("$id.bash","w");
fwrite($fp1,"docker run -d -v /home/docker/web/$id://www/ cordova;".PHP_EOL);
fwrite($fp1,"rm -rf $id.bash;".PHP_EOL);
fclose($fp1);
$dir2 = "$id/config.xml";
if(file_exists($dir2)){
	
}
else{
	copy("config.xml","$id/config.xml");
	$oldname= "<name>HelloCordova</name>";
	if($nameapp==""){
	$newname= $oldname;	
	}
	else{
		$newname="<name>$nameapp</name>";
	}
	$str = file_get_contents("$id/".'config.xml');
	$str=str_ireplace($oldname,$newname,$str);
	file_put_contents("$id/".'config.xml',$str);
}
//-----------------------------------------------------------------------
//settings passed inside options.bash
$dir3 = "$id/options.bash";
if(file_exists($dir3)){}
else{
	$fp = fopen("$id/".'options.bash','w');
	fwrite($fp,"rm -rf android-debug.apk;".PHP_EOL);
	fwrite($fp,"rm -rf myapp;".PHP_EOL);
	fwrite($fp,"cordova telemetry off;".PHP_EOL);
	fwrite($fp,"cordova create myapp;".PHP_EOL);
	fwrite($fp,"rm -rf myapp/www;".PHP_EOL);
	fwrite($fp,"rm -rf myapp/config.xml;".PHP_EOL);
	fwrite($fp,"mv www myapp;".PHP_EOL);
	fwrite($fp,"mv config.xml myapp;".PHP_EOL);
	fwrite($fp,"cd myapp;".PHP_EOL);
	fwrite($fp,"touch build_log.txt;".PHP_EOL);
	fwrite($fp,"cordova platform add android>>build_log.txt 2>&1;".PHP_EOL);
	fwrite($fp,"cordova build android>>build_log.txt 2>&1;".PHP_EOL);
	fwrite($fp,"cd ../;".PHP_EOL);
	fwrite($fp,"cp myapp/platforms/android/build/outputs/apk/android-debug.apk .;".PHP_EOL);
	fwrite($fp,"cp myapp/platforms/android/build/outputs/apk/debug/android-debug.apk .;".PHP_EOL);
	fwrite($fp,"cp myapp/build_log.txt .;".PHP_EOL);
	fwrite($fp,"rm -rf myapp;".PHP_EOL);
	fwrite($fp,"rm -rf options.bash".PHP_EOL);
	fclose($fp);
}
//-----------------------------------------------------------------------
header('Location: http://192.168.1.194:8000/www/signin.html');		
		}
		else {
			header('Location: http://192.168.1.194:8000/www/signin.html?Big file');
			//echo "Big file";
		}
	} else{
		header('Location: http://192.168.1.194:8000/www/signin.html?there was an error uploading your file');
	//echo "there was an error uploading your file";	
	}
} else{
	header('Location: http://192.168.1.194:8000/www/signin.html?You_are_not_allowed._Only_.ZIP_files');
	//echo "You are not allowed";
}
?>