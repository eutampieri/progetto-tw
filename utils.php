<?php
function price_to_string($eurocents, $separator = ',') {
    $euros = floatval($eurocents)/100;
    return number_format($euros, 2, $separator, '').' €';
}

function get_db() {
    $database = new PDO("sqlite:db.sqlite");
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $database;
}

function http_request($url, $body, $headers = [], $method="GET") {
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

class Stripe
{
    private $secret;
    function __construct($secret) {
        $this->secret = $secret;
    }
    function create_session($bill, $callback_ok, $callback_cancel, $email) {
        $bill_rows = [];
        foreach($bill as $i => $x) {
            error_log(json_encode([$i, $x]));
            array_push($bill_rows, "line_items[$i][price_data][currency]=eur&&line_items[$i][price_data][product_data][name]=".$x["name"]."&line_items[$i][price_data][unit_amount]=".$x["price"]."&line_items[$i][quantity]=".$x["quantity"]);
        }
        $query = "payment_method_types[]=card&mode=payment&success_url=".urlencode($callback_ok)."&cancel_url=".urlencode($callback_cancel)."&payment_intent_data[capture_method]=manual&customer_email=".urlencode($email)."&".implode("&", $bill_rows);
        error_log($query);
        $resp = generic_request("https://api.stripe.com/v1/checkout/sessions", $query, ["Authorization: Basic ".base64_encode($this->secret.':')], "POST");
        return [
            "url"=>$resp["url"],
            "payment_intent" => $resp["payment_intent"]
        ];
    }
    function get_payment_status($intent) {
        $resp = generic_request("https://api.stripe.com/v1/payment_intents/$intent", "", ["Authorization: Basic ".base64_encode($this->secret.':')]);
        return $resp["amount"] == $resp["amount_capturable"] && $resp["amount_received"] == 0;
    }
    function capture_payment($intent) {
        generic_request("https://api.stripe.com/v1/payment_intents/$intent/capture", "", ["Authorization: Basic ".base64_encode($this->secret.':')], "POST");
    }
}

function calc_delivery_price($cart) {
    return 500;
}
