<?php
$page_title = "Home";
$head_template = "page_head.php";
$body_template = "page.php";
$page_content_template = "home.php";
$products = [
    [
        "title" => "Lorem Ipsum",
        "price" => 1299,
        "image" => "https://picsum.photos/200/300",
    ]
];
require_once("templates/main.php");