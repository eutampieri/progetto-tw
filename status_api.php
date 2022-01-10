<?php
	require_once("utils.php");
	session_start();
	$stripe = new Stripe("sk_test_wWygRumClv9lRAWpxQyLyzgD00oDfv5zAD");
	$db = get_db();
	$order_id = $_GET['order_id'];
	if(!is_numeric($order_id)) {
		die("order_id must be a number");
	}
	$order_query = null;
	if(!(isset($_SESSION) && $_SESSION["admin"]==1)) {
		$order_query = $db->prepare("select cart_id, payment_id, tracking_number, name as courier_name from `order` left join express_courier on express_courier_id = `order`.express_courier_id where `order`.id=:order_id and `order`.user_id = :user_id");
	} else {
		$order_query = $db->prepare("select cart_id, payment_id, tracking_number, name as courier_name from `order` left join express_courier on express_courier_id = `order`.express_courier_id where `order`.id=:order_id");
	}
	$order_query->bindParam(":order_id", $order_id);
	$order_query->bindParam(":user_id", $_SESSION["user_id"]);
	$order_query->execute();
	$res['order'] = $order_query->fetch(PDO::FETCH_ASSOC);
	if(count($res["order"]) == 0) {
		http_response_code(401);
		die();
	}
	$updates_query = $db->prepare("select timestamp, status , \"Magazzino\" as place from order_update where order_id = :order_id order by `timestamp`");
	$updates_query->bindParam(":order_id", $order_id);
	$updates_query->execute();
	$res['updates'] = $updates_query->fetchAll(PDO::FETCH_ASSOC);
	$cart_query = $db->prepare("select product_id, cart.quantity, name from cart, product where cart.id = :cart_id and product.id = product_id");
	$cart_query->bindParam(":cart_id", $res['order']["cart_id"]);
	$cart_query->execute();
	$res['cart'] = $cart_query->fetchAll(PDO::FETCH_ASSOC);

	$stripe_data = $stripe->get_payment_details($res['order']["payment_id"]);
	$res["payment_infos"] = $stripe_data["charges"]["data"][0]["payment_method_details"]["card"];
	unset($res['order']["payment_id"]);
	unset($res['order']["cart_id"]);
	unset($res['payment_infos']["fingerprint"]);
	unset($res['payment_infos']["exp_month"]);
	unset($res['payment_infos']["exp_year"]);
	$res["payment_infos"]["receipt"] = $stripe_data["charges"]["data"][0]["receipt_url"];
	$res["payment_infos"]["total_amount"] = $stripe_data["amount"];

	if($res["order"]["courier_name"] === "Poste Italiane") {
		$res['updates'] = array_merge($res['updates'], poste_tracking($res["order"]["tracking_number"]));
	}

	header("Content-Type: application/json");
	echo json_encode($res);
?>
