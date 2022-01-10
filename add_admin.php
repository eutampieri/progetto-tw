<?php
require_once("utils.php");

$db = get_db();
session_start();

if(!(isset($_SESSION["admin"]) && $_SESSION["admin"]==1)) {
	header("Location: /login.php");
	die();
}

$page_title = "Eleva ad amministratore";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "add_admin_t.php";
require_once("templates/main.php");

