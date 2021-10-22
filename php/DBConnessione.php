<?php
	require_once "./DBInfo.php";
	
	global $hostname;
    global $DBusername;
    global $DBpassword;
    			
    $mysqli = new mysqli($hostname, $DBusername, $DBpassword);
		
	if ($mysqli->connect_error) 
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);

	$mysqli->select_db($database) or
		die ('Impossibile accedere al database!' . $mysqli->error);
?>