<?php
require_once("utils.php");

session_start();
$stripe = new Stripe("sk_test_wWygRumClv9lRAWpxQyLyzgD00oDfv5zAD");

if(isset($_REQUEST["create_checkout"]) && isset($_SESSION["cart_id"])){
    if(!isset($_SESSION["user_id"])) {
        $_SESSION["payment_pending"] = true;
        header("Location: /login.php");
        die();
    }
    $pdo = get_db();
    $cart_query = $pdo->prepare("select cart.id, product_id, quantity, name, price from cart, product where cart.id = :cart_id AND product_id = product.id");
    $cart_query->bindParam(":cart_id",$_SESSION["cart_id"]);
    $cart_query->execute();
    $cart = $cart_query->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT email FROM user WHERE id = :id");
    $stmt->bindParam(":id", $_SESSION["user_id"]);
    $stmt->execute();
    $email = $db->fetchAll(PDO::FETCH_ASSOC)[0]["email"];

    $delivery_price = calc_delivery_price($cart);
    array_push($cart, ["name" => "Shipping cost", "quantity" => 1, "price" => $delivery_price]);
    $stripe_session = $stripe->create_session($cart, "https://example.com", "https://example.com", $email);
    $_SESSION["payment_intent"] = $stripe_session["payment_intent"];
    http_response_code(303);
    header("Location: ".$stripe_session["url"]);
} else if(isset($_SESSION["payment_intent"])) {
    if($stripe->get_payment_status($_SESSION["payment_intent"])) {
        $db = get_db();
        $stripe->capture_payment($_SESSION["payment_intent"]);

        // Disassociate user from cart (DB)
        $stmt = $db->prepare("UPDATE user SET cart_id = NULL WHERE id = :id");
        $stmt->bindParam(":id", $_SESSION["user_id"]);
        $stmt->execute();
        // Create order
        $stmt = $db->prepare("INSERT INTO `order`(cart_id, `user_id`, payment_id) VALUES(:cart_id, :user_id, :payment_id)");
        $stmt->bindParam(":cart_id", $_SESSION["cart_id"]);
        $stmt->bindParam(":user_id", $_SESSION["user_id"]);
        $stmt->bindParam(":payment_id", $_SESSION["payment_intent"]);
        $stmt->execute();

        // Add order inserted event
        $order_id = intval($db->lastInsertId());
        $stmt = $db->prepare("INSERT INTO order_update VALUES(:ts, \"Ordine inserito\", :order_id)");
        $stmt->bindValue(":ts", time());
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();

        // TODO Add payment received event (from Stripe)

        // Unset session variables
        unset($_SESSION["payment_intent"]);
        unset($_SESSION["cart_id"]);

        $show_payment_successful_message = true;
        $page_title = "Conferma ordine";
        $head_template = "page_head.php";
        $body_template = "page.php";
        $page_content_template = "order_status_t.php";
        require_once("templates/main.php");

    } else {
        $_SESSION["payment_failed"] = true;
        http_response_code(303);
        header("Location: /cart.php");
    }
} else if(isset($_GET["cancel"])) {
    $_SESSION["payment_failed"] = true;
    http_response_code(303);
    header("Location: /cart.php");
} else {
    http_response_code(303);
    header("Location: /");
}
