<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';

function price_to_string($eurocents, $separator = ',') {
    $euros = floatval($eurocents)/100;
    return number_format($euros, 2, $separator, '').' €';
}

function get_db() {
    $config = json_decode(file_get_contents("db_conf.json"), true);
    $database = new PDO("mysql:host=".$config["host"].";dbname=".$config["name"], $config["username"], $config["password"]);
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
    function get_payment_details($intent) {
        $resp = json_decode(http_request("https://api.stripe.com/v1/payment_intents/$intent", "", ["Authorization: Basic ".base64_encode($this->secret.':')]), true);
        return $resp;
    }
    function get_payment_status($intent) {
        $resp = $this->get_payment_details($intent);
        return $resp["amount"] == $resp["amount_capturable"] && $resp["amount_received"] == 0;
    }
    function capture_payment($intent) {
        http_request("https://api.stripe.com/v1/payment_intents/$intent/capture", "", ["Authorization: Basic ".base64_encode($this->secret.':')], "POST");
    }
}

function calc_delivery_price($cart) {
    return count($cart) > 0 ? 500 : 0;
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

function send_notification($user, $message, $status = 0, $send = true) {
    $db = get_db();
    $query = $db->prepare("INSERT INTO `notification` (`user_id`, `message`, `status`, `date`) VALUES(:uid, :msg, :status, :now)");
    $query->bindParam(":uid", $user);
    $query->bindParam(":msg", $message);
    $query->bindParam(":status", $status);
    $query->bindValue(":now", time());
    $query->execute();
    $query = $db->prepare("SELECT `name`, `email` FROM `user` WHERE `id` = :id");
    $query->bindParam(":id", $user);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $mail = new PHPMailer(true);
    $conf = json_decode(file_get_contents("mail_conf.json"), true);
    if($send) {
        try {
            $mail->isSMTP();
            $mail->Host = $conf["host"];
            $mail->SMTPAuth = true;
            $mail->Username = $conf["user"];
            $mail->Password = $conf["password"];
            $mail->SMTPSecure = 'ssl';
            $mail->Port = $conf["port"];
            $mail->setFrom('shop@chelli.tampieri.me', 'Chelli&Tampieri Shop');
            $mail->addAddress($user["email"], $user["name"]);
            //Content
            $mail->Subject = 'Un aggiornamento dal nostro sito';
            $mail->Body    = $message;
            $mail->send();
        } catch (Exception $e) {
            error_log("Failed to send email ".$e);
        }
    }
    return intval($db->lastInsertId());
}

function poste_tracking($tracking_num) {
    $res = json_decode(http_request("https://www.poste.it/online/dovequando/DQ-REST/ricercasemplice", json_encode([
        "tipoRichiedente" => "WEB",
        "codiceSpedizione" => $tracking_num,
        "periodoRicerca" => 1,
    ]), ["Content-Type: application/json"], "POST"), true);
    if(!isset($res["listaMovimenti"])) {
        return [];
    }
    return array_map(fn($x) => ["timestamp" => $x["dataOra"]/1000, "status" => $x["statoLavorazione"], "place" => $x["luogo"]], $res["listaMovimenti"]);
}

function update_product_image($id) {
    if(isset($_FILES["image"]) && isset($_FILES["image"]["tmp_name"]) && $_FILES["image"]["tmp_name"] !== "") {
        $db = get_db();
        $query = $db->prepare("UPDATE product SET `image` = :i WHERE id = :id");
        $query->bindParam(":id", $id);
        $query->bindValue(":i", file_get_contents($_FILES["image"]["tmp_name"]));
        $query->execute();
        unlink($_FILES["image"]["tmp_name"]);
    }
}
