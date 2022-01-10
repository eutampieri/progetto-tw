<?php
require_once("utils.php");

session_start();

$db = get_db();
$query = $db->prepare("SELECT * FROM product WHERE id = :id AND deleted = 0;");
$query->bindParam(":id", $_GET["id"]);
$query->execute();

$products = $query->fetchAll(PDO::FETCH_ASSOC);

$cart_count = 0;
if(isset($_SESSION["cart_id"])) {
    $cart_count = load_cart_size($db, $_SESSION["cart_id"]);
}

if(count($products) == 1) {
	$product = $products[0];
    $page_title = $product["name"];
    $head_template = "page_head.php";
    $body_template = "page.php";
    $page_content_template = "product_t.php";
} else {
    http_response_code(404);
}
require_once("templates/main.php");
