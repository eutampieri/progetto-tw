<?php
$page_title = "Home";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "cart_t.php";
$products = [
	[
		"image" => "",
        "title" => "Lorem Ipsum",
		"quantity" => 3,
        "price" => 1299,
        "image" => "https://picsum.photos/200/300",
        "id" => 0,
    ]
];
require_once("templates/main.php");
