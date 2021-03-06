<?php
require_once("utils.php");

$db = get_db();
session_start();

function order_add_price($order) {
    $stripe = new Stripe("sk_test_wWygRumClv9lRAWpxQyLyzgD00oDfv5zAD");
    $stripe_data = $stripe->get_payment_details($order["payment_id"]);
    unset($order["payment_id"]);
    $order["total_amount"] = $stripe_data["amount"];
    return $order;
}

if(!isset($_SESSION["user_id"])) {
    header("Location: /login.php");
    die();
}

$cart_count = 0;
if(isset($_SESSION["cart_id"])) {
    $cart_count = load_cart_size($db, $_SESSION["cart_id"]);
}

$stmt = $db->prepare("SELECT `name`, `email` FROM `user` WHERE id = :id");
$stmt->bindParam(":id", $_SESSION["user_id"]);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($users) != 1) {
    unset($_SESSION["user_id"]);
    unset($_SESSION["cart_id"]);
    header("Location: /login.php");
    die();
}
$user = $users[0];

$stmt = $db->prepare("UPDATE `notification` SET `status` = 1 WHERE `user_id` = :uid");
$stmt->bindParam(":uid", $_SESSION["user_id"]);
$stmt->execute();
$stmt = $db->prepare("SELECT * FROM `notification` WHERE `user_id` = :uid");
$stmt->bindParam(":uid", $_SESSION["user_id"]);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT `order`.id, MIN(order_update.timestamp) AS `date`, `order`.payment_id FROM `order`, order_update WHERE `order`.user_id = :uid AND `order`.id = order_update.order_id GROUP BY order_update.order_id ORDER BY `date` DESC");
$stmt->bindParam(":uid", $_SESSION["user_id"]);
$stmt->execute();
$orders = array_map('order_add_price' ,$stmt->fetchAll(PDO::FETCH_ASSOC));

$page_title = "Area personale";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "user.php";
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
require_once("templates/main.php");

