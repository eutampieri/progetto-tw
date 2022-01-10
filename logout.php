<?php
session_start();
unset($_SESSION["user_id"]);
unset($_SESSION["admin"]);
unset($_SESSION["cart_id"]);
header("Location: /");

