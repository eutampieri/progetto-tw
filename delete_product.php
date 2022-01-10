<?php
require_once("utils.php");

session_start();
if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}
$db = get_db();
$delete_query = $db->prepare("delete from product where id=:id");
$delete_query->bindParam(":id",$_POST["id"]);
$delete_query->execute();

header("Location: /");

