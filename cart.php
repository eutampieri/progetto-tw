<?php
$pdo = new PDO("");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$cart_id = 1;
$cart_query = $pdo->query("select * from cart, product where cart.id = :cart_id AND product_id = product.id");
$cart_query->bindParam(":cart_id",$cart_id);
$cart_query->execute();
$cart = $cart_query->fetchAll(PDO::FETCH_ASSOC);
$delivery_price = 500;
$total_price = $delivery_price;

$page_title = "Home";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "cart_t.php";
$products = [
	[
		"image" => "",
        "title" => "Lorem Ipsum",
		"quantity" => 3,
        "price" => 1299,
        "image" => "https://picsum.photos/200/300",
        "id" => 0,
    ]
];
require_once("templates/main.php");
