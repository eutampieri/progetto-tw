<?php
$page_title = "Home";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "home.php";
$products = [
	[
				"image" => "",
        "title" => "Lorem Ipsum",
				"price" => 1299,
				"quantity" => 3,
    ]
];
require_once("templates/main.php");
