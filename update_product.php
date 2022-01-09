<?php
require_once("utils.php");

session_start();
if(!($_SESSION["admin"]===1)) {
	die("unauthorized request");
}
$db = get_db();
$update_query = $db->prepare("update product set `name`=:name, `description=:description, price=:price where id=:id");
$update_query->bindParam(":name",$product["name"]);
$update_query->bindParam(":description",$product["description"]);
$update_query->bindParam(":price",$product["price"]);
$update_query->bindParam(":id",$product["id"]);
$update_query->execute();

