<?php
require_once("utils.php");

session_start();

$db = get_db();
$query = $db->prepare("SELECT * FROM product WHERE id = :id;");
$query->bindParam(":id", $_GET["id"]);
$query->execute();

$products = $query->fetchAll(PDO::FETCH_ASSOC);

if(count($products) === 1) {
    $product = $products[0];
    $page_title = "Modifica ".$product["name"];
    $head_template = "page_head.php";
    $body_template = "page.php";
    $page_content_template = "edit_product_t.php";
} else {
    http_response_code(404);
}
require_once("templates/main.php");
