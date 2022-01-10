<?php
require_once("utils.php");

session_start();
if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
	die("unauthorized request");
}
$db = get_db();
$insert_query = $db->prepare("insert into product (name, description, price) values (:name, :description, :price)");
$insert_query->bindParam(":name",$_POST["name"]);
$insert_query->bindParam(":description",$_POST["description"]);
$insert_query->bindValue(":price",round($_POST["price"]*100));
$insert_query->execute();

header("Location: /");

