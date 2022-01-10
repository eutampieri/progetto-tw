<?php
require_once("utils.php");

session_start();
if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}
$db = get_db();
$delete_query = $db->prepare("update user set administrator=1 where email=:email");
$delete_query->bindParam(":email",$_POST["email"]);
$delete_query->execute();

header("Location: /");

