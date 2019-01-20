<?php
//elegxos gia uparksi tou www file wste na disable buttons kai na ksikinisw diadikasia paragwghs apk
	$id=$_POST['si'];
	$dir = "$id/www";
	if(file_exists($dir)){
		echo "1";
	}
	else{
	echo "2";
	}
?>