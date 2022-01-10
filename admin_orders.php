<?php
require_once("utils.php");

function fix_record($x) {
	global $stmt_for_status;
	$stmt_for_status->bindParam(":id", $x["id"]);
	$stmt_for_status->execute();
	$x["last_status"] = $stmt_for_status->fetch()["status"];
	return $x;
}

$db = get_db();
session_start();

$stmt_for_status = $db->prepare("SELECT status FROM order_update WHERE order_id = :id ORDER BY timestamp desc LIMIT 1");

if(!(isset($_SESSION["admin"]) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}

$stmt = $db->prepare("SELECT `order`.id, MIN(order_update.timestamp) AS `date` FROM `order`, order_update WHERE `order`.id = order_update.order_id GROUP BY order_update.order_id ORDER BY `date` DESC");
$stmt->execute();
$orders = array_map(fix_record ,$stmt->fetchAll(PDO::FETCH_ASSOC));

$page_title = "Lista ordini";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "admin_orders_t.php";
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
require_once("templates/main.php");

