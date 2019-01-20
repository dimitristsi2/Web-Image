<?php

$dir=$_POST['si'];
$file1="$dir/android-debug.apk";
$log = "$dir/build_log.txt";
$counter=0;
$counter1=0;

if(file_exists($file1))
	{
$counter=1;
    }
if(file_exists($log)){
	$counter1=1;
}
//an uparxei to log kai oxi to apk tote upirkse provlima me tin paragwgh tou apk
if($counter1==1 && $counter==0){
echo "2";
exit;
}
//an yparxoun kai ta 2 tote pige teleia h diadikasia
if($counter==1 && $counter1==1){
	echo "1";
	exit;
	}
//an den uparxei kanena tote h diadikasia akoma ginetai
if($counter==0 && $counter1==0){
	echo "3";
	exit;
}

?>