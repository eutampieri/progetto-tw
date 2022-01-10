<?php
require_once("utils.php");

session_start();
if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
	http_response_code(401);
	die("unauthorized request");
}
$db = get_db();
$delete_query = $db->prepare("insert into order_update (`timestamp`, `status`, `order_id`) values (:timestamp, :status, :order_id)");
$delete_query->bindParam(":timestamp",time());
$delete_query->bindParam(":status",$_POST["status"]);
$delete_query->bindParam(":order_id",$_POST["order_id"]);
$delete_query->execute();

header("Location: /modify_order.php?order_id=".$_POST["order_id"]);

