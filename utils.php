<?php
function price_to_string($eurocents, $separator = ',') {
    $euros = floatval($eurocents)/100;
    return str_replace('.', $separator, number_format($euros, 2)).' €';
}

function get_db() {
    $database = new PDO("sqlite:db.sqlite");
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $database;
}
