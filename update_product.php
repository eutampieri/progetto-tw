<?php
require_once("utils.php");

session_start();
if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}
$db = get_db();

$update_query = $db->prepare("update product set `name`=:name, description=:description, price=:price, quantity=:quantity where id=:id");
$update_query->bindParam(":name",$_POST["name"]);
$update_query->bindParam(":description",$_POST["description"]);
$update_query->bindValue(":price",round($_POST["price"]*100));
$update_query->bindParam(":quantity",$_POST["quantity"]);
$update_query->bindParam(":id",$_POST["id"]);
$update_query->execute();

header("Location: /product.php?id=".$_POST["id"]);

