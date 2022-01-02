<?php
require_once("utils.php");
$db = get_db();

session_start();

if(!isset($_SESSION["cart_id"])) {
    $query = $db->prepare("SELECT MAX(id)+1 as id FROM cart;");
    $query->execute();
    $_SESSION["cart_id"] = $query->fetchAll(PDO::FETCH_ASSOC)[0]["id"];
    if(isset($_SESSION["user_id"])){
        $query = $db->prepare("UPDATE user SET cart_id = :id WHERE id = :uid;");
        $query->bindParam(":uid", $_SESSION["user_id"]);
        $query->bindParam(":id", $_SESSION["cart_id"]);
        $query->execute();
    }
}

$stmt = $db->prepare("SELECT MAX(quantity) AS q FROM (SELECT quantity FROM cart WHERE id = :id AND product_id=:pid UNION SELECT 0);");
$stmt->bindParam(":id", $_SESSION["cart_id"]);
$stmt->bindParam(":pid", $_GET["product_id"]);
$stmt->execute();
$old_quantity = intval($stmt->fetchAll(PDO::FETCH_ASSOC)[0]["q"]);

$query = null;
if($old_quantity == 0) {
    $query = $db->prepare("INSERT INTO cart VALUES(:id, :pid, :qty)");
} else {
    $query = $db->prepare("UPDATE cart SET quantity = :qty WHERE id = :id AND product_id = :pid;");
}
$query->bindParam(":id", $_SESSION["cart_id"]);
$query->bindParam(":pid", $_GET["product_id"]);
$query->bindValue(":qty", intval($_GET["quantity"]) + $old_quantity);
$query->execute();

$stmt = $db->prepare("DELETE FROM cart WHERE quantity <= 0");
$stmt->execute();

http_response_code(303);
header("Location: /cart.php?json");
