<?php
require_once("utils.php");

session_start();

if(!(isset($_SESSION["admin"]) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}
$db = get_db();
$query = $db->prepare("update `order` set express_courier_id=:express_courier_id, tracking_number=:tracking_number where id=:order_id");
$query->bindParam(":express_courier_id", $_POST["courier"]);
$query->bindParam(":tracking_number", $_POST["tracking_number"]);
$query->bindParam(":order_id", $_POST["order_id"]);
$query->execute();

header("Location: /modify_order.php?order_id=".$_POST["order_id"]);

