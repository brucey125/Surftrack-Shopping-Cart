<?php

require_once("settings.php");

$db_new_connect = mysql_connect($config['db_localhost'],$config['db_username'],$config['db_password']);
$db_select = mysql_select_db($config['db_name'],$db_new_connect);

if (!$db_new_connect || !$db_select) {
	die(mysql_error());
}

?>