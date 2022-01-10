<?php
require_once("utils.php");

session_start();
if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}
$db = get_db();
$insert_query = $db->prepare("insert into order_update (`timestamp`, `status`, `order_id`) values (:timestamp, :status, :order_id)");
$insert_query->bindValue(":timestamp",time());
$insert_query->bindParam(":status",$_POST["status"]);
$insert_query->bindParam(":order_id",$_POST["order_id"]);
$insert_query->execute();

$user_id_query = $db->prepare("select user_id from `order` where id=:order_id");
$user_id_query->bindParam(":order_id",$_POST["order_id"]);
$user_id_query->execute();
$user_id = $user_id_query->fetch(PDO::FETCH_ASSOC)["user_id"];

send_notification($user_id,"L'ordine numero ".$_POST["order_id"]." ha un aggiornamento: ".$_POST["status"]);

header("Location: /modify_order.php?order_id=".$_POST["order_id"]);

