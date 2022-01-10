<?php
require_once("utils.php");

session_start();

if(!(isset($_SESSION["admin"]) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}

$order_id = $_GET["order_id"];

$page_title = "Modifica ordine";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "modify_order_t.php";
require_once("templates/main.php");
