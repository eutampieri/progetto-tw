<?php
	require_once("utils");
	$db = get_db();
	$order_id = $_GET['order_id'];
	if !is_number($order_id) {
		die("order_id must be a number");
	}
	$order_query = $db->query("select * from order, express_courier where order.id = :order_id and express_courier.id = order.express_courier_id");
	$order_query->bindParam(":order_id", $order_id);
	$order_query->execute();
	$res['order'] = $order_query->fetch(PDO::FETCH_ASSOC);
	$updates_query = $db->query("select * from order_update where order_id = :order_id");
	$updates_query->bindParam(":order_id", $order_id);
	$updates_query->execute();
	$res['updates'] = $updates_query->fetchAll(PDO::FETCH_ASSOC);

	header("Content-Type: text/json");
	echo json_encode($res);
?>
