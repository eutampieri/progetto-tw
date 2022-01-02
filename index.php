<?php
require_once("utils.php");

$db = get_db();
$query = $db->prepare("SELECT * FROM product;");
$query->execute();

$page_title = "Home";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "home.php";
$products = $query->fetchAll(PDO::FETCH_ASSOC);
require_once("templates/main.php");
