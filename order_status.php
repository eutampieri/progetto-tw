<?php
require_once("utils.php");

session_start();

$order_id = $_GET["order_id"];

$page_title = "Order status";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "order_status_t.php";
require_once("templates/main.php");
