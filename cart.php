<?php
require_once("utils.php");

session_start();

$pdo = get_db();
$cart_query = $pdo->prepare("select cart.id, product_id, cart.quantity, product.quantity as max_qty, name, price from cart, product where cart.id = :cart_id AND product_id = product.id");
$cart_query->bindParam(":cart_id",$_SESSION["cart_id"]);
$cart_query->execute();
$cart = $cart_query->fetchAll(PDO::FETCH_ASSOC);
$delivery_price = calc_delivery_price($cart);

if(isset($_GET["json"])) {
    header("Content-Type: application/json");
    echo json_encode([
        "delivery_price" => $delivery_price,
        "items" => $cart,
    ]);
} else {
    $page_title = "Carrello";
    $head_template = "page_head.php";
    $body_template = "page.php";
    $page_content_template = "cart_t.php";
    require_once("templates/main.php");
}
