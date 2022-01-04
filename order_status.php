<?php
require_once("utils.php");

session_start();

$db = get_db();

$order_id = $_GET["order_id"];

$cart_query = $db->prepare("select cart.id, product_id, quantity, name, price from cart, product, `order` where cart.id = `order`.cart_id and product_id = product.id and `order`.id = :order_id");
$cart_query->bindParam(":order_id",$order_id);
$cart_query->execute();
$cart = $cart_query->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Order status";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "order_status_t.php";
require_once("templates/main.php");
