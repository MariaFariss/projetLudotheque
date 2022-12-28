<?php

try {
		$server = "sql306.epizy.com";
		$user = "epiz_33272938";
		$password = "sFqAOaxSkXQhj";
		$database = "epiz_33272938_ludo";
		$access=new PDO("mysql:host=".$server.";dbname=".$database.";charset=utf8", $user, $password);
		$access->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

} catch (Exception $e) 
{
	$e->getMessage();
}
