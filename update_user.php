<?php
require_once("utils.php");
session_start();

$db = get_db();

if(!isset($_SESSION["user_id"])) {
    header("Location: /");
    die();
}

switch ($_POST["action"]) {
    case 'user_details':
        $stmt = $db->prepare("UPDATE user SET `name` = :name, email = :email WHERE id = :id");
        $stmt->bindParam(":name", $_POST["name"]);
        $stmt->bindParam(":email", $_POST["email"]);
        $stmt->bindParam(":id", $_SESSION["user_id"]);
        $stmt->execute();
				break;
		case 'password':
        $query = $db->prepare("select password from user where id = :id");
				$query->bindParam(":id", $_SESSION["user_id"]);
				$query->execute();
				$query = $query->fetch(PDO::FETCH_ASSOC);
				if(!is_bool($query) && password_verify($_POST["oldpassword"],$query["password"])) {
					$query = $db->prepare("update user set password = :password where id = :id");
					$query->bindParam(":id", $_SESSION["user_id"]);
					$query->bindParam(":password", password_hash($_POST["newpassword"],PASSWORD_DEFAULT));
					$query->execute();
				}
				break;
}
http_response_code(303);
header("Location: /me.php");
