<?php
require_once("utils.php");

$pdo = get_db();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$cart_query = $pdo->query("select * from cart, product where cart.id = :cart_id AND product_id = product.id");
$cart_query->bindParam(":cart_id",$_SESSION["cart_id"]);
$cart_query->execute();
$cart = $cart_query->fetchAll(PDO::FETCH_ASSOC);
$delivery_price = 500;

if(isset($_GET["json"])) {
    header("Content-Type: application/json");
    echo json_encode([
        "delivery_price" => $delivery_price,
        "items" => $cart,
    ]);
} else {
    $page_title = "Home";
    $head_template = "page_head.php";
    $body_template = "page.php";
    $page_content_template = "cart_t.php";
    require_once("templates/main.php");
}
