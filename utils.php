<?php
function price_to_string($eurocents, $separator = ',') {
    $euros = floatval($eurocents)/100;
    return number_format($euros, 2, $separator, '').' €';
}

function get_db() {
    $config = json_decode(file_get_contents("db_conf.json"), true);
    $database = new PDO("mysql:host=".$config["host".";dbname=".$config["name"], $config["username"], $config["password"]);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $database;
}

function http_request($url, $body, $headers = [], $method="GET") {
    if(function_exists('curl_version')) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        if($method === "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close ($ch);
        return $server_output;
    } else { // Fallback
        $opts = array('http' =>
            array(
                'method'  => $method,
                'header'  => array_merge(['Content-Type: application/x-www-form-urlencoded'], $headers),
                'content' => $body
            )
        );
        $context  = stream_context_create($opts);

        return file_get_contents($url, false, $context);
    }
}

class Stripe
{
    private $secret;
    function __construct($secret) {
        $this->secret = $secret;
    }
    function create_session($bill, $callback_ok, $callback_cancel, $email) {
        $bill_rows = [];
        foreach($bill as $i => $x) {
            array_push($bill_rows, "line_items[$i][price_data][currency]=eur&&line_items[$i][price_data][product_data][name]=".$x["name"]."&line_items[$i][price_data][unit_amount]=".$x["price"]."&line_items[$i][quantity]=".$x["quantity"]);
        }
        $query = "payment_method_types[]=card&mode=payment&success_url=".urlencode($callback_ok)."&cancel_url=".urlencode($callback_cancel)."&payment_intent_data[capture_method]=manual&customer_email=".urlencode($email)."&".implode("&", $bill_rows);
        $resp = json_decode(http_request("https://api.stripe.com/v1/checkout/sessions", $query, ["Authorization: Basic ".base64_encode($this->secret.':')], "POST"), true);
        return [
            "url"=>$resp["url"],
            "payment_intent" => $resp["payment_intent"]
        ];
    }
    function get_payment_status($intent) {
        $resp = json_decode(http_request("https://api.stripe.com/v1/payment_intents/$intent", "", ["Authorization: Basic ".base64_encode($this->secret.':')]), true);
        return $resp["amount"] == $resp["amount_capturable"] && $resp["amount_received"] == 0;
    }
    function capture_payment($intent) {
        http_request("https://api.stripe.com/v1/payment_intents/$intent/capture", "", ["Authorization: Basic ".base64_encode($this->secret.':')], "POST");
    }
}

function calc_delivery_price($cart) {
    return 500;
}

function calc_cart_size($cart) {
    $size = 0;
    foreach($cart as $item) {
        $size += intval($item["quantity"]);
    }
    return $size;
}

function load_cart_size($db, $cart_id) {
    $stmt = $db->prepare("SELECT SUM(quantity) AS n FROM cart WHERE id = :id");
    $stmt->bindParam(":id", $cart_id);
    $stmt->execute();
    return intval($stmt->fetchAll(PDO::FETCH_ASSOC)[0]["n"]);
}
