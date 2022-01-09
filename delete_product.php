<?php
require_once("utils.php");

session_start();
if(!($_SESSION["admin"]===1)) {
	die("unauthorized request");
}
$db = get_db();
$delete_query = $db->prepare("delete from product whele id=:id");
$delete_query->bindParam(":id",$_POST["id"]);
$delete_query->execute();

