<?php
require_once("utils.php");

function user_create(string $name, string $email, string $password) {
	$db = get_db();
	$query = $db->prepare("insert into user (name, email, password) values (:name, :email, :password)");
	$query->bindParam(":name", $name);
	$query->bindParam(":email", $email);
	$query->bindValue(":password", password_hash($password,PASSWORD_DEFAULT));
	try {
		$query->execute();
	} catch (PDOException $e) {
		error_log("User creation failed: " . $e->getMessage());
		return false;
	}
	send_notification(intval($db->lastInsertId()), "Ciao, $name! Grazie per esserti iscritto al nostro sito!",1);
	return true;
}

function user_login(string $email, string $password) {
	$db = get_db();
	$query = $db->prepare("select password, id, cart_id, administrator from user where email = :email");
	$query->bindParam(":email", $email);
	$query->execute();
	$query = $query->fetch(PDO::FETCH_ASSOC);
	if(!is_bool($query) && password_verify($password,$query["password"])) {
		$_SESSION["user_id"]=$query["id"];
		$_SESSION["admin"]=$query["administrator"];
		if(!isset($_SESSION["cart_id"])){
			$setcart = $db->prepare("UPDATE cart SET id = :user_cart_id WHERE id = :session_cart_id");
			$setcart->bindParam(":user_cart_id", $query["cart_id"]);
			$setcart->bindParam(":session_cart_id", $_SESSION["cart_id"]);
			$_SESSION["cart_id"]=$query["cart_id"];
		}
		return true;
	}
	return false;
}

session_start();
if ($_POST["action"]==="login") {
	if(user_login($_POST["email"],$_POST["password"])) {
		if(isset($_SESSION["payment_pending"]) && $_SESSION["payment_pending"] === true) {
			unset($_SESSION["payment_pending"]);
			header("Location: /pay.php?create_checkout");
		} else {
			header("Location: /me.php");
		}
	} else {
		header("Location: /login.php?login_error=true");
	}
} else if ($_POST["action"]==="signup") {
	if(user_create($_POST["name"],$_POST["email"],$_POST["password"])) {
		user_login($_POST["email"],$_POST["password"]);
		if(isset($_SESSION["payment_pending"]) && $_SESSION["payment_pending"] === true) {
			unset($_SESSION["payment_pending"]);
			header("Location: /pay.php?create_checkout");
		} else {
			header("Location: /");
		}
	} else {
		header("Location: /signup.php");
	}
}
