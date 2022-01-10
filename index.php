<?php
require_once("utils.php");

session_start();

$db = get_db();
$query = $db->prepare("SELECT * FROM product WHERE deleted = 0;");
$query->execute();

$cart_count = 0;
if(isset($_SESSION["cart_id"])) {
    $cart_count = load_cart_size($db, $_SESSION["cart_id"]);
}

$page_title = "Home";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "home.php";
$products = $query->fetchAll(PDO::FETCH_ASSOC);
require_once("templates/main.php");
