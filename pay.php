<?php
require_once("utils.php");

session_start();
$stripe = new Stripe("sk_test_wWygRumClv9lRAWpxQyLyzgD00oDfv5zAD");

if(isset($_REQUEST["create_checkout"]) && isset($_SESSION["cart_id"])){
    if(!isset($_SESSION["user_id"])) {
        $_SESSION["payment_pending"] = true;
        // TODO redirect to login
        die();
    }
    $pdo = get_db();
    $cart_query = $pdo->query("select cart.id, product_id, quantity, name, price from cart, product where cart.id = :cart_id AND product_id = product.id");
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
        $stripe->capture_payment($_SESSION["payment_intent"]);
        echo "Pagamento ok";
    } else {
        echo "Pagamento fallito";
    }
} else {
    http_response_code(303);
    header("Location: /");
}
