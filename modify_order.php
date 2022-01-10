<?php
require_once("utils.php");

session_start();

if(!(isset($_SESSION["admin"]) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}
$db = get_db();
$query = $db->prepare("select * from express_courier");
$query->execute();
$couriers = $query->fetchAll(PDO::FETCH_ASSOC);

$order_id = $_GET["order_id"];

$query = $db->prepare("SELECT user_id FROM `order` WHERE id = :id");
$stmt->bindParam(":id", $order_id);
$stmt->execute();
$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($res) != 1) {
	http_response_code(404);
	echo "Ordine non trovato";
	die();
}
$query = $db->prepare("SELECT * FROM user WHERE id = :id");
$stmt->bindParam(":id", $res[0]["user_id"]);
$stmt->execute();
$user = $stmt->fetch();

$page_title = "Modifica ordine";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "modify_order_t.php";
require_once("templates/main.php");
