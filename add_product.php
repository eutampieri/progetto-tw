<?php
require_once("utils.php");

session_start();

$db = get_db();
$product["name"] = "Nome prodotto";
$product["description"] = "Descrizione prodotto";
$product["price"] = 10;
$product["quantity"] = 1;
$product["id"] = null;
$page_title = "Aggiungi prodotto";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "edit_product_t.php";
require_once("templates/main.php");
