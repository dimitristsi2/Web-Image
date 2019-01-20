<?php

session_start();
$nameapp=$_POST['nameapp'];
$ownId=$_POST['id'];
$plugin=$_POST['plugin'];
$id = $_POST['userlog'];
$email = $_POST['email'];
$author = $_POST['author'];
$desc = $_POST['desc'];
$desc2 = $_POST['plug2'];
$file = $_FILES['file'];
$fileName = $_FILES['file']['name'];
$fileTmpName = $_FILES['file']['tmp_name'];
$fileSize = $_FILES['file']['size'];
$fileError = $_FILES['file']['error'];
$fileType = $_FILES['file']['type'];
$counter=0; //counter na elegxoume an uparxei config.xml
if($fileSize==0){
	copy("config.xml","$id/config.xml");
	$counter=1;
}
else{
$fileExt = explode('.', $fileName);	
$fileActualExt = strtolower(end($fileExt));
$fileN = $fileExt[0];
$allowed = array('xml');
if(in_array($fileActualExt, $allowed)){
	if($fileError === 0){
		if($fileSize < 1000000){
			$fileNameNew = "config".".".$fileActualExt;
			$fileDestination = $id.'/'.$fileNameNew;
		move_uploaded_file($fileTmpName, $fileDestination);
		$counter=1;
		}//end if file not big
			else {
			echo "Big file";
		}
	}
	else{
	echo "there was an error uploading your file";	
	}
} 
else{
	echo "You are not allowed";
}
}
//--------------------start creating files and configuring them---------------------
if($counter==1){   //ama uparxei to config.xml ston fakelo
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
	$i=0;
	foreach ($_POST['plugin'] as $names){
		if($names=="chromeapps"){
			fwrite($fp,"cordova plugin add cordova-plugin-chrome-apps-socket>>build_log.txt 2>&1;".PHP_EOL);
		}
		if($names=="scanner"){
			fwrite($fp,"cordova plugin add com.manateeworks.barcodescanner>>build_log.txt 2>&1;".PHP_EOL);
		}
		if($names=="proxy"){
			fwrite($fp,"cordova plugin add cordova-plugin-chrome-apps-proxy>>build_log.txt 2>&1;".PHP_EOL);
		}
		if($names=="parse"){
			fwrite($fp,"cordova plugin add cordova-plugin-parse-pushhandler>>build_log.txt 2>&1;".PHP_EOL);
		}
	}
	$attachment=">>build_log.txt 2>&1";
	$substr=";";
	if(strpos($desc2,';')!==FALSE){
		$newstring = str_replace($substr,$attachment.$substr, $desc2);
	}
	fwrite($fp,"$newstring".PHP_EOL);
	fwrite($fp,"cordova build android>>build_log.txt 2>&1;".PHP_EOL);
	fwrite($fp,"cd ../;".PHP_EOL);
	fwrite($fp,"cp myapp/platforms/android/build/outputs/apk/android-debug.apk .;".PHP_EOL);
	fwrite($fp,"cp myapp/platforms/android/build/outputs/apk/debug/android-debug.apk .;".PHP_EOL);
	fwrite($fp,"cp myapp/build_log.txt .;".PHP_EOL);
	fwrite($fp,"rm -rf myapp;".PHP_EOL);
	fwrite($fp,"rm -rf options.bash".PHP_EOL);
	fclose($fp);
//-------------------------------config.xml configurations------------------------------------------//
	$oldname= "<name>HelloCordova</name>";
	$oldid= "io.cordova.hellocordova";
	$emailold="dev@cordova.apache.org";
	$authorold="Apache Cordova Team";
	$descold=" A sample Apache Cordova application that responds to the deviceready event.";
	if($nameapp==""){
	$newname= $oldname;	
	}
	else{
		$newname="<name>$nameapp</name>";
	}
	if($ownId==""){
		$newid= $oldid;
	}	
	else{
		$newid= "$ownId";
	}
	if($email==""){
		$newemail=$emailold;
	}
	else{
		$newemail="$email";
	}
	if($author==""){
		$authornew=$authorold;
	}
	else{
		$authornew="$author";
	}
	if($desc==""){
		$descnew=$descold;
	}
	else{
		$descnew="$desc";
	}

	//read entire string
	$str = file_get_contents("$id/".'config.xml');
	//replace string with another
	$str=str_ireplace($oldid,$newid,$str);
	$str=str_ireplace($oldname,$newname,$str);
	$str=str_ireplace($emailold,$newemail,$str);
	$str=str_ireplace($authorold,$authornew,$str);
	$str=str_ireplace($descold,$descnew,$str);
	file_put_contents("$id/".'config.xml',$str);
	
	header('Location: http://192.168.1.194:8000/www/signin.html?success?options');
}
//----------------------------------------------------------------------------------
if($counter==0){
	header('Location: http://192.168.1.194:8000/www/ad_settings.html?error_options');
}
?>