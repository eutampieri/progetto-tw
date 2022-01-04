<?php
require_once("utils.php");

function user_create(string $name, string $email, string $password) {
	$db = get_db();
	$query = $db->query("insert into user values (null, :name, :email, :password, null)");
	$query->bindParam(":name", $name);
	$query->bindParam(":email", $email);
	$query->bindValue(":password", password_hash($password));
	try {
		$query->execute();
	} catch (PDOException $e) {
		error_log("User creation failed: " . $e->getMessage());
		return false;
	}
	return true;
}

function user_login(string $email, string $password) {
	$db = get_db();
	$query = $db->query("select password, id, cart_id from user where email = :email");
	$query->bindParams(":email", $email);
	$query->execute();
	$query->fetch(PDO::FETCH_ASSOC);
	if(password_verify($query["password"],password)) {
		$_SESSION["user_id"]=$query["id"];
		if(!is_null($_SESSION["cart_id"])){
			$setcart = $db->query("UPDATE cart SET id = :user_cart_id WHERE id = :session_cart_id");
			$setcart->bindParam(":user_cart_id", $query["cart_id"]);
			$setcart->bindParam(":session_cart_id", $_SESSION["cart_id"])
			$_SESSION["cart_id"]=null;
		}
		return true;
	}
	return false;
}

// TODO: error/success messages
if ($_POST["action"]==="login") {
	if(user_login($_POST["email"],$_POST["password"])) {
		if($_SESSION["payment_pending"] === true) {
			header("Location: /pay.php?create_checkout");
		} else {
			header("Location: /home.php");
		}
	} else {
		header("Location: /login.php");
	}
} else if ($_POST["action"]==="signup")
	if(user_login($_POST["name"],$_POST["email"],$_POST["password"])) {
		header("Location: /login.php");
	} else {
		header("Location: /signup.php");
	}
}
