<?php
require_once("utils.php");

$db = get_db();
session_start();

if(!isset($_SESSION["user_id"])) {
    die();
    // TODO redirect to login page
}

$stmt = $db->prepare("SELECT `name`, `email` FROM `user` WHERE id = :id");
$stmt->bindParam(":id", $_SESSION["user_id"]);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($users) != 1) {
    die();
    // TODO Clean session since it's invalid and redirect
}
$user = $users[0];

$stmt = $db->prepare("SELECT `order`.id, MIN(order_update.timestamp) AS `date` FROM `order`, order_update WHERE `order`.user_id = :uid AND `order`.id = order_update.order_id GROUP BY order_update.order_id ORDER BY `date` DESC");
$stmt->bindParam(":uid", $_SESSION["user_id"]);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Area personale";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "user.php";
$products = $query->fetchAll(PDO::FETCH_ASSOC);
require_once("templates/main.php");

