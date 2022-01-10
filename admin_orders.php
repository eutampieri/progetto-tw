<?php
require_once("utils.php");

$db = get_db();
session_start();

if(!(isset($_SESSION["admin"]) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}

$stmt = $db->prepare("SELECT `order`.id, MIN(order_update.timestamp) AS `date`, `order`.payment_id FROM `order`, order_update WHERE `order`.id = order_update.order_id GROUP BY order_update.order_id ORDER BY `date` DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Lista ordini";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "admin_orders_t.php";
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
require_once("templates/main.php");

