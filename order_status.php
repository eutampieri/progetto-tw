<?php
require_once("utils.php");

$db = get_db();

$cart_query = $db->prepare("select cart.id, product_id, quantity, name, price from cart, product where cart.id = :cart_id AND product_id = product.id");
$cart_query->bindParam(":cart_id",$_SESSION["cart_id"]);
$cart_query->execute();
$cart = $cart_query->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Order status";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "order_status_t.php";
$order_id = $_GET["order_id"];
require_once("templates/main.php");
