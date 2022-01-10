<?php
require_once("utils.php");

session_start();
if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
	die("unauthorized request");
}
$db = get_db();
$insert_query = $db->prepare("insert into product (name, description, price, quantity) values (:name, :description, :price, :quantity)");
$insert_query->bindParam(":name",$_POST["name"]);
$insert_query->bindParam(":description",$_POST["description"]);
$insert_query->bindValue(":price",round($_POST["price"]*100));
$insert_query->bindParam(":quantity",$_POST["quantity"]);
$insert_query->execute();

error_log($db->lastInsertId());
update_product_image($db->lastInsertId());

header("Location: /");

