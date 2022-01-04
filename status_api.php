<?php
	require_once("utils.php");
	$db = get_db();
	$order_id = $_GET['order_id'];
	if(!is_numeric($order_id)) {
		die("order_id must be a number");
	}
	$order_query = $db->prepare("select * from `order` left join express_courier on express_courier_id = `order`.express_courier_id where `order`.id=:order_id");
	$order_query->bindParam(":order_id", $order_id);
	$order_query->execute();
	$res['order'] = $order_query->fetch(PDO::FETCH_ASSOC);
	$updates_query = $db->prepare("select * from order_update where order_id = :order_id order by `timestamp` desc");
	$updates_query->bindParam(":order_id", $order_id);
	$updates_query->execute();
	$res['updates'] = $updates_query->fetchAll(PDO::FETCH_ASSOC);

	header("Content-Type: application/json");
	echo json_encode($res);
?>
